<?php
/**
 * File: my_dois.php
 * Description: My Published Projects
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

$rows = $db->get_results_prepared("SELECT * FROM dois WHERE user_pkey = $1 ORDER BY pkey DESC", array($userpkey));

include "includes/mheader.php";
?>

<link rel="stylesheet" href="/doi/doi.css" type="text/css" />
<script src='/doi/doi.js'></script>
<script src='/assets/js/jquery/jquery.min.js'></script>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>My Published Projects</h2>
						</header>

<?php
if(count($rows) > 0){
?>

	<table class="myDataTable">
		<thead>
		<tr>
			<th>&nbsp;</th>
			<th>DOI</th>
			<th>Project Name</th>
			<th class="hideSmall">Project Type</th>
			<th class="hideSmall">Date Published</th>
		</tr>
		</thead>

<?php
foreach($rows as $row){

	$uuid = $row->uuid;

	if($row->doi_type == "field"){
		$showtype = "StraboMobile";
		$landingPageURL = "https://strabospot.org/doi/detail/$uuid";
	}elseif($row->doi_type == "micro"){
		$showtype = "StraboMicro";
		$landingPageURL = "https://strabospot.org/straboMicroDOI/view?u=$uuid";
	}elseif($row->doi_type == "experimental"){
		$showtype = "StraboExperimental";
		$landingPageURL = "https://strabospot.org/experimental/doi_$uuid";
	}

	$sendProjectName = addslashes($row->project_name);

?>

	<tr>
		<td nowrap>
			<a href="<?php echo $landingPageURL?>" target="_blank">View</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="/edit_doi?u=<?php echo $row->uuid?>">Edit</a>&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="#" OnClick="doOpenDeleteModal('<?php echo $row->uuid?>','<?php echo $sendProjectName?>');">Delete</a>
		</td>
		<td nowrap=""><?php echo $row->doi?></div></td>
		<td nowrap=""><?php echo $row->project_name?></td>
		<td nowrap=""><?php echo $showtype?></td>
		<td nowrap="" style="text-align:center;"><?php echo $row->date_created?></td>
	</tr>

<?php
}
?>
	</table>

<?php
}else{
	?>
	No saved DOI projects found.
	<?php
}
?>

<div id="deleteModal" style="display:none;">
	<div class="grayOut"></div>
	<div class="modalBox" id="facilityApparatusModalBox">
		<div class="redCloseBox" onclick="doCancelDeleteModal();">X</div>
		<div class="topTitle" style="padding-top:10px;">Are you sure?</div>
		<div class="warningText">
			Deleting a published project is permanent, and cannot be undone!
		</div>
		<div class="continueText">
			If you are certain that you want to delete this published project, enter the provided code into the box below to confirm.
		</div>
		<div id="deleteTitle" class="projectTitle">
			Delete Project:<br>Foo Project Name Here
		</div>
		<div style="text-align:center;margin-top:10px;">
			<img id="randImage" src="/doi/ri_foo.jpg"/>
		</div>
		<div style="margin-left:30px;margin-top:15px;">
			<input type="text" id="randTextBox" placeholder="Enter number from above...">
		</div>
		<div style="text-align:center;margin-top:10px;">
			<button id="deleteProjectButton" class="submitButton" onclick="console.log('foo');"><span>Delete Project </span></button>
		</div>
		<div style="display:none">
			<input type="text" id="staticRandTextBox" value="12345">
			<input type="text" id="deleteUUID" value="12345">
		</div>
	</div>
</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include "includes/mfooter.php";
?>