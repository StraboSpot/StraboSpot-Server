<?php
/**
 * File: TestController.php
 * Description: TestController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class TestController extends MyController
{
	public function getAction($request) {

		$content = file_get_contents("testProject.json");
		$this->sm->loadProjectJSON($content);
		exit();
	}

	public function deleteAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function postAction($request) {

		$data = $this->strabo->insertImage($_POST,$_FILES['image_file']);

		if($data->Error != ""){
			header("Bad Request", true, 400);
		}else{
			header("Image saved.", true, 201);
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
