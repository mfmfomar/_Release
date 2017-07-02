var FORMULARIO =  function(){

	var varVerificar;
	var varGuardar;
 	//publica
  	return {
		comprobar:  function(url) {
			verificar(url);
		},
		guardar: function(url,data){
			guardarDatos(url,data);
		},
		getVerificar : function(){
			return varVerificar;
		},
		getGuardar : function(){
			return varGuardar;
		}
	}
	//privada
	function verificar (url) {
		$.ajax({   	
		  type: "POST",
		  url: url
		})
	    .done(function( data ) {
	    //echo( "success, " + data,1 );
	    	varVerificar = data;
	    	return data;
	    })
	    .fail(function() {
	  		return false;
	  });
	}

	function guardarDatos(url,data){
		/*
			convierte un  array en un json
			JSON.stringify(Var array) 	
			json_encode(Var array)

		*/
		if (varVerificar){
			$.ajax({   	
			  type: "POST",
			  url: url,
			  data:data
			})
		    .done(function( data ) {
		    //echo( "success, " + data,1 );
		    	varGuardar = data;
		    	return data;
		    })
		    .fail(function() {
		    	alert("guardarDatos.fail");
		  		return false;
		  });
		}
		
				

	}


}