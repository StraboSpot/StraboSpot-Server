<?php
/**
 * File: supporteddevices.php
 * Description: StraboField Devices – What to Buy?
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include 'includes/mheader.php';

//get groups based on userpkey
?>

<style type="text/css">

.rowdiv {
	text-align:center;
	padding-top:5px;
}

.rowdivv {
	
	padding-top:5px;
}

.rowheader {
	font-weight:bold;
	color:#333;
	font-size:1.2em;
}

.redred {
	color:#ab1424;
	font-weigth:bold;
	padding-right:5px;
}

.button {
   /* Green */
  
  
  padding: 3px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
}

.checkheader {
	font-size:1.3em;
}

.checkbody {
	border:1px dashed rgba(50, 50, 50, 0.5);border-radius:10px;padding:10px;display:none;margin-bottom:20px;
}

.displacementbox {
	border:1px dashed #CCC;border-radius:5px;padding:10px;display:none;margin-bottom:10px;margin-top:5px;;
}

.notesbox {
	border:1px dashed rgba(50, 50, 50, 0.5);border-radius:10px;padding:20px;margin-bottom:20px;
}

.separator {
  display: flex;
  align-items: center;
  text-align: center;
}

.separator::before,
.separator::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid #666;
}

.separator:not(:empty)::before {
  margin-right: .25em;
}

.separator:not(:empty)::after {
  margin-left: .25em;
}

.only-numeric {
	width:100px;
}

.errorbar {
	color:#bf342c;
	font-weight:bold;
	font-size:1.2em;
}
.pitem {
	padding-bottom:5px;
	font-size:.9em;
	font-weight:normal;
}
.topitem {
	padding-bottom:5px;
	padding-top:5px;
	font-size:1.2em;
	font-weight:normal;
}
.boldhead {
	font-weight:bold;
	
}
.bigli {
	
	font-size: 1.2em;
	font-weight: bold;
	margin: 0 0 0 2em;
}

.padleft {
	padding-left:15px;
}

</style>

<link rel="stylesheet" href="/assets/js/dropzone/dropzone.css" type="text/css" />
<script src='/assets/js/jquery/jquery.min.js'></script>
<script src="/assets/js/featherlight/featherlight.js"></script>
<script src="/assets/js/dropzone/dropzone.js"></script>

<script src="/assets/js/jquery-modal/jquery.modal.min.js"></script>
<link rel="stylesheet" href="/assets/js/jquery-modal/jquery.modal.min.css" type="text/css" />

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>StraboField Devices – What to Buy?</h2>
						</header>

<p>
<div style="text-align:center;">StraboField is now available on the Google Play store and the Apple App store and is compatible with tablets or small screens.<br>Below are the device requirements and some tools that may improve the user experience.</div>
</p>

<ol>
	<li class="">
		<div class="topitem">A cellphone, a WiFi+Cellular iPad, or a WiFi+Cellular iPad</div>
		<div class="padleft">StraboField is available for iOS and Android devices. The GPS functionality will work on all cellphones. When buying a tablet, you must buy the WiFi+Cellular option, because WiFi-only models do not have a built-in GPS unit. StraboSpot does not need internet connectivity in the field, so there is no need to purchase a cellular plan for your cellular enabled. Although there are 3rd party GPS units that are compatible with these devices, we have not tested them and cannot guarantee good results.</div>
		<div class="padleft">All models of iPad (iPad mini, iPad, iPad Air, and iPad Pro) can be purchased in a WiFi+Cellular configuration. StraboField will work well on any of the current iPad models, and we have tested it on most iPad models over the last 3-4 years. Of course, the app will be snappiest on newer iPads.</div>
		<div class="padleft">One of the advantages of the Android operating system is that it works on a variety of devices.  That being said, we have not tested StraboField on all makes or brands of Android tablets. As with the iPads, the app will be faster on newer devices.   </div>
	</li>
	<li class="">
		<div class="topitem">A Sturdy Case</div>
		<div class="padleft">This is a must for taking a big glass-fronted device into the field. Make sure to get a  sturdy case for your devices. A screen protector as part of the case (or separately) is very important, since flying shards of rock–even tiny ones!–can shatter the device screen if it has no protection. Matte screen protectors also make screens easier to see in the sun. With a case, iPads are really quite durable. At the University of Kansas, we have been using iPads for the last 5 years. Out of a total of almost 40 iPads in use, we have only had one fail. The failure was a student not putting on the cover and planting the screen on a rock—otherwise, no issues with the devices. Most of the StraboSpot team uses Otterbox Defender brand cases, but there are a variety of good choices. </div>
		<div class="padleft">Note: A lot of the protective cases come in dark colors so make sure to keep devices out of the sun otherwise they can overheat.</div>
	</li>
	<li class="">
		<div class="topitem">A Stylus (or Apple pencil for iPads)</div>
		<div class="padleft">If you like to sketch in the field, then a stylus greatly improves the experience on mobile devices. There are a huge range of choices here. There are very inexpensive styluses that are basically rubber-tipped pens that you can use if you don’t care too much about fine detail, and slightly more expensive blue-tooth enabled styluses. If using a stylus in the field, we highly recommended devising a way to secure your stylus to your device (a string and some tape have saved our Apple pencils ten times over)</div>
	</li>
	<li class="">
		<div class="topitem">A Portable Battery Pack</div>
		<div class="padleft">With the graphics-intensive map rendering and GPS use of StraboField, battery life will come in a bit under the 9-10 hours of battery life that Apple claims (for iPads). In our real-world testing, iPads will go from 100% to <10% under a “normal” day out in the field. Your milage may vary, so we suggest buying a portable battery pack. Battery packs are increasingly inexpensive and can slip into your backpack to reduce battery anxiety. They are also critical if you are staying in the field without electricity access for several days.</div>
	</li>
</ol>

					<div class="bottomSpacer"></div>

					</div>
				</div>
<?php
include 'includes/mfooter.php';
?>