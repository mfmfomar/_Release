<?php

class Session {
	
	public static function init() {
		@session_start();
	}	
		
	public static function destroy($key=false) {		
		if($key){
			if(is_array($key)){
				foreach ($key as $name) {
				  if(isset($_SESSION[$name])){
				  	unset($_SESSION[$name]);
				  }
				}
			}else{
				if(isset($_SESSION[$key])){
				  	unset($_SESSION[$key]);
				  }
			}
		}else{
			unset($_SESSION);
			session_destroy();	
		}
	}

	public static function set($key, $value) {
		$_SESSION[NAME."->".$key] = $value;
	}
	
	public static function get($key) {
		if(isset($_SESSION[NAME."->".$key])){
		  	return $_SESSION[NAME."->".$key];
		 }
	}

	public static function acceso($level,$grupo=FALSE) {
		if(!Session::get("login")){
			header('Location:'.URL."problema/acceso/1010");
			exit;
		}
		Session::tiempo();
		if(Session::getlevel($level) > Session::getlevel(Session::get("level"))){
			header('Location:'.URL."problema/acceso/5050");
			exit;
		}
	}
	
	public static function accesoView($level) {
		if(!Session::get('login')){
			return FALSE;
		}
		Session::tiempo();
		if(Session::getlevel($level)> Session::getlevel(Session::get("level"))){
			return FALSE;
		}
		return TRUE;
		
	}
	public static function getlevel($level) {
		$nivel["admin"]=8;
		$nivel["developer"]=7;
		$nivel["tester"]=6;
		$nivel["special"]=5;
		$nivel["premium"]=4;
		$nivel["gold"]=3;
		$nivel["silver"]=2;
		$nivel["usuario"]=1;
		
		if(!array_key_exists($level, $nivel)){
			return 0;
			//throw new Exception("problema de acceso");
		}else{
			return $nivel[$level];
		}
	}
	
	/*
	 * $key   nombre del arreglo
	 * $idf   el identificador del campo
	 * $value el valor del campo 
	
	public static function setArray($idf, $idf, $value) {
		$_SESSION[NAME."->".$idf][$idf] = $valor;
	}
	 */
	public static function setArray($nombre, $val) {
		foreach ($val as $key => $value) {
			$_SESSION[NAME."->".$nombre][$key] = $value;
		}
	}
	
	public static function setCustomArray($key, $array) {
		$numero = count($array);
			$tags = array_keys($array);
			$valores = array_values($array);// obtiene los valores de las varibles	
			// crea las variables y les asigna el valor
			for($i=0;$i<$numero;$i++){
				$_SESSION[NAME."->".$key][$tags[$i]]= $valores[$i];

			}
	}	
	
	public static function getArray($key, $idf) {
		return  $_SESSION[NAME."->".$key][$idf];
	}
	/*------------------------------------------------------*/
	public static function accesoEstricto($level, $noAdmin =false) {
		if(!Session::get('login')){
			return FALSE;
		}
		Session::tiempo();
		if($noAdmin == false){
			if(Session::get('level') == 'admin'){
				return false;
			}
		}

		if(count($level)){
			if(in_array(Session::get('level'), $level)){
				return false;
			}
		}
		return true;
	}

	public static function tiempo_temp() {
		if(!Session::get('tiempo') || !defined('SESSION_TIME')){
			echo "no se a definido el tiempo de sesion";
		}
		if(SESSION_TIME == 0 || Session::get('tiempo')==":)"){
			return;
		}

		if(time() - Session::get('tiempo') > (SESSION_TIME * 60)){
			header('location:'.URL."problema/acceso/8080");
		}else{
			Session::set('tiempo',time());
		}
	}

	public static function tiempo(){
		if(!Session::get('tiempo') || !defined('SESSION_TIME')){
			//no tiene sentido este if, por que cuando llegue aqui signigca que si esta declarada
	      echo "no se a definido el tiempo de sesion";
	    }else{
	      $tiempos[0]='SESSION_TIME';
	      $tiempos[1]=SESSION_TIME*60;
	      $tiempos[2]='tiempo del sistema';
	      $tiempos[3]=time();
	      $tiempos[4]='tiempo del usuario';
	      $tiempos[5]=Session::get('tiempo');
	      $tiempos[6]='diferencia';
	      $tiempos[7]=time() - Session::get('tiempo');
	      //printArray($tiempos);
	      if(SESSION_TIME == 0 || Session::get('tiempo')=="0"){
	        //echo "tiempo de sesion ilimitado";
	        return;
	      }else if(time() - Session::get('tiempo') > (SESSION_TIME * 60)){
	        header('location:'.URL."problema/acceso/8080");
	      }else{
	        echo "sesion activa";
	        Session::set('tiempo',time());

	      }
	    }
	}


}
?>