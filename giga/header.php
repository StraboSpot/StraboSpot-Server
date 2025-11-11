<?php
/**
 * File: header.php
 * Description: Page header template
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

// ********************** Server-Side Google Analytics **********************
require_once("GoogleAnalytics.php");
$script_name = $_SERVER['REQUEST_URI'];
$analytics = new GoogleAnalytics("UA-161637893-1", "GigaBrowser", false);
$analytics->Track("Hit to $script_name");
// **************************************************************************
?>
<!DOCTYPE html>
<!-- Template by html.am -->
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>ROGERS ATLAS OF ROCKS IN THIN SECTION</title>
		<style type="text/css">

			body {
				margin:0;
				padding:0;
				font-family: "Libre Franklin", "Helvetica Neue", helvetica, arial, sans-serif;
				line-height: 1.5em;
			}

			header {
				background-image: url("/giga/bgimage.jpg");
				background-size: 100% auto;
				height: 80px;
				text-align: center;
				color:#FFF;
				font-size: 48px;
				font-weight:bold;
				padding-top:50px;
				-webkit-text-fill-color: white;
				-webkit-text-stroke-width: 1px;
				-webkit-text-stroke-color: #333;
				 text-shadow: 2px 2px 2px #333;
				 white-space: nowrap;
				 overflow: hidden;
				 text-overflow: ellipsis;
			}

			header h1 {
				margin: 0;
				padding-top: 40px;
			}

			main {
				padding-bottom: 10010px;
				margin-bottom: -10000px;
				float: left;
				width: 100%;
			}

			nav {
				padding-bottom: 10010px;
				margin-bottom: -10000px;
				float: left;
				width: 100px;
				margin-left: -100px;
				background: #fff;
			}

			footer {
				clear: left;
				width: 100%;
				background: #fff;
				text-align: center;
				padding: 4px 0;
			}

			#wrapper {
				overflow: hidden;
			}

			#mainwrapper {
				padding-left: 50px;
			}

			#content {
				margin-right: 100px; /* Same as 'nav' width */
			}

			.innertube {
				margin: 15px; /* Padding for content */
				margin-top: 0;
			}

			p {
				
				text-indent: 25px;
			}

			nav ul {
				list-style-type: none;
				margin: 0;
				padding: 0;
			}

			nav ul a {
				color: darkgreen;
				text-decoration: none;
			}

			.rowtitle {
				font-size: 1.2em;
				font-weight: bold;
				color: #333;
				padding-left: 10px;
			}

			.rowDescription {
				padding-left:15px;
			}

			.general_location {
				padding-left:15px;
				font-size:.8em;
				color:#666;
			}

			/* unvisited link */
			a:link {
				color: #7f0000;
				text-decoration: none;
			}

			/* visited link */
			a:visited {
				color: #7f0000;
			}

			/* mouse over link */
			a:hover {
				color: #666;
				text-decoration: none;
			}

			/* selected link */
			a:active {
				color: #333;
			}

			.detailheading {
				font-weight: bold;

			}

			.gigarow {
				background-color: #FFF;
				margin-bottom: 10px;
				padding: 5px;
				border: 1px solid #999;
				box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			}

			.detailwrapper {
				width: 500px;
				background-color: #FFF;
				margin-bottom: 10px;
				padding: 15px;
				border: 1px solid #999;
				box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
			}

			.topBig {
				font-size: 1.5em;
				font-weight: bold;
			}

			.searchitem {
				padding-left: 20px;
			}

			.savebutton {
				transition-duration: 0.4s;
				background-color: #33cc33; /* Green */
				border: none;
				color: white;
				padding: 10px 32px;
				text-align: center;
				text-decoration: none;
				display: inline-block;
				font-size: 16px;
				font-weight: bold;
				border-radius: 12px;
			}

			.allsamplesbutton {
				transition-duration: 0.4s;
				background-color: #33cc33; /* Green */
				border: none;
				color: white;
				padding: 10px 32px;
				text-align: center;
				text-decoration: none;
				display: inline-block;
				font-size: 16px;
				font-weight: bold;
				border-radius: 12px;
			}

			.savebutton:hover {
				background-color: #006600; /* Green */
				color: white;
			}

			.bigtable {
				height:500px;
				width:100%;
				overflow-y:scroll;
				overflow-x:scroll;
				font-size:.8em;
			}

			.bigtable td {
				color: #333;
			}

			.aboutbox {
				font-size: .8em;
				 line-height: 1.4;
			}

		</style>

		<script src="jquery-3.4.1.min.js"></script>

		<script>

			function doSearch() {
				classString = "";
				classString += $("#igneous").is(":checked") ? ',igneous' : '';
				classString += $("#metamorphic").is(":checked") ? ',metamorphic' : '';
				classString += $("#lunar").is(":checked") ? ',lunar' : '';
				classString += $("#meteorite").is(":checked") ? ',meteorite' : '';
				classString = classString!="" ? classString.substring(1) : '';

				typeString = "";
				typeString += $("#volcanic").is(":checked") ? ',volcanic' : '';
				typeString += $("#plutonic").is(":checked") ? ',plutonic' : '';
				typeString += $("#regional_metamorphic").is(":checked") ? ',regional%20metamorphic' : '';
				typeString += $("#contact_metamorphic").is(":checked") ? ',contact%20metamorphic' : '';
				typeString += $("#cumulate").is(":checked") ? ',cumulate' : '';
				typeString += $("#mantle").is(":checked") ? ',mantle' : '';
				typeString = typeString!="" ? typeString.substring(1) : '';

				keywordsString = "";
				keywordsString += $("#keywords").val()!="" ? $("#keywords").val() : '';

				sendString="?";
				sendString += classString != "" ? '&class=' + classString : '';
				sendString += typeString != "" ? '&type=' + typeString : '';
				sendString += keywordsString != "" ? '&keyword=' + keywordsString : '';

				console.log("classString: " + classString);
				console.log("typeString: " + typeString);
				console.log("keywordsString: " + keywordsString);
				console.log("sendString: " + sendString);

				window.location.href='search' + sendString;

			}

			function viewAll() {
				window.location.href='search';
			}

		</script>

	</head>

	<body>

		<header>
			ROGERS ATLAS OF ROCKS IN THIN SECTION
		</header>

		<div id="wrapper">

			<main>
				<div id="content">
					<div class="innertube">
						<div id="mainwrapper">
<?php
if($_SERVER['PHP_SELF']!="/giga/index.php"){
?>
						<div><a href="/giga">Home</a></div>
<?php
}
?>
						<!--- End Header --->
