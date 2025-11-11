<?php
/**
 * File: MyController.php
 * Description: MyController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class MyController
{
	 public function setstrabohandler($strabo){
		 $this->strabo=$strabo;
	 }

	 public function foobar($value){

		 echo "$value";exit();

	 }

	 // Default handlers for unsupported HTTP methods
	 // Child controllers can override these if needed

	 public function putAction($request) {
		 header("Bad Request", true, 400);
		 $data["Error"] = "Bad Request.";
		 return $data;
	 }

	 public function patchAction($request) {
		 header("Bad Request", true, 400);
		 $data["Error"] = "Bad Request.";
		 return $data;
	 }

	 public function optionsAction($request) {
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
