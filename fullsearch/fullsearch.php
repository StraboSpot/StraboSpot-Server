<?php
/**
 * File: fullsearch.php
 * Description: Search interface for querying and filtering data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("searchWorker.php");
$json = file_get_contents("php://input");
$sr->setjson($json);
//$sr->setSearchType("count");

$data = json_decode($json);


if($data->searchType != "") $sr->setSearchType($data->searchType);

$out = $sr->getResults();

header('Content-Type: application/json');
$out = json_encode($out, JSON_PRETTY_PRINT);
echo $out;

?>