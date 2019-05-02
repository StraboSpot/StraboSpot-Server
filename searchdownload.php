<?

include("prepare_connections.php");

include("includes/straboClasses/straboOutputClass.php");

//$strabo->dumpVar($_GET);exit();

$straboOut = new straboOutputClass($strabo,$_GET);

$type=$_GET['type'];

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
	$straboOut->fieldbookOutdev();
}










?>