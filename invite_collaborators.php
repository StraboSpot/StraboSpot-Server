<?php
/**
 * File: invite_collaborators.php
 * Description: Collaboration management interface
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");
include("prepare_connections.php");

$email = $_SESSION['username'];

$project_id = $_GET['p'] ?? '';
$project_id = preg_replace('/[^a-zA-Z0-9\-]/', '', $project_id);
if($project_id == "") exit("No project id provided.");

$project = $strabo->getProject($project_id);

if($project->Error != "") exit($project->Error);

$project_name = $project->description->project_name;

if($_POST){

	$collaborationlevel = $_POST['collaborationlevel'];

	$addresses = $_POST['addresses'];
	$addresses = explode("\n", $addresses);

	$foundaddresses = [];

	foreach($addresses as $address){
		$address = trim($address);
		if($address != "" && filter_var($address, FILTER_VALIDATE_EMAIL)){
			if($address != $email){
				if(!in_array($address, $foundaddresses)){
					$collaborator_pkey = $db->get_var_prepared("SELECT pkey FROM users WHERE email=$1", array($address));
					if($collaborator_pkey != ""){

						$existcount = $db->get_var_prepared("SELECT count(*) FROM collaborators WHERE strabo_project_id = $1 AND project_owner_user_pkey = $2 AND collaborator_user_pkey = $3", array($project_id, $userpkey, $collaborator_pkey));
						if($existcount == 0){

							$uuid = $strabo->uuid->v4();

							$db->prepare_query("
								INSERT INTO collaborators (
									strabo_project_id,
									project_owner_user_pkey,
									collaborator_user_pkey,
									collaboration_level,
									uuid
								) VALUES ($1, $2, $3, $4, $5)
							", array($project_id, $userpkey, $collaborator_pkey, $collaborationlevel, $uuid));

						}
					}
				}
			}
		}

		$foundaddresses[] = $address;
	}

	header("Location: https://strabospot.org/collaborate?p=$project_id");

	exit();
}

include 'includes/mheader.php';

?>
<script>
	function validateForm() {

		var error = '';

		let addresses = document.getElementById('addresses').value;
		if(addresses == ""){
			error += "Addresses list cannot be blank.\n";
		}

		let collaborationlevel = document.getElementById('collaborationlevel').value;
		if(collaborationlevel == ""){
			error += "Collaboration level cannot be blank.\n";
		}

		if(error != ''){
			alert(error);
			return false;
		}

	}
</script>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Invite Collaborators</h2>
						</header>

<div class="medHeader" style="padding-top:0px;padding-bottom:20px;text-align:center;">
Invite users to collaborate on <?php echo $project_name?>. Enter Strabo email addresse(s), one line at a time.
<div style="padding-top:30px;">

		<form method="post" onsubmit="return validateForm()" >
			<div class="row gtr-uniform gtr-50">
				<div class="col-12">
					<textarea name="addresses" id="addresses" placeholder="Enter Strabo email addresse(s), one line at a time." rows="6"></textarea>
				</div>

				<div class="col-12">
					<select name="collaborationlevel" id="collaborationlevel">
						<option value="">Collaboration Level</option>
						<option value="readonly">Read Only</option>
						<option value="edit">Edit</option>
						<!--<option value="admin">Admin</option>-->
					</select>
				</div>

				<div class="col-12">
					<ul class="actions">
						<li><input type="submit" value="Invite Users" class="primary"></li>
						<li><input type="reset" onclick="window.location='collaborate?p=<?php echo $project_id?>'; return false;" value="Cancel"></li>
					</ul>
				</div>
			</div>
		</form>

</div>
</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include 'includes/mfooter.php';
?>