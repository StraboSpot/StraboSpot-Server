<?php
/**
 * File: edit_doi.php
 * Description: Edits Digital Object Identifier (DOI) metadata and properties
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

$uuid = isset($_GET['u']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', $_GET['u']) : '';
if($uuid == "") die("No uuid provided.");

//Save here
if(isset($_POST['doi']) && $_POST['doi'] != ""){
	$doi = trim($_POST['doi']);
	$showsaved = true;
	$db->prepare_query("UPDATE dois SET doi = $1 WHERE user_pkey = $2 AND uuid = $3", array($doi, $userpkey, $uuid));
}

$row = $db->get_row_prepared("SELECT * FROM dois WHERE user_pkey = $1 AND uuid = $2", array($userpkey, $uuid));

if($row->doi_type=="field"){
	$landingPageURL = "https://strabospot.org/doi/detail/$uuid";
	$pdfURL = "doi/doisearchdownload?type=pdf&u=$uuid";
	$projectFileURL = "doi/doisearchdownload?type=fieldapp&u=$uuid";
}elseif($row->doi_type=="micro"){
	$landingPageURL = "https://strabospot.org/straboMicroDOI/view?u=$uuid";
	$pdfURL = "doi/doisearchdownload?type=micropdf&u=$uuid";
	$projectFileURL = "doi/doisearchdownload?type=microzip&u=$uuid";
}elseif($row->doi_type=="experimental"){
	$landingPageURL = "https://strabospot.org/experimental/doi_$uuid";
	$projectFileURL = "doi/doisearchdownload?type=expjson&u=$uuid";
}

if($row->pkey == "") die("Project not found.");

include "includes/mheader.php";
?>

<script>
	function copyURL() {
		let textarea = document.getElementById("landingPageURL");
		textarea.style.display = "block";
		textarea.select();
		document.execCommand("copy");
		textarea.style.display = "none";
		alert("Landing page URL copied to clipboard.");
	}
</script>

<?php
if($_GET['n']){
	$headstring = "New DOI: $row->project_name";
}else{
	$headstring = "Edit DOI: $row->project_name";
}
?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2><?php echo $headstring?></h2>
						</header>

<?php
if($_GET['n']){
?>

<div style="text-align:center">
	<a href="https://osf.io" target="_blank">
		<img src="/includes/images/osf_logo_dark.png" width="200px"/>
	</a>
</div>
<div style="text-align:center;padding-top:10px;padding-bottom:15px;">
You can now proceed to <a href="https://osf.io" target="_blank">OSF</a> to create a new DOI project.<br>
Use the links below to gather data for DOI submission.<br>
Once a DOI has been generated, use the form below to update your project.
</div>
<?php
}
?>

<?php
if($showsaved){
?>
<div style="text-align:center;padding-top:10px;padding-bottom:20px;color:#45a819;font-size:24px;">
Success! DOI saved.
</div>
<?php
}
?>

<!--
<form name="savedoiform" method="POST">
	<div class="strabotable" style="margin-left:0px;">
		<table>
			<tbody>
				<tr>
					<td style="width:120px;">&nbsp;</td>
					<td style="width:200px;">Download DOI Data</td>
					<td style="width:300px;">DOI</td>
					<td>Project Name</td>
					<td style="width:200px;">Date Published</td>
				</tr>
				<tr>
					<td style="text-align:center;">
						<a href="<?php echo $landingPageURL?>" target="_blank">View Landing Page</a>&nbsp;&nbsp;&nbsp;
					</td>
					<td style="text-align:center;">
						<a href="#" onClick="copyURL();">Landing Page</a>&nbsp;&nbsp;&nbsp;
						<?php
						if($row->doi_type != "experimental"){
						?>
						<a href="<?php echo $pdfURL?>" target="_blank">PDF</a>&nbsp;&nbsp;&nbsp;
						<?php
						}else{
						?>
						&nbsp;&nbsp;&nbsp;
						<?php
						}
						?>
						<a href="<?php echo $projectFileURL?>" target="_blank">Project File</a>&nbsp;&nbsp;&nbsp;
					</td>
					<td nowrap=""><input type="text" name="doi" size="40" value="<?php echo $row->doi?>"></div></td>
					<td nowrap=""><?php echo $row->project_name?></td>
					<td nowrap=""><?php echo $row->date_created?></td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
-->

<!--

col-3 col-6-medium col-12-xsmall

-->

	<section>
		<form name="savedoiform" method="POST">
			<div class="row gtr-uniform gtr-50">
				<div class="col-12">
					<h3>Project Name:</h3>
				</div>

				<div class="col-12">
					<div><?php echo $row->project_name?></div>
				</div>

				<div class="col-12">
					<h3>View/Download:</h3>
				</div>

				<div class="col-3 col-6-medium col-12-xsmall">
					<a href="<?php echo $landingPageURL?>" target="_blank">View Landing Page</a>
				</div>
				<div class="col-3 col-6-medium col-12-xsmall">
					<a href="#" onClick="copyURL();">Copy Landing Page</a>
				</div>
				<div class="col-3 col-6-medium col-12-xsmall">
					<a href="<?php echo $pdfURL?>" target="_blank">Download PDF</a>
				</div>
				<div class="col-3 col-6-medium col-12-xsmall">
					<a href="<?php echo $projectFileURL?>" target="_blank">Download Project File</a>
				</div>

				<div class="col-12">
					<h3>DOI:</h3>
				</div>

				<div class="col-12">
					<input type="text" name="doi" id="doi" value="<?php echo $row->doi?>" placeholder="Enter DOI here...">
				</div>

				<div class="col-12">
					<h3>Date Published:</h3>
				</div>

				<div class="col-12">
					<div><?php echo $row->date_created?></div>
				</div>

			</div>
		</form>
	</section>

<?php
if($showsaved){
?>

				<div class="col-12" style="text-align: center;">
					<input class="primary" type="submit" onclick="window.location.href = 'my_dois';" value="Continue">
				</div>

<?php
}else{
?>

				<div class="col-12" style="text-align: center;">
					<input class="primary" type="submit" onclick="document.savedoiform.submit()" value="Save">
				</div>

<?php
}
?>

<div>
	<textarea style="display:none;" id="landingPageURL"><?php echo $landingPageURL?></textarea>
</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include "includes/mfooter.php";
?>