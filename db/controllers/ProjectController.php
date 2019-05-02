<?php

/*
******************************************************************
StraboSpot REST API
Project Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller allows for the creation/retrieval
				of projects.
******************************************************************
*/

class ProjectController extends MyController
{
    public function getAction($request) {
    
        if(isset($request->url_elements[2])) {
            $feature_id = $request->url_elements[2];

            if(isset($request->url_elements[3])) {
				// do nothing, this is not a supported action
				header("Bad Request", true, 400);
				$data["Error"] = $request->url_elements[3]." not supported.";
            	//update here
            	
            } else {
            	
            	$data = $this->strabo->getProject($feature_id);
            	
				if($data->Error!=""){
					header("Project not Found", true, 404);
				}
            }
        } else {
        	header("Bad Request", true, 400);
            $data["Error"] = "Project ID must be provided";
        }
        return $data;
    }

    public function deleteAction($request) {
    	
        if(isset($request->url_elements[2])) {

			//********************************************************************
			// check for feature with userid and id; delete if exists
			//********************************************************************
			
			$feature_id = (int)$request->url_elements[2];
			
			if($this->strabo->findProject($feature_id)){

				$this->strabo->deleteProject($feature_id);

				header("Project deleted", true, 204);
				$data['message']="Project $feature_id deleted.";

				
			}else{
				//Error, feature not found
				header("Bad Request", true, 404);
				$data["Error"] = "Project $feature_id not found.";
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
		
		$projectname = $upload['description']->project_name;
		$projectid = $upload['id'];

		if($projectname==""){

			// bad body sent, error
			header("Bad Request", true, 400);
			$data["Error"] = "Invalid body JSON sent.";

		}else{

			
			if($uuid = $this->strabo->createVersion($projectid)){
				$this->strabo->logToFile("Version created: $uuid.");
			}else{
				$this->strabo->logToFile("Version creation failed.");
			}
			
			$injson = json_encode($upload);

			
			
			$data = $this->strabo->insertProject($injson,$thisid);
			
			//valid JSON found. Look for id or self and update if exists

			if($data->Error!=""){
				header("Bad Request", true, 404);
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
