<?php
/**
 * File: build_m_doi.php
 * Description: Handles build m doi operations
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

$row = $db->get_row_prepared("SELECT * FROM micro_projectmetadata WHERE id = $1 AND userpkey = $2", array($pid, $userpkey));
if(!$row->id)  die("Project not found.");

$straboOut = new straboOutputClass($strabo,$_GET);

$doiOut = new doiOutputClass($strabo, $_GET);

$uuid = $uuid->v4();

$projectid = $row->strabo_id;
$projectname = $row->name;

$db->prepare_query("INSERT INTO dois (uuid, strabo_project_id, user_pkey, project_name, doi_type) VALUES ($1, $2, $3, $4, $5)",
	array($uuid, $projectid, $userpkey, $projectname, 'micro'));

exec("cp -rp /srv/app/www/straboMicroView/smzFiles/" . escapeshellarg($pid) . " /srv/app/www/doi/doiFiles/" . escapeshellarg($uuid));

$out = new stdClass();
$out->Message = "Success!";
$out->uuid = $uuid;
$out = json_encode($out);
header('Content-Type: application/json; charset=utf-8');
echo $out;

?>