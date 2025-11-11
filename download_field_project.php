<?php
/**
 * File: download_field_project.php
 * Description: Downloads project data in various export formats
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
	$url = "build_field_download.php";

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

	function devdoBuildDownload(){
		let uuid="Foo";
		let html='<div class="row gtr-uniform gtr-50"><div class="col-6 col-12-xsmall" style="text-align:center;"><input type="submit" onclick="copyShareLink(\''+uuid+'\')" value="Copy Share Link" class="primary"></div><div class="col-6 col-12-xsmall" style="text-align:center;"><input type="submit" onclick="downloadZip(\''+uuid+'\')" value="Download File" class="primary"></div></div>';
		jQuery('#buildingfilesmessage').html(html);
	}

	function doBuildDownload(){
		jQuery('#buildingfilesmessage').html('<div style="padding-top:20px;"><img src="/assets/js/images/box.gif"></td><td nowrap><h3>Building Project Download.<br>This may take a while.<br>Please wait...</h3></div>');
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

					let uuid = data.uuid;

					//Switch to download/share buttons here
					let html='<div class="row gtr-uniform gtr-50"><div class="col-6 col-12-xsmall" style="text-align:center;"><input type="submit" onclick="copyShareLink(\''+uuid+'\')" value="Copy Share Link" class="primary"></div><div class="col-6 col-12-xsmall" style="text-align:center;"><input type="submit" onclick="downloadZip(\''+uuid+'\')" value="Download File" class="primary"></div></div>';
					jQuery('#buildingfilesmessage').html(html);
				}
			},
			error: function(){
				//if fails
			}
		});
	}

	function doTest(){
		jQuery('#buildingfilesmessage').html('<div style="padding-top:20px;"><img src="/assets/js/images/box.gif"></td><td nowrap><h3>Building DOI. Please wait...</h3></div>');
	}

	function copyShareLink(uuid) {
		let textarea = document.getElementById("downloadURL");
		textarea.value = "https://strabospot.org/fdl/"+uuid;
		textarea.style.display = "block";
		textarea.select();
		document.execCommand("copy");
		textarea.style.display = "none";
		alert("Share Link copied to clipboard.");
	}

	function downloadZip(uuid){
		window.location.href = "https://strabospot.org/fdl/"+uuid;
	}

</script>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Download/Share Project: <?php echo $projectname?></h2>
						</header>

							<section id="content">

<div style="padding-top:10px;padding-bottom:15px;">
	This interface allows you to build a downloadable .zip file containing all of your project's data and imagery that is suitable for importing into the
	StraboMobile field app. Once this file is created, it can be
	downloaded or shared with others. Links will be provided allowing you to download the file or copy a link to share with others.
	<div style="text-align:center;margin-top:10px;font-weigt:bold;font-size:20px;">Please note that this sharing link is only valid for 48 hours.</div>
</div>

<div id="buildingfilesmessage" style="text-align:center;">
	<div style="">
		<h2 style="">Ready to Continue?</h2>
	</div>
	<input class="primary" type="submit" onclick="doBuildDownload()" value="Build Project Files"></input>
</div>

<div>
	<textarea style="display:none;" id="downloadURL"></textarea>
</div>

							</section>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include "includes/mfooter.php";
?>