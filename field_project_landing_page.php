<?php
/**
 * File: field_project_landing_page.php
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
include("prepare_connections.php");

$project_pkey = $db->get_var_prepared("SELECT project_pkey FROM project WHERE strabo_project_id=$1 AND (ispublic OR user_pkey = $2)",
	array($p, $userpkey));

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

$rows = $db->get_results_prepared("SELECT strabo_dataset_id, dataset_name, (SELECT count(*) FROM spot WHERE dataset_pkey = d.dataset_pkey) as spotcount FROM dataset d WHERE project_pkey = $1",
	array($project_pkey));

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
	$dataset_id = $rows[0]->strabo_dataset_id;
	header("Location: /fieldland/?datasetid=$dataset_id");
	exit();
}

	include("includes/mheader.php");
?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<h2>Select Dataset:</h2>
						</header>

<div class="table-wrapper">
										<table class="myDataTable">
											<thead>
												<tr>
													<th></th>
													<th>Dataset Name</th>
													<th>Spot Count</th>
												</tr>
											</thead>
											<tbody>

<?php

foreach($rows as $row){
?>
												<tr>
													<td><a href="/fieldland/?datasetid=<?php echo $row->strabo_dataset_id?>" target="_blank">View</a></td>
													<td>
														<?php echo $row->dataset_name?>
													</td>
													<td>
														<?php echo $row->spotcount?>
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