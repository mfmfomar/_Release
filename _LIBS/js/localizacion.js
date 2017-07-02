/*
var location = LOCALIZACION_CLASS;
	location.GEO.init(geoResponse);
	location.ip_api.init(geoResponse);
	location.freegeoip.init(geoResponse);
	*/
var GEO_class =  function(){
	var data = [];
	data['geo'] = null;
	data['timestamp'] = 0;
	data['accuracy'] = 0;
	data['altitude'] = null;
	data['altitudeAccuracy'] = null;
	data['heading'] = null;
	data['latitude'] = 0;
	data['longitude'] = 0;
	data['speed'] = null;
	var callback = null;
	return{
		init:function(funcion){
			callback = funcion;
			initialize();
		}
	}
	
	function initialize(){
		//console.log('GEO init');
		if(navigatorSupport()){
			//llamando la funcion inicial para ver trabajar la API
			obtainGeolocation();
		}else{
			console.log('HTML5 API - Geolocation: No es soportado, vamos no se resista más al cambio y la evolución')
		}
	}
	function navigatorSupport(){
		if(navigator.geolocation){
			//console.log('HTML5 API - Geolocation: Es soportado.');
			return true;
		}else{
			//console.log('HTML5 API - Geolocation: No es soportado, vamos no se resista más al cambio y la evolución.');
			return false;
		}
	}
	function obtainGeolocation(){
		//obtener la posición actual y llamar a la función  "localitation" cuando tiene éxito
		window.navigator.geolocation.getCurrentPosition(localitation);
	}
	function localitation(geo){
	 // En consola nos devuelve el Geoposition object con los datos nuestros
	 	data['geo'] =geo;
	 	data['timestamp'] =geo.timestamp;
	 	for (elemento in geo.coords) { 
	 		if(data.indexOf(elemento)){ 
		 		data[elemento]=geo.coords[elemento];
		 	}
	 		
	 	}
	 	if (typeof callback !== 'undefined' && jQuery.isFunction( callback ) ) {
	 		callback('GEO_class',data);
	 	}else{
	 		console.log('no se definio el callback de GEO_class');
	 	}
	 }
}(document);
var ip_api =  function(){
	var callback = null;
	return{
		init:function(funcion){
			callback = funcion;
			initialize();
		}
	}
	function initialize(){
		$.getJSON("http://ip-api.com/json/?callback=?", function(data) {
			if (typeof callback !== 'undefined' && jQuery.isFunction( callback ) ) {
	 			callback('ip_api',data);
		 	}else{
		 		console.log('no se definio el callback de ip_api');
		 	}
	    });
	}
}(document);
var freegeoip =  function(){
	var callback = null;
	return{
		init:function(funcion){
			callback = funcion;
			initialize();
		}

	}
	function initialize(){
		$.getJSON( "http://freegeoip.net/json/?callback=?", function(data){
			if (typeof callback !== 'undefined' && jQuery.isFunction( callback ) ) {
	 			callback('freegeoip',data);
		 	}else{
		 		console.log('no se definio el callback de ip_api');
		 	}
		  } );
		
	}
}(document);
var LOCALIZACION_CLASS={
	geo:GEO_class,
	ip_api:ip_api,
	freegeoip:freegeoip
};