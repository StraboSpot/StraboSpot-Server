<?php
/**
 * File: MoveSpotToDatasetController.php
 * Description: MoveSpotToDatasetController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class MoveSpotToDatasetController extends MyController
{

	public function getAction($request) {
		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function postAction($request) {
		$upload = $request->parameters;
		unset($upload['apiformat']);

		$spotid = $upload['spot_id'];
		if($spotid == ""){
			//Error, spot id not provided
			header("Bad Request", true, 404);
			$data["Error"] = "Spot id not provided.";
			return $data;
		}

		$datasetid = $upload['dataset_id'];
		if($datasetid == ""){
			//Error, dataset id not provided
			header("Bad Request", true, 404);
			$data["Error"] = "Dataset id not provided.";
			return $data;
		}

		$modified_timestamp = $upload['modified_timestamp'];
		if($modified_timestamp == ""){
			//Error, modified timestamp not provided
			header("Bad Request", true, 404);
			$data["Error"] = "Modified timestamp not provided.";
			return $data;
		}

		//First, check for spot
		if($this->strabo->findSpot($spotid)){

			if($this->strabo->findDataset($datasetid)){

				//Find original dataset
				$originaldatasetid = $this->strabo->getDatasetId($spotid);
				$originalprojectid = $this->strabo->getProjectId($originaldatasetid);
				$projectid = $this->strabo->getProjectId($datasetid);

				

				//remove
				$this->strabo->removeSpotFromDataset($spotid);

				//add
				$this->strabo->addSpotToDataset($datasetid,$spotid);

				//Update all modified_timestamps
				$userpkey = $this->strabo->userpkey;

				//Original Project
				$this->strabo->neodb->query("match (p:Project) where p.id = $originalprojectid and p.userpkey = $userpkey set p.modified_timestamp = $modified_timestamp");

				//Original Dataset
				$this->strabo->neodb->query("match (d:Dataset) where d.id = $originaldatasetid and d.userpkey = $userpkey set d.modified_timestamp = $modified_timestamp");

				//New Project
				$this->strabo->neodb->query("match (p:Project) where p.id = $projectid and p.userpkey = $userpkey set p.modified_timestamp = $modified_timestamp");

				//New Dataset
				$this->strabo->neodb->query("match (d:Dataset) where d.id = $datasetid and d.userpkey = $userpkey set d.modified_timestamp = $modified_timestamp");

				//Spot
				$this->strabo->neodb->query("match (s:Spot) where s.id = $spotid and s.userpkey = $userpkey set s.modified_timestamp = $modified_timestamp");

				//now build all relationships for datasets
				$this->strabo->buildDatasetRelationships($datasetid);
				$this->strabo->buildDatasetRelationships($originaldatasetid);

				$this->strabo->setDatasetCenter($datasetid);
				$this->strabo->setDatasetCenter($originaldatasetid);

				$this->strabo->setProjectCenter($originalprojectid);
				$this->strabo->setProjectCenter($projectid);

				//also add dataset to Postgres Database here.
				$this->strabo->buildPgDataset($originaldatasetid); //need to re-implement JMA 02282020
				$this->strabo->buildPgDataset($datasetid);

				header("Success", true, 201);
				$data['message'] = "Spot $spotid moved to dataset $datasetid.";

			}else{
				//Error, feature not found
				header("Bad Request", true, 404);
				$data["Error"] = "Dataset $datasetid not found.";
			}
		}else{
			//Error, feature not found
			header("Bad Request", true, 404);
			$data["Error"] = "Spot $spotid not found.";
		}

		return $data;
	}

	public function bkuppostAction($request) {

		if(isset($request->url_elements[2])) {

			//*******************************************************************************
			//update attributes for feature

			$feature_id = $request->url_elements[2];

			//********************************************************************
			// check for Dataset with userid and id
			//********************************************************************
			if($this->strabo->findDataset($feature_id)){

				$upload = $request->parameters;

				unset($upload['apiformat']);

				$straboid=$upload['id'];

				$features = $upload['features'];

				if($straboid!=""){

					//OK, check to see if feature exists
					if($this->strabo->findSpot($straboid)){

						//see if it already exists in dataset
						if(!$this->strabo->findSpotInDataset($feature_id,$straboid)){

							//********************************************************************
							// Add to group
							//********************************************************************
							$this->strabo->addSpotToDataset($feature_id,$straboid);

							header("Spot added to dataset", true, 201);
							$data['message']="Spot $straboid added to dataset $feature_id.";

						}else{

							//Error, feature not found
							header("Spot $straboid already exists in dataset $feature_id.", true, 200);
							$data["Error"] = "Spot $straboid already exists in dataset $feature_id.";

						}

					}else{

						//Error, feature not found
						header("Bad Request", true, 404);
						$data["Error"] = "Spot $straboid not found.";

					}

				}elseif(count($features)>0){

					//********************************************************************
					// Load the feature collection and add it to dataset
					//********************************************************************

					//check to see if any spots belong to different dataset
					/*
					foreach($features as $feature){

						$spotid = $feature->properties->id;

						if($this->strabo->spotExistsInOtherDataset($spotid,$feature_id)){

							//also get name
							$spotname = $this->strabo->getSpotName($spotid);

							header("Bad Request", true, 400);
							$data["Error"] = "Spot(s) already exist in another dataset: $spotname ($spotid).";
							break;
						}

					}
					*/

					if($data["Error"]==""){

						//delete relationships
						$this->strabo->deleteDatasetReltationships($feature_id);

						$data['type']="FeatureCollection";

						//this turns pixel coordinates into real-world coordinates so we can do spatial searches
						$features=$this->strabo->fixIncomingBasemaps($features);

						foreach($features as $feature){

							$spotid = $feature->properties->id;

							$this->strabo->deleteSingleSpot($spotid);

							if(!$this->strabo->spotExistsInOtherDataset($spotid,$feature_id)){

								$spotstarttime = microtime(true);

								$injson = json_encode($feature,JSON_PRETTY_PRINT);

								$thisdata = $this->strabo->insertSpot($injson);

								$parts = $thisdata->properties->self;

								$parts = explode("/",$parts);
								$straboid = end($parts);

								if(!$this->strabo->findSpotInDataset($feature_id,$straboid)){

									$this->strabo->addSpotToDataset($feature_id,$straboid);

									$totalspottime = microtime(true)-$spotstarttime; $this->strabo->logToFile("addspottodataset took: ".$totalspottime." secs","DATASET SPOT TIME");

								}

								$incomingspots[]=$feature->properties->id;

								$data['features'][]=$thisdata;

							}

						}

						//now look on server to see if any spots need to be deleted
						$serverspots = $this->strabo->getDatasetSpotIds($feature_id);
						foreach($serverspots as $ss){
							if(!in_array($ss,$incomingspots)){
								$this->strabo->deleteSingleSpot($ss);
							}
						}

						$this->strabo->logToFile("Start building relationships...");
						$spotstarttime=microtime(true);

						//now build all relationships for project
						$this->strabo->buildDatasetRelationships($feature_id);

						$this->strabo->setDatasetCenter($feature_id);

						$projectid = $this->strabo->getProjectId($feature_id);
						$this->strabo->setProjectCenter($projectid);

						$totalspottime = microtime(true)-$spotstarttime;
						$this->strabo->logToFile("Relationships done in $totalspottime seconds ...");

						//also add dataset to Postgres Database here.
						$this->strabo->buildPgDataset($feature_id); //need to re-implement JMA 02282020
					}

				}else{

					// bad body sent, error
					header("Bad Request", true, 400);
					$data["Error"] = "Invalid body JSON sent.";

				}

			}else{
				//Error, feature not found
				header("Bad Request", true, 404);
				$data["Error"] = "Dataset $feature_id not found.";
			}

		} else { //feature id is not set error

			//Error, feature not found
			header("Bad Request", true, 404);
			$data["Error"] = "No dataset ID provided.";

		}

		return $data;
	}

	public function deleteAction($request) {

		if(isset($request->url_elements[2])) {

			$feature_id = $request->url_elements[2];

			$this->strabo->deleteDatasetSpots($feature_id);

			header("Spots deleted", true, 204);
			$data['message']="Spots deleted.";

		} else {
			header("Bad Request", true, 400);
			$data["Error"] = "Bad Request.";
		}
		return $data;

		return $data;
	}

	public function putAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function optionsAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function patchAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function copyAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function searchAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

}
