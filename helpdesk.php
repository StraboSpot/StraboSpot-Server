<?php
/**
 * File: helpdesk.php
 * Description: StraboSpot Help Desk
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include 'includes/header.php';
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
	font-size:1.2em;
	font-weight:normal;
}
.boldhead {
	font-weight:bold;
	font-size:1.2em;
}
.bigli {
	
	font-size: 1.2em;
	font-weight: bold;
	margin: 0 0 0 2em;
}

</style>

<div class="rowdiv">
	<h2>StraboSpot Help Desk</h2>
</div>

<div style="font-size:1.2em;padding-top:20px;text-indent: 70px;">
In summer 2021 we will offer a weekly help desk for any Strabo2, StraboSpot1, or StraboMicro questions. The help desk will be staffed every Thursday from 11am to 1pm CDT.
There are two other ways to contact us outside of the Strabo help desk hours. You can report bugs and request features on our <a href="https://github.com/StraboSpot" target="_blank">GitHub</a>
 page or can <a href="mailto:strabospot@gmail.com">email us</a> directly.
</div>

<div style="font-size:1.2em;padding-top:30px;text-align:center;">
Click the link below to join the help desk session.
</div>

<div style="font-size:1.6em;padding-top:30px;text-align:center;">
<a href="https://uwmadison.zoom.us/j/95946177125?pwd=azYxdldCbXRjdkYxZmhlMUhVcW1LUT09" targe="_blank">Open Zoom Meeting</a>
</div>

<div style="font-size:1.2em;padding-top:10px;text-align:center;">
Meeting ID: 959 4617 7125
</div>

<?php
include 'includes/footer.php';
?>