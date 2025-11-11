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

?>
<!DOCTYPE html>
<html>
  <head>
	<title>Micro Installs</title>

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
	<script src="/assets/js/ol4/ol.js"></script>
	<script src="/assets/js/layerswitcher/layerswitcher.js"></script>

	<!-- Map Search-Specific Files-->
	<script src="includes/map_search_functions.js"></script>
	<!--<script src="includes/tab_builders.js"></script>
	<script src="includes/strat.js"></script>
	<script src="includes/data_model.js"></script>
	<script src="includes/sidebar.js"></script>-->



	<!-- External Libraries-->
	<script src="/assets/js/underscore/underscore-min.js"></script>

	<script src="/assets/js/jquery/jquery.min.js"></script>
	<script src="/assets/js/jquery-sidebar/jquery.sidebar.min.js"></script>
	<script src="/assets/js/jquery-ui/jquery-ui.js"></script>

	<script src="includes/js/jquery-debounce.js"></script>

  </head>



  <body>



	<div id="map" >
	</div>

	<div id="installimagediv">
		<image id="installimage" src="includes/images/strabo_installs.png">
	</div>



	<script src="includes/map_interface.js"></script>




  </body>
</html>