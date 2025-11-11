<?php
/**
 * File: dl_field_zip.php
 * Description: Handles dl field zip operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

ini_set('max_execution_time', 300);
include("prepare_connections.php");

include("doi/doiOutputClass.php");

$doiOut = new doiOutputClass($strabo,$_GET);

$uuid=$_GET['u'];

if(!is_dir("fieldZips/$uuid")){
	sleep(2);
	include("includes/header.php");
	echo "Download link has expired";
	include("includes/footer.php");
}

if(!$uuid) Die("No UUID provided.");

$doiOut->setGlobalUUID($uuid);

function fixFileName($filename){
	$filename = str_replace(" ", "_", $filename);
	$filename = preg_replace('/[^A-Za-z0-9\-_]/', '', $filename);
	return $filename;
}

	//Get filename from uuid folder first
	$filename = file_get_contents("fieldZips/$uuid/zipfilename.txt");

	if($filename == ""){
		//Get project name and read raw file
		$json = file_get_contents("fieldZips/$uuid/data.json");
		$json = json_decode($json);
		$projectname = $json->projectDb->project->description->project_name;
		$projectname = $doiOut->fixFileName($projectname);
		$filename = date("Y-m-d_gia") . "_" . $projectname . ".zip";
	}else{
		$filename = $filename.".zip";
	}

	$rawfile = "fieldZips/$uuid/field_app_project.zip";

	header("Content-Type: application/zip");
	header("Content-Disposition: attachment; filename=$filename");
	header("Content-Length: " . filesize($rawfile));

	readfile($rawfile);

?>