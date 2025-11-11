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

	include("prepare_connections.php");
	$userpkey = $_SESSION['userpkey'];

	$projectid = $_GET['projectid'] ?? '';
	$projectid = preg_replace('/[^a-zA-Z0-9\-]/', '', $projectid);
	$state = $_GET['state'] ?? '';
	$state = ($state === 'public') ? 'public' : 'private';

	if($userpkey!="" && $projectid!="" && $state!=""){

		$p = $strabo->getProject($projectid);

		unset($p->self);

		$safe_projectid = addslashes($projectid);
		$safe_userpkey = addslashes($userpkey);

		if($state=="public"){

				$neodb->query("match (p:Project {id:$safe_projectid,userpkey:$safe_userpkey}) set p.public = true;");
				$db->prepare_query("UPDATE project SET ispublic = true WHERE strabo_project_id=$1 AND user_pkey = $2", array($projectid, $userpkey));

		}else{
			$neodb->query("match (p:Project {id:$safe_projectid,userpkey:$safe_userpkey}) set p.public = false;");
				$db->prepare_query("UPDATE project SET ispublic = false WHERE strabo_project_id=$1 AND user_pkey = $2", array($projectid, $userpkey));
		}

	}

}

?>