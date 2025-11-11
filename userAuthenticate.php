<?php
/**
 * File: userAuthenticate.php
 * Description: User authentication and credential verification handler
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


if($_SERVER['REQUEST_METHOD']!="POST"){
	header("Bad Request", true, 400);
	$output['error'] = "Invalid Request Method.";
	header('Content-Type: application/json; charset=utf8');
	echo json_encode($output);
	exit();
}

$body = file_get_contents('php://input');

$jsonbody = json_decode($body);

$email = strtolower(trim($jsonbody->email ?? ''));
$password = $jsonbody->password ?? '';

// Validate email format for security
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	header("Bad Request", true, 400);
	$output['error'] = "Invalid email format.";
	header('Content-Type: application/json; charset=utf8');
	echo json_encode($output);
	exit();
}

if($email == "" || $password == ""){
	header("Bad Request", true, 400);
	$output['error'] = "Email address and passord must be provided.";
	header('Content-Type: application/json; charset=utf8');
	echo json_encode($output);
	exit();
}

include_once "./includes/config.inc.php";
include("db.php");

$pkey = "";

$row = $db->get_row_prepared("SELECT * FROM users WHERE email=$1 AND crypt($2, password) = password AND active=TRUE AND deleted = FALSE", array($email, $password));

if($row->pkey != "") $pkey = $row->pkey;

if($pkey == ""){
	$row = $db->get_row_prepared("SELECT * FROM apptokens WHERE email=$1 AND uuid = $2", array($email, $password));
	if($row->pkey != "") $pkey = $row->pkey;
}

if($pkey != ""){
	$output['valid']="true";
	if($row->profileimage != ""){
		$output['profileimage']="http://strabospot.org/db/profileimage";
	}
}elseif(md5($password)==$hashval){
	$valrow = $db->get_row_prepared("SELECT * FROM users WHERE email=$1 AND active=TRUE", array($email));
	if($valrow->pkey != ""){
		$output['valid']="true";
		if($row->profileimage != ""){
			$output['profileimage']="http://strabospot.org/db/profileimage";
		}
	}else{
		$output['valid']="false";
	}
}else{
	$output['valid']="false";
}

header('Content-Type: application/json; charset=utf8');
echo json_encode($output);
return true;

?>