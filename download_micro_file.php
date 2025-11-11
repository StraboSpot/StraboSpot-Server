<?php
/**
 * File: download_micro_file.php
 * Description: Downloads files from Strabo Micro projects
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("prepare_connections.php");

$id = isset($_GET['project_id']) ? (int)$_GET['project_id'] : 0;

$filename = $db->get_var_prepared("SELECT name FROM micro_projectmetadata WHERE id = $1", array($id));
$filename = preg_replace('/[^A-Za-z0-9\-_ ]/', '', $filename);
$filename = trim($filename);
$filename = str_replace(" ", "_", $filename);
$filename = strtolower($filename);
$filename = $filename.".smz";

header("Content-Type: application/smz");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Length: " . filesize($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$id."/project.zip"));

readfile($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$id."/project.zip");
exit;
?>

