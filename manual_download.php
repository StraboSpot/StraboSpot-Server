<?php
/**
 * File: manual_download.php
 * Description: Handles manual download operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


$manualtype = isset($_GET['t']) ? $_GET['t'] : '';

if($manualtype == "field"){
	$filename = "StraboField_Manual.pdf";
}elseif($manualtype == "micro"){
	$filename = "StraboMicro_Manual.pdf";
}elseif($manualtype == "experimental"){
	$filename = "StraboExperimental_Manual.pdf";
}elseif($manualtype == "tools"){
	$filename = "StraboTools_Manual.pdf";
}else{
	exit("Incorrect manual type provided.");
}

header("Content-type:application/pdf");
header("Content-Disposition:inline;filename=\"$filename\"");
readfile("manuals/$manualtype.pdf");

?>