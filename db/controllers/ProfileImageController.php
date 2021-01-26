<?php

/*
******************************************************************
StraboSpot REST API
Profile Image Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller allows for the upload/retrieval of
				a profile image for the currently authenticated
				user.
******************************************************************
*/

class ProfileImageController extends MyController
{
    public function getAction($request) {

		$imagename = $this->strabo->getProfileImageName();

		if($imagename != ""){

			$extension = end(explode(".",$imagename));

			header("Content-type:image/$extension");
			readfile("/var/www/profileimages/$imagename");
			exit();
			

		}else{
			//Error, image not found
			header("Image not Found", true, 404);
			$data["Error"] = "Profile image not found.";
		}
				


        return $data;
    }

    public function deleteAction($request) {
    	
		$this->strabo->deleteProfileImage();

		header("Profile image deleted", true, 204);
		$data['message']="Profile image deleted.";
        
        return $data;
    }


    public function postAction($request) {

		$data = $this->strabo->insertProfileImage($_POST,$_FILES['image_file']);
		
		if($data->Error != ""){
			header("Bad Request", true, 400);
		}else{
			header("Image saved.", true, 201);
			$data['message']="Profile Image Saved.";
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
