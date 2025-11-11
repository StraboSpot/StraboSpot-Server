<?php
/**
 * File: testsearchdatasetsjson.php
 * Description: Searches and filters datasets
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include_once "../includes/config.inc.php";
include "../neodb.php"; //neo4j database abstraction layer

if($_GET["hasimage"]=="yes"){
	$imagestring = "match (s)-[:HAS_IMAGE]->(i:Image)";
}

if($_GET["hasorientation"]=="yes"){
	$orientationstring = "match (s)-[:HAS_ORIENTATION]->(o:Orientation)";
}

if($_GET["hassample"]=="yes"){
	$samplestring = "match (s)-[:HAS_SAMPLE]->(samp:Sample)";
}

if($_GET["has3dstructure"]=="yes"){
	$_3dstructurestring = "match (s)-[:HAS_3D_STRUCTURE]->(td:_3DStructure)";
}

if($_GET["hasotherfeature"]=="yes"){
	$_3dstructurestring = "match (s)-[:HAS_OTHER_FEATURE]->(hof:OtherFeature)";
}



/*

$querystring="
match (p:Project {public:1})-[HAS_DATASET]->(d:Dataset)
match (d)-[:HAS_SPOT]->(s:Spot)
$imagestring
$orientationstring
$samplestring
$_3dstructurestring
return distinct(d), count(s) as c
";

$querystring="match (u:User)-[HAS_PROJECT]->(p:Project {public:1})-[pdr:HAS_DATASET]->(d)
with u,p,collect(d) as d
with u,{p:p,d:d} as p
with {u:u,p:p} as u
return u";

*/


$querystring="match (u:User)-[HAS_PROJECT]->(p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[:HAS_SPOT]->(s:Spot)
$imagestring
$orientationstring
$samplestring
$_3dstructurestring
with u,p,d,count(s) as c
with u,c,p,collect(d) as d
with u,c,d,collect(p) as p
with {u:u,c:c,d:d,p:p} as u
return u";


//$rows = $neodb->get_results("match (d:Dataset) with d, rand() as number return d order by number limit 50 ");
//$rows = $neodb->get_results("match (p:Project {public:1})-[HAS_DATASET]->(d:Dataset) return d");
//$rows = $neodb->get_results("match (p:Project {userpkey:[3,4]})-[HAS_DATASET]->(d:Dataset) return d");



$rows = $neodb->get_results("$querystring");



$features = array();

$features['type']="FeatureCollection";

$features['features']=array();

foreach($rows as $row){


	$row = $row->get("u");


	$user=$row["u"]->values();

	$ownerstring = $user["firstname"]." ".$user["lastname"];

	unset($f);
	$f=array();

	$f['type']='Feature';

	unset($centroid);

	$p=$row["p"][0];
	$p=$p->values();

	$projectname = $p['desc_project_name'];


	$d=$row["d"][0];

	$d=$d->values();


	$count = $row["c"];

	$centroid = $d["centroid"];
	$id = $d["id"];

	if($centroid){


		$centroid = str_replace("POINT (","",$centroid);
		$centroid = str_replace(")","",$centroid);

		$parts = explode(" ",$centroid);
		$longitude = (float)$parts[0];
		$latitude = (float)$parts[1];

		//if($longitude >= -180 && $longitude <= 180 && $latitude >= -90 && $latitude <= 90 ){

			$label = $d['name'];


			if($label=="")$label="No Name";

			$f['geometry']['type']="Point";
			$f['geometry']['coordinates'][0]=$longitude;
			$f['geometry']['coordinates'][1]=$latitude;

			$f['properties']['name']=$label;
			$f['properties']['projectname']=$projectname;
			$f['properties']['id']=$id;
			$f['properties']['count']=$count;
			$f['properties']['owner']=$ownerstring;

			$features['features'][]=$f;

		//}


	}
}


$json = json_encode($features,JSON_PRETTY_PRINT);

header('Content-Type: application/json');
echo $json;



?>