<?php
/**
 * File: searchmapsjson.php
 * Description: Search API endpoint returning JSON results
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("../includes/config.inc.php");
include("../db.php");

$rows = $db->get_results("select * from geotiffs where ispublic is true");

$thiscollection = array();
$thiscollection['type']="FeatureCollection";

$allfeatures = array();

foreach($rows as $row){

	$pkey=$row->pkey;
	$name=$row->name;
	$hash=$row->hash;
	$gdalinfo=$row->gdalinfo;
	$gdalinfo = explode("\n",$gdalinfo);

	foreach($gdalinfo as $part){
		if(substr($part,0,6)=="Center"){
			$part=trim(explode(")",$part)[0]);
			$part=trim(explode("(",$part)[1]);
			$longitude = (float)trim(explode(",",$part)[0]);
			$latitude = (float)trim(explode(",",$part)[1]);

		}
	}

	$thisfeature = array();
	$thisfeature['type']="Feature";
	$thisfeature['geometry']=array();
	$thisfeature['geometry']['type']="Point";
	$thisfeature['geometry']['coordinates']=array();
	$thisfeature['geometry']['coordinates'][0]=$longitude;
	$thisfeature['geometry']['coordinates'][1]=$latitude;
	$thisfeature['properties']=array();
	$thisfeature['properties']['name']=$name;
	$thisfeature['properties']['hash']=$hash;

	$allfeatures[]=$thisfeature;
}

$thiscollection['features'] = $allfeatures;

$json = json_encode($thiscollection,JSON_PRETTY_PRINT);

header('Content-Type: application/json');
echo $json;

?>