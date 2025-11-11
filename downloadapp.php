<?php
/**
 * File: downloadapp.php
 * Description: StraboField App
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include 'includes/mheader.php';

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
}

.greenbutton:hover span:after {
  opacity: 1;
  right: 0;
}

.greenbutton:active {
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

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>StraboField App</h2>
						</header>

<div>

	<div align="center">
		<h3>StraboSpot for iOS is available on the <a href="https://apps.apple.com/us/app/strabospot2/id1555903455" target="_blank">Apple App Store</a></h3>
	</div>
</div>

<div class="padBottom">

	<div align="center">
		<h3>StraboSpot for Android is available on the <a href="https://play.google.com/store/search?q=strabospot+2&c=apps&hl=en" target="_blank">Google Play Store</a></h3>
	</div>
</div>

<div class="box alt">
	<div class="row gtr-50 gtr-uniform">
		<div class="col-6 col-12-xsmall"><span class="image fit"><img src="assets/files/app_pics_2/1.PNG" alt=""></span></div>
		<div class="col-6 col-12-xsmall"><span class="image fit"><img src="assets/files/app_pics_2/2.PNG" alt=""></span></div>
		<div class="col-6 col-12-xsmall"><span class="image fit"><img src="assets/files/app_pics_2/3.PNG" alt=""></span></div>
		<div class="col-6 col-12-xsmall"><span class="image fit"><img src="assets/files/app_pics_2/4.PNG" alt=""></span></div>
	</div>
</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>
<?php
include("includes/mfooter.php");
?>