<?php
/**
 * File: searchdownload.php
 * Description: Handles searchdownload operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

ini_set('max_execution_time', 1800);
include("logincheck.php");
include("prepare_connections.php");

include("includes/straboClasses/straboOutputClass.php");

$straboOut = new straboOutputClass($strabo,$_GET);

$type=$_GET['type'];
$dsids=$_GET['dsids'];

if($type=="shapefile"){
	$straboOut->shapefileOut();
}elseif($type=="kml"){
	$straboOut->kmlOut();
}elseif($type=="xls"){
	$straboOut->xlsOut();
}elseif($type=="stereonet"){
	$straboOut->stereonetOut();
}elseif($type=="fieldbook"){
	$straboOut->fieldbookOut();
}elseif($type=="fieldbookdev"){
	$straboOut->devfieldbookOut();
}elseif($type=="shapefiledev"){
	$straboOut->devshapefileOut();
}elseif($type=="stratsection"){
	header("Location: pdataset_strat_sections.php?dataset_ids=$dsids");
}elseif($type=="stereonetdev"){
	$straboOut->devstereonetOut();
}elseif($type=="xlsdev"){
	$straboOut->devxlsOut();
}elseif($type=="download_images"){
	$straboOut->downloadImages();
}elseif($type=="download_imagesdev"){
	$straboOut->downloadImagesdev();
}elseif($type=="debug"){
	$straboOut->debugOut();
}elseif($type=="devkml"){
	$straboOut->devkmlOut();
}elseif($type=="sample_list"){
	$straboOut->xlsSampleList();
}elseif($type=="dev_sample_list"){
	$straboOut->devxlsSampleList();
}elseif($type=="expandedShapefile"){
	$straboOut->expandedShapefileOut();
}elseif($type=="devexpandedShapefile"){
	$straboOut->devexpandedShapefileOut();
}elseif($type=="kml_dev"){
	$straboOut->devkmlOut();
}elseif($type=="doi"){
	$straboOut->doiPDFOut();
}elseif($type=="doidata"){
	$straboOut->doiDataOut();
}elseif($type=="geojson"){
	$straboOut->geoJSONOut();
}elseif($type=="devgeojson"){
	$straboOut->devgeoJSONOut();
}elseif($type=="hollytest"){
	$straboOut->hollyTest();
}

?>