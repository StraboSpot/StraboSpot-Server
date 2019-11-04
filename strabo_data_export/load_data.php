<?PHP

exit();

/*
Load data created from export_data.php script
Warning! This script is destructive. It destroys
the neo4j database and repopulates with data
in the "exported_data" folder.

CALL spatial.addWKTLayer('geom','wkt')

match (n) detach delete n;

*/

include_once("../includes/config.inc.php");
include_once('../geophp/geoPHP.inc');
include("../db.php");
include("../neodb.php");
include("../db/strabospotclass.php");
$strabo = new StraboSpot($neodb,$userpkey,$db);

echo "Clearing the database";

//first, destroy all nodes and relationships:
$neodb->query("match (n) detach delete n;");

echo "...\n";

echo "Creating indexes";

$neodb->query("CREATE INDEX ON :Dataset(id)");
$neodb->query("CREATE INDEX ON :Image(id)");
$neodb->query("CREATE INDEX ON :Orientation(id)");
$neodb->query("CREATE INDEX ON :Project(id)");
$neodb->query("CREATE INDEX ON :RockUnit(id)");
$neodb->query("CREATE INDEX ON :Sample(id)");
$neodb->query("CREATE INDEX ON :Spot(id)");
$neodb->query("CREATE INDEX ON :Tag(id)");
$neodb->query("CREATE INDEX ON :Trace(id)");
$neodb->query("CREATE INDEX ON :User(userpkey)");
$neodb->query("CREATE INDEX ON :_3DStructure(id)");
$neodb->query("CREATE INDEX ON :Dataset(userpkey)");
$neodb->query("CREATE INDEX ON :Image(userpkey)");
$neodb->query("CREATE INDEX ON :Orientation(userpkey)");
$neodb->query("CREATE INDEX ON :Project(userpkey)");
$neodb->query("CREATE INDEX ON :RockUnit(userpkey)");
$neodb->query("CREATE INDEX ON :Sample(userpkey)");
$neodb->query("CREATE INDEX ON :Spot(userpkey)");
$neodb->query("CREATE INDEX ON :Tag(userpkey)");
$neodb->query("CREATE INDEX ON :Trace(userpkey)");

echo "...\n";

//now, add the spatial layer
//$neodb->query("CALL spatial.addWKTLayer('geom','wkt')");

echo "Loading images";

//first, load images so they are ready for later
$images = file_get_contents("images.json");
$images = json_decode($images);
$images = $images->images;
foreach($images as $image){
	unset($thisimage);
	$thisimage=array();
	foreach($image as $key=>$value){
		if($value){
			$thisimage[$key]=$value;
		}
	}
	$thisimage=json_encode($thisimage);
	$neodb->createNode($thisimage,"Image");
}

echo "...\n";

//exit();

$users=file_get_contents("exported_data/users.json");
$users=json_decode($users);

foreach($users as $user){

	echo "Loading $user->firstname $user->lastname ($user->pkey)";
	
	if($user->pkey != 8){//  don't load Joe's data, it's just too much.
	//if($user->pkey == 3){//  load only Jason's data for a test.
		unset($thisuser);
		$thisuser = array();

		$userpkey = (int)$user->pkey;
		
		$strabo->setuserpkey($userpkey);

		$thisuser["userpkey"]=(int)$userpkey;
		$thisuser["firstname"]=$user->firstname;
		$thisuser["lastname"]=$user->lastname;
		$thisuser["email"]=$user->email;
		if($user->profileimage){
			$thisuser["profileimage"]=$user->profileimage;
		}

		$thisuser = json_encode($thisuser);

		$userid = $neodb->createNode($thisuser,"User");
		
		$projects = file_get_contents("exported_data/$userpkey/projects.json");
		$projects = json_decode($projects);
		
		foreach($projects as $project){
		
			$projectid=$project->id;
			
			//echo "\nprojectid: $projectid ";

			$projectjson = json_encode($project);
			
			$pid = $strabo->insertProject($projectjson);
			
			$datasets = file_get_contents("exported_data/$userpkey/$projectid/datasets.json");
			$datasets = json_decode($datasets);
			
			foreach($datasets as $dataset){
			
			
				$datasetid = $dataset->id;
				
				//echo "datasetid: $datasetid";

				$dataset=json_encode($dataset);
				$dobject = $strabo->insertDataset($dataset);
				$strabo->addDatasetToProject($projectid,$datasetid,"HAS_DATASET");

				if(file_exists("exported_data/$userpkey/$projectid/$datasetid.json")){
					$spots = file_get_contents("exported_data/$userpkey/$projectid/$datasetid.json");
					$spots = json_decode($spots);
					$spots = $spots->features;
					$spots=$strabo->fixIncomingBasemaps($spots);

					foreach($spots as $spot){

						$spotid = $spot->properties->id;

						$spotjson=json_encode($spot);
						$thisdata = $strabo->insertSpot($spotjson);
						$strabo->addSpotToDataset($datasetid,$spotid);
					}
				}
				
			}
			
			//build relationships
			$strabo->buildProjectRelationships($projectid);
		
		}
		
	}
	
	echo "...\n";
}

















/*
bkup:

$spots = file_get_contents("test.json");
$spots = json_decode($spots);
$spots=$strabo->fixIncomingBasemaps($spots);

$neodb->dumpVar($spots);

exit();


*/




?>