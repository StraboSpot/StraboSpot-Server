<?
session_start();



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

//$neodb->dumpVar($_SESSION);exit();

$userpkey = $_SESSION['userpkey'];
if($userpkey == ""){
	$userpkey = 99999;
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

/*
$querystring="match (u:User)-[HAS_PROJECT]->(p:Project)-[pdr:HAS_DATASET]->(d)-[:HAS_SPOT]->(s:Spot)
where (p.public = 1 or p.public = true or p.userpkey = $userpkey)
$imagestring
$orientationstring
$samplestring
$_3dstructurestring
with u,p,d,count(s) as c
with u,c,p,collect(d) as d
with u,c,d,collect(p) as p
with {u:u,c:c,d:d,p:p} as u
return u";
*/

$querystring="match (u:User)-[HAS_PROJECT]->(p:Project)-[pdr:HAS_DATASET]->(d)-[:HAS_SPOT]->(s:Spot)
where (p.public = 1 or p.public = true or p.userpkey = $userpkey)
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


//echo $querystring;


$rows = $neodb->get_results("$querystring");

//$neodb->dumpVar($rows);exit();

//echo "count: ".count($rows);exit();

$features = array();

$features['type']="FeatureCollection";

$features['features']=array();

foreach($rows as $row){

	//$neodb->dumpVar($row);//exit();
	
	$row = $row->get("u");
	
	//$neodb->dumpVar($row);exit();
	
	$user=$row["u"]->values();
	//$neodb->dumpVar($user);exit();
	
	$ownerstring = $user["firstname"]." ".$user["lastname"];
	$userpkey = $user['userpkey'];
	
	unset($f);
	$f=array();
	
	$f['type']='Feature';

	unset($centroid);
	
	$p=$row["p"][0];
	$p=$p->values();
	
	$projectname = $p['desc_project_name'];
	
	//$neodb->dumpVar($projectname);exit();
	




























	//$d=$row["d"][0];


	foreach($row["d"] as $d){
		$d=$d->values();
		
		//$neodb->dumpVar($d);//exit();
		
		$count = $row["c"];
		
		$centroid = $d["centroid"];
		$id = $d["id"];
		
		//echo $id."<br>";
		
		if($centroid){
		
			//$neodb->dumpVar($d);
			//echo $id."<br>";
			
			$centroid = str_replace("POINT (","",$centroid);
			$centroid = str_replace(")","",$centroid);
	
			$parts = explode(" ",$centroid);
			$longitude = (float)$parts[0];
			$latitude = (float)$parts[1];
		
			if($longitude >= -180 && $longitude <= 180 && $latitude >= -90 && $latitude <= 90 ){
			
				$label = $d['name'];
			
				//echo "label: $label<br>";
			
				if($label=="")$label="No Name";
		
				$f['geometry']['type']="Point";
				$f['geometry']['coordinates'][0]=$longitude;
				$f['geometry']['coordinates'][1]=$latitude;
	
				$f['properties']['name']=$label;
				$f['properties']['projectname']=$projectname;
				$f['properties']['id']=$userpkey."-".$id;
				$f['properties']['count']=$count;
				$f['properties']['owner']=$ownerstring;
			
				$features['features'][]=$f;
				
				//echo $id."<br>";
			
			}
		
			//echo "long: $longitude lat: $latitude label: $label<br>";
		
		}
	}
	
	
	
	
	
}

//exit();

$json = json_encode($features,JSON_PRETTY_PRINT);

header('Content-Type: application/json');
echo $json;



?>