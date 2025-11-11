<?php
/**
 * File: basemapClass.php
 * Description: straboBasemapClass class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class straboBasemapClass
{

	 public function straboBasemapClass($strabo, $image_id){

		$this->font = $_SERVER['DOCUMENT_ROOT']."/includes/symbology/avenirlight.ttf";

		$this->strabo = $strabo;
		$this->image_id=$image_id;

		$this->buildImage();

	 }

	 public function buildImage(){

		 $imageinfo = $this->strabo->getImageInfo($this->image_id);

		 $filename = $imageinfo->filename;
		 $reportedwidth = $imageinfo->width;
		 $reportedheight = $imageinfo->height;

		 $this->baseimage = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT']."/dbimages/$filename");
		 $this->basewidth = imagesx($this->baseimage);
		 $this->baseheight = imagesy($this->baseimage);

		 $this->sizeratio = $this->basewidth / $reportedwidth;

		 //Create color for font
		 $this->black = imagecolorallocate($this->baseimage, 0, 0, 0);

		imageinterlace($this->baseimage, true);
		imagealphablending($this->baseimage, true);

		 $features = $this->strabo->getDatasetWithImage($this->image_id);
		 $features = $features['features'];

		 foreach($features as $f){
			 if($f['properties']['image_basemap'] == $this->image_id) $this->drawFeature($f);
		 }

	 }

	 public function drawFeature($f){

		if(strtolower($f['geometry']->type) == "point" || strtolower($f['geometry']->type) == "multipoint"){
			$this->drawPoint($f);
		}
		if(strtolower($f['geometry']->type) == "linestring" || strtolower($f['geometry']->type) == "multilinestring"){
			$this->drawLine($f);
		}
		if(strtolower($f['geometry']->type) == "polygon" || strtolower($f['geometry']->type) == "multipolygon"){
			$this->drawPolygon($f);
		}

	 }

	 public function drawPointLabel($f, $o, $multilabeloffset){

		if($o->strike != ""){
			$rot = $o->strike;
		}elseif($o->dip_direction != ""){
			$rot = ($o->dip_direction - 90) % 360;
		}elseif($o->trend != ""){
			$rot = $o->trend;
		}else{
			$rot = 0;
		}

		if(($rot >= 60 && $rot <= 120) || ($rot >= 240 || $rot <= 300)){
			$offset = array(70,-15);
		}else{
			$offset = array(20,-15);
		}

		if($o == null){
			$label = $f['properties']['name'];
		}else{
			if($o->plunge != ""){
				$label = $o->plunge;
			}elseif($o->dip != ""){
				$label = $o->dip;
			}else{
				$label = $f['properties']['name'];
			}

		}

		//Determine width of label and figure out if it will go off of right side of image. If so, move to left
		$b = imagettfbbox(20, 0, $this->font, $label);
		$labelwidth = abs($b[0] - $b[4]);

		$pointx = $f['geometry']->coordinates[0] + $offset[0] + $multilabeloffset;

		if( (($pointx * $this->sizeratio) + $labelwidth) > $this->basewidth ) $pointx = $f['geometry']->coordinates[0] - $offset[0] - $multilabeloffset - $labelwidth;

		$pointy = $f['geometry']->coordinates[1] + $offset[1];

		imagettftext($this->baseimage, 20, 0, ($pointx * $this->sizeratio) - ($height / 2), $this->baseheight - ($pointy * $this->sizeratio) - ($width / 2), $this->black, $this->font, $label);

	 }

	 public function drawPoint($f){

		 if($f['properties']['orientation_data'] == ""){

			//point imge
			$featureImage = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/includes/symbology/point.png");
			$featureImage = imagescale( $featureImage, 100, 100 );

			$pointx = $f['geometry']->coordinates[0];
			$pointy = $f['geometry']->coordinates[1];

			// Preserve transparency
			imagesavealpha($featureImage , true);
			$pngTransparency = imagecolorallocatealpha($featureImage , 0, 0, 0, 127);
			imagefill($featureImage , 0, 0, $pngTransparency);

			$width  = imagesx($featureImage); $height = imagesy($featureImage); //echo $width . " x " . $height;exit();
			imagecopy($this->baseimage, $featureImage, ($pointx * $this->sizeratio) - ($height / 2), $this->baseheight - ($pointy * $this->sizeratio) - ($width / 2), 0, 0, $width, $height);

			$this->drawPointLabel($f, null, 0);

		 }else{

			$pointx = $f['geometry']->coordinates[0];
			$pointy = $f['geometry']->coordinates[1];

			$multilabeloffset = 0;

			foreach($f['properties']['orientation_data'] as $o){

				//determine which image to pull
				if($o->dip != ""){
					$symbolorientation = $o->dip;
				}elseif($o->plunge != ""){
					$symbolorientation = $o->plunge;
				}else{
					$symbolorientation = 0;
				}

				$featuretype = $o->feature_type;

				if($o->facing == "overturned" && $featuretype == "bedding"){
					$featurefile = $_SERVER['DOCUMENT_ROOT']."/includes/symbology/bedding_overturned.png";
				}elseif($symbolorientation == 0 && ($featuretype == "bedding" || $featuretype == "foliation") ){
					$featurefile = $_SERVER['DOCUMENT_ROOT']."/includes/symbology/".$featuretype."_horizontal.png";
				}elseif($symbolorientation > 0 && $symbolorientation < 90 && ($featuretype == "bedding" || $featuretype == "contact" || $featuretype == "foliation" || $featuretype == "shear_zone")){
					$featurefile = $_SERVER['DOCUMENT_ROOT']."/includes/symbology/".$featuretype."_inclined.png";
				}elseif($symbolorientation == 90 && ($featuretype == "bedding" || $featuretype == "contact" || $featuretype == "foliation" || $featuretype == "shear_zone")){
					$featurefile = $_SERVER['DOCUMENT_ROOT']."/includes/symbology/".$featuretype."_vertical.png";
				}elseif($featuretype == "fault" || $featuretype == "fracture" || $featuretype == "vein"){
					$featurefile = $_SERVER['DOCUMENT_ROOT']."/includes/symbology/".$featuretype.".png";
				}else{
					$featurefile = $_SERVER['DOCUMENT_ROOT']."/includes/symbology/point.png";
				}

				$featureImage = imagecreatefrompng($featurefile);
				$featureImage = imagescale( $featureImage, 100, 100 );

				// Preserve transparency
				imagesavealpha($featureImage , true);
				$pngTransparency = imagecolorallocatealpha($featureImage , 0, 0, 0, 127);
				imagefill($featureImage , 0, 0, $pngTransparency);

				// Rotate the canvas including the required transparent "color"
				
				if($o->strike != ""){
					$rot = $o->strike * -1;
				}elseif($o->dip_direction != ""){
					$rot = ($o->dip_direction - 90) % 360;
				}elseif($o->trend != ""){
					$rot = $o->trend * -1;
				}else{
					$rot = 0;
				}

				$featureImage = imagerotate($featureImage, $rot, $pngTransparency);

				$width  = imagesx($featureImage); $height = imagesy($featureImage); //echo $width . " x " . $height;exit();
				imagecopy($this->baseimage, $featureImage, ($pointx * $this->sizeratio) - ($height / 2), $this->baseheight - ($pointy * $this->sizeratio) - ($width / 2), 0, 0, $width, $height);

				//Now add label
				$this->drawPointLabel($f, $o, $multilabeloffset);
				$multilabeloffset = $multilabeloffset + 70;

			}

		 }

	 }

	 public function drawLine($f){

		$linecolor = imagecolorallocate($this->baseimage, 102, 51, 0);
		$width = 2;
		$pattern = "111";

		if($f['properties']['trace'] != ""){
			$trace = $f['properties']['trace'];
			if($trace->trace_type == "geologic_struc"){
				$linecolor = imagecolorallocate($this->baseimage, 255, 0, 0);
				if($trace->geologic_structure_type=="" || $trace->geologic_structure_type=="" ){
					$width = 4;
				}
			}elseif($trace->trace_type == "contact"){
				$linecolor = imagecolorallocate($this->baseimage, 0, 0, 0);
				if($trace->contact_type == "intrusive" && $trace->intrusive_contact_type == "dike"){
					$width = 4;
				}
			}elseif($trace->trace_type == "geomorphic_fea"){
				$linecolor = imagecolorallocate($this->baseimage, 0, 0, 255);
				$width = 4;
			}elseif($trace->trace_type == "anthropenic_fe"){
				$linecolor = imagecolorallocate($this->baseimage, 128, 0, 128);
				$width = 4;
			}

			$pattern = "1110000000000";
			if($trace->trace_quality == "approximate" || $trace->trace_quality == "approximate(?)" ){
				$pattern = "11111110000000000";
			}elseif($trace->trace_quality == "other"){
				$pattern = "110000000000111111110000000000";
			}
		}

		$coords = $f['geometry']->coordinates;
		for($x = 0; $x < count($coords) - 1; $x++){
			$start = $coords[$x];
			$end = $coords[$x+1];
			$this->imagepatternedline($this->baseimage, $start[0] * $this->sizeratio, $this->baseheight - ($start[1] * $this->sizeratio), $end[0] * $this->sizeratio, $this->baseheight - ($end[1] * $this->sizeratio), $linecolor, $width, $pattern);
		}

		$label = $f['properties']['name'];

		//Draw Label
		$minx = 999999;
		$maxx = -999999;
		$miny = 999999;
		$maxy = -999999;
		foreach($f['geometry']->coordinates as $c){
			if($c[0] < $minx) $minx = $c[0];
			if($c[0] > $maxx) $maxx = $c[0];
			if($c[1] < $miny) $miny = $c[1];
			if($c[1] > $maxy) $maxy = $c[1];
		}

		$xlabelcenter = $minx + (($maxx - $minx) / 2);
		$ylabelcenter = $miny + (($maxy - $miny) / 2);

		$b = imagettfbbox(20, 0, $this->font, $label);
		$widthdiff = abs($b[0] - $b[4]) / 2;
		$heightdiff = abs($b[1] - $b[5]) / 2;

		imagettftext($this->baseimage, 20, 0, ($xlabelcenter * $this->sizeratio) - $widthdiff, $this->baseheight - ($ylabelcenter * $this->sizeratio) + $heightdiff, $this->black, $this->font, $label);

	 }

	 public function drawPolygon($f){
		//figure out color and draw polygon and label

		$label = $f['properties']['name'];

		$linecolor = imagecolorallocatealpha($this->baseimage, 0, 0, 0, 0);

		if($f['properties']['surface_feature']->surface_feature == "rock_unit"){
			$fillcolor = imagecolorallocatealpha($this->baseimage, 0, 255, 255, 85);
			$testfill = "0, 255, 255";
		}elseif($f['properties']['surface_feature']->surface_feature == "contiguous_outcrop"){
			$fillcolor = imagecolorallocatealpha($this->baseimage, 240, 128, 128, 85);
			$testfill = "240, 128, 128";
		}elseif($f['properties']['surface_feature']->surface_feature == "geologic_structure"){
			$fillcolor = imagecolorallocatealpha($this->baseimage, 0, 255, 255, 85);
			$testfill = "0, 255, 255";
		}elseif($f['properties']['surface_feature']->surface_feature == "geomorphic_feature"){
			$fillcolor = imagecolorallocatealpha($this->baseimage, 0, 128, 0, 85);
			$testfill = "0, 128, 0";
		}elseif($f['properties']['surface_feature']->surface_feature == "anthropogenic_feature"){
			$fillcolor = imagecolorallocatealpha($this->baseimage, 128, 0, 128, 85);
			$testfill = "128, 0, 128";
		}elseif($f['properties']['surface_feature']->surface_feature == "extent_of_mapping"){
			$fillcolor = imagecolorallocatealpha($this->baseimage, 128, 0, 128, 85);
			$testfill = "128, 0, 128";
		}elseif($f['properties']['surface_feature']->surface_feature == "extent_of_biological_marker"){
			$fillcolor = imagecolorallocatealpha($this->baseimage, 0, 128, 0, 85);
			$testfill = "0, 128, 0";
		}elseif($f['properties']['surface_feature']->surface_feature == "subjected_to_similar_process"){
			$fillcolor = imagecolorallocatealpha($this->baseimage, 255, 165, 0, 85);
			$testfill = "255, 165, 0";
		}elseif($f['properties']['surface_feature']->surface_feature == "gradients"){
			$fillcolor = imagecolorallocatealpha($this->baseimage, 255, 165, 0, 85);
			$testfill = "255, 165, 0";
		}else{
			$fillcolor = imagecolorallocatealpha($this->baseimage, 0, 0, 255, 85);
			$testfill = "0, 0, 255";
		}

		$polycoords = array();
		//
		for($x = 0; $x < count($f['geometry']->coordinates[0])-1; $x ++){

			$c = $f['geometry']->coordinates[0][$x];

			$polycoords[] = $c[0] * $this->sizeratio;
			$polycoords[] = $this->baseheight - ($c[1] * $this->sizeratio);
		}

		imagefilledpolygon($this->baseimage, $polycoords, count($polycoords)/2, $fillcolor);
		imagepolygon($this->baseimage, $polycoords, count($polycoords)/2, $linecolor);

		//OK, now draw label at center of polygon
		$minx = 999999;
		$maxx = -999999;
		$miny = 999999;
		$maxy = -999999;
		foreach($f['geometry']->coordinates[0] as $c){
			if($c[0] < $minx) $minx = $c[0];
			if($c[0] > $maxx) $maxx = $c[0];
			if($c[1] < $miny) $miny = $c[1];
			if($c[1] > $maxy) $maxy = $c[1];
		}

		$xlabelcenter = $minx + (($maxx - $minx) / 2);
		$ylabelcenter = $miny + (($maxy - $miny) / 2);

		$b = imagettfbbox(20, 0, $this->font, $label);
		$widthdiff = abs($b[0] - $b[4]) / 2;
		$heightdiff = abs($b[1] - $b[5]) / 2;

		imagettftext($this->baseimage, 20, 0, ($xlabelcenter * $this->sizeratio) - $widthdiff, $this->baseheight - ($ylabelcenter * $this->sizeratio) + $heightdiff, $this->black, $this->font, $label);

	 }

	 public function testOverlay(){
		 $featureImage = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/includes/symbology/bedding_inclined.png");

		$pointx = 1646.65;
		$pointy = 1390.99;

		// Preserve transparency
		imagesavealpha($featureImage , true);
		$pngTransparency = imagecolorallocatealpha($featureImage , 0, 0, 0, 127);
		imagefill($featureImage , 0, 0, $pngTransparency);

		// Rotate the canvas including the required transparent "color"
		$featureImage = imagerotate($featureImage, 62+90, $pngTransparency);

		$width  = imagesx($featureImage); $height = imagesy($featureImage); //echo $width . " x " . $height;exit();

		 imagecopy($this->baseimage, $featureImage, ($pointx * $this->sizeratio) - ($height / 2), $this->baseheight - ($pointy * $this->sizeratio) - ($width / 2), 0, 0, $width, $height);
	 }

	public function dumpVar($var){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}

	public function makeThumbnail(){
		$this->baseimage = imagescale($this->baseimage, 250);
	}

	public function out(){

		ImageAntiAlias($this->baseimage, true);
		header('Content-Type: image/jpeg');
		imagejpeg($this->baseimage);
	}

	public function rawOut(){

		ImageAntiAlias($this->baseimage, true);
		imagejpeg($this->baseimage);
	}

	public function saveImage($filename){
		ImageAntiAlias($this->baseimage, true);
		imagejpeg($this->baseimage, $filename);
	}

	// extra parameters:
	//   If there is '1' in the pattern that means the actual dot of line is visible,
	//   All characters regard for one pixel. Default: 1 dot wide dashed line with 4-4 dots and spaces.
	// Examples for pattern:
	// "1" or "" continuous line
	// "10" close dotline
	// "10000" dotline
	// "111111110000001100000011111111" dotline for design drawing
	// "111111111100000011000000110000001111111111" double dotline
	//  "11001100000011001111000011001111110000001100001100"
	// ."00001111001100111100000011000000110000110011001100"
	// ."11000011111100111111000011001111001111000011110000"

	public function imagepatternedline($image, $xstart, $ystart, $xend, $yend, $color, $thickness=1, $pattern="11000011") {

	   $pattern=(!strlen($pattern)) ? "1" : $pattern;

	   $x=$xend-$xstart;

	   $y=$yend-$ystart;

	   $length=floor(sqrt(pow(($x),2)+pow(($y),2)));

	   $fullpattern=$pattern;

	   while (strlen($fullpattern)<$length) $fullpattern.=$pattern;

	   if (!$length) {

		  if ($fullpattern[0]) imagefilledellipse($image, $xstart, $ystart, $thickness, $thickness, $color);

		  return;

	   }

	   $x1=$xstart;

	   $y1=$ystart;

	   $x2=$x1;

	   $y2=$y1;

	   $mx=$x/$length;

	   $my=$y/$length;

	   $line="";

	   for($i=0;$i<$length;$i++){

		  if (strlen($line)==0 or $fullpattern[$i]==$line[0]) {

			 $line.=$fullpattern[$i];

		  }else{

			 $x2+=strlen($line)*$mx;

			 $y2+=strlen($line)*$my;

			 if ($line[0]) imageline($image, round($x1), round($y1), round($x2-$mx), round($y2-$my), $color);

			 $k=1;

			 for($j=0;$j<$thickness-1;$j++) {

				$k1=-(($k-0.5)*$my)*(floor($j*0.5)+1)*2;

				$k2= (($k-0.5)*$mx)*(floor($j*0.5)+1)*2;

				$k=1-$k;

				if ($line[0]) {

				   imageline($image, round($x1)+$k1, round($y1)+$k2, round($x2-$mx)+$k1, round($y2-$my)+$k2, $color);

				   if ($y) imageline($image, round($x1)+$k1+1, round($y1)+$k2, round($x2-$mx)+$k1+1, round($y2-$my)+$k2, $color);

				   if ($x) imageline($image, round($x1)+$k1, round($y1)+$k2+1, round($x2-$mx)+$k1, round($y2-$my)+$k2+1, $color);

				}

			 }

			 $x1=$x2;

			 $y1=$y2;

			 $line=$fullpattern[$i];

		  }

	   }

	   $x2+=strlen($line)*$mx;

	   $y2+=strlen($line)*$my;

	   if ($line[0]) imageline($image, round($x1), round($y1), round($xend), round($yend), $color);

	   $k=1;

	   for($j=0;$j<$thickness-1;$j++) {

		  $k1=-(($k-0.5)*$my)*(floor($j*0.5)+1)*2;

		  $k2= (($k-0.5)*$mx)*(floor($j*0.5)+1)*2;

		  $k=1-$k;

		  if ($line[0]) {

			 imageline($image, round($x1)+$k1, round($y1)+$k2, round($xend)+$k1, round($yend)+$k2, $color);

			 if ($y) imageline($image, round($x1)+$k1+1, round($y1)+$k2, round($xend)+$k1+1, round($yend)+$k2, $color);

			 if ($x) imageline($image, round($x1)+$k1, round($y1)+$k2+1, round($xend)+$k1, round($yend)+$k2+1, $color);

		  }

	   }

	}

}
?>