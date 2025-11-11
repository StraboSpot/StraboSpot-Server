<?php
/**
 * File: get_my_experiments.php
 * Description: Retrieves and returns my experiments data
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



$exp = new StraboExp($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$exp->setuuid($uuid);

$json = new stdClass();

$project_rows = $db->get_results("select * from straboexp.project where userpkey = $userpkey order by modified_timestamp desc");
$projects = [];
foreach($project_rows as $prow){
	$p = new stdClass();
	$p->pkey = $prow->pkey;
	$p->userpkey = $prow->userpkey;
	$p->uuid = $prow->uuid;
	$p->created_timestamp = $prow->created_timestamp;
	$p->modified_timestamp = $prow->modified_timestamp;
	$p->name = $prow->name;
	$p->notes = $prow->notes;
	$p->ispublic = $prow->ispublic;

	$experiments = [];
	$experiment_rows = $db->get_results("select * from straboexp.experiment where project_pkey = $p->pkey order by modified_timestamp desc");
	foreach($experiment_rows as $erow){
		$e = new stdClass();
		$e->pkey = $erow->pkey;
		$e->project_pkey = $erow->project_pkey;
		$e->userpkey = $erow->userpkey;
		$e->id = $erow->id;
		$e->created_timestamp = $erow->created_timestamp;
		$e->modified_timestamp = $erow->modified_timestamp;
		$e->uuid = $erow->uuid;
		$experiments[] = $e;
	}

	$p->experiments = $experiments;

	$projects[] = $p;
}

$json->projects = $projects;

$json = json_encode($json, JSON_PRETTY_PRINT);

header('Content-Type: application/json; charset=utf-8');
echo $json;
?>