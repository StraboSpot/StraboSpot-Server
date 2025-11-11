<?php
/**
 * File: edit_jwt.php
 * Description: Edits records in jwts table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("logincheck.php");

$jwtuuid = $_GET['u'] ?? '';
$jwtuuid = preg_replace('/[^a-zA-Z0-9\-]/', '', $jwtuuid);
$userpkey = $_SESSION['userpkey'] ?? 0;

include("includes/config.inc.php");
include("db.php");

$row = $db->get_row_prepared("SELECT * FROM jwts WHERE uuid=$1 AND user_pkey=$2", array($jwtuuid, $userpkey));

$pkey=$row->pkey;
$note=$row->note;

if($row->pkey==""){
	include("includes/mheader.php");
	?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Error</h2>
						</header>
						JWT not found.
					<div class="bottomSpacer"></div>

					</div>
				</div>
	<?php
	include("includes/mfooter.php");
	exit();
}

if($_POST['submit']){

	$note = $_POST['note'] ?? '';

	$db->prepare_query("UPDATE jwts SET note=$1 WHERE uuid=$2 AND user_pkey=$3", array($note, $jwtuuid, $userpkey));
	header("Location: /my_jwts");

	exit();

}

include("includes/mheader.php");

?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Edit JWT</h2>
						</header>

<style type="text/css">
.button {
	background-color: #2cc426;
	border: none;
	color: white;
	padding: 7px 32px;
	text-align: center;
	text-decoration: none;
	display: inline-block;
	font-size: 16px;
	border-radius: 5px;
}
.button:hover {
	background-color: #2ba027;
	color: white;
}

.cancelbutton {
	background-color: #df1d29;
	border: none;
	color: white;
	padding: 7px 32px;
	text-align: center;
	text-decoration: none;
	display: inline-block;
	font-size: 16px;
	border-radius: 5px;
}
.cancelbutton:hover {
	background-color: #b92a33;
	color: white;
}

#map {
	width:960px;
	height:500px;
	background-color:#EEE;
}

.copybutton {
	width:25px;
	height:25px;
}

.textbox {
	font-size: 1.8em;
}

</style>

<script src='/assets/js/jquery/jquery.min.js'></script>

<div id="successmessage"></div>

<div>
	<form method="POST">

		<div style="margin-top:-5px" class="myDataTable">
			<ul class="actions MyDataUL">
				<li><input type="text" name="note" value="<?php echo $note?>"></li>
				<li>
					<input class="primary" type="submit" name="submit" value="Save">
				</li>
				<li>
					<input class="primary" type="button" name="cancel" value="Cancel" onclick="window.history.back();">
				</li>
			</ul>
		</div>

	</form>
</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include("includes/mfooter.php");
?>