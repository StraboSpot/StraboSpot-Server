<?php
/**
 * File: projectadmindownloadfieldproject.php
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

if(!in_array($userpkey, array(3,9,905,2272,4,342))) exit("Not found.");

$inuserpkey = isset($_GET['u']) ? (int)$_GET['u'] : 0;
if($inuserpkey == 0)  die("No user provided.");

$count = $neodb->get_var("Match (p:Project) where p.id = $projectid and p.userpkey = $inuserpkey return count(p)");
if($count == 0) die("Project not found.");

$row = $neodb->getNode("Match (p:Project) where p.id = $projectid and p.userpkey = $inuserpkey return p");
$desc = $row['json_description'];
$desc = json_decode($desc);
$projectname = $desc->project_name;
$url = "projectadminbuildfielddownload.php";

include "includes/header.php";

?>
<script>
	function doBuildDownload(){
		jQuery('#loadingmessage').html('<div style="padding-top:20px;"><img src="/assets/js/images/box.gif"></td><td nowrap><h3>Building Project Download.<br>This may take a while.<br>Please wait...</h3></div>');
		jQuery.ajax({
			url : "<?php echo $url?>?p=<?php echo $projectid?>&u=<?php echo $inuserpkey?>",
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
					let html='<button class="bigSubmitButton" style="width:250px;" onclick="copyShareLink(\''+uuid+'\')"><span>Copy Share Link</span></button>';
					html+='<button class="bigSubmitButton" style="width:250px;margin-left:50px;" onclick="downloadZip(\''+uuid+'\')"><span>Download File</span></button>';
					jQuery('#loadingmessage').html(html);
				}
			},
			error: function(){
				//if fails
			}
		});
	}

	function doTest(){
		jQuery('#loadingmessage').html('<div style="padding-top:20px;"><img src="/assets/js/images/box.gif"></td><td nowrap><h3>Building DOI. Please wait...</h3></div>');
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

<h2 style="text-align:center;">Download/Share Project: <?php echo $projectname?></h2>

<div style="width:800px;margin:auto;padding-top:10px;">

<div style="padding-top:10px;padding-bottom:15px;">
	This interface allows you to build a downloadable .zip file containing all of your project's data and imagery that is suitable for importing into the
	StraboMobile field app. Once this file is created, it can be
	downloaded or shared with others. Links will be provided allowing you to download the file or copy a link to share with others.
	<div style="text-align:center;margin-top:10px;font-weigt:bold;font-size:20px;">Please note that this sharing link is only valid for 48 hours.</div>

	<!--
	<div style="padding-left:40px;padding-top:10px;">
		<ol>
			<li>Clicking the button below begins the generation files necessary for publication at <a href="https://osf.io" target="_blank">OSF</a>.</li>
			<li>A snapshot of your StraboSpot project is taken and saved to the StraboSpot server.</li>
			<li>After the snapshot has been created, links are provided to download landing page, PDF, and field project files.</li>
			<li>Once these files are downloaded, they should be uploaded to OSF as a new project.</li>
			<li>The new project at <a href="https://osf.io" target="_blank">OSF</a> can then be published with a DOI.</li>
			<li>The new DOI value can be saved with the archived project at StraboSpot.</li>
		</ol>
	</div>
	-->
</div>

</div>

<div id="loadingmessage" style="text-align:center;">
	<div style="">
		<h2 style="">Ready to Continue?</h2>
	</div>
	<button class="bigSubmitButton" style="width:250px;" onclick="doBuildDownload()"><span>Build Project Files</span></button>
</div>

<div>
	<textarea style="display:none;" id="downloadURL"></textarea>
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

<?php
include "includes/footer.php";
?>