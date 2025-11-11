<?php
/**
 * File: microprojectpdf.php
 * Description: Generates PDF reports for Strabo Micro projects
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

SESSION_START();
include("prepare_connections.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$row = $db->get_row_prepared("SELECT * FROM micro_projectmetadata WHERE id = $1 AND (ispublic OR userpkey=$2)", array($id, $userpkey));

if($row->id == ""){
	echo "Error! Project not found.";
	exit();
}

$json = $row->projectjson;
$json = json_decode($json);

if($_GET['foo']=="bar") $db->dumpVar($json); exit();

?>