<?php

/*
******************************************************************
StraboSpot REST API
Dataset Spots ARC Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller returns spots for a given dataset
				that contain real-world coordinates. Also, there
				is an optional feature type parameter that limits
				returned features to a certain type.
******************************************************************
*/


class DatasetSpotsArcController extends MyController
{

    public function getAction($request) {

        if(isset($request->url_elements[2])) {
        
        	$feature_id = $request->url_elements[2];
        	$ingtype = strtolower($request->url_elements[3]);
        	
        	$data = $this->strabo->getDatasetSpotsArc($feature_id,$ingtype);

        } else {
        	header("Bad Request", true, 400);
            $data["Error"] = "Bad Request. No Dataset id specified.";
        }
        return $data;
    }



    public function postAction($request) {
    	
		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

        return $data;
    }


    public function oldpostAction($request) {
    
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
			
				if($straboid==""){

					// bad body sent, error
					header("Bad Request", true, 400);
					$data["Error"] = "Invalid body JSON sent.";

				}else{

				
					//OK, check to see if feature exists
					if($this->strabo->findSpot($straboid)){
					
						//see if it already exists in group
						if(!$this->strabo->findSpotInDataset($feature_id,$straboid)){

							//********************************************************************
							// Add to group 
							//********************************************************************
							$this->strabo->addSpotToDataset($feature_id,$straboid,"CONTAINS");

							header("Spot added to dataset", true, 201);
							$data['message']="Spot $straboid added to dataset $feature_id.";
							
						}else{

							//Error, feature not found
							header("Bad Request", true, 404);
							$data["Error"] = "Spot $straboid already exists in dataset $feature_id.";							

						}


					}else{

						//Error, feature not found
						header("Bad Request", true, 404);
						$data["Error"] = "Spot $straboid not found.";
					
					}


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

    public function olddeleteAction($request) {

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
