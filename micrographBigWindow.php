<?php
/**
 * File: micrographBigWindow.php
 * Description: Micrograph data handler
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

SESSION_START();
include("prepare_connections.php");
include 'microdb/microLandingClass.php';

$micrograph_id = isset($_GET['micrograph_id']) ? (int)$_GET['micrograph_id'] : 0;
$pkey = isset($_GET['pkey']) ? (int)$_GET['pkey'] : 0;

$row = $db->get_row_prepared("SELECT * FROM micro_projectmetadata WHERE id = $1 AND (ispublic OR userpkey=$2)", array($pkey, $userpkey));

if($row->id == ""){
	echo "Error! Project not found.";
	exit();
}

$json = $row->projectjson;
$json = json_decode($json);
$json->pkey = $pkey;
$ml = new MicroLanding($json);

$ml->showMicrograph($micrograph_id);

?>