<?php
/**
 * File: mymapscheck.php
 * Description: Map data validation and verification utility
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("prepare_connections.php");

$hash = isset($_GET['hash']) ? preg_replace('/[^a-zA-Z0-9]/', '', $_GET['hash']) : '';

$count = $db->get_var_prepared("SELECT count(*) FROM geotiffs WHERE hash = $1", array($hash));

if($count > 0){
	header("Good Request", true, 200);
}else{
	header("Not Found", true, 404);
}

?>