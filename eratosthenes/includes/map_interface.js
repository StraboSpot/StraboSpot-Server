//Map Interface Building
var zoomres=40; //where to switch to spots view
var datasetsurl = 'searchdatasets.json';
var lastdatasetsurl ='searchdatasets.json';
var allDatasets = [];

var newibextent = [0, 0, 400, 300];

var datasetPointStyleFunction = function() {
	return function(feature, resolution) {
		var style = new ol.style.Style({
			image: new ol.style.Circle({
				radius: 7,
				fill: new ol.style.Fill({
					color: 'rgba(255,0,0,0.8)'
				}),
				stroke: new ol.style.Stroke({color: 'black', width: 2})
			}),
			text: new ol.style.Text({
				text: datasetGetText(feature, resolution),
				offsetX: 5, //15
				offsetY: -20,
				font: '12px Calibri,sans-serif',
				fill: new ol.style.Fill({
					color: '#000'
				}),
				stroke: new ol.style.Stroke({
					color: '#fff',
					width: 5
				})
			})
		});
		return [style];
	};
};

var linesStyleFunction = function() {
	return function(feature, resolution) {
		var style = new ol.style.Style({
			image: new ol.style.Circle({
				radius: 7,
				fill: new ol.style.Fill({
					color: 'rgba(255,0,0,0.8)'
				}),
				stroke: new ol.style.Stroke({color: 'black', width: 2})
			})
		});
		return [style];
	};
};


var datasetGetText = function(feature, resolution) {

	var maxResolution = 10000;
	//var text = feature.get('projectname')+' - '+feature.get('name')+'\n('+feature.get('count')+' spots)';
	var text = feature.get('name');

	if (resolution > maxResolution) {
		text = '';
	}

	return text;
};

var locationssource = new ol.source.Vector({
url: 'data/points.json',
format: new ol.format.GeoJSON()
});

var locationslayer = new ol.layer.Vector({
	source: locationssource, //datasetPointsSource,
	style: datasetPointStyleFunction(),
	name: 'Locations'
	//title: 'Dataset Points',

});

var linessource = new ol.source.Vector({
url: 'data/lines.json',
format: new ol.format.GeoJSON()
});

var lineslayer = new ol.layer.Vector({
	source: linessource, //datasetPointsSource,
	name: 'Lines'
	//title: 'Dataset Points',

});



var baseLayers = new ol.layer.Group({
	'title': 'Base maps',
	layers: [
		new ol.layer.Tile({
			title: 'OSM',
			type: 'base',
			visible: true,
			source: new ol.source.OSM()
		}),
		new ol.layer.Group({
			title: 'MacroStrat',
			type: 'base',
			combine: true,
			visible: false,
			layers: [
				new ol.layer.Tile({
					source: new ol.source.XYZ({'url': 'http://tiles.strabospot.org/v5/mapbox.satellite/{z}/{x}/{y}.png'})
				}),
				new ol.layer.Tile({
					source: new ol.source.XYZ({'url': 'http://tiles.strabospot.org/v5/macrostrat/{z}/{x}/{y}.png'})
				})
			]
		}),
		new ol.layer.Tile({
			title: 'Mapbox Satellite',
			type: 'base',
			visible: true,
			source: new ol.source.XYZ({'url': 'http://tiles.strabospot.org/v5/mapbox.satellite/{z}/{x}/{y}.png'})
		}),
		new ol.layer.Tile({
			title: 'Mapbox Outdoors',
			type: 'base',
			visible: true,
			source: new ol.source.XYZ({'url': 'http://tiles.strabospot.org/v5/mapbox.outdoors/{z}/{x}/{y}.png'})
		})
	]
});

//4600000

var mapView = new ol.View({
	'projection': 'EPSG:3857',
	'center': [-11000000, 5200000],
	'zoom': 5, //5
	'minZoom': 0
});


map = new ol.Map({
	target: 'map',
	controls: ol.control.defaults({}),
	view: mapView
});

map.addLayer(baseLayers);
map.addLayer(lineslayer);
map.addLayer(locationslayer);


//map.on('moveend', updateMapDiv);

var layerSwitcher = new ol.control.LayerSwitcher({
	tipLabel: 'Layers' // Optional label for button
});

map.addControl(layerSwitcher);

//featureLayer.set('title',null);
//datasetsLayer.set('title',null);
layerSwitcher.renderPanel();

var outextent = map.getView().calculateExtent(map.getSize());

var outleft = ol.extent.getBottomLeft(outextent)[0];
var outbottom = ol.extent.getBottomLeft(outextent)[1];
var outright = ol.extent.getTopRight(outextent)[0];
var outtop = ol.extent.getTopRight(outextent)[1];

var outwkt = 'POLYGON (('+outleft+' '+outbottom+', '+outleft+' '+outtop+', '+outright+' '+outtop+', '+outright+' '+outbottom+', '+outleft+' '+outbottom+'))';
var outformat = new ol.format.WKT();
var outbox = outformat.readFeature(outwkt);
var outgeometry = outbox.getGeometry();



map.on('click', function (evt) {

	var feature = getClickedFeature(map, evt);
	var layer = getClickedLayer(map, evt);
	
	if(feature){
	
		console.log(feature.get('name'));
		$.featherlight("locationdetails.php?id="+encodeURI(feature.get('name')));
	}

});




