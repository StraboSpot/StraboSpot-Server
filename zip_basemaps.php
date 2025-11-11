<?php
/**
 * File: zip_basemaps.php
 * Description: Creates ZIP archives of basemap files for download
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");
include_once('includes/straboClasses/basemapClass.php');

$credentials = $_SESSION['credentials'];

$dataset_id=$_GET['dataset_id'];

$spots = $strabo->getDatasetSpots($dataset_id);

$image_basemaps = [];

foreach($spots['features'] as $spot){
	if($spot['properties']['image_basemap']!=""){
		if(!in_array($spot['properties']['image_basemap'],$image_basemaps)){
			$image_basemaps[] = $spot['properties']['image_basemap'];
		}
	}
}

$zipfolder = $strabo->generateRandomString();

exec("mkdir /srv/app/www/ziptemp/$zipfolder");
exec("mkdir /srv/app/www/ziptemp/$zipfolder/$zipfolder");

foreach($image_basemaps as $ib){
	$base = new straboBasemapClass($strabo, $ib);
	$base->saveImage($_SERVER['DOCUMENT_ROOT']."/ziptemp/$zipfolder/$zipfolder/$ib.jpg");
}

$zipfile = $_SERVER['DOCUMENT_ROOT']."/ziptemp/$zipfolder/$zipfolder.zip";
$zipfolder = $_SERVER['DOCUMENT_ROOT']."/ziptemp/$zipfolder/$zipfolder";

exec("/usr/bin/zip -rj $zipfile $zipfolder");

$file_name = basename($zipfile);

header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=$file_name");
header("Content-Length: " . filesize($zipfile));

readfile($zipfile);

?>