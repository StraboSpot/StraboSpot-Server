<?php

/*
******************************************************************
StraboSpot REST API
My Projects Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller returns all projects for the currently
				authorized user.
******************************************************************
*/

class MyProjectsController extends MyController
{
    public function getAction($request) {

        if(!isset($request->url_elements[2])) {

			$data = $this->strabo->getMyProjects();
			
			/*
			if($data==""){
				header("No Projects Found", true, 404);
				exit();
			}
			*/
			
        } else {
        
        	header("Bad Request", true, 400);
            $data["Error"] = "Bad Request.";
        }
        
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
