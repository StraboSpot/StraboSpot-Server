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

		$username=$_SERVER['PHP_AUTH_USER'];
		$remoteip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		$rand = rand(111111,999999);
		$userpkey = $this->strabo->userpkey;
		$id = str_repeat("0", 16 - strlen($userpkey)) . $userpkey;

		$params = array(
			'action_name' => 'Strabo Upload',
			'url' => 'https://strabospot.org/strabo_upload',
			'idsite' => '1',
			'rand' => $rand,
			'uid' => $username,
			'rec' => '1',
			'apiv' => '1',
			'_id' => $id,
			'send_image' => '0',
			'token_auth' => '01e0d17a086d20a2c2ee04064d0d6bc7',
			'cip' => $remoteip
		);

		$endpoint = 'https://stats.strabospot.org/matomo.php';

		$url = $endpoint . '?' . http_build_query($params);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);

		exit();
	}

	public function bkuppostAction($request) {

		$username=$_SERVER['PHP_AUTH_USER'];
		$password=$_SERVER['PHP_AUTH_PW'];

		echo "REQUEST: ".ucfirst($request->url_elements[1])."\n\n";
		echo "REQUEST_URI: ".$_SERVER["REQUEST_URI"]."\n\n";
		echo "username: $username\n\n";
		echo "Raw Input:\n".$rawinput;
		echo "Request Method: ".$_SERVER['REQUEST_METHOD'];

		exit();
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
