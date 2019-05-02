<?
include 'includes/header.php';

?>

<script src='assets/js/jquery/jquery.min.js'></script>
<script src="assets/js/featherlight/featherlight.js"></script>

<style>
.greenbutton {
  border-radius: 8px;
  background-color: #37ad53;
  border: none;
  color: #FFFFFF;
  text-align: center;
  font-size: 14px;
  padding: 5px;
  width: 150px;
  transition: all 0.2s;
  cursor: pointer;
  margin: 5px;
  border: 1px #666666 solid;
}

.greenbutton span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  transition: 0.2s;
  padding-right:25px;
}

.greenbutton span:after {
  content: '\00bb';
  position: absolute;
  opacity: 0;
  top: 0;
  right: -20px;
  transition: 0.2s;
}

.greenbutton:hover span {
  //padding-right: 25px;
}

.greenbutton:hover span:after {
  opacity: 1;
  right: 0;
}

.greenbutton:active {
  //background-color: #3e8e41;
  //box-shadow: 0 2px #666;
  transform: translateY(2px);
}

.redbutton {
  border-radius: 8px;
  background-color: #cc3426;
  border: none;
  color: #FFFFFF;
  text-align: center;
  font-size: 14px;
  padding: 5px;
  width: 150px;
  margin: 5px;
  border: 1px #666666 solid;
  curson: none;
}

.inlineblock{
	display:inline-block;
}


.osrow {
	padding: 20px 0px 0px 5px;
	font-size:24px;
}

.details {
	padding: 20px 0px 0px 15px;
}

.osimage {
	width: 30px;
	height: 30px;
}

.loadingbutton {
  border-radius: 8px;
  background-color: #FFFFFF;
  border: none;
  color: #333333;
  text-align: center;
  font-size: 14px;
  padding: 5px;
  width: 150px;
  transition: all 0.2s;
  margin: 5px;
  border: 1px #666666 solid;
}

.androidwindow {
	width:500px;
}

.androidheader{
	background-color:#CCCCCC;
	color:#FFFFFF;
	font-size: 24px;
	font-weight:bold;
	padding: 3px 3px 3px 10px;
	border-bottom: 1px solid #333333;
}

.androidbody{
	background-color:#FFFFFF;
	color:#333333;
	padding: 10px 3px 3px 0px;
}

.appimage{
	padding:10px;
}

.appimageborder{
	border:#DDDDDD 1px solid;
}

</style>



<h2>The StraboSpot App:</h2>

<div class="osrow">

	<div align="center">
		<img class="osimage" src="assets/js/images/apple.png"/> StraboSpot for iOS is available on the <a href="https://itunes.apple.com/us/app/strabospot/id1221659633?mt=8" target="_blank">iTunes Store</a>
	</div>
</div>

<div class="osrow">

	<div align="center">
		<img class="osimage" src="assets/js/images/android.png"/> StraboSpot for Android is available on the <a href="https://play.google.com/store/apps/details?id=org.strabospot.strabo2&hl=en" target="_blank">Google Play Store</a>
	</div>
</div>

<table style="padding-top:10px;">
	<tr>
		<td class="appimage"><img class="appimageborder" src="assets/files/app_pics/1.jpg" width="300px" /></td>
		<td class="appimage"><img class="appimageborder" src="assets/files/app_pics/2.jpg" width="300px" /></td>
		<td class="appimage"><img class="appimageborder" src="assets/files/app_pics/3.jpg" width="300px" /></td>
	</tr>
	<tr>
		<td class="appimage"><img class="appimageborder" src="assets/files/app_pics/4.jpg" width="300px" /></td>
		<td class="appimage"><img class="appimageborder" src="assets/files/app_pics/6.jpg" width="300px" /></td>
		<td class="appimage"></td>
	</tr>
</table>

<?
include("includes/footer.php");
?>