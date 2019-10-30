<?php

/*
******************************************************************
StraboSpot REST API
Project Images Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller retrieves images from a project.
******************************************************************
*/

class ProjectImagesController extends MyController
{

    public function getAction($request) {

        if(isset($request->url_elements[2])) {
        
        	$feature_id = $request->url_elements[2];

			$data = $this->strabo->getProjectImagesForAPI($feature_id);

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
