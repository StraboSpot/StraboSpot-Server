<?php
/**
 * File: projectadminbuildfielddownload.php
 * Description: Downloads project data in various export formats
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


sleep(3);

$projectid = isset($_GET['p']) ? (int)$_GET['p'] : 0;
if($projectid == 0) die("No project provided");

$inuserpkey = isset($_GET['u']) ? (int)$_GET['u'] : 0;
if($inuserpkey == 0)  die("No user provided.");

ini_set('max_execution_time', 300000);
include("logincheck.php");
include("prepare_connections.php");

include("includes/straboClasses/straboOutputClass.php");
include("doi/doiOutputClass.php");

if(!in_array($userpkey, array(3,9,905,2272,4,342))) exit("Not found.");

$safe_projectid = addslashes($projectid);
$safe_inuserpkey = addslashes($inuserpkey);
$count = $neodb->get_var("Match (p:Project) where p.id = $safe_projectid and p.userpkey = $safe_inuserpkey return count(p)");
if($count == 0) exit("Project not found.");

$strabo->setuserpkey($inuserpkey);

$straboOut = new straboOutputClass($strabo,$_GET);

$doiOut = new doiOutputClass($strabo, $_GET);

$uuid = $uuid->v4();

//Make UUID folder to hold data
mkdir("fieldZips/$uuid");

$project = $straboOut->doiDataOut($projectid);
$json = json_encode($project, JSON_PRETTY_PRINT);

$projectname = $project->projectDb->project->description->project_name;

//Put into postgres here

$projectfoldername = $straboOut->fixFileName($projectname);
$projectfoldername = date("Y-m-d_gia") . "_" . $projectfoldername;
file_put_contents("fieldZips/$uuid/zipfilename.txt", $projectfoldername);

//Make folder to hold Strabo Field Project
mkdir("fieldZips/$uuid/$projectfoldername");

//Write data to data.json
file_put_contents("fieldZips/$uuid/$projectfoldername/data.json", $json);

//Make images folder
mkdir("fieldZips/$uuid/$projectfoldername/images");

//Copy all images to images folder
$spots = $project->spotsDb;
foreach($spots as $key=>$s){
	$images = $s->properties->images;
	foreach($images as $i){
		$id = $i->id;
		$safe_id = addslashes($id);
		$fn = $neodb->get_var("Match (i:Image) where i.id = $safe_id return i.filename limit 1");
		if($fn != ""){
			copy("dbimages/$fn", "fieldZips/$uuid/$projectfoldername/images/$id.jpg");
		}
	}
}

//Zip project folder
exec("cd fieldZips/$uuid; zip -r field_app_project $projectfoldername 2>&1",$results);

//Move data.json and /images out of subfolder

exec("cd fieldZips/$uuid; rm -rf $projectfoldername",$results);

//create PDF here in doi folder

$out = new stdClass();
$out->Message = "Success!";
$out->uuid = $uuid;
$out = json_encode($out);
header('Content-Type: application/json; charset=utf-8');
echo $out;

?>