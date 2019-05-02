<?php

/*
******************************************************************
StraboSpot REST API
Verify Image Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller verifies the existence of an image file.
******************************************************************
*/

class VerifyImageController extends MyController
{

    public function getAction($request) {

        if(isset($request->url_elements[2])) {
        
        	$feature_id = $request->url_elements[2];
        	
        	if($this->strabo->findImageFile($feature_id)){
    			header("Success", true, 200);
				$data["Message"] = "Image file $feature_id exists.";
        	}else{
				header("Bad Request", true, 404);
				$data["Error"] = "Image file $feature_id does not exist.";
        	}

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
