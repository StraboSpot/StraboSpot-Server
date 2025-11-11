<?php
/**
 * File: mapwarperproxy.php
 * Description: JSON data endpoint
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

$data=$_GET['data'];

$extension = explode(".",$data)[1];

$content = file_get_contents("https://mapwarper.net/maps/tile/".$data);

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
