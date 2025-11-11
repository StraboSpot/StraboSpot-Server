<?php
/**
 * File: experimental_image_delete.php
 * Description: Deletes experiments and related data
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

if(in_array($type, $valid_types)){

/*
apparatus_pkey
type
original_file_name
upload_date
userpkey
*/

	if(in_array($type, array("schematic", "photo", "electrical"))){
		//no sub num
		$count = $db->get_var_prepared("SELECT count(*) FROM exp_images WHERE userpkey = $1 AND type = $2 AND apparatus_pkey = $3",
			array($userpkey, $type, $pkey));
		if($count > 0){
			doLog("found row in database");
			$db->prepare_query("DELETE FROM exp_images WHERE userpkey = $1 AND type = $2 AND apparatus_pkey = $3",
				array($userpkey, $type, $pkey));
			$file_name = $_SERVER['DOCUMENT_ROOT']."/expimages/$type"."_".$pkey;
			unlink($file_name);
		}else{
			doLog("didn't find row in database");
		}
	}else{
		$count = $db->get_var_prepared("SELECT count(*) FROM exp_images WHERE userpkey = $1 AND type = $2 AND apparatus_pkey = $3 AND sensor_num = $4",
			array($userpkey, $type, $pkey, $sensor_num));
		if($count > 0){
			doLog("found row in database");
			$db->prepare_query("DELETE FROM exp_images WHERE userpkey = $1 AND type = $2 AND apparatus_pkey = $3 AND sensor_num = $4",
				array($userpkey, $type, $pkey, $sensor_num));
			$file_name = $_SERVER['DOCUMENT_ROOT']."/expimages/$type"."_".$pkey."_".$sensor_num;
			unlink($file_name);
		}else{
			doLog("didn't find row in database");
		}
	}

}else{
	doLog("*******************************************************************************");
	doLog("invalid type.");
	doLog("*******************************************************************************");
}

function doLog($line){
	if(is_writable("log.txt")){
		file_put_contents("log.txt", $line."\n", FILE_APPEND | LOCK_EX);
	}
}

?>