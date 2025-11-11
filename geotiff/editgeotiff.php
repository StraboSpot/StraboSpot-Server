<?php
/**
 * File: editgeotiff.php
 * Description: Edits records in geotiffs table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("../logincheck.php");

$hash = $_GET['hash'] ?? '';
$hash = preg_replace('/[^a-zA-Z0-9\-]/', '', $hash);
$userpkey = (int)$_SESSION['userpkey'];

include("../includes/config.inc.php");
include("../db.php");

$row = $db->get_row_prepared("SELECT * FROM geotiffs WHERE hash=$1 AND userpkey=$2", array($hash, $userpkey));

$pkey=$row->pkey;
$name=$row->name;

if($row->pkey==""){
	include("../includes/mheader.php");
	?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Error</h2>
						</header>
						GeoTIFF not found.
					<div class="bottomSpacer"></div>

					</div>
				</div>
	<?php
	include("../includes/mfooter.php");
	exit();
}

if($_POST['submit']){

	$mapname = $_POST['mapname'] ?? '';

	$db->prepare_query("UPDATE geotiffs SET name=$1 WHERE hash=$2 AND userpkey=$3", array($mapname, $hash, $userpkey));
	header("Location: /geotiff");

	exit();

}

include("../includes/mheader.php");

?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Edit Map Name</h2>
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
				<li><input type="text" name="mapname" value="<?php echo $name?>"></li>
				<li>
					<input type="submit" name="submit" value="Save">
				</li>
				<li>
					<input type="button" name="cancel" value="Cancel" onclick="window.history.back();">
				</li>
			</ul>
		</div>

	</form>
</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include("../includes/mfooter.php");
?>