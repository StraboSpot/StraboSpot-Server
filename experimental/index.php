<?php
/**
 * File: index.php
 * Description: Main page or directory index
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../includes/header.php");
?>
<link rel="stylesheet" href="/experimental/experimental.css" type="text/css" />
<script src='/experimental/experimental.js'></script>
<script src='/assets/js/jquery/jquery.min.js'></script>

<div class="topTitle">StraboExperimental</div>

<div style="padding-top:20px;"></div>

<div class="frontPageGrid">
	<div class="frontBoxRow">
		<div class="frontBoxCell">
			<a href="add_project"><img title="Start New Project" class="frontBoxImage" src="gridImages/Homepage_Button_1new.png"/></a>
		</div>
		<div class="frontBoxCell">
			<a href="/my_experimental_data"><img class="frontBoxImage" src="gridImages/Homepage_Button_2new.png"/></a>
		</div>
	</div>
	<div class="frontBoxRow">
		<div class="frontBoxLabel">
			Start New Project
		</div>
		<div class="frontBoxLabel">
			Continue Project
		</div>
	</div>
	<div class="frontBoxRow">
		<div class="frontBoxCell">
			<a href="/fullsearch" target="_blank"><img class="frontBoxImage" src="gridImages/Homepage_Button_3new.png"/></a>
		</div>
		<div class="frontBoxCell">
			<a href="apparatus_repository"><img class="frontBoxImage" src="gridImages/Homepage_Button_4new.png"/></a>
		</div>
	</div>
	<div class="frontBoxRow">
		<div class="frontBoxLabel">
			Search Database
		</div>
		<div class="frontBoxLabel">
			Apparatus Repository
		</div>
	</div>
</div>

<div style="text-align:center;margin-bottom:100px;margin-top:30px;">
	<button class="submitButton" style="vertical-align:middle;" onclick="window.open('https://forms.gle/Q594vh2WzQLpHwnq9', '_blank');"><span>Feedback? </span></button>
</div>

<?php
include("../includes/footer.php");
?>