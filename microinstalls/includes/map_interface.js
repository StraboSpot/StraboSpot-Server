//Map Interface Building
	let zoomres=40; //where to switch to spots view
	let datasetsurl = 'installLocations.json';
	let lastdatasetsurl ='newsearchdatasets.json';
	let allDatasets = [];
	let newSearchFeatures = "";
	let ctx = null;
	let pixelRatio = null;
	let currentMode = "mainMap"; //stratMap
	let currentStratSection = null;
	let currentStratSectionId = null;

	let newibextent = [0, 0, 400, 300];
	let imageBasemapLayer = new ol.layer.Image({
  'source': new ol.source.ImageStatic({
	'attributions': [
	  new ol.Attribution({
		'html': '&copy; <a href="">Need image source here.</a>'
	  })
	],
	'url': '/search/images/nophoto.jpg',
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

	let stratSectionLayer = new ol.layer.Group({
	name: 'featureLayer',
	title: 'Strat Section',
	layers: []
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
			title: 'Mapbox Satellite',
			type: 'base',
			visible: true,
			source: new ol.source.XYZ({'url': 'https://tiles.strabospot.org/v5/mapbox.satellite/{z}/{x}/{y}.png'})
		}),
		new ol.layer.Tile({
			title: 'Mapbox Outdoors',
			type: 'base',
			visible: true,
			source: new ol.source.XYZ({'url': 'https://tiles.strabospot.org/v5/mapbox.outdoors/{z}/{x}/{y}.png'})
		}),
		new ol.layer.Tile({
			title: 'OSM',
			type: 'base',
			visible: true,
			source: new ol.source.OSM()
		})
	]
});

	let mapView = new ol.View({
	'projection': 'EPSG:3857',
	'center': [-9000000, 4600000], //[-11000000, 4600000]
	'zoom': 3, //5
	'minZoom': 3
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


map.on('precompose', function (event) {
	//Set up global variable to determine if we are currently on strat-section page

	if(currentMode == "stratMap"){


		ctx = event.context;
		pixelRatio = event.frameState.pixelRatio;
		drawAxes(ctx, pixelRatio, currentStratSection);

		//StratSectionFactory.drawAxes(ctx, pixelRatio, stratSection);

		//var mapSize = map.getSize();
		//var mapExtent = map.getView().calculateExtent(map.getSize());

	}

});


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

	let newSearchRebuildDatasetsLayer = function(){

	console.log("in newSearchRebuildDatasetsLayer");


	//var stringFeatures = JSON.stringify(newSearchFeatures);

	//var fooFeatures = '{"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"Point","coordinates":[-65.7270746324572,-24.39926619728]},"properties":{"name":"Default","projectname":"Andes 2018","id":"15214564309566","count":"1","owner":"Katie Graham"}},{"type":"Feature","geometry":{"type":"Point","coordinates":[-77.9999816,25.0412466]},"properties":{"name":"South Island Outcrop","projectname":"Modern Carbonates Field Trip 2019","id":"15767655255103","count":"1","owner":"Casey Duncan"}}]}';

	//$("#datasetswaiting").show();





	//var newSearchFormat = new ol.format.GeoJSON();
	//var newFeatures = newSearchFormat.readFeatures(JSON.parse(fooFeatures));


	//var newFeatures = (new ol.format.GeoJSON()).readFeatures(newSearchFeatures, {
	//dataProjection : 'EPSG:3857',
	//featureProjection: 'EPSG:4326'
	//});

	/*
	let newFeatures = (new ol.format.GeoJSON()).readFeatures(JSON.parse(fooFeatures), {
	dataProjection : 'EPSG:4326',
	featureProjection: 'EPSG:3857'
	});
	*/


	/*
	datasetPointsSource = new ol.source.Vector({
		features: newFeatures
	});
	*/

	/*
	datasetPointsSource = new ol.source.Vector({
	url: datasetsurl,
	format: new ol.format.GeoJSON()
	});
	*/




	let format = new ol.format.GeoJSON({
	  defaultDataProjection: 'EPSG:4326'
	});
	let features = format.readFeatures(newSearchFeatures, {
	  dataProjection: 'EPSG:4326',
	  featureProjection: 'EPSG:3857'
	});

	datasetPointsSource = new ol.source.Vector({
		features: features
	});











	/*
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
	*/

	datasetPointsLayer.setSource(datasetPointsSource);







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

	//document.getElementById("myres").innerHTML='resolution: '+resolution+' zoom: '+zoom;

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