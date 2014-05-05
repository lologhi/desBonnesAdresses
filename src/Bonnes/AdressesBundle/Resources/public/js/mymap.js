$(document).foundation();

//http://stackoverflow.com/questions/9895951/how-to-display-multiple-markers-with-individual-framecloud-popups-in-openlayers

var map, mappingLayer, bingRoad, vectorLayer, selectMarkerControl, selectedFeature;
OpenLayers.ImgPath = "/bundles/bonnesadresses/js/img/";

function displayAddressDetails(response) {
	$('#content table').empty();

	$('#content table').append('<tbody itemscope itemtype="http://schema.org/LocalBusiness"></tbody>');

	$('#content tbody').append('<tr><td>name</td><td><span itemprop="name" itemprop="name">' + response.name + '</span></td></tr>');

	if (response.adresse != null || response.codePostal != null || response.ville != null) {
		$('#content tbody').append('<tr><td>adresse</td><td><span class="postalAddress" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"></span></td></tr>');
		if (response.adresse != null) {
			$('#content tbody .postalAddress').append('<span itemprop="streetAddress" itemprop="street-address">' + response.adresse + ' </span>');
		}
		if (response.codePostal != null) {
			$('#content tbody .postalAddress').append('<span itemprop="postalCode">' + response.codePostal + ' </span>');
		}
		if (response.ville != null) {
			$('#content tbody .postalAddress').append('<span itemprop="addressLocality" itemprop="locality">' + response.ville + '</span>');
		}
	}

	if (response.url != null) {
		$('#content tbody').append('<tr><td>url</td><td itemprop="url">' + response.url + '</td></tr>');
	}

	if (response.origine != null || response.description != null) {
		$('#content tbody').append('<tr><td>description</td><td><span class="review" itemprop="review" itemscope itemtype="http://schema.org/Review"></span></td></tr>');
		if (response.origine != null) {
			$('#content tbody .review').append('(via <span itemprop="author" itemscope itemtype="http://schema.org/Person"><td itemprop="name">' + response.origine + '</span>) ');
		}
		if (response.description != null) {
			$('#content tbody .review').append('<span itemprop="reviewBody">' + response.description + '</span>');
		}
	}

	if (response.marker != null) {
		$('#content tbody').append('<tr><td>marker</td><td itemprop="url">' + response.marker + '</td></tr>');
	}

	$('#content tbody').append('<tr><td>lat, long</td><td>' + response.latitude + ', ' + response.longitude + '<div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates"><meta itemprop="latitude" content="' + response.latitude + '" /><meta itemprop="longitude" content="' + response.longitude + '" /></div></td></tr>');
}

function onFeatureSelect(feature) {
	selectedFeature = feature;
	popup = new OpenLayers.Popup.FramedCloud("tempId", feature.geometry.getBounds().getCenterLonLat(), null, selectedFeature.attributes.Name, null, true);
	feature.popup = popup;
	map.addPopup(popup);
	$.post(
		selectedFeature.attributes.DetailUrl,
		{idAddress: feature.data.Id},
		function(response){
			//console.log(response);
			if(response.id == feature.data.Id) {
				displayAddressDetails(response);
			}
		},
		"json");
	//document.title = response.name;
	if(selectedFeature.attributes.Url != window.location) {
		// L'URL actuelle n'est pas la même que la théorique
		window.history.pushState(selectedFeature.attributes.Url, selectedFeature.attributes.Name, selectedFeature.attributes.Url);
	}
	//console.log(feature.data.Id);
}

function onFeatureUnselect(feature) {
	map.removePopup(feature.popup);
	feature.popup.destroy();
	feature.popup = null;
}

function init(){
	map = new OpenLayers.Map( 'map');
	var apiKey = 'Am9j4sJYRl62rzLT5ux_MRqxxkvw4fzCaH8KS1kdAcjvg_QDznR_DhaTlZ7qhq3C';

	// Default OSM imagerySet
	mappingLayer = new OpenLayers.Layer.OSM("OpenStreetMap");
	// Bing's Road imagerySet (http://openlayers.org/blog/2010/12/18/bing-tiles-for-openlayers/)
	// Et https://www.bingmapsportal.com/
	bingRoad = new OpenLayers.Layer.Bing({key: apiKey, type: "Road"});
	map.addLayers([bingRoad, mappingLayer]);
	map.addControl(new OpenLayers.Control.LayerSwitcher());

	vectorLayer = new OpenLayers.Layer.Vector("Vector Layer", {projection: "EPSG:4326"});

	selectMarkerControl = new OpenLayers.Control.SelectFeature(vectorLayer, {onSelect: onFeatureSelect, onUnselect: onFeatureUnselect});
	map.addControl(selectMarkerControl);

	var s = new OpenLayers.Style({'pointRadius': 10, 'externalGraphic': '/bundles/bonnesadresses/js/img/marker.png'});
	new OpenLayers.StyleMap(s);

	selectMarkerControl.activate();
	map.addLayer(vectorLayer);

	var scaleline = new OpenLayers.Control.ScaleLine();
	map.addControl(scaleline);

	center();
}

// Centré sur Notre Dame de Paris
function center() {
	map.setCenter(new OpenLayers.LonLat(2.3499021, 48.8529682).transform("EPSG:4326", map.getProjectionObject()), 13);
}

function placeMyMarkers(lat, lon, name, url, id, marker, detailUrl) {
	var lonLat = new OpenLayers.Geometry.Point( lon, lat);
	lonLat.transform("EPSG:4326", map.getProjectionObject());
	var defaultStyle = OpenLayers.Util.extend({
		externalGraphic : "/bundles/bonnesadresses/images/maki-icons/" + marker + "-18.png",
		graphicWidth : 18,
		graphicHeight : 18,
		graphicOpacity: 1
	}, OpenLayers.Feature.Vector.style['default']);
	var randomFeature = new OpenLayers.Feature.Vector(lonLat, { Name: name, Url: url, Id: id, Lon : lon, Lat : lat, DetailUrl: detailUrl}, defaultStyle);
	vectorLayer.addFeatures(randomFeature);
}

function placeMySpecificMarkers(lat, lon, name, url, id) {
	var lonLat = new OpenLayers.Geometry.Point( lon, lat);
	lonLat.transform("EPSG:4326", map.getProjectionObject());
	var specialStyle = OpenLayers.Util.extend({
		externalGraphic : "/bundles/bonnesadresses/js/img/marker.png",
		graphicOpacity: 1
	}, OpenLayers.Feature.Vector.style['default']);
	var randomFeature = new OpenLayers.Feature.Vector(lonLat, { Name: name, Url: url, Id: id, Lon : lon, Lat : lat}, specialStyle);
	vectorLayer.addFeatures(randomFeature);
}

init();

/* http://www.tinywall.info/2012/02/change-browser-url-without-page-reload-refresh-with-ajax-request-using-javascript-html5-history-api-php-jquery-like-facebook-github-navigation-menu.html */
/* the below code is to override back button to get the ajax content without page reload*/
/*$(window).bind('popstate', function() {
	$.ajax({url:location.pathname+'?rel=tab',success: function(data){
		$('#content').html(data);
	}});
});/**/
