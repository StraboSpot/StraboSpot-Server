<?php
/**
 * File: deletegeotiff.php
 * Description: Deletes records from geotiffs table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("../logincheck.php");

$hash = $_GET['hash'] ?? '';
$hash = preg_replace('/[^a-zA-Z0-9\-]/', '', $hash);
$userpkey = (int)$_SESSION['userpkey'];

include("../includes/config.inc.php");
include("../db.php");

$row = $db->get_row_prepared("SELECT * FROM geotiffs WHERE hash=$1 AND userpkey=$2", array($hash, $userpkey));

if($row->pkey!=""){
	$db->prepare_query("DELETE FROM geotiffs WHERE hash=$1 AND userpkey=$2", array($hash, $userpkey));
	unlink("/var/www/geotiff/upload/files/" . basename($hash) . ".tif");
	unlink("/var/www/geotiff/upload/maps/" . basename($hash) . ".map");
	header("Location:/geotiff");
}

?>