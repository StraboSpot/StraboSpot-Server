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

include("../logincheck.php");
include("../prepare_connections.php");

$pkey = isset($_GET['apk']) ? (int)$_GET['apk'] : 0;

$credentials = $_SESSION['credentials'];

if(!in_array($userpkey, $admin_pkeys)){
	$is_admin = false;
}else{
	$is_admin = true;
}

//Check to be sure we are authorized to edit this facility
if($is_admin){
	$row = $db->get_row_prepared("SELECT * FROM apprepo.apparatus WHERE pkey = $1", array($pkey));
}else{
	$row = $db->get_row_prepared("SELECT * FROM apprepo.apparatus WHERE pkey = $1 AND userpkey = $2", array($pkey, $userpkey));
}

if($row->pkey == ""){
	echo "apparatus not found.";exit();
}

//Check to be sure we are authorized to edit this facility
if($is_admin){
	$db->prepare_query("DELETE FROM apprepo.apparatus WHERE pkey = $1", array($pkey));
}else{
	$db->prepare_query("DELETE FROM apprepo.apparatus WHERE pkey = $1 AND userpkey = $2", array($pkey, $userpkey));
}

header("Location: apparatus_repository");

?>