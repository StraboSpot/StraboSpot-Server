<?PHP

exit();

include_once("../includes/config.inc.php");
include_once('../geophp/geoPHP.inc');
include("../db.php");
include("../neodb.php");
include("../db/strabospotclass.php");
$strabo = new StraboSpot($neodb,$userpkey,$db);



$profiles = file_get_contents("profiles.json");
$profiles = json_decode($profiles);
$profiles = $profiles->profiles;
foreach($profiles as $profile){
	unset($thisprofile);
	$thisprofile=array();
	foreach($profile as $key=>$value){
		if($value){
			$thisprofile[$key]=$value;
		}
	}
	
	$userpkey=$thisprofile["userpkey"];
	$strabo->setuserpkey($userpkey);
	
	echo "$userpkey\n";
	
	$thisprofile=json_encode($thisprofile);
	
	$strabo->insertProfile($thisprofile);
	
}

























/*


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


















bkup:

$spots = file_get_contents("test.json");
$spots = json_decode($spots);
$spots=$strabo->fixIncomingBasemaps($spots);

$neodb->dumpVar($spots);

exit();


*/




?>