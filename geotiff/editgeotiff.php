<?PHP

include("../logincheck.php");


$hash = pg_escape_string($_GET['hash']);
$userpkey = pg_escape_string($_SESSION['userpkey']);

include("../includes/config.inc.php");
include("../db.php");

$row = $db->get_row("select * from geotiffs where hash='$hash' and userpkey=$userpkey");
//$row = $db->get_row("select * from geotiffs where hash='$hash'");

$pkey=$row->pkey;
$name=$row->name;

if($row->pkey==""){
	include("../includes/header.php");
	?>
	GeoTIFF not found.
	<?
	include("../includes/footer.php");
	exit();
}


if($_POST['submit']){

	$mapname = $_POST['mapname'];

	$db->query("update geotiffs set name='$mapname' where hash='$hash' and userpkey=$userpkey");
	header("Location: /geotiff");
	
	exit();
	
}


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



<script src='/assets/js/jquery/jquery.min.js'></script>



<div id="successmessage"></div>

<h2>Edit Map Name:</h2>
<div>
	<form method="POST">
		<input type="text" name="mapname" value="<?=$name?>"> <input type="submit" name="submit" value="Save">
	</form>
</div>
















<?
include("../includes/footer.php");
?>