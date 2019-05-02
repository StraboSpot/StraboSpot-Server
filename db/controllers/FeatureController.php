<?php

/*
******************************************************************
StraboSpot REST API
Feature Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller enables the creation/retrieval of
				spots.
******************************************************************
*/

class FeatureController extends MyController
{
 
    public function getAction($request) {
    
        if(isset($request->url_elements[2])) {
            $feature_id = $request->url_elements[2];

            if(isset($request->url_elements[3])) {
				// do nothing, this is not a supported action
				header("Bad Request", true, 400);
				$data["Error"] = $request->url_elements[3]." not supported.";

            } else {
            	//get the feature from neo4j
				$data = $this->strabo->getSingleSpot($feature_id);

            	if($data->Error!=""){
            		header("Feature not Found", true, 404);
            	}

            }

        } else {
        	header("Bad Request", true, 400);
            $data["Error"] = "Feature ID must be provided";
        }
        return $data;
    }

    public function deleteAction($request) {

    	
        if(isset($request->url_elements[2])) {
            $feature_id = (int)$request->url_elements[2];
            if(isset($request->url_elements[3])) {
				// do nothing, this is not a supported action
				header("Bad Request", true, 400);
				$data["Error"] = $request->url_elements[3]." not supported.";
            } else {
                
				if($this->strabo->findSpot($feature_id)){
					$projectid = $this->strabo->getProjectIdFromSpotId($feature_id);
					$this->strabo->deleteSingleSpot($feature_id);
					$this->strabo->setProjectCenter($projectid);
					header("Feature deleted", true, 204);
					$data['message']="Feature $feature_id deleted.";
				}else{
					//Error, feature not found
					header("Bad Request", true, 404);
					$data["Error"] = "Feature $feature_id not found.";
				}
            }
        } else {
        	header("Bad Request", true, 400);
            $data["Error"] = "Feature ID must be provided";
        }
        return $data;
    }

    public function postAction($request) {
    
        if(isset($request->url_elements[2])) {

			$thisid = $request->url_elements[2];

        }

		$data["Error"] = "This function is deprecated. Please use datasetspots function instead.";
        
        return $data;
    }
    
    public function origpostAction($request) {
    
        if(isset($request->url_elements[2])) {

			$thisid = $request->url_elements[2];

        }

		$upload = $request->parameters;

		unset($upload['apiformat']);
		
		$featuretype=$upload['type'];
		
		if($featuretype==""){

			// bad body sent, error
			header("Bad Request", true, 400);
			$data["Error"] = "Invalid body JSON sent.";

		}else{

			$injson = json_encode($upload);
			$data = $this->strabo->insertSpot($injson,$thisid);

			if($data->Error != ""){
				header("Bad Request", true, 400);
			}else{
				header("Feature created", true, 201);
			}

		}
        
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
