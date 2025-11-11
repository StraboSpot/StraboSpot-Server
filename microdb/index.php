<?php
/**
 * File: index.php
 * Description: Main page or directory index
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


set_time_limit(0);
//Initialize Databases
include_once "../includes/config.inc.php";
include "../db.php";
include "../neodb.php";
include "./strabomicroclass.php";
include_once('../includes/geophp/geoPHP.inc');
include_once "../includes/UUID.php";

$username=pg_escape_string(strtolower($_SERVER['PHP_AUTH_USER']));
$password=$_SERVER['PHP_AUTH_PW'];
$server = print_r($_SERVER, true);

if(file_exists("uploadLog.txt")){
}

$row = $db->get_row("select * from users where email='$username' and crypt('$password', password) = password and active = TRUE limit 1");

if($row->pkey == "" &&  md5($password)!=$hashval){
	header("HTTP/1.1 401 Unauthorized");
	echo "Unauthorized";exit();
}

$userpkey=$db->get_var("select pkey from users where email='$username'");

if($userpkey == ""){
	header("HTTP/1.1 401 Unauthorized");
	echo "Unauthorized";exit();
}

$userpkey=(int)$userpkey;

//Load Base Controller
include "./controllers/MyController.php";

//Load Additional Controllers
foreach (glob("./controllers/*.php") as $filename){
	include_once $filename;
}

include "./library/Request.php";
include "./views/ApiView.php";
include "./views/JsonView.php";
include "./views/HtmlView.php";

$sm = new StraboMicro($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$sm->setuuid($uuid);

$request = new Request();

//log raw input for debug

if(file_exists("log.txt")){
	if($username=="jasonash@ku.edu" || $username=="schermer@wwu.edu"){
		$rawinput = file_get_contents("php://input");
		file_put_contents ("log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
		file_put_contents ("log.txt", "REQUEST: ".ucfirst($request->url_elements[1])."\n\n", FILE_APPEND);
		file_put_contents ("log.txt", "REQUEST_URI: ".$_SERVER["REQUEST_URI"]."\n\n", FILE_APPEND);
		file_put_contents ("log.txt", "username: $username\n\n", FILE_APPEND);
		file_put_contents ("log.txt", "Raw Input:\n".$rawinput, FILE_APPEND);
		file_put_contents ("log.txt", "Request Method: ".$_SERVER['REQUEST_METHOD'], FILE_APPEND);
	}
}

// route the request to the right place
$controller_name = ucfirst($request->url_elements[1]) . 'Controller';

$showcontroller = $request->url_elements[1];
if($showcontroller==""){$showcontroller="null";}

if (class_exists($controller_name)) {
	$controller = new $controller_name();
	$controller->setstrabomicrohandler($sm);
	$action_name = strtolower($request->verb) . 'Action';
	$result = $controller->$action_name($request);
}else{
	//send an error header with brief explanation.
	header("Bad Request", true, 404);
	$result['Error']="No such function (".$showcontroller.")";
	header('Content-Type: application/json; charset=utf8');
}

$view_name = ucfirst($request->apiformat) . 'View';
if(class_exists($view_name)) {
	$view = new $view_name();
	$view->render($result);
}else{
	header("Bad Request", true, 400);
	echo "Error: $request->format output not supported.";
}