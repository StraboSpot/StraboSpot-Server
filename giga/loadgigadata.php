<?
exit();
include_once "../includes/config.inc.php"; //credentials, etc
include "../db.php"; //postgres database abstraction layer

//loadgigadata.php
$data = file_get_contents("gigadata.json");
$data = json_decode($data);

//$db->dumpVar($data); exit();

foreach($data as $row){

	$image_title = $row->image_title;
	$image_description = $row->image_description;
	$minerals_present = $row->minerals_present;
	$sample_name = $row->sample_name;
	$class = $row->class;
	$pixel_size_mm = $row->pixel_size_mm;
	$rock_type = $row->rock_type;
	$specialized_name = $row->specialized_name;
	$width_of_field_mm = $row->width_of_field_mm;
	$collector = $row->collector;
	$credit = $row->Credit;
	$p_gigapan_id = $row->p_gigapan_id;
	$x_gigapan_id = $row->x_gigapan_id;

	$query = "
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
			collector,
			credit,
			p_gigapan_id,
			x_gigapan_id
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
			'$collector',
			'$credit',
			'$p_gigapan_id',
			'$x_gigapan_id'
		)
	";
	
	//$db->dumpVar($query);exit();
	//GRANT USAGE, SELECT ON SEQUENCE giga_pkey_seq TO strabodbuser;
	
	$db->query($query);
	
	echo "$image_title done. <br>";

}

?>