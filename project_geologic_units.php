<?php
/**
 * File: project_geologic_units.php
 * Description: Error!
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

$project_id = $_GET['p'];

if($project_id==""){
	echo "no project id provided";exit();
}

if(!is_numeric($project_id)){
	echo "invalid project id.";exit();
}

include("prepare_connections.php");
include("includes/straboClasses/straboOutputClass.php");
include("doi/doiOutputClass.php");

$safe_project_id = addslashes($project_id);
$safe_userpkey = addslashes($userpkey);

$count = $neodb->get_var("Match (p:Project) where p.id = $safe_project_id and p.userpkey = $safe_userpkey return count(p)");
if($count == 0) exit("Project not found.");

$straboOut = new straboOutputClass($strabo,$_GET);

$doiOut = new doiOutputClass($strabo, $_GET);

$uuid = $uuid->v4();

$result = $straboOut->geologicUnitsOut($project_id);

if($result == "empty"){
	include("includes/mheader.php");

	?>

				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Error!</h2>
						</header>
						<div style="text-align:center;margin-bottom:500px;">No Geologic Units Found.</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>

	<?php

	include("includes/mfooter.php");
}

?>