<?php
/**
 * File: testUpload.php
 * Description: Handles testUpload operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

//phpInfo();exit();
function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

function showImage($image){
	header('Content-Type: image/jpeg');
	imagejpeg($image);
	exit();
}

function hexColorsToInteger($hexval){

	$redHex = substr($hexval, 2,2);
	$greenHex = substr($hexval, 4,2);
	$blueHex = substr($hexval, 6,2);

	$redDec = hexdec($redHex);
	$greenDec = hexdec($greenHex);
	$blueDec = hexdec($blueHex);




	return [$redDec, $greenDec, $blueDec];
}

function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
{
	/* this way it works well only for orthogonal lines
	imagesetthickness($image, $thick);
	return imageline($image, $x1, $y1, $x2, $y2, $color);
	*/
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

//dumpVar(gd_info());
$projectmetdatada_id = $_GET['id'];

//open json for testing
$json = file_get_contents("../straboMicroFiles/$projectmetdatada_id/project.json");
$j = json_decode($json);
//dumpVar($j);

//datasets samples micrographs
//first, gather all micrograhps
//then roll over each micrograph, and create base image
//then look for any micrograph with parent of current and add to base image

$allMicrographs = [];
foreach($j->datasets as $d){
	foreach ($d->samples as $s){
		foreach($s->micrographs as $m){
			$allMicrographs[] = $m;
		}
	}
}

//dumpVar($allMicrographs);exit();

foreach($allMicrographs as $m){
	$maxDimension = 2000;
	$id = $m->id;
	$scale = $m->scalePixelsPerCentimeter;

	$origImage = imagecreatefrompng("../straboMicroFiles/$projectmetdatada_id/uiImages/".$id);
	list($originalWidth, $originalHeight) = getimagesize("../straboMicroFiles/$projectmetdatada_id/uiImages/".$id);



	//dumpVar($origImage);exit();

	if($originalWidth > $originalHeight){
		$newWidth = $maxDimension;
		$newHeight = round($maxDimension * ($originalHeight/$originalWidth));
	}else{
		$newHeight = $maxDimension;
		$newWidth = round($maxDimension * ($originalWidth/$originalHeight));
	}

	$baseRatio = $newHeight / $originalHeight;

	$newImage = imagecreatetruecolor($newWidth, $newHeight);

	imagecopyresampled($newImage, $origImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);


	$spots = $m->spots;

	foreach($allMicrographs as $m){
		if($m->parentID == $id){
			//OK, add this image
			$subId = $m->id;
			$subScale = $m->scalePixelsPerCentimeter;
			$subImage = imagecreatefrompng("../straboMicroFiles/$projectmetdatada_id/uiImages/".$subId);
			$subOrigWidth = imagesx($subImage);
			$subOrigHeight = imagesy($subImage);
			$subOffsetX = $m->offsetInParent->X * $baseRatio;
			$subOffsetY = $m->offsetInParent->Y * $baseRatio;

			//first scale, then rotate
			$subWidth = floor($scale/$subScale * $baseRatio * $subOrigWidth); //??????? need to incorporate original scale factor delta
			$subHeight = floor($scale/$subScale * $baseRatio * $subOrigHeight);


			$rotation = $m->rotation * -1;

			$subImage = imagescale($subImage, $subWidth);

			$rotatedImage = imagecreatetruecolor($subWidth, $subHeight);
			// Preserve transparency
			imagesavealpha($rotatedImage , true);
			$pngTransparency = imagecolorallocatealpha($rotatedImage , 0, 0, 0, 127);
			imagefill($rotatedImage , 0, 0, $pngTransparency);

			imagecopyresampled($rotatedImage, $subImage, 0, 0, 0, 0, $subWidth, $subHeight, $subWidth, $subHeight);

			// Rotate the canvas including the required transparent "color"
			$rotatedImage = imagerotate($rotatedImage, $rotation, $pngTransparency);

			$rotatedWidth = imagesx($rotatedImage);
			$rotatedHeight = imagesy($rotatedImage);



			$deltaWidth = ($rotatedWidth - $subWidth) / 2;
			$deltaHeight = ($rotatedHeight - $subHeight) / 2;


			imagecopyresampled($newImage, $rotatedImage, $subOffsetX - $deltaWidth, $subOffsetY - $deltaHeight, 0, 0, $rotatedWidth, $rotatedHeight, $rotatedWidth, $rotatedHeight);







			//showImage($rotatedImage);


		}
	}

	if(count($spots) > 0){

		$shadowColor = imagecolorallocate($newImage, 25, 25, 25);

		$font = $_SERVER['DOCUMENT_ROOT']."/microdb/fonts/VeraBd.ttf";
		$fontSize = 18;
		//imagesetthickness($newImage, 5);

		foreach($spots as $spot){
			$values = [];
			$xTotal = 0;
			$yTotal = 0;
			$numPoints = 0;

			//move colors here
			list($red, $green, $blue) = hexColorsToInteger($spot->labelColor);
			$fontColor = imagecolorallocate($newImage, $red, $green, $blue);

			list($red, $green, $blue) = hexColorsToInteger($spot->color);
			$spotColor = imagecolorallocatealpha($newImage, $red, $green, $blue, 25);

			if($spot->geometryType=="polygon"){
				foreach($spot->points as $point){
					$values[] = floor($point->X * $baseRatio);
					$values[] = floor($point->Y * $baseRatio);
					$xTotal += floor($point->X * $baseRatio);
					$yTotal += floor($point->Y * $baseRatio);
					$numPoints ++;
				}

				imagefilledpolygon($newImage, $values, count($values)/2, $spotColor);

				$centerX = floor($xTotal / $numPoints);
				$centerY = floor($yTotal / $numPoints);

				//$col_ellipse = imagecolorallocate($newImage, 255, 0, 0);
				//imagefilledellipse($newImage, $centerX, $centerY, 20, 20, $col_ellipse);

				//imagestring($newImage, 5, $centerX, $centerY, 'Hello world!', $textColor);
				imagettftext($newImage, $fontSize, 0, $centerX + 10 + 3, $centerY - 10 + 3, $shadowColor, $font, $spot->name);
				imagettftext($newImage, $fontSize, 0, $centerX + 10 , $centerY - 10, $fontColor, $font, $spot->name);


			}elseif($spot->geometryType=="line"){

				foreach($spot->points as $point){
					$xTotal += floor($point->X * $baseRatio);
					$yTotal += floor($point->Y * $baseRatio);
					$numPoints ++;
				}

				$centerX = floor($xTotal / $numPoints);
				$centerY = floor($yTotal / $numPoints);

				$points = $spot->points;
				for($z = 1; $z < count($points); $z++){
					imagelinethick($newImage, floor($points[$z-1]->X * $baseRatio), floor($points[$z-1]->Y * $baseRatio), floor($points[$z]->X * $baseRatio), floor($points[$z]->Y * $baseRatio), $spotColor, 5);
				}

				imagettftext($newImage, $fontSize, 0, $centerX + 10 + 3, $centerY - 10 + 3, $shadowColor, $font, $spot->name);
				imagettftext($newImage, $fontSize, 0, $centerX + 10 , $centerY - 10, $fontColor, $font, $spot->name);

			}elseif($spot->geometryType=="point"){
				$centerX = floor($spot->points[0]->X * $baseRatio);
				$centerY = floor($spot->points[0]->Y * $baseRatio);

				imagefilledellipse($newImage, $centerX, $centerY, 20, 20, $spotColor);

				imagettftext($newImage, $fontSize, 0, $centerX + 10 + 3, $centerY - 10 + 3, $shadowColor, $font, $spot->name);
				imagettftext($newImage, $fontSize, 0, $centerX + 10 , $centerY - 10, $fontColor, $font, $spot->name);
			}
		}
	}










	showImage($newImage);

}

























exit();

$out = print_r($_FILES, true);
$server = print_r($_SERVER, true);

//dumpVar($out);
//dumpVar($_SERVER);


//sleep(1);

if(file_exists("uploadLog.txt")){
	file_put_contents ("uploadLog.txt", $out, FILE_APPEND);
	file_put_contents ("uploadLog.txt", "\n\n\n\n", FILE_APPEND);
	file_put_contents ("uploadLog.txt", $server, FILE_APPEND);
	file_put_contents ("uploadLog.txt", "\n\n**********\n\n", FILE_APPEND);
}else{
	echo "no exist";
}




echo '{"status":"success", "message":"Project uploaded successfully"}';


//echo '{"status":"failure", "message":"Project already exists in database."}';




/*

Array
(
	[upfile] => Array
		(
			[name] => pleaseFeedMe.jpg.zip
			[type] => application/octet-stream
			[tmp_name] => /tmp/phpSvGHq1
			[error] => 0
			[size] => 3136274
		)

)




Array
(
	[SCRIPT_URL] => /microdb/testUpload.php
	[SCRIPT_URI] => http://strabospot.org/microdb/testUpload.php
	[HTTP_AUTHORIZATION] => Basic amFzb25hc2hAa3UuZWR1OnN0cmFib3Rlc3Q=
	[HTTP_HOST] => strabospot.org
	[CONTENT_TYPE] => multipart/form-data; boundary=zxiHw9VQdXPYkKrvzMHRl7lw77a-aPD7HUT6IYA
	[HTTP_USER_AGENT] => Apache-HttpClient/4.5.13 (Java/13.0.2)
	[HTTP_ACCEPT_ENCODING] => gzip,deflate
	[HTTP_X_FORWARDED_FOR] => 98.187.39.206
	[HTTP_X_FORWARDED_HOST] => strabospot.org
	[HTTP_X_FORWARDED_SERVER] => jetstream.strabospot.org
	[CONTENT_LENGTH] => 3136542
	[HTTP_CONNECTION] => Keep-Alive
	[PATH] => /usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
	[SERVER_SIGNATURE] => <address>Apache/2.4.38 (Debian) Server at strabospot.org Port 80</address>

	[SERVER_SOFTWARE] => Apache/2.4.38 (Debian)
	[SERVER_NAME] => strabospot.org
	[SERVER_ADDR] => 192.168.144.3
	[SERVER_PORT] => 80
	[REMOTE_ADDR] => 192.168.144.1
	[DOCUMENT_ROOT] => /srv/app/www
	[REQUEST_SCHEME] => http
	[CONTEXT_PREFIX] =>
	[CONTEXT_DOCUMENT_ROOT] => /srv/app/www
	[SERVER_ADMIN] => [no address given]
	[SCRIPT_FILENAME] => /srv/app/www/microdb/testUpload.php
	[REMOTE_PORT] => 54418
	[REMOTE_USER] => jasonash@ku.edu
	[AUTH_TYPE] => Basic
	[GATEWAY_INTERFACE] => CGI/1.1
	[SERVER_PROTOCOL] => HTTP/1.1
	[REQUEST_METHOD] => POST
	[QUERY_STRING] =>
	[REQUEST_URI] => /microdb/testUpload.php
	[SCRIPT_NAME] => /microdb/testUpload.php
	[PHP_SELF] => /microdb/testUpload.php
	[PHP_AUTH_USER] => jasonash@ku.edu
	[PHP_AUTH_PW] => strabotest
	[REQUEST_TIME_FLOAT] => 1636994700.957
	[REQUEST_TIME] => 1636994700
)
*/

?>