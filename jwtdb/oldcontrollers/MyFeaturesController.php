<?php
/**
 * File: MyFeaturesController.php
 * Description: MyFeaturesController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class MyFeaturesController extends MyController
{
	public function getAction($request) {

		exit();

		if(!isset($request->url_elements[2])) {

			$data = $this->strabo->getMySpots();

		} else {
			header("Bad Request", true, 400);
			$data["Error"] = "Bad Request.";
		}
		return $data;
	}

	public function deleteAction($request) {

		exit();

		if(!isset($request->url_elements[2])) {

			$this->strabo->deleteMySpots();

			header("Feature deleted", true, 204);
			$data['message']="Features deleted.";

		} else {
			header("Bad Request", true, 400);
			$data["Error"] = "Bad Request.";
		}
		return $data;

	}

	public function postAction($request) {

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
