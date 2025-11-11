<?php
/**
 * File: outprojectjson.php
 * Description: Handles outprojectjson operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

$project_id = $_GET['project_id'];

if($project_id==""){
	echo "no project id provided";exit();
}

if(!is_numeric($project_id)){
	echo "invalid project id.";exit();
}

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

$safe_userpkey = addslashes($userpkey);
$safe_project_id = addslashes($project_id);

$row = $neodb->get_results("match (p:Project {userpkey:$safe_userpkey,id:$safe_project_id}) optional match (p)-[HAS_DATASET]->(d:Dataset) optional match (d)-[HAS_SPOT]->(s:Spot) with p,d,count(s) as count with p,collect ({d:d,count:count}) as d return p,d order by p.modified_timestamp desc;");
$row = $row[0];

if($row==""){
	echo "project $project_id not found";exit();
}

$project = $row->get("p");
$project=(object)$project->values();

$project_id = $project->id;

$project = $strabo->getProject($project_id);

$datasets = $row->get("d");
if(count($datasets)>0){

	$project->datasets = [];

	foreach($datasets as $dataset){

		$spotcount = $dataset["count"];

		$ds=$dataset["d"];
		$ds=(object)$ds->values();

		$dataset_id = $ds->id;

		$dataset = (object) $strabo->getSingleDataset($dataset_id);

		if($spotcount > 0){

			$spots = $strabo->getDatasetSpots($dataset_id);

			$dataset->spots = $spots;

		}

		$project->datasets[] = $dataset;

	}
}

$out = new stdClass();
$out->project = $project;

$json = json_encode($out, JSON_PRETTY_PRINT);

header('Content-Type: application/json; charset=utf-8');
echo $json;

?>