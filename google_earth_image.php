<?php
/**
 * File: google_earth_image.php
 * Description: Generates Google Earth KML/image exports
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


$id=$_GET['id'];

include("prepare_connections.php");

function fixLabel($label){
	$returnlabel = "";
	$delim = "";
	$labels = explode("_",$label);
	foreach($labels as $label){
		$label = ucfirst($label);
		$returnlabel.=$delim.$label;
		$delim=" ";
	}
	$returnlabel = trim($returnlabel);
	return $returnlabel;
}

?>
<!DOCTYPE html>
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
<title>StraboSpot Image</title>
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
	
	top: 170px;
	padding-top:15px;
	padding-bottom:15px;
}

#geimage-wrapper {
	width: 90%;
	margin: auto;
}

</style>
<style>
.wsite-background {background-image: url('/assets/files/bannerpics/18.jpg') !important;background-repeat: no-repeat !important;background-position: 50% 50% !important;background-size: cover !important;background-color: transparent !important;background: inherit;}
body.wsite-background {background-attachment: fixed !important;}
</style>
<script><!--
var STYLE_PREFIX = 'wsite';
//-->
</script>

<script src='/assets/js/jquery/jquery.min.js'></script>
<script src="/assets/js/featherlight/featherlight.js"></script>

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

</head>
<body class='  wsite-theme-light wsite-page-minutes '>
<!-- <body class="tall-header  wsite-theme-light wsite-page-index"> with banner--->
<div id="wrapper">
	<div class="bg-wrapper">
		<div id="header">
			<div id="sitename">
				<span class='wsite-logo'><a href='/'>
					<span id="wsite-title">StraboSpot Image</span></a>
				</span>
			</div>
		</div>

		<!-- <div class="banner-wrap wsite-background wsite-custom-background">here</div> -->
		<div id="geimage-wrapper">
			<div id="content">
				<div id='wsite-content' class='wsite-elements wsite-not-footer'>
<!-- end header -->

<img src="https://www.strabospot.org/mapimage/<?php echo $id?>.jpg" style="width:100%;"/>

<?php

$hidecols = array("annotated", "userpkey", "origfilename", "filename", "imagesha1", "width", "id", "lat", "lng");

$results = $neodb->get_results("match (i:Image) where i.id=$id return i limit 1");
$results = $results[0];
$results = $results->get("i");
$results = $results->values();

?>
<br><br>
<table>
	<tr><tdcolspan="2"><div align="center" style="font-size:1.4em;font-weight:bold;">Image Details</div></td></tr>
<?php

foreach($results as $key=>$value){
	if(!in_array($key,$hidecols)){
		$label=fixLabel($key);
		?>
		<tr><td nowrap valign="top" style=""><?php echo $label?>: </td><td><?php echo $value?></td></tr>
		<?php
	}
}

?>
</table>
<?php

?>

<!-- begin footer -->
				</div>
			</div>
		</div>
	<div id="footer">
		<div id="footer-content"><div class='wsite-elements wsite-footer'>
			<div>
				<div class="wsite-multicol"><div class="wsite-multicol-table-wrap" style="margin:0 -15px;">
					<table class="wsite-multicol-table">
						<tbody class="wsite-multicol-tbody">
							<tr class="wsite-multicol-tr">
								<td class="wsite-multicol-col" style="width:50%; padding:0 15px;">
									<div class="wsite-spacer" style="height:50px;"></div>
								</td>
								<td class="wsite-multicol-col" style="width:50%; padding:0 15px;">
									<div class="wsite-spacer" style="height:50px;"></div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div style="text-align:right;"><div style="height:10px;overflow:hidden"></div>

			<!-- Proudly powered by <a href="http://www.google.com" href="http://www.google.com">Google</a> -->

		<div style="height:10px;overflow:hidden"></div>
	</div>
</div>

</body>
</html>