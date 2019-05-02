<?

//http://mapwarper.net/maps/1608333333.kml

//mwcheck.php

$id = $_GET['id'];

$content = file_get_contents("http://mapwarper.net/maps/$id.kml");

if(strlen($content)>10){
	header("Good Request", true, 200);
}else{
	header("Not Found", true, 404);
}

?>