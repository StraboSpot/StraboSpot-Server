<?

include_once "../../includes/config.inc.php"; //credentials, etc
include "../../db.php"; //postgres database abstraction layer


if($_POST['submit']!=""){
	if($_POST['submit']=="Upload"){

		
		
		//$db->dumpVar($_POST);
		
		try {
			$rows = json_decode($_POST["jsondata"]);
		} catch (Exception $e) {
			echo 'Error reading JSON data: ',  $e->getMessage(), "\n";
			exit();
		}
		
		
		if($rows == ""){
			echo "Error reading JSON data.";
			exit();
		}
		
		//$db->dumpVar($rows);exit();
		
		$db->query("truncate table giga");
		
		foreach($rows as $row){
		
			//$dumpVar($row)
			
			$image_title=$row->image_title;
			$image_description=$row->image_description;
			$class=$row->class;
			$rock_type=$row->type;
			$sample_name=$row->sample_name;
			$minerals_present=$row->minerals_present;
			$pixel_size_mm=$row->pixel_size_mm;
			$width_of_field_mm=$row->width_of_field_mm;
			$credit=$row->Credit;
			$p_gigapan_id=$row->P_gigapan_ID;
			$x_gigapan_id=$row->X_gigapan_ID;
			$longitude=$row->longitude;
			$latitude=$row->latitude;
			$general_location=$row->general_location;
			
			$keywords = $image_title." ".$image_description." ".$class." ".$rock_type." ".$sample_name." ".$minerals_present." ".$credit." ".$general_location;
			$keywords = pg_escape_string($keywords);
			$vectors = "to_tsvector('$keywords')";
		
			
			
			
			
			
			
			
			//exit();
			$query = "
					insert into giga
						(
							image_title,
							image_description,
							class,
							rock_type,
							sample_name,
							minerals_present,
							pixel_size_mm,
							width_of_field_mm,
							credit,
							p_gigapan_id,
							x_gigapan_id,
							longitude,
							latitude,
							keywords,
							general_location
						)values(
							'$image_title',
							'$image_description',
							'$class',
							'$rock_type',
							'$sample_name',
							'$minerals_present',
							'$pixel_size_mm',
							'$width_of_field_mm',
							'$credit',
							'$p_gigapan_id',
							'$x_gigapan_id',
							'$longitude',
							'$latitude',
							$vectors,
							'$general_location'
						)
			";
			
			$db->query($query);
		
		}
		
		include("../header.php");
		
		?>
		
		<h1>JSON Upload</h1>
		<div>Data Saved Successfully</div>
		<div style="padding-left:50px;padding-top:10px;"><a href="./">Continue</a>
		
		<?
		
		include("../footer.php");
		
		
		exit();


	}else{
	
		header("Location: ./");
	
	}
}





include("../header.php");

$inputsize = 40;

?>

<h1>Upload JSON</h1>
<div class="detailwrapper">
	
	<form method="POST">

		<div>
			Paste JSON here. Careful! Entire database will be overwritten!<br>
			JSON can be generated using this tool:<br>
			<div style="text-align: center;">
				<a href="https://www.csvjson.com/csv2json" target="_blank">https://www.csvjson.com/csv2json</a>
			</div>
		</div>
		<textarea name="jsondata" rows="30" cols="60"></textarea>

		
		<div style="margin-top:20px;margin-bottom:20px;">
			<span style="margin-left:100px;"><input type="submit" name="submit" value="Cancel""><span>
			<span style="margin-left:50px;"><input type="submit" name="submit" value="Upload" onclick="return confirm('Are you sure? Database will be overwritten!')"><span>
		</div>
	
	</form>
	


</div>

<?

include("../footer.php");

?>