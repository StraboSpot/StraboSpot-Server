<?php
/**
 * File: ProjectPDFController.php
 * Description: ProjectPDFController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class ProjectPDFController extends MyController
{
	public function getAction($request) {

		if(isset($request->url_elements[2])) {
			$project_id = $request->url_elements[2];

			if(isset($request->url_elements[3])) {
				// do nothing, this is not a supported action
				header("Bad Request", true, 400);
				$data["Error"] = $request->url_elements[3]." not supported.";

			} else {
				//get the feature from database
				$data = $this->sm->getProjectPDF($project_id);

				if($data->Error != ""){

					//Error, project not found
					header("Project not Found", true, 404);

				}else{

				}

			}
		} else {
			header("Bad Request", true, 400);
			$data["Error"] = "Project ID must be provided";
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
