<?php

/*
******************************************************************
StraboSpot Public Image
Author: Jason Ash (jasonash@ku.edu)
Description: This script allows the public retrieval of images
				from the StraboSpot database
******************************************************************
*/



//Initialize Databases
include_once "includes/config.inc.php";
include "db.php";
include "neodb.php";


$id=$_GET['id'];

if(!is_numeric($id)){
	echo "invalid image id ($id)";
	exit();
}

function showNotFound(){
	header ('Content-Type: image/png');
	$output = file_get_contents("/var/www/includes/images/image-not-found.png");
	echo $output;
	exit();
}

function oldshowFile($filename){
	header ('Content-Type: image/jpg');
	$output = file_get_contents("/var/www/dbimages/$filename");
	echo $output;
	exit();
}

function showFile($filename){
	//header ('Content-Type: image/jpg');
	//$output = file_get_contents("/var/www/dbimages/$filename");
	//echo $output;
	header('Location: /dbimages/'.$filename);
	exit();
}

if($id==""){
	showNotFound();
}

$rows = $neodb->get_results("match (i:Image) where i.id=$id and i.filename is not null return i.filename");

foreach($rows as $row){ //roll over to look for duplicates

	$lookname = $row->get("i.filename");
	if(file_exists("dbimages/$lookname")){
		$filename = $lookname;
	}

}



if($filename==""){
	showNotFound();
}else{
	showFile($filename);
}

?>