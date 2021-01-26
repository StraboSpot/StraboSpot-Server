<?php

/*
******************************************************************
StraboSpot REST API
Dataset Spots Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller adds/gets spots from a given dataset.
******************************************************************
*/

class DatasetSpotsController extends MyController
{

    public function getAction($request) {

        if(isset($request->url_elements[2])) {
        
        	$feature_id = $request->url_elements[2];
        	
        	$data = $this->strabo->getDatasetSpots($feature_id);

        } else {
        	header("Bad Request", true, 400);
            $data["Error"] = "Bad Request. No Dataset id specified.";
        }
        return $data;
    }



    public function postAction($request) {
    
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
					foreach($features as $feature){

						$spotid = $feature->properties->id;
						
						if($this->strabo->spotExistsInOtherDataset($spotid,$feature_id)){
							header("Bad Request", true, 400);
							$data["Error"] = "Spot(s) already exist in another dataset.";
							break;
						}
					
					}
					
					if($data["Error"]==""){

						//delete relationships
						$this->strabo->deleteDatasetReltationships($feature_id);

						$data['type']="FeatureCollection";
					
						//this turns pixel coordinates into real-world coordinates so we can do spatial searches
						$features=$this->strabo->fixIncomingBasemaps($features);


						foreach($features as $feature){
					
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
