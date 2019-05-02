<?php

include("logincheck.php");
include("prepare_connections.php");
include_once("includes/straboSVG.php");

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