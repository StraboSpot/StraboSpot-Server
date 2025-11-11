<?php
/**
 * File: notableSpots.php
 * Description: Handles notableSpots operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../prepare_connections.php");

/*

https://strabospot.org/search/?datasetid=$datasetid


Needed Params:
xmin
xmax
ymin
ymax

https://strabospot.org/REST/notableSpots?xmin=-109.031390&xmax=-102.039524&ymin=36.993778&ymax=40.955011
https://strabospot.org/REST/notableSpots?xmin=-114.044427&xmax=-109.031390&ymin=37.028869&ymax=40.988192
https://strabospot.org/REST/notableSpots?xmin=-114.044427&xmax=-111.625857&ymin=37.028869&ymax=38.967951

https://strabospot.org/REST/notableSpots?xmin=-114.044427&xmax=-111.625857&ymin=37.028869&ymax=38.967951

https://strabospot.org/REST/notableSpots?xmin=-106.164745&xmax=-103.636240&ymin=38.225235&ymax=39.219487

example query:
and ST_Contains(ST_GeomFromText('POLYGON((-117.5125 38.977500343323,-113.2375 40.552500343323,-113.63125 37.346250343323,-117.5125 38.977500343323))'), s.location)
*/

$xmin = isset($_GET['xmin']) ? (float)$_GET['xmin'] : 0;
$xmax = isset($_GET['xmax']) ? (float)$_GET['xmax'] : 0;
$ymin = isset($_GET['ymin']) ? (float)$_GET['ymin'] : 0;
$ymax = isset($_GET['ymax']) ? (float)$_GET['ymax'] : 0;

function showError($error){
	$out = new stdClass();
	$error .= "The StraboSpot notableSpots endpoint expects four parameters (xmin, xmax, ymin, ymax) used to create a bounding box for locating spots. These bounding values should be provided in decimal degrees.";
	$out->error = $error;
	header("Bad Request", true, 400);
	header('Content-type: application/json');
	echo json_encode($out, JSON_PRETTY_PRINT);
	exit();
}

if($xmin == "" || $xmax == "" || $ymin == "" || $ymax == ""){

	if($xmin == "") $error .= "xmin cannot be blank. ";
	if($xmax == "") $error .= "xmax cannot be blank. ";
	if($ymin == "") $error .= "ymin cannot be blank. ";
	if($ymax == "") $error .= "ymax cannot be blank. ";
	showError($error);

}else{

	if(($xmin > $xmax) || ($ymin > $ymax)){

		if($xmin > $xmax) $error .= "xmin cannot be greater than xmax. ";
		if($ymin > $ymax) $error .= "ymin cannot be greater than ymax. ";
		showError($error);

	}else{

		//Gather dataset ids from Neo4J to make sure that the landing page is going to work
		$neoids = [];

		$rows = file_get_contents("https://strabospot.org/search/newsearchdatasets.json");
		$rows = json_decode($rows);
		$rows = $rows->features;

		foreach($rows as $row){
			$neoids[] = explode("-", $row->properties->id)[1];
		}

		/*
		$rows = $neodb->get_results("match (u:User)-[HAS_PROJECT]->(p:Project)-[pdr:HAS_DATASET]->(d)-[:HAS_SPOT]->(s:Spot) where (p.public = 1 or p.public = true or p.userpkey = $userpkey) return distinct(d)");
		foreach($rows as $row){
			$row=$row->get("d");
			$row=$row->values();
			if($row['centroid']){
				$neoids[] = $row['id'];
			}
		}
		*/

		//OK, we have everything we need
		//and ST_Contains(ST_GeomFromText('POLYGON((-117.5125 38.977500343323,-113.2375 40.552500343323,-113.63125 37.346250343323,-117.5125 38.977500343323))'), s.location)

		$out = new stdClass();
		$out->type = "FeatureCollection";
		$features = [];

		$poly = "$xmin $ymin, $xmin $ymax, $xmax $ymax, $xmax $ymin, $xmin $ymin";

		$rows = $db->get_results("
			select s.spotjson, d.strabo_dataset_id
			from
			project p, dataset d, spot s, image i
			where
			p.project_pkey = d.project_pkey and
			d.dataset_pkey = s.dataset_pkey and
			s.spot_pkey = i.spot_pkey
			and
			(
			((i.caption is not null and i.caption != '') or (i.title is not null and i.title != ''))
			or s.has_rosetta)
			and ST_Contains(ST_GeomFromText('POLYGON(($poly))'), s.location)
			and p.ispublic
			and not strat_exists
			group by s.spotjson, d.strabo_dataset_id
			--limit 10
			;
		");

		foreach($rows as $row){
			$datasetid = $row->strabo_dataset_id;
			$json = $row->spotjson;
			$json = str_replace("\/db\/image\/", "\/pi\/", $json);
			$json = json_decode($json);
			$json->properties->landing_page = "https://strabospot.org/search/?datasetid=$datasetid";
			if(in_array($datasetid, $neoids)){
				$features[] = $json;
			}
		}

		$out->features = $features;

		if(count($features) > 0){

		}else{
			header("No spots found in bounding box.", true, 404);
		}

		header('Content-type: application/json');
		echo json_encode($out, JSON_PRETTY_PRINT);

	}
}

?>