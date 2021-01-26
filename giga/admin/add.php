<?

include_once "../../includes/config.inc.php"; //credentials, etc
include "../../db.php"; //postgres database abstraction layer

if($_POST['submit']!=""){
	if($_POST['submit']=="Save"){

		$pkey = $_POST['pkey'];
		$image_title = $_POST['image_title'];
		$image_description = $_POST['image_description'];
		$class = $_POST['class'];
		$rock_type = $_POST['rock_type'];
		$specialized_name = $_POST['specialized_name'];
		$sample_name = $_POST['sample_name'];
		$minerals_present = $_POST['minerals_present'];
		$polarization = $_POST['polarization'];
		$pixel_size_mm = $_POST['pixel_size_mm'];
		$width_of_field_mm = $_POST['width_of_field_mm'];
		$credit = $_POST['credit'];
		$p_gigapan_id = $_POST['p_gigapan_id'];
		$x_gigapan_id = $_POST['x_gigapan_id'];
		$longitude = $_POST['longitude'];
		$latitude = $_POST['latitude'];
		$general_location = $_POST['general_location'];

		$keywords = $image_title." ".$image_description." ".$class." ".$rock_type." ".$sample_name." ".$minerals_present." ".$credit." ".$general_location;
		$keywords = pg_escape_string($keywords);
		$vectors = "to_tsvector('$keywords')";

		$db->query("
		insert into giga 
			(
				image_title,
				image_description,
				minerals_present,
				sample_name,
				class,
				pixel_size_mm,
				rock_type,
				specialized_name,
				width_of_field_mm,
				credit,
				p_gigapan_id,
				x_gigapan_id,
				longitude,
				latitude,
				keywords
			) values (
				'$image_title',
				'$image_description',
				'$minerals_present',
				'$sample_name',
				'$class',
				'$pixel_size_mm',
				'$rock_type',
				'$specialized_name',
				'$width_of_field_mm',
				'$credit',
				'$p_gigapan_id',
				'$x_gigapan_id',
				'$longitude',
				'$latitude',
				$vectors
			)
		");
		
		include("../header.php");
		
		?>
		
		<h1>Add New Entry</h1>
		<div>Entry Saved Successfully</div>
		<div style="padding-left:50px;padding-top:10px;"><a href="./">Continue</a>
		
		<?
		
		include("../footer.php");
		
		
		exit();
	}else{
	
		header("Location: ./");
	
	}
}

//$row = $db->get_row("select * from giga where pkey = $pkey");

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

//$db->dumpVar($row);

include("../header.php");

$inputsize = 40;

?>

<h1>Add New Entry</h1>
<div class="detailwrapper">
	
	<form method="POST">

		<table>
			<tr><td><div class="detailrow"><span class="detailheading">Image Title:</td><td><input type="text" name="image_title" value="<?=$image_title?>" size="<?=$inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Image Description:</td><td><input type="text" name="image_description" value="<?=$image_description?>" size="<?=$inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Class:</td><td><input type="text" name="class" value="<?=$class?>" size="<?=$inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Rock Type:</td><td><input type="text" name="rock_type" value="<?=$rock_type?>" size="<?=$inputsize?>"></td></tr>
			<!--
			<tr><td><div class="detailrow"><span class="detailheading">Specialized Name:</td><td><input type="text" name="specialized_name" value="<?=$specialized_name?>" size="<?=$inputsize?>"></td></tr>
			-->
			<tr><td><div class="detailrow"><span class="detailheading">Sample Name:</td><td><input type="text" name="sample_name" value="<?=$sample_name?>" size="<?=$inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">General Location:</td><td><input type="text" name="general_location" value="<?=$general_location?>" size="<?=$inputsize?>"></td></tr>

			<tr><td><div class="detailrow"><span class="detailheading">Minerals Present:</td><td><input type="text" name="minerals_present" value="<?=$minerals_present?>" size="<?=$inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Polarization:</td><td><input type="text" name="polarization" value="<?=$polarization?>" size="<?=$inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Pixel Size (mm):</td><td><input type="text" name="pixel_size_mm" value="<?=$pixel_size_mm?>" size="<?=$inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Latitude:</td><td><input type="text" name="latitude" value="<?=$latitude?>" size="<?=$latitude?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Longitude:</td><td><input type="text" name="longitude" value="<?=$longitude?>" size="<?=$longitude?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Width of Field (mm):</td><td><input type="text" name="width_of_field_mm" value="<?=$width_of_field_mm?>" size="<?=$inputsize?>"></td></tr>
			<!--
			<tr><td><div class="detailrow"><span class="detailheading">Collector:</td><td><input type="text" name="collector" value="<?=$collector?>" size="<?=$inputsize?>"></td></tr>
			-->
			<tr><td><div class="detailrow"><span class="detailheading">Credit:</td><td><input type="text" name="credit" value="<?=$credit?>" size="<?=$inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Gigapan ID:</td><td><input type="text" name="p_gigapan_id" value="<?=$p_gigapan_id?>" size="<?=$inputsize?>"></td></tr>
			<tr><td><div class="detailrow"><span class="detailheading">Cross Polarized Gigapan ID:</td><td><input type="text" name="x_gigapan_id" value="<?=$x_gigapan_id?>" size="<?=$inputsize?>"></td></tr>
			
		</table>	
		
		<input type="hidden" name="pkey" value="<?=$pkey?>">
		
		<div style="margin-top:20px;margin-bottom:20px;">
			<span style="margin-left:150px;"><input type="submit" name="submit" value="Cancel"><span>
			<span style="margin-left:50px;"><input type="submit" name="submit" value="Save"><span>
		</div>
	
	</form>
	


</div>

<?

include("../footer.php");

?>