<?php
/**
 * File: get_apprepo_facility_alone.php
 * Description: Retrieves and returns apprepo facility alone data
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

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id == 0){
	$error = new stdClass();
	$error->Error = "Id not provided.";
	header('Content-type: application/json');
	echo json_encode($error, JSON_PRETTY_PRINT);
	exit();
}

$obj = $exp->getApprepoFacilityAlone($id);
header('Content-type: application/json');
echo json_encode($obj, JSON_PRETTY_PRINT);





?>