<?php

/*
******************************************************************
StraboSpot REST API
Image Controller
Author: Jason Ash (jasonash@ku.edu)
Description: This controller allows for the upload/retrieval of
				images to the Strabo database.
******************************************************************
*/

class ImageController extends MyController
{
    public function getAction($request) {
    
        if(isset($request->url_elements[2])) {
            $image_id = $request->url_elements[2];
            
            if(isset($request->url_elements[3])) {
				// do nothing, this is not a supported action
				header("Bad Request", true, 400);
				$data["Error"] = $request->url_elements[3]." not supported.";

            } else {
            	//get the feature from neo4j
				$imageinfo = $this->strabo->getImageInfo($image_id);
				
				if($imageinfo->count > 0){

					if($imageinfo->filename != ""){
					
						$filename = $imageinfo->filename;
						$origfilename = $imageinfo->origfilename;
						$extension = $imageinfo->extension;

						header("Content-type:image/$extension");
						readfile("dbimages/$filename");
						exit();
					
					}else{
					
						//Error, image not found
						header("Image not Found", true, 404);
						$data["Error"] = "Image $image_id has no uploaded file.";
					
					}
					
				}else{
					//Error, image not found
					header("Image not Found", true, 404);
					$data["Error"] = "Image $image_id not found.";
				}

            }
        } else {
        	header("Bad Request", true, 400);
            $data["Error"] = "Image ID must be provided";
        }
        return $data;
    }

    public function deleteAction($request) {
    	
        if(isset($request->url_elements[2])) {
            $image_id = (int)$request->url_elements[2];
            if(isset($request->url_elements[3])) {
				// do nothing, this is not a supported action
				header("Bad Request", true, 400);
				$data["Error"] = $request->url_elements[3]." not supported.";
            } else {
                

				//********************************************************************
				// check for feature with userid and id
				//********************************************************************
				
				$imageinfo = $this->strabo->getImageInfo($image_id);

				if($imageinfo->count > 0){
					
					//get filename so we can delete actual file
					$filename = $imageinfo->filename;
					unlink("dbimages/$filename");

					$this->strabo->deleteImage($image_id);
					
					header("Image deleted", true, 204);
					$data['message']="Image $feature_id deleted.";
					
				}else{
					//Error, feature not found
					header("Bad Request", true, 404);
					$data["Error"] = "Image $image_id not found.";
				}

            }
        } else {
        	header("Bad Request", true, 400);
            $data["Error"] = "Image ID must be provided";
        }
        return $data;
    }


    public function postAction($request) {

		$data = $this->strabo->insertImage($_POST,$_FILES['image_file']);
		
		if($data->Error != ""){
			header("Bad Request", true, 400);
		}else{
			header("Image saved.", true, 201);
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
