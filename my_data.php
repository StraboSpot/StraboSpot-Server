<?php
/**
 * File: my_data.php
 * Description: Personal data dashboard and management interface
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


header("Location:my_field_data");
exit();

include("logincheck.php");

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

$projectrows = $neodb->get_results("match (p:Project {userpkey:$userpkey}) optional match (p)-[HAS_DATASET]->(d:Dataset) optional match (d)-[HAS_SPOT]->(s:Spot) with p,d,count(s) as count with p,collect ({d:d,count:count}) as d return p,d order by p.uploaddate desc;");

//September 21, 2021, 8:48 pm UTC +00:00

$microrows = $db->get_results("select id, strabo_id, name, to_char(uploaddate, 'Month DD, YYYY, HH:MI:SS pm TZ') as uploaddate, ispublic, projectjson from micro_projectmetadata where userpkey = $userpkey order by id desc");

$experimentalrows = $db->get_results("select pkey, name, notes, to_char(modified_timestamp, 'Month DD, YYYY, HH:MI:SS pm TZ') as timestamp, ispublic from straboexp.project where userpkey = $userpkey order by modified_timestamp desc");

if(count($microrows) > 0){
	$strabospotprojectheader = "My StraboField Projects";
}else{
	$strabospotprojectheader = "My StraboField Projects";  //"My Projects";
}

$total=0;

include("adminkeys.php");

//Build apptoken here for passing to web app
$username = $_SESSION['username'];
$apptoken = $uuid = $uuid->v4();
$db->get_var("DELETE from apptokens WHERE created_on < NOW() - INTERVAL '24 hours'");
$db->query("insert into apptokens (uuid, email) values ('$apptoken','$username')");
$tokencreds = base64_encode($username."*****".$apptoken);

include 'includes/header.php';
//get groups based on userpkey
?>

<style type="text/css">
 /* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 34px;
  height: 14px;
  border: 1px solid #333;
  border-radius: 7px;
  margin-bottom: -2px;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #f00;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius: 5px;

}

.slider:before {
  position: absolute;
  content: "";
  height: 6px;
  width: 6px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius:3px;
}

input:checked + .slider {
  background-color: #79b22e;
}

input:focus + .slider {
  box-shadow: 0 0 1px #79b22e;
}

input:checked + .slider:before {
  -webkit-transform: translateX(20px);
  -ms-transform: translateX(20px);
  transform: translateX(20px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.selecty {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	padding: 0px 5px 0px 5px;
	border-radius: 4px;
	border: 1px solid #999;
	background-color: #DDD; /*#f64c3f*/
	color: #666;
	width: 120px;
	height: 20px;
	text-align: center;
	
}

.downloadselect {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	padding: 0px 5px 0px 5px;
	border-radius: 4px;
	border: 1px solid #999;
	background-color: #DDD; /*#f64c3f*/
	color: #666;
	width: 80px;
	height: 20px;
	text-align: center;
	font-weight: bold;
	
}

</style>

<script src='/assets/js/jquery/jquery.min.js'></script>
<script src="/assets/js/featherlight/featherlight.js"></script>

<script type='text/javascript'>

	function  moveDataset(datasetid){
		var e = document.getElementById("dataset"+datasetid);
		var newproject = e.options[e.selectedIndex].value;

		if(newproject != "" && newproject != "null"){
			document.location.href = "move_dataset?did="+datasetid+"&pid="+newproject;
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

	function  projectMicroPub(projectid){
		if(document.getElementById('switch_'+projectid).checked){
			console.log("https://strabospot.org/micro_project_public?projectid="+projectid+"&state=public");
			$.get("/micro_project_public?projectid="+projectid+"&state=public");
		}else{
			console.log("https://strabospot.org/micro_project_public?projectid="+projectid+"&state=private");
			$.get("/micro_project_public?projectid="+projectid+"&state=private");
		}
	}

	function  projectExperimentalPub(projectid){
		if(document.getElementById('switch_exp_'+projectid).checked){
			console.log("https://strabospot.org/experimental/project_public?projectid="+projectid+"&state=public");
			$.get("/experimental/project_public?projectid="+projectid+"&state=public");
		}else{
			console.log("https://strabospot.org/experimental/project_public?projectid="+projectid+"&state=private");
			$.get("/experimental/project_public?projectid="+projectid+"&state=private");
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
		}else if(selected=="devgeojson"){
			window.location='/searchdownload?type=devgeojson&userpkey=<?php echo $userpkey?>&dsids='+id;
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
				let editurl = "https://app2.strabospot.org/index.html#/app/manage-project?credentials=<?php echo $tokencreds?>&projectid="+pid+"&r="+randnum;
				window.open(editurl,'_blank', 'width=800,height=600,menubar=yes,toolbar=yes');
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
		}
	}

	function doExperimentalProjectDownload(pid, projectname){
		var selected = $('#edl-'+pid).find(":selected").val();
		$('#edl-'+pid).find(":selected").prop('selected', false);

		switch(selected){
			case "newexperiment":
				window.location='https://strabospot.org/experimental/add_experiment?ppk='+pid;
				break;
			case "delete":
				if (confirm("Are you sure you want to delete project "+projectname+"?") == true) {
					window.location='/experimental/delete_project?ppk='+pid;
				}
				break;
			case "edit":
				window.location='/experimental/edit_project?ppk='+pid;
				break;
			case "json":
				window.location='/experimental/download_project?ppk='+pid;
				break;
			case "plot":
				javascript:alert('Coming Soon...');
				break;
			case "doi":
				window.open('https://strabospot.org/publish_doi?p='+pid+'&t=e','_blank');
				break;
		}

	}

	function doMicroProjectDownload(pid, projectname){
		var selected = $('#mdl-'+pid).find(":selected").val();
		$('#mdl-'+pid).find(":selected").prop('selected', false);

		switch(selected){
			case "delete":
				if (confirm("Are you sure you want to delete project "+projectname+"?") == true) {
					window.location='/delete_micrograph_project?ppk='+pid;
				}
				break;
			case "doi":
				window.open('https://strabospot.org/publish_doi?p='+pid+'&t=m','_blank');
				break;
		}

	}

</script>

<?php
if(count($experimentalrows) > 0){
?>

<a name="straboexperimental"></a><h2>My StraboExperimental Projects: <span style="font-size:16px;"><a href="experimental/add_project">(Add Project)</a></span></h2>
<div style ="padding-left:0px;padding-top:10px;padding-bottom:20px;">
<?php

	if(count($experimentalrows)==0){
		?>
			No Experimental Projects found. Click <a href="experimental/add_project">here</a> to add project.
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
			<span>
			<h3 style="font-size:1.7em;"><?php echo $er->name?></h3>

			<div align="left"><h3>Last Modified: <?php echo $modified_timestamp?>

			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

			<?php
			if(!in_array($userpkey, array(3, 7724000, 8617))){
			?>

			<a href="experimental/add_experiment?ppk=<?php echo $er->pkey?>">Add Experiment</a>
			| <a href="experimental/edit_project?ppk=<?php echo $er->pkey?>">Edit</a>
			| <a href="experimental/delete_project?ppk=<?php echo $er->pkey?>" OnClick="return confirm('Are you sure you want to delete <?php echo $projectname?>?')">Delete</a>
			| <a href="/experimental/download_project?ppk=<?php echo $er->pkey?>">JSON</a>
			| <a href="javascript:alert('Coming Soon...');">Plot Data</a>

			<?php
			}
			?>

			<?php
			if(in_array($userpkey, array(3, 7724000, 8617))){
			?>

						<select class="downloadselect" id="edl-<?php echo $er->pkey?>" onChange="doExperimentalProjectDownload(<?php echo $er->pkey?>,'<?php echo $projectname?>');">
							<option value=""  style="display:none">Options...</option>
							<option value="newexperiment">Add Experiment</option>
							<option value="edit">Edit Project</option>
							<option value="delete">Delete Project</option>
							<option value="json">Project JSON</option>
							<option value="plot">Plot Data</option>
							<option value="doi">Get DOI</option>
						</select>
			<?php
			}
			?>

			| <span style="color:red;">Public?</span>

			<label class="switch"><input type="checkbox" name="switch_exp_<?php echo $pkey?>" id="switch_exp_<?php echo $pkey?>" onclick="projectExperimentalPub(<?php echo $pkey?>)"<?php echo $checked?>><div class="slider"></div></label>
			<!--<label class="switch"><input type="checkbox"><div class="slider round"></div></label>-->

			</h3></div>

			</span>
			<?php

			$erows = $db->get_results("select pkey, id, to_char(modified_timestamp, 'Month DD, YYYY, HH:MI:SS pm TZ') as timestamp, json from straboexp.experiment where project_pkey = $pkey order by modified_timestamp desc");
			if(count($erows) > 0){
				?>
				<div class="strabotable" style="margin-left:0px;margin-top:3px;">
					<table>

						<tr>
							<td>&nbsp;</td>
							<td>Experiment ID</td>
							<td>Apparatus Type</td>
							<td>Test Features</td>
							<td>Data Entered</td>
							<td>Last Modified</td>
						</tr>
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
						<tr>
							<td nowrap>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<!--<a href="https://strabospot.org/experimental/view_experiment?e=<?php echo $epkey?>">View</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
								<a href="https://strabospot.org/experimental/view_experiment?e=<?php echo $epkey?>">View</a>&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="https://strabospot.org/experimental/edit_experiment?e=<?php echo $epkey?>">Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="https://strabospot.org/experimental/download_experiment?e=<?php echo $epkey?>">Download</a>&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="https://strabospot.org/experimental/delete_experiment?e=<?php echo $epkey?>" OnClick="return confirm('Are you sure you want to delete <?php echo $experiment_id?>?')">Delete</a>&nbsp;&nbsp;&nbsp;&nbsp;

							</td>
							<td nowrap><?php echo $experiment_id?></td>
							<td nowrap><?php echo $apparatus_type?></td>
							<td><?php echo $test_features?></td>
							<td><?php echo $data_entered?></td>
							<td nowrap><?php echo $modified_timestamp?></td>
						</tr>
				<?php
				}
				?>
					</table>
				</div>
				<br>
				<?php
			}else{
			?>
			<div style="padding-top:10px; padding-left:20px;">
			No experiments found for <?php echo $er->name?>. Click  <a href="experimental/add_experiment?ppk=<?php echo $er->pkey?>">here</a> to add experiment.
			</div>
			<?php
			}

		}//end foreach
	}//end if count rows
?>
</div>
<?php
}//end if admin
?>

<?php
if(count($microrows) > 0){
?>
<a name="strabomicro"></a>
<h2>My StraboMicro Projects:</h2>
<div style ="padding-left:0px;padding-top:10px;">
<?php

	foreach($microrows as $mr){

		if($mr->ispublic == "t"){
			$checked = " checked";
		}else{
			$checked = "";
		}

		$projectname = $mr->name;
		$projectid = $mr->id;
		$strabo_id = $mr->strabo_id;

		$micrographcount = 0;
		$spotcount = 0;

		$pdata = json_decode($mr->projectjson);
		foreach($pdata->datasets as $d){
			foreach($d->samples as $s){
				foreach($s->micrographs as $m){
					$micrographcount++;
					foreach($m->spots as $sp){
						$spotcount++;
					}
				}
			}
		}

		if(is_dir("straboMicroFiles/$projectid/webImages")){
			$murl = "https://strabospot.org/straboMicroView/view?p=$projectid";
		}else{
			$murl = "https://strabospot.org/microproject?id=$projectid";
		}

		

		?>
		<span>
		<h3 style="font-size:1.7em;"><?php echo $mr->name?></h3>

		<div align="left"><h3>Upload Date: <?php echo $mr->uploaddate?>

		<?php
		if(in_array($userpkey, array(2, 8617))){
		?>

					<select class="downloadselect" id="mdl-<?php echo $projectid?>" onChange="doMicroProjectDownload(<?php echo $projectid?>,'<?php echo $projectname?>');">
						<option value=""  style="display:none">Options...</option>
						<option value="delete">Delete Project</option>
						<option value="doi">Get DOI</option>
					</select>
		<?php
		}
		?>

		| <span style="color:red;">Public?</span>

		<label class="switch"><input type="checkbox" name="switch_<?php echo $projectid?>" id="switch_<?php echo $projectid?>" onclick="projectMicroPub(<?php echo $projectid?>)"<?php echo $checked?>><div class="slider"></div></label>
		<!--<label class="switch"><input type="checkbox"><div class="slider round"></div></label>-->

		</h3></div>

		</span>

		<div class="strabotable" style="margin-left:0px;margin-top:3px;">
			<table>

				<tr>
					<td style="width:300px;">&nbsp;</td>
					<td>Num Micrographs</td>
					<td>Num Spots</td>
				</tr>

				<tr>
					<td nowrap>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="<?php echo $murl?>" target="_blank">View</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="download_micro_file?project_id=<?php echo $projectid?>">Download</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="share_micro_file?project_id=<?php echo $projectid?>">Share</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php
		if(in_array($userpkey, array(3, 7724000, 8617))){
		?>
						<a href="publish_doi?p=<?php echo $projectid?>&t=m">Get DOI</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<?php
		}
		?>
						<a href="delete_micrograph_project?project_id=<?php echo $strabo_id?>" OnClick="return confirm('Are you sure you want to delete <?php echo $projectname?>?')">Delete</a>
					</td>
					<td><?php echo $micrographcount?></td>
					<td><?php echo $spotcount?></td>
				</tr>

			</table>
		</div>
		<div>&nbsp;</div>

		<?php

	}

	?>
	<div style="padding-top:50px;"></div>
	<?php

}
?>

<h2><?php echo $strabospotprojectheader?>: <span style="font-size:16px;"><a href="new_project">(Add Project)</a></span></h2>

<div style ="padding-left:0px;padding-top:20px;">
<?php

if(count($projectrows)==0){
	?>
		No Projects found. Click <a href="new_project">here</a> to add project.
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
		$uploaddate = date("F j, Y, g:i a T P", $pvals["uploaddate"]);
		$projectname = $pvals["desc_project_name"];

		$drows=$projectrow->get("d");

		$datasetcount = count($drows);

		?>

		<span>

		<h3 style="font-size:1.7em;"><?php echo $projectname?></h3>

		<div align="left" style="margin-bottom:3px;"><h3>Last Uploaded: <?php echo $uploaddate?>

		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

		<?php
		$origlink = "https://app.strabospot.org/indexWeb.html#/app/manage-project?credentials=".$credentials."&projectid=".$projectid."&r=".rand(111111111,999999999);
		$origapp2link = "https://app2.strabospot.org/index.html#/app/manage-project?credentials=".$credentials."&projectid=".$projectid."&r=".rand(111111111,999999999);
		$link = "https://app.strabospot.org/indexWeb.html#/app/manage-project?credentials=".$tokencreds."&projectid=".$projectid."&r=".rand(111111111,999999999);
		$app2link = "https://app2.strabospot.org/index.html#/app/manage-project?credentials=".$tokencreds."&projectid=".$projectid."&r=".rand(111111111,999999999);
		?>

		<?php
		
		?>

		<?php
		
		?>

		<?php
		if(!in_array($userpkey, array(3, 7724000))){
		?>

		<a href="<?php echo $app2link?>" onclick="window.open('<?php echo $app2link?>','_blank', 'width=800,height=600,menubar=yes,toolbar=yes'); return false;">View/Edit/Add Data</a>

		&nbsp;|&nbsp;<a href="delete_project?id=<?php echo $projectid?>" OnClick="return confirm('Are you sure you want to delete <?php echo $projectname?>?')">Delete</a>

		| <a href="debugproject/<?php echo $projectid?>" target="_blank">JSON</a>

		<?php
		}
		?>

		<?php
		if(in_array($userpkey, array(3, 7724000))){
		?>

					<select class="downloadselect" id="pdl-<?php echo $projectid?>" onChange="doProjectDownload(<?php echo $projectid?>,'<?php echo $projectname?>');">
						<option value=""  style="display:none">Options...</option>
						<option value="edit">View/Edit/Add Data</option>
						<option value="delete">Delete Project</option>
						<option value="field">Download/Share StraboMobile Project File</option>
						<option value="doi">Get DOI for Project</option>
						<option value="json">Download Project in Strabo JSON Format</option>
					</select>
		<?php
		}
		?>

		| <span style="color:red;">Public?</span>

		<label class="switch"><input type="checkbox" name="switch_<?php echo $projectid?>" id="switch_<?php echo $projectid?>" onclick="projectPub(<?php echo $projectid?>)"<?php echo $checked?>><div class="slider"></div></label>
		<!--<label class="switch"><input type="checkbox"><div class="slider round"></div></label>-->

		</h3>
		</div>

		</span>

		<?php

		if($drows[0]["d"]){

		?>

			<div class="strabotable" style="margin-left:0px;">

			<table>

			<tr>
				<td>&nbsp;</td>
				<td style="width:140px;">Download</td>
				<td>Dataset Name</td>
				<td style="width: 90px;">Num Spots</td>
				<?php
				if(count($projectrows)>1){
				?>
				<td style="width:120px;">&nbsp;</td>
				<?php
				}
				?>
				<td style="width:220px;">Last Modified</td>
			</tr>

			<?php
			foreach($drows as $d){

			$featurecount = $d["count"];

			if($d["d"]){

			$dvals = $d["d"]->values();

			}

			$id = $dvals["id"];
			$featuretype = ucfirst($dvals["featuretype"]);
			$uploaddate = date("F j, Y, g:i a T P", $dvals["datecreated"]);
			$modified_timestamp = date("F j, Y, g:i a T P", substr($dvals["modified_timestamp"],0,10));
			$name = $dvals["name"];

			?>

			<tr>
				<td style="width:100px;">
					<?php
					
					?>
					&nbsp;&nbsp;&nbsp;
					<a href="delete_dataset?id=<?php echo $id?>" OnClick="return confirm('Are you sure you want to delete <?php echo $name?>?')">Delete</a>
					&nbsp;
					&nbsp;
					<a href="edit_dataset?id=<?php echo $id?>" >Edit</a>
				</td>
				<td style = "white-space: nowrap;">
					<?php
					if($featurecount > 0){

					?>
					<!--
					<a href="exportshapefile?id=<?php echo $id?>">Shapefile</a>&nbsp;&nbsp;&nbsp;
					<a href="exportkml?id=<?php echo $id?>">KML</a>&nbsp;&nbsp;&nbsp;
					<a href="datasetxls?id=<?php echo $id?>">XLS</a>&nbsp;&nbsp;&nbsp;
					<a href="stereonetoutput/<?php echo $id?>">Stereonet</a>&nbsp;<img width="15" height="15" src="includes/images/questionmark.png" onClick="$.featherlight('https://strabospot.org/stereonetdoc');" />
					-->

					<!--Download:-->
					<select class="selecty" id="dl-<?php echo $id?>" onChange="doDownload(<?php echo $id?>);">
						<option value="" style="display:none;">Download...</option>
						<option value="shapefile">Shapefile</option>
						<option value="kml">KMZ</option>
						<option value="xls">XLS</option>
						<option value="stereonet">Stereonet Mobile</option>
						<option value="fieldbook">Field Book</option>
						<option value="strat_sections">Strat Section(s)</option>
						<option value="download_images">Download Photos</option>
						<option value="landing_page">Landing Page</option>
						<option value="sample_list">Sample List</option>
						<option value="geojson">GeoJSON</option>
						<?php
						if($userpkey==3 || $userpkey == 9 || $userpkey == 3){
						?>
						<option value="fieldbookdev">Field Book Dev</option>
						<option value="shapefiledev">Shapefile Dev</option>
						<option value="xlsdev">XLS Dev</option>
						<option value="image_basemaps">Image Basemaps</option>
						<option value="kml_dev">KML Dev</option>
						<option value="devgeojson">devGeoJSON</option>
						<?php
						}
						?>
						<!--<option value="debug">Debug</option>-->
					</select>

					<?php
					}else{
					?>
					No Spots.
					<?php
					}
					?>
				</td>
				<td>
					<?php echo $name?>
				</td>
				<td>
					<?php echo $featurecount?>
				</td>
				<?php
				if(count($projectrows)>1){
				?>
				<td>
				<select id="dataset<?php echo $id?>" onchange="moveDataset(<?php echo $id?>)" style ="-webkit-appearance: none; -moz-appearance: none; appearance: none; padding: 1px; width:100px;">
					<option value="" style="display:none;">Move to...
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
				<?php
				}
				?>
				<td>
					<?php echo $modified_timestamp?>
				</td>
			</tr>

			<?php
			}
			?>

			</table>

			</div><br><br>
		<?php

		}else{

		?>
		<div style="padding-left:30px;"><h3>No datasets exist in this project. </h3></div>
		<?php

		}

	}

}// end if count projectrows

?>
</div>
<?php

?>

<!--
<?php echo $total?>
-->

<?php
include 'includes/footer.php';
?>