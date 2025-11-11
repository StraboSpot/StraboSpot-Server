<?php
/**
 * File: projectadmingetprojects.php
 * Description: Handles projectadmingetprojects operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

function showerror($val){
	$out = new stdClass();
	$out->Error = $val;
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($out, JSON_PRETTY_PRINT);
	exit();
}

if(!in_array($userpkey, array(3,9,905,2272,4,342))){
	showerror("Access Denied!");
}

$u = isset($_GET['u']) ? (int)$_GET['u'] : 0;

if($u == 0){
	showerror("No Pkey Provided.");
}

$rows = $neodb->get_results("match (p:Project {userpkey:$u}) optional match (p)-[HAS_DATASET]->(d:Dataset) return p, count(d) as c order by p.modified_timestamp desc;");

$results = array();

foreach($rows as $row){
	$result = new stdClass();
	$project = (object)$row->get("p")->values();
	$datasetcount = $row->get("c");

	$result->id = $project->id;
	$result->name = $project->desc_project_name;
	$result->modified_timestamp = date("F j, Y, g:i a T P", substr($project->modified_timestamp,0,10));
	$result->datasetcount = $datasetcount;

	$results[] = $result;

}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($results, JSON_PRETTY_PRINT);

?>