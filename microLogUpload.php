<?php
/**
 * File: microLogUpload.php
 * Description: Uploads and processes Strabo Micro log files
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

exit();

$server = print_r($_SERVER, true);

header('Content-Type: application/json; charset=utf-8');
echo '{"status":"error","message":"'.$server.'"}';

exit();

include("prepare_connections.php");

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

function throwError($error){
	header('Content-Type: application/json; charset=utf-8');
	echo '{"status":"error","message":"'.$error.'"}';

	if(file_exists("log.txt")){
		$fileError = print_r($error, true);
		file_put_contents ("log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
		file_put_contents ("log.txt", "error: ".$fileError."\n\n", FILE_APPEND);
	}

	exit();
}

$email = $_POST['email'] ?? '';
if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
	throwError("Invalid email format");
}
$micropass = $_POST['micropass'] ?? '';
$notes = $_POST['notes'] ?? '';

$inFiles = print_r($_FILES, true);

if(file_exists("log.txt")){

		file_put_contents ("log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
		file_put_contents ("log.txt", "email: ".$email."\n\n", FILE_APPEND);
		file_put_contents ("log.txt", "micropass: ".$micropass."\n\n", FILE_APPEND);
		file_put_contents ("log.txt", "notes: ".$notes."\n\n", FILE_APPEND);
		file_put_contents ("log.txt", "inFiles: ".$inFiles."\n\n", FILE_APPEND);

}

if($micropass != "tGQgFe9u5mTMPGEnjRfqMm7DCb5nY3wPXFxtLZpz") throwError("incorrect password");

$file = $_FILES['log_file'];

if($file['name']==""){
	throwError("No file provided.");
}

$pathinfo = pathinfo($file['name']);

if($pathinfo['extension'] != "zip") throwError("Wrong file type provided.");

$tmp_name = $file['tmp_name'];

$pkey = $db->get_var("select nextval('micro_logs_pkey_seq')");

exec("mv $tmp_name /srv/app/www/microLogs/$pkey.txt");

$db->prepare_query("
	INSERT INTO micro_logs (
		pkey,
		email,
		notes
	) VALUES (
		$1,
		$2,
		$3
	)
", array($pkey, $email, $notes));

header('Content-Type: application/json; charset=utf-8');
echo '{"status":"success"}';

?>