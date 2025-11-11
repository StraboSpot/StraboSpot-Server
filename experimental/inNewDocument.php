<?php
/**
 * File: inNewDocument.php
 * Description: Adds new records to straboexp table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../prepare_connections.php");

if(file_exists("log.txt")){

	$text = print_r($_REQUEST, true);
	file_put_contents ("log.txt", $text, FILE_APPEND);

	$text = print_r($_FILES, true);
	file_put_contents ("log.txt", $text, FILE_APPEND);

	file_put_contents ("log.txt", "userpkey: $userpkey", FILE_APPEND);

	file_put_contents ("log.txt", "\n\n\n*************************************************************************\n\n\n", FILE_APPEND);

}


$project_pkey = intval($_REQUEST['project_pkey']);

if($project_pkey == "") $project_pkey = 99999999;

include_once("../adminkeys.php");

//Check for Login Timeout Here
include("apiLoginCheck.php");


/*
Array
(
	$_REQUEST
	[uuid] => 7ff2aa58-0184-4b1d-bfb9-d96a95c53fd0
)
Array
(
	$_FILES
	[infile] => Array
		(
			[name] => oona_tiny.jpeg
			[type] => image/jpeg
			[tmp_name] => /tmp/phpu8hTKh
			[error] => 0
			[size] => 8066
		)

)
*/

$f = $_FILES['infile'];
$original_filename = $f['name'];
$uuid = $_REQUEST['uuid'];
$tmp_name = $f['tmp_name'];

$db->query("
	insert into straboexp.file_holdings
		(userpkey, uuid, original_filename)
	values (
		$userpkey,
		'$uuid',
		'$original_filename'
	)
");

move_uploaded_file($tmp_name, "/srv/app/www/experimental/expimages/$uuid");





?>