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


$sample_id = (int)$_GET['s'];

if($sample_id=="" || !is_int($sample_id)){
	exit();
}

include_once "../includes/config.inc.php"; //credentials, etc
include "../db.php"; //postgres database abstraction layer
include "../neodb.php"; //neo4j database abstraction layer
include "../db/strabospotclass.php"; //strabospot specific functions
include_once('../includes/geophp/geoPHP.inc'); //geospatial functions
include_once('../includes/UUID.php'); //UUID Class


$geoPHP = new geoPHP;


//sample 15862012663680


$neorow = $neodb->get_results("match (d:Dataset)-[HAS_SPOT]->(s:Spot)-[HAS_SAMPLE]->(smp:Sample) where smp.id=$sample_id return d,s limit 1;");

if(count($neorow) == 0){
	include '../includes/header.php';
	echo "Error. Sample not found.";
	include '../includes/footer.php';
	exit();
}

$neorow = $neorow[0];

$dataset = $neorow->get("d");
$dataset = $dataset->values();
$dataset_id = $dataset['id'];



$spot = $neorow->get("s");
$spot = $spot->values();
$spot_id = $spot['id'];










	//15890844994687 baraboo 1 spot






/*
		{
			"type": "Feature",
			"geometry": {
				"type": "Point",
				"coordinates": [
					-119.0082604625,
					37.594527
				]
			},
			"properties": {
				"name": "Lake George area",
				"projectname": "Lake George",
				"id": 15385041846501,
				"count": 8,
				"owner": "Marcus Bursik"
			}
		}


		var thing = new ol.geom.Polygon( [[
			ol.proj.transform([-16,-22], 'EPSG:4326', 'EPSG:3857'),
			ol.proj.transform([-44,-55], 'EPSG:4326', 'EPSG:3857'),
			ol.proj.transform([-88,75], 'EPSG:4326', 'EPSG:3857')
		]]);
		var featurething = new ol.Feature({
			name: "Thing",
			geometry: thing
		});
*/

























session_start();
$userlevel = $_SESSION['userlevel'];

if($_GET['datasetid']!=""){
	$sitetitle = "Strabo Dataset";
	$sitebanner = "&nbsp;&nbsp;&nbsp;Strabo Dataset";
}else{
	$sitetitle = "StraboSpot Search";
	$sitebanner = "StraboSpot Search";
}

	$sitetitle = "Spot Details";
	$sitebanner = "Spot Details";








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
	<link rel="stylesheet" href="/assets/js/ol4/ol.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/layerswitcher/layerswitcher.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/jquery-sidebar/jquery.sidebar.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/jquery-ui/jquery-ui.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/featherlight/featherlight.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/ionic/css/ionic.css" type="text/css">
	<link rel="stylesheet" href="includes/map_search.css" type="text/css">
	<link rel="stylesheet" href="/search/includes/fancybox/src/css/core.css" type="text/css">

	<!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
	<script src="/assets/js/ol4/ol.js"></script>
	<script src="/assets/js/layerswitcher/layerswitcher.js"></script>

	<!-- Map Search-Specific Files-->
	<script src="includes/map_search_functions.js"></script>
	<script src="includes/tab_builders.js"></script>
	<script src="includes/strat.js"></script>

	<script src="/search/includes/data_model.js"></script>

	<!-- External Libraries-->
	<script src="/assets/js/underscore/underscore-min.js"></script>
	<script src="/assets/js/jquery/jquery.min.js"></script>
	<script src="/assets/js/jquery-sidebar/jquery.sidebar.min.js"></script>
	<script src="/assets/js/jquery-ui/jquery-ui.js"></script>
	<script src="/assets/js/featherlight/featherlight.js"></script>
	<script src="/assets/js/turf/turf.min.js"></script>
	<script src="/search/includes/fancybox/src/js/core.js"></script>

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
	if($_GET['datasetid']!=""){
		$datasetid = $_GET['datasetid'];
		//$onload = " onload = \"gotoDataset($datasetid)\"";
		$onload = " onload = \"testSpotLand()\"";



		include_once "../includes/config.inc.php";
		include "../neodb.php"; //neo4j database abstraction layer

		$querystring="match (d:Dataset) where d.id = $datasetid return d limit 1";

		$rows = $neodb->get_results("$querystring");
		$row = $rows[0];
		$row = $row->get("d");
		$d=$row->values();

		$datasetid = $d['id'];
		$centroid = $d['centroid'];
		$datasetname = $d['name'];
		$centroid = str_replace("POINT (","",$centroid);
		$centroid = str_replace(")","",$centroid);
		$centroid = explode(" ",$centroid);
		$longitude = $centroid[0];
		$latitude = $centroid[1];

		echo '<script type="application/ld+json">
		{
		  "@context": {
			"@vocab": "http://schema.org/",
			"re3data": "http://example.org/re3data/0.1/",
			"earthcollab": "https://library.ucar.edu/earthcollab/schema#",
			"geolink": "http://schema.geolink.org/1.0/base/main#",
			"geolink-vocab": "http://schema.geolink.org/1.0/voc/local#",
			"vivo": "http://vivoweb.org/ontology/core#",
			"dcat": "http://www.w3.org/ns/dcat#",
			"dbpedia": "http://dbpedia.org/resource/",
			"geo-upper": "http://www.geoscienceontology.org/geo-upper#"
		  },
		  "@type": "Dataset",
		  "isAccessibleForFree": true,
		  "description": "'.$datasetname.'",
		  "includedInDataCatalog": {
			"url": "https://strabospot.org",
			"@id": "https://strabospot.org"
		  },
		  "keywords": "strabospot, structure, tectonics",
		  "name": "'.$datasetname.'",
		  "url": "https://strabospot.org/search/ds/'.$datasetid.'",
		  "provider": {
			"@type": "Organization",
			"legalName": "StraboSpot",
			"name": "StraboSpot",
			"url": "https://strabospot.org",
			"@id": "https://strabospot.org"
		  },
		  "publisher": {
			"@type": "Organization",
			"description": "StraboSpot",
			"url": "https://strabospot.org",
			"name": "StraboSpot",
			"@id": "https://strabospot.org"
		  },
		  "spatialCoverage": [
			{
			  "@type": "Place",
			  "geo": {
				"latitude": '.$latitude.',
				"longitude": '.$longitude.',
				"@type": "GeoCoordinates"
			  }
			}
		  ],
		  "measurementTechnique": [],
		  "@id": "https://strabospot.org/search/ds/'.$datasetid.'"
		}
		</script> ';



	}
	?>

  </head>

  <body<?php echo $onload?>>
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
		<button style="display:none;" onclick="gotoDataset(15246069817544);">Go To Dataset</button>
		<button style="display:none;" onclick="addFeatures(15860241101933);">Go To Dataset</button>
		<button style="display:none;" onclick="testSpotLand();">Test Spot Land</button>
		<div id="myres" style="display:none">span</div>
		<span class="siteTitle">&nbsp;</span>
	</div>

	<div id="toptexttitle">
		<?php echo $sitebanner?>
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

	<div id="back_map"><button class="back_map_button" onClick="goBack();"/></div>

	<div id="download_map"><button class="download_map_button tooltip" onClick="openDownloadWindow();"/><span class="tooltiptext">Download Options</span></div>

	<?php
	//if($userlevel >=5){
	?>
	<div id="link_map"><button class="link_map_button" onClick="showStaticUrl();"/></div>
	<?php
	//}
	?>

	<div id="map_home"><button class="map_home_button tooltip" onClick="zoomHome();"/><span class="tooltiptext">Original Zoom Level</span></div>

	<!--
	<div id="map_query"><button class="map_query_button tooltip" onClick="toggleSpotQuery();"/><span class="tooltiptext">Set Search Criteria</span></div>

	<a data-fancybox="modal" data-src="#modal" href="javascript:;">Inline (HTML) Content</a>

	$.fancybox.open({src  : '#queryWindow', type : 'inline', opts : { afterShow : function( instance, current ) {console.info( 'done!' );}}});

	<div id="map_query"><button class="map_query_button tooltip" onClick="$.featherlight('#queryWindow');"/><span class="tooltiptext">Set Search Criteria</span></div>
	-->

	<div id="map_query"><button class="map_query_button tooltip" onClick="$.fancybox.open({src  : '#queryWindow', type : 'inline', opts : { afterShow : function( instance, current ) {console.info( 'done!' );}}});"/><span class="tooltiptext">Set Search Criteria</span></div>

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
							<button class="downloadsubmit" onclick="downloadData('shapefile');"><span>Shapefile</span></button>
						</td>
						<td>
							Download StraboSpot data in shapefile format.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<button class="downloadsubmit" onclick="downloadData('kml');"><span>KMZ</span></button>
						</td>
						<td>
							Download StraboSpot data in .kmz format suitable for Google Earth.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<button class="downloadsubmit" onclick="downloadData('xls');"><span>Excel</span></button>
						</td>
						<td>
							Download StraboSpot data in Microsoft Excel spreadsheet format.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<button class="downloadsubmit" onclick="downloadData('stereonet');"><span>Stereonet</span></button>
						</td>
						<td>
							Download StraboSpot data in format suitable for Rick Allmendinger's Stereonet software.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<button class="downloadsubmit" onclick="downloadData('fieldbook');"><span>Field Book</span></button>
						</td>
						<td>
							Download StraboSpot data in PDF Field Book format.
						</td>
					</tr>
					<tr>
						<td valign="top">
							<button class="downloadsubmit" onclick="downloadData('stratsection');"><span>Strat Section(s)</span></button>
						</td>
						<td>
							Download StraboSpot strat section SVG file(s).
						</td>
					</tr>

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


	<div class="sidebar right" style="overflow-y: scroll;">

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

	<?php
	if($_GET['datasetid']!=""){
		$c = $_GET['datasetid'];
	?>
	<script>
		gotoDataset(<?php echo $c?>);
	</script>
	<?php
	}
	?>

	<script>
		addFeatures(<?php echo $dataset_id?>);

		setTimeout(function () {
			map.getLayers().forEach(function(layer) {
			//if(layer instanceof ol.layer.Vector){
				if(layer.get("name")=="featureLayer"){
					console.log(layer.get("name"));
					layer.getLayers().forEach(function(insideLayer){

						//console.log("inside layer");

						var source = insideLayer.getSource();

						var mappedFeatures = source.getFeatures();

						//console.log(mappedFeatures);

						_.each(mappedFeatures, function(thisFeature){

							if(thisFeature.get("id") == <?php echo $spot_id?>){
								console.log(thisFeature);
								clickedMapFeature = thisFeature.get('id');
								setSelectedSymbol(map, thisFeature.getGeometry());
								getCurrentSpot();
								console.log(currentSpot);
								updateSidebar();
								openSideBar();
							}
						});

						//source.getFeatures.forEach(function(thisFeature){
						//	console.log(thisFeature);
						//});
					});

				}
			//}
			});
			$("#tabs").tabs("option", "active", 6);
		}, 1000);








		var testSpotLand = function(){

			//console.log(loadedFeatures.features);

			//_.each(loadedFeatures.features, function(thisfeat){
			//	console.log("id: " + thisfeat.properties.id);
			//	if(thisfeat.properties.id == <?php echo $spot_id?>){
			//		console.log("here comes a feature:");
			//		console.log(thisfeat);
			//
			//		clickedMapFeature = thisfeat.properties.id;


			//	}
			//});



					//var source = layer.getSource();


			    	//setSelectedSymbol(map, thisfeat.getGeometry());
					//getCurrentSpot();
					//updateSidebar();
					//openSideBar();

			//_.each(featureLayer.getLayers(), function(thisLayer){
			//	var thisSource = thisLayer.getFeatures();
			//
			//})

			//var features = featureLayer.getFeatures();

			//_.each(featureLayer.getLayers(), function(thisLayer){
				//var thisSource = thisLayer.getFeatures();
			//	console.log(thisLayer);
			//
			//})


// 			map.getLayers().forEach(function(layer) {
// 				//If this is actually a group, we need to create an inner loop to go through its individual layers
// 				if(layer instanceof ol.layer.Group) {
// 					layer.getLayers().forEach(function(groupLayer) {
// 						//If this is a vector layer, add it to our extent
// 						if(layer instanceof ol.layer.Vector)
// 							console.log("found vector layer");
// 					});
// 				}
// 				else if(layer instanceof ol.layer.Vector){
// 				   console.log("found outervector layer");
// 				   var source = layer.getSource();
// 				   //source.getFeatures().forEach(function(thisFeature) {
// 				   //		console.log("found feature");
// 				   //})
// 				}
// 			});

// 			map.getLayers().forEach(function(layer) {
// 				//if(layer instanceof ol.layer.Vector){
// 					if(layer.get("name")=="featureLayer"){
// 						console.log(layer.get("name"));
// 						var source = layer.getSource();
// 						source.getFeatures.forEach(function(thisFeature){
// 							console.log(thisFeature);
// 						});
// 					}
// 				//}
// 			});

			map.getLayers().forEach(function(layer) {
				//if(layer instanceof ol.layer.Vector){
					if(layer.get("name")=="featureLayer"){
						console.log(layer.get("name"));
						layer.getLayers().forEach(function(insideLayer){

							//console.log("inside layer");

							var source = insideLayer.getSource();

							var mappedFeatures = source.getFeatures();

							//console.log(mappedFeatures);

							_.each(mappedFeatures, function(thisFeature){

								if(thisFeature.get("id") == <?php echo $spot_id?>){
									console.log(thisFeature);
									clickedMapFeature = thisFeature.get('id');
									setSelectedSymbol(map, thisFeature.getGeometry());
									getCurrentSpot();
									console.log(currentSpot);
									updateSidebar();
									openSideBar();
								}
							});

							//source.getFeatures.forEach(function(thisFeature){
							//	console.log(thisFeature);
							//});
						});

					}
				//}
			});

		}

	</script>



  </body>
</html>