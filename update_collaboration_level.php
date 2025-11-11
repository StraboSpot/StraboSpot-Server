<?php
/**
 * File: update_collaboration_level.php
 * Description: Collaboration management interface
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

$level = $_GET['l'] ?? '';
$uuid = $_GET['u'] ?? '';
$uuid = preg_replace('/[^a-zA-Z0-9\-]/', '', $uuid);

$db->prepare_query("UPDATE collaborators SET collaboration_level = $1 WHERE uuid = $2", array($level, $uuid));

?>