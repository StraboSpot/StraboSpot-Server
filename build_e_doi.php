<?php
/**
 * File: build_e_doi.php
 * Description: Handles build e doi operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


sleep(3);

$pid = isset($_GET['p']) ? (int)$_GET['p'] : 0;
if($pid == 0) die("No project provided");

ini_set('max_execution_time', 3000);
include("logincheck.php");
include("prepare_connections.php");

include("includes/straboClasses/straboOutputClass.php");
include("doi/doiOutputClass.php");

$row = $db->get_row_prepared("SELECT * FROM straboexp.project WHERE pkey = $1 AND userpkey = $2", array($pid, $userpkey));
if(!$row->pkey)  die("Project not found.");

$projectname = $row->name;
$projectid = $row->uuid;

$project = new stdClass();
$project->name = $projectname;
$project->created_timestamp = $row->created_timestamp;
$project->modified_timestamp = $row->modified_timestamp;

$experiments = [];

$exprows = $db->get_results_prepared("SELECT * FROM straboexp.experiment WHERE project_pkey = $1", array($pid));

foreach($exprows as $exprow){
	$json = $exprow->json;
	$exp = json_decode($json);

	$met = new stdClass();
	$met->name = $exprow->id;
	$met->uuid = $uuid->v4();
	$met->created_timestamp = $exprow->created_timestamp;
	$met->modified_timestamp = $exprow->modified_timestamp;
	$exp->metadata = $met;

	$experiments[] = $exp;
}

$project->experiments = $experiments;

$project = json_encode($project, JSON_PRETTY_PRINT);

$straboOut = new straboOutputClass($strabo,$_GET);

$doiOut = new doiOutputClass($strabo, $_GET);

$uuid = $uuid->v4();

//Make UUID folder to hold data
mkdir("doi/doiFiles/$uuid");
file_put_contents("doi/doiFiles/$uuid/project.json", $project);

$db->prepare_query("INSERT INTO dois (uuid, strabo_project_id, user_pkey, project_name, doi_type) VALUES ($1, $2, $3, $4, $5)",
	array($uuid, $projectid, $userpkey, $projectname, 'experimental'));

$out = new stdClass();
$out->Message = "Success!";
$out->uuid = $uuid;
$out = json_encode($out);
header('Content-Type: application/json; charset=utf-8');
echo $out;

?>