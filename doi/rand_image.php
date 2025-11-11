<?php
/**
 * File: rand_image.php
 * Description: Handles rand image operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


$txt = $_GET['n'];

if($txt == "foo") $txt = "";

$img = imagecreate(120, 42);
$fontFile = realpath("../includes/arial.ttf");//replace with your font
$fontSize = 24;
$fontColor = imagecolorallocate($img, 255, 255, 255);
$black = imagecolorallocate($img, 60, 60, 60);
$angle = 0;

$iWidth = imagesx($img);
$iHeight = imagesy($img);

$tSize = imagettfbbox($fontSize, $angle, $fontFile, $txt);
$tWidth = max([$tSize[2], $tSize[4]]) - min([$tSize[0], $tSize[6]]);
$tHeight = max([$tSize[5], $tSize[7]]) - min([$tSize[1], $tSize[3]]);

// text is placed in center you can change it by changing $centerX, $centerY values
$centerX = CEIL(($iWidth - $tWidth) / 2);
$centerX = $centerX<0 ? 0 : $centerX;
$centerY = CEIL(($iHeight - $tHeight) / 2);
$centerY = $centerY<0 ? 0 : $centerY;

imagefill($img, 0, 0, $black);

imagettftext($img, $fontSize, $angle, $centerX, $centerY, $fontColor, $fontFile, $txt);
header('Content-type: image/jpeg');
imagejpeg($img);//save image
imagedestroy($img);



?>