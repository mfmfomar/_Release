<?php
//gettype tipo de variable

	//imprime todos los archivos que se han incluido
	function verArchivos() {
		$archivos_incluidos = get_included_files();
		echo "<pre>";
		print_r( $archivos_incluidos );
		echo "</pre>";
	}
	
	//imprime un array con formato de <pre>
	function printArray($array)
	{
		if (empty($array) and !is_array($array) ){
			return FALSE;
		}else{
			echo "<pre>";
			print_r( $array );
			echo "</pre>";
		}
		
	}
		
	//regresa la url actual
	function currentUrl($value='')
	{
	 	return 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	}

	//muestra todos los errores...
	function muestraElputoError(){
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
	}

	function loadXml($urlXml){
		//$urlXml = "views/base/sideBar.xml";
		if (file_exists($urlXml)) {
		   $xml=simplexml_load_file( $urlXml ); 
		  if($xml){
		  	//$nombre[ $xml->getName() ] = $xml;
		  	return $xml;
	   		
		  } else echo "Sintaxi XML inválida";
		} else echo "Error abriendo views/base/sideBar.xml";
		//printArray($this -> menuSide);
	}

?>