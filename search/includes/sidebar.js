rownum=1;

var ownerAutoCompleteOptions = {
	url: function (phrase) {
		return 'ownersearch.php?term=' + phrase;
	},

	getValue: 'label',

	

	list: {
		match: {
			enabled: true
		},
		maxNumberOfElements: 10,
		onChooseEvent: function() {
			console.log("Item Chosen!");
			getSearchCount();
		}	
	},

	theme: "round"
};

var sampleIDAutoCompleteOptions = {
	url: function (phrase) {
		return 'sampleidsearch.php?term=' + phrase;
	},

	getValue: 'label',

	list: {
		match: {
			enabled: true
		},
		maxNumberOfElements: 10,
		onChooseEvent: function() {
			console.log("Item Chosen!");
			getSearchCount();
		}	
	},

	theme: "round"
};

function addRow() {
	$( "#rowContainer" ).append( '			<div class="itemRow" id="itemRow_' + rownum + '">\
	<div class="andOrColumn" id="andOrColumn_' + rownum + '">\
		<select class="searchSelect" id="andOr_' + rownum + '" onchange="getSearchCount()">\
			<option value="and">And</option>\
			<option value="or">Or</option>\
			<option value="not">Not</option>\
		</select>\
	</div>\
	<div class="typeColumn" id="typeColumn_' + rownum + '">\
		<select class="searchSelect" id="typeColSel_' + rownum + '" onchange="updateSearchRow(' + rownum + ')">\
			<option value="">select...</option>\
			<option value="Date Collected">Date Collected</option>\
			<option value="Image Type">Image Type</option>\
			<option value="Keywords">Keywords</option>\
			<option value="Metamorphic Facies">Metamorphic Facies</option>\
			<option value="Microstructure">Microstructure</option>\
			<option value="Mineral">Minerals</option>\
			<option value="Orientation">Orientation</option>\
			<option value="Owner">Owner</option>\
			<option value="Rock Type">Rock Type</option>\
			<option value="Sample">Sample</option>\
			<option value="Sample ID">Sample ID</option>\
			<option value="Strat Column">Strat Column</option>\
			<option value="Tectonic Province">Tectonic Province</option>\
		</select>\
	</div>\
	<div class="resultColumn" id="resultColumn_' + rownum + '">&nbsp;</div>\
	<div class="addRowColumn" id="addRowCol_' + rownum + '"></div>\
	<div class="clearColumn"></div>\
</div>' );
	var looknum = rownum-1;
	$("#addRowCol_"+ looknum).html('<input type="button" class="minusButton" value="-" onclick="removeRow(' + looknum + ')">');
	rownum++;
	removeLowestAndSwitch();
}

function removeRow(rownum){
	$("#itemRow_" + rownum).remove();
	removeLowestAndSwitch();
	getSearchCount();
}

function removeLowestAndSwitch(){
	var smallestNum = 200;
	for(i = 200; i >= 0; i--){
		if ($("#itemRow_" + i).length ){ smallestNum = i; }
	}
	console.log("smallest: " + smallestNum);
	$("#andOrColumn_" + smallestNum).html("&nbsp;");
}

function updateSearchRow(rownum){
	
	var valueOfSelect = $("#typeColSel_" + rownum).val();
	
	$("#addRowCol_"+ rownum).html('<input type="button" class="plusButton" value="+" onclick="addRow()">');
	
	if(valueOfSelect == ""){
		//clear box
		$("#resultColumn_" + rownum).html("&nbsp;");
		
	}else if(valueOfSelect == "Date Collected"){

		$("#resultColumn_" + rownum).html("");

		$("#resultColumn_" + rownum).append('<Label class="yearLabel" for="minYearSearch_' + rownum + '">Min Year: </Label>');
		var newResult = $("<input></input>");
		newResult.attr("id", 'minYearSearch_' + rownum);
		newResult.attr("class", 'searchText');
		newResult.attr("size", '2');
		$("#resultColumn_" + rownum).append(newResult);
		$( "#minYearSearch_" + rownum ).keyup($.debounce(350, function(e) {
			getSearchCount();
		}));

		$("#resultColumn_" + rownum).append(' <Label class="yearLabel" for="maxYearSearch_' + rownum + '">Max Year: </Label>');
		var newResult = $("<input></input>");
		newResult.attr("id", 'maxYearSearch_' + rownum);
		newResult.attr("class", 'searchText');
		newResult.attr("size", '2');
		$("#resultColumn_" + rownum).append(newResult);
		$( "#maxYearSearch_" + rownum ).keyup($.debounce(350, function(e) {
			getSearchCount();
		}));

	}else if(valueOfSelect == "Image Type"){
	
		$("#resultColumn_" + rownum).html("");
		var newResult = $('<Select class="searchSelect">\
						<option value="">Select...</option>\
						<option value="photo">Photo</option>\
						<option value="sketch">Sketch</option>\
						<option value="geological_cs">Geological Cross Section</option>\
						<option value="geophysical_cs">Geophysical Cross Section</option>\
						<option value="strat_section">Stratigraphic Section</option>\
						<option value="micrograph_ref">Micrograph Reference</option>\
						<option value="micrograph">Micrograph</option>\
						<option value="sample">Sample</option>\
						<option value="subsample">Subsample</option>\
						<option value="undef_sample">Undeformed Sample</option>\
						<option value="exp_setup">Experimental Setup</option>\
						<option value="def_sample">Deformed Sample</option>\
						<option value="exp_apparatus">Experimental Apparatus</option>\
						<option value="other_image_ty">Other</option>\
					</Select>');
		newResult.attr("id", 'imageTypeSearch_' + rownum);
		$("#resultColumn_" + rownum).append(newResult);
		$("#imageTypeSearch_" + rownum).change(function() {
			console.log( "Image Type Changed." );
			getSearchCount();
		});

	}else if(valueOfSelect == "Keywords"){
		
		$("#resultColumn_" + rownum).html("");
		var newResult = $("<input></input>");
		newResult.attr("id", 'keywordSearch_' + rownum);
		newResult.attr("class", 'searchText');
		$("#resultColumn_" + rownum).append(newResult);
		/*
		$( "#keywordSearch_" + rownum ).keyup(function() {
		  console.log( "Handler for .change() called." );
		});
		*/
		$( "#keywordSearch_" + rownum ).keyup($.debounce(350, function(e) {
			console.log("Keyword Changed!");
			getSearchCount();
		}));
	
	}else if(valueOfSelect == "Metamorphic Facies"){
	
		$("#resultColumn_" + rownum).html("List from Basil");
	
	}else if(valueOfSelect == "Microstructure"){
	
		// simple exists
		$("#resultColumn_" + rownum).html("");
		//var newResult = $("<input></input>");
		//newResult.attr("id", 'microstructureExistsSearch_' + rownum);
		//newResult.attr("type", 'checkbox');
		//newResult.prop( "checked", true );
		//$("#resultColumn_" + rownum).append(newResult);
		$("#resultColumn_" + rownum).append(' <Label class="existsLabel">Exists</Label>');
		$("#microstructureExistsSearch_" + rownum).change(function() {
			console.log( "Checkbox Changed." );
			getSearchCount();
		});
		getSearchCount();
	
	}else if(valueOfSelect == "Mineral"){
	
		$("#resultColumn_" + rownum).html("List from Basil");
	
	}else if(valueOfSelect == "Orientation"){
	
		// simple exists
		$("#resultColumn_" + rownum).html("");
		//var newResult = $("<input></input>");
		//newResult.attr("id", 'orientationExistsSearch_' + rownum);
		//newResult.attr("type", 'checkbox');
		//newResult.prop( "checked", true );
		//$("#resultColumn_" + rownum).append(newResult);
		$("#resultColumn_" + rownum).append(' <Label class="existsLabel">Exists</Label>');
		$("#orientationExistsSearch_" + rownum).change(function() {
			console.log( "Checkbox Changed." );
			getSearchCount();
		});
		getSearchCount();
	
	}else if(valueOfSelect == "Owner"){
	
		$("#resultColumn_" + rownum).html("");
		var newResult = $("<input></input>");
		newResult.attr("id", 'ownerSearch_' + rownum);
		newResult.attr("placeholder", "Smith, John...");
		$("#resultColumn_" + rownum).append(newResult);
		$('#ownerSearch_' + rownum).easyAutocomplete(ownerAutoCompleteOptions);

	}else if(valueOfSelect == "Rock Type"){
	
		$("#resultColumn_" + rownum).html("List from Basil");
	
	}else if(valueOfSelect == "Sample"){
	
		// simple exists
		$("#resultColumn_" + rownum).html("");
		//var newResult = $("<input></input>");
		//newResult.attr("id", 'sampleExistsSearch_' + rownum);
		//newResult.attr("type", 'checkbox');
		//newResult.prop( "checked", true );
		//$("#resultColumn_" + rownum).append(newResult);
		$("#resultColumn_" + rownum).append(' <Label class="existsLabel">Exists</Label>');
		$("#sampleExistsSearch_" + rownum).change(function() {
			console.log( "Checkbox Changed." );
			getSearchCount();
		});
		getSearchCount();
	
	}else if(valueOfSelect == "Sample ID"){

		$("#resultColumn_" + rownum).html("");
		var newResult = $("<input></input>");
		newResult.attr("id", 'sampleIDSearch_' + rownum);
		newResult.attr("placeholder", "ABC123...");
		$("#resultColumn_" + rownum).append(newResult);
		$('#sampleIDSearch_' + rownum).easyAutocomplete(sampleIDAutoCompleteOptions);
		
	}else if(valueOfSelect == "Strat Column"){

		// simple exists
		$("#resultColumn_" + rownum).html("");
		//var newResult = $("<input></input>");
		//newResult.attr("id", 'stratColumnExistsSearch_' + rownum);
		//newResult.attr("type", 'checkbox');
		//newResult.prop( "checked", true );
		//$("#resultColumn_" + rownum).append(newResult);
		$("#resultColumn_" + rownum).append(' <Label class="existsLabel">Exists</Label>');
		$("#stratColumnExistsSearch_" + rownum).change(function() {
			console.log( "Checkbox Changed." );
			getSearchCount();
		});
		getSearchCount();

	}else if(valueOfSelect == "Tectonic Province"){
	
		$("#resultColumn_" + rownum).html("List from Basil");
	
	}
}

function getSearchCount(){
	//this function parses all inputs and does an AJAX call to the server
	//to get a count of dataset/spot level results
	console.log("Doing search here...");
	console.log("********************");
	$("#searchCountResults").html("");
	$("#performingSearch").fadeIn(400);
	
	querystring = createQueryString();
	//console.log("query string: " + querystring);
	
	if(querystring!=""){
		//send JSON querystring to server
		
		//clear results
		$("#searchResults").html("");
		
		$.post( "interfacesearch.php", querystring, function( data ) {
			console.log('did search and received: ' + data.counts.projectcount);
			
			//handle results from server here
			// update sidebar, map, etc...
			
			$("#performingSearch").hide();

			var projectCount = data.counts.projectcount;
			var datasetCount = data.counts.datasetcount;
			var spotCount = data.counts.spotcount;

			$("#searchCountResults").html("Results: " + projectCount + " Projects / " + datasetCount + " Datasets / " + spotCount + " Spots");
			

			
			//populate results from search
			if(projectCount > 0){
				projects = data.projects;
				projectnum = 0;
				_.each(projects, function(p){
					
					var projectname = p.project_name;
					var owner = p.owner_firstname + ' ' + p.owner_lastname;
					
					var newProjectString = '<div class="wrap-collabsible">\
							<input id="collapsible' + projectnum + '" class="toggle" type="checkbox">\
							<label for="collapsible' + projectnum + '" class="lbl-toggle">' + projectname + '<br><span class="projectOwner">Owned By ' + owner + '</span></label>\
							<div class="collapsible-content">\
								<div class="content-inner">\
									<ul style="padding-left: 15px;margin-top: 0px;margin-bottom: 0px;">\
										';
					
					datasets = p.datasets;
					_.each(datasets, function(d){
						datasetname = d.dataset_name;
						datasetid = d.dataset_id;
						newProjectString = newProjectString + '<li onclick="alert(\'' + datasetid + '\')">' + datasetname + '</li>'
					});
					
					
					newProjectString = newProjectString + '</ul>\
								</div>\
							</div>\
						</div>';
						
					var newProject = $(newProjectString)
					
					$("#searchResults").append(newProject);
					
					projectnum++;
					
				});
			}
			
			//Rebuild map
			
			//set var newSearchFeatures to the featurecollection
			newSearchFeatures = data.geoJSON; 
			
			newSearchRebuildDatasetsLayer();
			
			
		});
	}else{
		$("#searchCountResults").html("");
		$("#performingSearch").hide();
	}
	
	/*
	setTimeout(function(){
		$("#performingSearch").hide();
		
		var datasetCount = getRandomInteger(0,99);
		var spotCount = getRandomInteger(1111,99999);
		
		$("#searchCountResults").html("Results: " + datasetCount + " datasets / " + spotCount + " spots");
	}, 	1000);
	*/
}













//roll through search rows and build query for server
function createQueryString(){

	var paramArray = [];
	
	for (let i = 0; i < 100; i++) {

		if( $("#typeColSel_"+i).val() != null && $("#typeColSel_"+i).val() != "" ){

			var addThisParam = false;
			var thisParam = {};
			var thisConstraints = [];
			
			var thisQualifier = $("#andOr_"+i).val();
			if( typeof thisQualifier === 'undefined' ) thisQualifier = "and";

			thisParam.num = i;
			thisParam.qualifier = thisQualifier;
			
			
			//Date Collected
			if( $("#minYearSearch_" + i).val() != "" ||  $("#maxYearSearch_" + i).val() != "" ){
				if( $("#minYearSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'minYear';
					thisConstraint.constraintValue = $("#minYearSearch_" + i).val();
					thisConstraints.push(thisConstraint);
				}
				if( $("#maxYearSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'maxYear';
					thisConstraint.constraintValue = $("#maxYearSearch_" + i).val();
					thisConstraints.push(thisConstraint);
				}

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Image Type
			if( $("#imageTypeSearch_" + i).val() != "" ){
				if( $("#imageTypeSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'imageType';
					thisConstraint.constraintValue = $("#imageTypeSearch_" + i).val();
					thisConstraints.push(thisConstraint);
				}

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Keyword
			if( $("#keywordSearch_" + i).val() != "" ){
				if( $("#keywordSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'keyword';
					thisConstraint.constraintValue = $("#keywordSearch_" + i).val();
					thisConstraints.push(thisConstraint);
				}

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//MicroStructure
			if( $("#typeColSel_"+i).val() == "Microstructure" ){
				thisConstraint = {};
				thisConstraint.constraintType = 'microstructureExists';
				thisConstraints.push(thisConstraint);

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Orientation
			if( $("#typeColSel_"+i).val() == "Orientation" ){
				thisConstraint = {};
				thisConstraint.constraintType = 'orientationExists';
				thisConstraints.push(thisConstraint);

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Owner
			if( $("#ownerSearch_" + i).val() != "" ){
				if( $("#ownerSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'owner';
					//thisConstraint.constraintValue = $("#ownerSearch_" + i).val();
					thisConstraint.constraintValue = $("#ownerSearch_" + i).getSelectedItemData().id;
					thisConstraints.push(thisConstraint);
					//$(#ownerSearch_" + i).getSelectedItemData().id;
					//console.log("owner chosen: " + $("#ownerSearch_" + i).getSelectedItemData().id);
				}

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Orientation
			if( $("#typeColSel_"+i).val() == "Sample" ){
				thisConstraint = {};
				thisConstraint.constraintType = 'sampleExists';
				thisConstraints.push(thisConstraint);

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Sample ID
			if( $("#sampleIDSearch_" + i).val() != "" ){
				if( $("#sampleIDSearch_" + i).val() ){
					thisConstraint = {};
					thisConstraint.constraintType = 'sampleID';
					thisConstraint.constraintValue = $("#sampleIDSearch_" + i).val();
					thisConstraints.push(thisConstraint);
				}

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			//Strat Column
			if( $("#typeColSel_"+i).val() == "Strat Column" ){
				thisConstraint = {};
				thisConstraint.constraintType = 'stratColumnExists';
				thisConstraints.push(thisConstraint);

				thisParam.constraints = thisConstraints;
				addThisParam = true;
			}

			if(thisParam.constraints.length > 0){
				if(addThisParam == true){
					paramArray.push(thisParam);
				}
			}
		}

	}
	
	if(paramArray.length > 0){
		outObject = {params: paramArray};
		outJSON = JSON.stringify(outObject);
		return outJSON;
	}else{
		return "";
	}

}




















function getRandomInteger(min, max) {
	return Math.floor(Math.random() * (max - min) ) + min;
}


function sidesearch_open() {
	document.getElementById("map").style.marginLeft = "430px";
	document.getElementById("mySidebar").style.width = "430px";
	document.getElementById("mySidebar").style.display = "block";
	//document.getElementById("openNav").style.display = 'none';
}
function sidesearch_close() {
	document.getElementById("map").style.marginLeft = "0px";
	document.getElementById("mySidebar").style.display = "none";
	//document.getElementById("openNav").style.display = "inline-block";
}

