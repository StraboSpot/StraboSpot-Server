<?PHP
//export image json
exit();
include_once("../includes/config.inc.php");
include_once('../geophp/geoPHP.inc');
include("../db.php");
include("../neodb.php");
include("../db/strabospotclass.php");
$strabo = new StraboSpot($neodb,$userpkey,$db);

function dprint($myvar){
	echo nl2br(print_r($myvar,true));
}



//$results = $neodb->query("match (i:Image) return i;");


exec("rm -rf exported_data");

exec("mkdir exported_data");

$rows = $db->get_results("select * from users");

//$neodb->dumpVar($rows);

$usersjson = json_encode($rows,JSON_PRETTY_PRINT);

file_put_contents("exported_data/users.json",$usersjson);

foreach($rows as $row){

	$userpkey = $row->pkey;
	$username = $row->firstname." ".$row->lastname;
	
	echo "starting $username...";
	
	$strabo->setuserpkey($userpkey);
	
	exec("mkdir exported_data/$userpkey");
	
	$myprojects = $strabo->getMyProjects();
	$myprojects = $myprojects["projects"];
	
	//$neodb->dumpVar($myprojects);exit();
	
	if(count($myprojects) > 0){

		unset($allprojects);
		$allprojects = array();
		foreach($myprojects as $myproject){
			$projectid = $myproject["id"];
			$thiswholeproject = $strabo->getProject($projectid);
			$allprojects[]=$thiswholeproject;
			
			exec("mkdir exported_data/$userpkey/$projectid");
			
			//now datasets
			unset($alldatasets);
			$alldatasets = array();
			$datasets=$strabo->getProjectDatasets($projectid);
			
			if(count($datasets)>0){
			
				$datasets=$datasets["datasets"];
				foreach($datasets as $dataset){
					$datasetid = $dataset["id"];
					$thiswholedataset = $strabo->getSingleDataset($datasetid);
					$alldatasets[]=$thiswholedataset;
				
					//exec("mkdir exported_data/$userpkey/$projectid/$datasetid");
				
					//now spots
					$spots = $strabo->getDatasetSpots($datasetid);
					if($spots!=""){
						$spots = json_encode($spots,JSON_PRETTY_PRINT);
						file_put_contents("exported_data/$userpkey/$projectid/$datasetid.json",$spots);
					}
				}
			
			}
			
			$alldatasetsjson=json_encode($alldatasets,JSON_PRETTY_PRINT);
			file_put_contents("exported_data/$userpkey/$projectid/datasets.json",$alldatasetsjson);
			
			
		}

		$allprojectsjson=json_encode($allprojects,JSON_PRETTY_PRINT);
		file_put_contents("exported_data/$userpkey/projects.json",$allprojectsjson);

	}
	
	echo "done!\n";//exit();
}

?>