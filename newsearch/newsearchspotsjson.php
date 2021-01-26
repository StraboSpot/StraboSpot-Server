<?
ob_start('ob_gzhandler');

include_once "../includes/config.inc.php"; //credentials, etc
include "../db.php"; //postgres database abstraction layer
include "../neodb.php"; //neo4j database abstraction layer
include "../db/strabospotclass.php"; //strabospot specific functions
include_once('../includes/geophp/geoPHP.inc'); //geospatial functions
include_once('../includes/UUID.php'); //UUID Class


$geoPHP = new geoPHP;










/*
$wkt = "GEOMETRYCOLLECTION(POINT(1 1),POINT(1 5),POINT(5 5),POINT(5 1),POINT(10 1))";
$collection = $geoPHP::load($wkt,'wkt');
$bufObj = $collection->buffer('0.1');

$outwkt = $bufObj->out('wkt');

echo $outwkt;

exit();

$poly1 = $geoPHP::load('POINT(0 0)','wkt');

$poly2 = $geoPHP::load('POINT(1 1)','wkt');

$combined_poly = $poly1->union($poly2);

$wkt = $combined_poly->out('wkt');

$poly1 = $geoPHP::load($wkt,'wkt');

$poly2 = $geoPHP::load('POINT(5 5)','wkt');

$combined_poly = $poly1->union($poly2);

$wkt = $combined_poly->out('wkt');

echo $wkt;

exit();



$geoPHP = new geoPHP;

$poly1 = $geoPHP::load('POLYGON((30 10,10 20,20 40,40 40,30 10))','wkt');

$poly2 = $geoPHP::load('POLYGON((35 10,10 20,15 40,45 45,35 10),(20 30, 35 35, 30 20, 20 30))','wkt');

$combined_poly = $poly1->union($poly2);

$kml = $combined_poly->out('kml');

echo "kml: $kml";exit();

*/





//Initialize StraboSpot class
$strabo = new StraboSpot($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$strabo->setuuid($uuid);

function logtofile($string,$label=""){

	if(is_writable("log.txt")){
		file_put_contents ("log.txt", "\n\n$label\n\n $string \n\n", FILE_APPEND);
		file_put_contents ("log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
	}

}

//if($_GET['env']){

	if($_GET['dsets']){
		$parts = explode("-", $_GET['dsets']);
		$userpkey = $parts[0];
		$datasetid = $parts[1];
		$querydsets="where d.id in [".$datasetid."] and u.userpkey = $userpkey";
		$dsets = explode(",", $_GET['dsets']);
	}
	
	//$neodb->dumpVar($dsets);exit();

	$offset=0; //.2

	$parts = explode(",",$_GET['env']);
	$left = $parts[0]-$offset;
	$top = $parts[1]+$offset;
	$right = $parts[2]+$offset;
	$bottom = $parts[3]-$offset;

	//echo "POLYGON(($left $top, $right $top, $right $bottom, $left $bottom, $left $top))"; exit();
	
	$envelope = $geoPHP::load("POLYGON(($left $top, $right $top, $right $bottom, $left $bottom, $left $top))",'wkt');

	$width = abs($left - $right);
	$height = abs($top - $bottom);

	logtofile("width: $width    height: $height");

	//if($width<500){ //1.5

		/*
		$string = "CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s
					match (p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
					return s";
		

		$string = "CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s
					match (p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
					with d as dataset, collect(s) as spots
					return dataset,spots";
		
		$string = "CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s
					match (p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
					with p as project, collect(distinct(d)) as dataset, collect(s) as spots
					return project,dataset,spots";

		where d.id in [14868247022025,14887490216612]
		
		


		$query = "CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s
					match (p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
					$querydsets
					optional match (s)-[:HAS_IMAGE]->(i)
					with p,d,s,collect(i) as i
					with p,d,{s:s,i:i} as s
					with p,d,collect(s) as s
					with p,{d:d,s:s} as d
					with p,collect(d) as d
					with {p:p,d:d} as p
					return p";
					
		*/

		//disable envelope for now
		$query = "match (u:User)-[HAS_PROJECT]->(p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
					$querydsets
					optional match (s)-[:HAS_IMAGE]->(i)
					with u,p,d,s,collect(i) as i
					with u,p,d,{s:s,i:i} as s
					with u,p,d,collect(s) as s
					with u,p,{d:d,s:s} as d
					with u,p,collect(d) as d
					with u,{p:p,d:d} as p
					with {u:u,p:p} as u
					return u";

		//$neodb->dumpVar($query);exit();
		
		logtofile($query);
	


		$string = $_GET['env'];

		//logtofile($string);

		
		/*
		$spots = $neodb->get_results("CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s
					match (p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
					return s");
		
	
		$rows = $neodb->get_results("CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s
					match (p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
					with d as dataset, collect(s) as spots
					return dataset,spots");
					
		

		$rows = $neodb->get_results("CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s
					match (p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
					with p as project, collect(distinct(d)) as dataset, collect(s) as spots
					return project,dataset,spots");
		*/

		$rows = $neodb->get_results("$query");



		//$neodb->dumpVar($rows);exit();
	
		/*
		$spots = $neodb->get_results("CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s
					match (p:Project {userpkey:[3,4]})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
					return s");
		*/
	
		//$spots = $neodb->get_results("CALL spatial.intersects('geom','POLYGON (($left $top, $right $top, $right $bottom, $left $bottom, $left $top))') YIELD node as s return s;");

		if(count($rows)>0){

			//$neodb->dumpVar($rows);exit();

			$features = array();
			$featurecollection=array();
			$featurecollection['type']="FeatureCollection";
			$datasets = array();
			$tags = array();
			$relationships = array();
	
			$image_basemaps=array();
	
			foreach($rows as $row){
		
				$userrow = $row->get("u");
				$user = (object)$userrow["u"]->values();
				
				$userstring = $user->firstname." ".$user->lastname;
				
				//$neodb->dumpVar($user);exit();
				
				//$neodb->dumpVar($userrow);exit();

				
				$projectrow = $userrow["p"];
				$project = (object)$projectrow["p"]->values();

				if($project->json_tags){
					$jsontags = json_decode($project->json_tags);
					foreach($jsontags as $tag){
						$tags[]=$tag;
					}
				}

				if($project->json_relationships){
					$jsonrelationships = json_decode($project->json_relationships);
					foreach($jsonrelationships as $relationship){
						$relationships[]=$relationship;
					}
				}
				
				
				//$neodb->dumpVar($projectrow);exit();
				
				unset($thisdataset);
				$thisdataset = array();
				$dbdatasets = $projectrow["d"];
				
				//$neodb->dumpVar($dbdatasets);
				
				
				//$dbdatasets = $dbdatasets->values();

				foreach($dbdatasets as $dataset){

					$datasetvals = $dataset["d"];
					
					//$neodb->dumpVar($dataset);exit();
					
					$datasetvals = (object)$datasetvals->values();

					$thisdataset['name']=$datasetvals->name;
					$thisdataset['id']=$userpkey."-".$datasetvals->id;

					//only show spots if  dataset is id is in $_GET['dsets'] or $_GET['dsets'] is not set (show all)
					if(1==1){ //show all for now?
					//if(!$dsets || in_array($datasetvals->id,$dsets)){
			
						$spots = $dataset["s"];
						
						//$neodb->dumpVar($spots);exit();
			
						$spotcount = 0;
				
						$left = 999999;
						$right = -999999;
						$top = -999999;
						$bottom = 999999;
						
						foreach($spots as $spot){

							//$neodb->dumpVar($spot);exit();
							
							$spotvals = $spot["s"];
							$spotvals = (object)$spotvals->values();
							$spotwkt = $spotvals->wkt;
							$spotstratid = $spotvals->strat_section_id;
							//$neodb->dumpVar($spotvals);
							
							//if($spotwkt!=""){
							if($spotstratid=="" && $spotwkt!=""){
							
								//$neodb->dumpVar($spotvals);exit();

								//$neodb->dumpVar($spotwkt);exit();
							
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

											//echo "lon: $lon<br>";
											//echo "lat: $lat<br>";

											if($lon > $right) $right = $lon;
											if($lon < $left) $left = $lon;
											if($lat > $top) $top = $lat;
											if($lat < $bottom) $bottom = $lat;
										}
										
									}

								}

								//check to see if spot in envelope
								//if($envelope->intersects($spotgeo)){

									//gather image stuff first
							
									unset($imagestuff);
									$imagestuff=array();
									$images=$spot["i"];
									if(count($images)>0){
										foreach($images as $i){
											$i = (object)$i->values();
											$imagestuff[]=$i;
										}
									}
							
							
							
							

							
									//$neodb->dumpVar($spotvals);//exit();

									if($spotvals->geometrytype!="wwwPoint"){
			
										if($spotvals->image_basemap==""){
				
											$spotvals = $strabo->singleSpotJSONFromFeatureData($spotvals,$imagestuff);
		
											$spotvals['properties']['datasetid']=$userpkey."-".$datasetvals->id;
											$spotvals['properties']['owner']=$userstring;

											$features[]=$spotvals;
							
											$spotcount++;
				
										}else{

											$image_basemaps[]=$spotvals->image_basemap;
									
											$spotvals = $strabo->singleSpotJSONFromFeatureData($spotvals,$imagestuff);
		
											$spotvals['properties']['datasetid']=$userpkey."-".$datasetvals->id;
											$spotvals['properties']['owner']=$userstring;

											$features[]=$spotvals;

										}
			
									}

								//}//end if spot in envelope
							
							}//end if wkt is not null

						}//end foreach spots

						/*
						echo "top: $top<br>";
						echo "bottom: $bottom<br>";
						echo "left: $left<br>";
						echo "right: $right";exit();
						*/
						
						if($top!=-999999 && $bottom!=999999 && $right!=-999999 && $left!=999999){
							$featurecollection['envelope']="POLYGON (($left $bottom, $left $top, $right $top, $right $bottom, $left $bottom))";
						}
			
					}else{ //still get spotcount

			
						$spots = $dataset["s"];
						
						//$neodb->dumpVar($spots);exit();
			
						$spotcount = 0;
				
						foreach($spots as $spot){
							
							$spotvals = $spot["s"];

							$spotvals = (object)$spotvals->values();
							
							//$neodb->dumpVar($spotvals);exit();

							if($spotvals->geometrytype!="wwwPoint"){
			
								if($spotvals->image_basemap==""){

									$spotcount++;
				
								}else{
									$image_basemaps[]=(int)$spotvals->image_basemap;
								}
			
							}
	
						}
					}
				}
				
			
				$thisdataset['spotcount']=$spotcount;
			
				$datasets[]=$thisdataset;
		
			}

			//exit();
			
			//$neodb->dumpVar($image_basemaps);
			
			$featurecollection['features']=$features;
			$featurecollection['datasets']=$datasets;
			$featurecollection['tags']=$tags;
			$featurecollection['relationships']=$relationships;
			$featurecollection['image_basemaps']=array_unique($image_basemaps);
		
			$json = json_encode($featurecollection,JSON_PRETTY_PRINT);



		}

	//}

//}

if($json==""){

	$features = array();
	$featurecollection['features']=$features;
	$json = json_encode($featurecollection,JSON_PRETTY_PRINT);

}


header('Content-Type: application/json');



echo $json;













































exit();













		if(count($rows)>0){
	
			$features = array();
			$featurecollection=array();
			$featurecollection['type']="FeatureCollection";
			$datasets = array();
			$tags = array();
			$relationships = array();
	
			foreach($rows as $row){
		
				$project = (object)$row->get("project")->values();
				
				if($project->json_tags){
					$jsontags = json_decode($project->json_tags);
					foreach($jsontags as $tag){
						$tags[]=$tag;
					}
				}

				if($project->json_relationships){
					$jsonrelationships = json_decode($project->json_relationships);
					foreach($jsonrelationships as $relationship){
						$relationships[]=$relationship;
					}
				}
				
				unset($thisdataset);
				$thisdataset = array();
				$dbdatasets = $row->get("dataset");
			
				foreach($dbdatasets as $dataset){

					$dataset = (object)$dataset->values();
				
					$thisdataset['name']=$dataset->name;
					$thisdataset['id']=$dataset->id;
			
			

					//only show spots if  dataset is id is in $_GET['dsets'] or $_GET['dsets'] is not set (show all)
					if(!$dsets || in_array($dataset->id,$dsets)){
			
						$spots = $row->get("spots");
			
						$spotcount = 0;
				
						foreach($spots as $spot){
	
							$spot = (object)$spot->values();

							if($spot->geometrytype!="wwwPoint"){
			
								if($spot->image_basemap==""){
				
									$spot = $strabo->singleSpotJSONFromFeatureData($spot);
		
									$spot['properties']['datasetid']=$userpkey."-".$dataset->id;

									$features[]=$spot;
							
									$spotcount++;
				
								}
			
							}
	
						}
			
					}else{ //still get spotcount

						$spots = $row->get("spots");
				
						foreach($spots as $spot){
	
							$spot = (object)$spot->values();

							if($spot->geometrytype!="wwwPoint"){
			
								if($spot->image_basemap==""){
							
									$spotcount++;
				
								}
			
							}
	
						}

					}
				
				}
			
				$thisdataset['spotcount']=$spotcount;
			
				$datasets[]=$thisdataset;
		
			}

			$featurecollection['features']=$features;
			$featurecollection['datasets']=$datasets;
			$featurecollection['tags']=$tags;
			$featurecollection['relationships']=$relationships;
		
			$json = json_encode($featurecollection,JSON_PRETTY_PRINT);



		}



if($json==""){

	$features = array();
	$featurecollection['features']=$features;
	$json = json_encode($featurecollection,JSON_PRETTY_PRINT);

}


header('Content-Type: application/json');

echo $json;













include_once "includes/config.inc.php"; //credentials, etc
include "db.php"; //postgres database abstraction layer
include "neodb.php"; //neo4j database abstraction layer
include "db/strabospotclass.php"; //strabospot specific functions
include_once('geophp/geoPHP.inc'); //geospatial functions
include_once('includes/UUID.php'); //UUID Class

//Initialize StraboSpot class
$strabo = new StraboSpot($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$strabo->setuuid($uuid);



if($_GET['env']){

	$offset=0.1; //.2

	$parts = explode(",",$_GET['env']);
	$left = $parts[0]-$offset;
	$top = $parts[1]+$offset;
	$right = $parts[2]+$offset;
	$bottom = $parts[3]-$offset;

	$width = abs($left - $right);
	$height = abs($top - $bottom);

	/*
	$string = "CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s
				match (p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
				return s";
	*/

	$string = "CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s
				match (p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
				with d as dataset, count(s) as datasetcount, collect(s) as spots
				return dataset,datasetcount,spots";


	logtofile($string);
	


	$string = $_GET['env'];

	logtofile($string);

	logtofile("width: $width    height: $height");
	
	/*
	$spots = $neodb->get_results("CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s
				match (p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
				return s");
	*/
	
	$rows = $neodb->get_results("CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s
				match (p:Project {public:1})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
				with d as dataset, count(s) as datasetcount, collect(s) as spots
				return dataset,datasetcount,spots");

	$neodb->dumpVar($rows);exit();
	
	/*
	$spots = $neodb->get_results("CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s
				match (p:Project {userpkey:[3,4]})-[pdr:HAS_DATASET]->(d)-[dsr:HAS_SPOT]->(s)
				return s");
	*/
	
	//$spots = $neodb->get_results("CALL spatial.intersects('geom','POLYGON (($left $top, $right $top, $right $bottom, $left $bottom, $left $top))') YIELD node as s return s;");

	if(count($spots)>0){
	
		$features = array();
		$featurecollection=array();
		$featurecollection['type']="FeatureCollection";
	
		foreach($spots as $spot){
	
			$spot = (object)$spot->get("s")->values();
		
			if($spot->geometrytype!="wwwPoint"){
			
				if($spot->image_basemap==""){
				
					$spot = $strabo->singleSpotJSONFromFeatureData($spot);
		
					$features[]=$spot;
				
				}
			
			}
	
		}
		
		$featurecollection['features']=$features;
		
		$json = json_encode($featurecollection,JSON_PRETTY_PRINT);

		header('Content-Type: application/json');
		echo $json;

	}



}
















































exit();




if(1==2){

	function random_float ($min,$max) {
		return ($min + lcg_value()*(abs($max - $min)));
	}

	function randomstring(){

		$letters = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
		for($x=0;$x<10;$x++){
			$outstring.=$letters[rand(0,51)];
		}
	
		return $outstring;
	}

		$parts = explode(",",$_GET['env']);
		$left = $parts[0];
		$top = $parts[1];
		$right = $parts[2];
		$bottom = $parts[3];

	$features = array();

	$features['type']="FeatureCollection";

	$features['features']=array();

	for($x=1;$x<=100;$x++){

		$longitude = random_float($left,$right);
		$latitude = random_float($bottom,$top);

		$label = randomstring();
		$id=rand(1111111111,9999999999);

		unset($f);
		$f=array();

		$f['type']='Feature';

		$f['geometry']['type']="Point";
		$f['geometry']['coordinates'][0]=$longitude;
		$f['geometry']['coordinates'][1]=$latitude;

		$f['properties']['name']=$label;
		$f['properties']['id']=$id;

		$features['features'][]=$f;

		//echo "long: $longitude lat: $latitude label: $label<br>";


	}

	$json = json_encode($features,JSON_PRETTY_PRINT);

	header('Content-Type: application/json');
	echo $json;
	exit();

}




include_once "includes/config.inc.php"; //credentials, etc
include "db.php"; //postgres database abstraction layer
include "neodb.php"; //neo4j database abstraction layer
include "db/strabospotclass.php"; //strabospot specific functions
include_once('geophp/geoPHP.inc'); //geospatial functions
include_once('includes/UUID.php'); //UUID Class

//Initialize StraboSpot class
$strabo = new StraboSpot($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$strabo->setuuid($uuid);


if($_GET['env']){

	$parts = explode(",",$_GET['env']);
	$left = $parts[0];
	$top = $parts[1];
	$right = $parts[2];
	$bottom = $parts[3];



	$string = "curl -u neo4j:JM@izbt0tc -H \"Content-Type: application/json\" -d '{\"minx\":\"$left\", \"maxx\":\"$right\", \"miny\":\"$bottom\", \"maxy\":\"$top\", \"layer\":\"geom\"}' http://localhost:7474/db/data/ext/SpatialPlugin/graphdb/findGeometriesIntersectingBBox";

	if(is_writable("test.txt")){
		if($label==""){$label="Spatial Query";}
		file_put_contents ("test.txt", "\n\n$label\n\n $string \n\n", FILE_APPEND);
		file_put_contents ("/var/www/db/test.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
	}

	$string = $_GET['env'];

	if(is_writable("test.txt")){
		if($label==""){$label="Spatial Query";}
		file_put_contents ("test.txt", "\n\n$label\n\n $string \n\n", FILE_APPEND);
		file_put_contents ("/var/www/db/test.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
	}



	$data = array("minx"=>"$left","maxx"=>"$right","miny"=>"$bottom","maxy"=>"$top","layer"=>"geom");
	$data_json = json_encode($data);

	$url="http://localhost:7474/db/data/ext/SpatialPlugin/graphdb/findGeometriesIntersectingBBox";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "neo4j:JM@izbt0tc");
	$response  = curl_exec($ch);
	curl_close($ch);

	$spots = json_decode($response);
	
	if(count($spots)>0){
	
		$features = array();
		$featurecollection=array();
		$featurecollection['type']="FeatureCollection";
	
		foreach($spots as $spot){
	
			$spot = $spot->data;
		
			if($spot->geometrytype!="wwwPoint"){
			
				if($spot->image_basemap==""){
				
					$spot = $strabo->singleSpotJSONFromFeatureData($spot);
		
					$features[]=$spot;
				
				}
			
			}
	
		}
		
		$featurecollection['features']=$features;
		
		$json = json_encode($featurecollection,JSON_PRETTY_PRINT);

		if(is_writable("test.txt")){
			if($label==""){$label="Spatial Query";}
			file_put_contents ("test.txt", "\n\n$label\n\n $string \n\n", FILE_APPEND);
			file_put_contents ("/var/www/db/test.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
		}

		header('Content-Type: application/json');
		echo $json;

	}


























	exit();




















include_once "includes/config.inc.php"; //credentials, etc
include "db.php"; //postgres database abstraction layer
include "neodb.php"; //neo4j database abstraction layer
include "db/strabospotclass.php"; //strabospot specific functions
include_once('geophp/geoPHP.inc'); //geospatial functions
include_once('includes/UUID.php'); //UUID Class

//Initialize StraboSpot class
$strabo = new StraboSpot($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$strabo->setuuid($uuid);


if($_GET['env']){

	$parts = explode(",",$_GET['env']);
	$left = $parts[0];
	$top = $parts[1];
	$right = $parts[2];
	$bottom = $parts[3];



	$string = "curl -u neo4j:JM@izbt0tc -H \"Content-Type: application/json\" -d '{\"minx\":\"$left\", \"maxx\":\"$right\", \"miny\":\"$bottom\", \"maxy\":\"$top\", \"layer\":\"geom\"}' http://localhost:7474/db/data/ext/SpatialPlugin/graphdb/findGeometriesIntersectingBBox";

	if(is_writable("test.txt")){
		if($label==""){$label="Spatial Query";}
		file_put_contents ("test.txt", "\n\n$label\n\n $string \n\n", FILE_APPEND);
		file_put_contents ("/var/www/db/test.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
	}

	$string = $_GET['env'];

	if(is_writable("test.txt")){
		if($label==""){$label="Spatial Query";}
		file_put_contents ("test.txt", "\n\n$label\n\n $string \n\n", FILE_APPEND);
		file_put_contents ("/var/www/db/test.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
	}



	$data = array("minx"=>"$left","maxx"=>"$right","miny"=>"$bottom","maxy"=>"$top","layer"=>"geom");
	$data_json = json_encode($data);

	$url="http://localhost:7474/db/data/ext/SpatialPlugin/graphdb/findGeometriesIntersectingBBox";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "neo4j:JM@izbt0tc");
	$response  = curl_exec($ch);
	curl_close($ch);

	$spots = json_decode($response);
	
	if(count($spots)>0){
	
		$features = array();
		$featurecollection=array();
		$featurecollection['type']="FeatureCollection";
	
		foreach($spots as $spot){
	
			$spot = $spot->data;
		
			if($spot->geometrytype=="Point"){
			
				$spot = $strabo->singleSpotJSONFromFeatureData($spot);
		
				$features[]=$spot;
			
			}
	
		}
		
		$featurecollection['features']=$features;
		
		$json = json_encode($featurecollection,JSON_PRETTY_PRINT);

		if(is_writable("test.txt")){
			if($label==""){$label="Spatial Query";}
			file_put_contents ("test.txt", "\n\n$label\n\n $string \n\n", FILE_APPEND);
			file_put_contents ("/var/www/db/test.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
		}

		header('Content-Type: application/json');
		echo $json;

	}


}




























	//echo "$left $top, $right $top, $right $bottom, $left $bottom, $left $top";exit();
	
	$string = "CALL spatial.intersects('geom','POLYGON (($left $top, $right $top, $right $bottom, $left $bottom, $left $top))') YIELD node as s return s;";
	
	//$string = "CALL spatial.bbox('geom',{$left , $bottom}, {$right , $top}) YIELD node as s return s;";
	$string = "CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s return s;";
	
	if(is_writable("test.txty")){
		if($label==""){$label="Spatial Query";}
		file_put_contents ("test.txt", "\n\n$label\n\n $string \n\n", FILE_APPEND);
		file_put_contents ("/var/www/db/test.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
	}
	
	
	$string = "CALL spatial.intersects('geom','POLYGON (($left $top, $right $top, $right $bottom, $left $bottom, $left $top))') YIELD node as s return s;";
	
	if(is_writable("test.txty")){
		if($label==""){$label="Spatial Query";}
		file_put_contents ("test.txt", "\n\n$label\n\n $string \n\n", FILE_APPEND);
		file_put_contents ("/var/www/db/test.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
	}


	//curl -u neo4j:JM@izbt0tc http://localhost:7474/db/data/ext/SpatialPlugin/graphdb/findGeometriesIntersectingBBox
	
	$string = "curl -u neo4j:JM@izbt0tc http://localhost:7474/db/data/ext/SpatialPlugin/graphdb/findGeometriesIntersectingBBox?minx=$left&maxx=$right&miny=$bottom&maxy=$top&layer=geom";
	
	
	$string = "curl -u neo4j:JM@izbt0tc -H \"Content-Type: application/json\" -d '{\"minx\":\"$left\", \"maxx\":\"$right\", \"miny\":\"$bottom\", \"maxy\":\"$top\", \"layer\":\"geom\"}' http://localhost:7474/db/data/ext/SpatialPlugin/graphdb/findGeometriesIntersectingBBox";

	if(is_writable("test.txty")){
		if($label==""){$label="Spatial Query";}
		file_put_contents ("test.txt", "\n\n$label\n\n $string \n\n", FILE_APPEND);
		file_put_contents ("/var/www/db/test.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
	}
	



}















exit();
include_once "includes/config.inc.php";
include "neodb.php"; //neo4j database abstraction layer


//$rows = $neodb->get_results("match (d:Dataset) with d, rand() as number return d order by number limit 50 ");
$rows = $neodb->get_results("match (d:Dataset) return d");



$features = array();

$features['type']="FeatureCollection";

$features['features']=array();

foreach($rows as $row){

	unset($f);
	$f=array();
	
	$f['type']='Feature';

	unset($centroid);
	
	$d=$row->get("d");
	$d=(object)$d->values();
	
	$centroid = $d->centroid;
	$id = $d->id;
	
	if($centroid){
	
		//$neodb->dumpVar($d);
		
		$centroid = str_replace("POINT (","",$centroid);
		$centroid = str_replace(")","",$centroid);

		$parts = explode(" ",$centroid);
		$longitude = (float)$parts[0];
		$latitude = (float)$parts[1];
	
		$label = $d->name;
		if($label=="")$label="No Name";
	
		$f['geometry']['type']="Point";
		$f['geometry']['coordinates'][0]=$longitude;
		$f['geometry']['coordinates'][1]=$latitude;

		$f['properties']['name']=$label;
		$f['properties']['id']=$id;
		
		$features['features'][]=$f;
	
		//echo "long: $longitude lat: $latitude label: $label<br>";
	
	}
}

$json = json_encode($features,JSON_PRETTY_PRINT);

header('Content-Type: application/json');
echo $json;

/*


if(is_writable("test.txtu")){
	$string = print_r($_GET,true);
	if($label==""){$label="Spot Search Query";}
	file_put_contents ("test.txt", "\n\n$label\n\n $string \n\n", FILE_APPEND);
	file_put_contents ("/var/www/db/test.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
	//file_put_contents ("/var/www/db/log.txt", $string, FILE_APPEND);
	//file_put_contents ("/var/www/db/log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
}


function random_float ($min,$max) {
    return ($min + lcg_value()*(abs($max - $min)));
}

function randomstring(){

	$letters = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
	for($x=0;$x<10;$x++){
		$outstring.=$letters[rand(0,51)];
	}
	
	return $outstring;
}

//-97.83106156320606,38.50035893502138,-97.76947804422413,38.47261177924224

//echo "random num: ".random_float(-97.83106156320606,-97.76947804422413);

//exit();


	$features = array();

	$features['type']="FeatureCollection";

	$features['features']=array();

	for($x=1;$x<=100;$x++){

		$longitude = random_float($left,$right);
		$latitude = random_float($bottom,$top);

		$label = randomstring();
		$id=rand(1111111111,9999999999);

		unset($f);
		$f=array();
	
		$f['type']='Feature';
	
		$f['geometry']['type']="Point";
		$f['geometry']['coordinates'][0]=$longitude;
		$f['geometry']['coordinates'][1]=$latitude;

		$f['properties']['name']=$label;
		$f['properties']['id']=$id;
	
		$features['features'][]=$f;

		//echo "long: $longitude lat: $latitude label: $label<br>";
	

	}

	$json = json_encode($features,JSON_PRETTY_PRINT);

	header('Content-Type: application/json');
	echo $json;

*/

?>