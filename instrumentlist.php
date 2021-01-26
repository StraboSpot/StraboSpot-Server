<?

include("prepare_connections.php");

$rows = $db->get_results("
		select
		i.pkey as instrument_pkey,
		ii.name as institution_name,
		i.instrumentname
		from
		instrument_institution ii,
		instrument i
		where
		ii.pkey = i.institution_pkey
		order by
		ii.name,
		i.instrumentname
		");
		
//$db->dumpVar($rows);

$instruments = array();

foreach($rows as $row){
	$thisinstrument = array();
	$thisinstrument['id'] = $row->instrument_pkey;
	$thisinstrument['name'] = $row->institution_name . " - " . $row->instrumentname;
	$instruments[] = $thisinstrument;
}

$out = array();
$out['instruments'] = $instruments;

header('Content-Type: application/json');
echo json_encode($out, JSON_PRETTY_PRINT);



?>