<?php

/*
******************************************************************
StraboSpot REST API
Dataset Fields Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller returns fields for a given dataset
				that contain real-world coordinates. Also, there
				is an optional feature type parameter that limits
				returned features to a certain type.
******************************************************************
*/


class DatasetFieldsController extends MyController
{

    public function getAction($request) {

        if(isset($request->url_elements[2])) {
        
        	$feature_id = $request->url_elements[2];
        	$ingtype = strtolower($request->url_elements[3]);
        	
        	$data = $this->strabo->getDatasetFields($feature_id,$ingtype);

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
