<?

session_start();

$dsids = $_GET['dsids'];

//$_SESSION[userpkey] => 3;

if($_SESSION['userpkey']!=""){
	$userpkey = $_SESSION['userpkey'];
}else{
	$userpkey = 0;
}

include("../includes/config.inc.php");
include("../db.php");
include "../neodb.php"; //neo4j database abstraction layer
include "../db/strabospotclass.php"; //strabospot specific functions
include_once('../includes/geophp/geoPHP.inc'); //geospatial functions
include_once('../includes/UUID.php'); //UUID Class
include_once('../includes/straboClasses/searchQueryRowBuilder.php'); //Build SearchQuery
$querybuilder = new searchQueryRowBuilder();
$querybuilder->setDb($db);

$geoPHP = new geoPHP;

//$spotgeo = $geoPHP::load("POINT(-118.429104 37.418169)",'wkt'); exit();

function logToFile($string){
	if(file_exists("log.txt")){
		file_put_contents ("log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
		file_put_contents ("log.txt", "\n".$string, FILE_APPEND);
	}
}



logToFile(file_get_contents("php://input"));


$json = '{"params":[{"num":0,"qualifier":"and","constraints":[{"constraintType":"keyword","constraintValue":"strabo workshop"}]}]}';


//change this to rawinput
//$json = file_get_contents("fulljson.json");
$json = file_get_contents("php://input");


$searchrows = $querybuilder->buildSearchQueryRows($json);


//$db->dumpVar($searchrows);exit();



if($dsids == ""){
	//do dataset-level return
	$query = "select
				users.pkey as userpkey,
				users.firstname,
				users.lastname,
				project.strabo_project_id,
				project.project_name,
				dataset.strabo_dataset_id,
				dataset.dataset_name,
				ST_AsText(dataset.location) as dataset_wkt,
				ST_AsText(project.location) as project_wkt,
				count(distinct(spot.spot_pkey)) as spotcount
				from 
				users
				FULL OUTER JOIN project ON users.pkey = project.user_pkey
				FULL OUTER JOIN dataset on project.project_pkey = dataset.project_pkey
				FULL OUTER JOIN spot on dataset.dataset_pkey = spot.dataset_pkey
				FULL OUTER JOIN image on spot.spot_pkey = image.spot_pkey
				FULL OUTER JOIN sample on spot.spot_pkey = sample.spot_pkey
				FULL OUTER JOIN rock_type on spot.spot_pkey = rock_type.spot_pkey
				where
				(
					(project.ispublic = true or project.user_pkey = $userpkey)
				) and (
				1 = 1
				and spot.location is not null
				$searchrows
				)
				group by
				userpkey,
				firstname,
				lastname,
				strabo_project_id,
				project_name,
				strabo_dataset_id,
				dataset_name,
				dataset_wkt,
				project_wkt
				order by userpkey, strabo_project_id
				";



	//$db->dumpVar($query);exit();

	logToFile($query);

	//Now do query 
	$rows = $db->get_results($query);




	/*

	$db->dumpVar($rows);
	[userpkey] => 3
	[firstname] => Jason
	[lastname] => Ash
	[strabo_project_id] => 14756125769448
	[project_name] => Malpais Mesa
	[strabo_dataset_id] => 14756125771913
	[dataset_name] => Default
	[dataset_wkt] => POINT(-102.630200984133 49.5265392640636)
	[project_wkt] => POINT(-102.630200984133 49.5265392640636)
	[spotcount] => 16
	*/

	$allprojects = [];
	$currentprojectid = "";
	$projectcount=0;
	$datasetcount=0;
	$spotcount=0;

	foreach($rows as $row){
		$uniqid = $row->userpkey . "-" . $row->strabo_project_id;
		if($uniqid != $currentprojectid){
			if($currentprojectid!=""){
				$currentproject['datasets'] = $projectdatasets;
				$allprojects[]=$currentproject;
				unset($currentproject);
				unset($projectdatasets);
			}
			$currentprojectid = $uniqid;
			//echo "$currentprojectid<br>";
			$projectcount++;
		}
	
		$currentproject['owner_firstname'] = $row->firstname;
		$currentproject['owner_lastname'] = $row->lastname;
		$currentproject['owner_id'] = $row->userpkey;
		$currentproject['project_id'] = $row->strabo_project_id;
		$currentproject['project_name'] = $row->project_name;
		$currentproject['project_location'] = $row->project_wkt;

		unset($dataset);
	
		$dataset['owner_firstname'] = $row->firstname;
		$dataset['owner_lastname'] = $row->lastname;
		$dataset['owner_id'] = $row->userpkey;
		$dataset['dataset_id'] = $row->userpkey."-".$row->strabo_dataset_id;
	
		$dataset_name = $row->dataset_name;
		if($dataset_name==""){
			$dataset_name = "Default";
		}
		$dataset['dataset_name'] = $dataset_name;
	
		$dataset['dataset_location'] = $row->dataset_wkt;
		$dataset['spot_count'] = $row->spotcount;

		$projectdatasets[] = $dataset;
	
		$datasetcount++;
		$spotcount += $row->spotcount;
	}

	$currentproject['datasets'] = $projectdatasets;
	$allprojects[]=$currentproject;

	//$db->dumpVar($allprojects);exit();


	$out['counts']['projectcount']=$projectcount;
	$out['counts']['datasetcount']=$datasetcount;
	$out['counts']['spotcount']=$spotcount;

	$out['projects']=$allprojects;



	//Build GeoJSON for map
	/*
	{
		"type": "FeatureCollection",
		"features": [
			{
				"type": "Feature",
				"geometry": {
					"type": "Point",
					"coordinates": [
						-77.8993995,
						24.9266425
					]
				},
				"properties": {
					"name": "Reefs Day",
					"projectname": "Modern Carbonates Field Trip 2019",
					"id": 15762705796038,
					"count": 7,
					"owner": "Casey Duncan"
				}
			},

	$db->dumpVar($rows);
	[userpkey] => 3
	[firstname] => Jason
	[lastname] => Ash
	[strabo_project_id] => 14756125769448
	[project_name] => Malpais Mesa
	[strabo_dataset_id] => 14756125771913
	[dataset_name] => Default
	[dataset_wkt] => POINT(-102.630200984133 49.5265392640636)
	[project_wkt] => POINT(-102.630200984133 49.5265392640636)
	[spotcount] => 16

	*/

	$geojsonfeatures = [];

	foreach($rows as $row){
		unset($thisfeature);
		$thisfeature['type'] = "Feature";
	
		$points = str_replace("POINT(", "", $row->dataset_wkt);
		$points = str_replace(")", "", $points);
		$points = explode(" ", $points);
		$longitude = $points[0];
		$latitude = $points[1];
	
		$thisfeature['geometry']['type'] = "Point";
		$thisfeature['geometry']['coordinates'][0] = (float)$longitude;
		$thisfeature['geometry']['coordinates'][1] = (float)$latitude;
	
		$thisfeature['properties']['name'] = $row->dataset_name;
		$thisfeature['properties']['projectname'] = $row->project_name;
		$thisfeature['properties']['id'] = $row->userpkey."-".$row->strabo_dataset_id;
		$thisfeature['properties']['count'] = $row->spotcount;
		$thisfeature['properties']['owner'] = $row->firstname." ".$row->lastname;

		$geojsonfeatures[] = $thisfeature;

	}

	//$db->dumpVar($geojsonfeatures);exit();

	$geojsonfeaturecollection['type'] = "FeatureCollection";

	//$geojsonfeaturecollection['crs']['type'] = "name";
	//$geojsonfeaturecollection['crs']['properties']['name'] = "EPSG:4326";

	$geojsonfeaturecollection['features'] = $geojsonfeatures;




	/*
	  'crs': {
		'type': 'name',
		'properties': {
		  'name': 'EPSG:3857'
		}
	  },
	*/

	$out['geoJSON'] = $geojsonfeaturecollection;


	//$db->dumpVar($out);


	header('Content-Type: application/json');
	$out = json_encode($out, JSON_PRETTY_PRINT);
	echo $out;

}else{
	//do spot-level return
	
	$parts = explode("-", $dsids);
	
	//$db->dumpVar($parts);exit();
	
	$dsids = $parts[1];
	$thisuserpkey = $parts[0];

	$user = $db->get_row("select * from users where pkey = $thisuserpkey");
	$ownername = $user->firstname." ".$user->lastname;

	$spotsquery = "select
			spot.spotjson,
			users.pkey,
			dataset.strabo_dataset_id,
			st_astext(spot.location) as spotlocation
			from 
			users
			FULL OUTER JOIN project ON users.pkey = project.user_pkey
			FULL OUTER JOIN dataset on project.project_pkey = dataset.project_pkey
			FULL OUTER JOIN spot on dataset.dataset_pkey = spot.dataset_pkey
			FULL OUTER JOIN image on spot.spot_pkey = image.spot_pkey
			FULL OUTER JOIN sample on spot.spot_pkey = sample.spot_pkey
			FULL OUTER JOIN rock_type on spot.spot_pkey = rock_type.spot_pkey
			where
			(
				(project.ispublic = true or project.user_pkey = $userpkey)
			) and (
			project.user_pkey = $thisuserpkey and
			dataset.strabo_dataset_id = '$dsids'
			and spot.location is not null
			$searchrows
			)
			group by
			spot.spotjson,
			users.pkey,
			dataset.strabo_dataset_id,
			spotlocation
			order by users.pkey, strabo_dataset_id
			";

	
	//$db->dumpVar($spotsquery);exit();
	
	
	$spots = [];
	$imagebasemaps = [];
	$medianLongitudes = [];
	$medianLatitudes = [];
	
	$spotrows = $db->get_results($spotsquery);
	
	$spotcount = count($spotrows);
	
	$left = 999999;
	$right = -999999;
	$top = -999999;
	$bottom = 999999;
	
	//$db->dumpVar($spotrows);exit();
	
	foreach($spotrows as $sp){
	
		//$db->dumpVar($sp);exit();
	
		$thisspot = json_decode($sp->spotjson);
		
		$thisspot = (array)$thisspot;
		
		$properties = (array)$thisspot['properties'];
		//$db->dumpVar($properties);exit();
		
		$properties['datasetid'] = $thisuserpkey."-".$dsids;
		$properties['owner'] = $ownername;
		
		$thisspot['properties'] = (object) $properties;
		
		$thisspot = (object) $thisspot;
		
		//$db->dumpVar($thisspot);exit();
		
		$hasimagebasemap = "no";
		if($thisspot->properties->image_basemap){
			$imagebasemaps[] = $thisspot->properties->image_basemap;
			$hasimagebasemap = "yes";
		}

		$spotwkt = $sp->spotlocation;
		
		//echo "spotwkt: $spotwkt";exit();
		

		//$spotgeo = $geoPHP::load("POINT(-118.429104 37.418169)",'wkt');
		
		if($spotwkt!=""){

			//if not image basemap;
			if($hasimagebasemap=="no" && $thisspot->properties->strat_section_id == ""){
			//if($hasimagebasemap=="no"){
			
				$spotgeo = $geoPHP::load($spotwkt,'wkt');
			
				//$outenvelope = $spotgeo->envelope();
				$outenvelope = $spotgeo->asArray();
				$outenvelope = $spotgeo->out('json');
			
			
			
				$outenvelope = json_decode($outenvelope);
			
				//$neodb->dumpVar($outenvelope);
			
				$coords = $outenvelope->coordinates;
			
				if($outenvelope->type=="Point"){
			
					$lon = $coords[0];
					$lat = $coords[1];
					
					$medianLongitudes[] = $lon;
					$medianLatitudes[] = $lat;
				
					//echo "lon: $lon<br>";
					//echo "lat: $lat<br>";
				
					if($lon > $right) $right = $lon;
					if($lon < $left) $left = $lon;
					if($lat > $top) $top = $lat;
					if($lat < $bottom) $bottom = $lat;
			
				}elseif($outenvelope->type=="LineString"){
			
					foreach($coords as $coord){

						$lon = $coord[0];
						$lat = $coord[1];
						
						$medianLongitudes[] = $lon;
						$medianLatitudes[] = $lat;

						//echo "lon: $lon<br>";
						//echo "lat: $lat<br>";

						if($lon > $right) $right = $lon;
						if($lon < $left) $left = $lon;
						if($lat > $top) $top = $lat;
						if($lat < $bottom) $bottom = $lat;
					}
			
				}elseif($outenvelope->type=="Polygon"){

					foreach($coords as $outercoords){
				
						foreach($outercoords as $coord){

							$lon = $coord[0];
							$lat = $coord[1];
							
							$medianLongitudes[] = $lon;
							$medianLatitudes[] = $lat;

							//echo "lon: $lon<br>";
							//echo "lat: $lat<br>";

							if($lon > $right) $right = $lon;
							if($lon < $left) $left = $lon;
							if($lat > $top) $top = $lat;
							if($lat < $bottom) $bottom = $lat;
						}
					
					}

				}
			
			} //end if hasimagebasemap = no
			
			$spots[]=$thisspot;

		} // end if wkt











		
	}
	
	
	
	$datasetrow = $db->get_row("select * from dataset where strabo_dataset_id='$dsids' and user_pkey = $thisuserpkey limit 1");
	
	//$db->dumpVar($datasetrow);exit();
	
	//$db->dumpVar($medianLongitudes); $db->dumpVar($medianLatitudes); exit();
	$medianLongitude = (string) median($medianLongitudes);
	$medianLatitude = (string) median($medianLatitudes);
	
	//$db->dumpVar($medianLongitude); $db->dumpVar($medianLatitude); exit();
	
	//$db->dumpVar($datasetrow);
	//$db->dumpVar("spotcount: $spotcount");
	
	
	$out['type'] = "FeatureCollection";
	
	$dataset['name'] = $datasetrow->dataset_name;
	$dataset['id'] = $datasetrow->user_pkey."-".$datasetrow->strabo_dataset_id;
	$dataset['spotcount'] = $spotcount;
	
	
	$out['datasets'][] = $dataset;

	$out['features'] = $spots;
	
	if(count($imagebasemaps)>0){
		$out['image_basemaps'] = $imagebasemaps;
	}
	
	
	$out['tags'] = [];
	$out['relationships'] = [];
	
	$project_tags = $neodb->get_var("match (p:Project)-[HAS_DATASET]->(d:Dataset) where d.id=$dsids and d.userpkey = $thisuserpkey return p.json_tags;");
	
	//$db->dumpVar($project_tags);exit();

	if($project_tags!=""){
		$project_tags = json_decode($project_tags);
		$out['tags'] = $project_tags;
	}
	
	$project_relationships = $neodb->get_var("match (p:Project)-[HAS_DATASET]->(d:Dataset) where d.id=$dsids and d.userpkey = $thisuserpkey return p.json_relationships;");

	if($project_relationships!=""){
		$project_relationships = json_decode($project_relationships);
		$out['relationships'] = $project_relationships;
	}
	

	if($top!=-999999 && $bottom!=999999 && $right!=-999999 && $left!=999999){
		$out['envelope']="POLYGON (($left $bottom, $left $top, $right $top, $right $bottom, $left $bottom))";
		
		//Find middle of envelope to pass out too
		/*
		$envelope = $geoPHP::load("POLYGON (($left $bottom, $left $top, $right $top, $right $bottom, $left $bottom))",'wkt');
		$centroid = $envelope->centroid();
		$centroid = $centroid->out("wkt");
		$centroid = str_replace("POINT (","",$centroid);
		$centroid = str_replace(")","",$centroid);
		$parts = explode(" ", $centroid);
		$outCentroid['longitude'] = $parts[0];
		$outCentroid['latitude'] = $parts[1];
		$out['centroid'] = $outCentroid;
		*/
		
		//Return median of all vertices instead
		$outCentroid['longitude'] = $medianLongitude;
		$outCentroid['latitude'] = $medianLatitude;
		$out['centroid'] = $outCentroid;
		
	}else{
		echo "no samples found";exit();
	}

	//Also make midpoint of envelope here for better zooming
	
	

	//$db->dumpVar($out);









	header('Content-Type: application/json');
	$out = json_encode($out, JSON_PRETTY_PRINT);
	echo $out;






}




function median($arr){
    if($arr){
        $count = count($arr);
        sort($arr);
        $mid = floor(($count-1)/2);
        return ($arr[$mid]+$arr[$mid+1-$count%2])/2;
    }
    return 0;
}




?>