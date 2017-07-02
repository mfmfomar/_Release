var Funciones_class =  function(){
	return{
		joinData:function(data,estructura){
			return joinData(data,estructura);
		},
		getS:function(key){
			return getS(key);
		},
		setS:function(name,key){
			return setS(name,key);
		},
		shuffleArray:function(array){
			return shuffleArray(array);
		},
		ejecutar:function(funcion){
			return ejecutar(funcion);
		}
	}
	function getS(key){
		if( window.localStorage ){
			if (localStorage.getItem(key) != undefined && localStorage.getItem(key) !=""){
				return localStorage.getItem(key);
			}else{
				return false;
			}
		}
		return false;
	}

	function setS(name,key){
		if( window.localStorage ){
			return localStorage.setItem(name,key);
		}
		return false;
	}

	function joinData(data,estructura){
		//console.log(data);
		for (var dato in data){ 
			//console.log(dato+'='+data[dato]);
			var re = new RegExp("{"+dato+"}", 'g');
			estructura = estructura.replace(re, data[dato]);
		}
		return estructura;
	}
	function shuffleArray(array) {
	  var currentIndex = array.length
	    , temporaryValue
	    , randomIndex
	    ;

	  // While there remain elements to shuffle...
	  while (0 !== currentIndex) {

	    // Pick a remaining element...
	    randomIndex = Math.floor(Math.random() * currentIndex);
	    currentIndex -= 1;

	    // And swap it with the current element.
	    temporaryValue = array[currentIndex];
	    array[currentIndex] = array[randomIndex];
	    array[randomIndex] = temporaryValue;
	  }

	  return array;
	}
	function ejecutar(funcion){
		if (typeof funcion !== 'undefined' && jQuery.isFunction( funcion ) ) {
			funcion();
		}else{
			console.log("llamada a funcion inexistente");
		}
	}
	/*22/03/2016
	function function_name (argument) {
		arrayEspacios.forEach(function (element, index, array) {
			//console.log("[" + index + "] = " + element);
			random = Math.floor(Math.random() * arrayEspacios.length) ;
			console.log(arrayEspacios[random]);
			//arrayEspacios.remove(random);
			delete arrayEspacios[random];	   
		});
	}
	*/
}(document);