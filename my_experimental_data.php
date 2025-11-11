<?php
/**
 * File: my_experimental_data.php
 * Description: My StraboExperimental Data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

$credentials = $_SESSION['credentials'];
$experimentalrows = $db->get_results_prepared("SELECT pkey, name, notes, to_char(modified_timestamp, 'Month DD, YYYY, HH:MI:SS pm TZ') as timestamp, ispublic FROM straboexp.project WHERE userpkey = $1 ORDER BY modified_timestamp DESC", array($userpkey));
$total=0;

include("adminkeys.php");

$username = $_SESSION['username'];
$apptoken = $uuid = $uuid->v4();
$db->get_var("DELETE from apptokens WHERE created_on < NOW() - INTERVAL '24 hours'");
$db->prepare_query("INSERT INTO apptokens (uuid, email) VALUES ($1, $2)", array($apptoken, $username));
$tokencreds = base64_encode($username."*****".$apptoken);

include("includes/mheader.php");

?>

<script type='text/javascript'>

	function  projectExperimentalPub(projectid){
		if(document.getElementById('switch_'+projectid).checked){
			console.log("https://strabospot.org/experimental/project_public?projectid="+projectid+"&state=public");
			$.get("/experimental/project_public?projectid="+projectid+"&state=public");
		}else{
			console.log("https://strabospot.org/experimental/project_public?projectid="+projectid+"&state=private");
			$.get("/experimental/project_public?projectid="+projectid+"&state=private");
		}
	}

	function doExperimentalProjectDownload(pid, projectname){
		var selected = $('#edl-'+pid).find(":selected").val();
		$('#edl-'+pid).find(":selected").prop('selected', false);

		switch(selected){
			case "newexperiment":
				window.location='/experimental/add_experiment?ppk='+pid;
				break;
			case "delete":
				if (confirm("Are you sure you want to delete project "+projectname+"?") == true) {
					window.location='/experimental/delete_project?ppk='+pid;
				}
				break;
			case "edit":
				window.location='/experimental/edit_project?ppk='+pid;
				break;
			case "download":
				window.location='/experimental/download_project?ppk='+pid;
				break;
			case "json":
				javascript:alert('Coming Soon...');
				break;
			case "plot":
				javascript:alert('Coming Soon...');
				break;
			case "doi":
				window.location='/publish_doi?p='+pid+'&t=e';
				break;
			case "landing":
				window.location='/experimental/landing_redirect?ppk='+pid;
				break;
		}

	}

	function doExperimentalExperimentDownload(pkey, experimentname){
		var selected = $('#exp-'+pkey).find(":selected").val();
		$('#exp-'+pkey).find(":selected").prop('selected', false);

		switch(selected){
			case "view":
				window.location='/experimental/view_experiment?e='+pkey;
				break;
			case "edit":
				window.location='/experimental/edit_experiment?e='+pkey;
				break;
			case "download":
				window.location='/experimental/download_experiment?e='+pkey;
				break;
			case "delete":
				if (confirm("Are you sure you want to delete experiment "+experimentname+"?") == true) {
					window.location='/experimental/delete_experiment?e='+pkey;
				}
				break;
		}
	}

</script>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>My StraboExperimental Data</h2>

						</header>
							<div style="text-align:center;"><a href="experimental/add_project">(Add Project)</a></div>
							<section id="content">

<?php
if(count($experimentalrows)==0){
	?>
		<div style="text-align:center;margin-bottom:500px;">No Projects found.<br>Click <a href="/experimental/add_project">here</a> to add project.</div>
	<?php
}else{

		foreach($experimentalrows as $er){

			if($er->ispublic == "t"){
				$checked = " checked";
			}else{
				$checked = "";
			}

			$projectname = addslashes($er->name);
			$pkey = $er->pkey;
			$modified_timestamp = $er->timestamp;

?>

								<!-- foreach project -->
								<section>
									<h3><?php echo $er->name?></h3>
									<div style="margin-top:-5px" class="myDataTable">
										<ul class="actions MyDataUL">
											<li>Last Modified: <?php echo $modified_timestamp?></li>
											<li>
												<select class="myDataSelect" id="edl-<?php echo $er->pkey?>" onChange="doExperimentalProjectDownload(<?php echo $er->pkey?>,'<?php echo $projectname?>');">
													<option value=""  style="display:none">Options...</option>
													<option value="newexperiment">Add Experiment</option>
													<option value="landing">Landing Page</option>
													<option value="edit">Edit Project</option>
													<option value="download">Download Project</option>
													<option value="delete">Delete Project</option>
													<!--
													<option value="json">Project JSON</option>
													<option value="plot">Plot Data</option>
													-->
													<option value="doi">Get DOI</option>
												</select>
											</li>
											<li>
												<span>Public? </span><label class="switch"><input type="checkbox" name="switch_<?php echo $pkey?>" id="switch_<?php echo $pkey?>" onclick="projectExperimentalPub(<?php echo $pkey?>)"<?php echo $checked?>><div class="slider sliderFront"></div></label>
											</li>
										</ul>
									</div>

								<?php
								$erows = $db->get_results_prepared("SELECT pkey, id, to_char(modified_timestamp, 'Month DD, YYYY, HH:MI:SS pm TZ') as timestamp, json FROM straboexp.experiment WHERE project_pkey = $1 ORDER BY modified_timestamp DESC", array($pkey));
								if(count($erows) > 0){
								?>
									<div class="table-wrapper">
										<table class="myDataTable">
											<thead>
												<tr>
													<th>&nbsp;</th>
													<th>Experiment&nbsp;ID</th>
													<th>Apparatus&nbsp;Type</th>
													<th class="hideSmall">Test&nbsp;Features</th>
													<th class="hideSmall">Data&nbsp;Entered</th>
													<th class="hideSmall">Last&nbsp;Modified</th>
												</tr>
											</thead>
											<tbody>

											<?php
											foreach($erows as $erow){

												//gather data
												$epkey = $erow->pkey;
												$experiment_id = $erow->id;
												$modified_timestamp = $erow->timestamp;

												//Gather apparatus type, test features, data entered
												$json = json_decode($erow->json);
												$apparatus_type = $json->apparatus->type;
												if($apparatus_type == "") $apparatus_type = "N/A";
												$test_features = $json->experiment->features;
												if($test_features == ""){
													$test_features = "N/A";
												}else{
													$test_features = implode(", ", $test_features);
												}

												//Data Entered
												$data_entered = [];
												if($json->facility != "") $data_entered[] = "Facility";
												if($json->apparatus != "") $data_entered[] = "Apparatus";
												if($json->daq != "") $data_entered[] = "DAQ";
												if($json->sample != "") $data_entered[] = "Sample";
												if($json->experiment != "") $data_entered[] = "Experiment";
												if($json->data != "") $data_entered[] = "Data";

												if(count($data_entered) > 0){
													$data_entered = implode(", ", $data_entered);
												}else{
													$data_entered = "N/A";
												}

											?>

												<!-- foreach dataset -->
												<tr>
													<td>
														<select class="myDataSelect" id="exp-<?php echo $epkey?>" onChange="doExperimentalExperimentDownload(<?php echo $epkey?>,'<?php echo $experiment_id?>');">
															<option value="" style="display:none;">Options...</option>
															<option value="view">View</option>
															<option value="edit">Edit</option>
															<option value="download">Download</option>
															<option value="delete">Delete</option>

														</select>
													</td>
													<td nowrap><?php echo $experiment_id?></td>
													<td nowrap><?php echo $apparatus_type?></td>
													<td class="hideSmall"><?php echo $test_features?></td>
													<td class="hideSmall"><?php echo $data_entered?></td>
													<td class="hideSmall" nowrap><?php echo $modified_timestamp?></td>
												</tr>

											<?php
											}
											?>

											</tbody>
										</table>
									</div>

								<?php
								}else{
								?>
									<div class="padLeft padBottom">No datasets exist for this project.</div>
								<?php
								}
								?>

								</section>

<?php
	}//end foreach project
}
?>

							</section>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include("includes/mfooter.php");
?>