<?
//export profiles json

include_once("../includes/config.inc.php");
include_once('../geophp/geoPHP.inc');
include("../db.php");
include("../neodb.php");
include("../db/strabospotclass.php");

function dprint($myvar){
	echo nl2br(print_r($myvar,true));
}

$strabo = new StraboSpot($neodb,$userpkey,$db);

$results = $neodb->query("match (i:Profile) return i;");

//$neodb->dumpVar($results);

$rows = $results->results[0]->data;

$allimages = array();

foreach ($rows as $row){

	unset($thisimage);
	$thisimage = array();
	$vals = $row->row[0];
	foreach($vals as $key=>$value){
		if($key!="id" && $key!="src"){
			if($key=="straboid"){$key="id";}
			$thisimage[$key]=$value;
		}
	}
	$allimages["profiles"][]=$thisimage;

}

$allimages = json_encode($allimages,JSON_PRETTY_PRINT);

//$neodb->dumpVar($allimages);

unlink("profiles.json");

file_put_contents("profiles.json",$allimages);



?>