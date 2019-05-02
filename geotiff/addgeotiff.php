<?
session_start();

include("../includes/header.php");
?>

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
	background-color:#FFF;
	border-top: 1px solid #666666;
	border-bottom: 1px solid #666666;
	padding:0px;
	margin-bottom:10px;
}
</style>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/jquery.fileupload.css">


<div id="mapbanner" style="padding:5px 0px 5px 0px;margin-bottom:20px;">
	<table>
		<tr>
			<td>
				<div>
					<img src="/includes/images/mappart.png"/>
				</div>
			</td>
			<td valign="top">
				<div style="padding-left:10px;font-family: 'Raleway', sans-serif;font-size:16px;">
					The StraboSpot My Maps interface allows you to upload your own custom GeoTIFF map files for inclusion in the StraboSpot mobile and desktop apps.
					Files are accepted in .tif rectified GeoTIFF format up to 1024MB in size. The preferred coordinate system for uploaded files is WGS 84. If another
					coordinate system is provided, the file will be automatically converted which may result in undesirable map appearance.
				</div>
			</td>
		</tr>
	</table>
	


	<div style="clear:both;">
	</div>
</div>
<br>
<div id="gifbutton">
	<span class="btn btn-success fileinput-button">
		<i class="glyphicon glyphicon-plus"></i>
		<span>Select GeoTIFF File</span>
		<input id="fileupload" type="file" name="geotifffile" accept="image/tiff,.tif">
	</span>
</div>

<div id="uploading">
	Uploading Map...
</div>


<br>
<div id="progress" class="progress">
	<div class="progress-bar progress-bar-success"></div>
</div>
<div id="files" class="files"></div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="js/vendor/jquery.ui.widget.js"></script>
<script src="js/jquery.iframe-transport.js"></script>
<script src="js/jquery.fileupload.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

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
        		$('#errorcontent').text(data.result.error);
        		$.featherlight($('#errorbox'));
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

<script src="//cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>



<div style="display:none;">
<div id="errorbox">
	<div id="errorhead">Error!</div>
	<div id="errorcontent">
	
	</div>
</div>
</div>

<!--
<a href="#" data-featherlight="#errorbox">Open element in lightbox</a>
<a href="javascript:void(0)" onclick="$.featherlight($('#errorbox'));">Here</a>
-->

<?
include("../includes/footer.php");
?>
