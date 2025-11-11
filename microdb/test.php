<?php
/**
 * File: test.php
 * Description: Testing and development utility file
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


exit();

set_time_limit(0);
//Initialize Databases
include_once "../includes/config.inc.php";
include "../db.php";
include "../neodb.php";
include "./strabomicroclass.php";
include_once('../includes/geophp/geoPHP.inc');
include_once "../includes/UUID.php";

$userpkey=(int)$userpkey;

$sm = new StraboMicro($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$sm->setuuid($uuid);

/*
$json = $db->get_var("select projectjson from strabomicro.micro_projectmetadata where strabo_id = '16866805364130'");
$json = json_decode($json);

$keywords = $sm->getKeywords($json);

$db->dumpVar($keywords);
*/

$rows = $db->get_results("select id, userpkey, projectjson from strabomicro.micro_projectmetadata order by id");

foreach($rows as $row){

	$userpkey = $row->userpkey;
	$userrow = $db->get_row("select * from users where pkey = $userpkey");
	$firstname = $userrow->firstname;
	$lastname = $userrow->lastname;

	$id = $row->id;
	$json = $row->projectjson;
	$json = json_decode($json);

	$keywords = $sm->getKeywords($json);
	$keywords .= " ".$firstname;
	$keywords .= " ".$lastname;

	$keywords = pg_escape_string($keywords);

	$db->query("update strabomicro.micro_projectmetadata set keywords = to_tsvector('$keywords') where id = $id");

	echo "$id $keywords<br>";
}































/*
20241030Bkup
$username=$_SERVER['PHP_AUTH_USER'];
$password=$_SERVER['PHP_AUTH_PW'];
$server = print_r($_SERVER, true);

if(file_exists("uploadLog.txt")){
	//$server = print_r($_SERVER,true);
	//file_put_contents ("uploadLog.txt", "\n\n $server \n\n", FILE_APPEND);
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

$sm = new StraboMicro($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$sm->setuuid($uuid);
*/


?>