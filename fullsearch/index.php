<?php
/**
 * File: index.php
 * Description: Main page or directory index
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("../logincheck.php");
include("../prepare_connections.php");
include("../includes/mheader.php");

$words = array(
	"Paterson Apparatus	",
	"Rheometer",
	"Low Temperature",
	"Permeability",
	"Stiffness",
	"Single Ended",
	"LVDT",
	"Igneous Rock",
	"Humite",
	"mDarcy",
	"Viscosity"
);

$words = array(
	"Paterson Apparatus	",
	"Rheometer",
	"Low Temperature",
	"Permeability",
	"Stiffness"
);



?>



			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>StraboSpot Search</h2>
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

						<div class="row">
							<div class="col-9 col-12-xsmall">
								<input type="text" name="keywordInput" id="keywordInput" value="" placeholder="Enter Keywords Here...">
							</div>


							<div class="col-3 col-12-xsmall-search-button">
								<ul class="actions fit">
									<li><a href="javascript:addKeyword();" class="button primary fit">Add</a></li>
								</ul>
							</div>

							<!--
							<div class="col-3 col-12-xsmall-search-button">
								<input type="submit" value="Submit" class="primary">
							</div>
							-->
						</div>

						<div class="searchTerms">
							<ul id="searchSelectedTerms">
						<?php
						foreach($wordss as $word){
							$newuuid = $uuid->v4();
						?>
							   <li id="<?php echo $newuuid?>"><a href="javascript:removeSearchTerm('<?php echo $newuuid?>');"><?php echo $word?></a></li>
						<?php
						}
						?>
							</ul>
						</div>


						<div id="countSearchResults" style="display:none;">
							<h3>Search Results:</h3>
							<div class="row gtr-uniform gtr-50">
								<div class="col-4 col-12-xsmall ">
									<div class="searchCountResult">
										<h4 class="countSearchHeader">StraboField</h4>
										<div class="countSearchResultRow" id="straboFieldProjectCount">123 Projects</div>
										<div class="countSearchResultRow" id="straboFieldDatasetCount">234 Datasets</div>
										<div class="countSearchResultRow" id="straboFieldSpotCount">345 Spots</div>
										<div class="countSearchResultRow">&nbsp;</div>
									</div>
								</div>
								<div class="col-4 col-12-xsmall ">
									<div class="searchCountResult">
										<h4 class="countSearchHeader">StraboMicro</h4>
										<div class="countSearchResultRow" id="straboMicroProjectCount">456 Projects</div>
										<div class="countSearchResultRow" id="straboMicroSampleCount">567 Samples</div>
										<div class="countSearchResultRow" id="straboMicroMicrographCount">678 Micrographs</div>
										<div class="countSearchResultRow" id="straboMicroSpotCount">789 Spots</div>
									</div>
								</div>
								<div class="col-4 col-12-xsmall ">
									<div class="searchCountResult">
										<h4 class="countSearchHeader">StraboExperimental</h4>
										<div class="countSearchResultRow" id="straboExperimentalProjectCount">789 Projects</div>
										<div class="countSearchResultRow" id="straboExperimentalExperimentCount">890 Experiments</div>
										<div class="countSearchResultRow">&nbsp;</div>
										<div class="countSearchResultRow">&nbsp;</div>
									</div>
								</div>
							</div>
						</div>

						<div id="downloadButtonsWrapper" class="row gtr-uniform gtr-50" style="display:none">
							<div class="col-6 col-12-xsmall ">
								<div class="searchDownloadButtonWrapper">
									<a href="javascript:gotoResults('project');" class="button primary fit">Project Results</a>
								</div>
							</div>
							<div class="col-6 col-12-xsmall ">
								<div class="searchDownloadButtonWrapper">
									<a href="javascript:showComingSoon();" class="button primary fit">Image Results</a>
								</div>
							</div>
						</div>









						<!--
						<div class="row gtr-uniform gtr-50">
							<div class="col-4 col-12-medium">
								<div class="searchCountResult">
									here
								</div>
							</div>
							<div class="col-4 col-12-medium">
								<div class="searchCountResult">
									here
								</div>
							</div>
							<div class="col-4 col-12-medium">
								<div class="searchCountResult">
									here
								</div>
							</div>
						</div>
						-->






					<div style="display:none;">
						<form id="resultsForm" method="POST" action="results.php" target="_blank">
							<input type="text" name="resultType" id="resultType" value="here"/>
							<input type="text" name="queryJSON" id="queryJSON" value="here"/>
							<input type="text" name="allResultsJSON" id="allResultsJSON" value="here"/>
						</form>
					</div>



					<div class="bottomSpacer"></div>

					</div>
				</div>

				<script src="js/fullsearch.js"></script>

<!--<a href="javascript:doDebug();">here</a>-->

<?php
include("../includes/mfooter.php");
?>
