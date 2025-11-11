<?php
/**
 * File: add_jwt.php
 * Description: Adds new records to jwts table(s)
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("logincheck.php");
include("prepare_connections.php");

include_once('includes/jwt/quick-jwt.php');
$qjt = new QuickJWT();

if($_POST['submit']){

	$note = $_POST['note'] ?? '';
	$email = $_SESSION['username'];
	$jwtuuid = $uuid->v4();

	$payload = ['email' => $email, 'note' => $note,'uuid' => $jwtuuid, 'datecreated' => time()];
	$token = $qjt->sign($payload, $jwtsecret);

	$db->prepare_query("
			INSERT INTO jwts (
								user_pkey,
								note,
								jwt,
								uuid
									) VALUES (
								$1,
								$2,
								$3,
								$4
							)
	", array($userpkey, $note, $token, $jwtuuid));

	header("Location: /my_jwts");

	exit();

}

include("includes/mheader.php");

?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Add JWT</h2>
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
				<li><input type="text" name="note" value="<?php echo $note?>" placeholder="Note for JWT..."></li>
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