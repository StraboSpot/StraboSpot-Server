<?php
/**
 * File: strat_section_csv.php
 * Description: Exports stratigraphic section data to CSV format
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

$file = $parent_spot->properties->name;
$file = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $file);
$file = mb_ereg_replace("([\.]{2,})", '', $file);
$file = str_replace(" ", "_", $file);
$file = $file . ".csv";

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

$csvSpots = [];
foreach($allspots as $spot){

	if($spot['properties']['sed'] != ""){
		$newSpot = new stdClass();

		$newSpot->id = (string) $spot['properties']['id'];
		$newSpot->unitName = $spot['properties']['name'];

		//figure out top and bottom
		if(count($spot['geometry']->coordinates[0]) == 1){
			$min = $spot['geometry']->coordinates[1];
			$max = $spot['geometry']->coordinates[1];
		}else{
			$points = $spot['geometry']->coordinates[0];
			$max = -999999999;
			$min = 999999999;
			foreach($points as $point){
				if($point[1] < $min) $min = $point[1];
				if($point[1] > $max) $max = $point[1];
			}
		}

		$newSpot->unitBase = $min / 20;
		$newSpot->unitTop = $max / 20;

		$newSpot->unitThickness = $spot['properties']['sed']->interval->interval_thickness . " " . $spot['properties']['sed']->interval->thickness_units;

		$newSpot->primaryLithology = $spot['properties']['sed']->lithologies[0]->primary_lithology;

		$csvSpots[] = $newSpot;
	}

}

	//fix all of this to send values manually
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'.$file.'"');

echo '"unit_id","unit_name","unit_base","unit_top","unit_thickness","primary_lithology"'."\n";
foreach($csvSpots as $s){
	echo "\"$s->id\",";
	echo "\"$s->unitName\",";
	echo "$s->unitBase,";
	echo "$s->unitTop,";
	echo "\"$s->unitThickness\",";
	echo "\"$s->primaryLithology\"";
	echo "\n";
}

?>