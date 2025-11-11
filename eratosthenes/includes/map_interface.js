//Map Interface Building
	let zoomres=40; //where to switch to spots view
	let datasetsurl = 'searchdatasets.json';
	let lastdatasetsurl ='searchdatasets.json';
	let allDatasets = [];

	let newibextent = [0, 0, 400, 300];

	let datasetPointStyleFunction = function() {
	return function(feature, resolution) {
	let style = new ol.style.Style({
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

	let linesStyleFunction = function() {
	return function(feature, resolution) {
	let style = new ol.style.Style({
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


	let datasetGetText = function(feature, resolution) {

	let maxResolution = 10000;
	//var text = feature.get('projectname')+' - '+feature.get('name')+'\n('+feature.get('count')+' spots)';
	let text = feature.get('name');

	if (resolution > maxResolution) {
		text = '';
	}

	return text;
};

	let locationssource = new ol.source.Vector({
url: 'data/points.json',
format: new ol.format.GeoJSON()
});

	let locationslayer = new ol.layer.Vector({
	source: locationssource, //datasetPointsSource,
	style: datasetPointStyleFunction(),
	name: 'Locations'
	//title: 'Dataset Points',

});

	let linessource = new ol.source.Vector({
url: 'data/lines.json',
format: new ol.format.GeoJSON()
});

	let lineslayer = new ol.layer.Vector({
	source: linessource, //datasetPointsSource,
	name: 'Lines'
	//title: 'Dataset Points',

});



	let baseLayers = new ol.layer.Group({
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

	let mapView = new ol.View({
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

	let layerSwitcher = new ol.control.LayerSwitcher({
	tipLabel: 'Layers' // Optional label for button
});

map.addControl(layerSwitcher);

//featureLayer.set('title',null);
//datasetsLayer.set('title',null);
layerSwitcher.renderPanel();

	let outextent = map.getView().calculateExtent(map.getSize());

	let outleft = ol.extent.getBottomLeft(outextent)[0];
	let outbottom = ol.extent.getBottomLeft(outextent)[1];
	let outright = ol.extent.getTopRight(outextent)[0];
	let outtop = ol.extent.getTopRight(outextent)[1];

	let outwkt = 'POLYGON (('+outleft+' '+outbottom+', '+outleft+' '+outtop+', '+outright+' '+outtop+', '+outright+' '+outbottom+', '+outleft+' '+outbottom+'))';
	let outformat = new ol.format.WKT();
	let outbox = outformat.readFeature(outwkt);
	let outgeometry = outbox.getGeometry();



map.on('click', function (evt) {

	let feature = getClickedFeature(map, evt);
	let layer = getClickedLayer(map, evt);

	if(feature){

		console.log(feature.get('name'));
		$.featherlight("locationdetails.php?id="+encodeURI(feature.get('name')));
	}

});




