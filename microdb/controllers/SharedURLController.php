<?php
/**
 * File: SharedURLController.php
 * Description: SharedURLController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class SharedURLController extends MyController
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
				$projectinfo = $this->sm->getSharedURL($project_id);

				if($projectinfo->bytes > 0){

					$data = $projectinfo;

				}else{
					//Error, project not found
					header("Project not Found", true, 404);
					$data["Error"] = "Project $project_id not found.";
				}

			}
		} else {
			header("Bad Request", true, 400);
			$data["Error"] = "Project ID must be provided";
		}
		return $data;
	}

	public function deleteAction($request) {

		if(isset($request->url_elements[2])) {
			$project_id = (int)$request->url_elements[2];
			if(isset($request->url_elements[3])) {
				// do nothing, this is not a supported action
				header("Bad Request", true, 400);
				$data["Error"] = $request->url_elements[3]." not supported.";
			} else {

				//********************************************************************
				// check for project with userid and id
				//********************************************************************

				$projectinfo = $this->sm->getProjectInfo($project_id);

				if($projectinfo->count > 0){

					$this->sm->deleteProject($project_id);

					header("Project deleted", true, 204);
					$data['message']="Project $project_id deleted.";

				}else{
					//Error, feature not found
					header("Bad Request", true, 404);
					$data["Error"] = "Project $project_id not found.";
				}

			}
		} else {
			header("Bad Request", true, 400);
			$data["Error"] = "Project ID must be provided";
		}
		return $data;
	}

	public function postAction($request) {

		$data = $this->sm->insertProject($_POST,$_FILES['microProject']);

		$outData = new stdClass();

		if($data->Error != ""){
			header("Bad Request", true, 400);
			$outData->status="failure";
			$outData->message=$data->Error;
		}else{
			header("Project saved.", true, 201);
			$outData->status="success";
			$outData->message="Project uploaded successfully.";
		}

		return $outData;
	}    public function optionsAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}    public function copyAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}}
