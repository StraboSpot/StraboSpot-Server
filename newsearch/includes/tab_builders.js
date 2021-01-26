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

var addTabValueNotesRow = function (label, value, bordertop){
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
	thishtml += '<div class="sidebar_value_row_value"><pre>'+value+'</pre></div>';
	thishtml += '</div>';
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
	
	thishtml = '<div class="back_button" onClick="'+backfunction+'"><img width="20" height="20" src="/search/includes/images/back.png"></img> Back...</div>'
	
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
		thishtml = '<div class="back_button" onClick="updateImagesTab();"><img width="20" height="20" src="/search/includes/images/back.png"></img> Back to Images</div>'+thishtml;
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
	
	thishtml += '<div onClick="moreImageInfo('+image.id+');" class="image_info_button"><img src="/search/includes/images/info.png" width="15" height="15"> More Info</div>';
	
	if(isImageBasemap(image.id)){
		thishtml += '<div onClick="switchToImageBasemap('+image.id+');" class="map_icon"><img src="/search/includes/images/map.png" width="15" height="15"> Image Basemap</div>';
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

var buildNotes = function(){
	var thishtml = "";
	if(currentSpot.properties.notes){
		thishtml += '<div class="sidebar_value_row_title">Notes</div>';
		thishtml += addTabValueRow('','<pre>'+currentSpot.properties.notes+'</pre>');
	}else{
		thishtml = "No notes for this spot.";
	}
	return thishtml;
}

var buildIgMet = function(){
	var thishtml = "";
	if(currentSpot.properties.pet){
		var pet = currentSpot.properties.pet;
		
		var showigmet = false;
		
		if(pet.rock_type){
			thishtml += addTabValueRow('Rock Type(s)', pet.rock_type.join(", "), false);
			showigmet = true;
		}
		
		
		if(pet.metamorphic_rock_type){
			thishtml += addTabTitleRow('Metamorphic');
			if(pet.metamorphic_rock_type){
				thishtml += addTabValueRow('Metamorphic Rock Type(s)', pet.metamorphic_rock_type.join(", "), true);
			}
			if(pet.protolith){
				thishtml += addTabValueRow('Protolith', pet.protolith, true);
			}
			if(pet.facies){
				thishtml += addTabValueRow('Facies', pet.facies.join(", "), true);
			}
			if(pet.zone){
				thishtml += addTabValueRow('Zone', pet.zone.join(", "), true);
			}
			if(pet.notes_metamorphic){
				thishtml += addTabValueNotesRow("Notes", pet.notes_metamorphic, true);
			}
			showigmet = true;
		}
		
		if(pet.ore_type){
			thishtml += addTabTitleRow('Alteration, Ore');
			if(pet.ore_type){
				thishtml += addTabValueRow('Alteration, Ore Rock Type', pet.ore_type, false);
			}
			if(pet.hydrothermal_alteration){
				thishtml += addTabValueRow('Hydrothermal Alteration(s)', pet.hydrothermal_alteration.join(", "), true);
			}
			if(pet.alteration_host_rock){
				thishtml += addTabValueRow('Host Rock', pet.alteration_host_rock, false);
			}
			if(pet.magmatic_fluid_source){
				thishtml += addTabValueRow('Magmatic Fluid Source', pet.magmatic_fluid_source, false);
			}
			if(pet.mineralized_elements){
				thishtml += addTabValueRow('Mineralized Elements', pet.mineralized_elements, false);
			}
			if(pet.notes_ore){
				thishtml += addTabValueNotesRow("Notes", pet.notes_ore, true);
			}
			showigmet = true;
		}
		
		
		if(pet.minerals){
			thishtml += addTabTitleRow('Minerals');
			thishtml += buildIgMetMinerals(pet.minerals);
			showigmet = true;
		}
		
		if(pet.reactions){
			thishtml += addTabTitleRow('Reactions');
			thishtml += buildIgMetReactions(pet.reactions);
			showigmet = true;
		}

		if(!showigmet){
			thishtml = "No Ig/Met data for this spot.";
		}


		
	}else{
		thishtml = "No Ig/Met data for this spot.";
	}
	return thishtml;
}

var buildIgMetMinerals = function(inMinerals){
	var thishtml = "";
	var showTopBorder = false;
	//addTabValueRow = function (label,value,bordertop){
	_.each(inMinerals, function (mineral) {
		var thisMineral = "";
		if(mineral.mineral_abbrev){ thisMineral += "<div>Mineral Name Abbreviation: " + mineral.mineral_abbrev + "</div>"; }
		if(mineral.full_mineral_name){ thisMineral += "<div>Full Mineral Name: " + mineral.full_mineral_name + "</div>"; }
		if(mineral.habit){ thisMineral += "<div>Habit: " + mineral.habit + "</div>"; }
		if(mineral.textural_setting_igneous){ thisMineral += "<div>Textural Setting - Igneous: " + mineral.textural_setting_igneous.join(", ") + "</div>"; }
		if(mineral.textural_setting_metamorphic){ thisMineral += "<div>Textural Setting - Metamorphic: " + mineral.textural_setting_metamorphic.join(", ") + "</div>"; }
		if(mineral.average_grain_size_mm){ thisMineral += "<div>Average Grain Size (mm): " + mineral.average_grain_size_mm + "</div>"; }
		if(mineral.maximum_grain_size_mm){ thisMineral += "<div>Maximum Grain Size (mm): " + mineral.maximum_grain_size_mm + "</div>"; }
		if(mineral.modal){ thisMineral += "<div>Modal: " + mineral.modal + "</div>"; }
		if(mineral.mineral_notes){ thisMineral += "<div>Notes: " + mineral.mineral_notes + "</div>"; }

		
		
		thishtml += addTabValueRow(mineral.full_mineral_name, thisMineral, showTopBorder);
		showTopBorder = true;
	});
	
	return thishtml;
}

var buildIgMetReactions = function(inReactions){
	var thishtml = "";
	var showTopBorder = false;
	//addTabValueRow = function (label,value,bordertop){
	_.each(inReactions, function (reaction) {
		var thisReaction = "";
		if(reaction.based_on){ thisReaction += "<div>Based On: " + reaction.based_on.join(", ") + "</div>"; }
		if(reaction.notes){ thisReaction += "<div>Notes: " + reaction.notes + "</div>"; }

		thishtml += addTabValueRow(reaction.reactions, thisReaction, showTopBorder);
		showTopBorder = true;
	});
	
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
		//tabhtml += addTabTitleRow('Notes');
		//tabhtml += addTabValueRow('','<pre>'+currentSpot.properties.notes+'</pre>');
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
			thishtml = '<img class="geometry-icon" src="/search/includes/images/polygon-image-basemap.png"/>';
		}else if(spot.geometry.type == "LineString" || spot.geometry.type == "MultiLineString"){
			thishtml = '<img class="geometry-icon" src="/search/includes/images/line-image-basemap.png"/>';
		}else if(spot.geometry.type == "Point" || spot.geometry.type == "MultiPoint"){
			thishtml = '<img class="geometry-icon" src="/search/includes/images/point-image-basemap.png"/>';
		}
	}else{
		if(spot.geometry.type == "Polygon" || spot.geometry.type == "Polygon"){
			thishtml = '<img class="geometry-icon" src="/search/includes/images/polygon.png"/>';
		}else if(spot.geometry.type == "LineString" || spot.geometry.type == "MultiLineString"){
			thishtml = '<img class="geometry-icon" src="/search/includes/images/line.png"/>';
		}else if(spot.geometry.type == "Point" || spot.geometry.type == "MultiPoint"){
			thishtml = '<img class="geometry-icon" src="/search/includes/images/point.png"/>';
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

/*
*********** Notes Tab ***************************
*/

var updateNotesTab = function(){
	
	var tabhtml = "";
	
	tabhtml += buildNotes();
	
	$("#notes_tab").html(tabhtml);
}

/*
*********** Ig/Met Tab ***************************
*/

var updateIgMetTab = function(){
	var tabhtml = "";
	tabhtml += buildIgMet();
	$("#igmet_tab").html(tabhtml);
}

/************ strat_section Tab ****************************/
var updateStratSectionTab = function(){
	var tabhtml = "";
	tabhtml += buildStratSection();
	$("#strat_section_tab").html(tabhtml);
}


/************ sed_lithologies Tab ****************************/
var updateSedLithologiesTab = function(){
	var tabhtml = "";
	tabhtml += buildSedLithologies();
	$("#sed_lithologies_tab").html(tabhtml);
}


/************ sed_bedding Tab ****************************/
var updateSedBeddingTab = function(){
	var tabhtml = "";
	tabhtml += buildSedBedding();
	$("#sed_bedding_tab").html(tabhtml);
}


/************ sed_structures Tab ****************************/
var updateSedStructuresTab = function(){
	var tabhtml = "";
	tabhtml += buildSedStructures();
	$("#sed_structures_tab").html(tabhtml);
}


/************ sed_diagenesis Tab ****************************/
var updateSedDiagenesisTab = function(){
	var tabhtml = "";
	tabhtml += buildSedDiagenesis();
	$("#sed_diagenesis_tab").html(tabhtml);
}


/************ sed_fossils Tab ****************************/
var updateSedFossilsTab = function(){
	var tabhtml = "";
	tabhtml += buildSedFossils();
	$("#sed_fossils_tab").html(tabhtml);
}

/************ sed_interpretations Tab ****************************/
var updateSedInterpretationsTab = function(){
	var tabhtml = "";
	tabhtml += buildSedInterpretations();
	$("#sed_interpretations_tab").html(tabhtml);
}





















































var buildStratSection = function(){
	tabhtml = "";
	if(currentSpot.properties.sed){
		if(currentSpot.properties.sed.strat_section  && currentSpot.properties.sed.column_profile != ""){
			var content = currentSpot.properties.sed.strat_section;
			
			tabhtml += '<div class="sidebar_value_row">';
			tabhtml += '<div onClick="switchToStratSection('+currentSpot.properties.sed.strat_section.strat_section_id+');" class="map_icon"><img src="/search/includes/images/map.png" width="15" height="15"> View Strat Section</div>';

			tabhtml += '<div onClick="window.open(\'https://strabospot.org/pstrat_section?id='+currentSpot.properties.id+'&did='+getDatasetIdForSpotId(currentSpot.properties.id)+'\');" class="map_icon"><img src="/search/includes/images/download.png" width="15" height="15"> Download Strat Section</div>';
			
			tabhtml += '</div>';
			
			if(content.section_well_name){
				tabhtml += addTabValueRow('Section/Well Name', content.section_well_name, false);
			}

			if(content.column_profile){
				tabhtml += addTabValueRow('Column Profile', content.column_profile, true);
			}

			if(content.column_y_axis_units){
				tabhtml += addTabValueRow('Column Y-Axis Units', content.column_y_axis_units, true);
			}

			if(content.section_type){
				tabhtml += addTabValueRow('Section Type', content.section_type, true);
			}

			if(content.what_core_repository){
				tabhtml += addTabValueRow('What Core Repository?', content.what_core_repository, true);
			}

			if(content.type_of_corer){
				tabhtml += addTabValueRow('Type of corer', content.type_of_corer, true);
			}

			if(content.depth_from_surface_to_start_of){
				tabhtml += addTabValueRow('Depth from surface to start of core', content.depth_from_surface_to_start_of, true);
			}

			if(content.total_core_length){
				tabhtml += addTabValueRow('Total core length', content.total_core_length, true);
			}

			if(content.location_locality){
				tabhtml += addTabValueRow('Location/Locality', content.location_locality, true);
			}

			if(content.basin){
				tabhtml += addTabValueRow('Basin', content.basin, true);
			}

			if(content.age){
				tabhtml += addTabValueRow('Age', content.age, true);
			}

			if(content.purpose){
				tabhtml += addTabValueRow('Purpose', content.purpose.join(', '), true);
			}

			if(content.other_purpose){
				tabhtml += addTabValueRow('Other Purpose', content.other_purpose, true);
			}

			if(content.project_description){
				tabhtml += addTabValueRow('Project Description', content.project_description, true);
			}

			if(content.dates_of_work){
				tabhtml += addTabValueRow('Dates of Work', content.dates_of_work, true);
			}

			if(content.scale_of_interest){
				tabhtml += addTabValueRow('Scale of Interest', content.scale_of_interest.join(', '), true);
			}

			if(content.other_scale_of_interest){
				tabhtml += addTabValueRow('Other Scale of Interest', content.other_scale_of_interest, true);
			}

			if(content.obs_interval_bed_obs_scale){
				tabhtml += addTabValueRow('Observation Interval (average bed/observation scale)', content.obs_interval_bed_obs_scale, true);
			}

			if(content.how_is_section_georeferenced){
				tabhtml += addTabValueRow('How is the Section Georeferenced?', content.how_is_section_georeferenced, true);
			}

			if(content.strat_section_notes){
				tabhtml += addTabValueRow('Notes', content.strat_section_notes, true);
			}

		}
	}
	if(tabhtml==""){tabhtml="No Strat Section for this spot.";}
	return tabhtml;
}

var buildSedLithologies = function(){
	console.log(currentSpot);
	tabhtml = "";
	if(currentSpot.properties.sed){
		if(currentSpot.properties.sed.lithologies){
			_.each(currentSpot.properties.sed.lithologies, function(content){
			
				tabhtml += addTabTitleRow('Primary Lithology');

				if(content.primary_lithology){
					tabhtml += addTabValueRow('Primary Lithology', content.primary_lithology, false);
				}

				if(content.siliciclastic_type){
					tabhtml += addTabValueRow('Siliciclastic Type', content.siliciclastic_type, true);
				}

				if(content.dunham_classification){
					tabhtml += addTabValueRow('Dunham Classification', content.dunham_classification, true);
				}

				if(content.grain_type){
					tabhtml += addTabValueRow('Grain Type', content.grain_type, true);
				}

				if(content.evaporite_type){
					tabhtml += addTabValueRow('Evaporite type', content.evaporite_type.join(', '), true);
				}

				if(content.other_evaporite_type){
					tabhtml += addTabValueRow('Other Evaporite Type', content.other_evaporite_type, true);
				}

				if(content.organic_coal_lithologies){
					tabhtml += addTabValueRow('Organic/Coal Lithologies', content.organic_coal_lithologies.join(', '), true);
				}

				if(content.other_organic_coal_lithology){
					tabhtml += addTabValueRow('Other Organic/Coal Lithology', content.other_organic_coal_lithology, true);
				}

				if(content.volcaniclastic_type){
					tabhtml += addTabValueRow('Volcaniclastic type', content.volcaniclastic_type.join(', '), true);
				}

				if(content.other_volcaniclastic_type){
					tabhtml += addTabValueRow('Other Volcaniclastic Type', content.other_volcaniclastic_type, true);
				}

				if(content.report_presence_of_particle_ag){
					tabhtml += addTabValueRow('Report presence of particle aggregates ', content.report_presence_of_particle_ag, true);
				}

				if(content.componentry){
					tabhtml += addTabValueRow('Componentry', content.componentry.join(', '), true);
				}

				if(content.approximate_relative_abundance){
					tabhtml += addTabValueRow('Approximate relative abundances of clasts', content.approximate_relative_abundance, true);
				}

				if(content.phosphorite_type){
					tabhtml += addTabValueRow('Phosphorite type', content.phosphorite_type.join(', '), true);
				}

				if(content.other_phosphorite_type){
					tabhtml += addTabValueRow('Other Phosphorite Type', content.other_phosphorite_type, true);
				}

				tabhtml += addTabTitleRow('Lithification & Color');

				if(content.relative_resistance_weather){
					tabhtml += addTabValueRow('Relative resistance (weathering profile)', content.relative_resistance_weather, true);
				}

				if(content.lithification){
					tabhtml += addTabValueRow('Lithification', content.lithification, true);
				}

				if(content.evidence_of_deposit_alteration){
					tabhtml += addTabValueRow('Evidence of deposit alteration', content.evidence_of_deposit_alteration, true);
				}

				if(content.evidence_of_clast_alteration){
					tabhtml += addTabValueRow('Evidence of clast alteration', content.evidence_of_clast_alteration, true);
				}

				if(content.fresh_color){
					tabhtml += addTabValueRow('Fresh Color', content.fresh_color, true);
				}

				if(content.weathered_color){
					tabhtml += addTabValueRow('Weathered Color', content.weathered_color, true);
				}

				if(content.color_appearance){
					tabhtml += addTabValueRow('Color Appearance', content.color_appearance.join(', '), true);
				}

				if(content.other_color_appearance){
					tabhtml += addTabValueRow('Other Color Appearance', content.other_color_appearance, true);
				}

				if(content.notes){
					tabhtml += addTabValueRow('Notes', content.notes, true);
				}
			
			});
		}
	}
	if(tabhtml==""){tabhtml="No Lithologies for this spot.";}
	return tabhtml;
}

var buildSedBedding = function(){
	tabhtml = "";
	
	if(currentSpot.properties.sed){
		if(currentSpot.properties.sed.bedding){
			
			var content = currentSpot.properties.sed.bedding;
			
			if(content.interbed_proportion_change){
				tabhtml += addTabValueRow('Interbed Proportion Change (Up Section)', content.interbed_proportion_change, false);
			}

			if(content.interbed_proportion){
				tabhtml += addTabValueRow('Lithology 1: Interbed Relative Proportion (%)', content.interbed_proportion, true);
			}

			if(content.lithology_at_bottom_contact){
				tabhtml += addTabValueRow('Which Lithology is at Bottom Contact?', content.lithology_at_bottom_contact, true);
			}

			if(content.lithology_at_top_contact){
				tabhtml += addTabValueRow('Which Lithology is at Top Contact?', content.lithology_at_top_contact, true);
			}

			if(content.thickness_of_individual_beds){
				tabhtml += addTabValueRow('Thickness of Individual Beds', content.thickness_of_individual_beds, true);
			}

			if(content.package_thickness_units){
				tabhtml += addTabValueRow('Package Beds Thickness Units', content.package_thickness_units, true);
			}

			if(content.package_bedding_trends){
				tabhtml += addTabValueRow('Package Bedding Trends', content.package_bedding_trends, true);
			}

			if(content.other_bedding_trend){
				tabhtml += addTabValueRow('Other Package Bedding Trend', content.other_bedding_trend, true);
			}
			
			if(currentSpot.properties.sed.bedding.beds){
				_.each(currentSpot.properties.sed.bedding.beds, function(content){
			
				tabhtml += addTabTitleRow('Bed');
			
				if(content.package_geometry){
					tabhtml += addTabValueRow('Bed Geometry', content.package_geometry.join(', '), false);
				}

				tabhtml += addTabTitleRow('Lower Contact');

				if(content.shape_of_lower_contacts){
					tabhtml += addTabValueRow('"Shape of lower contact (if variable', content.shape_of_lower_contacts.join(', '), true);
				}

				if(content.character_of_lower_contacts){
					tabhtml += addTabValueRow('"Character of lower contact(s) (if variable', content.character_of_lower_contacts.join(', '), true);
				}

				if(content.lower_contact_relief){
					tabhtml += addTabValueRow('Lower contact relief', content.lower_contact_relief, true);
				}

				tabhtml += addTabTitleRow('Upper Contact');

				if(content.shape_of_upper_contacts){
					tabhtml += addTabValueRow('"Shape of upper contact (if variable', content.shape_of_upper_contacts.join(', '), true);
				}

				if(content.character_of_upper_contacts){
					tabhtml += addTabValueRow('"Character of upper contact (if variable', content.character_of_upper_contacts.join(', '), true);
				}

				if(content.upper_contact_relief){
					tabhtml += addTabValueRow('Upper Contact Relief', content.upper_contact_relief, true);
				}

				tabhtml += addTabTitleRow('Interbed Thickness');

				if(content.avg_thickness){
					tabhtml += addTabValueRow('Average Thickness', content.avg_thickness, true);
				}

				if(content.max_thickness){
					tabhtml += addTabValueRow('Maximum Thickness', content.max_thickness, true);
				}

				if(content.min_thickness){
					tabhtml += addTabValueRow('Minimum Thickness', content.min_thickness, true);
				}

				if(content.interbed_thickness_units){
					tabhtml += addTabValueRow('Interbed Thickness Units', content.interbed_thickness_units, true);
				}

				if(content.notes){
					tabhtml += addTabValueRow('Notes', content.notes, true);
				}
			
				});
			}
		}
	}
	if(tabhtml==""){tabhtml="No Bedding for this spot.";}
	return tabhtml;
}

var buildSedStructures = function(){
	
	tabhtml = "";
	if(currentSpot.properties.sed){
		if(currentSpot.properties.sed.structures){
			_.each(currentSpot.properties.sed.structures, function(content){
			
				//physical
				if(content.massive_structureless){
					tabhtml += addTabValueRow('Massive/Structureless?', content.massive_structureless, false);
				}

				tabhtml += addTabTitleRow('Cross Bedding');

				if(content.cross_bedding_type){
					tabhtml += addTabValueRow('Cross Bedding Type', content.cross_bedding_type.join(', '), true);
				}

				if(content.cross_bedding_height_cm){
					tabhtml += addTabValueRow('Cross Bedding Height (cm)', content.cross_bedding_height_cm, true);
				}

				if(content.cross_bedding_width_cm){
					tabhtml += addTabValueRow('Cross Bedding Width (cm)', content.cross_bedding_width_cm, true);
				}

				if(content.cross_bedding_thickness_cm){
					tabhtml += addTabValueRow('Cross Bedding Thickness (cm)', content.cross_bedding_thickness_cm, true);
				}

				if(content.cross_bedding_spacing_cm){
					tabhtml += addTabValueRow('Cross Bedding Spacing (cm)', content.cross_bedding_spacing_cm, true);
				}

				tabhtml += addTabTitleRow('Ripple Lamination');

				if(content.ripple_lamination_type){
					tabhtml += addTabValueRow('Ripple Lamination Type', content.ripple_lamination_type.join(', '), true);
				}

				if(content.other_ripple_lamination_type){
					tabhtml += addTabValueRow('Other Ripple Lamination Type', content.other_ripple_lamination_type, true);
				}

				if(content.ripple_lamination_height_mm){
					tabhtml += addTabValueRow('Ripple Lamination Height (mm)', content.ripple_lamination_height_mm, true);
				}

				if(content.ripple_lamination_width_mm){
					tabhtml += addTabValueRow('Ripple Lamination Width (mm)', content.ripple_lamination_width_mm, true);
				}

				if(content.ripple_lamination_thick_mm){
					tabhtml += addTabValueRow('Ripple Lmation Thickness (mm)', content.ripple_lamination_thick_mm, true);
				}

				if(content.ripple_lamination_spacing_mm){
					tabhtml += addTabValueRow('Ripple Lamination Spacing (mm)', content.ripple_lamination_spacing_mm, true);
				}

				tabhtml += addTabTitleRow('Horizontal Bedding');

				if(content.horizontal_bedding_type){
					tabhtml += addTabValueRow('Horizontal Bedding Type', content.horizontal_bedding_type.join(', '), true);
				}

				if(content.other_horizontal_bedding_type){
					tabhtml += addTabValueRow('Other Horizontal Bedding Type', content.other_horizontal_bedding_type, true);
				}

				tabhtml += addTabTitleRow('Graded Bedding');

				if(content.graded_bedding_type){
					tabhtml += addTabValueRow('Graded Bedding Type', content.graded_bedding_type, true);
				}

				tabhtml += addTabTitleRow('Deformation Structures');

				if(content.deformation_structures){
					tabhtml += addTabValueRow('Deformation Structure Type', content.deformation_structures.join(', '), true);
				}

				if(content.other_deformation_structure_type){
					tabhtml += addTabValueRow('Other Deformation Structure Type', content.other_deformation_structure_type, true);
				}

				tabhtml += addTabTitleRow('Lags');

				if(content.lag_type){
					tabhtml += addTabValueRow('Lag Type', content.lag_type.join(', '), true);
				}

				if(content.other_lag_type){
					tabhtml += addTabValueRow('Other Lag Type', content.other_lag_type, true);
				}

				if(content.clast_composition){
					tabhtml += addTabValueRow('Clast Composition', content.clast_composition, true);
				}

				if(content.clast_size){
					tabhtml += addTabValueRow('Clast Size', content.clast_size, true);
				}

				if(content.layer_thickness_shape){
					tabhtml += addTabValueRow('Layer Thickness/Shape', content.layer_thickness_shape, true);
				}

				tabhtml += addTabTitleRow('Other Common Structures');

				if(content.other_common_structures){
					tabhtml += addTabValueRow('Other Common Structure Type', content.other_common_structures.join(', '), true);
				}

				if(content.bouma_sequence_part){
					tabhtml += addTabValueRow('Bouma Sequence Part', content.bouma_sequence_part.join(', '), true);
				}

				if(content.notes){
					tabhtml += addTabValueRow('Notes', content.notes, true);
				}

				//bioturbation
				if(content.bioturbation_index){
					tabhtml += addTabValueRow('Bioturbation Index', content.bioturbation_index, false);
				}

				if(content.notes){
					tabhtml += addTabValueRow('Notes', content.notes, true);
				}

				//bedding plane
				if(content.bedding_plane_features){
					tabhtml += addTabValueRow('Bedding plane features', content.bedding_plane_features.join(', '), false);
				}

				if(content.other_bedding_plane_feature){
					tabhtml += addTabValueRow('Other Bedding Plane Feature', content.other_bedding_plane_feature, true);
				}

				if(content.bedding_plane_features_scale){
					tabhtml += addTabValueRow('Bedding Plane Features Scale', content.bedding_plane_features_scale, true);
				}

				if(content.bedding_plane_features_orientation){
					tabhtml += addTabValueRow('Bedding Plane Features Orientation', content.bedding_plane_features_orientation, true);
				}

				if(content.bedform_type){
					tabhtml += addTabValueRow('Bedform Type', content.bedform_type.join(', '), true);
				}

				if(content.other_bedform_type){
					tabhtml += addTabValueRow('Other Bedform Type', content.other_bedform_type, true);
				}

				if(content.bedform_scale){
					tabhtml += addTabValueRow('Bedform Scale', content.bedform_scale, true);
				}

				if(content.crest_orientation_azimuth_0_360){
					tabhtml += addTabValueRow('"Crest Orientation (Azimuth', content.crest_orientation_azimuth_0_360, true);
				}

				if(content.notes){
					tabhtml += addTabValueRow('Notes', content.notes, true);
				}

				//pedogenic
				tabhtml += addTabTitleRow('Pedogenic Structures');

				if(content.paleosol_horizons){
					tabhtml += addTabValueRow('Master Paleosol Horizons', content.paleosol_horizons.join(', '), false);
				}

				if(content.other_horizon){
					tabhtml += addTabValueRow('Other Horizon', content.other_horizon, true);
				}

				if(content.o_horizon_thickness_cm){
					tabhtml += addTabValueRow('O Horizon thickness (cm)', content.o_horizon_thickness_cm, true);
				}

				if(content.a_horizon_thickness_cm){
					tabhtml += addTabValueRow('A Horizon thickness (cm)', content.a_horizon_thickness_cm, true);
				}

				if(content.e_horizon_thickness_cm){
					tabhtml += addTabValueRow('E Horizon thickness (cm)', content.e_horizon_thickness_cm, true);
				}

				if(content.b_horizon_thickness_cm){
					tabhtml += addTabValueRow('B Horizon thickness (cm)', content.b_horizon_thickness_cm, true);
				}

				if(content.k_horizon_thickness_cm){
					tabhtml += addTabValueRow('K Horizon thickness (cm)', content.k_horizon_thickness_cm, true);
				}

				if(content.c_horizon_thickness_cm){
					tabhtml += addTabValueRow('C Horizon thickness (cm)', content.c_horizon_thickness_cm, true);
				}

				if(content.r_horizon_thickness_cm){
					tabhtml += addTabValueRow('R Horizon thickness (cm)', content.r_horizon_thickness_cm, true);
				}

				if(content.compound_thickness_cm){
					tabhtml += addTabValueRow('Compound thickness (cm)', content.compound_thickness_cm, true);
				}

				if(content.composite_thickness_cm){
					tabhtml += addTabValueRow('Composite thickness (cm)', content.composite_thickness_cm, true);
				}

				if(content.paleosol_structures){
					tabhtml += addTabValueRow('Paleosol structures', content.paleosol_structures.join(', '), true);
				}

				if(content.other_structure){
					tabhtml += addTabValueRow('Other Paleosol Structure', content.other_structure, true);
				}

				if(content.additional_modifiers){
					tabhtml += addTabValueRow('Additional modifiers', content.additional_modifiers, true);
				}

				if(content.paleosol_classification){
					tabhtml += addTabValueRow('"Paleosol classification (after Soil Survey Staff', content.paleosol_classification.join(', '), true);
				}

				if(content.notes){
					tabhtml += addTabValueRow('Notes', content.notes, true);
				}


			
			});
		}
	}
	if(tabhtml==""){tabhtml="No Structures for this spot.";}
	return tabhtml;
}

var buildSedDiagenesis = function(){
	
	tabhtml = "";
	if(currentSpot.properties.sed){
		if(currentSpot.properties.sed.diagenesis){
			_.each(currentSpot.properties.sed.diagenesis, function(content){
			
				tabhtml += addTabTitleRow('Cement');

				if(content.cement_composition){
					tabhtml += addTabValueRow('Cement Mineralogy', content.cement_composition.join(', '), false);
				}

				if(content.other_cement_composition){
					tabhtml += addTabValueRow('Other Cement Mineralogy', content.other_cement_composition, true);
				}

				tabhtml += addTabTitleRow('Veins');

				if(content.vein_type){
					tabhtml += addTabValueRow('Vein Type', content.vein_type, true);
				}

				if(content.vein_width){
					tabhtml += addTabValueRow('Vein Width (cm)', content.vein_width, true);
				}

				if(content.vein_length){
					tabhtml += addTabValueRow('Vein Length (cm)', content.vein_length, true);
				}

				if(content.vein_orientation){
					tabhtml += addTabValueRow('Vein Orientation', content.vein_orientation, true);
				}

				if(content.vein_mineralogy){
					tabhtml += addTabValueRow('Vein Mineralogy', content.vein_mineralogy, true);
				}

				if(content.other_vein_mineralogy){
					tabhtml += addTabValueRow('Other Vein Mineralogy', content.other_vein_mineralogy, true);
				}

				tabhtml += addTabTitleRow('Fractures');

				if(content.fracture_type){
					tabhtml += addTabValueRow('Fracture Type', content.fracture_type, true);
				}

				if(content.fracture_width){
					tabhtml += addTabValueRow('Fracture Width (cm)', content.fracture_width, true);
				}

				if(content.fracture_length){
					tabhtml += addTabValueRow('Fracture Length (cm)', content.fracture_length, true);
				}

				if(content.fracture_orientation){
					tabhtml += addTabValueRow('Fracture Orientation', content.fracture_orientation, true);
				}

				if(content.fracture_mineralogy){
					tabhtml += addTabValueRow('Fracture Mineralogy', content.fracture_mineralogy, true);
				}

				if(content.other_fracture_mineralogy){
					tabhtml += addTabValueRow('Other Fracture Mineralogy', content.other_fracture_mineralogy, true);
				}

				tabhtml += addTabTitleRow('Nodules/Concretions');

				if(content.nodules_concretions_size){
					tabhtml += addTabValueRow('Nodules/Concretions Size', content.nodules_concretions_size, true);
				}

				if(content.min){
					tabhtml += addTabValueRow('Min', content.min, true);
				}

				if(content.max){
					tabhtml += addTabValueRow('Max', content.max, true);
				}

				if(content.average){
					tabhtml += addTabValueRow('Average', content.average, true);
				}

				if(content.nodules_concretions_shape){
					tabhtml += addTabValueRow('Nodules/Concretions Shape', content.nodules_concretions_shape.join(', '), true);
				}

				if(content.other_nodules_concretion_shape){
					tabhtml += addTabValueRow('Other Nodule/Concretions Shape', content.other_nodules_concretion_shape, true);
				}

				if(content.spacing){
					tabhtml += addTabValueRow('Spacing', content.spacing, true);
				}

				if(content.nodules_concretions_type){
					tabhtml += addTabValueRow('Nodules/Concretions Type', content.nodules_concretions_type, true);
				}

				if(content.other_nodules_concretions_type){
					tabhtml += addTabValueRow('Other Nodules/Concretions Type', content.other_nodules_concretions_type, true);
				}

				if(content.nodules_concretions_comp){
					tabhtml += addTabValueRow('Nodules/Concretions Composition', content.nodules_concretions_comp.join(', '), true);
				}

				if(content.other_nodules_concretion_comp){
					tabhtml += addTabValueRow('Other Nodules/Concretions Composition', content.other_nodules_concretion_comp, true);
				}

				tabhtml += addTabTitleRow('Replacement');

				if(content.replacement_type){
					tabhtml += addTabValueRow('Replacement Type', content.replacement_type, true);
				}

				if(content.other_replacement_type){
					tabhtml += addTabValueRow('Other Replacement Type', content.other_replacement_type, true);
				}

				tabhtml += addTabTitleRow('Recrystallization');

				if(content.recrystallization_type){
					tabhtml += addTabValueRow('Recrystallization Type', content.recrystallization_type, true);
				}

				if(content.other_recrystallization_type){
					tabhtml += addTabValueRow('Other Recrystallization Type', content.other_recrystallization_type, true);
				}

				tabhtml += addTabTitleRow('Other Diagenetic Features');

				if(content.other_diagenetic_features){
					tabhtml += addTabValueRow('Other Diagenetic Features', content.other_diagenetic_features.join(', '), true);
				}

				if(content.other_features){
					tabhtml += addTabValueRow('Other Features', content.other_features, true);
				}

				tabhtml += addTabTitleRow('Porosity type');

				if(content.fabric_selective){
					tabhtml += addTabValueRow('Fabric Selective', content.fabric_selective.join(', '), true);
				}

				if(content.other_fabric_selective){
					tabhtml += addTabValueRow('Other Fabric Selective', content.other_fabric_selective, true);
				}

				if(content.non_selective){
					tabhtml += addTabValueRow('Non-Frabric Selective', content.non_selective.join(', '), true);
				}

				if(content.other_non_selective){
					tabhtml += addTabValueRow('Other Non-Fabric Selective', content.other_non_selective, true);
				}

				tabhtml += addTabTitleRow('Carbonate Desiccation and Dissolution');

				if(content.carbonate_desicc_and_diss){
					tabhtml += addTabValueRow('Carbonate Desiccation and Dissolution Type', content.carbonate_desicc_and_diss.join(', '), true);
				}

				if(content.other_carbonate_desicc_diss){
					tabhtml += addTabValueRow('Other Carbonate Desiccation and Dissolution Type', content.other_carbonate_desicc_diss, true);
				}

				if(content.notes){
					tabhtml += addTabValueRow('Notes', content.notes, true);
				}


			
			});
		}
	}
	if(tabhtml==""){tabhtml="No Diagenesis for this spot.";}
	return tabhtml;
}

var buildSedFossils = function(){

	tabhtml = "";
	if(currentSpot.properties.sed){
		if(currentSpot.properties.sed.fossils){
			_.each(currentSpot.properties.sed.fossils, function(content){
			
				tabhtml += addTabTitleRow('Body');

				if(content.invertebrate){
					tabhtml += addTabValueRow('Invertebrate', content.invertebrate.join(', '), false);
				}

				if(content.other_invertebrate){
					tabhtml += addTabValueRow('Other Invertebrate', content.other_invertebrate, true);
				}

				if(content.mollusca){
					tabhtml += addTabValueRow('Mollusc', content.mollusca.join(', '), true);
				}

				if(content.other_mollusca){
					tabhtml += addTabValueRow('Other Mollusc', content.other_mollusca, true);
				}

				if(content.arthropoda){
					tabhtml += addTabValueRow('Arthropod', content.arthropoda.join(', '), true);
				}

				if(content.other_anthropoda){
					tabhtml += addTabValueRow('Other Arthropod', content.other_anthropoda, true);
				}

				if(content.echinodermata){
					tabhtml += addTabValueRow('Echinoderm', content.echinodermata.join(', '), true);
				}

				if(content.other_echinodermata){
					tabhtml += addTabValueRow('Other Echinoderm', content.other_echinodermata, true);
				}

				if(content.cnidaria){
					tabhtml += addTabValueRow('Cnidarian', content.cnidaria.join(', '), true);
				}

				if(content.other_cnidaria){
					tabhtml += addTabValueRow('Other Cnidarian', content.other_cnidaria, true);
				}

				if(content.chordate){
					tabhtml += addTabValueRow('Chordate', content.chordate, true);
				}

				if(content.other_chordata){
					tabhtml += addTabValueRow('Other Chordate', content.other_chordata, true);
				}

				if(content.protista){
					tabhtml += addTabValueRow('Protist', content.protista.join(', '), true);
				}

				if(content.other_protista){
					tabhtml += addTabValueRow('Other Protist', content.other_protista, true);
				}

				if(content.calcimicrobe){
					tabhtml += addTabValueRow('Calcimicrobe', content.calcimicrobe.join(', '), true);
				}

				if(content.other_calcimicrobe){
					tabhtml += addTabValueRow('Other Calcimicrobe', content.other_calcimicrobe, true);
				}

				if(content.plant_algae){
					tabhtml += addTabValueRow('Plant/algae', content.plant_algae.join(', '), true);
				}

				if(content.other_plant_algae){
					tabhtml += addTabValueRow('Other Plant/Algae', content.other_plant_algae, true);
				}

				if(content.green_algae){
					tabhtml += addTabValueRow('Green Algae', content.green_algae.join(', '), true);
				}

				if(content.other_green_algae){
					tabhtml += addTabValueRow('Other Green Algae', content.other_green_algae, true);
				}

				if(content.vertebrate){
					tabhtml += addTabValueRow('Vertebrate', content.vertebrate.join(', '), true);
				}

				if(content.other_vertebrate){
					tabhtml += addTabValueRow('Other Vertebrate', content.other_vertebrate, true);
				}

				if(content.faunal_assemblage){
					tabhtml += addTabValueRow('Faunal assemblage', content.faunal_assemblage, true);
				}

				if(content.other_faunal_assemblage){
					tabhtml += addTabValueRow('Other faunal assemblage', content.other_faunal_assemblage, true);
				}

				tabhtml += addTabTitleRow('Trace');

				if(content.diversity){
					tabhtml += addTabValueRow('Diversity', content.diversity, true);
				}

				if(content.descriptive){
					tabhtml += addTabValueRow('Descriptive', content.descriptive.join(', '), true);
				}

				if(content.other_descriptive){
					tabhtml += addTabValueRow('Other Descriptive', content.other_descriptive, true);
				}

				if(content.burrow_fill_type){
					tabhtml += addTabValueRow('Burrow Fill Type', content.burrow_fill_type.join(', '), true);
				}

				if(content.other_burrow_fill){
					tabhtml += addTabValueRow('Other Burrow Fill Type', content.other_burrow_fill, true);
				}

				if(content.behavioral_grouping){
					tabhtml += addTabValueRow('Behavioral grouping', content.behavioral_grouping, true);
				}

				if(content.other_behavioral_grouping){
					tabhtml += addTabValueRow('Other Behavioral Grouping', content.other_behavioral_grouping, true);
				}

				if(content.ichnofacies){
					tabhtml += addTabValueRow('Ichnofacies', content.ichnofacies, true);
				}

				if(content.other_ichnofacies){
					tabhtml += addTabValueRow('Other Ichnofacies', content.other_ichnofacies, true);
				}

				if(content.list_of_specific_types){
					tabhtml += addTabValueRow('List of specific types', content.list_of_specific_types, true);
				}

				tabhtml += addTabTitleRow('Biogenic Growth Structures');

				if(content.dominant_component){
					tabhtml += addTabValueRow('Dominant component', content.dominant_component, true);
				}

				if(content.other_dominant_component){
					tabhtml += addTabValueRow('Other Dominant Component', content.other_dominant_component, true);
				}

				if(content.microbial_reef_or_skelatal_mic){
					tabhtml += addTabValueRow('Microbial Reef or Skeletal-Microbial Reef Type', content.microbial_reef_or_skelatal_mic.join(', '), true);
				}

				if(content.other_microbial_or_skeletal_mi){
					tabhtml += addTabValueRow('Other Microbial or Skeletal Microbial Reef', content.other_microbial_or_skeletal_mi, true);
				}

				if(content.mud_mound){
					tabhtml += addTabValueRow('Mud Mound Type', content.mud_mound.join(', '), true);
				}

				if(content.other_mud_mound){
					tabhtml += addTabValueRow('Other Mud Mound', content.other_mud_mound, true);
				}

				tabhtml += addTabTitleRow('Biogenic Growth Structure Scale and Orientation');

				if(content.height){
					tabhtml += addTabValueRow('Height', content.height, true);
				}

				if(content.width){
					tabhtml += addTabValueRow('Width', content.width, true);
				}

				if(content.shape){
					tabhtml += addTabValueRow('Shape', content.shape, true);
				}

				if(content.type){
					tabhtml += addTabValueRow('Type', content.type, true);
				}

				if(content.other_type){
					tabhtml += addTabValueRow('Other Type', content.other_type, true);
				}

				if(content.accessory_structures){
					tabhtml += addTabValueRow('Accessory Structures', content.accessory_structures.join(', '), true);
				}

				if(content.other_accessory_structure){
					tabhtml += addTabValueRow('Other Accessory Structure', content.other_accessory_structure, true);
				}

				if(content.notes){
					tabhtml += addTabValueRow('Notes', content.notes, true);
				}


			
			});
		}
	}
	if(tabhtml==""){tabhtml="No Fossils for this spot.";}
	return tabhtml;
}

var buildSedInterpretations = function(){

	console.log("Current Spot: "); console.log(currentSpot);

	tabhtml = "";
	if(currentSpot.properties.sed){
		if(currentSpot.properties.sed.interpretations){
			_.each(currentSpot.properties.sed.interpretations, function(content){
			
tabhtml += addTabTitleRow('Process Interpretation');

if(content.energy){
	tabhtml += addTabValueRow('Energy', content.energy, false);
}

if(content.other_energy){
	tabhtml += addTabValueRow('Other Energy', content.other_energy, true);
}

if(content.sediment_transport){
	tabhtml += addTabValueRow('Sediment Transport', content.sediment_transport.join(', '), true);
}

if(content.other_sediment_transport){
	tabhtml += addTabValueRow('Other Sediment Transport', content.other_sediment_transport, true);
}

if(content.fluidization){
	tabhtml += addTabValueRow('Fluidization', content.fluidization, true);
}

if(content.other_fluidization){
	tabhtml += addTabValueRow('Other Fluidization', content.other_fluidization, true);
}

if(content.miscellaneous){
	tabhtml += addTabValueRow('Miscellaneous', content.miscellaneous.join(', '), true);
}

if(content.other_miscellaneous){
	tabhtml += addTabValueRow('Other Misc. Process', content.other_miscellaneous, true);
}

if(content.notes){
	tabhtml += addTabValueRow('Notes', content.notes, true);
}

tabhtml += addTabTitleRow('Environment Interpretation');

if(content.general){
	tabhtml += addTabValueRow('General', content.general.join(', '), false);
}

if(content.clastic){
	tabhtml += addTabValueRow('Clastic', content.clastic.join(', '), true);
}

if(content.alluvial_fan_environments){
	tabhtml += addTabValueRow('Alluvial fan environments', content.alluvial_fan_environments.join(', '), true);
}

if(content.other_alluvial_fan){
	tabhtml += addTabValueRow('Other Alluvial Fan', content.other_alluvial_fan, true);
}

if(content.eolian_environments){
	tabhtml += addTabValueRow('Eolian environments', content.eolian_environments.join(', '), true);
}

if(content.other_eolian){
	tabhtml += addTabValueRow('Other Eolian', content.other_eolian, true);
}

if(content.fluvial_environments){
	tabhtml += addTabValueRow('Fluvial environments', content.fluvial_environments.join(', '), true);
}

if(content.other_fluvial){
	tabhtml += addTabValueRow('Other Fluvial', content.other_fluvial, true);
}

if(content.shallow_marine_clastic_environ){
	tabhtml += addTabValueRow('Shallow marine clastic environments', content.shallow_marine_clastic_environ.join(', '), true);
}

if(content.other_shallow_marine){
	tabhtml += addTabValueRow('Other Shallow Marine', content.other_shallow_marine, true);
}

if(content.deep_marine_environments){
	tabhtml += addTabValueRow('Deep marine environments', content.deep_marine_environments.join(', '), true);
}

if(content.other_deep_marine){
	tabhtml += addTabValueRow('Other Deep Marine', content.other_deep_marine, true);
}

if(content.glacial_and_proglacial_environ){
	tabhtml += addTabValueRow('Glacial and proglacial environments', content.glacial_and_proglacial_environ.join(', '), true);
}

if(content.other_glacial){
	tabhtml += addTabValueRow('Other Glacial', content.other_glacial, true);
}

if(content.lake_environments){
	tabhtml += addTabValueRow('Lake environments', content.lake_environments.join(', '), true);
}

if(content.other_lake){
	tabhtml += addTabValueRow('Other Lake', content.other_lake, true);
}

if(content.other_clastic){
	tabhtml += addTabValueRow('Other Clastic', content.other_clastic, true);
}

if(content.carbonates){
	tabhtml += addTabValueRow('Carbonates', content.carbonates.join(', '), true);
}

if(content.factory){
	tabhtml += addTabValueRow('Factory', content.factory.join(', '), true);
}

if(content.carbonate){
	tabhtml += addTabValueRow('Environment', content.carbonate.join(', '), true);
}

if(content.other_carbonate_environment){
	tabhtml += addTabValueRow('Other Carbonate Environment', content.other_carbonate_environment, true);
}

if(content.lake_subenvironments){
	tabhtml += addTabValueRow('Lake Subenvironments', content.lake_subenvironments.join(', '), true);
}

if(content.other_carbonate_lake_subenvironment){
	tabhtml += addTabValueRow('Other Carbonate Lake Subenvironment', content.other_carbonate_lake_subenvironment, true);
}

if(content.tidal_flat_subenvironments){
	tabhtml += addTabValueRow('Tidal Flat Subenvironments', content.tidal_flat_subenvironments.join(', '), true);
}

if(content.other_tidal_flat){
	tabhtml += addTabValueRow('Other Tidal Flat', content.other_tidal_flat, true);
}

if(content.reef_subenvironments){
	tabhtml += addTabValueRow('Reef Subenvironments', content.reef_subenvironments.join(', '), true);
}

if(content.other_reef){
	tabhtml += addTabValueRow('Other Reef', content.other_reef, true);
}

if(content.detailed_carbonate_env_interpr){
	tabhtml += addTabValueRow('Detailed carbonate environmental interpretations', content.detailed_carbonate_env_interpr, true);
}

if(content.tectonic_setting){
	tabhtml += addTabValueRow('Tectonic Setting', content.tectonic_setting.join(', '), true);
}

if(content.other_tectonic_setting){
	tabhtml += addTabValueRow('Other Tectonic Setting', content.other_tectonic_setting, true);
}

if(content.notes){
	tabhtml += addTabValueRow('Notes', content.notes, true);
}

tabhtml += addTabTitleRow('Sedimentary Surfaces (for line spots only)');

if(content.geometry){
	tabhtml += addTabValueRow('Geometry', content.geometry, false);
}

if(content.relief){
	tabhtml += addTabValueRow('Relief', content.relief, true);
}

if(content.relief_scale){
	tabhtml += addTabValueRow('Relief Scale', content.relief_scale, true);
}

if(content.extent){
	tabhtml += addTabValueRow('Extent', content.extent, true);
}

if(content.extent_scale){
	tabhtml += addTabValueRow('Extent Scale', content.extent_scale, true);
}

if(content.type){
	tabhtml += addTabValueRow('Type', content.type.join(', '), true);
}

if(content.other_type){
	tabhtml += addTabValueRow('Other Type', content.other_type, true);
}

if(content.stratal_termination){
	tabhtml += addTabValueRow('Stratal Termination', content.stratal_termination, true);
}

tabhtml += addTabTitleRow('Sedimentary Surface Interpretation');

if(content.general_surfaces){
	tabhtml += addTabValueRow('General Surfaces', content.general_surfaces, true);
}

if(content.sequence_stratigraphic_surfaces){
	tabhtml += addTabValueRow('Sequence Stratigraphic Surfaces', content.sequence_stratigraphic_surfaces, true);
}

if(content.other_sequence_stratigraphic_surface){
	tabhtml += addTabValueRow('Other Sequence Stratigraphic Surface', content.other_sequence_stratigraphic_surface, true);
}

if(content.named){
	tabhtml += addTabValueRow('Named', content.named, true);
}

if(content.notes){
	tabhtml += addTabValueRow('Notes', content.notes, true);
}

tabhtml += addTabTitleRow('Architecture Interpretation');

if(content.description){
	tabhtml += addTabValueRow('Description', content.description.join(', '), false);
}

if(content.stacking_sequence_stratigraphy){
	tabhtml += addTabValueRow('Stacking/Sequence Stratigraphy', content.stacking_sequence_stratigraphy.join(', '), true);
}

if(content.other_stacking_sequence_stratigraphy){
	tabhtml += addTabValueRow('Other Stacking/Sequence Stratigraphy', content.other_stacking_sequence_stratigraphy, true);
}

if(content.fluvial_architectural_elements){
	tabhtml += addTabValueRow('Fluvial Architectural Elements', content.fluvial_architectural_elements.join(', '), true);
}

if(content.other_fluvial_element){
	tabhtml += addTabValueRow('Other Fluvial Element', content.other_fluvial_element, true);
}

if(content.lacustrine_architecture_interpretation){
	tabhtml += addTabValueRow('Lacustrine Architecture Interpretation', content.lacustrine_architecture_interpretation.join(', '), true);
}

if(content.other_lacustrine_architecture_interpretation){
	tabhtml += addTabValueRow('Other Lacustrine Architecture Interpretation', content.other_lacustrine_architecture_interpretation, true);
}

if(content.carbonate_platform_geometry){
	tabhtml += addTabValueRow('Carbonate Platform Geometry', content.carbonate_platform_geometry.join(', '), true);
}

if(content.other_platform_geometry){
	tabhtml += addTabValueRow('Other Platform Geometry', content.other_platform_geometry, true);
}

if(content.deep_water_architctural_element){
	tabhtml += addTabValueRow('Deep-Water Architectural Elements', content.deep_water_architctural_element.join(', '), true);
}

if(content.other_deep_water_architectural_element){
	tabhtml += addTabValueRow('Other Deep-Water Architectural Element', content.other_deep_water_architectural_element, true);
}

if(content.carbonate_margin_geometry){
	tabhtml += addTabValueRow('Carbonate Margin Geometry', content.carbonate_margin_geometry.join(', '), true);
}

if(content.other_carbonate_margin_geometry){
	tabhtml += addTabValueRow('Other Carbonate Margin Geometry', content.other_carbonate_margin_geometry, true);
}

if(content.notes){
	tabhtml += addTabValueRow('Notes', content.notes, true);
}


			
			});
		}
	}
	if(tabhtml==""){tabhtml="No Interpretations for this spot.";}
	return tabhtml;
}



/*
grey box
tabhtml += addTabTitleRow('Spot Owner');

addTabValueRow = function (label,value,bordertop)

myArray.join(", ");


*/





















