<?php

/*
******************************************************************
StraboSpot REST API
Mirror Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller serves as a debug tool. It simply
				returns anything provided to it without alteration.
******************************************************************
*/

class MirrorController extends MyController
{
    public function getAction($request) {
    	
    	$data["Message"] = "GET hit in mirror.";
    	
    	return $data;
    
    }

    public function deleteAction($request) {
    	
    	$data["Message"] = "DELETE hit in mirror.";
    	
    	return $data;
    }


    public function postAction($request) {

		$upload = $request->parameters;

    	$data["Message"] = "POST hit in mirror."; 
    	
    	print_r($upload);exit();
    	
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
