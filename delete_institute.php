<?php
/**
 * File: delete_institute.php
 * Description: Deletes records from institute table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

$userpkey = $_SESSION['userpkey'];

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

if(!in_array($userpkey, $admin_pkeys)){ //restrict to admins
	exit();
}

$pkey = isset($_GET['p']) ? (int)$_GET['p'] : 0;

if($pkey == 0) exit();

$db->prepare_query("DELETE FROM institute WHERE pkey = $1", array($pkey));

header("Location: institutes");

?>