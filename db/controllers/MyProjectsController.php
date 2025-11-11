<?php
/**
 * File: MyProjectsController.php
 * Description: MyProjectsController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class MyProjectsController extends MyController
{
	public function getAction($request) {

		if(!isset($request->url_elements[2])) {

			$data = $this->strabo->getMyProjects();

		} else {

			header("Bad Request", true, 400);
			$data["Error"] = "Bad Request.";
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
