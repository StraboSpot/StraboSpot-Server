<?PHP

include("../logincheck.php");


$hash = pg_escape_string($_GET['hash']);
$userpkey = pg_escape_string($_SESSION['userpkey']);

include("../includes/config.inc.php");
include("../db.php");

$row = $db->get_row("select * from geotiffs where hash='$hash' and userpkey=$userpkey");

$pkey=$row->pkey;
$name=$row->name;
$gdalinfo=$row->gdalinfo;

if($row->pkey==""){
	include("../includes/header.php");
	?>
	GeoTIFF not found.
	<?
	include("../includes/footer.php");
	exit();
}

$gdalinfo = explode("\n",$gdalinfo);

//$db->dumpVar($gdalinfo);

foreach($gdalinfo as $part){
	if(substr($part,0,10)=="Lower Left"){
		$part=trim(explode(")",$part)[0]);
		$part=trim(explode("(",$part)[1]);
		$left = trim(explode(",",$part)[0]);
		$bottom = trim(explode(",",$part)[1]);
		
		//echo "left:--$left--<br>";
		//echo "bottom:--$bottom--<br>";
	}
	if(substr($part,0,11)=="Upper Right"){
		$part=trim(explode(")",$part)[0]);
		$part=trim(explode("(",$part)[1]);
		$right = trim(explode(",",$part)[0]);
		$top = trim(explode(",",$part)[1]);
		
		//echo "right:--$right--<br>";
		//echo "top:--$top--<br>";
	}

}

//echo "$left $bottom $right $top";

//envelope:POLYGON ((-95.251790045453 38.95801841741, -95.251790045453 38.9588, -95.251481621913 38.9588, -95.251481621913 38.95801841741, -95.251790045453 38.95801841741))

if($left!="" && $right!="" && $top!="" && $bottom!=""){
	$envelope = "POLYGON(($left $bottom, $left $top, $right $top, $right $bottom, $left $bottom))";
}

//echo $envelope;

//exit();





include("../includes/header.php");

?>
<style type="text/css">
.button {
    background-color: #666; 
    border: none;
    color: white;
    padding: 7px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    border-radius: 5px;
}
.button:hover {
    background-color: #000;
    color: white;
}

	#map {
		width:960px;
		height:500px;
		background-color:#EEE;
	}

#successmessage {
	background-color:#30ae4f;
	color:#FFF;
	padding:5px;
	display:none;
	position: absolute;
	left: 50%;
	top: 450px;
	transform: translate(-50%,-50%);
	width: 500px;
	z-index: 30000;
	padding: 0.5em 0.5em 0.5em 0.5em;
	border: 2px solid #666666;
	border-radius: 8px;
	font-size:24px;
	text-align:center;
}

.copybutton {
	width:25px;
	height:25px;
}

</style>

<div style="display:none">envelope: <?=$envelope?></div>
<link rel="stylesheet" href="/assets/js/ol4/ol.css" type="text/css">
<link rel="stylesheet" href="/assets/js/layerswitcher/layerswitcher.css" type="text/css">

<script src="/assets/js/ol4/ol.js"></script>
<script src="/assets/js/layerswitcher/layerswitcher.js"></script>

<script src='/assets/js/jquery/jquery.min.js'></script>

<script>
	function myCopy() {
		var copyText = document.getElementById('copybox');
		copyText.select();
		document.execCommand("copy");
		//alert("Copied code: " + copyText.value + " to clipboard");
		$("#successmessage").html('Code&nbsp;'+copyText.value+'&nbsp;copied<br>to&nbsp;clipboard.');
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

<h2>Map: <?=$name?></h2>
<div id="map" class="map"></div>
<div>
	<div align="center" style="padding-top:10px; font-size:1.7em;">
		This code should be entered into the StraboSpot app to use this map:
	</div>
	<div align="center" style="padding-top:5px;">
		<input id="copybox" style="font-size:1.8em;" type="text" value="<?=$hash?>" readonly>
		
		<!--<a href="javascript:void(0);" onclick="myCopy();"><img class="copybutton" src="/includes/images/copy-icon.png"></img></a>-->

			<button class="btn" data-clipboard-text="<?=$hash?>">
				<img class="copybutton" src="/includes/images/copy-icon.png"></img>
			</button>

	</div>
</div>




<script>



var baseLayers = new ol.layer.Group({
	'title': 'Base maps',
	layers: [
		new ol.layer.Tile({
			title: 'OSM',
			type: 'base',
			visible: true,
			source: new ol.source.OSM()
		}),
		new ol.layer.Tile({
			title: '<?=$name?>',
			source: new ol.source.XYZ({
				url: 'https://strabospot.org/geotiff/tiles/<?=$hash?>/{z}/{x}/{y}.png'
			})
		})

	]
});

var mapView = new ol.View({
	'projection': 'EPSG:3857',
	'center': [-11000000, 4600000],
	'zoom': 5, //5
	'minZoom': 0
});


map = new ol.Map({
	target: 'map',
	controls: ol.control.defaults({}),
	view: mapView
});

map.addLayer(baseLayers);


var layerSwitcher = new ol.control.LayerSwitcher({
	tipLabel: 'Layers' // Optional label for button
});

map.addControl(layerSwitcher);


layerSwitcher.renderPanel();

<?
if($envelope!=""){
?>
var newformat = new ol.format.WKT();
var inpoly = (new ol.format.WKT()).readFeature('<?=$envelope?>', {dataProjection: 'EPSG:4326',featureProjection: 'EPSG:3857'});
var newgeometry = inpoly.getGeometry();

console.log(newgeometry);

mapView.fit(newgeometry);
<?
}
?>
</script>











<?
include("../includes/footer.php");
?>