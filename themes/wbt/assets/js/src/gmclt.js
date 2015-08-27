var GMCLT = GMCLT || {};

GMCLT.Traffic = function() {
	
  	var init = function() {
  		google.maps.event.addDomListener(window, 'load', initialize);
  	};

	var initialize = function() {
		var myLatlng = new google.maps.LatLng(35.2269, -80.8433);
		var mapOptions = {
			zoom: 11,
		    scrollwheel: false,
		    //draggable: false,
		    center: myLatlng
		}

		trafficmap = new google.maps.Map(document.getElementById('traffic-map-canvas'), mapOptions);

		var trafficLayer = new google.maps.TrafficLayer();
		trafficLayer.setMap(trafficmap);
		getTrafficIncidents();
		getTrafficCameras();
	};

	var getTrafficIncidents = function() {
		trafficObject = new Object();
			
		infowindow = new google.maps.InfoWindow({
	        content: 'Test content'
	    });
			
		jQuery.getJSON('http://site.gmclt.com/api/Traffic.cfc?method=getActiveIncidents&callback=?',
			
			function (trafficObject) {
					
				for (var i=0;i<trafficObject.length;i++) { 
					var id = trafficObject[i].incidentId;
					var location = new google.maps.LatLng(trafficObject[i].lat, trafficObject[i].lng);
					var markerimage = trafficObject[i].marker;
					var title = trafficObject[i].headline;
					var zindex = trafficObject[i].zindex;
					
					//create marker
					window['marker' + id] = new google.maps.Marker({
						position: location,
						map: trafficmap,
						icon: markerimage,
						title: title,
						zIndex: zindex
					});

					//Add listener for click
					google.maps.event.addListener(window['marker' + id], 'click', buildIncidentClickHandler(trafficObject[i]));
					
				}
			});
	};

	var buildIncidentClickHandler =	function(i) {
		return function() {
			var id = i.incidentId;
			var body = i.body;
			var title = i.headline;
			var markerimage = i.marker;
			var startdate = i.startdate;
			var starttime = i.starttime;
			var enddate = i.enddate;
			var endtime = i.endtime;
			
			if (startdate == enddate) {
				var activedatestring = startdate + ' at ' + starttime + ' - ' + endtime;
			}
			else {
				var activedatestring = startdate + ' at ' + starttime + ' - ' + enddate + ' at ' + endtime;
			}
			
			infowindow.setContent("<h3><img src='" + markerimage + "' align='left' style='padding: 10px;' vspace='2' hspace='2'>" + title + "</h3><p class='attribution'>" + activedatestring + "</p><p>" + body + "</p>");
			infowindow.open(trafficmap,window['marker' + id]);
		};
	};

	var	getTrafficCameras = function() {
		cameraObject = new Object();
		
		infowindow = new google.maps.InfoWindow({
	        content: 'Test content'
	    });
		
		jQuery.getJSON('http://site.gmclt.com/api/Traffic.cfc?method=getActiveCameras&callback=?',
		
			function (cameraObject) {
				
				for (var i=0;i<cameraObject.length;i++) { 
					var id = cameraObject[i].webId;
					var location = new google.maps.LatLng(cameraObject[i].latitude, cameraObject[i].longitude);
					var title = cameraObject[i].name;
					
					//create marker
					window['camera' + id] = new google.maps.Marker({
						position: location,
						map: trafficmap,
						icon: 'http://content.gmclt.com/traffic/trafficCamera.png',
						title: title,
						zIndex: 1
					});

					//Add listener for click
					google.maps.event.addListener(window['camera' + id], 'click', buildCameraClickHandler(cameraObject[i]));
					
				}
			});
	};

	var buildCameraClickHandler = function(i) {
		return function() {
			var id = i.webId;
			var orientation = i.orientation;
			var name = i.name;
			var refreshRate = i.refreshRate;
			var provider = i.provider;
			var fullImage = i.fullImage;
			
			infowindow.setContent("<img src='" + fullImage + "' style='padding: 10px;' vspace='2' hspace='2'><h2>" + name + "</h2><p class='attribution'>" + orientation + "</p><p>" + provider + "</p>");
			infowindow.open(trafficmap,window['camera' + id]);
		};

  };

  	var oPublic =
	    {
	      init: init
	    };
	    return oPublic;
 
 }();
  
GMCLT.Weather = function() {
	 
 	var init = function() {
		//alert('hello');
		//handlebarsExample();
		populateWeatherData('USNC0121');	
		jQuery('#gmcltWX_search').keydown(function (e){
		    if(e.keyCode == 13){
		        searchWeatherLocations();
		    }
		}); 
		jQuery('#gmcltWX_searchsubmit').click(function(){
			searchWeatherLocations();
			
		});
	};
	
	var populateWeatherData = function(locationId) {
		var wxConditionsSource = jQuery("#currentConditions-template").html(); 
		var wxConditionsTemplate = Handlebars.compile(wxConditionsSource); 
		var wxForecastFullSource = jQuery("#forecastFull-template").html(); 
		var wxForecastFullTemplate = Handlebars.compile(wxForecastFullSource);
		var wxForecastSource = jQuery("#forecast-template").html(); 
		var wxForecastTemplate = Handlebars.compile(wxForecastSource); 
		
		jQuery.getJSON('http://api.gmclt.com/weather/weather.cfc?method=getWeatherData&locationId=' + locationId + '&callback=?',
		
			function (wxDataObject) {
				jQuery('#gmcltWX_currentContent').html(wxConditionsTemplate(wxDataObject));
				jQuery('#gmcltWX_forecastFullContent').html(wxForecastFullTemplate(wxDataObject));
				jQuery('#gmcltWX_forecastContent').html(wxForecastTemplate(wxDataObject));
				jQuery('.gmcltWX_search').show();
				jQuery('.gmcltWX_loading').hide();
			})
			.fail(function() {
			   wxError();
			});
		
		
		
	};
	
	var searchWeatherLocations = function() {
		var searchQuery = jQuery.trim(jQuery('#gmcltWX_search').val());
		var wxSearchSource = jQuery("#searchResults-template").html(); 
		var wxSearchTemplate = Handlebars.compile(wxSearchSource);
		
		if (searchQuery.length) {
			jQuery('.gmcltWX_loading').show();
			jQuery('#gmcltWX_currentContent').html('');
			jQuery('#gmcltWX_forecastFullContent').html('');
			jQuery('#gmcltWX_forecastContent').html('');
			jQuery.getJSON('http://api.gmclt.com/weather/weather.cfc?method=searchLocations&queryString=' + searchQuery + '&callback=?',
		
			function (wxSearchObject) {
				if (wxSearchObject.match) {
					populateWeatherData(wxSearchObject.results[0].locationId);
					jQuery('#gmcltWX_search').val('');
				}
				else {
					jQuery('#gmcltWX_currentContent').html(wxSearchTemplate(wxSearchObject));
					jQuery('.gmcltWX_loading').hide();
					jQuery('#gmcltWX_search').val('');
				}
				
			})
			.fail(function() {
			   wxError();
			});
			
		}
		
	};
	
	
	// Enable the visual refresh
	google.maps.visualRefresh = true;
	var map;
	var infoWindow;
	// Default State
	var GMCLTstate = 'NC';

	function initializeMap() {
		geocoder = new google.maps.Geocoder();
	  	geocoder.geocode( { 'address': GMCLTstate}, function(results, status) {
	      if (status == google.maps.GeocoderStatus.OK) {
			  var mapOptions = {
				  zoom: 7,
				  center: results[0].geometry.location,
				  mapTypeId: google.maps.MapTypeId.ROADMAP
				  };
			
			  map = new google.maps.Map(document.getElementById('map-canvas'),
			      mapOptions);
			  
			  addAlertBoxes(GMCLTstate);
			  
			  tileNEX = new google.maps.ImageMapType({
				  getTileUrl: function(tile, zoom) {
					  return "http://mesonet.agron.iastate.edu/cache/tile.py/1.0.0/nexrad-n0q-900913/" + zoom + "/" + tile.x + "/" + tile.y +".png?"+ (new Date()).getTime(); 
				  },
				  tileSize: new google.maps.Size(256, 256),
				  opacity:0.60,
		          name : 'NEXRAD',
				  isPng: true
				  });

        
			 
				  map.overlayMapTypes.push(null); // create empty overlay entry
				  map.overlayMapTypes.setAt("1",tileNEX);
				  
			var stateList = 'NC,SC,VA,WV,TN,GA,AL,AK,AZ,AR,CA,CO,CT,DE,DC,FL,HI,ID,IL,IN,IA,KS,KY,LA,ME,MD,MA,MI,MN,MS,MO,MT,NE,NV,NH,NJ,NM,NY,ND,OH,OK,OR,PA,RI,SD,TX,UT,VT,WA,WI,WY';
       		var stateARRAY = stateList.split(',');
        
	   		for (var i=0; i < stateARRAY.length; i++){
	   			if (stateARRAY[i] != GMCLTstate) {
		        addAlertBoxes(stateARRAY[i]);
	        }
        }
		
		
	} else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
    
    }
    
    function addAlertBoxes(state) {
	    var GMCLTMapObject = new Object();
		        $.getJSON('http://site.gmclt.com/_global/_components/WeatherAlerts.cfc?method=getStateAlerts&state=' + state + '&callback=?',
			
				function (GMCLTMapObject) {
		                   for (var i=0; i < GMCLTMapObject.length; i++){
		                   	
		                       var id = GMCLTMapObject[i].ID;
							   			   
			                   var myCoords = new Array();
			                                      
			                   for (var j=0; j < GMCLTMapObject[i].CountiesPolygon.length; j++){
				                   myCoords.push(new google.maps.LatLng(GMCLTMapObject[i].CountiesPolygon[j].lat,GMCLTMapObject[i].CountiesPolygon[j].lng));
			                   }
			                   
			                   window['alertZone' + id] = new google.maps.Polygon({
							    paths: myCoords,
							    strokeColor: '#FF0000',
							    strokeOpacity: 0.8,
							    strokeWeight: 1,
							    fillColor: '#FF0000',
							    fillOpacity: 0.35
							  });
							
							  window['alertZone' + id].setMap(map);
							  
							  google.maps.event.addListener(window['alertZone' + id], 'click', buildPolyClickHandler(id));
							  
							  
							  }
		
		                  
			        });

	    
    }
    
    function changeState(){
	    var x=document.getElementById("GMCLTStates").selectedIndex;
		var y=document.getElementById("GMCLTStates").options;
		
		GMCLTstate = y[x].value;
		initializeMap();
		document.getElementById('alert-content').innerHTML = '<p>Click on a highlighted zone above to view weather alert details</p>';
		
		
		
    }
    
    function buildPolyClickHandler(id) {
	    return function() {
	    	
	    	var GMCLTDetailsObject = new Object();
		        $.getJSON('http://site.gmclt.com/_global/_components/WeatherAlerts.cfc?method=getAlertDetail&CountyID=' + id + '&callback=?',
			
				function (GMCLTDetailsObject) {
					var html = '<h1>WBT Operation Stormwatch Alerts for ' + GMCLTDetailsObject[0].Name + ' County ' + GMCLTDetailsObject[0].State + '</h1>';
					
					if (GMCLTDetailsObject[0].Events.length > 1){
						var alerttext = 'There are ' + GMCLTDetailsObject[0].Events.length + ' active alerts';
					}
					else {
						var alerttext = 'There is 1 active alert';
					}
					var html = html + '<p>' + alerttext + '</p>';
					
					for (var i=0; i < GMCLTDetailsObject[0].Events.length; i++){
					 	var html = html + '<h2>' + GMCLTDetailsObject[0].Events[i] + ' for ' + GMCLTDetailsObject[0].Name + ' until ' + GMCLTDetailsObject[0].Expires[i] + '</h2>';
					 	var html = html + '<p>' + GMCLTDetailsObject[0].Summary[i] + '</p>'; 
					 	var html = html + '<hr />';
					}
					
					document.getElementById('alert-content').innerHTML = html;
					
				});
	    	
	    	
	    };
    }
    
	google.maps.event.addDomListener(window, 'load', initializeMap);
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	var wxError = function() {
		var wxErrorSource = jQuery("#error-template").html(); 
		var wxErrorTemplate = Handlebars.compile(wxErrorSource);
		jQuery('#gmcltWX_currentContent').html(wxErrorTemplate());
		jQuery('.gmcltWX_loading').hide();
		jQuery('.gmcltWX_search').hide();
	};
	
	var oPublic =
	    {
	      init: init,
	      stormwatchInit: stormwatchInit,
	      populateWeatherData: populateWeatherData
	    };
    return oPublic;
	 
}();