/*
This file contains Javascript for the StraboSpot map search Interface. It is derived
from the StraboSpot ionic app.

Author: Jason Ash (jasonash@ku.edu) 04/19/2017
*/

//global vars
	let spotNames = [];
	let layerStates = [];
	let iblayerStates = [];
	let loadedFeatures = "";
	let loadedEnvelope = "";
	let currentSpot = "";
	let savedCenterZoom = {};
//var activeFeature = {};
	let activeId = 0;
	let activeGeometry = {};

	let idsNames = {};
	let featureSpotIds = {};
	let featureTypes = {};

	let currentlyLoading = false;

	let currentBasemapId = 0;

	let activeTabName = "";
	let activeTabId = "";
	let baseMap = {};
	let baseMapActive = false;
	let crumbs = [];

	let fitFeatures = [];

	let expandedDatasets = [];		//datasets that are expanded (into spots) on the search interface
	let alldatasets = [];			//all datasets currently pulled from server (for putting back on map);

	let tab_ids = [];
tab_ids[0]="spot_tab";
tab_ids[1]="notes_tab";
tab_ids[2]="orientations_tab";
tab_ids[3]="_3d_structures_tab";
tab_ids[4]="images_tab";
tab_ids[5]="nesting_tab";
tab_ids[6]="samples_tab";
tab_ids[7]="other_features_tab";
tab_ids[8]="tags_tab";
tab_ids[9]="igmet_tab";
tab_ids[10]="strat_section_tab";
tab_ids[11]="sed_lithologies_tab";
tab_ids[12]="sed_bedding_tab";
tab_ids[13]="sed_structures_tab";
tab_ids[14]="sed_diagenesis_tab";
tab_ids[15]="sed_fossils_tab";
tab_ids[16]="sed_interpretations_tab";


	let tab_names = [];
tab_names[0]="spot";
tab_names[1]="notes";
tab_names[2]="orientations";
tab_names[3]="_3d_structures";
tab_names[4]="images";
tab_names[5]="nesting";
tab_names[6]="samples";
tab_names[7]="other_features";
tab_names[8]="tags";
tab_names[9]="igmet";
tab_names[10]="strat_section";
tab_names[11]="sed_lithologies";
tab_names[12]="sed_bedding";
tab_names[13]="sed_structures";
tab_names[14]="sed_diagenesis";
tab_names[15]="sed_fossils";
tab_names[16]="sed_interpretations";

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

	let mylayers = map.getLayers();
	mylayers.forEach(function (thislayer) {
		if(thislayer.get('name')=='selectedHighlightLayer'){
			map.removeLayer(thislayer);
		}
	});

}

// Add a feature to highlight selected Spot
// Encompassing orange circle for a point, orange stroke for a line, and orange fill for a polygon
function setSelectedSymbol(map, geometry) {
	let selected = new ol.Feature({
		geometry: geometry
	});

	let style = {};
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

	let selectedHighlightSource = new ol.source.Vector({
		features: [selected]
	});

	selectedHighlightLayer = new ol.layer.Vector({
		name: 'selectedHighlightLayer',
		source: selectedHighlightSource,
		style: style
	});

	map.addLayer(selectedHighlightLayer);

}
















// Add a feature to highlight selected Dataset
function setSelectedDataset(map, longitude, latitude) {
	let selected = new ol.Feature({
		geometry: new ol.geom.Point(ol.proj.transform([longitude, latitude], 'EPSG:4326', 'EPSG:3857'))
	});

	let style = {};

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


	let selectedHighlightSource = new ol.source.Vector({
		features: [selected]
	});

	selectedHighlightLayer = new ol.layer.Vector({
		name: 'selectedHighlightLayer',
		source: selectedHighlightSource,
		style: style
	});

	map.addLayer(selectedHighlightLayer);

}





















	let hideLayer = function(layername){
	let mylayers = map.getLayers();
	mylayers.forEach(function (thislayer) {
		if(thislayer.get('name')==layername){
			thislayer.setVisible(false);
		}
	});
}

	let showLayer = function(layername){
	let mylayers = map.getLayers();
	mylayers.forEach(function (thislayer) {
		if(thislayer.get('name')==layername){
			thislayer.setVisible(true);
		}
	});
}

	let envelopeOutside = function() { //check to see if current view's data is still valid

	let extent = map.getView().calculateExtent(map.getSize());
	let bottomLeft = ol.proj.transform(ol.extent.getBottomLeft(extent),'EPSG:3857', 'EPSG:4326');
	let topRight = ol.proj.transform(ol.extent.getTopRight(extent),'EPSG:3857', 'EPSG:4326');
	let left = bottomLeft[0];
	let right = topRight[0];
	let top = topRight[1];
	let bottom = bottomLeft[1];

	if(loadedEnvelope!=""){

	let lb_point = turf.point([left, bottom]);
	let lt_point = turf.point([left, top]);
	let rt_point = turf.point([right, top]);
	let rb_point = turf.point([right, bottom]);


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
	let updateMap = function() {

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

		console.log('Loaded Features Exist.');

		datasetsLayer.set('title','Datasets');
		featureLayer.set('title','Spots');

		//add dummy layers to layer switcher to allow turning off datasets
	let datasets = loadedFeatures.datasets;
		_.each(datasets, function(d){

	let datasetLayer = new ol.layer.Tile({
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

	let spots = loadedFeatures.features;

	let totalspotcount = spots.length;
	let currentpercent = 0;

		//create orientation parameters
	let currentspotcount = 1;
	let mappedFeatures = [];
		_.each(spots, function (spot) {
			if(!spot.properties.image_basemap && !spot.properties.strat_section_id){
				if(spot.properties.name!=""){
					spotNames[spot.properties.id]=spot.properties.name;
				}else{
					spotNames[spot.properties.id]=spot.properties.id;
				}
				if(layerStates[spot.properties.datasetid]!="no" ){	//look to see whether layer is turned on
					if ((spot.geometry.type === 'Point' || spot.geometry.type === 'MultiPoint') && spot.properties.orientation_data) {
						_.each(spot.properties.orientation_data, function (orientation) {
	let feature = JSON.parse(JSON.stringify(spot));
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
	let featureGroup = _.groupBy(fitFeatures, function (feature) {
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
	let spotTypeLayer = {
					'type': 'FeatureCollection',
					'features': featureGroup[key],
					'properties': {
					'name': key + ' (' + featureGroup[key].length + ')'
					}
				};

	let newlayer = geojsonToVectorLayer(spotTypeLayer, map.getView().getProjection());

				featureLayer.getLayers().push(newlayer);

			}
		}

		layerSwitcher.renderPanel();


	}else{

		featureLayer.set('title',null);
		datasetsLayer.set('title',null);
		layerSwitcher.renderPanel();
		console.log('Loaded Features dont Exist.');
	}

	$('#spotswaiting').hide();

}

//put features from ibloadedFeatures onto map
	let updateImageBaseMap = function() {

	setCurrentTypeVisibility(map);

	ibfeatureLayer.getLayers().clear();

	if(loadedFeatures!="" && loadedFeatures!=null){

		ibfeatureLayer.set('title','Spots');

	let spots = loadedFeatures.features;

	let mappedFeatures = [];
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
	let feature = JSON.parse(JSON.stringify(spot));
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
	let featureGroup = _.groupBy(mappedFeatures, function (feature) {
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
	let spotTypeLayer = {
					'type': 'FeatureCollection',
					'features': featureGroup[key],
					'properties': {
					'name': key + ' (' + featureGroup[key].length + ')'
					}
				};

				// Add the feature collection layer to the map
	let newlayer = geojsonToVectorLayer(spotTypeLayer, map.getView().getProjection());

				ibfeatureLayer.getLayers().push(newlayer);

			}
		}

		layerSwitcher.renderPanel();

	}else{

		ibfeatureLayer.set('title',null);
		layerSwitcher.renderPanel();
	}

}

	let datasetGetText = function(feature, resolution) {

	let maxResolution = 10000;
	let text = feature.get('projectname')+' - '+feature.get('name')+'\n('+feature.get('count')+' spots)';

	if (resolution > maxResolution) {
		text = '';
	}

	return text;
};

	let datasetPointStyleFunction = function() {
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

	let bkupdatasetPointStyleFunction = function() {
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

	let spotStyleFunction = function() {
	return function(feature, resolution) {

	let thistype = feature.getGeometry().getType();

		if(thistype=="Point"){

	let style = new ol.style.Style({
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

	let style = new ol.style.Style({
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

	let style = new ol.style.Style({
			  stroke: new ol.style.Stroke({
				color: 'black',
				width: 2
			  })
			});

			return style;

		}

	};
};

	let getFeatureTypeLabel = function(feature_type){
	let feature_types = JSON.parse('{"groove_marks":"groove marks","parting_lineat":"parting lineations","magmatic_miner_1":"magmatic mineral alignment","xenolith_encla":"xenolith/enclave alignment","intersection":"intersection","pencil_cleav":"pencil cleavage","mineral_align":"mineral alignment","deformed_marke":"deformed marker","rodding":"rodding","boudin":"boudin","mullions":"mullions","fold_hinge":"fold hinge","striations":"striations","slickenlines":"slickenlines","slickenfibers":"slickenfibers","mineral_streak":"mineral streaks","vorticity_axis":"vorticity axis","flow_transport":"flow/transport direction","vergence":"vergence","vector":"vector","other":"other","bedding":"bedding","contact":"contact","foliation":"foliation","fracture":"fracture","vein":"vein","fault":"fault","shear_zone":"shear zone","shear_zone_bou":"shear zone boundary","fold_axial_surface":"fold axial surface","plane_of_boudinage":"plane of boudinage","plane_of_mullions":"plane of mullions","stratigraphic":"stratigraphic","intrusive":"intrusive body","injection":"injection structure","vein_array":"vein array","zone_fracturin":"zone of fracturing","zone_faulting":"zone of faulting","damage_zone":"damage zone","alteration_zone":"alteration zone","enveloping_surface":"enveloping surface","unknown":"unknown","tectonite":"tectonite","igneous_migmat":"igneous/migmatite","soft_sediment_":"soft sediment deformation","other_fabric":"other fabric","anticline":"anticline","syncline":"syncline","monocline":"monocline","antiform":"antiform","synform":"synform","s_fold":"s-fold","z_fold":"z-fold","m_fold":"m-fold","sheath":"sheath","single_layer_b":"single-layer buckle","ptygmatic":"ptygmatic","crenulation":"crenulation","interfolial":"interfolial","boudinage":"boudinage","mullion":"mullion","lobate_cuspate":"lobate-cuspate","other_3d_structure":"other 3D structure","ellipsoidal_data":"ellipsoidal data","non_ellipsoidal_data":"non-ellipsoidal data","elliptical_data":"elliptical data"}');
	return feature_types[feature_type];
}

	let getTraceTypeLabel = function(feature_type){
	let feature_types = JSON.parse('{"contact":"contact","geologic_struc":"geologic structure","geomorphic_fea":"geomorphic feature","anthropenic_fe":"anthropogenic feature","scale_bar":"scale bar","geological_cross_section":"geological cross section","geophysical_cross_section":"geophysical cross section","stratigraphic_section":"stratigraphic section","other_feature":"other feature"}');
	return feature_types[feature_type];
}

	let getSurfaceFeatureTypeLabel = function(feature_type){
	let feature_types = JSON.parse('{"rock_unit":"rock unit","contiguous_outcrop":"contiguous outcrop","geologic_structure":"geologic structure","geomorphic_feature":"geomorphic feature","anthropogenic_feature":"anthropogenic feature","extent_of_mapping":"extent of mapping","extent_of_biological_marker":"extent of biological marker","subjected_to_similar_process":"subjected to similar process","gradients":"gradients","other":"other"}');
	return feature_types[feature_type];
}

// Save the current visibility for each feature type in the Spots Feature Layer
	let typeVisibility = [];
function setCurrentTypeVisibility(map) {
	map.getLayers().forEach(function (layer) {
		if (layer.get('name') === 'featureLayer') {
			layer.getLayers().forEach(function (typeLayer) {
	let type = typeLayer.get('title').split(' (')[0];
				typeVisibility[type] = typeLayer.get('visible');
			});
		}
	});

}

function toRadians(deg) {
	return deg * (Math.PI / 180);
}

function getSymbolPath(feature_type, orientation, orientation_type) {

	let symbols = {
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
	let default_symbol = symbols.default_point;
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

function hexToRGB(hex, alpha) {
	let r = parseInt(hex.slice(1, 3), 16),
		g = parseInt(hex.slice(3, 5), 16),
		b = parseInt(hex.slice(5, 7), 16);

	if (alpha) {
		return "rgba(" + r + ", " + g + ", " + b + ", " + alpha + ")";
	} else {
		return "rgb(" + r + ", " + g + ", " + b + ")";
	}
}

//hexToRGB('#FF0000', 0.5);

// creates a ol vector layer for supplied geojson object
function geojsonToVectorLayer(geojson, projection, tags) {
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
			offsetX: 30, //15
			offsetY: -5,
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
	let color = '#663300';
	let width = 2;
	let lineDash = [1, 0];

		if (feature.get('trace')) {
	let trace = feature.get('trace');

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
	let feature_type = 'none';
	let rotation = 0;
	let symbol_orientation = 0;
	let orientation_type = 'none';
	let orientation = feature.get('orientation');
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

	let thisId = feature.get('id');

	let color = 'rgba(0, 0, 255, 0.4)';			 // blue

		if (feature.get('surface_feature')) {
	let surfaceFeature = feature.get('surface_feature');
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

		//fix color here based on tags if exist
		//color = '#FF0000';
		//hexToRGB('#FF0000', 0.5);

		_.each(loadedFeatures.tags, function(thistag){
			if(_.contains(thistag.spots, thisId)){
				if(thistag.color && thistag.color != ""){
					color = hexToRGB(thistag.color, 0.4);
				}
			}
		});



		return new ol.style.Fill({
			'color': color
		});
	}

	// Set styles for points, lines and polygon and groups
	function styleFunction(feature, resolution) {
	let rotation = 0;
	let pointText = feature.get('name');
	let orientation = feature.get('orientation');
		if (orientation) {
			rotation = orientation.strike || orientation.trend || rotation;
			//pointText = orientation.dip || orientation.plunge || pointText;
			pointText = feature.get('name');
		}

	let pointStyle = [
			new ol.style.Style({
				'image': getIconForFeature(feature),
				'text': textStylePoint(pointText.toString(), rotation)
			})
		];
	let lineStyle = [
			new ol.style.Style({
				'stroke': getStrokeStyle(feature),
				'text': textStyle(feature.get('name'))
			})
		];
	let polyText = feature.get('name');
	let polyStyle = [
			new ol.style.Style({
				'stroke': new ol.style.Stroke({
					'color': '#000000',
					'width': 0.5
				}),
				'fill': getPolyFill(feature),
				'text': textStyle(polyText)
			})
		];
	let styles = [];
		styles.Point = pointStyle;
		styles.MultiPoint = pointStyle;
		styles.LineString = lineStyle;
		styles.MultiLineString = lineStyle;
		styles.Polygon = polyStyle;
		styles.MultiPolygon = polyStyle;

		return styles[feature.getGeometry().getType()];
	}

	let features;
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

	let getEnvelope = function(){ //padded
	let extent = map.getView().calculateExtent(map.getSize());

	let offset=0.2;
	let bottomLeft = ol.proj.transform(ol.extent.getBottomLeft(extent),'EPSG:3857', 'EPSG:4326');
	let topRight = ol.proj.transform(ol.extent.getTopRight(extent),'EPSG:3857', 'EPSG:4326');
	let left = bottomLeft[0]-offset;
	let right = topRight[0]+offset;
	let top = topRight[1]+offset;
	let bottom = bottomLeft[1]-offset;

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

	let getCurrentViewEnvelope = function(){ //not padded
	let extent = map.getView().calculateExtent(map.getSize());

	let offset=0.0;
	let bottomLeft = ol.proj.transform(ol.extent.getBottomLeft(extent),'EPSG:3857', 'EPSG:4326');
	let topRight = ol.proj.transform(ol.extent.getTopRight(extent),'EPSG:3857', 'EPSG:4326');
	let left = bottomLeft[0]-offset;
	let right = topRight[0]+offset;
	let top = topRight[1]+offset;
	let bottom = bottomLeft[1]-offset;

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
	let addFeatures = function(id){

	//dsets=14849417046343

	currentlyLoading = true;

	env = getEnvelope();

	//show loading animation
	//document.getElementById('spotswaiting').style.display="block";

	//$('#spotswaiting').css('display', 'block');
	$('#spotswaiting').show();
	//$('#spotsProgressMessage').html('Getting spots from server.');
	//$('#spotsProgressMessage').show();










	querystring = createQueryString();

	$.post( "interfacesearch.php?dsids="+id, querystring, function( result ) {
		console.log('Did dataset search and received:');
		console.log(result);















	//get data from interfacesearch
	//$.getJSON('newsearchspots.json?env='+env+'&dsets='+id,function(result){

		//$('#spotsProgressMessage').html('Adding spots to map.');
		//$('#spotsProgressMessage').show();

		//$('#spotsProgressInnerBar').css('width', '0%');
		//$('#spotsProgressBar').show();


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

		console.log("loadedFeatures:");
		console.log(loadedFeatures);

		saveIdsToNames();

		updateMap();

		//document.getElementById('spotswaiting').style.display="none";

		currentlyLoading = false;

		if(result.envelope!=""){
			//zoomToPoly(result.envelope);
			console.log(result.envelope);
		}

		if(result.centroid!=""){
			console.log("centroid:");
			console.log(result.centroid);
			longitude = result.centroid.longitude;
			latitude = result.centroid.latitude;
			console.log("Longitude: "+longitude);
			console.log("Latitude: "+latitude);

			//check current zoom
	let currentZoom = map.getView().getZoom();
			if(currentZoom < 10){
				panToCoords(longitude, latitude, 10);
			}else{
				panToCoords(longitude, latitude, currentZoom);
			}

		}

	});


}






//add features from a single dataset
	let OLDDDDDDDDDDDaddFeatures = function(id){

	//dsets=14849417046343

	currentlyLoading = true;

	env = getEnvelope();

	//show loading animation
	//document.getElementById('spotswaiting').style.display="block";

	//$('#spotswaiting').css('display', 'block');
	$('#spotswaiting').show();
	//$('#spotsProgressMessage').html('Getting spots from server.');
	//$('#spotsProgressMessage').show();


	$.getJSON('newsearchspots.json?env='+env+'&dsets='+id,function(result){

		//$('#spotsProgressMessage').html('Adding spots to map.');
		//$('#spotsProgressMessage').show();

		//$('#spotsProgressInnerBar').css('width', '0%');
		//$('#spotsProgressBar').show();


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
	let loadFeatures = function(){

	if((loadedFeatures=="" || envelopeOutside()) && !currentlyLoading){

		currentlyLoading = true;

		env = getEnvelope();

		featureLayer.getLayers().clear();

		//show loading animation
		document.getElementById('spotswaiting').style.display="block";

		$.getJSON('newsearchspots.json?env='+env,function(result){
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

	let openSideBar = function(){
	$(".sidebar.right").trigger("sidebar:open");
}

	let closeSideBar = function(){
	$("#sidebarquery").hide();
	$(".sidebar.right").trigger("sidebar:close");
}

	let toggleSideBar = function(){
	$(".sidebar.right").trigger("sidebar:toggle");
}



	let getCurrentSpot = function(){
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
	let updateSidebar = function(){

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
	}else if(activeTabName=="notes"){
		updateNotesTab();
	}else if(activeTabName=="igmet"){
		updateIgMetTab();

	}else if(activeTabName=="strat_section"){
		updateStratSectionTab();
	}else if(activeTabName=="sed_lithologies"){
		updateSedLithologiesTab();
	}else if(activeTabName=="sed_bedding"){
		updateSedBeddingTab();
	}else if(activeTabName=="sed_structures"){
		updateSedStructuresTab();
	}else if(activeTabName=="sed_diagenesis"){
		updateSedDiagenesisTab();
	}else if(activeTabName=="sed_fossils"){
		updateSedFossilsTab();
	}else if(activeTabName=="sed_interpretations"){
		updateSedInterpretationsTab();

	}else{
		$("#"+activeTabId).text(activeTabName+' '+Math.random()+' '+currentSpot.properties.name);
	}

}

	let cgetImageInfo = function(imageid){
	let im = new Image();
	im.src = 'https://strabospot.org/mapimage/'+imageid+'.jpg';

	baseMap.height = im.height;
	baseMap.width = im.width;
	baseMap.id = imageid;

	baseMap.height = 55;
	baseMap.width = 100;
}

	let getImageInfo = function(imageid){

	return new Promise(function(resolve, reject){

		_.each(currentSpot.properties.images, function (image) {
			if(image.id == imageid){
				baseMap.height = image.height;
				baseMap.width = image.width;
				baseMap.id = imageid;

				resolve(imageid);
			}
		});

		reject(imageid);

	})
}

	let bkup05072020getImageInfo = function(imageid){
	return new Promise(function(resolve, reject){

	let im = new Image()

		im.src = 'https://strabospot.org/mapimage/'+imageid+'.jpg';

		im.onload = function(){
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

	let switchToMainMap = function(){

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

	let projection = 'EPSG:3857';
	let center = [-11000000, 4600000];
	let zoom = 5;
	let minZoom = 5;

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

	let goBack = function(){
	currentMode = "mainMap";
	if(crumbs.length > 1){
		crumbs.pop();
		switchToImageBasemap(crumbs.pop());
	}else{
		switchToMainMap();
		closeSideBar();
	}
}

	let switchToImageBasemap = function(imageid){

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

	let ibextent = [0, 0, baseMap.width, baseMap.height];
	let ibprojection = new ol.proj.Projection({
				'code': 'map-image',
				'units': 'pixels',
				'extent': ibextent
			});
	let ibcenter = ol.extent.getCenter(ibextent);
	let ibzoom = 2;
	let ibminZoom = 1;

	let ibView = new ol.View({
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

	let idToName = function(id){

	returnid = id;

	if(idsNames[id]){
		returnid=idsNames[id];
	}

	return returnid;
}

	let saveIdsToNames = function(){

	//build idsNames array to hold all names of spots and sub-properties for later retrieval via idToName();
	//idsNames[12341234]="foo";

	if(loadedFeatures){
	let spots = loadedFeatures.features;
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

function getSpotWithThisStratSection(stratSectionId) {
	let activeSpots = loadedFeatures.features;
	return _.find(activeSpots, function (spot) {
		return _.has(spot, 'properties') && _.has(spot.properties, 'sed') &&
		_.has(spot.properties.sed, 'strat_section') &&
		spot.properties.sed.strat_section.strat_section_id == stratSectionId;  // Comparing int to string so use only 2 equal signs
	});
}


	let switchToStratSection = function(stratSectionId){

	return new Promise(function(resolve, reject){

		saveExtent();

		crumbs.push(stratSectionId);

		//currentBasemapId = imageid;
		currentStratSection = getSpotWithThisStratSection(stratSectionId);
		currentStratSectionId = currentStratSection.properties.sed.strat_section.strat_section_id;
		currentMode = "stratMap";

		$("#back_map").show();
		$("#map_query").hide();
		$("#map_home").hide();

		closeSideBar();

		//getImageInfo(imageid).then(function(promiseimageid){

	let stratextent = [0, 0, 2000, 3000];
	let stratprojection = new ol.proj.Projection({
				'code': 'strat-image',
				'units': 'pixels',
				'extent': stratextent
			});



	let stratcenter = ol.extent.getCenter([0,0,100,50]);
	let stratzoom = 6;
	let stratminZoom = 1;

	let stratView = new ol.View({
			'projection': stratprojection,
			'center': stratcenter,
			'zoom': stratzoom,
			'minZoom': stratminZoom
			});

			removeSelectedSymbol(map);
			map.removeLayer(baseLayers);
			map.removeLayer(datasetPointsLayer);
			map.removeLayer(datasetsLayer);
			map.removeLayer(featureLayer);
			map.removeLayer(ibfeatureLayer);
			map.removeLayer(imageBasemapLayer);

			map.setView(stratView);


// 			imageBasemapLayer = new ol.layer.Image({
// 			  'source': new ol.source.ImageStatic({
// 				'attributions': [
// 				  new ol.Attribution({
// 					'html': '&copy; <a href="">Need image source here.</a>'
// 				  })
// 				],
// 				'url': 'https://strabospot.org/mapimage/'+imageid+'.jpg',
// 				'projection': new ol.proj.Projection({
// 				  'code': 'map-image',
// 				  'units': 'pixels',
// 				  'extent': ibextent
// 				}),
// 				'imageExtent': ibextent
// 			  })
// 			});

			//map.addLayer(imageBasemapLayer);

			updateStratSection();

			map.addLayer(stratSectionLayer);

			layerSwitcher.renderPanel();

			//$timeout(function () {
			//	map.updateSize();         // use OpenLayers API to force map to update
			//});

			//setTimeout(initialResizeMap, 500);

			resolve(stratSectionId);
		//});

	});
}

//put features onto map
	let updateStratSection = function() {

	setCurrentTypeVisibility(map);

	stratSectionLayer.getLayers().clear();

	if(loadedFeatures!="" && loadedFeatures!=null){

		ibfeatureLayer.set('title','Spots');

	let spots = loadedFeatures.features;

	let mappedFeatures = getStratIntervalSpots();

	let stratFeatures = (new ol.format.GeoJSON()).readFeatures({'type': 'FeatureCollection', 'features': mappedFeatures});

		// Create a geojson to hold all the spots that fit the same spot type
	let spotTypeLayer = new ol.layer.Vector({
			'source': new ol.source.Vector({
				'features': stratFeatures
			}),
			'title': 'Strat Section',
			'style': stratStyleFunction
		});

		// Add the feature collection layer to the map
		//var newlayer = geojsonToVectorLayer(spotTypeLayer, map.getView().getProjection());


		//stratSectionLayer.getLayers().push(newlayer);




		stratSectionLayer.getLayers().push(spotTypeLayer);


		layerSwitcher.renderPanel();

	}else{

		stratSectionLayer.set('title',null);
		layerSwitcher.renderPanel();
	}

}


/*



	let spotTypeLayer = {
			'type': 'FeatureCollection',
			'features': mappedFeatures,
			'properties': {
			'name': 'Strat Section',
			'style': stratStyleFunction
			}
		};



	let spotTypeLayer = new ol.layer.Vector({
			'source': new ol.source.Vector({
				'features': mappedFeatures
			}),
			'title': 'Strat Section',
			'style': styleFunction
		});









	let updateStratSection = function() {

	setCurrentTypeVisibility(map);

	stratSectionLayer.getLayers().clear();

	if(loadedFeatures!="" && loadedFeatures!=null){

		ibfeatureLayer.set('title','Spots');

	let spots = loadedFeatures.features;

	let mappedFeatures = getStratIntervalSpots();



		// Create a geojson to hold all the spots that fit the same spot type
	let spotTypeLayer = {
			'type': 'FeatureCollection',
			'features': mappedFeatures,
			'properties': {
			'name': 'Strat Section'
			},
			'style': stratStyleFunction
		};

		// Add the feature collection layer to the map
	let newlayer = geojsonToVectorLayer(spotTypeLayer, map.getView().getProjection());

		stratSectionLayer.getLayers().push(newlayer);


		layerSwitcher.renderPanel();

	}else{

		stratSectionLayer.set('title',null);
		layerSwitcher.renderPanel();
	}

}
*/


function nullstratStyleFunction(feature, resolution) {
		return null;
}


// Set styles for points, lines and polygon and groups
function stratStyleFunction(feature, resolution) {
	let rotation = 0;
	let pointText = feature.get('name');
	let orientation = feature.get('orientation');
	if (orientation) {
		rotation = orientation.strike || orientation.trend || rotation;
		if (!IS_WEB) pointText = orientation.dip || orientation.plunge || pointText;
	}

	let styles = [];
	let geomType = feature.getGeometry().getType();
	if (geomType === 'Point' || geomType === 'MultiPoint') {
		styles.push([
			new ol.style.Style({
				'image': getIconForFeature(feature),
				'text': textStylePoint(pointText.toString(), rotation)
			})
		]);
	}
	else if (geomType === 'LineString' || geomType === 'MultiLineString') {
		styles.push([
			new ol.style.Style({
				'stroke': getStrokeStyle(feature),
				'text': textStyle(feature.get('name'))
			})
		]);
	}
	else if (geomType === 'Polygon' || geomType === 'MultiPolygon') {
		styles.push(
			new ol.style.Style({
				'stroke': new ol.style.Stroke({
					'color': '#000000',
					'width': 0.5
				}),
				'fill': getPolyFill(feature, resolution),
				'text': textStyle(feature.get('name'))
			})
		);
	}
	else if (geomType === 'GeometryCollection') {
	let sed = feature.get('sed');
		function getIsInterbed(i) {
		 return sed.bedding && sed.bedding.lithology_at_bottom_contact === 'lithology_2' ? i % 2 === 0 : i % 2 !== 0;
		}

	let geometries = feature.getGeometry().getGeometries();
		_.each(geometries, function (geometry, i) {
			styles.push(
				new ol.style.Style({
					'geometry': geometry,
					'stroke': new ol.style.Stroke({
						'color': '#000000',
						'width': 0.5
					}),
					'fill': getPolyFill(feature, resolution, getIsInterbed(i)),
					'text': textStyle(feature.get('name'))
				})
			);
		});
	}
	return _.flatten(styles);
}


	let featureIdToSpotId = function(featureid){

	let spotid=null
	if(featureSpotIds[featureid]){
		spotid = featureSpotIds[featureid];
	}
	return spotid;

}

	let featureIdToFeatureType = function(featureid){

	let featuretype=null
	if(featureTypes[featureid]){
		featuretype = featureTypes[featureid];
	}
	return featuretype;

}

	let switchToFeature = function(featureid){

	let spotid = featureIdToSpotId(featureid);
	let featureType = featureIdToFeatureType(featureid);

	if(spotid){
		switchToSpot(spotid,featureType);
	}

}

	let getSpot = function(spotid){
	let thisSpot=null;
	_.each(loadedFeatures.features, function (spot) {
		if(spot.properties.id == spotid){
			thisSpot = spot;
		}
	});
	return thisSpot;
}

	let getSpotFromFeatureId = function(featureid){
	let thisspot = null;
	let spotid = featureIdToSpotId(featureid);
	if(spotid!=null){
		thisspot = getSpot(spotid);
	}
	return thisspot;
}

	let switchToSpot = function(spotid,featureType){

	//check if featuretype is passed so we can switch to the right tab
	let tabnum = 0;
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

	let thisspot = getSpot(spotid);

	if(thisspot){

		if(spotid){

			clickedMapFeature = spotid;
			getCurrentSpot().then(function(promiseimageid){

				if(!thisspot.properties.image_basemap){

	let spotfeature = JSON.parse(JSON.stringify(currentSpot));

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

	let spotfeature = JSON.parse(JSON.stringify(currentSpot));

					feature = (new ol.format.GeoJSON()).readFeature(spotfeature);


	let thisbaseMapId=thisspot.properties.image_basemap;

					if(!baseMapActive || (thisbaseMapId!=currentBasemapId)){


						switchToImageBasemap(thisspot.properties.image_basemap).then(function(promiseimageid){

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

	let debugMap = function(){

	let extent = map.getView().calculateExtent(map.getSize());
	let zoom = map.getView().getZoom();

	let offset=0.0;
	let bottomLeft = ol.proj.transform(ol.extent.getBottomLeft(extent),'EPSG:3857', 'EPSG:4326');
	let topRight = ol.proj.transform(ol.extent.getTopRight(extent),'EPSG:3857', 'EPSG:4326');
	let left = bottomLeft[0]-offset;
	let right = topRight[0]+offset;
	let top = topRight[1]+offset;
	let bottom = bottomLeft[1]-offset;

	console.log("left: "+left+" right: "+right+" top: "+top+" bottom: "+bottom);
	console.log("zoom: "+zoom);

	console.log(newSearchFeatures);



}

	let saveExtent = function(){
	savedCenterZoom.center = map.getView().getCenter();
	savedCenterZoom.zoom = map.getView().getZoom();
}

	let zoomToSavedExtent = function(){
	mapView.setCenter(savedCenterZoom.center);
	mapView.setZoom(savedCenterZoom.zoom);
}

	let zoomToCenterAndExtent = function(encodedstring){
	let string = atob(encodedstring);
	let newcenter = [];
	let newzoom = 0;

	let res = string.split("x");

	newcenter[0]=Number(res[0]);
	newcenter[1]=Number(res[1]);
	newzoom = res[2];

	mapView.setCenter(newcenter);
	mapView.setZoom(newzoom);

}

	let showStaticUrl = function(){

	let getcenter = map.getView().getCenter();
	let getzoom = map.getView().getZoom();

	console.log(getcenter);

	let encodedstring = btoa(getcenter.join("x")+"x"+getzoom);

	console.log(encodedstring);

	let thishtml = "<div style='padding:20px 20px 20px 20px;'>";
	thishtml += "<h3>Static URL</h3>";
	thishtml += "<div>This URL will link directly to the zoom/center of the current map view:</div>";
	thishtml += "<div style='padding-top:5px;'>https://strabospot.org/search?c="+encodedstring+"</div>";
	thishtml += "<div>&nbsp;</div>";
	thishtml += "</div>";

	$.featherlight(thishtml);

}

	let hideEffect = "blind";//blind,drop,explode,

	let switchToQueryDiv = function(){

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

	let switchToSpotDiv = function(){

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

	let toggleSpotQuery = function(){

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

	let turnOnUpdateButton = function(){

	updateQueryButton();
	updateClearButton();
}

	let updateQueryButton = function(){ //show button if query has changed
	if(buildDatasetsURL()!=datasetsurl){
		showQueryButton();
	}else{
		hideQueryButton();
	}
}

	let updateClearButton = function(){ //show clear button if any query options are set.
	if(querySet()){
		showClearButton();
	}else{
		hideClearButton();
	}
}

	let querySet = function(){
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

	let clearQuery = function(){

	$('#query_has_image').attr('checked', false);
	$('#query_has_orientation').attr('checked', false);
	$('#query_has_sample').attr('checked', false);
	$('#query_has_3d_structure').attr('checked', false);
	$('#query_has_other_features').attr('checked', false);

	updateQueryButton();
	hideClearButton();
}

	let showQueryButton = function(){
	$("#querybuttondiv").html('<button id="querybutton" class="querysubmit" onClick="updateQuery();"><span>Update</span></button>');
}

	let hideQueryButton = function(){
	$("#querybuttondiv").html('<button id="greyquerybutton" class="greyquerysubmit"><span>Update&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>');
}

	let showClearButton = function(){
	$("#clearbuttondiv").html('<button id="clearbutton" class="querysubmit" onClick="clearQuery();"><span>Clear</span></button>');
}

	let hideClearButton = function(){
	$("#clearbuttondiv").html('<button id="greyclearbutton" class="greyquerysubmit"><span>Clear&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>');
}

	let updateQuery = function(){

	hideQueryButton();
	$.fancybox.close();
	closeSideBar();
	removeSelectedSymbol(map);

	datasetsurl = buildDatasetsURL();

	//updateMapDiv();

	updateMap();


	if(lastdatasetsurl != datasetsurl){


		rebuildDatasetsLayer();

		lastdatasetsurl = datasetsurl;
	}else{
	}

	let resolution = map.getView().getResolution();
	let zoom = map.getView().getZoom();
	if(!baseMapActive){

		if(resolution < zoomres){

			if(querySet()){
				//query set and we're zoomed in. Let's zoom to features here

				if(fitFeatures.length > 0){

					//zoomToFitFeatures();

				}else{
				}

			}
		}
	}
}

	let zoomToFitFeatures = function() {
	if(fitFeatures.length > 0){
	let fc = turf.featureCollection(fitFeatures);
	let newSpot = {};
		if (fitFeatures.length == 1) newSpot = JSON.parse(JSON.stringify(fitFeatures[0]));
		else if (fitFeatures.length == 2
		&& fitFeatures[0].geometry.type === 'Point' && fitFeatures[1].geometry.type === 'Point') {
		newSpot = turf.lineString([fitFeatures[0].geometry.coordinates, fitFeatures[1].geometry.coordinates]);
		}
		else newSpot = turf.convex(fc);

		// Buffer the polygon
		newSpot = turf.buffer(newSpot, 5, 'meters');

	let newfeature = (new ol.format.GeoJSON()).readFeature(newSpot, {dataProjection: 'EPSG:4326',featureProjection: 'EPSG:3857'});

	let newgeometry = newfeature.getGeometry();

		mapView.fit(newgeometry, {duration: 1000});

	}
}

	let buildGetSearchVars = function(){

	let delim = "";

	let newurl = "";

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

	let buildDatasetsURL= function(){

	let newurl = buildGetSearchVars();

	newurl = "newsearchdatasets.json?"+newurl;

	return newurl;
}

	let spotFitsQuery = function(spot){


	let spotfits = true;

	if($("#query_has_image").is(":checked")){
		if(!spot.properties.images){
			spotfits = false;
		}
	}

	if($("#query_has_orientation").is(":checked")){
		if(!spot.properties.orientation && !spot.properties.orientation_data){
			spotfits = false;
		}else{
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

const delay = ms => new Promise(res => setTimeout(res, ms));

	let gotoDataset = async (datasetid) => {
//var gotoDataset = function(datasetid){
	$('#spotswaiting').show();
	await delay(3000);
	if(allDatasets.length == 0){
		updateAllDatasets();
	}
	console.log("dataset id: " + datasetid);
	ds = datasetPointsSource.getFeatures();
	$.each(ds, function(index,value){

	let thisid = value.get('id').split('-')[1];


		if(thisid == datasetid){
			console.log("found it");
			clickedDatasetId = datasetid;
			expandDataset(value);
			datasetPointsSource.removeFeature(value);
		}
	});
	//alert('hey');
}

	let expandDataset = function(feature){
	console.log("expanding");
	expandedDatasets.push(feature);
	mapDataset(feature);
	updateShownDatasets();
}

	let mapDataset = function(feature){
	id = feature.get('id');
	addFeatures(id);
}

	let closeDataset = function(id){

	console.log("close dataset: "+id);

	//close dataset from map and list box
	removeDatasetFromArray(id);
	removeDatasetFromLoadedFeatures(id);
	restoreDatasetPoint(id);

	updateShownDatasets();

}

	let restoreDatasetPoint = function(id){

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

	let removeDatasetFromArray = function(id){
	let newArray = [];
	_.each(expandedDatasets, function(ds){
		thisid = ds.get('id');
		if(thisid!=id){
			newArray.push(ds);
		}
	});

	expandedDatasets = newArray;
}

	let removeDatasetFromLoadedFeatures = function(id){

	console.log("closing this dataset: "+id);

	let newLoadedFeatures = {};
	newLoadedFeatures.features = [];
	newLoadedFeatures.datasets = [];
	newLoadedFeatures.tags = [];
	newLoadedFeatures.relationships = [];
	newLoadedFeatures.image_basemaps = [];

	_.each(loadedFeatures.features, function(feat){
		console.log("this feature dsetid: "+feat.properties.datasetid);
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

	let updateShownDatasets = function(){
	if(expandedDatasets.length > 0){
		//create html and show div
	let thishtml = '';
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
		$("#sideDownloadButton").show();
	}else{
		//clear html and hide div
		//#openDatasets && #openDatasetsList
		$("#openDatasetsList").html("");
		$("#openDatasets").hide();
		$("#download_map").hide();
		$("#sideDownloadButton").hide();
	}
}

	let updateAllDatasets = function(){
	//updates allDatasets array with all dataset features from datasetPointsSource
	allDatasets = datasetPointsSource.getFeatures();
}

	let randomString = function(){
	let text = "";
	let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	for(let i=0; i < 10; i++ )
		text += possible.charAt(Math.floor(Math.random() * possible.length));

	return text;
}

	let zoomToPoly = function(inwkt){
	let newformat = new ol.format.WKT();

	let inpoly = (new ol.format.WKT()).readFeature(inwkt, {dataProjection: 'EPSG:4326',featureProjection: 'EPSG:3857'});

	/*
	let inpoly = (new ol.format.WKT()).readFeature(inwkt, {
						'featureProjection': 'EPSG:4326'
					});

	let inpoly = (format.readFeature(inwkt, {
						'featureProjection': 'EPSG:3857'
					});

	let inpoly = newformat.readFeature(inwkt);
	*/

	let newgeometry = inpoly.getGeometry();
	mapView.fit(newgeometry, {duration: 1000});
}

	let openDownloadWindow = function(){

	if(currentMode == "stratMap"){

		console.log("download strat section here...")

	let stratSectionId = currentStratSection.properties.id;

		console.log("stratSectionId:"); console.log(stratSectionId);

		//also get current dataset id

		//pstrat_section?id=15904123800227&did=15904120884632

	let currentStratDatasetId = getDatasetIdForSpotId(stratSectionId);
		console.log("currentStratDatasetId: " + currentStratDatasetId);

	let idParts = currentStratDatasetId.split("-");
	let downloadId = idParts[1];
		console.log("downloadId: " + downloadId);

		window.open("https://strabospot.org/pstrat_section?id="+stratSectionId+"&did="+downloadId);

	}else{

		console.log(expandedDatasets);

	let thishtml="";
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
}

function getDatasetIdForSpotId(spotId){
	let returnId = "foo";
	_.each(loadedFeatures.features, function(feature){
		if(feature.properties.id == spotId){
			returnId = feature.properties.datasetid;
		}
	});
	return returnId;
}

	let downloadData = function(datatype){
	let boxeschecked=false;
	let dsids = [];
	$('.dldatasets:checked').each(function(){
		dsids.push($(this).val());
		boxeschecked=true;
	});
	if(boxeschecked){
		dsids=dsids.join();
	let range = $("#downloadscope:checked").val();	//all -or- envelope
	let envelope = getCurrentViewEnvelope();
		console.log("download "+datatype+" with ids=(s): "+dsids+" with range: "+range+" and envelope: "+envelope);

		//need to add search query too
	let getvars = buildGetSearchVars();

		querystring = createQueryString();

		//window.location='/newsearchdownload?type='+datatype+'&dsids='+dsids+'&range='+range+'&envelope='+envelope+'&'+getvars;
		//window.location='/newsearchdownload?type='+datatype+'&dsids='+dsids+'&range='+range+'&envelope='+envelope+'&querystring='+querystring;
		console.log('/newsearchdownload?type='+datatype+'&dsids='+dsids+'&range='+range+'&envelope='+envelope+'&querystring='+querystring);
		window.open('/newsearchdownload?type='+datatype+'&dsids='+dsids+'&range='+range+'&envelope='+envelope+'&querystring='+querystring);


	}else{
		alert("Error! You must choose at least one dataset.");
	}

}

	let zoomHome = function(){
	mapView.fit(outgeometry, {duration: 1000});
}