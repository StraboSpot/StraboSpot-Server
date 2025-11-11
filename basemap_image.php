<?php
/**
 * File: basemap_image.php
 * Description: Generates and outputs full-size basemap images with geological features
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
$base->out();

?>