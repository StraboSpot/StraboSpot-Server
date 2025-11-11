<?php
/**
 * File: logMicroInstall.php
 * Description: Logs Strabo Micro application installation events
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

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://tools.keycdn.com/geo.json?host=".$ip);
	curl_setopt($ch,CURLOPT_USERAGENT, "keycdn-tools:https://strabospot.org");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$json = curl_exec($ch);
	$json = json_decode($json);

	$latitude = "null";
	$longitude = "null";

	if($json->status == "success"){
		$data = $json->data->geo;
		$longitude = $data->longitude;
		$latitude = $data->latitude;
		$country = $data->country_name;
		$region = $data->region_name;
	}

	$query = "insert into micro_installs (	ip,
version,
longitude,
latitude,
country,
region,
email
)
values
(
'$ip',
'$ver',
$longitude,
$latitude,
'$country',
'$region',
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