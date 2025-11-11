<?php
/**
 * File: delete_facility.php
 * Description: Deletes facility records
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../prepare_connections.php");

$pkey = isset($_GET['fpk']) ? (int)$_GET['fpk'] : 0;

$credentials = $_SESSION['credentials'];

if(!in_array($userpkey, $admin_pkeys)){
	$is_admin = false;
}else{
	$is_admin = true;
}

//Check to be sure we are authorized to edit this facility
if($is_admin){
	$row = $db->get_row_prepared("SELECT * FROM apprepo.facility WHERE pkey = $1", array($pkey));
}else{
	//$row = $db->get_row("select * from apprepo.facility where pkey = $pkey and userpkey = $userpkey");
	$row = $db->get_row_prepared("SELECT * FROM apprepo.facility WHERE pkey = $1 AND pkey IN (SELECT facility_pkey FROM apprepo.facility_users WHERE users_pkey = $2)", array($pkey, $userpkey));
}

if($row->pkey == ""){
	echo "facility not found.";exit();
}

//Check to be sure we are authorized to edit this facility
if($is_admin){
	$db->prepare_query("DELETE FROM apprepo.facility WHERE pkey = $1", array($pkey));
}else{
	//$db->query("delete from apprepo.facility where pkey = $pkey and userpkey = $userpkey");
	$db->prepare_query("DELETE FROM apprepo.facility WHERE pkey = $1 AND pkey IN (SELECT facility_pkey FROM apprepo.facility_users WHERE users_pkey = $2)", array($pkey, $userpkey));
}

header("Location: apparatus_repository");

?>