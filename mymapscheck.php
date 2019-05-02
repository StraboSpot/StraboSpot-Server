<?PHP

include("prepare_connections.php");

$hash = pg_escape_string($_GET['hash']);

$count = $db->get_var("select count(*) from geotiffs where hash='$hash'");

if($count > 0){
	header("Good Request", true, 200);
}else{
	header("Not Found", true, 404);
}

?>