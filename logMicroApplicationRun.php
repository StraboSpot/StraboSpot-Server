<?php
/**
 * File: logMicroApplicationRun.php
 * Description: Logs Strabo Micro application runtime events and usage
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include_once "./includes/config.inc.php";
include("db.php");

$content = file_get_contents('php://input');
$content = json_decode($content);

$p = $content->password;
$ver = $content->version;
$email = $content->email;

if($p == "FwUk4wQF9dwumR2p8"){

	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

	$query = "insert into micro_runs (	ip,
version,
email
)
values
(
'$ip',
'$ver',
'$email'
)";

	$db->query($query);

	header('Content-Type: application/json');
	$data=[];
	$data['logged'] = "true";
	$data = json_encode($data);
	echo $data;

}

?>