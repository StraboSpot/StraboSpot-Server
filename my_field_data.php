<?php
/**
 * File: my_field_data.php
 * Description: My StraboField Data
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
$safe_userpkey = addslashes($userpkey);
$projectrows = $neodb->get_results("match (p:Project {userpkey:$safe_userpkey}) optional match (p)-[HAS_DATASET]->(d:Dataset) optional match (d)-[HAS_SPOT]->(s:Spot) with p,d,count(s) as count with p,collect ({d:d,count:count}) as d return p,d order by p.uploaddate desc;");
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

	function  moveDataset(datasetid){
		var e = document.getElementById("dataset"+datasetid);
		var newproject = e.options[e.selectedIndex].value;

		if(newproject != "" && newproject != "null"){
			document.location.href = "move_dataset?did="+datasetid+"&pid="+newproject;
		}

	}

	function  devmoveDataset(datasetid){
		var e = document.getElementById("dataset"+datasetid);
		var newproject = e.options[e.selectedIndex].value;

		if(newproject != "" && newproject != "null"){
			console.log("https://strabospot.org/dev_move_dataset?did="+datasetid+"&pid="+newproject);
		}

	}

	function  projectPub(projectid){
		if(document.getElementById('switch_'+projectid).checked){
			console.log("https://strabospot.org/project_public?projectid="+projectid+"&state=public");
			$.get("/project_public?projectid="+projectid+"&state=public");
		}else{
			console.log("https://strabospot.org/project_public?projectid="+projectid+"&state=private");
			$.get("/project_public?projectid="+projectid+"&state=private");
		}
	}

	function doDownload(id){

		var selected = $('#dl-'+id).find(":selected").val();

		$('#dl-'+id).find(":selected").prop('selected', false);

		if(selected=="shapefile"){
			window.location='/chooseshapefile?type=shapefiledev&userpkey=<?php echo $userpkey?>&dsids='+id;
		}else if(selected=="kml"){
			window.location='/searchdownload?type=kml&userpkey=<?php echo $userpkey?>&dsids='+id;
		}else if(selected=="xls"){
			window.location='/searchdownload?type=xls&userpkey=<?php echo $userpkey?>&dsids='+id;
		}else if(selected=="stereonet"){
			window.location='/searchdownload?type=stereonet&userpkey=<?php echo $userpkey?>&dsids='+id;
		}else if(selected=="fieldbook"){
			window.open('/searchdownload?type=fieldbook&userpkey=<?php echo $userpkey?>&dsids='+id);
		}else if(selected=="strat_sections"){
			window.location='/dataset_strat_sections?dataset_id='+id;
		}else if(selected=="image_basemaps"){
			window.location='/image_basemaps?dataset_id='+id;
		}else if(selected=="sample_list"){
			window.location='/searchdownload?type=sample_list&userpkey=<?php echo $userpkey?>&dsids='+id;
		}else if(selected=="dev_sample_list"){
			window.location='/searchdownload?type=dev_sample_list&userpkey=<?php echo $userpkey?>&dsids='+id;
		}else if(selected=="fieldbookdev"){
			window.location='/searchdownload?type=fieldbookdev&userpkey=<?php echo $userpkey?>&dsids='+id;
		}else if(selected=="shapefiledev"){
			window.location='/chooseshapefile?type=shapefiledev&userpkey=<?php echo $userpkey?>&dsids='+id;
		}else if(selected=="landing_page"){
			window.location='/landingpage?dsid='+id;
		}else if(selected=="xlsdev"){
			window.location='/searchdownload?type=xlsdev&userpkey=<?php echo $userpkey?>&dsids='+id;
		}else if(selected=="download_images"){
			window.location='/searchdownload?type=download_images&userpkey=<?php echo $userpkey?>&dsids='+id;
		}else if(selected=="kml_dev"){
			window.location='/searchdownload?type=kml_dev&userpkey=<?php echo $userpkey?>&dsids='+id;
		}else if(selected=="geojson"){
			window.location='/searchdownload?type=geojson&userpkey=<?php echo $userpkey?>&dsids='+id;
		}else if(selected=="geologic_units"){
			window.location='/searchdownload?type=geologic_units&userpkey=<?php echo $userpkey?>&dsids='+id;
		}else if(selected=="debug"){
			alert(id);
		}
	}

	function doProjectDownload(pid, projectname){
		var selected = $('#pdl-'+pid).find(":selected").val();
		$('#pdl-'+pid).find(":selected").prop('selected', false);

		switch(selected){
			case "edit":
				let randnum = Math.floor(Math.random()*90000) + 10000;
				let editurl = "https://app2.strabospot.org/index.html#/app/manage-project?credentials="+tokenCreds+"&projectid="+pid+"&r="+randnum;
				console.log(editurl);
				window.open(editurl, '_blank').focus();
				break;
			case "collaborate":
				window.location='/collaborate?p='+pid;
				break;
			case "delete":
				if (confirm("Are you sure you want to delete project "+projectname+"?") == true) {
					window.location='delete_project?id='+pid;
				}
				break;
			case "field":
				window.location='/download_field_project?p='+pid;
				break;
			case "doi":
				window.location='/publish_doi?p='+pid;
				break;
			case "json":
				window.open('/debugproject/'+pid,'_blank');
				break;
			case "geologic_units":
				window.location='/project_geologic_units?p='+pid;
				break;
		}
	}

</script>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>My StraboField Data</h2>
						</header>

<?php
if(in_array($userpkey, array(3,8988))){

$collabquery = "
SELECT 	c.uuid,
	c.strabo_project_id,
	p.project_name,
	c.collaboration_level,
	u.firstname,
	u.lastname
FROM
	project p,
	collaborators c,
	users u
WHERE
	p.strabo_project_id = c.strabo_project_id AND
	c.project_owner_user_pkey = u.pkey AND
	c.accepted = false AND
	c.collaborator_user_pkey = $1
";

$collabrows = $db->get_results_prepared($collabquery, array($userpkey));

	if(count($collabrows) > 0){
?>

<div>You have been invited to collaborate on the following StraboField Projects:</div>

<div class="table-wrapper">
	<table class="myDataTable">
		<thead>
			<tr>
				<th>Project</th>
				<th class="hideSmall">Type</th>
				<th class="hideSmall">Owner</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>

<?php
foreach($collabrows as $c){

$clevel = $c->collaboration_level;
if($clevel == "readonly") $showlevel = "Read Only";
if($clevel == "edit") $showlevel = "Edit";
if($clevel == "admin") $showlevel = "Admin";

?>
			<!-- foreach collaboration request -->
			<tr>
				<!--<td><?php echo $c->project_name?></td>-->
				<td>This here is a long project title test.</td>
				<td class="hideSmall"><?php echo $showlevel?> </td>
				<td class="hideSmall"><?php echo $c->firstname?> <?php echo $c->lastname?> </td>
				<td><a href="accept_collaboration?p=<?php echo $c->strabo_project_id?>&u=<?php echo $c->uuid?>" class="button primary fit small">Accept</a></td>
				<td><a href="deny_collaboration?p=<?php echo $c->strabo_project_id?>&u=<?php echo $c->uuid?>" class="button primary fit small">Deny</a></td>
			</tr>
<?php
}
?>

		</tbody>
	</table>
</div>

<div style="padding-bottom:100px;"></div>

<?php
	}
}
?>

							<div style="text-align:center;"><a href="/new_project">(Add Project)</a></div>

							<section id="content">

<?php
if(count($projectrows)==0){
	?>
		<div style="text-align:center;margin-bottom:500px;">No Projects found.<br>Click <a href="new_project">here</a> to add project.</div>
	<?php
}else{

	foreach($projectrows as $projectrow){

		$pvals = $projectrow->get("p")->values();

		if($pvals["public"]){
			$checked = " checked";
		}else{
			$checked = "";
		}

		if($userpkey == 3){
		}

		$projectid=$pvals["id"];
		$uploaddate = date("F j, Y, g:i a T", $pvals["uploaddate"]);
		$projectname = $pvals["desc_project_name"];

		$dropdown_projectname = str_replace("'", "", $projectname);

		$drows=$projectrow->get("d");
		$datasetcount = count($drows);

?>

								<!-- foreach project -->
								<section>
									<h3><?php echo $projectname?></h3>
									<div style="margin-top:-5px" class="myDataTable">
										<ul class="actions MyDataUL">
											<li>Last Uploaded: <?php echo $uploaddate?></li>
											<li>
												<select class="myDataSelect" id="pdl-<?php echo $projectid?>" onChange="doProjectDownload(<?php echo $projectid?>,'<?php echo $dropdown_projectname?>');">
													<option value="" style="display:none">Options...</option>
													<option value="edit">View/Edit/Add Data</option>
													<option value="field">Download/Share StraboMobile Project File</option>
													<option value="doi">Get DOI for Project</option>
													<?php
													if(in_array($userpkey, array(3,8988))){
													?>
													<option value="collaborate">Invite Collaborators</option>
													<?php
													}
													?>
													<option value="json">Download Project in Strabo JSON Format</option>
													<option value="geologic_units">Download Geologic Units</option>
													<option value="delete">Delete Project</option>
												</select>
											</li>
											<li>
												<span>Public? </span><label class="switch"><input type="checkbox" name="switch_<?php echo $projectid?>" id="switch_<?php echo $projectid?>" onclick="projectPub(<?php echo $projectid?>)"<?php echo $checked?>><div class="slider sliderFront"></div></label>
											</li>
										</ul>
									</div>

								<?php
								if($drows[0]["d"]){ //If datasets exist
								?>
									<div class="table-wrapper">
										<table class="myDataTable">
											<thead>
												<tr>
													<th></th>
													<th></th>
													<th>Dataset Name</th>
													<th>Spots</th>
													<th class="hideSmall"></th>
													<th class="hideSmall">Modified</th>
												</tr>
											</thead>
											<tbody>

											<?php
											foreach($drows as $d){
											$featurecount = $d["count"];

											if($d["d"]){

											$dvals = $d["d"]->values();

											}

											$id = $dvals["id"];
											$featuretype = ucfirst($dvals["featuretype"]);
											$uploaddate = date("F j, Y, g:i a T P", $dvals["datecreated"]);
											$modified_timestamp = date("F j, Y, g:i a T", substr($dvals["modified_timestamp"],0,10));
											$name = $dvals["name"];

											?>

												<!-- foreach dataset -->
												<tr>
													<td><a href="delete_dataset?id=<?php echo $id?>" OnClick="return confirm('Are you sure you want to delete <?php echo $name?>?')">Delete</a></td>
													<td>
														<select class="myDataSelect" id="dl-<?php echo $id?>" onChange="doDownload(<?php echo $id?>);">
															<option value="" style="display:none;">Download</option>
															<option value="shapefile">Shapefile</option>
															<option value="kml">KMZ</option>
															<option value="xls">XLS</option>
															<option value="stereonet">Stereonet Mobile</option>
															<option value="fieldbook">Field Book</option>
															<option value="strat_sections">Strat Section(s)</option>
															<option value="download_images">Download Photos</option>
															<option value="landing_page">Landing Page</option>
															<option value="sample_list">Sample List</option>
<?php
if($userpkey==3 || $userpkey==3){
?>
															<option value="dev_sample_list">Dev Sample List</option>
<?php
}
?>
															<option value="geojson">GeoJSON</option>
															<option value="image_basemaps">Image Basemaps</option>
														</select>
													</td>
													<td><?php echo $name?></td>
													<td><?php echo $featurecount?></td>
													<td class="hideSmall">
									<?php
									if($userpkey == 3867234){
									?>
														<select id="dataset<?php echo $id?>" onchange="devmoveDataset(<?php echo $id?>)" class="myDataSelect">
									<?php
									}else{
									?>
														<select id="dataset<?php echo $id?>" onchange="moveDataset(<?php echo $id?>)" class="myDataSelect">
									<?php
									}
									?>
															<option value="" style="display:none;">Move To</option>
															<?php

															foreach($projectrows as $pr){

																$pvals = $pr->get("p")->values();
																$thisprojectid=$pvals["id"];
																$thisprojectname = $pvals["desc_project_name"];

																if($thisprojectid!=$projectid){
																?>
																<option value="<?php echo $thisprojectid?>"><?php echo $thisprojectname?>
																<?php
																}
															}

															?>
														</select>
													</td>
													<td class="hideSmall"><?php echo $modified_timestamp?></td>
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

<script type='text/javascript'>

var tokenCreds = "foo";

var intervalID = window.setInterval(refreshLoginToken, 1800000);

function refreshLoginToken() {
	let request = new XMLHttpRequest();
	request.open("GET", "/update_token", true);
	request.responseType = 'json';
	request.onload = () => {
		if (request.status == 200) {
			console.log('response', request.response);
			tokenCreds = request.response.tokencreds;
		}
	}
	request.send();
}

refreshLoginToken();

</script>

<?php
include("includes/mfooter.php");
?>