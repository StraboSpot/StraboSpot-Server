<?php
/**
 * File: SearchController.php
 * Description: SearchController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class SearchController extends MyController
{

	public function postAction($request) {

		if(isset($request->url_elements[2])) {
			// do nothing, this is not a supported action
			header("Bad Request", true, 400);
			$data["Error"] = "Bad Request.";
		} else {
			$upload = $request->parameters;
			unset($upload['apiformat']);

			$bbox="";

			if (array_key_exists('BBOX', $upload)) {
				$bbox = $upload['BBOX'];
			}

			if($bbox==""){
				header("Bad Request", true, 400);
				$data["Error"] = "No query parameters provided.";
			}else{
				//OK, do search
				$parts=explode(",",$bbox);
				$west=trim($parts[0]);
				$south=trim($parts[1]);
				$east=trim($parts[2]);
				$north=trim($parts[3]);

				$polygon = "$west $south, $east $south, $east $north, $west $north, $west $south";

				$data = $this->strabo->getPolygonSpots($polygon);

			}

		}
		return $data;
	}

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
