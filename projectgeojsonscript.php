<?php
/**
 * File: projectgeojsonscript.php
 * Description: Project Data in Standard GeoJSON Format
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

$project_id = $_GET['project_id'];
$raw = $_GET['r'];

if($project_id==""){
	echo "no project id provided";exit();
}

if(!is_numeric($project_id)){
	echo "invalid project id.";exit();
}

include("prepare_connections.php");
include("includes/straboClasses/straboOutputClass.php");
include("doi/doiOutputClass.php");

$count = $neodb->get_var("Match (p:Project) where p.id = $project_id and p.userpkey = $userpkey return count(p)");
if($count == 0) exit("Project not found.");

$straboOut = new straboOutputClass($strabo,$_GET);

$doiOut = new doiOutputClass($strabo, $_GET);

$uuid = $uuid->v4();

$project = $straboOut->projectGeoJSONOut($project_id);
$json = json_encode($project, JSON_PRETTY_PRINT);

if($raw){
	header('Content-Type: application/json; charset=utf-8');
	echo $json;
	exit();
}else{
	include("includes/header.php");
	?>
	<h1>Project Data in Standard GeoJSON Format</h1>
	<h3>Click <a href="https://strabospot.org/projectgeojsonscript?project_id=<?php echo $project_id?>&r=1" target="_blank">here</a> to download raw combined JSON</h3>
	<h2>Project JSON:</h2>
	<pre>
	<?php echo $json?>
	</pre>
	<br><br>
	<?php
	include("includes/footer.php");
}

exit();

?>