<?php
/**
 * File: publicthumbnailimage.php
 * Description: Handles publicthumbnailimage operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("prepare_connections.php");

$id=$_GET['id'];

if(!is_numeric($id)){
	echo "invalid image id ($id)";
	exit();
}

function showNotFound(){
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

$thumbSize = 150;

if(isset($_GET['thumbsize']) && $_GET['thumbsize'] != "") {
	$thumbSize = (int)$_GET['thumbsize'];
}

if($thumbSize > 5000) $thumbSize = 5000;
if($thumbSize < 1) $thumbSize = 150;

function showFile($filename){

	global $db;
	global $thumbSize;

	$old_image = imagecreatefromjpeg($filename);

	$width  = imagesx($old_image);
	$height = imagesy($old_image);

	if($width > $height){
		$newwidth = $thumbSize;
		$newheight = round(($height / $width) * $thumbSize);
	}else{
		$newheight = $thumbSize;
		$newwidth = round(($width / $height) * $thumbSize);
	}

	$new_image = imagecreatetruecolor($newwidth, $newheight);
	imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
	header('Content-type: image/jpeg');
	imagejpeg($new_image);
	imagedestroy($old_image);
	imagedestroy($new_image);

	exit();
}

if($id==""){
	$filename = "includes/images/image-not-found.jpg";
}

$safe_id = addslashes($id);
$rows = $neodb->get_results("match (i:Image) where i.id=$safe_id and i.filename is not null return i.filename");

foreach($rows as $row){ //roll over to look for duplicates

	$lookname = $row->get("i.filename");
	if(file_exists("dbimages/$lookname")){
		$filename = "dbimages/$lookname";
	}

}

if($filename == "") $filename = "/srv/app/www/includes/images/image-not-found.jpg";

showFile($filename);

?>