<?PHP

include("../logincheck.php");


$hash = pg_escape_string($_GET['hash']);
$userpkey = pg_escape_string($_SESSION['userpkey']);

include("../includes/config.inc.php");
include("../db.php");

$row = $db->get_row("select * from geotiffs where hash='$hash' and userpkey=$userpkey");

if($row->pkey!=""){
	$db->query("delete from geotiffs where hash='$hash' and userpkey=$userpkey");
	unlink("/var/www/geotiff/upload/files/$hash.tif");
	unlink("/var/www/geotiff/upload/maps/$hash.map");
	header("Location:/geotiff");
}









?>