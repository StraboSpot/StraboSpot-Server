<?
$pkey = $_GET['s'];
if($pkey=="") exit();

include_once "../includes/config.inc.php"; //credentials, etc
include "../db.php"; //postgres database abstraction layer


$row = $db->get_row("select * from giga where pkey = $pkey");

$image_title=$row->image_title;
$image_description=$row->image_description;
$class=$row->class;
$rock_type=$row->rock_type;
$specialized_name=$row->specialized_name;
$sample_name=$row->sample_name;
$minerals_present=$row->minerals_present;
$polarization=$row->polarization;
$pixel_size_mm=$row->pixel_size_mm;
$width_of_field_mm=$row->width_of_field_mm;
$collector=$row->collector;
$credit=$row->credit;
$p_gigapan_id=$row->p_gigapan_id;
$x_gigapan_id=$row->x_gigapan_id;
$general_location=$row->general_location;

//$db->dumpVar($row);

include("header.php");


?>

<h1><?=$image_title?></h1>
<div class="detailwrapper">
	<!--<div class="detailrow"><span class="detailheading">Image Title: </span><span class="detailcontents"><?=$image_title?></span></div>-->
	<div class="detailrow"><span class="detailheading">Image Description: </span><span class="detailcontents"><?=$image_description?></span></div>
	<div class="detailrow"><span class="detailheading">Class: </span><span class="detailcontents"><?=$class?></span></div>
	<div class="detailrow"><span class="detailheading">Rock Type: </span><span class="detailcontents"><?=$rock_type?></span></div>
	<!--
	<div class="detailrow"><span class="detailheading">Specialized Name: </span><span class="detailcontents"><?=$specialized_name?></span></div>
	-->
	<div class="detailrow"><span class="detailheading">Sample Name: </span><span class="detailcontents"><?=$sample_name?></span></div>
	<div class="detailrow"><span class="detailheading">General Location: </span><span class="detailcontents"><?=$general_location?></span></div>
	<div class="detailrow"><span class="detailheading">Minerals Present: </span><span class="detailcontents"><?=$minerals_present?></span></div>
	<!--
	<div class="detailrow"><span class="detailheading">Polarization: </span><span class="detailcontents"><?=$polarization?></span></div>
	-->
	<div class="detailrow"><span class="detailheading">Pixel Size (mm): </span><span class="detailcontents"><?=$pixel_size_mm?></span></div>
	<div class="detailrow"><span class="detailheading">Width of Field (mm): </span><span class="detailcontents"><?=$width_of_field_mm?></span></div>
	
	<div class="detailrow"><span class="detailheading">Latitude: </span><span class="detailcontents"><?=$latitude?></span></div>
	<div class="detailrow"><span class="detailheading">Longitude: </span><span class="detailcontents"><?=$longitude?></span></div>

	<!--<div class="detailrow"><span class="detailheading">Collector: </span><span class="detailcontents"><?=$collector?></span></div>-->
	<div class="detailrow"><span class="detailheading">Credit: </span><span class="detailcontents"><?=$credit?></span></div>
	<!--
	<div class="detailrow"><span class="detailheading">P Gigapan ID: </span><span class="detailcontents"><?=$p_gigapan_id?></span></div>
	<div class="detailrow"><span class="detailheading">X Gigapan ID: </span><span class="detailcontents"><?=$x_gigapan_id?></span></div>
	-->
	<div class="detailrow"><span class="detailheading">Image:</span></div>
	<div class="detailrow">
		<a href="http://www.gigapan.com/gigapans/<?=$p_gigapan_id?>" target="_blank">
			<img src = "http://static.gigapan.com/gigapans0/<?=$p_gigapan_id?>/images/<?=$p_gigapan_id?>-600x400.jpg" width="500px;">
		</a>
	</div>
	<!--
	<div class="detailrow"><span class="detailheading"><a href="http://www.gigapan.com/gigapans/<?=$p_gigapan_id?>" target="_blank">Gigapan Link: </span><span class="detailcontents">(click here)</a></div>
	-->
<?
	if($x_gigapan_id != ""){
?>
	<div class="detailrow" style="margin-top:20px;"><span class="detailheading">Cross Polarized Image:</span></div>
	<div class="detailrow">
		<a href="http://www.gigapan.com/gigapans/<?=$x_gigapan_id?>" target="_blank">
			<img src = "http://static.gigapan.com/gigapans0/<?=$x_gigapan_id?>/images/<?=$x_gigapan_id?>-600x400.jpg" width="500px;">
		</a>
	</div>
	<!--
	<div class="detailrow"><span class="detailheading"><a href="http://www.gigapan.com/gigapans/<?=$x_gigapan_id?>" target="_blank">Gigapan Link: </span><span class="detailcontents">(click here)</a></div>
	-->
<?
	}
?>

</div>

<?

include("footer.php");

?>