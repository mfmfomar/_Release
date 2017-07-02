<?php
if ($_SERVER['HTTP_HOST'] =='localhost') {
	define(libsJS, "http://localhost/Web/_Release/_LIBS/js/");
} else if($_SERVER['HTTP_HOST'] =="www.anunciatehoy.com") {
	define(libsJS, "http://gearcoresoftware.com/alejandro/_Release/_LIBS/js/");
}else{
	define(libsJS, "http://".$_SERVER['SERVER_ADDR']."/Web/_Release/_LIBS/js/");
}
?>