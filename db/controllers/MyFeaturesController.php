<?php

/*
******************************************************************
StraboSpot REST API
My Features Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller returns/deletes all features for the 
				currently authorized user.
******************************************************************
*/

class MyFeaturesController extends MyController
{
    public function getAction($request) {

        if(!isset($request->url_elements[2])) {

			$data = $this->strabo->getMySpots();

        } else {
        	header("Bad Request", true, 400);
            $data["Error"] = "Bad Request.";
        }
        return $data;
    }


    public function deleteAction($request) {

        if(!isset($request->url_elements[2])) {

			$this->strabo->deleteMySpots();
		
			header("Feature deleted", true, 204);
			$data['message']="Features deleted.";
	
        } else {
        	header("Bad Request", true, 400);
            $data["Error"] = "Bad Request.";
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
