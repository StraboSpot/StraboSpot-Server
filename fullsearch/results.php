<?php
/**
 * File: results.php
 * Description: StraboSpot Search Results
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("searchWorker.php");

/*
Array
(
	[resultType] => project
	[queryJSON] => {"params":[{"num":0,"qualifier":"and","constraints":[{"constraintType":"keyword","constraintValue":"scanner"}]}]}
	[allResultsJSON] => {"searchType":"results","straboField":{"counts":{"projectcount":0,"datasetcount":0,"spotcount":0},"projects":[]},"straboMicro":{"counts":{"projectcount":8,"samplecount":17,"micrographcount":170,"spotcount":33},"projects":[{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","project_id":"16529681568311","project_name":"RemoteTest","notes":null,"last_modified":"07/06/2022","project_location":null,"sample_count":1,"micrograph_count":3,"spot_count":1,"samples":[{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","sample_id":"3-16529681661017","sample_name":"Sample1","micrograph_count":"3","spot_count":"1"}]},{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","project_id":"16535154474780","project_name":"Montague","notes":null,"last_modified":"08/24/2023","project_location":null,"sample_count":5,"micrograph_count":100,"spot_count":5,"samples":[{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","sample_id":"3-16577466805124","sample_name":"06: HW SS","micrograph_count":"31","spot_count":"2"},{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","sample_id":"3-16577504643493","sample_name":"12: FW Sandstone","micrograph_count":"4","spot_count":"0"},{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","sample_id":"3-16577535613873","sample_name":"15: HW SS","micrograph_count":"49","spot_count":"3"},{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","sample_id":"3-16577570495024","sample_name":"24: FW thin bed ss","micrograph_count":"4","spot_count":"0"},{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","sample_id":"3-16577572140722","sample_name":"25: FW ss bed","micrograph_count":"12","spot_count":"0"}]},{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","project_id":"17090633424227","project_name":"SpotPadTest","notes":null,"last_modified":"03/05/2024","project_location":null,"sample_count":1,"micrograph_count":14,"spot_count":4,"samples":[{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","sample_id":"3-17090633543228","sample_name":"spotPadTestSample","micrograph_count":"14","spot_count":"4"}]},{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","project_id":"17176049358379","project_name":"DOI Upload Test","notes":"asdf","last_modified":"06/05/2024","project_location":null,"sample_count":1,"micrograph_count":3,"spot_count":2,"samples":[{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","sample_id":"3-17176049476613","sample_name":"SampOne","micrograph_count":"3","spot_count":"2"}]},{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","project_id":"17266708846653","project_name":"test_EXCITE","notes":null,"last_modified":"09/19/2024","project_location":null,"sample_count":1,"micrograph_count":4,"spot_count":0,"samples":[{"owner_firstname":"Jason","owner_lastname":"Ash","owner_id":"3","sample_id":"3-17266718542422","sample_name":"CCPF1A_migmatite","micrograph_count":"4","spot_count":"0"}]},{"owner_firstname":"Richard","owner_lastname":"Wessels","owner_id":"1976","project_id":"17266708846653","project_name":"test_EXCITE","notes":null,"last_modified":"09/19/2024","project_location":null,"sample_count":1,"micrograph_count":4,"spot_count":0,"samples":[{"owner_firstname":"Richard","owner_lastname":"Wessels","owner_id":"1976","sample_id":"1976-17266718542422","sample_name":"CCPF1A_migmatite","micrograph_count":"4","spot_count":"0"}]},{"owner_firstname":"Ellen","owner_lastname":"Nelson","owner_id":"3086","project_id":"15010100285080","project_name":"Idaho","notes":null,"last_modified":"06/09/2023","project_location":null,"sample_count":5,"micrograph_count":34,"spot_count":20,"samples":[{"owner_firstname":"Ellen","owner_lastname":"Nelson","owner_id":"3086","sample_id":"3086-16461468996859","sample_name":"17SD2_3","micrograph_count":"25","spot_count":"5"},{"owner_firstname":"Ellen","owner_lastname":"Nelson","owner_id":"3086","sample_id":"3086-16700143675565","sample_name":"22SHR","micrograph_count":"2","spot_count":"6"},{"owner_firstname":"Ellen","owner_lastname":"Nelson","owner_id":"3086","sample_id":"3086-16700148652618","sample_name":"SHR345","micrograph_count":"2","spot_count":"3"},{"owner_firstname":"Ellen","owner_lastname":"Nelson","owner_id":"3086","sample_id":"3086-16704503100532","sample_name":"SHR318","micrograph_count":"2","spot_count":"3"},{"owner_firstname":"Ellen","owner_lastname":"Nelson","owner_id":"3086","sample_id":"3086-16747784602236","sample_name":"ATL-80-1","micrograph_count":"3","spot_count":"3"}]},{"owner_firstname":"Jelle","owner_lastname":"Reichard","owner_id":"8200","project_id":"17158525954702","project_name":"uumeteor.test","notes":null,"last_modified":"05/22/2024","project_location":null,"sample_count":2,"micrograph_count":8,"spot_count":1,"samples":[{"owner_firstname":"Jelle","owner_lastname":"Reichard","owner_id":"8200","sample_id":"8200-17158526555225","sample_name":"mezo.1","micrograph_count":"5","spot_count":"1"},{"owner_firstname":"Jelle","owner_lastname":"Reichard","owner_id":"8200","sample_id":"8200-17159617456217","sample_name":"Bjurbole","micrograph_count":"3","spot_count":"0"}]}]}}
)
*/


$resultType = addslashes($_POST['resultType']);
$allResultsJSON = addslashes($_POST['allResultsJSON']);
$queryJSON = addslashes($_POST['queryJSON']);



/*
$json = $_POST['allResultsJSON'];
$json = json_decode($json);
$db->dumpVar($json);exit();
*/

include("../includes/mheader.php");

//$json = file_get_contents("php://input");

?>



			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>StraboSpot Search Results</h2>
						</header>

						<div id="loadingScreen" style="display:none;">
							<div class="grayOut" style="display:inline;"></div>
							<div id="loadingmessage" style="text-align:center;display:block;">
								<div class="loader" style="margin-left: 100px;"></div>
								<div>Searching. Please wait...</div>
							</div>
						</div>



						<div id="comingSoonScreen" style="display:none;">
							<div class="grayOut" style="display:inline;"></div>
							<div id="loadingmessage" style="text-align:center;display:block;">
								<!--<div class="loader" style="margin-left: 100px;"></div>-->
								<div>Coming Soon...</div>
							</div>
						</div>


						<div id="messageScreen" style="display:none;">
							<div class="grayOut" style="display:inline;"></div>
							<div id="loadingmessage" style="text-align:center;display:block;">
								<!--<div class="loader" style="margin-left: 100px;"></div>-->
								<div id="alertMessage">Alert Message Here...</div>
							</div>
						</div>


						<div id="saveSearchScreen" style="display:none;">
							<div class="grayOut" style="display:inline;"></div>
							<div id="savesearchmessage" style="text-align:center;display:block;">
								<!--<div class="loader" style="margin-left: 100px;"></div>-->
								<header class="major">
									<h2>Save Search</h2>
								</header>
								<div>
									<div class="row">
										<div class="col-6  col-12-medium-search-button col-12-small-search-button col-12-xsmall-search-button">
											<input type="text" name="searchName" id="searchName" value="" placeholder="Name of search...">
										</div>


										<div class="col-3  col-12-medium-search-button col-12-small-search-button col-12-xsmall-search-button">
											<a href="javascript:doSaveSearch();" class="button primary fit">Save</a>
										</div>

										<div class="col-3  col-12-medium-search-button col-12-small-search-button col-12-xsmall-search-button">
											<a href="javascript:cancelSaveSearch();" class="button primary fit">Cancel</a>
										</div>

										<!--
										<div class="col-3 col-12-xsmall-search-button">
											<input type="submit" value="Submit" class="primary">
										</div>
										-->
									</div>
								</div>
							</div>
						</div>














































						<div id="mySearchesScreen" style="display:none;">
							<div class="grayOut" style="display:block;"></div>
							<div id="mysearchesmessage" style="display:block;">
								<!--<div class="loader" style="margin-left: 100px;"></div>-->
								<div style="text-align:right;cursor:default;" onClick="closeMySearches();">X</div>
								<header class="major">
									<h2>My Saved Searches</h2>
								</header>
								<div id="savedSearchRows">

								</div>
							</div>
						</div>




















































						<div class="row">
							<div class="col-3 col-12-small col-12-xsmall" style="background-color:transparent;">

								<h2 class="lowSpace">Filter</h2>

								<div class="row">
									<div class="col-9 col-12-medium-search-button col-12-small-search-button col-12-xsmall-search-button">
										<input type="text" name="keywordInput" id="keywordInput" value="" placeholder="Enter Keywords Here..."/>
									</div>
									<div class="col-3 col-12-medium-search-button col-12-small-search-button col-12-xsmall-search-button">
										<ul class="actions fit">
											<li>
												<a href="javascript:addKeyword();" class="button primary fit plusbutton">
													<image src="/fullsearch/images/plus.png" width="20px;" style="margin-bottom:-4px;"/>
												</a>
											</li>
										</ul>
									</div>
								</div>

								<h3>Applied Filters: <!--<span style="cursor: copy;" onclick="alert('hi');" class="addPlus">+</span>--> </h3>
								<div class="searchTerms">
									<ul id="searchSelectedTerms">
									   <!--
									   <li id="d4138934-0b76-45f1-b73f-bb648821f909"><a href="javascript:removeSearchTerm('d4138934-0b76-45f1-b73f-bb648821f909');">Rock</a></li>
									   <li id="dc70988a-cfd2-4c27-b3f9-1faf5630dffa"><a href="javascript:removeSearchTerm('dc70988a-cfd2-4c27-b3f9-1faf5630dffa');">Rheometer</a></li>
									   <li id="dac8600d-abef-461d-9075-11d8028a126f"><a href="javascript:removeSearchTerm('dac8600d-abef-461d-9075-11d8028a126f');">Low Temperature</a></li>
									   <li id="308a31f2-c30a-4b3f-90bc-33e6182c4300"><a href="javascript:removeSearchTerm('308a31f2-c30a-4b3f-90bc-33e6182c4300');">Permeability</a></li>
									   <li id="290fda40-2723-4143-a4bb-00ec86cdf95f"><a href="javascript:removeSearchTerm('290fda40-2723-4143-a4bb-00ec86cdf95f');">Stiffness</a></li>
									   -->
									</ul>
								</div>

								<!--Sources-->
								<div><input type="checkbox" id="StraboFieldCheck" name="StraboFieldCheck" checked="true" onChange="handleSourceChecks();"><label for="StraboFieldCheck" id="StraboFieldLabel">StraboField (<span id="straboFieldResultCount"></span>)</label></div>
								<div><input type="checkbox" id="StraboMicroCheck" name="StraboMicroCheck" checked="true" onChange="handleSourceChecks();"><label for="StraboMicroCheck" id="StraboMicroLabel">StraboMicro (<span id="straboMicroResultCount"></span>)</label></div>
								<div><input type="checkbox" id="StraboExperimentalCheck" name="StraboExperimentalCheck" checked="true" onChange="handleSourceChecks();"><label for="StraboExperimentalCheck" id="StraboExperimentalLabel">StraboExperimental (<span id="straboExperimentalResultCount"></span>)</label></div>

								<!---
								<div><a href="javascript:buildPaginationLinks();">TEST</a></div>
								<div><a href="javascript:dumpAllResults();">DUMP</a></div>
								<div><a href="javascript:doDebug();">DEBUG</a></div>
								--->


							</div>
							<div class="col-9 col-12-small col-12-xsmall" style="background-color:transparent;">
								<div class="row resultsNavBar">
									<div class="col-3 col-12-xsmall-search-button">



<div class="dropdown">
<select class="dropbtn checkbtn" id="checkSelect" onchange="doHandleChecks();">
	<option value="" style="display:none"></option>
	<option value="all">Select All Results</option>
	<option value="page">Select Results on Page</option>
	<option value="none">Select None</option>
</select>
</div>

<div id="checkCount" class="dropdown checkCount">
0
</div>

<!--
<div class="dropdown" style="background-color:#e44c65;">
<div style="position:relative;">x</div>
<select class="dropbtn checkbtn" id="checkSelect" onchange="doHandleChecks();">
	<option value="" style="display:none"></option>
	<option value="all">Select All Results</option>
	<option value="page">Select Results on Page</option>
	<option value="none">Select None</option>
</select>
</div>

<div class="dropdown" style="background-color:red;">
<select class="dropbtn checkbtn" id="checkSelect" onchange="doHandleChecks();">
	<option value="" style="display:none"></option>
	<option value="all">Select All Results</option>
	<option value="page">Select Results on Page</option>
	<option value="none">Select None</option>
</select>
</div>
-->

<div class="dropdown">
<select class="dropbtn dlbtn" id="saveSelect" onchange="doHandleSave();">
	<option value="" style="display:none"></option>
	<option value="save">Save Search</option>
	<option value="load">Load Search</option>
	<option value="review">Review Results for Export</option>
</select>
</div>

									</div>
									<div class="col-3 col-12-xsmall-search-button"><a href="#" class="button primary fit small">List View</a></div>
									<div class="col-3 col-12-xsmall-search-button"><a href="javascript:gotoMapView();" class="button fit small">Map View</a></div>
									<div class="col-3 col-12-xsmall-search-button resultsSort">
										<select name="sortType" id="sortType" class="resultsSelect" onChange="javascript:doSort();">
											<option value="dateCreated">Date Created</option>
											<option value="lastUpdated">Last Updated</option>
											<option value="numImages">Number of Images</option>
											<option value="numSpots">Number of Spots</option>
										</select>
									</div>
								</div>






								<div id = "resultRows">






<?php
for($x=5; $x<5; $x++){
?>

									<div class="row resultRow">
										<div class="col-1 noPad">
											<input type="checkbox" id="12345<?php echo $x?>-check" name="12345<?php echo $x?>-check" checked="false"/>
											<label for="12345<?php echo $x?>-check" id="12345<?php echo $x?>-label"></label>
										</div>
										<div class="col-11" style="background-color: transparent;">
											<div class="row">
												<div class="col-8 col-12-xsmall smallPadLeft projectTitle">
													<span class="pickaxe-image"></span>
													<span class="microscope-image"></span>
													<span class="beaker-image"></span>
													Broken Hill Quartz Vein Mapping
												</div>
												<div class="col-4 col-12-xsmall smallPadLeft projectDate align-right xsmall-align-left">Last Updated 08/08/2024</div>
											</div>
											<div class="row">
												<div class="col-6 col-12-xsmall smallPadLeft projectOwner">John Smith</div>
												<div class="col-6 col-12-xsmall smallPadLeft dataCount align-right xsmall-align-left">200 Spots, 56 Images</div>
											</div>
											<div class="row">
												<div class="col-10 col-12-xsmall smallPadLeft projectOwner">
													This is some long description string with details about the project...
												</div>
												<div class="col-2 col-12-xsmall smallPadLeft dataCount align-right xsmall-align-left">
													<a href="#">VIEW</a>
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



<?php
}
?>












								</div>

								<div class="row" id="paginationLinks" style="justify-content: center;">
									<div class="paginationLink">&lt;&lt;</div>
									<div class="paginationLink">&lt;</div>
									<div class="paginationLink">100</div>
									<div class="paginationLink">200</div>
									<div class="paginationLink">300</div>

									<div class="paginationLink">400</div>
									<div class="paginationLink">500</div>

									<div class="paginationLink">&gt;</div>
									<div class="paginationLink">&gt;&gt;</div>

								</div>




							</div>
						</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>

				<script src="js/fullsearch.js"></script>

				<script>

					//Load incoming JSON results
					resultType = '<?php echo $resultType?>';
					allResultsJSON = '<?php echo $allResultsJSON?>';
					if(allResultsJSON != ''){
						allResults = JSON.parse(allResultsJSON);
					}

					addIncomingQueryParams('<?php echo $queryJSON?>');

					window.onload = function() {
						//showProjectResults(0);
						updateResultsList();
					};

				</script>

<div style="display:none;">
	<div class="row resultRow" id="sourceResultRow">
		<div class="col-1 noPad">
			<input type="checkbox" id="choicecheck" name="12345<?php echo $x?>-check"/>
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
					<a href="#" id="projectLink" target="_blank">VIEW</a>
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
</div>

<div style="display:none;">
	<div id="sourcePaginationDiv" class="paginationLink"></div>

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

</div>

<?php
include("../includes/mfooter.php");
?>