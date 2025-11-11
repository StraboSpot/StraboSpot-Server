<?php
/**
 * File: activate_version.php
 * Description: Activates a specific version of project data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

$pkey = isset($_GET['p']) ? (int)$_GET['p'] : 0;

include_once("../adminkeys.php");

//Check for Login Timeout Here
include("apiLoginCheck.php");

include("../prepare_connections.php");
include("../expdb/straboexpclass.php");

$exp = new StraboExp($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$exp->setuuid($uuid);

$uuid = $db->get_var_prepared("SELECT uuid FROM straboexp.versions WHERE pkey = $1 AND userpkey = $2", array($pkey, $userpkey));
if($uuid != ""){
	$project_pkey = $db->get_var_prepared("SELECT pkey FROM straboexp.project WHERE uuid = $1 AND userpkey = $2", array($uuid, $userpkey));
	if($project_pkey != ""){
		$exp->createProjectVersion($project_pkey);
	}
	$exp->restoreVersion($pkey);
	header("Location: /my_experimental_data");
}else{
	die("version not found on server.");
}


?>