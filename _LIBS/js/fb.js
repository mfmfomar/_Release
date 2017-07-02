/*
	scope:
	publish_actions
	user_groups
	email
	user_photos
	user_videos
*/
var FACEBOOK_class =  function(){
	var status;
	window.fbAsyncInit = function(){
		FB.init({
		  appId      : '1603877956502700', // App ID
		  channelUrl : '', // Channel File
		  status     : true, // check login status
		  cookie     : true, // enable cookies to allow the server to access the session
		  xfbml      : true  // parse XFBML
		});
	    
		FB.Event.subscribe('auth.authResponseChange', function(response) {
			if (response.status === 'connected') {
				//document.getElementById("message").innerHTML +=  "<br>Connected to Facebook";
				//SUCCESS
				status = "connected";
				console.log("Connected to Facebook");
			}	 
			else if (response.status === 'not_authorized'){
				//document.getElementById("message").innerHTML +=  "<br>Failed to Connect";
				//FAILED
				status = "not_authorized";
				console.log("Failed to Facebook");
			} 
			else {
				//document.getElementById("message").innerHTML +=  "<br>Logged Out";
				//UNKNOWN ERROR
				status = "Logged Out";
				console.log("Logged Out");
			}
		});	
	};
	return{
		login:  function() {
			Login();	
		},
		hi: function(){
			console.log("hiiii");
		},
		get:  function(variable,callback) {
			switch(variable) {
			    case 'grupos':
	    			if (status === "connected"){
	    				getGrups(callback);
	    			}else{
				    	Login(getGrups,callback);
	    			}
			        break;
			    default:
					return  "hi";
			     	break;
			}			
		},
		set: function (variable,arrgs,callback){
			switch(variable) {
			    case 'publicar':
	    			if (status === "connected"){
	    				//getGrups(callback);
	    				publicar(arrgs,callback)
	    			}
			        break;
			    default:
					return  "hi";
			     	break;
			}
		}
			
	}
	function getGrups(callback){
		if (typeof callback !== 'undefined' && jQuery.isFunction( callback ) ) {
			FB.api("/me/groups",function (response) {
				if (response && !response.error) {
		    		callback(response);
				}else{
		    		callback(response);
				}
			},{scope: 'email,user_groups'});
		}else{
			console.log("no esta definida la funcion de respuesta");
		}
	}
	function Login(execute,callback){
		FB.login(function(response) {
			if (response.authResponse) {
				execute(callback);
			}
			else {
				console.log('User cancelled login or did not fully authorize.');
			}
		},{scope: 'publish_actions,email,user_photos,user_groups'});
	}

	function publicar(arrgs,callback){
    	var params = {};
		params['caption'] = arrgs.caption;
		params['description'] = arrgs.description;
		params['link'] = arrgs.link;
		params['message'] = arrgs.message;
		params['name'] = arrgs.name;
		params['picture'] = arrgs.picture;

		//FB.api('/'+grupo+'/feed', 'post', {message: msj, picture:foto}, function(response) {
		FB.api('/'+arrgs.grupo+'/feed', 'post', params, function(response) {
			if (!response || response.error) {
				console.log('Error occured \n'+response.error);
			} else {
				//console.log('Post ID: ' + response.id);
				callback(response);
			}
		});
		

	}

	function getUserInfo() {
		FB.api('/me', function(response) {
			var str="<b>Name</b> : "+response.name+"<br>";
			str +="<b>Link: </b>"+response.link+"<br>";
			str +="<b>Username:</b> "+response.username+"<br>";
			str +="<b>id: </b>"+response.id+"<br>";
			str +="<b>Email:</b> "+response.email+"<br>";
			str +="<input type='button' value='Get Photo' onclick='getPhoto();'/>";
			str +="<input type='button' value='Logout' onclick='Logout();'/>";
			document.getElementById("status").innerHTML=str;
		});
	}
	function getPhoto(){
	  	FB.api('/me/picture?type=normal', function(response) {
			var str="<br/><b>Pic</b> : <img src='"+response.data.url+"'/>";
	  		document.getElementById("status").innerHTML+=str;  	  	    
		});
	}
	function Logout(){
		FB.logout(function(){document.location.reload();});
	}

}
// Load the SDK asynchronously
(function(d){
	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
ref.parentNode.insertBefore(js, ref);
}(document));