<?PHP

include("../logincheck.php");

include("../includes/config.inc.php");
include("../db.php");

include("../includes/header.php");

?>


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
    padding: 10px 32px;
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
	background-color:#FFF;
	border-top: 1px solid #666666;
	border-bottom: 1px solid #666666;
	padding:0px;
	margin-bottom:10px;
}


</style>

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
					Files are accepted in .tif rectified GeoTIFF format up to 500MB in size. The preferred coordinate system for uploaded files is WGS 84. If another
					coordinate system is provided, the file will be automatically converted which may result in undesirable map appearance.
				</div>
			</td>
		</tr>
	</table>
	


	<div style="clear:both;">
	</div>
</div>

<div style="width:100%; background-color:#FFF;">
	<div style="float:left">
		<h2>My Maps</h2>
	</div>
	<div style="float:right;">
		<button class="button" onclick="window.location.href='addgeotiff'">Add New Map</button>
	</div>
	<div style="clear:both">
	</div>
</div>

<script src='/assets/js/jquery/jquery.min.js'></script>

<script>
	function myCopy(hash) {
		var copyText = document.getElementById(hash);
		copyText.select();
		document.execCommand("copy");
		//alert("Copied code: " + copyText.value + " to clipboard");
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
	
</script>

<div id="successmessage"></div>

<?

$userpkey = $_SESSION['userpkey'];

$rows = $db->get_results("select * from geotiffs where userpkey=$userpkey order by uploaddate desc");

if(count($rows)>0){
?>

	<table class="mymaps">
		<tr>
			<th>&nbsp;</th>
			<th>Map Name</th>
			<th>Map Code</th>
			<th>File Size</th>
			<th>Upload Date</th>
		</tr>
<?

foreach($rows as $row){

	$hash=$row->hash;
	$name=$row->name;
	$uploaddate=$row->uploaddate;
	$filesize=$row->filesize;
	$filesize = $filesize/1000000;
	?>
	<tr>
		<td>
			<div align="center"><a href="detail/<?=$hash?>">VIEW</a> | <a href="delete/<?=$hash?>" onclick="return confirm('Are you sure?')">DELETE</a></div>
		</td>
		<td><?=$name?></td>
		<td>
		
			<input id="<?=$hash?>" value="<?=$hash?>">

			<button class="btn" data-clipboard-text="<?=$hash?>">
				<img class="clippy" src="/includes/images/clippy.svg" alt="Copy to clipboard" width="13">
			</button>

			<!--
			<button class="btn" data-clipboard-target="#<?=$hash?>">
				<img class="clippy" src="/includes/images/clippy.svg" alt="Copy to clipboard" width="13">
			</button>
			-->
			
			<!--<input type="text" class="hashbox" id="<?=$hash?>" value="<?=$hash?>" readonly> <a href="javascript:void(0);" onclick="myCopy('<?=$hash?>');"><img class="copybutton" src="/includes/images/copy-icon.png"></img></a>-->
		
		</td>
		<td><?=$filesize?> MB</td>
		<td><?=$uploaddate?></td>
	</tr>
	<?

}
?>
	</table>

<?
}else{
	?>
	No maps found. Click <a href="addgeotiff">here</a> to upload a new map.
	<?
}






include("../includes/footer.php");

?>