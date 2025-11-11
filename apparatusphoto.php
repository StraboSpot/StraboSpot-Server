<?php
/**
 * File: apparatusphoto.php
 * Description: Retrieves and displays apparatus photographs
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

$pkey = $_GET['pkey'];
$size = $_GET['size'];

if($size == "large"){
	$pixelsize = 500;
}else{
	$pixelsize = 100;
}

$documentroot = $_SERVER['DOCUMENT_ROOT'];
$path = $documentroot."/expimages/photo_".$pkey;

$resizeimage = "no";

if(file_exists($path)){

	$fi = new finfo(FILEINFO_MIME);
	$fileinfo = $fi->file($path);

	$arr = explode(";", $fileinfo);

	$firstpart = $arr[0];

	if($firstpart == "image/png"){
		$old_image = imagecreatefrompng($path);
		list($old_width, $old_height) = getimagesize($path);
	}elseif($firstpart == "image/jpeg"){
		$old_image = imagecreatefromjpeg($path);
		list($old_width, $old_height) = getimagesize($path);
	}else{
		$old_image = imagecreatefrompng($documentroot."/expimages/nophoto.png");
		list($old_width, $old_height) = getimagesize($documentroot."/expimages/nophoto.png");
	}

}else{
	$old_image = imagecreatefrompng($documentroot."/expimages/nophoto.png");
	list($old_width, $old_height) = getimagesize($documentroot."/expimages/nophoto.png");
}

$image_ratio = $old_width / $old_height;

$new_width = $pixelsize;
$new_height = floor($new_width / $image_ratio);

$new_image = imagecreatetruecolor($new_width, $new_height);

imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);

header('Content-type: image/jpeg');
imagejpeg($new_image);
imagedestroy($old_image);
imagedestroy($new_image);

?>