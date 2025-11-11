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


session_start();
$userlevel = $_SESSION['userlevel'];

?>
<!DOCTYPE html>
<html>
  <head>
	<title>StraboSpot Search</title>

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
	<link rel="stylesheet" href="/assets/js/ol4/ol.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/layerswitcher/layerswitcher.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/jquery-sidebar/jquery.sidebar.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/jquery-ui/jquery-ui.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/featherlight/featherlight.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/ionic/css/ionic.css" type="text/css">
	<link rel="stylesheet" href="includes/map_search.css" type="text/css">
	<link rel="stylesheet" href="includes/fancybox/src/css/core.css" type="text/css">

	<!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
	<script src="/assets/js/ol4/ol.js"></script>
	<script src="/assets/js/layerswitcher/layerswitcher.js"></script>

	<!-- Map Search-Specific Files-->
	<script src="includes/map_search_functions.js"></script>
	<script src="includes/tab_builders.js"></script>
	<script src="includes/data_model.js"></script>

	<!-- External Libraries-->
	<script src="/assets/js/underscore/underscore-min.js"></script>
	<script src="/assets/js/jquery/jquery.min.js"></script>
	<script src="/assets/js/jquery-sidebar/jquery.sidebar.min.js"></script>
	<script src="/assets/js/jquery-ui/jquery-ui.js"></script>
	<script src="/assets/js/featherlight/featherlight.js"></script>
	<script src="/assets/js/turf/turf.min.js"></script>
	<script src="includes/fancybox/src/js/core.js"></script>

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

		
	</script>

  </head>
  <body>
	<div id="map" class="map"></div>
	<div id="toptext">
		<button style="display:none;" onclick='zoomToCenterAndExtent("LTEwODkwNDUwLjA3Mjc2NDE1OHg0NjUzMTcyLjIxNDA5Mjc5M3gxMw==");'>Zoom Test</button>
		<button style="display:none;" onclick='console.log(buildDatasetsURL());'>Get URL</button>
		<button style="display:none;" onclick='console.log($("#query_has_image").is(":checked"));'>Image Checkbox Value</button>
		<button style="display:none;" onclick='switchToSpotDiv();'>Spot</button>
		<button style="display:none;" onclick='switchToQueryDiv();'>Query</button>
		<button style="display:none;" onclick='toggleSpotQuery();'>Toggle</button>
		<button style="display:none;" onclick='setSelectedSymbol(map, activeGeometry);'>Enable Geometry</button>
		<button style="display:none;" onclick="console.log(idsNames);">log idsnames</button>
		<button style="display:none;" onclick="saveExtent()">Save Extent</button>
		<button style="display:none;" onclick="zoomToSavedExtent()">Zoom to Saved Extent</button>
		<button style="display:none;" onclick="$.featherlight('http://cdn3-www.dogtime.com/assets/uploads/gallery/30-impossibly-cute-puppies/impossibly-cute-puppy-30.jpg');">featherlight test</button>
		<button style="display:none;" onclick="loadFeatures()">LoadFeatures</button>
		<button style="display:none;" onclick="openSideBar();">Open</button>
		<button style="display:none;" onclick="closeSideBar();">Close</button>
		<button style="display:none;" onclick="toggleSideBar();">Toggle</button>
		<div id="myres" style="display:none">span</div>
		<!--<span class="siteTitle">&nbsp;</span>-->
	</div>

	<div id="toptexttitle">
		StraboSpot Public Maps
	</div>

	<div id="toptextdescription">
		Maps diplayed above are publicly available for importing to the StraboSpot app.<br>Please click on individual maps for details.
	</div>

	<img id="toptextlogo" src="/includes/images/strabo_icon_web.png"/>

	<div id="openDatasets">
		<div class="openDatasetsBar">
			Open Datasets
		</div>
		<div id="openDatasetsList">

		</div>
	</div>
	<div id="spotswaiting">
		<table>
			<tr>
				<td><img src="/assets/js/images/box.gif"></td><td nowrap>Loading Spots...</td>
			</tr>
		</table>

		<div id="spotsProgressMessage">
			Gathering maps from server...
		</div>

		<div id="spotsProgressBar">
			<div id="spotsProgressInnerBar">
				&nbsp;
			</div>
		</div>

	</div>

	<div id="datasetswaiting">
		<table>
			<tr>
				<td><img src="/assets/js/images/box.gif"></td><td nowrap>Rebuilding Map...</td>
			</tr>
		</table>
	</div>

	<div id="back_map"><button class="back_map_button" onClick="goBack();"/></div>

	<div id="download_map"><button class="download_map_button tooltip" onClick="openDownloadWindow();"/><span class="tooltiptext">Download Options</span></div>

	<div id="map_home"><button class="map_home_button tooltip" onClick="zoomHome();"/><span class="tooltiptext">Original Zoom Level</span></div>

	<script src="includes/map_interface.js"></script>
	<?php
	if($_GET['c']!=""){
		$c = $_GET['c'];
	?>
	<script>
		zoomToCenterAndExtent('<?php echo $c?>');
	</script>
	<?php
	}
	?>
  </body>
</html>