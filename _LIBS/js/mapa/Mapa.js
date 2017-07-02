/*
function main (argument) {

        var mapa = MAPA('../../archivos/ui/zoner/',"map");
        mapa.initialize();

    }
*/

var MAPA =  function(url,divId){
    ////////////////////////////////
    var map;
    ////////////////////////////////
    // MARKERS
    var markers = [];
    var dataJson=[];
    var markerCluster=[];
    ///////////////////////////////
    var mi_latitud;
    var mi_longitud;
    var position = new google.maps.LatLng(31.859576999999998, -116.606428);
    ////////////////////////////////
    // OPCIONES DEL MAPA
    var mapStyles = [{featureType:'water',elementType:'all',stylers:[{hue:'#d7ebef'},{saturation:-5},{lightness:54},{visibility:'on'}]},{featureType:'landscape',elementType:'all',stylers:[{hue:'#eceae6'},{saturation:-49},{lightness:22},{visibility:'on'}]},{featureType:'poi.park',elementType:'all',stylers:[{hue:'#dddbd7'},{saturation:-81},{lightness:34},{visibility:'on'}]},{featureType:'poi.medical',elementType:'all',stylers:[{hue:'#dddbd7'},{saturation:-80},{lightness:-2},{visibility:'on'}]},{featureType:'poi.school',elementType:'all',stylers:[{hue:'#c8c6c3'},{saturation:-91},{lightness:-7},{visibility:'on'}]},{featureType:'landscape.natural',elementType:'all',stylers:[{hue:'#c8c6c3'},{saturation:-71},{lightness:-18},{visibility:'on'}]},{featureType:'road.highway',elementType:'all',stylers:[{hue:'#dddbd7'},{saturation:-92},{lightness:60},{visibility:'on'}]},{featureType:'poi',elementType:'all',stylers:[{hue:'#dddbd7'},{saturation:-81},{lightness:34},{visibility:'on'}]},{featureType:'road.arterial',elementType:'all',stylers:[{hue:'#dddbd7'},{saturation:-92},{lightness:37},{visibility:'on'}]},{featureType:'transit',elementType:'geometry',stylers:[{hue:'#c8c6c3'},{saturation:4},{lightness:10},{visibility:'on'}]}];

    var mapOptions = {
          center: new google.maps.LatLng(31.859576999999998, -116.606428),
          zoom: 14,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          scrollwheel: false,
           styles: mapStyles 
        };

        $('.geo-location').on("click", function() {
            if (navigator.geolocation) {
                $('#'+divId).addClass('fade-map');
                navigator.geolocation.getCurrentPosition(success);
            } else {
                error('Geo Location is not supported');
            }
        });
        function success(position) {

            miPosicion = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            map.setCenter(miPosicion);
            //console.log(position.coords.latitude+'='+ position.coords.longitude);

             //poner un marcador
                //setMarker(position.coords.latitude,position.coords.longitude);
             $('#'+divId).removeClass('fade-map');
        }    
       
    //publica
    return {
        initialize:  function(json_data) {
            dataJson=json_data;
            initialize();
            listeners();
        },
        get:  function(variable) {
            switch(variable) {
                case 'mi_latitud':
                    return  mi_latitud;
                    break;
                case 'mi_longitud':
                    return  mi_longitud;
                    break;
                case 'mi_longitud':
                    deleteMarkers();
                    break;
                default:
                    return  markers;
                    break;
            }
            
        },
        addMarker: function(json_data){
                    setMarker_data(json_data);

                    //Agrupador
                    var clusterStyles = [
                        {
                            url: 'assets/img/cluster.png',
                            height: 37,
                            width: 37
                        }
                    ];
                    markerCluster = new MarkerClusterer(map, markers, {styles: clusterStyles, maxZoom: 15});
        },
        clearMap:function(json_data){

            deleteMarkers();
            initialize();
        }

    }
    
    function setMarker (posicionLat,posicionLng) {

            var marker = new MarkerWithLabel({
                position:new google.maps.LatLng(posicionLat,posicionLng),
                map: map,
                icon: url+'assets/img/marker.png',
                //labelContent: pictureLabel,
                labelAnchor: new google.maps.Point(50, 0),
                labelClass: "marker-style"
            });
    }
    
    function setMarker_data (data) {
        //tipo -> casa departamento terreno local
        //imgMarker
        //detallesUrl
        //estatus -> venta renta
        //imgPropiedad
        //precio
        //Descripcion
        //posicionLat
        //posicionLng
        //console.log(data[0]['tipo']);

        for (i = 0; i < data.length; i++) {
            ///imagen dentro del marker
            var pictureLabel = document.createElement("img");
            pictureLabel.src = url + data[i]['imgMarker'];

            ///marker
            //seleccionar el tipo de estilo
            var clase;
            switch(data[i]["disponibilidad"]) {
                case "1":
                    clase = "marker-style-green";
                    break;
                case "0":
                    clase = "marker-style-red";
                    break;
                default:
                    clase = "marker-style";
            }
            var marker = new MarkerWithLabel({
                position:new google.maps.LatLng(data[i]['lat'],data[i]['lng']),
                map: map,
                icon: url+'assets/img/marker.png',
                labelContent: pictureLabel,
                labelAnchor: new google.maps.Point(50, 0),
                labelClass: clase
            });

            ///lo guarda en el arreglo
            markers.push(marker);

            ///cuadro de detalles
            var boxText = document.createElement("div");
            infoboxOptions = {
                content: boxText,
                disableAutoPan: false,
                //maxWidth: 150,
                pixelOffset: new google.maps.Size(-100, 0),
                zIndex: null,
                alignBottom: true,
                boxClass: "infobox-wrapper",
                enableEventPropagation: true,
                closeBoxMargin: "0px 0px -8px 0px;height:14%;width:14%",
                closeBoxURL: url+"assets/img/close-btn.png",
                infoBoxClearance: new google.maps.Size(1, 1)
            };
            
            
// Seccion de cascarones (inicio) 
boxText['default']='<div class="infobox-inner">' +
    '<a href="' +  data[i]['detallesUrl'] + '">' +
    '<div class="infobox-image" style="position: relative">' +
        '<figure class="boxtipo">' + data[i]['estatus'] + '</figure>'+
        '<img src="' + url+data[i]['imgPropiedad'] + '">' + 
        '<div><span class="infobox-price">' + data[i]['precio'] + '</span></div>' +
    '</div>' +
    '</a>' +
    '<div class="infobox-description">' +
        '<div class="infobox-title"><a href="' +'">'+data[i]['Descripcion']+'</a></div>' +
        '<!--<div class="infobox-location">' + '2479 Murphy Court descripcion' + '</div>-->' +
    '</div>' +
'</div>';

boxText[1]='<div class="infobox-inner">' +
    '<a href="' +  data[i]['detallesUrl'] + '">' +
    '<div class="infobox-image" style="position: relative">' +
        '<img src="' + url+data[i]['imgPropiedad'] + '">' + 
    '</div>' +
    '</a>' +
'</div>';
// Termino de cascaron (fin)

            boxText.innerHTML = boxText[1];
            //Define the infobox
            markers[(i)].infobox = new InfoBox(infoboxOptions);
            /// agrega un listener al market

                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        $(".infobox-wrapper>img").click();
                        markers[i].infobox.open(map, this);
                    }
                })(marker, i));



        }
    }
                                
                                
    

/////Aprobadas  
    function initialize() {
        loadLocation();
        map = new google.maps.Map(document.getElementById(divId),mapOptions);
    }
  
    function loadLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(setPosition);          
    //var mi_longitud;

        } else {
           //errorPosition();
            console.log("Geolocation is not supported by this browser.");
        }
    }
  
    function setPosition(position) {
      mi_latitud = position.coords.latitude;
      mi_longitud = position.coords.longitude;
      mapOptions.center= new google.maps.LatLng(mi_latitud,mi_longitud);
     // map = new google.maps.Map(document.getElementById(divId),mapOptions);
      map.setCenter(mapOptions.center);
      setMarker(mi_latitud,mi_longitud);

/*
              setMarker_data(dataJson);
                    var clusterStyles = [
                        {
                            url: 'assets/img/cluster.png',
                            height: 37,
                            width: 37
                        }
                    ];
                    var markerCluster = new MarkerClusterer(map, markers, {styles: clusterStyles, maxZoom: 15});
*/
        

    }
    function errorPosition() {
    
        mi_latitud = (Math.cos(Math.random() * 70) * 70).toFixed(6);
        mi_longitud = (Math.cos(Math.random() * 180) * 180).toFixed(6); 
    }

    function listeners(){
        // Change markers on zoom 
            google.maps.event.addListener(map, 'zoom_changed', function() {
                var zoom = map.getZoom();
               
                // iterate over markers and call setVisible
                for (i = 0; i < markers.length; i++) {
                    markers[i].setVisible(zoom >= 10);
                }
            });
    }
    

// Sets the map on all markers in the array.
function setMapOnAll(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setMapOnAll(null);
}

// Shows any markers currently in the array.
function showMarkers() {
  setMapOnAll(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
  clearMarkers();
  markers = [];
  markerCluster = [];
}



    
     
    
}
