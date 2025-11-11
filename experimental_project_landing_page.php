<?php
/**
 * File: experimental_project_landing_page.php
 * Description: Application landing page
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


session_start();

if($_SESSION['userpkey']!=""){
	$userpkey = $_SESSION['userpkey'];
}else{
	$userpkey = 0;
}

$p = $_GET['p'] ?? '';
$p = preg_replace('/[^a-zA-Z0-9\-]/', '', $p);
include("prepare_connections.php");

$project_pkey = $db->get_var_prepared("SELECT pkey FROM straboexp.project WHERE uuid=$1", array($p));

if($project_pkey == ""){
	include("includes/mheader.php");
?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<h2>Project Not Found.</h2>
						</header>
					<div class="bottomSpacer"></div>
					</div>
				</div>
<?php
	include("includes/mfooter.php");
	exit();
}

$rows = $db->get_results_prepared("SELECT * FROM straboexp.experiment WHERE project_pkey = $1", array($project_pkey));

if(count($rows) == 0){
	include("includes/mheader.php");
?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<h2>Project Not Found.</h2>
						</header>
					<div class="bottomSpacer"></div>
					</div>
				</div>
<?php
	include("includes/mfooter.php");
	exit();
}

if(count($rows) == 1){
	$uuid = $rows[0]->uuid;
	header("Location: /experimental/view_experiment?u=".$uuid);
	exit();
}

	include("includes/mheader.php");
?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<h2>Select Experiment:</h2>
						</header>

<div class="textRight"><a href="/experimental/download_project?u=<?php echo $p?>">Download Project</a></div>

<div class="table-wrapper">
										<table class="myDataTable">
											<thead>
												<tr>
													<th></th>
													<th>Experiment Name</th>
												</tr>
											</thead>
											<tbody>

<?php

foreach($rows as $row){
?>
												<tr>
													<td style="width: 200px;">
														<a href="/experimental/view_experiment?u=<?php echo $row->uuid?>" target="_blank">View</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<a href="/experimental/download_experiment?u=<?php echo $row->uuid?>">Download</a>
													</td>
													<td>
														<?php echo $row->id?>
													</td>
												</tr>
<?php
}

?>
											</tbody>
										</table>
									</div>

					<div class="bottomSpacer"></div>
					</div>
				</div>
<?php
	include("includes/mfooter.php");

?>