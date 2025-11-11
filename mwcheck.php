<?php
/**
 * File: mwcheck.php
 * Description: Map data validation and verification utility
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


//http://mapwarper.net/maps/1608333333.kml

//mwcheck.php

$id = $_GET['id'];

$content = file_get_contents("http://mapwarper.net/maps/$id.kml");

if(strlen($content)>10){
	header("Good Request", true, 200);
}else{
	header("Not Found", true, 404);
}

?>