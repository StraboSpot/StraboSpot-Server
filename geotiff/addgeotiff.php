<?php
/**
 * File: addgeotiff.php
 * Description: Add new record interface
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

session_start();

include("../includes/mheader.php");
?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Upload GeoTIFF</h2>
						</header>

<style type='text/css'>
	#errorbox {
		background-color:#fa7777;
		color:#FFF;
		padding:5px;
	}

	#errorhead {
		font-size:18px;
		font-weight:bold;
		text-align:center;
	}

	#uploading {

		font-size:18px;
		color:#666666;
		font-weight:bold;
		display:none;
	}

#mapbanner{
	width:100%;
	background-color:#222;
	border-top: 1px solid #666666;
	border-bottom: 1px solid #666666;
	padding:0px;
	margin-bottom:10px;
}
</style>

<!--
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
-->

<link rel="stylesheet" href="css/jquery.fileupload.css">

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

<br>
<div id="gifbutton">
	<span class="  fileinput-button button" >
		<i class="glyphicon glyphicon-plus"></i>
		<span>Select File</span>
		<input id="fileupload" type="file" name="geotifffile" accept="image/tiff,.tif,.zip">
	</span>
</div>

<div id="uploading">
	Uploading Map...
</div>

<div id="errormessage"></div>

<br>
<div id="progress" class="progress">
	<div class="progress-bar progress-bar-success"></div>
</div>
<div id="files" class="files"></div>

<script>
$(function () {
	'use strict';
	var url = 'upload/';
	$('#fileupload').fileupload({
		url: url,
		dataType: 'json',
		send: function (e, data) {
			$('#gifbutton').hide();
			$('#uploading').show();
		},
		done: function (e, data) {
			console.log(data.result);
			if(data.result.error){
				$('#gifbutton').show();
				$('#uploading').hide();
				$('#progress .progress-bar').css('width', '0%');
				$('#errormessage').text(data.result.error);
				$("#errormessage").fadeIn();
				$("#errormessage").fadeOut(5000);
			}else{
				window.location.href = "/geotiff/detail/"+data.result.hash;
			}

		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#progress .progress-bar').css('width', progress + '%');
		}
	}).prop('disabled', !$.support.fileInput)
		.parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>

<!--
<a href="#" data-featherlight="#errorbox">Open element in lightbox</a>
<a href="javascript:void(0)" onclick="$.featherlight($('#errorbox'));">Here</a>
-->

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include("../includes/mfooter.php");
?>
