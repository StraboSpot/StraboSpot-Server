<?
ini_set('max_execution_time', 300);
include("logincheck.php");
include("prepare_connections.php");

include("includes/straboClasses/straboOutputClass.php");

//$strabo->dumpVar($_GET);exit();

$straboOut = new straboOutputClass($strabo,$_GET);

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
}elseif($type=="stratsection"){
	header("Location: pdataset_strat_sections.php?dataset_ids=$dsids");
}elseif($type=="stereonetdev"){
	$straboOut->devstereonetOut();
}elseif($type=="xlsdev"){
	$straboOut->devxlsOut();
}elseif($type=="debug"){
	$straboOut->debugOut();
}










?>