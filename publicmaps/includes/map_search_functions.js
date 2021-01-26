/*
This file contains Javascript for the StraboSpot map search Interface. It is derived
from the StraboSpot ionic app.

Author: Jason Ash (jasonash@ku.edu) 04/19/2017
*/

//global vars
var spotNames = [];
var layerStates = [];
var iblayerStates = [];
var loadedFeatures = "";
var loadedEnvelope = "";
var currentSpot = "";
var savedCenterZoom = {};
//var activeFeature = {};
var activeId = 0;
var activeGeometry = {};

var idsNames = {};
var featureSpotIds = {};
var featureTypes = {};

var currentlyLoading = false;

var currentBasemapId = 0;

var activeTabName = "";
var activeTabId = "";
var baseMap = {};
var baseMapActive = false;
var crumbs = [];

var fitFeatures = [];

var expandedDatasets = [];		//datasets that are expanded (into spots) on the search interface
var alldatasets = [];			//all datasets currently pulled from server (for putting back on map);

var tab_ids = [];
tab_ids[0]="spot_tab";
tab_ids[1]="orientations_tab";
tab_ids[2]="_3d_structures_tab";
tab_ids[3]="images_tab";
tab_ids[4]="nesting_tab";
tab_ids[5]="samples_tab";
tab_ids[6]="other_features_tab";
tab_ids[7]="tags_tab";

var tab_names = [];
tab_names[0]="spot";
tab_names[1]="orientations";
tab_names[2]="_3d_structures";
tab_names[3]="images";
tab_names[4]="nesting";
tab_names[5]="samples";
tab_names[6]="other_features";
tab_names[7]="tags";
		
function getClickedFeature(map, evt) {
	return map.forEachFeatureAtPixel(evt.pixel, function (feat, lyr) {
		return feat;
	}, this, function (lyr) {
		// we only want the layer where the spots are located
		return (lyr instanceof ol.layer.Vector) && lyr.get('name') !== 'drawLayer' &&
			lyr.get('name') !== 'geolocationLayer' && lyr.get('name') !== 'selectedHighlightLayer';
	});
}

function getClickedLayer(map, evt) {
	return map.forEachFeatureAtPixel(evt.pixel, function (feat, lyr) {
		return lyr;
	}, this, function (lyr) {
		// we only want the layer where the spots are located
		return (lyr instanceof ol.layer.Vector) && lyr.get('name') !== 'drawLayer' &&
			lyr.get('name') !== 'geolocationLayer' && lyr.get('name') !== 'selectedHighlightLayer';
	});
}

function removeSelectedSymbol(map) {
	//map.removeLayer(selectedHighlightLayer);

	var mylayers = map.getLayers();
	mylayers.forEach(function (thislayer) {
		//console.log(thislayer.getProperties());
		if(thislayer.get('name')=='selectedHighlightLayer'){
			map.removeLayer(thislayer);
		}
	});

}

// Add a feature to highlight selected Spot
// Encompassing orange circle for a point, orange stroke for a line, and orange fill for a polygon
function setSelectedSymbol(map, geometry) {
	var selected = new ol.Feature({
		geometry: geometry
	});

	var style = {};
	if (geometry.getType() === 'Point') {
		style = new ol.style.Style({
			image: new ol.style.Circle({
				radius: 40,
				stroke: new ol.style.Stroke({
					color: 'white',
					width: 2
				}),
				fill: new ol.style.Fill({
					color: [245, 121, 0, 0.6]
				})
			})
		});
	}
	else if (geometry.getType() === 'LineString' || geometry.getType() === 'MultiLineString') {
		style = new ol.style.Style({
			stroke: new ol.style.Stroke({
				color: [245, 121, 0, 0.6],
				width: 4
			})
		})
	}
	else {
		style = new ol.style.Style({
			stroke: new ol.style.Stroke({
				color: 'white',
				width: 2
			}),
			fill: new ol.style.Fill({
				color: [245, 121, 0, 0.6]
			})
		})
	}

	var selectedHighlightSource = new ol.source.Vector({
		features: [selected]
	});

	selectedHighlightLayer = new ol.layer.Vector({
		name: 'selectedHighlightLayer',
		source: selectedHighlightSource,
		style: style
	});

	map.addLayer(selectedHighlightLayer);

}

var hideLayer = function(layername){
	var mylayers = map.getLayers();
	mylayers.forEach(function (thislayer) {
		//console.log(thislayer.getProperties());
		if(thislayer.get('name')==layername){
			thislayer.setVisible(false);
		}
	});
}

var showLayer = function(layername){
	var mylayers = map.getLayers();
	mylayers.forEach(function (thislayer) {
		//console.log(thislayer.getProperties());
		if(thislayer.get('name')==layername){
			thislayer.setVisible(true);
		}
	});
}

var envelopeOutside = function() { //check to see if current view's data is still valid

	var extent = map.getView().calculateExtent(map.getSize());
	var bottomLeft = ol.proj.transform(ol.extent.getBottomLeft(extent),'EPSG:3857', 'EPSG:4326');
	var topRight = ol.proj.transform(ol.extent.getTopRight(extent),'EPSG:3857', 'EPSG:4326');
	var left = bottomLeft[0];
	var right = topRight[0];
	var top = topRight[1];
	var bottom = bottomLeft[1];

	if(loadedEnvelope!=""){
	
		var lb_point = turf.point([left, bottom]);
		var lt_point = turf.point([left, top]);
		var rt_point = turf.point([right, top]);
		var rb_point = turf.point([right, bottom]);

	
		if( turf.inside(lb_point, loadedEnvelope) && turf.inside(lt_point, loadedEnvelope) && turf.inside(rt_point, loadedEnvelope) && turf.inside(rb_point, loadedEnvelope)  ){

			return false;
		}else{

			return true;
		}
	
	}else{

		return true;
	
	}

}

//put features from loadedFeatures onto map
var updateMap = function() {

	console.log('in updateMap');
	console.log(loadedFeatures);
	
	spotNames = [];

	//capture layer visibility states
	layerStates = [];
	datasetsLayer.getLayers().forEach(function (thislayer) {
		l = thislayer.getProperties();
		if(l.visible){
			layerStates[l.id]='yes';
		}else{
			layerStates[l.id]='no';
		}
	});

	setCurrentTypeVisibility(map);
	
	featureLayer.getLayers().clear();
	datasetsLayer.getLayers().clear();

	if(loadedFeatures!="" && loadedFeatures!=null && loadedFeatures.features.length > 0){

		datasetsLayer.set('title','Datasets');
		featureLayer.set('title','Spots');
		
		//add dummy layers to layer switcher to allow turning off datasets
		var datasets = loadedFeatures.datasets;
		_.each(datasets, function(d){

			var datasetLayer = new ol.layer.Tile({
				'id':d.id,
				'title': d.name+' ('+d.spotcount+')',
				'layergroup': 'Datasets'
			});

			if(layerStates[d.id]=="no"){
				datasetLayer.setVisible(false);
			}

			//add listener for turning on/off datasets
			datasetLayer.on('change:visible', function (e) {
				loadFeatures();
			});
			
			datasetsLayer.getLayers().push(datasetLayer);

		});

		var spots = loadedFeatures.features;

		var totalspotcount = spots.length;
		var currentpercent = 0;
		
		//create orientation parameters
		var currentspotcount = 1;
		var mappedFeatures = [];
		_.each(spots, function (spot) {
			if(!spot.properties.image_basemap){
				if(spot.properties.name!=""){
					spotNames[spot.properties.id]=spot.properties.name;
				}else{
					spotNames[spot.properties.id]=spot.properties.id;
				}
				if(layerStates[spot.properties.datasetid]!="no" ){	//look to see whether layer is turned on				
					if ((spot.geometry.type === 'Point' || spot.geometry.type === 'MultiPoint') && spot.properties.orientation_data) {
						_.each(spot.properties.orientation_data, function (orientation) {
							var feature = JSON.parse(JSON.stringify(spot));
							delete feature.properties.orientation_data;
							_.each(orientation.associated_orientation, function (associatedOrientation) {
								feature.properties.orientation = associatedOrientation;
								mappedFeatures.push(JSON.parse(JSON.stringify(feature)));
							});
							feature.properties.orientation = orientation;
							mappedFeatures.push(JSON.parse(JSON.stringify(feature)));
						});
					}
					else mappedFeatures.push(JSON.parse(JSON.stringify(spot)));
				}
			}
			
			currentpercent = Math.round(currentspotcount/totalspotcount*100);
			//console.log(currentpercent);
			//$('#spotsProgressInnerBar').css('width', currentpercent+'%');
			currentspotcount ++;
		});

		//check for query fit here
		
		
		fitFeatures = [];
		_.each(mappedFeatures, function (spot) {
			if(spotFitsQuery(spot)){
				fitFeatures.push(JSON.parse(JSON.stringify(spot)));
			}
		});

		// get distinct groups and aggregate spots by group type
		var featureGroup = _.groupBy(fitFeatures, function (feature) {
			if (feature.geometry.type === 'Point' || feature.geometry.type === 'MultiPoint') {
				if (feature.properties.orientation && feature.properties.orientation.feature_type) {
					return getFeatureTypeLabel(
					feature.properties.orientation.feature_type) || 'no orientation type';
				}
				else return 'no orientation type'
			}else if (feature.geometry.type === 'LineString' || feature.geometry.type === 'MultiLineString') {
				if (feature.properties.trace && feature.properties.trace.trace_type) {
					return getTraceTypeLabel(feature.properties.trace.trace_type) || 'no trace type';
				}
				else return 'no trace type';
			}else if (feature.geometry.type === 'Polygon' || feature.geometry.type === 'MultiPolygon') {
				if (feature.properties.surface_feature && feature.properties.surface_feature.surface_feature_type) {
					return getSurfaceFeatureTypeLabel(feature.properties.surface_feature.surface_feature_type) || 'no surface feature type';
				}
				else return 'no surface feature type';
			}
			return 'no type';
		});

		// Go through each group and assign all the aggregates to the geojson feature
		for (var key in featureGroup) {

			if (featureGroup.hasOwnProperty(key)) {

				// Create a geojson to hold all the spots that fit the same spot type
				var spotTypeLayer = {
					'type': 'FeatureCollection',
					'features': featureGroup[key],
					'properties': {
					'name': key + ' (' + featureGroup[key].length + ')'
					}
				};

				var newlayer = geojsonToVectorLayer(spotTypeLayer, map.getView().getProjection());

				featureLayer.getLayers().push(newlayer);						
			
			}
		}
		
		layerSwitcher.renderPanel();
	
	
	}else{
	
		featureLayer.set('title',null);
		datasetsLayer.set('title',null);
		layerSwitcher.renderPanel();
	}
	
	$('#spotswaiting').hide();

}

//put features from ibloadedFeatures onto map
var updateImageBaseMap = function() {

	setCurrentTypeVisibility(map);
	
	ibfeatureLayer.getLayers().clear();

	if(loadedFeatures!="" && loadedFeatures!=null){

		ibfeatureLayer.set('title','Spots');

		var spots = loadedFeatures.features;

		var mappedFeatures = [];
		_.each(spots, function (spot) {
			if(spot.properties.image_basemap == baseMap.id){			
				if(spot.properties.name!=""){
					spotNames[spot.properties.id]=spot.properties.name;
				}else{
					spotNames[spot.properties.id]=spot.properties.id;
				}
				if(iblayerStates[spot.properties.datasetid]!="no" ){	//look to see whether layer is turned on				
					if ((spot.geometry.type === 'Point' || spot.geometry.type === 'MultiPoint') && spot.properties.orientation_data) {
						_.each(spot.properties.orientation_data, function (orientation) {
							var feature = JSON.parse(JSON.stringify(spot));
							delete feature.properties.orientation_data;
							_.each(orientation.associated_orientation, function (associatedOrientation) {
								feature.properties.orientation = associatedOrientation;
								mappedFeatures.push(JSON.parse(JSON.stringify(feature)));
							});
							feature.properties.orientation = orientation;
							mappedFeatures.push(JSON.parse(JSON.stringify(feature)));
						});
					}
					else mappedFeatures.push(JSON.parse(JSON.stringify(spot)));
				}
			}
		});

		// get distinct groups and aggregate spots by group type
		var featureGroup = _.groupBy(mappedFeatures, function (feature) {
			if (feature.geometry.type === 'Point' || feature.geometry.type === 'MultiPoint') {
				if (feature.properties.orientation && feature.properties.orientation.feature_type) {
					return getFeatureTypeLabel(
					feature.properties.orientation.feature_type) || 'no orientation type';
				}
				else return 'no orientation type'
			}else if (feature.geometry.type === 'LineString' || feature.geometry.type === 'MultiLineString') {
				if (feature.properties.trace && feature.properties.trace.trace_type) {
					return getTraceTypeLabel(feature.properties.trace.trace_type) || 'no trace type';
				}
				else return 'no trace type';
			}else if (feature.geometry.type === 'Polygon' || feature.geometry.type === 'MultiPolygon') {
				if (feature.properties.surface_feature && feature.properties.surface_feature.surface_feature_type) {
					return getSurfaceFeatureTypeLabel(feature.properties.surface_feature.surface_feature_type) || 'no surface feature type';
				}
				else return 'no surface feature type';
			}
			return 'no type';
		});

		// Go through each group and assign all the aggregates to the geojson feature
		for (var key in featureGroup) {

			if (featureGroup.hasOwnProperty(key)) {

				// Create a geojson to hold all the spots that fit the same spot type
				var spotTypeLayer = {
					'type': 'FeatureCollection',
					'features': featureGroup[key],
					'properties': {
					'name': key + ' (' + featureGroup[key].length + ')'
					}
				};

				// Add the feature collection layer to the map
				var newlayer = geojsonToVectorLayer(spotTypeLayer, map.getView().getProjection());

				ibfeatureLayer.getLayers().push(newlayer);						
			
			}
		}
		
		layerSwitcher.renderPanel();
	
	}else{
	
		ibfeatureLayer.set('title',null);
		layerSwitcher.renderPanel();
	}

}

var datasetGetText = function(feature, resolution) {

	var maxResolution = 10000;
	var text = feature.get('name');

	if (resolution > maxResolution) {
		text = '';
	}

	return text;
};

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
				offsetY: -25,
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

var spotStyleFunction = function() {
	return function(feature, resolution) {

		var thistype = feature.getGeometry().getType();

		if(thistype=="Point"){

			var style = new ol.style.Style({
				image: new ol.style.Circle({
					radius: 7,
					fill: new ol.style.Fill({
						color: 'rgba(255,0,255,0.8)'
					}),
					stroke: new ol.style.Stroke({color: 'black', width: 2})
				}),
				text: new ol.style.Text({
					//text: datasetGetText(feature, resolution),
					offsetX: 15,
					offsetY: -15,
					font: '16px Calibri,sans-serif',
					fill: new ol.style.Fill({
						color: '#000'
					}),
					stroke: new ol.style.Stroke({
						color: '#fff',
						width: 5
					})
				})
			});
			return style;

		}

		if(thistype=="Polygon"){

			var style = new ol.style.Style({
				stroke: new ol.style.Stroke({
					color: 'black',
					//lineDash: [4],
					width: 1
				}),
				fill: new ol.style.Fill({
					color: 'rgba(255, 0, 0, 0.2)'
				})
			});

			return style;

		}

		if(thistype=="LineString"){

			var style = new ol.style.Style({
			  stroke: new ol.style.Stroke({
				color: 'black',
				width: 2
			  })
			});

			return style;

		}

	};
};

var getFeatureTypeLabel = function(feature_type){
	var feature_types = JSON.parse('{"groove_marks":"groove marks","parting_lineat":"parting lineations","magmatic_miner_1":"magmatic mineral alignment","xenolith_encla":"xenolith/enclave alignment","intersection":"intersection","pencil_cleav":"pencil cleavage","mineral_align":"mineral alignment","deformed_marke":"deformed marker","rodding":"rodding","boudin":"boudin","mullions":"mullions","fold_hinge":"fold hinge","striations":"striations","slickenlines":"slickenlines","slickenfibers":"slickenfibers","mineral_streak":"mineral streaks","vorticity_axis":"vorticity axis","flow_transport":"flow/transport direction","vergence":"vergence","vector":"vector","other":"other","bedding":"bedding","contact":"contact","foliation":"foliation","fracture":"fracture","vein":"vein","fault":"fault","shear_zone":"shear zone","shear_zone_bou":"shear zone boundary","fold_axial_surface":"fold axial surface","plane_of_boudinage":"plane of boudinage","plane_of_mullions":"plane of mullions","stratigraphic":"stratigraphic","intrusive":"intrusive body","injection":"injection structure","vein_array":"vein array","zone_fracturin":"zone of fracturing","zone_faulting":"zone of faulting","damage_zone":"damage zone","alteration_zone":"alteration zone","enveloping_surface":"enveloping surface","unknown":"unknown","tectonite":"tectonite","igneous_migmat":"igneous/migmatite","soft_sediment_":"soft sediment deformation","other_fabric":"other fabric","anticline":"anticline","syncline":"syncline","monocline":"monocline","antiform":"antiform","synform":"synform","s_fold":"s-fold","z_fold":"z-fold","m_fold":"m-fold","sheath":"sheath","single_layer_b":"single-layer buckle","ptygmatic":"ptygmatic","crenulation":"crenulation","interfolial":"interfolial","boudinage":"boudinage","mullion":"mullion","lobate_cuspate":"lobate-cuspate","other_3d_structure":"other 3D structure","ellipsoidal_data":"ellipsoidal data","non_ellipsoidal_data":"non-ellipsoidal data","elliptical_data":"elliptical data"}');
	return feature_types[feature_type];
}

var getTraceTypeLabel = function(feature_type){
	var feature_types = JSON.parse('{"contact":"contact","geologic_struc":"geologic structure","geomorphic_fea":"geomorphic feature","anthropenic_fe":"anthropogenic feature","scale_bar":"scale bar","geological_cross_section":"geological cross section","geophysical_cross_section":"geophysical cross section","stratigraphic_section":"stratigraphic section","other_feature":"other feature"}');
	return feature_types[feature_type];
}

var getSurfaceFeatureTypeLabel = function(feature_type){
	var feature_types = JSON.parse('{"rock_unit":"rock unit","contiguous_outcrop":"contiguous outcrop","geologic_structure":"geologic structure","geomorphic_feature":"geomorphic feature","anthropogenic_feature":"anthropogenic feature","extent_of_mapping":"extent of mapping","extent_of_biological_marker":"extent of biological marker","subjected_to_similar_process":"subjected to similar process","gradients":"gradients","other":"other"}');
	return feature_types[feature_type];
}

// Save the current visibility for each feature type in the Spots Feature Layer
var typeVisibility = [];
function setCurrentTypeVisibility(map) {
	map.getLayers().forEach(function (layer) {
		if (layer.get('name') === 'featureLayer') {
			layer.getLayers().forEach(function (typeLayer) {
				var type = typeLayer.get('title').split(' (')[0];
				typeVisibility[type] = typeLayer.get('visible');
			});
		}
	});

}

function toRadians(deg) {
	return deg * (Math.PI / 180);
}

function getSymbolPath(feature_type, orientation, orientation_type) {

	var symbols = {
		'default_point': '/assets/js/images/geology/point.png',

		// Planar Feature Symbols
		'bedding_horizontal': '/assets/js/images/geology/bedding_horizontal.png',
		'bedding_inclined': '/assets/js/images/geology/bedding_inclined.png',
		'bedding_vertical': '/assets/js/images/geology/bedding_vertical.png',
		'contact_inclined': '/assets/js/images/geology/contact_inclined.png',
		'contact_vertical': '/assets/js/images/geology/contact_vertical.png',
		'fault': '/assets/js/images/geology/fault.png',
		'foliation_horizontal': '/assets/js/images/geology/foliation_horizontal.png',
		'foliation_inclined': '/assets/js/images/geology/foliation_general_inclined.png',
		'foliation_vertical': '/assets/js/images/geology/foliation_general_vertical.png',
		'fracture': '/assets/js/images/geology/fracture.png',
		'shear_zone_inclined': '/assets/js/images/geology/shear_zone_inclined.png',
		'shear_zone_vertical': '/assets/js/images/geology/shear_zone_vertical.png',
		'vein': '/assets/js/images/geology/vein.png',

		// Old
		// 'axial_planar_inclined': '/assets/js/images/geology/cleavage_inclined.png',
		// 'axial_planar_vertical': '/assets/js/images/geology/cleavage_vertical.png',
		// 'joint_inclined': '/assets/js/images/geology/joint_surface_inclined.png',
		// 'joint_vertical': '/assets/js/images/geology/joint_surface_vertical.png',
		// 'shear_fracture': '/assets/js/images/geology/shear_fracture.png',

		// Linear Feature Symbols
		// 'fault': '/assets/js/images/geology/fault_striation.png',
		// 'flow': '/assets/js/images/geology/flow.png',
		// 'fold_hinge': '/assets/js/images/geology/fold_axis.png',
		// 'intersection': '/assets/js/images/geology/intersection.png',
		'lineation_general': '/assets/js/images/geology/lineation_general.png'
		// 'solid_state': '/assets/js/images/geology/solid_state.png',
		// 'vector': '/assets/js/images/geology/vector.png'
	};

	// Set a default symbol by whether feature is planar or linear
	var default_symbol = symbols.default_point;
	if (orientation_type === 'linear_orientation') default_symbol = symbols.lineation_general;

	switch (true) {
		case (orientation === 0):
			return symbols[feature_type + '_horizontal'] || symbols[feature_type + '_inclined'] || symbols[feature_type] || default_symbol;
		case ((orientation > 0) && (orientation < 90)):
			return symbols[feature_type + '_inclined'] || symbols[feature_type] || default_symbol;
		case (orientation === 90):
			return symbols[feature_type + '_vertical'] || symbols[feature_type] || default_symbol;
		default:
			return default_symbol;
	}
}

// creates a ol vector layer for supplied geojson object
function geojsonToVectorLayer(geojson, projection) {
	// textStyle is a function because each point has a different text associated
	function textStyle(text) {
		return new ol.style.Text({
			'font': '12px Calibri,sans-serif',
			'text': text,
			'fill': new ol.style.Fill({
				'color': '#000'
			}),
			'stroke': new ol.style.Stroke({
				'color': '#fff',
				'width': 3
			})
		});
	}

	function textStylePoint(text, rotation) {
		return new ol.style.Text({
			'font': '12px Calibri,sans-serif',
			'text': '					       ' + text,	// we pad with spaces due to rotational offset
			'textAlign': 'center',
			'fill': new ol.style.Fill({
				'color': '#000'
			}),
			'stroke': new ol.style.Stroke({
				'color': '#fff',
				'width': 3
			})
		});
	}

	function getStrokeStyle(feature) {
		var color = '#663300';
		var width = 2;
		var lineDash = [1, 0];

		if (feature.get('trace')) {
			var trace = feature.get('trace');

			// Set line color and weight
			if (trace.trace_type && trace.trace_type === 'geologic_struc') color = '#FF0000';
			else if (trace.trace_type && trace.trace_type === 'contact') color = '#000000';
			else if (trace.trace_type && trace.trace_type === 'geomorphic_fea') {
				width = 4;
				color = '#0000FF';
			}
			else if (trace.trace_type && trace.trace_type === 'anthropenic_fe') {
				width = 4;
				color = '#800080';
			}

			// Set line pattern
			lineDash = [.01, 10];
			if (trace.trace_quality && trace.trace_quality === 'known') lineDash = [1, 0];
			else if (trace.trace_quality && trace.trace_quality === 'approximate'
				|| trace.trace_quality === 'questionable') lineDash = [20, 15];
			else if (trace.trace_quality && trace.trace_quality === 'other') lineDash = [20, 15, 0, 15];
		}

		return new ol.style.Stroke({
			'color': color,
			'width': width,
			'lineDash': lineDash
		});
	}

	function getIconForFeature(feature) {
		var feature_type = 'none';
		var rotation = 0;
		var symbol_orientation = 0;
		var orientation_type = 'none';
		var orientation = feature.get('orientation');
		if (orientation) {
			rotation = orientation.strike || orientation.trend || rotation;
			symbol_orientation = orientation.dip || orientation.plunge || symbol_orientation;
			feature_type = orientation.feature_type || feature_type;
			orientation_type = orientation.type || orientation_type;
		}

		return new ol.style.Icon({
			'anchorXUnits': 'fraction',
			'anchorYUnits': 'fraction',
			'opacity': 1,
			'rotation': toRadians(rotation),
			'src': getSymbolPath(feature_type, symbol_orientation, orientation_type),
			'scale': 0.05
		});
	}

	function getPolyFill(feature) {
		var color = 'rgba(0, 0, 255, 0.4)';			 // blue
		if (feature.get('surface_feature')) {
			var surfaceFeature = feature.get('surface_feature');
			switch (surfaceFeature.surface_feature_type) {
				case 'rock_unit':
					color = 'rgba(0, 255, 255, 0.4)';	 // light blue
					break;
				case 'contiguous_outcrop':
					color = 'rgba(240, 128, 128, 0.4)'; // pink
					break;
				case 'geologic_structure':
					color = 'rgba(0, 255, 255, 0.4)';	 // light blue
					break;
				case 'geomorphic_feature':
					color = 'rgba(0, 128, 0, 0.4)';		 // green
					break;
				case 'anthropogenic_feature':
					color = 'rgba(128, 0, 128, 0.4)';	 // purple
					break;
				case 'extent_of_mapping':
					color = 'rgba(128, 0, 128, 0)';		 // no fill
					break;
				case 'extent_of_biological_marker':	 // green
					color = 'rgba(0, 128, 0, 0.4)';
					break;
				case 'subjected_to_similar_process':
					color = 'rgba(255, 165, 0,0.4)';		// orange
					break;
				case 'gradients':
					color = 'rgba(255, 165, 0,0.4)';		// orange
					break;
			}
		}
		return new ol.style.Fill({
			'color': color
		});
	}

	// Set styles for points, lines and polygon and groups
	function styleFunction(feature, resolution) {
		var rotation = 0;
		var pointText = feature.get('name');
		var orientation = feature.get('orientation');
		if (orientation) {
			rotation = orientation.strike || orientation.trend || rotation;
			pointText = orientation.dip || orientation.plunge || pointText;
		}

		var pointStyle = [
			new ol.style.Style({
				'image': getIconForFeature(feature),
				'text': textStylePoint(pointText.toString(), rotation)
			})
		];
		var lineStyle = [
			new ol.style.Style({
				'stroke': getStrokeStyle(feature),
				'text': textStyle(feature.get('name'))
			})
		];
		var polyText = feature.get('name');
		var polyStyle = [
			new ol.style.Style({
				'stroke': new ol.style.Stroke({
					'color': '#000000',
					'width': 0.5
				}),
				'fill': getPolyFill(feature),
				'text': textStyle(polyText)
			})
		];
		var styles = [];
		styles.Point = pointStyle;
		styles.MultiPoint = pointStyle;
		styles.LineString = lineStyle;
		styles.MultiLineString = lineStyle;
		styles.Polygon = polyStyle;
		styles.MultiPolygon = polyStyle;

		return styles[feature.getGeometry().getType()];
	}

	var features;
	if (projection.getUnits() === 'pixels') {
		features = (new ol.format.GeoJSON()).readFeatures(geojson);
	}
	else {
		features = (new ol.format.GeoJSON()).readFeatures(geojson, {
			'featureProjection': projection
		});
	}

	return new ol.layer.Vector({
		'source': new ol.source.Vector({
			'features': features
		}),
		'title': geojson.properties.name,
		'style': styleFunction,
		'visible': typeVisibility[geojson.properties.name.split(' (')[0]]
		//'visible': true
	});
}

var getEnvelope = function(){ //padded
		var extent = map.getView().calculateExtent(map.getSize());

		var offset=0.2;
		var bottomLeft = ol.proj.transform(ol.extent.getBottomLeft(extent),'EPSG:3857', 'EPSG:4326');
		var topRight = ol.proj.transform(ol.extent.getTopRight(extent),'EPSG:3857', 'EPSG:4326');
		var left = bottomLeft[0]-offset;
		var right = topRight[0]+offset;
		var top = topRight[1]+offset;
		var bottom = bottomLeft[1]-offset;

		loadedEnvelope = turf.polygon([[
			[left, bottom],
			[left, top],
			[right, top],
			[right, bottom],
			[left, bottom]
		]]);

		env = left+','+top+','+right+','+bottom;
		
		return env;
}

var getCurrentViewEnvelope = function(){ //not padded
		var extent = map.getView().calculateExtent(map.getSize());

		var offset=0.0;
		var bottomLeft = ol.proj.transform(ol.extent.getBottomLeft(extent),'EPSG:3857', 'EPSG:4326');
		var topRight = ol.proj.transform(ol.extent.getTopRight(extent),'EPSG:3857', 'EPSG:4326');
		var left = bottomLeft[0]-offset;
		var right = topRight[0]+offset;
		var top = topRight[1]+offset;
		var bottom = bottomLeft[1]-offset;

		loadedEnvelope = turf.polygon([[
			[left, bottom],
			[left, top],
			[right, top],
			[right, bottom],
			[left, bottom]
		]]);

		env = left+','+top+','+right+','+bottom;
		
		return env;
}



//add features from a single dataset
var addFeatures = function(id){

	//dsets=14849417046343
	
	currentlyLoading = true;
	
	env = getEnvelope();

	//show loading animation
	//document.getElementById('spotswaiting').style.display="block";

	//$('#spotswaiting').css('display', 'block');
	$('#spotswaiting').show();
	//$('#spotsProgressMessage').html('Getting spots from server.');
	//$('#spotsProgressMessage').show();
	

	$.getJSON('searchspots.json?env='+env+'&dsets='+id,function(result){
		
		//$('#spotsProgressMessage').html('Adding spots to map.');
		//$('#spotsProgressMessage').show();
		
		//$('#spotsProgressInnerBar').css('width', '0%');
		//$('#spotsProgressBar').show();
		
		//console.log(result);
		
		//add new features to loadedfeatures.features
		
		if(loadedFeatures==""){
			loadedFeatures = {};
			loadedFeatures.features = [];
			loadedFeatures.datasets = [];
			loadedFeatures.tags = [];
			loadedFeatures.relationships = [];
			loadedFeatures.image_basemaps = [];
		}

		//add features to loadedFeatures
		_.each(result.features, function(newfeat){
			loadedFeatures.features.push(newfeat);
		});
		_.each(result.datasets, function(newds){
			loadedFeatures.datasets.push(newds);
		});
		_.each(result.tags, function(newtag){
			newtag.datasetid=id;
			loadedFeatures.tags.push(newtag);
		});
		_.each(result.relationships, function(newrel){
			newrel.datasetid=id;
			loadedFeatures.relationships.push(newrel);
		});
		_.each(result.image_basemaps, function(newib){
			newib.datasetid=id;
			loadedFeatures.image_basemaps.push(newib);
		});
		
		console.log(loadedFeatures);

		saveIdsToNames();
		
		updateMap();
		
		//document.getElementById('spotswaiting').style.display="none";
		
		currentlyLoading = false;
		
		if(result.envelope!=""){
			zoomToPoly(result.envelope);
			console.log(result.envelope);
		}
	});
		

}




//fetch features from database
var loadFeatures = function(){

	if((loadedFeatures=="" || envelopeOutside()) && !currentlyLoading){
	
		currentlyLoading = true;
		
		env = getEnvelope();

		featureLayer.getLayers().clear();

		//show loading animation
		document.getElementById('spotswaiting').style.display="block";

		$.getJSON('searchspots.json?env='+env,function(result){
			loadedFeatures = result;
			
			saveIdsToNames();
			
			document.getElementById('spotswaiting').style.display="none";

			updateMap();
			currentlyLoading = false;
		});
		
	}else{

		updateMap();
	}
}

var openSideBar = function(){
	$(".sidebar.right").trigger("sidebar:open");
}

var closeSideBar = function(){
	$("#sidebarquery").hide();
	$(".sidebar.right").trigger("sidebar:close");
}

var toggleSideBar = function(){
	$(".sidebar.right").trigger("sidebar:toggle");
}



var getCurrentSpot = function(){
	return new Promise(function(resolve, reject){
		currentSpot="";
		_.each(loadedFeatures.features, function (spot) {
			if(spot.properties.id == clickedMapFeature){
				currentSpot = spot;
			}	
		});
		resolve();
	});
}

//get info for current spot and tab and update UI
var updateSidebar = function(){

	switchToSpotDiv();

	$("#sidebar_spot_name").text(spotNames[clickedMapFeature]);
	
	activeTabName = tab_names[$( "#tabs" ).tabs( "option" ).active];
	activeTabId = tab_ids[$( "#tabs" ).tabs( "option" ).active];

	if(activeTabName=="spot"){
		updateSpotTab();
	}else if(activeTabName=="orientations"){
		updateOrientationsTab();
	}else if(activeTabName=="_3d_structures"){
		update3DStructuresTab();
	}else if(activeTabName=="images"){
		updateImagesTab();
	}else if(activeTabName=="tags"){
		updateTagsTab();
	}else if(activeTabName=="samples"){
		updateSamplesTab();
	}else if(activeTabName=="other_features"){
		updateOtherFeaturesTab();
	}else if(activeTabName=="nesting"){
		updateNestingTab();
	}else{
		$("#"+activeTabId).text(activeTabName+' '+Math.random()+' '+currentSpot.properties.name);
	}

}

var cgetImageInfo = function(imageid){
	var im = new Image();
	im.src = 'https://strabospot.org/mapimage/'+imageid+'.jpg';
	
	baseMap.height = im.height;
	baseMap.width = im.width;
	baseMap.id = imageid;
	
	baseMap.height = 55;
	baseMap.width = 100;
}

var getImageInfo = function(imageid){
    return new Promise(function(resolve, reject){

        var im = new Image()

        im.src = 'https://strabospot.org/mapimage/'+imageid+'.jpg';

        im.onload = function(){
        	//console.log("here: "+im.width+' '+im.height);
			baseMap.height = im.height;
			baseMap.width = im.width;
			baseMap.id = imageid;

            resolve(imageid);
        }
        im.onerror = function(){
            reject(imageid);
        }

    })
}

var switchToMainMap = function(){

	return new Promise(function(resolve, reject){
	
		crumbs = [];
		lastMapId = "0";
		baseMapActive = false;
		currentBasemapId = 0;
		$("#back_map").hide();
		$("#map_query").show();
		$("#map_home").show();

		removeSelectedSymbol(map);
		map.removeLayer(baseLayers);
		map.removeLayer(datasetPointsLayer);
		map.removeLayer(datasetsLayer);
		map.removeLayer(featureLayer);
		map.removeLayer(ibfeatureLayer);
		map.removeLayer(imageBasemapLayer);

		var projection = 'EPSG:3857';
		var center = [-11000000, 4600000];
		var zoom = 5;
		var minZoom = 5;
	
		map.setView(mapView);
	
		map.addLayer(baseLayers);
		map.addLayer(datasetPointsLayer);
		map.addLayer(datasetsLayer);
		map.addLayer(featureLayer);

		layerSwitcher.renderPanel();

		setSelectedSymbol(map, activeGeometry);

		clickedMapFeature = activeId;

		getCurrentSpot().then(function(){
			updateSidebar();
			//openSideBar();
			zoomToSavedExtent();
			resolve();
		});
	
	});
	
}

var goBack = function(){

	if(crumbs.length > 1){
		crumbs.pop();
		switchToImageBasemap(crumbs.pop());
	}else{
		switchToMainMap();
	}
}

var switchToImageBasemap = function(imageid){

	return new Promise(function(resolve, reject){
	
		if(!baseMapActive){
			saveExtent();
		}
	
		crumbs.push(imageid);
	
		currentBasemapId = imageid;

		$("#back_map").show();
		$("#map_query").hide();
		$("#map_home").hide();


		baseMapActive = true;
	
		closeSideBar();
	
		getImageInfo(imageid).then(function(promiseimageid){

			var ibextent = [0, 0, baseMap.width, baseMap.height];
			var ibprojection = new ol.proj.Projection({
				'code': 'map-image',
				'units': 'pixels',
				'extent': ibextent
			});
			var ibcenter = ol.extent.getCenter(ibextent);
			var ibzoom = 2;
			var ibminZoom = 1;

			var ibView = new ol.View({
			'projection': ibprojection,
			'center': ibcenter,
			'zoom': ibzoom,
			'minZoom': ibminZoom
			});

			removeSelectedSymbol(map);
			map.removeLayer(baseLayers);
			map.removeLayer(datasetPointsLayer);
			map.removeLayer(datasetsLayer);
			map.removeLayer(featureLayer);
			map.removeLayer(ibfeatureLayer);
			map.removeLayer(imageBasemapLayer);

			map.setView(ibView);

			imageBasemapLayer = new ol.layer.Image({
			  'source': new ol.source.ImageStatic({
				'attributions': [
				  new ol.Attribution({
					'html': '&copy; <a href="">Need image source here.</a>'
				  })
				],
				'url': 'https://strabospot.org/mapimage/'+imageid+'.jpg',
				'projection': new ol.proj.Projection({
				  'code': 'map-image',
				  'units': 'pixels',
				  'extent': ibextent
				}),
				'imageExtent': ibextent
			  })
			});
		
			map.addLayer(imageBasemapLayer);

			updateImageBaseMap();

			map.addLayer(ibfeatureLayer);

			layerSwitcher.renderPanel();
			resolve(imageid);
		});
		
	});
}

var idToName = function(id){

	returnid = id;

	if(idsNames[id]){
		returnid=idsNames[id];
	}
	
	return returnid;
}

var saveIdsToNames = function(){
	
	//build idsNames array to hold all names of spots and sub-properties for later retrieval via idToName();
	//idsNames[12341234]="foo";
	
	if(loadedFeatures){
		var spots = loadedFeatures.features;
		_.each(spots, function(spot){
		
			spot=spot.properties;
			
			if(spot.name){
				idsNames[spot.id]=spot.name;
			}
			
			if(spot.images){
				_.each(spot.images, function(image){
					featureSpotIds[image.id]=spot.id;
					featureTypes[image.id]="image";
					if(image.title){
						idsNames[image.id]=image.title;
					}
				});
			}

			if(spot.orientation_data){
				_.each(spot.orientation_data, function(or){
					featureSpotIds[or.id]=spot.id;
					featureTypes[or.id]="orientation";
					if(or.label){
						idsNames[or.id]=or.label;
					}
					if(or.associated_orientation){
						_.each(or.associated_orientation, function(aor){
							featureSpotIds[aor.id]=spot.id;
							featureTypes[aor.id]="orientation";
							if(aor.label){
								idsNames[aor.id]=aor.label;
							}
						});
					}
				});
			}

			if(spot._3d_structures){
				_.each(spot._3d_structures, function(str){
					featureSpotIds[str.id]=spot.id;
					featureTypes[str.id]="3d_structure";
					if(str.label){
						idsNames[str.id]=str.label;
					}
				});
			}

			if(spot.other_features){
				_.each(spot.other_features, function(of){
					featureSpotIds[of.id]=spot.id;
					featureTypes[of.id]="other_feature";
					if(of.label){
						idsNames[of.id]=of.label;
					}
				});
			}
			
			if(spot.samples){
				_.each(spot.samples, function(sample){
					featureSpotIds[sample.id]=spot.id;
					featureTypes[sample.id]="sample";
					if(sample.label){
						idsNames[sample.id]=sample.label;
					}
				});
			}

		});
	}

}

var featureIdToSpotId = function(featureid){

	var spotid=null
	if(featureSpotIds[featureid]){
		spotid = featureSpotIds[featureid];
	}
	return spotid;

}

var featureIdToFeatureType = function(featureid){

	var featuretype=null
	if(featureTypes[featureid]){
		featuretype = featureTypes[featureid];
	}
	return featuretype;

}

var switchToFeature = function(featureid){

	var spotid = featureIdToSpotId(featureid);
	var featureType = featureIdToFeatureType(featureid);
	
	if(spotid){
		switchToSpot(spotid,featureType);
	}

}

var getSpot = function(spotid){
	var thisSpot=null;
	_.each(loadedFeatures.features, function (spot) {
		if(spot.properties.id == spotid){
			thisSpot = spot;
		}	
	});
	return thisSpot;
}

var getSpotFromFeatureId = function(featureid){
	var thisspot = null;
	var spotid = featureIdToSpotId(featureid);
	if(spotid!=null){
		thisspot = getSpot(spotid);
	}
	return thisspot;
}

var switchToSpot = function(spotid,featureType){

	//check if featuretype is passed so we can switch to the right tab 
	var tabnum = 0;
	if(featureType == "image"){
		tabnum = 3;
	}else if(featureType == "orientation"){
		tabnum = 1;
	}else if(featureType == "3d_structure"){
		tabnum = 2;
	}else if(featureType == "other_feature"){
		tabnum = 6;
	}else if(featureType == "sample"){
		tabnum = 5;
	}

	var thisspot = getSpot(spotid);
	
	if(thisspot){
	
		if(spotid){

			clickedMapFeature = spotid;
			//console.log(activeId);
			getCurrentSpot().then(function(promiseimageid){

				if(!thisspot.properties.image_basemap){

					var spotfeature = JSON.parse(JSON.stringify(currentSpot));

					feature = (new ol.format.GeoJSON()).readFeature(spotfeature, {
						'featureProjection': 'EPSG:3857'
					});

					activeGeometry = feature.getGeometry();
					activeId = spotid;
					clickedMapFeature = spotid;

					if(baseMapActive){
						switchToMainMap().then(function(){
							//mapView.fit(activeGeometry, {padding: [170, 50, 30, 150], minResolution: 15});
							if(currentSpot.geometry.type=="Point"){
								mapView.fit(activeGeometry, {padding: [170, 50, 30, 150], minResolution: 5, duration: 500});
							}else{
								mapView.fit(activeGeometry, {padding: [300, 300, 300, 300], constrainResolution: false, minResolution: 20, duration: 500});
							}
							
							$( "#tabs" ).tabs( "option", "active", tabnum );
						});
					}else{
						//console.log("just zoom to feature and select");
						removeSelectedSymbol(map)

						if(currentSpot.geometry.type=="Point"){
							mapView.fit(activeGeometry, {padding: [170, 50, 30, 150], minResolution: 5, duration: 500});

						}else{
							mapView.fit(activeGeometry, {padding: [300, 300, 300, 300], constrainResolution: false, duration: 500});
						}
						
						setSelectedSymbol(map, feature.getGeometry());



						$( "#tabs" ).tabs( "option", "active", tabnum );


						updateSidebar();	
						//openSideBar();
					}
		
				}else{
				
					var spotfeature = JSON.parse(JSON.stringify(currentSpot));

					feature = (new ol.format.GeoJSON()).readFeature(spotfeature);

				
					var thisbaseMapId=thisspot.properties.image_basemap;
			
					if(!baseMapActive || (thisbaseMapId!=currentBasemapId)){

						//console.log(thisspot);
					
						switchToImageBasemap(thisspot.properties.image_basemap).then(function(promiseimageid){
					
							//console.log("switching to image basemap");
							//now create image overlay and open sidebar
							setSelectedSymbol(map, feature.getGeometry());

							$( "#tabs" ).tabs( "option", "active", tabnum );
						
							updateSidebar();
		
							//openSideBar();
					
						});
				
					}else{
				
						console.log('already on the image basemap, just set feature');
						removeSelectedSymbol(map);
						setSelectedSymbol(map, feature.getGeometry());
						$( "#tabs" ).tabs( "option", "active", tabnum );
						updateSidebar();
						//openSideBar();
				
				
					}
		
				}
			
			});
			
		}
	
	}
}

var debugMap = function(){

	var extent = map.getView().calculateExtent(map.getSize());
	var zoom = map.getView().getZoom();

	var offset=0.0;
	var bottomLeft = ol.proj.transform(ol.extent.getBottomLeft(extent),'EPSG:3857', 'EPSG:4326');
	var topRight = ol.proj.transform(ol.extent.getTopRight(extent),'EPSG:3857', 'EPSG:4326');
	var left = bottomLeft[0]-offset;
	var right = topRight[0]+offset;
	var top = topRight[1]+offset;
	var bottom = bottomLeft[1]-offset;
	
	console.log("left: "+left+" right: "+right+" top: "+top+" bottom: "+bottom);
	console.log("zoom: "+zoom);
}

var saveExtent = function(){
	savedCenterZoom.center = map.getView().getCenter();
	savedCenterZoom.zoom = map.getView().getZoom();
}

var zoomToSavedExtent = function(){
	mapView.setCenter(savedCenterZoom.center);
	mapView.setZoom(savedCenterZoom.zoom);
}

var zoomToCenterAndExtent = function(encodedstring){
	var string = atob(encodedstring);
	var newcenter = [];
	var newzoom = 0;
	
	var res = string.split("x");
	
	newcenter[0]=Number(res[0]);
	newcenter[1]=Number(res[1]);
	newzoom = res[2];

	mapView.setCenter(newcenter);
	mapView.setZoom(newzoom);

} 

var showStaticUrl = function(){
	
	var getcenter = map.getView().getCenter();
	var getzoom = map.getView().getZoom();

	console.log(getcenter);

	var encodedstring = btoa(getcenter.join("x")+"x"+getzoom);
	
	console.log(encodedstring);
	
	var thishtml = "<div style='padding:20px 20px 20px 20px;'>";
	thishtml += "<h3>Static URL</h3>";
	thishtml += "<div>This URL will link directly to the zoom/center of the current map view:</div>";
	thishtml += "<div style='padding-top:5px;'>https://strabospot.org/search?c="+encodedstring+"</div>";
	thishtml += "<div>&nbsp;</div>";
	thishtml += "</div>";
	
	$.featherlight(thishtml);
	
}

var hideEffect = "blind";//blind,drop,explode,

var switchToQueryDiv = function(){

	if(sidebarState=="open"){
	
		if($("#sidebarspot").is(':visible')){
			$( "#sidebarspot" ).effect( hideEffect, {}, 300, function(){
				setTimeout(function() {
					$( "#sidebarquery" ).removeAttr( "style" ).hide().fadeIn();
				}, 200 );
			});
		}
	
	}else{
	
		$("#sidebarspot").hide();
		$("#sidebarquery").show();
	
		openSideBar();
	
	}
}

var switchToSpotDiv = function(){
	
	if(sidebarState=="open"){

		if($("#sidebarquery").is(':visible')){
			$( "#sidebarquery" ).effect( hideEffect, {}, 300, function(){
				setTimeout(function() {
					$( "#sidebarspot" ).removeAttr( "style" ).hide().fadeIn();
				}, 200 );
			});
		}

	}else{
	
		$("#sidebarquery").hide();
		$("#sidebarspot").show();
	
		openSideBar();
	}
}

var toggleSpotQuery = function(){

	if($("#sidebarquery").is(':visible')){
		if (typeof clickedMapFeature === 'undefined' || !clickedMapFeature) {
			closeSideBar();
			$("#sidebarquery").hide();
		}else{
			switchToSpotDiv();
		}
	}else{
		switchToQueryDiv();
	}
}

var turnOnUpdateButton = function(){

	updateQueryButton();
	updateClearButton();
}

var updateQueryButton = function(){ //show button if query has changed
	if(buildDatasetsURL()!=datasetsurl){
		showQueryButton();
	}else{
		hideQueryButton();
	}	
}

var updateClearButton = function(){ //show clear button if any query options are set.
	if(querySet()){
		showClearButton();
	}else{
		hideClearButton();
	}
}

var querySet = function(){
	isset = false;
	
	if($("#query_has_image").is(":checked")){
		isset = true;
	}
	
	if($("#query_has_orientation").is(":checked")){
		isset = true;
	}
	
	if($("#query_has_sample").is(":checked")){
		isset = true;
	}
	
	if($("#query_has_3d_structure").is(":checked")){
		isset = true;
	}
	
	if($("#query_has_other_features").is(":checked")){
		isset = true;
	}

	return isset;
}

var clearQuery = function(){

	$('#query_has_image').attr('checked', false);
	$('#query_has_orientation').attr('checked', false);
	$('#query_has_sample').attr('checked', false);
	$('#query_has_3d_structure').attr('checked', false);
	$('#query_has_other_features').attr('checked', false);

	updateQueryButton();
	hideClearButton();
}

var showQueryButton = function(){
	$("#querybuttondiv").html('<button id="querybutton" class="querysubmit" onClick="updateQuery();"><span>Update</span></button>');
}

var hideQueryButton = function(){
	$("#querybuttondiv").html('<button id="greyquerybutton" class="greyquerysubmit"><span>Update&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>');
}

var showClearButton = function(){
	$("#clearbuttondiv").html('<button id="clearbutton" class="querysubmit" onClick="clearQuery();"><span>Clear</span></button>');
}

var hideClearButton = function(){
	$("#clearbuttondiv").html('<button id="greyclearbutton" class="greyquerysubmit"><span>Clear&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>');
}

var updateQuery = function(){

	hideQueryButton();
	$.fancybox.close();
	closeSideBar();
	removeSelectedSymbol(map);

	datasetsurl = buildDatasetsURL();
	
	//updateMapDiv();
	
	updateMap();

	
	if(lastdatasetsurl != datasetsurl){
	
		//console.log("rebuild datasetpoints layer here (datasetsurl: "+datasetsurl+")");

		rebuildDatasetsLayer();

		lastdatasetsurl = datasetsurl;
	}else{
		//console.log("Not Changed (datasetsurl: "+datasetsurl+")");
	}

	var resolution = map.getView().getResolution();
	var zoom = map.getView().getZoom();
	if(!baseMapActive){
	
		if(resolution < zoomres){

			if(querySet()){
				//query set and we're zoomed in. Let's zoom to features here

				if(fitFeatures.length > 0){
				
					//zoomToFitFeatures();
				
				}else{
					//console.log("no matching spots");
				}
				
			}
		}
	}
}

var zoomToFitFeatures = function() {
	if(fitFeatures.length > 0){
		var fc = turf.featureCollection(fitFeatures);
		var newSpot = {};
		if (fitFeatures.length == 1) newSpot = JSON.parse(JSON.stringify(fitFeatures[0]));
		else if (fitFeatures.length == 2
		&& fitFeatures[0].geometry.type === 'Point' && fitFeatures[1].geometry.type === 'Point') {
		newSpot = turf.lineString([fitFeatures[0].geometry.coordinates, fitFeatures[1].geometry.coordinates]);
		}
		else newSpot = turf.convex(fc);

		// Buffer the polygon
		newSpot = turf.buffer(newSpot, 5, 'meters');

		var newfeature = (new ol.format.GeoJSON()).readFeature(newSpot, {dataProjection: 'EPSG:4326',featureProjection: 'EPSG:3857'});

		var newgeometry = newfeature.getGeometry();

		mapView.fit(newgeometry, {duration: 1000});

	}
}

var buildGetSearchVars = function(){

	var delim = "";	

	var newurl = "";

	if($("#query_has_image").is(":checked")){
		newurl += delim + 'hasimage=yes';
		delim = '&';
	}

	if($("#query_has_orientation").is(":checked")){
		newurl += delim + 'hasorientation=yes';
		delim = '&';
	}

	if($("#query_has_sample").is(":checked")){
		newurl += delim + 'hassample=yes';
		delim = '&';
	}

	if($("#query_has_3d_structure").is(":checked")){
		newurl += delim + 'has3dstructure=yes';
		delim = '&';
	}

	if($("#query_has_other_features").is(":checked")){
		newurl += delim + 'hasotherfeature=yes';
		delim = '&';
	}
	
	return newurl;

}

var buildDatasetsURL= function(){

	var newurl = buildGetSearchVars();

	newurl = "searchdatasets.json?"+newurl;

	return newurl;
}

var spotFitsQuery = function(spot){

	//console.log(spot);
	
	var spotfits = true;

	if($("#query_has_image").is(":checked")){
		if(!spot.properties.images){
			spotfits = false;
		}
	}

	if($("#query_has_orientation").is(":checked")){
		if(!spot.properties.orientation && !spot.properties.orientation_data){
			spotfits = false;
			//console.log(spot.properties.orientation_data);
		}else{
			//console.log("spot does have orientation_data");
		}
	}

	if($("#query_has_sample").is(":checked")){
		if(!spot.properties.samples){
			spotfits = false;
		}
	}

	if($("#query_has_3d_structure").is(":checked")){
		if(!spot.properties._3d_structures){
			spotfits = false;
		}
	}

	if($("#query_has_other_features").is(":checked")){
		if(!spot.properties.other_features){
			spotfits = false;
		}
	}

	return spotfits;

}

var expandDataset = function(feature){
	expandedDatasets.push(feature);
	mapDataset(feature);
	updateShownDatasets();
}

var mapDataset = function(feature){
	id = feature.get('id');
	addFeatures(id);
}

var closeDataset = function(id){
	
	//close dataset from map and list box
	removeDatasetFromArray(id);
	removeDatasetFromLoadedFeatures(id);
	restoreDatasetPoint(id);
	
	
	updateShownDatasets();

}

var restoreDatasetPoint = function(id){
	
	console.log("restoreDatasetPoints alldatasets:");
	console.log(allDatasets);
	
	_.each(allDatasets, function(ds){
		thisid = ds.get('id');
		if(thisid==id){
			console.log("check to see if feature should be shown"); //... if it exists in datasetpointssource
			datasetPointsSource.addFeature(ds);
		}
	});
}

var removeDatasetFromArray = function(id){
	var newArray = [];
	_.each(expandedDatasets, function(ds){
		thisid = ds.get('id');
		if(thisid!=id){
			newArray.push(ds);
		}
	});
	
	expandedDatasets = newArray;
}

var removeDatasetFromLoadedFeatures = function(id){
	var newLoadedFeatures = {};
	newLoadedFeatures.features = [];
	newLoadedFeatures.datasets = [];
	newLoadedFeatures.tags = [];
	newLoadedFeatures.relationships = [];
	newLoadedFeatures.image_basemaps = [];

	_.each(loadedFeatures.features, function(feat){
		if(feat.properties.datasetid!=id){
			newLoadedFeatures.features.push(feat);
		}
	});

	_.each(loadedFeatures.datasets, function(ds){
		if(ds.id!=id){
			newLoadedFeatures.datasets.push(ds);
		}
	});

	_.each(loadedFeatures.tags, function(newtag){
		if(newtag.datasetid!=id){
			newLoadedFeatures.tags.push(newtag);
		}
	});

	_.each(loadedFeatures.relationships, function(newrel){
		if(newrel.datasetid!=id){
			newLoadedFeatures.tags.push(newrel);
		}
	});

	_.each(loadedFeatures.image_basemaps, function(newib){
		if(newib.datasetid!=id){
			newLoadedFeatures.image_basemaps.push(newib);
		}
	});

	loadedFeatures = newLoadedFeatures;
	updateMap();

	removeSelectedSymbol(map);
	clickedMapFeature = undefined;
	closeSideBar();

	console.log(loadedFeatures);
}

var updateShownDatasets = function(){
	if(expandedDatasets.length > 0){
		//create html and show div
		var thishtml = '';
		_.each(expandedDatasets, function(ds){
			name = ds.get('name');
			id = ds.get('id');
			count = ds.get('count');
			owner = ds.get('owner');
			projectname = ds.get('projectname');
			
			//thishtml += '<div class="datasetRow" onclick="closeDataset(\''+id+'\');" ><img src="includes/images/red_close_button.png" width="10px" height="10px" /> '+name+' ('+count+' spots)</div>';
			thishtml += '<div><img class = "datasetRow" src="includes/images/red_close_button.png" width="10px" height="10px" onclick="closeDataset(\''+id+'\');" /> '+projectname + ' - ' + name+' ('+count+' spots) (Owned by '+owner+')</div>';
		});
		$("#openDatasetsList").html(thishtml);
		$("#openDatasets").show();
		$("#download_map").show();
	}else{
		//clear html and hide div
		//#openDatasets && #openDatasetsList
		$("#openDatasetsList").html("");
		$("#openDatasets").hide();
		$("#download_map").hide();
	}
}

var updateAllDatasets = function(){
	//updates allDatasets array with all dataset features from datasetPointsSource
	allDatasets = datasetPointsSource.getFeatures();
	//console.log(allDatasets);
}

var randomString = function(){
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < 10; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}

var zoomToPoly = function(inwkt){
	var newformat = new ol.format.WKT();

	var inpoly = (new ol.format.WKT()).readFeature(inwkt, {dataProjection: 'EPSG:4326',featureProjection: 'EPSG:3857'});
	
	/*
	var inpoly = (new ol.format.WKT()).readFeature(inwkt, {
						'featureProjection': 'EPSG:4326'
					});

	var inpoly = (format.readFeature(inwkt, {
						'featureProjection': 'EPSG:3857'
					});
	
	var inpoly = newformat.readFeature(inwkt);
	*/
	
	var newgeometry = inpoly.getGeometry();
	mapView.fit(newgeometry, {duration: 1000});
}

var openDownloadWindow = function(){
	
	console.log(expandedDatasets);
	
	var thishtml="";
	_.each(expandedDatasets, function(ds){
		name = ds.get('name');
		id = ds.get('id');
		count = ds.get('count');
		
		thishtml+="<div><input type=\"checkbox\" name=\"dldataset\" class=\"dldatasets\" value=\""+id+"\" checked=\"checked\"> "+name+" ("+count+" spots)</div>";
		$("#downloadDatasets").html(thishtml);
	});
	
	$.fancybox.open(
		{
			src  : '#downloadOptionsWindow',
			type : 'inline',
			opts : {
				afterShow : function( instance, current ){
					console.info( 'done!' );
				}
			}
		}
	);
}

var downloadData = function(datatype){
	var boxeschecked=false;
	var dsids = [];
	$('.dldatasets:checked').each(function(){
		dsids.push($(this).val());
		boxeschecked=true;
	});
	if(boxeschecked){
		dsids=dsids.join();
		var range = $("#downloadscope:checked").val();	//all -or- envelope
		var envelope = getCurrentViewEnvelope();
		console.log("download "+datatype+" with ids=(s): "+dsids+" with range: "+range+" and envelope: "+envelope);

		//need to add search query too
		var getvars = buildGetSearchVars();
		
		//console.log("search vars: "+getvars);

		window.location='/searchdownload?type='+datatype+'&dsids='+dsids+'&range='+range+'&envelope='+envelope+'&'+getvars;


	}else{
		alert("Error! You must choose at least one dataset.");
	}
	
}

var zoomHome = function(){
	mapView.fit(outgeometry, {duration: 1000});
}