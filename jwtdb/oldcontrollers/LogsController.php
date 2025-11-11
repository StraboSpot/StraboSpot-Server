<?php
/**
 * File: LogsController.php
 * Description: LogsController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class LogsController extends MyController
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

	public function throwError($error){
		header('Content-Type: application/json; charset=utf-8');
		echo '{"status":"error","message":"'.$error.'"}';

		if(file_exists($_SERVER['DOCUMENT_ROOT']."/log.txt")){
			$fileError = print_r($error, true);
			file_put_contents ($_SERVER['DOCUMENT_ROOT']."/log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
			file_put_contents ($_SERVER['DOCUMENT_ROOT']."/log.txt", "error: ".$fileError."\n\n", FILE_APPEND);
		}

		exit();
	}

	public function postAction($request) {

		

		$email = pg_escape_string($_SERVER['PHP_AUTH_USER']);
		$notes = pg_escape_string($_POST['notes']);
		$appversion = pg_escape_string($_POST['appversion']);

		$inFiles = print_r($_FILES, true);

		if(file_exists($_SERVER['DOCUMENT_ROOT']."/log.txt")){
			if($email=="jasonash@ku.edu"){

				file_put_contents ($_SERVER['DOCUMENT_ROOT']."/log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
				file_put_contents ($_SERVER['DOCUMENT_ROOT']."/log.txt", "email: ".$email."\n\n", FILE_APPEND);
				file_put_contents ($_SERVER['DOCUMENT_ROOT']."/log.txt", "micropass: ".$micropass."\n\n", FILE_APPEND);
				file_put_contents ($_SERVER['DOCUMENT_ROOT']."/log.txt", "notes: ".$notes."\n\n", FILE_APPEND);
				file_put_contents ($_SERVER['DOCUMENT_ROOT']."/log.txt", "inFiles: ".$inFiles."\n\n", FILE_APPEND);

			}
		}

		$file = $_FILES['log_file'];

		if($file['name']==""){
			throwError("No file provided.");
		}

		$pathinfo = pathinfo($file['name']);

		if($pathinfo['extension'] != "zip") throwError("Wrong file type provided.");

		$tmp_name = $file['tmp_name'];

		$pkey = $this->strabo->db->get_var("select nextval('micro_logs_pkey_seq')");

		exec("mv $tmp_name /srv/app/www/microLogs/$pkey.txt");

		$this->strabo->db->query("
			insert into micro_logs (
				pkey,
				email,
				notes,
				appversion
			) values (
				$pkey,
				'$email',
				'$notes',
				'$appversion'
			)
		");

		require_once "Mail.php";
		include($_SERVER['DOCUMENT_ROOT']."/includes/config.inc.php");
		$from     = "StraboSpot <strabospot@gmail.com>";
		$subject="StraboMicro Error Report";

		$host     = "ssl://smtp.gmail.com";
		$port     = "465";
		$emailusername = "$straboemailaddress";  //<> give errors
		$password = "$straboemailpassword";

		$headers = array(
			'From'    => $from,
			'Subject' => $subject,
			'Content-Type' => "text/html; charset=iso-8859-1"
		);

		$message= "<html><body>
					<h2>StraboMicro Error Report</h2>
					A new error report has been sent by StraboMicro user $email<br><br>

					$notes<br><br>

					<a href=\"https://www.strabospot.org/microLogView?pkey=$pkey\">https://www.strabospot.org/microLogView/$pkey</a><br><br>

					<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
					</body></html>";

		$smtp = Mail::factory('smtp', array(
			'host'     => $host,
			'port'     => $port,
			'auth'     => true,
			'username' => $emailusername,
			'password' => $password
		));

		$to = "jasonash@ku.edu";

		$headers['To']=$to;

		$mail = $smtp->send($to, $headers, $message);

		header('Content-Type: application/json; charset=utf-8');
		echo "{\"status\":\"success\"}";
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
