<?php
/**
 * File: edit.php
 * Description: Edits records in giga table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

$pkey = isset($_GET['s']) ? (int)$_GET['s'] : 0;
if($pkey == 0) exit();

include_once "../../includes/config.inc.php"; //credentials, etc
include "../../db.php"; //postgres database abstraction layer

if($_POST['submit']!=""){
	if($_POST['submit']=="Save"){

		$pkey = isset($_POST['pkey']) ? (int)$_POST['pkey'] : 0;
		$image_title = isset($_POST['image_title']) ? trim($_POST['image_title']) : '';
		$image_description = isset($_POST['image_description']) ? trim($_POST['image_description']) : '';
		$class = isset($_POST['class']) ? trim($_POST['class']) : '';
		$rock_type = isset($_POST['rock_type']) ? trim($_POST['rock_type']) : '';
		$specialized_name = isset($_POST['specialized_name']) ? trim($_POST['specialized_name']) : '';
		$sample_name = isset($_POST['sample_name']) ? trim($_POST['sample_name']) : '';
		$minerals_present = isset($_POST['minerals_present']) ? trim($_POST['minerals_present']) : '';
		$polarization = isset($_POST['polarization']) ? trim($_POST['polarization']) : '';
		$pixel_size_mm = isset($_POST['pixel_size_mm']) ? trim($_POST['pixel_size_mm']) : '';
		$width_of_field_mm = isset($_POST['width_of_field_mm']) ? trim($_POST['width_of_field_mm']) : '';
		$credit = isset($_POST['credit']) ? trim($_POST['credit']) : '';
		$p_gigapan_id = isset($_POST['p_gigapan_id']) ? trim($_POST['p_gigapan_id']) : '';
		$x_gigapan_id = isset($_POST['x_gigapan_id']) ? trim($_POST['x_gigapan_id']) : '';
		$longitude = isset($_POST['longitude']) ? trim($_POST['longitude']) : '';
		$latitude = isset($_POST['latitude']) ? trim($_POST['latitude']) : '';
		$general_location = isset($_POST['general_location']) ? trim($_POST['general_location']) : '';

		$keywords = $image_title." ".$image_description." ".$class." ".$rock_type." ".$sample_name." ".$minerals_present." ".$credit." ".$general_location;

		$db->prepare_query("
			UPDATE giga SET
				image_title=$1,
				image_description=$2,
				class=$3,
				rock_type=$4,
				specialized_name=$5,
				sample_name=$6,
				minerals_present=$7,
				polarization=$8,
				pixel_size_mm=$9,
				width_of_field_mm=$10,
				credit=$11,
				p_gigapan_id=$12,
				x_gigapan_id=$13,
				longitude=$14,
				latitude=$15,
				keywords=to_tsvector($16),
				general_location=$17
			WHERE pkey = $18
		", array(
			$image_title,
			$image_description,
			$class,
			$rock_type,
			$specialized_name,
			$sample_name,
			$minerals_present,
			$polarization,
			$pixel_size_mm,
			$width_of_field_mm,
			$credit,
			$p_gigapan_id,
			$x_gigapan_id,
			$longitude,
			$latitude,
			$keywords,
			$general_location,
			$pkey
		));

		include("../header.php");

		?>

		<h1>Edit Entry</h1>
		<div>Entry Saved Successfully</div>
		<div style="padding-left:50px;padding-top:10px;"><a href="./">Continue</a>

		<?php

		include("../footer.php");

		exit();
	}elseif($_POST['submit']=="Delete"){

		$pkey = isset($_POST['pkey']) ? (int)$_POST['pkey'] : 0;
		//delete here
		$db->prepare_query("DELETE FROM giga WHERE pkey=$1", array($pkey));

		include("../header.php");

		?>

		<h1>Edit Entry</h1>
		<div>Entry Deleted Successfully</div>
		<div style="padding-left:50px;padding-top:10px;"><a href="./">Continue</a>

		<?php

		include("../footer.php");

		exit();

	}else{

		header("Location: ./");

	}
}

$row = $db->get_row_prepared("SELECT * FROM giga WHERE pkey = $1", array($pkey));

$pkey = $row->pkey;
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
$credit=$row->credit;
$p_gigapan_id=$row->p_gigapan_id;
$x_gigapan_id=$row->x_gigapan_id;
$longitude=$row->longitude;
$latitude=$row->latitude;
$general_location=$row->general_location;

include("../header.php");

$inputsize = 40;

?>

<h1>Edit Entry</h1>
<div class="detailwrapper">

	<form method="POST">

		<table>
			<tr><td><div class="detailrow"><span class="detailheading">Image Title:</td><td><input type="text" name="image_title" value="<?php echo $image_title?>" size="<?php echo $inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Image Description:</td><td><input type="text" name="image_description" value="<?php echo $image_description?>" size="<?php echo $inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Class:</td><td><input type="text" name="class" value="<?php echo $class?>" size="<?php echo $inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Rock Type:</td><td><input type="text" name="rock_type" value="<?php echo $rock_type?>" size="<?php echo $inputsize?>"></td></tr>

			<!--
			<tr><td><div class="detailrow"><span class="detailheading">Specialized Name:</td><td><input type="text" name="specialized_name" value="<?php echo $specialized_name?>" size="<?php echo $inputsize?>"></td></tr>
			-->
			<tr><td><div class="detailrow"><span class="detailheading">Sample Name:</td><td><input type="text" name="sample_name" value="<?php echo $sample_name?>" size="<?php echo $inputsize?>"></td></tr>

			<tr><td><div class="detailrow"><span class="detailheading">General Location:</td><td><input type="text" name="general_location" value="<?php echo $general_location?>" size="<?php echo $inputsize?>"></td></tr>

			<tr><td><div class="detailrow"><span class="detailheading">Minerals Present:</td><td><input type="text" name="minerals_present" value="<?php echo $minerals_present?>" size="<?php echo $inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Polarization:</td><td><input type="text" name="polarization" value="<?php echo $polarization?>" size="<?php echo $inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Pixel Size (mm):</td><td><input type="text" name="pixel_size_mm" value="<?php echo $pixel_size_mm?>" size="<?php echo $inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Latitude:</td><td><input type="text" name="latitude" value="<?php echo $latitude?>" size="<?php echo $latitude?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Longitude:</td><td><input type="text" name="longitude" value="<?php echo $longitude?>" size="<?php echo $longitude?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Width of Field (mm):</td><td><input type="text" name="width_of_field_mm" value="<?php echo $width_of_field_mm?>" size="<?php echo $inputsize?>"></td></tr>
			<!--
			<tr><td><div class="detailrow"><span class="detailheading">Collector:</td><td><input type="text" name="collector" value="<?php echo $collector?>" size="<?php echo $inputsize?>"></td></tr>
			-->
			<tr><td><div class="detailrow"><span class="detailheading">Credit:</td><td><input type="text" name="credit" value="<?php echo $credit?>" size="<?php echo $inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Gigapan ID:</td><td><input type="text" name="p_gigapan_id" value="<?php echo $p_gigapan_id?>" size="<?php echo $inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Cross Polarized Gigapan ID:</td><td><input type="text" name="x_gigapan_id" value="<?php echo $x_gigapan_id?>" size="<?php echo $inputsize?>"></td></tr>

		</table>

		<input type="hidden" name="pkey" value="<?php echo $pkey?>">

		<div style="margin-top:20px;margin-bottom:20px;">
			<span style="margin-left:100px;"><input type="submit" name="submit" value="Cancel""><span>
			<span style="margin-left:50px;"><input type="submit" name="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this entry?')"><span>
			<span style="margin-left:50px;"><input type="submit" name="submit" value="Save"><span>
		</div>

	</form>

</div>

<?php

include("../footer.php");

?>