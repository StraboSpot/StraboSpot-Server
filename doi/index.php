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


//10.1080/10509585.2015.1092083
//0021a279-6e8d-4176-a9f5-94f1742fe128

include("../prepare_connections.php");

$userlevel = $_SESSION['userlevel'];


if(isset($_GET['u']) && $_GET['u'] != ""){

	$uuid = preg_replace('/[^a-zA-Z0-9\-]/', '', $_GET['u']);
	$count = $db->get_var_prepared("SELECT count(*) FROM dois WHERE uuid = $1", array($uuid));
	if($count == 0) exit("DOI not found;");

}else{
	exit("No UUID provided.");
}

?>
<!DOCTYPE html>
<html>
  <head>
	<title><?php echo $sitetitle?></title>

	<link rel="apple-touch-icon" sizes="57x57" href="/assets/bicons/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/assets/bicons/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/assets/bicons/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/assets/bicons/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/assets/bicons/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/assets/bicons/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/assets/bicons/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/assets/bicons/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/assets/bicons/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/assets/bicons/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/assets/bicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/assets/bicons/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/assets/bicons/favicon-16x16.png">
	<link rel="manifest" href="/bicons/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/assets/bicons/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<link href='https://fonts.googleapis.com/css?family=Raleway:400,300,200,700&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css' />
	<link rel="stylesheet" href="../includes/ol.css" type="text/css">
	<link rel="stylesheet" href="../includes/layerswitcher.css" type="text/css">

	<link rel="stylesheet" href="/assets/js/jquery-sidebar/jquery.sidebar.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/jquery-ui/jquery-ui.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/featherlight/featherlight.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/ionic/css/ionic.css" type="text/css">
	<link rel="stylesheet" href="../includes/new_map_search.css" type="text/css">
	<link rel="stylesheet" href="../includes/fancybox/src/css/core.css" type="text/css">
	<link rel="stylesheet" href="../includes/sidesearch.css" type="text/css">
	<link rel="stylesheet" href="../includes/w3.css" type="text/css">



	<!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
	<script src="/assets/js/ol4/ol.js"></script>
	<script src="/assets/js/layerswitcher/layerswitcher.js"></script>

	<!-- Map Search-Specific Files-->
	<script src="../includes/map_search_functions.js"></script>
	<script src="../includes/tab_builders.js"></script>
	<script src="../includes/strat.js"></script>
	<script src="../includes/data_model.js"></script>
	<script src="../includes/sidebar.js"></script>

	<!-- External Libraries-->
	<script src="/assets/js/underscore/underscore-min.js"></script>

	<script src="/assets/js/jquery/jquery.min.js"></script>
	<script src="/assets/js/jquery-sidebar/jquery.sidebar.min.js"></script>
	<script src="/assets/js/jquery-ui/jquery-ui.js"></script>

	<script src="../includes/js/jquery-debounce.js"></script>



	<script src="/assets/js/featherlight/featherlight.js"></script>
	<script src="/assets/js/turf/turf.min.js"></script>
	<script src="../includes/fancybox/src/js/core.js"></script>

	<link rel="stylesheet" href="../includes/js/easyautocomplete/dist/easy-autocomplete.css" type="text/css">
	<script src="../includes/js/easyautocomplete/dist/jquery.easy-autocomplete.js"></script>

	<script>
		$( function() {
			$( "#tabs" ).tabs({
			  activate: function( event, ui ) {
				updateSidebar();
			  }
			});
		});

		$( function() {
			$( "#querytabs" ).tabs({
			  activate: function( event, ui ) {
				console.log("query tab clicked");
			  }
			});
			$("#querytabs").tabs({ active: 0 });
		});

		$( function() {
			$( "#queryaccordion" ).accordion({
				heightStyle: "content",
				classes: {
					"ui-accordion": "query-accordion",
					"ui-accordion-header": "query-accordion-header",
					"ui-accordion-header-active": "query-accordion-active"
				}
			});
		} );

		/*
		$( function() {
			$( "#toptext" ).draggable();
		} );
		*/
	</script>

	<?php
	if($_GET['u']!=""){
		$uuid = pg_escape_string($_GET['u']);

		//Add all datasets to open here...
		//$onload = " onload = \"gotoDataset($datasetid);\"";
		$onload = " onload = \"loadDOI('$uuid');\"";

	}
	?>

  </head>
























































  <body<?php echo $onload?>>

	<div class="newSearchMapWrapper">
		<div id="map" >

			<div id="back_map"><button class="back_map_button" onClick="goBack();"/></div>

			<div id="download_map"><button class="download_map_button tooltip" onClick="openDownloadWindow();"/><span class="tooltiptext">Download Options</span></div>

			<div id="map_home"><button class="map_home_button tooltip" onClick="zoomHome();"/></div>

			<?php
			if($userlevel >=566){
			?>
			<div id="link_map"><button class="link_map_button" onClick="showStaticUrl();"/></div>
			<?php
			}
			?>



		</div>

	</div>


	<div id="spotswaiting">
		<table>
			<tr>
				<td><img src="/assets/js/images/box.gif"></td><td nowrap>Loading Project...</td>
			</tr>
		</table>
		<div id="spotsProgressMessage">
			Gathering spots from server...
		</div>
		<div id="spotsProgressBar">
			<div id="spotsProgressInnerBar">
				&nbsp;
			</div>
		</div>
	</div>


	<div id="openDatasets">
		<div class="openDatasetsBar">
			Project Datasets:
		</div>
		<div id="openDatasetsList">

		</div>
	</div>

	<!--
	<div id="spotswaiting">
		<table>
			<tr>
				<td><img src="/assets/js/images/box.gif"></td><td nowrap>Loading Spots...</td>
			</tr>
		</table>

		<div id="spotsProgressMessage">
			Gathering spots from server...
		</div>

		<div id="spotsProgressBar">
			<div id="spotsProgressInnerBar">
				&nbsp;
			</div>
		</div>

	</div>
	-->

	<div id="datasetswaiting">
		<table>
			<tr>
				<td><img src="/assets/js/images/box.gif"></td><td nowrap>Rebuilding Map...</td>
			</tr>
		</table>
	</div>



	<div id="downloadOptionsWindow">
		<div id="downloadOptionsWindowInside">
			<div id="downloadOptionsWindowBar">
				Download Options:
			</div>
			<div id="downloadOptionsWindowContent">
				<fieldset class="downloadOptionsFS">
					<legend class="downloadOptionsLegend">Download Datasets:</legend>
					<div id="downloadDatasets"></div>
				</fieldset>

				<fieldset class="downloadOptionsFS">
					<legend class="downloadOptionsLegend">Download Scope:</legend>
					<input type="radio" name="downloadscope" id="downloadscope" value="all" checked="checked"> All Spots<br>
					<input type="radio" name="downloadscope" id="downloadscope" value="envelope"> Only spots in current window.<br>
				</fieldset>



				<table class="downloadTable">
					<tr>
						<td valign="top">
							<button class="downloadsubmit" onclick="downloadData('fieldapp');"><span>StraboMobile</span></button>
						</td>
						<td>
							Download data in format suitable for importing into StraboMobile Field App.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<button class="downloadsubmit" onclick="downloadData('shapefile');"><span>Shapefile</span></button>
						</td>
						<td>
							Download data in shapefile format.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<button class="downloadsubmit" onclick="downloadData('kml');"><span>KMZ</span></button>
						</td>
						<td>
							Download data in .kmz format suitable for Google Earth.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<button class="downloadsubmit" onclick="downloadData('xls');"><span>Excel</span></button>
						</td>
						<td>
							Download data in Microsoft Excel spreadsheet format.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<button class="downloadsubmit" onclick="downloadData('stereonet');"><span>Stereonet</span></button>
						</td>
						<td>
							Download data in format suitable for Rick Allmendinger's Stereonet software.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<button class="downloadsubmit" onclick="downloadData('pdf');"><span>PDF</span></button>
						</td>
						<td>
							Download data in PDF format.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<button class="downloadsubmit" onclick="downloadData('stratsection');"><span>Strat&nbsp;Section(s)</span></button>
						</td>
						<td>
							Download strat section SVG file(s).
						</td>
					</tr>
<?php
if(in_array($userpkey,[3])){
?>
					<tr>
						<td valign="top">
							<button class="downloadsubmit" onclick="downloadData('debug');"><span>Debug</span></button>
						</td>
						<td>
							Download StraboSpot data in debug format.
						</td>
					</tr>
<?php
}
?>
				</table>



			</div>
		</div>
	</div>

	<div id="queryWindow">
		<div id="queryWindowInside">
			<div id="queryWindowBar">
				Search Criteria:
			</div>
			<div id="queryWindowContent">

				<div class="queryheader">

					<div class="floatright" id="querybuttondiv">
						<button id="greyquerybutton" class="greyquerysubmit"><span>Update&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>
					</div>

					<div class="floatright" id="clearbuttondiv">
						<button id="greyclearbutton" class="greyquerysubmit"><span>Clear&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></button>
					</div>

					<div class="clearfloat"></div>
				</div>

				<div id="queryTabsWrapper">

					<div id="querytabs">
						<ul>
							<li><a href="#spot_query_tab">SPOTS</a></li>
							<li><a href="#orient_query_tab">ORIENTATIONS</a></li>
							<li><a href="#samp_query_tab">SAMPLES</a></li>
							<li><a href="#_3d_query_tab">3D STRUCTURES</a></li>
							<li><a href="#of_query_tab">OTHER FEATURES</a></li>
						</ul>
						<div class="queryTab" id="spot_query_tab">
							<div>
								<div class="query_row">
									<div class="query_row_title"><input type="checkbox" id="query_has_image" value="yes" onClick='turnOnUpdateButton();'> Spot has Image</div>
								</div>
							</div>
						</div>
						<div id="orient_query_tab">
							<div>
								<div class="query_row">
									<div class="query_row_title"><input type="checkbox" id="query_has_orientation" value="yes" onClick='turnOnUpdateButton();'> Spot has Orientation Data</div>
								</div>
							</div>
						</div>
						<div id="samp_query_tab">
							<div>
								<div class="query_row">
									<div class="query_row_title"><input type="checkbox" id="query_has_sample" value="yes" onClick='turnOnUpdateButton();'> Spot has Sample Data</div>
								</div>
							</div>
						</div>
						<div id="_3d_query_tab">
							<div>
								<div class="query_row">
									<div class="query_row_title"><input type="checkbox" id="query_has_3d_structure" value="yes" onClick='turnOnUpdateButton();'> Spot has 3D Structure data</div>
								</div>
							</div>
						</div>
						<div id="of_query_tab">
							<div>
								<div class="query_row">
									<div class="query_row_title"><input type="checkbox" id="query_has_other_features" value="yes" onClick='turnOnUpdateButton();'> Spot has Other Features</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="sidebar right">

		<div id="sidebarspot">
			<div id="sidebar_spot_name" align="center">foofoo</div>
			<div id="tabs">
				<ul>
					<li><a href="#spot_tab">SPOT</a></li>
					<li><a href="#notes_tab">NOTES</a></li>
					<li><a href="#orientations_tab">ORIENTATIONS</a></li>
					<li><a href="#_3d_structures_tab">3D STRUCTURES</a></li>
					<li><a href="#images_tab">IMAGES</a></li>
					<li><a href="#nesting_tab">NESTING</a></li>
					<li><a href="#samples_tab">SAMPLES</a></li>
					<li><a href="#tephra_tab">TEPHRA</a></li>
					<li><a href="#other_features_tab">OTHER FEATURES</a></li>
					<li><a href="#tags_tab">TAGS</a></li>
					<li><a href="#igmet_tab">IG/MET</a></li>
					<li><a href="#strat_section_tab">STRAT SECTION</a></li>
					<li><a href="#sed_lithologies_tab">SED LITHOLOGIES</a></li>
					<li><a href="#sed_bedding_tab">SED BEDDING</a></li>
					<li><a href="#sed_structures_tab">SED STRUCTURES</a></li>
					<li><a href="#sed_diagenesis_tab">SED DIAGENESIS</a></li>
					<li><a href="#sed_fossils_tab">SED FOSSILS</a></li>
					<li><a href="#sed_interpretations_tab">SED INTERPRETATIONS</a></li>
				</ul>
				<div id="spot_tab" class="sidebarOverflow"></div>
				<div id="notes_tab" class="sidebarOverflow"></div>
				<div id="orientations_tab" class="sidebarOverflow"></div>
				<div id="_3d_structures_tab" class="sidebarOverflow"></div>
				<div id="images_tab" class="sidebarOverflow"></div>
				<div id="nesting_tab" class="sidebarOverflow"></div>
				<div id="samples_tab" class="sidebarOverflow"></div>
				<div id="tephra_tab" class="sidebarOverflow"></div>
				<div id="other_features_tab" class="sidebarOverflow"></div>
				<div id="tags_tab" class="sidebarOverflow"></div>
				<div id="igmet_tab" class="sidebarOverflow"></div>
				<div id="strat_section_tab" class="sidebarOverflow"></div>
				<div id="sed_lithologies_tab" class="sidebarOverflow"></div>
				<div id="sed_bedding_tab" class="sidebarOverflow"></div>
				<div id="sed_structures_tab" class="sidebarOverflow"></div>
				<div id="sed_diagenesis_tab" class="sidebarOverflow"></div>
				<div id="sed_fossils_tab" class="sidebarOverflow"></div>
				<div id="sed_interpretations_tab" class="sidebarOverflow"></div>
			</div>
		</div>

	</div>

	<div id="projectBox">
		<div>
			<img src="../../includes/images/strabo_front_page_banner.jpg" width="350px;">
		</div>
		<div id="projectTitle">
			Project: My Project Name Here
		</div>
		<div id="projectDOI">
			DOI: abc
		</div>
		<div id="projectDate">
			DOI Published on 05/17/2024
		</div>
		<div id="projectOwner">
			Owner: John Smith
		</div>
	</div>

	<script>
		//initialize JQuery sidebar
		var sidebarState = "closed";
		$(".sidebar.right").sidebar({side: "right"});

		$(".sidebar.right").on("sidebar:opened", function () {
		   sidebarState = "open";
		});

		$(".sidebar.right").on("sidebar:closed", function () {
		   sidebarState = "closed";
		});

	</script>
	<script src="../includes/map_interface.js"></script>





  </body>
</html>