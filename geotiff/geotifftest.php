<?php
/**
 * File: geotifftest.php
 * Description: Handles geotifftest operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("../includes/config.inc.php");
include("../db.php");

$count=$db->get_var("select count(*) from users");

echo "count: $count";

exit();
session_start();

//test geotiff file

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "<pre>";
}

dumpVar($_SESSION);

exec("/usr/bin/gdalinfo LindsborgRectified.tif",$gdalinfo);

$upperleft="";

foreach($gdalinfo as $part){
	$part = trim($part);
	if(substr($part,0,10)=="Upper Left"){
		$part=explode("(",$part)[1];
		$part=trim(explode(",",$part)[0]);
		$upperleft=$part;
	}
}

if($upperleft=="" || $upperleft=="0.0"){
	//error
	echo "file is bad";
}else{
	echo "save file<br><br>";
	$gdalinfo = implode("\n",$gdalinfo);
	dumpVar($gdalinfo);
}

?>