<?php
/**
 * File: spotjson.php
 * Description: Returns geological spot data in JSON format
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include_once "./includes/config.inc.php";
include("db.php");

$id = $_GET['id'];

$count = $db->get_var("select count(*) from spot");

if($id != ""){
	$json = $db->get_var("select spotjson from spot where strabo_spot_id='$id' limit 1");

	if($json != ""){
		header('Content-Type: application/json');
		echo $json;
	}else{
		header('Content-Type: application/json');
		echo "{\"Error\":\"Spot not found.\"}";
	}
}

?>