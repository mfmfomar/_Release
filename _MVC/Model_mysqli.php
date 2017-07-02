<?php

class Model{
	function __construct() {
		$this->mysqli;
		$this->last_Id;
			//$this->openDb(dbhost, dbuser, dbpass, dbname);
			//$this->closeDb();
	}

	public function openDb($dbhost, $dbuser, $dbpass, $dbname,$desarrollador=false) {
		//echo "Se creo la conexion ";
		$this->mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
		$this->mysqli->set_charset("utf8");
		//$this->mysqli->query("SET NAMES 'utf8'");
			/*
			 * Esta es la forma OO "oficial" de hacerlo,
			 * AUNQUE $connect_error estaba averiado hasta PHP 5.2.9 y 5.3.0.
			 */
			if ($this->mysqli->connect_error) {
				if($desarrollador){
			    	//die('</br> Error de Conexion-1 </br>(' . $this->mysqli->connect_error. ') </br>('.$dbhost.','. $dbuser.','. $dbpass.','. $dbname.')');
			    	$text= 'Error de Conexion-1 (' . $this->mysqli->connect_error. ') </br>('.$dbhost.','. $dbuser.','. $dbpass.','. $dbname.')'; 
					return $text;

				}else{
			    	die('Error de Conexion-2 (' . $this->mysqli->connect_error. ') (T-T)'
			       . $this->mysqli->connect_error);					
				}

			}

			/*
			 * Use esto en lugar de $connect_error si necesita asegurarse
			 * de la compatibilidad con versiones de PHP anteriores a 5.2.9 y 5.3.0.
			 */
			if (mysqli_connect_error()) {
			    die('Error de Conexion-2 (' . mysqli_connect_error() . ') '
			            . mysqli_connect_error());
			}

			//echo 'Éxito... ' . $mysqli->host_info . "\n";
		/* cambiar el conjunto de caracteres a utf8 */
		if (!$this->mysqli->set_charset("utf8")) {
		    printf("Error cargando el conjunto de caracteres utf8: %s\n", $mysqli->error);
		} else {
		    //printf("Conjunto de caracteres actual: %s\n", $mysqli->character_set_name());
		}
		
	}

	function closeDb() {
		$this ->mysqli->close();
	}

    function query($query) {
		$this->openDb(dbhost, dbuser, dbpass, dbname);
    	$result = mysqli_query($this->mysqli, $query) or die("Lo siento hay un error :'(  <br><br><br>".$query." -><br>".mysqli_error($this->mysqli)); 
    	$this->last_Id = $this ->mysqli -> insert_id;
    	$this->closeDb();
    	return $result;
   
    }
    function test(){
		$this->var = $this->openDb(dbhost, dbuser, dbpass, dbname,true);
		$this->closeDb();
		$a = $this->var;
		if (strpos($a, 'Error') !== false) {
		    return $this->var;
		}
		
    	return "Conexión con la base de datos realizada con exito";

    }


	//SelectGenerico
	function select($campos,$tabla, $where = FALSE, $order = FALSE){
		$query="SELECT $campos FROM $tabla $where $order";	
		return $this -> query($query);
	}

	//insertGenerico
	// con indedices asiciativos
	function insertar($tabla, $datos){
		/* antes ('$valores')";  NO MODIFICAR@*/
			$columnas = implode("`,`", array_keys($datos));
			$valores = implode("','", $datos);
			$query="INSERT INTO $tabla
			(`$columnas`)
			VALUES
			('$valores')";
			return $this -> query($query);	
	}

	//update generico
	function update($tabla,$datos,$Where){
		//printArray($datos);
		if($Where !=""){
			$SET='SET ';
			foreach( $datos as $key  => $value){
				if(next($datos) !=null){
					$SET .="`$key` = '$value',";	
				}else{
					$SET .="`$key` = '$value' ";	
				}		
			}
			$query = "UPDATE $tabla  $SET $Where";
			return $this -> query($query);			
		}else{
			return FALSE;			

		}

	}

	function delete($tabla,$Where){
		$query="DELETE FROM $tabla $Where";
		return $this -> query($query);
	}

	function result_to_array($result) {
		if (version_compare(phpversion(), '5.5.10', '<')) {
		   while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
				$array[] = $row;
			}
		}else{
			if($result ->num_rows >0){
				 while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					$array[] = $row;
				}	
			}else{
				return null;
			}
		}
		return $array;
	}

}
?>