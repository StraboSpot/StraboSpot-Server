<?php

/*
******************************************************************
StraboSpot REST API
Dataset Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller allows usert to manage datasets
******************************************************************
*/

class DatasetController extends MyController
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
				$data = $this->strabo->getSingleDataset($feature_id);

            	if($data["Error"]!=""){
            		header("Dataset not Found", true, 404);
            	}

            }
        } else {
        	header("Bad Request", true, 400);
            $data["Error"] = "Dataset ID must be provided";
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
                

				//********************************************************************
				// check for feature with userid and id; delete if exists
				//********************************************************************

				if($this->strabo->findDataset($feature_id)){

					$this->strabo->deleteSingleDataset($feature_id);

					header("Dataset deleted", true, 204);
					$data['message']="Dataset $feature_id deleted.";

					
				}else{
					//Error, feature not found
					header("Bad Request", true, 404);
					$data["Error"] = "Dataset $feature_id not found.";
				}

            }
        } else {
        	header("Bad Request", true, 400);
            $data["Error"] = "Dataset ID must be provided";
        }
        return $data;
    }


    public function postAction($request) {
    
    	$thisid = $request->url_elements[2];
    	$upload = $request->parameters;
    	unset($upload['apiformat']);
    	$injson=json_encode($upload);
    	
		$data = $this->strabo->insertDataset($injson,$thisid);

		if($data->Error != ""){
			header("Bad Request", true, 400);
		}else{
			header("Dataset created", true, 201);
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
