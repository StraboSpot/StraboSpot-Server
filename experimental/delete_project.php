<?php
/**
 * File: delete_project.php
 * Description: Deletes projects and all associated data from the system
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

$pkey = isset($_GET['ppk']) ? (int)$_GET['ppk'] : 0;

$credentials = $_SESSION['credentials'];

if(!in_array($userpkey, $admin_pkeys)){
	$is_admin = false;
}else{
	$is_admin = true;
}

//Check to be sure we are authorized to edit this facility
if($is_admin){
	$row = $db->get_row_prepared("SELECT * FROM straboexp.project WHERE pkey = $1", array($pkey));
}else{
	$row = $db->get_row_prepared("SELECT * FROM straboexp.project WHERE pkey = $1 AND userpkey = $2", array($pkey, $userpkey));
}

if($row->pkey == ""){
	echo "project not found.";exit();
}

//Check to be sure we are authorized to edit this facility

//create version here
$exp = new StraboExp($neodb,$userpkey,$db);
$uuid = new UUID();
$exp->setuuid($uuid);
$exp->createProjectVersion($pkey);

if($is_admin){
	$db->prepare_query("DELETE FROM straboexp.project WHERE pkey = $1", array($pkey));
}else{
	$db->prepare_query("DELETE FROM straboexp.project WHERE pkey = $1 AND userpkey = $2", array($pkey, $userpkey));
}

header("Location: /my_experimental_data");

?>