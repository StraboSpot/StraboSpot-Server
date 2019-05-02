<?php

/*
******************************************************************
StraboSpot REST API
Feature Images Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller retrieves images for a given spot 
					(deprecated)
******************************************************************
*/

class FeatureImagesController extends MyController
{
    public function getAction($request) {
    
		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

        return $data;

    }

    public function deleteAction($request) {
    	
		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

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
