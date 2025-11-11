<?php
/**
 * File: view.php
 * Description: Displays information from micro_projectmetadata table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

//include("../logincheck.php");

session_start();

include("../prepare_connections.php");

//$userpkey
$p = $_GET['p'];

//determine if project exists
//$count = $db->get_var("select count(*) from micro_projectmetadata where id = $p and (ispublic or userpkey = $userpkey)");
$row = $db->get_row("select * from micro_projectmetadata where id = $p and (ispublic or userpkey = $userpkey)");
if($row->id == ""){
	echo "Unable to load project $p.";
	exit();
}


?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="assets/microView.css" type="text/css" />
<link rel="stylesheet" href="assets/jquery-ui/jquery-ui.css">
<script src='assets/jquery.min.js'></script>
<script src="assets/jquery-ui/jquery-ui.js"></script>
<script src='assets/microFields.js'></script>
<script src='assets/microView_config.js'></script>
<script src='assets/microView.js'></script>
<script src='assets/fabric.min.js'></script>
<title>StraboMicro Viewer</title>
<meta property='og:site_name' content='StraboMicro Viewer' />
<meta property='og:title' content='StraboMicro Viewer' />
<meta property='og:description' content='July, 2023 ﻿' />
<meta property='og:url' content='https://www.strabospot.org' />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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
<link rel="manifest" href="/assets/bicons/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/bicons/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
</head>
<body>
	<div id="whole-doc">
		<div id="content-wrapper">
			<div class="projectWrapper">
				<div id="topBar" style="display:none;">
					<img id="topLogo" src="assets/strabo_icon_small.png"> StraboMicro
				</div>
				<div id="topHRDiv" style="display:none;">
					<hr id="topHR"/>
				</div>
				<div style="float:left; width: 240px;">&nbsp;</div>
				<div style="float:left; width: 760px; padding-left:10px; text-align:center;"><h2 id="projectTitle">Project: Test Project</h2></div>

				<div style="float:left; width: 300px; text-align:left; padding-left: 100px; vertical-align:baseline; font-size:1.2em; background-color:white; padding-bottom:10px;">
					<a href="/download_micro_file?project_id=<?php echo $p?>"><img src="/assets/files/micro_download.png" width="13px" style="vertical-align:baseline;"> Download .SMZ</a><br>
					<a href="/download_micro_pdf?project_id=<?php echo $p?>" target="_blank"><img src="/assets/files/micro_download.png" width="13px" style="vertical-align:baseline;"> Download .PDF</a><br>
<?php
if($row->userpkey == $userpkey){
?>
					<a href="/share_micro_file?project_id=<?php echo $p?>" target="_blank"><img src="/assets/files/micro_share.png" width="13px" style="vertical-align:baseline;"> Share Project File</a>
<?php
}
?>
				</div>

				<div style="clear:left;"></div>
				<div id="leftColumn"></div>
				<div id="centerColumn">
					<div id="loadingMessage">
						<div class="floatLeft"><img src="assets/loading.gif"></div>
						<div class="floatLeft" style="padding-left: 10px;">Loading Image...</div>
						<div class="clearLeft"></div>
					</div>
					<div id="notFoundImage" style="display:none;"><img src="assets/notFound.jpg" width="750"></div>
					<div id="outsideWrapper">
						<div id="insideWrapper">
							<img src="assets/white.png" id="mainImage">
							<canvas id="coveringCanvas"></canvas>
						</div>
					</div>
				</div>
				<div id="rightColumn">
					<div id="rightHeader"></div>
					<div id="accordion"></div>
				</div>
				<div class="clearLeft">
				</div>
			</div>
		</div>
		<div id="footer" style="display:none;">
		The data presented above was exported from StraboMicro.<br>
		StraboMicro can be downloaded <a href="https://strabospot.org/micro" target="_blank">here</a>.
		</div>
	</div>
	<script>
	document.addEventListener("DOMContentLoaded", function(event){
		loadProject();
	});
	</script>
</body>
</html>