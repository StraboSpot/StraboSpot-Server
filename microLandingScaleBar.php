<?php
/**
 * File: microLandingScaleBar.php
 * Description: pkey and id
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

//pkey and id
$pkey = $_GET['pkey'];
$id = $_GET['id'];
$webWidth = 750;

if($pkey == "" || $id == ""){
	echo "Not enough info provided.";exit();
}

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

function imageLineThick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
{
	
	if ($thick == 1) {
		return imageline($image, $x1, $y1, $x2, $y2, $color);
	}
	$t = $thick / 2 - 0.5;
	if ($x1 == $x2 || $y1 == $y2) {
		return imagefilledrectangle($image, round(min($x1, $x2) - $t), round(min($y1, $y2) - $t), round(max($x1, $x2) + $t), round(max($y1, $y2) + $t), $color);
	}
	$k = ($y2 - $y1) / ($x2 - $x1); //y = kx + q
	$a = $t / sqrt(1 + pow($k, 2));
	$points = array(
		round($x1 - (1+$k)*$a), round($y1 + (1-$k)*$a),
		round($x1 - (1-$k)*$a), round($y1 - (1+$k)*$a),
		round($x2 + (1+$k)*$a), round($y2 - (1-$k)*$a),
		round($x2 + (1-$k)*$a), round($y2 + (1+$k)*$a),
	);
	imagefilledpolygon($image, $points, 4, $color);
	return imagepolygon($image, $points, 4, $color);
}

//get scale and orig width from data scalePixelsPerCentimeter

$json = file_get_contents($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/$pkey/project.json");
$json = utf8_encode($json);

if($json == ""){
	echo "Project not found";exit();
}
$json = json_decode($json);

foreach($json->datasets as $d){
	foreach($d->samples as $s){
		foreach($s->micrographs as $m){
			if($m->id == $id){
				$scalePixelsPerCentimeter = $m->scalePixelsPerCentimeter;
				$origWidth = $m->width;
			}
		}
	}
}

//calculate how much 250 pix is
$ratio = $origWidth / $webWidth;

//figure out how many pixels 250 would be in large image
$largePixels = 250 * $ratio;

//figure out how many cm in largepixels
$scaleCm = $largePixels / $scalePixelsPerCentimeter;

$scaleNum = $scaleCm * 10000;
$unit = "µm";

if($scaleNum > 1000){
	$scaleNum = $scaleNum / 1000;
	$unit = "mm";

	if($scaleNum > 10){
		$scaleNum = $scaleNum / 10;
		$unit = "cm";
	}
}

//if num < 10, round to next lowest integer, else round to next lowest 10
if($scaleNum < 10){
	//round to lower int
	$showNum = floor($scaleNum);
	$length = 250 * ($showNum / $scaleNum);
}else{
	//round to lower 10
	$showNum = floor($scaleNum/10)*10;
	$length = 250 * ($showNum / $scaleNum);
}

$newImage = imagecreatetruecolor(275, 40);
$backgroundColor = imagecolorallocate($newImage, 255, 255, 255);
$fontColor = imagecolorallocate($newImage, 25, 25, 25);

$font = $_SERVER['DOCUMENT_ROOT']."/microdb/fonts/VeraBd.ttf";
$fontSize = 12; //18

imagefill($newImage, 0, 0, $backgroundColor);

imagettftext($newImage, $fontSize, 0, 10, 18, $fontColor, $font, "$showNum $unit");

$thick = 2;
imageLineThick($newImage, 5, 25, $length + 5, 25, $fontColor, $thick);
imageLineThick($newImage, 5, 20, 5, 30, $fontColor, $thick);
imageLineThick($newImage, $length + 5, 20, $length + 5, 30, $fontColor, $thick);

header("Content-type: image/jpeg");
imagejpeg($newImage);

?>