<?php
/**
 * File: download_basemap.php
 * Description: Handles download basemap operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");
include_once('includes/straboClasses/basemapClass.php');

$i = $_GET['i'];
if($i == "") exit("No id provided.");

$base = new straboBasemapClass($strabo, $i);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename='.basename($i.".jpg"));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
ob_clean();
flush();

$base->rawOut();

?>