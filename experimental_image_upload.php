<?php
/**
 * File: experimental_image_upload.php
 * Description: Handles experimental image upload operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

$userpkey = $_SESSION['userpkey'];

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

$valid_types = array("schematic", "photo", "cpsensor", "tempsensor", "dispaxsensor", "disprotsensor", "dispvolsensor", "dispshearsensor", "forcesensor", "torquesensor", "poresensor", "electrical");

$type = $_GET['type'] ?? '';
$pkey = isset($_GET['pkey']) ? (int)$_GET['pkey'] : 0;
$sensor_num = isset($_GET['sensor_num']) ? (int)$_GET['sensor_num'] : 0;

$files = print_r($_FILES, true);

if(in_array($type, $valid_types)){

/*
apparatus_pkey
type
original_file_name
upload_date
userpkey
*/

	$original_file_name = $_FILES['file']['name'];
	$tmp_name = $_FILES['file']['tmp_name'];

	if($original_file_name != ""){

		doLog("*******************************************************************************");
		doLog("type: " . $type);
		doLog("pkey: " . $pkey);
		doLog("files: " . $files);
		doLog("userpkey: " . $userpkey);
		doLog("sensor_num: " . $sensor_num);
		doLog("DOCUMENT ROOT: " . $_SERVER['DOCUMENT_ROOT']);
		doLog("*******************************************************************************");

		if(in_array($type, array("schematic", "photo", "electrical"))){

			$new_file_name = $_SERVER['DOCUMENT_ROOT']."/expimages/$type"."_".$pkey;

			$db->prepare_query("DELETE FROM exp_images WHERE userpkey = $1 AND type = $2 AND apparatus_pkey = $3",
				array($userpkey, $type, $pkey));

			$db->prepare_query("INSERT INTO exp_images (apparatus_pkey, type, original_file_name, userpkey) VALUES ($1, $2, $3, $4)",
				array($pkey, $type, $original_file_name, $userpkey));

			copy($tmp_name, $new_file_name);

		}else{

			$new_file_name = $_SERVER['DOCUMENT_ROOT']."/expimages/$type"."_".$pkey."_".$sensor_num;

			$db->prepare_query("DELETE FROM exp_images WHERE userpkey = $1 AND type = $2 AND apparatus_pkey = $3 AND sensor_num = $4",
				array($userpkey, $type, $pkey, $sensor_num));

			$db->prepare_query("INSERT INTO exp_images (apparatus_pkey, type, original_file_name, userpkey, sensor_num) VALUES ($1, $2, $3, $4, $5)",
				array($pkey, $type, $original_file_name, $userpkey, $sensor_num));

			copy($tmp_name, $new_file_name);

		}

	}else{
		doLog("no file provided.");
	}

}else{
	doLog("*******************************************************************************");
	doLog("invalid type.");
	doLog("*******************************************************************************");
}

//type and pkey

function doLog($line){
	if(is_writable("log.txt")){
		file_put_contents("log.txt", $line."\n", FILE_APPEND | LOCK_EX);
	}
}

?>