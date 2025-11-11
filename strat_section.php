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


include("logincheck.php");
include("prepare_connections.php");
include_once("includes/straboSVG.php");

ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

$strat_spot_id = $_GET['id'];
$dataset_id = $_GET['did'];

if($strat_spot_id == "" || $dataset_id == ""){
	exit("no parameters given");
}

$parent_spot = $strabo->getSingleSpot($strat_spot_id);

$strat_section_id = $parent_spot->properties->sed->strat_section->strat_section_id;
$strat_section = $parent_spot->properties->sed->strat_section;

$spots = $strabo->getDatasetSpots($dataset_id);
$spots = $spots['features'];

$allspots = [];
foreach($spots as $spot){
	if($spot['properties']['strat_section_id']==$strat_section_id){
		array_push($allspots,$spot);
	}
}

$svg = new straboSVG($allspots,$strat_section);

$svg->outToBrowser();

?>