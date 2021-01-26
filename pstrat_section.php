<?php

//include("logincheck.php");

$userpkey=99999;

//Initialize Databases
include_once "includes/config.inc.php"; //credentials, etc
include "db.php"; //postgres database abstraction layer
include "neodb.php"; //neo4j database abstraction layer
include "db/strabospotclass.php"; //strabospot specific functions
include_once('includes/geophp/geoPHP.inc'); //geospatial functions
include_once('includes/UUID.php'); //UUID Class

//Initialize StraboSpot class
$strabo = new StraboSpot($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$strabo->setuuid($uuid);
include_once("includes/straboSVG.php");

$strat_spot_id = $_GET['id'];
$dataset_id = $_GET['did'];

if($strat_spot_id == "" || $dataset_id == ""){
	exit("no parameters given");
}

if (strpos($dataset_id, '-') !== false) {
	$parts = explode("-", $dataset_id);
	$dataset_id = $parts[1];
}

$parent_spot = $strabo->getPublicSingleSpot($strat_spot_id);

//$db->dumpVar($parent_spot); exit();



$strat_section_id = $parent_spot->properties->sed->strat_section->strat_section_id;
$strat_section = $parent_spot->properties->sed->strat_section;

$spots = $strabo->getPublicDatasetSpots($dataset_id);


//$db->dumpVar($spots); exit();

$spots = $spots['features'];

$allspots = [];
foreach($spots as $spot){
	if($spot['properties']['strat_section_id']==$strat_section_id){
		array_push($allspots,$spot);
	}
}

//$strabo->dumpVar($allspots);exit();

$svg = new straboSVG($allspots,$strat_section);

$svg->outToBrowser();



?>