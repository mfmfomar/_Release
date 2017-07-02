<?php

class Model{
	var $conn;
	
	public function openDb($dbhost, $dbuser, $dbpass, $dbname, $conn) {
		//echo "Se creo la conexion ";
		$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error connecting to mysql');
		mysql_select_db($dbname) or die('Error select db');
		mysql_query("SET NAMES utf8");
		return $conn;
	}

	public function closeDb($conn) {
		mysql_close($conn);
	}

	public function query($query) {
		if ($result = mysql_query($query) or die("Error de Query: </br >" . mysql_error()."<br/>".$query)) {
		} else {
			$result = FALSE;
		}
		return $result;
	}
	
	function __construct(){
			$this->openDb(dbhost, dbuser, dbpass, dbname, $conn);
		
	}
	
	//getGenerico
	function select($tabla, $where = FALSE, $order = FALSE){
		$query="SELECT *
		FROM $tabla
		$where
		$order";
	
		return $this -> query($query);
	}

	
	//insertGenerico con indedices asiciativos,
	// no funciona con fecha,
	// para fechas hay  que hacer update
	function insertar($tabla, $datos){

		$columnas = implode(",", array_keys($datos));
		$valores = implode(",", $datos);
		$query="INSERT INTO $tabla
		($columnas)
		VALUES
		(".$valores.")";
		return $this -> query($query);
	}
	
	
	function insertarRelacionArray($tabla,$tablaRelacion, $datos){

		foreach( $datos as $row){
			$query="INSERT INTO $tabla
			($tablaRelacion[0],$tablaRelacion[2])
			VALUES
			($tablaRelacion[1],$row)";
			
			echo '<br>'.$query;
			exit;
			$this -> query($query);
		}
		
	}
	
	function updateRelacionArray($tabla,$tablaRelacion, $datos){
		foreach( $datos as $row ){
			$query="UPDATE $tabla 
			SET($tablaRelacion[0] = $tablaRelacion[1], $tablaRelacion[2] = $row)";
			echo '<br>'.$query;
			exit;
			$this -> query($query);
		}
		
	}
	
	
	

	
	//deleteGenerico
	function delete($tabla, $id){
		$query="DELETE FROM $tabla
		WHERE id = $id";
		//echo "$query";
		return $this -> query($query);
	}
	
	function deleteCondition($tabla, $Where)
	{
		$query="DELETE FROM $tabla
		WHERE $Where";
		return $this -> query($query);
	}
	
	
	//update generico
	function update($tabla,$datos,$id,$tag=FALSE)
	{
		if($tag == FALSE)
			$tag = "id";
		$columnas =  array_keys($datos);
		$SET='SET ';
		$i=0;
		foreach( $datos as $key  => $value){
			if(next($datos)){
				$SET .="`$key` = $value ,";	
			}else{
				$SET .="`$key` = $value ";	
			}
			
		}
		$query = "UPDATE $tabla  $SET WHERE $tag =$id;";
		return $this -> query($query);
	}
	
	function getPostVar($metodo ="")
	{
		if( $metodo == $_POST or $metodo == $_GET or $metodo == $_REQUEST){
				$numero = count($metodo);
				$tags = array_keys($metodo);
				$valores = array_values($metodo);// obtiene los valores de las varibles	
				$valida = new Formulario();
				// crea las variables y les asigna el valor
					for($i=0;$i<$numero;$i++){
					 $valores[$i] =	$valida -> valida_contenido($valores[$i]);
					$var[$tags[$i]]= "'".$valores[$i]."'";
					}
				return $var;	
		}else{
			//tiene que regresar todas
			return null;
		}
	}
	
	function result_to_array($result) {
		 $rows =mysql_num_rows($result);
		 
		if ($rows == 1){
			return(mysql_fetch_array($result,MYSQL_ASSOC));
		}elseif($rows > 1){
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$array[] = $row;
			}	
			return $array;
		}else{
			return null;
		}
		
	}
}
?>