<?php
/**
 * File: ProjectTimestampController.php
 * Description: ProjectTimestampController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class ProjectTimestampController extends MyController
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

		$upload = $request->parameters;
		unset($upload['apiformat']);

		if(isset($request->url_elements[2])) {

			$thisid = $request->url_elements[2];

			if($upload['timestamp'] != ""){

				$timestamp = $upload['timestamp'];
				$userpkey = $this->strabo->userpkey;

				//update here
				$this->strabo->neodb->query("match (p:Project {id:$thisid, userpkey:$userpkey}) set p.modified_timestamp=$timestamp");

			}else{
				$errors.="No Timestamp Provided. ";
			}

		}else{
			$errors.="No Project ID Provided. ";
		}

		 if(!$errors){

			$data = $upload;

		}else{
			header("Bad Request", true, 400);
			$data["Error"] = $errors;
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
