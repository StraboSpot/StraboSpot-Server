<?php
/**
 * File: view_experimental_uploaded_file.php
 * Description: Displays uploaded file content and metadata
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


$uuid = $_REQUEST['uuid'];
$original_filename = $_REQUEST['original_filename'];

$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type aka mimetype extension

if(file_exists("expimages/$uuid")){
	$header = finfo_file($finfo, "expimages/$uuid");
}else{
	exit("file not found.");
}

header("Content-Type:   $header");
//header("Content-Disposition: attachment;filename=\"$original_filename\"");
header('Content-Disposition: inline; filename="'.$original_filename.'"');
header("Cache-Control: max-age=0");
readfile("expimages/$uuid");

?>