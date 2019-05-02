<?php

/*
******************************************************************
StraboSpot REST API
User Authenticate Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller verifies user credentials.
******************************************************************
*/

class UserAuthenticateController extends MyController
{

    public function getAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

        return $data;

    }


    public function postAction($request) {
    
		list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
		$email=$_SERVER['PHP_AUTH_USER'];

		$row = $this->strabo->db->get_row("select * from users where email='$email'");

		$data['valid']="true";
		if($row->profileimage != ""){
			$data['profileimage']="http://strabospot.org/db/profileimage";
		}

        
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
