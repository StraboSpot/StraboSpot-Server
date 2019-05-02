<?php

/*
******************************************************************
StraboSpot REST API
Dataset Spots Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller adds/gets single spot from a given dataset.
******************************************************************
*/

class DatasetSingleSpotController extends MyController
{

    public function getAction($request) {
    	
		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

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

				$straboid=$upload['properties']->id;

				if($straboid!=""){
					
					//********************************************************************
					// Load the spot and add it to dataset
					//********************************************************************

					//delete relationships
					$this->strabo->deleteDatasetReltationships($feature_id);
					
					//fix single spot basemap coords

					$injson = json_encode($upload,JSON_PRETTY_PRINT);
					
					$thisdata = $this->strabo->insertSpot($injson);
					
					$parts = $thisdata->properties->self;

					$parts = explode("/",$parts);
					$straboid = end($parts);

					if(!$this->strabo->findSpotInDataset($feature_id,$straboid)){

						$this->strabo->addSpotToDataset($feature_id,$straboid);
						
						$totalspottime = microtime(true)-$spotstarttime; $this->strabo->logToFile("addspottodataset took: ".$totalspottime." secs","DATASET SPOT TIME");
					
					}

					$this->strabo->logToFile("Start building relationships...");
					$spotstarttime=microtime(true);
					
					//now build all relationships for project
					$this->strabo->buildDatasetRelationships($feature_id);
					
					$totalspottime = microtime(true)-$spotstarttime;
					$this->strabo->logToFile("Relationships done in $totalspottime seconds ...");
					
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
    	
		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

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
