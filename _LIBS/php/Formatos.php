<?php
/*
 * Ultima modificacion 3-marzo 2015

 	function getSecurePostVar($var)
	{
	    $tmp =  stripslashes($var);
	    $temp2 = htmlspecialchars($tmp, ENT_QUOTES | ENT_IGNORE, "UTF-8");
	    $secure  = mysql_real_escape_string( $temp2 );
	    return  $secure;
	}
 */
class Formatos {

	function __construct() {
	}

	function tablaArray($identificador,$vector){
		//array{$identificador}
		//echo array_key_exists('first', $search_array);
		$result;
		$n=0;
		foreach ($vector as $key => $value) {
			if (strpos($key,'array{'.$identificador.'}') !== false) {
				$result[$n]=$value;
				$n++;
			}
		}
		//echo json_encode($result);
		return $result;
	}
	function tablaArrayUNSET($identificador,$vector){
		//array{$identificador}
		//echo array_key_exists('first', $search_array);
		$result;
		$n=0;
		foreach ($vector as $key => $value) {
			if (strpos($key,'array{'.$identificador.'}') !== false) {
				unset($vector[$key]);
			}
		}
		return $vector;
	}

	function verificaEncriptacion($try,$password){
		$salt = '~!@#$%^&*()_+=-0987654321`';
		$try = sha1(md5($salt . $try));
		if($try == $password){
			return true;
		}else{
			return false;
		}


	}
	function encripta($password = ''){
		if ($password != ''){
			$salt = '~!@#$%^&*()_+=-0987654321`';
			return $password = sha1(md5($salt . $password));
		}else{
			return false;
		}
	}
	function multiexplode ($delimiters,$string) {
    
	    $ready = str_replace($delimiters, $delimiters[0], $string);
	    $launch = explode($delimiters[0], $ready);
	    return  $launch;
	}
	function valida_contenido($txt) {
		//caracteres de invalidos
		//modificado el 25/06/2017 para el sito buscolugares.com
		//$invalid_chr = "'\" ~ script SCRIPT";
		$invalid_chr = " ' \" ~ script SCRIPT";

		//generamos arreglo de invalidos
		$invalid_chrs = explode(' ', $invalid_chr);
		//quitamos chrs invalidos
		$txt = str_replace($invalid_chrs, "", $txt);

		// strip any slashes
		$txt = stripslashes($txt);

		// add  slashes //problemas con login
		//$txt = addslashes($txt);

		return $txt;
	}
	function valida_contenidoArray($array){
		
		foreach ($array as $item => $value){
			$array[$item] = $this -> valida_contenido($value);
		}
		return $array;

	}
	//meotodo que recibe son tipos de variables y no texto
	function getPostVar($metodo ="",$excepcion){
		if( $metodo == $_POST or $metodo == $_GET or $metodo == $_REQUEST){
				$numero = count($metodo);
				$tags = array_keys($metodo);
				$valores = array_values($metodo);// obtiene los valores de las varibles	

				// crea las variables y les asigna el valor
					for($i=0;$i<$numero;$i++){
					 $valores[$i] = $this->valida_contenido($valores[$i]);
					 	////al parecer no funciona el if
					 	//echo "$tags[$i], $excepcion <br>";
					 /*
					    if( in_array($tags[$i], $excepcion) ){
							$var[$tags[$i]]= "'".$valores[$i]."'";
					    }else{
					 		$var[$tags[$i]]= "'".strtolower($valores[$i])."'";
					    }
					 */
				    $var[$tags[$i]]= "'".$valores[$i]."'";
					}
				return $var;	
		}else{
			//tiene que regresar todas
			return null;
		}
	}

	function toJson($string,$ifArray=FALSE){
		/*
		una cadena string con semi formato o formato Json regresa un array o un objeto
		*/
		$invalid_chr = "' \" ";
	    $invalid_chrs = explode(' ', $invalid_chr);
		$data =str_replace($invalid_chrs,'',$string);

		$arr = array('{' => '{"','}' => '"}',',' => '","',':' => '":"');  
		$data= strtr($data,$arr);
		$variable = explode(",", $data);
		$cadena="";
		$cadenaTemp="";
		for ($i=count($variable)-1; $i >=0 ; $i--) { 
			if (strpos($variable[$i], ':')){
				$cadena=$variable[$i].$cadenaTemp.$cadena;
				$cadenaTemp="";
			}else{
				$cadenaTemp="^".$variable[$i].$cadenaTemp;
			}
		}
		$cadena = str_replace('"^"',',',$cadena);
		$data = str_replace('""','","',$cadena);
		if ($ifArray){
			$data=  (array)json_decode($data);
		}else{
			$data= json_decode($data);//$data->{'campo'};
		}
		return $data;
	}
	//regresa la extension de un archivo... que tenga extresion obvio
	function extension($file_name){
		$tmp = explode('.', $file_name);
		$file_extension = end($tmp);
        return $file_extension;
	}

	function fecha_fechaBD($fecha) {
		if ($fecha == "")
			$fecha == "00/00/00";
		$pieces = explode("/", $fecha);
		$mes = $pieces[0];
		$dia = $pieces[1];
		$anio = $pieces[2];
		$newFecha = $anio . "-" . $mes . "-" . $dia;
		return $newFecha;
	}

	function fechaBD_fecha($fecha) {
		if ($fecha == "")
			$fecha == "00-00-00";
		$pieces = explode("-", $fecha);
		$mes = $pieces[1];
		$dia = $pieces[2];
		$anio = $pieces[0];
		$newFecha = $mes . "/" . $dia . "/" . $anio;
		return $newFecha;
	}

	function formato_moneda($numero) {
		return "$" . number_format($numero, 2);
	}

	function result_to_array($result) {
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$array[] = $row;
		}
		return $array;
	}
	
	function array_to_bd($data){
		$numero = count($data);
        $tags = array_keys($data);
        $valores = array_values($data);
        for ($i = 0; $i < $numero; $i++) {
            $valor = trim($valores[$i]);
			if(!is_numeric($valor))
            	//$var[$tags[$i]] = "'$valor'";
            	$var[$tags[$i]] = str_replace("'",'',$valor);  
			else {
				$var[$tags[$i]] = "$valor";
			}
        }
        return $var;
	}

	function textoFecha($fecha) {
		// 2013/01/31 $resultFecha="***";
		$pieces = explode("-", $fecha);
		$resultFecha = $pieces[1];
		switch ($pieces[1]) { case "01" :
				$resultFecha = $pieces[2] . " de Enero, " . $pieces[0];
				break;
			case "02" :
				$resultFecha = $pieces[2] . " de Febrero, " . $pieces[0];
				break;
			case "03" :
				$resultFecha = $pieces[2] . " de Marzo, " . $pieces[0];
				break;
			case "04" :
				$resultFecha = $pieces[2] . " de Abril, " . $pieces[0];
				break;
			case "05" :
				$resultFecha = $pieces[2] . " de Mayo, " . $pieces[0];
				break;
			case "06" :
				$resultFecha = $pieces[2] . " de Junio, " . $pieces[0];
				break;
			case "07" :
				$resultFecha = $pieces[2] . " de Julio, " . $pieces[0];
				break;
			case "08" :
				$resultFecha = $pieces[2] . " de Agosto, " . $pieces[0];
				break;
			case "09" :
				$resultFecha = $pieces[2] . " de Septiembre, " . $pieces[0];
				break;
			case "10" :
				$resultFecha = $pieces[2] . " de Octubre, " . $pieces[0];
				break;
			case "11" :
				$resultFecha = $pieces[2] . " de Noviembre, " . $pieces[0];
				break;
			case "12" :
				$resultFecha = $pieces[2] . " de Diciembre, " . $pieces[0];
				break;
		}
		return $resultFecha;
	}

	function textoHora($hora) {
		// 00:00:00
		$pieces = explode(":", $hora);

		switch ((int)$pieces[0]) {
			case 12 :
				$resultHora = "12:";
				break;
			case 13 :
				$resultHora = "1:";
				break;
			case 14 :
				$resultHora = "2:";
				break;
			case 15 :
				$resultHora = "3:";
				break;
			case 16 :
				$resultHora = "4:";
				break;
			case 17 :
				$resultHora = "5:";
				break;
			case 18 :
				$resultHora = "6:";
				break;
			case 19 :
				$resultHora = "7:";
				break;
			case 20 :
				$resultHora = "8:";
				break;
			case 21 :
				$resultHora = "9:";
				break;
			case 22 :
				$resultHora = "10:";
				break;
			case 23 :
				$resultHora = "11:";
				break;
		}
		if (!isset($resultHora)) {
			$resultHora = (int)$pieces[0] . ":" . $pieces[1] . "am";
		} else {
			$resultHora .= $pieces[1] . "pm";
		}
		return $resultHora;
	}
	
	 /*
     * $cadena - cadena original a rellenar
     * $total - numero de caracteres que tendra la cadena
     * $caracteres - string con el que se rellenara
     * $posicion - orientacion en la que se llenara
     *              - LEFT
     *              - BOTH
     * $input = "Alien";
     * echo str_pad($input, 10);                      // produce "Alien     "
     * echo str_pad($input, 10, "-=", STR_PAD_LEFT);  // produce "-=-=-Alien"
     * echo str_pad($input, 10, "_", STR_PAD_BOTH);   // produce "__Alien___"
     * echo str_pad($input, 6 , "___");               // produce "Alien_"
     */
    function rellenar_con_caracter($cadena, $total, $caracteres, $posicion = FALSE) {
        if ($posicion == FALSE) {
            return str_pad($cadena, $total, $caracteres);
        } else {
            if ($posicion == "LEFT") {
                return str_pad($cadena, $total, $caracteres, STR_PAD_LEFT);
            } else if ($posicion == "BOTH"){
                return str_pad($cadena, $total, $caracteres, STR_PAD_BOTH);
            }
        }
    }
	

}
?>