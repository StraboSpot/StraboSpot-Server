<?php
/**
 * File: move_dataset.php
 * Description: Moves datasets between projects or locations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

include("prepare_connections.php");

$projectid=$_GET['pid'];
$datasetid=$_GET['did'];

$original_project_id = $neodb->get_var("match (a:Project)-[b:HAS_DATASET]->(c:Dataset) where a.userpkey=$userpkey and c.id=$datasetid return a.id");

$original_project = $strabo->getProject($original_project_id);

$target_project = $strabo->getProject($projectid);

//Gather dataset spots so we can determine which tags to migrate with dataset
$datasetspots = (object)$strabo->getDatasetSpots($datasetid);

$spots = [];
foreach($datasetspots->features as $sp){
	$spots[] = $sp['properties']['id'];
}

$movetags = [];
foreach($original_project->tags as $otag){
	$movetag = false;
	foreach($otag->spots as $ospot){
		if(in_array($ospot, $spots)){
			$movetag = true;
		}
	}

	if($movetag){
		$movetags[] = $otag;
	}
}

//Now we know what tags to move over. Let's gather a list of tags from the destination to
//see what needs to be created and what needs to be combined
$target_tags = $target_project->tags;

$target_tag_ids = [];
foreach($target_tags as $tt){
	$target_tag_ids[] = $tt->id;
}

foreach($movetags as $movetag){
	if(in_array($movetag->id, $target_tag_ids)){
		//Combine spots
		foreach($target_tags as $tt){
			if($tt->id == $movetag->id){
				$tspotids = $tt->spots;
				foreach($movetag->spots as $mspotid){
					if(!in_array($mspotid,$tspotids)){
						$tt->spots[] = $mspotid;
					}
				}
			}
		}
	}else{
		//Just copy over
		$target_tags[] = $movetag;
	}
}

//Now move postgres dataset to target project and update tags...
$dataset_pkey = $db->get_var_prepared("SELECT dataset_pkey FROM dataset WHERE user_pkey=$1 AND strabo_dataset_id = $2", array($userpkey, $datasetid));

$target_project_pkey = $db->get_var_prepared("SELECT project_pkey FROM project WHERE user_pkey=$1 AND strabo_project_id = $2", array($userpkey, $projectid));

//Move Postgres Project
$db->prepare_query("UPDATE dataset SET project_pkey = $1 WHERE dataset_pkey = $2", array($target_project_pkey, $dataset_pkey));

//Move Neo4j Dataset

$intprojectid=$strabo->straboIDToID($projectid,"Project");
$intdatasetid=$strabo->straboIDToID($datasetid,"Dataset");

$neodb->query("match (a:Project)-[b:HAS_DATASET]->(c:Dataset) where a.userpkey=$userpkey and id(c)=$intdatasetid delete b");

$neodb->addRelationship($intprojectid, $intdatasetid, "HAS_DATASET");

//Tags
//Get tags from original project that reference the dataset
//Get tags from destination project and add if needed

$json_target_tags = addslashes(json_encode($target_tags, JSON_PRETTY_PRINT));

$neodb->query("match (a:Project) where a.userpkey=$userpkey and id(a)=$intprojectid set a.json_tags = '$json_target_tags' return a");

header("Location:my_field_data");

?>