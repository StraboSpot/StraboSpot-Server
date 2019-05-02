<?

if($_SERVER['REQUEST_METHOD']!="POST"){
	header("Bad Request", true, 400);
	$output['error'] = "Invalid Request Method.";
	header('Content-Type: application/json; charset=utf8');
	echo json_encode($output);
	exit();
}

$body = file_get_contents('php://input');

$jsonbody = json_decode($body);

$email = strtolower($jsonbody->email);
$password = $jsonbody->password;

if($email == "" || $password == ""){
	header("Bad Request", true, 400);
	$output['error'] = "Email address and passord must be provided.";
	header('Content-Type: application/json; charset=utf8');
	echo json_encode($output);
	exit();
}

include_once "./includes/config.inc.php";
include("db.php");

$row = $db->get_row("select * from users where email='$email' and crypt('$password', password) = password and active=TRUE;");

if($row->pkey != ""){
	$output['valid']="true";
	if($row->profileimage != ""){
		$output['profileimage']="http://strabospot.org/db/profileimage";
	}
}else{
	$output['valid']="false";
}

header('Content-Type: application/json; charset=utf8');
echo json_encode($output);
return true;

?>