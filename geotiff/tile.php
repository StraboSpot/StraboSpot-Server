<?php
/**
 * File: tile.php
 * Description: Handles tile operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


$hash=$_GET['hash'];
$x=$_GET['x'];
$y=$_GET['y'];
$z=$_GET['z'];

if(file_exists("/srv/app/www/geotiff/upload/files/$hash.tif") && file_exists("/srv/app/www/geotiff/upload/maps/$hash.map")){

	$img = file_get_contents("https://strabospot.org/cgi-bin/mapserv?map=/var/www/geotiff/upload/maps/".$hash.".map&layer=geotifflayer&mode=tile&tile=".$x."+".$y."+".$z);
	header("Content-Type: image/png");
	echo $img;

}else{

	header("HTTP/1.0 404 Not Found");

	header("Content-Type: image/png");
	$im = @imagecreate(256, 256)
		or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);
	$text_color = imagecolorallocate($im, 0, 0, 0);
	imagestring($im, 5, 110, 90,  "Error!", $text_color);
	imagestring($im, 2, 60, 110,  "GeoTIFF $hash", $text_color);
	imagestring($im, 5, 70, 130,  "does not exist.", $text_color);
	imagepng($im);
	imagedestroy($im);

}

exit();

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

dumpVar($_GET);

?>