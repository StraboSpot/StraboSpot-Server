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

 /* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 34px;
  height: 14px;
  border: 1px solid #333;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #f00;
  -webkit-transition: .4s;
  transition: .4s;
  
}

.slider:before {
  position: absolute;
  content: "";
  height: 6px;
  width: 6px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #79b22e;
}

input:focus + .slider {
  box-shadow: 0 0 1px #79b22e;
}

input:checked + .slider:before {
  -webkit-transform: translateX(20px);
  -ms-transform: translateX(20px);
  transform: translateX(20px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.selecty {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	padding: 1px;
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
					Files are accepted in .tif rectified GeoTIFF format up to 1024MB in size. The preferred coordinate system for uploaded files is WGS 84. If another
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

	function  mapPub(maphash){
		
		//console.log(maphash);
		
		
		if(document.getElementById('switch_'+maphash).checked){
			//console.log("switch "+projectid+" checked");
			console.log("https://strabospot.org/map_public?maphash="+maphash+"&state=public");
			$.get("/map_public?maphash="+maphash+"&state=public");
		}else{
			//console.log("switch "+projectid+" not checked");
			console.log("https://strabospot.org/map_public?maphash="+maphash+"&state=private");
			$.get("/map_public?maphash="+maphash+"&state=private");
		}
		
	}

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
			<th>Public?</th>
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
	
	//$db->dumpVar($row);exit();
	
	if($row->ispublic=="t"){
		$checked=" checked";
	}else{
		$checked="";
	}
	
	
	?>
	<tr>
		<td>
			<div align="center"><a href="detail/<?=$hash?>">VIEW</a>
				 | <a href="edit/<?=$hash?>">EDIT</a>
				 | <a href="delete/<?=$hash?>" onclick="return confirm('Are you sure?')">DELETE</a>
			</div>
		</td>
		<td>
			<label class="switch"><input type="checkbox" name="switch_<?=$hash?>" id="switch_<?=$hash?>" onclick="mapPub('<?=$hash?>')"<?=$checked?>><div class="slider"></div></label>
		</td>
		<td><?=$name?></td>
		<td>
		
			<input id="<?=$hash?>" value="<?=$hash?>" readonly>

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