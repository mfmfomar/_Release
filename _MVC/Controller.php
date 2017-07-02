<?php
/*
 * Ultima modificacion 3-febrero-2014
 */
class Controller {

	function __construct() {

	}

	function loadModel($name) {
		$path = "models/" . $name . "_model.php";
		if (file_exists($path)) {
			require_once ($path);
			$modelName = $name."_Model";
			if(class_exists($modelName)){
				$this->model = new $modelName();					
			}else{
				echo "No existe el modelo $name <br>";
				echo "En la ruta $path ---<br>";
				
			}
		
		}else{
			echo "No existe el archivo $name <br>";
			echo "En la ruta $path <br>";
		}
	}

	function response($arrgs){
		header('Access-Control-Allow-Origin: *');
		        //unset( $_GET['url']);
		$this->metodo = $_SERVER['REQUEST_METHOD'];
		$formatos = new Formatos();
		/*
		switch ($this->metodo) {
		    case "GET":
				$this->arrgs = $formatos -> valida_contenidoArray( $formatos -> getPostVar( $_GET) );
		        break;
		    case "POST":
				$this->arrgs = $formatos -> valida_contenidoArray( $formatos -> getPostVar( $_POST) );
		        break;
		    case 2:
		        echo "i es igual a 2 ===> estoy en la funcon response class controller";
		        break;
		}
		*/
		if (method_exists($this, $this->metodo )){
			$this->$_SERVER['REQUEST_METHOD']($arrgs);
		}else{
			$xhr['XMLHttpRequest'] = false;
			echo json_encode($xhr);
		}
	}		
}
?>