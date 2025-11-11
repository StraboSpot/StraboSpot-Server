<?php
/**
 * File: deletesearch.php
 * Description: Deletes records from fullsearches table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("searchWorker.php");

$p = $_GET['p'] ?? '';
if(!is_numeric($p) || $p == "") die("Invalid search pkey provided.");
$p = (int)$p;

$db->prepare_query("DELETE FROM fullsearches WHERE pkey = $1 AND user_pkey = $2", array($p, $userpkey));

$out = new stdClass();

$out->message = "Success!";

header('Content-Type: application/json');
$out = json_encode($out, JSON_PRETTY_PRINT);
echo $out;

?>