<?
/* 
   This script fixes invalid headers being sent by MapWarper
   by creating our own and proxying the file content.

   http://mapwarper.net/maps/tile/16083/14/2919/6471.png
*/

$data=$_GET['data'];

$extension = explode(".",$data)[1];

$content = file_get_contents("http://mapwarper.net/maps/tile/".$data);

if(strlen($content)>0){

	header("Content-type:image/$extension");
	echo $content;

}else{

	http_response_code(404);
	$out['error']="Mapwarper image not found.";
	header('Content-Type: application/json');
	echo json_encode($out);

}

?>
