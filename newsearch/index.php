<?

session_start();
$userlevel = $_SESSION['userlevel'];
$userpkey = $_SESSION['userpkey'];

//print_r($_SESSION);exit();

if($_GET['datasetid']!=""){
	$sitetitle = "Strabo Dataset";
	$sitebanner = "&nbsp;&nbsp;&nbsp;Strabo Dataset";
}else{
	$sitetitle = "StraboSpot Search";
	$sitebanner = "StraboSpot Search";
}

?>
<!DOCTYPE html>
<html>
  <head>
	<title><?=$sitetitle?></title>

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
	<link rel="stylesheet" href="includes/ol.css" type="text/css">
	<link rel="stylesheet" href="includes/layerswitcher.css" type="text/css">

	<link rel="stylesheet" href="/assets/js/jquery-sidebar/jquery.sidebar.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/jquery-ui/jquery-ui.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/featherlight/featherlight.css" type="text/css">
	<link rel="stylesheet" href="/assets/js/ionic/css/ionic.css" type="text/css">
	<link rel="stylesheet" href="includes/new_map_search.css" type="text/css">
	<link rel="stylesheet" href="includes/fancybox/src/css/core.css" type="text/css">
	<link rel="stylesheet" href="includes/sidesearch.css" type="text/css">
	<link rel="stylesheet" href="includes/w3.css" type="text/css">
	
	

	<!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
	<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
	<script src="/assets/js/ol4/ol.js"></script>
	<script src="/assets/js/layerswitcher/layerswitcher.js"></script>

	<!-- Map Search-Specific Files-->
	<script src="includes/map_search_functions.js"></script>
	<script src="includes/tab_builders.js"></script>
	<script src="includes/strat.js"></script>
	<script src="includes/data_model.js"></script>
	<script src="includes/sidebar.js"></script>
	
	<!-- External Libraries-->
	<script src="/assets/js/underscore/underscore-min.js"></script>

	<script src="/assets/js/jquery/jquery.min.js"></script>
	<script src="/assets/js/jquery-sidebar/jquery.sidebar.min.js"></script>
	<script src="/assets/js/jquery-ui/jquery-ui.js"></script>

	<script src="includes/js/jquery-debounce.js"></script>
	


	<script src="/assets/js/featherlight/featherlight.js"></script>
	<script src="/assets/js/turf/turf.min.js"></script>
	<script src="includes/fancybox/src/js/core.js"></script>
	
	<link rel="stylesheet" href="includes/js/easyautocomplete/dist/easy-autocomplete.css" type="text/css">
	<script src="includes/js/easyautocomplete/dist/jquery.easy-autocomplete.js"></script>

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


  
    <?
    if($_GET['datasetid']!=""){
    	$datasetid = $_GET['datasetid'];
    	$onload = " onload = \"gotoDataset($datasetid)\"";

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
























































  <body<?=$onload?>>

	
	<div class="newSearchSideNav"  id="mySidebar">


		<div class="sidenav">

			<div class="sideBarHeader">
				<div style="text-align: center;padding-bottom:10px;padding-top:10px;">
					<image src="includes/images/strabo-search-header.jpg" width="350px" />
				</div>
			</div>
			
			<div class="sideBarContent">
				<div style="font-weight:bold;">
				Set Search Criteria:
				</div>
				<div id="rowContainer">












					
					
				</div>
				
				<!--<input type="checkbox" name="dldataset" class="dldatasets" value="foo" checked="checked\"> checkbox here-->

				<div id="performingSearch" style="width: 450px; padding-top:50px; font-weight:700; color:#666; display: none;">
					<div style="text-align:center;">
						<div style="float: left; padding-left:120px;">&nbsp;</div>
						<div style="float: left; display: inline-block;"><img src="includes/images/box.gif"/></div>
						<div style="float: left; display: inline-block; padding-top:15px;">Performing Search...</div>
						<div style="clear: left;"></div>
					</div>
				</div>
				<div style="width: 100%; padding-top:10px; padding-bottom: 10px; font-weight: 700; color: #b30000; font-size: 13px;">
					<div id="searchCountResults" style="text-align:center;"></div>
				</div>


				<div id="searchResults"></div>
			</div>
			
			<div class="sideBarFooter">
				<div>
					<!--<button onclick="doNewSearchTest();">here</button>-->
					
					<div style="padding-top:10px;">
						<div style="float:left;text-align:center;width:210px;">
							<button id="sideNewSearchButton" class="bottomLeftButtons" style="display:none;" onclick="newSearchReset();"><span>New Search</span></button>
						</div>
						<div style="float:left;text-align:center;width:210px;">
							<button id="sideDownloadButton" class="bottomLeftButtons" style="display:none;" onclick="openDownloadWindow();"><span>Download</span></button>
						</div>
						
					</div>
					
				</div>
			</div>


		</div>

		<div id="myres" style="display:none;"></div>
	</div>

	<div class="newSearchMapWrapper">
		<div id="map" >

			<div id="back_map"><button class="back_map_button" onClick="goBack();"/></div>
	
			<div id="download_map"><button class="download_map_button tooltip" onClick="openDownloadWindow();"/><span class="tooltiptext">Download Options</span></div>

			<div id="map_home"><button class="map_home_button tooltip" onClick="zoomHome();"/></div>

			<?
			if($userlevel >=566){
			?>
			<div id="link_map"><button class="link_map_button" onClick="showStaticUrl();"/></div>
			<?
			}
			?>
			


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


    <div id="openDatasets">
    	<div class="openDatasetsBar">
    		Open Datasets
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
							<button class="downloadsubmit" onclick="downloadData('stratsection');"><span>Strat&nbsp;Section(s)</span></button>
						</td>
						<td>
							Download StraboSpot strat section SVG file(s).
						</td>
					</tr>
<?
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
<?
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
    <?
    if($_GET['c']!=""){
    	$c = $_GET['c'];
    ?>
    <script>
    	zoomToCenterAndExtent('<?=$c?>');
    </script>
    <?
    }
    ?>

    <?
    if($_GET['sdatasetid']!=""){
    	$c = $_GET['datasetid'];
    ?>
    <script>
    	gotoDataset(<?=$c?>);
    </script>
    <?
    }
    ?>
	<script>
		//sidesearch_open();
		//$(".ol-viewport").append('    ');
		addRow();
	</script>
	

	
  </body>
</html>