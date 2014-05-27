$(document).foundation();

//http://stackoverflow.com/questions/9895951/how-to-display-multiple-markers-with-individual-framecloud-popups-in-openlayers

var map, mappingLayer, bingRoad, vectorLayer, selectMarkerControl, selectedFeature;
OpenLayers.ImgPath = "/bundles/bonnesadresses/js/img/";

function displayAddressDetails(response) {
	$('#content').empty();

	$('#content').append('<div itemscope="" itemtype="http://schema.org/LocalBusiness"></div>');

	$('#content div').append('<h1 itemprop="name">' + response.name + '</h1>');

	if (response.adresse != null || response.codePostal != null || response.ville != null) {
		$('#content div').append('<p class="postalAddress" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"></p>');
		if (response.adresse != null) {
			$('#content div .postalAddress').append('<span itemprop="streetAddress" itemprop="street-address">' + response.adresse + ' </span>');
		}
		if (response.codePostal != null) {
			$('#content div .postalAddress').append('<span itemprop="postalCode">' + response.codePostal + ' </span>');
		}
		if (response.ville != null) {
			$('#content div .postalAddress').append('<span itemprop="addressLocality" itemprop="locality">' + response.ville + '</span>');
		}
	}

	if (response.telephone != null) {
		$('#content div').append('<p itemprop="telephone">' + response.telephone + '</p>');
	}

	if (response.url != null) {
		$('#content div').append('<p itemprop="url"><a href="' + response.url + '">' + response.name + '</a></p>');
	}

	if (response.origine != null || response.description != null) {
		$('#content div').append('<blockquote class="review" itemprop="review" itemscope itemtype="http://schema.org/Review"></blockquote>');
		if (response.description != null) {
			$('#content div .review').append('<p itemprop="reviewBody">' + response.description + '</p>');
		}
		if (response.origine != null) {
			$('#content div .review p').append('<cite itemprop="author" itemscope itemtype="http://schema.org/Person"><td itemprop="name">' + response.origine + '</cite>');
		}
	}

	if (response.marker != null) {
		$('#content div').append('<p>' + response.marker + '</p>');
	}

	$('#content div').append('<p>' + response.latitude + ', ' + response.longitude + '<div itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates"><meta itemprop="latitude" content="' + response.latitude + '" /><meta itemprop="longitude" content="' + response.longitude + '" /></p>');
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

	nameFilter = new OpenLayers.Filter.Comparison({
	    type: OpenLayers.Filter.Comparison.LIKE,
	    property: "Name",
	    value: ""
	});

	categoryFilter = new OpenLayers.Filter.Comparison({
		type: OpenLayers.Filter.Comparison.LIKE,
		property: "Marker",
		value: ""
	});

	filterStrategy = new OpenLayers.Strategy.Filter();

	vectorLayer = new OpenLayers.Layer.Vector("Vector Layer", {projection: "EPSG:4326", strategies: [filterStrategy]});

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
	var randomFeature = new OpenLayers.Feature.Vector(lonLat, { Name: name, Url: url, Id: id, Lon : lon, Lat : lat, DetailUrl: detailUrl, Marker: marker}, defaultStyle);
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

$("#filter input").keyup(function() {
	nameFilter.value = $(this).val();
	filterStrategy.setFilter(nameFilter);
});
$(".categoryFilter img").click(function() {
	if ($(this).attr("class") == 'circle-stroked') {
		filterStrategy.setFilter();
	} else {
		categoryFilter.value = $(this).attr("class");
		filterStrategy.setFilter(categoryFilter);
	}
});
