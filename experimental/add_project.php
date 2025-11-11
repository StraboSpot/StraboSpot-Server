<?php
/**
 * File: add_project.php
 * Description: Creates new projects with initial configuration
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../includes/header.php");
?>
<link rel="stylesheet" href="/experimental/experimental.css" type="text/css" />
<script src='/experimental/experimental.js'></script>
<script src='/assets/js/jquery/jquery.min.js'></script>

<div class="topTitle">New Experimental Project</div>


<!--<a onClick="doTest();">do test</a>-->

<div id="bigWindow">
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Project Info</legend>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Project Name <span class="reqStar">*</span></label>
					<input id="projectName" class="formControl" type="text" value=""></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel">Description</span></label>
					<textarea class="formControl docDescText" data-schemaformat="markdown" id="projectDescription"></textarea>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>

	<div style="text-align:center;">
		<button class="submitButton" style="vertical-align:middle;margin-right:150px;" onclick="javascript:history.back();"><span>Cancel </span></button>
		<button class="submitButton" style="vertical-align:middle" onclick="doSubmitNewProject()"><span>Submit </span></button>
	</div>
</div>

<?php
include("../includes/footer.php");
?>