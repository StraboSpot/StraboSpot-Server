<?php
/**
 * File: installlocationsjson.php
 * Description: Handles installlocationsjson operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("../prepare_connections.php");
$rows = $db->get_results("SELECT longitude, latitude FROM public.micro_installs where longitude is not null and latitude is not null group by longitude, latitude order by longitude, latitude;");


$out = new stdClass();
$out->type = "FeatureCollection";

$featuresArray = [];

foreach($rows as $row){


	$thisFeature = new stdClass();
	$thisFeature->type = "Feature";
	$thisGeometry = new stdClass();
	$thisGeometry->type = "Point";
	$thisGeometry->coordinates = array((float)$row->longitude, (float)$row->latitude);
	$thisFeature->geometry = $thisGeometry;
	$featuresArray[] = $thisFeature;
}


$out->features = $featuresArray;

$out = json_encode($out, JSON_PRETTY_PRINT);

header('Content-Type: application/json; charset=utf-8');

echo $out;


/*

{
	"type": "FeatureCollection",
	"features": [
		{
			"type": "Feature",
			"geometry": {
				"type": "Point",
				"coordinates": [
					-119.0082604625,
					37.594527
				]
			},






SELECT longitude, latitude FROM public.micro_installs group by longitude, latitude;

*/

?>