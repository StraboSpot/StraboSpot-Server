<?php
/**
 * File: ProjectImagesController.php
 * Description: ProjectImagesController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class ProjectImagesController extends MyController
{

	public function getAction($request) {

		if(isset($request->url_elements[2])) {

			$feature_id = $request->url_elements[2];

			$data = $this->strabo->getProjectImagesForAPI($feature_id);

		} else {
			header("Bad Request", true, 400);
			$data["Error"] = "Bad Request. No Project id specified.";
		}
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
