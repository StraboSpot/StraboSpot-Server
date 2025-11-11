<?php
/**
 * File: accept_collaboration.php
 * Description: Accepts collaboration requests
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

$referer = $_SERVER['HTTP_REFERER'] ?? '';

$project_id = $_GET['p'] ?? '';
if(!is_numeric($project_id) || $project_id == "") exit("Invalid project id provided.");

$uuid = $_GET['u'] ?? '';
$uuid = preg_replace('/[^a-zA-Z0-9\-]/', '', $uuid);
if($uuid == "") exit("No uuid provided.");

$count = $db->get_var_prepared("SELECT count(*) FROM collaborators WHERE uuid = $1", array($uuid));
if($count == 0){
	sleep(1);
	exit("Collaboration Invite not Found.");
}

/*
Check here to see if user already has a project with this project_id.
If so, exit.
*/

$querystring = "MATCH (n:Project) WHERE n.id = $project_id and n.userpkey = $userpkey RETURN n;";

$records = $neodb->query($querystring);

$count=count($records);

if($count > 0){
	$record = $records[0];
	$project = $record->get("n");
	$properties = $project->values();
	$project_name = $properties['desc_project_name'];

	//Also get User information
	$row = $db->get_row_prepared("
		SELECT * FROM
		users u,
		collaborators c
		WHERE
		u.pkey = c.project_owner_user_pkey
		AND c.uuid = $1
		LIMIT 1
	", array($uuid));

	$owner_name = $row->firstname." ".$row->lastname;

include 'includes/mheader.php';

?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Error!</h2>
						</header>

<div class="row" style="justify-content: center;">
	<div class="col-6 col-12-small">
		<ul class="actions fit small">
			<li>
				You already have a copy of <?php echo $project_name?> in your account. In order to collaborate on the project <?php echo $project_name?> owned by <?php echo $owner_name?>, you must not have a copy of this project in your account. Please delete your own copy of <?php echo $project_name?> in order to collaborate.
			</li>
		</ul>
	</div>
</div>

<div class="row" style="justify-content: center;">
	<div class="col-2 col-12-small">
		<ul class="actions fit small">
			<li><a href="my_field_data2" class="button primary fit small">Continue</a></li>
		</ul>
	</div>
</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include 'includes/mfooter.php';

		}else{

			echo "accept here";

			$db->prepare_query("UPDATE collaborators SET accepted = true, accepted_date = now() WHERE uuid = $1", array($uuid));

			header("Location: $referer");

		}

?>