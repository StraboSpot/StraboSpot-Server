<?php
/**
 * File: project_public.php
 * Description: Public view interface for shared projects
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

session_start();

if($_SESSION['loggedin']=="yes"){

	include("../prepare_connections.php");
	$userpkey = $_SESSION['userpkey'];

	$pkey = isset($_GET['projectid']) ? (int)$_GET['projectid'] : 0;
	$state = $_GET['state'] ?? '';
	$state = ($state === 'public') ? 'public' : 'private';

	if($userpkey!="" && $pkey > 0 && $state!=""){

		if($state=="public"){
			$db->prepare_query("UPDATE straboexp.project SET ispublic = true WHERE pkey=$1 AND userpkey = $2", array($pkey, $userpkey));
		}else{
			$db->prepare_query("UPDATE straboexp.project SET ispublic = false WHERE pkey=$1 AND userpkey = $2", array($pkey, $userpkey));
		}
	}
}

?>