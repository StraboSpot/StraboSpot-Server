//Map Interface Building
var zoomres=40; //where to switch to spots view
var datasetsurl = 'searchdatasets.json';
var lastdatasetsurl ='searchdatasets.json';
var allDatasets = [];

var newibextent = [0, 0, 400, 300];
var imageBasemapLayer = new ol.layer.Image({
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
var datasetPointsSource = new ol.source.Vector({
features: (new ol.format.GeoJSON()).readFeatures(datasetsJSON)
});
*/


var datasetPointsSource = new ol.source.Vector({
url: datasetsurl,
format: new ol.format.GeoJSON()
});

var datasetPointsLayer = new ol.layer.Vector({
	source: datasetPointsSource,
	style: datasetPointStyleFunction(),
	name: 'datasetpoints'
	//title: 'Dataset Points',

});

var featureLayer = new ol.layer.Group({
	name: 'featureLayer',
	title: 'Spots',
	layers: []
});

var datasetsLayer = new ol.layer.Group({
	'name': 'datasetsLayer',
	'title': 'Datasets',
	'layers': []
});

var ibfeatureLayer = new ol.layer.Group({
	name: 'ibfeatureLayer',
	title: 'Spots',
	layers: []
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

var mapView = new ol.View({
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

var layerSwitcher = new ol.control.LayerSwitcher({
	tipLabel: 'Layers' // Optional label for button
});

map.addControl(layerSwitcher);

featureLayer.set('title',null);
datasetsLayer.set('title',null);
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

	if(allDatasets.length == 0){
		updateAllDatasets();
	}
	
	removeSelectedSymbol(map);

	var feature = getClickedFeature(map, evt);
	var layer = getClickedLayer(map, evt);
	if (feature && layer && layer.get('name') !== 'datasetpointss' && layer.get('name') !== 'selectedHighlightLayer') {
		
		if(layer.get('name')!== 'datasetpoints'){
		
			clickedMapFeature = feature.get('id');
		
			if(!baseMapActive){
				//activeFeature = feature; //save this for returning to map
				activeId = feature.get('id');
				activeGeometry = feature.getGeometry();
			}

			setSelectedSymbol(map, feature.getGeometry());

			getCurrentSpot(); //get the clicked spot into memory

			console.log(currentSpot);
		
			updateSidebar();
	
			openSideBar();
		
		}else{
		
			//console.log(feature.getProperties());
			clickedDatasetId = feature.get('id');
			
			expandDataset(feature);
			
			datasetPointsSource.removeFeature(feature);
			
			//console.log(expandedDatasets);
		
		}

	}else{
		clickedMapFeature = undefined;
		closeSideBar();
	}
});



var showZoomedOut = function(){

	//hideLayer('spots');
	$("#spotswaiting").hide();
	closeSideBar();
	removeSelectedSymbol(map);
	hideLayer('featureLayer');
	
	if(lastdatasetsurl != datasetsurl){
	
		//console.log("rebuild datasetpoints layer here (datasetsurl: "+datasetsurl+")");

		rebuildDatasetsLayer();

		lastdatasetsurl = datasetsurl;
	}else{
		//console.log("Not Changed (datasetsurl: "+datasetsurl+")");
	}
	
	showLayer('datasetpoints');
	featureLayer.set('title',null);
	datasetsLayer.set('title',null);
	layerSwitcher.renderPanel();

}

var rebuildDatasetsLayer = function(){

	$("#datasetswaiting").show();
	
	console.log("in rebuildDatasetsLayer");

	datasetPointsSource = new ol.source.Vector({
	url: datasetsurl,
	format: new ol.format.GeoJSON()
	});

	datasetPointsLayer.setSource(datasetPointsSource);

	var listenerKey = datasetPointsSource.on('change', function(e) {
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

var showZoomedIn = function(){

	hideLayer('datasetpoints');
	showLayer('featureLayer');

	featureLayer.set('title','Spots');
	datasetsLayer.set('title','Datasets');

	loadFeatures();

}

function updateMapDiv(evt) {

	var resolution = map.getView().getResolution();
	var zoom = map.getView().getZoom();
	
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