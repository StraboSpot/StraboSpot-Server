<?php
/**
 * File: doisearchdownload.php
 * Description: Handles doisearchdownload operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

ini_set('max_execution_time', 300);
include("../prepare_connections.php");

include("doiOutputClass.php");

$doiOut = new doiOutputClass($strabo,$_GET);

$type=$_GET['type'];
$uuid=$_GET['u'];

$doiOut->setGlobalUUID($uuid);


function fixFileName($filename){
	$filename = str_replace(" ", "_", $filename);
	$filename = preg_replace('/[^A-Za-z0-9\-_]/', '', $filename);
	return $filename;
}

if($type=="shapefile"){
	$doiOut->doiShapefileOut();
}elseif($type=="fieldapp"){

	//Get filename from uuid folder first
	$filename = file_get_contents("doiFiles/$uuid/zipfilename.txt");

	if($filename == ""){
		//Get project name and read raw file
		$json = file_get_contents("doiFiles/$uuid/data.json");
		$json = json_decode($json);
		$projectname = $json->projectDb->project->description->project_name;
		$projectname = $doiOut->fixFileName($projectname);
		$filename = date("Y-m-d_gia") . "_" . $projectname . ".zip";
	}else{
		$filename = $filename.".zip";
	}

	$rawfile = "doiFiles/$uuid/field_app_project.zip";

	header("Content-Type: application/zip");
	header("Content-Disposition: attachment; filename=$filename");
	header("Content-Length: " . filesize($rawfile));

	readfile($rawfile);

}elseif($type=="microzip"){

	//Get filename from uuid folder first
	$json = file_get_contents("doiFiles/$uuid/project.json");
	$json = json_decode($json);
	$projectName = $json->name;

	if($projectName == "") $projectName = date("Y-m-d_gia") . "_Strabo Micro Project";

	$filename = fixFileName($projectName).".zip";

	$rawfile = "doiFiles/$uuid/project.zip";

	header("Content-Type: application/zip");
	header("Content-Disposition: attachment; filename=$filename");
	header("Content-Length: " . filesize($rawfile));

	readfile($rawfile);

}elseif($type=="expjson"){

	//Get filename from uuid folder first
	$json = file_get_contents("doiFiles/$uuid/project.json");
	$json = json_decode($json);

	$projectName = $json->name;

	if($projectName == "") $projectName = date("Y-m-d_gia") . "_Strabo Micro Project";

	$filename = fixFileName($projectName).".json";

	$rawfile = "doiFiles/$uuid/project.json";

	header("Content-Type: application/json");
	header("Content-Disposition: attachment; filename=$filename");
	header("Content-Length: " . filesize($rawfile));

	readfile($rawfile);

}elseif($type=="kml"){
	$doiOut->doiKMLOut();
}elseif($type=="xls"){
	$doiOut->doiXLSOut();
}elseif($type=="stereonet"){
	$doiOut->doiStereonetOut();
}elseif($type=="pdf"){

	//Get filename from uuid folder first
	$filename = file_get_contents("doiFiles/$uuid/zipfilename.txt");

	if($filename == ""){
		//Get project name and read raw file
		$json = file_get_contents("doiFiles/$uuid/data.json");
		$json = json_decode($json);
		$projectname = $json->projectDb->project->description->project_name;
		$projectname = $doiOut->fixFileName($projectname);
		$filename = date("Y-m-d_gia") . "_" . $projectname . ".pdf";
	}else{
		$filename = $filename.".pdf";
	}

	//echo $filename;

	header("Content-type: application/pdf");
	header("Content-Disposition: inline; filename=$filename");
	@readfile("/srv/app/www/doi/doiFiles/$uuid/project.pdf");

}elseif($type=="micropdf"){

	//Get filename from uuid folder first
	$json = file_get_contents("doiFiles/$uuid/project.json");
	$json = json_decode($json);
	$projectName = $json->name;

	if($projectName == "") $projectName = date("Y-m-d_gia") . "_Strabo Micro Project";

	$filename = fixFileName($projectName).".pdf";

	header("Content-type: application/pdf");
	header("Content-Disposition: inline; filename=$filename");
	@readfile("/srv/app/www/doi/doiFiles/$uuid/project.pdf");


}elseif($type=="fieldbookdev"){
	$doiOut->fieldbookOutdev();
}elseif($type=="shapefiledev"){
	$doiOut->devshapefileOut();
}elseif($type=="stratsection"){
	header("Location: strat_sections.php?u=$uuid");
}elseif($type=="stereonetdev"){
	$doiOut->devstereonetOut();
}elseif($type=="xlsdev"){
	$doiOut->devxlsOut();
}elseif($type=="download_images"){
	$doiOut->downloadImages();
}elseif($type=="debug"){
	$doiOut->debugOut();
}elseif($type=="devkml"){
	$doiOut->devkmlOut();
}elseif($type=="sample_list"){
	$doiOut->xlsSampleList();
}elseif($type=="expandedShapefile"){
	$doiOut->expandedShapefileOut();
}elseif($type=="devexpandedShapefile"){
	$doiOut->devexpandedShapefileOut();
}elseif($type=="kml_dev"){
	$doiOut->devkmlOut();
}elseif($type=="doi"){
	$doiOut->doiPDFOut();
}elseif($type=="doidata"){
	$doiOut->doiDataOut();
}










?>