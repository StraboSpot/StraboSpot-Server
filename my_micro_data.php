<?php
/**
 * File: my_micro_data.php
 * Description: Personal Strabo Micro data dashboard and project listing
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
$microrows = $db->get_results_prepared("SELECT id, strabo_id, name, to_char(uploaddate, 'Month DD, YYYY, HH:MI:SS pm TZ') as uploaddate, ispublic, projectjson FROM micro_projectmetadata WHERE userpkey = $1 ORDER BY id DESC", array($userpkey));
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
	function  projectMicroPub(projectid){
		if(document.getElementById('switch_'+projectid).checked){
			console.log("https://strabospot.org/micro_project_public?projectid="+projectid+"&state=public");
			$.get("/micro_project_public?projectid="+projectid+"&state=public");
		}else{
			console.log("https://strabospot.org/micro_project_public?projectid="+projectid+"&state=private");
			$.get("/micro_project_public?projectid="+projectid+"&state=private");
		}
	}

	function doMicroProjectDownload(pkey, projectname, murl, pid){

		var selected = $('#mdl-'+pkey).find(":selected").val();
		$('#mdl-'+pkey).find(":selected").prop('selected', false);
		switch(selected){
			case "view":
				window.location=murl;
				break;
			case "download":
				window.location='/download_micro_file?project_id='+pkey;
				break;
			case "share":
				window.location='/share_micro_file?project_id='+pkey;
				break;
			case "doi":
				window.location='/publish_doi?p='+pkey+'&t=m';
				break;
			case "delete":
				if (confirm("Are you sure you want to delete project "+projectname+"?") == true) {
					window.location='/delete_micrograph_project?project_id='+pid;
				}
				break;
		}

	}
</script>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>My StraboMicro Data</h2>
						</header>

							<section id="content">

<?php
if(count($microrows)==0){
	?>
		<div style="text-align:center;margin-bottom:500px;">No Projects found.</div>
	<?php
}else{

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
			$murl = "straboMicroView/view?p=$projectid";
		}else{
			$murl = "microproject?id=$projectid";
		}

?>

								<!-- foreach project -->
								<section>
									<h3><?php echo $mr->name?></h3>
									<div style="margin-top:-5px" class="myDataTable">
										<ul class="actions MyDataUL">
											<li><h3>Upload Date: <?php echo $mr->uploaddate?></li>
											<li>
												<span>Public? </span><label class="switch"><input type="checkbox" name="switch_<?php echo $projectid?>" id="switch_<?php echo $projectid?>" onclick="projectMicroPub(<?php echo $projectid?>)"<?php echo $checked?>><div class="slider sliderFront"></div></label>
											</li>
										</ul>
									</div>

									<div class="table-wrapper">
		<div class="strabotable" style="margin-left:0px;margin-top:3px;">
			<table>

				<tr>
					<td style="width:300px;">&nbsp;</td>
					<td>Num Micrographs</td>
					<td>Num Spots</td>
				</tr>

				<tr>
					<td nowrap>
						<select class="myDataSelect" id="mdl-<?php echo $projectid?>" onChange="doMicroProjectDownload(<?php echo $projectid?>, '<?php echo $projectname?>', '<?php echo $murl?>', '<?php echo $strabo_id?>');">
							<option value=""  style="display:none">Options...</option>
							<option value="view">View</option>
							<option value="download">Download</option>
							<option value="share">Share</option>
							<option value="doi">Get DOI</option>
							<option value="delete">Delete</option>
						</select>
					</td>
					<td><?php echo $micrographcount?></td>
					<td><?php echo $spotcount?></td>
				</tr>

			</table>
		</div>
									</div>

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