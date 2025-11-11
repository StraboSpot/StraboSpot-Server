<?php
/**
 * File: apparatusschematic.php
 * Description: Displays apparatus schematic diagrams and technical drawings
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("logincheck.php");

$userpkey = $_SESSION['userpkey'];

include("prepare_connections.php");

$pkey = $_GET['pkey'];

$filename = $db->get_var("select original_file_name from exp_images where type='schematic' and apparatus_pkey = $pkey");

$path = $_SERVER['DOCUMENT_ROOT']."/expimages/schematic_".$pkey;

if($filename != "" && file_exists($path)){

	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary");
	header("Content-disposition: attachment; filename=\"" . $filename . "\"");
	readfile($path);

}

?>