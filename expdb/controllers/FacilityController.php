<?php
/**
 * File: FacilityController.php
 * Description: FacilityController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class FacilityController extends MyController
{
	public function getAction($request) {

		if(isset($request->url_elements[2])) {
			$facility_id = $request->url_elements[2];

			if(isset($request->url_elements[3])) {
				// do nothing, this is not a supported action
				header("Bad Request", true, 400);
				$data["Error"] = $request->url_elements[3]." not supported.";

			} else {
				//get the facility from database

				$data = $this->expclass->getFacility($facility_id);

				if($data->Error!=""){
					header("Facility not Found", true, 404);
				}

			}
		} else {
			header("Bad Request", true, 400);
			$data["Error"] = "Facility UUID must be provided";
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
				// check for facility with userid and id
				//********************************************************************

				/*
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
				*/

			}
		} else {
			header("Bad Request", true, 400);
			$data["Error"] = "Project ID must be provided";
		}
		return $data;
	}

	public function postAction($request) {

		$upload = $request->parameters;
		unset($upload['apiformat']);

		$uuid = $request->url_elements[2];
		if($uuid == ""){
			$uuid = $upload['uuid'];
		}

		$institutename = $upload['institute'];

		if($institutename==""){

			// bad body sent, error
			header("Bad Request", true, 400);
			$data["Error"] = "Invalid body JSON sent.";

		}else{

			$injson = json_encode($upload, JSON_PRETTY_PRINT);

			$data = $this->expclass->insertFacility($injson, $uuid);

			if($data->Error!=""){
				header("Bad Request", true, 404);
			}else{
				header("Feature created", true, 201);
			}
		}

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
