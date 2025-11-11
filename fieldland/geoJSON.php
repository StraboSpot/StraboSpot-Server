<?php
/**
 * File: geoJSON.php
 * Description: Handles geoJSON operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


$datasetid = $_GET['datasetid'];
$onload = " onload = \"gotoDataset($datasetid)\"";

include_once "../includes/config.inc.php";
include "../neodb.php"; //neo4j database abstraction layer
include "../db/strabospotclass.php";
include "../db.php";
include_once('../includes/geophp/geoPHP.inc');
include_once "../includes/UUID.php";

$strabo = new StraboSpot($neodb,$userpkey,$db);

$querystring="match (p:Project)-[HAS_DATASET]->(d:Dataset) where d.id = $datasetid return p limit 1";

$rows = $neodb->get_results("$querystring");
$row = $rows[0];
$row = $row->get("p");
$p=$row->values();

$projectjson = [];

$querystring = "match (a:Dataset)-[r:HAS_SPOT]->(s:Spot) where a.id=$datasetid optional match (s)-[c:HAS_IMAGE]-(i:Image) with s, collect(i) as i RETURN s,i;";



foreach($p as $key=>$value){
	if($key!="preferences" && $key!="json_other_features" && $key!="userpkey" && $key!="public" ){
		if($key=="json_tags" || $key=="json_description"){
			$savevalue = json_decode($value);
		}else{
			$savevalue = $value;
		}

		$key = str_replace("json_","",$key);
		$key = str_replace("desc_","",$key);

		$projectjson[$key] = $savevalue;
	}

	$projectjson['detailURL'] = "https://strabospot.org/search/dsmap/$datasetid";
}



$json = $strabo->getFeatureCollection($querystring);
$json['projectMetadata'] = $projectjson;



$json = json_encode($json, JSON_PRETTY_PRINT);



header('Content-Type: application/json');
print $json;





?>