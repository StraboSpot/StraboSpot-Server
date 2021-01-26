<?
include("logincheck.php");

//print_r($_SESSION);

include("prepare_connections.php");

/*
Array
(
    [maphash] => 5d6a9557d55e6
    [state] => public
)
*/

$hash = $_GET['maphash'];

if($_GET['state']=='public'){
	$db->query("update geotiffs set ispublic = TRUE where hash='$hash' and userpkey=$userpkey");
	//echo "update geotiffs set ispublic = TRUE where hash='$hash' and userpkey=$userpkey";
}

if($_GET['state']=='private'){
	$db->query("update geotiffs set ispublic = FALSE where hash='$hash' and userpkey=$userpkey");
}




?>