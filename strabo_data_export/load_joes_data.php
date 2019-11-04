<?PHP
/*
Load data created from export_data.php script
Warning! This script is destructive. It destroys
the neo4j database and repopulates with data
in the "exported_data" folder.

CALL spatial.addWKTLayer('geom','wkt')

match (n) detach delete n;

*/

exit();

$spotcount=0;

$starttime=time();

include_once("../includes/config.inc.php");
include_once('../geophp/geoPHP.inc');
include("../db.php");
include("../neodb.php");
include("../db/strabospotclass.php");
$strabo = new StraboSpot($neodb,$userpkey,$db);



$users=file_get_contents("exported_data/users.json");
$users=json_decode($users);

foreach($users as $user){

	if($user->pkey == 8){//  load only Joe's data
	
		echo "Loading $user->firstname $user->lastname ($user->pkey)";

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

						if(!$strabo->findSpot($spotid)){
						
							$spotjson=json_encode($spot);
							$thisdata = $strabo->insertSpot($spotjson);
							
							$strabo->addSpotToDataset($datasetid,$spotid);
						
						}
						
						$spotcount++;
						
						if($spotcount % 1000 == 0){
							echo "$spotcount done...\n";
						}
						
					}
				}
				
			}
			
			$totalcount++;
			
			//build relationships
			$strabo->buildProjectRelationships($projectid);
		
		}
		
	}
	
	echo "...\n";
}

$timetook = time()-$starttime;

$persamp = $timetook/$spotcount;

echo "$totalcount spots loaded in $timetook seconds. ($persamp per spot)\n";


?>