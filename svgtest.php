<?php

include("logincheck.php");
include("prepare_connections.php");
include_once("includes/straboSVG.php");

//will be passed in: dataset_id && id of parent strat_section spot.

//dataset id: 15352063729347 //this will be passed in 
//strat_section_id: 15365974019014
//parent strat_section spot id: 15365973856225 //this is the one that will be passed in


$allspots = [];

$parent_spot_id = 15365973856225;

$parent_spot = $strabo->getSingleSpot($parent_spot_id);

$strat_section_id = $parent_spot->properties->sed->strat_section->strat_section_id;
$strat_section = $parent_spot->properties->sed->strat_section;


/*
echo "strat_section_id: $strat_section_id<br>";
echo "column_profile: $column_profile<br>";
echo "column_y_axis_units: $column_y_axis_units<br>";

$strabo->dumpVar($parent_spot);
*/











$spots = $strabo->getDatasetSpots(15352063729347);
$spots = $spots['features'];

foreach($spots as $spot){
	if($spot['properties']['strat_section_id']==$strat_section_id){
		array_push($allspots,$spot);
	}
}





// gather all spots first to place on column graph

$svg = new straboSVG($allspots,$strat_section);

//$svg->line(100,100,200,200,2,"#333333","dot");
//$svg->text("bar",100,200,45,null,"#ff0000");

$svg->outToBrowser();





















exit();




$yinterval = 20;
$xinterval = 10;
$pixelratio = 1;




$sectionheight = round(875.4);

$yaxisheight = $sectionheight * $pixelratio;

$svgheight = $yaxisheight + (200 * $pixelratio);



$svgwidth=1500; //need to calculate


$image = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$image .= "<svg width=\"$svgwidth\" height=\"$svgheight\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\">\n";
$image .= "<rect width=\"100%\" height=\"100%\" fill=\"#F5F5F5\"/>";


$image .= "<text x=\"100\" y=\"100\" fill=\"#333333\" transform=\"rotate(45 100,100)\">I love SVG</text>";

$image.= "</svg>";


header('Content-Type: image/svg+xml');
echo $image;