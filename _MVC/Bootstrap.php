<?php
class Bootstrap {

	function __construct($api) {
			
		$url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
		$url = explode("/", $url);
		$url = array_filter($url);

		$controlador = array_shift($url);
		$metodo = array_shift($url);
		$argumentos = $url;
		if ($controlador ==""){
			header("Location: ".URL."inicio");
		}else{
			$file = 'controllers/' . $controlador . '.php';
			if ( file_exists( $file )){
				require_once $file;
				if(class_exists($controlador)){
					$controller = new $controlador();					
				}else{
					echo "Error al cargar la clase ".$controlador;
					exit();
				}
					
				$controller -> loadModel($controlador);
				if($api=="api"){
					$controller ->response();
				}else{

					$controller -> view = new View;
					if ( $metodo != '' and method_exists($controller, $metodo) ) {
						if ( count($argumentos) > 0 ) {
							//echo "exiten argumentos";
							// asigna nombre de variable a los argumentos de la url
							foreach ($argumentos as $clave => $valor) {
						
								$posicion = strpos($valor, "=");
								if( $posicion ){
									$result = explode("=", $valor);
									$temp[ $result[0] ] = $result[1];
								}else{
									$temp[] = $valor;
								}
								$argumentos = $temp;
							}
							$controller -> {$metodo}($argumentos);
						}else{
							$controller -> {$metodo}();
						}
					}else{
						//indexview
						//echo "<br>no exite el metodo -><br>".$metodo;
						$argumentos[] =$metodo;
						if ( count($argumentos) > 0 ) {
							//echo "exiten argumentos";
							// asigna nombre de variable a los argumentos de la url
							foreach ($argumentos as $clave => $valor) {
						
								$posicion = strpos($valor, "=");
								if( $posicion ){
									$result = explode("=", $valor);
									$temp[ $result[0] ] = $result[1];
								}else{
									$temp[] = $valor;
								}
								$argumentos = $temp;
							}
						}
						$controller -> indexView($argumentos);
					}
				}
				

			}else{
				/*
				 no existe el controlador revisara si esta en la carpeta REST
				 de otra forma redirecciona a inicioController
				*/
				 
				header("Location: ".URL."inicio");
				//echo "no exite el controlador".$file;
			}
		}

	}

}
?>