<?php

	/**********************************************************************
	*  Author: Jason Ash (jasonash@ku.edu)
	*  Web...: https://strabospot.org
	*  Name..: StraboSVG
	*  Desc..: Provides basic SVG functionality
	*
	*/

	//geoPHP required
	include_once('includes/geophp/geoPHP.inc');

	class straboSVG{

		var $svgwidth=500;
		var $svgheight=500;
		var $svgout = "";
		var $yinterval = 20;
		var $xinterval = 10;
		var $pixelratio = 4;
		var $lines = "";
		var $polygons = "";
		var $spotlines = "";
		var $spotpoints = "";
		var $spotlabels = "";
		var $sectionheight = 0;
		var $xlabelcount = 0;
		var $yaxisoffset = 50;
		var $ytickwidth = 10;
		var $xaxisoffset = 100;
		var $xtickwidth = 10;
		var $smallticks = [];
		var $smalltickwidth = 3;
		var $loadedPatternDirs = [];

		function straboSVG($allspots,$strat_section){

			$this->strat_section = $strat_section;
			
			//$this->dumpVar($this->strat_section);
			//$this->dumpVar($allspots);exit();
			
			//https://www.strabospot.org/pi/15198456385976
			
			$this->loadXAxisLabels();
			$this->loadPatternInfo();
			$this->loadPatterns($allspots,$strat_section);
			
			//$this->dumpVar($allspots);exit();
			
			//get sectionheight
			foreach($allspots as $spot){
				$geom = geoPHP::load(json_encode($spot),'json');
				$bbox = $geom->getBBox();
				$this->sectionheight = $bbox['maxy']>$this->sectionheight ? $bbox['maxy'] : $this->sectionheight;
			}

			$this->column_profile = $strat_section->column_profile;
			$this->misc_labels = $strat_section->misc_labels;
			$this->column_y_axis_units = $strat_section->column_y_axis_units;
			
			$this->yaxisheight = $this->sectionheight + 40;
			$this->svgheight = ($this->yaxisheight * $this->pixelratio) + (130 * $this->pixelratio);

			$this->buildYAxis();
			$this->buildXAxis();
			
			$this->buildImages();
			
			$this->drawSpots($allspots);
			
		}
		
		function dumpVar($var){
			echo "\n<pre>\n";
			print_r($var);
			echo "\n******************************************************\n";
			echo "\n</pre>\n";
		}
		
		function setWidth($width){
			$this->svgwidth = $width;
		}

		function setHeight($height){
			$this->svgheight = $height;
		}

		function debug(){
			echo "width: $this->svgwidth height: $this->svgheight";
		}

		function line($x1, $y1, $x2, $y2, $weight=1, $color="#333", $linetype="solid"){
			$y1 = $this->svgheight - ($this->pixelratio*$y1);
			$y2 = $this->svgheight - ($this->pixelratio*$y2);
			$x1 = ($this->pixelratio*$x1);
			$x2 = ($this->pixelratio*$x2);
			
			if($linetype == "dot"){
				$dasharray=" stroke-dasharray=\"$weight\"";
			}elseif($linetype == "dash"){
				$dashweight = $weight*2;
				$dasharray = " stroke-dasharray=\"$dashweight\"";
			}else{
				$dasharray="";
			}
			
			$this->lines .= "<line x1=\"$x1\" y1=\"$y1\" x2=\"$x2\" y2=\"$y2\" style=\"stroke:$color;stroke-width:$weight\"$dasharray />\n";
		}

		function text($text="foo", $x = 0, $y = 0, $rotate = 0, $size = 16, $color = "#333", $anchor = "start" ){
			$y = $this->svgheight - ($this->pixelratio * $y);
			$x = ($this->pixelratio * $x);
			$this->texts .= "<text x=\"$x\" y=\"$y\" style=\"text-anchor: $anchor; font-family: Arial; fill: $color; stroke: none; font-size: $size"."px;\" transform=\"rotate($rotate $x,$y)\">$text</text>\n";
		}

		function outSVG(){
			$svgdisplay = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
			$svgdisplay .= "<svg width=\"$this->svgwidth\" height=\"$this->svgheight\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\">\n";
			$svgdisplay .= $this->patterns;
			//$svgdisplay .= "<rect width=\"100%\" height=\"100%\" fill=\"#F5F5F5\"/>\n";
			$svgdisplay .= "<rect width=\"100%\" height=\"100%\" fill=\"#FFFFFF\"/>\n";
			$svgdisplay .= $this->lines;
			$svgdisplay .= $this->images;
			$svgdisplay .= $this->polygons;
			$svgdisplay .= $this->texts;
			$svgdisplay .= $this->spotlines;
			$svgdisplay .= $this->spotpoints;
			$svgdisplay .= $this->spotlabels;
			$svgdisplay.= "</svg>";
			echo $svgdisplay;
		}

		function outToBrowser(){
			header('Content-Type: image/svg+xml');
			$this->outSVG();
		}

		function getBase64Resized($image_id){
			$img = imagecreatefromjpeg("https://www.strabospot.org/pi/$image_id");
	
			if($img){
				$width = imagesx($img);
				$height = imagesy($img);
	
				if($height > $width){
					$newwidth = 600;
				}else{
					$newwidth = 800;
				}
	
				$img = imagescale($img,$newwidth);

				ob_start();
				imagejpeg($img);
				imagedestroy($img);
				$i = ob_get_clean();
				$i = base64_encode($i);
	
				return $i;

			}else{
				return null;
			}
	

		}

		function buildImages(){
			$y1 = $this->svgheight - ($this->pixelratio*$y1);
			$y2 = $this->svgheight - ($this->pixelratio*$y2);
			$x1 = ($this->pixelratio*$x1);
			$x2 = ($this->pixelratio*$x2);

			if($this->strat_section->images){
				foreach($this->strat_section->images as $i){
					
					//$this->dumpVar($i);
					
					//$x = ($i->image_origin_x * $this->pixelratio) + $this->xaxisoffset;
					//$y = $this->svgheight - (($i->image_origin_y * $this->pixelratio) + $this->yaxisoffset);
					
					
					$height = $i->image_height * $this->pixelratio;
					$width = $i->image_width * $this->pixelratio;
					
					$x = $this->yaxisoffset * $this->pixelratio + ($i->image_origin_x * $this->pixelratio);
					$y = $this->svgheight - ($this->xaxisoffset * $this->pixelratio) - ($height) - ($i->image_origin_y * $this->pixelratio);
					
					$id = $i->id;
					$base64 = $this->getBase64Resized($id);
					if($base64){
						//$this->images .= "<image xlink:href=\"https://www.strabospot.org/pi/$id\" x=\"$x\" y=\"$y\" height=\"".$height."px\" width=\"".$width."px\"/>\n";
						
						$this->images .= "<image xlink:href=\"data:image/jpg;base64,$base64\" x=\"$x\" y=\"$y\" height=\"".$height."px\" width=\"".$width."px\"/>\n";
	
					}
					
				}
			}

			//$this->dumpVar($this->images);

			//exit();
			$height = 133 * $this->pixelratio;
			$width = 100 * $this->pixelratio;
			$x = $this->yaxisoffset * $this->pixelratio + ($i->image_origin_x * $this->pixelratio);
			$y = $this->svgheight - ($this->xaxisoffset * $this->pixelratio) - ($height) - ($i->image_origin_y);
			
			//$this->images = "<image xlink:href=\"https://www.strabospot.org/pi/15198456385976\" x=\"$x\" y=\"$y\" height=\"".$height."px\" width=\"".$width."px\"/>";
		}
		
		function buildYAxis(){
			$tickcount = floor($this->yaxisheight / $this->yinterval) + 0;
			for($x = 0; $x <= $tickcount; $x++){
				$y = ($x * $this->yinterval) + $this->xaxisoffset;
				if($x>0){
					$this->line($this->yaxisoffset, $y, $this->yaxisoffset-$this->ytickwidth, $y, 2);
					$this->text($x, $this->yaxisoffset - 5.5 - $this->ytickwidth, $y-1, 0, 12);
				}
			}
			
			$this->line($this->yaxisoffset, $this->xaxisoffset - 15, $this->yaxisoffset, $this->yaxisheight + $this->xaxisoffset, 2);
		}

		function drawXLabels($labeltype, $yoffset, $color = "#333333", $offset=0){
			$labels=[];
			eval("\$labelarray=\$this->xAxisLabels->$labeltype;");
			foreach($labelarray as $part){
				$labels[]=$part->label;
			}
			$this->xlabelcount = count($labelarray)>$this->xlabelcount ? count($labelarray) : $this->xlabelcount;
			$x=1;
			foreach($labels as $label){
				//ticks
				$this->line( $this->yaxisoffset + ($x * $this->xinterval) + ($offset * $this->xinterval), $this->xaxisoffset - 8 - $yoffset , $this->yaxisoffset + ($x * $this->xinterval) + ($offset * $this->xinterval), $this->xaxisoffset - $yoffset, 2, $color);
				$this->text($label, $this->yaxisoffset + ($x * $this->xinterval) - 1 + ($offset * $this->xinterval), $this->xaxisoffset - ($yoffset) - $this->xtickwidth - 1, 37, null, $color);
				$this->line($this->yaxisoffset + ($x * $this->xinterval) + ($offset * $this->xinterval), $this->xaxisoffset, $this->yaxisoffset + ($x * $this->xinterval) + ($offset * $this->xinterval), $this->yaxisheight + $this->xaxisoffset, 2, "#333", "dot");
				$x++;
			}
			
			if($yoffset != 0){ //also draw new x axi line
				//echo "offset: $offset";exit();
				//$this->line( $this->yaxisoffset, $this->xaxisoffset - 8 - $yoffset , $this->yaxisoffset + (($x + 1) * $this->xinterval), $this->xaxisoffset - 8 - $yoffset, 2, $color);
				$this->line( $this->yaxisoffset, $this->xaxisoffset - $yoffset , $this->yaxisoffset + (($x + $offset) * $this->xinterval), $this->xaxisoffset - $yoffset, 2, $color);
				$this->line( $this->yaxisoffset, $this->xaxisoffset - 28 - $yoffset ,$this->yaxisoffset, $this->xaxisoffset - 15, 2, $color);
			}
			
			//x axis label
			$this->text($labeltype, $this->yaxisoffset - 25, $this->xaxisoffset - 10 - $yoffset, 0, 12, $color);
			
			
		}
		
		function buildXAxis(){
			if($this->column_profile == 'clastic'){
				$labeloffset = 0;
				$this->drawXLabels('clastic',0,"#333333",$labeloffset);
				//$this->drawXLabels('clastic',30,"#FF0000");
			}elseif($this->column_profile =='carbonate'){
				$labeloffset = 0.333;
				$this->drawXLabels('carbonate',0,"blue",$labeloffset);
			}elseif($this->column_profile =='mixed_clastic'){
				$labeloffset = 0;
				$this->drawXLabels('clastic',0,"#333333",$labeloffset);
				$labeloffset = 0.333;
				$this->drawXLabels('carbonate',35,"blue",$labeloffset);
			}elseif($this->column_profile =='weathering_pro'){
				$labeloffset = 0;
				$this->drawXLabels('weathering',0,"#333333",$labeloffset);
			}elseif($this->column_profile =='basic_lithologies'){
				$labeloffset = 1;
				$this->drawXLabels('basic_lithologies',0,"#333333",$labeloffset);
			}else{
				exit("incorrect profile type: $this->column_profile");
			}
			
			
			if($this->misc_labels == true){
				$labeloffset = 1.666;
				$this->drawXLabels('misc',35,"green",$labeloffset);
			}
			
			
			//define svgwidth
			$this->svgwidth = ($this->xlabelcount * $this->pixelratio * $this->xinterval) + (100 * $this->pixelratio);
			$this->line($this->yaxisoffset - $this->ytickwidth - 10, $this->xaxisoffset, ($this->xlabelcount * $this->xinterval ) + $this->yaxisoffset + $this->xinterval + ($labeloffset * $this->xinterval) , $this->xaxisoffset, 2);
			$this->text("0".$this->column_y_axis_units, $this->yaxisoffset - $this->ytickwidth - 18, $this->xaxisoffset - 1, 0, 14);
			
			//$this->text($this->column_profile, $this->yaxisoffset - 25, $this->xaxisoffset - 10, 0, 12);
		}

		function spotPolygon($name, $parts, $centroid, $color="", $pattern){
			$parts=$parts[0];
			array_pop($parts);
			$coordsstring="";
			foreach($parts as $part){
				//small ticks for non-integer
				$origx = $part[0];
				$origy = $part[1];
				$ylabel = $part[1]/$this->yinterval;
				$texty = $part[1];

				$x = ($origx + $this->yaxisoffset) * $this->pixelratio;
				$y = $this->svgheight - (($origy + $this->xaxisoffset) * $this->pixelratio );
				$texty = ( $ylabel * $this->yinterval ) + $this->xaxisoffset;
				
				if($origx == 0){
					if($ylabel != round($ylabel)){
						if(!in_array($ylabel,$this->smallticks)){
							$this->text($ylabel, $this->yaxisoffset - 10, $texty - 1, 0, 10);
							$this->line( $this->yaxisoffset - 3, $texty,  $this->yaxisoffset, $texty, 2);
							$this->smallticks[] = $origy;
						}
					}
				}

				$coordstring.=" $x,$y";
			}
			
			$cx = $centroid[0] + $this->yaxisoffset;
			$cy = $centroid[1] + $this->xaxisoffset - 1;
			$this->spotLabel($name,$cx,$cy,0,12,"#333333","middle");
			
			$coordstring = trim($coordstring);
			
			if($color!=""){
				$colorstring="fill:$color; ";
			}elseif($pattern!=""){
				$patternstring=" class=\"$pattern\"";
			}
			
			$this->polygons .= "<polygon points=\"$coordstring\" style=\"$colorstring"."stroke:#333333;stroke-width:2\"$patternstring />\n";
		}


		function spotLine($name, $parts, $centroid, $color="#EEEEEE"){
			$coordsstring="";
			foreach($parts as $part){
				//small ticks for non-integer
				$origx = $part[0];
				$origy = $part[1];

				$x = ($origx + $this->yaxisoffset) * $this->pixelratio;
				$y = $this->svgheight - (($origy + $this->xaxisoffset) * $this->pixelratio );

				$coordstring.=" $x,$y";
			}
			
			$cx = $centroid[0] + $this->yaxisoffset;
			$cy = $centroid[1] + $this->xaxisoffset + 3;
			$this->spotLabel($name,$cx,$cy,0,12,"#333333","middle");
			
			$coordstring = trim($coordstring);
			
			$this->spotlines .= "<polyline points=\"$coordstring\" style=\"fill:none;stroke:$color;stroke-width:3\" />\n";

		}

		function spotPoint($name, $parts, $centroid, $color="#EEEEEE"){
			
			$x = $parts[0];
			$y = $parts[1];
			
			$x = ($x + $this->yaxisoffset) * $this->pixelratio;
			$y = $this->svgheight - (($y + $this->xaxisoffset) * $this->pixelratio );

			$cx = $centroid[0] + $this->yaxisoffset + 2;
			$cy = $centroid[1] + $this->xaxisoffset + 2;
			$this->spotLabel($name,$cx,$cy,0,12,"#333333","start");

			$this->spotpoints .= "<circle cx=\"$x\" cy=\"$y\" r=\"7\" stroke=\"black\" stroke-width=\"2\" fill=\"$color\" />\n";
		}

		function spotLabel($text="foo", $x = 0, $y = 0, $rotate = 0, $size = 16, $color = "#333", $anchor){
			$y = $this->svgheight - ($this->pixelratio * $y);
			$x = ($this->pixelratio * $x);
			//$this->spotlabels .= "<text x=\"$x\" y=\"$y\" style=\"text-anchor: $anchor; font-family: Arial; fill: $color; stroke: none; font-size: $size"."px;\" transform=\"rotate($rotate $x,$y)\">$text</text>\n";
			$this->spotlabels .= "<text x=\"$x\" y=\"$y\" style=\"text-anchor: $anchor; paint-order: stroke; font-family: Arial; fill: $color; stroke: #FFFFFF; stroke-width: 2px; font-weight:bold; font-size: $size"."px;\" transform=\"rotate($rotate $x,$y)\">$text</text>\n";
		}

		function drawSpots($allspots){
			foreach($allspots as $spot){
				$geom = geoPHP::load(json_encode($spot),'json');
				$parts = $geom->asArray();
				$geomtype = $spot['geometry']->type;
				$spotname = $spot['properties']['name'];
				$centroid = $geom->centroid()->asArray();

				if($geomtype=="LineString"){
					$this->spotLine($spotname, $parts, $centroid, "#666666"); //FF9933
				}elseif($geomtype=="Polygon"){
				
				
					//getClasses will either return an array of classes from patterns, or a single color string like this: rgba(153, 0, 0, 1)
					$classes = $this->getClasses($spot);
					if(is_array($classes)){
						//do patterns
						foreach($classes as $class){
							$this->spotPolygon($spotname, $parts, $centroid, "", "$class");
						}
					}else{
						//do color here
						$this->spotPolygon($spotname, $parts, $centroid, "$classes", "");
					}

				}elseif($geomtype=="Point"){
					$this->spotPoint($spotname, $parts, $centroid, "#e0eaf9");
				}
			}
			
			//exit();

		}



		function getColorString($spot){
			
			if($this->strat_section->column_profile=="basic_lithologies"){
				$lithology = $spot['properties']['sed']->lithologies;
				if($lithology->primary_lithology=="limestone") $colorstring = "rgba(77, 255, 222, 1)";
				elseif($lithology->primary_lithology=="dolomite") $colorstring = "rgba(77, 255, 179, 1)";
				elseif($lithology->primary_lithology=="organic_coal") $colorstring = "rgba(0, 0, 0, 1)";
				elseif($lithology->primary_lithology=="evaporite") $colorstring = "rgba(153, 77, 255, 1)";
				elseif($lithology->primary_lithology=="chert") $colorstring = "rgba(102, 77, 77, 1)";
				elseif($lithology->primary_lithology=="ironstone") $colorstring = "rgba(153, 0, 0, 1)";
				elseif($lithology->primary_lithology=="phosphatic") $colorstring = "rgba(153, 255, 179, 1)";
				elseif($lithology->primary_lithology=="volcaniclastic") $colorstring = "rgba(255, 128, 255, 1)";
				elseif($lithology->mud_silt_principal_grain_size!="") $colorstring="rgba(128, 222, 77, 1)";
				elseif($lithology->sand_principal_grain_size!="") $colorstring="rgba(255, 255, 77, 1)";
				elseif($lithology->congl_principal_grain_size!="") $colorstring="rgba(255, 102, 0, 1)";
				elseif($lithology->breccia_principal_grain_size!="") $colorstring="rgba(213, 0, 0, 1)";
				else $colorstring = "rgba(255, 255, 255, 1)";

			}else{
				if($spot['properties']['sed']->lithologies->mud_silt_principal_grain_size != "") $lithology = $spot['properties']['sed']->lithologies->mud_silt_principal_grain_size;
				elseif($spot['properties']['sed']->lithologies->sand_principal_grain_size != "") $lithology = $spot['properties']['sed']->lithologies->sand_principal_grain_size;
				elseif($spot['properties']['sed']->lithologies->congl_principal_grain_size != "") $lithology = $spot['properties']['sed']->lithologies->congl_principal_grain_size;
				elseif($spot['properties']['sed']->lithologies->breccia_principal_grain_size != "") $lithology = $spot['properties']['sed']->lithologies->breccia_principal_grain_size;
				elseif($spot['properties']['sed']->lithologies->principal_dunham_class != "") $lithology = $spot['properties']['sed']->lithologies->principal_dunham_class;
				elseif($spot['properties']['sed']->lithologies->primary_lithology != "") $lithology = $spot['properties']['sed']->lithologies->primary_lithology;

				if($lithology == "clay") $colorstring = "rgba(128, 222, 77, 1)";
				elseif($lithology == "mud") $colorstring = "rgba(77, 255, 0, 1)";
				elseif($lithology == "silt") $colorstring = "rgba(153, 255, 102, 1)";
				elseif($lithology == "sand_very_fin") $colorstring = "rgba(255, 255, 179, 1)";
				elseif($lithology == "sand_fine_low") $colorstring = "rgba(255, 255, 153, 1)";
				elseif($lithology == "sand_fine_upp") $colorstring = "rgba(255, 255, 128, 1)";
				elseif($lithology == "sand_medium_l") $colorstring = "rgba(255, 255, 102, 1)";
				elseif($lithology == "sand_medium_u") $colorstring = "rgba(255, 255, 77, 1)";
				elseif($lithology == "sand_coarse_l") $colorstring = "rgba(255, 255, 0, 1)";
				elseif($lithology == "sand_coarse_u") $colorstring = "rgba(255, 235, 0, 1)";
				elseif($lithology == "sand_very_coa") $colorstring = "rgba(255, 222, 0, 1)";
				elseif($spot['properties']['sed']->lithologies->primary_lithology == "siliciclastic" && $spot['properties']['sed']->lithologies->principal_siliciclastic_type === "conglomerate"){
					if($lithology == "pebble") $colorstring = "rgba(255, 128, 0, 1)";
					elseif($lithology == "cobble") $colorstring = "rgba(255, 102, 0, 1)";
					elseif($lithology == "boulder") $colorstring = "rgba(255, 77, 0, 1)";

				}elseif($spot['properties']['sed']->lithologies->primary_lithology == "siliciclastic" && $spot['properties']['sed']->lithologies->principal_siliciclastic_type === "breccia"){
					if($lithology == "granule") $colorstring = "rgba(230, 0, 0, 1)";
					elseif($lithology == "pebble") $colorstring = "rgba(204, 0, 0, 1)";
					elseif($lithology == "cobble") $colorstring = "rgba(179, 0, 0, 1)";
					elseif($lithology == "boulder") $colorstring = "rgba(153, 0, 0, 1)";
				}elseif($lithology == "mudstone") $colorstring = "rgba(77, 255, 128, 1)";
				elseif($lithology == "wackestone") $colorstring = "rgba(77, 255, 179, 1)";
				elseif($lithology == "packstone") $colorstring = "rgba(77, 255, 222, 1)";
				elseif($lithology == "grainstone") $colorstring = "rgba(179, 255, 255, 1)";
				elseif($lithology == "boundstone") $colorstring = "rgba(77, 128, 255, 1)";
				elseif($lithology == "cementstone") $colorstring = "rgba(0, 179, 179, 1)";
				elseif($lithology == "recrystallized") $colorstring = "rgba(0, 102, 222, 1)";
				elseif($lithology == "floatstone") $colorstring = "rgba(77, 255, 255, 1)";
				elseif($lithology == "rudstone") $colorstring = "rgba(77, 204, 255, 1)";
				elseif($lithology == "framestone") $colorstring = "rgba(77, 128, 255, 1)";
				elseif($lithology == "bafflestone") $colorstring = "rgba(77, 128, 255, 1)";
				elseif($lithology == "bindstone") $colorstring = "rgba(77, 128, 255, 1)";
				elseif($lithology == "evaporite") $colorstring = "rgba(153, 77, 255, 1)";
				elseif($lithology == "chert") $colorstring = "rgba(102, 77, 77, 1)";
				elseif($lithology == "ironstone") $colorstring = "rgba(153, 0, 0, 1)";
				elseif($lithology == "phosphatic") $colorstring = "rgba(153, 255, 179, 1)";
				elseif($lithology == "volcaniclastic") $colorstring = "rgba(255, 128, 255, 1)";
				elseif($lithology == "organic_coal") $colorstring = "rgba(0, 0, 0, 1)";

			}
			
			return $colorstring;
			
		}



		function getClasses($spot){
		
			//either return an array of patterns or a single color string like this: rgba(153, 0, 0, 1)
			$patterndir = $this->getPatternDir($spot);
			//echo "$patterndir<br>";
			
			$colorstring = "";
			if($patterndir == "Blank/Blank"){ //check for color string here
				$colorstring = $this->getColorString($spot);
			}
			
			if($colorstring != ""){
				return $colorstring;
			}else{
			
				$parts = explode("/",$patterndir);
				$folder1 = $parts[0];
				$folder2 = $parts[1];
			
				$return1 = "";
				$return2 = "";
			
				foreach($this->patternInfo as $p){
					if($p->folder1 == $folder1 && $p->folder2 == $folder2){
						$return1 = $p->class1;
						$return2 = $p->class2;
					}
				}
			
				if($return1 == "") $return1 = "foo";
				if($return2 == "") $return2 = "foo";

				return array($return1,$return2);
			
			}
			

			
			//$featureProperties = $spot['properties'];
			//$this->dumpVar($featureProperties);
			
			//add functionality here to get classes based on spot properties.
		
			//return array('straboclass-27','straboclass-28');
		
		
		
		}

		function oldloadPatterns($allspots){ //look through spots and determine which patterns to load
			//$this->patterns = file_get_contents("includes/svgpatterns.txt");
			
			$this->patterns = file_get_contents("svgwork/cleaned/Dolomite/DoBafBasic.svg");
			$this->patterns .= file_get_contents("svgwork/cleaned/Dolomite/DoMudBasic.svg");
			
		}

		function loadPattern($pattern){
		
			if(!in_array($pattern,$this->loadedPatternDirs)){
				//echo "$pattern<br>";
				$this->patterns .= file_get_contents("includes/svg/cleaned/$pattern.svg");
				//echo "trying to load includes/svg/cleaned/$pattern.svg <br>";
				$this->loadedPatternDirs[] = $pattern;
			}
		
		}


		function loadPatterns($spots){
			
			foreach($spots as $spot){
			
				$pattern = $this->getPatternDir($spot);
				
				$this->loadPattern($pattern);
			
			}
			
			//exit();

		}


		function getPatternDir($spot){

				$spot = (object) $spot;
				$featureProperties = (object) $spot->properties;
				
				//$this->dumpVar($this->strat_section);//exit();
				//$this->dumpVar($spot);
				
				$returnpair="";
				
				if ($featureProperties->sed && $featureProperties->sed->lithologies && $featureProperties->strat_section_id) {
					$stratSectionSettings = $this->strat_section;
					if ($stratSectionSettings && $stratSectionSettings->column_profile === 'basic_lithologies') {
						$lithology = (object) $featureProperties->sed->lithologies;
						
						// Limestone / Dolomite / Misc. Lithologies
						if ($lithology->primary_lithology == 'limestone')$returnpair = 'Basic/LimeSimple';
						elseif ($lithology->primary_lithology == 'dolomite')$returnpair = 'Basic/DoloSimple';
						//elseif ($lithology->primary_lithology == 'organic_coal')$returnpair = 'Misc/SiltBasic';
						elseif ($lithology->primary_lithology == 'evaporite')$returnpair = 'Misc/EvaBasic';
						elseif ($lithology->primary_lithology == 'chert')$returnpair = 'Basic/ChertBasic';
						//elseif ($lithology->primary_lithology == 'ironstone')$returnpair = 'Misc/SiltBasic';
						elseif ($lithology->primary_lithology == 'phosphatic')$returnpair = 'Misc/PhosBasic';
						elseif ($lithology->primary_lithology == 'volcaniclastic')$returnpair = 'Misc/VolBasic';

						// Siliciclastic (Mudstone/Shale, Sandstone, Conglomerate, Breccia)
						elseif ($lithology->mud_silt_principal_grain_size)$returnpair = 'Basic/MudSimple';
						elseif ($lithology->sand_principal_grain_size)$returnpair = 'Basic/SandSimple';
						elseif ($lithology->congl_principal_grain_size)$returnpair = 'Basic/CongSimple';
						elseif ($lithology->breccia_principal_grain_size)$returnpair = 'Basic/BrecSimple';
						
						//$this->dumpVar($spot);
					
					}elseif($stratSectionSettings){

						$primaryLithology = $featureProperties->sed->lithologies->primary_lithology;

						if( $featureProperties->sed->lithologies->mud_silt_principal_grain_size!="" ){
							$grainSize = $featureProperties->sed->lithologies->mud_silt_principal_grain_size;
						}elseif( $featureProperties->sed->lithologies->sand_principal_grain_size!="" ){
							$grainSize = $featureProperties->sed->lithologies->sand_principal_grain_size;
						}elseif( $featureProperties->sed->lithologies->congl_principal_grain_size!="" ){
							$grainSize = $featureProperties->sed->lithologies->congl_principal_grain_size;
						}elseif( $featureProperties->sed->lithologies->breccia_principal_grain_size!="" ){
							$grainSize = $featureProperties->sed->lithologies->breccia_principal_grain_size;
						}elseif( $featureProperties->sed->lithologies->principal_dunham_class!="" ){
							$grainSize = $featureProperties->sed->lithologies->principal_dunham_class;
						}elseif( $featureProperties->sed->lithologies->primary_lithology!="" ){
							$grainSize = $featureProperties->sed->lithologies->primary_lithology;
						}
						
						//echo "grainSize: $grainSize<br>";
						
						// Mudstone/Shale
						if ($grainSize == 'clay')$returnpair = 'Siliciclastics/ClayBasic';
						elseif ($grainSize == 'mud')$returnpair = 'Siliciclastics/MudBasic';
						elseif ($grainSize == 'silt')$returnpair = 'Siliciclastics/SiltBasic';
						// Sandstone
						elseif ($grainSize == 'sand_very_fin')$returnpair = 'Siliciclastics/VFBasic';
						elseif ($grainSize == 'sand_fine_low')$returnpair = 'Siliciclastics/FLBasic';
						elseif ($grainSize == 'sand_fine_upp')$returnpair = 'Siliciclastics/FUBasic';
						elseif ($grainSize == 'sand_medium_l')$returnpair = 'Siliciclastics/MLBasic';
						elseif ($grainSize == 'sand_medium_u')$returnpair = 'Siliciclastics/MUBasic';
						elseif ($grainSize == 'sand_coarse_l')$returnpair = 'Siliciclastics/CLBasic';
						elseif ($grainSize == 'sand_coarse_u')$returnpair = 'Siliciclastics/CUBasic';
						elseif ($grainSize == 'sand_very_coa')$returnpair = 'Siliciclastics/VCBasic';
						// Conglomerate
						elseif ($primaryLithology == 'siliciclastic' &&
							$featureProperties->sed->lithologies->principal_siliciclastic_type == 'conglomerate') {
								if ($grainSize == 'granule')$returnpair = 'Siliciclastics/CGrBasic';
								elseif ($grainSize == 'pebble')$returnpair = 'Siliciclastics/CPebBasic';
						  		elseif ($grainSize == 'cobble')$returnpair = 'Siliciclastics/CCobBasic';
						  		elseif ($grainSize == 'boulder')$returnpair = 'Siliciclastics/CBoBasic';
						}
						// Breccia
						elseif ($primaryLithology == 'siliciclastic' &&
						  $featureProperties->sed->lithologies->principal_siliciclastic_type === 'breccia') {
						  if ($grainSize == 'granule')$returnpair = 'Siliciclastics/BGrBasic';
						  elseif ($grainSize == 'pebble')$returnpair = 'Siliciclastics/BPebBasic';
						  elseif ($grainSize == 'cobble')$returnpair = 'Siliciclastics/BCobBasic';
						  elseif ($grainSize == 'boulder')$returnpair = 'Siliciclastics/BBoBasic';
						}
						// Limestone
						elseif ($primaryLithology == 'limestone') {
						  if ($grainSize == 'bafflestone')$returnpair = 'Limestone/LiBoBasic';
						  elseif ($grainSize == 'bindstone')$returnpair = 'Limestone/LiBoBasic';
						  elseif ($grainSize == 'boundstone')$returnpair = 'Limestone/LiBoBasic';
						  elseif ($grainSize == 'floatstone')$returnpair = 'Limestone/LiFloBasic';
						  elseif ($grainSize == 'framestone')$returnpair = 'Limestone/LiBoBasic';
						  elseif ($grainSize == 'grainstone')$returnpair = 'Limestone/LiGrBasic';
						  elseif ($grainSize == 'mudstone')$returnpair = 'Limestone/LiMudBasic';
						  elseif ($grainSize == 'packstone')$returnpair = 'Limestone/LiPaBasic';
						  elseif ($grainSize == 'rudstone')$returnpair = 'Limestone/LiRudBasic';
						  elseif ($grainSize == 'wackestone')$returnpair = 'Limestone/LiWaBasic';
						  //elseif ($grainSize == 'cementstone')$returnpair = 'Limestone/SiltBasic';
						  //elseif ($grainSize == 'recrystallized')$returnpair = 'Limestone/SiltBasic';
						}
						// Dolomite
						elseif ($primaryLithology == 'dolomite') {
						  if ($grainSize == 'bafflestone')$returnpair = 'Dolomite/DoBoBasic';
						  elseif ($grainSize == 'bindstone')$returnpair = 'Dolomite/DoBoBasic';
						  elseif ($grainSize == 'boundstone')$returnpair = 'Dolomite/DoBoBasic';
						  elseif ($grainSize == 'floatstone')$returnpair = 'Dolomite/DoFloBasic';
						  elseif ($grainSize == 'framestone')$returnpair = 'Dolomite/DoBoBasic';
						  elseif ($grainSize == 'grainstone')$returnpair = 'Dolomite/DoGrBasic';
						  elseif ($grainSize == 'mudstone')$returnpair = 'Dolomite/DoMudBasic';
						  elseif ($grainSize == 'packstone')$returnpair = 'Dolomite/DoPaBasic';
						  elseif ($grainSize == 'rudstone')$returnpair = 'Dolomite/DoRudBasic';
						  elseif ($grainSize == 'wackestone')$returnpair = 'Dolomite/DoWaBasic';
						  //elseif ($grainSize == 'cementstone')$returnpair = 'Dolomite/SiltBasic';
						  //elseif ($grainSize == 'recrystallized')$returnpair = 'Dolomite/SiltBasic';
						}
						// Misc. Lithologies
						elseif ($grainSize == 'evaporite')$returnpair = 'Misc/EvaBasic';
						elseif ($grainSize == 'chert')$returnpair = 'Basic/ChertBasic';
						//elseif ($grainSize === 'ironstone')$returnpair = 'Misc/SiltBasic';
						elseif ($grainSize == 'phosphatic')$returnpair = 'Misc/PhosBasic';
						elseif ($grainSize == 'volcaniclastic')$returnpair = 'Misc/VolBasic';
						//elseif ($grainSize === 'organic_coal')$returnpair = 'Misc/SiltBasic';

					}
					
					
					//$this->dumpVar($spot);
				
				
				}else{//end if featureproperties
					//echo "sed not found<br>";
					//$this->dumpVar($featureProperties);
				}
				
				if($returnpair=="") $returnpair = "Blank/Blank";
				
				return $returnpair;

		}















		function loadXAxisLabels(){
		
			$this->xAxisLabels = json_decode('
				{
				  "clastic": [
					{
					  "value": "clay",
					  "label": "clay"
					},
					{
					  "value": "mud",
					  "label": "mud"
					},
					{
					  "value": "silt",
					  "label": "silt"
					},
					{
					  "value": "sand_very_fin",
					  "label": "sand- very fine"
					},
					{
					  "value": "sand_fine_low",
					  "label": "sand- fine lower"
					},
					{
					  "value": "sand_fine_upp",
					  "label": "sand- fine upper"
					},
					{
					  "value": "sand_medium_l",
					  "label": "sand- medium lower"
					},
					{
					  "value": "sand_medium_u",
					  "label": "sand- medium upper"
					},
					{
					  "value": "sand_coarse_l",
					  "label": "sand- coarse lower"
					},
					{
					  "value": "sand_coarse_u",
					  "label": "sand- coarse upper"
					},
					{
					  "value": "sand_very_coa",
					  "label": "sand- very coarse"
					},
					{
					  "value": "granule",
					  "label": "granule"
					},
					{
					  "value": "pebble",
					  "label": "pebble"
					},
					{
					  "value": "cobble",
					  "label": "cobble"
					},
					{
					  "value": "boulder",
					  "label": "boulder"
					}
				  ],
				  "carbonate": [
					{
					  "value": "mudstone",
					  "label": "mudstone"
					},
					{
					  "value": "wackestone",
					  "label": "wackestone"
					},
					{
					  "value": "packstone",
					  "label": "packstone"
					},
					{
					  "value": "grainstone",
					  "label": "grainstone"
					},
					{
					  "value": "floatstone",
					  "label": "floatstone"
					},
					{
					  "value": "rudstone",
					  "label": "rudstone"
					},
					{
					  "value": "boundstone",
					  "label": "boundstone"
					},
					{
					  "value": "framestone",
					  "label": "framestone"
					},
					{
					  "value": "bindstone",
					  "label": "bindstone"
					},
					{
					  "value": "bafflestone",
					  "label": "bafflestone"
					},
					{
					  "value": "cementstone",
					  "label": "cementstone"
					},
					{
					  "value": "recrystallized",
					  "label": "recrystallized"
					}
				  ],
				  "misc": [
					{"value": "organic/coal","label": "organic/coal"},
					{"value": "evaporite","label": "evaporite"},
					{"value": "chert","label": "chert"},
					{"value": "ironstone","label": "ironstone"},
					{"value": "phosphatic","label": "phosphatic"},
					{"value": "volcaniclastic","label": "volcaniclastic"}
				  ],
				  "basic_lithologies": [
					{
					  "value": "other",
					  "label": "other"
					},
					{
					  "value": "coal",
					  "label": "coal"
					},
					{
					  "value": "mudstone",
					  "label": "mudstone"
					},
					{
					  "value": "sandstone",
					  "label": "sandstone"
					},
					{
					  "value": "conglomerate_breccia",
					  "label": "conglomerate/breccia"
					},
					{
					  "value": "limestone_dolomite",
					  "label": "limestone/dolomite"
					}
				  ],
				  "weathering": [
					{
					  "value": "1",
					  "label": "1 - least resistant"
					},
					{
					  "value": "2",
					  "label": "2"
					},
					{
					  "value": "3",
					  "label": "3 - moderately resistant"
					},
					{
					  "value": "4",
					  "label": "4"
					},
					{
					  "value": "5",
					  "label": "5 - most resistant"
					}
				  ]
				}
			');

		}


		function loadPatternInfo(){
			$patterninfo = file_get_contents("includes/svg/cleaned/patterninfo.json");
			$this->patternInfo = json_decode($patterninfo);
		}


	}
?>