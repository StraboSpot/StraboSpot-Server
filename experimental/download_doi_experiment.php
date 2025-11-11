<?php
/**
 * File: download_doi_experiment.php
 * Description: Downloads experimental data with DOI information
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../prepare_connections.php");


function fixFileName($filename){
	$filename = str_replace(" ", "_", $filename);
	$filename = preg_replace('/[^A-Za-z0-9\-_]/', '', $filename);
	return $filename;
}

$project_uuid = $_GET['p'];
if($project_uuid == "") die("Project not found.");
$row = $db->get_row("select * from dois where uuid = '$project_uuid'");
if($row->pkey == "") die("Project not found.");

$experiment_uuid = $_GET['e'];
if($experiment_uuid == "") die("Experiment not found.");

//Get Experiment from File
$json = file_get_contents("/srv/app/www/doi/doiFiles/$project_uuid/project.json");
$json = json_decode($json);

$expfound = false;
$experiments = $json->experiments;
foreach($experiments as $e){
	if($e->metadata->uuid == $experiment_uuid){
		$expfound = true;
		$json = $e;
	}
}

if(!$expfound) die("Experiment not found.");


$datasetname = $json->metadata->name;

unset($json->metadata);

$filename = fixFileName($datasetname).".json";

$out = json_encode($json, JSON_PRETTY_PRINT);

header("Content-Type: application/json");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Length: " . strlen($out));
echo $out;


?>