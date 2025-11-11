<?php
/**
 * File: projectadmingetusers.php
 * Description: Handles projectadmingetusers operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

function showerror($val){
	$out = new stdClass();
	$out->Error = $val;
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($out, JSON_PRETTY_PRINT);
	exit();
}

if(!in_array($userpkey, array(3,9,905,2272,4,342))){
	showerror("Access Denied!");
}

$searchstring = isset($_GET['s']) ? strtolower(trim($_GET['s'])) : '';
$safe_searchstring = addslashes($searchstring);

if($searchstring == ""){
	showerror("No Search Provided.");
}

$rows = $neodb->get_results("match (u:User) where lower(u.firstname + ' ' + u.lastname) contains '$safe_searchstring' or lower(u.email) contains '$safe_searchstring' optional match (u)-[r:HAS_PROJECT]->(p:Project) return u, count(p) as c order by u.lastname, u.firstname limit 10;");

$results = array();

foreach($rows as $row){
	$result = new stdClass();
	$user = (object)$row->get("u")->values();
	$projectcount = $row->get("c");

	$result->pkey = $user->userpkey;
	$result->name = $user->firstname . " " . $user->lastname;
	$result->email = $user->email;
	$result->projectcount = $projectcount;

	$results[] = $result;

}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($results, JSON_PRETTY_PRINT);

?>