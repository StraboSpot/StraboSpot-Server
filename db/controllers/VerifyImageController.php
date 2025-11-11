<?php
/**
 * File: VerifyImageController.php
 * Description: VerifyImageController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class VerifyImageController extends MyController
{

	public function getAction($request) {

		if(isset($request->url_elements[2])) {

			$feature_id = $request->url_elements[2];

			if($this->strabo->findImageFile($feature_id)){

				$modified_timestamp = $this->strabo->getImageTimestamp($feature_id);

				header("Success", true, 200);
				$data["Message"] = "Image file $feature_id exists.";
				$data["modified_timestamp"] = $modified_timestamp;
			}else{
				header("Bad Request", true, 404);
				$data["Error"] = "Image file $feature_id does not exist.";
			}

		} else {
			header("Bad Request", true, 400);
			$data["Error"] = "Bad Request. No Dataset id specified.";
		}
		return $data;
	}

	public function postAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;

	}

	public function deleteAction($request) {

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
