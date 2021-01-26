<?
set_time_limit(0);
session_start();
include("prepare_connections.php");

include("includes/straboClasses/newSearchOutputClass.php");
include("includes/straboClasses/searchQueryRowBuilder.php");

$querybuilder = new searchQueryRowBuilder();

//$strabo->dumpVar($_GET);exit();

$strabo->setrowbuilder($querybuilder);

$straboOut = new newSearchOutputClass($strabo,$_GET);

$type=$_GET['type'];
$dsids=$_GET['dsids'];

if($type=="shapefile"){
	$straboOut->shapefileOut();
}elseif($type=="kml"){
	$straboOut->kmlOut();
}elseif($type=="xls"){
	$straboOut->xlsOut();
}elseif($type=="stereonet"){
	$straboOut->stereonetOut();
}elseif($type=="fieldbook"){
	$straboOut->fieldbookOut();
}elseif($type=="fieldbookdev"){
	$straboOut->newfieldbookOut();
}elseif($type=="shapefiledev"){
	$straboOut->devshapefileOut();
}elseif($type=="debug"){
	$straboOut->debugOut();
}elseif($type=="stratsection"){
	$parts = explode("-", $dsids);
	$lookId = $parts[1];
	header("Location: pdataset_strat_sections.php?dataset_ids=$lookId");
}










?>