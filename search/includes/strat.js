
    var basicLitholigiesLabels = ['other', 'coal', 'mudstone', 'sandstone', 'conglomerate/breccia', 'limestone/dolostone'];
    //var grainSizeOptions = DataModelsFactory.getSedLabelsDictionary();
    var spotsWithStratSections = {};
    var stratSectionSpots = {};
    var xInterval = 10;  // Horizontal spacing between grain sizes/weathering tick marks
    var yMultiplier = 20;  // 1 m interval thickness = 20 pixels
    var grainSizeOptions = {
							  "clastic": [
								{
								  "value": "clay",
								  "label": "clay"
								},
								{
								  "value": "silt",
								  "label": "silt"
								},
								{
								  "value": "sand_very_fin",
								  "label": "sand- very fine"
								},
								{
								  "value": "sand_fine_low",
								  "label": "sand- fine lower"
								},
								{
								  "value": "sand_fine_upp",
								  "label": "sand- fine upper"
								},
								{
								  "value": "sand_medium_l",
								  "label": "sand- medium lower"
								},
								{
								  "value": "sand_medium_u",
								  "label": "sand- medium upper"
								},
								{
								  "value": "sand_coarse_l",
								  "label": "sand- coarse lower"
								},
								{
								  "value": "sand_coarse_u",
								  "label": "sand- coarse upper"
								},
								{
								  "value": "sand_very_coa",
								  "label": "sand- very coarse"
								},
								{
								  "value": "granule",
								  "label": "granule"
								},
								{
								  "value": "pebble",
								  "label": "pebble"
								},
								{
								  "value": "cobble",
								  "label": "cobble"
								},
								{
								  "value": "boulder",
								  "label": "boulder"
								}
							  ],
							  "carbonate": [
								{
								  "value": "mudstone",
								  "label": "mudstone"
								},
								{
								  "value": "wackestone",
								  "label": "wackestone"
								},
								{
								  "value": "packstone",
								  "label": "packstone"
								},
								{
								  "value": "grainstone",
								  "label": "grainstone"
								},
								{
								  "value": "floatstone",
								  "label": "floatstone"
								},
								{
								  "value": "rudstone",
								  "label": "rudstone"
								},
								{
								  "value": "boundstone",
								  "label": "boundstone"
								},
								{
								  "value": "framestone",
								  "label": "framestone"
								},
								{
								  "value": "bindstone",
								  "label": "bindstone"
								},
								{
								  "value": "bafflestone",
								  "label": "bafflestone"
								},
								{
								  "value": "cementstone",
								  "label": "cementstone"
								},
								{
								  "value": "recrystallized",
								  "label": "recrystallized"
								}
							  ],
							  "lithologies": [
								{
								  "value": "siliciclastic",
								  "label": "siliciclastic"
								},
								{
								  "value": "limestone",
								  "label": "limestone"
								},
								{
								  "value": "dolostone",
								  "label": "dolostone"
								},
								{
								  "value": "organic_coal",
								  "label": "organic/coal"
								},
								{
								  "value": "evaporite",
								  "label": "evaporite"
								},
								{
								  "value": "chert",
								  "label": "chert"
								},
								{
								  "value": "ironstone",
								  "label": "ironstone"
								},
								{
								  "value": "phosphatic",
								  "label": "phosphatic"
								},
								{
								  "value": "volcaniclastic",
								  "label": "volcaniclastic"
								}
							  ],
							  "weathering": [
								{
								  "value": "1",
								  "label": "1 - least resistant"
								},
								{
								  "value": "2",
								  "label": "2"
								},
								{
								  "value": "3",
								  "label": "3 - moderately resistant"
								},
								{
								  "value": "4",
								  "label": "4"
								},
								{
								  "value": "5",
								  "label": "5 - most resistant"
								}
							  ]
							};

	var patterns = {};
	// Limestone / Dolostone / Misc. Lithologies
	patterns['limestone'] = loadPattern('basic/LimeSimple');
	patterns['dolostone'] = loadPattern('basic/DoloSimple');
	patterns['evaporite'] = loadPattern('misc/EvaBasic');
	patterns['chert'] = loadPattern('misc/ChertBasic');
	patterns['phosphatic'] = loadPattern('misc/PhosBasic');
	patterns['volcaniclastic'] = loadPattern('misc/VolBasic');

	// Siliciclastic (Mudstone/Shale, Sandstone, Conglomerate, Breccia)
	patterns['mud_silt'] = loadPattern('basic/MudSimple');
	patterns['sandstone'] = loadPattern('basic/SandSimple');
	patterns['conglomerate'] = loadPattern('basic/CongSimple');
	patterns['breccia'] = loadPattern('basic/BrecSimple');

	// Mudstone/Shale
	patterns['clay'] = loadPattern('siliciclastics/ClayBasic');
	patterns['mud'] = loadPattern('siliciclastics/MudBasic');
	patterns['silt'] = loadPattern('siliciclastics/SiltBasic');
	// Sandstone
	patterns['sand_very_fin'] = loadPattern('siliciclastics/VFBasic');
	patterns['sand_fine_low'] = loadPattern('siliciclastics/FLBasic');
	patterns['sand_fine_upp'] = loadPattern('siliciclastics/FUBasic');
	patterns['sand_medium_l'] = loadPattern('siliciclastics/MLBasic');
	patterns['sand_medium_u'] = loadPattern('siliciclastics/MUBasic');
	patterns['sand_coarse_l'] = loadPattern('siliciclastics/CLBasic');
	patterns['sand_coarse_u'] = loadPattern('siliciclastics/CUBasic');
	patterns['sand_very_coa'] = loadPattern('siliciclastics/VCBasic');

	// Conglomerate
	patterns['congl_granule'] = loadPattern('siliciclastics/CGrBasic');
	patterns['congl_pebble'] = loadPattern('siliciclastics/CPebBasic');
	patterns['congl_cobble'] = loadPattern('siliciclastics/CCobBasic');
	patterns['congl_boulder'] = loadPattern('siliciclastics/CBoBasic');

	// Breccia
	patterns['brec_granule'] = loadPattern('siliciclastics/BGrBasic');
	patterns['brec_pebble'] = loadPattern('siliciclastics/BPebBasic');
	patterns['brec_cobble'] = loadPattern('siliciclastics/BCobBasic');
	patterns['brec_boulder'] = loadPattern('siliciclastics/BBoBasic');

	// Limestone
	patterns['li_bafflestone'] = loadPattern('limestone/LiBoBasic');
	patterns['li_bindstone'] = loadPattern('limestone/LiBoBasic');
	patterns['li_boundstone'] = loadPattern('limestone/LiBoBasic');
	patterns['li_floatstone'] = loadPattern('limestone/LiFloBasic');
	patterns['li_framestone'] = loadPattern('limestone/LiBoBasic');
	patterns['li_grainstone'] = loadPattern('limestone/LiGrBasic');
	patterns['li_mudstone'] = loadPattern('limestone/LiMudBasic');
	patterns['li_packstone'] = loadPattern('limestone/LiPaBasic');
	patterns['li_rudstone'] = loadPattern('limestone/LiRudBasic');
	patterns['li_wackestone'] = loadPattern('limestone/LiWaBasic');

	// Dolostone
	patterns['do_bafflestone'] = loadPattern('dolostone/DoBoBasic');
	patterns['do_bindstone'] = loadPattern('dolostone/DoBoBasic');
	patterns['do_boundstone'] = loadPattern('dolostone/DoBoBasic');
	patterns['do_floatstone'] = loadPattern('dolostone/DoFloBasic');
	patterns['do_framestone'] = loadPattern('dolostone/DoBoBasic');
	patterns['do_grainstone'] = loadPattern('dolostone/DoGrBasic');
	patterns['do_mudstone'] = loadPattern('dolostone/DoMudBasic');
	patterns['do_packstone'] = loadPattern('dolostone/DoPaBasic');
	patterns['do_rudstone'] = loadPattern('dolostone/DoRudBasic');
	patterns['do_wackestone'] = loadPattern('dolostone/DoWaBasic');

	// Misc. Lithologies
	patterns['evaporite'] = loadPattern('misc/EvaBasic');
	patterns['chert'] = loadPattern('misc/ChertBasic');
	patterns['phosphatic'] = loadPattern('misc/PhosBasic');
	patterns['volcaniclastic'] = loadPattern('misc/VolBasic');
	

	var symbols = {
		'default_point': 'img/geology/point.png',

		// Planar Feature Symbols
		'bedding_horizontal': 'img/geology/bedding_horizontal.png',
		'bedding_inclined': 'img/geology/bedding_inclined.png',
		'bedding_overturned': 'img/geology/bedding_overturned.png',
		'bedding_vertical': 'img/geology/bedding_vertical.png',
		'contact_inclined': 'img/geology/contact_inclined.png',
		'contact_vertical': 'img/geology/contact_vertical.png',
		'fault': 'img/geology/fault.png',
		'foliation_horizontal': 'img/geology/foliation_horizontal.png',
		'foliation_inclined': 'img/geology/foliation_general_inclined.png',
		'foliation_vertical': 'img/geology/foliation_general_vertical.png',
		'fracture': 'img/geology/fracture.png',
		'shear_zone_inclined': 'img/geology/shear_zone_inclined.png',
		'shear_zone_vertical': 'img/geology/shear_zone_vertical.png',
		'vein': 'img/geology/vein.png',

		// Old
		// 'axial_planar_inclined': 'img/geology/cleavage_inclined.png',
		// 'axial_planar_vertical': 'img/geology/cleavage_vertical.png',
		// 'joint_inclined': 'img/geology/joint_surface_inclined.png',
		// 'joint_vertical': 'img/geology/joint_surface_vertical.png',
		// 'shear_fracture': 'img/geology/shear_fracture.png',

		// Linear Feature Symbols
		// 'fault': 'img/geology/fault_striation.png',
		// 'flow': 'img/geology/flow.png',
		// 'fold_hinge': 'img/geology/fold_axis.png',
		// 'intersection': 'img/geology/intersection.png',
		'lineation_general': 'img/geology/lineation_general.png'
		// 'solid_state': 'img/geology/solid_state.png',
		// 'vector': 'img/geology/vector.png'
	};


	// Round value to the number of decimal places in the variable places
	function roundToDecimalPlaces(value, places) {
		var multiplier = Math.pow(10, places);
		return (Math.round(value * multiplier) / multiplier);
	}
	
	// Get pixel coordinates from map coordinates
	function getPixel(coord, pixelRatio) {
		return {
			'x': map.getPixelFromCoordinate(coord)[0] * pixelRatio,
			'y': map.getPixelFromCoordinate(coord)[1] * pixelRatio
		};
	}
	
	// Get the height (y) of the whole section
	function getSectionHeight() {
		var intervalSpots = getStratIntervalSpots();
		var sectionHeight = 0;
		
		var fc = turf.featureCollection(intervalSpots);
		var envelope = turf.envelope(fc);
		
		//console.log("envelope"); console.log(envelope);
		
		sectionHeight = envelope.geometry.coordinates[0][3][1];
		
		//console.log("sectionHeight: " + sectionHeight);

		return sectionHeight;
	}

	// Get only Spots that are intervals
	function getStratIntervalSpots() {
		var intervalSpots = [];
		
		//console.log("LoadedFeatures: "); console.log(loadedFeatures.features);
		//console.log("currentStratSection"); console.log(currentStratSection);
		
		//console.log("currentStratSectionId: " + currentStratSectionId);
		
		//currentStratSection.properties.sed.strat_section.strat_section_id
		
		_.each(loadedFeatures.features, function (feature) {			
			//console.log("One Feature: "); console.log(feature);
			if (//feature.properties.surface_feature
				//&& feature.properties.surface_feature.surface_feature_type == 'strat_interval'
				//&& 
				feature.properties.strat_section_id == currentStratSectionId
				){
				
				//console.log("Found Feature: "); console.log(feature);
				
				intervalSpots.push(feature);
			}
		});
		//console.log("interval spots "); console.log(intervalSpots);
		return intervalSpots;
	}
	
	// Get only Spots that are not intervals
	function getStratOtherSpots() {
		var intervalSpots = [];
		
		//console.log("LoadedFeatures: "); console.log(loadedFeatures.features);
		//console.log("currentStratSection"); console.log(currentStratSection);
		
		//currentStratSection.properties.sed.strat_section.strat_section_id
		
		_.each(loadedFeatures.features, function (feature) {			
			//console.log("One Feature: "); console.log(feature);
			if (!feature.properties.surface_feature
				&& feature.properties.strat_section_id == currentStratSectionId
				){
				
				//console.log("Found Feature: "); console.log(feature);
				
				intervalSpots.push(feature);
			}
		});
		//console.log("interval spots "); console.log(intervalSpots);
		return intervalSpots;
	}
	
	

	function drawAxes(ctx, pixelRatio, stratSection) {
		
		stratSection = stratSection.properties.sed.strat_section;
		
		//console.log("stratSection: "); console.log(stratSection);
		
		ctx.font = "30px Arial";

		//var map = MapSetupFactory.getMap();
		var zoom = map.getView().getZoom();

		// Y Axis
		var currentSectionHeight = getSectionHeight();
		var yAxisHeight = currentSectionHeight + 40;

		ctx.beginPath();
		ctx.setLineDash([]);
		var p = getPixel([0, 0], pixelRatio);
		ctx.moveTo(p.x, p.y);
		p = getPixel([0, yAxisHeight], pixelRatio);
		ctx.lineTo(p.x, p.y);

		// Tick Marks for Y Axis
		_.times(Math.floor(yAxisHeight / yMultiplier) + 1, function (i) {
			var y = i * yMultiplier;
			p = i === 0 ? getPixel([-15, 0], pixelRatio) : getPixel([-10, y], pixelRatio);
			if (i === 0 || zoom >= 5 || (zoom < 5 && zoom > 2 && i % 5 === 0) || (zoom <= 2 && i % 10 === 0)) {
				ctx.textAlign = 'right';
				if (i === 0 && stratSection.column_y_axis_units) {
					ctx.fillText('0 ' + stratSection.column_y_axis_units, p.x, p.y);
				}
				else ctx.fillText(i, p.x, p.y);
				p = getPixel([-5, y], pixelRatio);
				ctx.moveTo(p.x, p.y);
				p = getPixel([0, y], pixelRatio);
				ctx.lineTo(p.x, p.y);
			}
		});
		ctx.stroke();

		
		/*
		var fc = turf.featureCollection(intervalSpots);
		var envelope = turf.envelope(fc);
		
		//console.log("envelope"); console.log(envelope);
		
		sectionHeight = envelope.geometry.coordinates[0][3][1];
		*/
		
		
		
		
		
		// Tick Marks for Intervals
		// Only show tick marks if zoom level is 6 or greater
		if (zoom >= 6) {
			var intervalSpots = getStratIntervalSpots();
			_.each(intervalSpots, function (intervalSpot) {
				//var extent = intervalSpot.getGeometry().getExtent();
				//var y = extent[3];
				
				var fc = turf.featureCollection([intervalSpot]);
				var envelope = turf.envelope(fc);
				var y = envelope.geometry.coordinates[0][3][1];
				
				var label = roundToDecimalPlaces(y / yMultiplier, 2);
				if (!Number.isInteger(label)) {
					p = getPixel([-3, y], pixelRatio);
					ctx.textAlign = 'right';
					ctx.fillText(label, p.x, p.y);
					p = getPixel([-2, y], pixelRatio);
					ctx.moveTo(p.x, p.y);
					p = getPixel([0, y], pixelRatio);
					ctx.lineTo(p.x, p.y);
				}
			});
			ctx.stroke();
		}
		
// 		if (zoom >= 6) {
// 			var intervalSpots = getStratIntervalSpots();
// 			_.each(intervalSpots, function (intervalSpot) {
// 				var extent = intervalSpot.getGeometry().getExtent();
// 				var y = extent[3];
// 				var label = HelpersFactory.roundToDecimalPlaces(extent[3] / yMultiplier, 2);
// 				if (!Number.isInteger(label)) {
// 					p = getPixel([-3, y], pixelRatio);
// 					ctx.textAlign = 'right';
// 					ctx.fillText(label, p.x, p.y);
// 					p = getPixel([-2, y], pixelRatio);
// 					ctx.moveTo(p.x, p.y);
// 					p = getPixel([0, y], pixelRatio);
// 					ctx.lineTo(p.x, p.y);
// 				}
// 			});
// 			ctx.stroke();
// 		}

		// Setup to draw X Axis
		var labels = {};
		var y = 0;
		var a = 174.5;
		var b = -40.5;
		var c = 2.5;
		var x = zoom;
		if (stratSection.column_profile === 'clastic') {
			labels = _.pluck(grainSizeOptions.clastic, 'label');
			//drawAxisX(ctx, pixelRatio, yAxisHeight, labels, 1);
			drawAxisXStacked(ctx, pixelRatio, yAxisHeight, labels, 1, y, 'black', stratSection.column_profile);
			y += (a + b * x + c * x * x) * -1
		}
		else if (stratSection.column_profile === 'carbonate') {
			labels = _.pluck(grainSizeOptions.carbonate, 'label');
			//drawAxisX(ctx, pixelRatio, yAxisHeight, labels, 2.33);
			drawAxisXStacked(ctx, pixelRatio, yAxisHeight, labels, 2.33, y, 'blue', stratSection.column_profile);
			y += (a + b * x + c * x * x) * -1
		}
		else if (stratSection.column_profile === 'mixed_clastic') {
			labels = _.pluck(grainSizeOptions.clastic, 'label');
			//drawAxisX(ctx, pixelRatio, yAxisHeight, labels, 1);
			drawAxisXStacked(ctx, pixelRatio, yAxisHeight, labels, 1, y, 'black', 'clastic');
			y += (a + b * x + c * x * x) * -1;
			labels = _.pluck(grainSizeOptions.carbonate, 'label');
			//drawAxisX(ctx, pixelRatio, yAxisHeight, labels, 2.33, 'blue');
			drawAxisXStacked(ctx, pixelRatio, yAxisHeight, labels, 2.33, y, 'blue', 'carbonate');
			y += (a + b * x + c * x * x) * -1
		}
		else if (stratSection.column_profile === 'basic_lithologies') {
			labels = basicLitholigiesLabels;
			drawAxisXStacked(ctx, pixelRatio, yAxisHeight, labels, 2, y, 'black', stratSection.column_profile);
			y += (a + b * x + c * x * x) * -1
		}
		else if (stratSection.column_profile === 'weathering_pro') {
			labels = _.pluck(grainSizeOptions.weathering, 'label');
			//drawAxisX(ctx, pixelRatio, yAxisHeight, labels, 1);
			drawAxisXStacked(ctx, pixelRatio, yAxisHeight, labels, 1, y, 'black', 'weathering');
			y += (a + b * x + c * x * x) * -1
		}
		else console.log('Incorrect profile type:', stratSection.column_profile);

		if (stratSection.misc_labels === true) {
			labels = _.pluck(grainSizeOptions.lithologies, 'label');
			labels = _.rest(labels, 3);
			//drawAxisX(ctx, pixelRatio, yAxisHeight, labels, 2.66, 'green');
			drawAxisXStacked(ctx, pixelRatio, yAxisHeight, labels, 2.66, y, 'green', 'misc.');
		}
	}
	
	// X Axis Stacked
	function drawAxisXStacked(ctx, pixelRatio, yAxisHeight, labels, spacing, y, color, profile) {
		var p = y === 0 ? getPixel([-10, y], pixelRatio) : getPixel([0, y], pixelRatio);
		ctx.beginPath();
		ctx.moveTo(p.x, p.y);
		var xAxisLength = (_.size(labels) + spacing) * xInterval;
		p = getPixel([xAxisLength, y], pixelRatio);
		ctx.lineTo(p.x, p.y);
		ctx.strokeStyle = color;
		ctx.stroke();

		// Create Grain Size Labels for X Axis
		ctx.textAlign = 'left';
		ctx.lineWidth = 3;

		// Line and label for x-axis group
		ctx.beginPath();
		p = getPixel([0, y], pixelRatio);
		ctx.moveTo(p.x, p.y);
		p = getPixel([0, y - 40], pixelRatio);
		ctx.lineTo(p.x, p.y);
		ctx.strokeStyle = color;
		ctx.stroke();
		ctx.save();
		p = getPixel([-2, y - 2], pixelRatio);
		ctx.translate(p.x, p.y);
		ctx.rotate(270 * Math.PI / 180);		 // text at 270 degrees
		ctx.fillStyle = color;
		ctx.textAlign = 'right';
		ctx.fillText(profile, 0, 0);
		ctx.restore();

		_.each(labels, function (label, i) {
			var x = (i + spacing) * xInterval;

			// Tick Mark
			ctx.beginPath();
			ctx.setLineDash([]);
			p = getPixel([x, y], pixelRatio);
			ctx.moveTo(p.x, p.y);
			p = getPixel([x, y - 4], pixelRatio);
			ctx.lineTo(p.x, p.y);
			ctx.strokeStyle = color;
			ctx.stroke();

			// Label
			ctx.save();
			p = getPixel([x, y - 5], pixelRatio);
			ctx.translate(p.x, p.y);
			ctx.rotate(30 * Math.PI / 180);		 // text at 30 degrees
			ctx.fillStyle = color;
			ctx.fillText(label, 0, 0);
			ctx.restore();

			// Vertical Dashed Line
			ctx.beginPath();
			ctx.setLineDash([5]);
			p = getPixel([x, 0], pixelRatio);
			ctx.moveTo(p.x, p.y);
			p = getPixel([x, yAxisHeight], pixelRatio);
			ctx.lineTo(p.x, p.y);
			ctx.strokeStyle = 'color';
			ctx.stroke();
			ctx.setLineDash([]);
		});
	}
	
	function getPolyFill(feature, resolution, isInterbed) {
		var featureProperties = feature.getProperties();

		// If a Strat Interval
		var fill = [];
		if (featureProperties.surface_feature &&
			featureProperties.surface_feature.surface_feature_type === 'strat_interval') {
			fill = getStratIntervalFill(featureProperties, resolution, isInterbed)
		}
		else {
			var color;
			color = 'rgba(0, 0, 255, 0.4)';			 // blue
			var colorApplied = false;
			var tags = getTagsBySpotId(feature.get('id'));
			if (tags.length > 0) {
				_.each(tags, function (tag) {
					if (tag.type === 'geologic_unit' && tag.color && !_.isEmpty(tag.color) && !colorApplied) {
						var rgbColor = hexToRgb(tag.color);
						color = 'rgba(' + rgbColor.r + ', ' + rgbColor.g + ', ' + rgbColor.b + ', 0.4)';
						colorApplied = true;
					}
				});
			}
			if (feature.get('surface_feature') && !colorApplied) {
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
			fill = new ol.style.Fill({
				'color': color
			});
		}
		return fill;
	}
	
	function getStratIntervalFill(featureProperties, resolution, isInterbed) {
		
		//console.log("currentStratSection:");
		//console.log(currentStratSection);
		
		//console.log("featureProperties:");
		//console.log(featureProperties);
		
		var fill;
		var color;
		var n = isInterbed ? 1 : 0;
		if (featureProperties.sed && featureProperties.sed.character) {
			if (featureProperties.sed.lithologies && featureProperties.sed.lithologies[n]) {
				//$log.log(props.sed.lithologies);
				var lithologies = featureProperties.sed.lithologies;
				var lithologyField = lithologies[n].primary_lithology;
				var grainSize = getGrainSize(lithologies[n]);
				var stratSectionSettings = currentStratSection.properties.sed.strat_section;
				if (stratSectionSettings.display_lithology_patterns) {
					if (stratSectionSettings.column_profile === 'basic_lithologies') {
						// Limestone / Dolostone / Misc. Lithologies
						if (lithologyField === 'limestone') fill = patterns['limestone'];
						else if (lithologyField === 'dolostone') fill = patterns['dolostone'];
						//else if (lithologyField === 'organic_coal') patterns[grainSize] = loadPattern('misc/SiltBasic');
						else if (lithologyField === 'evaporite') fill = patterns['evaporite'];
						else if (lithologyField === 'chert') fill = patterns['chert'];
						//else if (lithologyField === 'ironstone') patterns[grainSize] = loadPattern('misc/SiltBasic');
						else if (lithologyField === 'phosphatic') fill = patterns['phosphatic'];
						else if (lithologyField === 'volcaniclastic') fill = patterns['volcaniclastic'];

						// Siliciclastic (Mudstone/Shale, Sandstone, Conglomerate, Breccia)
						else if (lithologies[n].mud_silt_grain_size) fill = patterns['mud_silt'];
						else if (lithologies[n].sand_grain_size) fill = patterns['sandstone'];
						else if (lithologies[n].congl_grain_size) fill = patterns['conglomerate'];
						else if (lithologies[n].breccia_grain_size) fill = patterns['breccia'];
					}
					else {
						if (lithologyField === 'limestone') fill = patterns['li_' + grainSize];
						else if (lithologyField === 'dolostone') fill = patterns['do_' + grainSize];
						else if (lithologyField === 'siliciclastic' && lithologies[n].siliciclastic_type === 'conglomerate') {
							fill = patterns['congl_' + grainSize];
						}
						else if (lithologyField === 'siliciclastic' && lithologies[n].siliciclastic_type === 'breccia') {
							fill = patterns['brec_' + grainSize];
						}
						else fill = patterns[grainSize];
					}
				}
			}
			if (!fill) {
				if (featureProperties.sed.character === 'unexposed_cove' || featureProperties.sed.character === 'not_measured') {
					var canvas = document.createElement('canvas');
					var ctx = canvas.getContext('2d');

					var extent = featureProperties.geometry.getExtent();
					var width = 10 / resolution * 2;
					var height = (extent[3] - extent[1]) / resolution * 2;
					canvas.width = width;
					canvas.height = height;

					ctx.beginPath();
					ctx.moveTo(0, 0);
					ctx.lineTo(width, height);
					ctx.moveTo(0, height);
					ctx.lineTo(width, 0);
					ctx.stroke();

					var pattern = ctx.createPattern(canvas, 'no-repeat');
					fill = new ol.style.Fill();
					fill.setColor(pattern);
				}
				// Basic Lithologies Column Profile
				else if (stratSectionSettings.column_profile === 'basic_lithologies') {
					// Limestone / Dolostone / Misc. Lithologies
					if (lithologyField === 'limestone') color = 'rgba(77, 255, 222, 1)';					 // CMYK 70,0,13,0 USGS Color 820
					else if (lithologyField === 'dolostone') color = 'rgba(77, 255, 179, 1)';			 // CMYK 70,0,30,0 USGS Color 840
					else if (lithologyField === 'organic_coal') color = 'rgba(0, 0, 0, 1)';				// CMYK 100,100,100,0 USGS Color 999
					else if (lithologyField === 'evaporite') color = 'rgba(153, 77, 255, 1)';			// CMYK 40,70,0,0 USGS Color 508;
					else if (lithologyField === 'chert') color = 'rgba(102, 77, 77, 1)';					 // CMYK 60,70,70,0
					else if (lithologyField === 'ironstone') color = 'rgba(153, 0, 0, 1)';				 // CMYK 40,100,100,0
					else if (lithologyField === 'phosphatic') color = 'rgba(153, 255, 179, 1)';		// CMYK 40,0,30,0
					else if (lithologyField === 'volcaniclastic') color = 'rgba(255, 128, 255, 1)';// CMYK 0,50,0,0

					// Siliciclastic (Mudstone/Shale, Sandstone, Conglomerate, Breccia)
					else if (lithologies[n].mud_silt_grain_size) color = 'rgba(128, 222, 77, 1)';					// CMYK 50,13,70,0 USGS Color 682
					else if (lithologies[n].sand_grain_size) color = 'rgba(255, 255, 77, 1)';							// CMYK 0,0,70,0 USGS Color 80
					else if (lithologies[n].congl_grain_size) color = 'rgba(255, 102, 0, 1)';							// CMYK 0,60,100,0 USGS Color 97
					else if (lithologies[n].breccia_grain_size) color = 'rgba(213, 0, 0, 1)';							// CMYK 13,100,100,4

					else color = 'rgba(255, 255, 255, 1)';																											// default white

					fill = new ol.style.Fill();
					fill.setColor(color);
				}
				else {
					// Mudstone/Shale
					if (grainSize === 'clay') color = 'rgba(128, 222, 77, 1)';								// CMYK 50,13,70,0 USGS Color 682
					else if (grainSize === 'mud') color = 'rgba(77, 255, 0, 1)';							// CMYK 70,0,100,0 USGS Color 890
					else if (grainSize === 'silt') color = 'rgba(153, 255, 102, 1)';					// CMYK 40,0,60,0 USGS Color 570
					// Sandstone
					else if (grainSize === 'sand_very_fin') color = 'rgba(255, 255, 179, 1)'; // CMYK 0,0,30,0 USGS Color 40
					else if (grainSize === 'sand_fine_low') color = 'rgba(255, 255, 153, 1)'; // CMYK 0,0,40,0 USGS Color 50
					else if (grainSize === 'sand_fine_upp') color = 'rgba(255, 255, 128, 1)'; // CMYK 0,0,50,0 USGS Color 60
					else if (grainSize === 'sand_medium_l') color = 'rgba(255, 255, 102, 1)'; // CMYK 0,0,60,0 USGS Color 70
					else if (grainSize === 'sand_medium_u') color = 'rgba(255, 255, 77, 1)';	// CMYK 0,0,70,0 USGS Color 80
					else if (grainSize === 'sand_coarse_l') color = 'rgba(255, 255, 0, 1)';	 // CMYK 0,0,100,0 USGS Color 90
					else if (grainSize === 'sand_coarse_u') color = 'rgba(255, 235, 0, 1)';	 // CMYK 0,8,100,0 USGS Color 91
					else if (grainSize === 'sand_very_coa') color = 'rgba(255, 222, 0, 1)';	 // CMYK 0,13,100,0 USGS Color 92
					// Conglomerate
					else if (featureProperties.sed.lithologies[n].primary_lithology === 'siliciclastic' &&
						featureProperties.sed.lithologies[n].siliciclastic_type === 'conglomerate') {
						if (grainSize === 'granule') color = 'rgba(255, 153, 0, 1)';						// CMYK 0,40,100,0 USGS Color 95
						else if (grainSize === 'pebble') color = 'rgba(255, 128, 0, 1)';				// CMYK 0,50,100,0 USGS Color 96
						else if (grainSize === 'cobble') color = 'rgba(255, 102, 0, 1)';				// CMYK 0,60,100,0 USGS Color 97
						else if (grainSize === 'boulder') color = 'rgba(255, 77, 0, 1)';				// CMYK 0,70,100,0 USGS Color 98
					}
					// Breccia
					else if (featureProperties.sed.lithologies[n].primary_lithology === 'siliciclastic' &&
						featureProperties.sed.lithologies[n].siliciclastic_type === 'breccia') {
						if (grainSize === 'granule') color = 'rgba(230, 0, 0, 1)';							// CMYK 10,100,100,0 USGS Color 95
						else if (grainSize === 'pebble') color = 'rgba(204, 0, 0, 1)';					// CMYK 20,100,100,0 USGS Color 96
						else if (grainSize === 'cobble') color = 'rgba(179, 0, 0, 1)';					// CMYK 30,100,100,0 USGS Color 97
						else if (grainSize === 'boulder') color = 'rgba(153, 0, 0, 1)';				 // CMYK 40,100,100,0 USGS Color 98
					}
					// Limestone / Dolostone
					else if (grainSize === 'mudstone') color = 'rgba(77, 255, 128, 1)';			 // CMYK 70,0,50,0 USGS Color 860
					else if (grainSize === 'wackestone') color = 'rgba(77, 255, 179, 1)';		 // CMYK 70,0,30,0 USGS Color 840
					else if (grainSize === 'packstone') color = 'rgba(77, 255, 222, 1)';			// CMYK 70,0,13,0 USGS Color 820
					else if (grainSize === 'grainstone') color = 'rgba(179, 255, 255, 1)';		// CMYK 30,0,0,0 USGS Color 400
					else if (grainSize === 'boundstone') color = 'rgba(77, 128, 255, 1)';		 // CMYK 70,50,0,0 USGS Color 806
					else if (grainSize === 'cementstone') color = 'rgba(0, 179, 179, 1)';		 // CMYK 100,30,30,0 USGS Color 944
					else if (grainSize === 'recrystallized') color = 'rgba(0, 102, 222, 1)';	// CMYK 100,60,13,0 USGS Color 927
					else if (grainSize === 'floatstone') color = 'rgba(77, 255, 255, 1)';		 // CMYK 70,0,0,0 USGS Color 800
					else if (grainSize === 'rudstone') color = 'rgba(77, 204, 255, 1)';			 // CMYK 70,20,0,0 USGS Color 803
					else if (grainSize === 'framestone') color = 'rgba(77, 128, 255, 1)';		 // CMYK 70,50,0,0 USGS Color 806
					else if (grainSize === 'bafflestone') color = 'rgba(77, 128, 255, 1)';		// CMYK 70,50,0,0 USGS Color 806
					else if (grainSize === 'bindstone') color = 'rgba(77, 128, 255, 1)';			// CMYK 70,50,0,0 USGS Color 806
					// Misc. Lithologies
					else if (grainSize === 'evaporite') color = 'rgba(153, 77, 255, 1)';			// CMYK 40,70,0,0 USGS Color 508
					else if (grainSize === 'chert') color = 'rgba(102, 77, 77, 1)';					 // CMYK 60,70,70,0
					else if (grainSize === 'ironstone') color = 'rgba(153, 0, 0, 1)';				 // CMYK 40,100,100,0
					else if (grainSize === 'phosphatic') color = 'rgba(153, 255, 179, 1)';		// CMYK 40,0,30,0
					else if (grainSize === 'volcaniclastic') color = 'rgba(255, 128, 255, 1)';// CMYK 0,50,0,0
					else if (grainSize === 'organic_coal') color = 'rgba(0, 0, 0, 1)';				// CMYK 100,100,100,0 USGS Color 999
					else color = 'rgba(255, 255, 255, 1)';																		// default white

					fill = new ol.style.Fill();
					fill.setColor(color);
				}
			}
		}
		else $log.error('Strat Interval indicated but no lithology for', featureProperties);
		return fill;
	}
	
	function getGrainSize(lithology) {
		return lithology.mud_silt_grain_size || lithology.sand_grain_size ||
			lithology.congl_grain_size || lithology.breccia_grain_size ||
			lithology.dunham_classification || lithology.primary_lithology;
	}
	
	
	
	function textStylePoint(text, rotation) {
		var labelText;
		if ((rotation >= 60 && rotation <= 120) || (rotation >= 240 && rotation <= 300)) {
			labelText = '				 ' + text	// we pad with spaces due to rotational offset
		}
		else labelText = '		 ' + text;
		return new ol.style.Text({
			'font': '12px Calibri,sans-serif',
			'text': labelText,
			'textAlign': 'left',
			'fill': new ol.style.Fill({
				'color': '#000'
			}),
			'stroke': new ol.style.Stroke({
				'color': '#fff',
				'width': 3
			})
		});
	}
	
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
	
	function loadPattern(src) {
		var fill = new ol.style.Fill();
		var canvas = document.createElement('canvas');
		var context = canvas.getContext('2d');
		var img = new Image();
		img.src = 'img/sed/' + src + '.png';
		img.onload = function () {
			canvas.width = img.width;
			canvas.height = img.height;
			var pattern = context.createPattern(img, 'repeat');
			fill.setColor(pattern);
			//if (!_.isEmpty(featureLayer)) featureLayer.getLayersArray()[0].getSource().changed();		// Assumes the intervals on feature layer 0 (and other Spots on layer 1)
		};
		return fill;
	}
	
	function getTagsBySpotId(spotId) {
		return _.filter(loadedFeatures.tags, function (tag) {
			if (tag.spots && _.contains(tag.spots, spotId)) return true;
			else if (tag.features && tag.features[spotId]) return true;
			return false;
		});
	}
	
	function hexToRgb(hex) {
		// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
		hex = hex.replace(shorthandRegex, function (m, r, g, b) {
			return r + r + g + g + b + b;
		});

		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		return result ? {
			r: parseInt(result[1], 16),
			g: parseInt(result[2], 16),
			b: parseInt(result[3], 16)
		} : null;
	}
	
	function toRadians(deg) {
		return deg * (Math.PI / 180);
	}
	
	function getIconForFeature(feature) {
		var feature_type = 'none';
		var rotation = 0;
		var symbol_orientation = 0;
		var facing = undefined;
		var orientation_type = 'none';
		var orientation = feature.get('orientation');
		//var mapPreferences = ProjectFactory.getMapPreferences();
		//if (angular.isUndefined(mapPreferences.show_point_symbology)) mapPreferences.show_point_symbology = true;
		if (orientation) {
			rotation = orientation.strike || (orientation.dip_direction - 90) % 360 || orientation.trend || rotation;
			symbol_orientation = orientation.dip || orientation.plunge || symbol_orientation;
			feature_type = orientation.feature_type || feature_type;
			orientation_type = orientation.type || orientation_type;
			facing = orientation.facing;
		}

		return new ol.style.Icon({
			'anchorXUnits': 'fraction',
			'anchorYUnits': 'fraction',
			'opacity': 1,
			'rotation': toRadians(rotation),
			'src': getSymbolPath(feature_type, symbol_orientation, orientation_type, facing),
			'scale': 0.05
		});
	}
	
	function getStrokeStyle(feature) {
		var color = '#663300';
		var width = 2;
		var lineDash = [1, 0];

		if (feature.get('trace')) {
			var trace = feature.get('trace');

			// Set line color and weight
			if (trace.trace_type && trace.trace_type === 'geologic_struc') {
				color = '#FF0000';
				if (trace.geologic_structure_type
					&& (trace.geologic_structure_type === 'fault' || trace.geologic_structure_type === 'shear_zone')) {
					width = 4;
				}
			}
			else if (trace.trace_type && trace.trace_type === 'contact') {
				color = '#000000';
				if (trace.contact_type && trace.contact_type === 'intrusive'
					&& trace.intrusive_contact_type && trace.intrusive_contact_type === 'dike') {
					width = 4;
				}
			}
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
	
	function sgetSymbolPath(feature_type, orientation, orientation_type, facing) {
		// Set a default symbol by whether feature is planar or linear
		var default_symbol = symbols.default_point;
		if (orientation_type === 'linear_orientation') default_symbol = symbols.lineation_general;

		if (facing && facing === 'overturned' && symbols[feature_type + '_overturned']) {
			return symbols[feature_type + '_overturned'];
		}

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
	
	
	
	
	
	
	
	