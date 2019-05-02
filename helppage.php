<?

$extra=$_GET['extra'];
if($extra!=""){$extra="/".$extra;}

include("includes/header.php");

?>

<div style="font-size:24px;">
Click <a href="/files/Strabo_Help_Guide.pdf">here</a> for the StraboSpot Help Guide
</div>
<!--
<iframe frameborder="0" height="700" id="iframe-0" name="iframe-0" scrolling="auto" src="https://help.strabospot.org<?=$extra?>" width="100%">Your browser does not support iframes. But You can use the following Link.</iframe>
--<




<?
include("includes/footer.php");



?>