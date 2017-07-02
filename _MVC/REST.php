<?php
/*
Arrays
$_POST
$_GET
$_PUT
$_DELETE
*/
class REST extends Controller {
	function __construct() {
	}
	function response($arrgs){
		header('Access-Control-Allow-Origin: *');
		$this->metodo = $_SERVER['REQUEST_METHOD'];
		$formatos = new Formatos();
		switch ($this->metodo) {
		    case "GET":
		        unset( $_GET['url']);
				$this->arrgs = $formatos -> valida_contenidoArray( $formatos -> getPostVar( $_GET) );
		        break;
		    case "POST":
		        $this->metodoType = $_POST;
		        break;
		    case 2:
		        echo "i es igual a 2";
		        break;
		}
		if (method_exists($this, $this->metodo )){
			$this->$_SERVER['REQUEST_METHOD']();
		}else{
			$xhr['XMLHttpRequest'] = false;
			echo json_encode($xhr);
		}
	}
	function _get($arrgs){
		if ( $this->arrgs['tabla'] !="" and $this->arrgs['where'] != define ){
			$data = $this -> model -> select( $this->arrgs['select'], $this->arrgs['tabla']);
		}else if ($this->arrgs['where'] !="" and $this->arrgs['where'] == define){
			$data = $this -> model -> select( $this->arrgs['select'], $this->arrgs['tabla'],"WHERE ". $this->arrgs['where']);
		}
		$data_array = $this -> model -> result_to_array ( $data );
		return  json_encode( $data_array );
	}

	function _post($arrgs){
		/*
		la fecha tiene que llegar validada, si no no la va a guardar

		*/
		if ($arrgs['level'] == 0){
				$post = $_POST;
				$count = count($post);
				if ($arrgs['level'] == 0 && $count>0){
					foreach ($post as $key => $value) {
						$post[$key]= $this -> valida_contenido($post[$key]);
					}
					return $this -> model -> insertar($arrgs['tabla'], $post);
				}
				return 0;
		}
		return 0;
	}

	function _put($arrgs){
		if ($arrgs['level'] == 0){
			$update = $_REQUEST;
			$count = count($update);
			$condicion ="WHERE ".$arrgs['condicion'];
			return $this -> model -> update($arrgs['tabla'],$update,$condicion);
		}
		return 0;
	}

	function _delete($arrgs){
		if ($arrgs['level'] == 0){
			$delete = $_REQUEST;
			$count = count($delete);
			printArray($delete);
			$condicion ="WHERE ";
			foreach ($delete as $key => $value) {
				if ($condicion == "WHERE "){	
					$condicion .= $key." = '" .$value."'";
				}else{
					$condicion .= " AND ".$key." = '" .$value."'";
				}
			}
			return $this -> model -> delete($arrgs['tabla'],$condicion);
		}
		return 0;
	}


}
?>