<?php
/**
 * File: strat_section.php
 * Description: Stratigraphic section display and management
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

$uuid = $_GET['u'] ?? '';
$uuid = preg_replace('/[^a-zA-Z0-9\-]/', '', $uuid);
if($uuid == "") die("No UUID provided.");

$strat_spot_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($strat_spot_id == 0) die("No strat id provided.");

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

//Gather data from DOI file:
$json = file_get_contents("doiFiles/".$uuid."/data.json");
if($json == "") die("DOI file not found.");

include_once("doiSVG.php");

$json = json_decode($json, true);
$spots = $json['spotsDb'];


//$parent_spot = $strabo->getPublicSingleSpot($strat_spot_id);

//Get parent spot from strat_spot_id
foreach($spots as $key=>$spot){
	if($spot['properties']['id'] == $strat_spot_id){
		$strat_section_id = $spot['properties']['sed']['strat_section']['strat_section_id'];
		$strat_section = $spot['properties']['sed']['strat_section'];
	}
}

if($strat_section_id == "")die("Strat Section not found.");


$allspots = [];
foreach($spots as $key=>$spot){
	if($spot['properties']['strat_section_id']==$strat_section_id){
		array_push($allspots,$spot);
	}
}

//dumpVar($allspots);exit();

$svg = new straboSVG($allspots,$strat_section);

$svg->outToBrowser();



?>