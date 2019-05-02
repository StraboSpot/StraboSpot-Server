var addTabValueRow = function (label,value,bordertop){
	var thishtml="";
	if(bordertop){
		borderclass = ' sidebar_value_row_border_top';
	}else{
		borderclass = '';
	}
	thishtml += '<div class="sidebar_value_row'+borderclass+'">';
	if(label!=''){
		thishtml += '<div class="sidebar_value_row_title">'+label+'</div>';
	}
	thishtml += '<div class="sidebar_value_row_value">'+value+'</div>';
	thishtml += '</div>';
	return thishtml;
}

var addRockUnitRow = function (label,value,bordertop){
	var thishtml="";
	if(bordertop){
		borderclass = ' sidebar_value_row_border_top';
	}else{
		borderclass = '';
	}
	thishtml += '<div class="sidebar_value_row'+borderclass+'">';
	thishtml += '<div class="sidebar_value_row_value"><span class="tag_row_label">'+label+'</span> '+value+'</div>';
	thishtml += '</div>';
	return thishtml;
}

var addTabTitleRow = function (label){
	var thishtml="";
	thishtml += '<div class="sidebar_title_row">'+label+'</div>';
	return thishtml;
}

var addTabCategory = function (group, label, modelvars, spotvars){
	var thishtml="";
	var bordertop = false;
	
	thishtml += addTabTitleRow(label);
	
	_.each(modelvars, function(value, key){
		if(spotvars[key]){
			if(spotvars[key]!='not_specified'){
				var thisval = cvFixVal(group,key,spotvars[key]);
				thishtml += addTabValueRow(value,thisval,bordertop);
				bordertop = true;
			}
		}
	});
	
	return thishtml;
}

cvFixVal = function (group,itemname,value){

	var thisval="";
	if(typeof controlledVocab[group+'_'+itemname] != 'undefined'){
		if(typeof controlledVocab[group+'_'+itemname][value] != 'undefined'){
			thisval = controlledVocab[group+'_'+itemname][value];
		}else{
			thisval = value;
		}
	}else{
		thisval = value;
	}
	
	return thisval;

}



/*
*********** Rock Unit ***************************
*/

var hasRockUnit = function(spotid){
	var foundit = false;
	_.each(loadedFeatures.tags, function(tag){
		if(tag.type=='geologic_unit'){
			_.each(tag.spots, function (thisspotid){
				if(thisspotid == spotid){
					foundit = true;
				}
			});
		}
	});
	
	return foundit;
}

var fixSpaces = function(string){

	var newstring = string.replace(/_/g, " ");

	return newstring;
}

var buildRockUnitRows = function(spotid){
	var thishtml = "";
	var showtopbar = false;
	_.each(loadedFeatures.tags, function(tag){
		if(tag.type=='geologic_unit'){
			_.each(tag.spots, function (thisspotid){
				if(thisspotid == spotid){
					thishtml += addRockUnitRow('Geologic Unit',tag.unit_label_abbreviation,showtopbar);
					_.each(tag, function (value, key){
						if(key != "id" && key != "type" && key != "spots" && key != "" && key != "" && key != "" && key != "" && key != ""){
							key = fixSpaces(key);
							thishtml += addRockUnitRow(key,value,showtopbar);
							showtopbar = true;
						}
					});
				}
			});
		}
	});
	return thishtml;
}

var buildOrientations = function(){
	var thishtml = "";
	var bordertop = false;
	if(currentSpot.properties.orientation_data){
		_.each(currentSpot.properties.orientation_data, function(orientation){
			thishtml += addOrientation(orientation,bordertop);
			bordertop = true;
		});
	}else{
		thishtml = "No orientation data for this spot.";
	}
	return thishtml;
}

var buildSamples = function(){
	var thishtml = "";
	var bordertop = false;
	if(currentSpot.properties.samples){
		_.each(currentSpot.properties.samples, function(sample){
			thishtml += addSample(sample,bordertop);
			bordertop = true;
		});
	}else{
		thishtml = "No samples for this spot.";
	}
	return thishtml;
}

var buildOtherFeatures = function(){
	var thishtml = "";
	var bordertop = false;
	if(currentSpot.properties.other_features){
		_.each(currentSpot.properties.other_features, function(of){
			thishtml += addOtherFeature(of,bordertop);
			bordertop = true;
		});
	}else{
		thishtml = "No other features for this spot.";
	}
	return thishtml;
}

var build3DStructures = function(){
	var thishtml = "";
	var bordertop = false;
	if(currentSpot.properties._3d_structures){
		_.each(currentSpot.properties._3d_structures, function(structure){
			thishtml += add3DStructure(structure,bordertop);
			bordertop = true;
		});
	}else{
		thishtml = "No 3D structures for this spot.";
	}
	return thishtml;
}

var buildImages = function(){
	var thishtml = "";
	var bordertop = false;
	if(currentSpot.properties.images){
		_.each(currentSpot.properties.images, function(image){
			thishtml += addImage(image,bordertop);
			bordertop = true;
		});
	}else{
		thishtml = "No images for this spot.";
	}
	return thishtml;
}


var hasFeatureLevelTags = function(spotid){
	var foundit = false;
	_.each(loadedFeatures.tags, function(tag){
		_.each(tag.features, function (value,thisspotid){
			if(thisspotid == spotid){
				foundit = true;
			}
		});
	});
	
	return foundit;
}


var hasSpotLevelTags = function(spotid){
	var foundit = false;
	_.each(loadedFeatures.tags, function(tag){
		_.each(tag.spots, function (thisspotid){
			if(thisspotid == spotid){
				foundit = true;
			}
		});
	});
	
	return foundit;
}


var addTagRow = function (tag,bordertop){

	var thishtml="";
	if(bordertop){
		borderclass = ' sidebar_value_row_border_top';
	}else{
		borderclass = '';
	}
	
	var tagid = tag.id;
	var tagname = tag.name;
	var tagtype = tag.type;
	tagtype = tagtype.replace("_", " ");
	
	//get counts for tag
	var counthtml = '';
	var countdelim = '';

	var spotcount = 0;
	
	if(tag.spots){
		//add check for query
		_.each(tag.spots, function(spotid){
			if(spotFitsQuery(getSpot(spotid))){
				spotcount++;
			}
		});
	}
	
	if(spotcount > 0){
		counthtml = spotcount+" Spots";
		countdelim = ", ";
	}
	
	var featurecount = 0;
	_.each(tag.features, function(feature){
		//featurecount = featurecount + feature.length;
		_.each(feature, function(featureid){
			if(spotFitsQuery(getSpotFromFeatureId(featureid))){
				featurecount++;
			}
		});
	});
	
	if(featurecount > 0){
		counthtml += countdelim+featurecount+" Features";
	}

	thishtml += '<div class="sidebar_tag_row'+borderclass+'" onClick="tagDetail('+tagid+',\'tags_tab\');">';
	thishtml += '<div class="sidebar_value_row_value"><span class="tag_row_label">'+tagname+'</span> '+tagtype+': '+counthtml+'</div>';
	thishtml += '</div>';
	return thishtml;
}


var addTagDetailSpotRow = function (spotid,bordertop){

	var thishtml="";
	if(bordertop){
		borderclass = ' sidebar_value_row_border_top';
	}else{
		borderclass = '';
	}

	var spotname = idToName(spotid);

	thishtml += '<div class="sidebar_tag_row'+borderclass+'" onClick="switchToSpot('+spotid+');">';
	thishtml += '<div class="sidebar_value_row_value"><span class="tag_row_label">'+spotname+'</span></div>';
	thishtml += '</div>';
	return thishtml;
}

var addTagDetailFeatureRow = function (featureid,bordertop){

	var thishtml="";
	if(bordertop){
		borderclass = ' sidebar_value_row_border_top';
	}else{
		borderclass = '';
	}
	
	var featurename = idToName(featureid);

	thishtml += '<div class="sidebar_tag_row'+borderclass+'" onClick="switchToFeature('+featureid+');">';
	thishtml += '<div class="sidebar_value_row_value" ><span class="tag_row_label">'+featurename+'</span></div>';
	thishtml += '</div>';
	return thishtml;
}



var getSpotLevelTags = function(spotid){
	var thishtml="";
	var bordertop = false;
	_.each(loadedFeatures.tags, function(tag){
		_.each(tag.spots, function (thisspotid){
			if(thisspotid == spotid){
				thishtml += addTagRow(tag,bordertop);
				bordertop = true;
			}
		});
	});
	
	return thishtml;
}

var getFeatureLevelTags = function(spotid){
	var thishtml="";
	var bordertop = false;
	_.each(loadedFeatures.tags, function(tag){
		_.each(tag.features, function (value,thisspotid){
			if(thisspotid == spotid){
				thishtml += addTagRow(tag,bordertop);
				bordertop = true;
			}
		});
	});
	
	return thishtml;
}


var tagDetail = function(tagid,target){
	var thishtml = "";
	var thistag = {};
	var spotname = "";
	
	var backfunction = "alert('Error! No Back Function Set.');";
	
	if(target=='tags_tab'){
		backfunction = "updateTagsTab();";
	}else if(target=='orientations_tab'){
		backfunction = "updateOrientationsTab();";
	}else if(target=='_3d_structures_tab'){
		backfunction = "update3DStructuresTab();";
	}else if(target=='samples_tab'){
		backfunction = "updateSamplesTab();";
	}else if(target=='other_features_tab'){
		backfunction = "updateOtherFeaturesTab();";
	}
	
	thishtml = '<div class="back_button" onClick="'+backfunction+'"><img width="20" height="20" src="includes/images/back.png"></img> Back...</div>'
	
	thishtml += addTabTitleRow('Tag');
	
	var bordertop = false;
	_.each(loadedFeatures.tags, function(tag){
		if(tag.id == tagid){
			thistag = tag;
			_.each(tag, function(value, key){
				if(key!='id' && key!='spots' && key!='features'){
					key = key.replace(new RegExp('_', 'g'), ' ');
					thishtml += addTabValueRow(key,value,bordertop);
					bordertop=true;
				}
			});
		}
	});

	bordertop = false;
	if(thistag.spots){
		var spotcount=0;
		_.each(thistag.spots, function(spotid){
			if(spotFitsQuery(getSpot(spotid))){
				spotcount++;
			}
		});
		if(spotcount == 1){
			thishtml += addTabTitleRow('1 Spot');
		}else{
			thishtml += addTabTitleRow(spotcount+' Spots');
		}
		_.each(thistag.spots, function(spot){
			if(spotFitsQuery(getSpot(spot))){
				thishtml += addTagDetailSpotRow(spot,bordertop);
				bordertop = true;
			}
		});
	}

	bordertop = false;
	if(thistag.features){
		
		var featurecount=0;
		_.each(thistag.features, function(featurearray){
			_.each(featurearray, function(featureid){
				if(spotFitsQuery(getSpotFromFeatureId(featureid))){
					featurecount++;
				}
			});
		});
		
		if(featurecount == 1){
			thishtml += addTabTitleRow('1 Feature');
		}else{
			thishtml += addTabTitleRow(featurecount+' Features');
		}

		var featurecount=0;
		_.each(thistag.features, function(featurearray){
			_.each(featurearray, function(feature){
				if(spotFitsQuery(getSpotFromFeatureId(feature))){
					thishtml += addTagDetailFeatureRow(feature,bordertop);
					bordertop = true;
				}
			});
		});

	}


	$("#"+target).html(thishtml);
}


var buildTags = function(){
	var thishtml="";
	if(hasSpotLevelTags(currentSpot.properties.id)){
		thishtml += addTabTitleRow('Spot Level Tags');
		thishtml += getSpotLevelTags(currentSpot.properties.id);
	}

	if(hasFeatureLevelTags(currentSpot.properties.id)){
		thishtml += addTabTitleRow('Feature Level Tags');
		thishtml += getFeatureLevelTags(currentSpot.properties.id);
	}
	

	//console.log(loadedFeatures.tags);
	
	if(thishtml == ""){
		thishtml = "No Tags for this spot.";
	}
	
	return thishtml;
}


var isImageBasemap = function(imageid){
	var found = false;
	_.each(loadedFeatures.image_basemaps, function(ib){
		if(ib==imageid){
			found = true;
		}
	});
	return found;
}

var moreImageInfo = function(imageid){
	var thishtml = "";
	_.each(currentSpot.properties.images, function(image){
		if(image.id==imageid){
			thishtml = addTabCategory('image', 'Image Details', image_vars, image);
		}
	});
	
	if(thishtml!=""){
		thishtml = '<div class="back_button" onClick="updateImagesTab();"><img width="20" height="20" src="includes/images/back.png"></img> Back to Images</div>'+thishtml;
	}else{
		thishtml = "<div>No image data found.</div>";
	}
	
	//$.featherlight('<div>'+thishtml+'</div>');
	$("#images_tab").html('<div>'+thishtml+'</div>');
}

var addImage = function(image,bordertop){
	
	//console.log(image);
	
	var thishtml = "";
	
	thishtml += '<div class="image_row">';
	
	thishtml += '<div class="col image_col">';
	
	thishtml += '<img src="https://strabospot.org/mapimage/'+image.id+'.jpg" width="100" height="100" onClick="$.featherlight(\'https://strabospot.org/mapimage/'+image.id+'.jpg\',{closeOnClick:\'anywhere\'});">';
	
	thishtml += '</div>';

	thishtml += '<div class="col">';
	
	
	
	if(image.title){
		var title = image.title;
		if(title.length > 30){
			title = title.substring(0,30)+'...';
		}
		thishtml += '<div class="image_title">'+title+'</div>';
	}
	
	if(image.caption){
		var caption = image.caption;
		if(caption.length > 30){
			caption = caption.substring(0,30)+'...';
		}
		thishtml += '<div class="image_caption">'+caption+'</div>';
	}
	
	thishtml += '<div onClick="moreImageInfo('+image.id+');" class="image_info_button"><img src="includes/images/info.png" width="15" height="15"> More Info</div>';
	
	if(isImageBasemap(image.id)){
		thishtml += '<div onClick="switchToImageBasemap('+image.id+');" class="map_icon"><img src="includes/images/map.png" width="15" height="15"> Image Basemap</div>';
	}
	
	thishtml += '</div>';
	
	thishtml += '</div>';
	
	return thishtml;
}

var addDetailValueRow = function(key,value){
	thishtml = '<div><span class="detail_bold">'+key+'</span> : '+value+'</div>';
	return thishtml;
}

//tagDetail('+tagid+',\'tags_tab\');

var getFeatureTags = function(oid,tagstab){
	var thishtml = "";
	var thisname = "";
	var thisdiv = "";
	var foundtags = [];
	_.each(loadedFeatures.tags, function(tag){
		if(tag.features){
			_.each(tag.features, function(feat){
				_.each(feat, function(thisfeat){
					if(thisfeat == oid){
						thisname=tag.name;
						thisdiv='<span class="tinytag" onclick="tagDetail('+tag.id+',\''+tagstab+'\');">'+tag.name+'</span>';
						foundtags.push(thisdiv);
					}
				});
			})
		}
	});
	
	if(foundtags.length > 0){
		thishtml = '<div class = "tags_italic">'+'Tags: '+foundtags.join(', ')+'</div>';
	}
	
	return thishtml;
}

var addOrientation = function (o,bordertop){
	var thishtml="";

	//console.log(o);
	if(o.orientation_type){
		o.type=o.orientation_type;
	}

	var thisborderclass = "";
	if(bordertop){
		thisborderclass = " sidebar_value_row_border_top";
	}
	
	var groupvars = "";
	if(o.type=="planar_orientation") groupvars = planar_orientation_vars;
	if(o.type=="linear_orientation") groupvars = linear_orientation_vars;
	if(o.type=="tabular_zone_orientation") groupvars = tabular_zone_orienation_vars;

	thishtml += '<div class = "sidebar_value_row'+thisborderclass+'">';
	
	var thislabel="";
	if(o.label){
		thislabel = o.label;
	}else{
		thislabel = o.type;
	}
	
	thishtml += '<div class = "sidebar_value_row_title">'+thislabel+'</div>';
	//thishtml += '<div class = "tags_italic">'+'Tags: tag one, tag two, tag three</div>';

	thishtml += getFeatureTags(o.id,'orientations_tab');
	
	thishtml += '<div class = "detail_pad">';
	
	_.each(groupvars, function(value, key){
		if(o[key]){
			if(key!='label'){
				var thisval = cvFixVal(o.type,key,o[key]);
				thishtml += addDetailValueRow(value,thisval);
				bordertop = true;
			}
		}
	});
	
	if(o.associated_orientation){
		_.each(o.associated_orientation, function(assoc){
			thishtml += addDetailValueRow('Associated Orientation','');
			thishtml += addOrientation(assoc);
		});
	}
	
	thishtml += '</div>';
	thishtml += '</div>';
	
	return thishtml;
}

var addSample = function (samp,bordertop){
	var thishtml="";

	//console.log(o);

	var thisborderclass = "";
	if(bordertop){
		thisborderclass = " sidebar_value_row_border_top";
	}
	
	var groupvars = sample_vars;

	thishtml += '<div class = "sidebar_value_row'+thisborderclass+'">';
	
	thishtml += '<div class = "sidebar_value_row_title">'+samp.label+'</div>';
	//thishtml += '<div class = "tags_italic">'+'Tags: tag one, tag two, tag three</div>';

	thishtml += getFeatureTags(samp.id,'samples_tab');
	
	thishtml += '<div class = "detail_pad">';
	
	_.each(groupvars, function(value, key){
		if(samp[key]){
			if(key!='label'){
				var thisval = cvFixVal(samp.type,key,samp[key]);
				thishtml += addDetailValueRow(value,thisval);
				bordertop = true;
			}
		}
	});
	
	thishtml += '</div>';
	thishtml += '</div>';
	
	return thishtml;
}

var addOtherFeature = function (otherfeature,bordertop){
	var thishtml="";

	//console.log(o);

	var thisborderclass = "";
	if(bordertop){
		thisborderclass = " sidebar_value_row_border_top";
	}
	
	var groupvars = other_features_vars;

	thishtml += '<div class = "sidebar_value_row'+thisborderclass+'">';
	
	thishtml += '<div class = "sidebar_value_row_title">'+otherfeature.label+'</div>';
	//thishtml += '<div class = "tags_italic">'+'Tags: tag one, tag two, tag three</div>';

	thishtml += getFeatureTags(otherfeature.id,'other_features_tab');
	
	thishtml += '<div class = "detail_pad">';

	_.each(groupvars, function(value, key){
		if(otherfeature[key]){
			if(otherfeature!='label'){
				var thisval = cvFixVal(otherfeature.type,key,otherfeature[key]);
				thishtml += addDetailValueRow(value,thisval);
				bordertop = true;
			}
		}
	});
	
	thishtml += '</div>';
	thishtml += '</div>';
	
	return thishtml;
}
var add3DStructure = function (structure,bordertop){
	var thishtml="";

	//console.log(o);

	var thisborderclass = "";
	if(bordertop){
		thisborderclass = " sidebar_value_row_border_top";
	}
	
	var groupvars = "";
	if(structure.type=="fabric") groupvars = fabric_vars;
	if(structure.type=="fold") groupvars = fold_vars;
	if(structure.type=="tensor") groupvars = tensor_vars;
	if(structure.type=="other_3d_structure") groupvars = other_3d_structure_vars;

	thishtml += '<div class = "sidebar_value_row'+thisborderclass+'">';
	
	thishtml += '<div class = "sidebar_value_row_title">'+structure.label+'</div>';
	//thishtml += '<div class = "tags_italic">'+'Tags: tag one, tag two, tag three</div>';

	thishtml += getFeatureTags(structure.id,'_3d_structures_tab');
	
	thishtml += '<div class = "detail_pad">';
	
	_.each(groupvars, function(value, key){
		if(structure[key]){
			if(key!='label'){
				var thisval = cvFixVal(structure.type,key,structure[key]);
				thishtml += addDetailValueRow(value,thisval);
				bordertop = true;
			}
		}
	});
	
	thishtml += '</div>';
	thishtml += '</div>';
	
	return thishtml;
}

/*
*********** Spot Tab ***************************
*/

var updateSpotTab = function(){
	
	var tabhtml = "";

	tabhtml += addTabValueRow('Spot Name',spotNames[clickedMapFeature]);
	//tabhtml += addTabValueRow('Spot Name','foofoofoo');
	
	tabhtml += addTabTitleRow('Geography');
	tabhtml += addTabValueRow('Geometry',currentSpot.geometry.type);
	
	if(currentSpot.geometry.type=='Point'){
		tabhtml += addTabValueRow('Latitude',currentSpot.geometry.coordinates[1],true);
		tabhtml += addTabValueRow('Longitude',currentSpot.geometry.coordinates[0],true);
	}
	
	if(currentSpot.properties.surface_feature){
		tabhtml += addTabCategory('surface_feature', 'Surface Feature',surface_feature_vars,currentSpot.properties.surface_feature);
	}

	if(currentSpot.properties.trace){
		tabhtml += addTabCategory('trace', 'Trace',trace_vars,currentSpot.properties.trace);
	}

	if(hasRockUnit(currentSpot.properties.id)){
		tabhtml += addTabTitleRow('Rock Unit');
		tabhtml += buildRockUnitRows(currentSpot.properties.id);
	}

	if(currentSpot.properties.notes){
		tabhtml += addTabTitleRow('Notes');
		tabhtml += addTabValueRow('',currentSpot.properties.notes);
	}

	tabhtml += addTabTitleRow('Other');
	tabhtml += addTabValueRow('ID',currentSpot.properties.id,false);
	tabhtml += addTabValueRow('Date',currentSpot.properties.time.substring(0, 10),true);
	tabhtml += addTabValueRow('Time',currentSpot.properties.time.substring(11,19)+' UTC',true);

	tabhtml += addTabTitleRow('Spot Owner');
	tabhtml += addTabValueRow('',currentSpot.properties.owner);
	
	$("#spot_tab").html(tabhtml);
}



var getChildren = function(thisSpot) {
	var childrenSpots = [];
	if (thisSpot.properties.images) {
		var imageBasemaps = _.map(thisSpot.properties.images, function (image) {
			return image.id;
		});
		childrenSpots = _.filter(loadedFeatures.features, function (spot) {
			return _.contains(imageBasemaps, spot.properties.image_basemap);
		});
	}
	// Only non-point features can have children
	if (_.propertyOf(thisSpot.geometry)('type')) {
		if (_.propertyOf(thisSpot.geometry)('type') !== 'Point') {
			var otherSpots = _.reject(loadedFeatures.features, function (spot) {
				return spot.properties.id === thisSpot.properties.id || !spot.geometry;
			});
			_.each(otherSpots, function (spot) {
				// If Spot is a point and is inside thisSpot then Spot is a child
				if (_.propertyOf(spot.geometry)('type') === 'Point') {
					if (turf.inside(spot, thisSpot)){
						if(spotFitsQuery(spot)){
							childrenSpots.push(spot);
						}
					}
				}
				// If Spot is not a point and all of its points are inside thisSpot then Spot is a child
				else {
					var points = turf.explode(spot);
					if (points.features) {
						var pointsInside = [];
						_.each(points.features, function (point) {
							if (turf.inside(point, thisSpot)) pointsInside.push(point);
						});
						if (points.features.length === pointsInside.length){
							if(spotFitsQuery(spot)){
								childrenSpots.push(spot);
							}
						}
					}
				}
			});
		}
	}
	return childrenSpots;
}

var getParents = function(thisSpot) {
	var parentSpots = [];
	if (thisSpot.properties.image_basemap) {

		_.each(loadedFeatures.features, function(spot){
			if(spot.properties.images){
				_.each(spot.properties.images, function(image){
					if(image.id == thisSpot.properties.image_basemap){
						parentSpots.push(spot);
					}
				});
			}
		});

	}else{
		var otherSpots = _.reject(loadedFeatures.features, function (spot) {
			return spot.properties.id === thisSpot.properties.id || !spot.geometry;
		});
		if (_.propertyOf(thisSpot.geometry)('type')) {
			if (_.propertyOf(thisSpot.geometry)('type') === 'Point') {
				// If thisSpot is a point and the point is inside a polygon Spot then that polygon Spot is a parent
				_.each(otherSpots, function (spot) {
					if (_.propertyOf(spot.geometry)('type') === 'Polygon' || _.propertyOf(spot.geometry)(
							'type') === 'MutiPolygon') {
						if (turf.inside(thisSpot, spot)){
							if(spotFitsQuery(spot)){
								parentSpots.push(spot);
							}
						}
					}
				});
			}else{
				// If thisSpot is a line or polygon and all of its points are inside a feature then
				// that feature is a parent of this Spot
				var points = turf.explode(thisSpot);
				if (points.features) {
					_.each(otherSpots, function (spot) {
						
						if (_.propertyOf(spot.geometry)('type') !== 'Point') {
							var pointsInside = [];
							_.each(points.features, function (point) {
								if (turf.inside(point, spot)) pointsInside.push(point);
							});
							if (points.features.length === pointsInside.length){
								if(spotFitsQuery(spot)){
									parentSpots.push(spot);
								}
							}
						}
					});
				}
			}
		}
	}
	return parentSpots;
}

var getSpotIcon = function(spot){
	var thishtml="";
	if(spot.properties.image_basemap){
		if(spot.geometry.type == "Polygon" || spot.geometry.type == "Polygon"){
			thishtml = '<img class="geometry-icon" src="includes/images/polygon-image-basemap.png"/>';
		}else if(spot.geometry.type == "LineString" || spot.geometry.type == "MultiLineString"){
			thishtml = '<img class="geometry-icon" src="includes/images/line-image-basemap.png"/>';
		}else if(spot.geometry.type == "Point" || spot.geometry.type == "MultiPoint"){
			thishtml = '<img class="geometry-icon" src="includes/images/point-image-basemap.png"/>';
		}
	}else{
		if(spot.geometry.type == "Polygon" || spot.geometry.type == "Polygon"){
			thishtml = '<img class="geometry-icon" src="includes/images/polygon.png"/>';
		}else if(spot.geometry.type == "LineString" || spot.geometry.type == "MultiLineString"){
			thishtml = '<img class="geometry-icon" src="includes/images/line.png"/>';
		}else if(spot.geometry.type == "Point" || spot.geometry.type == "MultiPoint"){
			thishtml = '<img class="geometry-icon" src="includes/images/point.png"/>';
		}
	}
	return thishtml;
}

var buildNesting = function(){

	var thishtml = "";

	var parents = getParents(currentSpot);
	var children = getChildren(currentSpot);

	if(parents.length > 0){
		console.log(parents);
		_.each(parents, function(spot){
			thishtml += '<div class = "nest_title pointer" onClick = "switchToSpot('+spot.properties.id+');">'+getSpotIcon(spot)+' '+spot.properties.name+'</div>';
		});
		thishtml += '<div class = "nest_down_arrow"><i class="ion-arrow-down-c padding-left"></i></div>';
	}
	
	if(parents.length > 0 || children.length > 0){
		thishtml += '<div class = "nest_title">'+getSpotIcon(currentSpot)+'This Spot ('+currentSpot.properties.name+')</div>';
	}

	if(children.length > 0){
		thishtml += '<div class = "nest_down_arrow"><i class="ion-arrow-down-c padding-left"></i></div>';
		_.each(children, function(spot){
			thishtml += '<div class = "nest_title pointer" onClick = "switchToSpot('+spot.properties.id+');">'+getSpotIcon(spot)+' '+spot.properties.name+'</div>';
		});
	}

	if(thishtml==""){
		thishtml = 'This spot is not nested.';
	}
	
	return thishtml;

}

/*
*********** Orientations Tab ***************************
*/

var updateOrientationsTab = function(){
	
	var tabhtml = "";
	
	tabhtml += buildOrientations();
	
	$("#orientations_tab").html(tabhtml);
}

/*
*********** Samples Tab ***************************
*/

var updateSamplesTab = function(){
	
	var tabhtml = "";
	
	tabhtml += buildSamples();
	
	$("#samples_tab").html(tabhtml);
}

/*
*********** Other Features Tab ***************************
*/

var updateOtherFeaturesTab = function(){
	
	var tabhtml = "";
	
	tabhtml += buildOtherFeatures();
	
	$("#other_features_tab").html(tabhtml);
}

/*
*********** 3D Structures Tab ***************************
*/

var update3DStructuresTab = function(){
	
	var tabhtml = "";
	
	tabhtml += build3DStructures();
	
	$("#_3d_structures_tab").html(tabhtml);
}

/*
*********** Images Tab ***************************
*/

var updateImagesTab = function(){
	
	var tabhtml = "";
	
	tabhtml += buildImages();
	
	$("#images_tab").html(tabhtml);
}

/*
*********** Tags Tab ***************************
*/

var updateTagsTab = function(){
	
	var tabhtml = "";
	
	tabhtml += buildTags();
	
	$("#tags_tab").html(tabhtml);
}

/*
*********** Nesting Tab ***************************
*/

var updateNestingTab = function(){
	
	var tabhtml = "";
	
	tabhtml += buildNesting();
	
	$("#nesting_tab").html(tabhtml);
}






