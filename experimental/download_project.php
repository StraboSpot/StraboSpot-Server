<?php
/**
 * File: download_project.php
 * Description: Downloads project data in various export formats
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

if(isset($_GET['u']) && $_GET['u'] != ""){
	$uuid = preg_replace('/[^a-zA-Z0-9\-]/', '', $_GET['u']);
	$project_pkey = $db->get_var_prepared("SELECT pkey FROM straboexp.project WHERE uuid = $1", array($uuid));
}else{
	$project_pkey = isset($_GET['ppk']) ? (int)$_GET['ppk'] : 0;
}

if($project_pkey == "" || $project_pkey == 0) die("Project not found.");
$row = $db->get_row_prepared("
	SELECT
	pkey,
	name,
	to_char (modified_timestamp AT TIME ZONE 'UTC', 'Dy, Mon DD YYYY HH24:MI:SS UTC') as modified_timestamp,
	to_char (created_timestamp AT TIME ZONE 'UTC', 'Dy, Mon DD YYYY HH24:MI:SS UTC') as created_timestamp,
	notes,
	uuid
	FROM straboexp.project WHERE pkey = $1 AND (userpkey = $2 OR ispublic)
", array($project_pkey, $userpkey));
if($row->pkey == "") die("project not found.");

//Wed, Oct 18 2023 19:29:57 UTC

$exp = new StraboExp($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$exp->setuuid($uuid);

$filename = $row->name.".json";
$filename = $exp->fixFileName($filename);

$project = new stdClass();
$project->name = $row->name;
$project->created_timestamp = $row->created_timestamp;
$project->last_modified_timestamp = $row->modified_timestamp;
$project->notes = $row->notes;

$experiments = [];
$exprows = $db->get_results_prepared("SELECT * FROM straboexp.experiment WHERE project_pkey = $1", array($project_pkey));
foreach($exprows as $exprow){
	$experiments[] = json_decode($exprow->json);
}

$project->experiments = $experiments;

$project->uuid = $row->uuid;

$out = new stdClass();
$out->project = $project;

$json = json_encode($out, JSON_PRETTY_PRINT);

header('Content-disposition: attachment; filename='.$filename);
header('Content-type: application/json');
echo $json;
?>