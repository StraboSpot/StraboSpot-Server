jQuery(document).ready(function($){

// jQuery code is in here

});

function doTest() {
	alert("hello world");
}

function removeAllChildNodes(parent) {
    while (parent.firstChild) {
        parent.removeChild(parent.firstChild);
    }
}

function getRandomInt(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min) + min); // The maximum is exclusive and the minimum is inclusive
}

function findSubNode(node, id = "foofoo"){
	let myNode = recursiveSearch(node.children, id)
	return myNode;
}

function recursiveSearch(arr, id) {
    for (let i = 0; i < arr.length; i++) {
        if (arr[i].id === id) {
            return arr[i];
        }
        if (arr[i].children) {
            const result = recursiveSearch(arr[i].children, id);
            if (result) {
                return result;
            }
        }
    }
    return null;
}


function doDebug() {
	
	//console.log("facilityData:"); console.log(facilityData);
	//console.log("apparatusData:"); console.log(apparatusData);
	//console.log("daqData:"); console.log(daqData);
	//console.log("sampleData:"); console.log(sampleData);
	//console.log("experimentData:"); console.log(experimentData);
	//console.log("dataData:"); console.log(dataData);
	
	//exper_deleteAllData();
	
	
	//let testDiv = document.getElementById("sampleModal");
	//let myNode = findSubNode(testDiv, "sampleName");
	//let myNode = recursiveSearch(testDiv.children, "sampleName")
	//console.log(myNode);

	let out = {};
	out.facility = facilityData;
	out.apparatus = apparatusData;
	out.daq = daqData;
	out.sample = sampleData;
	out.experiment = experimentData;
	out.data = dataData;
	let jsonString = JSON.stringify(out, null, "\t");
	console.log(jsonString);
	

}

function fixZip(zip) {
	var returnString = "";
	returnString = zip.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');
	if(returnString.length > 5) returnString = returnString.substring(0,5);
	return returnString;
}

function constrainDecimal(val){
	let returnVal = val.replace(/[^-0-9.]/g, '').replace(/(\..*)\./g, '$1')
	return returnVal;
}

function uuidv4() {
	return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
		(c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
	);
}

function checkForProjectSubmitErrors() {
	var error = "";
	if($( "#projectName" ).val()=="") error += "Project Name Cannot be Blank.\n";
	return error;
}

function doSubmitNewProject() {
	var error = checkForProjectSubmitErrors();
	if(error != ""){
		alert(error);
	}else{
		//OK, now create JSON for POSTING to the server
		var outJSONString = buildProjectJSON();

		//POST json to inNewExperiment.php
		$.post( "inNewProject.php", {json: outJSONString})
			.done(function( data ) {
				if(data.Error){
					alert("Error!\n" + data.Error);
				}else{
					console.log(data);
					
					$( "#bigWindow" ).html('\
					<div class="topTitle green center" style="padding-top:50px;">Success!</div>\
					<div class="mainFSLegend" style="text-align: center; padding-top:30px;">Project<br>'+data.name+'<br>has been added to the database.</div>\
					<div style="text-align:center;padding-top:25px;"><button class="submitButton" style="vertical-align:middle" onclick="window.location.href = \'/my_experimental_data\';"><span>Continue </span></button></div>\
					');
					
				}
				
		});
		
	}
}

function buildProjectJSON() {
	var project = new Object();
	project.name = $( "#projectName" ).val();
	if($( "#projectDescription" ).val()!=""){
		project.description = $( "#projectDescription" ).val();
	}
	
	var outObject = new Object();
	outObject.project = project;
	
	var outJSONString = JSON.stringify(outObject);
	
	return outJSONString;
}

function checkForFacilitySubmitErrors() {
	var error = "";
	if($( "#facilityName" ).val()=="") error += "Facility Name Cannot be Blank.\n";
	if($( "#facilityType" ).val()=="") error += "Facility Type Cannot be Blank.\n";
	if($( "#instituteName" ).val()=="") error += "Institute Name Cannot be Blank.\n";
	return error;
}

function checkForApparatusSubmitErrors() {
	var error = "";

	if($( "#apparatusName" ).val()=="") error += "Apparatus Name Cannot be Blank.\n";
	if($( "#apparatusType" ).val()=="") error += "Apparatus Type Cannot be Blank.\n";
	
	//Loop over Apparatus Documents and check for files
	let documentsTable = document.getElementById("documentRows");
	var docRows = documentsTable.children;
	if(docRows.length > 0){
		for(let i = 0; i < docRows.length; i++){
			var rowNum = i + 1;
			var thisFile = docRows[i].children[0].children[0].children[2].children[0].children[1].value;
			
			console.log(docRows[i].children[0].children[0].children[2].children[0]);
			
			if(thisFile == ""){
				error += "File for document "+ rowNum +" cannot be blank\n";
			}
		}
	}
	
	
	return error;
}

function doSubmitNewApparatus() {
	var error = checkForApparatusSubmitErrors();
	if(error != ""){
		alert(error);
	}else{
		
		document.getElementById("progressBox").style.display="inline";
		
		var fd = new FormData();
		
		//Gather Facility Pkey
		var facilityPkey = document.getElementById("facilityPkey").value;
		fd.append('facility_pkey', facilityPkey);
		
		//Build JSON
		var jsonString = buildApparatusJSON();
		fd.append('json',jsonString);
		console.log(jsonString);
		
		//Gather files
		let docsDiv = document.getElementById("documentRows");
		let docRows = docsDiv.children;
		if(docRows.length > 0){
			for(let i = 0; i < docRows.length; i++){
				var fileNode = docRows[i].children[0].children[0].children[2].children[0].children[1];
				if(fileNode.type == "file"){
					var uuid = docRows[i].children[0].children[0].children[3].children[0].children[2].value;
					
					//Add this file to POST and label it with uuid
					fd.append(uuid,fileNode.files[0]);
				}
			}
		}

		$.ajax({
			url : "inNewApparatus.php",
			type: "POST",
			data : fd,
			xhr: function () {
					var xhr = $.ajaxSettings.xhr();
					xhr.onprogress = function e() {
						// For downloads
						if (e.lengthComputable) {
							var percent = Math.round(e.loaded / e.total * 100);
							document.getElementById("progressDigit").innerHTML = percent + "%";
							document.getElementById("progressBar").style.width = percent + "%";
						}
					};
					xhr.upload.onprogress = function (e) {
						// For uploads
						if (e.lengthComputable) {
							var percent = Math.round(e.loaded / e.total * 100);
							document.getElementById("progressDigit").innerHTML = percent + "%";
							document.getElementById("progressBar").style.width = percent + "%";
						}
					};
					return xhr;
				},
			processData: false,
			contentType: false,
			success:function(data){
				if(data.Error){
					document.getElementById("progressBox").style.display="none";
					alert("Error!\n" + data.Error);
				}else{
					setTimeout(function () {
						document.getElementById("progressBox").style.display="none";
						$( "#bigWindow" ).html('\
						<div class="topTitle green center" style="padding-top:50px;">Success!</div>\
						<div class="mainFSLegend" style="text-align: center; padding-top:30px;">Apparatus<br>'+data.name+'<br>has been added to the database.</div>\
						<div style="text-align:center;padding-top:25px;"><button class="submitButton" style="vertical-align:middle" onclick="window.location.href = \'apparatus_repository\';"><span>Continue </span></button></div>\
						');
					}, 1000)
				}
			},
			error: function(){
				//if fails     
			}
		});
	}
}

function doSubmitEditApparatus() {
	var error = checkForApparatusSubmitErrors();
	
	console.log(error);
	
	
	if(error != ""){
		alert(error);
	}else{

		document.getElementById("progressBox").style.display="inline";
		
		var fd = new FormData();
		
		//Gather Facility Pkey
		var apparatusPkey = document.getElementById("apparatusPkey").value;
		fd.append('apparatus_pkey', apparatusPkey);
		
		//Build JSON
		var jsonString = buildApparatusJSON();
		fd.append('json',jsonString);
		console.log(jsonString);
		
		//Gather files
		let docsDiv = document.getElementById("documentRows");
		let docRows = docsDiv.children;
		if(docRows.length > 0){
			for(let i = 0; i < docRows.length; i++){
				var fileNode = docRows[i].children[0].children[0].children[2].children[0].children[1];
				if(fileNode.type == "file"){
					var uuid = docRows[i].children[0].children[0].children[3].children[0].children[2].value;
					
					//Add this file to POST and label it with uuid
					fd.append(uuid,fileNode.files[0]);
				}
			}
		}

		$.ajax({
			url : "inEditApparatus.php",
			type: "POST",
			data : fd,
			xhr: function () {
					var xhr = $.ajaxSettings.xhr();
					xhr.onprogress = function e() {
						// For downloads
						if (e.lengthComputable) {
							var percent = Math.round(e.loaded / e.total * 100);
							document.getElementById("progressDigit").innerHTML = percent + "%";
							document.getElementById("progressBar").style.width = percent + "%";
						}
					};
					xhr.upload.onprogress = function (e) {
						// For uploads
						if (e.lengthComputable) {
							var percent = Math.round(e.loaded / e.total * 100);
							document.getElementById("progressDigit").innerHTML = percent + "%";
							document.getElementById("progressBar").style.width = percent + "%";
						}
					};
					return xhr;
				},
			processData: false,
			contentType: false,
			success:function(data){
				if(data.Error){
					document.getElementById("progressBox").style.display="none";
					alert("Error!\n" + data.Error);
				}else{
					setTimeout(function () {
						document.getElementById("progressBox").style.display="none";
						$( "#bigWindow" ).html('\
						<div class="topTitle green center" style="padding-top:50px;">Success!</div>\
						<div class="mainFSLegend" style="text-align: center; padding-top:30px;">Changes to Apparatus<br>'+data.name+'<br>have been saved to the database.</div>\
						<div style="text-align:center;padding-top:25px;"><button class="submitButton" style="vertical-align:middle" onclick="window.location.href = \'apparatus_repository\';"><span>Continue </span></button></div>\
						');
					}, 1000)
				}
			},
			error: function(){
				//if fails     
			}
		});
	}
}


function buildApparatusJSON() {
	var apparatus = new Object();
	apparatus.name = $( "#apparatusName" ).val();
	apparatus.type = $( "#apparatusType" ).val();
	apparatus.other_type = $( "#otherApparatusType" ).val();
	apparatus.location = $( "#apparatusLocation" ).val();
	apparatus.id = $( "#apparatusId" ).val();
	apparatus.description = $( "#apparatusDescription" ).val();
	
	const features = [];
	if($("#f_loading").is(':checked')) features.push("Loading");
	if($("#f_unloading").is(':checked')) features.push("Unloading");
	if($("#f_heating").is(':checked')) features.push("Heating");
	if($("#f_cooling").is(':checked')) features.push("Cooling");
	if($("#f_high_temperature").is(':checked')) features.push("High Temperature");
	if($("#f_ultra-high_temperature").is(':checked')) features.push("Ultra-High Temperature");
	if($("#f_low_temperature").is(':checked')) features.push("Low Temperature");
	if($("#f_sub-zero_temperature").is(':checked')) features.push("Sub-Zero Temperature");
	if($("#f_high_pressure").is(':checked')) features.push("High Pressure");
	if($("#f_ultra-high_pressure").is(':checked')) features.push("Ultra-High Pressure");
	if($("#f_hydrostatic_tests").is(':checked')) features.push("Hydrostatic Tests");
	if($("#f_hip").is(':checked')) features.push("HIP");
	if($("#f_synthesis").is(':checked')) features.push("Synthesis");
	if($("#f_deposition_evaporation").is(':checked')) features.push("Deposition/Evaporation");
	if($("#f_mineral_reactions").is(':checked')) features.push("Mineral Reactions");
	if($("#f_hydrothermal_reactions").is(':checked')) features.push("Hydrothermal Reactions");
	if($("#f_elasticity").is(':checked')) features.push("Elasticity");
	if($("#f_local_axial_strain").is(':checked')) features.push("Local Axial Strain");
	if($("#f_local_radial_strain").is(':checked')) features.push("Local Radial Strain");
	if($("#f_elastic_moduli").is(':checked')) features.push("Elastic Moduli");
	if($("#f_yield_strength").is(':checked')) features.push("Yield Strength");
	if($("#f_failure_strength").is(':checked')) features.push("Failure Strength");
	if($("#f_strength").is(':checked')) features.push("Strength");
	if($("#f_extension").is(':checked')) features.push("Extension");
	if($("#f_creep").is(':checked')) features.push("Creep");
	if($("#f_friction").is(':checked')) features.push("Friction");
	if($("#f_frictional_sliding").is(':checked')) features.push("Frictional Sliding");
	if($("#f_slide_hold_slide").is(':checked')) features.push("Slide Hold Slide");
	if($("#f_stepping").is(':checked')) features.push("Stepping");
	if($("#f_pure_shear").is(':checked')) features.push("Pure Shear");
	if($("#f_simple_shear").is(':checked')) features.push("Simple Shear");
	if($("#f_rotary_shear").is(':checked')) features.push("Rotary Shear");
	if($("#f_torsion").is(':checked')) features.push("Torsion");
	if($("#f_viscosity").is(':checked')) features.push("Viscosity");
	if($("#f_indentation").is(':checked')) features.push("Indentation");
	if($("#f_hardness").is(':checked')) features.push("Hardness");
	if($("#f_dynamic_tests").is(':checked')) features.push("Dynamic Tests");
	if($("#f_hydraulic_fracturing").is(':checked')) features.push("Hydraulic Fracturing");
	if($("#f_hydrothermal_fracturing").is(':checked')) features.push("Hydrothermal Fracturing");
	if($("#f_shockwave").is(':checked')) features.push("Shockwave");
	if($("#f_reactive_flow").is(':checked')) features.push("Reactive Flow");
	if($("#f_pore_fluid_control").is(':checked')) features.push("Pore Fluid Control");
	if($("#f_pore_fluid_chemistry").is(':checked')) features.push("Pore Fluid Chemistry");
	if($("#f_pore_volume_compaction").is(':checked')) features.push("Pore Volume Compaction");
	if($("#f_storage_capacity").is(':checked')) features.push("Storage Capacity");
	if($("#f_permeability").is(':checked')) features.push("Permeability");
	if($("#f_steady-state_permeability").is(':checked')) features.push("Steady-State Permeability");
	if($("#f_transient_permeability").is(':checked')) features.push("Transient Permeability");
	if($("#f_hydraulic_conductivity").is(':checked')) features.push("Hydraulic Conductivity");
	if($("#f_drained_undrained_pore_fluid").is(':checked')) features.push("Drained/Undrained Pore Fluid");
	if($("#f_uniaxial_stress_strain").is(':checked')) features.push("Uniaxial Stress/Strain");
	if($("#f_biaxial_stress_strain").is(':checked')) features.push("Biaxial Stress/Strain");
	if($("#f_triaxial_stress_strain").is(':checked')) features.push("Triaxial Stress/Strain");
	if($("#f_differential_stress").is(':checked')) features.push("Differential Stress");
	if($("#f_true_triaxial").is(':checked')) features.push("True Triaxial");
	if($("#f_resistivity").is(':checked')) features.push("Resistivity");
	if($("#f_electrical_resistivity").is(':checked')) features.push("Electrical Resistivity");
	if($("#f_electrical_capacitance").is(':checked')) features.push("Electrical Capacitance");
	if($("#f_streaming_potential").is(':checked')) features.push("Streaming Potential");
	if($("#f_acoustic_velocity").is(':checked')) features.push("Acoustic Velocity");
	if($("#f_acoustic_events").is(':checked')) features.push("Acoustic Events");
	if($("#f_p-wave_velocity").is(':checked')) features.push("P-Wave Velocity");
	if($("#f_s-wave_velocity").is(':checked')) features.push("S-Wave Velocity");
	if($("#f_source_location").is(':checked')) features.push("Source Location");
	if($("#f_tomography").is(':checked')) features.push("Tomography");
	if($("#f_in-situ_x-ray").is(':checked')) features.push("In-Situ X-Ray");
	if($("#f_infrared").is(':checked')) features.push("Infrared");
	if($("#f_raman").is(':checked')) features.push("Raman");
	if($("#f_visual").is(':checked')) features.push("Visual");
	if($("#f_other").is(':checked')) features.push("Other");
	
	if(features.length > 0){
		apparatus.features = features;
	}

	//Build Parameters
	let paramsTable = document.getElementById("paramsTable");
	if(paramsTable.children[0].children.length > 1){
		var paramsArray = [];
		let paramRows = paramsTable.children[0].children;
		for(let i = 1; i < paramRows.length; i++){
			var param = new Object();
			param.type = paramRows[i].children[0].children[0].value;
			param.min = paramRows[i].children[1].children[0].value;
			param.max = paramRows[i].children[2].children[0].value;
			param.unit = paramRows[i].children[3].children[0].value;
			param.prefix = paramRows[i].children[4].children[0].value;
			param.note = paramRows[i].children[5].children[0].value;

			paramsArray.push(param);
		}
		apparatus.parameters = paramsArray;
	}
	
	//Build Documents
	let docsDiv = document.getElementById("documentRows");
	let docRows = docsDiv.children;
	if(docRows.length > 0){
		var docsArray = [];
		for(let i = 0; i < docRows.length; i++){
			var doc = new Object();
			
			
			doc.type = docRows[i].children[0].children[0].children[0].children[0].children[1].value;
			doc.other_type = docRows[i].children[0].children[0].children[0].children[0].children[2].children[0].value;
			
			
			doc.format = docRows[i].children[0].children[0].children[1].children[0].children[1].value;
			doc.other_format = docRows[i].children[0].children[0].children[1].children[0].children[2].children[0].value;

			//if this type is file, get file name, otherwise get name from div
			//look in this node: console.log(docRows[i].children[0].children[0].children[2].children[0].children[1]);
			
			//console.log(docRows[i].children[0].children[0].children[2].children[0].children[1].type);
			
			if(docRows[i].children[0].children[0].children[2].children[0].children[1].type == "file"){
				//get file name from input - remove path first
				var fullPath = docRows[i].children[0].children[0].children[2].children[0].children[1].value;
				var fileName = fullPath.split(/[\\\/]/).pop();
				//alert(fileName);
			}else{
				//get file name from div
				//console.log(docRows[i].children[0].children[0].children[2].children[0].children[1].children[0].innerHTML);
				var fileName = docRows[i].children[0].children[0].children[2].children[0].children[1].children[0].innerHTML;
			}
			
			doc.path = fileName;
			
			//doc.path = 'foofoo';
			doc.id = docRows[i].children[0].children[0].children[3].children[0].children[1].value;
			doc.uuid = docRows[i].children[0].children[0].children[3].children[0].children[2].value;
			doc.description = docRows[i].children[0].children[1].children[0].children[0].children[1].value;
			
			docsArray.push(doc);
		}
		apparatus.documents = docsArray;
	}

	var outObject = new Object();
	outObject.apparatus = apparatus;
	
	var outJSONString = JSON.stringify(outObject);
	
	return outJSONString;
}














/*
function exper_handleApparatusDocTypeChange(apparatusNum){
	let docRow = document.getElementById("docRow-"+apparatusNum);
	let docType = findSubNode(docRow,"docType").value;
	let otherDocTypeHolder = findSubNode(docRow,"otherDocTypeHolder");
	let otherDocType = findSubNode(docRow,"otherDocType");
	otherDocType.value = "";
	if(docType == "Other"){
		otherDocTypeHolder.style.display = "inline";
	}else{
		otherDocTypeHolder.style.display = "none";
	}
}

function exper_handleApparatusDocFormatChange(apparatusNum){
	let docRow = document.getElementById("docRow-"+apparatusNum);
	let docFormat = findSubNode(docRow,"docFormat").value;
	
	console.log("docFormat: " + docFormat);
	
	let otherDocFormatHolder = findSubNode(docRow,"otherDocFormatHolder");
	let otherDocFormat = findSubNode(docRow,"otherDocFormat");
	otherDocFormat.value = "";
	if(docFormat == "Other"){
		otherDocFormatHolder.style.display = "inline";
	}else{
		otherDocFormatHolder.style.display = "none";
	}
}



function exper_handleApparatusDocTypeChange(apparatusNum){
	console.log("type here");
}

function exper_handleApparatusDocFormatChange(apparatusNum){
	console.log("format here");
}

*/















function buildFacilityJSON() {

		var facility = new Object();
		
		var address = new Object();
		address.street = $( "#street" ).val();
		address.building = $( "#buildingApartment" ).val();
		address.postcode = $( "#postalCode" ).val();
		address.city = $( "#city" ).val();
		address.state = $( "#state" ).val();
		address.country = $( "#country" ).val();
		address.latitude = $( "#latitude" ).val();
		address.longitude = $( "#longitude" ).val();
		facility.address = address;
		
		var contact = new Object();
		contact.firstname = $( "#firstName" ).val();
		contact.lastname = $( "#lastName" ).val();
		contact.affiliation = $( "#affiliation" ).val();
		contact.email = $( "#email" ).val();
		contact.phone = $( "#phone" ).val();
		contact.website = $( "#website" ).val();
		contact.id = $( "#orcid" ).val();
		facility.contact = contact;
		
		facility.institute = $( "#instituteName" ).val();
		facility.department = $( "#department" ).val();
		facility.name = $( "#facilityName" ).val();
		facility.type = $( "#facilityType" ).val();
		facility.other_type = $( "#otherFacilityType" ).val();
		facility.id = $( "#facilityId" ).val();
		facility.website = $( "#facilityWebsite" ).val();
		facility.description = $( "#facilityDescription" ).val();
		
		var outObject = new Object();
		outObject.facility = facility;
		
		var outJSONString = JSON.stringify(outObject);
		
		return outJSONString;

}

function doSubmitNewFacility() {
	var error = checkForFacilitySubmitErrors();
	if(error != ""){
		alert(error);
	}else{
		//OK, now create JSON for POSTING to the server
		var outJSONString = buildFacilityJSON();

		//POST json to inNewFacility.php
		$.post( "inNewFacility.php", {json: outJSONString})
			.done(function( data ) {
				if(data.Error){
					alert("Error!\n" + data.Error);
				}else{
					console.log(data);
					$( "#bigWindow" ).html('\
					<div class="topTitle green center" style="padding-top:50px;">Success!</div>\
					<div class="mainFSLegend" style="text-align: center; padding-top:30px;">Facility<br>'+data.name+'<br>has been added to the database.</div>\
					<div style="text-align:center;padding-top:25px;"><button class="submitButton" style="vertical-align:middle" onclick="window.location.href = \'apparatus_repository\';"><span>Continue </span></button></div>\
					');
				}
				
		});
		
	}
}

function doSubmitEditFacility(pkey) {
	var error = checkForFacilitySubmitErrors();
	if(error != ""){
		alert(error);
	}else{
		//OK, now create JSON for POSTING to the server
		var outJSONString = buildFacilityJSON();

		//POST json to inNewFacility.php
		$.post( "inEditFacility.php?p="+pkey, {json: outJSONString})
			.done(function( data ) {
				if(data.Error){
					alert("Error!\n" + data.Error);
				}else{
					console.log(data);
					$( "#bigWindow" ).html('\
					<div class="topTitle green center" style="padding-top:50px;">Success!</div>\
					<div class="mainFSLegend" style="text-align: center; padding-top:30px;">Changes to<br>'+data.name+'<br>have been saved to the database.</div>\
					<div style="text-align:center;padding-top:25px;"><button class="submitButton" style="vertical-align:middle" onclick="window.location.href = \'apparatus_repository\';"><span>Continue </span></button></div>\
					');
				}
				
		});
		
	}
}

function doMultiFileDebug() {

	var fd = new FormData();
	fd.append('foo','bar');
	fd.append('blah','boo');
	
	var file1files = $('#foofile1')[0].files;
	fd.append('foofoofile1id',file1files[0]);
	
	var file2files = $('#foofile2')[0].files;
	fd.append('foofoofile2id',file2files[0]);

	$.ajax({
		url : "debug.php",
		type: "POST",
		data : fd,
		processData: false,
		contentType: false,
		success:function(data){
			if(data.Error){
				alert("Error!\n" + data.Error);
			}else{
				console.log(data);
			}
		},
		error: function(){
			//if fails     
		}
	});
}

function fooDebug() {
	document.getElementById("testSelect").value = "4";
}

function addApparatusDocument() {

	let docsDiv = document.getElementById("documentRows");
	let existingRows = docsDiv.children;
	
	//Figure out what row we're on
	var newRowNum = existingRows.length + 1;

	let sourceDoc = document.getElementById("sourceApparatusDocumentRow");
	let newDoc = sourceDoc.cloneNode(true);
	newDoc.id = "docRow-" + newRowNum;
	
	newDoc.children[1].children[0].children[0].setAttribute('onclick','deleteApparatusDocument('+ newRowNum +');');
	newDoc.children[1].children[1].children[0].setAttribute('onclick','moveApparatusDocumentUp('+ newRowNum +');');
	newDoc.children[1].children[2].children[0].setAttribute('onclick','moveApparatusDocumentDown('+ newRowNum +');');

	//Some Debug Values
	//newDoc.children[0].children[0].children[3].children[0].children[1].value = "doc_id_"+newRowNum;
	
	//Add onClick for new file uploads
	let fileHolder = findSubNode(newDoc, "fileHolder");
	fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="exper_uploadApparatusFile(' + newRowNum + ')">';
	
	//Add UUID to Document to track file relationships later
	newDoc.children[0].children[0].children[3].children[0].children[2].value = uuidv4();
	
	//Hide Up Arrow
	if(newRowNum == 1){
		newDoc.children[1].children[1].children[0].style.display = "none";
	}
	
	//Hide Down Arrow
	newDoc.children[1].children[2].children[0].style.display = "none";
	
	//If newRowNum > 0, go to previous row and show down button
	if(newRowNum > 1){
		existingRows[newRowNum-2].children[1].children[2].children[0].style.display = "inline";
	}

	docsDiv.appendChild(newDoc);
	
	exper_fixButtonsAndDivs();

}

function moveApparatusDocumentUp(rowNum){

	let documentsTable = document.getElementById("documentRows");
	let newTable = documentsTable.cloneNode(true);
	var toPos = rowNum - 1;
	var numRows = documentsTable.children.length;

	//First, delete rows from new table
	removeAllChildNodes(newTable);

	//Move row out into a fake table
	//let holdingDiv = document.getElementById("holdingDiv").cloneNode(true);
	let holdingDiv = document.createElement("div");
	holdingDiv.append(documentsTable.children[rowNum-1]);
	
	
	for(let x = 1; x <= numRows; x++){
		if(x == toPos){
			newTable.append(holdingDiv.children[0]);
		}else{
			newTable.append(documentsTable.children[0]);
		}
	}

	

	/*
	for(var i = 0; i < numRows; i++){
		var locNum = i + 1;
		newTable.children[i].id = "docRow-" + locNum;
		newTable.children[i].children[1].children[0].children[0].setAttribute('onclick','deleteApparatusDocument('+ locNum +');');
		newTable.children[i].children[1].children[1].children[0].setAttribute('onclick','moveApparatusDocumentUp('+ locNum +');');
		newTable.children[i].children[1].children[2].children[0].setAttribute('onclick','moveApparatusDocumentDown('+ locNum + ');');

		
		if(i==0){
			//Disable up
			newTable.children[i].children[1].children[1].children[0].style.display = "none";
			
			if(newTable.children.length == 1) {
				//Disable down
				newTable.children[i].children[1].children[2].children[0].style.display = "none";
			}else{
				//Enable down
				newTable.children[i].children[1].children[2].children[0].style.display = "inline";
			}
		}else if(i == numRows - 1) {
			//Disable down on last row
			newTable.children[i].children[1].children[2].children[0].style.display = "none";
			if(newTable.children.length > 1) {
				//Enable up
				newTable.children[i].children[1].children[1].children[0].style.display = "inline";

			}else{
				//Disable up
				newTable.children[i].children[1].children[1].children[0].style.display = "none";
			}
		}else{
			//Enable both
			newTable.children[i].children[1].children[1].children[0].style.display = "inline";
			newTable.children[i].children[1].children[2].children[0].style.display = "inline";
		}
	}
	*/

	documentsTable.remove();
	newTable.id = "documentRows";

	document.getElementById("docsWrapper").append(newTable);
	
	exper_fixButtonsAndDivs();
}

function deleteApparatusDocumentFile(uuid){
	let documentsTable = document.getElementById("documentRows");
	var numRows = documentsTable.children.length;
	for(let x = 0; x < numRows; x++){
		let row = documentsTable.children[x];
		if(row.children[0].children[0].children[3].children[0].children[2].value == uuid){

			row.children[0].children[0].children[2].children[0].children[1].remove();
			row.children[0].children[0].children[2].children[0].innerHTML += '<input type="file" class="formControl"/>';

		}
	}
}

function moveApparatusDocumentDown(rowNum){

	let documentsTable = document.getElementById("documentRows");
	let newTable = documentsTable.cloneNode(true);
	var toPos = rowNum + 1;
	var numRows = documentsTable.children.length;
	
	//First, delete rows from new table
	removeAllChildNodes(newTable);

	//Move row out into a fake table
	//let holdingDiv = document.getElementById("holdingDiv").cloneNode(true);
	let holdingDiv = document.createElement("div");
	holdingDiv.append(documentsTable.children[rowNum-1]);
	
	
	for(let x = 1; x <= numRows; x++){
		if(x == toPos){
			newTable.append(holdingDiv.children[0]);
		}else{
			newTable.append(documentsTable.children[0]);
		}
	}

	
	
	
	/*
	for(var i = 0; i < numRows; i++){
		var locNum = i + 1;
		newTable.children[i].id = "docRow-" + locNum;
		newTable.children[i].children[1].children[0].children[0].setAttribute('onclick','deleteApparatusDocument('+ locNum +');');
		newTable.children[i].children[1].children[1].children[0].setAttribute('onclick','moveApparatusDocumentUp('+ locNum +');');
		newTable.children[i].children[1].children[2].children[0].setAttribute('onclick','moveApparatusDocumentDown('+ locNum + ');');

		
		if(i==0){
			//Disable up
			newTable.children[i].children[1].children[1].children[0].style.display = "none";
			
			if(newTable.children.length == 1) {
				//Disable down
				newTable.children[i].children[1].children[2].children[0].style.display = "none";
			}else{
				//Enable down
				newTable.children[i].children[1].children[2].children[0].style.display = "inline";
			}
		}else if(i == numRows - 1) {
			//Disable down on last row
			newTable.children[i].children[1].children[2].children[0].style.display = "none";
			if(newTable.children.length > 1) {
				//Enable up
				newTable.children[i].children[1].children[1].children[0].style.display = "inline";

			}else{
				//Disable up
				newTable.children[i].children[1].children[1].children[0].style.display = "none";
			}
		}else{
			//Enable both
			newTable.children[i].children[1].children[1].children[0].style.display = "inline";
			newTable.children[i].children[1].children[2].children[0].style.display = "inline";
		}
		
	}
	*/

	documentsTable.remove();
	newTable.id = "documentRows";

	document.getElementById("docsWrapper").append(newTable);
	
	exper_fixButtonsAndDivs();
}

function deleteApparatusDocument(rowNum){

	let documentsTable = document.getElementById("documentRows");
	documentsTable.children[rowNum-1].remove();
	
	exper_fixButtonsAndDivs();
	
	/*
	let numRows = documentsTable.children.length;
	
	for(var i = 0; i < documentsTable.children.length; i++){
		var locNum = i + 1;
		documentsTable.children[i].id = "docRow-" + locNum;
		documentsTable.children[i].children[1].children[0].children[0].setAttribute('onclick','deleteApparatusDocument('+ locNum +');');
		documentsTable.children[i].children[1].children[1].children[0].setAttribute('onclick','moveApparatusDocumentUp('+ locNum +');');
		documentsTable.children[i].children[1].children[2].children[0].setAttribute('onclick','moveApparatusDocumentDown('+ locNum + ');');

		
		if(i==0){
			//Disable up
			documentsTable.children[i].children[1].children[1].children[0].style.display = "none";
			
			if(documentsTable.children.length == 1) {
				//Disable down
				documentsTable.children[i].children[1].children[2].children[0].style.display = "none";
			}else{
				//Enable down
				documentsTable.children[i].children[1].children[2].children[0].style.display = "inline";
			}
		}else if(i == numRows - 1) {
			//Disable down on last row
			documentsTable.children[i].children[1].children[2].children[0].style.display = "none";
			if(documentsTable.children.length > 1) {
				//Enable up
				documentsTable.children[i].children[1].children[1].children[0].style.display = "inline";

			}else{
				//Disable up
				documentsTable.children[i].children[1].children[1].children[0].style.display = "none";
			}
		}else{
			//Enable both
			documentsTable.children[i].children[1].children[1].children[0].style.display = "inline";
			documentsTable.children[i].children[1].children[2].children[0].style.display = "inline";
		}
		
	}
	*/

}

function addApparatusParamRow(){

	let paramsWrapper = document.getElementById("paramsTable");
	let paramsTable = paramsWrapper.children[0];

	let existingRows = paramsTable.children;
	
	//Figure out what row we're on
	var newRowNum = paramsTable.children.length;

	let sourceParam = document.getElementById("sourceParamRow");
	let newParam = sourceParam.cloneNode(true);
	newParam.id = "paramRow-" + newRowNum;
	paramsTable.appendChild(newParam);

	//Show Table if it is Hidden
	paramsWrapper.style.display = "block";
	
	var myChildren = newParam.children;
	
	//Hide Up Arrow
	if(newRowNum == 1){
		myChildren[6].children[0].children[1].style.display = "none";
	}
	
	//Hide Down Arrow
	myChildren[6].children[0].children[2].style.display = "none";
	
	//If newRowNum > 0, go to previous row and show down button
	if(newRowNum > 1){
		existingRows[newRowNum-1].children[6].children[0].children[2].style.display = "inline";
	}
	
	//Set some test vals
	//myChildren[5].children[0].value = newRowNum;

	//Set onClick Attributes for Buttons
	myChildren[6].children[0].children[0].setAttribute('onclick','deleteApparatusParam('+newRowNum+');')
	myChildren[6].children[0].children[1].setAttribute('onclick','moveApparatusParamUp('+newRowNum+');')
	myChildren[6].children[0].children[2].setAttribute('onclick','moveApparatusParamDown('+newRowNum+');')


}

function moveApparatusParamUp(rowNum){
	let paramsTable = document.getElementById("paramsTable");
	let newTable = paramsTable.cloneNode(true);
	var toPos = rowNum - 1;
	var numRows = paramsTable.children[0].children.length - 1;

	//First, delete rows from new table
	for(let x = 0; x < numRows; x++){
		newTable.children[0].children[numRows - x].remove();
	}

	//Move row out into a fake table
	let holdingTable = document.getElementById("holdingTable").cloneNode(true);
	holdingTable.append(paramsTable.children[0].children[rowNum]);
	
	
	for(let x = 1; x < numRows; x++){
		if(x == toPos) newTable.children[0].append(holdingTable.children[0]);
		newTable.children[0].append(paramsTable.children[0].children[1]);
	}
	
	
	for(var i = 1; i <= numRows; i++){
		newTable.children[0].children[i].id = "paramRow-" + i;
		newTable.children[0].children[i].children[6].children[0].children[0].setAttribute('onclick','deleteApparatusParam('+ i +');')
		newTable.children[0].children[i].children[6].children[0].children[1].setAttribute('onclick','moveApparatusParamUp('+ i +');')
		newTable.children[0].children[i].children[6].children[0].children[2].setAttribute('onclick','moveApparatusParamDown('+ i +');')

		if(i==1){
			//Disable up
			newTable.children[0].children[i].children[6].children[0].children[1].style.display = "none";
			//Disable down
			if(newTable.children[0].children.length == 2) newTable.children[0].children[i].children[6].children[0].children[2].style.display = "none";
		}else if(i == numRows) {
			//Disable down on last row
			newTable.children[0].children[i].children[6].children[0].children[2].style.display = "none";
		}else{
			//Enable both
			newTable.children[0].children[i].children[6].children[0].children[1].style.display = "inline";
			newTable.children[0].children[i].children[6].children[0].children[2].style.display = "inline";
		}
	}
	
	paramsTable.remove();
	newTable.id = "paramsTable";
	

	document.getElementById("paramsSubDiv").append(newTable);

}

function moveApparatusParamDown(rowNum){
	
	let paramsTable = document.getElementById("paramsTable");
	let newTable = paramsTable.cloneNode(true);
	var toPos = rowNum + 1;
	var numRows = paramsTable.children[0].children.length - 1;
	
	//First, delete rows from new table
	for(let x = 0; x < numRows; x++){
		newTable.children[0].children[numRows - x].remove();
	}

	//Move row out into a fake table
	let holdingTable = document.getElementById("holdingTable").cloneNode(true);
	holdingTable.append(paramsTable.children[0].children[rowNum]);
	
	
	for(let x = 1; x < numRows; x++){
		if(x == toPos){
			newTable.children[0].append(holdingTable.children[0]);
		}
		newTable.children[0].append(paramsTable.children[0].children[1]);
	}
	
	if(newTable.children[0].children.length < numRows + 1) newTable.children[0].append(holdingTable.children[0]);
	
	
	for(var i = 1; i <= numRows; i++){
		newTable.children[0].children[i].id = "paramRow-" + i;
		newTable.children[0].children[i].children[6].children[0].children[0].setAttribute('onclick','deleteApparatusParam('+ i +');')
		newTable.children[0].children[i].children[6].children[0].children[1].setAttribute('onclick','moveApparatusParamUp('+ i +');')
		newTable.children[0].children[i].children[6].children[0].children[2].setAttribute('onclick','moveApparatusParamDown('+ i +');')

		if(i==1){
			//Disable up
			newTable.children[0].children[i].children[6].children[0].children[1].style.display = "none";
			//Disable down
			if(newTable.children[0].children.length == 2) newTable.children[0].children[i].children[6].children[0].children[2].style.display = "none";
		}else if(i == numRows) {
			//Disable down on last row
			newTable.children[0].children[i].children[6].children[0].children[2].style.display = "none";
		}else{
			//Enable both
			newTable.children[0].children[i].children[6].children[0].children[1].style.display = "inline";
			newTable.children[0].children[i].children[6].children[0].children[2].style.display = "inline";
		}
	}
	
	paramsTable.remove();
	newTable.id = "paramsTable";
	

	document.getElementById("paramsSubDiv").append(newTable);
}

function deleteApparatusParam(rowNum){
	
	let paramsTable = document.getElementById("paramsTable");
	paramsTable.children[0].children[rowNum].remove();
	
	numRows = paramsTable.children[0].children.length;
	for(var i = 1; i < numRows; i++){
		paramsTable.children[0].children[i].id = "paramRow-" + i;
		paramsTable.children[0].children[i].children[6].children[0].children[0].setAttribute('onclick','deleteApparatusParam('+ i +');')
		paramsTable.children[0].children[i].children[6].children[0].children[1].setAttribute('onclick','moveApparatusParamUp('+ i +');')
		paramsTable.children[0].children[i].children[6].children[0].children[2].setAttribute('onclick','moveApparatusParamDown('+ i +');')

		if(i==1){
			//Disable up
			paramsTable.children[0].children[i].children[6].children[0].children[1].style.display = "none";
			//Disable down
			if(paramsTable.children[0].children.length == 2) paramsTable.children[0].children[i].children[6].children[0].children[2].style.display = "none";
		}else if(i == numRows-1) {
			//Disable down on last row
			paramsTable.children[0].children[i].children[6].children[0].children[2].style.display = "none";
		}else{
			//Enable both
			paramsTable.children[0].children[i].children[6].children[0].children[1].style.display = "inline";
			paramsTable.children[0].children[i].children[6].children[0].children[2].style.display = "inline";
		}
	}
	
	if(paramsTable.children[0].children.length == 1){
		paramsTable.style.display = "none";
	}
}

// ************************************************************************************************************************************************************
//								Experiment Page Section
// ************************************************************************************************************************************************************

var facilityData = null;
var apparatusData = null;
var daqData = null;
var sampleData = null;
var experimentData = null;
var dataData = null;

var tempFacilityData = null;
var tempApparatusData = null;
var tempDaqData = null;
var tempSampleData = null;
var tempExperimentData = null;
var tempDataData = null;

function clearExperimentPageData(){
	var facilityData = null;
	var apparatusData = null;
	var daqData = null;
	var sampleData = null;
	var experimentData = null;
	var dataData = null;
}

function isJSON(str) {
    if (typeof str !== 'string') return false;
    try {
        const result = JSON.parse(str);
        const type = Object.prototype.toString.call(result);
        return type === '[object Object]' 
            || type === '[object Array]';
    } catch (err) {
        return false;
    }
}

function setupExperimentalIdInputs(){
	let mainExperimentId = document.getElementById("mainExperimentId");
	mainExperimentId.setAttribute('onchange','handleMainExperimentIdChange();');
	
	let experimentId = document.getElementById("experimentId");
	experimentId.setAttribute('onchange','handleExperimentIdChange();');
}

function handleMainExperimentIdChange(){
	let newVal = document.getElementById("mainExperimentId").value;
	document.getElementById("experimentId").value = newVal;
	if(experimentData != null){
		experimentData.id = newVal;
	}
	//exper_buildExperimentData();
}

function handleExperimentIdChange(){
	let newVal = document.getElementById("experimentId").value;
	document.getElementById("mainExperimentId").value = newVal;
	//exper_buildExperimentData();
}

function exper_closeChooseExperimentModal(){
	let chooseExperimentModal = document.getElementById("chooseExperimentModal");
	chooseExperimentModal.style.display = "none";
}

function exper_openChooseExperimentModal(dataType){
	let chooseExperimentModal = document.getElementById("chooseExperimentModal");
	chooseExperimentModal.style.display = "inline";
	
	
	$.ajax({
		url : "my_experiments.json",
		type: "GET",
		processData: false,
		contentType: false,
		success:function(data){
			if(data.Error){
				alert("Error!\n" + data.Error);
			}else{
				exper_fillExperimentList(data, dataType);
			}
		},
		error: function(){
			//if fails     
		}
	});
	
}

function exper_fillExperimentList(data, dataType){
	console.log(data);
	let experimentsDiv = document.getElementById("experimentList");
	experimentsDiv.innerHTML = "";
	
	let showTable = false;
	
	let outString = "";
	
	if(data.projects != null){
		for(let pNum = 0; pNum < data.projects.length; pNum ++){
			let p = data.projects[pNum];
			if(p.experiments != null){
				if(p.experiments.length > 0){
					showTable = true;
					outString += '<h3 style="font-size:1.7em;">Project: '+p.name+'</h3><div class="strabotable" style="margin-left:0px;"><table><tr><td style="width:100px;">&nbsp;</td><td>Experiment ID</td><td>Created Timestamp</td><td>Modified Timestamp</td></tr>';
					for(let eNum = 0; eNum < p.experiments.length; eNum ++){
						let e = p.experiments[eNum];
						outString += '<tr><td style="width:10px;"><a href="javascript:exper_loadRemoteExperiment('+ e.pkey + ', \'' + dataType + '\');">Select</a></td><td>'+e.id+'</td><td>'+e.created_timestamp+'</td><td>'+e.modified_timestamp+'</td></tr>';
					}
					
					outString += '</table></div><div style="margin-top:15px;"></div>';
				}
			}
		}
	}
	
	if(showTable == false){
		experimentsDiv.innerHTML = "No existing projects found.";
	}else{
		experimentsDiv.innerHTML = outString;
	}
}

function exper_loadRemoteExperiment(experimentPkey, dataType){
	exper_closeChooseExperimentModal();
	exper_fetchExperiment(experimentPkey, dataType);
}

function exper_fetchExperiment(experimentPkey, dataType){
	
	console.log("get_experiment?e="+experimentPkey);
	
	$.ajax({
		url : "get_experiment?e="+experimentPkey,
		type: "GET",
		processData: false,
		contentType: false,
		success:function(data){
			if(data.Error){
				alert("Error!\n" + data.Error);
			}else{
				//console.log(data);
				let jsonString = JSON.stringify(data, null, "\t");
				//console.log(jsonString);
				exper_loadExperiment(data, dataType);
			}
		},
		error: function(){
			//if fails     
		}
	});
}




function exper_loadExampleDataFromJSON(dataType){
	if (confirm("Are you sure you want to load example data?\nThis will clear any data already entered in the interface.") == true) {

		$.ajax({
			url : "Carr_057_UM.json",
			type: "GET",
			processData: false,
			contentType: false,
			success:function(data){
				if(data.Error){
					alert("Error!\n" + data.Error);
				}else{
					//console.log(data);
					let jsonString = JSON.stringify(data, null, "\t");
					//console.log(jsonString);
					exper_loadExperiment(data, dataType);
				}
			},
			error: function(){
				//if fails     
			}
		});

	}
}








function exper_fetchExperimentForViewOnly(experimentPkey){
	$.ajax({
		url : "get_experiment?e="+experimentPkey,
		type: "GET",
		processData: false,
		contentType: false,
		success:function(data){
			if(data.Error){
				alert("Error!\n" + data.Error);
			}else{
				//console.log(data);
				let jsonString = JSON.stringify(data, null, "\t");
				//console.log(jsonString);
				exper_loadExperimentForView(data);
			}
		},
		error: function(){
			//if fails     
		}
	});
}

function exper_fetchDOIExperimentForViewOnly(projectuuid, experimentuuid){
	
	console.log(projectuuid);
	console.log(experimentuuid);
	
	$.ajax({
		url : "https://strabospot.org/experimental/downloadDOIExperiment.php?p="+projectuuid+"&e="+experimentuuid,
		type: "GET",
		processData: false,
		contentType: false,
		success:function(data){
			if(data.Error){
				alert("Error!\n" + data.Error);
			}else{
				//console.log(data);
				let jsonString = JSON.stringify(data, null, "\t");
				//console.log(jsonString);
				exper_loadExperimentForView(data);
			}
		},
		error: function(){
			//if fails     
		}
	});
	
}

function exper_loadExperimentForView(inData){

	if(inData.facility != null) facilityData = inData.facility;
	if(inData.apparatus != null) apparatusData = inData.apparatus;
	if(inData.daq != null) daqData = inData.daq;
	if(inData.sample != null) sampleData = inData.sample;
	if(inData.experiment != null) experimentData = inData.experiment;
	if(inData.data != null) dataData = inData.data;
	fixAllButtons();
	exper_fixDownloadButton();	
}

//Add function for each section to load from data to enable cancel functionality


function exper_loadExperiment(inData, dataType){

	console.log("dataType: " + dataType);
	
	document.getElementById("loadingExperimentBox").style.display = "inline";
	
	if(dataType == 'all') exper_clearAllData();
	if(dataType == 'facilityApparatus') exp_deleteApparatusDataRaw();
	if(dataType == 'daq') exper_deleteDAQDataRaw();
	if(dataType == 'sample') exper_deleteSampleDataRaw();
	if(dataType == 'experiment') exper_deleteExpSetupDataraw();
	if(dataType == 'data') exper_deleteDataDataRaw();


	/*
	let facilityData = null;
	let apparatusData = null;
	let daqData = null;
	let sampleData = null;
	let experimentData = null;
	let dataData = null;
	*/
	if(dataType == 'all') document.getElementById("experimentUUID").value = inData.uuid;

	if(dataType == 'facilityApparatus' || dataType == 'all') if(inData.facility != null) facilityData = inData.facility;
	if(dataType == 'facilityApparatus' || dataType == 'all') if(inData.apparatus != null) apparatusData = inData.apparatus; exper_populateApparatusData();
	if(dataType == 'daq' || dataType == 'all') if(inData.daq != null) daqData = inData.daq; exper_populateDAQData();
	if(dataType == 'sample' || dataType == 'all') if(inData.sample != null) sampleData = inData.sample; exper_populateSampleData();
	if(dataType == 'experiment' || dataType == 'all') if(inData.experiment != null) experimentData = inData.experiment; exper_populateExperimentData();
	if(dataType == 'data' || dataType == 'all') if(inData.data != null) dataData = inData.data; exper_populateDataData();

	if(dataType == 'experiment' || dataType == 'all'){
		let inExperimentId = null;
		if(inData.experimentid != null){
			inExperimentId = inData.experimentid;
		}
		if(inExperimentId == null){
			if(experimentData != null){
				if(experimentData.id != null){
					inExperimentId = experimentData.id;
				}
			}
		}
		if(inExperimentId == null){
			inExperimentId = "";
		}
		
		document.getElementById("mainExperimentId").value = inExperimentId;
		document.getElementById("experimentId").value = inExperimentId;
	}
	
	//DAQ
	if(daqData != null) populateDAQDataToInterface(daqData);

	
	//Sample
	if(sampleData != null) populateSampleDataToInterface(sampleData);

	
	//Experiment
	if(experimentData != null) populateExperimentDataToInterface(experimentData);

	
	//Data
	if(dataData != null) populateDataDataToInterface(dataData);


	exper_fixButtonsAndDivs();
	fixAllButtons();
	exper_experimentRenameAllProtocolButtons();

	setTimeout(function () {
		//exper_openExperimentSetupModal();
		document.getElementById("loadingExperimentBox").style.display = "none";
	}, 100)
	
}

function oldexper_loadExperiment(inData){

	document.getElementById("loadingExperimentBox").style.display = "inline";
	
	exper_clearAllData();
	
	/*
	let facilityData = null;
	let apparatusData = null;
	let daqData = null;
	let sampleData = null;
	let experimentData = null;
	let dataData = null;
	*/
	document.getElementById("experimentUUID").value = inData.uuid;

	if(inData.facility != null) facilityData = inData.facility;
	if(inData.apparatus != null) apparatusData = inData.apparatus; exper_populateApparatusData();
	if(inData.daq != null) daqData = inData.daq; exper_populateDAQData();
	if(inData.sample != null) sampleData = inData.sample; exper_populateSampleData();
	if(inData.experiment != null) experimentData = inData.experiment; exper_populateExperimentData();
	if(inData.data != null) dataData = inData.data; exper_populateDataData();

	let inExperimentId = null;
	if(inData.experimentid != null){
		inExperimentId = inData.experimentid;
	}
	if(inExperimentId == null){
		if(experimentData != null){
			if(experimentData.id != null){
				inExperimentId = experimentData.id;
			}
		}
	}
	if(inExperimentId == null){
		inExperimentId = "";
	}
		
	document.getElementById("mainExperimentId").value = inExperimentId;
	document.getElementById("experimentId").value = inExperimentId;
	
	//DAQ
	if(daqData != null){
		populateDAQDataToInterface(daqData);
	}
	
	//Sample
	if(sampleData != null){
		populateSampleDataToInterface(sampleData);
	}
	
	//Experiment
	if(experimentData != null){
		populateExperimentDataToInterface(experimentData);
	}
	
	//Data
	if(dataData != null){
		populateDataDataToInterface(dataData);
	}

	exper_fixButtonsAndDivs();
	fixAllButtons();
	exper_experimentRenameAllProtocolButtons();

	setTimeout(function () {
		//exper_openExperimentSetupModal();
		document.getElementById("loadingExperimentBox").style.display = "none";
	}, 100)
	
}

//break out sections from above
function populateFacilityDataToInterface(facilityData){
	if(facilityData != null){
		//console.log(JSON.stringify(facilityData, null, "\t"));
		
		let m = document.getElementById("facilityApparatusModal");
		if(facilityData.institute != null) findSubNode(m,"instituteName").value = facilityData.institute;
		if(facilityData.department != null) findSubNode(m,"department").value = facilityData.department;
		if(facilityData.name != null) findSubNode(m,"facilityName").value = facilityData.name;
		if(facilityData.type != null) findSubNode(m,"facilityType").value = facilityData.type;
		
		if(facilityData.type == "Other") findSubNode(m,"otherFacilityTypeHolder").style.display = "inline";
		
		if(facilityData.other_type != null){
			findSubNode(m,"otherFacilityType").value = facilityData.other_type;
		}
		
		if(facilityData.id != null) findSubNode(m,"facilityId").value = facilityData.id;
		if(facilityData.website != null) findSubNode(m,"facilityWebsite").value = facilityData.website;
		if(facilityData.description != null) findSubNode(m,"facilityDescription").value = facilityData.description;
		
		if(facilityData.address != null){
			if(facilityData.address.street != null) findSubNode(m,"street").value = facilityData.address.street;
			if(facilityData.address.building != null) findSubNode(m,"buildingApartment").value = facilityData.address.building;
			if(facilityData.address.postcode != null) findSubNode(m,"postalCode").value = facilityData.address.postcode;
			if(facilityData.address.city != null) findSubNode(m,"city").value = facilityData.address.city;
			if(facilityData.address.state != null) findSubNode(m,"state").value = facilityData.address.state;
			if(facilityData.address.country != null) findSubNode(m,"country").value = facilityData.address.country;
			if(facilityData.address.latitude != null) findSubNode(m,"latitude").value = facilityData.address.latitude;
			if(facilityData.address.longitude != null) findSubNode(m,"longitude").value = facilityData.address.longitude;
		}
		
		if(facilityData.contact != null){
			if(facilityData.contact.firstname != null) findSubNode(m,"firstName").value = facilityData.contact.firstname;
			if(facilityData.contact.lastname != null) findSubNode(m,"lastName").value = facilityData.contact.lastname;
			if(facilityData.contact.affiliation != null) findSubNode(m,"affiliation").value = facilityData.contact.affiliation;
			if(facilityData.contact.email != null) findSubNode(m,"email").value = facilityData.contact.email;
			if(facilityData.contact.phone != null) findSubNode(m,"phone").value = facilityData.contact.phone;
			if(facilityData.contact.website != null) findSubNode(m,"website").value = facilityData.contact.website;
			if(facilityData.contact.id != null) findSubNode(m,"orcid").value = facilityData.contact.id;
		}

		exper_fixButtonsAndDivs();
		fixAllButtons();
	
	}
}

function populateApparatusDataToInterface(apparatusData){
	if(apparatusData != null){
		console.log("populate apparatus data here...");
		console.log(JSON.stringify(apparatusData, null, "\t"));
		
		let m = document.getElementById("facilityApparatusModal");
		if(apparatusData.name != null) findSubNode(m,"apparatusName").value = apparatusData.name;
		if(apparatusData.type != null) findSubNode(m,"apparatusType").value = apparatusData.type;
		
		if(apparatusData.type == "Other Apparatus") findSubNode(m,"otherApparatusTypeHolder").style.display = "inline";
		
		if(apparatusData.other_type != null){
			findSubNode(m,"otherApparatusType").value = apparatusData.other_type;
		}
		
		if(apparatusData.location != null) findSubNode(m,"apparatusLocation").value = apparatusData.location;
		if(apparatusData.id != null) findSubNode(m,"apparatusId").value = apparatusData.id;
		if(apparatusData.description != null) findSubNode(m,"apparatusDescription").value = apparatusData.description;
		
		if(apparatusData.features != null && apparatusData.features.length > 0){
			if(apparatusData.features.includes("Loading")) $( "#appf_loading" ).prop( "checked", true );
			if(apparatusData.features.includes("Unloading")) $( "#appf_unloading" ).prop( "checked", true );
			if(apparatusData.features.includes("Heating")) $( "#appf_heating" ).prop( "checked", true );
			if(apparatusData.features.includes("Cooling")) $( "#appf_cooling" ).prop( "checked", true );
			if(apparatusData.features.includes("High Temperature")) $( "#appf_high_temperature" ).prop( "checked", true );
			if(apparatusData.features.includes("Ultra-High Temperature")) $( "#appf_ultra-high_temperature" ).prop( "checked", true );
			if(apparatusData.features.includes("Low Temperature")) $( "#appf_low_temperature" ).prop( "checked", true );
			if(apparatusData.features.includes("Sub-Zero Temperature")) $( "#appf_sub-zero_temperature" ).prop( "checked", true );
			if(apparatusData.features.includes("High Pressure")) $( "#appf_high_pressure" ).prop( "checked", true );
			if(apparatusData.features.includes("Ultra-High Pressure")) $( "#appf_ultra-high_pressure" ).prop( "checked", true );
			if(apparatusData.features.includes("Hydrostatic Tests")) $( "#appf_hydrostatic_tests" ).prop( "checked", true );
			if(apparatusData.features.includes("HIP")) $( "#appf_hip" ).prop( "checked", true );
			if(apparatusData.features.includes("Synthesis")) $( "#appf_synthesis" ).prop( "checked", true );
			if(apparatusData.features.includes("Deposition/Evaporation")) $( "#appf_deposition_evaporation" ).prop( "checked", true );
			if(apparatusData.features.includes("Mineral Reactions")) $( "#appf_mineral_reactions" ).prop( "checked", true );
			if(apparatusData.features.includes("Hydrothermal Reactions")) $( "#appf_hydrothermal_reactions" ).prop( "checked", true );
			if(apparatusData.features.includes("Elasticity")) $( "#appf_elasticity" ).prop( "checked", true );
			if(apparatusData.features.includes("Local Axial Strain")) $( "#appf_local_axial_strain" ).prop( "checked", true );
			if(apparatusData.features.includes("Local Radial Strain")) $( "#appf_local_radial_strain" ).prop( "checked", true );
			if(apparatusData.features.includes("Elastic Moduli")) $( "#appf_elastic_moduli" ).prop( "checked", true );
			if(apparatusData.features.includes("Yield Strength")) $( "#appf_yield_strength" ).prop( "checked", true );
			if(apparatusData.features.includes("Failure Strength")) $( "#appf_failure_strength" ).prop( "checked", true );
			if(apparatusData.features.includes("Strength")) $( "#appf_strength" ).prop( "checked", true );
			if(apparatusData.features.includes("Extension")) $( "#appf_extension" ).prop( "checked", true );
			if(apparatusData.features.includes("Creep")) $( "#appf_creep" ).prop( "checked", true );
			if(apparatusData.features.includes("Friction")) $( "#appf_friction" ).prop( "checked", true );
			if(apparatusData.features.includes("Frictional Sliding")) $( "#appf_frictional_sliding" ).prop( "checked", true );
			if(apparatusData.features.includes("Slide Hold Slide")) $( "#appf_slide_hold_slide" ).prop( "checked", true );
			if(apparatusData.features.includes("Stepping")) $( "#appf_stepping" ).prop( "checked", true );
			if(apparatusData.features.includes("Pure Shear")) $( "#appf_pure_shear" ).prop( "checked", true );
			if(apparatusData.features.includes("Simple Shear")) $( "#appf_simple_shear" ).prop( "checked", true );
			if(apparatusData.features.includes("Rotary Shear")) $( "#appf_rotary_shear" ).prop( "checked", true );
			if(apparatusData.features.includes("Torsion")) $( "#appf_torsion" ).prop( "checked", true );
			if(apparatusData.features.includes("Viscosity")) $( "#appf_viscosity" ).prop( "checked", true );
			if(apparatusData.features.includes("Indentation")) $( "#appf_indentation" ).prop( "checked", true );
			if(apparatusData.features.includes("Hardness")) $( "#appf_hardness" ).prop( "checked", true );
			if(apparatusData.features.includes("Dynamic Tests")) $( "#appf_dynamic_tests" ).prop( "checked", true );
			if(apparatusData.features.includes("Hydraulic Fracturing")) $( "#appf_hydraulic_fracturing" ).prop( "checked", true );
			if(apparatusData.features.includes("Hydrothermal Fracturing")) $( "#appf_hydrothermal_fracturing" ).prop( "checked", true );
			if(apparatusData.features.includes("Shockwave")) $( "#appf_shockwave" ).prop( "checked", true );
			if(apparatusData.features.includes("Reactive Flow")) $( "#appf_reactive_flow" ).prop( "checked", true );
			if(apparatusData.features.includes("Pore Fluid Control")) $( "#appf_pore_fluid_control" ).prop( "checked", true );
			if(apparatusData.features.includes("Pore Fluid Chemistry")) $( "#appf_pore_fluid_chemistry" ).prop( "checked", true );
			if(apparatusData.features.includes("Pore Volume Compaction")) $( "#appf_pore_volume_compaction" ).prop( "checked", true );
			if(apparatusData.features.includes("Storage Capacity")) $( "#appf_storage_capacity" ).prop( "checked", true );
			if(apparatusData.features.includes("Permeability")) $( "#appf_permeability" ).prop( "checked", true );
			if(apparatusData.features.includes("Steady-State Permeability")) $( "#appf_steady-state_permeability" ).prop( "checked", true );
			if(apparatusData.features.includes("Transient Permeability")) $( "#appf_transient_permeability" ).prop( "checked", true );
			if(apparatusData.features.includes("Hydraulic Conductivity")) $( "#appf_hydraulic_conductivity" ).prop( "checked", true );
			if(apparatusData.features.includes("Drained/Undrained Pore Fluid")) $( "#appf_drained_undrained_pore_fluid" ).prop( "checked", true );
			if(apparatusData.features.includes("Uniaxial Stress/Strain")) $( "#appf_uniaxial_stress_strain" ).prop( "checked", true );
			if(apparatusData.features.includes("Biaxial Stress/Strain")) $( "#appf_biaxial_stress_strain" ).prop( "checked", true );
			if(apparatusData.features.includes("Triaxial Stress/Strain")) $( "#appf_triaxial_stress_strain" ).prop( "checked", true );
			if(apparatusData.features.includes("Differential Stress")) $( "#appf_differential_stress" ).prop( "checked", true );
			if(apparatusData.features.includes("True Triaxial")) $( "#appf_true_triaxial" ).prop( "checked", true );
			if(apparatusData.features.includes("Resistivity")) $( "#appf_resistivity" ).prop( "checked", true );
			if(apparatusData.features.includes("Electrical Resistivity")) $( "#appf_electrical_resistivity" ).prop( "checked", true );
			if(apparatusData.features.includes("Electrical Capacitance")) $( "#appf_electrical_capacitance" ).prop( "checked", true );
			if(apparatusData.features.includes("Streaming Potential")) $( "#appf_streaming_potential" ).prop( "checked", true );
			if(apparatusData.features.includes("Acoustic Velocity")) $( "#appf_acoustic_velocity" ).prop( "checked", true );
			if(apparatusData.features.includes("Acoustic Events")) $( "#appf_acoustic_events" ).prop( "checked", true );
			if(apparatusData.features.includes("P-Wave Velocity")) $( "#appf_p-wave_velocity" ).prop( "checked", true );
			if(apparatusData.features.includes("S-Wave Velocity")) $( "#appf_s-wave_velocity" ).prop( "checked", true );
			if(apparatusData.features.includes("Source Location")) $( "#appf_source_location" ).prop( "checked", true );
			if(apparatusData.features.includes("Tomography")) $( "#appf_tomography" ).prop( "checked", true );
			if(apparatusData.features.includes("In-Situ X-Ray")) $( "#appf_in-situ_x-ray" ).prop( "checked", true );
			if(apparatusData.features.includes("Infrared")) $( "#appf_infrared" ).prop( "checked", true );
			if(apparatusData.features.includes("Raman")) $( "#appf_raman" ).prop( "checked", true );
			if(apparatusData.features.includes("Visual")) $( "#appf_visual" ).prop( "checked", true );
			if(apparatusData.features.includes("Other")) $( "#appf_other" ).prop( "checked", true );
		}

		if(apparatusData.parameters != null && apparatusData.parameters.length > 0){



			for(let paramNum = 0; paramNum < apparatusData.parameters.length; paramNum ++){

				let p = apparatusData.parameters[paramNum];
				let paramsWrapper = document.getElementById("paramsTable");
				let paramsTable = paramsWrapper.children[0];
				let existingRows = paramsTable.children;
				var newRowNum = paramsTable.children.length;
				paramsWrapper.style.display = "block";

				let sourceParam = document.getElementById("sourceParamRow");
				let newParam = sourceParam.cloneNode(true);
				newParam.id = "paramRow-" + newRowNum;
				
				//add data
				if(p.type != null) findSubNode(newParam,"paramName").value = p.type;
				if(p.min != null) findSubNode(newParam,"paramMin").value = p.min;
				if(p.max != null) findSubNode(newParam,"paramMax").value = p.max;
				if(p.unit != null) findSubNode(newParam,"paramUnit").value = p.unit;
				if(p.prefix != null) findSubNode(newParam,"paramPrefix").value = p.prefix;
				if(p.note != null) findSubNode(newParam,"paramNote").value = p.note;
				

				paramsTable.appendChild(newParam);

				var myChildren = newParam.children;
	
				//Hide Up Arrow
				if(newRowNum == 1){
					myChildren[6].children[0].children[1].style.display = "none";
				}
	
				//Hide Down Arrow
				myChildren[6].children[0].children[2].style.display = "none";
	
				//If newRowNum > 0, go to previous row and show down button
				if(newRowNum > 1){
					existingRows[newRowNum-1].children[6].children[0].children[2].style.display = "inline";
				}

				//Set onClick Attributes for Buttons
				myChildren[6].children[0].children[0].setAttribute('onclick','deleteApparatusParam('+newRowNum+');')
				myChildren[6].children[0].children[1].setAttribute('onclick','moveApparatusParamUp('+newRowNum+');')
				myChildren[6].children[0].children[2].setAttribute('onclick','moveApparatusParamDown('+newRowNum+');')				

			}
		}
		
		if(apparatusData.documents != null && apparatusData.documents.length > 0){
			for(let docNum = 0; docNum < apparatusData.documents.length; docNum ++){

				let d = apparatusData.documents[docNum];
				let docsDiv = document.getElementById("documentRows");
				let existingRows = docsDiv.children;
	
				//Figure out what row we're on
				var newRowNum = existingRows.length + 1;

				let sourceDoc = document.getElementById("sourceApparatusDocumentRow");
				let newDoc = sourceDoc.cloneNode(true);
				newDoc.id = "docRow-" + newRowNum;
				
				console.log(newDoc);
				
				//doc data
				if(d.type != null) findSubNode(newDoc,"docType").value = d.type;
				
				if(d.type == "Other") findSubNode(newDoc,"otherDocTypeHolder").style.display = "inline";
				
				if(d.other_type != null){
					findSubNode(newDoc,"otherDocType").value = d.other_type;
				}

				if(d.format != null) findSubNode(newDoc,"docFormat").value = d.format;
				
				if(d.format == "Other") findSubNode(newDoc,"otherDocFormatHolder").style.display = "inline";
				
				if(d.other_format != null){
					findSubNode(newDoc,"otherDocFormat").value = d.other_format;
				}
				
				if(d.id != null) findSubNode(newDoc,"documentId").value = d.id;
				
				//fix path here
				if(d.path != null){
					findSubNode(newDoc,"originalFilename").value = d.path;
					let fileHolder = findSubNode(newDoc,"fileHolder");
					//fileHolder.remove();
					console.log("***************************************************");
					fileHolder.innerHTML = '<label class="formLabel">File Uploaded</label><div><a href="'+d.path+'" target="_blank">'+d.path+'</a></div><div><a id="deleteLink" href="javascript:void(0);">(Delete File)</a></div>';					
				}
				
				if(d.uuid != null) findSubNode(newDoc,"uuid").value = d.uuid;
				if(d.description != null) findSubNode(newDoc,"docDescription").value = d.description;

				
	
				newDoc.children[1].children[0].children[0].setAttribute('onclick','deleteApparatusDocument('+ newRowNum +');');
				newDoc.children[1].children[1].children[0].setAttribute('onclick','moveApparatusDocumentUp('+ newRowNum +');');
				newDoc.children[1].children[2].children[0].setAttribute('onclick','moveApparatusDocumentDown('+ newRowNum +');');

				//Add UUID to Document to track file relationships later
				newDoc.children[0].children[0].children[3].children[0].children[2].value = uuidv4();
	
				//Hide Up Arrow
				if(newRowNum == 1){
					newDoc.children[1].children[1].children[0].style.display = "none";
				}
	
				//Hide Down Arrow
				newDoc.children[1].children[2].children[0].style.display = "none";
	
				//If newRowNum > 0, go to previous row and show down button
				if(newRowNum > 1){
					existingRows[newRowNum-2].children[1].children[2].children[0].style.display = "inline";
				}

				docsDiv.appendChild(newDoc);



			}
		}


		exper_fixButtonsAndDivs();
		fixAllButtons();
	}
}

function populateDAQDataToInterface(daqData){
	if(daqData != null){
		if(daqData.name != null) document.getElementById("daqGroupName").value = daqData.name;
		if(daqData.type != null) document.getElementById("daqType").value = daqData.type;
		if(daqData.type != null) document.getElementById("daqType").value = daqData.type;
		if(daqData.location != null) document.getElementById("daqLocation").value = daqData.location;
		if(daqData.description != null) document.getElementById("daqDescription").value = daqData.description;
		
		//Add Devices
		if(daqData.devices != null){
			let devicesDiv = document.getElementById("daq_devices");
			devicesDiv.innerHTML = "";
			for(let deviceNum = 0; deviceNum < daqData.devices.length; deviceNum ++){
				let device = daqData.devices[deviceNum];
				let sourceDeviceDiv = document.getElementById("sourceDAQDeviceRow");
				let newDeviceDiv = sourceDeviceDiv.cloneNode(true);
				newDeviceDiv.id = "daq_device_"+deviceNum;
				
				findSubNode(newDeviceDiv, "deviceName").value = daqData.devices[deviceNum].name;
				
				//Add Channels
				if(device.channels != null){
					let channelHolder = findSubNode(newDeviceDiv, "channelHolder");
					let channelButtonHolder = findSubNode(newDeviceDiv, "channelButtonHolder");
					for(let channelNum = 0; channelNum < daqData.devices[deviceNum].channels.length; channelNum ++){
						let channel = device.channels[channelNum];
						//Channel Div
						let sourceChannelDiv = document.getElementById("sourceDAQChannelRow");
						let newChannelDiv = sourceChannelDiv.cloneNode(true);
						newChannelDiv.id = "daq_device_channel_" + deviceNum + "_" + channelNum;
						newChannelDiv.classList.add("daq_device_channel_group_" + deviceNum);
						
						let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);
						
						
						
						//Channel data here
						findSubNode(newChannelDiv, "channelHeader").value = channel.header.type;
						
						findSubNode(newChannelDiv, "otherChannelHeader").value = channel.header.other_type;
						if(channel.header.type == "Other"){
							findSubNode(newChannelDiv, "otherChannelHeaderHolder").style.display = "inline";
						}
						
						exper_updateDAQHeaderInputs_with_div(newChannelDiv, newButton);
						
						findSubNode(newChannelDiv, "specifierA").value = channel.header.spec_a;
						findSubNode(newChannelDiv, "specifierB").value = channel.header.spec_b;
						findSubNode(newChannelDiv, "otherSpecifier").value = channel.header.spec_c;
						findSubNode(newChannelDiv, "channelUnit").value = channel.header.unit;
						
						findSubNode(newChannelDiv, "channelNum").value = channel.number;
						findSubNode(newChannelDiv, "channelType").value = channel.type;
						findSubNode(newChannelDiv, "channelConfiguration").value = channel.configuration;
						findSubNode(newChannelDiv, "channelNote").value = channel.note;
						
						findSubNode(newChannelDiv, "channelResBit").value = channel.resolution;
						findSubNode(newChannelDiv, "channelMin").value = channel.range_low;
						findSubNode(newChannelDiv, "channelMax").value = channel.range_high;
						findSubNode(newChannelDiv, "channelRate").value = channel.rate;
						findSubNode(newChannelDiv, "channelFilter").value = channel.filter;
						findSubNode(newChannelDiv, "channelGain").value = channel.gain;
						
						if(channel.sensor != null){
							findSubNode(newChannelDiv, "channelSensorTemplate").value = channel.sensor.template;
							findSubNode(newChannelDiv, "channelSensorActuator").value = channel.sensor.detail;
							findSubNode(newChannelDiv, "channelSensorType").value = channel.sensor.type;
							findSubNode(newChannelDiv, "channelSensorManufacturer").value = channel.sensor.id;
							findSubNode(newChannelDiv, "channelSensorModelNum").value = channel.sensor.model;
							findSubNode(newChannelDiv, "channelSensorVersionLetter").value = channel.sensor.version;
							findSubNode(newChannelDiv, "channelSensorVersionNum").value = channel.sensor.number;
							findSubNode(newChannelDiv, "channelSensorSerialNum").value = channel.sensor.serial;
						}
						
						if(channel.calibration != null){
							findSubNode(newChannelDiv, "channelCalibrationTemplate").value = channel.calibration.template;
							findSubNode(newChannelDiv, "channelCalibrationInput").value = channel.calibration.input;
							findSubNode(newChannelDiv, "channelCalibrationUnit").value = channel.calibration.unit;
							findSubNode(newChannelDiv, "channelCalibrationExcitation").value = channel.calibration.excitation;
							findSubNode(newChannelDiv, "channelCalibrationDate").value = channel.calibration.date;
							findSubNode(newChannelDiv, "channelCalibrationNote").value = channel.calibration.note;
						}
						
						if(channel.data != null){
							let dataHolder = findSubNode(newChannelDiv, "daqDeviceDataRows");
							for(let dataNum = 0; dataNum < channel.data.length; dataNum ++){
								let dataPart = channel.data[dataNum];
								let newDataRow = document.getElementById("sourceDataRow").cloneNode(true);
								findSubNode(newDataRow, "dataAVal").value = dataPart.a;
								findSubNode(newDataRow, "dataBVal").value = dataPart.b;
								dataHolder.append(newDataRow);
							}
						}
						
						
						
						channelHolder.append(newChannelDiv);
						
						//Channel Button
						
						newButton.classList.add("daq_device_channel_button_group_" + deviceNum);

						let headerString = channel.header.type;
						let otherHeaderString = channel.header.other_type;
						let channelString = channel.number;
						let unitString = channel.header.unit;
						let newButtonString = "";

						if(headerString == "Other"){
							if(otherHeaderString != ""){
								headerString = otherHeaderString
							}
						}
	
						if(headerString == "Time" && channelString == ""){
	
							if(unitString != ""){
								newButtonString = headerString + " - " + unitString;
							}else{
								newButtonString = headerString;
							}
	
						}else{

							if(channelString != ""){
								newButtonString = channelString + " - " + headerString;
							}else{
								newButtonString = headerString;
							}
	
						}

						newButton.children[0].innerHTML = newButtonString;

						channelButtonHolder.append(newButton);
						exper_fixButtonsAndDivs();
						
					}

				}
				
				let documentHolder = findSubNode(newDeviceDiv, "documentHolder");
				documentHolder.id = "daq_device_documents_" + deviceNum;
				let documentButtonHolder = findSubNode(newDeviceDiv, "documentButtonHolder");
				documentButtonHolder.id = "daq_device_document_buttons_" + deviceNum;
				
				//Add Documents
				if(device.documents != null){

					for(let documentNum = 0; documentNum < device.documents.length; documentNum ++){
						let doc = device.documents[documentNum];
					
						let sourceDiv = document.getElementById("sourceDocumentRow");
						let newDocDiv = sourceDiv.cloneNode(true);
						newDocDiv.id = "daq_device_document_" + deviceNum + "_" + documentNum;



						findSubNode(newDocDiv, "docType").value = doc.type;

						if(doc.type == "Other") findSubNode(newDocDiv, "otherDocTypeHolder").style.display = "inline";
						
						if(doc.other_type != null && doc.other_type != ""){
							findSubNode(newDocDiv, "otherDocType").value = doc.other_type;
						}
						
						findSubNode(newDocDiv, "docFormat").value = doc.format;
						
						if(doc.format == "Other") findSubNode(newDocDiv, "otherDocFormatHolder").style.display = "inline";
						
						if(doc.other_format != null && doc.other_format != ""){
							findSubNode(newDocDiv, "otherDocFormat").value = doc.other_format;
						}



						//fix file selector
						let fileHolder = findSubNode(newDocDiv, "fileHolder");
						fileHolder.children[1].remove();
						//fileHolder.innerHTML += '<div class="existingFile"><span>''</span> <span></span><a onclick="deleteApparatusDocumentFile('+doc.uuid+')" href="javascript:void(0);">(Delete File)</a></div>';
						//fileHolder.innerHTML += '<div class="existingFile"><span id="filename">'+doc.path+'</span> <span></span><a onclick="exper_deleteDocumentFile(\''+doc.uuid+'\')" href="javascript:void(0);">(Delete File)</a></div>';

						fileHolder.innerHTML = '<label class="formLabel">File Uploaded</label><div><a href="'+doc.path+'" target="_blank">'+doc.path+'</a></div>';
						fileHolder.innerHTML += '<div><a href="javascript:void(0);" onClick="exper_deleteDAQDeviceFile(' + deviceNum + ',' + documentNum + ')">(Delete File)</a></div>';
						
						let originalFilename = findSubNode(newDocDiv, "originalFilename");
						originalFilename.value = doc.path;
						
						findSubNode(newDocDiv, "docId").value = doc.id;
						findSubNode(newDocDiv, "uuid").value = doc.uuid;
						findSubNode(newDocDiv, "docDescription").value = doc.description;
						
						
						documentHolder.append(newDocDiv);
					
						//Channel Button
						let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);
						newButton.id = "daq_device_document_button_" + deviceNum + "_" + documentNum;
						newButton.classList.add("daq_device_document_button_group_" + deviceNum);
						let documentType = doc.type;
						//let channelNumVal = channel.number;
						newButton.children[0].innerHTML = documentType;
						
						
						documentButtonHolder.append(newButton);
					}
				}
				
				devicesDiv.append(newDeviceDiv);
				
			}
		}
		
		exper_fixButtonsAndDivs();
		fixAllButtons();
		
		
	}
}

function populateSampleDataToInterface(sampleData){

	if(sampleData != null){
		if(sampleData.name != null) document.getElementById("sampleName").value = sampleData.name;
		if(sampleData.igsn != null) document.getElementById("sampleIGSN").value = sampleData.igsn;
		if(sampleData.id != null) document.getElementById("sampleID").value = sampleData.id;
		if(sampleData.description != null) document.getElementById("sampleDescription").value = sampleData.description;

		if(sampleData.parent != null){
			if(sampleData.parent.name != null) document.getElementById("parentSampleName").value = sampleData.parent.name;
			if(sampleData.parent.igsn != null) document.getElementById("parentSampleIGSN").value = sampleData.parent.igsn;
			if(sampleData.parent.id != null) document.getElementById("parentSampleID").value = sampleData.parent.id;
			if(sampleData.parent.description != null) document.getElementById("parentSampleDescription").value = sampleData.parent.description;
		}

		if(sampleData.material != null){
			if(sampleData.material.material.type != null) document.getElementById("materialType").value = sampleData.material.material.type;
			exper_updateSampleMaterialNameInput();
			if(sampleData.material.material.name != null) document.getElementById("materialName").value = sampleData.material.material.name;
			if(sampleData.material.material.state != null) document.getElementById("materialState").value = sampleData.material.material.state;
			if(sampleData.material.material.note != null) document.getElementById("materialNote").value = sampleData.material.material.note;
		
			if(sampleData.material.composition != null){
				let phaseHolder = document.getElementById("sample_mineral_phases");
				phaseHolder.innerHTML = "";

				let buttonsDiv = document.getElementById("sample_mineral_phase_buttons");
				buttonsDiv.innerHTML = "";

				for(let phaseNum = 0; phaseNum < sampleData.material.composition.length; phaseNum ++){

					let phase = sampleData.material.composition[phaseNum];
					//Phase Div
					let sourceDiv = document.getElementById("sourceSampleMineral");
					let newDiv = sourceDiv.cloneNode(true);
					newDiv.id = "sample_mineral_phase_" + phaseNum;
	
					//Add Select to New Div
					let mineralHolder = findSubNode(newDiv, "mineralSelectHolder");
					let newSelect = document.getElementById("sourceSelectMineral").cloneNode(true);
					newSelect.setAttribute('onchange','exper_sampleRenameMineralButton(' + phaseNum +');');
					newSelect.id = "mineralName";
					newSelect.value = phase.mineral;
					mineralHolder.append(newSelect);
				
					if(phase.fraction != null) findSubNode(newDiv, "mineralFraction").value = phase.fraction;
					if(phase.grainsize != null) findSubNode(newDiv, "mineralGrainSize").value = phase.grainsize;
					if(phase.unit != null) findSubNode(newDiv, "mineralUnit").value = phase.unit;

					phaseHolder.appendChild(newDiv);

					//Also add button to sideBar

					let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

					//set id, class, onclick
					newButton.id = "sample_mineral_phase_button_" + phaseNum;
					newButton.classList.add("sample_mineral_phase_button_group");
					newButton.children[0].innerHTML = phase.mineral;
					newButton.setAttribute('onclick','exper_sampleSwitchToMineral(' + phaseNum +');');
	
					buttonsDiv.append(newButton);


				}
			}
		
			if(sampleData.material.provenance != null){
				let p = sampleData.material.provenance;
				if(p.formation != null) document.getElementById("formationName").value = p.formation;
				if(p.member != null) document.getElementById("memberName").value = p.member;
				if(p.submember != null) document.getElementById("subMemberName").value = p.submember;
				if(p.source != null) document.getElementById("sampleSource").value = p.source;
			
				if(p.location != null){
					let l = p.location;
					if(l.street != null) document.getElementById("sampleLocationStreet").value = l.street;
					if(l.building != null) document.getElementById("sampleLocationBuilding").value = l.building;
					if(l.postcode != null) document.getElementById("sampleLocationPostcode").value = l.postcode;
					if(l.city != null) document.getElementById("sampleLocationCity").value = l.city;
					if(l.state != null) document.getElementById("sampleLocationState").value = l.state;
					if(l.country != null) document.getElementById("sampleLocationCountry").value = l.country;
					if(l.latitude != null) document.getElementById("sampleLocationLatitude").value = l.latitude;
					if(l.longitude != null) document.getElementById("sampleLocationLongitude").value = l.longitude;
				}
			}

			if(sampleData.material.texture != null){
				let t = sampleData.material.texture;
				if(t.bedding != null) document.getElementById("sampleTextureBedding").value = t.bedding;
				if(t.lineation != null) document.getElementById("sampleTextureLineation").value = t.lineation;
				if(t.foliation != null) document.getElementById("sampleTextureFoliation").value = t.foliation;
				if(t.fault != null) document.getElementById("sampleTextureFault").value = t.fault;

			}
		}
		
		//Parameters
		if(sampleData.parameters != null){
			
			let existingDiv = document.getElementById("sample_parameters");
			existingDiv.innerHTML = "";
			let buttonsDiv = document.getElementById("sample_parameter_buttons");
			buttonsDiv.innerHTML = "";
			
			for(let paramNum = 0; paramNum < sampleData.parameters.length; paramNum ++){
				let param = sampleData.parameters[paramNum];
				
				let sourceDiv = document.getElementById("sourceSampleParameter");
				let newDiv = sourceDiv.cloneNode(true);
				newDiv.id = "sample_parameter_" + paramNum;

				//Populate Data
				if(param.control != null) findSubNode(newDiv, "parameterVariable").value = param.control;
				
				if(param.other_control != null) findSubNode(newDiv, "otherParameterControl").value = param.other_control;
				if(param.control == "Other"){
					findSubNode(newDiv, "otherParameterControlHolder").style.display="inline";
				}
				
				if(param.value != null) findSubNode(newDiv, "parameterValue").value = param.value;
				if(param.unit != null) findSubNode(newDiv, "parameterUnit").value = param.unit;
				if(param.prefix != null) findSubNode(newDiv, "parameterPrefix").value = param.prefix;
				if(param.note != null) findSubNode(newDiv, "parameterNote").value = param.note;

				existingDiv.appendChild(newDiv);

				//Also add button to sideBar
	
				let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

				//set id, class, onclick
				newButton.id = "sample_parameter_button_" + paramNum;
				newButton.classList.add("sample_parameter_button_group");
				newButton.children[0].innerHTML = param.control;
				newButton.setAttribute('onclick','exper_sampleSwitchToParameter(' + paramNum +');');
	
				buttonsDiv.append(newButton);
				
			}
		}


		//Add Documents
		if(sampleData.documents != null){
			let documentHolder = document.getElementById("sample_documents");
			documentHolder.innerHTML = "";
			let documentButtonHolder = document.getElementById("sample_document_buttons");
			documentButtonHolder.innerHTML = "";
			for(let documentNum = 0; documentNum < sampleData.documents.length; documentNum ++){
				let doc = sampleData.documents[documentNum];
			
				let sourceDiv = document.getElementById("sourceDocumentRow");
				let newDocDiv = sourceDiv.cloneNode(true);
				newDocDiv.id = "sample_document_" + documentNum;

				findSubNode(newDocDiv, "docType").value = doc.type;

				if(doc.type == "Other") findSubNode(newDocDiv, "otherDocTypeHolder").style.display = "inline";
				
				if(doc.other_type != null && doc.other_type != ""){
					findSubNode(newDocDiv, "otherDocType").value = doc.other_type;
				}
				
				findSubNode(newDocDiv, "docFormat").value = doc.format;
				
				if(doc.format == "Other") findSubNode(newDocDiv, "otherDocFormatHolder").style.display = "inline";
				
				if(doc.other_format != null && doc.other_format != ""){
					findSubNode(newDocDiv, "otherDocFormat").value = doc.other_format;
				}
				
				//fix file selector
				let fileHolder = findSubNode(newDocDiv, "fileHolder");
				fileHolder.children[1].remove();
				//fileHolder.innerHTML += '<div class="existingFile"><span>''</span> <span></span><a onclick="deleteApparatusDocumentFile('+doc.uuid+')" href="javascript:void(0);">(Delete File)</a></div>';
				//fileHolder.innerHTML += '<div class="existingFile"><a href="'+doc.path+'" target="_blank">'+doc.path+'</a></div>';
				//fileHolder.innerHTML += '<div class="existingFile"><a onclick="exper_deleteDocumentFile(\''+doc.uuid+'\')" href="javascript:void(0);">(Delete File)</a></div>';
				
				fileHolder.innerHTML = '<label class="formLabel">File Uploaded</label><div><a href="'+doc.path+'" target="_blank">'+doc.path+'</a></div>';
				fileHolder.innerHTML += '<div><a href="javascript:void(0);" onClick="exper_deleteDocumentFile(\'' + doc.uuid + '\')">(Delete File)</a></div>';
				
				
				findSubNode(newDocDiv, "docId").value = doc.id;
				findSubNode(newDocDiv, "uuid").value = doc.uuid;
				findSubNode(newDocDiv, "originalFilename").value = doc.path;
				findSubNode(newDocDiv, "docDescription").value = doc.description;
				
			
				documentHolder.append(newDocDiv);
			
				//Channel Button
				let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);
				newButton.classList.add("sample_document_button_group");
				let documentType = doc.type;
				//let channelNumVal = channel.number;
				newButton.children[0].innerHTML = documentType;
				documentButtonHolder.append(newButton);
			}
		}
	}
}

function populateExperimentDataToInterface(experimentData){

	//console.log(experimentData);
	
	if(experimentData != null){
		if(experimentData.title != null) document.getElementById("experimentTitle").value = experimentData.title;
		
		if(document.getElementById("mainExperimentId").value != ""){
			document.getElementById("experimentId").value = document.getElementById("mainExperimentId").value;
		}else{
			if(experimentData.id != null) document.getElementById("experimentId").value = experimentData.id;
		}
		
		if(experimentData.ieda != null) document.getElementById("iedaId").value = experimentData.ieda;
		if(experimentData.start_date != null) document.getElementById("experimentStartDate").value = experimentData.start_date;
		if(experimentData.end_date != null) document.getElementById("experimentEndDate").value = experimentData.end_date;
		if(experimentData.description != null) document.getElementById("experimentDescription").value = experimentData.description;

		let experimentDiv = document.getElementById("experimentTestFeatures");

		findSubNode(experimentDiv, "f_loading").checked = false;
		findSubNode(experimentDiv, "f_unloading").checked = false;
		findSubNode(experimentDiv, "f_heating").checked = false;
		findSubNode(experimentDiv, "f_cooling").checked = false;
		findSubNode(experimentDiv, "f_high_temperature").checked = false;
		findSubNode(experimentDiv, "f_ultra-high_temperature").checked = false;
		findSubNode(experimentDiv, "f_low_temperature").checked = false;
		findSubNode(experimentDiv, "f_sub-zero_temperature").checked = false;
		findSubNode(experimentDiv, "f_high_pressure").checked = false;
		findSubNode(experimentDiv, "f_ultra-high_pressure").checked = false;
		findSubNode(experimentDiv, "f_hydrostatic_tests").checked = false;
		findSubNode(experimentDiv, "f_hip").checked = false;
		findSubNode(experimentDiv, "f_synthesis").checked = false;
		findSubNode(experimentDiv, "f_deposition_evaporation").checked = false;
		findSubNode(experimentDiv, "f_mineral_reactions").checked = false;
		findSubNode(experimentDiv, "f_hydrothermal_reactions").checked = false;
		findSubNode(experimentDiv, "f_elasticity").checked = false;
		findSubNode(experimentDiv, "f_local_axial_strain").checked = false;
		findSubNode(experimentDiv, "f_local_radial_strain").checked = false;
		findSubNode(experimentDiv, "f_elastic_moduli").checked = false;
		findSubNode(experimentDiv, "f_yield_strength").checked = false;
		findSubNode(experimentDiv, "f_failure_strength").checked = false;
		findSubNode(experimentDiv, "f_strength").checked = false;
		findSubNode(experimentDiv, "f_extension").checked = false;
		findSubNode(experimentDiv, "f_creep").checked = false;
		findSubNode(experimentDiv, "f_friction").checked = false;
		findSubNode(experimentDiv, "f_frictional_sliding").checked = false;
		findSubNode(experimentDiv, "f_slide_hold_slide").checked = false;
		findSubNode(experimentDiv, "f_stepping").checked = false;
		findSubNode(experimentDiv, "f_pure_shear").checked = false;
		findSubNode(experimentDiv, "f_simple_shear").checked = false;
		findSubNode(experimentDiv, "f_rotary_shear").checked = false;
		findSubNode(experimentDiv, "f_torsion").checked = false;
		findSubNode(experimentDiv, "f_viscosity").checked = false;
		findSubNode(experimentDiv, "f_indentation").checked = false;
		findSubNode(experimentDiv, "f_hardness").checked = false;
		findSubNode(experimentDiv, "f_dynamic_tests").checked = false;
		findSubNode(experimentDiv, "f_hydraulic_fracturing").checked = false;
		findSubNode(experimentDiv, "f_hydrothermal_fracturing").checked = false;
		findSubNode(experimentDiv, "f_shockwave").checked = false;
		findSubNode(experimentDiv, "f_reactive_flow").checked = false;
		findSubNode(experimentDiv, "f_pore_fluid_control").checked = false;
		findSubNode(experimentDiv, "f_pore_fluid_chemistry").checked = false;
		findSubNode(experimentDiv, "f_pore_volume_compaction").checked = false;
		findSubNode(experimentDiv, "f_storage_capacity").checked = false;
		findSubNode(experimentDiv, "f_permeability").checked = false;
		findSubNode(experimentDiv, "f_steady-state_permeability").checked = false;
		findSubNode(experimentDiv, "f_transient_permeability").checked = false;
		findSubNode(experimentDiv, "f_hydraulic_conductivity").checked = false;
		findSubNode(experimentDiv, "f_drained_undrained_pore_fluid").checked = false;
		findSubNode(experimentDiv, "f_uniaxial_stress_strain").checked = false;
		findSubNode(experimentDiv, "f_biaxial_stress_strain").checked = false;
		findSubNode(experimentDiv, "f_triaxial_stress_strain").checked = false;
		findSubNode(experimentDiv, "f_differential_stress").checked = false;
		findSubNode(experimentDiv, "f_true_triaxial").checked = false;
		findSubNode(experimentDiv, "f_resistivity").checked = false;
		findSubNode(experimentDiv, "f_electrical_resistivity").checked = false;
		findSubNode(experimentDiv, "f_electrical_capacitance").checked = false;
		findSubNode(experimentDiv, "f_streaming_potential").checked = false;
		findSubNode(experimentDiv, "f_acoustic_velocity").checked = false;
		findSubNode(experimentDiv, "f_acoustic_events").checked = false;
		findSubNode(experimentDiv, "f_p-wave_velocity").checked = false;
		findSubNode(experimentDiv, "f_s-wave_velocity").checked = false;
		findSubNode(experimentDiv, "f_source_location").checked = false;
		findSubNode(experimentDiv, "f_tomography").checked = false;
		findSubNode(experimentDiv, "f_in-situ_x-ray").checked = false;
		findSubNode(experimentDiv, "f_infrared").checked = false;
		findSubNode(experimentDiv, "f_raman").checked = false;
		findSubNode(experimentDiv, "f_visual").checked = false;
		findSubNode(experimentDiv, "f_other").checked = false;

		var options_str = "";

		if(experimentData.features != null){
			let features = experimentData.features;
			if(features != null){
				if(features.includes != null){
					if(features.includes("Loading")) findSubNode(experimentDiv, "f_loading").checked = true;
					if(features.includes("Unloading")) findSubNode(experimentDiv, "f_unloading").checked = true;
					if(features.includes("Heating")) findSubNode(experimentDiv, "f_heating").checked = true;
					if(features.includes("Cooling")) findSubNode(experimentDiv, "f_cooling").checked = true;
					if(features.includes("High Temperature")) findSubNode(experimentDiv, "f_high_temperature").checked = true;
					if(features.includes("Ultra-High Temperature")) findSubNode(experimentDiv, "f_ultra-high_temperature").checked = true;
					if(features.includes("Low Temperature")) findSubNode(experimentDiv, "f_low_temperature").checked = true;
					if(features.includes("Sub-Zero Temperature")) findSubNode(experimentDiv, "f_sub-zero_temperature").checked = true;
					if(features.includes("High Pressure")) findSubNode(experimentDiv, "f_high_pressure").checked = true;
					if(features.includes("Ultra-High Pressure")) findSubNode(experimentDiv, "f_ultra-high_pressure").checked = true;
					if(features.includes("Hydrostatic Tests")) findSubNode(experimentDiv, "f_hydrostatic_tests").checked = true;
					if(features.includes("HIP")) findSubNode(experimentDiv, "f_hip").checked = true;
					if(features.includes("Synthesis")) findSubNode(experimentDiv, "f_synthesis").checked = true;
					if(features.includes("Deposition/Evaporation")) findSubNode(experimentDiv, "f_deposition_evaporation").checked = true;
					if(features.includes("Mineral Reactions")) findSubNode(experimentDiv, "f_mineral_reactions").checked = true;
					if(features.includes("Hydrothermal Reactions")) findSubNode(experimentDiv, "f_hydrothermal_reactions").checked = true;
					if(features.includes("Elasticity")) findSubNode(experimentDiv, "f_elasticity").checked = true;
					if(features.includes("Local Axial Strain")) findSubNode(experimentDiv, "f_local_axial_strain").checked = true;
					if(features.includes("Local Radial Strain")) findSubNode(experimentDiv, "f_local_radial_strain").checked = true;
					if(features.includes("Elastic Moduli")) findSubNode(experimentDiv, "f_elastic_moduli").checked = true;
					if(features.includes("Yield Strength")) findSubNode(experimentDiv, "f_yield_strength").checked = true;
					if(features.includes("Failure Strength")) findSubNode(experimentDiv, "f_failure_strength").checked = true;
					if(features.includes("Strength")) findSubNode(experimentDiv, "f_strength").checked = true;
					if(features.includes("Extension")) findSubNode(experimentDiv, "f_extension").checked = true;
					if(features.includes("Creep")) findSubNode(experimentDiv, "f_creep").checked = true;
					if(features.includes("Friction")) findSubNode(experimentDiv, "f_friction").checked = true;
					if(features.includes("Frictional Sliding")) findSubNode(experimentDiv, "f_frictional_sliding").checked = true;
					if(features.includes("Slide Hold Slide")) findSubNode(experimentDiv, "f_slide_hold_slide").checked = true;
					if(features.includes("Stepping")) findSubNode(experimentDiv, "f_stepping").checked = true;
					if(features.includes("Pure Shear")) findSubNode(experimentDiv, "f_pure_shear").checked = true;
					if(features.includes("Simple Shear")) findSubNode(experimentDiv, "f_simple_shear").checked = true;
					if(features.includes("Rotary Shear")) findSubNode(experimentDiv, "f_rotary_shear").checked = true;
					if(features.includes("Torsion")) findSubNode(experimentDiv, "f_torsion").checked = true;
					if(features.includes("Viscosity")) findSubNode(experimentDiv, "f_viscosity").checked = true;
					if(features.includes("Indentation")) findSubNode(experimentDiv, "f_indentation").checked = true;
					if(features.includes("Hardness")) findSubNode(experimentDiv, "f_hardness").checked = true;
					if(features.includes("Dynamic Tests")) findSubNode(experimentDiv, "f_dynamic_tests").checked = true;
					if(features.includes("Hydraulic Fracturing")) findSubNode(experimentDiv, "f_hydraulic_fracturing").checked = true;
					if(features.includes("Hydrothermal Fracturing")) findSubNode(experimentDiv, "f_hydrothermal_fracturing").checked = true;
					if(features.includes("Shockwave")) findSubNode(experimentDiv, "f_shockwave").checked = true;
					if(features.includes("Reactive Flow")) findSubNode(experimentDiv, "f_reactive_flow").checked = true;
					if(features.includes("Pore Fluid Control")) findSubNode(experimentDiv, "f_pore_fluid_control").checked = true;
					if(features.includes("Pore Fluid Chemistry")) findSubNode(experimentDiv, "f_pore_fluid_chemistry").checked = true;
					if(features.includes("Pore Volume Compaction")) findSubNode(experimentDiv, "f_pore_volume_compaction").checked = true;
					if(features.includes("Storage Capacity")) findSubNode(experimentDiv, "f_storage_capacity").checked = true;
					if(features.includes("Permeability")) findSubNode(experimentDiv, "f_permeability").checked = true;
					if(features.includes("Steady-State Permeability")) findSubNode(experimentDiv, "f_steady-state_permeability").checked = true;
					if(features.includes("Transient Permeability")) findSubNode(experimentDiv, "f_transient_permeability").checked = true;
					if(features.includes("Hydraulic Conductivity")) findSubNode(experimentDiv, "f_hydraulic_conductivity").checked = true;
					if(features.includes("Drained/Undrained Pore Fluid")) findSubNode(experimentDiv, "f_drained_undrained_pore_fluid").checked = true;
					if(features.includes("Uniaxial Stress/Strain")) findSubNode(experimentDiv, "f_uniaxial_stress_strain").checked = true;
					if(features.includes("Biaxial Stress/Strain")) findSubNode(experimentDiv, "f_biaxial_stress_strain").checked = true;
					if(features.includes("Triaxial Stress/Strain")) findSubNode(experimentDiv, "f_triaxial_stress_strain").checked = true;
					if(features.includes("Differential Stress")) findSubNode(experimentDiv, "f_differential_stress").checked = true;
					if(features.includes("True Triaxial")) findSubNode(experimentDiv, "f_true_triaxial").checked = true;
					if(features.includes("Resistivity")) findSubNode(experimentDiv, "f_resistivity").checked = true;
					if(features.includes("Electrical Resistivity")) findSubNode(experimentDiv, "f_electrical_resistivity").checked = true;
					if(features.includes("Electrical Capacitance")) findSubNode(experimentDiv, "f_electrical_capacitance").checked = true;
					if(features.includes("Streaming Potential")) findSubNode(experimentDiv, "f_streaming_potential").checked = true;
					if(features.includes("Acoustic Velocity")) findSubNode(experimentDiv, "f_acoustic_velocity").checked = true;
					if(features.includes("Acoustic Events")) findSubNode(experimentDiv, "f_acoustic_events").checked = true;
					if(features.includes("P-Wave Velocity")) findSubNode(experimentDiv, "f_p-wave_velocity").checked = true;
					if(features.includes("S-Wave Velocity")) findSubNode(experimentDiv, "f_s-wave_velocity").checked = true;
					if(features.includes("Source Location")) findSubNode(experimentDiv, "f_source_location").checked = true;
					if(features.includes("Tomography")) findSubNode(experimentDiv, "f_tomography").checked = true;
					if(features.includes("In-Situ X-Ray")) findSubNode(experimentDiv, "f_in-situ_x-ray").checked = true;
					if(features.includes("Infrared")) findSubNode(experimentDiv, "f_infrared").checked = true;
					if(features.includes("Raman")) findSubNode(experimentDiv, "f_raman").checked = true;
					if(features.includes("Visual")) findSubNode(experimentDiv, "f_visual").checked = true;
					if(features.includes("Other")) findSubNode(experimentDiv, "f_other").checked = true;

					options_str = "";
					if(features.includes("Loading"))  options_str += '<option value="Loading">Loading</option>';
					if(features.includes("Unloading"))  options_str += '<option value="Unloading">Unloading</option>';
					if(features.includes("Heating"))  options_str += '<option value="Heating">Heating</option>';
					if(features.includes("Cooling"))  options_str += '<option value="Cooling">Cooling</option>';
					if(features.includes("High Temperature"))  options_str += '<option value="High Temperature">High Temperature</option>';
					if(features.includes("Ultra-High Temperature"))  options_str += '<option value="Ultra-High Temperature">Ultra-High Temperature</option>';
					if(features.includes("Low Temperature"))  options_str += '<option value="Low Temperature">Low Temperature</option>';
					if(features.includes("Sub-Zero Temperature"))  options_str += '<option value="Sub-Zero Temperature">Sub-Zero Temperature</option>';
					if(features.includes("High Pressure"))  options_str += '<option value="High Pressure">High Pressure</option>';
					if(features.includes("Ultra-High Pressure"))  options_str += '<option value="Ultra-High Pressure">Ultra-High Pressure</option>';
					if(features.includes("Hydrostatic Tests"))  options_str += '<option value="Hydrostatic Tests">Hydrostatic Tests</option>';
					if(features.includes("HIP"))  options_str += '<option value="HIP">HIP</option>';
					if(features.includes("Synthesis"))  options_str += '<option value="Synthesis">Synthesis</option>';
					if(features.includes("Deposition/Evaporation"))  options_str += '<option value="Deposition/Evaporation">Deposition/Evaporation</option>';
					if(features.includes("Mineral Reactions"))  options_str += '<option value="Mineral Reactions">Mineral Reactions</option>';
					if(features.includes("Hydrothermal Reactions"))  options_str += '<option value="Hydrothermal Reactions">Hydrothermal Reactions</option>';
					if(features.includes("Elasticity"))  options_str += '<option value="Elasticity">Elasticity</option>';
					if(features.includes("Local Axial Strain"))  options_str += '<option value="Local Axial Strain">Local Axial Strain</option>';
					if(features.includes("Local Radial Strain"))  options_str += '<option value="Local Radial Strain">Local Radial Strain</option>';
					if(features.includes("Elastic Moduli"))  options_str += '<option value="Elastic Moduli">Elastic Moduli</option>';
					if(features.includes("Yield Strength"))  options_str += '<option value="Yield Strength">Yield Strength</option>';
					if(features.includes("Failure Strength"))  options_str += '<option value="Failure Strength">Failure Strength</option>';
					if(features.includes("Strength"))  options_str += '<option value="Strength">Strength</option>';
					if(features.includes("Extension"))  options_str += '<option value="Extension">Extension</option>';
					if(features.includes("Creep"))  options_str += '<option value="Creep">Creep</option>';
					if(features.includes("Friction"))  options_str += '<option value="Friction">Friction</option>';
					if(features.includes("Frictional Sliding"))  options_str += '<option value="Frictional Sliding">Frictional Sliding</option>';
					if(features.includes("Slide Hold Slide"))  options_str += '<option value="Slide Hold Slide">Slide Hold Slide</option>';
					if(features.includes("Stepping"))  options_str += '<option value="Stepping">Stepping</option>';
					if(features.includes("Pure Shear"))  options_str += '<option value="Pure Shear">Pure Shear</option>';
					if(features.includes("Simple Shear"))  options_str += '<option value="Simple Shear">Simple Shear</option>';
					if(features.includes("Rotary Shear"))  options_str += '<option value="Rotary Shear">Rotary Shear</option>';
					if(features.includes("Torsion"))  options_str += '<option value="Torsion">Torsion</option>';
					if(features.includes("Viscosity"))  options_str += '<option value="Viscosity">Viscosity</option>';
					if(features.includes("Indentation"))  options_str += '<option value="Indentation">Indentation</option>';
					if(features.includes("Hardness"))  options_str += '<option value="Hardness">Hardness</option>';
					if(features.includes("Dynamic Tests"))  options_str += '<option value="Dynamic Tests">Dynamic Tests</option>';
					if(features.includes("Hydraulic Fracturing"))  options_str += '<option value="Hydraulic Fracturing">Hydraulic Fracturing</option>';
					if(features.includes("Hydrothermal Fracturing"))  options_str += '<option value="Hydrothermal Fracturing">Hydrothermal Fracturing</option>';
					if(features.includes("Shockwave"))  options_str += '<option value="Shockwave">Shockwave</option>';
					if(features.includes("Reactive Flow"))  options_str += '<option value="Reactive Flow">Reactive Flow</option>';
					if(features.includes("Pore Fluid Control"))  options_str += '<option value="Pore Fluid Control">Pore Fluid Control</option>';
					if(features.includes("Pore Fluid Chemistry"))  options_str += '<option value="Pore Fluid Chemistry">Pore Fluid Chemistry</option>';
					if(features.includes("Pore Volume Compaction"))  options_str += '<option value="Pore Volume Compaction">Pore Volume Compaction</option>';
					if(features.includes("Storage Capacity"))  options_str += '<option value="Storage Capacity">Storage Capacity</option>';
					if(features.includes("Permeability"))  options_str += '<option value="Permeability">Permeability</option>';
					if(features.includes("Steady-State Permeability"))  options_str += '<option value="Steady-State Permeability">Steady-State Permeability</option>';
					if(features.includes("Transient Permeability"))  options_str += '<option value="Transient Permeability">Transient Permeability</option>';
					if(features.includes("Hydraulic Conductivity"))  options_str += '<option value="Hydraulic Conductivity">Hydraulic Conductivity</option>';
					if(features.includes("Drained/Undrained Pore Fluid"))  options_str += '<option value="Drained/Undrained Pore Fluid">Drained/Undrained Pore Fluid</option>';
					if(features.includes("Uniaxial Stress/Strain"))  options_str += '<option value="Uniaxial Stress/Strain">Uniaxial Stress/Strain</option>';
					if(features.includes("Biaxial Stress/Strain"))  options_str += '<option value="Biaxial Stress/Strain">Biaxial Stress/Strain</option>';
					if(features.includes("Triaxial Stress/Strain"))  options_str += '<option value="Triaxial Stress/Strain">Triaxial Stress/Strain</option>';
					if(features.includes("Differential Stress"))  options_str += '<option value="Differential Stress">Differential Stress</option>';
					if(features.includes("True Triaxial"))  options_str += '<option value="True Triaxial">True Triaxial</option>';
					if(features.includes("Resistivity"))  options_str += '<option value="Resistivity">Resistivity</option>';
					if(features.includes("Electrical Resistivity"))  options_str += '<option value="Electrical Resistivity">Electrical Resistivity</option>';
					if(features.includes("Electrical Capacitance"))  options_str += '<option value="Electrical Capacitance">Electrical Capacitance</option>';
					if(features.includes("Streaming Potential"))  options_str += '<option value="Streaming Potential">Streaming Potential</option>';
					if(features.includes("Acoustic Velocity"))  options_str += '<option value="Acoustic Velocity">Acoustic Velocity</option>';
					if(features.includes("Acoustic Events"))  options_str += '<option value="Acoustic Events">Acoustic Events</option>';
					if(features.includes("P-Wave Velocity"))  options_str += '<option value="P-Wave Velocity">P-Wave Velocity</option>';
					if(features.includes("S-Wave Velocity"))  options_str += '<option value="S-Wave Velocity">S-Wave Velocity</option>';
					if(features.includes("Source Location"))  options_str += '<option value="Source Location">Source Location</option>';
					if(features.includes("Tomography"))  options_str += '<option value="Tomography">Tomography</option>';
					if(features.includes("In-Situ X-Ray"))  options_str += '<option value="In-Situ X-Ray">In-Situ X-Ray</option>';
					if(features.includes("Infrared"))  options_str += '<option value="Infrared">Infrared</option>';
					if(features.includes("Raman"))  options_str += '<option value="Raman">Raman</option>';
					if(features.includes("Visual"))  options_str += '<option value="Visual">Visual</option>';
					if(features.includes("Other"))  options_str += '<option value="Other">Other</option>';
				}
			}
		}
		
		//Populate Drop Down
		
	
		if(experimentData.author != null){
			let author = experimentData.author;
			if(author.firstname != null) document.getElementById("experimentFirstName").value = author.firstname;
			if(author.lastname != null) document.getElementById("experimentLastName").value = author.lastname;
			if(author.affiliation != null) document.getElementById("experimentAffiliation").value = author.affiliation;
			if(author.email != null) document.getElementById("experimentEmail").value = author.email;
			if(author.phone != null) document.getElementById("experimentPhone").value = author.phone;
			if(author.website != null) document.getElementById("experimentWebsite").value = author.website;
			if(author.id != null) document.getElementById("experimentORCID").value = author.id;
		}

		if(experimentData.geometry != null){

			let existingDiv = document.getElementById("experiment_geometries");
			existingDiv.innerHTML = "";
		
			let buttonsDiv = document.getElementById("experiment_geometry_buttons");
			buttonsDiv.innerHTML = "";
		
			let geometries = experimentData.geometry;

			for(let newRowNum = 0; newRowNum < geometries.length; newRowNum ++ ){
				let geom = geometries[newRowNum];

				let sourceDiv = document.getElementById("sourceExperimentGeometry");
				let newDiv = sourceDiv.cloneNode(true);
				newDiv.id = "experiment_geometry_" + newRowNum;
				newDiv.classList.add("experiment_geometry_group");

				//Add Vals
				if(geom.order != null) findSubNode(newDiv, "experimentGeometryNum").value = geom.order;
				if(geom.material != null) findSubNode(newDiv, "experimentGeometryMaterial").value = geom.material;
				if(geom.type != null) findSubNode(newDiv, "experimentGeometryType").value = geom.type;
				if(geom.geometry != null) findSubNode(newDiv, "experimentGeometryGeometry").value = geom.geometry;
			
				//Now Dimensions
				if(geom.dimensions != null){
					let  dimsDiv = findSubNode(newDiv, "dimensionRows");
					for(let dimNum = 0; dimNum < geom.dimensions.length; dimNum ++){
						let dim = geom.dimensions[dimNum];
						let sourceDimDiv = document.getElementById("sourceExperimentGeometryDimension");
						let newDimDiv = sourceDimDiv.cloneNode(true);
					
						newDimDiv.id = "experiment_geometry_dimension_" + newRowNum + "_" + dimNum;
						
						//Vals
						if(dim.variable != null) findSubNode(newDimDiv, "dimensionVariable").value = dim.variable;
						if(dim.value != null) findSubNode(newDimDiv, "dimensionValue").value = dim.value;
						if(dim.unit != null) findSubNode(newDimDiv, "dimensionUnit").value = dim.unit;
						if(dim.prefix != null) findSubNode(newDimDiv, "dimensionPrefix").value = dim.prefix;
						if(dim.note != null) findSubNode(newDimDiv, "dimensionNote").value = dim.note;
					
						dimsDiv.appendChild(newDimDiv);
					}
				}
			
				existingDiv.appendChild(newDiv);

				//Also add button to sideBar
				let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

				//set id, class, onclick
				newButton.id = "experiment_geometry_button_" + newRowNum;
				newButton.classList.add("experiment_geometry_button_group");
			
				let geometryNum = geom.order;
				let geometryType = geom.type;
			
				newButton.children[0].innerHTML = geometryType + " #" + geometryNum;

				buttonsDiv.append(newButton);

			}
		}
	
		if(experimentData.protocol != null){
		
			let existingDiv = document.getElementById("experiment_protocols");
			existingDiv.innerHTML = "";
		
			let buttonsDiv = document.getElementById("experiment_protocol_buttons");
			buttonsDiv.innerHTML = "";
		
			let protocols = experimentData.protocol;

			for(let newRowNum = 0; newRowNum < protocols.length; newRowNum ++ ){
				let protocol = protocols[newRowNum];
			
				let sourceDiv = document.getElementById("sourceExperimentProtocol");
				let newDiv = sourceDiv.cloneNode(true);
				newDiv.id = "experiment_protocol_" + newRowNum;
				newDiv.classList.add("experiment_protocol_group");

				//manually add values to select
				let experimentTestSelect = findSubNode(newDiv, "experimentProtocolTest");
				experimentTestSelect.innerHTML = options_str;
			
				//Add Vals
				if(protocol.test != null) findSubNode(newDiv, "experimentProtocolTest").value = protocol.test;
				if(protocol.objective != null) findSubNode(newDiv, "experimentProtocolObjective").value = protocol.objective;
				if(protocol.description != null) findSubNode(newDiv, "experimentProtocolDescription").value = protocol.description;
			
				//Now Parameters
				if(protocol.parameters != null){
					let paramsDiv = findSubNode(newDiv, "parameterRows");
					for(let paramNum = 0; paramNum < protocol.parameters.length; paramNum ++){
						let param = protocol.parameters[paramNum];
						let sourceParamDiv = document.getElementById("sourceExperimentProtocolParameter");
						let newParamDiv = sourceParamDiv.cloneNode(true);
						
						newParamDiv.id = "experiment_protocol_parameter_" + newRowNum + "_" + paramNum;
					
						//Vals
						if(param.control != null) findSubNode(newParamDiv, "parameterVariable").value = param.control;
						if(param.value != null) findSubNode(newParamDiv, "parameterValue").value = param.value;
						if(param.unit != null) findSubNode(newParamDiv, "parameterUnit").value = param.unit;
						if(param.note != null) findSubNode(newParamDiv, "parameterNote").value = param.note;

						paramsDiv.appendChild(newParamDiv);
					}
				}
			
				existingDiv.appendChild(newDiv);

				//Also add button to sideBar
				let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

				//set id, class, onclick
				newButton.id = "experiment_protocol_button_" + newRowNum;
				newButton.classList.add("experiment_protocol_button_group");
				newButton.children[0].innerHTML = "Step";

				buttonsDiv.append(newButton);
			
			}
		}
		
		exper_experimentRenameAllProtocolButtons();

		//Add Documents
		if(experimentData.documents != null){
			let documentHolder = document.getElementById("experiment_documents");
			documentHolder.innerHTML = "";
		
			let documentButtonHolder = document.getElementById("experiment_document_buttons");
			documentButtonHolder.innerHTML = "";
		
			for(let documentNum = 0; documentNum < experimentData.documents.length; documentNum ++){
				let doc = experimentData.documents[documentNum];
		
				let sourceDiv = document.getElementById("sourceDocumentRow");
				let newDocDiv = sourceDiv.cloneNode(true);
				newDocDiv.id = "sample_document_" + documentNum;

				findSubNode(newDocDiv, "docType").value = doc.type;

				if(doc.type == "Other") findSubNode(newDocDiv, "otherDocTypeHolder").style.display = "inline";
				
				if(doc.other_type != null && doc.other_type != ""){
					findSubNode(newDocDiv, "otherDocType").value = doc.other_type;
				}
				
				findSubNode(newDocDiv, "docFormat").value = doc.format;
				
				if(doc.format == "Other") findSubNode(newDocDiv, "otherDocFormatHolder").style.display = "inline";
				
				if(doc.other_format != null && doc.other_format != ""){
					findSubNode(newDocDiv, "otherDocFormat").value = doc.other_format;
				}
			
				//fix file selector
				let fileHolder = findSubNode(newDocDiv, "fileHolder");
				fileHolder.children[1].remove();
				//fileHolder.innerHTML += '<div class="existingFile"><span id="filename">'+doc.path+'</span> <span></span><a onclick="exper_deleteDocumentFile(\''+doc.uuid+'\')" href="javascript:void(0);">(Delete File)</a></div>';
				
				fileHolder.innerHTML = '<label class="formLabel">File Uploaded</label><div><a href="'+doc.path+'" target="_blank">'+doc.path+'</a></div>';
				fileHolder.innerHTML += '<div><a href="javascript:void(0);" onClick="exper_deleteDocumentFile(\'' + doc.uuid + '\')">(Delete File)</a></div>';
			
				findSubNode(newDocDiv, "docId").value = doc.id;
				findSubNode(newDocDiv, "uuid").value = doc.uuid;
				findSubNode(newDocDiv, "originalFilename").value = doc.path;
				findSubNode(newDocDiv, "docDescription").value = doc.description;
			
				documentHolder.append(newDocDiv);
		
				//Channel Button
				let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);
				newButton.classList.add("experiment_document_button_group");
				let documentType = doc.type;
				//let channelNumVal = channel.number;
				newButton.children[0].innerHTML = documentType;
				documentButtonHolder.append(newButton);
			}
		}
		
		exper_fixButtonsAndDivs();
		fixAllButtons();
		
	}
}

function populateDataDataToInterface(dataData){

	if(dataData != null){
		//console.log(dataData);
		if(dataData.datasets != null && dataData.datasets.length > 0){
			let datasetHolder = document.getElementById("data_datasets");
			datasetHolder.innerHTML = "";
			
			let datasetButtonHolder = document.getElementById("data_dataset_buttons");
			datasetButtonHolder.innerHTML = "";
			
			for(let datasetNum = 0; datasetNum < dataData.datasets.length; datasetNum ++){
				let dataset = dataData.datasets[datasetNum];
				let sourceDatasetDiv = document.getElementById("sourceDatasetRow");
				let newDatasetDiv = sourceDatasetDiv.cloneNode(true);
				newDatasetDiv.id = "data_dataset_" + datasetNum;
			
				let dataSubType = dataset.data;
				findSubNode(newDatasetDiv, "dataData").value = dataset.data;
				
				findSubNode(newDatasetDiv, "dataType").value = dataset.type;
				
				if(dataset.type == "Other") findSubNode(newDatasetDiv, "otherDataTypeHolder").style.display = "inline";
				
				if(dataset.other_type != null && dataset.other_type != ""){
					findSubNode(newDatasetDiv, "otherDataType").value = dataset.other_type;
				}
				
				if(dataset.format == "Other") findSubNode(newDatasetDiv, "otherDataFormatHolder").style.display = "inline";
				
				if(dataset.other_format != null && dataset.other_format != ""){
					findSubNode(newDatasetDiv, "otherDataFormat").value = dataset.other_format;
				}
			
				//file
				if(dataset.path != ""){
					let fileHolder = findSubNode(newDatasetDiv, "fileHolder");
					fileHolder.children[1].remove();
					//fileHolder.innerHTML += '<div class="existingFile"><span id="filename">'+dataset.path+'</span> <span></span><a onclick="exper_deleteDocumentFile(\''+dataset.uuid+'\')" href="javascript:void(0);">(Delete File)</a></div>';
					
					//fileHolder.innerHTML = '<label class="formLabel">File Uploaded</label><div><a href="'+dataset.path+'" target="_blank">'+dataset.path+'</a></div><div><a id="deleteLink" href="javascript:void(0);">(Delete File)</a></div>';
					//fileHolder.innerHTML += '<div><a id="deleteLink" href="javascript:void(0);" onClick="exper_deleteDocumentFile(\''+dataset.uuid+'\')">(Delete File)</a></div>';
					
					fileHolder.innerHTML = '<label class="formLabel">File Uploaded</label><div><a href="'+dataset.path+'" target="_blank">'+dataset.path+'</a></div>';
					fileHolder.innerHTML += '<div><a href="javascript:void(0);" onClick="exper_deleteDocumentFile(\'' + dataset.uuid + '\')">(Delete File)</a></div>';
					
				}
			
				findSubNode(newDatasetDiv, "uuid").value = dataset.uuid;
				findSubNode(newDatasetDiv, "originalFilename").value = dataset.path;
				findSubNode(newDatasetDiv, "dataId").value = dataset.id;
				findSubNode(newDatasetDiv, "dataFormat").value = dataset.format;
				findSubNode(newDatasetDiv, "dataQuality").value = dataset.rating;
				findSubNode(newDatasetDiv, "dataDescription").value = dataset.description;
				findSubNode(newDatasetDiv, "dataData").value = dataset.data;
				findSubNode(newDatasetDiv, "dataData").value = dataset.data;
			
				let extraData = findSubNode(newDatasetDiv, "extraData");
			
				//Parameters
				if(dataSubType == "Parameters"){
					let sourceParametersHolder = document.getElementById("sourceDataParametersBox");
					let newParametersHolder = sourceParametersHolder.cloneNode(true);
					newParametersHolder.id = "data_parameters_"+datasetNum;
					newParametersHolder.style.display = "block";
				
					if(dataset.parameters != null && dataset.parameters.length > 0){
						findSubNode(newParametersHolder, "parameterRowsHolder").style.display = "block";
						let parameterRows = findSubNode(newParametersHolder, "parameterRows");
					
						for(let parameterNum = 0; parameterNum < dataset.parameters.length; parameterNum++){
							let p = dataset.parameters[parameterNum];
							let sourceParameterRow = document.getElementById("sourceDataParameterRow");
							let newParameterRow = sourceParameterRow.cloneNode(true);
							newParameterRow.id = "data_parameter_" + datasetNum + "_" + parameterNum;
							newParameterRow.style.display = "block";
						
							findSubNode(newParameterRow, "parameterControl").value = p.control;
							findSubNode(newParameterRow, "parameterValue").value = p.value;
							findSubNode(newParameterRow, "parameterError").value = p.error;
							findSubNode(newParameterRow, "parameterUnit").value = p.unit;
							findSubNode(newParameterRow, "parameterPrefix").value = p.prefix;
							findSubNode(newParameterRow, "parameterNote").value = p.note;

							parameterRows.append(newParameterRow);
						}
					
					
					}
				
					extraData.append(newParametersHolder);
				}
			
				//Headers
				if(dataSubType == "Time Series"){
					let sourceHeadersHolder = document.getElementById("sourceDataHeadersBox");
					let newHeadersHolder = sourceHeadersHolder.cloneNode(true);
					newHeadersHolder.id = "data_headers_"+datasetNum;
					newHeadersHolder.style.display = "block";
				
					if(dataset.headers != null && dataset.headers.length > 0){
						let headers = findSubNode(newHeadersHolder, "headers");
						let headerButtons = findSubNode(newHeadersHolder, "header_buttons");

						for(let headerNum = 0; headerNum < dataset.headers.length; headerNum ++){
							let h = dataset.headers[headerNum];
						
							//console.log(h);
						
							let sourceHeaderRow = document.getElementById("sourceDataHeaderRow");
							let newHeaderRow = sourceHeaderRow.cloneNode(true);
							newHeaderRow.id = "data_header_" + datasetNum + "_" + headerNum;
							newHeaderRow.style.display = "block";
						
							findSubNode(newHeaderRow, "headerHeader").value = h.header.header;
						
							//Header Button
							let newHeaderButton = document.getElementById("sourceSideBarButton").cloneNode(true);
							newHeaderButton.classList.add("data_header_button_group_" + datasetNum);
							newHeaderButton.children[0].innerHTML = h.header.header;
							headerButtons.append(newHeaderButton);
						
							//exper_updateDataHeaderInputs(datasetNum, headerNum);
							exper_updateDataHeaderInputs_with_div(newHeaderRow, newHeaderButton);

							findSubNode(newHeaderRow, "headerSpecA").value = h.header.spec_a;
							findSubNode(newHeaderRow, "headerSpecB").value = h.header.spec_b;
							findSubNode(newHeaderRow, "headerSpecC").value = h.header.spec_c;
							findSubNode(newHeaderRow, "headerUnit").value = h.header.unit;
							findSubNode(newHeaderRow, "headerType").value = h.type;
							findSubNode(newHeaderRow, "headerChannelNum").value = h.number;
							findSubNode(newHeaderRow, "headerDataQuality").value = h.rating;
							findSubNode(newHeaderRow, "headerNote").value = h.note;

							headers.append(newHeaderRow);

						}
					
					}
				
					extraData.append(newHeadersHolder);
				}
			
				//Phases
				if(dataSubType == "Pore Fluid"){
					let sourcePhasesHolder = document.getElementById("sourceDataPhasesBox");
					let newPhasesHolder = sourcePhasesHolder.cloneNode(true);
					newPhasesHolder.id = "data_phases_"+datasetNum;
					newPhasesHolder.style.display = "block";

					if(dataset.fluid != null){
						if(dataset.fluid.phases != null && dataset.fluid.phases.length > 0){
							let phases = findSubNode(newPhasesHolder, "phases");
							let phaseButtons = findSubNode(newPhasesHolder, "phase_buttons");
					
					

					
							for(let phaseNum = 0; phaseNum < dataset.fluid.phases.length; phaseNum ++){
								let ph = dataset.fluid.phases[phaseNum];
						
								console.log(ph);
						
								let sourcePhaseRow = document.getElementById("soureDataPhaseRow");
								let newPhaseRow = sourcePhaseRow.cloneNode(true);
								newPhaseRow.id = "data_phase_" + datasetNum + "_" + phaseNum;
								newPhaseRow.style.display = "block";
						
								findSubNode(newPhaseRow, "phaseComposition").value = ph.component;
								findSubNode(newPhaseRow, "phaseFraction").value = ph.fraction;
								findSubNode(newPhaseRow, "phaseActivity").value = ph.activity;
								findSubNode(newPhaseRow, "phaseFugacity").value = ph.fugacity;
								findSubNode(newPhaseRow, "phaseUnit").value = ph.unit;
								findSubNode(newPhaseRow, "phaseChemistryData").value = ph.composition;
							
								//Solutes
								if(ph.composition != null && ph.composition == "Chemistry"){
								
									//Add Solutes Holder
									let sourceSolutesBox = document.getElementById("sourceDataSolutesBox");
									let newSolutesBox = sourceSolutesBox.cloneNode(true);
									newSolutesBox.id = "";
									newSolutesBox.style.display = "inline";
									let solutesHolder = findSubNode(newPhaseRow, "solutesHolder");
									let soluteRowsHolder = findSubNode(newSolutesBox, "soluteRowsHolder");
									soluteRowsHolder.style.display = "block";
								
									if(ph.solutes != null && ph.solutes.length > 0){
									
										let soluteRows = findSubNode(newSolutesBox, "soluteRows");
									
										for(sNum = 0; sNum < ph.solutes.length; sNum++){
											let s = ph.solutes[sNum];
											let sourceSoluteRow = document.getElementById("sourceDataSoluteRow");
											let newSoluteRow = sourceSoluteRow.cloneNode(true);
											newSoluteRow.id = "data_solute_" + datasetNum + "_" + phaseNum+ "_" + sNum;
											newSoluteRow.style.display = "block";
										
											findSubNode(newSoluteRow, "soluteComponent").value = s.component;
											findSubNode(newSoluteRow, "soluteValue").value = s.value;
											findSubNode(newSoluteRow, "soluteError").value = s.error;
											findSubNode(newSoluteRow, "soluteUnit").value = s.unit;

										
											soluteRows.append(newSoluteRow);
										}
									}
								
									solutesHolder.append(newSolutesBox);
								}

						
								//Phase Button
								let newPhaseButton = document.getElementById("sourceSideBarButton").cloneNode(true);
								newPhaseButton.classList.add("data_phase_button_group_" + datasetNum);
								if(ph.component != null && ph.component != ""){
									newPhaseButton.children[0].innerHTML = ph.component;
								}else{
									newPhaseButton.children[0].innerHTML = "Phase";
								}
							
								phaseButtons.append(newPhaseButton);

								phases.append(newPhaseRow);

							}
					
						}
					}

					extraData.append(newPhasesHolder);
				}

			
				datasetHolder.append(newDatasetDiv);
			
				//Dataset Button
				let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);
				newButton.classList.add("data_dataset_button_group");
			
				newButton.children[0].innerHTML = dataset.data;
				datasetButtonHolder.append(newButton);
			}
		}
		
		exper_fixButtonsAndDivs();
		fixAllButtons();
		
	}
}



































































function fixAllButtons() { //This function goes back and fixes all side buttons once they've been added to the interface

	//DAQ
	if(daqData != null){
		let devicesDiv = document.getElementById("daq_devices");
		if(devicesDiv != null){
			if(daqData.devices != null){
				for(let deviceNum = 0; deviceNum < daqData.devices.length; deviceNum ++){
					let device = daqData.devices[deviceNum];

					//DAQ Device Channels
					if(device.channels != null){
						exper_daqSwitchToChannel(deviceNum, 0);
					}
		
					//DAQ Device Documents
					if(device.documents != null){
						exper_daqSwitchToDocument(deviceNum, 0);
					}
				}
			}
		}
	}
	
	let phasesDiv = document.getElementById("sample_mineral_phases");
	if(phasesDiv != null){
		phasesDiv = document.getElementById("sample_mineral_phases").children;
		for(let phaseNum = 0; phaseNum < phasesDiv.length; phaseNum ++){
			exper_sampleSwitchToMineral(0);
		}
	}
	
	let sampleParameters = document.getElementById("sample_parameters");
	if(sampleParameters != null){
		sampleParameters = document.getElementById("sample_parameters").children;
		for(let buttonNum = 0; buttonNum < sampleParameters.length; buttonNum ++){
			exper_sampleSwitchToParameter(0);
		}
	}
	
	let sampleDocuments = document.getElementById("sample_documents");
	if(sampleDocuments != null){
		sampleDocuments = document.getElementById("sample_documents").children;
		for(let buttonNum = 0; buttonNum < sampleDocuments.length; buttonNum ++){
			exper_sampleSwitchToDocument(0);
		}
	}
	
	let experimentGeometries = document.getElementById("experiment_geometries");
	if(experimentGeometries != null){
		experimentGeometries = document.getElementById("experiment_geometries").children;
		for(let buttonNum = 0; buttonNum < experimentGeometries.length; buttonNum ++){
			exper_experimentSwitchToGeometry(0);
		}
	}
	
	let experimentProtocols = document.getElementById("experiment_protocols");
	if(experimentProtocols != null){
		experimentProtocols = document.getElementById("experiment_protocols").children;
		for(let buttonNum = 0; buttonNum < experimentProtocols.length; buttonNum ++){
			exper_experimentSwitchToProtocol(0);
		}
	}
	
	let experimentDocuments = document.getElementById("experiment_documents");
	if(experimentDocuments != null){
		experimentDocuments = document.getElementById("experiment_documents").children;
		for(let buttonNum = 0; buttonNum < experimentDocuments.length; buttonNum ++){
			exper_experimentSwitchToDocument(0);
		}
	}
	
	let dataDatasets = document.getElementById("data_datasets");
	if(dataDatasets != null){
		dataDatasets = dataDatasets.children;
		for(let dNum = 0; dNum < dataDatasets.length; dNum ++){
			let dataset = dataDatasets[dNum];
			
			let headers = findSubNode(dataset, "headers");
			if(headers != null){
				headers = headers.children;
				if(headers.length > 0){
					exper_dataSwitchToHeader(dNum, 0);
				}
			}
			
			let phases = findSubNode(dataset, "phases");
			if(phases != null){
				phases = phases.children;
				if(phases.length > 0){
					exper_dataSwitchToPhase(dNum, 0);
				}
			}
			
			exper_dataSwitchToDataset(0);
		}
	}
	
	exper_fixDownloadButton();
	
}

function oldfixAllButtons() { //This function goes back and fixes all side buttons once they've been added to the interface

	//DAQ
	if(daqData != null){
		if(daqData.devices != null){
			let devicesDiv = document.getElementById("daq_devices");
			for(let deviceNum = 0; deviceNum < daqData.devices.length; deviceNum ++){
				let device = daqData.devices[deviceNum];

				//DAQ Device Channels
				if(device.channels != null){
					exper_daqSwitchToChannel(0, 0);
				}
				
				//DAQ Device Documents
				if(device.documents != null){
					exper_daqSwitchToDocument(0, 0);
				}
			}
		}
	}
	
}

function exper_deleteDocumentFile(uuid){
	
	//DAQ Device Documents
	let deviceRows = document.getElementById("daq_devices");
	if(deviceRows != null){
		deviceRows = deviceRows.children;
		for(let d = 0; d < deviceRows.length; d ++){
			let deviceNum = d + 1;

			let documentRows = document.getElementById("daq_device_documents_" + d);
			if(documentRows != null){
				documentRows = documentRows.children;
				for(docNum = 0; docNum < documentRows.length; docNum++){
					let documentNum = docNum + 1;
					let docRow = documentRows[docNum];
					
					let thisUuid = findSubNode(docRow, "uuid").value;
					
					if(thisUuid == uuid){
						let fileHolder = findSubNode(docRow, "fileHolder");
						fileHolder.children[1].remove();
						fileHolder.innerHTML += '<input id="docFile" type="file" class="formControl"/>';
					}
				}
			}
		}
	}
	
	let sampleDocumentRows = document.getElementById("sample_documents").children;
	for(docNum = 0; docNum < sampleDocumentRows.length; docNum++){
		let docRow = sampleDocumentRows[docNum];
		let thisUuid = findSubNode(docRow, "uuid").value;
		if(thisUuid == uuid){
			let fileHolder = findSubNode(docRow, "fileHolder");
			fileHolder.children[1].remove();
			fileHolder.innerHTML += '<input id="docFile" type="file" class="formControl"/>';
		}
	}
	
	let experimentDocumentRows = document.getElementById("experiment_documents").children;
	for(docNum = 0; docNum < experimentDocumentRows.length; docNum++){
		let docRow = experimentDocumentRows[docNum];
		let thisUuid = findSubNode(docRow, "uuid").value;
		if(thisUuid == uuid){
			let fileHolder = findSubNode(docRow, "fileHolder");
			fileHolder.children[1].remove();
			fileHolder.innerHTML += '<input id="docFile" type="file" class="formControl"/>';
		}
	}
	
	let dataDatasets = document.getElementById("data_datasets");
	if(dataDatasets != null){
		dataDatasets = dataDatasets.children;
		if(dataDatasets.length > 0){
			for(let dNum = 0; dNum < dataDatasets.length; dNum ++){
				let datasetRow = dataDatasets[dNum];
				let thisUuid = findSubNode(datasetRow, "uuid").value;
				if(thisUuid == uuid){
					let fileHolder = findSubNode(datasetRow, "fileHolder");
					fileHolder.children[1].remove();
					fileHolder.innerHTML += '<input id="dataFile" type="file" class="formControl"/>';
				}
			}
		}
	}
	
	exper_fixButtonsAndDivs();
	
}

//Load data from JSON File
function exper_loadDataFromJSON(dataType){

	var input = document.createElement('input');
	input.type = 'file';
	input.accept = ".json,.txt";

	input.onchange = e => { 

		// getting a hold of the file reference
		var file = e.target.files[0]; 

		// setting up the reader
		var reader = new FileReader();
		//reader.readAsDataURL(file); // this is reading as data url
		reader.readAsText(file); // this is reading as data url

		// here we tell the reader what to do when it's done reading...
		reader.onload = readerEvent => {
			var content = readerEvent.target.result; // this is the content!
			if(isJSON(content)){
				let data = JSON.parse(content);
				if(data.facility == null && data.apparatus == null && data.daq == null && data.sample == null && data.experiment == null && data.data == null ){
					alert("Invalid JSON File.");
				}else{
					console.log("continue to load");
					if(dataType == "all") exper_clearAllData();
					exper_loadExperiment(data, dataType);				
				}
			}else{
				alert("Invalid JSON File.");
			}
		}
	}

	input.click();
	
}

function exper_CheckForExperimentSubmitErrors(){
	var error = "";
	if($( "#mainExperimentId" ).val()=="") error += "Experiment Id Cannot be Blank.\n";
	if(facilityData == null) error += "Apparatus Info Cannot be Blank.\n";
	
	return error;
}

function buildJSONString(){
	let out = {};
	out.experiment_id = experimentId;
	out.facility = facilityData;
	out.apparatus = apparatusData;
	out.daq = daqData;
	out.sample = sampleData;
	out.experiment = experimentData;
	out.data = dataData;
	
	//fix out here
	out = removeEmpty(out);
	out = removeEmpty(out);
	
	let jsonString = JSON.stringify(out, null, "\t");
	return jsonString;
}

function doSubmitNewExperiment() {
	var error = exper_CheckForExperimentSubmitErrors();
	if(error != ""){
		alert(error);
	}else{

		document.getElementById("progressBox").style.display="inline";

		var fd = new FormData();
		
		//Gather Experiment Id
		let experimentId = document.getElementById("mainExperimentId").value;
		fd.append('experiment_id', experimentId);

		//Gather Facility Pkey
		var projectPkey = document.getElementById("projectPkey").value;
		fd.append('project_pkey', projectPkey);

		//Build JSON
		/*
		let out = {};
		out.experiment_id = experimentId;
		out.facility = facilityData;
		out.apparatus = apparatusData;
		out.daq = daqData;
		out.sample = sampleData;
		out.experiment = experimentData;
		out.data = dataData;
		let jsonString = JSON.stringify(out, null, "\t");
		*/
		
		let jsonString = buildJSONString();
		
		fd.append('json',jsonString);
		console.log(jsonString);
		
		//Gather Files
		/*
		let daqDevices = document.getElementById("daq_devices").children;
		for(let x = 0; x < daqDevices.length; x++){
			let docRows = document.getElementById("daq_device_documents_" + x).children;
			for(let y = 0; y < docRows.length; y++){
				//find file
				let fileNode = findSubNode(docRows[y], "docFile");
				if(fileNode != null){
					if(fileNode.type == "file"){
						let uuid = findSubNode(docRows[y], "uuid").value;
						fd.append(uuid,fileNode.files[0]);
					}
				}
			} 
		}
		
		let sampleDocuments = document.getElementById("sample_documents").children;
		for(let x = 0; x < sampleDocuments.length; x++){
			//find file
			let fileNode = findSubNode(sampleDocuments[x], "docFile");
			if(fileNode != null){
				if(fileNode.type == "file"){
					let uuid = findSubNode(sampleDocuments[x], "uuid").value;
					fd.append(uuid,fileNode.files[0]);
				}
			}
		}
		
		let experimentDocuments = document.getElementById("experiment_documents").children;
		for(let x = 0; x < experimentDocuments.length; x++){
			//find file
			let fileNode = findSubNode(experimentDocuments[x], "docFile");
			if(fileNode != null){
				if(fileNode.type == "file"){
					let uuid = findSubNode(experimentDocuments[x], "uuid").value;
					fd.append(uuid,fileNode.files[0]);
				}
			}
		}

		let dataDatasets = document.getElementById("data_datasets");
		if(dataDatasets != null){
			dataDatasets = dataDatasets.children;
			if(dataDatasets.length > 0){
				for(let dNum = 0; dNum < dataDatasets.length; dNum ++){
					let datasetRow = dataDatasets[dNum];
					let fileNode = findSubNode(datasetRow, "dataFile");
					if(fileNode != null){
						if(fileNode.type == "file"){
							let uuid = findSubNode(datasetRow, "uuid").value;
							console.log("Found dataset file!!!!!!!!!!");
							fd.append(uuid,fileNode.files[0]);
						}
					}
				}
			}
		}
		*/

		$.ajax({
			url : "inNewExperiment.php",
			type: "POST",
			data : fd,
			xhr: function () {
					var xhr = $.ajaxSettings.xhr();
					xhr.onprogress = function e() {
						// For downloads
						if (e.lengthComputable) {
							var percent = Math.round(e.loaded / e.total * 100);
							document.getElementById("progressDigit").innerHTML = percent + "%";
							document.getElementById("progressBar").style.width = percent + "%";
						}
					};
					xhr.upload.onprogress = function (e) {
						// For uploads
						if (e.lengthComputable) {
							var percent = Math.round(e.loaded / e.total * 100);
							document.getElementById("progressDigit").innerHTML = percent + "%";
							document.getElementById("progressBar").style.width = percent + "%";
						}
					};
					return xhr;
				},
			processData: false,
			contentType: false,
			success:function(data){
				if(data.Error){
					document.getElementById("progressBox").style.display="none";
					alert("Error!\n" + data.Error);
				}else{
					setTimeout(function () {
						document.getElementById("progressBox").style.display="none";
						
						$( "#bigWindow" ).html('\
						<div class="topTitle green center" style="padding-top:50px;">Success!</div>\
						<div class="mainFSLegend" style="text-align: center; padding-top:30px;">Experiment<br>'+experimentId+'<br>has been added to the database.</div>\
						<div style="text-align:center;padding-top:25px;"><button class="submitButton" style="vertical-align:middle" onclick="window.location.href = \'/my_experimental_data\';"><span>Continue </span></button></div>\
						');
						
					}, 1000)
				}
			},
			error: function(){
				//if fails     
			}
		});
	}
}

function doSubmitEditExperiment() {
	var error = exper_CheckForExperimentSubmitErrors();
	if(error != ""){
		alert(error);
	}else{

		document.getElementById("progressBox").style.display="inline";

		var fd = new FormData();
		
		
		//Gather Experiment Id
		let experimentId = document.getElementById("mainExperimentId").value;
		fd.append('experiment_id', experimentId);
		
		//Gather Facility Pkey
		var experimentPkey = document.getElementById("experimentPkey").value;
		fd.append('experiment_pkey', experimentPkey);

		//Build JSON
		let out = {};
		out.uuid = document.getElementById("experimentUUID").value;
		out.experiment_id = experimentId;
		out.facility = facilityData;
		out.apparatus = apparatusData;
		out.daq = daqData;
		out.sample = sampleData;
		out.experiment = experimentData;
		out.data = dataData;
		let jsonString = JSON.stringify(out, null, "\t");
		fd.append('json',jsonString);
		//console.log(jsonString);
		
		//Gather Files
		/*
		let daqDevices = document.getElementById("daq_devices").children;
		for(let x = 0; x < daqDevices.length; x++){
			let docRows = document.getElementById("daq_device_documents_" + x).children;
			for(let y = 0; y < docRows.length; y++){
				//find file
				let fileNode = findSubNode(docRows[y], "docFile");
				if(fileNode != null){
					if(fileNode.type == "file"){
						let uuid = findSubNode(docRows[y], "uuid").value;
						fd.append(uuid,fileNode.files[0]);
					}
				}
			} 
		}
		
		let sampleDocuments = document.getElementById("sample_documents").children;
		for(let x = 0; x < sampleDocuments.length; x++){
			//find file
			let fileNode = findSubNode(sampleDocuments[x], "docFile");
			if(fileNode != null){
				if(fileNode.type == "file"){
					let uuid = findSubNode(sampleDocuments[x], "uuid").value;
					fd.append(uuid,fileNode.files[0]);
				}
			}
		}
		
		let experimentDocuments = document.getElementById("experiment_documents").children;
		for(let x = 0; x < experimentDocuments.length; x++){
			//find file
			let fileNode = findSubNode(experimentDocuments[x], "docFile");
			if(fileNode != null){
				if(fileNode.type == "file"){
					let uuid = findSubNode(experimentDocuments[x], "uuid").value;
					fd.append(uuid,fileNode.files[0]);
				}
			}
		}
		
		let dataDatasets = document.getElementById("data_datasets");
		if(dataDatasets != null){
			dataDatasets = dataDatasets.children;
			if(dataDatasets.length > 0){
				for(let dNum = 0; dNum < dataDatasets.length; dNum ++){
					let datasetRow = dataDatasets[dNum];
					let fileNode = findSubNode(datasetRow, "dataFile");
					if(fileNode != null){
						if(fileNode.type == "file"){
							let uuid = findSubNode(datasetRow, "uuid").value;
							fd.append(uuid,fileNode.files[0]);
						}
					}
				}
			}
		}
		*/

		console.log("uploading project...");
		
		$.ajax({
			url : "inEditExperiment.php",
			type: "POST",
			data : fd,
			xhr: function () {
					var xhr = $.ajaxSettings.xhr();
					xhr.onprogress = function e() {
						// For downloads
						if (e.lengthComputable) {
							var percent = Math.round(e.loaded / e.total * 100);
							document.getElementById("progressDigit").innerHTML = percent + "%";
							document.getElementById("progressBar").style.width = percent + "%";
						}
					};
					xhr.upload.onprogress = function (e) {
						// For uploads
						if (e.lengthComputable) {
							var percent = Math.round(e.loaded / e.total * 100);
							document.getElementById("progressDigit").innerHTML = percent + "%";
							document.getElementById("progressBar").style.width = percent + "%";
						}
					};
					return xhr;
				},
			processData: false,
			contentType: false,
			success:function(data){
				
				console.log("success here...");
				
				if(data.Error){
					document.getElementById("progressBox").style.display="none";
					alert("Error!\n" + data.Error);
				}else{
					setTimeout(function () {
						document.getElementById("progressBox").style.display="none";
						
						$( "#bigWindow" ).html('\
						<div class="topTitle green center" style="padding-top:50px;">Success!</div>\
						<div class="mainFSLegend" style="text-align: center; padding-top:30px;">Experiment<br>'+experimentId+'<br>has been edited successfully.</div>\
						<div style="text-align:center;padding-top:25px;"><button class="submitButton" style="vertical-align:middle" onclick="window.location.href = \'/my_experimental_data\';"><span>Continue </span></button></div>\
						');
						
					}, 1000)
				}
			},
			error: function(){
				//if fails     
			}
		});
		
	}
}

//**************** Apparatus ****************

function exper_CloseApparatusModal(){
	let apparatusModal = document.getElementById("apparatusModal");
	apparatusModal.style.display = "none";
}

function exper_openApparatusModal(){
	let apparatusModal = document.getElementById("apparatusModal");
	apparatusModal.style.display = "inline";
	
	$.ajax({
		url : "apparatusList.json",
		type: "GET",
		processData: false,
		contentType: false,
		success:function(data){
			if(data.Error){
				alert("Error!\n" + data.Error);
			}else{
				exper_fillApparatusList(data);
			}
		},
		error: function(){
			//if fails     
		}
	});
}

function exper_fillApparatusList(data) {
	//console.log(data);
	
	var html = '';
	
	//for(var j=0; j < 20; j++){
	var facilities = data.facilities;
	for(var fnum = 0; fnum < facilities.length; fnum++){
		var f = facilities[fnum];
		html += '<div class="facilityHeader">'+f.institute+'</div>';
		html += '<div>';
		html += '<span class="facilityHeader">'+f.name+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
		html += '<span><strong><a href="view_facility?fpk='+f.id+'" target="_blank">View</a></strong></span>';
		html += '</div>';

		html += '<div class="strabotable" style="margin-left:0px;margin-bottom:10px;">';
		html += '<table>';
		html += '<tr>';
		html += '<td>&nbsp;</td>';
		html += '<td>&nbsp;</td>';
		html += '<td>Apparatus&nbsp;Name</td>';
		html += '<td>Apparatus&nbsp;Type</td>';
		html += '<td>Last&nbsp;Modified</td>';
		html += '</tr>';
		
		for(var anum = 0; anum < f.apparatuses.length; anum++){
			var a = f.apparatuses[anum];
			html += '<tr>';
			html += '<td style="vertical-align:top;width:80px;text-align:center;" nowrap><a href="javascript:exper_selectFacilityApparatus('+f.id+', '+a.id+')">Select</a></td>';
			html += '<td style="vertical-align:top;width:80px;text-align:center;" nowrap><a href="view_apparatus?apk='+a.id+'" target="_blank">View</a></td>';
			html += '<td style="vertical-align:top;" nowrap>'+a.name+'</td>';
			html += '<td style="vertical-align:top;" nowrap>'+a.type+'</td>';
			html += '<td style="vertical-align:top;" nowrap>'+a.modified_timestamp+'</td>';
			html += '</tr>';
			html += '';
			html += '';
			html += '';
			html += '';
			html += '';
			html += '';
			html += '';
			html += '';
		}
		
		html += '</table>';
		html += '</div>';
	}
	//}//j
	
	document.getElementById('apparatusList').innerHTML = html;
}

function exper_selectFacilityApparatus(facilityId, apparatusId){
	//console.log("facilityId: " + facilityId);
	//console.log("apparatusId: " + apparatusId);
	
	//Get selected data
	$.ajax({
		url : "get_apprepo_facility_alone?id="+facilityId,
		type: "GET",
		processData: false,
		contentType: false,
		success:function(data){
			if(data.Error){
				alert("Error!\n" + data.Error);
			}else{
				facilityData = data;
				$.ajax({
					url : "get_apprepo_apparatus?id="+apparatusId,
					type: "GET",
					processData: false,
					contentType: false,
					success:function(data){
						if(data.Error){
							alert("Error!\n" + data.Error);
						}else{
							apparatusData = data;
							exper_fixDownloadButton();
							//console.log(apparatusData);
							exper_populateApparatusData();
							exper_CloseApparatusModal();
						}
					},
					error: function(){
						//if fails     
					}
				});
			}
		},
		error: function(){
			//if fails     
		}
	});
}

function exper_CloseFacilityApparatusModal() {
	let facilityApparatusModal = document.getElementById("facilityApparatusModal");
	facilityApparatusModal.style.display = "none";
}

function exper_openFacilityApparatusModal() {
	let facilityApparatusModal = document.getElementById("facilityApparatusModal");
	facilityApparatusModal.style.display = "inline";
}

function exper_doEditFacilityApparatus() {
	//copy current facilityApparatus data to temp
	console.log("edit facilityApparatus");
	tempFacilityData = facilityData;
	tempApparatusData = apparatusData;
	
	exper_clearFacilityApparatusInterface();
	populateFacilityDataToInterface(facilityData);
	populateApparatusDataToInterface(apparatusData);
	
	exper_openFacilityApparatusModal();
}

function exper_doSaveFacilityApparatusInfo() {
	var error = exper_checkForFacilityApparatusSubmitErrors();
	
	if(error != ""){
		alert(error);
	}else{
		exper_buildFacilityApparatusdata();
		exper_populateApparatusData();
		document.getElementById("facilityApparatusModalBox").scrollTop = 0;
		exper_CloseFacilityApparatusModal();
		exper_fixDownloadButton();
	}
}

function exper_doCancelFacilityApparatusEdit() {
	facilityData = tempFacilityData;
	apparatusData = tempApparatusData;
	//populateFacilityApparatusDataToInterface(daqData); This is to fill in the modal -- need to add
	exper_populateApparatusData();
	document.getElementById("facilityApparatusModalBox").scrollTop = 0;
	exper_CloseFacilityApparatusModal();
	exper_fixButtonsAndDivs();
	fixAllButtons();
}

function exper_checkForFacilityApparatusSubmitErrors(){
	var error = "";
	if($( "#facilityName" ).val()=="") error += "Facility Name Cannot be Blank.\n";
	if($( "#facilityType" ).val()=="") error += "Facility Type Cannot be Blank.\n";
	if($( "#instituteName" ).val()=="") error += "Institute Name Cannot be Blank.\n";
	if($( "#apparatusName" ).val()=="") error += "Apparatus Name Cannot be Blank.\n";
	if($( "#apparatusType" ).val()=="") error += "Apparatus Type Cannot be Blank.\n";

	let documentRows = document.getElementById("documentRows");
	if(documentRows != null){
		documentRows = documentRows.children;
		for(docNum = 0; docNum < documentRows.length; docNum++){
			let documentNum = docNum + 1;
			let fileNode = documentRows[docNum].children[0].children[0].children[2].children[0].children[1];
			if(fileNode.type == "file"){
				if(fileNode.value == ""){
					error += "No file provided for apparatus document " + documentNum + ".\n";
				}
			}
			
		}
	}

	return error;
}

function exper_buildFacilityApparatusdata() {

	//facilityApparatusModal
	let facilityApparatusModal = document.getElementById("facilityApparatusModal");
	
	var facility = new Object();
	
	var address = new Object();
	address.street = findSubNode(facilityApparatusModal, "street" ).value;
	address.building = findSubNode(facilityApparatusModal, "buildingApartment" ).value;
	address.postcode = findSubNode(facilityApparatusModal, "postalCode" ).value;
	address.city = findSubNode(facilityApparatusModal, "city" ).value;
	address.state = findSubNode(facilityApparatusModal, "state" ).value;
	address.country = findSubNode(facilityApparatusModal, "country" ).value;
	address.latitude = findSubNode(facilityApparatusModal, "latitude" ).value;
	address.longitude = findSubNode(facilityApparatusModal, "longitude" ).value;
	facility.address = address;
	
	var contact = new Object();
	contact.firstname = findSubNode(facilityApparatusModal, "firstName" ).value;
	contact.lastname = findSubNode(facilityApparatusModal, "lastName" ).value;
	contact.affiliation = findSubNode(facilityApparatusModal, "affiliation" ).value;
	contact.email = findSubNode(facilityApparatusModal, "email" ).value;
	contact.phone = findSubNode(facilityApparatusModal, "phone" ).value;
	contact.website = findSubNode(facilityApparatusModal, "website" ).value;
	contact.id = findSubNode(facilityApparatusModal, "orcid" ).value;
	facility.contact = contact;
	
	facility.institute = findSubNode(facilityApparatusModal, "instituteName" ).value;
	facility.department = findSubNode(facilityApparatusModal, "department" ).value;
	facility.name = findSubNode(facilityApparatusModal, "facilityName" ).value;
	facility.type = findSubNode(facilityApparatusModal, "facilityType" ).value;
	facility.other_type = findSubNode(facilityApparatusModal, "otherFacilityType" ).value;
	facility.id = findSubNode(facilityApparatusModal, "facilityId" ).value;
	facility.website = findSubNode(facilityApparatusModal, "facilityWebsite" ).value;
	facility.description = findSubNode(facilityApparatusModal, "facilityDescription" ).value;
	
	facilityData = facility;
	
	//console.log(facilityData);
	
	var apparatus = new Object();
	apparatus.name = findSubNode(facilityApparatusModal, "apparatusName" ).value;
	apparatus.type = findSubNode(facilityApparatusModal, "apparatusType" ).value;
	apparatus.other_type = findSubNode(facilityApparatusModal, "otherApparatusType" ).value;
	apparatus.location = findSubNode(facilityApparatusModal, "apparatusLocation" ).value;
	apparatus.id = findSubNode(facilityApparatusModal, "apparatusId" ).value;
	apparatus.description = findSubNode(facilityApparatusModal, "apparatusDescription" ).value;
	
	const features = [];
	if($("#appf_loading").is(':checked')) features.push("Loading");
	if($("#appf_unloading").is(':checked')) features.push("Unloading");
	if($("#appf_heating").is(':checked')) features.push("Heating");
	if($("#appf_cooling").is(':checked')) features.push("Cooling");
	if($("#appf_high_temperature").is(':checked')) features.push("High Temperature");
	if($("#appf_ultra-high_temperature").is(':checked')) features.push("Ultra-High Temperature");
	if($("#appf_low_temperature").is(':checked')) features.push("Low Temperature");
	if($("#appf_sub-zero_temperature").is(':checked')) features.push("Sub-Zero Temperature");
	if($("#appf_high_pressure").is(':checked')) features.push("High Pressure");
	if($("#appf_ultra-high_pressure").is(':checked')) features.push("Ultra-High Pressure");
	if($("#appf_hydrostatic_tests").is(':checked')) features.push("Hydrostatic Tests");
	if($("#appf_hip").is(':checked')) features.push("HIP");
	if($("#appf_synthesis").is(':checked')) features.push("Synthesis");
	if($("#appf_deposition_evaporation").is(':checked')) features.push("Deposition/Evaporation");
	if($("#appf_mineral_reactions").is(':checked')) features.push("Mineral Reactions");
	if($("#appf_hydrothermal_reactions").is(':checked')) features.push("Hydrothermal Reactions");
	if($("#appf_elasticity").is(':checked')) features.push("Elasticity");
	if($("#appf_local_axial_strain").is(':checked')) features.push("Local Axial Strain");
	if($("#appf_local_radial_strain").is(':checked')) features.push("Local Radial Strain");
	if($("#appf_elastic_moduli").is(':checked')) features.push("Elastic Moduli");
	if($("#appf_yield_strength").is(':checked')) features.push("Yield Strength");
	if($("#appf_failure_strength").is(':checked')) features.push("Failure Strength");
	if($("#appf_strength").is(':checked')) features.push("Strength");
	if($("#appf_extension").is(':checked')) features.push("Extension");
	if($("#appf_creep").is(':checked')) features.push("Creep");
	if($("#appf_friction").is(':checked')) features.push("Friction");
	if($("#appf_frictional_sliding").is(':checked')) features.push("Frictional Sliding");
	if($("#appf_slide_hold_slide").is(':checked')) features.push("Slide Hold Slide");
	if($("#appf_stepping").is(':checked')) features.push("Stepping");
	if($("#appf_pure_shear").is(':checked')) features.push("Pure Shear");
	if($("#appf_simple_shear").is(':checked')) features.push("Simple Shear");
	if($("#appf_rotary_shear").is(':checked')) features.push("Rotary Shear");
	if($("#appf_torsion").is(':checked')) features.push("Torsion");
	if($("#appf_viscosity").is(':checked')) features.push("Viscosity");
	if($("#appf_indentation").is(':checked')) features.push("Indentation");
	if($("#appf_hardness").is(':checked')) features.push("Hardness");
	if($("#appf_dynamic_tests").is(':checked')) features.push("Dynamic Tests");
	if($("#appf_hydraulic_fracturing").is(':checked')) features.push("Hydraulic Fracturing");
	if($("#appf_hydrothermal_fracturing").is(':checked')) features.push("Hydrothermal Fracturing");
	if($("#appf_shockwave").is(':checked')) features.push("Shockwave");
	if($("#appf_reactive_flow").is(':checked')) features.push("Reactive Flow");
	if($("#appf_pore_fluid_control").is(':checked')) features.push("Pore Fluid Control");
	if($("#appf_pore_fluid_chemistry").is(':checked')) features.push("Pore Fluid Chemistry");
	if($("#appf_pore_volume_compaction").is(':checked')) features.push("Pore Volume Compaction");
	if($("#appf_storage_capacity").is(':checked')) features.push("Storage Capacity");
	if($("#appf_permeability").is(':checked')) features.push("Permeability");
	if($("#appf_steady-state_permeability").is(':checked')) features.push("Steady-State Permeability");
	if($("#appf_transient_permeability").is(':checked')) features.push("Transient Permeability");
	if($("#appf_hydraulic_conductivity").is(':checked')) features.push("Hydraulic Conductivity");
	if($("#appf_drained_undrained_pore_fluid").is(':checked')) features.push("Drained/Undrained Pore Fluid");
	if($("#appf_uniaxial_stress_strain").is(':checked')) features.push("Uniaxial Stress/Strain");
	if($("#appf_biaxial_stress_strain").is(':checked')) features.push("Biaxial Stress/Strain");
	if($("#appf_triaxial_stress_strain").is(':checked')) features.push("Triaxial Stress/Strain");
	if($("#appf_differential_stress").is(':checked')) features.push("Differential Stress");
	if($("#appf_true_triaxial").is(':checked')) features.push("True Triaxial");
	if($("#appf_resistivity").is(':checked')) features.push("Resistivity");
	if($("#appf_electrical_resistivity").is(':checked')) features.push("Electrical Resistivity");
	if($("#appf_electrical_capacitance").is(':checked')) features.push("Electrical Capacitance");
	if($("#appf_streaming_potential").is(':checked')) features.push("Streaming Potential");
	if($("#appf_acoustic_velocity").is(':checked')) features.push("Acoustic Velocity");
	if($("#appf_acoustic_events").is(':checked')) features.push("Acoustic Events");
	if($("#appf_p-wave_velocity").is(':checked')) features.push("P-Wave Velocity");
	if($("#appf_s-wave_velocity").is(':checked')) features.push("S-Wave Velocity");
	if($("#appf_source_location").is(':checked')) features.push("Source Location");
	if($("#appf_tomography").is(':checked')) features.push("Tomography");
	if($("#appf_in-situ_x-ray").is(':checked')) features.push("In-Situ X-Ray");
	if($("#appf_infrared").is(':checked')) features.push("Infrared");
	if($("#appf_raman").is(':checked')) features.push("Raman");
	if($("#appf_visual").is(':checked')) features.push("Visual");
	if($("#appf_other").is(':checked')) features.push("Other");
	
	if(features.length > 0){
		apparatus.features = features;
	}

	//Build Parameters
	let paramsTable = document.getElementById("paramsTable");
	if(paramsTable.children[0].children.length > 1){
		var paramsArray = [];
		let paramRows = paramsTable.children[0].children;
		for(let i = 1; i < paramRows.length; i++){
			var param = new Object();
			param.type = paramRows[i].children[0].children[0].value;
			param.min = paramRows[i].children[1].children[0].value;
			param.max = paramRows[i].children[2].children[0].value;
			param.unit = paramRows[i].children[3].children[0].value;
			param.prefix = paramRows[i].children[4].children[0].value;
			param.note = paramRows[i].children[5].children[0].value;

			paramsArray.push(param);
		}
		apparatus.parameters = paramsArray;
	}
	
	//Build Documents
	let docsDiv = document.getElementById("documentRows");
	let docRows = docsDiv.children;
	if(docRows.length > 0){
		var docsArray = [];
		for(let i = 0; i < docRows.length; i++){
			var doc = new Object();
			doc.type = docRows[i].children[0].children[0].children[0].children[0].children[1].value;
			doc.other_type = docRows[i].children[0].children[0].children[0].children[0].children[2].children[0].value;
			doc.format = docRows[i].children[0].children[0].children[1].children[0].children[1].value;
			doc.other_format = docRows[i].children[0].children[0].children[1].children[0].children[2].children[0].value;
			
			console.log("doc other format: " + doc.other_format);

			//if this type is file, get file name, otherwise get name from div
			//look in this node: console.log(docRows[i].children[0].children[0].children[2].children[0].children[1]);
			
			//console.log(docRows[i].children[0].children[0].children[2].children[0].children[1].type);
			
			/*
			if(docRows[i].children[0].children[0].children[2].children[0].children[1].type == "file"){
				//get file name from input - remove path first
				var fullPath = docRows[i].children[0].children[0].children[2].children[0].children[1].value;
				var fileName = fullPath.split(/[\\\/]/).pop();
				//alert(fileName);
			}else{
				//get file name from div
				//console.log(docRows[i].children[0].children[0].children[2].children[0].children[1].children[0].innerHTML);
				var fileName = docRows[i].children[0].children[0].children[2].children[0].children[1].children[0].innerHTML;
			}
			
			
			
			doc.path = fileName;
			*/
			
			doc.path = findSubNode(docRows[i],"originalFilename").value;
			
			//doc.path = 'foofoo';
			doc.id = docRows[i].children[0].children[0].children[3].children[0].children[1].value;
			doc.uuid = docRows[i].children[0].children[0].children[3].children[0].children[2].value;
			doc.description = docRows[i].children[0].children[1].children[0].children[0].children[1].value;
			
			docsArray.push(doc);
		}
		apparatus.documents = docsArray;
	}
	
	apparatusData = apparatus;
	
	console.log(apparatusData);
	

}





















































function exper_populateApparatusData() {
	//console.log(apparatusData);
	if(apparatusData != null){
		let apparatusDiv = document.getElementById('apparatusInfo');
		let showType = "";
		if(apparatusData.type == "Other Apparatus" && apparatusData.other_type != null && apparatusData.other_type != ""){
			showType = apparatusData.other_type;
		}else{
			showType = apparatusData.type;
		}
		apparatusDiv.innerHTML = '			<div class="formRow"> \
					<div class="formCell expButtonSpacer">\
						<button class="squareButtonSmaller" onclick="exper_doEditFacilityApparatus();"><img title="Edit" src="/experimental/buttonImages/icons8-edit-30.png" width="17" height="17"></button>\
						<button class="squareButtonSmaller" onclick="exp_deleteApparatusData();"><img title="Delete" src="/experimental/buttonImages/icons8-trash-30.png" width="17" height="17"></button>\
					</div>\
					<div class="formCell w33">\
						<div class="formPart">\
							<label class="formLabel">Apparatus Name</label>\
							<div>'+apparatusData.name+'</div>\
						</div>\
					</div>\
					<div class="formCell w33">\
						<div class="formPart">\
							<label class="formLabel">Apparatus Type</label>\
							<div>'+showType+'</div>\
						</div>\
					</div>\
				</div>\
				<div class="formRow">\
					<div class="formCell expButtonSpacer">\
						&nbsp;\
					</div>\
					<div class="formCell w33">\
						<div class="formPart">\
							<label class="formLabel">Institute</label>\
							<div>'+facilityData.institute+'</div>\
						</div>\
					</div>\
					<div class="formCell w33">\
						<div class="formPart">\
							<label class="formLabel">Department</label>\
							<div>'+facilityData.department+'</div>\
						</div>\
					</div>\
				</div>';
	
		//fill in front page section
	}
}

function exper_clearFacilityApparatusInterface() {

	let facilityApparatusModal = document.getElementById("facilityApparatusModal");
	
	findSubNode(facilityApparatusModal, "street" ).value = "";
	findSubNode(facilityApparatusModal, "buildingApartment" ).value = "";
	findSubNode(facilityApparatusModal, "postalCode" ).value = "";
	findSubNode(facilityApparatusModal, "city" ).value = "";
	findSubNode(facilityApparatusModal, "state" ).value = "";
	findSubNode(facilityApparatusModal, "country" ).value = "";
	findSubNode(facilityApparatusModal, "latitude" ).value = "";
	findSubNode(facilityApparatusModal, "longitude" ).value = "";

	findSubNode(facilityApparatusModal, "firstName" ).value = "";
	findSubNode(facilityApparatusModal, "lastName" ).value = "";
	findSubNode(facilityApparatusModal, "affiliation" ).value = "";
	findSubNode(facilityApparatusModal, "email" ).value = "";
	findSubNode(facilityApparatusModal, "phone" ).value = "";
	findSubNode(facilityApparatusModal, "website" ).value = "";
	findSubNode(facilityApparatusModal, "orcid" ).value = "";

	findSubNode(facilityApparatusModal, "instituteName" ).value = "";
	findSubNode(facilityApparatusModal, "department" ).value = "";
	findSubNode(facilityApparatusModal, "facilityName" ).value = "";
	findSubNode(facilityApparatusModal, "facilityType" ).value = "";
	findSubNode(facilityApparatusModal, "facilityId" ).value = "";
	findSubNode(facilityApparatusModal, "facilityWebsite" ).value = "";
	findSubNode(facilityApparatusModal, "facilityDescription" ).value = "";

	findSubNode(facilityApparatusModal, "apparatusName" ).value = "";
	findSubNode(facilityApparatusModal, "apparatusType" ).value = "";
	findSubNode(facilityApparatusModal, "apparatusLocation" ).value = "";
	findSubNode(facilityApparatusModal, "apparatusId" ).value = "";
	findSubNode(facilityApparatusModal, "apparatusDescription" ).value = "";
	
	$( "#appf_loading" ).prop( "checked", true );
	$( "#appf_unloading" ).prop( "checked", true );
	$( "#appf_heating" ).prop( "checked", false );
	$( "#appf_cooling" ).prop( "checked", false );
	$( "#appf_high_temperature" ).prop( "checked", false );
	$( "#appf_ultra-high_temperature" ).prop( "checked", false );
	$( "#appf_low_temperature" ).prop( "checked", false );
	$( "#appf_sub-zero_temperature" ).prop( "checked", false );
	$( "#appf_high_pressure" ).prop( "checked", false );
	$( "#appf_ultra-high_pressure" ).prop( "checked", false );
	$( "#appf_hydrostatic_tests" ).prop( "checked", false );
	$( "#appf_hip" ).prop( "checked", false );
	$( "#appf_synthesis" ).prop( "checked", false );
	$( "#appf_deposition_evaporation" ).prop( "checked", false );
	$( "#appf_mineral_reactions" ).prop( "checked", false );
	$( "#appf_hydrothermal_reactions" ).prop( "checked", false );
	$( "#appf_elasticity" ).prop( "checked", false );
	$( "#appf_local_axial_strain" ).prop( "checked", false );
	$( "#appf_local_radial_strain" ).prop( "checked", false );
	$( "#appf_elastic_moduli" ).prop( "checked", false );
	$( "#appf_yield_strength" ).prop( "checked", false );
	$( "#appf_failure_strength" ).prop( "checked", false );
	$( "#appf_strength" ).prop( "checked", false );
	$( "#appf_extension" ).prop( "checked", false );
	$( "#appf_creep" ).prop( "checked", false );
	$( "#appf_friction" ).prop( "checked", false );
	$( "#appf_frictional_sliding" ).prop( "checked", false );
	$( "#appf_slide_hold_slide" ).prop( "checked", false );
	$( "#appf_stepping" ).prop( "checked", false );
	$( "#appf_pure_shear" ).prop( "checked", false );
	$( "#appf_simple_shear" ).prop( "checked", false );
	$( "#appf_rotary_shear" ).prop( "checked", false );
	$( "#appf_torsion" ).prop( "checked", false );
	$( "#appf_viscosity" ).prop( "checked", false );
	$( "#appf_indentation" ).prop( "checked", false );
	$( "#appf_hardness" ).prop( "checked", false );
	$( "#appf_dynamic_tests" ).prop( "checked", false );
	$( "#appf_hydraulic_fracturing" ).prop( "checked", false );
	$( "#appf_hydrothermal_fracturing" ).prop( "checked", false );
	$( "#appf_shockwave" ).prop( "checked", false );
	$( "#appf_reactive_flow" ).prop( "checked", false );
	$( "#appf_pore_fluid_control" ).prop( "checked", false );
	$( "#appf_pore_fluid_chemistry" ).prop( "checked", false );
	$( "#appf_pore_volume_compaction" ).prop( "checked", false );
	$( "#appf_storage_capacity" ).prop( "checked", false );
	$( "#appf_permeability" ).prop( "checked", false );
	$( "#appf_steady-state_permeability" ).prop( "checked", false );
	$( "#appf_transient_permeability" ).prop( "checked", false );
	$( "#appf_hydraulic_conductivity" ).prop( "checked", false );
	$( "#appf_drained_undrained_pore_fluid" ).prop( "checked", false );
	$( "#appf_uniaxial_stress_strain" ).prop( "checked", false );
	$( "#appf_biaxial_stress_strain" ).prop( "checked", false );
	$( "#appf_triaxial_stress_strain" ).prop( "checked", false );
	$( "#appf_differential_stress" ).prop( "checked", false );
	$( "#appf_true_triaxial" ).prop( "checked", false );
	$( "#appf_resistivity" ).prop( "checked", false );
	$( "#appf_electrical_resistivity" ).prop( "checked", false );
	$( "#appf_electrical_capacitance" ).prop( "checked", false );
	$( "#appf_streaming_potential" ).prop( "checked", false );
	$( "#appf_acoustic_velocity" ).prop( "checked", false );
	$( "#appf_acoustic_events" ).prop( "checked", false );
	$( "#appf_p-wave_velocity" ).prop( "checked", false );
	$( "#appf_s-wave_velocity" ).prop( "checked", false );
	$( "#appf_source_location" ).prop( "checked", false );
	$( "#appf_tomography" ).prop( "checked", false );
	$( "#appf_in-situ_x-ray" ).prop( "checked", false );
	$( "#appf_infrared" ).prop( "checked", false );
	$( "#appf_raman" ).prop( "checked", false );
	$( "#appf_visual" ).prop( "checked", false );
	$( "#appf_other" ).prop( "checked", false );

	//Clear Parameters
	let paramsTable = document.getElementById("paramsTable");
	if(paramsTable.children[0].children.length > 1){
		let paramRows = paramsTable.children[0].children;
		
		console.log("num params: " + paramRows.length);
		
		let numRows = paramRows.length;
		for(let i = 1; i < numRows; i++){
			paramRows[1].remove();
		}
		
		paramsTable.style.display = "none";
	}
	
	//Build Documents
	let docsDiv = document.getElementById("documentRows");
	docsDiv.innerHTML = "";



}

/*
function exper_populateApparatusData() {
	//console.log(apparatusData);
	if(apparatusData != null){
		let apparatusDiv = document.getElementById('apparatusInfo');
		apparatusDiv.innerHTML = '			<div class="formRow"> \
					<div class="formCell expButtonSpacer">\
						<button class="squareButtonSmaller" onclick="window.open(\'view_apparatus?u='+apparatusData.uuid+'\', \'_blank\');"><img title="View" src="/experimental/buttonImages/icons8-view-30.png" width="17" height="17"></button>\
						<button class="squareButtonSmaller" onclick="exper_openApparatusModal();"><img title="Edit" src="/experimental/buttonImages/icons8-edit-30.png" width="17" height="17"></button>\
						<button class="squareButtonSmaller" onclick="exp_deleteApparatusData();"><img title="Delete" src="/experimental/buttonImages/icons8-trash-30.png" width="17" height="17"></button>\
					</div>\
					<div class="formCell w33">\
						<div class="formPart">\
							<label class="formLabel">Apparatus Name</label>\
							<div>'+apparatusData.name+'</div>\
						</div>\
					</div>\
					<div class="formCell w33">\
						<div class="formPart">\
							<label class="formLabel">Apparatus Type</label>\
							<div>'+apparatusData.type+'</div>\
						</div>\
					</div>\
				</div>\
				<div class="formRow">\
					<div class="formCell expButtonSpacer">\
						&nbsp;\
					</div>\
					<div class="formCell w33">\
						<div class="formPart">\
							<label class="formLabel">Institute</label>\
							<div>'+facilityData.institute+'</div>\
						</div>\
					</div>\
					<div class="formCell w33">\
						<div class="formPart">\
							<label class="formLabel">Department</label>\
							<div>'+facilityData.department+'</div>\
						</div>\
					</div>\
				</div>';
	
		//fill in front page section
	}
}
*/

function exp_deleteApparatusDataRaw() {
	//if (confirm("Are you sure you want to delete Apparatus Data?\nThis cannot be undone.") == true) {
		facilityData = null;
		apparatusData = null;
		let apparatusDiv = document.getElementById('apparatusInfo');
		apparatusDiv.innerHTML = '			<div class="formRow">\
				<span class="enterDataSpan">Enter Data: </span>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditFacilityApparatus()"><span>Manually </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal(\'facilityApparatus\');"><span>from Previous Experiment  </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON(\'facilityApparatus\')"><span>From JSON File </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openApparatusModal()"><span>From Apparatus Repository </span></button>\
			</div>';
	//}
	exper_fixDownloadButton();
}

function exp_deleteApparatusData() {
	if (confirm("Are you sure you want to delete Apparatus Data?\nThis cannot be undone.") == true) {
		facilityData = null;
		apparatusData = null;
		let apparatusDiv = document.getElementById('apparatusInfo');
		apparatusDiv.innerHTML = '			<div class="formRow">\
				<span class="enterDataSpan">Enter Data: </span>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditFacilityApparatus()"><span>Manually </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal(\'facilityApparatus\');"><span>from Previous Experiment  </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON(\'facilityApparatus\')"><span>From JSON File </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openApparatusModal()"><span>From Apparatus Repository </span></button>\
			</div>';
	}
	
	exper_fixDownloadButton();
}

//**************** DAQ ****************

function debugPrint(a = "not provided", b = "not provided", c = "not provided") {
	console.log("a: " + a + " b: " + b + " c: " + c);
}

function exper_CloseDAQModal() {
	let daqModal = document.getElementById("daqModal");
	daqModal.style.display = "none";
}

function exper_openDAQModal() {
	let daqModal = document.getElementById("daqModal");
	daqModal.style.display = "inline";
}

function exper_doEditDAQ() {
	//copy current daq data to temp
	console.log("edit daq");
	tempDaqData = daqData;
	exper_clearDAQInterface();
	populateDAQDataToInterface(daqData);
	exper_openDAQModal();
}

function exper_doSaveDAQInfo() {
	var error = exper_checkForDAQSubmitErrors();
	
	if(error != ""){
		alert(error);
	}else{
		exper_buildDAQdata();
		exper_populateDAQData();
		document.getElementById("daqModalBox").scrollTop = 0;
		exper_CloseDAQModal();
		exper_fixDownloadButton();
	}
}

function exper_doCancelDAQEdit() {
	daqData = tempDaqData;
	populateDAQDataToInterface(daqData);
	exper_populateDAQData();
	document.getElementById("daqModalBox").scrollTop = 0;
	exper_CloseDAQModal();
	exper_fixButtonsAndDivs();
	fixAllButtons();
}

function exper_populateDAQData() {
	if(daqData != null){
		let daqDiv = document.getElementById('daqInfo');
		daqDiv.innerHTML = '			<div class="formRow"> \
					<div class="formCell expButtonSpacer">\
						<button class="squareButtonSmaller" onclick="exper_doEditDAQ();"><img title="Edit" src="/experimental/buttonImages/icons8-edit-30.png" width="17" height="17"></button>\
						<button class="squareButtonSmaller" onclick="exper_deleteDAQData();"><img title="Delete" src="/experimental/buttonImages/icons8-trash-30.png" width="17" height="17"></button>\
					</div>\
					<div class="formCell w33">\
						<div class="formPart">\
							<label class="formLabel">Group Name</label>\
							<div>'+daqData.name+'</div>\
						</div>\
					</div>\
					<div class="formCell w25">\
						<div class="formPart">\
							<label class="formLabel">Type</label>\
							<div>'+daqData.type+'</div>\
						</div>\
					</div>\
					<div class="formCell w25">\
						<div class="formPart">\
							<label class="formLabel">Location</label>\
							<div>'+daqData.location+'</div>\
						</div>\
					</div>\
				</div>';
	}
	
}

function exper_deleteDAQData() {
	if (confirm("Are you sure you want to delete DAQ Data?\nThis cannot be undone.") == true) {
		daqData = null;
		let daqDiv = document.getElementById('daqInfo');
		
		//Reset DAQ fields here
		exper_clearDAQData();
		
		daqDiv.innerHTML = '			<div class="formRow">\
				<span class="enterDataSpan">Enter Data: </span>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditDAQ()"><span>Manually </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal(\'daq\');"><span>From Previous Experiment  </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON(\'daq\')"><span>From JSON File </span></button>\
			</div>';
	}
	
	exper_fixDownloadButton();
}

function exper_deleteDAQDataRaw() {
	//if (confirm("Are you sure you want to delete DAQ Data?\nThis cannot be undone.") == true) {
		daqData = null;
		let daqDiv = document.getElementById('daqInfo');
		
		//Reset DAQ fields here
		exper_clearDAQData();
		
		daqDiv.innerHTML = '			<div class="formRow">\
				<span class="enterDataSpan">Enter Data: </span>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditDAQ()"><span>Manually </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal(\'daq\');"><span>From Previous Experiment  </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON(\'daq\')"><span>From JSON File </span></button>\
			</div>';
	//}
	exper_fixDownloadButton();
}

function exper_clearAllData(){
	exper_deleteAllData();
}

function exper_deleteAllData() {
	document.getElementById('mainExperimentId').value="";
	exp_deleteApparatusDataRaw();
	exper_deleteDAQDataRaw();
	exper_deleteSampleDataRaw();
	exper_deleteExpSetupDataraw();
	exper_deleteDataDataRaw();
}

function exper_clearDAQData() {
	removeAllChildNodes(document.getElementById("daq_devices"));
	document.getElementById("daqGroupName").value="";
	document.getElementById("daqType").value="Standard";
	document.getElementById("daqLocation").value="";
	document.getElementById("daqDescription").value="";
}

function exper_clearDAQInterface() {
	console.log("clear daq interface");
	document.getElementById("daqGroupName").value = "";
	document.getElementById("daqType").value = "Standard";
	document.getElementById("daqLocation").value = "";
	document.getElementById("daqDescription").value = "";
	let daqDevices = document.getElementById("daq_devices");
	removeAllChildNodes(daqDevices);
}

function exper_buildDAQdata(){
	var daq = new Object();
	daq.name = $( "#daqGroupName" ).val();
	daq.type = $( "#daqType" ).val();
	daq.location = $( "#daqLocation" ).val();
	daq.description = $( "#daqDescription" ).val();

	let deviceRows = document.getElementById("daq_devices");
	if(deviceRows != null){
		
		let devices = [];
		deviceRows = deviceRows.children;
		for(let d = 0; d < deviceRows.length; d ++){
			
			var device = new Object();
			device.name = deviceRows[d].children[0].children[0].children[0].children[0].children[1].value;
			
			//OK, now find channel rows
			//let channelRows = deviceRows[d].children[0].children[1].children[0].children[0].children[0].children[1].children[0].children[1].children[0];
			let channelRows = document.getElementById("daq_device_channels_" + d);
			if(channelRows != null){
				let channels = [];
				channelRows = channelRows.children;
				for(let ch = 0; ch < channelRows.length; ch ++){
					var chan = new Object();
					
					var header = new Object();
					//console.log(channelRows[ch]);
					header.type = channelRows[ch].children[0].children[0].children[0].children[0].children[1].value;
					header.other_type = findSubNode(channelRows[ch], "otherChannelHeader").value;
					header.spec_a = channelRows[ch].children[0].children[1].children[0].children[0].children[1].children[0].value;
					header.spec_b = channelRows[ch].children[0].children[1].children[1].children[0].children[1].children[0].value;
					header.spec_c = channelRows[ch].children[0].children[1].children[2].children[0].children[1].value;
					header.unit = channelRows[ch].children[0].children[1].children[3].children[0].children[1].children[0].value;
					chan.header = header;
					
					chan.number = channelRows[ch].children[0].children[3].children[0].children[0].children[1].value;
					chan.type = channelRows[ch].children[0].children[3].children[1].children[0].children[1].value;
					chan.configuration = channelRows[ch].children[0].children[3].children[2].children[0].children[1].value;
					chan.note = channelRows[ch].children[0].children[3].children[3].children[0].children[1].value;
					
					chan.resolution = channelRows[ch].children[0].children[4].children[0].children[0].children[1].value;
					chan.range_low = channelRows[ch].children[0].children[4].children[1].children[0].children[1].value;
					chan.range_high = channelRows[ch].children[0].children[4].children[2].children[0].children[1].value;
					chan.rate = channelRows[ch].children[0].children[4].children[3].children[0].children[1].value;
					chan.filter = channelRows[ch].children[0].children[4].children[4].children[0].children[1].value;
					chan.gain = channelRows[ch].children[0].children[4].children[5].children[0].children[1].value;
					
					var sensor = new Object();
					sensor.template = channelRows[ch].children[0].children[6].children[0].children[0].children[1].value;
					sensor.detail = channelRows[ch].children[0].children[6].children[1].children[0].children[1].value;
					sensor.type = channelRows[ch].children[0].children[7].children[0].children[0].children[1].value;
					sensor.id = channelRows[ch].children[0].children[7].children[1].children[0].children[1].value;
					sensor.model = channelRows[ch].children[0].children[7].children[2].children[0].children[1].value;
					sensor.version = channelRows[ch].children[0].children[8].children[0].children[0].children[1].value;
					sensor.number = channelRows[ch].children[0].children[8].children[1].children[0].children[1].value;
					sensor.serial = channelRows[ch].children[0].children[8].children[2].children[0].children[1].value;
					chan.sensor = sensor;
					
					var cal = new Object;
					cal.template = channelRows[ch].children[0].children[10].children[0].children[0].children[1].value;
					cal.input = channelRows[ch].children[0].children[10].children[1].children[0].children[1].value;
					cal.unit = channelRows[ch].children[0].children[10].children[2].children[0].children[1].value;
					cal.excitation = channelRows[ch].children[0].children[10].children[3].children[0].children[1].value;
					cal.date = channelRows[ch].children[0].children[11].children[0].children[0].children[1].value;
					cal.note = channelRows[ch].children[0].children[11].children[1].children[0].children[1].value;
					chan.calibration = cal;
					
					//OK, now find data rows
					let dataRows = channelRows[ch].children[0].children[12].children[0].children[0].children[0].children[1].children[0];
					if(dataRows != null){
						let datas = [];
						dataRows = dataRows.children;
						for(let dt = 0; dt < dataRows.length; dt++){
							var dataPart = new Object;
							//console.log(dataRows[dt]);
							dataPart.a = dataRows[dt].children[0].children[0].children[0].value;
							dataPart.b = dataRows[dt].children[0].children[1].children[0].value;
							datas.push(dataPart);
						}
						if(datas.length > 0) chan.data = datas;
					}

					channels.push(chan);
				}
				if(channels.length > 0) device.channels = channels;
			}
			
			//Documents
			//let documentRows = deviceRows[d].children[0].children[1].children[0].children[0].children[1].children[1].children[0];
			let documentRows = document.getElementById("daq_device_documents_" + d);
			if(documentRows != null){
				var documents = [];
				documentRows = documentRows.children;
				for(docNum = 0; docNum < documentRows.length; docNum++){
					//console.log(documentRows[docNum]);
					var doc = new Object();
					doc.type = documentRows[docNum].children[0].children[0].children[0].children[0].children[1].value;
					doc.other_type = documentRows[docNum].children[0].children[0].children[0].children[0].children[2].children[0].value;
					
					doc.format = documentRows[docNum].children[0].children[0].children[1].children[0].children[1].value;
					doc.other_format = documentRows[docNum].children[0].children[0].children[1].children[0].children[2].children[0].value;
					
					//Check to see if this is a file node!!!!
					/*
					let node = documentRows[docNum].children[0].children[0].children[2].children[0].children[1];
					if(node.type=="file"){
						let fullPath = node.value;
						doc.path = fullPath.split(/[\\\/]/).pop();
					}else{
						//get filename from id filename
						doc.path = findSubNode(node, "filename").innerHTML;
					}
					*/
					
					
					doc.path = findSubNode(documentRows[docNum], "originalFilename").value;
					
					console.log("doc path: " + doc.path);
					
					doc.id = documentRows[docNum].children[0].children[0].children[3].children[0].children[1].value;
					doc.uuid = documentRows[docNum].children[0].children[0].children[3].children[0].children[2].value;
					
					doc.description = documentRows[docNum].children[0].children[1].children[0].children[0].children[1].value;
					
					documents.push(doc);
				}
				if(documents.length > 0) device.documents = documents;
			}

			devices.push(device);
		}
		
		if(devices.length > 0) daq.devices = devices;

	}

	daqData = daq;
	console.log(daqData);
}

function exper_checkForDAQSubmitErrors(){
	var error = "";
	if($( "#daqGroupName" ).val()=="") error += "DAQ Group Name Cannot be Blank.\n";

	let deviceRows = document.getElementById("daq_devices");
	if(deviceRows != null){

		deviceRows = deviceRows.children;
		for(let d = 0; d < deviceRows.length; d ++){
			let deviceNum = d + 1;

			//Check Device Name
			let deviceName = deviceRows[d].children[0].children[0].children[0].children[0].children[1].value;
			if(deviceName==""){
				error += "DAQ Device Name " + deviceNum + " Cannot be Blank.\n";
			}
			
			//OK, now find channel rows
			//let channelRows = deviceRows[d].children[0].children[1].children[0].children[0].children[0].children[1].children[0].children[1].children[0];
			let channelRows = document.getElementById("daq_device_channels_" + d);
			if(channelRows != null){
				channelRows = channelRows.children;
				for(let ch = 0; ch < channelRows.length; ch ++){
					
					let channelNum = ch + 1;
					
					//Check for Header
					let headerVal = findSubNode(channelRows[ch], "channelHeader").value;
					if(headerVal == "") error += "Channel Header must be provided for channel " + channelNum + ".\n";
					
					//specifierA
					let specAVal = findSubNode(channelRows[ch], "specifierA").value;
					if(specAVal == "") error += "Specifier A must be provided for channel " + channelNum + ".\n";
					
					//channelUnit
					let chanUnit = findSubNode(channelRows[ch], "channelUnit").value;
					if(chanUnit == "") error += "Channel unit must be provided for channel " + channelNum + ".\n";
					
					
					
					
					
					//OK, now find data rows
					let dataRows = channelRows[ch].children[0].children[12].children[0].children[0].children[0].children[1].children[0];

					if(dataRows != null){
						dataRows = dataRows.children;
						for(let dt = 0; dt < dataRows.length; dt++){
							let dataNum = dt + 1;
							let aVal = dataRows[dt].children[0].children[0].children[0].value;
							let bVal = dataRows[dt].children[0].children[1].children[0].value;
							
							//Check A and B vals
							if(aVal == "" || bVal == ""){
								error += "Both A and B values must be provided for device " + deviceNum + " channel " + channelNum + " data val " + dataNum + ".\n";
							}
							
						}
					}
					
				}
			}
			
			
			//let documentRows = deviceRows[d].children[0].children[1].children[0].children[0].children[1].children[1].children[0];
			//daq_device_documents_0
			let documentRows = document.getElementById("daq_device_documents_" + d);
			if(documentRows != null){
				documentRows = documentRows.children;
				for(docNum = 0; docNum < documentRows.length; docNum++){
					let documentNum = docNum + 1;
					
					let originalFilename = findSubNode(documentRows[docNum],"originalFilename").value;
					if(originalFilename == ""){
						error += "No file provided for device " + deviceNum + " document " + documentNum + ".\n";
					}
					
					/*
					let fileNode = documentRows[docNum].children[0].children[0].children[2].children[0].children[1];
					if(fileNode.type == "file"){
						if(fileNode.value == ""){
							error += "No file provided for device " + deviceNum + " document " + documentNum + ".\n";
						}
					}
					*/
				}
			}

			
		}
	}

	return error;
}

function isSideBarButtonSelected(button){
	let className = button.className;
	let classes = className.split(" ");
	//console.log(classes);
	return classes.includes("straboRedSelectedButton");
}

function exper_fixButtonsAndDivs(){ //need to rewrite this whole thing

	/*
	foreach devices, fix buttons and divs
		foreach channel, fix buttons and divs
			foreach data, fix buttons and divs
		foreach document, fix buttons and divs
	
	daq_device_x
		daq_device_channels_x
			daq_device_channel_x_y
		daq_device_channel_buttons_x
			daq_device_channel_button_x_y
		daq_device_documents_x
			daq_device_document_x_y
		daq_device_document_buttons_x
			daq_device_document_button_x_y
	*/












	//Fix facility type dropdown
	let facilityTypeSelect = document.getElementById("facilityType");
	if(facilityTypeSelect != null){
		facilityTypeSelect.setAttribute('onchange','exper_handleFacilityTypeChange();');
	}




	//Fix apparatus type dropdown
	let apparatusTypeSelect = document.getElementById("apparatusType");
	if(apparatusTypeSelect != null){
		apparatusTypeSelect.setAttribute('onchange','exper_handleApparatusTypeChange();');
	}



	//Fix apparatus documents
	let documentsTable = document.getElementById("documentRows");
	let numRows = documentsTable.children.length;
	
	for(var i = 0; i < documentsTable.children.length; i++){
		
		console.log("fixing document row " + i);
		
		var locNum = i + 1;
		
		let docRow = documentsTable.children[i];
		
		documentsTable.children[i].id = "docRow-" + locNum;
		documentsTable.children[i].children[1].children[0].children[0].setAttribute('onclick','deleteApparatusDocument('+ locNum +');');
		documentsTable.children[i].children[1].children[1].children[0].setAttribute('onclick','moveApparatusDocumentUp('+ locNum +');');
		documentsTable.children[i].children[1].children[2].children[0].setAttribute('onclick','moveApparatusDocumentDown('+ locNum + ');');

		let daqDocTypeSelect = findSubNode(docRow,"docType");
		if(daqDocTypeSelect != null){
			daqDocTypeSelect.setAttribute('onchange','exper_handleApparatusDocTypeChange('+ locNum + ');');
		}
		
		let daqDocFormatSelect = findSubNode(docRow,"docFormat");
		if(daqDocFormatSelect != null){
			daqDocFormatSelect.setAttribute('onchange','exper_handleApparatusDocFormatChange('+ locNum + ');');
		}
		
		let deleteLink = findSubNode(docRow,"deleteLink");
		if(deleteLink != null){
			deleteLink.setAttribute('onclick','exper_deleteApparatusFile('+ locNum + ');');
		}
		
		if(i==0){
			//Disable up
			documentsTable.children[i].children[1].children[1].children[0].style.display = "none";
			
			if(documentsTable.children.length == 1) {
				//Disable down
				documentsTable.children[i].children[1].children[2].children[0].style.display = "none";
			}else{
				//Enable down
				documentsTable.children[i].children[1].children[2].children[0].style.display = "inline";
			}
		}else if(i == numRows - 1) {
			//Disable down on last row
			documentsTable.children[i].children[1].children[2].children[0].style.display = "none";
			if(documentsTable.children.length > 1) {
				//Enable up
				documentsTable.children[i].children[1].children[1].children[0].style.display = "inline";

			}else{
				//Disable up
				documentsTable.children[i].children[1].children[1].children[0].style.display = "none";
			}
		}else{
			//Enable both
			documentsTable.children[i].children[1].children[1].children[0].style.display = "inline";
			documentsTable.children[i].children[1].children[2].children[0].style.display = "inline";
		}
		
		let docFile = findSubNode(docRow, "docFile");
		if(docFile != null){
			docFile.setAttribute('onchange','exper_uploadApparatusFile('+ locNum +');');
		}
		
	}








































	
	
	//foreach daq devices
	let deviceRows = document.getElementById("daq_devices");
	if(deviceRows != null){

		deviceRows = deviceRows.children;
		let deviceCount = deviceRows.length;
		let lastDevice = deviceCount - 1;
		for(let d = 0; d < deviceRows.length; d ++){
			
			deviceRows[d].id = "daq_device_" + d;

			//find delete, up, and down buttons
			let deleteButton = deviceRows[d].children[1].children[0].children[0];
			let upButton = deviceRows[d].children[1].children[1].children[0];
			let downButton = deviceRows[d].children[1].children[2].children[0];

			deleteButton.setAttribute('onclick','exper_deleteDAQDevice('+ d +');');
			upButton.setAttribute('onclick','exper_moveDAQDeviceUp('+ d +');');
			downButton.setAttribute('onclick','exper_moveDAQDeviceDown('+ d +');');

			upButton.style.display = "inline";
			downButton.style.display = "inline";
			if(d == 0) upButton.style.display = "none";
			if(d == lastDevice) downButton.style.display = "none";
			
			//fix add channel button
			let addChannelButton = deviceRows[d].children[0].children[1].children[0].children[0].children[0].children[0].children[0];
			addChannelButton.setAttribute('onclick','exper_addDAQDeviceChannel('+ d +');');
			
			//fix add document button
			//console.log(deviceRows[d].children[0].children[1].children[0].children[0].children[1].children[0].children[0]);
			let addDocumentButton = deviceRows[d].children[0].children[1].children[0].children[0].children[1].children[0].children[0];
			addDocumentButton.setAttribute('onclick','exper_addDAQDeviceDocument('+ d +');');
			
			//Also need to re-configure the side channel buttons, change ids and classes? onclicks?
			//Find channel buttons
			let channelButtonRows = deviceRows[d].children[0].children[1].children[0].children[0].children[0].children[1].children[0].children[0].children[0];
			channelButtonRows.id = "daq_device_channel_buttons_" + d;
			let channelButtons = channelButtonRows.children;
			for(let ch = 0; ch < channelButtons.length; ch ++){
				channelButtons[ch].id = "daq_device_channel_button_" + d + "_" + ch;
				channelButtons[ch].setAttribute('onclick','exper_daqSwitchToChannel('+ d +', '+ ch +');');
				
				//fix class for switching
				let highlightButton = isSideBarButtonSelected(channelButtons[ch]);
				channelButtons[ch].className = "";
				channelButtons[ch].classList.add("sideBarButton");
				channelButtons[ch].classList.add("daq_device_channel_button_group_" + d);
				if(highlightButton){
					channelButtons[ch].classList.add("straboRedSelectedButton");
				}
				
			}

			//Find channel rows
			let channelRows = deviceRows[d].children[0].children[1].children[0].children[0].children[0].children[1].children[0].children[1].children[0];

			if(channelRows != null){

				//Assign channels div id
				channelRows.id = "daq_device_channels_" + d;

				channelRows = channelRows.children;
				let channelCount = channelRows.length;
				let lastChannel = channelCount - 1;
				for(let ch = 0; ch < channelRows.length; ch ++){
					
					//Assign id to channel div
					channelRows[ch].id = "daq_device_channel_" + d + "_" + ch;

					//fix channel div class
					channelRows[ch].classList.add("daq_device_channel_group_" + d);
					

					//Fix Selects to change button onChange vals
					let channelHeaderSelect = channelRows[ch].children[0].children[0].children[0].children[0].children[1];
					channelHeaderSelect.setAttribute('onchange','exper_daqHandleChannelHeaderChange(' + d + ', ' + ch +'); exper_daqRenameChannelButton(' + d + ', ' + ch +');');
	
					let channelNumSelect = channelRows[ch].children[0].children[3].children[0].children[0].children[1];
					channelNumSelect.setAttribute('onchange','exper_daqRenameChannelButton(' + d + ', ' + ch +');');
					
					let channelUnitSelect = findSubNode(channelRows[ch], "channelUnit");
					if(channelUnitSelect != null){
						channelUnitSelect.setAttribute('onchange','exper_daqRenameChannelButton(' + d + ', ' + ch +');');
					}
					
					let otherChannelHeaderTextField = findSubNode(channelRows[ch], "otherChannelHeader");
					if(otherChannelHeaderTextField != null){
						otherChannelHeaderTextField.setAttribute('onkeyup','exper_daqRenameChannelButton(' + d + ', ' + ch +');');
					}

					//find up/down/delete buttons
					let deleteButton = channelRows[ch].children[1].children[0].children[0];
					let upButton = channelRows[ch].children[1].children[1].children[0];
					let downButton = channelRows[ch].children[1].children[2].children[0];
					
					deleteButton.setAttribute('onclick','exper_deleteDAQDeviceChannel('+ d +', '+ ch +');');
					upButton.setAttribute('onclick','exper_moveDAQDeviceChannelUp('+ d +', '+ ch +');');
					downButton.setAttribute('onclick','exper_moveDAQDeviceChannelDown('+ d +', '+ ch +');');
					
					upButton.style.display = "inline";
					downButton.style.display = "inline";
					if(ch == 0) upButton.style.display = "none";
					if(ch == lastChannel) downButton.style.display = "none";

					//find add data button
					let dataButton = channelRows[ch].children[0].children[12].children[0].children[0].children[0].children[0].children[0];
					dataButton.setAttribute('onclick','exper_addDAQDeviceChannelData('+ d +', '+ ch +');');

					//OK, now find data rows
					let dataRows = channelRows[ch].children[0].children[12].children[0].children[0].children[0].children[1].children[0];
					
					//Assign id to datas div
					dataRows.id = "daq_device_channel_datas_" + d + "_" + ch;

					if(dataRows != null){
						dataRows = dataRows.children;
						let dataCount = dataRows.length;
						let lastData = dataCount - 1;
						for(let dt = 0; dt < dataRows.length; dt++){
							
							dataRows[dt].id = "daq_device_channel_data_" + d + "_" + ch + "_" + dt;
							
							/*
							JMA 2022-06-21
							let deleteButton = dataRows[dt].children[0].children[2];
							let upButton = dataRows[dt].children[0].children[3];
							let downButton = dataRows[dt].children[0].children[4];
							*/
							
							let deleteButton = findSubNode(dataRows[dt], "deleteButton");
							let upButton = findSubNode(dataRows[dt], "upButton");
							let downButton = findSubNode(dataRows[dt], "downButton");
							
							deleteButton.setAttribute('onclick','exper_deleteDAQDeviceChannelData('+ d +', '+ ch +', '+ dt +');');
							upButton.setAttribute('onclick','exper_moveDAQDeviceChannelDataUp('+ d +', '+ ch +', '+ dt +');');
							downButton.setAttribute('onclick','exper_moveDAQDeviceChannelDataDown('+ d +', '+ ch +', '+ dt +');');
				
							upButton.style.display = "inline";
							downButton.style.display = "inline";
							if(dt == 0) upButton.style.display = "none";
							if(dt == lastData) downButton.style.display = "none";
							
						}
					}
					
					
				}
			}
		
			
			//Also need to re-configure the side document buttons.
			
			
			//let documentButtonRows = deviceRows[d].children[0].children[1].children[0].children[0].children[1].children[1].children[0].children[0].children[0]; 20231023
			let documentButtonRows = document.getElementById("daq_device_document_buttons_" + d);
			
			if(documentButtonRows != null){
				documentButtonRows.id = "daq_device_document_buttons_" + d;
				let documentButtons = documentButtonRows.children;
				for(let dr = 0; dr < documentButtons.length; dr ++){
					documentButtons[dr].id = "daq_device_document_button_" + d + "_" + dr;
					documentButtons[dr].setAttribute('onclick','exper_daqSwitchToDocument('+ d +', '+ dr +');');
				
					//fix class for switching
					let highlightButton = isSideBarButtonSelected(documentButtons[dr]);
					documentButtons[dr].className = "";
					documentButtons[dr].classList.add("sideBarButton");
					documentButtons[dr].classList.add("daq_device_document_button_group_" + d);
					if(highlightButton){
						documentButtons[dr].classList.add("straboRedSelectedButton");
					}
				
				}
			}
			
			
			
			//let documentRows = deviceRows[d].children[0].children[1].children[0].children[0].children[1].children[1].children[0].children[1].children[0]; 20231023
			let documentRows = document.getElementById("daq_device_documents_" + d);
			//console.log(documentRows);
			
			if(documentRows != null){
				
				//Assign id to Documents div
				documentRows.id = "daq_device_documents_" + d;

				documentRows = documentRows.children;
				let documentCount = documentRows.length;
				let lastDocument = documentCount - 1;
				for(docNum = 0; docNum < documentCount; docNum++){

					//fix document div class
					documentRows[docNum].id = "daq_device_document_" + d + "_" + docNum;
					documentRows[docNum].classList.add("daq_device_document_group_" + d);

					//add listeners to selects
					let docTypeSelect = findSubNode(documentRows[docNum],"docType");
					if(docTypeSelect != null){
						docTypeSelect.setAttribute('onchange','exper_handleDAQDocTypeSelectChange('+ d + ',' + docNum +');exper_daqRenameDocumentButton(' + d + ', ' + docNum +');');
					}
					
					let docFormatSelect = findSubNode(documentRows[docNum],"docFormat");
					if(docFormatSelect != null){
						docFormatSelect.setAttribute('onchange','exper_handleDAQDocFormatSelectChange('+ d + ',' + docNum +');');
					}
					
					let fileElement = findSubNode(documentRows[docNum], "docFile");
					if(fileElement != null){
						let fileHolder = findSubNode(documentRows[docNum], "fileHolder");
						//fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="alert("here")">';
						fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="exper_uploadDAQDeviceFile(' + d + ',' + docNum + ')">';
					}
					
					let deleteLink = findSubNode(documentRows[docNum],"deleteLink");
					if(deleteLink != null){
						deleteLink.setAttribute('onclick','exper_deleteDAQDeviceFile('+ d + ',' + docNum +');');
					}
					
					//Add onclick to document type select - exper_daqRenameDocumentButton
					//console.log(documentRows[docNum].children[0].children[0].children[0].children[0].children[1]);
					//////let docTypeSelect = documentRows[docNum].children[0].children[0].children[0].children[0].children[1];
					//////docTypeSelect.setAttribute('onchange','exper_daqRenameDocumentButton(' + d + ', ' + docNum +');');
					
					let deleteButton = documentRows[docNum].children[1].children[0].children[0];
					let upButton = documentRows[docNum].children[1].children[1].children[0];
					let downButton = documentRows[docNum].children[1].children[2].children[0];
					
					deleteButton.setAttribute('onclick','exper_deleteDAQDeviceDocument('+ d +', '+ docNum +');');
					upButton.setAttribute('onclick','exper_moveDAQDeviceDocumentUp('+ d +', '+ docNum +');');
					downButton.setAttribute('onclick','exper_moveDAQDeviceDocumentDown('+ d +', '+ docNum +');');
					
					upButton.style.display = "inline";
					downButton.style.display = "inline";
					if(docNum == 0) upButton.style.display = "none";
					if(docNum == lastDocument) downButton.style.display = "none";
					

				}
				
			}
		}
	}
	
	//Sample
	//Sample Mineral Phase buttons.
	let sampleMineralButtonRows = document.getElementById("sample_mineral_phase_buttons");
	if(sampleMineralButtonRows != null){
		sampleMineralButtonRows = sampleMineralButtonRows.children;
		for(let dr = 0; dr < sampleMineralButtonRows.length; dr ++){
			sampleMineralButtonRows[dr].id = "sample_mineral_phase_button_" + dr;
			sampleMineralButtonRows[dr].setAttribute('onclick','exper_sampleSwitchToMineral('+ dr +');');
		
			//fix class for switching
			let highlightButton = isSideBarButtonSelected(sampleMineralButtonRows[dr]);
			sampleMineralButtonRows[dr].className = "";
			sampleMineralButtonRows[dr].classList.add("sideBarButton");
			sampleMineralButtonRows[dr].classList.add("sample_mineral_phase_button_group");
			if(highlightButton){
				sampleMineralButtonRows[dr].classList.add("straboRedSelectedButton");
			}
		
		}
	}
	
	
	//Sample Mineral Phase divs. 
	let sampleMineralRows = document.getElementById("sample_mineral_phases");
	//console.log(documentRows);
	
	if(sampleMineralRows != null){

		sampleMineralRows = sampleMineralRows.children;
		let rowCount = sampleMineralRows.length;
		let lastRow = rowCount - 1;
		for(rowNum = 0; rowNum < rowCount; rowNum++){

			//fix document div class
			sampleMineralRows[rowNum].id = "sample_mineral_phase_" + rowNum;
			sampleMineralRows[rowNum].classList.add("sample_mineral_phase_group");
			
			//Add onclick to document type select - exper_daqRenameDocumentButton
			//console.log(documentRows[docNum].children[0].children[0].children[0].children[0].children[1]);
			
			
			let mineralNameSelect = findSubNode(sampleMineralRows[rowNum], "mineralName");
			mineralNameSelect.setAttribute('onchange','exper_sampleRenameMineralButton(' + rowNum +');');
			
			let deleteButton = findSubNode(sampleMineralRows[rowNum], "deleteButton");
			let upButton = findSubNode(sampleMineralRows[rowNum], "upButton");
			let downButton = findSubNode(sampleMineralRows[rowNum], "downButton");
			
			deleteButton.setAttribute('onclick','exper_deleteSampleMineralPhase('+ rowNum +');');
			upButton.setAttribute('onclick','exper_moveSampleMineralPhaseUp('+ rowNum +');');
			downButton.setAttribute('onclick','exper_moveSampleMineralPhaseDown('+ rowNum +');');
			
			upButton.style.display = "inline";
			downButton.style.display = "inline";
			if(rowNum == 0) upButton.style.display = "none";
			if(rowNum == lastRow) downButton.style.display = "none";
			

		}
		
	}

	//**************************
	
	//Sample Parameter buttons.
	let sampleParameterButtonRows = document.getElementById("sample_parameter_buttons");
	if(sampleParameterButtonRows != null){
		sampleParameterButtonRows = sampleParameterButtonRows.children;
		for(let dr = 0; dr < sampleParameterButtonRows.length; dr ++){
			sampleParameterButtonRows[dr].id = "sample_parameter_button_" + dr;
			sampleParameterButtonRows[dr].setAttribute('onclick','exper_sampleSwitchToParameter('+ dr +');');
		
			//fix class for switching
			let highlightButton = isSideBarButtonSelected(sampleParameterButtonRows[dr]);
			sampleParameterButtonRows[dr].className = "";
			sampleParameterButtonRows[dr].classList.add("sideBarButton");
			sampleParameterButtonRows[dr].classList.add("sample_parameter_button_group");
			if(highlightButton){
				sampleParameterButtonRows[dr].classList.add("straboRedSelectedButton");
			}
		
		}
	}
	
	
	//Sample Parameter divs. 
	let sampleParameterRows = document.getElementById("sample_parameters");
	//console.log(documentRows);
	
	if(sampleParameterRows != null){

		sampleParameterRows = sampleParameterRows.children;
		let rowCount = sampleParameterRows.length;
		let lastRow = rowCount - 1;
		for(rowNum = 0; rowNum < rowCount; rowNum++){

			//fix document div class
			sampleParameterRows[rowNum].id = "sample_parameter_" + rowNum;
			sampleParameterRows[rowNum].classList.add("sample_parameter_group");

			let parameterVariableSelect = findSubNode(sampleParameterRows[rowNum], "parameterVariable");
			parameterVariableSelect.setAttribute('onchange','exper_handleSampleVariableChange(' + rowNum +');exper_sampleRenameParameterButton(' + rowNum +');');
			
			let deleteButton = findSubNode(sampleParameterRows[rowNum], "deleteButton");
			let upButton = findSubNode(sampleParameterRows[rowNum], "upButton");
			let downButton = findSubNode(sampleParameterRows[rowNum], "downButton");
			
			deleteButton.setAttribute('onclick','exper_deleteSampleParameter('+ rowNum +');');
			upButton.setAttribute('onclick','exper_moveSampleParameterUp('+ rowNum +');');
			downButton.setAttribute('onclick','exper_moveSampleParameterDown('+ rowNum +');');
			
			upButton.style.display = "inline";
			downButton.style.display = "inline";
			if(rowNum == 0) upButton.style.display = "none";
			if(rowNum == lastRow) downButton.style.display = "none";

		}
		
	}

	//**************************
	
	//Sample Document buttons.
	let sampleDocumentButtonRows = document.getElementById("sample_document_buttons");
	if(sampleDocumentButtonRows != null){
		sampleDocumentButtonRows = sampleDocumentButtonRows.children;
		for(let dr = 0; dr < sampleDocumentButtonRows.length; dr ++){
			sampleDocumentButtonRows[dr].id = "sample_document_button_" + dr;
			sampleDocumentButtonRows[dr].setAttribute('onclick','exper_sampleSwitchToDocument('+ dr +');');
		
			//fix class for switching
			let highlightButton = isSideBarButtonSelected(sampleDocumentButtonRows[dr]);
			sampleDocumentButtonRows[dr].className = "";
			sampleDocumentButtonRows[dr].classList.add("sideBarButton");
			sampleDocumentButtonRows[dr].classList.add("sample_document_button_group");
			if(highlightButton){
				sampleDocumentButtonRows[dr].classList.add("straboRedSelectedButton");
			}
		
		}
	}
	
	
	//Sample Document divs. 
	let sampleDocumentRows = document.getElementById("sample_documents");
	//console.log(documentRows);
	
	if(sampleDocumentRows != null){

		sampleDocumentRows = sampleDocumentRows.children;
		let rowCount = sampleDocumentRows.length;
		let lastRow = rowCount - 1;
		for(rowNum = 0; rowNum < rowCount; rowNum++){

			//fix document div class
			sampleDocumentRows[rowNum].id = "sample_document_" + rowNum;
			sampleDocumentRows[rowNum].classList.add("sample_document_group");

			//add listeners to selects
			let docTypeSelect = findSubNode(sampleDocumentRows[rowNum], "docType");
			if(docTypeSelect != null){
				docTypeSelect.setAttribute('onchange','exper_handleSampleDocTypeChange('+ rowNum +');exper_sampleRenameDocumentButton(' + rowNum +');');
			}
			
			let docFormatSelect = findSubNode(sampleDocumentRows[rowNum],"docFormat");
			if(docFormatSelect != null){
				docFormatSelect.setAttribute('onchange','exper_handleSampleDocFormatChange('+ rowNum +');');
			}
			
			//Document uploads
			let fileElement = findSubNode(sampleDocumentRows[rowNum], "docFile");
			if(fileElement != null){
				let fileHolder = findSubNode(sampleDocumentRows[rowNum], "fileHolder");
				//fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="alert("here")">';
				fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="exper_uploadSampleFile(' + rowNum + ')">';
			}
			
			let deleteLink = findSubNode(sampleDocumentRows[rowNum],"deleteLink");
			if(deleteLink != null){
				deleteLink.setAttribute('onclick','exper_deleteSampleFile('+ rowNum +');');
			}

			

			let deleteButton = findSubNode(sampleDocumentRows[rowNum], "deleteButton");
			let upButton = findSubNode(sampleDocumentRows[rowNum], "upButton");
			let downButton = findSubNode(sampleDocumentRows[rowNum], "downButton");
			
			deleteButton.setAttribute('onclick','exper_deleteSampleDocument('+ rowNum +');');
			upButton.setAttribute('onclick','exper_moveSampleDocumentUp('+ rowNum +');');
			downButton.setAttribute('onclick','exper_moveSampleDocumentDown('+ rowNum +');');
			
			upButton.style.display = "inline";
			downButton.style.display = "inline";
			if(rowNum == 0) upButton.style.display = "none";
			if(rowNum == lastRow) downButton.style.display = "none";

		}
		
	}

	//Experiment Geometry buttons.
	let experimentGeometryButtonRows = document.getElementById("experiment_geometry_buttons");

	if(experimentGeometryButtonRows != null){
		experimentGeometryButtonRows = experimentGeometryButtonRows.children;
		for(let dr = 0; dr < experimentGeometryButtonRows.length; dr ++){
			experimentGeometryButtonRows[dr].id = "experiment_geometry_button_" + dr;
			experimentGeometryButtonRows[dr].setAttribute('onclick','exper_experimentSwitchToGeometry('+ dr +');');
		
			//fix class for switching
			let highlightButton = isSideBarButtonSelected(experimentGeometryButtonRows[dr]);
			experimentGeometryButtonRows[dr].className = "";
			experimentGeometryButtonRows[dr].classList.add("sideBarButton");
			experimentGeometryButtonRows[dr].classList.add("experiment_geometry_button_group");
			if(highlightButton){
				experimentGeometryButtonRows[dr].classList.add("straboRedSelectedButton");
			}
		
	}
	}

	//Experiment Geometry Divs
	//Sample Document divs. 
	let experimentGeometryRows = document.getElementById("experiment_geometries");

	if(experimentGeometryRows != null){

		experimentGeometryRows = experimentGeometryRows.children;
		let rowCount = experimentGeometryRows.length;
		let lastRow = rowCount - 1;
		for(rowNum = 0; rowNum < rowCount; rowNum++){

			//fix document div class
			experimentGeometryRows[rowNum].id = "experiment_geometry_" + rowNum;
			experimentGeometryRows[rowNum].classList.add("experiment_geometry_group");

			let geometryNumSelect = findSubNode(experimentGeometryRows[rowNum], "experimentGeometryNum");
			geometryNumSelect.setAttribute('onchange','exper_experimentRenameGeometryButton(' + rowNum +');');
			
			let geometryTypeSelect = findSubNode(experimentGeometryRows[rowNum], "experimentGeometryType");
			geometryTypeSelect.setAttribute('onchange','exper_experimentRenameGeometryButton(' + rowNum +');');

			let deleteButton = findSubNode(experimentGeometryRows[rowNum], "deleteButton");
			let upButton = findSubNode(experimentGeometryRows[rowNum], "upButton");
			let downButton = findSubNode(experimentGeometryRows[rowNum], "downButton");
			
			deleteButton.setAttribute('onclick','exper_deleteExperimentGeometry('+ rowNum +');');
			upButton.setAttribute('onclick','exper_moveExperimentGeometryUp('+ rowNum +');');
			downButton.setAttribute('onclick','exper_moveExperimentGeometryDown('+ rowNum +');');
			
			upButton.style.display = "inline";
			downButton.style.display = "inline";
			if(rowNum == 0) upButton.style.display = "none";
			if(rowNum == lastRow) downButton.style.display = "none";
			
			//Fix Add Dimension Button
			let addDimensionButton = findSubNode(experimentGeometryRows[rowNum], "addDimensionButton");
			addDimensionButton.setAttribute('onclick','exper_addExperimentGeometryDimension(' + rowNum + ');');
			
			//Fix Dimensions
			//Show/Hide dimensionRowsHolder based on number of dimensions
			let dimensionRowsHolder = findSubNode(experimentGeometryRows[rowNum], "dimensionRowsHolder");
			if(dimensionRowsHolder != null){
				let dimensionRowsDiv = findSubNode(experimentGeometryRows[rowNum], "dimensionRows");
				let dimensionRows = dimensionRowsDiv.children;
				if(dimensionRows.length > 0 ){
					dimensionRowsHolder.style.display = "block";
				}else{
					dimensionRowsHolder.style.display = "none";
				}
			
				for(dRow = 0; dRow < dimensionRows.length; dRow++){

					let lastDimRow = dimensionRows.length - 1;
					let deleteDimButton = findSubNode(dimensionRows[dRow], "deleteDimensionButton");
					let upDimButton = findSubNode(dimensionRows[dRow], "upDimensionButton");
					let downDimButton = findSubNode(dimensionRows[dRow], "downDimensionButton");
			
					deleteDimButton.setAttribute('onclick','exper_deleteExperimentGeometryDimension('+ rowNum +', '+ dRow +');');
					upDimButton.setAttribute('onclick','exper_moveExperimentGeometryDimensionUp('+ rowNum +', '+ dRow +');');
					downDimButton.setAttribute('onclick','exper_moveExperimentGeometryDimensionDown('+ rowNum +', '+ dRow +');');
			
					upDimButton.style.display = "inline";
					downDimButton.style.display = "inline";
					if(dRow == 0) upDimButton.style.display = "none";
					if(dRow == lastDimRow) downDimButton.style.display = "none";

				}
			}
			

		}
		
	}

	//Experiment Protocol buttons.
	let experimentProtocolButtonRows = document.getElementById("experiment_protocol_buttons");

	if(experimentProtocolButtonRows != null){
		experimentProtocolButtonRows = experimentProtocolButtonRows.children;
		for(let dr = 0; dr < experimentProtocolButtonRows.length; dr ++){
			experimentProtocolButtonRows[dr].id = "experiment_protocol_button_" + dr;
			experimentProtocolButtonRows[dr].setAttribute('onclick','exper_experimentSwitchToProtocol('+ dr +');');
		
			//fix class for switching
			let highlightButton = isSideBarButtonSelected(experimentProtocolButtonRows[dr]);
			experimentProtocolButtonRows[dr].className = "";
			experimentProtocolButtonRows[dr].classList.add("sideBarButton");
			experimentProtocolButtonRows[dr].classList.add("experiment_protocol_button_group");
			if(highlightButton){
				experimentProtocolButtonRows[dr].classList.add("straboRedSelectedButton");
			}
		
		}
	}

	//Experiment Protocol Divs
	let experimentProtocolRows = document.getElementById("experiment_protocols");

	if(experimentProtocolRows != null){

		experimentProtocolRows = experimentProtocolRows.children;
		let rowCount = experimentProtocolRows.length;
		let lastRow = rowCount - 1;
		for(rowNum = 0; rowNum < rowCount; rowNum++){

			//fix document div class
			experimentProtocolRows[rowNum].id = "experiment_protocol_" + rowNum;
			experimentProtocolRows[rowNum].classList.add("experiment_protocol_group");

			//let protocolNumSelect = findSubNode(experimentProtocolRows[rowNum], "experimentProtocolNum");
			//protocolNumSelect.setAttribute('onchange','exper_experimentRenameProtocolButton(' + rowNum +');');
			
			let protocolTestSelect = findSubNode(experimentProtocolRows[rowNum], "experimentProtocolTest");
			if(protocolTestSelect != null){
				protocolTestSelect.setAttribute('onchange','exper_experimentRenameProtocolButton(' + rowNum +');');
			}

			let deleteButton = findSubNode(experimentProtocolRows[rowNum], "deleteButton");
			let upButton = findSubNode(experimentProtocolRows[rowNum], "upButton");
			let downButton = findSubNode(experimentProtocolRows[rowNum], "downButton");
			
			deleteButton.setAttribute('onclick','exper_deleteExperimentProtocol('+ rowNum +');');
			upButton.setAttribute('onclick','exper_moveExperimentProtocolUp('+ rowNum +');');
			downButton.setAttribute('onclick','exper_moveExperimentProtocolDown('+ rowNum +');');
			
			upButton.style.display = "inline";
			downButton.style.display = "inline";
			if(rowNum == 0) upButton.style.display = "none";
			if(rowNum == lastRow) downButton.style.display = "none";
			
			//Fix Add Dimension Button
			let addParameterButton = findSubNode(experimentProtocolRows[rowNum], "addParameterButton");
			addParameterButton.setAttribute('onclick','exper_addExperimentProtocolParameter(' + rowNum + ');');
			
			//Fix Paramters
			//Show/Hide parameterRowsHolder based on number of parameters
			
			let parameterRowsHolder = findSubNode(experimentProtocolRows[rowNum], "parameterRowsHolder");
			let parameterRowsDiv = findSubNode(experimentProtocolRows[rowNum], "parameterRows");
			if(parameterRowsDiv != null){
				let parameterRows = parameterRowsDiv.children;
				if(parameterRows.length > 0 ){
					parameterRowsHolder.style.display = "block";
				}else{
					parameterRowsHolder.style.display = "none";
				}
			
				for(dRow = 0; dRow < parameterRows.length; dRow++){

					let lastParameterRow = parameterRows.length - 1;
					let deleteParameterButton = findSubNode(parameterRows[dRow], "deleteParameterButton");
					let upParameterButton = findSubNode(parameterRows[dRow], "upParameterButton");
					let downParameterButton = findSubNode(parameterRows[dRow], "downParameterButton");
			
					deleteParameterButton.setAttribute('onclick','exper_deleteExperimentProtocolParameter('+ rowNum +', '+ dRow +');');
					upParameterButton.setAttribute('onclick','exper_moveExperimentProtocolParameterUp('+ rowNum +', '+ dRow +');');
					downParameterButton.setAttribute('onclick','exper_moveExperimentProtocolParameterDown('+ rowNum +', '+ dRow +');');
			
					upParameterButton.style.display = "inline";
					downParameterButton.style.display = "inline";
					if(dRow == 0) upParameterButton.style.display = "none";
					if(dRow == lastParameterRow) downParameterButton.style.display = "none";

				}
			}
			
			

		}
		
	}

	//Experiment Document buttons.
	let experimentDocumentButtonRows = document.getElementById("experiment_document_buttons");
	if(experimentDocumentButtonRows != null){
		experimentDocumentButtonRows = experimentDocumentButtonRows.children;
		for(let dr = 0; dr < experimentDocumentButtonRows.length; dr ++){
			experimentDocumentButtonRows[dr].id = "experiment_document_button_" + dr;
			experimentDocumentButtonRows[dr].setAttribute('onclick','exper_experimentSwitchToDocument('+ dr +');');
		
			//fix class for switching
			let highlightButton = isSideBarButtonSelected(experimentDocumentButtonRows[dr]);
			experimentDocumentButtonRows[dr].className = "";
			experimentDocumentButtonRows[dr].classList.add("sideBarButton");
			experimentDocumentButtonRows[dr].classList.add("experiment_document_button_group");
			if(highlightButton){
				experimentDocumentButtonRows[dr].classList.add("straboRedSelectedButton");
			}
		
		}
	}

	//Experiment Document divs. 
	let experimentDocumentRows = document.getElementById("experiment_documents");
	
	if(experimentDocumentRows != null){

		experimentDocumentRows = experimentDocumentRows.children;
		let rowCount = experimentDocumentRows.length;
		let lastRow = rowCount - 1;
		for(rowNum = 0; rowNum < rowCount; rowNum++){

			//fix document div class
			experimentDocumentRows[rowNum].id = "experiment_document_" + rowNum;
			experimentDocumentRows[rowNum].classList.add("experiment_document_group");

			let docTypeSelect = findSubNode(experimentDocumentRows[rowNum], "docType");
			if(docTypeSelect != null){
				docTypeSelect.setAttribute('onchange','exper_handleExperimentDocTypeChange('+ rowNum +');exper_experimentRenameDocumentButton(' + rowNum +');');
			}
			
			let docFormatSelect = findSubNode(experimentDocumentRows[rowNum],"docFormat");
			if(docFormatSelect != null){
				docFormatSelect.setAttribute('onchange','exper_handleExperimentDocFormatChange('+ rowNum +');');
			}





			//Document uploads
			let fileElement = findSubNode(experimentDocumentRows[rowNum], "docFile");
			if(fileElement != null){
				let fileHolder = findSubNode(experimentDocumentRows[rowNum], "fileHolder");
				//fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="alert("here")">';
				fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="exper_uploadExperimentFile(' + rowNum + ')">';
			}
			
			let deleteLink = findSubNode(experimentDocumentRows[rowNum],"deleteLink");
			if(deleteLink != null){
				deleteLink.setAttribute('onclick','exper_deleteExperimentFile('+ rowNum +');');
			}


			let deleteButton = findSubNode(experimentDocumentRows[rowNum], "deleteButton");
			let upButton = findSubNode(experimentDocumentRows[rowNum], "upButton");
			let downButton = findSubNode(experimentDocumentRows[rowNum], "downButton");
			
			deleteButton.setAttribute('onclick','exper_deleteExperimentDocument('+ rowNum +');');
			upButton.setAttribute('onclick','exper_moveExperimentDocumentUp('+ rowNum +');');
			downButton.setAttribute('onclick','exper_moveExperimentDocumentDown('+ rowNum +');');
			
			upButton.style.display = "inline";
			downButton.style.display = "inline";
			if(rowNum == 0) upButton.style.display = "none";
			if(rowNum == lastRow) downButton.style.display = "none";

		}
		
	}










	//Data Dataset buttons.
	let dataDatasetButtonRows = document.getElementById("data_dataset_buttons");

	if(dataDatasetButtonRows != null){
		dataDatasetButtonRows = dataDatasetButtonRows.children;
		for(let dr = 0; dr < dataDatasetButtonRows.length; dr ++){
			dataDatasetButtonRows[dr].id = "data_dataset_button_" + dr;
			dataDatasetButtonRows[dr].setAttribute('onclick','exper_dataSwitchToDataset('+ dr +');');
		
			//fix class for switching
			let highlightButton = isSideBarButtonSelected(dataDatasetButtonRows[dr]);
			dataDatasetButtonRows[dr].className = "";
			dataDatasetButtonRows[dr].classList.add("sideBarButton");
			dataDatasetButtonRows[dr].classList.add("data_dataset_button_group");
			if(highlightButton){
				dataDatasetButtonRows[dr].classList.add("straboRedSelectedButton");
			}
		
		}
	}

	//Data Dataset Divs
	let dataDatasetRows = document.getElementById("data_datasets");
	if(dataDatasetRows != null){
		dataDatasetRows = dataDatasetRows.children;
		
		let rowCount = dataDatasetRows.length;
		let lastRow = rowCount - 1;
		for(rowNum = 0; rowNum < rowCount; rowNum++){
		
			let dRow = dataDatasetRows[rowNum];

			//fix dataset div class
			dRow.id = "data_dataset_" + rowNum;
			dRow.classList.add("data_dataset_group");
			
			//Add Listener to Data Type
			let dataTypeDropdown = findSubNode(dRow,"dataType");
			if(dataTypeDropdown != null){
				dataTypeDropdown.setAttribute('onchange','exper_handleDataDataTypeChange('+ rowNum +');');
			}
			
			//Add Listener to Data Format
			let dataFormatDropdown = findSubNode(dRow,"dataFormat");
			if(dataFormatDropdown != null){
				dataFormatDropdown.setAttribute('onchange','exper_handleDataDataFormatChange('+ rowNum +');');
			}
			
			//Document uploads
			let fileElement = findSubNode(dRow, "dataFile");
			if(fileElement != null){
				let fileHolder = findSubNode(dRow, "fileHolder");
				//fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="alert("here")">';
				fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="exper_uploadDataFile(' + rowNum + ')">';
			}
			
			let deleteLink = findSubNode(dRow,"deleteLink");
			if(deleteLink != null){
				deleteLink.setAttribute('onclick','exper_deleteDataFile('+ rowNum +');');
			}
			
			let dataSelect = findSubNode(dRow, "dataData");
			if(dataSelect != null){
				dataSelect.setAttribute('onchange','exper_dataRenameDatasetButton(' + rowNum +');');
			}

			let deleteButton = findSubNode(dRow, "deleteButton");
			let upButton = findSubNode(dRow, "upButton");
			let downButton = findSubNode(dRow, "downButton");
			
			deleteButton.setAttribute('onclick','exper_deleteDataDataset('+ rowNum +');');
			upButton.setAttribute('onclick','exper_moveDataDatasetUp('+ rowNum +');');
			downButton.setAttribute('onclick','exper_moveDataDatasetDown('+ rowNum +');');
			
			upButton.style.display = "inline";
			downButton.style.display = "inline";
			if(rowNum == 0) upButton.style.display = "none";
			if(rowNum == lastRow) downButton.style.display = "none";
			
			//////////////////////////////////// Parameters
			//Fix addParameterButton onchange
			let addParameterButton = findSubNode(dRow, "addParameterButton");
			if(addParameterButton != null){
				addParameterButton.setAttribute('onclick','exper_addDataParameter(' + rowNum +');');
				
				let parameterRows = findSubNode(dRow, "parameterRows");
				if(parameterRows != null){
					parameterRows = parameterRows.children;
					let parameterRowCount = parameterRows.length;
					let lastParameterRow = parameterRowCount - 1;
					for(parameterRowNum = 0; parameterRowNum < parameterRowCount; parameterRowNum++){

						let pRow = parameterRows[parameterRowNum];
						pRow.id = "data_parameter_" + rowNum + "_" + parameterRowNum;

						let deleteParameterButton = findSubNode(pRow, "deleteParameterButton");
						let upParameterButton = findSubNode(pRow, "upParameterButton");
						let downParameterButton = findSubNode(pRow, "downParameterButton");
		
						deleteParameterButton.setAttribute('onclick','exper_deleteDataParameter('+ rowNum + ', ' + parameterRowNum + ');');
						upParameterButton.setAttribute('onclick','exper_moveDataParameterUp('+ rowNum + ', ' + parameterRowNum + ');');
						downParameterButton.setAttribute('onclick','exper_moveDataParameterDown('+ rowNum + ', ' + parameterRowNum + ');');
		
						upParameterButton.style.display = "inline";
						downParameterButton.style.display = "inline";
						if(parameterRowNum == 0) upParameterButton.style.display = "none";
						if(parameterRowNum == lastParameterRow) downParameterButton.style.display = "none";

					}
				}
				
				

				
			}
			
			//////////////////////////////////// Headers
			//Header Buttons
			let dataHeaderButtonRows = findSubNode(dRow, "header_buttons");
			if(dataHeaderButtonRows != null){
				dataHeaderButtonRows = dataHeaderButtonRows.children;
				for(let dr = 0; dr < dataHeaderButtonRows.length; dr ++){
					dataHeaderButtonRows[dr].id = "data_header_button_" + rowNum + "_" + dr;
					dataHeaderButtonRows[dr].setAttribute('onclick','exper_dataSwitchToHeader(' + rowNum + ', ' + dr +');');
		
					//fix class for switching
					let highlightButton = isSideBarButtonSelected(dataHeaderButtonRows[dr]);
					dataHeaderButtonRows[dr].className = "";
					dataHeaderButtonRows[dr].classList.add("sideBarButton");
					dataHeaderButtonRows[dr].classList.add("data_header_button_group_" + rowNum);
					if(highlightButton){
						dataHeaderButtonRows[dr].classList.add("straboRedSelectedButton");
					}
		
				}
			}
			
			//Header Divs
			let addHeaderButton = findSubNode(dRow, "addHeaderButton");
			if(addHeaderButton != null){
				addHeaderButton.setAttribute('onclick','exper_addDatasetHeader(' + rowNum +');');
			}
			
			//Find headers and fix ids and classes
			let headerRows = findSubNode(dRow, "headers");
			if(headerRows != null){
				headerRows = headerRows.children;
	
				let headerRowCount = headerRows.length;
				let lastHeaderRow = headerRowCount - 1;

				for(headerRowNum = 0; headerRowNum < headerRowCount; headerRowNum++){
	
					let hRow = headerRows[headerRowNum];

					//fix dataset div class
					hRow.id = "data_header_" + rowNum + "_" + headerRowNum;
					hRow.classList.add("data_header_group_" + rowNum);
					
					//fix select onchange
					let headerSelect = findSubNode(hRow, "headerHeader");
					if(headerSelect != null){
						headerSelect.setAttribute('onchange', 'exper_updateDataHeaderInputs(' + rowNum + ', ' + headerRowNum + ');');
					}
					
					let deleteHeaderButton = findSubNode(hRow, "deleteHeaderButton");
					let upHeaderButton = findSubNode(hRow, "upHeaderButton");
					let downHeaderButton = findSubNode(hRow, "downHeaderButton");
	
					deleteHeaderButton.setAttribute('onclick','exper_deleteDataHeader('+ rowNum + ', ' + headerRowNum + ');');
					upHeaderButton.setAttribute('onclick','exper_moveDataHeaderUp('+ rowNum + ', ' + headerRowNum + ');');
					downHeaderButton.setAttribute('onclick','exper_moveDataHeaderDown('+ rowNum + ', ' + headerRowNum + ');');
	
					upHeaderButton.style.display = "inline";
					downHeaderButton.style.display = "inline";
					if(headerRowNum == 0) upHeaderButton.style.display = "none";
					if(headerRowNum == lastHeaderRow) downHeaderButton.style.display = "none";

				}
			}

			//////////////////////////////////// Phases

			//Phase Buttons
			let dataPhaseButtonRows = findSubNode(dRow, "phase_buttons");
			if(dataPhaseButtonRows != null){
				dataPhaseButtonRows = dataPhaseButtonRows.children;
				for(let dr = 0; dr < dataPhaseButtonRows.length; dr ++){
					dataPhaseButtonRows[dr].id = "data_phase_button_" + rowNum + "_" + dr;
					dataPhaseButtonRows[dr].setAttribute('onclick','exper_dataSwitchToPhase(' + rowNum + ', ' + dr +');');
		
					//fix class for switching
					let highlightButton = isSideBarButtonSelected(dataPhaseButtonRows[dr]);
					dataPhaseButtonRows[dr].className = "";
					dataPhaseButtonRows[dr].classList.add("sideBarButton");
					dataPhaseButtonRows[dr].classList.add("data_phase_button_group_" + rowNum);
					if(highlightButton){
						dataPhaseButtonRows[dr].classList.add("straboRedSelectedButton");
					}
		
				}
			}

			//Phase Divs
			let addPhaseButton = findSubNode(dRow, "addPhaseButton");
			if(addPhaseButton != null){
				addPhaseButton.setAttribute('onclick','exper_addDataPhase(' + rowNum +');');
			}

			let phaseRows = findSubNode(dRow, "phases");
			if(phaseRows != null){
				phaseRows = phaseRows.children;
				let phaseRowCount = phaseRows.length;
				let lastPhaseRow = phaseRowCount - 1;
				for(phaseRowNum = 0; phaseRowNum < phaseRowCount; phaseRowNum++){

					let pRow = phaseRows[phaseRowNum];
					pRow.id = "data_phase_" + rowNum + "_" + phaseRowNum;
					pRow.classList.add("data_phase_group_" + rowNum);

					//phaseComposition onchange
					let phaseComposition = findSubNode(pRow, "phaseComposition");
					if(phaseComposition != null){
						phaseComposition.setAttribute('onchange','exper_updateDataPhaseInputs(' + rowNum + ', ' + phaseRowNum +');');
					}
					
					let phaseChemistryData = findSubNode(pRow, "phaseChemistryData");
					if(phaseChemistryData != null){
						phaseChemistryData.setAttribute('onchange','exper_updateDataPhaseChemistryHolder(' + rowNum + ', ' + phaseRowNum +');');
					}
					
					//Add Solute Button
					let addSoluteButton = findSubNode(pRow, "addSoluteButton");
					if(addSoluteButton != null){
						addSoluteButton.setAttribute('onclick','exper_addDataSolute(' + rowNum + ', ' + phaseRowNum +');');
					}
					
					//Fix Solute Buttons here
					let soluteRows = findSubNode(pRow, "soluteRows");
					if(soluteRows != null){
						soluteRows = soluteRows.children;
						let soluteRowCount = soluteRows.length;
						let lastSoluteRow = soluteRowCount - 1;
						for(soluteRowNum = 0; soluteRowNum < soluteRowCount; soluteRowNum++){
						
							let sRow = soluteRows[soluteRowNum];
							sRow.id = "data_solute_" + rowNum + "_" + phaseRowNum + "_" + soluteRowNum;
							
							//Now buttons
							let deleteSoluteButton = findSubNode(sRow, "deleteSoluteButton");
							let upSoluteButton = findSubNode(sRow, "upSoluteButton");
							let downSoluteButton = findSubNode(sRow, "downSoluteButton");
	
							deleteSoluteButton.setAttribute('onclick','exper_deleteDataSolute('+ rowNum + ', ' + phaseRowNum + ', ' + soluteRowNum + ');');
							upSoluteButton.setAttribute('onclick','exper_moveDataSoluteUp('+ rowNum + ', ' + phaseRowNum + ', ' + soluteRowNum + ');');
							downSoluteButton.setAttribute('onclick','exper_moveDataSoluteDown('+ rowNum + ', ' + phaseRowNum + ', ' + soluteRowNum + ');');
	
							upSoluteButton.style.display = "inline";
							downSoluteButton.style.display = "inline";
							if(soluteRowNum == 0) upSoluteButton.style.display = "none";
							if(soluteRowNum == lastSoluteRow) downSoluteButton.style.display = "none";
						
						}

					}

					let deletePhaseButton = findSubNode(pRow, "deletePhaseButton");
					let upPhaseButton = findSubNode(pRow, "upPhaseButton");
					let downPhaseButton = findSubNode(pRow, "downPhaseButton");
	
					deletePhaseButton.setAttribute('onclick','exper_deleteDataPhase('+ rowNum + ', ' + phaseRowNum + ');');
					upPhaseButton.setAttribute('onclick','exper_moveDataPhaseUp('+ rowNum + ', ' + phaseRowNum + ');');
					downPhaseButton.setAttribute('onclick','exper_moveDataPhaseDown('+ rowNum + ', ' + phaseRowNum + ');');
	
					upPhaseButton.style.display = "inline";
					downPhaseButton.style.display = "inline";
					if(phaseRowNum == 0) upPhaseButton.style.display = "none";
					if(phaseRowNum == lastPhaseRow) downPhaseButton.style.display = "none";

				}
			}












		}
	}


	exper_fixDownloadButton();
	

	buildTooltips();















































/*
	let dataDatasetRows = document.getElementById("data_datasets");
	if(dataDatasetRows != null){
		dataDatasetRows = dataDatasetRows.children;
		
		let rowCount = dataDatasetRows.length;
		let lastRow = rowCount - 1;
		for(rowNum = 0; rowNum < rowCount; rowNum++){
		
			let dRow = dataDatasetRows[rowNum];

			//fix dataset div class
			dataDatasetRows[rowNum].id = "data_dataset_" + rowNum;
			dataDatasetRows[rowNum].classList.add("data_dataset_group");
			
			let dataSelect = findSubNode(dataDatasetRows[rowNum], "dataData");

			dataSelect.setAttribute('onchange','exper_dataRenameDatasetButton(' + rowNum +');');

			let deleteButton = findSubNode(dataDatasetRows[rowNum], "deleteButton");
			let upButton = findSubNode(dataDatasetRows[rowNum], "upButton");
			let downButton = findSubNode(dataDatasetRows[rowNum], "downButton");
			
			deleteButton.setAttribute('onclick','exper_deleteDataDataset('+ rowNum +');');
			upButton.setAttribute('onclick','exper_moveDataDatasetUp('+ rowNum +');');
			downButton.setAttribute('onclick','exper_moveDataDatasetDown('+ rowNum +');');
			
			upButton.style.display = "inline";
			downButton.style.display = "inline";
			if(rowNum == 0) upButton.style.display = "none";
			if(rowNum == lastRow) downButton.style.display = "none";
			
			//Parameters
			//addParameterButton
			
		
		}
		
	}
*/











	
}

function exper_fixDownloadButton(){
	
	console.log("fix download button here.");
	
	//Fix download button
	let downloadButton = document.getElementById("downloadButton");
	
	if(downloadButton != null){
		let downloadImage = document.getElementById("downloadImage");
		//downloadButton.style.display = "none";
		///experimental/buttonImages/icons8-download-66.png
	
		if(facilityData != null || apparatusData != null || daqData != null || sampleData != null || experimentData != null || dataData != null){
			downloadButton.disabled = false;
			downloadImage.src = "/experimental/buttonImages/icons8-download-66.png";
		}else{
			downloadButton.disabled = true;
			downloadImage.src = "/experimental/buttonImages/empty.png";
		}
	}
}

//device
function exper_addDAQDevice() {
	let existingDiv = document.getElementById("daq_devices");
	let existingRows = existingDiv.children;
	
	//Figure out what row we're on
	var newRowNum = existingRows.length;

	let sourceDiv = document.getElementById("sourceDAQDeviceRow");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "daq_device_" + newRowNum;
	newDiv.classList.add("daq_device_group");
	
	//change channels holder div id
	let channelsDiv = newDiv.children[0].children[1].children[0].children[0].children[0].children[1].children[0].children[1].children[0];
	channelsDiv.id = "daq_device_channels_" + newRowNum;
	
	//change buttons holder div id
	let buttonsDiv = newDiv.children[0].children[1].children[0].children[0].children[0].children[1].children[0].children[0].children[0];
	buttonsDiv.id = "daq_device_channel_buttons_" + newRowNum;
	
	let docsDiv = findSubNode(newDiv, "documentHolder");
	docsDiv.id = "daq_device_documents_" + newRowNum;
	
	let docButtonssDiv = findSubNode(newDiv, "documentButtonHolder");
	docButtonssDiv.id = "daq_device_document_buttons_" + newRowNum;

	existingDiv.appendChild(newDiv);
	
	//Fix Buttons
	exper_fixButtonsAndDivs();
}

function exper_deleteDAQDevice(dnum){
	if (confirm("Are you sure you want to delete this Device?\nThis cannot be undone.") == true) {
		console.log("delete device " + dnum);
		let existingDiv = document.getElementById("daq_devices");
		let existingRows = existingDiv.children;
		existingRows[dnum].remove();
		//Fix Buttons
		exper_fixButtonsAndDivs();
	}
}

function exper_moveDAQDeviceUp(rowNum){

	exper_moveItemUpDown("daq_devices", rowNum, "up");
	//Fix Buttons
	exper_fixButtonsAndDivs();
}

function exper_moveDAQDeviceDown(rowNum){

	exper_moveItemUpDown("daq_devices", rowNum, "down");
	//Fix Buttons
	exper_fixButtonsAndDivs();
}

//channel
function exper_addDAQDeviceChannel(deviceRowNum){
	//console.log(deviceRowNum);
	//let deviceRow = document.getElementById("daq_devices").children[deviceRowNum];
	
	//let existingDiv = deviceRow.children[0].children[1].children[0].children[0].children[0].children[1].children[0].children[1].children[0];
	let existingDiv = document.getElementById("daq_device_channels_" + deviceRowNum);

	let existingRows = existingDiv.children;

	//Figure out what row we're on
	var newRowNum = existingRows.length;

	let sourceDiv = document.getElementById("sourceDAQChannelRow");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "daq_device_channel_" + deviceRowNum + "_" + newRowNum;
	newDiv.classList.add("daq_device_channel_group_" + deviceRowNum);

	existingDiv.appendChild(newDiv);
	
	//Also add button to sideBar
	//let buttonsDiv = deviceRow.children[0].children[1].children[0].children[0].children[0].children[1].children[0].children[0].children[0];
	let buttonsDiv = document.getElementById("daq_device_channel_buttons_" + deviceRowNum);
	let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

	//set id, class, onclick
	newButton.id = "daq_device_channel_button_" + deviceRowNum + "_" + newRowNum;
	newButton.classList.add("daq_device_channel_button_group_" + deviceRowNum);
	newButton.children[0].innerHTML = "Channel";
	//newButton.setAttribute('onclick','exper_daqSwitchToChannel(' + deviceRowNum + ', ' + newRowNum +');');
	
	buttonsDiv.append(newButton);

	//Switch to new Channel
	exper_daqSwitchToChannel(deviceRowNum, newRowNum);

	//Fix Buttons
	exper_fixButtonsAndDivs();
	

}







function exper_daqRenameChannelButton(deviceRowNum, channelRowNum){
	let channelRow = document.getElementById("daq_device_channel_" + deviceRowNum + "_" + channelRowNum);

	let headerString = findSubNode(channelRow, "channelHeader").value;
	let otherHeaderString = findSubNode(channelRow, "otherChannelHeader").value;
	let channelString = findSubNode(channelRow, "channelNum").value;
	let unitString = findSubNode(channelRow, "channelUnit").value;
	let newButtonString = "";

	if(headerString == "Other"){
		if(otherHeaderString != ""){
			headerString = otherHeaderString
		}
	}
	
	if(headerString == "Time" && channelString == ""){
	
		if(unitString != ""){
			newButtonString = headerString + " - " + unitString;
		}else{
			newButtonString = headerString;
		}
	
	}else{

		if(channelString != ""){
			newButtonString = channelString + " - " + headerString;
		}else{
			newButtonString = headerString;
		}
	
	}

	let changeButton = document.getElementById("daq_device_channel_button_" + deviceRowNum + "_" + channelRowNum);
	changeButton.children[0].innerHTML = newButtonString;
	
	//exper_updateDAQHeaderInputs(deviceRowNum, channelRowNum); 20240620 JMA Wrong Place?
}

















function oldexper_daqRenameChannelButton(deviceRowNum, channelRowNum){
	let channelRow = document.getElementById("daq_device_channel_" + deviceRowNum + "_" + channelRowNum);
	let headerString = channelRow.children[0].children[0].children[0].children[0].children[1].value;
	let channelString = channelRow.children[0].children[3].children[0].children[0].children[1].value;
	
	let newButtonString = "";
	if(channelString != ""){
		newButtonString = channelString + " - " + headerString;
	}else{
		newButtonString = headerString;
	}
	
	let changeButton = document.getElementById("daq_device_channel_button_" + deviceRowNum + "_" + channelRowNum);
	changeButton.children[0].innerHTML = newButtonString;
	
	//exper_updateDAQHeaderInputs(deviceRowNum, channelRowNum); 20240620 JMA Wrong Place?
}

function exper_daqHandleChannelHeaderChange(deviceRowNum, channelRowNum){
	let channelRow = document.getElementById("daq_device_channel_" + deviceRowNum + "_" + channelRowNum);
	let channelHeader = findSubNode(channelRow, "channelHeader");
	let otherChannelHeader = findSubNode(channelRow, "otherChannelHeader");
	let otherChannelHeaderHolder = findSubNode(channelRow, "otherChannelHeaderHolder");
	
	otherChannelHeader.value = "";
	if(channelHeader.value == "Other"){
		otherChannelHeaderHolder.style.display = "inline";
	}else{
		otherChannelHeaderHolder.style.display = "none";
	}
	
	exper_updateDAQHeaderInputs(deviceRowNum, channelRowNum); //JMA 20240620 Moved here. Works.
}










































function exper_updateDAQHeaderInputs(deviceNum, channelNum){
	let headerRow = document.getElementById("daq_device_channel_" + deviceNum + "_" + channelNum);
	let headerButton = document.getElementById("daq_device_channel_button_" + deviceNum + "_" + channelNum);
	
	/*
	console.log("headerRow");
	console.log(headerRow);
	console.log("headerButton");
	console.log(headerButton);
	*/

	exper_updateDAQHeaderInputs_with_div(headerRow, headerButton);
}

function exper_updateDAQHeaderInputs_with_div(headerRow, headerButton){

	let headerType = findSubNode(headerRow, "channelHeader").value;
	
	let spec_a_holder = findSubNode(headerRow, "specAHolder");
	let spec_b_holder = findSubNode(headerRow, "specBHolder");
	let unit_holder = findSubNode(headerRow, "unitHolder");
	
	//Loop through experimental_data_fields to find values for new selects
	var spec_a_vals = [];
	var spec_b_vals = [];
	var unit_vals = [];

	dataFields.forEach((df) => {
		if(df.headerType == headerType){
			if(df.fieldVal == "spec_a"){
				spec_a_vals.push(df.selectVal);
			}
			if(df.fieldVal == "spec_b"){
				spec_b_vals.push(df.selectVal);
			}
			if(df.fieldVal == "unit"){
				unit_vals.push(df.selectVal);
			}
		}
	});
	
	//Set up spec_a select
	spec_a_holder.innerHTML = "";
	if(spec_a_vals.length > 0){
		//Create select
		var spec_a_select = document.createElement("select");
		spec_a_select.classList.add("formControl");
		spec_a_select.classList.add("formSelect");
		spec_a_select.id = "specifierA";
		spec_a_holder.appendChild(spec_a_select);
		
		var blankOption = document.createElement("option");
		blankOption.value = "";
		blankOption.text = "Select...";
		spec_a_select.appendChild(blankOption);
		
		for (var i = 0; i < spec_a_vals.length; i++) {
			var option = document.createElement("option");
			option.value = spec_a_vals[i];
			option.text = spec_a_vals[i];
			spec_a_select.appendChild(option);
		}
	}else{
		var spec_a_input = document.createElement("input");
		spec_a_input.setAttribute('type', 'text');
		spec_a_input.classList.add("formControl");
		spec_a_input.id = "specifierA";
		spec_a_holder.appendChild(spec_a_input);
	}

	//Set up spec_b select
	spec_b_holder.innerHTML = "";
	if(spec_b_vals.length > 0){
		//Create select
		var spec_b_select = document.createElement("select");
		spec_b_select.classList.add("formControl");
		spec_b_select.classList.add("formSelect");
		spec_b_select.id = "specifierB";
		spec_b_holder.appendChild(spec_b_select);
		
		var blankOption = document.createElement("option");
		blankOption.value = "";
		blankOption.text = "Select...";
		spec_b_select.appendChild(blankOption);
		
		for (var i = 0; i < spec_b_vals.length; i++) {
			var option = document.createElement("option");
			option.value = spec_b_vals[i];
			option.text = spec_b_vals[i];
			spec_b_select.appendChild(option);
		}
	}else{
		var spec_b_input = document.createElement("input");
		spec_b_input.setAttribute('type', 'text');
		spec_b_input.classList.add("formControl");
		spec_b_input.id = "specifierB";
		spec_b_holder.appendChild(spec_b_input);
	}
	
	//Set up unit select
	unit_holder.innerHTML = "";
	if(unit_vals.length > 0){
		//Create select
		var unit_select = document.createElement("select");
		unit_select.classList.add("formControl");
		unit_select.classList.add("formSelect");
		unit_select.id = "channelUnit";
		unit_holder.appendChild(unit_select);
		
		var blankOption = document.createElement("option");
		blankOption.value = "";
		blankOption.text = "Select...";
		unit_select.appendChild(blankOption);
		
		for (var i = 0; i < unit_vals.length; i++) {
			var option = document.createElement("option");
			option.value = unit_vals[i];
			option.text = unit_vals[i];
			unit_select.appendChild(option);
		}
	}else{
		var unit_input = document.createElement("input");
		unit_input.setAttribute('type', 'text');
		unit_input.classList.add("formControl");
		unit_input.id = "channelUnit";
		unit_holder.appendChild(unit_input);
	}
	
	//Fix Buttons
	exper_fixButtonsAndDivs();
	
}













































function exper_daqSwitchToChannel(deviceRowNum, channelRowNum){

	//console.log("deviceRowNum: " + deviceRowNum + " channelRowNum: " + channelRowNum);
	
	// Get all elements with class="tabcontent" and hide them
	divs = document.getElementsByClassName("daq_device_channel_group_" + deviceRowNum);
	for (i = 0; i < divs.length; i++) {
		divs[i].style.display = "none";
	}

	// Get all buttons with class and remove the class "straboRedSelectedButton"
	buttons = document.getElementsByClassName("daq_device_channel_button_group_" + deviceRowNum);
	for (i = 0; i < buttons.length; i++) {
		buttons[i].className = buttons[i].className.replace(" straboRedSelectedButton", "");
	}

	document.getElementById("daq_device_channel_" + deviceRowNum + "_" + channelRowNum).style.display = "block";
	document.getElementById("daq_device_channel_button_" + deviceRowNum + "_" + channelRowNum).classList.add("straboRedSelectedButton");
}

function exper_deleteDAQDeviceChannel(deviceRowNum, channelRowNum){
	
	if (confirm("Are you sure you want to delete this Channel?\nThis cannot be undone.") == true) {
		
		
		
		//Get max row to confirm what to select next
		let maxRow = document.getElementById("daq_device_channel_buttons_" + deviceRowNum).children.length - 1;
		let moveRow = channelRowNum + 1;
		if(moveRow > maxRow) moveRow = channelRowNum - 1;
		if(moveRow >= 0) exper_daqSwitchToChannel(deviceRowNum, moveRow);
		
		console.log("deviceRowNum: " + deviceRowNum + " channelRowNum: " + channelRowNum + " moveRow: " + moveRow);
		
		channelRow = document.getElementById("daq_device_channel_" + deviceRowNum + "_" + channelRowNum);
		channelRow.remove();
		
		buttonRow = document.getElementById("daq_device_channel_button_" + deviceRowNum + "_" + channelRowNum);
		buttonRow.remove();
		
		//Select different one here
		
		
		//Fix Buttons
		exper_fixButtonsAndDivs();
	}
}

function exper_moveDAQDeviceChannelUp(deviceRowNum, rowNum){

	exper_moveItemUpDown("daq_device_channels_" + deviceRowNum, rowNum, "up");
	exper_moveItemUpDown("daq_device_channel_buttons_" + deviceRowNum, rowNum, "up");

	//Fix Buttons
	exper_fixButtonsAndDivs();

}

function exper_moveDAQDeviceChannelDown(deviceRowNum, rowNum){

	exper_moveItemUpDown("daq_device_channels_" + deviceRowNum, rowNum, "down");
	exper_moveItemUpDown("daq_device_channel_buttons_" + deviceRowNum, rowNum, "down");

	//Fix Buttons
	exper_fixButtonsAndDivs();

}

//data
function exper_addDAQDeviceChannelData(deviceRowNum, channelRowNum){
	let deviceRow = document.getElementById("daq_devices").children[deviceRowNum];

	let channelRow = deviceRow.children[0].children[1].children[0].children[0].children[0].children[1].children[0].children[1].children[0].children[channelRowNum];

	let existingDiv = channelRow.children[0].children[12].children[0].children[0].children[0].children[1].children[0];

	let existingRows = existingDiv.children;
	
	//Figure out what row we're on
	var newRowNum = existingRows.length + 1;

	let sourceDiv = document.getElementById("sourceDataRow");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "daqDeviceChannelDataRow-" + deviceRowNum + "-" + channelRowNum + "-" + newRowNum;
	
	//some debug data
	//newDiv.children[0].children[0].children[0].value = getRandomInt(1111,9999);
	//newDiv.children[0].children[1].children[0].value = getRandomInt(1111,9999);

	existingDiv.appendChild(newDiv);
	
	//Fix Buttons
	exper_fixButtonsAndDivs();
}

function exper_moveDAQDeviceChannelDataUp(deviceRowNum, channelRowNum, rowNum){

	exper_moveItemUpDown("daq_device_channel_datas_" + deviceRowNum + "_" + channelRowNum, rowNum, "up");

	//Fix Buttons
	exper_fixButtonsAndDivs();
}

function exper_moveDAQDeviceChannelDataDown(deviceRowNum, channelRowNum, rowNum){

	exper_moveItemUpDown("daq_device_channel_datas_" + deviceRowNum + "_" + channelRowNum, rowNum, "down");

	//Fix Buttons
	exper_fixButtonsAndDivs();

}

function exper_deleteDAQDeviceChannelData(deviceRowNum, channelRowNum, dataRowNum){
	//if (confirm("Are you sure you want to delete this Data?\nThis cannot be undone.") == true) {
		let existingDevicesDiv = document.getElementById("daq_devices");
		let existingDevicesRows = existingDevicesDiv.children;
		let deviceRow = existingDevicesRows[deviceRowNum];
		let channelRow = deviceRow.children[0].children[1].children[0].children[0].children[0].children[1].children[0].children[1].children[0].children[channelRowNum];
		let dataRow = channelRow.children[0].children[12].children[0].children[0].children[0].children[1].children[0].children[dataRowNum];
		dataRow.remove();
		//Fix Buttons
		exper_fixButtonsAndDivs();
	//}
}

//document
function exper_addDAQDeviceDocument(deviceRowNum){
	//console.log(deviceRowNum);
	//let deviceRow = document.getElementById("daq_devices").children[deviceRowNum];
	
	//let existingDiv = deviceRow.children[0].children[1].children[0].children[0].children[1].children[1].children[0];
	let existingDiv = document.getElementById("daq_device_documents_" + deviceRowNum);
	let existingRows = existingDiv.children;
	
	//Figure out what row we're on
	var newRowNum = existingRows.length + 1;

	let sourceDiv = document.getElementById("sourceDocumentRow");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "daq_device_document_" + deviceRowNum + "_" + newRowNum;
	
	//Set UUID
	//console.log(newDiv);
	let uuidNode = newDiv.children[0].children[0].children[3].children[0].children[2];
	uuidNode.value = uuidv4();
	//console.log(uuidNode);

	existingDiv.appendChild(newDiv);

	//Also add button to sideBar
	let buttonsDiv = document.getElementById("daq_device_document_buttons_" + deviceRowNum);
	let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

	//set id, class, onclick
	newButton.id = "daq_device_document_button_" + deviceRowNum + "_" + newRowNum;
	newButton.classList.add("daq_device_document_button_group_" + deviceRowNum);
	newButton.children[0].innerHTML = "Manual";
	newButton.setAttribute('onclick','exper_daqSwitchToDocument(' + deviceRowNum + ', ' + newRowNum +');');
	
	buttonsDiv.append(newButton);
	
	exper_daqSwitchToDocument(deviceRowNum, newRowNum);

	//Fix Buttons
	exper_fixButtonsAndDivs();
}

function exper_daqRenameDocumentButton(deviceRowNum, documentRowNum){
	let documentRow = document.getElementById("daq_device_document_" + deviceRowNum + "_" + documentRowNum);

	let newButtonString = documentRow.children[0].children[0].children[0].children[0].children[1].value;
	let changeButton = document.getElementById("daq_device_document_button_" + deviceRowNum + "_" + documentRowNum);
	changeButton.children[0].innerHTML = newButtonString;
}

function exper_daqSwitchToDocument(deviceRowNum, documentRowNum){

	// Get all elements with class="tabcontent" and hide them
	divs = document.getElementsByClassName("daq_device_document_group_" + deviceRowNum);
	for (i = 0; i < divs.length; i++) {
		divs[i].style.display = "none";
	}

	// Get all buttons with class and remove the class "straboRedSelectedButton"
	buttons = document.getElementsByClassName("daq_device_document_button_group_" + deviceRowNum);
	for (i = 0; i < buttons.length; i++) {
		buttons[i].className = buttons[i].className.replace(" straboRedSelectedButton", "");
	}
	
	document.getElementById("daq_device_document_" + deviceRowNum + "_" + documentRowNum).style.display = "block";
	document.getElementById("daq_device_document_button_" + deviceRowNum + "_" + documentRowNum).classList.add("straboRedSelectedButton");
}

function exper_deleteDAQDeviceDocument(deviceRowNum, channelRowNum){
	if (confirm("Are you sure you want to delete this Document?\nThis cannot be undone.") == true) {

		//Get max row to confirm what to select next
		let maxRow = document.getElementById("daq_device_document_buttons_" + deviceRowNum).children.length - 1;
		let moveRow = channelRowNum + 1;
		if(moveRow > maxRow) moveRow = channelRowNum - 1;
		if(moveRow >= 0) exper_daqSwitchToDocument(deviceRowNum, moveRow);

		document.getElementById("daq_device_document_" + deviceRowNum + "_" + channelRowNum).remove();
		document.getElementById("daq_device_document_button_" + deviceRowNum + "_" + channelRowNum).remove();

		//Fix Buttons
		exper_fixButtonsAndDivs();

	}
}

function exper_moveDAQDeviceDocumentUp(deviceRowNum, rowNum){

	exper_moveItemUpDown("daq_device_documents_" + deviceRowNum, rowNum, "up");
	exper_moveItemUpDown("daq_device_document_buttons_" + deviceRowNum, rowNum, "up");

	//Fix Buttons
	exper_fixButtonsAndDivs();
	
}


function exper_moveDAQDeviceDocumentDown(deviceRowNum, rowNum){

	exper_moveItemUpDown("daq_device_documents_" + deviceRowNum, rowNum, "down");
	exper_moveItemUpDown("daq_device_document_buttons_" + deviceRowNum, rowNum, "down");

	//Fix Buttons
	exper_fixButtonsAndDivs();
	
}

function exper_moveItemUpDown(divId, rowNum, upDown = "up"){

	//Create tempDiv, holdingDiv
	let tempDiv = document.createElement("div");
	let holdingDiv = document.createElement("div");
	let existingRows = document.getElementById(divId);

	let numRows = existingRows.children.length;
	let toPos = 15;
	if(upDown == "down"){
		toPos = rowNum + 1;
	}else{
		toPos = rowNum - 1;
	}

	//First Document Rows
	for(let x = 0; x < numRows; x++){
		tempDiv.append(existingRows.children[0]);
	}
	
	holdingDiv.append(tempDiv.children[rowNum])
	
	for(x = 0; x < numRows; x++){
		if(x == toPos){
			existingRows.append(holdingDiv.children[0]);
		}else{
			existingRows.append(tempDiv.children[0]);
		}
	}
	
	if(existingRows.children.length < numRows){
		existingRows.append(tempDiv.children[0]);
	}

}

function exper_moveItemUpDownWithDiv(existingRows, rowNum, upDown = "up"){

	//Create tempDiv, holdingDiv
	let tempDiv = document.createElement("div");
	let holdingDiv = document.createElement("div");

	let numRows = existingRows.children.length;
	let toPos = 15;
	if(upDown == "down"){
		toPos = rowNum + 1;
	}else{
		toPos = rowNum - 1;
	}

	//First Document Rows
	for(let x = 0; x < numRows; x++){
		tempDiv.append(existingRows.children[0]);
	}
	
	holdingDiv.append(tempDiv.children[rowNum])
	
	for(x = 0; x < numRows; x++){
		if(x == toPos){
			existingRows.append(holdingDiv.children[0]);
		}else{
			existingRows.append(tempDiv.children[0]);
		}
	}
	
	if(existingRows.children.length < numRows){
		existingRows.append(tempDiv.children[0]);
	}

}

// *********************************************************************************
// *********************************************************************************
// ***************************   Sample   ******************************************
// *********************************************************************************
// *********************************************************************************


function exper_CloseSampleModal() {
	let modal = document.getElementById("sampleModal");
	modal.style.display = "none";
}

function exper_openSampleModal() {
	let modal = document.getElementById("sampleModal");
	modal.style.display = "inline";
}

function exper_doCancelSampleEdit() {
	sampleData = tempSampleData;
	//console.log(sampleData);
	populateSampleDataToInterface(sampleData);
	exper_populateSampleData();
	document.getElementById("sampleModalBox").scrollTop = 0;
	exper_CloseSampleModal();
	exper_fixButtonsAndDivs();
	fixAllButtons();
}

function exper_doEditSample() {
	//copy current daq data to temp
	console.log("edit sample");
	tempSampleData = sampleData;
	
	//JMA 2023-10-03
	exper_clearSampleInterface();
	populateSampleDataToInterface(sampleData);
	
	
	
	exper_openSampleModal();
	exper_fixButtonsAndDivs();
	fixAllButtons();
}

function exper_checkForSampleSubmitErrors(){
	var error = "";
	let sampleModal = document.getElementById("sampleModalBox");

	if(findSubNode(sampleModal, "sampleName").value=="") error += "Sample Name Cannot be Blank.\n";
	if(findSubNode(sampleModal, "sampleID").value=="") error += "Sample ID Cannot be Blank.\n";
	if(findSubNode(sampleModal, "materialType").value=="") error += "Material Type Cannot be Blank.\n";
	if(findSubNode(sampleModal, "materialName").value=="") error += "Material Name Cannot be Blank.\n";

	let sampleDocuments = findSubNode(sampleModal, "sample_documents").children;
	if(sampleDocuments != null){
		if(sampleDocuments.length > 0){
			for(let x = 0; x < sampleDocuments.length; x++){
				let fileNum = x + 1;
				let docFile = findSubNode(sampleDocuments[x], "docFile");
				if(docFile != null){
					if(docFile.value == "") error += "No file chosen for sample document " + fileNum + ".\n";
				}
			}
		}
	}
	return error;
}

function exper_doSaveSampleInfo() {
	var error = exper_checkForSampleSubmitErrors();
	
	if(error != ""){
		alert(error);
	}else{
		exper_buildSampleData();
		exper_populateSampleData();
		document.getElementById("sampleModalBox").scrollTop = 0;
		exper_CloseSampleModal();
		exper_fixDownloadButton();
	}
}

function exper_populateSampleData() {
	if(sampleData != null){
		let sampleDiv = document.getElementById('sampleInfo');
		sampleDiv.innerHTML = '			<div class="formRow"> \
					<div class="formCell expButtonSpacer">\
						<button class="squareButtonSmaller" onclick="exper_doEditSample();"><img title="Edit" src="/experimental/buttonImages/icons8-edit-30.png" width="17" height="17"></button>\
						<button class="squareButtonSmaller" onclick="exper_deleteSampleData();"><img title="Delete" src="/experimental/buttonImages/icons8-trash-30.png" width="17" height="17"></button>\
					</div>\
					<div class="formCell w33">\
						<div class="formPart">\
							<label class="formLabel">Sample Name</label>\
							<div>'+sampleData.name+'</div>\
						</div>\
					</div>\
					<div class="formCell w25">\
						<div class="formPart">\
							<label class="formLabel">IGSN</label>\
							<div>'+sampleData.igsn+'</div>\
						</div>\
					</div>\
					<div class="formCell w25">\
						<div class="formPart">\
							<label class="formLabel">Sample ID</label>\
							<div>'+sampleData.id+'</div>\
						</div>\
					</div>\
				</div>';
	
	}
}

function exper_deleteSampleDataRaw() {
	//if (confirm("Are you sure you want to delete Sample Data?\nThis cannot be undone.") == true) {

		exper_clearSampleData();
		
		let sampleInfoDiv = document.getElementById("sampleInfo");
		sampleInfoDiv.innerHTML = '			<div class="formRow">\
			<span class="enterDataSpan">Enter Data: </span>\
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditSample()"><span>Manually </span></button>\
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal(\'sample\');"><span>From Previous Experiment  </span></button>\
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON(\'sample\')"><span>From JSON File </span></button>\
			</div>';
	//}
	
	exper_fixDownloadButton();
}

function exper_deleteSampleData() {
	if (confirm("Are you sure you want to delete Sample Data?\nThis cannot be undone.") == true) {

		exper_clearSampleData();
		
		let sampleInfoDiv = document.getElementById("sampleInfo");
		sampleInfoDiv.innerHTML = '			<div class="formRow">\
			<span class="enterDataSpan">Enter Data: </span>\
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditSample()"><span>Manually </span></button>\
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal(\'sample\');"><span>From Previous Experiment  </span></button>\
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON(\'sample\')"><span>From JSON File </span></button>\
			</div>';
	}
	
	exper_fixDownloadButton();
}

function exper_clearSampleData(){
	sampleData = null;

	let sampleModal = document.getElementById("sampleModalBox");
	sampleModal.innerHTML = "";

	let sourceDiv = document.getElementById("sourceSampleModal");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "";
	sampleModal.append(newDiv);

}

function exper_clearSampleInterface() {
	console.log("clear sample interface");
	
	let sampleModal = document.getElementById("sampleModalBox");
	sampleModal.innerHTML = "";

	let sourceDiv = document.getElementById("sourceSampleModal");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "";
	sampleModal.append(newDiv);

}











function exper_buildSampleData(){
	let sampleModal = document.getElementById("sampleModalBox"); 
	var sample = new Object();
	
	sample.name = findSubNode(sampleModal, "sampleName").value;
	sample.igsn = findSubNode(sampleModal, "sampleIGSN").value;
	sample.id = findSubNode(sampleModal, "sampleID").value;
	sample.description = findSubNode(sampleModal, "sampleDescription").value;
	
	if(findSubNode(sampleModal, "parentSampleName").value != ""){
		var parentSample = new Object();
		parentSample.name = findSubNode(sampleModal, "parentSampleName").value;
		parentSample.igsn = findSubNode(sampleModal, "parentSampleIGSN").value;
		parentSample.id = findSubNode(sampleModal, "parentSampleID").value;
		parentSample.description = findSubNode(sampleModal, "parentSampleDescription").value;
		sample.parent = parentSample;
	}
	
	var sampleMaterial = new Object();
	
	var material = new Object();
	material.type = findSubNode(sampleModal, "materialType").value;
	material.name = findSubNode(sampleModal, "materialName").value;
	material.state = findSubNode(sampleModal, "materialState").value;
	material.note = findSubNode(sampleModal, "materialNote").value;
	sampleMaterial.material = material;
	
	var composition = [];
	let phases = findSubNode(sampleModal, "sample_mineral_phases").children;
	for(let x = 0; x < phases.length; x++){
		var thisPhase = new Object();
		thisPhase.mineral = findSubNode(phases[x], "mineralName").value;
		thisPhase.fraction = findSubNode(phases[x], "mineralFraction").value;
		thisPhase.unit = findSubNode(phases[x], "mineralUnit").value;
		thisPhase.grainsize = findSubNode(phases[x], "mineralGrainSize").value;
		composition.push(thisPhase);
	}
	sampleMaterial.composition = composition;
	
	var provenance = new Object();
	provenance.formation = findSubNode(sampleModal, "formationName").value;
	provenance.member = findSubNode(sampleModal, "memberName").value;
	provenance.submember = findSubNode(sampleModal, "subMemberName").value;
	provenance.source = findSubNode(sampleModal, "sampleSource").value;
	
	var loc = new Object();
	loc.street = findSubNode(sampleModal, "sampleLocationStreet").value;
	loc.building = findSubNode(sampleModal, "sampleLocationBuilding").value;
	loc.postcode = findSubNode(sampleModal, "sampleLocationPostcode").value;
	loc.city = findSubNode(sampleModal, "sampleLocationCity").value;
	loc.state = findSubNode(sampleModal, "sampleLocationState").value;
	loc.country = findSubNode(sampleModal, "sampleLocationCountry").value;
	loc.latitude = findSubNode(sampleModal, "sampleLocationLatitude").value;
	loc.longitude = findSubNode(sampleModal, "sampleLocationLongitude").value;
	provenance.location = loc;
	sampleMaterial.provenance = provenance;
	
	var texture = new Object();
	texture.bedding = findSubNode(sampleModal, "sampleTextureBedding").value;
	texture.lineation = findSubNode(sampleModal, "sampleTextureLineation").value;
	texture.foliation = findSubNode(sampleModal, "sampleTextureFoliation").value;
	texture.fault = findSubNode(sampleModal, "sampleTextureFault").value;
	sampleMaterial.texture = texture;
	
	sample.material = sampleMaterial;
	
	var parameters = [];
	let params = findSubNode(sampleModal, "sample_parameters").children;
	for(let x = 0; x < params.length; x++){
		var thisParam = new Object();
		thisParam.control = findSubNode(params[x], "parameterVariable").value;
		thisParam.other_control = findSubNode(params[x], "otherParameterControl").value;
		thisParam.value = findSubNode(params[x], "parameterValue").value;
		thisParam.unit = findSubNode(params[x], "parameterUnit").value;
		thisParam.prefix = findSubNode(params[x], "parameterPrefix").value;
		thisParam.note = findSubNode(params[x], "parameterNote").value;
		parameters.push(thisParam);
	}
	sample.parameters = parameters;

















	//Documents
	//let documentRows = deviceRows[d].children[0].children[1].children[0].children[0].children[1].children[1].children[0];
	let documentRows = document.getElementById("sample_documents");
	if(documentRows != null){
		var documents = [];
		documentRows = documentRows.children;
		for(docNum = 0; docNum < documentRows.length; docNum++){
			//console.log(documentRows[docNum]);
			var doc = new Object();
			doc.type = documentRows[docNum].children[0].children[0].children[0].children[0].children[1].value;
			doc.other_type = documentRows[docNum].children[0].children[0].children[0].children[0].children[2].children[0].value;
			
			
			doc.format = documentRows[docNum].children[0].children[0].children[1].children[0].children[1].value;
			doc.other_format = documentRows[docNum].children[0].children[0].children[1].children[0].children[2].children[0].value;
			
			//Check to see if this is a file node!!!!
			/*
			let node = documentRows[docNum].children[0].children[0].children[2].children[0].children[1];
			if(node.type=="file"){
				let fullPath = node.value;
				doc.path = fullPath.split(/[\\\/]/).pop();
			}else{
				//get filename from id filename
				doc.path = findSubNode(node, "filename").innerHTML;
			}
			*/
			
			doc.path = findSubNode(documentRows[docNum],"originalFilename").value;
			
			console.log("doc path: " + doc.path);
			
			doc.id = documentRows[docNum].children[0].children[0].children[3].children[0].children[1].value;
			doc.uuid = documentRows[docNum].children[0].children[0].children[3].children[0].children[2].value;
			
			doc.description = documentRows[docNum].children[0].children[1].children[0].children[0].children[1].value;
			
			documents.push(doc);
		}
		if(documents.length > 0) sample.documents = documents;
	}

	sampleData = sample;
	console.log(sampleData);
}

function exper_updateSampleMaterialNameInput(){
	
	let materialNameHolder = document.getElementById("materialNameHolder");
	removeAllChildNodes(materialNameHolder);
	let materialType = document.getElementById("materialType").value;
	
	if(materialType == "Glass" || materialType == "Ice" || materialType == "Ceramic" || materialType == "Plastic" || materialType == "Metal"){
		let newTextNode = document.getElementById("sourceTextInput").cloneNode(true);
		newTextNode.id = "materialName";
		//newTextNode.value = materialType;
		materialNameHolder.append(newTextNode);
		document.getElementById("materialNameLabel").innerHTML = "Name";
	}else if(materialType == "Soil"){
		let newNode = document.getElementById("sourceSelectSoil").cloneNode(true);
		newNode.id = "materialName";
		materialNameHolder.append(newNode);
		document.getElementById("materialNameLabel").innerHTML = "Soil Type";
	}else if(materialType == "Mineral"){
		let newNode = document.getElementById("sourceSelectMineral").cloneNode(true);
		newNode.id = "materialName";
		materialNameHolder.append(newNode);
		document.getElementById("materialNameLabel").innerHTML = "Mineral Name";
	}else if(materialType == "Igneous Rock"){
		let newNode = document.getElementById("sourceSelectIgneous").cloneNode(true);
		newNode.id = "materialName";
		materialNameHolder.append(newNode);
		document.getElementById("materialNameLabel").innerHTML = "Igneous Rock";
	}else if(materialType == "Sedimentary Rock"){
		let newNode = document.getElementById("sourceSelectSedimentary").cloneNode(true);
		newNode.id = "materialName";
		materialNameHolder.append(newNode);
		document.getElementById("materialNameLabel").innerHTML = "Sedimentary Rock";
	}else if(materialType == "Metamorphic Rock"){
		let newNode = document.getElementById("sourceSelectMetamorphic").cloneNode(true);
		newNode.id = "materialName";
		materialNameHolder.append(newNode);
		document.getElementById("materialNameLabel").innerHTML = "Metamorphic Rock";
	}else if(materialType == "Epos Lithologies"){
		let newNode = document.getElementById("sourceSelectEpos").cloneNode(true);
		newNode.id = "materialName";
		materialNameHolder.append(newNode);
		document.getElementById("materialNameLabel").innerHTML = "Epos Lithology";
	}else if(materialType == "Standards"){
		let newNode = document.getElementById("sourceSelectStandards").cloneNode(true);
		newNode.id = "materialName";
		materialNameHolder.append(newNode);
		document.getElementById("materialNameLabel").innerHTML = "Lab Standard";
	}else if(materialType == "Commodity"){
		let newNode = document.getElementById("sourceSelectCommodity").cloneNode(true);
		newNode.id = "materialName";
		materialNameHolder.append(newNode);
		document.getElementById("materialNameLabel").innerHTML = "Commodity Name";
	}
	
}

function exper_addSampleMineralPhase(){
	//console.log(deviceRowNum);
	//let deviceRow = document.getElementById("daq_devices").children[deviceRowNum];
	
	//let existingDiv = deviceRow.children[0].children[1].children[0].children[0].children[1].children[1].children[0];
	let existingDiv = document.getElementById("sample_mineral_phases");
	let existingRows = existingDiv.children;
	
	//Figure out what row we're on
	var newRowNum = existingRows.length + 1;

	let sourceDiv = document.getElementById("sourceSampleMineral");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "sample_mineral_phase_" + newRowNum;
	
	//Add Select to New Div
	let mineralHolder = findSubNode(newDiv, "mineralSelectHolder");
	let newSelect = document.getElementById("sourceSelectMineral").cloneNode(true);
	newSelect.setAttribute('onchange','exper_sampleRenameMineralButton(' + newRowNum +');');
	newSelect.id = "mineralName";
	mineralHolder.append(newSelect);

	existingDiv.appendChild(newDiv);

	//Also add button to sideBar
	let buttonsDiv = document.getElementById("sample_mineral_phase_buttons");
	let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

	//set id, class, onclick
	newButton.id = "sample_mineral_phase_button_" + newRowNum;
	newButton.classList.add("sample_mineral_phase_button_group");
	newButton.children[0].innerHTML = "Other";
	newButton.setAttribute('onclick','exper_sampleSwitchToMineral(' + newRowNum +');');
	
	buttonsDiv.append(newButton);
	
	exper_sampleSwitchToMineral(newRowNum);

	//Fix Buttons
	exper_fixButtonsAndDivs();
}

function exper_sampleRenameMineralButton(rowNum){
	let mineralRow = document.getElementById("sample_mineral_phase_" + rowNum);
	let newButtonString = findSubNode(mineralRow, "mineralName").value;
	let changeButton = document.getElementById("sample_mineral_phase_button_" + rowNum);
	changeButton.children[0].innerHTML = newButtonString;
}

function exper_sampleSwitchToMineral(rowNum){

	// Get all elements with class="tabcontent" and hide them
	divs = document.getElementsByClassName("sample_mineral_phase_group");
	for (i = 0; i < divs.length; i++) {
		divs[i].style.display = "none";
	}

	// Get all buttons with class and remove the class "straboRedSelectedButton"
	buttons = document.getElementsByClassName("sample_mineral_phase_button_group");
	for (i = 0; i < buttons.length; i++) {
		buttons[i].className = buttons[i].className.replace(" straboRedSelectedButton", "");
	}
	
	document.getElementById("sample_mineral_phase_" + rowNum).style.display = "block";
	document.getElementById("sample_mineral_phase_button_" + rowNum).classList.add("straboRedSelectedButton");
}

function exper_deleteSampleMineralPhase(rowNum){
	//if (confirm("Are you sure you want to delete this phase?\nThis cannot be undone.") == true) {

		//Get max row to confirm what to select next
		let maxRow = document.getElementById("sample_mineral_phase_buttons").children.length - 1;
		let moveRow = rowNum + 1;
		if(moveRow > maxRow) moveRow = rowNum - 1;
		if(moveRow >= 0) exper_sampleSwitchToMineral(moveRow);

		document.getElementById("sample_mineral_phase_" + rowNum).remove();
		document.getElementById("sample_mineral_phase_button_" + rowNum).remove();

		//Fix Buttons
		exper_fixButtonsAndDivs();

	//}
}

function exper_moveSampleMineralPhaseUp(rowNum){

	exper_moveItemUpDown("sample_mineral_phases", rowNum, "up");
	exper_moveItemUpDown("sample_mineral_phase_buttons", rowNum, "up");

	//Fix Buttons
	exper_fixButtonsAndDivs();
	
}


function exper_moveSampleMineralPhaseDown(rowNum){

	exper_moveItemUpDown("sample_mineral_phases", rowNum, "down");
	exper_moveItemUpDown("sample_mineral_phase_buttons", rowNum, "down");

	//Fix Buttons
	exper_fixButtonsAndDivs();
	
}

// ************* sample parameters

function exper_addSampleParameter(){

	let existingDiv = document.getElementById("sample_parameters");
	let existingRows = existingDiv.children;
	
	//Figure out what row we're on
	var newRowNum = existingRows.length + 1;

	let sourceDiv = document.getElementById("sourceSampleParameter");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "sample_parameter_" + newRowNum;

	existingDiv.appendChild(newDiv);

	//Also add button to sideBar
	let buttonsDiv = document.getElementById("sample_parameter_buttons");
	let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

	//set id, class, onclick
	newButton.id = "sample_parameter_button_" + newRowNum;
	newButton.classList.add("sample_parameter_button_group");
	newButton.children[0].innerHTML = "Weight";
	newButton.setAttribute('onclick','exper_sampleSwitchToParameter(' + newRowNum +');');
	
	buttonsDiv.append(newButton);
	
	exper_sampleSwitchToParameter(newRowNum);

	//Fix Buttons
	exper_fixButtonsAndDivs();
}

function exper_sampleRenameParameterButton(rowNum){
	let row = document.getElementById("sample_parameter_" + rowNum);
	let newButtonString = findSubNode(row, "parameterVariable").value;
	let changeButton = document.getElementById("sample_parameter_button_" + rowNum);
	changeButton.children[0].innerHTML = newButtonString;
}



function exper_handleSampleVariableChange(rowNum){
	let row = document.getElementById("sample_parameter_" + rowNum);
	let parameterVariable = findSubNode(row, "parameterVariable").value;
	
	let otherParameterControl = findSubNode(row, "otherParameterControl");
	let otherParameterControlHolder = findSubNode(row, "otherParameterControlHolder");
	
	otherParameterControl.value = "";
	if(parameterVariable == "Other"){
		otherParameterControlHolder.style.display = "inline";
	}else{
		otherParameterControlHolder.style.display = "none";
	}
}


function exper_handleSampleDocTypeChange(rowNum){
	let docRow = document.getElementById("sample_document_"+rowNum);
	let docType = findSubNode(docRow,"docType").value;
	let otherDocTypeHolder = findSubNode(docRow,"otherDocTypeHolder");
	let otherDocType = findSubNode(docRow,"otherDocType");
	otherDocType.value = "";
	if(docType == "Other"){
		otherDocTypeHolder.style.display = "inline";
	}else{
		otherDocTypeHolder.style.display = "none";
	}
}

function exper_handleSampleDocFormatChange(rowNum){
	let docRow = document.getElementById("sample_document_"+rowNum);
	let docFormat = findSubNode(docRow,"docFormat").value;
	
	console.log("docFormat: " + docFormat);
	
	let otherDocFormatHolder = findSubNode(docRow,"otherDocFormatHolder");
	let otherDocFormat = findSubNode(docRow,"otherDocFormat");
	otherDocFormat.value = "";
	if(docFormat == "Other"){
		otherDocFormatHolder.style.display = "inline";
	}else{
		otherDocFormatHolder.style.display = "none";
	}
}







function exper_sampleSwitchToParameter(rowNum){

	// Get all elements with class="tabcontent" and hide them
	divs = document.getElementsByClassName("sample_parameter_group");
	for (i = 0; i < divs.length; i++) {
		divs[i].style.display = "none";
	}

	// Get all buttons with class and remove the class "straboRedSelectedButton"
	buttons = document.getElementsByClassName("sample_parameter_button_group");
	for (i = 0; i < buttons.length; i++) {
		buttons[i].className = buttons[i].className.replace(" straboRedSelectedButton", "");
	}
	
	document.getElementById("sample_parameter_" + rowNum).style.display = "block";
	document.getElementById("sample_parameter_button_" + rowNum).classList.add("straboRedSelectedButton");
}

function exper_deleteSampleParameter(rowNum){
	//if (confirm("Are you sure you want to delete this phase?\nThis cannot be undone.") == true) {

		//Get max row to confirm what to select next
		let maxRow = document.getElementById("sample_parameter_buttons").children.length - 1;
		let moveRow = rowNum + 1;
		if(moveRow > maxRow) moveRow = rowNum - 1;
		if(moveRow >= 0) exper_sampleSwitchToParameter(moveRow);

		document.getElementById("sample_parameter_" + rowNum).remove();
		document.getElementById("sample_parameter_button_" + rowNum).remove();
		exper_fixButtonsAndDivs();

	//}
}

function exper_moveSampleParameterUp(rowNum){

	exper_moveItemUpDown("sample_parameters", rowNum, "up");
	exper_moveItemUpDown("sample_parameter_buttons", rowNum, "up");
	exper_fixButtonsAndDivs();
	
}


function exper_moveSampleParameterDown(rowNum){

	exper_moveItemUpDown("sample_parameters", rowNum, "down");
	exper_moveItemUpDown("sample_parameter_buttons", rowNum, "down");
	exper_fixButtonsAndDivs();
	
}

// ********** sample documents

function exper_addSampleDocument(){

	let existingDiv = document.getElementById("sample_documents");
	let existingRows = existingDiv.children;
	
	//Figure out what row we're on
	var newRowNum = existingRows.length + 1;

	let sourceDiv = document.getElementById("sourceDocumentRow");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "sample_document_" + newRowNum;
	
	//Set UUID
	findSubNode(newDiv, "uuid").value = uuidv4();

	existingDiv.appendChild(newDiv);

	//Also add button to sideBar
	let buttonsDiv = document.getElementById("sample_document_buttons");
	let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

	//set id, class, onclick
	newButton.id = "sample_document_button_" + newRowNum;
	newButton.classList.add("sample_document_button_group");
	newButton.children[0].innerHTML = "Manual";
	newButton.setAttribute('onclick','exper_sampleSwitchToDocument(' + newRowNum +');');
	
	buttonsDiv.append(newButton);
	
	exper_sampleSwitchToDocument(newRowNum);

	//Fix Buttons
	exper_fixButtonsAndDivs();
}

function exper_sampleRenameDocumentButton(rowNum){
	let row = document.getElementById("sample_document_" + rowNum);
	let newButtonString = findSubNode(row, "docType").value;
	let changeButton = document.getElementById("sample_document_button_" + rowNum);
	changeButton.children[0].innerHTML = newButtonString;
}

function exper_sampleSwitchToDocument(rowNum){

	// Get all elements with class="tabcontent" and hide them
	divs = document.getElementsByClassName("sample_document_group");
	for (i = 0; i < divs.length; i++) {
		divs[i].style.display = "none";
	}

	// Get all buttons with class and remove the class "straboRedSelectedButton"
	buttons = document.getElementsByClassName("sample_document_button_group");
	for (i = 0; i < buttons.length; i++) {
		buttons[i].className = buttons[i].className.replace(" straboRedSelectedButton", "");
	}
	
	document.getElementById("sample_document_" + rowNum).style.display = "block";
	document.getElementById("sample_document_button_" + rowNum).classList.add("straboRedSelectedButton");
}

function exper_deleteSampleDocument(rowNum){
	//if (confirm("Are you sure you want to delete this phase?\nThis cannot be undone.") == true) {

		//Get max row to confirm what to select next
		let maxRow = document.getElementById("sample_document_buttons").children.length - 1;
		let moveRow = rowNum + 1;
		if(moveRow > maxRow) moveRow = rowNum - 1;
		if(moveRow >= 0) exper_sampleSwitchToDocument(moveRow);

		document.getElementById("sample_document_" + rowNum).remove();
		document.getElementById("sample_document_button_" + rowNum).remove();
		exper_fixButtonsAndDivs();

	//}
}

function exper_moveSampleDocumentUp(rowNum){

	exper_moveItemUpDown("sample_documents", rowNum, "up");
	exper_moveItemUpDown("sample_document_buttons", rowNum, "up");
	exper_fixButtonsAndDivs();
	
}


function exper_moveSampleDocumentDown(rowNum){

	exper_moveItemUpDown("sample_documents", rowNum, "down");
	exper_moveItemUpDown("sample_document_buttons", rowNum, "down");
	exper_fixButtonsAndDivs();
	
}


// *********************************************************************************
// *********************************************************************************
// ************************   Experimental Setup   *********************************
// *********************************************************************************
// *********************************************************************************

function exper_doSaveExperimentInfo() {
	var error = exper_checkForExperimentSubmitErrors();
	
	if(error != ""){
		alert(error);
	}else{
		exper_buildExperimentData();
		exper_populateExperimentData();
		document.getElementById("experimentSetupModalBox").scrollTop = 0;
		exper_CloseExperimentSetupModal();
		exper_fixDownloadButton();
	}
}

function exper_populateExperimentData() {
	if(experimentData != null){
		let experimentDiv = document.getElementById('experimentalSetupInfo');
		experimentDiv.innerHTML = '			<div class="formRow"> \
					<div class="formCell expButtonSpacer">\
						<button class="squareButtonSmaller" onclick="exper_doEditExperimentSetup();"><img title="Edit" src="/experimental/buttonImages/icons8-edit-30.png" width="17" height="17"></button>\
						<button class="squareButtonSmaller" onclick="exper_deleteExperimentSetupData();"><img title="Delete" src="/experimental/buttonImages/icons8-trash-30.png" width="17" height="17"></button>\
					</div>\
					<div class="formCell w33">\
						<div class="formPart">\
							<label class="formLabel">Experiment Title</label>\
							<div>'+experimentData.title+'</div>\
						</div>\
					</div>\
					<div class="formCell w25">\
						<div class="formPart">\
							<label class="formLabel">Experiment ID</label>\
							<div>'+experimentData.id+'</div>\
						</div>\
					</div>\
				</div>';
	
	}
}

function exper_checkForExperimentSubmitErrors(){
	var error = "";
	let experimentModal = document.getElementById("experimentSetupModalBox");

	if(findSubNode(experimentModal, "experimentTitle").value=="") error += "Experiment Title Cannot be Blank.\n";

	let experimentDocuments = findSubNode(experimentModal, "experiment_documents").children;
	if(experimentDocuments != null){
		if(experimentDocuments.length > 0){
			for(let x = 0; x < experimentDocuments.length; x++){
				let fileNum = x + 1;
				let docFile = findSubNode(experimentDocuments[x], "docFile");
				if(docFile != null){
					if(docFile.value == "") error += "No file chosen for experiment document " + fileNum + ".\n";
				}
			}
		}
	}
	
	return error;
}

function exper_buildExperimentData(){
	let modal = document.getElementById("experimentSetupModalBox"); 
	var experiment = new Object();
	
	experiment.title = findSubNode(modal, "experimentTitle").value;
	experiment.id = findSubNode(modal, "experimentId").value;
	experiment.ieda = findSubNode(modal, "iedaId").value;
	experiment.start_date = findSubNode(modal, "experimentStartDate").value;
	experiment.end_date = findSubNode(modal, "experimentEndDate").value;
	experiment.description = findSubNode(modal, "experimentDescription").value;

	//features
	const features = [];
	if(findSubNode(modal, "f_loading").checked) features.push("Loading");
	if(findSubNode(modal, "f_unloading").checked) features.push("Unloading");
	if(findSubNode(modal, "f_heating").checked) features.push("Heating");
	if(findSubNode(modal, "f_cooling").checked) features.push("Cooling");
	if(findSubNode(modal, "f_high_temperature").checked) features.push("High Temperature");
	if(findSubNode(modal, "f_ultra-high_temperature").checked) features.push("Ultra-High Temperature");
	if(findSubNode(modal, "f_low_temperature").checked) features.push("Low Temperature");
	if(findSubNode(modal, "f_sub-zero_temperature").checked) features.push("Sub-Zero Temperature");
	if(findSubNode(modal, "f_high_pressure").checked) features.push("High Pressure");
	if(findSubNode(modal, "f_ultra-high_pressure").checked) features.push("Ultra-High Pressure");
	if(findSubNode(modal, "f_hydrostatic_tests").checked) features.push("Hydrostatic Tests");
	if(findSubNode(modal, "f_hip").checked) features.push("HIP");
	if(findSubNode(modal, "f_synthesis").checked) features.push("Synthesis");
	if(findSubNode(modal, "f_deposition_evaporation").checked) features.push("Deposition/Evaporation");
	if(findSubNode(modal, "f_mineral_reactions").checked) features.push("Mineral Reactions");
	if(findSubNode(modal, "f_hydrothermal_reactions").checked) features.push("Hydrothermal Reactions");
	if(findSubNode(modal, "f_elasticity").checked) features.push("Elasticity");
	if(findSubNode(modal, "f_local_axial_strain").checked) features.push("Local Axial Strain");
	if(findSubNode(modal, "f_local_radial_strain").checked) features.push("Local Radial Strain");
	if(findSubNode(modal, "f_elastic_moduli").checked) features.push("Elastic Moduli");
	if(findSubNode(modal, "f_yield_strength").checked) features.push("Yield Strength");
	if(findSubNode(modal, "f_failure_strength").checked) features.push("Failure Strength");
	if(findSubNode(modal, "f_strength").checked) features.push("Strength");
	if(findSubNode(modal, "f_extension").checked) features.push("Extension");
	if(findSubNode(modal, "f_creep").checked) features.push("Creep");
	if(findSubNode(modal, "f_friction").checked) features.push("Friction");
	if(findSubNode(modal, "f_frictional_sliding").checked) features.push("Frictional Sliding");
	if(findSubNode(modal, "f_slide_hold_slide").checked) features.push("Slide Hold Slide");
	if(findSubNode(modal, "f_stepping").checked) features.push("Stepping");
	if(findSubNode(modal, "f_pure_shear").checked) features.push("Pure Shear");
	if(findSubNode(modal, "f_simple_shear").checked) features.push("Simple Shear");
	if(findSubNode(modal, "f_rotary_shear").checked) features.push("Rotary Shear");
	if(findSubNode(modal, "f_torsion").checked) features.push("Torsion");
	if(findSubNode(modal, "f_viscosity").checked) features.push("Viscosity");
	if(findSubNode(modal, "f_indentation").checked) features.push("Indentation");
	if(findSubNode(modal, "f_hardness").checked) features.push("Hardness");
	if(findSubNode(modal, "f_dynamic_tests").checked) features.push("Dynamic Tests");
	if(findSubNode(modal, "f_hydraulic_fracturing").checked) features.push("Hydraulic Fracturing");
	if(findSubNode(modal, "f_hydrothermal_fracturing").checked) features.push("Hydrothermal Fracturing");
	if(findSubNode(modal, "f_shockwave").checked) features.push("Shockwave");
	if(findSubNode(modal, "f_reactive_flow").checked) features.push("Reactive Flow");
	if(findSubNode(modal, "f_pore_fluid_control").checked) features.push("Pore Fluid Control");
	if(findSubNode(modal, "f_pore_fluid_chemistry").checked) features.push("Pore Fluid Chemistry");
	if(findSubNode(modal, "f_pore_volume_compaction").checked) features.push("Pore Volume Compaction");
	if(findSubNode(modal, "f_storage_capacity").checked) features.push("Storage Capacity");
	if(findSubNode(modal, "f_permeability").checked) features.push("Permeability");
	if(findSubNode(modal, "f_steady-state_permeability").checked) features.push("Steady-State Permeability");
	if(findSubNode(modal, "f_transient_permeability").checked) features.push("Transient Permeability");
	if(findSubNode(modal, "f_hydraulic_conductivity").checked) features.push("Hydraulic Conductivity");
	if(findSubNode(modal, "f_drained_undrained_pore_fluid").checked) features.push("Drained/Undrained Pore Fluid");
	if(findSubNode(modal, "f_uniaxial_stress_strain").checked) features.push("Uniaxial Stress/Strain");
	if(findSubNode(modal, "f_biaxial_stress_strain").checked) features.push("Biaxial Stress/Strain");
	if(findSubNode(modal, "f_triaxial_stress_strain").checked) features.push("Triaxial Stress/Strain");
	if(findSubNode(modal, "f_differential_stress").checked) features.push("Differential Stress");
	if(findSubNode(modal, "f_true_triaxial").checked) features.push("True Triaxial");
	if(findSubNode(modal, "f_resistivity").checked) features.push("Resistivity");
	if(findSubNode(modal, "f_electrical_resistivity").checked) features.push("Electrical Resistivity");
	if(findSubNode(modal, "f_electrical_capacitance").checked) features.push("Electrical Capacitance");
	if(findSubNode(modal, "f_streaming_potential").checked) features.push("Streaming Potential");
	if(findSubNode(modal, "f_acoustic_velocity").checked) features.push("Acoustic Velocity");
	if(findSubNode(modal, "f_acoustic_events").checked) features.push("Acoustic Events");
	if(findSubNode(modal, "f_p-wave_velocity").checked) features.push("P-Wave Velocity");
	if(findSubNode(modal, "f_s-wave_velocity").checked) features.push("S-Wave Velocity");
	if(findSubNode(modal, "f_source_location").checked) features.push("Source Location");
	if(findSubNode(modal, "f_tomography").checked) features.push("Tomography");
	if(findSubNode(modal, "f_in-situ_x-ray").checked) features.push("In-Situ X-Ray");
	if(findSubNode(modal, "f_infrared").checked) features.push("Infrared");
	if(findSubNode(modal, "f_raman").checked) features.push("Raman");
	if(findSubNode(modal, "f_visual").checked) features.push("Visual");
	if(findSubNode(modal, "f_other").checked) features.push("Other");
	experiment.features = features;

	let author = {};
	author.firstname = findSubNode(modal, "experimentFirstName").value;
	author.lastname = findSubNode(modal, "experimentLastName").value;
	author.affiliation = findSubNode(modal, "experimentAffiliation").value;
	author.email = findSubNode(modal, "experimentEmail").value;
	author.phone = findSubNode(modal, "experimentPhone").value;
	author.website = findSubNode(modal, "experimentWebsite").value;
	author.id = findSubNode(modal, "experimentORCID").value;
	experiment.author = author;
	
	//geometries
	let geos = [];
	let foundGeometries = findSubNode(modal, "experiment_geometries").children;
	if(foundGeometries.length > 0){
		for(let x = 0; x < foundGeometries.length; x++){
			let geo = {};
			geo.order = findSubNode(foundGeometries[x], "experimentGeometryNum").value;
			geo.type = findSubNode(foundGeometries[x], "experimentGeometryType").value;
			geo.geometry = findSubNode(foundGeometries[x], "experimentGeometryGeometry").value;
			geo.material = findSubNode(foundGeometries[x], "experimentGeometryMaterial").value;
			
			let dimensions = [];
			let foundDimensions = findSubNode(foundGeometries[x], "dimensionRows").children;
			if(foundDimensions.length > 0){
				for(let y = 0; y < foundDimensions.length; y++){
					let dim = {};
					dim.variable = findSubNode(foundDimensions[y], "dimensionVariable").value;
					dim.value = findSubNode(foundDimensions[y], "dimensionValue").value;
					dim.unit = findSubNode(foundDimensions[y], "dimensionUnit").value;
					dim.prefix = findSubNode(foundDimensions[y], "dimensionPrefix").value;
					dim.note = findSubNode(foundDimensions[y], "dimensionNote").value;
					dimensions.push(dim);
				}
			}
			
			geo.dimensions = dimensions;
			

			geos.push(geo);
		}
	}
	experiment.geometry = geos;

	//protocol
	let protocols = [];
	let foundProtocols = findSubNode(modal, "experiment_protocols").children;
	if(foundProtocols.length > 0){
		for(let x = 0; x < foundProtocols.length; x++){
			let prot = {};
			prot.test = findSubNode(foundProtocols[x], "experimentProtocolTest").value;
			prot.objective = findSubNode(foundProtocols[x], "experimentProtocolObjective").value;
			prot.description = findSubNode(foundProtocols[x], "experimentProtocolDescription").value;
			
			let parameters = [];
			let foundParameters = findSubNode(foundProtocols[x], "parameterRows").children;
			if(foundParameters.length > 0){
				for(let y = 0; y < foundParameters.length; y++){
					let param = {};
					param.control = findSubNode(foundParameters[y], "parameterVariable").value;
					param.value = findSubNode(foundParameters[y], "parameterValue").value;
					param.unit = findSubNode(foundParameters[y], "parameterUnit").value;
					param.note = findSubNode(foundParameters[y], "parameterNote").value;
					parameters.push(param);
				}
				prot.parameters = parameters;
			}
			
			protocols.push(prot);
		}
	}
	experiment.protocol = protocols;

	/*
	var documents = [];
	let docs = findSubNode(modal, "experiment_documents").children;
	for(let x = 0; x < docs.length; x++){
		var thisDoc = new Object();
		thisDoc.type = findSubNode(docs[x], "docType").value;
		thisDoc.format = findSubNode(docs[x], "docFormat").value;
		thisDoc.id = findSubNode(docs[x], "docId").value;
		thisDoc.description = findSubNode(docs[x], "docDescription").value;
		thisDoc.uuid = findSubNode(docs[x], "uuid").value;

		if(findSubNode(docs[x], "docFile").type == "file"){
			//get file name from input - remove path first
			var fullPath = findSubNode(docs[x], "docFile").value;
			var fileName = fullPath.split(/[\\\/]/).pop();
		}else{
			//get file name from div
			var fileName = findSubNode(docs[x], "docFile").innerHTML;
		}
		
		thisDoc.path = fileName;

		documents.push(thisDoc);
	}
	experiment.documents = documents;
	*/

	//Documents
	let documentRows = document.getElementById("experiment_documents");
	if(documentRows != null){
		var documents = [];
		documentRows = documentRows.children;
		for(docNum = 0; docNum < documentRows.length; docNum++){
			//console.log(documentRows[docNum]);
			var doc = new Object();
			doc.type = documentRows[docNum].children[0].children[0].children[0].children[0].children[1].value;
			doc.other_type = documentRows[docNum].children[0].children[0].children[0].children[0].children[2].children[0].value;
			
			doc.format = documentRows[docNum].children[0].children[0].children[1].children[0].children[1].value;
			doc.other_format = documentRows[docNum].children[0].children[0].children[1].children[0].children[2].children[0].value;
			
			//Check to see if this is a file node!!!!
			/*
			let node = documentRows[docNum].children[0].children[0].children[2].children[0].children[1];
			if(node.type=="file"){
				let fullPath = node.value;
				doc.path = fullPath.split(/[\\\/]/).pop();
			}else{
				//get filename from id filename
				doc.path = findSubNode(node, "filename").innerHTML;
			}
			*/
			
			doc.path = findSubNode(documentRows[docNum], "originalFilename").value;
			
			console.log("doc path: " + doc.path);
			
			doc.id = documentRows[docNum].children[0].children[0].children[3].children[0].children[1].value;
			doc.uuid = documentRows[docNum].children[0].children[0].children[3].children[0].children[2].value;
			
			doc.description = documentRows[docNum].children[0].children[1].children[0].children[0].children[1].value;
			
			documents.push(doc);
		}
		if(documents.length > 0) experiment.documents = documents;
	}

	if(experiment.id != null){
		document.getElementById("mainExperimentId").value = experiment.id;
	}
	
	experimentData = experiment;
	console.log(experimentData);
}

function exper_CloseExperimentSetupModal() {
	let modal = document.getElementById("experimentSetupModal");
	modal.style.display = "none";
}

function exper_openExperimentSetupModal() {
	let modal = document.getElementById("experimentSetupModal");
	modal.style.display = "inline";
}

function exper_handleExperimentDocTypeChange(rowNum){
	let docRow = document.getElementById("experiment_document_"+rowNum);
	let docType = findSubNode(docRow,"docType").value;
	let otherDocTypeHolder = findSubNode(docRow,"otherDocTypeHolder");
	let otherDocType = findSubNode(docRow,"otherDocType");
	otherDocType.value = "";
	if(docType == "Other"){
		otherDocTypeHolder.style.display = "inline";
	}else{
		otherDocTypeHolder.style.display = "none";
	}
}

function exper_handleExperimentDocFormatChange(rowNum){
	let docRow = document.getElementById("experiment_document_"+rowNum);
	let docFormat = findSubNode(docRow,"docFormat").value;
	
	console.log("docFormat: " + docFormat);
	
	let otherDocFormatHolder = findSubNode(docRow,"otherDocFormatHolder");
	let otherDocFormat = findSubNode(docRow,"otherDocFormat");
	otherDocFormat.value = "";
	if(docFormat == "Other"){
		otherDocFormatHolder.style.display = "inline";
	}else{
		otherDocFormatHolder.style.display = "none";
	}
}

function exper_doCancelExperimentSetupEdit() {
	experimentData = tempExperimentData;
	populateExperimentDataToInterface(experimentData);
	exper_populateExperimentData();
	document.getElementById("experimentSetupModal").scrollTop = 0;
	exper_CloseExperimentSetupModal();
	exper_fixButtonsAndDivs();
	fixAllButtons();
}

function exper_doEditExperimentSetup() {
	//copy current daq data to temp
	console.log("edit experiment");
	tempExperimentData = experimentData;
	exper_clearExperimentSetupInterface();
	populateExperimentDataToInterface(experimentData);
	exper_openExperimentSetupModal();
}

function exper_deleteExpSetupDataraw() {
	//if (confirm("Are you sure you want to delete Experimental Setup Data?\nThis cannot be undone.") == true) {

		exper_clearExperimentSetupData();
		
		let infoDiv = document.getElementById("experimentalSetupInfo");
		infoDiv.innerHTML = '			<div class="formRow">\
				<span class="enterDataSpan">Enter Data: </span>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditExperimentSetup()"><span>Manually </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal(\'experiment\');"><span>From Previous Experiment  </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON(\'experiment\')"><span>From JSON File </span></button>\
			</div>';
	//}
	
	exper_fixDownloadButton();
}

function exper_deleteExperimentSetupData() {
	if (confirm("Are you sure you want to delete Experimental Setup Data?\nThis cannot be undone.") == true) {

		exper_clearExperimentSetupData();
		
		let infoDiv = document.getElementById("experimentalSetupInfo");
		infoDiv.innerHTML = '			<div class="formRow">\
				<span class="enterDataSpan">Enter Data: </span>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditExperimentSetup()"><span>Manually </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal(\'experiment\');"><span>From Previous Experiment  </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON(\'experiment\')"><span>From JSON File </span></button>\
			</div>';
	}
	
	exper_fixDownloadButton();
}

function exper_clearExperimentSetupData(){
	experimentData = null;

	let experimentSetupModal = document.getElementById("experimentSetupModalBox");
	experimentSetupModal.innerHTML = "";

	let sourceDiv = document.getElementById("sourceExperimentSetupModal");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "";
	experimentSetupModal.append(newDiv);

}

function exper_clearExperimentSetupInterface() {
	console.log("clear experiment setup interface");
	
	let experimentSetupModal = document.getElementById("experimentSetupModalBox");
	experimentSetupModal.innerHTML = "";
	
	document.getElementById("experimentId").value = document.getElementById("mainExperimentId").value;

	let sourceDiv = document.getElementById("sourceExperimentSetupModal");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "";
	experimentSetupModal.append(newDiv);

}

function exper_addExperimentGeometry(){

	let existingDiv = document.getElementById("experiment_geometries");

	let existingRows = existingDiv.children;

	//Figure out what row we're on
	var newRowNum = existingRows.length;

	let sourceDiv = document.getElementById("sourceExperimentGeometry");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "experiment_geometry_" + newRowNum;
	newDiv.classList.add("experiment_geometry_group");

	existingDiv.appendChild(newDiv);
	
	//Also add button to sideBar
	let buttonsDiv = document.getElementById("experiment_geometry_buttons");
	let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

	//set id, class, onclick
	newButton.id = "experiment_geometry_button_" + newRowNum;
	newButton.classList.add("experiment_geometry_button_group");
	newButton.children[0].innerHTML = "Sample #1";

	buttonsDiv.append(newButton);

	//Switch to new Channel
	exper_experimentSwitchToGeometry(newRowNum);

	//Fix Buttons
	exper_fixButtonsAndDivs();
}

function exper_experimentSwitchToGeometry(rowNum){

	// Get all elements with class="tabcontent" and hide them
	divs = document.getElementsByClassName("experiment_geometry_group");
	for (i = 0; i < divs.length; i++) {
		divs[i].style.display = "none";
	}

	// Get all buttons with class and remove the class "straboRedSelectedButton"
	buttons = document.getElementsByClassName("experiment_geometry_button_group");
	for (i = 0; i < buttons.length; i++) {
		buttons[i].className = buttons[i].className.replace(" straboRedSelectedButton", "");
	}
	
	document.getElementById("experiment_geometry_" + rowNum).style.display = "block";
	document.getElementById("experiment_geometry_button_" + rowNum).classList.add("straboRedSelectedButton");
}

function exper_experimentRenameGeometryButton(rowNum){
	let row = document.getElementById("experiment_geometry_" + rowNum);
	let selectedNum = findSubNode(row, "experimentGeometryNum").value;
	let selectedType = findSubNode(row, "experimentGeometryType").value;
	let newButtonString = selectedType + " #" + selectedNum;
	
	let changeButton = document.getElementById("experiment_geometry_button_" + rowNum);
	changeButton.children[0].innerHTML = newButtonString;
}

function exper_deleteExperimentGeometry(rowNum){
	if (confirm("Are you sure you want to delete this geometry?\nThis cannot be undone.") == true) {

		//Get max row to confirm what to select next
		let maxRow = document.getElementById("experiment_geometry_buttons").children.length - 1;
		let moveRow = rowNum + 1;
		if(moveRow > maxRow) moveRow = rowNum - 1;
		if(moveRow >= 0) exper_experimentSwitchToGeometry(moveRow);

		document.getElementById("experiment_geometry_" + rowNum).remove();
		document.getElementById("experiment_geometry_button_" + rowNum).remove();
		exper_fixButtonsAndDivs();

	}
}

function exper_moveExperimentGeometryUp(rowNum){

	exper_moveItemUpDown("experiment_geometries", rowNum, "up");
	exper_moveItemUpDown("experiment_geometry_buttons", rowNum, "up");
	exper_fixButtonsAndDivs();
	
}

function exper_moveExperimentGeometryDown(rowNum){

	exper_moveItemUpDown("experiment_geometries", rowNum, "down");
	exper_moveItemUpDown("experiment_geometry_buttons", rowNum, "down");
	exper_fixButtonsAndDivs();
	
}

function exper_addExperimentGeometryDimension(rowNum){
	console.log('add dimension to geometry ' + rowNum);
	let row = document.getElementById("experiment_geometry_" + rowNum);
	let existingDiv = findSubNode(row, "dimensionRows");
	let existingRows = existingDiv.children;

	//Figure out what row we're on
	var newRowNum = existingRows.length;

	let sourceDiv = document.getElementById("sourceExperimentGeometryDimension");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "experiment_geometry_dimension_" + rowNum + "_" + newRowNum;
	//newDiv.classList.add("experiment_geometry_dimension_group_0");

	existingDiv.appendChild(newDiv);
	exper_fixButtonsAndDivs();
}

function exper_deleteExperimentGeometryDimension(geomNum, dimensionNum){
	let geometryRow = document.getElementById("experiment_geometries").children[geomNum];
	let dimensionRows = findSubNode(geometryRow, "dimensionRows").children;
	dimensionRows[dimensionNum].remove();
	exper_fixButtonsAndDivs();
}

function exper_moveExperimentGeometryDimensionDown(geomNum, rowNum){
	let geometryRow = document.getElementById("experiment_geometries").children[geomNum];
	let dimensionRows = findSubNode(geometryRow, "dimensionRows");
	exper_moveItemUpDownWithDiv(dimensionRows, rowNum, "down");
	exper_fixButtonsAndDivs();
}

function exper_moveExperimentGeometryDimensionUp(geomNum, rowNum){
	let geometryRow = document.getElementById("experiment_geometries").children[geomNum];
	let dimensionRows = findSubNode(geometryRow, "dimensionRows");
	exper_moveItemUpDownWithDiv(dimensionRows, rowNum, "up");
	exper_fixButtonsAndDivs();
}

//*********


function exper_getTFOptionsString(){
	var options_str = "";
	if($("#f_loading").is(':checked')) options_str += '<option value="Loading">Loading</option>';
	if($("#f_unloading").is(':checked')) options_str += '<option value="Unloading">Unloading</option>';
	if($("#f_heating").is(':checked')) options_str += '<option value="Heating">Heating</option>';
	if($("#f_cooling").is(':checked')) options_str += '<option value="Cooling">Cooling</option>';
	if($("#f_high_temperature").is(':checked')) options_str += '<option value="High Temperature">High Temperature</option>';
	if($("#f_ultra-high_temperature").is(':checked')) options_str += '<option value="Ultra-High Temperature">Ultra-High Temperature</option>';
	if($("#f_low_temperature").is(':checked')) options_str += '<option value="Low Temperature">Low Temperature</option>';
	if($("#f_sub-zero_temperature").is(':checked')) options_str += '<option value="Sub-Zero Temperature">Sub-Zero Temperature</option>';
	if($("#f_high_pressure").is(':checked')) options_str += '<option value="High Pressure">High Pressure</option>';
	if($("#f_ultra-high_pressure").is(':checked')) options_str += '<option value="Ultra-High Pressure">Ultra-High Pressure</option>';
	if($("#f_hydrostatic_tests").is(':checked')) options_str += '<option value="Hydrostatic Tests">Hydrostatic Tests</option>';
	if($("#f_hip").is(':checked')) options_str += '<option value="HIP">HIP</option>';
	if($("#f_synthesis").is(':checked')) options_str += '<option value="Synthesis">Synthesis</option>';
	if($("#f_deposition_evaporation").is(':checked')) options_str += '<option value="Deposition/Evaporation">Deposition/Evaporation</option>';
	if($("#f_mineral_reactions").is(':checked')) options_str += '<option value="Mineral Reactions">Mineral Reactions</option>';
	if($("#f_hydrothermal_reactions").is(':checked')) options_str += '<option value="Hydrothermal Reactions">Hydrothermal Reactions</option>';
	if($("#f_elasticity").is(':checked')) options_str += '<option value="Elasticity">Elasticity</option>';
	if($("#f_local_axial_strain").is(':checked')) options_str += '<option value="Local Axial Strain">Local Axial Strain</option>';
	if($("#f_local_radial_strain").is(':checked')) options_str += '<option value="Local Radial Strain">Local Radial Strain</option>';
	if($("#f_elastic_moduli").is(':checked')) options_str += '<option value="Elastic Moduli">Elastic Moduli</option>';
	if($("#f_yield_strength").is(':checked')) options_str += '<option value="Yield Strength">Yield Strength</option>';
	if($("#f_failure_strength").is(':checked')) options_str += '<option value="Failure Strength">Failure Strength</option>';
	if($("#f_strength").is(':checked')) options_str += '<option value="Strength">Strength</option>';
	if($("#f_extension").is(':checked')) options_str += '<option value="Extension">Extension</option>';
	if($("#f_creep").is(':checked')) options_str += '<option value="Creep">Creep</option>';
	if($("#f_friction").is(':checked')) options_str += '<option value="Friction">Friction</option>';
	if($("#f_frictional_sliding").is(':checked')) options_str += '<option value="Frictional Sliding">Frictional Sliding</option>';
	if($("#f_slide_hold_slide").is(':checked')) options_str += '<option value="Slide Hold Slide">Slide Hold Slide</option>';
	if($("#f_stepping").is(':checked')) options_str += '<option value="Stepping">Stepping</option>';
	if($("#f_pure_shear").is(':checked')) options_str += '<option value="Pure Shear">Pure Shear</option>';
	if($("#f_simple_shear").is(':checked')) options_str += '<option value="Simple Shear">Simple Shear</option>';
	if($("#f_rotary_shear").is(':checked')) options_str += '<option value="Rotary Shear">Rotary Shear</option>';
	if($("#f_torsion").is(':checked')) options_str += '<option value="Torsion">Torsion</option>';
	if($("#f_viscosity").is(':checked')) options_str += '<option value="Viscosity">Viscosity</option>';
	if($("#f_indentation").is(':checked')) options_str += '<option value="Indentation">Indentation</option>';
	if($("#f_hardness").is(':checked')) options_str += '<option value="Hardness">Hardness</option>';
	if($("#f_dynamic_tests").is(':checked')) options_str += '<option value="Dynamic Tests">Dynamic Tests</option>';
	if($("#f_hydraulic_fracturing").is(':checked')) options_str += '<option value="Hydraulic Fracturing">Hydraulic Fracturing</option>';
	if($("#f_hydrothermal_fracturing").is(':checked')) options_str += '<option value="Hydrothermal Fracturing">Hydrothermal Fracturing</option>';
	if($("#f_shockwave").is(':checked')) options_str += '<option value="Shockwave">Shockwave</option>';
	if($("#f_reactive_flow").is(':checked')) options_str += '<option value="Reactive Flow">Reactive Flow</option>';
	if($("#f_pore_fluid_control").is(':checked')) options_str += '<option value="Pore Fluid Control">Pore Fluid Control</option>';
	if($("#f_pore_fluid_chemistry").is(':checked')) options_str += '<option value="Pore Fluid Chemistry">Pore Fluid Chemistry</option>';
	if($("#f_pore_volume_compaction").is(':checked')) options_str += '<option value="Pore Volume Compaction">Pore Volume Compaction</option>';
	if($("#f_storage_capacity").is(':checked')) options_str += '<option value="Storage Capacity">Storage Capacity</option>';
	if($("#f_permeability").is(':checked')) options_str += '<option value="Permeability">Permeability</option>';
	if($("#f_steady-state_permeability").is(':checked')) options_str += '<option value="Steady-State Permeability">Steady-State Permeability</option>';
	if($("#f_transient_permeability").is(':checked')) options_str += '<option value="Transient Permeability">Transient Permeability</option>';
	if($("#f_hydraulic_conductivity").is(':checked')) options_str += '<option value="Hydraulic Conductivity">Hydraulic Conductivity</option>';
	if($("#f_drained_undrained_pore_fluid").is(':checked')) options_str += '<option value="Drained/Undrained Pore Fluid">Drained/Undrained Pore Fluid</option>';
	if($("#f_uniaxial_stress_strain").is(':checked')) options_str += '<option value="Uniaxial Stress/Strain">Uniaxial Stress/Strain</option>';
	if($("#f_biaxial_stress_strain").is(':checked')) options_str += '<option value="Biaxial Stress/Strain">Biaxial Stress/Strain</option>';
	if($("#f_triaxial_stress_strain").is(':checked')) options_str += '<option value="Triaxial Stress/Strain">Triaxial Stress/Strain</option>';
	if($("#f_differential_stress").is(':checked')) options_str += '<option value="Differential Stress">Differential Stress</option>';
	if($("#f_true_triaxial").is(':checked')) options_str += '<option value="True Triaxial">True Triaxial</option>';
	if($("#f_resistivity").is(':checked')) options_str += '<option value="Resistivity">Resistivity</option>';
	if($("#f_electrical_resistivity").is(':checked')) options_str += '<option value="Electrical Resistivity">Electrical Resistivity</option>';
	if($("#f_electrical_capacitance").is(':checked')) options_str += '<option value="Electrical Capacitance">Electrical Capacitance</option>';
	if($("#f_streaming_potential").is(':checked')) options_str += '<option value="Streaming Potential">Streaming Potential</option>';
	if($("#f_acoustic_velocity").is(':checked')) options_str += '<option value="Acoustic Velocity">Acoustic Velocity</option>';
	if($("#f_acoustic_events").is(':checked')) options_str += '<option value="Acoustic Events">Acoustic Events</option>';
	if($("#f_p-wave_velocity").is(':checked')) options_str += '<option value="P-Wave Velocity">P-Wave Velocity</option>';
	if($("#f_s-wave_velocity").is(':checked')) options_str += '<option value="S-Wave Velocity">S-Wave Velocity</option>';
	if($("#f_source_location").is(':checked')) options_str += '<option value="Source Location">Source Location</option>';
	if($("#f_tomography").is(':checked')) options_str += '<option value="Tomography">Tomography</option>';
	if($("#f_in-situ_x-ray").is(':checked')) options_str += '<option value="In-Situ X-Ray">In-Situ X-Ray</option>';
	if($("#f_infrared").is(':checked')) options_str += '<option value="Infrared">Infrared</option>';
	if($("#f_raman").is(':checked')) options_str += '<option value="Raman">Raman</option>';
	if($("#f_visual").is(':checked')) options_str += '<option value="Visual">Visual</option>';
	if($("#f_other").is(':checked')) options_str += '<option value="Other">Other</option>';
	return options_str;
}

function exper_updateExperimentTestDropdowns(){
	console.log("update dropdowns here");
	
	let options_str = exper_getTFOptionsString();
	
	let existingProtocols = document.getElementById("experiment_protocols").children;
	if(existingProtocols != null && existingProtocols.length > 0){
		for (i = 0; i < existingProtocols.length; i++) {
			let protocol = existingProtocols[i];
			let testDropdown = findSubNode(protocol, "experimentProtocolTest");
			
			let existingVal = testDropdown.value;
			
			testDropdown.options.length = 0;
			testDropdown.innerHTML = options_str;
			
			testDropdown.value = existingVal;
		}
	}
	
	exper_fixButtonsAndDivs();
	
	exper_experimentRenameAllProtocolButtons();
}

function exper_addExperimentProtocol(){

	let existingDiv = document.getElementById("experiment_protocols");

	let existingRows = existingDiv.children;

	//Figure out what row we're on
	var newRowNum = existingRows.length;

	let sourceDiv = document.getElementById("sourceExperimentProtocol");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "experiment_protocol_" + newRowNum;
	newDiv.classList.add("experiment_protocol_group");

	existingDiv.appendChild(newDiv);
	
	//Also add button to sideBar
	let buttonsDiv = document.getElementById("experiment_protocol_buttons");
	let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

	//set id, class, onclick
	newButton.id = "experiment_protocol_button_" + newRowNum;
	newButton.classList.add("experiment_protocol_button_group");
	newButton.children[0].innerHTML = "Step";

	buttonsDiv.append(newButton);

	//Switch to new Channel
	exper_experimentSwitchToProtocol(newRowNum);
	
	//Update Step Drop-downs
	exper_updateExperimentTestDropdowns();

	//Fix Buttons
	exper_fixButtonsAndDivs();
}

function exper_experimentSwitchToProtocol(rowNum){

	// Get all elements with class="tabcontent" and hide them
	divs = document.getElementsByClassName("experiment_protocol_group");
	for (i = 0; i < divs.length; i++) {
		divs[i].style.display = "none";
	}

	// Get all buttons with class and remove the class "straboRedSelectedButton"
	buttons = document.getElementsByClassName("experiment_protocol_button_group");
	for (i = 0; i < buttons.length; i++) {
		buttons[i].className = buttons[i].className.replace(" straboRedSelectedButton", "");
	}
	
	document.getElementById("experiment_protocol_" + rowNum).style.display = "block";
	document.getElementById("experiment_protocol_button_" + rowNum).classList.add("straboRedSelectedButton");
}

function exper_deleteExperimentProtocol(rowNum){
	if (confirm("Are you sure you want to delete this step?\nThis cannot be undone.") == true) {

		//Get max row to confirm what to select next
		let maxRow = document.getElementById("experiment_protocol_buttons").children.length - 1;
		let moveRow = rowNum + 1;
		if(moveRow > maxRow) moveRow = rowNum - 1;
		if(moveRow >= 0) exper_experimentSwitchToProtocol(moveRow);

		document.getElementById("experiment_protocol_" + rowNum).remove();
		document.getElementById("experiment_protocol_button_" + rowNum).remove();
		exper_fixButtonsAndDivs();

	}
}

function exper_moveExperimentProtocolUp(rowNum){

	exper_moveItemUpDown("experiment_protocols", rowNum, "up");
	exper_moveItemUpDown("experiment_protocol_buttons", rowNum, "up");
	exper_fixButtonsAndDivs();
	
}

function exper_moveExperimentProtocolDown(rowNum){

	exper_moveItemUpDown("experiment_protocols", rowNum, "down");
	exper_moveItemUpDown("experiment_protocol_buttons", rowNum, "down");
	exper_fixButtonsAndDivs();
	
}

function exper_experimentRenameProtocolButton(rowNum){
	let row = document.getElementById("experiment_protocol_" + rowNum);
	let selectedVal = findSubNode(row, "experimentProtocolTest").value;
	
	let newButtonString = "Stepp";
	
	if(selectedVal != ""){
		newButtonString = selectedVal;
	}else{
		newButtonString = "Step";
	}
	
	let changeButton = document.getElementById("experiment_protocol_button_" + rowNum);
	changeButton.children[0].innerHTML = newButtonString;
}

function exper_experimentRenameAllProtocolButtons(){
	let protocols = document.getElementById("experiment_protocols").children;
	if(protocols != null && protocols.length > 0){
		for (rowNum = 0; rowNum < protocols.length; rowNum++) {


			let row = document.getElementById("experiment_protocol_" + rowNum);
			let selectedVal = findSubNode(row, "experimentProtocolTest").value;
	
			let newButtonString = "Stepp";
	
			if(selectedVal != ""){
				newButtonString = selectedVal;
			}else{
				newButtonString = "Step";
			}
	
			let changeButton = document.getElementById("experiment_protocol_button_" + rowNum);
			changeButton.children[0].innerHTML = newButtonString;


		}
	}
}






function exper_addExperimentProtocolParameter(rowNum){
	console.log('add parameter to protocol ' + rowNum);
	let row = document.getElementById("experiment_protocol_" + rowNum);
	let existingDiv = findSubNode(row, "parameterRows");
	let existingRows = existingDiv.children;

	//Figure out what row we're on
	var newRowNum = existingRows.length;

	let sourceDiv = document.getElementById("sourceExperimentProtocolParameter");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "experiment_protocol_parameter_" + rowNum + "_" + newRowNum;
	//newDiv.classList.add("experiment_geometry_dimension_group_0");

	existingDiv.appendChild(newDiv);
	exper_fixButtonsAndDivs();
}

function exper_deleteExperimentProtocolParameter(geomNum, parameterNum){
	let protocolRow = document.getElementById("experiment_protocols").children[geomNum];
	let parameterRows = findSubNode(protocolRow, "parameterRows").children;
	parameterRows[parameterNum].remove();
	exper_fixButtonsAndDivs();
}

function exper_moveExperimentProtocolParameterDown(geomNum, rowNum){
	let protocolRow = document.getElementById("experiment_protocols").children[geomNum];
	let parameterRows = findSubNode(protocolRow, "parameterRows");
	exper_moveItemUpDownWithDiv(parameterRows, rowNum, "down");
	exper_fixButtonsAndDivs();
}

function exper_moveExperimentProtocolParameterUp(geomNum, rowNum){
	let protocolRow = document.getElementById("experiment_protocols").children[geomNum];
	let parameterRows = findSubNode(protocolRow, "parameterRows");
	exper_moveItemUpDownWithDiv(parameterRows, rowNum, "up");
	exper_fixButtonsAndDivs();
}














// ********** experiment documents

function exper_addExperimentDocument(){

	let existingDiv = document.getElementById("experiment_documents");
	let existingRows = existingDiv.children;
	
	//Figure out what row we're on
	var newRowNum = existingRows.length + 1;

	let sourceDiv = document.getElementById("sourceDocumentRow");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "experiment_document_" + newRowNum;
	
	//Set UUID
	findSubNode(newDiv, "uuid").value = uuidv4();

	existingDiv.appendChild(newDiv);

	//Also add button to sideBar
	let buttonsDiv = document.getElementById("experiment_document_buttons");
	let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

	//set id, class, onclick
	newButton.id = "experiment_document_button_" + newRowNum;
	newButton.classList.add("experiment_document_button_group");
	newButton.children[0].innerHTML = "Manual";
	newButton.setAttribute('onclick','exper_experimentSwitchToDocument(' + newRowNum +');');
	
	buttonsDiv.append(newButton);
	
	exper_experimentSwitchToDocument(newRowNum);

	//Fix Buttons
	exper_fixButtonsAndDivs();
}

function exper_experimentRenameDocumentButton(rowNum){
	let row = document.getElementById("experiment_document_" + rowNum);
	let newButtonString = findSubNode(row, "docType").value;
	let changeButton = document.getElementById("experiment_document_button_" + rowNum);
	changeButton.children[0].innerHTML = newButtonString;
}

function exper_experimentSwitchToDocument(rowNum){

	// Get all elements with class="tabcontent" and hide them
	divs = document.getElementsByClassName("experiment_document_group");
	for (i = 0; i < divs.length; i++) {
		divs[i].style.display = "none";
	}

	// Get all buttons with class and remove the class "straboRedSelectedButton"
	buttons = document.getElementsByClassName("experiment_document_button_group");
	for (i = 0; i < buttons.length; i++) {
		buttons[i].className = buttons[i].className.replace(" straboRedSelectedButton", "");
	}
	
	document.getElementById("experiment_document_" + rowNum).style.display = "block";
	document.getElementById("experiment_document_button_" + rowNum).classList.add("straboRedSelectedButton");
}

function exper_deleteExperimentDocument(rowNum){
	//if (confirm("Are you sure you want to delete this phase?\nThis cannot be undone.") == true) {

		//Get max row to confirm what to select next
		let maxRow = document.getElementById("experiment_document_buttons").children.length - 1;
		let moveRow = rowNum + 1;
		if(moveRow > maxRow) moveRow = rowNum - 1;
		if(moveRow >= 0) exper_experimentSwitchToDocument(moveRow);

		document.getElementById("experiment_document_" + rowNum).remove();
		document.getElementById("experiment_document_button_" + rowNum).remove();
		exper_fixButtonsAndDivs();

	//}
}

function exper_moveExperimentDocumentUp(rowNum){

	exper_moveItemUpDown("experiment_documents", rowNum, "up");
	exper_moveItemUpDown("experiment_document_buttons", rowNum, "up");
	exper_fixButtonsAndDivs();
	
}


function exper_moveExperimentDocumentDown(rowNum){

	exper_moveItemUpDown("experiment_documents", rowNum, "down");
	exper_moveItemUpDown("experiment_document_buttons", rowNum, "down");
	exper_fixButtonsAndDivs();
	
}


// *********************************************************************************
// *********************************************************************************
// ******************************   Data   *****************************************
// *********************************************************************************
// *********************************************************************************

function exper_openDataModal() {
	let modal = document.getElementById("dataModal");
	modal.style.display = "inline";
}

function exper_closeDataModal() {
	let modal = document.getElementById("dataModal");
	modal.style.display = "none";
}

function exper_doCancelDataEdit() {
	dataData = tempDataData;
	populateDataDataToInterface(dataData);
	exper_populateDataData();
	document.getElementById("dataModalBox").scrollTop = 0;
	exper_closeDataModal();
	exper_fixButtonsAndDivs();
	fixAllButtons();
}

function exper_doEditData() {
	//copy current daq data to temp
	console.log("edit data");
	tempDataData = dataData;
	exper_clearDataInterface();
	populateDataDataToInterface(dataData);
	exper_openDataModal();
}

function exper_clearDataData(){
	dataData = null;

	let dataModal = document.getElementById("dataModalBox");
	dataModal.innerHTML = "";

	let sourceDiv = document.getElementById("sourceDataModal");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "";
	dataModal.append(newDiv);

}

function exper_clearDataInterface() {
	console.log("clear data interface");
	
	let dataModal = document.getElementById("dataModalBox");
	dataModal.innerHTML = "";

	let sourceDiv = document.getElementById("sourceDataModal");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "";
	dataModal.append(newDiv);

}

function exper_updateDataHeaderInputs(datasetNum, headerNum){
	let headerRow = document.getElementById("data_header_" + datasetNum + "_" + headerNum);
	let headerButton = document.getElementById("data_header_button_" + datasetNum + "_" + headerNum);
	exper_updateDataHeaderInputs_with_div(headerRow, headerButton);
}

function exper_updateDataHeaderInputs_with_div(headerRow, headerButton){
	/* Fix this to actually get correct row data_header_0_2 */
	let headerType = findSubNode(headerRow, "headerHeader").value;
	
	//Change Button Text data_header_button_0_0
	
	headerButton.children[0].innerHTML = headerType;
	
	let spec_a_holder = findSubNode(headerRow, "specAHolder");
	let spec_b_holder = findSubNode(headerRow, "specBHolder");
	let unit_holder = findSubNode(headerRow, "unitHolder");
	
	//Loop through experimental_data_fields to find values for new selects
	var spec_a_vals = [];
	var spec_b_vals = [];
	var unit_vals = [];
	dataFields.forEach((df) => {
		if(df.headerType == headerType){
			if(df.fieldVal == "spec_a"){
				spec_a_vals.push(df.selectVal);
			}
			if(df.fieldVal == "spec_b"){
				spec_b_vals.push(df.selectVal);
			}
			if(df.fieldVal == "unit"){
				unit_vals.push(df.selectVal);
			}
		}
	});
	
	//Set up spec_a select
	spec_a_holder.innerHTML = "";
	if(spec_a_vals.length > 0){
		//Create select
		var spec_a_select = document.createElement("select");
		spec_a_select.classList.add("formControl");
		spec_a_select.classList.add("formSelect");
		spec_a_select.id = "headerSpecA";
		spec_a_holder.appendChild(spec_a_select);
		for (var i = 0; i < spec_a_vals.length; i++) {
			var option = document.createElement("option");
			option.value = spec_a_vals[i];
			option.text = spec_a_vals[i];
			spec_a_select.appendChild(option);
		}
	}else{
		var spec_a_input = document.createElement("input");
		spec_a_input.setAttribute('type', 'text');
		spec_a_input.classList.add("formControl");
		spec_a_input.id = "headerSpecA";
		spec_a_holder.appendChild(spec_a_input);
	}

	//Set up spec_b select
	spec_b_holder.innerHTML = "";
	if(spec_b_vals.length > 0){
		//Create select
		var spec_b_select = document.createElement("select");
		spec_b_select.classList.add("formControl");
		spec_b_select.classList.add("formSelect");
		spec_b_select.id = "headerSpecB";
		spec_b_holder.appendChild(spec_b_select);
		for (var i = 0; i < spec_b_vals.length; i++) {
			var option = document.createElement("option");
			option.value = spec_b_vals[i];
			option.text = spec_b_vals[i];
			spec_b_select.appendChild(option);
		}
	}else{
		var spec_b_input = document.createElement("input");
		spec_b_input.setAttribute('type', 'text');
		spec_b_input.classList.add("formControl");
		spec_b_input.id = "headerSpecB";
		spec_b_holder.appendChild(spec_b_input);
	}
	
	//Set up unit select
	unit_holder.innerHTML = "";
	if(unit_vals.length > 0){
		//Create select
		var unit_select = document.createElement("select");
		unit_select.classList.add("formControl");
		unit_select.classList.add("formSelect");
		unit_select.id = "headerUnit";
		unit_holder.appendChild(unit_select);
		for (var i = 0; i < unit_vals.length; i++) {
			var option = document.createElement("option");
			option.value = unit_vals[i];
			option.text = unit_vals[i];
			unit_select.appendChild(option);
		}
	}else{
		var unit_input = document.createElement("input");
		unit_input.setAttribute('type', 'text');
		unit_input.classList.add("formControl");
		unit_input.id = "headerUnit";
		unit_holder.appendChild(unit_input);
	}
	
}

function exper_addDataset(){

	let existingDiv = document.getElementById("data_datasets");
	let existingRows = existingDiv.children;
	
	//Figure out what row we're on
	var newRowNum = existingRows.length;

	let sourceDiv = document.getElementById("sourceDatasetRow");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "data_dataset_" + newRowNum;
	
	//Populate UUID
	let uuid = findSubNode(newDiv, "uuid");
	uuid.value = uuidv4();

	existingDiv.appendChild(newDiv);

	//Also add button to sideBar
	let buttonsDiv = document.getElementById("data_dataset_buttons");
	let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

	//set id, class, onclick
	newButton.id = "data_dataset_button_" + newRowNum;
	newButton.classList.add("data_dataset_button_group");
	newButton.children[0].innerHTML = "Parameters";
	newButton.setAttribute('onclick','exper_dataSwitchToDataset(' + newRowNum +');'); //exper_sampleSwitchToParameter
	
	buttonsDiv.append(newButton);
	
	exper_dataSwitchToDataset(newRowNum);

	//Take care of extra divs
	exper_dataRenameDatasetButton(newRowNum);

	//Fix Buttons
	exper_fixButtonsAndDivs();
	

}

function exper_dataSwitchToDataset(rowNum){

	// Get all elements with class="tabcontent" and hide them
	divs = document.getElementsByClassName("data_dataset_group");
	for (i = 0; i < divs.length; i++) {
		divs[i].style.display = "none";
	}

	// Get all buttons with class and remove the class "straboRedSelectedButton"
	buttons = document.getElementsByClassName("data_dataset_button_group");
	for (i = 0; i < buttons.length; i++) {
		buttons[i].className = buttons[i].className.replace(" straboRedSelectedButton", "");
	}
	
	document.getElementById("data_dataset_" + rowNum).style.display = "block";
	document.getElementById("data_dataset_button_" + rowNum).classList.add("straboRedSelectedButton");
}

function exper_dataRenameDatasetButton(rowNum){
	let datasetRow = document.getElementById("data_dataset_" + rowNum);
	let button = document.getElementById("data_dataset_button_" + rowNum);
	let dataDataVal = findSubNode(datasetRow, "dataData").value;
	if(dataDataVal == "") dataDataVal = "foo";
	button.children[0].innerHTML = dataDataVal;
	
	//Add Extra Data
	let extraDataDiv = findSubNode(datasetRow, "extraData");
	extraDataDiv.innerHTML = "";
	if(dataDataVal == "Parameters"){
		//sourceDataParametersBox
		let sourceDiv = document.getElementById("sourceDataParametersBox");
		let newDiv = sourceDiv.cloneNode(true);
		newDiv.style.display = "inline";
		newDiv.id = "data_parameters_" + rowNum;
		extraDataDiv.appendChild(newDiv);
	}else if(dataDataVal == "Time Series"){
		//sourceDataHeadersBox
		let sourceDiv = document.getElementById("sourceDataHeadersBox");
		let newDiv = sourceDiv.cloneNode(true);
		newDiv.style.display = "inline";
		newDiv.id = "data_headers_" + rowNum;
		extraDataDiv.appendChild(newDiv);
	}else if(dataDataVal == "Pore Fluid"){
		let sourceDiv = document.getElementById("sourceDataPhasesBox");
		let newDiv = sourceDiv.cloneNode(true);
		newDiv.style.display = "inline";
		newDiv.id = "data_phases_" + rowNum;
		extraDataDiv.appendChild(newDiv);
	}
	
	//Fix Buttons
	exper_fixButtonsAndDivs();
	
}

function exper_deleteDataDataset(rowNum){
	if (confirm("Are you sure you want to delete this dataset?\nThis cannot be undone.") == true) {

		//Get max row to confirm what to select next
		let maxRow = document.getElementById("data_dataset_buttons").children.length - 1;
		let moveRow = rowNum + 1;
		if(moveRow > maxRow) moveRow = rowNum - 1;
		if(moveRow >= 0) exper_dataSwitchToDataset(moveRow);

		document.getElementById("data_dataset_" + rowNum).remove();
		document.getElementById("data_dataset_button_" + rowNum).remove();
		exper_fixButtonsAndDivs();

	}
}

function exper_moveDataDatasetUp(rowNum){

	exper_moveItemUpDown("data_datasets", rowNum, "up");
	exper_moveItemUpDown("data_dataset_buttons", rowNum, "up");
	exper_fixButtonsAndDivs();
	
}

function exper_moveDataDatasetDown(rowNum){

	exper_moveItemUpDown("data_datasets", rowNum, "down");
	exper_moveItemUpDown("data_dataset_buttons", rowNum, "down");
	exper_fixButtonsAndDivs();
	
}

function exper_handleDataDataTypeChange(rowNum){
	let datasetRow = document.getElementById("data_dataset_" + rowNum);
	
	let otherDataTypeHolder = findSubNode(datasetRow, "otherDataTypeHolder");
	let otherDataType = findSubNode(datasetRow, "otherDataType");
	
	let dataType = findSubNode(datasetRow, "dataType").value;
	
	otherDataType.value = "";
	
	if(dataType == "Other"){
		otherDataTypeHolder.style.display = "inline";
	}else{
		otherDataTypeHolder.style.display = "none";
	}
	
}

function exper_handleDataDataFormatChange(rowNum){
	let datasetRow = document.getElementById("data_dataset_" + rowNum);
	
	let otherDataFormatHolder = findSubNode(datasetRow, "otherDataFormatHolder");
	let otherDataFormat = findSubNode(datasetRow, "otherDataFormat");
	
	let dataFormat = findSubNode(datasetRow, "dataFormat").value;
	
	otherDataFormat.value = "";
	
	if(dataFormat == "Other"){
		otherDataFormatHolder.style.display = "inline";
	}else{
		otherDataFormatHolder.style.display = "none";
	}
	
}

function exper_addDataParameter(rowNum){
	let datasetRow = document.getElementById("data_dataset_" + rowNum);
	let holder = findSubNode(datasetRow, "parameterRowsHolder");
	if(holder != null){
		holder.style.display = "block";
		
		let parameterRows = findSubNode(datasetRow, "parameterRows");
		let parameterNum = parameterRows.children.length;

		let sourceDiv = document.getElementById("sourceDataParameterRow");
		let newDiv = sourceDiv.cloneNode(true);
		newDiv.style.display = "inline";
		newDiv.id = "data_parameter_" + rowNum + "_" + parameterNum;
		parameterRows.appendChild(newDiv);
		exper_fixButtonsAndDivs();
	}
}

function exper_moveDataParameterUp(datasetNum, parameterNum){
	
	let datasetRow = document.getElementById("data_dataset_" + datasetNum);
	let parametersDiv = findSubNode(datasetRow, "parameterRows");
	if(parametersDiv != null){
		exper_moveItemUpDownWithDiv(parametersDiv, parameterNum, "up");
	}
	
	exper_fixButtonsAndDivs();
}

//exper_moveItemUpDownWithDiv(dimensionRows, rowNum, "down");

function exper_moveDataParameterDown(datasetNum, parameterNum){
	
	let datasetRow = document.getElementById("data_dataset_" + datasetNum);
	let parametersDiv = findSubNode(datasetRow, "parameterRows");
	if(parametersDiv != null){
		exper_moveItemUpDownWithDiv(parametersDiv, parameterNum, "down");
	}
	
	exper_fixButtonsAndDivs();
}

//exper_deleteDataParameter(0, 3);

function exper_deleteDataParameter(datasetNum, parameterNum){
	document.getElementById("data_parameter_" + datasetNum + "_" + parameterNum).remove();
	
	let datasetRow = document.getElementById("data_dataset_" + datasetNum);
	let parametersDiv = findSubNode(datasetRow, "parameterRows");
	let holder = findSubNode(datasetRow, "parameterRowsHolder"); 
	if(parametersDiv != null){
		if(parametersDiv.children.length == 0) holder.style.display = "none";
	}
	
	exper_fixButtonsAndDivs();
}


function exper_addDatasetHeader(rowNum){
	let datasetRow = document.getElementById("data_dataset_" + rowNum);
	
	let existingDiv = findSubNode(datasetRow, "headers"); 
	let existingRows = existingDiv.children;
	
	//Figure out what row we're on
	var newRowNum = existingRows.length;

	let sourceDiv = document.getElementById("sourceDataHeaderRow");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "data_header_" + rowNum + "_" + newRowNum;

	existingDiv.appendChild(newDiv);

	//Also add button to sideBar
	let buttonsDiv = findSubNode(datasetRow, "header_buttons"); 
	let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

	//set id, class, onclick
	newButton.id = "data_header_button_" + rowNum + "_" + newRowNum;
	newButton.classList.add("data_header_button_group_" + rowNum);
	newButton.children[0].innerHTML = "Foo";
	newButton.setAttribute('onclick','exper_dataSwitchToHeader(' + rowNum + ', ' + newRowNum +');');
	
	buttonsDiv.append(newButton);
	
	exper_dataSwitchToHeader(rowNum, newRowNum);

	//Take care of extra divs
	//exper_dataRenameDatasetButton(newRowNum);
	

	//Fix Buttons
	exper_fixButtonsAndDivs();
	
	exper_updateDataHeaderInputs(rowNum, newRowNum);
}

function exper_dataSwitchToHeader(rowNum, headerNum){

	// Get all elements with class="tabcontent" and hide them
	divs = document.getElementsByClassName("data_header_group_" + rowNum);
	for (i = 0; i < divs.length; i++) {
		divs[i].style.display = "none";
	}

	// Get all buttons with class and remove the class "straboRedSelectedButton"
	buttons = document.getElementsByClassName("data_header_button_group_" + rowNum);
	for (i = 0; i < buttons.length; i++) {
		buttons[i].className = buttons[i].className.replace(" straboRedSelectedButton", "");
	}
	
	document.getElementById("data_header_" + rowNum + "_" + headerNum).style.display = "block";
	document.getElementById("data_header_button_" + rowNum + "_" + headerNum).classList.add("straboRedSelectedButton");
}

//data_header_button_0_0
//data_header_0_2

function exper_moveDataHeaderUp(datasetNum, headerNum){
	
	let datasetRow = document.getElementById("data_dataset_" + datasetNum);
	let headersDiv = findSubNode(datasetRow, "headers");
	if(headersDiv != null){
		exper_moveItemUpDownWithDiv(headersDiv, headerNum, "up");
	}

	let headerButtonsDiv = findSubNode(datasetRow, "header_buttons");
	if(headerButtonsDiv != null){
		exper_moveItemUpDownWithDiv(headerButtonsDiv, headerNum, "up");
	}
	
	exper_fixButtonsAndDivs();
}

function exper_moveDataHeaderDown(datasetNum, headerNum){
	
	let datasetRow = document.getElementById("data_dataset_" + datasetNum);
	let headersDiv = findSubNode(datasetRow, "headers");
	if(headersDiv != null){
		exper_moveItemUpDownWithDiv(headersDiv, headerNum, "down");
	}

	let headerButtonsDiv = findSubNode(datasetRow, "header_buttons");
	if(headerButtonsDiv != null){
		exper_moveItemUpDownWithDiv(headerButtonsDiv, headerNum, "down");
	}
	
	exper_fixButtonsAndDivs();
}

function exper_deleteDataHeader(datasetNum, headerNum){
	if (confirm("Are you sure you want to delete this header?\nThis cannot be undone.") == true) {

		//Get max row to confirm what to select next
		let datasetRow = document.getElementById("data_dataset_" + datasetNum);
		let headerButtonsDiv = findSubNode(datasetRow, "header_buttons");
		let maxRow = headerButtonsDiv.children.length - 1;
		let moveRow = rowNum + 1;
		if(moveRow > maxRow) moveRow = rowNum - 1;
		if(moveRow >= 0) exper_dataSwitchToHeader(datasetNum, moveRow);

		document.getElementById("data_header_" + datasetNum + "_" + headerNum).remove();
		document.getElementById("data_header_button_" + datasetNum + "_" + headerNum).remove();
		exper_fixButtonsAndDivs();

	}
}

function exper_addDataPhase(rowNum){
	let datasetRow = document.getElementById("data_dataset_" + rowNum);
	
	let existingDiv = findSubNode(datasetRow, "phases"); 
	let existingRows = existingDiv.children;
	
	//Figure out what row we're on
	var newRowNum = existingRows.length;
	var buttonNum = newRowNum + 1;

	let sourceDiv = document.getElementById("soureDataPhaseRow");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "data_phase_" + rowNum + "_" + newRowNum;

	existingDiv.appendChild(newDiv);

	//Also add button to sideBar
	let buttonsDiv = findSubNode(datasetRow, "phase_buttons"); 
	let newButton = document.getElementById("sourceSideBarButton").cloneNode(true);

	//set id, class, onclick
	newButton.id = "data_phase_button_" + rowNum + "_" + newRowNum;
	newButton.classList.add("data_phase_button_group_" + rowNum);
	newButton.children[0].innerHTML = "Phase " + buttonNum;
	newButton.setAttribute('onclick','exper_dataSwitchToPhase(' + rowNum + ', ' + newRowNum +');');
	
	buttonsDiv.append(newButton);
	
	exper_dataSwitchToPhase(rowNum, newRowNum);

	//Update Chemistry Interface
	exper_updateDataPhaseChemistryHolder(rowNum, newRowNum);
	

	//Fix Buttons
	exper_fixButtonsAndDivs();
	
	//exper_updateDataPhaseInputs(rowNum, newRowNum);
}

function exper_dataSwitchToPhase(rowNum, phaseNum){

	// Get all elements with class="tabcontent" and hide them
	divs = document.getElementsByClassName("data_phase_group_" + rowNum);
	for (i = 0; i < divs.length; i++) {
		divs[i].style.display = "none";
	}

	// Get all buttons with class and remove the class "straboRedSelectedButton"
	buttons = document.getElementsByClassName("data_phase_button_group_" + rowNum);
	for (i = 0; i < buttons.length; i++) {
		buttons[i].className = buttons[i].className.replace(" straboRedSelectedButton", "");
	}
	
	document.getElementById("data_phase_" + rowNum + "_" + phaseNum).style.display = "block";
	document.getElementById("data_phase_button_" + rowNum + "_" + phaseNum).classList.add("straboRedSelectedButton");
}

function exper_updateDataPhaseInputs(datasetNum, phaseNum){
	let phaseRow = document.getElementById("data_phase_" + datasetNum + "_" + phaseNum);
	let phaseComposition = findSubNode(phaseRow, "phaseComposition").value;
	
	//Change Button Text data_header_button_0_0
	let phaseButton = document.getElementById("data_phase_button_" + datasetNum + "_" + phaseNum);
	if(phaseComposition != ""){
		phaseButton.children[0].innerHTML = phaseComposition;
	}else{
		phaseButton.children[0].innerHTML = "Phase";
	}
	
}

function exper_moveDataPhaseUp(datasetNum, phaseNum){
	
	let datasetRow = document.getElementById("data_dataset_" + datasetNum);
	let phasesDiv = findSubNode(datasetRow, "phases");
	if(phasesDiv != null){
		exper_moveItemUpDownWithDiv(phasesDiv, phaseNum, "up");
	}

	let phasesButtonsDiv = findSubNode(datasetRow, "phase_buttons");
	if(phasesButtonsDiv != null){
		exper_moveItemUpDownWithDiv(phasesButtonsDiv, phaseNum, "up");
	}
	
	exper_fixButtonsAndDivs();
}

function exper_moveDataPhaseDown(datasetNum, phaseNum){
	
	let datasetRow = document.getElementById("data_dataset_" + datasetNum);
	let phasesDiv = findSubNode(datasetRow, "phases");
	if(phasesDiv != null){
		exper_moveItemUpDownWithDiv(phasesDiv, phaseNum, "down");
	}

	let phasesButtonsDiv = findSubNode(datasetRow, "phase_buttons");
	if(phasesButtonsDiv != null){
		exper_moveItemUpDownWithDiv(phasesButtonsDiv, phaseNum, "down");
	}
	
	exper_fixButtonsAndDivs();
}

function exper_deleteDataPhase(datasetNum, phaseNum){
	if (confirm("Are you sure you want to delete this phase?\nThis cannot be undone.") == true) {

		//Get max row to confirm what to select next
		let datasetRow = document.getElementById("data_dataset_" + datasetNum);
		let phaseButtonsDiv = findSubNode(datasetRow, "phase_buttons");
		let maxRow = phaseButtonsDiv.children.length - 1;
		let moveRow = rowNum + 1;
		if(moveRow > maxRow) moveRow = rowNum - 1;
		if(moveRow >= 0) exper_dataSwitchToPhase(datasetNum, moveRow);

		document.getElementById("data_phase_" + datasetNum + "_" + phaseNum).remove();
		document.getElementById("data_phase_button_" + datasetNum + "_" + phaseNum).remove();
		exper_fixButtonsAndDivs();

	}
}

function exper_updateDataPhaseChemistryHolder(datasetNum, phaseNum){
	let phaseRow = document.getElementById("data_phase_" + datasetNum + "_" + phaseNum);
	let phaseChemistryDataSelect = findSubNode(phaseRow, "phaseChemistryData");
	if(phaseChemistryDataSelect != null){
		let selectedData = phaseChemistryDataSelect.value;
		let solutesHolder = findSubNode(phaseRow, "solutesHolder");
		if(selectedData == "Chemistry"){
			//Add chemistry interface
			let sourceDiv = document.getElementById("sourceDataSolutesBox");
			let newDiv = sourceDiv.cloneNode(true);
			newDiv.id = "data_solutes_holder_" + datasetNum + "_" + phaseNum;
			newDiv.style.display = "block";
			
			//Fix Add Solute Button exper_addDataSolute(0,0);
			//findSubNode(newDiv, "addSoluteButton").setAttribute('onclick','exper_addDataSolute('+ datasetNum + ', ' + phaseNum + ');');
			
			solutesHolder.appendChild(newDiv);
		}else{
			//Clear chemistry interface
			solutesHolder.innerHTML = "";
		}
	}
	
	exper_fixButtonsAndDivs();
}

//exper_addDataSolute
function exper_addDataSolute(datasetNum, phaseNum){
	let phaseRow = document.getElementById("data_phase_" + datasetNum + "_" + phaseNum);
	let soluteRowsHolder = findSubNode(phaseRow, "soluteRowsHolder");
	let soluteRows = findSubNode(phaseRow, "soluteRows");
	soluteRowsHolder.style.display = "block";
	
	let sourceDiv = document.getElementById("sourceDataSoluteRow");
	let newDiv = sourceDiv.cloneNode(true);
	newDiv.id = "newSolute";
	newDiv.style.display = "block";
	soluteRows.appendChild(newDiv);
	
	exper_fixButtonsAndDivs();
}

function exper_moveDataSoluteUp(datasetNum, phaseNum, soluteNum){
	let phaseRow = document.getElementById("data_phase_"+datasetNum + "_" + phaseNum);
	if(phaseRow != null){
		let soluteRows = findSubNode(phaseRow, "soluteRows");
		if(soluteRows != null){
			exper_moveItemUpDownWithDiv(soluteRows, soluteNum, "up");
		}
	}
	exper_fixButtonsAndDivs();
}

function exper_moveDataSoluteDown(datasetNum, phaseNum, soluteNum){
	let phaseRow = document.getElementById("data_phase_"+datasetNum + "_" + phaseNum);
	if(phaseRow != null){
		let soluteRows = findSubNode(phaseRow, "soluteRows");
		if(soluteRows != null){
			exper_moveItemUpDownWithDiv(soluteRows, soluteNum, "down");
		}
	}
	exper_fixButtonsAndDivs();
}


//data_solute_0_1_2
//exper_deleteDataSolute

function exper_deleteDataSolute(datasetNum, phaseNum, soluteNum){
	document.getElementById("data_solute_" + datasetNum + "_" + phaseNum + "_" + soluteNum).remove();
	exper_fixButtonsAndDivs();
}

function exper_doSaveDataInfo() {
	var error = exper_checkForDataSubmitErrors();
	
	if(error != ""){
		alert(error);
	}else{
		exper_buildDataData();
		exper_populateDataData();
		document.getElementById("dataModalBox").scrollTop = 0;
		exper_closeDataModal();
		exper_fixDownloadButton();
	}
}

function exper_checkForDataSubmitErrors(){
	var error = "";
	
	//Only check for files
	let datasets = document.getElementById("data_datasets");
	if(datasets != null){
		datasets = datasets.children;
		for(let dnum = 0; dnum < datasets.length; dnum++){
			showDnum = dnum + 1;
			let dRow = datasets[dnum];
			
			if(findSubNode(dRow, "dataData").value == "") error += "No data source provided for dataset " + showDnum + ".\n";
			if(findSubNode(dRow, "dataType").value == "") error += "No data type provided for dataset " + showDnum + ".\n";
			
			let dataFile = findSubNode(dRow, "dataFile");
			if(dataFile != null){
				if(dataFile.type == "file"){
					if(dataFile.value == ""){
						//error += "No file provided for dataset " + showDnum + ".\n";
					}
				}
			}
		}
	}

	return error;
}

function exper_buildDataData(){
	var data = new Object();

	let datasetRows = document.getElementById("data_datasets");
	if(datasetRows != null){
		
		//Add keys here. Not needed? Populate before submitting?
		let keys = new Object();
		keys.facility = "";
		keys.apparatus = "";
		keys.sample = "";
		keys.user = "";
		keys.experiment = "";
		data.keys = keys;

		let datasets = [];
		datasetRows = datasetRows.children;
		for(let dNum = 0; dNum < datasetRows.length; dNum++){
			let dRow = datasetRows[dNum];
			let dataset = new Object();
			
			dataset.data = findSubNode(dRow, "dataData").value;
			dataset.type = findSubNode(dRow, "dataType").value;
			dataset.other_type = findSubNode(dRow, "otherDataType").value;
			dataset.format = findSubNode(dRow, "dataFormat").value;
			dataset.other_format = findSubNode(dRow, "otherDataFormat").value;
			dataset.id = findSubNode(dRow, "dataId").value;
			dataset.uuid = findSubNode(dRow, "uuid").value;

			/*
			let dataFile = findSubNode(dRow, "dataFile");
			if(dataFile != null){
				if(dataFile.type == "file"){
					//get file name from input - remove path first
					var fullPath = dataFile.value;
					var fileName = fullPath.split(/[\\\/]/).pop();
				}
			}else{
				var fileName = findSubNode(dRow, "filename").innerHTML;
			}

			dataset.path = fileName;
			*/
			
			dataset.path = findSubNode(dRow,"originalFilename").value;
			
			dataset.rating = findSubNode(dRow, "dataQuality").value;
			dataset.description = findSubNode(dRow, "dataDescription").value;
			
			//Parameters
			let parameterRows = findSubNode(dRow, "parameterRows");
			if(parameterRows != null){
				parameterRows = parameterRows.children;
				if(parameterRows.length > 0){
					let parameters = [];
					for(let paramNum = 0; paramNum < parameterRows.length; paramNum++){
						let paramRow = parameterRows[paramNum];
						let parameter = new Object();
						parameter.control = findSubNode(paramRow, "parameterControl").value;
						parameter.value = findSubNode(paramRow, "parameterValue").value;
						parameter.error = findSubNode(paramRow, "parameterError").value;
						parameter.unit = findSubNode(paramRow, "parameterUnit").value;
						parameter.prefix = findSubNode(paramRow, "parameterPrefix").value;
						parameter.note = findSubNode(paramRow, "parameterNote").value;
						
						parameters.push(parameter);
					}
					dataset.parameters = parameters;
				}
			}
			
			//Phases
			let phaseRows = findSubNode(dRow, "phases");
			if(phaseRows != null){
				phaseRows = phaseRows.children;
				if(phaseRows.length > 0){
					let phases = [];
					for(let phaseNum = 0; phaseNum < phaseRows.length; phaseNum++){
						let phaseRow = phaseRows[phaseNum];
						let phase = new Object();
						phase.composition = findSubNode(phaseRow, "phaseChemistryData").value;
						phase.component = findSubNode(phaseRow, "phaseComposition").value;
						phase.fraction = findSubNode(phaseRow, "phaseFraction").value;
						phase.activity = findSubNode(phaseRow, "phaseActivity").value;
						phase.fugacity = findSubNode(phaseRow, "phaseFugacity").value;
						phase.unit = findSubNode(phaseRow, "phaseUnit").value;
						
						//solutes
						let soluteRows = findSubNode(phaseRow, "soluteRows");
						if(soluteRows != null){
							soluteRows = soluteRows.children;
							if(soluteRows.length > 0){
								let solutes = [];
								for(let soluteNum = 0; soluteNum < soluteRows.length; soluteNum++){
									let soluteRow = soluteRows[soluteNum];
									let solute = new Object();
									solute.component = findSubNode(soluteRow, "soluteComponent").value;
									solute.value = findSubNode(soluteRow, "soluteValue").value;
									solute.error = findSubNode(soluteRow, "soluteError").value;
									solute.unit = findSubNode(soluteRow, "soluteUnit").value;
									solutes.push(solute);
								}
								phase.solutes = solutes;
							}
						}

						phases.push(phase);
					}
					let fluid = new Object();
					fluid.phases = phases;
					dataset.fluid = fluid;
				}
			}
			
			//Headers
			let headerRows = findSubNode(dRow, "headers");
			if(headerRows != null){
				headerRows = headerRows.children;
				if(headerRows.length > 0){
					let headers = [];
					for(let headerNum = 0; headerNum < headerRows.length; headerNum++){
						let headerRow = headerRows[headerNum];
						let header = new Object();
						let subHeader = new Object();
						
						subHeader.header = findSubNode(headerRow, "headerHeader").value;
						subHeader.spec_a = findSubNode(headerRow, "headerSpecA").value;
						subHeader.spec_b = findSubNode(headerRow, "headerSpecB").value;
						subHeader.spec_c = findSubNode(headerRow, "headerSpecC").value;
						subHeader.unit = findSubNode(headerRow, "headerUnit").value;
						header.header = subHeader;
						
						header.type = findSubNode(headerRow, "headerType").value;
						header.number = findSubNode(headerRow, "headerChannelNum").value;
						header.note = findSubNode(headerRow, "headerNote").value;
						header.rating = findSubNode(headerRow, "headerDataQuality").value;

						headers.push(header);
					}
					dataset.headers = headers;
				}
			}

			datasets.push(dataset);
		}
		data.datasets = datasets;
	}

	if(!jQuery.isEmptyObject(data)){
		dataData = data;
	}

	//console.log(JSON.stringify(data, null, "\t"));
}

function exper_populateDataData(){
	if(dataData != null){
		let datasetInfoDiv = document.getElementById("dataInfo");
		
		let dataString = '<div class="formRow">\
							<div class="formCell expButtonSpacer">\
								<button class="squareButtonSmaller" onclick="exper_doEditData();"><img title="Edit" src="/experimental/buttonImages/icons8-edit-30.png" width="17" height="17"></button>\
								<button class="squareButtonSmaller" onclick="exper_deleteDataData();"><img title="Delete" src="/experimental/buttonImages/icons8-trash-30.png" width="17" height="17"></button>\
							</div>\
							<div class="formCell w75">\
								<div class="formPart">\
									<div style="padding-left: 5px; display: block;">\
										<div class="frontBoxRow" style="font-weight:bold;">\
											<div class="dataLeft w33">Dataset Id</div>\
											<div class="dataLeft w33">Data Source</div>\
											<div class="dataLeft w33">Data Type</div>\
										</div>\
										<div>\
											<!--Each Dataset Here-->';

		for(let dNum=0; dNum < dataData.datasets.length; dNum++){
			let dRow = dataData.datasets[dNum];
			
			var dataType = dRow.type;
			if(dataType == "Other"){
				if(dRow.other_type != null && dRow.other_type != ""){
					dataType = dRow.other_type;
				}
			}
			
			dataString += '					<div>\
												<div class="frontBoxRow">\
													<div class="dataLeft w33">' + dRow.id + '&nbsp;</div>\
													<div class="dataLeft w33">' + dRow.data + '&nbsp;</div>\
													<div class="dataLeft w33">' + dataType + '&nbsp;</div>\
												</div>\
											</div>';
		}

		dataString += '					</div>\
									</div>\
								</div>\
							</div>\
						</div>';
		
		datasetInfoDiv.innerHTML = dataString;
		
	}
}


function exper_deleteDataData() {
	if (confirm("Are you sure you want to delete Experimental Data?\nThis cannot be undone.") == true) {
		dataData = null;
		let dataDiv = document.getElementById('dataInfo');
		
		//Reset Data fields here
		exper_clearDataData();
		
		dataDiv.innerHTML = '			<div class="formRow">\
				<span class="enterDataSpan">Enter Data: </span>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditData()"><span>Manually </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal(\'data\');"><span>From Previous Experiment  </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON(\'data\')"><span>From JSON File </span></button>\
			</div>';
	}
	
	exper_fixDownloadButton();
}

function exper_deleteDataDataRaw() {
	//if (confirm("Are you sure you want to delete Experimental Data?\nThis cannot be undone.") == true) {
		dataData = null;
		let dataDiv = document.getElementById('dataInfo');
		
		//Reset Data fields here
		exper_clearDataData();
		
		dataDiv.innerHTML = '			<div class="formRow">\
				<span class="enterDataSpan">Enter Data: </span>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditData()"><span>Manually </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal(\'data\');"><span>From Previous Experiment  </span></button>\
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON(\'data\')"><span>From JSON File </span></button>\
			</div>';
	//}
	
	exper_fixDownloadButton();
}

function loadFilledTestFile(){
	$.ajax({
		url : "filled.json",
		type: "GET",
		processData: false,
		contentType: false,
		success:function(data){
			if(data.Error){
				alert("Error!\n" + data.Error);
			}else{
				exper_clearAllData();
				exper_loadExperiment(data);	
			}
		},
		error: function(){
			//if fails     
		}
	});
}

function exper_handleFacilityTypeChange(){

	let facilityTypeSelect = document.getElementById("facilityType");

	let facilityType = facilityTypeSelect.value;
	let otherFacilityTypeHolder = document.getElementById("otherFacilityTypeHolder");
	let otherFacilityType = document.getElementById("otherFacilityType");
	otherFacilityType.value = "";
	if(facilityType == "Other"){
		otherFacilityTypeHolder.style.display = "inline";
	}else{
		otherFacilityTypeHolder.style.display = "none";
	}
}

function exper_handleApparatusTypeChange(){

	let apparatusTypeSelect = document.getElementById("apparatusType");

	let apparatusType = apparatusTypeSelect.value;
	let otherApparatusTypeHolder = document.getElementById("otherApparatusTypeHolder");
	let otherApparatusType = document.getElementById("otherApparatusType");
	otherApparatusType.value = "";
	if(apparatusType == "Other Apparatus"){
		otherApparatusTypeHolder.style.display = "inline";
	}else{
		otherApparatusTypeHolder.style.display = "none";
	}
}

function exper_uploadApparatusFileFoo(apparatusNum){
	console.log("apparatusNum: " + apparatusNum);
}


function exper_uploadApparatusFile(apparatusNum){
	console.log("apparatusNum: " + apparatusNum);

	//first, hide file (docFile);
	let deviceRow = document.getElementById("docRow-"+apparatusNum);
	//let fileHolder = document.getElementById("fileHolder");
	let fileHolder = findSubNode(deviceRow,"fileHolder");
	let docFile = findSubNode(deviceRow, "docFile");
	let uuid = findSubNode(deviceRow, "uuid").value;
	let originalFilenameHolder = findSubNode(deviceRow, "originalFilename");
	let originalFilename = docFile.files[0].name;
	originalFilename=originalFilename.replace(/\s+/g,"_");
	
	originalFilenameHolder.value = 'https://strabospot.org/i/' + uuid + '/' + originalFilename;
	docFile.style.display = "none";
	
	//add loading information
	fileHolder.innerHTML += "<div>Uploading File...</div>";
	
	const loadingBar = document.createElement("div");
	loadingBar.classList.add("greenLoadingBar");
	fileHolder.append(loadingBar);
	
	//send file to server
	var fd = new FormData();
	fd.append('uuid', uuid);
	fd.append("infile",docFile.files[0]);
	
	$.ajax({
		url : "inNewDocument.php",
		type: "POST",
		data : fd,
		xhr: function () {
				var xhr = $.ajaxSettings.xhr();
				xhr.onprogress = function e() {
					// For downloads
					if (e.lengthComputable) {
						var percent = Math.round(e.loaded / e.total * 100);
						loadingBar.style.width = percent + "%";
					}
				};
				xhr.upload.onprogress = function (e) {
					// For uploads
					if (e.lengthComputable) {
						var percent = Math.round(e.loaded / e.total * 100);
						loadingBar.style.width = percent + "%";
					}
				};
				return xhr;
			},
		processData: false,
		contentType: false,
		success:function(data){
			if(data.Error){
				alert("Error!\n" + data.Error);
			}else{
				setTimeout(function () {
					
					fileHolder.innerHTML = "";
					fileHolder.innerHTML = '<label class="formLabel">File Uploaded</label><div><a href="/i/'+uuid+'/'+originalFilename+'" target="_blank">' + 'https://strabospot.org/i/' + uuid + '/' + originalFilename + '</a></div>';
					fileHolder.innerHTML += '<div><a id="deleteLink" href="javascript:void(0);" onClick="exper_deleteApparatusFile(' + apparatusNum + ')">(Delete File)</a></div>';
					
				}, 500)
			}
		},
		error: function(){
			//if fails     
		}
	});
	
}

function exper_deleteApparatusFile(apparatusNum){

	//first, hide file (docFile);
	let deviceRow = document.getElementById("docRow-"+apparatusNum);
	let fileHolder = findSubNode(deviceRow,"fileHolder");
	let originalFilenameHolder = findSubNode(deviceRow, "originalFilename");
	originalFilenameHolder.value = "";
	fileHolder.innerHTML = "";
	
	//add file select here
	fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="exper_uploadApparatusFile(' + apparatusNum + ')">';



}

function exper_handleApparatusDocTypeChange(apparatusNum){
	let docRow = document.getElementById("docRow-"+apparatusNum);
	let docType = findSubNode(docRow,"docType").value;
	let otherDocTypeHolder = findSubNode(docRow,"otherDocTypeHolder");
	let otherDocType = findSubNode(docRow,"otherDocType");
	otherDocType.value = "";
	if(docType == "Other"){
		otherDocTypeHolder.style.display = "inline";
	}else{
		otherDocTypeHolder.style.display = "none";
	}
}

function exper_handleApparatusDocFormatChange(apparatusNum){
	let docRow = document.getElementById("docRow-"+apparatusNum);
	let docFormat = findSubNode(docRow,"docFormat").value;
	
	console.log("docFormat: " + docFormat);
	
	let otherDocFormatHolder = findSubNode(docRow,"otherDocFormatHolder");
	let otherDocFormat = findSubNode(docRow,"otherDocFormat");
	otherDocFormat.value = "";
	if(docFormat == "Other"){
		otherDocFormatHolder.style.display = "inline";
	}else{
		otherDocFormatHolder.style.display = "none";
	}
}

// ******************************************************************************************************************************


function exper_uploadDAQDeviceFile(deviceNum, documentNum){
	console.log("deviceNum: " + deviceNum);
	console.log("documentNum: " + documentNum);

	//first, hide file (docFile);
	let deviceRow = document.getElementById("daq_device_document_"+deviceNum+"_"+documentNum);
	//let fileHolder = document.getElementById("fileHolder");
	let fileHolder = findSubNode(deviceRow,"fileHolder");
	let docFile = findSubNode(deviceRow, "docFile");
	let uuid = findSubNode(deviceRow, "uuid").value;
	let originalFilenameHolder = findSubNode(deviceRow, "originalFilename");
	let originalFilename = docFile.files[0].name;
	originalFilename=originalFilename.replace(/\s+/g,"_");
	
	originalFilenameHolder.value = 'https://strabospot.org/i/' + uuid + '/' + originalFilename;
	docFile.style.display = "none";
	
	//add loading information
	fileHolder.innerHTML += "<div>Uploading File...</div>";
	
	const loadingBar = document.createElement("div");
	loadingBar.classList.add("greenLoadingBar");
	fileHolder.append(loadingBar);
	
	//send file to server
	var fd = new FormData();
	fd.append('uuid', uuid);
	fd.append("infile",docFile.files[0]);
	
	$.ajax({
		url : "inNewDocument.php",
		type: "POST",
		data : fd,
		xhr: function () {
				var xhr = $.ajaxSettings.xhr();
				xhr.onprogress = function e() {
					// For downloads
					if (e.lengthComputable) {
						var percent = Math.round(e.loaded / e.total * 100);
						loadingBar.style.width = percent + "%";
					}
				};
				xhr.upload.onprogress = function (e) {
					// For uploads
					if (e.lengthComputable) {
						var percent = Math.round(e.loaded / e.total * 100);
						loadingBar.style.width = percent + "%";
					}
				};
				return xhr;
			},
		processData: false,
		contentType: false,
		success:function(data){
			if(data.Error){
				alert("Error!\n" + data.Error);
			}else{
				setTimeout(function () {
					
					fileHolder.innerHTML = "";
					fileHolder.innerHTML = '<label class="formLabel">File Uploaded</label><div><a href="/i/'+uuid+'/'+originalFilename+'" target="_blank">' + 'https://strabospot.org/i/' + uuid + '/' + originalFilename + '</a></div>';
					fileHolder.innerHTML += '<div><a id="deleteLink" href="javascript:void(0);" onClick="exper_deleteDAQDeviceFile(' + deviceNum + ',' + documentNum + ')">(Delete File)</a></div>';
					
				}, 500)
			}
		},
		error: function(){
			//if fails     
		}
	});
	
}

function exper_deleteDAQDeviceFile(deviceNum, documentNum){

	//first, hide file (docFile);
	let deviceRow = document.getElementById("daq_device_document_"+deviceNum+"_"+documentNum);
	let fileHolder = findSubNode(deviceRow,"fileHolder");
	let originalFilenameHolder = findSubNode(deviceRow, "originalFilename");
	originalFilenameHolder.value = "";
	fileHolder.innerHTML = "";
	
	//add file select here
	fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="exper_uploadDAQDeviceFile(' + deviceNum + ',' + documentNum + ')">';



}

function exper_handleDAQDocTypeSelectChange(deviceNum, documentNum){
	let deviceRow = document.getElementById("daq_device_document_"+deviceNum+"_"+documentNum);
	let docType = findSubNode(deviceRow,"docType").value;
	let otherDocTypeHolder = findSubNode(deviceRow,"otherDocTypeHolder");
	let otherDocType = findSubNode(deviceRow,"otherDocType");
	otherDocType.value = "";
	if(docType == "Other"){
		otherDocTypeHolder.style.display = "inline";
	}else{
		otherDocTypeHolder.style.display = "none";
	}
}

function exper_handleDAQDocFormatSelectChange(deviceNum, documentNum){
	let deviceRow = document.getElementById("daq_device_document_"+deviceNum+"_"+documentNum);
	let docFormat = findSubNode(deviceRow,"docFormat").value;
	let otherDocFormatHolder = findSubNode(deviceRow,"otherDocFormatHolder");
	let otherDocFormat = findSubNode(deviceRow,"otherDocFormat");
	otherDocFormat.value = "";
	if(docFormat == "Other"){
		otherDocFormatHolder.style.display = "inline";
	}else{
		otherDocFormatHolder.style.display = "none";
	}
}



//************************************************************************************************
//exper_uploadSampleFile

function exper_uploadSampleFile(sampleNum){
	console.log("sampleNum: " + sampleNum);

	//first, hide file (docFile);
	let sampleRow = document.getElementById("sample_document_"+sampleNum);
	//let fileHolder = document.getElementById("fileHolder");
	let fileHolder = findSubNode(sampleRow,"fileHolder");
	let docFile = findSubNode(sampleRow, "docFile");
	let uuid = findSubNode(sampleRow, "uuid").value;
	let originalFilenameHolder = findSubNode(sampleRow, "originalFilename");
	let originalFilename = docFile.files[0].name;
	originalFilename=originalFilename.replace(/\s+/g,"_");
	
	originalFilenameHolder.value = 'https://strabospot.org/i/' + uuid + '/' + originalFilename;
	docFile.style.display = "none";
	
	//add loading information
	fileHolder.innerHTML += "<div>Uploading File...</div>";
	
	const loadingBar = document.createElement("div");
	loadingBar.classList.add("greenLoadingBar");
	fileHolder.append(loadingBar);
	
	//send file to server
	var fd = new FormData();
	fd.append('uuid', uuid);
	fd.append("infile",docFile.files[0]);
	
	$.ajax({
		url : "inNewDocument.php",
		type: "POST",
		data : fd,
		xhr: function () {
				var xhr = $.ajaxSettings.xhr();
				xhr.onprogress = function e() {
					// For downloads
					if (e.lengthComputable) {
						var percent = Math.round(e.loaded / e.total * 100);
						loadingBar.style.width = percent + "%";
					}
				};
				xhr.upload.onprogress = function (e) {
					// For uploads
					if (e.lengthComputable) {
						var percent = Math.round(e.loaded / e.total * 100);
						loadingBar.style.width = percent + "%";
					}
				};
				return xhr;
			},
		processData: false,
		contentType: false,
		success:function(data){
			if(data.Error){
				alert("Error!\n" + data.Error);
			}else{
				setTimeout(function () {
					
					fileHolder.innerHTML = "";
					fileHolder.innerHTML = '<label class="formLabel">File Uploaded</label><div><a href="/i/'+uuid+'/'+originalFilename+'" target="_blank">' + 'https://strabospot.org/i/' + uuid + '/' + originalFilename + '</a></div>';
					fileHolder.innerHTML += '<div><a id="deleteLink" href="javascript:void(0);" onClick="exper_deleteSampleFile(' + sampleNum + ')">(Delete File)</a></div>';
					
				}, 500)
			}
		},
		error: function(){
			//if fails     
		}
	});
	
}

function exper_deleteSampleFile(sampleNum){

	//first, hide file (docFile);
	let sampleRow = document.getElementById("sample_document_"+sampleNum);
	let fileHolder = findSubNode(sampleRow,"fileHolder");
	let originalFilenameHolder = findSubNode(sampleRow, "originalFilename");
	originalFilenameHolder.value = "";
	fileHolder.innerHTML = "";
	
	//add file select here
	fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="exper_uploadSampleFile(' + sampleNum + ')">';



}

//************************************************************************************************

function exper_uploadExperimentFile(experimentNum){
	console.log("experimentNum: " + experimentNum);

	//first, hide file (docFile);
	let experimentRow = document.getElementById("experiment_document_"+experimentNum);
	let fileHolder = findSubNode(experimentRow,"fileHolder");
	let docFile = findSubNode(experimentRow, "docFile");
	let uuid = findSubNode(experimentRow, "uuid").value;
	let originalFilenameHolder = findSubNode(experimentRow, "originalFilename");
	let originalFilename = docFile.files[0].name;
	originalFilename=originalFilename.replace(/\s+/g,"_");
	
	originalFilenameHolder.value = 'https://strabospot.org/i/' + uuid + '/' + originalFilename;
	docFile.style.display = "none";
	
	//add loading information
	fileHolder.innerHTML += "<div>Uploading File...</div>";
	
	const loadingBar = document.createElement("div");
	loadingBar.classList.add("greenLoadingBar");
	fileHolder.append(loadingBar);
	
	//send file to server
	var fd = new FormData();
	fd.append('uuid', uuid);
	fd.append("infile",docFile.files[0]);
	
	$.ajax({
		url : "inNewDocument.php",
		type: "POST",
		data : fd,
		xhr: function () {
				var xhr = $.ajaxSettings.xhr();
				xhr.onprogress = function e() {
					// For downloads
					if (e.lengthComputable) {
						var percent = Math.round(e.loaded / e.total * 100);
						loadingBar.style.width = percent + "%";
					}
				};
				xhr.upload.onprogress = function (e) {
					// For uploads
					if (e.lengthComputable) {
						var percent = Math.round(e.loaded / e.total * 100);
						loadingBar.style.width = percent + "%";
					}
				};
				return xhr;
			},
		processData: false,
		contentType: false,
		success:function(data){
			if(data.Error){
				alert("Error!\n" + data.Error);
			}else{
				setTimeout(function () {
					
					fileHolder.innerHTML = "";
					fileHolder.innerHTML = '<label class="formLabel">File Uploaded</label><div><a href="/i/'+uuid+'/'+originalFilename+'" target="_blank">' + 'https://strabospot.org/i/' + uuid + '/' + originalFilename + '</a></div>';
					fileHolder.innerHTML += '<div><a id="deleteLink" href="javascript:void(0);" onClick="exper_deleteExperimentFile(' + experimentNum + ')">(Delete File)</a></div>';
					
				}, 500)
			}
		},
		error: function(){
			//if fails     
		}
	});
	
}

function exper_deleteExperimentFile(experimentNum){

	//first, hide file (docFile);
	let experimentRow = document.getElementById("experiment_document_"+experimentNum);
	let fileHolder = findSubNode(experimentRow,"fileHolder");
	let originalFilenameHolder = findSubNode(experimentRow, "originalFilename");
	originalFilenameHolder.value = "";
	fileHolder.innerHTML = "";
	
	//add file select here
	fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="exper_uploadExperimentFile(' + experimentNum + ')">';

}

//************************************************************************************************

function exper_uploadDataFile(dataNum){
	console.log("dataNum: " + dataNum);

	//first, hide file (docFile);
	let dataRow = document.getElementById("data_dataset_"+dataNum);
	let fileHolder = findSubNode(dataRow,"fileHolder");
	let docFile = findSubNode(dataRow, "docFile");
	let uuid = findSubNode(dataRow, "uuid").value;
	let originalFilenameHolder = findSubNode(dataRow, "originalFilename");
	let originalFilename = docFile.files[0].name;
	originalFilename=originalFilename.replace(/\s+/g,"_");
	
	originalFilenameHolder.value = 'https://strabospot.org/i/' + uuid + '/' + originalFilename;
	docFile.style.display = "none";
	
	//add loading information
	fileHolder.innerHTML += "<div>Uploading File...</div>";
	
	const loadingBar = document.createElement("div");
	loadingBar.classList.add("greenLoadingBar");
	fileHolder.append(loadingBar);
	
	//send file to server
	var fd = new FormData();
	fd.append('uuid', uuid);
	fd.append("infile",docFile.files[0]);
	
	$.ajax({
		url : "inNewDocument.php",
		type: "POST",
		data : fd,
		xhr: function () {
				var xhr = $.ajaxSettings.xhr();
				xhr.onprogress = function e() {
					// For downloads
					if (e.lengthComputable) {
						var percent = Math.round(e.loaded / e.total * 100);
						loadingBar.style.width = percent + "%";
					}
				};
				xhr.upload.onprogress = function (e) {
					// For uploads
					if (e.lengthComputable) {
						var percent = Math.round(e.loaded / e.total * 100);
						loadingBar.style.width = percent + "%";
					}
				};
				return xhr;
			},
		processData: false,
		contentType: false,
		success:function(data){
			if(data.Error){
				alert("Error!\n" + data.Error);
			}else{
				setTimeout(function () {
					
					fileHolder.innerHTML = "";
					fileHolder.innerHTML = '<label class="formLabel">File Uploaded</label><div><a href="/i/'+uuid+'/'+originalFilename+'" target="_blank">' + 'https://strabospot.org/i/' + uuid + '/' + originalFilename + '</a></div>';
					fileHolder.innerHTML += '<div><a id="deleteLink" href="javascript:void(0);" onClick="exper_deleteDataFile(' + dataNum + ')">(Delete File)</a></div>';
					
				}, 500)
			}
		},
		error: function(){
			//if fails     
		}
	});
	
}

function exper_deleteDataFile(dataNum){

	//first, hide file (docFile);
	let dataRow = document.getElementById("data_dataset_"+dataNum);
	let fileHolder = findSubNode(dataRow,"fileHolder");
	let originalFilenameHolder = findSubNode(dataRow, "originalFilename");
	originalFilenameHolder.value = "";
	fileHolder.innerHTML = "";
	
	//add file select here
	fileHolder.innerHTML = '<label class="formLabel">Choose File <span class="reqStar">*</span></label><input type="file" id="docFile" class="formControl" onChange="exper_uploadDataFile(' + dataNum + ')">';

}

// *********************************************************************************
// *********************************************************************************
// **************************   Project Download   *********************************
// *********************************************************************************
// *********************************************************************************

function exper_openDownloadModal() {
	let modal = document.getElementById("downloadJSONModal");
	modal.style.display = "inline";
}

function exper_closeDownloadModal() {
	let modal = document.getElementById("downloadJSONModal");
	modal.style.display = "none";
}

function exper_doCancelDownloadJSON() {
	exper_closeDownloadModal();
}


function downloadProject() {
	//Populate modal and then open
	let out = {};
	out.facility = facilityData;
	out.apparatus = apparatusData;
	out.daq = daqData;
	out.sample = sampleData;
	out.experiment = experimentData;
	out.data = dataData;
	
	//fix out here
	out = removeEmpty(out);
	out = removeEmpty(out);
	
	let jsonString = JSON.stringify(out, null, "\t");
	let ta = document.getElementById('projectJSONText');
	ta.value = jsonString;


	exper_openDownloadModal();
}


function exper_doDownloadProjectJSON(){

	let experimentId = document.getElementById("mainExperimentId").value;
	if(experimentId == ""){
		experimentId = "strabo_experimental_project";
	}
	
	experimentId = experimentId.replace(/(\W+)/gi, '_');

	let out = {};
	out.facility = facilityData;
	out.apparatus = apparatusData;
	out.daq = daqData;
	out.sample = sampleData;
	out.experiment = experimentData;
	out.data = dataData;
	
	//fix out here
	out = removeEmpty(out);
	out = removeEmpty(out);
	
	let jsonString = JSON.stringify(out, null, "\t");

	//var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(jsonString);
	var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(jsonString);
	var dlAnchorElem = document.getElementById('downloadAnchorElem');
	dlAnchorElem.setAttribute("href",     dataStr     );
	dlAnchorElem.setAttribute("download", experimentId + ".json");
	dlAnchorElem.click();

}

function exper_doCopyProjectJSON() {
	let textarea = document.getElementById("projectJSONText");
	let content = textarea.value;
	textarea.select();
	document.execCommand("copy");
	textarea.value = "";
	textarea.value = content;
	clearSelection;
	
	
	//setTimeout(() => { textarea.scrollTop = 0; }, 10);
	
	exper_closeDownloadModal();
	fadeOutCopiedMessage();
	
}

function clearSelection() {
    var sel;
    if ( (sel = document.selection) && sel.empty ) {
        sel.empty();
    } else {
        if (window.getSelection) {
            window.getSelection().removeAllRanges();
        }
        var activeEl = document.activeElement;
        if (activeEl) {
            var tagName = activeEl.nodeName.toLowerCase();
            if ( tagName == "textarea" ||
                    (tagName == "input" && activeEl.type == "text") ) {
                // Collapse the selection to the end
                activeEl.selectionStart = activeEl.selectionEnd;
            }
        }
    }

}

function exper_openDataCopiedModal() {
	let modal = document.getElementById("dataCopiedModal");
	modal.style.display = "inline";
	modal.style.opacity = 1;
}

function exper_closeDataCopiedModal() {
	let modal = document.getElementById("dataCopiedModal");
	modal.style.display = "none";
	modal.style.opacity = 1;
}

function fadeOutCopiedMessage() {
	exper_openDataCopiedModal();
	
	var loader = document.getElementById("dataCopiedModal");
	loader.style.transition = '0s';
	loader.style.visibility = 'visible';

	setTimeout(function() {
		
		loader.style.transition = '1s';
		loader.style.opacity = '0';
		loader.style.visibility = 'hidden';
	}, 500);

    /*
    var fadeTarget = document.getElementById("dataCopiedModal");
    var fadeEffect = setInterval(function () {
        if (!fadeTarget.style.opacity) {
            fadeTarget.style.opacity = 1;
        }
        if (fadeTarget.style.opacity > 0) {
            fadeTarget.style.opacity -= 0.1;
        } else {
            clearInterval(fadeEffect);
            exper_closeDataCopiedModal();
        }
    }, 200);
    */
    
	
}

/*
function removeEmpty(obj) {
  const newObj = {};
  Object.entries(obj).forEach(([k, v]) => {
    if (v === Object(v)) {
      if(Object.keys(v).length != 0){
      	newObj[k] = removeEmpty(v);
      }
    } else if (v != null && v != "") {
      newObj[k] = obj[k];
    }
  });
  
  //newObj = removeEmpty(newObj);
  
  //return newObj;
  return obj;
}
*/


function removeEmpty(obj) {
  return obj;
}


function buildTooltips() {
	let elements = document.getElementsByClassName("tooltipper");

	//console.log(elements);
	
	//let outList = "";
	//console.log(toolTipsData);

	for (let i = 0; i < elements.length; i++) {
		let expcode = elements[i].getAttribute('exp-code');
		
		//console.log("Looking for: " + expcode);
		
		//outList+=expcode+"<br>";
		let tooltip = "";
		let pos = "";

		//Get tooltip based on expcode
		for(let x = 0; x < toolTipsData.length; x++){
			thisTip = toolTipsData[x];
			if(thisTip.headerId == expcode){
				tooltip = thisTip.toolTip;
				pos = "hint--"+thisTip.headerPos;
			}
		}

		if(tooltip != ""){
			elements[i].classList.add(pos);
			elements[i].classList.add("hint--large");
			elements[i].classList.add("hint--rounded");
			elements[i].setAttribute("aria-label", tooltip);
			//console.log("found "+tooltip);
		}
		
	};
	
	//document.body.innerHTML=outList;
}


/*

{headerId: "facilityName", headerPos: "top", toolTip: "tip one", },

	dataFields.forEach((df) => {
		if(df.headerType == headerType){
			if(df.fieldVal == "spec_a"){
				spec_a_vals.push(df.selectVal);
			}
			if(df.fieldVal == "spec_b"){
				spec_b_vals.push(df.selectVal);
			}
			if(df.fieldVal == "unit"){
				unit_vals.push(df.selectVal);
			}
		}
	});
*/


