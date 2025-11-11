<?php
/**
 * File: strabotoolsdownload.php
 * Description: StraboTools App
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
							<h2>StraboTools App</h2>
						</header>

<div>
	<div align="center">
		<h3>StraboTools for iOS is available in the <a href="https://apps.apple.com/us/app/strabotools/id1496239162?ls=1" target="_blank">App Store</a></h3>
	</div>
</div>

<div style="width:100%;padding-top:20px;">
<p>StraboTools is designed to aid geologic field work by providing quantitative data that are otherwise difficult to estimate in the field. The app was developed for work in plutonic rocks such as granite, but it can be useful in field work in any type of rock and for study of thin sections as well. The app analyzes a photograph taken within the app or imported into it.</p>

<p>•The Edge Fabric tool plots an ellipse that summarizes the orientations of image brightness gradients. In rocks with a fabric produced by deformation, this ellipse provides the orientation of the fabric and an axial ratio, which correlates with bulk deformation. The tool also reports the orientation in space of the line defined by the long axis of the ellipse. If, for example, the fabric being measured is planar, then these intersection lineations will lie on a great circle. Edge fabric diagrams are useful both in the field and for analysis of photomicrographs.</p>

<p>•The Color Index tool allows quantitative determination of the color index (CI; area percent dark minerals) from a photograph. The user takes a photograph and then moves a slider to highlight those pixels darker than a certain threshold. The resulting area percent is displayed. Both adaptive and full-image thresholding are available.</p>

<p>•Edge Detect applies an edge detection filter to the image. This can be useful in highlighting subtle features in an outcrop and in interpreting edge fabric ellipses.</p>

</div>

<div class="box alt">
	<div class="row gtr-50 gtr-uniform">
		<div class="col-6 col-12-xsmall"><span class="image fit"><img src="assets/files/tools_pics/ipad2.png" alt=""></span></div>
		<div class="col-6 col-12-xsmall"><span class="image fit"><img src="assets/files/tools_pics/ipad3.png" alt=""></span></div>
		<div class="col-6 col-12-xsmall"><span class="image fit"><img src="assets/files/tools_pics/ipad4.png" alt=""></span></div>
		<div class="col-6 col-12-xsmall"><span class="image fit"><img src="assets/files/tools_pics/ipad5.png" alt=""></span></div>
		<div class="col-6 col-12-xsmall"><span class="image fit"><img src="assets/files/tools_pics/ipad6.png" alt=""></span></div>
		<div class="col-6 col-12-xsmall"><span class="image fit"><img src="assets/files/tools_pics/ipad7.png" alt=""></span></div>
	</div>
</div>

					</div>
				</div>
<?php
include("includes/mfooter.php");
?>