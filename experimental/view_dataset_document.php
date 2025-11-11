<?php
/**
 * File: view_dataset_document.php
 * Description: Displays dataset information and data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../prepare_connections.php");
include("../expdb/straboexpclass.php");

$docUUID = $_GET['u'];
if($docUUID == "") die("Document not found.");
$row = $db->get_row("select * from straboexp.dataset where uuid = '$docUUID' limit 1");
if($row->pkey == "") die("Document not found.");

$exp = new StraboExp($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$exp->setuuid($uuid);

$originalFileName = $row->path;



$file = $_SERVER['DOCUMENT_ROOT']."/experimental/expimages/".$docUUID;

if(!file_exists($file)) die("Document file not found.");

$ext = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
$basename = pathinfo($originalFileName, PATHINFO_BASENAME);

if(in_array($ext, array("jpg", "jpeg", "png", "gif", "tif"))){
	$ctype = "image";
}else{
	$ctype = "application";
}

header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Content-type: $ctype/".$ext);
header('Content-length: '.filesize($file));
header("Content-Disposition: inline; filename=\"$basename\"");
ob_clean();
flush();
readfile($file);
exit;




?>