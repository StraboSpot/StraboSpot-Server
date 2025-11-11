<?php
/**
 * File: downloadgeotiff.php
 * Description: Handles downloadgeotiff operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


$hash = $_GET['hash'] ?? '';
$hash = preg_replace('/[^a-zA-Z0-9\-]/', '', $hash);
$userpkey = $_SESSION['userpkey'] ?? 0;

include("../includes/config.inc.php");
include("../db.php");

$row = $db->get_row_prepared("SELECT * FROM geotiffs WHERE hash=$1", array($hash));

if(!file_exists("upload/files/$hash.tif") || $row->pkey==""){
	exit("GeoTIFF not found.");
}

$filename = $row->name;

header('Content-Type: image/tiff');
header("Content-disposition: attachment; filename=\"" . $filename . "\"");
header("Content-Length: ".filesize("upload/files/$hash.tif"));
readfile("upload/files/$hash.tif");

?>