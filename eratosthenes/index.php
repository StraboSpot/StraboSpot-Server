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
	<title>Eratosthenes Project</title>

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

	</div>

	<div id="insidetext">
		<a href="data/EratosthenesData.xlsx">Download Data</a>&nbsp;&nbsp;<a href="data/EratosthenesMap.kmz">Download Map</a>
	</div>

	<div id="toptexttitle">
		Eratosthenes Project
	</div>

	<img id="toptextlogo" src="includes/images/eratosthenes.png"/>

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
			Gathering spots from server...
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

	<!--
	<div id="map_query"><button class="map_query_button tooltip" onClick="toggleSpotQuery();"/><span class="tooltiptext">Set Search Criteria</span></div>

	<a data-fancybox="modal" data-src="#modal" href="javascript:;">Inline (HTML) Content</a>

	$.fancybox.open({src  : '#queryWindow', type : 'inline', opts : { afterShow : function( instance, current ) {console.info( 'done!' );}}});

	<div id="map_query"><button class="map_query_button tooltip" onClick="$.featherlight('#queryWindow');"/><span class="tooltiptext">Set Search Criteria</span></div>
	-->

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

				<div id="queryTabsWrapper"></div>
			</div>
		</div>
	</div>

	<div class="sidebar right">

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