<?php
/**
 * File: debugprojectscript.php
 * Description: Debugging utility and diagnostic tool
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

$project = $straboOut->doiDataOut($project_id);
$json = json_encode($project, JSON_PRETTY_PRINT);

if($raw){
	header('Content-Type: application/json; charset=utf-8');
	echo $json;
	exit();
}else{
	include("includes/mheader.php");
	?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Project Data in Strabo JSON Format</h2>
						</header>

							<section id="content">

	<h3><a href="/debugprojectscript?project_id=<?php echo $project_id?>&r=1" target="_blank">Click here to download raw combined JSON</a></h3>
	<h2>Project JSON:</h2>
	<pre>
	<?php echo $json?>
	</pre>
	<br><br>

							</section>

					<div class="bottomSpacer"></div>

					</div>
				</div>

	<?php
	include("includes/mfooter.php");
}

exit();

?>