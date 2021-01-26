<?PHP

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
	exit();
}

$hash = pg_escape_string($_GET['hash']);
$userpkey = pg_escape_string($_SESSION['userpkey']);

include("../includes/config.inc.php");
include("../db.php");

//"bbox": "-77.0677446,38.8520027,-76.9661915,38.929003",
// left, bottom, right, top


//    5b7597c754016.tif
$error['error'] = "Map $hash not found.";
$error = json_encode($error);

if($hash!=""){
	$count = $db->get_var("select count(*) from geotiffs where hash = '$hash'");
	if($count > 0){
		if(file_exists("upload/files/$hash.tif")){
			exec("/usr/bin/gdalinfo -json upload/files/$hash.tif", $results);
			$results = implode("\n", $results);
			$results = json_decode($results);
			$results = $results->wgs84Extent->coordinates[0];

			$left = $results[0][0];
			$bottom = $results[1][1];
			$right = $results[2][0];
			$top = $results[0][1];
			
			$bbox = "$left,$bottom,$right,$top";
			$out["data"]["bbox"] = $bbox;
			
			header('Content-Type: application/json');
			echo json_encode($out);

		}else{
			header("HTTP/1.0 404 Not Found");
			header('Content-Type: application/json');
			echo $error;
		}
	}else{
		header("HTTP/1.0 404 Not Found");
		header('Content-Type: application/json');
		echo $error;
	}
}else{
	header("HTTP/1.0 404 Not Found");
	header('Content-Type: application/json');
	echo $error;
}


?>