<?
session_start();

//$_SESSION[userpkey] => 3;

if($_SESSION['userpkey']!=""){
	$userpkey = $_SESSION['userpkey'];
}else{
	$userpkey = 0;
}

include("../includes/config.inc.php");
include("../db.php");


function logToFile($string){
	if(file_exists("log.txt")){
		file_put_contents ("log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
		file_put_contents ("log.txt", "\n".$string, FILE_APPEND);
	}
}



logToFile(file_get_contents("php://input"));





//change this to rawinput
//$json = file_get_contents("fulljson.json");
$json = file_get_contents("php://input");
$json = json_decode($json);
$json = $json->params;

//$db->dumpVar($json);exit();

$allitems = [];

foreach($json as $searchitem){
	$qual = $searchitem->qualifier;
	if($qual == "") $qual = "and";
	if($qual=="or"){
		$prequalifier = "OR";
		$notword = "";
		$notsymbol = "";
	}elseif($qual=="not"){
		$prequalifier = "AND";
		$notword = "NOT";
		$notsymbol = "!";
	}else{
		$prequalifier = "AND";
		$notword = "";
		$notsymbol = "";
	}
	
	$thisitem = " $prequalifier (";
	
	$constraints = $searchitem->constraints;
	
	//constraintValue
	
	//Year Min / Year Max
	if($constraints[0]->constraintType == "minYear" || $constraints[0]->constraintType == "minYear"){
		if($constraints[0]->constraintType == "minYear"){
			$minyear = $constraints[0]->constraintValue;
			if($constraints[1]->constraintType == "maxYear"){
				$maxyear = $constraints[1]->constraintValue;
			}else{
				$maxyear = 9999;
			}
		}elseif($constraints[0]->constraintType == "maxYear"){
			$minyear = 0;
			$maxyear = $constraints[0]->constraintValue;
		}
		
		$thisitem .= "spot.date_created $notword BETWEEN '".$minyear."-1-1' AND '".$maxyear."-12-31'";
		
	}
	
	//Image Type
	if($constraints[0]->constraintType == "imageType" ){
		if($constraints[0]->constraintValue != ""){
			$imagetype = $constraints[0]->constraintValue;
			
			$thisitem .= "image.image_type ". $notsymbol ."= '$imagetype'";
		}
	}
	
	//Keyword
	if($constraints[0]->constraintType == "keyword" ){
		if($constraints[0]->constraintValue != ""){
			$thiskeyword = "";
			$newkeywords = [];
			$keyword = $constraints[0]->constraintValue;
			$keyword = trim($keyword);
			$keyword = explode(" ", $keyword);
			foreach($keyword as $k){
				$newkeywords[] = $notsymbol.$k;
			}
			
			//$db->dumpVar($newkeywords);
			
			$newkeywords = implode(" & ", $newkeywords);
			
			//WHERE keywords @@ to_tsquery('!rock & !bar');
			
			$thisitem .= "spot.keywords @@ to_tsquery('$newkeywords')";
		}else{
			$thisitem .= "1 = 2";
		}
	}
	
	//Microstructures
	if($constraints[0]->constraintType == "microstructureExists" ){
		if($notword=="NOT"){
			$lookbool = "FALSE";
		}else{
			$lookbool = "TRUE";
		}
		$thisitem .= "spot.micro_exists = $lookbool";
	}

	//Orientation
	if($constraints[0]->constraintType == "orientationExists" ){
		if($notword=="NOT"){
			$lookbool = "FALSE";
		}else{
			$lookbool = "TRUE";
		}
		$thisitem .= "spot.orientation_exists = $lookbool";
	}
	
	//Owner
	if($constraints[0]->constraintType == "owner" ){
		$ownerpkey = $constraints[0]->constraintValue;
		$thisitem .= "users.pkey ".$notsymbol."= $ownerpkey";
		$userpkey=0;
	}

	//Sample
	if($constraints[0]->constraintType == "sampleExists" ){
		if($notword=="NOT"){
			$lookbool = "FALSE";
		}else{
			$lookbool = "TRUE";
		}
		$thisitem .= "spot.sample_exists = $lookbool";
	}

	//Sample ID
	if($constraints[0]->constraintType == "sampleID" ){
		$sampleid = strtolower($constraints[0]->constraintValue);
		$thisitem .= "lower(sample.sample_id) ".$notsymbol."= '$sampleid'";
		$userpkey=0;
	}

	//Strat Column Exists
	if($constraints[0]->constraintType == "stratColumnExists" ){
		if($notword=="NOT"){
			$lookbool = "FALSE";
		}else{
			$lookbool = "TRUE";
		}
		$thisitem .= "spot.strat_exists = $lookbool";
	}
	
	$thisitem .= " )";
	$allitems[] = $thisitem;
}


$searchrows = implode("\n", $allitems);


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
			count(spot.spot_pkey) as spotcount
			from 
			users
			FULL OUTER JOIN project ON users.pkey = project.user_pkey
			FULL OUTER JOIN dataset on project.project_pkey = dataset.project_pkey
			FULL OUTER JOIN spot on dataset.dataset_pkey = spot.dataset_pkey
			FULL OUTER JOIN image on spot.spot_pkey = image.spot_pkey
			FULL OUTER JOIN sample on spot.spot_pkey = sample.spot_pkey
			where
			(
				(project.ispublic = true or project.user_pkey = $userpkey)
			) and (
			1 = 1
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
	$dataset['dataset_id'] = $row->strabo_dataset_id;
	
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
	$thisfeature['properties']['id'] = $row->strabo_dataset_id;
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











?>