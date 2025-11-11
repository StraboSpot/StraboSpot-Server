<?php
/**
 * File: devheader.php
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

if($_SESSION['loggedin']=="yes"){
	$username=$_SESSION['username'];
	$bartext='<div align="right" style="font-size:.8em;">logged in as: '.$username.' (<a href="/logout">logout</a>)</div>';
}else{
	$bartext="";
}

$scriptname=$_SERVER['SCRIPT_NAME'];

if($scriptname=="/index.php"){
	$homeactive="active";
}elseif($scriptname=="/overview.php" || $scriptname=="/news.php" || $scriptname=="/privacy.php"){
	$aboutactive="active";
}elseif($scriptname=="/login.php" ||
		$scriptname=="/register.php" ||
		$scriptname=="/load_shapefile.php" ||
		$scriptname=="/my_data.php" ||
		$scriptname=="/view_dataset.php"||
		$scriptname=="/koboforms.php"||
		$scriptname=="/viewform.php"||
		$scriptname=="/new_project.php"||
		$scriptname=="/view_project.php"||
		$scriptname=="/edit_project.php"||
		$scriptname=="/change_password.php"||
		$scriptname=="/versioning.php"
		){
	$accountactive="active";
}elseif($scriptname=="/api.php"){
	$apiactive="active";
}elseif($scriptname=="/downloadapp.php" || $scriptname=="/requestaccess.php"){
	$appactive="active";
}elseif($scriptname=="/helppage.php"){
	$helpactive="active";
}else{
	$homeactive="active";
}

?><!DOCTYPE html>
<html>
<head>
<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-107694781-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-107694781-1');
</script>
<title>StraboSpot</title>
<meta property='og:site_name' content='StraboSpot' />
<meta property='og:title' content='StraboSpot' />
<meta property='og:description' content='January, 2015 ﻿' />
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

<link rel="stylesheet" href="/assets/files/buildtime.css" type="text/css" />
<link rel='stylesheet' type='text/css' href='/assets/files/fancybox.css' />
<link rel='stylesheet' type='text/css' href='/assets/files/main_style.css%3F1422559220.css' title='wsite-theme-css' />
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,700,400italic,700italic&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css' />
<link href='https://fonts.googleapis.com/css?family=Alice&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css' />
<link href='https://fonts.googleapis.com/css?family=Raleway:400,300,200,700&amp;subset=latin,latin-ext' rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="/assets/js/featherlight/featherlight.css" type="text/css">
<style type='text/css'>
.wsite-elements.wsite-not-footer div.paragraph, .wsite-elements.wsite-not-footer p, .wsite-elements.wsite-not-footer .product-block .product-title, .wsite-elements.wsite-not-footer .product-description, .wsite-elements.wsite-not-footer .wsite-form-field label, .wsite-elements.wsite-not-footer .wsite-form-field label, #wsite-content div.paragraph, #wsite-content p, #wsite-content .product-block .product-title, #wsite-content .product-description, #wsite-content .wsite-form-field label, #wsite-content .wsite-form-field label, .blog-sidebar div.paragraph, .blog-sidebar p, .blog-sidebar .wsite-form-field label, .blog-sidebar .wsite-form-field label {}
#wsite-content div.paragraph, #wsite-content p, #wsite-content .product-block .product-title, #wsite-content .product-description, #wsite-content .wsite-form-field label, #wsite-content .wsite-form-field label, .blog-sidebar div.paragraph, .blog-sidebar p, .blog-sidebar .wsite-form-field label, .blog-sidebar .wsite-form-field label {}
.wsite-elements.wsite-footer div.paragraph, .wsite-elements.wsite-footer p, .wsite-elements.wsite-footer .product-block .product-title, .wsite-elements.wsite-footer .product-description, .wsite-elements.wsite-footer .wsite-form-field label, .wsite-elements.wsite-footer .wsite-form-field label{}
.wsite-elements.wsite-not-footer h2, .wsite-elements.wsite-not-footer .product-long .product-title, .wsite-elements.wsite-not-footer .product-large .product-title, .wsite-elements.wsite-not-footer .product-small .product-title, #wsite-content h2, #wsite-content .product-long .product-title, #wsite-content .product-large .product-title, #wsite-content .product-small .product-title, .blog-sidebar h2 {}
#wsite-content h2, #wsite-content .product-long .product-title, #wsite-content .product-large .product-title, #wsite-content .product-small .product-title, .blog-sidebar h2 {}
.wsite-elements.wsite-footer h2, .wsite-elements.wsite-footer .product-long .product-title, .wsite-elements.wsite-footer .product-large .product-title, .wsite-elements.wsite-footer .product-small .product-title{}
#wsite-title {}
.wsite-menu-default a {}
.wsite-menu a {}
.wsite-image div, .wsite-caption {}
.galleryCaptionInnerText {}
.fancybox-title {}
.wslide-caption-text {}
.wsite-phone {}
.wsite-headline {}
.wsite-headline-paragraph {}
.wsite-button-inner {}
.wsite-not-footer blockquote, #wsite-com-product-tab blockquote {}
.wsite-footer blockquote {}
.blog-header h2 a {}
#wsite-content h2.wsite-product-title {}
.wsite-product .wsite-product-price a {}


.apidoc {
	font: 13px "PT Sans","Helvetica Neue",Helvetica,Arial,sans-serif;
	font-family: "PT Sans","Helvetica Neue",Helvetica,Arial,sans-serif;
	font-style: normal;
	font-variant: normal;
	font-weight: normal;
	font-size: 13px;
	line-height: normal;
	font-size-adjust: none;
	font-stretch: normal;
	-x-system-font: none;
	-moz-font-feature-settings: normal;
	-moz-font-language-override: normal;
}



.apidoc h3 {
	color: #333333;
	font-size: 24px;
}

.apidoc  code {
	border-top: 1px solid #FFE6E6;
	border-bottom: 1px solid #FFE6E6;
  background-color: #FFE6E6;
}

pre, code {
	font-family: "Ubuntu Mono",Menlo,Consolas,Inconsolata,monospace;
	font-size: 14px;
}
code {
	padding: 2px 4px;
	font-size: 90%;
	color: #C7254E;
	background-color: #F9F2F4;
	border-radius: 4px;
}
code, kbd, pre, samp {
	font-family: Menlo,Monaco,Consolas,"Courier New",monospace;
}
code, kbd, pre, samp {
	font-family: monospace,monospace;
	font-size: 1em;
}
* {
	box-sizing: border-box;
}

.programlisting {
	display: block;
	padding: 9.5px;
	margin: 0px 0px 10px;
	font-size: 13px;
	line-height: 1.42857;
	color: #333;
	word-break: break-all;
	word-wrap: break-word;
	background-color: #F5F5F5;
	border: 1px solid #CCC;
	border-radius: 4px;
}

li.listitem {
	padding-bottom:7px;
	font-size: 13px;
}

.strabotable {
	margin:0px;padding:0px;
	width:100%;
	box-shadow: 10px 10px 5px #888888;
	border:1px solid #000000;

	-moz-border-radius-bottomleft:0px;
	-webkit-border-bottom-left-radius:0px;
	border-bottom-left-radius:0px;

	-moz-border-radius-bottomright:0px;
	-webkit-border-bottom-right-radius:0px;
	border-bottom-right-radius:0px;

	-moz-border-radius-topright:0px;
	-webkit-border-top-right-radius:0px;
	border-top-right-radius:0px;

	-moz-border-radius-topleft:0px;
	-webkit-border-top-left-radius:0px;
	border-top-left-radius:0px;
}.strabotable table{
	border-collapse: collapse;
		border-spacing: 0;
	width:100%;
	height:100%;
	margin:0px;padding:0px;
}.strabotable tr:last-child td:last-child {
	-moz-border-radius-bottomright:0px;
	-webkit-border-bottom-right-radius:0px;
	border-bottom-right-radius:0px;
}
.strabotable table tr:first-child td:first-child {
	-moz-border-radius-topleft:0px;
	-webkit-border-top-left-radius:0px;
	border-top-left-radius:0px;
}
.strabotable table tr:first-child td:last-child {
	-moz-border-radius-topright:0px;
	-webkit-border-top-right-radius:0px;
	border-top-right-radius:0px;
}.strabotable tr:last-child td:first-child{
	-moz-border-radius-bottomleft:0px;
	-webkit-border-bottom-left-radius:0px;
	border-bottom-left-radius:0px;
}.strabotable tr:hover td{

}
.strabotable tr:nth-child(odd){ background-color:#e5e5e5; }
.strabotable tr:nth-child(even)    { background-color:#ffffff; }.strabotable td{
	vertical-align:middle;


	border:1px solid #000000;
	border-width:0px 1px 1px 0px;
	text-align:left;
	padding:7px;
	font-size:11px;
	font-family:Helvetica;
	font-weight:normal;
	color:#000000;
}.strabotable tr:last-child td{
	border-width:0px 1px 0px 0px;
}.strabotable tr td:last-child{
	border-width:0px 0px 1px 0px;
}.strabotable tr:last-child td:last-child{
	border-width:0px 0px 0px 0px;
}
.strabotable tr:first-child td{
		background:-o-linear-gradient(bottom, #cccccc 5%, #b2b2b2 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #cccccc), color-stop(1, #b2b2b2) );
	background:-moz-linear-gradient( center top, #cccccc 5%, #b2b2b2 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#cccccc", endColorstr="#b2b2b2");	background: -o-linear-gradient(top,#cccccc,b2b2b2);

	background-color:#cccccc;
	border:0px solid #000000;
	text-align:center;
	border-width:0px 0px 1px 1px;
	font-size:11px;
	font-family:Helvetica;
	font-weight:bold;
	color:#000000;
}
.strabotable tr:first-child:hover td{
	background:-o-linear-gradient(bottom, #cccccc 5%, #b2b2b2 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #cccccc), color-stop(1, #b2b2b2) );
	background:-moz-linear-gradient( center top, #cccccc 5%, #b2b2b2 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#cccccc", endColorstr="#b2b2b2");	background: -o-linear-gradient(top,#cccccc,b2b2b2);

	background-color:#cccccc;
}
.strabotable tr:first-child td:first-child{
	border-width:0px 0px 1px 0px;
}
.strabotable tr:first-child td:last-child{
	border-width:0px 0px 1px 1px;
}

.docwrapper{
	width: 600px;
}

.docheader{
	background-color:#CCCCCC;
	color:#FFFFFF;
	font-size: 24px;
	font-weight:bold;
	padding: 3px 3px 3px 10px;
	border-bottom: 1px solid #333333;
}

.docbody{
	background-color:#FFFFFF;
	color:#333333;
	padding: 5px 3px 3px 5px;
}

.docimage{
	padding: 5px 3px 3px 15px;
}

.docfieldset{
	border: 1px solid #CDCDCD;
	padding: 8px;
	margin: 8px 0
}

.doclegend{
	color:#666666;
}

.aboutmessage{
	font-family: 'Raleway', sans-serif;
	background-color:#FFF;
	font-size:24px;
	position: relative;
	/*left: 50%;*/
	top: 170px;
	padding-top:15px;
	padding-bottom:15px;
}

.btn {
	position: relative;
	display: inline-block;
	padding: 3px 3px;
	font-size: 13px;
	font-weight: 700;
	line-height: 10px;
	color: #333;
	white-space: nowrap;
	vertical-align: middle;
	cursor: pointer;
	background-color: #eee;
	background-image: linear-gradient(#fcfcfc,#eee);
	border: 1px solid #d5d5d5;
	border-radius: 3px;
		border-top-left-radius: 3px;
		border-bottom-left-radius: 3px;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	-webkit-appearance: none;
}


#successmessage {
	background-color:#30ae4f;
	color:#FFF;
	padding:5px;
	display:none;
	position: absolute;
	left: 50%;
	top: 300px;
	transform: translate(-50%,-50%);
	width: 500px;
	z-index: 30000;
	padding: 0.5em 0.5em 0.5em 0.5em;
	border: 2px solid #666666;
	border-radius: 8px;
	font-size:24px;
	text-align:center;
}


#strabologo {
	padding:5px;
	position: absolute;
	left: 50%;
	top: 400px;
	transform: translate(-50%,-50%);
	width: 256px;
	z-index: 30000;
	padding: 0.5em 0.5em 0.5em 0.5em;
	text-align:center;
}


</style>
<style>
<?php
$num = rand(1,22);
?>
.wsite-background {background-image: url('/assets/files/bannerpics/<?php echo $num?>.jpg') !important;background-repeat: no-repeat !important;background-position: 50% 50% !important;background-size: cover !important;background-color: transparent !important;background: inherit;}
body.wsite-background {background-attachment: fixed !important;}
</style>
<script><!--
var STYLE_PREFIX = 'wsite';
//-->
</script>

<script src='/assets/js/jquery/jquery.min.js'></script>
<script src="/assets/js/featherlight/featherlight.js"></script>
<script src="/assets/js/clipboardjs/clipboard.js"></script>



<script src='/assets/files/main.js'></script>
<script>_W.relinquish && _W.relinquish()</script>
<script type='text/javascript'><!--
(function(jQuery){
function initFlyouts(){initPublishedFlyoutMenus([{"id":"","title":"Home","url":"/","target":""},
													{"id":"","title":"About","url":"/about","target":""},
													{"id":"","title":"Account","url":"/","target":""},
													{"id":"","title":"Api","url":"/api","target":""},
													{"id":"","title":"Help","url":"/help","target":""}
													],"","<li><a href=\"#\" data-membership-required=\"\" >{{title}}<\/a><\/li>",'active',false)}
if (jQuery) {
jQuery(document).ready(function() { jQuery(initFlyouts); });
}else{
if (Prototype.Browser.IE) window.onload = initFlyouts;
else document.observe('dom:loaded', initFlyouts);
}
})(window._W && _W.jQuery)

//-->
</script>

<?php
if($_SERVER['PHP_SELF']=="/register.php"){
?>
<script src='https://www.google.com/recaptcha/api.js'></script>
<?php
}
?>

</head>
<?php
if($scriptname=="/index.php"||$scriptname=="/index2.php"||$scriptname=="/indexdev.php"){
?>
<body class="tall-header  wsite-theme-light wsite-page-index">
<?php
}else{
?>
<body class='  wsite-theme-light wsite-page-minutes '>
<?php
}
?>
<!-- <body class="tall-header  wsite-theme-light wsite-page-index"> with banner--->
<div id="wrapper">
	<div class="bg-wrapper">
		<div id="header">
			<div id="sitename">
				<span class='wsite-logo'><a href='/'>
					<span id="wsite-title"><img src="/includes/images/strabo_icon_web.png" width="26" height="26"/> StraboSpot</span></a>
				</span>
			</div>
		</div>
		<div id="navigation">

			<!-- login here -->
			<?php echo $bartext?>


			<ul class='wsite-menu-default'>
				<li id='<?php echo $homeactive?>'><a href="/" data-membership-required="0" >Home</a></li>
				<li id='<?php echo $aboutactive?>'>
					<a href="/overview" data-membership-required="0" >About</a>
					<div class='wsite-menu-wrap' style='display:none'>
						<ul class='wsite-menu'>
							<li id=''><a href='/overview' ><span class='wsite-menu-title'>Overview</span></a></li>

							<li id=''><a href='https://strabospot.wordpress.com' target='_blank'><span class='wsite-menu-title'>News</span></a></li>

							<li id=''><a href='/privacy' ><span class='wsite-menu-title'>Privacy Policy</span></a></li>
						</ul>
					</div>
				</li>
				<li id='<?php echo $accountactive?>'>
					<a href="#" data-membership-required="0" >Account</a>
					<div class='wsite-menu-wrap' style='display:none'>
						<ul class='wsite-menu'>
					<?php
					if($_SESSION['loggedin']=="yes"){
					?>
							<li id=''><a href='/logout' ><span class='wsite-menu-title'>Logout</span></a></li>
							<li id=''><a href='/load_shapefile' ><span class='wsite-menu-title'>Load Shapefile</span></a></li>
							<li id=''><a href='/my_data' ><span class='wsite-menu-title'>My Data</span></a></li>
					<?php
					if($username=="jasonash@ku.edu" || $username=="jdwalker@ku.edu" || $username=="afg@unc.edu"){
					?>
							<li id=''><a href='/geotiff' ><span class='wsite-menu-title'>My Maps</span></a></li>
					<?php
					}
					?>
							<li id=''><a href='/change_password' ><span class='wsite-menu-title'>Change Password</span></a></li>
							<li id=''><a href='/versioning' ><span class='wsite-menu-title'>Versioning</span></a></li>
					<?php
					}else{
					?>
							<li id=''><a href='/login' ><span class='wsite-menu-title'>Login</span></a></li>
					<?php
					}

					if($_SESSION['loggedin']!="yes"){
					?>
							<li id=''><a href='/register' ><span class='wsite-menu-title'>Register</span></a></li>
					<?php
					}
					?>
						</ul>
					</div>
				</li>
				<li id='<?php echo $apiactive?>'><a href="/api" data-membership-required="0" >API</a></li>
				<li id='<?php echo $appactive?>'>
					<a href="downloadapp" data-membership-required="0" >Strabo-App</a>
					<!--
					<div class='wsite-menu-wrap' style='display:none'>
						<ul class='wsite-menu'>
							<li id=''><a href='download' ><span class='wsite-menu-title'>Download App</span></a></li>
							<li id=''><a href='https://build.phonegap.com/apps/1289076/share' target="_blank" ><span class='wsite-menu-title'>Download App</span></a></li>
							<li id=''><a href='/downloadapp'><span class='wsite-menu-title'>Download</span></a></li>
							<li id=''><a href='https://app.strabospot.org/' target="_blank" ><span class='wsite-menu-title'>Online App</span></a></li>
						</ul>
					</div>
					-->
				</li>
				<li id='<?php echo $searchactive?>'><a href="/search" data-membership-required="0" >Search</a></li>
				<li id='<?php echo $helpactive?>'><a href="/files/Strabo_Help_Guide.pdf" data-membership-required="0" target="_blank">Help</a></li>
			</ul>
		</div>

<?php
if($scriptname=="/index.php"||$scriptname=="/index2.php"||$scriptname=="/indexdev.php"){
?>
		<div class="banner-wrap wsite-background wsite-custom-background">
			<div id="strabologo"><img src="/includes/images/strabo_icon_web.png" width="256" height="256"/></div>

<?php
if($scriptname=="/index2.php"){
?>

<div class="aboutmessage">COLLECT, STORE AND SHARE GEOLOGIC DATA</div>

<?php
}
?>


		</div>

<?php
}
?>

		<!-- <div class="banner-wrap wsite-background wsite-custom-background">here</div> -->
		<div id="content-wrapper">
			<div id="content">
				<div id='wsite-content' class='wsite-elements wsite-not-footer'>
<!-- end header -->