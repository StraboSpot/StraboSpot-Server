<?php
/**
 * File: index.php
 * Description: Main page or directory index
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("../logincheck.php");

include("../includes/config.inc.php");
include("../db.php");

include("../includes/mheader.php");

?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Strabo MyMaps</h2>
						</header>

<style type="text/css">
table.mymaps {
	border-width: 1px;
	border-spacing: 0px;
	border-style: solid;
	border-color: gray;
	border-collapse: collapse;
	background-color: white;
	width:100%;
}
table.mymaps th {
	border-width: 1px;
	padding: 3px;
	border-style: solid;
	border-color: gray;
	background-color: #333333;
	color: #FFF;
}
table.mymaps td {
	border-width: 1px;
	padding: 3px;
	border-style: solid;
	border-color: gray;
	background-color: white;
}
.button {
	background-color: #da6161;
	border: none;
	color: white;
	padding: 10px 20px;
	text-align: center;
	text-decoration: none;
	display: inline-block;
	font-size: 16px;
	border-radius: 5px;
	-webkit-transition-duration: 0.4s; /* Safari */
	transition-duration: 0.4s;
}
.button:hover {
	background-color: #983d3d;
	color: white;
}
.copybutton {
	width:20px;
	height:20px;
}

#mapbanner{
	width:100%;
	background-color:#222;
	border-top: 1px solid #666666;
	border-bottom: 1px solid #666666;
	padding:0px;
	margin-bottom:10px;
}

 /* The switch - the box around the bslider */
.switch {
  position: relative;
  display: inline-block;
  width: 34px;
  height: 14px;
  border: 1px solid #333;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

.selecty {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	padding: 1px;
}

</style>

<div>
<p>
<span class="image left"><img src="/includes/images/mappart.png" alt=""></span>
					The StraboSpot My Maps interface allows you to upload your own custom GeoTIFF map files for inclusion in the StraboField mobile and desktop apps.<br>
					Files are accepted in the following formats:
					<ul style="padding-left:35px;">
						<li>.tif rectified GeoTIFF format up to 1024MB in size.</li>
						<li>.zip archive provided by the USGS NGMD containing .tif and .tif.aux.xml files</li>
					</ul>
					The preferred coordinate system for uploaded files is WGS 84. If another
					coordinate system is provided, the file will be automatically converted which may result in undesirable map appearance.
</p>
</div>

<div>
	<div style="float:right;">
		<input type="submit" onclick="window.location.href='addgeotiff'" value="+ Add New Map"></input>
	</div>
	<div style="clear:both">
		&nbsp;
	</div>
</div>

<script src='/assets/js/jquery/jquery.min.js'></script>

<script>
	function myCopy(hash) {
		var copyText = document.getElementById(hash);
		copyText.select();
		document.execCommand("copy");
		$("#successmessage").html('Code&nbsp;'+hash+'&nbsp;copied<br>to&nbsp;clipboard.');
		$("#successmessage").fadeIn();
		$("#successmessage").fadeOut(2000);
	}

	var clipboard = new ClipboardJS('.btn');

	clipboard.on('success', function(e) {
		console.info('Action:', e.action);
		console.info('Text:', e.text);
		console.info('Trigger:', e.trigger);

		$("#successmessage").html('Code&nbsp;'+e.text+'&nbsp;copied<br>to&nbsp;clipboard.');
		$("#successmessage").fadeIn();
		$("#successmessage").fadeOut(2000);

		e.clearSelection();
	});

	function  mapPub(maphash){

		if(document.getElementById('switch_'+maphash).checked){
			console.log("https://strabospot.org/map_public?maphash="+maphash+"&state=public");
			$.get("/map_public?maphash="+maphash+"&state=public");
		}else{
			console.log("https://strabospot.org/map_public?maphash="+maphash+"&state=private");
			$.get("/map_public?maphash="+maphash+"&state=private");
		}

	}

	function doMapOption(hash, mapname){
		var selected = $('#map-'+hash).find(":selected").val();
		$('#map-'+hash).find(":selected").prop('selected', false);

		switch(selected){
			case "view":
				window.location='/geotiff/detail/'+hash;
				break;
			case "edit":
				window.location='/geotiff/edit/'+hash;
				break;
			case "delete":
				if (confirm("Are you sure you want to delete map "+mapname+"?") == true) {
					window.location='/geotiff/delete/'+hash;
				}
				break;
		}
	}

/*
https://strabospot.org/geotiff/detail/66e09349881bf
https://strabospot.org/geotiff/edit/66e09349881bf
https://strabospot.org/geotiff/delete/66e09349881bf
*/

</script>

<div id="successmessage"></div>

<?php

$userpkey = $_SESSION['userpkey'];

$rows = $db->get_results_prepared("SELECT * FROM geotiffs WHERE userpkey=$1 ORDER BY uploaddate DESC", array($userpkey));

if(count($rows)>0){
?>

	<table class="myDataTable">
		<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Public?</th>
			<th>Map Name</th>
			<th class="hideSmall">Map Code</th>
			<th></th>
			<th class="hideSmall">File Size</th>
			<th class="hideSmall">Upload Date</th>
		</tr>
		</thead>
<?php

foreach($rows as $row){

	$hash=$row->hash;
	$name=$row->name;
	$uploaddate=substr($row->uploaddate,0,10);
	$filesize=$row->filesize;
	$filesize = round($filesize/1000000);

	if($row->ispublic=="t"){
		$checked=" checked";
	}else{
		$checked="";
	}

	?>
	<tr>
		<td nowrap>
			<!--
			<div align="center">
				<a href="detail/<?php echo $hash?>">VIEW</a>
				 | <a href="edit/<?php echo $hash?>">EDIT</a>
				 | <a href="delete/<?php echo $hash?>" onclick="return confirm('Are you sure?')">DELETE</a>
			</div>
			-->
		<select class="myDataSelect hideBigNineEighty" id="map-<?php echo $hash?>" onchange="doMapOption('<?php echo $hash?>','<?php echo $name?>');">
			<option value="" style="display:none">Options...</option>
			<option value="view">View</option>
			<option value="edit">Edit</option>
			<option value="delete">Delete</option>
		</select>

		<span class="hideSmallNineEighty">
			<a href="detail/<?php echo $hash?>">View</a>&nbsp;&nbsp;&nbsp;<a href="edit/<?php echo $hash?>">Edit</a>&nbsp;&nbsp;&nbsp;<a href="delete/<?php echo $hash?>">Delete</a>
		</span>

		</td>
		<td>
			<label class="switch"><input type="checkbox" name="switch_<?php echo $hash?>" id="switch_<?php echo $hash?>" onclick="mapPub('<?php echo $hash?>')"<?php echo $checked?>><div class="slider sliderFix"></div></label>
		</td>
		<td><?php echo $name?></td>
		<td class="hideSmall" nowrap>

			<input type="text" id="<?php echo $hash?>" value="<?php echo $hash?>" size="8" style="height: 1.7em; display: inline;" readonly>
		</td>
		<td>
			<button class="btn" data-clipboard-text="<?php echo $hash?>">
				<img class="clippy" src="/includes/images/clippy.svg" alt="Copy to clipboard" width="13">
			</button>

		</td>
		<td class="hideSmall" nowrap><?php echo $filesize?> MB</td>
		<td class="hideSmall"><?php echo $uploaddate?></td>
	</tr>
	<?php

}
?>
	</table>

<?php
}else{
	?>
	No maps found. Click <a href="addgeotiff">here</a> to upload a new map.
	<?php
}
?>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include("../includes/mfooter.php");

?>