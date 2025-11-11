<?php
/**
 * File: MirrorController.php
 * Description: MirrorController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class MirrorController extends MyController
{
	public function getAction($request) {

		$data["Message"] = "GET hit in mirror.";

		return $data;

	}

	public function deleteAction($request) {

		$data["Message"] = "DELETE hit in mirror.";

		return $data;
	}

	public function postAction($request) {

		$upload = $request->parameters;

		$data["Message"] = "POST hit in mirror.";

		print_r($upload);exit();

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
