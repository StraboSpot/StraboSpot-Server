<?php
/**
 * File: link_to_macrostrat.php
 * Description: Link Strabo Account to Macrostrat
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

	$note = "Macrostrat";
	$email = $_SESSION['username'];
	$jwtuuid = $uuid->v4();

	$payload = ['email' => $email, 'note' => $note,'uuid' => $jwtuuid, 'datecreated' => time()];
	$token = $qjt->sign($payload, $jwtsecret);

	$db->query("
			insert into jwts (

								user_pkey,
								note,
								jwt,
								uuid
									) values (
								$userpkey,
								'$note',
								'$token',
								'$jwtuuid'
							)
	");

	

	//eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE4MDc4MTkxNDczNzUsInBlcnNvbl9pZCI6MTMwMTU4fQ.VnthJyHBgm5BVIHk_E7UYoecdlKbOX55qCK8cT9tWrY

	header("Location: https://dev.rockd.org/login?j=$token");

	exit();

}

include("includes/mheader.php");

?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Link Strabo Account to Macrostrat</h2>
						</header>

<script src='/assets/js/jquery/jquery.min.js'></script>

<div id="successmessage"></div>

<div class="row padBottom">
	<div class="col-12">
		Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ac dolor id felis maximus pulvinar ut ut ante. Donec pharetra nulla ex, a porta sapien imperdiet vitae. Vivamus tempor, mauris in imperdiet rutrum, turpis tellus rhoncus erat, mattis dapibus turpis velit a neque. Etiam ac purus sit amet nunc porttitor hendrerit id in magna. Donec posuere lectus dui, nec faucibus odio maximus quis. Nullam ut lacinia tortor. Vestibulum iaculis urna id quam tempor malesuada. Aenean vitae tristique est, a fringilla elit. Ut eleifend elit iaculis ex consectetur, a porttitor diam vestibulum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; In in arcu in est consequat commodo fermentum ac mauris. Quisque erat est, dictum sed elementum a, mollis eu lacus. Maecenas lobortis purus nibh, id rhoncus augue placerat vitae. Pellentesque sed semper magna. Curabitur scelerisque nulla eleifend dapibus dictum.
	</div>
</div>

<form method="POST">
<div style="text-align: center;" class="row">

	<div class="col-3 hideSmall"></div>

	<div class="col-3 col-12-xsmall padTop15">
		<div><input class="primary" type="submit" name="submit" value="Link Account"></div>
	</div>

	<div class="col-3 col-12-xsmall padTop15">
		<div><input class="primary" type="button" name="cancel" value="Cancel" onclick="window.location.href = '/';"></div>
	</div>

	<div class="col-3 hideSmall"></div>

</div>
</form>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include("includes/mfooter.php");
?>