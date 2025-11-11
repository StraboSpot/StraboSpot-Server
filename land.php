<?php
/**
 * File: land.php
 * Description: Application landing page
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include_once("prepare_connections.php");

$hash = $_GET['h'] ?? '';
$hash = preg_replace('/[^a-zA-Z0-9]/', '', $hash);

$datasetid = $db->get_var_prepared("SELECT datasetid FROM landing_pages WHERE hash=$1", array($hash));

if($datasetid != ""){
	header("Location: /search/?datasetid=$datasetid");
}else{
	header("Location: /d404");
}

?>