//Map Interface Building
	let zoomres=40; //where to switch to spots view
	let datasetsurl = 'searchmaps.json';
	let lastdatasetsurl ='searchdatasets.json';
	let allDatasets = [];

	let newibextent = [0, 0, 400, 300];
	let imageBasemapLayer = new ol.layer.Image({
  'source': new ol.source.ImageStatic({
	'attributions': [
	  new ol.Attribution({
		'html': '&copy; <a href="">Need image source here.</a>'
	  })
	],
	'url': 'images/nophoto.jpg',
	'projection': new ol.proj.Projection({
	  'code': 'map-image',
	  'units': 'pixels',
	  'extent': newibextent
	}),
	'imageExtent': newibextent
  })
});



/*
	let datasetPointsSource = new ol.source.Vector({
features: (new ol.format.GeoJSON()).readFeatures(datasetsJSON)
});
*/


	let datasetPointsSource = new ol.source.Vector({
url: datasetsurl,
format: new ol.format.GeoJSON()
});

	let datasetPointsLayer = new ol.layer.Vector({
	source: datasetPointsSource,
	style: datasetPointStyleFunction(),
	name: 'datasetpoints'
	//title: 'Dataset Points',

});

	let featureLayer = new ol.layer.Group({
	name: 'featureLayer',
	title: 'Spots',
	layers: []
});

	let datasetsLayer = new ol.layer.Group({
	'name': 'datasetsLayer',
	'title': 'Datasets',
	'layers': []
});

	let ibfeatureLayer = new ol.layer.Group({
	name: 'ibfeatureLayer',
	title: 'Spots',
	layers: []
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

	let mapView = new ol.View({
	'projection': 'EPSG:3857',
	'center': [-11000000, 4600000],
	'zoom': 5, //5
	'minZoom': 0
});


map = new ol.Map({
	target: 'map',
	controls: ol.control.defaults({}),
	view: mapView
});

map.addLayer(baseLayers);
map.addLayer(datasetPointsLayer);
map.addLayer(datasetsLayer);
map.addLayer(featureLayer);



map.on('moveend', updateMapDiv);

	let layerSwitcher = new ol.control.LayerSwitcher({
	tipLabel: 'Layers' // Optional label for button
});

map.addControl(layerSwitcher);

featureLayer.set('title',null);
datasetsLayer.set('title',null);
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

	if(allDatasets.length == 0){
		updateAllDatasets();
	}

	removeSelectedSymbol(map);

	let feature = getClickedFeature(map, evt);
	let layer = getClickedLayer(map, evt);
	if (feature && layer && layer.get('name') !== 'datasetpointss' && layer.get('name') !== 'selectedHighlightLayer') {

		if(layer.get('name')!== 'datasetpoints'){



		}else{

			clickedDatasetId = feature.get('hash');

			//alert(clickedDatasetId);
			window.open("https://strabospot.org/geotiff/detail/"+clickedDatasetId);

		}

	}else{
		clickedMapFeature = undefined;
		closeSideBar();
	}
});



	let showZoomedOut = function(){

	//hideLayer('spots');
	$("#spotswaiting").hide();
	closeSideBar();
	removeSelectedSymbol(map);
	hideLayer('featureLayer');

	if(lastdatasetsurl != datasetsurl){


		rebuildDatasetsLayer();

		lastdatasetsurl = datasetsurl;
	}else{
	}

	showLayer('datasetpoints');
	featureLayer.set('title',null);
	datasetsLayer.set('title',null);
	layerSwitcher.renderPanel();

}

	let rebuildDatasetsLayer = function(){

	$("#datasetswaiting").show();

	console.log("in rebuildDatasetsLayer");

	datasetPointsSource = new ol.source.Vector({
	url: datasetsurl,
	format: new ol.format.GeoJSON()
	});

	datasetPointsLayer.setSource(datasetPointsSource);

	let listenerKey = datasetPointsSource.on('change', function(e) {
		if (datasetPointsSource.getState() == 'ready') {
			$("#datasetswaiting").hide();

			//remove those features in expandedDatasets
			_.each(expandedDatasets, function(exdat){
				exid = exdat.get('id');
				newDatasets = datasetPointsSource.getFeatures();
				_.each(newDatasets, function(newds){
					newid = newds.get('id');
					if(newds.get('id')==exid){
						datasetPointsSource.removeFeature(newds);
					}
				});
			});

			console.log("allDatasets:");
			console.log(allDatasets);

			ol.Observable.unByKey(listenerKey);
		}
	});

}

	let showZoomedIn = function(){

	hideLayer('datasetpoints');
	showLayer('featureLayer');

	featureLayer.set('title','Spots');
	datasetsLayer.set('title','Datasets');

	loadFeatures();

}

function updateMapDiv(evt) {

	let resolution = map.getView().getResolution();
	let zoom = map.getView().getZoom();

	document.getElementById("myres").innerHTML='resolution: '+resolution+' zoom: '+zoom;

	/*
	if(!baseMapActive){

		if(resolution < zoomres){ //show individual spots (was 20)

			showZoomedIn();

		}else{

			showZoomedOut();

		}

	}
	*/

}