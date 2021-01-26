<?
include_once "../../includes/config.inc.php"; //credentials, etc
include "../../db.php"; //postgres database abstraction layer

$rows = $db->get_results("select * from giga order by pkey");

include("../header.php");
?>
<h1>Gigapan Database</h1>



<div class="bigtable">

	<table>
		<tr>
			<th>pkey</th>
			<th>Image Title</th>
			<th>Image Description</th>
			<th>Class</th>
			<th>Rock Type</th>
			<th>Specialized Name</th>
			<th>Sample Name</th>
			<th>General Location</th>
			<th>Minerals Present</th>
			<th>Polarization</th>
			<th>Pixel Size Mm</th>
			<th>Width Of Field Mm</th>
			<th>Credit</th>
			<th>P Gigapan Id</th>
			<th>X Gigapan Id</th>
			<th>Longitude</th>
			<th>Latitude</th>
		</tr>

<?



foreach($rows as $row){

	$pkey=$row->pkey;
	$image_title=$row->image_title;
	$image_description=$row->image_description;
	$class=$row->class;
	$rock_type=$row->rock_type;
	$specialized_name=$row->specialized_name;
	$sample_name=$row->sample_name;
	$general_location=$row->general_location;
	$minerals_present=$row->minerals_present;
	$polarization=$row->polarization;
	$pixel_size_mm=$row->pixel_size_mm;
	$width_of_field_mm=$row->width_of_field_mm;
	$credit=$row->credit;
	$p_gigapan_id=$row->p_gigapan_id;
	$x_gigapan_id=$row->x_gigapan_id;
	$longitude=$row->longitude;
	$latitude=$row->latitude;

	
	?>
		<tr>
			<td><?=$pkey?></td>
			<td><?=$image_title?></td>
			<td><?=$image_description?></td>
			<td><?=$class?></td>
			<td><?=$rock_type?></td>
			<td><?=$specialized_name?></td>
			<td><?=$sample_name?></td>
			<td><?=$general_location?></td>
			<td><?=$minerals_present?></td>
			<td><?=$polarization?></td>
			<td><?=$pixel_size_mm?></td>
			<td><?=$width_of_field_mm?></td>
			<td><?=$credit?></td>
			<td><?=$p_gigapan_id?></td>
			<td><?=$x_gigapan_id?></td>
			<td><?=$longitude?></td>
			<td><?=$latitude?></td>
		</tr>
	<?
	
}


?>

	</table>
</div>



<?
include("../footer.php");
?>