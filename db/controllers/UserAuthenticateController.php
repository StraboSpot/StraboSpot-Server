<?php
/**
 * File: UserAuthenticateController.php
 * Description: UserAuthenticateController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class UserAuthenticateController extends MyController
{

	public function getAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;

	}

	public function postAction($request) {

		list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':' , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
		$email = strtolower(trim($_SERVER['PHP_AUTH_USER']));

		// Validate email format
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$data['Error'] = 'Invalid credentials';
			return $data;
		}

		$row = $this->strabo->db->get_row_prepared("SELECT * FROM users WHERE email=$1", array($email));

		$data['valid']="true";
		if($row->profileimage != ""){
			$data['profileimage']="http://strabospot.org/db/profileimage";
		}

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
