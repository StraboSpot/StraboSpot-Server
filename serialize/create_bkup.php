<?
	//tar -czf example_neo4j_data.tar.gz serialized/
	
	include("../prepare_connections.php");
	
	$strabo->settesting(true);
	
	$document_root = $_SERVER['DOCUMENT_ROOT'];

	$users = $db->get_results("select * from users where pkey < 10 and pkey != 8");
	
	foreach($users as $user){
		$userpkey = $user->pkey;
		$strabo->setuserpkey($userpkey);
		
		exec("rm -rf serialized/$userpkey");
		
		mkdir("serialized/$userpkey");
		mkdir("serialized/$userpkey/projects");




		//First, create files for user and profile
		$row = $neodb->getRecord("MATCH (u:User) WHERE u.userpkey = $userpkey optional match (u)-[r:HAS_PROFILE]->(p:Profile) RETURN u,p;");

		$u = $row->get("u")->values();
		$u = json_encode($u, JSON_PRETTY_PRINT);
		file_put_contents("serialized/$userpkey/user.json", $u);

		if($row->get("p")!=""){
			$p = $row->get("p")->values();
			$p = json_encode($p, JSON_PRETTY_PRINT);
			file_put_contents("serialized/$userpkey/profile.json", $p);
		}
		
		//Next, create project.json that has summary data about each project
		$myprojects = $strabo->getMyProjects();
		$myprojects = $myprojects['projects'];
		$myprojects_json = json_encode($myprojects, JSON_PRETTY_PRINT);
		file_put_contents("serialized/$userpkey/projects_summary.json", $myprojects_json);

		foreach($myprojects as $myproject){
			$myproject = (object) $myproject;
			$projectid = $myproject->id;
			
			mkdir("serialized/$userpkey/projects/$projectid");
			mkdir("serialized/$userpkey/projects/$projectid/datasets");
			
			
			//Next, create detailed project.json for project
			$project = $strabo->getProject($projectid);
			$project = json_encode($project, JSON_PRETTY_PRINT);
			file_put_contents("serialized/$userpkey/projects/$projectid/project.json", $project);
			
			//Next, get all datasets
			$datasets = $strabo->getProjectDatasets($projectid);
			$datasets = $datasets['datasets'];
			$datasets_json = json_encode($datasets, JSON_PRETTY_PRINT);
			file_put_contents("serialized/$userpkey/projects/$projectid/datasets.json", $datasets_json);

			foreach($datasets as $mydataset){
				$mydataset = (object) $mydataset;
				$datasetid = $mydataset->id;

				mkdir("serialized/$userpkey/projects/$projectid/datasets/$datasetid");
				
				$dataset = $strabo->getSingleDataset($datasetid);
				$dataset_json = json_encode($dataset, JSON_PRETTY_PRINT);
				file_put_contents("serialized/$userpkey/projects/$projectid/datasets/$datasetid/dataset.json", $dataset_json);
				
				//Also, get spots
				$spots = $strabo->getDatasetSpots($datasetid);
				$spots_json = json_encode($spots, JSON_PRETTY_PRINT);
				file_put_contents("serialized/$userpkey/projects/$projectid/datasets/$datasetid/spots.json", $spots_json);
				
			}

			//exit();
		
		}

		//exit();
		
	}








?>