<?php
/**
 * File: inEditApparatus.php
 * Description: Apparatus editing interface
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

//Check json for errors before passing to experimental API:
$json = $_REQUEST['json'];
$json = json_decode($json);


$apparatus_pkey = $_POST['apparatus_pkey'];

if($json->apparatus != ""){
	$obj = $exp->insertApparatus($json, null, $_FILES, $apparatus_pkey);
	header('Content-type: application/json');
	echo json_encode($obj, JSON_PRETTY_PRINT);
}else{
	$error = new stdClass();
	$error->Error = "Invalid JSON provided.";
	header('Content-type: application/json');
	echo json_encode($error, JSON_PRETTY_PRINT);
}









?>