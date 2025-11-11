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


//Initialize Databases
include_once "../includes/config.inc.php";
include "../db.php";
include "../neodb.php";
include "./strabospotclass.php";
include_once('../includes/geophp/geoPHP.inc');
include_once "../includes/UUID.php";

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

list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));

$username = strtolower(trim($_SERVER['PHP_AUTH_USER']));
$password = $_SERVER['PHP_AUTH_PW'];

// Validate email format for security
if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
	http_response_code(401);
	header('Content-Type: application/json');
	echo json_encode(['Error' => 'Invalid credentials']);
	exit();
}

$userpkey = $db->get_var_prepared("SELECT pkey FROM users WHERE email=$1", array($username));
$userpkey = (int)$userpkey;

$strabo = new StraboSpot($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$strabo->setuuid($uuid);

$request = new Request();

$request_method = $_SERVER['REQUEST_METHOD'];
$user_agent = $_SERVER['HTTP_USER_AGENT'];

//log raw input for debug

$rawinput = file_get_contents("php://input");

if(file_exists("log.txt")){
	if($_SERVER["REQUEST_URI"] != "/db/imagexxx"){
		if($username=="jasonash@ku.edu" || $username=="riplangford@gmail.comdd" || $username=="nathan.novak79@gmail.comdd"){

			file_put_contents ("log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
			file_put_contents ("log.txt", "REQUEST: ".ucfirst($request->url_elements[1])."\n\n", FILE_APPEND);
			file_put_contents ("log.txt", "REQUEST_URI: ".$_SERVER["REQUEST_URI"]."\n\n", FILE_APPEND);
			file_put_contents ("log.txt", "username: $username\n\n", FILE_APPEND);
			file_put_contents ("log.txt", "Raw Input:\n".$rawinput, FILE_APPEND);
			file_put_contents ("log.txt", "Request Method: ".$_SERVER['REQUEST_METHOD'], FILE_APPEND);
		}
	}
}

//Delete raw cache data that is older than 1 week
$db->query("
	delete from rawcache WHERE uploaddate < NOW() - INTERVAL '1 WEEK'
");

//Store username, request, request_uri, rawinput, date
$db->query("
	insert into rawcache (
							username,
							request,
							request_uri,
							rawdata,
							request_method,
							user_agent
						) values (
							'$username',
							'".$request->url_elements[1]."',
							'".$_SERVER["REQUEST_URI"]."',
							'".pg_escape_string($rawinput)."',
							'$request_method',
							'$user_agent'
							);
");

// route the request to the right place
$request_type = $request->url_elements[1];
$controller_name = ucfirst($request->url_elements[1]) . 'Controller';

$showcontroller = $request->url_elements[1];
if($showcontroller==""){$showcontroller="null";}

if (class_exists($controller_name)) {
	$controller = new $controller_name();
	$controller->setstrabohandler($strabo);
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

	//Log REST call to Matomo
	$username=$_SERVER['PHP_AUTH_USER'];
	$remoteip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$rand = rand(111111,999999);
	$userpkey = $strabo->userpkey;
	$id = str_repeat("0", 16 - strlen($userpkey)) . $userpkey;

	$params = array(
		'action_name' => 'Strabo REST API',
		'url' => 'https://strabospot.org/db/'.$request_type,
		'idsite' => '1',
		'rand' => $rand,
		'uid' => $username,
		'rec' => '1',
		'apiv' => '1',
		'_id' => $id,
		'send_image' => '0',
		'token_auth' => '01e0d17a086d20a2c2ee04064d0d6bc7',
		'cip' => $remoteip
	);

	$endpoint = 'https://stats.strabospot.org/matomo.php';

	$url = $endpoint . '?' . http_build_query($params);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);

	$view = new $view_name();
	$view->render($result);

}else{
	header("Bad Request", true, 400);
	echo "Error: $request->format output not supported.";
}