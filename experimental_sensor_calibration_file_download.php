<?php
/**
 * File: experimental_sensor_calibration_file_download.php
 * Description: Downloads experimental data and results
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

//experimental_sensor_calibration_file_download.php

include("logincheck.php");

$userpkey = $_SESSION['userpkey'];

include("prepare_connections.php");

$apparatus_pkey = isset($_GET['apparatus_pkey']) ? (int)$_GET['apparatus_pkey'] : 0;
$type = $_GET['type'] ?? '';
$allowed_types = ['electrical', 'thermal', 'optical'];
if(!in_array($type, $allowed_types)) {
	echo "Invalid type.";
	exit();
}
$sensor_num = isset($_GET['sensor_num']) ? (int)$_GET['sensor_num'] : 0;

if($type=="electrical"){

$filename = $db->get_var_prepared("SELECT original_file_name FROM exp_images WHERE type='electrical' AND apparatus_pkey = $1", array($apparatus_pkey));

$path = $_SERVER['DOCUMENT_ROOT']."/expimages/electrical_".$apparatus_pkey;

}else{

$filename = $db->get_var_prepared("SELECT original_file_name FROM exp_images WHERE type=$1 AND apparatus_pkey = $2 AND sensor_num = $3", array($type, $apparatus_pkey, $sensor_num));

$path = $_SERVER['DOCUMENT_ROOT']."/expimages/".$type."_".$apparatus_pkey."_".$sensor_num;

}

if($filename != "" && file_exists($path)){

	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary");
	header("Content-disposition: attachment; filename=\"" . $filename . "\"");
	readfile($path);

}else{
	echo "File not found.";
}

?>