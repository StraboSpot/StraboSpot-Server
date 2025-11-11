<?php
/**
 * File: ImageController.php
 * Description: ImageController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class ImageController extends MyController
{
	public function getAction($request) {

		if(isset($request->url_elements[2])) {
			$image_id = $request->url_elements[2];

			$project_folder = $this->sm->getProjectFolderFromImageId($image_id);

			if(file_exists($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$project_folder."/images/".$image_id.".jpg")){
				header('Content-type: image/jpeg');
				readfile($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$project_folder."/images/".$image_id.".jpg");
				exit();
			}else{
				header('Content-type: image/png');
				readfile($_SERVER['DOCUMENT_ROOT']."/includes/images/image-not-found.png");
				exit();
			}

		} else {
			header("Bad Request", true, 400);
			$data["Error"] = "Image ID must be provided";
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
	}    public function optionsAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}    public function copyAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}}
