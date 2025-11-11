<?php
/**
 * File: inEditFacility.php
 * Description: Facility editing interface
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include_once("../adminkeys.php");

//Check for Login Timeout Here
include("apiLoginCheck.php");

include("../prepare_connections.php");
include("../expdb/straboexpclass.php");

$exp = new StraboExp($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$exp->setuuid($uuid);
$exp->setadminkeys($admin_pkeys);

//Check to see if we have permission to edit
$pkey = isset($_GET['p']) ? (int)$_GET['p'] : 0;
if($pkey == 0){
	$error = new stdClass();
	$error->Error = "No id provided.";
	header('Content-type: application/json');
	echo json_encode($error, JSON_PRETTY_PRINT);
	exit();
}

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
	$error = new stdClass();
	$error->Error = "Facility not found.";
	header('Content-type: application/json');
	echo json_encode($error, JSON_PRETTY_PRINT);
	exit();
}

//Check json for errors before passing to experimental API:
$json = $_REQUEST['json'];
$json = json_decode($json);



if($json->facility != ""){
	$obj = $exp->insertFacility($json, $pkey);
	header('Content-type: application/json');
	echo json_encode($obj, JSON_PRETTY_PRINT);
}else{
	$error = new stdClass();
	$error->Error = "Invalid JSON provided.";
	header('Content-type: application/json');
	echo json_encode($error, JSON_PRETTY_PRINT);
}









?>