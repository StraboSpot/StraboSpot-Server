<?php
/**
 * File: instrumentlist.php
 * Description: Handles instrumentlist operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("prepare_connections.php");

$rows = $db->get_results("
		select
		i.pkey as instrument_pkey,
		ii.institute_name,
		i.instrumentname,
		i.instrumenttype
		from
		institute ii,
		instrument i
		where
		ii.pkey = i.institution_pkey
		order by
		ii.institute_name,
		i.instrumentname
		");

$instruments = array();

foreach($rows as $row){
	$thisinstrument = array();
	$thisinstrument['id'] = $row->instrument_pkey;
	$thisinstrument['name'] = $row->institute_name . " - " . $row->instrumenttype . " - " . $row->instrumentname;
	$instruments[] = $thisinstrument;
}

$out = array();
$out['instruments'] = $instruments;

header('Content-Type: application/json');
echo json_encode($out, JSON_PRETTY_PRINT);

?>