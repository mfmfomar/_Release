<?php
class View {

	function __construct() {
		//echo "View</br >";
	}
		
	public function renderArray($array,$arrg="",$headerArray=""){
		//validar que los archivos existan
		foreach ($array as $name) {
			require_once "views/" . $name . ".php";

		}

	}
	
	public function render($name){
	 	require_once "views/" . $name . ".php";
	}

}
?>