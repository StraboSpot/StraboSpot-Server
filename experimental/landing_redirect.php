<?php
/**
 * File: landing_redirect.php
 * Description: Landing page redirect handler
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../prepare_connections.php");

$pkey = isset($_GET['ppk']) ? (int)$_GET['ppk'] : 0;

$uuid = $db->get_var_prepared("SELECT uuid FROM straboexp.project WHERE pkey = $1", array($pkey));

header("Location: https://strabospot.org/experimental/view_project?u=$uuid");








?>