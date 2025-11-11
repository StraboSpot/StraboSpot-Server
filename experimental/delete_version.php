<?php
/**
 * File: delete_version.php
 * Description: Deletes records from straboexp table(s)
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


//$exp->createProjectVersion(56);


//$exp->restoreVersion($pkey);

$db->prepare_query("DELETE FROM straboexp.versions WHERE pkey = $1 AND userpkey = $2", array($pkey, $userpkey));

header("Location: /versioning");
?>