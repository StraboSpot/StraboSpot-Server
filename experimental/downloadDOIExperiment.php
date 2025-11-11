<?php
/**
 * File: downloadDOIExperiment.php
 * Description: Downloads experimental data with DOI information
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../prepare_connections.php");

$project_uuid = isset($_GET['p']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', $_GET['p']) : '';
if($project_uuid == "") die("Project not found.");
$row = $db->get_row_prepared("SELECT * FROM dois WHERE uuid = $1", array($project_uuid));
if($row->pkey == "") die("Project not found.");

$experiment_uuid = isset($_GET['e']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', $_GET['e']) : '';
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


header('Content-Type: application/json; charset=utf-8');
echo json_encode($json, JSON_PRETTY_PRINT);

?>