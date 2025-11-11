<?php
/**
 * File: publish_doi.php
 * Description: Ready to Continue?
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

$projectid = isset($_GET['p']) ? (int)$_GET['p'] : 0;
if($projectid == 0) die("No project provided.");

$type = "field";
if($_GET[t]=="m") $type = "micro";
if($_GET[t]=="e") $type = "experimental";

if($type == "field"){

	$safe_projectid = addslashes($projectid);
	$safe_userpkey = addslashes($userpkey);
	$count = $neodb->get_var("Match (p:Project) where p.id = $safe_projectid and p.userpkey = $safe_userpkey return count(p)");
	if($count == 0) die("Project not found.");

	$row = $neodb->getNode("Match (p:Project) where p.id = $safe_projectid and p.userpkey = $safe_userpkey return p");
	$desc = $row['json_description'];
	$desc = json_decode($desc);
	$projectname = $desc->project_name;
	$url = "build_doi.php";

}elseif($type == "micro"){

	$row = $db->get_row_prepared("SELECT * FROM micro_projectmetadata WHERE id = $1 AND userpkey = $2", array($projectid, $userpkey));
	if(!$row->id)  die("Project not found.");
	$projectname = $row->name;
	$url = "build_m_doi.php";

}elseif($type == "experimental"){

	$row = $db->get_row_prepared("SELECT * FROM straboexp.project WHERE pkey = $1 AND userpkey = $2", array($projectid, $userpkey));
	if(!$row->pkey)  die("Project not found.");
	$projectname = $row->name;
	$url = "build_e_doi.php";

}

include "includes/mheader.php";

?>
<script>
	function doBuildDOI(){
		jQuery('#buildingmessage').html('<div style="padding-top:20px;"><img src="/assets/js/images/box.gif"></td><td nowrap><h3>Building DOI Files.<br>This may take a while.<br>Please wait...</h3></div>');
		jQuery.ajax({
			url : "<?php echo $url?>?p=<?php echo $projectid?>",
			type: "GET",
			processData: false,
			contentType: false,
			success:function(data){
				if(data.Error){
					alert("Error!\n" + data.Error);
				}else{
					console.log(data);
					let newloc = 'edit_doi?u='+data.uuid+'&n=1';
					window.location.href = newloc;
				}
			},
			error: function(){
				//if fails
			}
		});
	}

	function doTest(){
		jQuery('#buildingmessage').html('<div style="padding-top:20px;"><img src="/assets/js/images/box.gif"></td><td nowrap><h3>Building DOI. Please wait...</h3></div>');
	}
</script>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Get DOI for Project: <?php echo $projectname?></h2>
						</header>

							<section id="content">

<div style="margin:auto;padding-top:10px;">

	<div style="text-align:center">
		<a href="https://osf.io" target="_blank">
			<img src="/includes/images/osf_logo_dark.png" width="200px"/>
		</a>
	</div>
	<div style="padding-top:10px;padding-bottom:15px;">
		While StraboSpot does not provide Digital Object Identifiers directly, we recommend using the Open Science Framework (OSF) for publishing project data. The process for creating StraboSpot
		data suitable for publication is as follows:
		<div style="padding-left:40px;padding-top:10px;">
			<ol>
				<li>Clicking the button below begins the generation files necessary for publication at <a href="https://osf.io" target="_blank">OSF</a>.</li>
				<li>A snapshot of your StraboSpot project is taken and saved to the StraboSpot server.</li>
				<li>After the snapshot has been created, links are provided to download landing page and project files.</li>
				<li>Once these files are downloaded, they should be uploaded to OSF as a new project.</li>
				<li>The new project at <a href="https://osf.io" target="_blank">OSF</a> can then be published with a DOI.</li>
				<li>The new DOI value can be saved with the archived project at StraboSpot.</li>
			</ol>
		</div>
	</div>

</div>

<div id="buildingmessage" style="text-align:center;">
	<div style="">
		<h2 style="">Ready to Continue?</h2>
	</div>
	<input class="primary" type="submit" onclick="doBuildDOI()" value="Create DOI Files"></input>
	<!--<button class="bigSubmitButton" style="width:250px;" onclick="doBuildDOI()"><span>Create DOI Files</span></button>-->
</div>

<!--
<div id="loadingmessage" style="text-align:center;">
	<div style="">
		<h2 style="">Ready to Continue?</h2>
	</div>
	<button class="bigSubmitButton" style="width:250px;" onclick="console.log('foo')"><span>Create DOI Files</span></button>
</div>
-->

<!--
<div id="loadingmessage" style="text-align:center;">
	<div style="padding-top:20px;"><img src="/assets/js/images/box.gif"></td><td nowrap><h3>Building DOI. Please wait...</h3></div>
</div>
-->

							</section>

					<div class="bottomSpacer"></div>

					</div>
				</div>
<?php
include "includes/mfooter.php";
?>