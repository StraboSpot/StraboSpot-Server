
	let allResultsJSON = "";
	let allResults = null;
	let resultsRows = [];
	let showRows = [];
	let checkedProjectIds = [];
	let resultType = "";
	let currentPage = 1;
	let numResults = 0;
	let showCount = 0;
	let fieldCount = 0;
	let microCount = 0;
	let experimentalCount = 0;
	let resultsPerPage = 10;

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

$("#keywordInput").on('keyup', function (e) {
	if (e.key === 'Enter' || e.keyCode === 13) {
		addKeyword();
	}
});

function uuidv4() {
  return "10000000-1000-4000-8000-100000000000".replace(/[018]/g, c =>
	(+c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> +c / 4).toString(16)
  );
}

function removeSearchTerm(term){
	$( "#"+term ).remove();
	doSearch();
}

function addKeyword(){
	const keyword = $("#keywordInput").val();

	if(keyword != ""){
		addKeywordToPage(keyword);
	}

	doSearch();
}

function addKeywordToPage(keyword) {
	const newUUID = uuidv4();

	const newLi = document.createElement("li");
	newLi.setAttribute('id', newUUID);

	let a = document.createElement('a');
	a.setAttribute('href','javascript:removeSearchTerm(\''+newUUID+'\');');
	a.innerHTML = keyword;
	newLi.appendChild(a);

	const ul = document.getElementById("searchSelectedTerms");
	ul.appendChild(newLi);

	$("#keywordInput").val('');


}

/*
{
  "params": [
	{
	  "num": 0,
	  "qualifier": "and",
	  "constraints": [
		{
		  "constraintType": "keyword",
		  "constraintValue": "rock"
		}
	  ]
	}
  ]
}
*/

function addIncomingQueryParams(json){
	if(json!=""){
		data = JSON.parse(json);
		if(data.params != null){
			for(let i=0; i<data.params.length; i++){
				let param = data.params[i];
				if(param.constraints != null){
					for(let j=0; j<param.constraints.length; j++){
						let constraint = param.constraints[j];
						if(constraint.constraintType != null){
							if(constraint.constraintType == "keyword"){
								if(constraint.constraintValue != null){
									let keyword = constraint.constraintValue;
									addKeywordToPage(keyword);
								}
							}
						}
					}
				}
			}
		}
	}
}

function doSearch(){

	saveChecks();

	//$("#loadingScreen").fadeIn();
	$("#loadingScreen").show();

	let outData = buildQueryData("results"); //count

	//$("#loadingScreen").hide();




	if(outData){
		outJSON = JSON.stringify(outData);

		$.ajax({
			url : "fullsearch.php",
			type: "POST",
			data : outJSON,
			processData: false,
			contentType: false,
			success:function(data){
				if(data.Error){
					$("#loadingScreen").hide();
					alert("Error!\n" + data.Error);
				}else{
					setTimeout(function () {
						$("#loadingScreen").hide();
						allResults = data;
						allResultsJSON = JSON.stringify(data);
						showBasicCounts(data);
						updateResultsList();
					}, 1000)
				}
			},
			error: function(){
				//if fails
			}
		});
	}else{
		$("#loadingScreen").hide();
		$("#countSearchResults").hide();
		$("#downloadButtonsWrapper").hide();
		allResults = null;
		allResultsJSON = JSON.stringify(data);
		showBasicCounts(data);
		updateResultsList();
	}

}































function doHandleSave(){
	let selected = $('#saveSelect').find(":selected").val();
	$('#saveSelect').find(":selected").prop('selected', false);
	console.log("handle save: " + selected);

	if(selected == "save"){
		openSaveSearch();
	}

	if(selected == "load"){
		openMySearches();
	}

	//showComingSoon();
	//save review
}

function openSaveSearch() {
	$( "#saveSearchScreen" ).show();
}





/*
addIncomingQueryParams('<?=$queryJSON?>');
*/





function doLoadSavedSearch(saveNum){
	$( "#mySearchesScreen" ).hide();

	const ul = document.getElementById("searchSelectedTerms");
	while (ul.firstChild) {
		ul.removeChild(ul.lastChild);
	}

	let row = document.getElementById("search-" + saveNum);

	let sj = findSubNode(row, "searchJSON").innerHTML;

	addIncomingQueryParams(sj);

	doSearch();

}










function doDeleteSavedSearch(saveNum){
	console.log(saveNum);

	if (confirm("Are you sure?") == true) {

		$.ajax(
			{
				url: "deletesearch?p=" + saveNum,
				'success': function(result){
					console.log(result);

				},
				'error' : function(request,error){
					alert("Request: "+JSON.stringify(request));
				}
			}
		);

		$("#search-" + saveNum).remove();

	} else {
		console.log("No.");
	}

}

function openMySearches() {
	$("#savedSearchRows").html('<div style="text-align:center;font-size:1.5em;padding-top:20px;">Loading, please wait...</div>');
	$( "#mySearchesScreen" ).show();

	$.ajax(
		{
			url: "mysearches",
			'success': function(result){
				console.log(result);
				if(result.searches != null && result.searches.length > 0){



					//Load search rows here
					$("#savedSearchRows").html('');

					for(let i=0; i<result.searches.length; i++){
						let row = result.searches[i];

						let sourceDiv = document.getElementById("sourceSavedSearchRow");
						let newRow = sourceDiv.cloneNode(true);
						newRow.id = "search-" + row.pkey;

						findSubNode(newRow, "searchName").innerHTML = row.search_name;
						findSubNode(newRow, "searchDate").innerHTML = row.date_saved;

						findSubNode(newRow, "loadLink").innerHTML = "<a href=\"javascript:doLoadSavedSearch("+row.pkey+");\">Load</a>";
						findSubNode(newRow, "deleteLink").innerHTML = "<a href=\"javascript:doDeleteSavedSearch("+row.pkey+");\">Delete</a>";

						findSubNode(newRow, "searchJSON").innerHTML = row.search_json;


						$("#savedSearchRows").append(newRow);
					}

/*
date_saved: "12/30/2024"

pkey: "4"

search_json: '{"searchType":"results","params":[{"num":0,"qualifier":"and","constraints":[{"constraintType":"keyword","constraintValue":"zircon"}]}],"searchName":"sdfgsdg"}'

search_name: "sdfgsdg"

sourcePaginationDiv


	<div id="sourceSavedSearchRow" class="row resultRow">
		<div class="col-4 col-12-medium col-12-xsmall smallPadLeft" id="searchName">JEA Data JEA Data JEA Data JEA Data JEA Data JEA Data JEA Data JEA Data JEA Data JEA Data JEA Data </div>
		<div class="col-2 col-12-medium col-12-xsmall smallPadLeft" id="searchDate">03/14/2022</div>
		<div class="col-3 col-12-medium col-12-xsmall smallPadLeft align-center" id="loadLink">
			<a href="#">Load</a>
		</div>
		<div class="col-3 col-12-medium col-12-xsmall smallPadLeft align-center" id="deleteLink">
			<a href="#">Delete</a>
		</div>
		<div id="searchJSON" style="display:none;">here</div>
	</div>


*/







				}else{
					$("#savedSearchRows").html('<div style="text-align:center;font-size:1.5em;padding-top:20px;">No saved searches found.</div>');
				}
			},
			'error' : function(request,error){
				alert("Request: "+JSON.stringify(request));
			}
		}
	);


}



































function doSaveSearch() {
	console.log("do save search here");

	//build data to save here

	let outData = buildQueryData("results");
	console.log(outData);

	let searchName = $("#searchName").val();

	if(searchName == ""){

		alert("Error!\nSearch Name cannot be blank.");

	}else{

		//Save Here
		outData.searchName = searchName;

		outJSON = JSON.stringify(outData);

		$.ajax({
			url : "savesearch.php",
			type: "POST",
			data : outJSON,
			processData: false,
			contentType: false,
			success:function(data){
				if(data.Error){
					$("#saveSearchScreen").hide();
					alert("Error!\n" + data.Error);
				}else{
					setTimeout(function () {
						$("#searchName").val("");
						$("#saveSearchScreen").hide();
						showMessage("Success!<br>Search Saved.");
					}, 100)
				}
			},
			error: function(){
				//if fails
			}
		});


	}













}























































function cancelSaveSearch() {
	console.log("do cancel search here");
	$( "#saveSearchScreen" ).fadeOut( "slow" );
}



function closeMySearches() {
	$( "#mySearchesScreen" ).fadeOut( "slow" );
}
































function showBasicCounts(data){

	let fieldDataExists = false;
	let microDataExists = false;
	let experimentalDataExists = false;

	//defaults
	$("#straboFieldProjectCount").html("0 Projects");
	$("#straboFieldDatasetCount").html("0 Datasets");
	$("#straboFieldSpotCount").html("0 Spots");
	$("#straboMicroProjectCount").html("0 Projects");
	$("#straboMicroSampleCount").html("0 Samples");
	$("#straboMicroMicrographCount").html("0 Micrographs");
	$("#straboMicroSpotCount").html("0 Spots");
	$("#straboExperimentalProjectCount").html("0 Projects");
	$("#straboExperimentalExperimentCount").html("0 Experiments");

	//straboField
	if(data.straboField){
		if(data.straboField.counts){
			if(data.straboField.counts.projectcount){
				$("#straboFieldProjectCount").html(data.straboField.counts.projectcount + " Projects");
				if(data.straboField.counts.projectcount > 0) fieldDataExists = true;
			}
			if(data.straboField.counts.datasetcount){
				$("#straboFieldDatasetCount").html(data.straboField.counts.datasetcount + " Datasets");
				if(data.straboField.counts.datasetcount > 0) fieldDataExists = true;
			}
			if(data.straboField.counts.spotcount){
				$("#straboFieldSpotCount").html(data.straboField.counts.spotcount + " Spots");
				if(data.straboField.counts.spotcount > 0) fieldDataExists = true;
			}
		}
	}

	//straboMicro

	if(data.straboMicro){
		if(data.straboMicro.counts){
			if(data.straboMicro.counts.projectcount){
				$("#straboMicroProjectCount").html(data.straboMicro.counts.projectcount + " Projects");
				if(data.straboMicro.counts.projectcount > 0) microDataExists = true;
			}

			if(data.straboMicro.counts.samplecount){
				$("#straboMicroSampleCount").html(data.straboMicro.counts.samplecount + " Samples");
				if(data.straboMicro.counts.samplecount > 0) microDataExists = true;
			}

			if(data.straboMicro.counts.micrographcount){
				$("#straboMicroMicrographCount").html(data.straboMicro.counts.micrographcount + " Micrographs");
				if(data.straboMicro.counts.micrographcount > 0) microDataExists = true;
			}
			if(data.straboMicro.counts.spotcount){
				$("#straboMicroSpotCount").html(data.straboMicro.counts.spotcount + " Spots");
				if(data.straboMicro.counts.spotcount > 0) microDataExists = true;
			}
		}
	}


	//straboExperimental
	if(data.straboExperimental){
		if(data.straboExperimental.counts){
			if(data.straboExperimental.counts.projectcount){
				$("#straboExperimentalProjectCount").html(data.straboExperimental.counts.projectcount + " Projects");
				if(data.straboExperimental.counts.projectcount > 0) experimentalDataExists = true;
			}
			if(data.straboExperimental.counts.experimentcount){
				$("#straboExperimentalExperimentCount").html(data.straboExperimental.counts.experimentcount + " Experiments");
				if(data.straboExperimental.counts.experimentcount > 0) experimentalDataExists = true;
			}
		}
	}

	if(fieldDataExists){
		$("#straboFieldDownloadButton").show();
	}else{
		$("#straboFieldDownloadButton").hide();
	}

	if(microDataExists){
		$("#straboMicroDownloadButton").show();
	}else{
		$("#straboMicroDownloadButton").hide();
	}

	if(experimentalDataExists){
		$("#straboExperimentalDownloadButton").show();
	}else{
		$("#straboExperimentalDownloadButton").hide();
	}

	if(fieldDataExists || microDataExists || experimentalDataExists){
		$("#downloadButtonsWrapper").show();
	}else{
		$("#downloadButtonsWrapper").hide();
	}

	$("#countSearchResults").show();
}

/*
function doDebug(){

	//let out = buildQueryData();
	//$("#straboFieldDownloadButton").hide();
	//$("#straboMicroDownloadButton").hide();
	//$("#straboExperimentalDownloadButton").hide();


	let outData = buildQueryData();
	outJSON = JSON.stringify(outData);
	let newWindow = window.open("","_blank");
	newWindow.blur();
	window.focus();

	$.ajax({
		type: "POST",
		url: "results",
		data: outJSON,
		processData: false,
		contentType: false,
		success: function(response) {
			// Open a new window with the response data
			//var newWindow = window.open("", "_blank");
			newWindow.document.write(response);
			newWindow.focus();
		}
	});

}
*/

function buildQueryData(searchType){
	let outData = {};
	outData.searchType = searchType;
	let params = [];
	let keywordLis = $("#searchSelectedTerms").children();
	if(keywordLis.length > 0){
		for(let x=0; x<keywordLis.length; x++){
			//keywords.push(keywordLis[x].innerText);
			let param = {};
			let constraints = [];
			param.num = x;
			param.qualifier = "and";

			let constraint = {};
			constraint.constraintType = "keyword";
			constraint.constraintValue = keywordLis[x].innerText;
			constraints.push(constraint);

			param.constraints = constraints;
			params.push(param);
		}
		outData.params = params;
		return outData;
	}else{
		return null;
	}
}

function getParamsArray(){
	let params = [];
	let keywordList = $("#searchSelectedTerms").children();
	if(keywordList.length > 0){
		for(let x=0; x<keywordList.length; x++){
			params.push(keywordList[x].innerText);
		}
		return params;
	}else{
		return null;
	}
}

function gotoResults(resultType){
	$("#resultType").val(resultType);

	let outData = buildQueryData();
	outJSON = JSON.stringify(outData);
	$("#queryJSON").val(outJSON);

	$("#allResultsJSON").val(allResultsJSON);

	$("#resultsForm").submit();
}

function showProjectResults(page = 0){
	console.log(allResults);
	//$("#foo").html(allResultsJSON);
}


//let fileHolder = findSubNode(newDoc, "fileHolder");
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

/*
	let sourceDoc = document.getElementById("sourceApparatusDocumentRow");
	let newDoc = sourceDoc.cloneNode(true);
*/

function getPaginationButton() {
	let sourceDiv = document.getElementById("sourcePaginationDiv");
	let newButton = sourceDiv.cloneNode(true);
	newButton.id = "";
	return newButton;
}

function goToPage(page){
	let numPages = Math.ceil(numResults / resultsPerPage);
	if(page > numPages) page = numPages;
	if(page < 1) page = 1;
	currentPage = page;
	buildPaginationLinks();
	refreshResultRows();
	document.body.scrollTop = document.documentElement.scrollTop = 0;
}



function buildPaginationLinks() {


	//currentPage = 11;

	//Clear Buttons
	const paginationLinksContainer = document.getElementById("paginationLinks")
	if(paginationLinksContainer != null){
		while (paginationLinksContainer.firstChild) {
			paginationLinksContainer.firstChild.remove()
		}
	}

	//Calculate Number of Pages
	let numPages = Math.ceil(showCount / resultsPerPage);


	if(numPages > 1){

		//Show < and << if needed
		if(currentPage > 1){

			//<<
			newButton = getPaginationButton();
			newButton.innerHTML = "&lt;&lt;";
			newButton.setAttribute('onclick','goToPage('+ 1 + ');');
			paginationLinksContainer.appendChild(newButton);

			//<
			newButton = getPaginationButton();
			newButton.innerHTML = "&lt;";
			let previousPage = currentPage - 1;
			newButton.setAttribute('onclick','goToPage('+ previousPage + ');');
			paginationLinksContainer.appendChild(newButton);

		}


		//Figure out start and end page
		let startPage = currentPage - 2;
		let endPage = currentPage + 2;

		if(startPage < 1){
			endPage = endPage + (1 - startPage);
			startPage = 1;
		}

		if(endPage > numPages) endPage = numPages;

		if( (endPage - startPage) < 4 ){
			startPage = endPage - 4;
		}

		if(startPage < 1) startPage = 1;


		for(let x=startPage; x<=endPage; x++){
			newButton = getPaginationButton();
			newButton.innerHTML = x;
			if(x == currentPage){
				newButton.classList.add("paginationLinkSelected");
			}else{
				newButton.setAttribute('onclick','goToPage('+ x + ');');
			}

			paginationLinksContainer.appendChild(newButton);

		}

		//Show > and >> if needed
		if(currentPage < numPages){



			//>
			newButton = getPaginationButton();
			newButton.innerHTML = "&gt;";
			let nextPage = currentPage + 1;
			newButton.setAttribute('onclick','goToPage('+ nextPage + ');');
			paginationLinksContainer.appendChild(newButton);

			//>>
			newButton = getPaginationButton();
			newButton.innerHTML = "&gt;&gt;";
			newButton.setAttribute('onclick','goToPage('+ numPages + ');');
			paginationLinksContainer.appendChild(newButton);

		}

	}

}

function showField() {
	if($("#StraboFieldCheck").is(':checked')){
		return true;
	}else{
		return false;
	}
}

function showMicro() {
	if($("#StraboMicroCheck").is(':checked')){
		return true;
	}else{
		return false;
	}
}

function showExperimental() {
	if($("#StraboExperimentalCheck").is(':checked')){
		return true;
	}else{
		return false;
	}
}

function updateShowRows() {
	showRows = [];

	for(let x = 0; x < resultsRows.length; x++){
		//type: "straboField"
		let thisRow = resultsRows[x];

		if(showField()){
			if(thisRow.type == "straboField") showRows.push(thisRow);
		}

		if(showMicro()){
			if(thisRow.type == "straboMicro") showRows.push(thisRow);
		}

		if(showExperimental()){
			if(thisRow.type == "straboExperimental") showRows.push(thisRow);
		}
	}

}

function updateResultsList() {

	//Build a list of project results from allResults
	saveChecks();
	resultsRows = [];
	currentPage = 1;

	//Account for Source Checkboxes!!!! (only for numResults, then also use checks to tell if rows should be displayed.)

	if(allResults != null) {

		if(allResults.straboField != null) fieldCount = allResults.straboField.counts.projectcount;
		if(allResults.straboMicro != null) microCount = allResults.straboMicro.counts.projectcount;
		if(allResults.straboExperimental != null) experimentalCount = allResults.straboExperimental.counts.projectcount;
		numResults = fieldCount + microCount + experimentalCount;

		//StraboField
		if(allResults.straboField != null){
			if(allResults.straboField.projects != null){
				for (var i = 0; i < allResults.straboField.projects.length; i++){
					let row = allResults.straboField.projects[i];
					row.type = "straboField";
					row.uuid = uuidv4();

					if(checkedProjectIds.includes(row.project_id)){
						row.isChecked = true;
					}else{
						row.isChecked = false;
					}

					resultsRows.push(row);
				}
			}
		}

		//StraboMicro
		if(allResults.straboMicro != null){
			if(allResults.straboMicro.projects != null){
				for (var i = 0; i < allResults.straboMicro.projects.length; i++){
					let row = allResults.straboMicro.projects[i];
					row.type = "straboMicro";
					row.uuid = uuidv4();

					if(checkedProjectIds.includes(row.project_id)){
						row.isChecked = true;
					}else{
						row.isChecked = false;
					}

					resultsRows.push(row);
				}
			}
		}

		//StraboExperimental
		if(allResults.straboExperimental != null){
			if(allResults.straboExperimental.projects != null){
				for (var i = 0; i < allResults.straboExperimental.projects.length; i++){
					let row = allResults.straboExperimental.projects[i];
					row.type = "straboExperimental";
					row.uuid = uuidv4();

					if(checkedProjectIds.includes(row.project_id)){
						row.isChecked = true;
					}else{
						row.isChecked = false;
					}

					resultsRows.push(row);
				}
			}
		}


		//$('#').html();

	}else{
		fieldCount = 0;
		microCount = 0;
		experimentalCount = 0;


	}

	sortResults();

	updateCounts();
	updateShowCount();
	updateShowRows();
	updateCheckCount();
	buildPaginationLinks();
	refreshResultRows();


}

function saveChecks() {
	checkedProjectIds = [];
	for(let x = 0; x < resultsRows.length; x++){
		let thisRow = resultsRows[x];
		if(thisRow.isChecked) checkedProjectIds.push(thisRow.project_id);
	}
}

function updateCheckCount() {
	let checkCount = 0;
	for(let x = 0; x < showRows.length; x++){
		let thisRow = showRows[x];
		if(thisRow.isChecked) checkCount++;
	}
	$("#checkCount").html(checkCount);
}













/*
	<div class="row resultRow" id="sourceResultRow">
		<div class="col-1 noPad">
			<input type="checkbox" id="choicecheck" name="12345<?=$x?>-check" checked="true"/>
			<label for="" id="choicecheckLabel"></label>
		</div>
		<div class="col-11" style="background-color: transparent;">
			<div class="row">
				<div class="col-8 col-12-xsmall smallPadLeft projectTitle" id="projectTitle"> <!-- add span and project title -->

				</div>
				<div class="col-4 col-12-xsmall smallPadLeft projectDate align-right xsmall-align-left" id="lastUpdated"></div>
			</div>
			<div class="row">
				<div class="col-6 col-12-xsmall smallPadLeft projectOwner" id="projectOwner"></div>
				<div class="col-6 col-12-xsmall smallPadLeft dataCount align-right xsmall-align-left" id="spotImageCount"></div>
			</div>
			<div class="row">
				<div class="col-10 col-12-xsmall smallPadLeft projectOwner" id="projectDescription"></div>
				<div class="col-2 col-12-xsmall smallPadLeft dataCount align-right xsmall-align-left">
					<a href="#" id="projectLink">VIEW</a>
				</div>
			</div>
			<!--
			<div class="row">
				<div class="col-6 col-12-xsmall smallPadLeft">here</div>
				<div class="col-3 col-12-xsmall smallPadLeft">here</div>
				<div class="col-3 col-12-xsmall smallPadLeft">here</div>
			</div>
			<div class="row">
				<div class="col-6 col-12-xsmall smallPadLeft">here</div>
				<div class="col-3 col-12-xsmall smallPadLeft">here</div>
				<div class="col-3 col-12-xsmall smallPadLeft">here</div>
			</div>
			-->
		</div>
	</div>


dataset_count: 1
datasets: Array [ {…} ]
isChecked: false
last_modified: "07/08/2020"
notes: null
owner_firstname: "Joe"
owner_id: "8"
owner_lastname: "Andrew"
project_id: "14615941736260"
project_location: "POINT(-117.0717210999559 34.90842266062577)"
project_name: "GSA Memoir"
spot_count: 202
type: "straboField"
uuid: "3dbea2e8-d6d6-4f98-b9b0-221c93176b48"

*/










function refreshResultRows() {

	/*
	console.log("refresh result rows:");
	console.log("resultsPerPage: " + resultsPerPage);
	console.log("currentPage: " + currentPage);
	console.log("fieldCount: " + fieldCount);
	console.log("microCount: " + microCount);
	console.log("experimentalCount: " + experimentalCount);
	*/

	//Clear Rows
	const resultRowsContainer = document.getElementById("resultRows")
		if(resultRowsContainer != null){
		while (resultRowsContainer.firstChild) {
			resultRowsContainer.firstChild.remove()
		}
	}

	//Calculate Actual Total Count to Show
	updateShowCount();


	//Calculate which results to show
	let firstResult = (currentPage - 1) * resultsPerPage;
	let lastResult = currentPage * resultsPerPage;
	if(lastResult > showRows.length) lastResult = showRows.length;



	for(let x = firstResult; x < lastResult; x++){


		//let uuid = uuidv4(); //No, pull from row

		let sourceDiv = document.getElementById("sourceResultRow");
		let newRow = sourceDiv.cloneNode(true);



		//let fileHolder = findSubNode(newDoc, "fileHolder");

		let row = showRows[x];


		let uuid = row.uuid;
		newRow.id = uuid;

		if(row.type == "straboField"){

			//Checkbox
			let checkBox = findSubNode(newRow, "choicecheck");
			checkBox.id = uuid + "-check";
			checkBox.name = uuid + "-check";
			checkBox.setAttribute('onChange', "handleSingleCheck('" + uuid + "')");

			if(row.isChecked){
				checkBox.checked = true;
			}

			let thisLabel = findSubNode(newRow, "choicecheckLabel");
			thisLabel.setAttribute('for',uuid + "-check");

			let projectTitle = findSubNode(newRow, "projectTitle");
			projectTitle.innerHTML = '<span class="pickaxe-image tooltip"><span class="tooltiptext">StraboField</span></span>' + row.project_name;

			let lastUpdated = findSubNode(newRow, "lastUpdated");
			lastUpdated.innerHTML = 'Last Updated ' + row.last_modified;

			let projectOwner = findSubNode(newRow, "projectOwner");
			projectOwner.innerHTML = row.owner_firstname + " " + row.owner_lastname;


			let spotImageCount = findSubNode(newRow, "spotImageCount");
			spotImageCount.innerHTML = row.spot_count + ' Spots ' + row.image_count + ' Images';


			let projectDescription = findSubNode(newRow, "projectDescription");
			projectDescription.innerHTML = row.notes;

			let projectLink = findSubNode(newRow, "projectLink");
			projectLink.href = "/fpl/" + row.project_id;

		}

		if(row.type == "straboMicro"){

			//Checkbox
			let checkBox = findSubNode(newRow, "choicecheck");
			checkBox.id = uuid + "-check";
			checkBox.name = uuid + "-check";
			checkBox.setAttribute('onChange', "handleSingleCheck('" + uuid + "')");

			if(row.isChecked){
				checkBox.checked = true;
			}

			let thisLabel = findSubNode(newRow, "choicecheckLabel");
			thisLabel.setAttribute('for',uuid + "-check");

			let projectTitle = findSubNode(newRow, "projectTitle");
			projectTitle.innerHTML = '<span class="microscope-image tooltip"><span class="tooltiptext">StraboMicro</span></span>' + row.project_name;

			let lastUpdated = findSubNode(newRow, "lastUpdated");
			lastUpdated.innerHTML = 'Last Updated ' + row.last_modified;

			let projectOwner = findSubNode(newRow, "projectOwner");
			projectOwner.innerHTML = row.owner_firstname + " " + row.owner_lastname;


			let spotImageCount = findSubNode(newRow, "spotImageCount");
			spotImageCount.innerHTML = row.micrograph_count + ' Micrographs ' + row.spot_count + ' Spots';


			let projectDescription = findSubNode(newRow, "projectDescription");
			projectDescription.innerHTML = row.notes;

			let projectLink = findSubNode(newRow, "projectLink");
			projectLink.href = "/mpl/" + row.project_id;

		}

		if(row.type == "straboExperimental"){

			//Checkbox
			let checkBox = findSubNode(newRow, "choicecheck");
			checkBox.id = uuid + "-check";
			checkBox.name = uuid + "-check";
			checkBox.setAttribute('onChange', "handleSingleCheck('" + uuid + "')");

			if(row.isChecked){
				checkBox.checked = true;
			}

			let thisLabel = findSubNode(newRow, "choicecheckLabel");
			thisLabel.setAttribute('for',uuid + "-check");

			let projectTitle = findSubNode(newRow, "projectTitle");
			projectTitle.innerHTML = '<span class="beaker-image tooltip"><span class="tooltiptext">StraboExperimental</span></span>' + row.project_name;

			let lastUpdated = findSubNode(newRow, "lastUpdated");
			lastUpdated.innerHTML = 'Last Updated ' + row.last_modified;

			let projectOwner = findSubNode(newRow, "projectOwner");
			projectOwner.innerHTML = row.owner_firstname + " " + row.owner_lastname;


			let spotImageCount = findSubNode(newRow, "spotImageCount");
			spotImageCount.innerHTML = row.experiment_count + ' Experiment(s)';


			let projectDescription = findSubNode(newRow, "projectDescription");
			projectDescription.innerHTML = row.notes;

			let projectLink = findSubNode(newRow, "projectLink");
			projectLink.href = "/epl/" + row.project_uuid;

		}

/*
isChecked: false
last_modified: "06/17/2024"
micrograph_count: 50
notes: null
owner_firstname: "Youseph"
owner_id: "5631"
owner_lastname: "Ibrahim"
project_id: "17183158994929"
project_location: null
project_name: "Red_Hills"
sample_count: 4
samples: Array(4) [ {…}, {…}, {…}, … ]
spot_count: 18
type: "straboMicro"
uuid: "f412cf42-24e5-4339-807d-6cb029503c00"
*/


		resultRowsContainer.appendChild(newRow);
	}
}

function handleSourceChecks() {
	saveChecks();
	updateResultsList();
}

function handleSingleCheck(uuid) {
	let checkState = $("#" + uuid + "-check").is(':checked');


	for(let x = 0; x < resultsRows.length; x++){
		if(resultsRows[x].uuid == uuid){
			resultsRows[x].isChecked = checkState;
		}
	}

	for(let x = 0; x < showRows.length; x++){
		if(showRows[x].uuid == uuid){
			showRows[x].isChecked = checkState;
		}
	}

	updateCheckCount();

}




function dumpAllResults() {
	console.log(resultsRows);
}

function updateShowCount() {
	showCount = 0;
	if($("#StraboFieldCheck").is(':checked')) showCount += fieldCount;
	if($("#StraboMicroCheck").is(':checked')) showCount += microCount;
	if($("#StraboExperimentalCheck").is(':checked')) showCount += experimentalCount;
}

function updateCounts() {
	$('#straboFieldResultCount').html(fieldCount);
	$('#straboMicroResultCount').html(microCount);
	$('#straboExperimentalResultCount').html(experimentalCount);
}



//select * from strabomicro.micro_projectmetadata where strabo_id = '14615941093975';





































































































function doHandleChecks() {
	let selected = $('#checkSelect').find(":selected").val();
	$('#checkSelect').find(":selected").prop('selected', false);
	console.log("handle checks: " + selected);

	//First, uncheck all from resultsRows and showRows
	for(let x = 0; x < resultsRows.length; x++){
		resultsRows[x].isChecked = false;
	}

	for(let x = 0; x < showRows.length; x++){
		showRows[x].isChecked = false;
	}

	//all page none
	if(selected == "all"){
		//Check all on current page first:
		let pageRows = document.getElementById("resultRows").children;
		for(let i = 0; i < pageRows.length; i++){
			let pageRow = pageRows[i];
			let checkBox = findSubNode(pageRow, pageRow.id + "-check");
			checkBox.checked = true;
		}

		//Now, check all from resultsRows and showRows
		for(let x = 0; x < resultsRows.length; x++){
			resultsRows[x].isChecked = true;
		}

		for(let x = 0; x < showRows.length; x++){
			showRows[x].isChecked = true;
		}
	}

	if(selected == "page"){
		//Check all on current page first:
		let pageRows = document.getElementById("resultRows").children;
		for(let i = 0; i < pageRows.length; i++){
			let pageRow = pageRows[i];
			let checkBox = findSubNode(pageRow, pageRow.id + "-check");
			checkBox.checked = true;
		}

		//Check page from resultsRows and showRows
		let firstResult = (currentPage - 1) * resultsPerPage;
		let lastResult = currentPage * resultsPerPage;
		if(lastResult > showRows.length) lastResult = showRows.length;

		let uuids = [];

		for(let x = firstResult; x < lastResult; x++){

			let row = showRows[x];
			uuids.push(row.uuid);

		}

		//Now, check all from resultsRows and showRows
		for(let x = 0; x < resultsRows.length; x++){
			if(uuids.includes(resultsRows[x].uuid)) resultsRows[x].isChecked = true;
		}



























	}

	if(selected == "none"){
		//Check all on current page first:
		let pageRows = document.getElementById("resultRows").children;
		for(let i = 0; i < pageRows.length; i++){
			let pageRow = pageRows[i];
			let checkBox = findSubNode(pageRow, pageRow.id + "-check");
			checkBox.checked = false;
		}
	}

	updateCheckCount();
}












































































































































/*

For checks, when data comes in, add a boolean field "isChecked"

{
	"searchType": "results",
	"straboField": {
		"counts": {
			"projectcount": 0,
			"datasetcount": 0,
			"spotcount": 0
		}
	},
	"straboMicro": {
		"counts": {
			"projectcount": 0,
			"samplecount": 0,
			"micrographcount": 0,
			"spotcount": 0
		}
	}
}%

straboFieldProjectCount

	let selected = $('#dl-'+id).find(":selected").val();
$('#dl-'+id).find(":selected").prop('selected', false);

field:
dataset_count: 1
datasets: Array [ {…} ]
image_count: 1
isChecked: false
last_modified: "10/26/2024"
notes: null
owner_firstname: "Sydney"
owner_id: "9168"
owner_lastname: "Bauer"
project_id: "17297272044767"
project_location: "POINT(-104.6513988551555 30.3118222721379)"
project_name: "Field Methods - Tectonics"
spot_count: 1
type: "straboField"
uuid: "1bf672ed-963e-4aaf-91e5-1c79546f4c3f"

micro:
isChecked: false
last_modified: "06/17/2024"
micrograph_count: 50
notes: null
owner_firstname: "Youseph"
owner_id: "5631"
owner_lastname: "Ibrahim"
project_id: "17183158994929"
project_location: null
project_name: "Red_Hills"
sample_count: 4
samples: Array(4) [ {…}, {…}, {…}, … ]
spot_count: 18
type: "straboMicro"
uuid: "cb88de58-2806-47be-a5a2-0847c954f251"

cars.sort(function(a, b){
  let x = a.type.toLowerCase();
  let y = b.type.toLowerCase();
  if (x < y) {return -1;}
  if (x > y) {return 1;}
  return 0;
});

(new Date("2013/09/05 15:34:00").getTime()/1000) 1378413240
(new Date("9/18/2024").getTime()/1000)

*/


function doSort(){
	updateResultsList();
}


function sortResults(){
	let selected = $('#sortType').find(":selected").val();

	//dateCreated lastUpdated numImages numSpots



	if(selected == "dateCreated"){
		resultsRows.sort(function(a, b){
			if(a.project_id > b.project_id){ return -1; }
			if(a.project_id < b.project_id){ return  1; }
			return 0;
		});
	}

	if(selected == "lastUpdated"){
		resultsRows.sort(function(a, b){

			let aLastMod = "";
			let bLastMod = "";

			if(a.last_modified != null){
				aLastMod = a.last_modified;
			}else{
				aLastMod = "01/01/1971";
			}

			if(b.last_modified != null){
				bLastMod = b.last_modified;
			}else{
				bLastMod = "01/01/1971";
			}


			let aNumericDate = new Date(aLastMod).getTime()/1000;
			let bNumericDate = new Date(bLastMod).getTime()/1000;

			if(aNumericDate > bNumericDate){ return -1; console.log("greater");}
			if(aNumericDate < bNumericDate){ return  1; console.log("lesser");}


			return 0;
		});
	}

	if(selected == "numImages"){
		console.log("sort by num images");
		resultsRows.sort(function(a, b){

			let aicount = 0;
			let bicount = 0;

			if(a.image_count != null) aicount = a.image_count;
			if(a.micrograph_count != null) aicount = a.micrograph_count;

			if(b.image_count != null) bicount = b.image_count;
			if(b.micrograph_count != null) bicount = b.micrograph_count;


			if(aicount > bicount){ return -1; }
			if(aicount < bicount){ return  1; }
			return 0;
		});
	}

	if(selected == "numSpots"){
		console.log("sort by num spots");
		resultsRows.sort(function(a, b){

			let ascount = 0;
			let bscount = 0;

			if(a.spot_count != null) ascount = a.spot_count;
			if(b.spot_count != null) bscount = b.spot_count;

			if(ascount > bscount){ return -1; }
			if(ascount < bscount){ return  1; }
			return 0;
		});
	}

}

function doDebug() {

	console.log("debug here");

	console.log(showRows);

	console.log((new Date("9/18/2024").getTime()/1000) );

	//comingSoonScreen
	showComingSoon();

}

function showMessage(inMessage) {
	$("#alertMessage").html(inMessage);
	$("#messageScreen").show();
	setTimeout(function() {
		 $('#messageScreen').fadeOut();
	}, 1000);
}

function showComingSoon() {
	$("#comingSoonScreen").show();
	setTimeout(function() {
		 $('#comingSoonScreen').fadeOut();
	}, 1000);
}

function gotoMapView() {
	showComingSoon();
}
