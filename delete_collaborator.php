<?php
/**
 * File: delete_collaborator.php
 * Description: Deletes records from collaborators table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

$project_id = $_GET['p'] ?? '';
$project_id = preg_replace('/[^a-zA-Z0-9\-]/', '', $project_id);

$uuid = $_GET['u'] ?? '';
$uuid = preg_replace('/[^a-zA-Z0-9\-]/', '', $uuid);
if($uuid == "") exit("No uuid provided.");

$db->prepare_query("DELETE FROM collaborators WHERE uuid = $1", array($uuid));

if($project_id != ""){
	header("Location: collaborate?p=$project_id");
}else{
	header("Location: my_field_data2");
}

?>