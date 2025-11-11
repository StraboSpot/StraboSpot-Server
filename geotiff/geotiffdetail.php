<?php
/**
 * File: geotiffdetail.php
 * Description: Error
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


$hash = $_GET['hash'] ?? '';
$hash = preg_replace('/[^a-zA-Z0-9\-]/', '', $hash);

include("../includes/config.inc.php");
include("../db.php");

$row = $db->get_row_prepared("SELECT * FROM geotiffs WHERE hash=$1", array($hash));

$userpkey = (int)$row->userpkey;
$user = $db->get_row_prepared("SELECT * FROM users WHERE pkey = $1", array($userpkey));

$ownerstring = $user->firstname . " " . $user->lastname . " (" . $user->email . ")";

$pkey=$row->pkey;
$name=$row->name;
$gdalinfo=$row->gdalinfo;

if($row->pkey==""){
	include("../includes/mheader.php");
	?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Error</h2>
						</header>
						GeoTIFF not found.
					<div class="bottomSpacer"></div>

					</div>
				</div>
	<?php
	include("../includes/mfooter.php");
	exit();
}

$gdalinfo = explode("\n",$gdalinfo);

foreach($gdalinfo as $part){
	if(substr($part,0,10)=="Lower Left"){
		$part=trim(explode(")",$part)[0]);
		$part=trim(explode("(",$part)[1]);
		$left = trim(explode(",",$part)[0]);
		$bottom = trim(explode(",",$part)[1]);

	}
	if(substr($part,0,11)=="Upper Right"){
		$part=trim(explode(")",$part)[0]);
		$part=trim(explode("(",$part)[1]);
		$right = trim(explode(",",$part)[0]);
		$top = trim(explode(",",$part)[1]);

	}

}

if($left!="" && $right!="" && $top!="" && $bottom!=""){
	$envelope = "POLYGON(($left $bottom, $left $top, $right $top, $right $bottom, $left $bottom))";
}

include("../includes/mheader.php");

?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Map: <?php echo $name?></h2>
						</header>

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
		width:100%;
		height:500px;
		background-color:#EEE;
	}

.copybutton {
	width:25px;
	height:25px;
}

</style>

<div style="display:none">envelope: <?php echo $envelope?></div>
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

<h2 style="font-size:16px;padding:.01em 0;">Owned by: <?php echo $ownerstring?> <a href="../dl/<?php echo $hash?>">Download GeoTIFF</a></h2>
<div id="map" class="map"></div>
<div>
	<div align="center" style="padding-top:10px; font-size:1.5em;">
		To use this map within the StraboSpot app, use the Other Basemaps function and choose "StraboSpot MyMaps" as the map type with the following code:
	</div>
	<div align="center" style="padding-top:5px;">
		<input id="copybox" type="text" value="<?php echo $hash?>" readonly><br>

		<!--<a href="javascript:void(0);" onclick="myCopy();"><img class="copybutton" src="/includes/images/copy-icon.png"></img></a>-->

			<button class="btn" data-clipboard-text="<?php echo $hash?>">
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
			title: '<?php echo $name?>',
			source: new ol.source.XYZ({
				url: 'https://strabospot.org/geotiff/tiles/<?php echo $hash?>/{z}/{x}/{y}.png'
			})
		})

	]
});

var mapView = new ol.View({
	'projection': 'EPSG:3857',
	'center': [-11000000, 4600000],
	'zoom': 5, //5
	'maxZoom': 50,
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

<?php
if($envelope!=""){
?>
var newformat = new ol.format.WKT();
var inpoly = (new ol.format.WKT()).readFeature('<?php echo $envelope?>', {dataProjection: 'EPSG:4326',featureProjection: 'EPSG:3857'});
var newgeometry = inpoly.getGeometry();

console.log(newgeometry);

mapView.fit(newgeometry);
<?php
}
?>
</script>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include("../includes/mfooter.php");
?>