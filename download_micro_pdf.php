<?php
/**
 * File: download_micro_pdf.php
 * Description: PDF document generation handler
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
$filename = $filename.".pdf";

header("Content-Type: application/pdf");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Length: " . filesize($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$id."/project.pdf"));

readfile($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$id."/project.pdf");
exit;
?>

