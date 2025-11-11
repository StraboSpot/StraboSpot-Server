<?php
/**
 * File: micro_project_public.php
 * Description: Public view interface for published Strabo Micro projects
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

session_start();

if($_SESSION['loggedin']=="yes"){

	include("prepare_connections.php");
	$userpkey = $_SESSION['userpkey'];

	$projectid = isset($_GET['projectid']) ? (int)$_GET['projectid'] : 0;
	$state = $_GET['state'] ?? '';
	$state = ($state === 'public') ? 'public' : 'private';

	if($userpkey!="" && $projectid > 0 && $state!=""){

		if($state=="public"){
			$db->prepare_query("UPDATE micro_projectmetadata SET ispublic = true WHERE id=$1 AND userpkey = $2", array($projectid, $userpkey));
		}else{
			$db->prepare_query("UPDATE micro_projectmetadata SET ispublic = false WHERE id=$1 AND userpkey = $2", array($projectid, $userpkey));
		}
	}
}

?>