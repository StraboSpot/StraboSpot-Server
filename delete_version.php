<?
include("logincheck.php");
include("prepare_connections.php");

$myuuid = $_GET['uuid'];

$count = $db->get_var("select count(*) from versions where userpkey=$userpkey and uuid='$myuuid'");

if($count > 0){
	unlink("versions/$myuuid");
	$db->query("delete from versions where userpkey=$userpkey and uuid='$myuuid'");
}

header("Location:versioning");

?>