<?php
/**
 * File: publicimage.php
 * Description: Handles publicimage operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
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
	header("HTTP/1.0 404 Not Found");
	header ('Content-Type: image/png');
	$output = file_get_contents("/srv/app/www/includes/images/image-not-found.png");
	echo $output;
	exit();
}

function oldshowFile($filename){
	header ('Content-Type: image/jpg');
	$output = file_get_contents("/srv/app/www/dbimages/$filename");
	echo $output;
	exit();
}

function showFile($filename){
	header('Location: /dbimages/'.$filename);
	exit();
}

if($id==""){
	showNotFound();
}

$safe_id = addslashes($id);
$rows = $neodb->get_results("match (i:Image) where i.id=$safe_id and i.filename is not null return i.filename");

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