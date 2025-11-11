<?php
/**
 * File: delete_apparatus.php
 * Description: Deletes apparatus records
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

$userpkey = (int)$_SESSION['userpkey'];

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

if(!in_array($userpkey, $admin_pkeys)){
	//only admins for now...
	$is_admin = false;
}else{
	$is_admin = true;
}

$apparatus_pkey = $_GET['a'] ?? '';
if(!is_numeric($apparatus_pkey) || $apparatus_pkey == ""){
	echo "Invalid apparatus provided.";
	exit();
}
$apparatus_pkey = (int)$apparatus_pkey;

$arow = $db->get_row_prepared("SELECT * FROM exp_apparatus WHERE pkey = $1", array($apparatus_pkey));

if(!$is_admin && $arow->userpkey != $userpkey){
	echo "not authorized";
	exit();
}

if($arow->pkey == ""){
	echo "Apparatus not found.";
	exit();
}

//OK, first delete all images
$files = scandir($_SERVER['DOCUMENT_ROOT']."/expimages");

foreach($files as $file){
	if (strpos($file, "_".$apparatus_pkey."_") !== false || $file == "photo_".$apparatus_pkey || $file == "schematic_".$apparatus_pkey || $file == "electrical_".$apparatus_pkey ) {
		unlink($_SERVER['DOCUMENT_ROOT']."/expimages/".$file);
	}
}

$db->prepare_query("DELETE FROM exp_images WHERE apparatus_pkey = $1", array($apparatus_pkey));
$db->prepare_query("DELETE FROM exp_apparatus WHERE pkey = $1", array($apparatus_pkey));

header("Location: /apparatus_repository");

?>