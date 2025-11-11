<?php
/**
 * File: mheader.php
 * Description: Page header template
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("sessioncheck.php");

session_start();

if($_SERVER['SCRIPT_NAME']=="/index.php") $landing = " landing";

if($_SESSION['loggedin']=="yes"){
	$username=$_SESSION['username'];
	$bartext='<div align="right" style="font-size:.8em;">logged in as: '.$username.' (<a href="/logout">logout</a>)</div>';
	$accountheader = $username;
}else{
	$accountheader = "Account";
	$bartext="";
}

include_once("prepare_connections.php");

$showinstrumentmenu = false;
if($_SESSION['userpkey']!=""){
	if($db){
		$instcount = $db->get_var("
			select count(*) from instrument_users where users_pkey = ".$_SESSION['userpkey']."
		");


		if(in_array($userpkey, $admin_pkeys) || $instcount > 0){
			$showinstrumentmenu = true;
		}
	}
}

$showadminmenu = false;
if($_SESSION['userpkey']!=""){
	if(in_array($userpkey, $admin_pkeys)){
		$showadminmenu = true;
	}
}

$showdois = false;
if($_SESSION['userpkey']!=""){
	if($db){
		$doicount = $db->get_var("
			select count(*) from dois where user_pkey = ".$_SESSION['userpkey']."
		");

		if($doicount > 0){
			$showdois = true;
		}
	}
}

if($userpkey != ""){
	$showcollaborations = false;
	$collaborationcount = $db->get_var("select count(*) from collaborators where collaborator_user_pkey = $userpkey and accepted = true;");
	if($collaborationcount > 0){
		$showcollaborations = true;
	}
}

if($userpkey == "adsf"){
	echo "showinstrumentmenu: $showinstrumentmenu<br>";
	echo "showadminmenu: $showadminmenu<br>";
	echo "showdois: $showdois<br>";

}

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>StraboSpot</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="/massets/css/main.css" />
		<noscript><link rel="stylesheet" href="/massets/css/noscript.css" /></noscript>
		<link rel="apple-touch-icon" sizes="57x57" href="/massets/bicons/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/massets/bicons/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/massets/bicons/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/massets/bicons/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/massets/bicons/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/massets/bicons/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/massets/bicons/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/massets/bicons/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/massets/bicons/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/massets/bicons/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/massets/bicons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/massets/bicons/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/massets/bicons/favicon-16x16.png">

		<script src='/assets/js/jquery/jquery.min.js'></script>
		<script src='/assets/js/k/k.js'></script>
		<link rel="stylesheet" href="/assets/js/k/k.css" type="text/css" />
		<script src="/assets/js/featherlight/featherlight.js"></script>
		<script src="/assets/js/clipboardjs/clipboard.js"></script>

		<?php
		if($_SERVER['PHP_SELF']=="/register.php"){
		?>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<?php
		}
		?>
	</head>
	<body class="is-preload<?php echo $landing?>">
		<div id="page-wrapper">

			<!-- Header -->
				<header id="header">

					<?php
					if($_SESSION['loggedin']=="yes"){
						$showname = " (" . $username . ")";
					}
					?>
					<h1 id="logo"><a href="/">STRABOSPOT<span id="titleEmail"><?php echo $showname?></span></a></h1>
					<nav id="nav">
						<ul>
							<li><a href="#">About</a>
								<ul>
									<li><a href="/overview">Overview</a></li>
									<li><a href="/privacy">Privacy Policy</a></li>
								</ul>
							</li>



							<li><a href="/api">API</a></li>

							<li><a href="#">Software</a>
								<ul>
									<li>
										<a href="#">StraboField</a>
										<ul>
											<li><a href="/supporteddevices">Supported Devices</a></li>
											<li><a href="/downloadapp">Download StraboField</a></li>
										</ul>
									</li>

									<li>
										<a href="#">StraboTools</a>
										<ul>
											<li><a href="/strabotoolsdownload">What is StraboTools?</a></li>
											<li><a href="https://apps.apple.com/us/app/strabotools/id1496239162?ls=1" target="_blank">Download StraboTools</a></li>
										</ul>
									</li>

									<li>
										<a href="#">StraboMicro</a>
										<ul>
											<li><a href="/whatisstrabomicro">What is StraboMicro?</a></li>
											<li><a href="/micro">Download StraboMicro</a></li>
										</ul>
									</li>

									<li>
										<a href="/experimental">StraboExperimental (Beta)</a>
									</li>

									<li>
										<a href="#">StraboSpot Offline</a>
										<ul>
											<li><a href="/whatisstrabospotoffline">What is StraboSpot Offline?</a></li>
											<li><a href="/StraboSpotOffline.zip">Download StraboSpot Offline</a></li>
										</ul>
									</li>
								</ul>
							</li>


							<li>
								<a href="#">Search</a>
								<ul>
									<li><a href="/fullsearch">Search All Strabo Data</a></li>
									<li><a href="/search" target="_blank">Search Strabo Field Data</a></li>
									<li><a href="/publicmaps" target="_blank">Search Public Maps</a></li>
								</ul>
							</li>

							<li><a href="/help">Help</a></li>

							<li><a href="/teaching">Teaching</a></li>

							<li>
								<a href="#"><?php echo $accountheader?></a>
								<ul>
								<?php
								if($_SESSION['loggedin']=="yes"){
								?>

									<li><a href="/my_field_data">My StraboField Data</a></li>
									<li><a href="/my_micro_data">My StraboMicro Data</a></li>
									<li><a href="/my_experimental_data">My StraboExperimental Data</a></li>
<?php
if($showcollaborationsxxx){
?>
									<li><a href="/my_collaborations">My Collaborations</a></li>
<?php
}
?>
									<li><a href="/load_shapefile">Load Shapefile</a></li>
									<li><a href="/geotiff">My Maps</a></li>
									<li><a href="/my_jwts">My JWTs</a></li>
									<li><a href="/versioning">Versioning</a></li>






<?php
if($showdois){
?>
									<li><a href="/my_dois">My DOIs</a></li>
<?php
}

if($showinstrumentmenu){
?>
									<li><a href="/instrumentcatalog">My Instruments</a></li>
<?php
}

if($showadminmenu){
?>
									<li><a href="/institutes">Institutes</a></li>
<?php
}
if($_SESSION['userpkey']=="3"){
?>
									<li><a href="/microErrorReports">Micro Error Reports</a></li>
<?php
}
?>






<?php
if(in_array($userpkey, array(3, 7217))){
?>
									<li><a href="/mailusers">Strabo Users List</a></li>
									<li><a href="/manual_upload">Upload Strabo Manuals</a></li>
<?php
}
?>
									<li><a href="/change_password">Change Password</a></li>
									<li><a href="/delete_account">Delete Account</a></li>
									<li><a href="/logout">Logout</a></li>
								<?php
								}else{
								?>
									<li><a href="/login">Login</a></li>
									<li><a href="/register">Register</a></li>
								<?php
								}
								?>
								</ul>
							</li>

						</ul>
					</nav>
				</header>
