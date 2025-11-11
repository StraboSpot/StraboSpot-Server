<?php
/**
 * File: mysearches.php
 * Description: Search interface for querying and filtering data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("searchWorker.php");

$out = new stdClass();
$searches = [];

$rows = $db->get_results_prepared("SELECT
							pkey,
							search_name,
							search_json,
							to_char(date_saved, 'MM/DD/YYYY') as date_saved
						FROM fullsearches WHERE user_pkey = $1 ORDER BY pkey DESC", array($userpkey));

foreach($rows as $row){
	unset($row->user_pkey);
	$searches[] = $row;
}

$out->searches = $searches;

header('Content-Type: application/json');
$out = json_encode($out, JSON_PRETTY_PRINT);
echo $out;

?>