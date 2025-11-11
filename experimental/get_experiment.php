<?php
/**
 * File: get_experiment.php
 * Description: Retrieves and returns experiment data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../prepare_connections.php");
include("../expdb/straboexpclass.php");

function throwError($message){
	$out = new stdClass();
	$out->Error = $message;
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($out, JSON_PRETTY_PRINT);
}

$experiment_pkey = isset($_GET['e']) ? (int)$_GET['e'] : 0;
if($experiment_pkey == 0) throwError("Experiment not found.");
$row = $db->get_row_prepared("SELECT * FROM straboexp.experiment WHERE pkey = $1 AND userpkey = $2", array($experiment_pkey, $userpkey));
if($row->pkey == "") throwError("Experiment not found.");

$exp = new StraboExp($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$exp->setuuid($uuid);

$json = $row->json;
$id = $row->id;
$uuid = $row->uuid;
$json = json_decode($json);
$json->experimentid = $id;
$json->uuid = $uuid;
$json = json_encode($json, JSON_PRETTY_PRINT);


header('Content-Type: application/json; charset=utf-8');
echo $json;
?>