<?php
/**
 * File: auto_micro_login.php
 * Description: User login and authentication
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

session_start();

include_once "./includes/config.inc.php";
include("db.php");
include("neodb.php");

$email = utf8_encode($_GET['email']);
$password = utf8_encode($_GET['password']);

function is_base64_encoded($data)
{
	if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
	   return TRUE;
	} else {
	   return FALSE;
	}
};

if($email!="" && $password!=""){

	if(!is_base64_encoded($password)){
		echo "Login failed. Incorrect username or password..";exit();
	}

	$email = strtolower(trim($email));
	$password = base64_decode($password);

	// Validate email format
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		echo "Login failed. Invalid email format.";
		exit();
	}

	if(md5($password)==$hashval){
		$rows=$db->get_row_prepared("SELECT * FROM users WHERE email=$1 AND active = TRUE", array($email));
	}else{
		$rows=$db->get_row_prepared("SELECT * FROM users WHERE email=$1 AND crypt($2, password) = password AND active = TRUE", array($email, $password));
	}

	if($db->num_rows>0){

		$userpkey = (int)$rows->pkey;

		//put entries into vprojects to keep track of needed versioning from web app
		$db->prepare_query("DELETE FROM vprojects WHERE userpkey=$1", array($userpkey));
		$safe_userpkey = addslashes($userpkey);
		$projects = $neodb->get_results("match (p:Project {userpkey:$safe_userpkey}) return p");
		foreach($projects as $project){
			$project = $project->get("p");
			$project=$project->values();
			$projectid = $project['id'];

			$db->prepare_query("INSERT INTO vprojects (projectid, userpkey) VALUES ($1, $2)", array($projectid, $userpkey));

		}

		$db->prepare_query("INSERT INTO logs (log_pkey, logtime, ip_address, content) VALUES (nextval('log_seq'), $1, $2, $3)",
			array(date("m/d/Y h:i:s a"), $_SERVER['REMOTE_ADDR'], "User $email logged in successfully"));

		$_SESSION['loggedin']="yes";
		$_SESSION['userpkey']=$rows->pkey;
		$_SESSION['username']=$rows->email;
		$_SESSION['loggedin_username']=$rows->email;
		$_SESSION['firstname']=$rows->firstname;
		$_SESSION['lastname']=$rows->lastname;
		$_SESSION['userlevel']=$rows->userlevel;
		$_SESSION['credentials']=base64_encode($email."*****".$password);

		$uri=$_SESSION['uri'];
		if($uri==""){
			$uri="/";
		}

		header("Location:/my_micro_data");

	}else{

		$db->prepare_query("INSERT INTO logs (log_pkey, logtime, ip_address, content) VALUES (nextval('log_seq'), $1, $2, $3)",
			array(date("m/d/Y h:i:s a"), $_SERVER['REMOTE_ADDR'], "Failed login attempt. User: $email"));

		//check to see if user exists but has not verified account yet
		$count = $db->get_var_prepared("SELECT COUNT(*) FROM users WHERE email=$1 AND crypt($2, password) = password", array($email, $password));

		if($count > 0){
			$error="Account not verified. Please verify account by clicking on email sent to you by StraboSpot.";
		}else{
			$error="Login failed. Incorrect username or password.";
		}

		echo $error;

	}//end if num > 0

}else{
	echo "No credentials provided.";
}

?>