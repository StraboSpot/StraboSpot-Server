<?php
/**
 * File: delete_doi.php
 * Description: Deletes Digital Object Identifier (DOI) records and associated files from the system
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

$uuid = $_GET['u'] ?? '';
// UUIDs should only contain alphanumeric characters and hyphens
$uuid = preg_replace('/[^a-zA-Z0-9\-]/', '', $uuid);
if($uuid == "") die("No uuid provided.");

$row = $db->get_row_prepared("SELECT * FROM dois WHERE user_pkey = $1 AND uuid=$2", array($userpkey, $uuid));

if($row->pkey == "") die("Project not found.");

if($uuid !=""){

	$db->prepare_query("DELETE FROM dois WHERE user_pkey = $1 AND uuid = $2", array($userpkey, $uuid));
	exec("rm -rf doi/doiFiles/" . escapeshellarg($uuid), $results);

}

$out = new stdClass();
$out->Message = "Success! Deleted uuid: $uuid.";
$out->uuid = $uuid;
$out = json_encode($out);
header('Content-Type: application/json; charset=utf-8');
echo $out;

?>