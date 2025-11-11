<?php
/**
 * File: instrumentdetail.php
 * Description: Handles instrumentdetail operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("prepare_connections.php");

$pkey = $_GET['pkey'];

if($pkey == "" || !is_numeric($pkey)){
	exit();
}

$row = $db->get_row("select * from instrument where pkey = $pkey");

$instrument = array();
$instrument['instrumentname'] = $row->instrumentname;
$instrument['instrumenttype'] = $row->instrumenttype;
$instrument['instrumentbrand'] = $row->instrumentbrand;
$instrument['instrumentmodel'] = $row->instrumentmodel;
$instrument['university'] = $row->university;
$instrument['laboratory'] = $row->laboratory;
$instrument['datacollectionsoftware'] = $row->datacollectionsoftware;
$instrument['datacollectionsoftwareversion'] = $row->datacollectionsoftwareversion;
$instrument['postprocessingsoftware'] = $row->postprocessingsoftware;
$instrument['postprocessingsoftwareversion'] = $row->postprocessingsoftwareversion;
$instrument['filamenttype'] = $row->filamenttype;

$detectors = array();
$detectorrows = $db->get_results("select * from instrument_detector where instrument_pkey = $pkey");

foreach($detectorrows as $drow){
	$thisdetector = array();
	$thisdetector['type'] = $drow->type;
	$thisdetector['make'] = $drow->make;
	$thisdetector['model'] = $drow->model;
	$detectors[] = $thisdetector;
}

$instrument['detectors'] = $detectors;

$instrument['instrumentnotes'] = $row->instrumentnotes;

header('Content-Type: application/json');
echo json_encode($instrument, JSON_PRETTY_PRINT);

?>