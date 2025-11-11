<?php
/**
 * File: share_micro_file.php
 * Description: Strabo Micro project management interface
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

$project_id = isset($_GET['project_id']) ? (int)$_GET['project_id'] : 0;

if($project_id == 0) exit();

$row = $db->get_row_prepared("SELECT * FROM micro_projectmetadata WHERE userpkey=$1 AND id = $2", array($userpkey, $project_id));

if($row->id == "") exit("Project not found.");

$sharekey = $row->sharekey;

include 'includes/mheader.php';

?>

<!-- Main -->
<div id="main" class="wrapper style1">
	<div class="container">

		<header class="major">
			<h2>Share StraboMicro Project</h2>
		</header>

		<style type="text/css">
			.button {
				background-color: #666;
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
				background-color: #000;
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
		</style>

		<script src='/assets/js/jquery/jquery.min.js'></script>

		<script>
			function myCopy() {
				var copyText = document.getElementById('copybox');
				copyText.select();
				document.execCommand("copy");
				$("#successmessage").html('Code&nbsp;'+copyText.value+'&nbsp;copied<br>to&nbsp;clipboard.');
				$("#successmessage").fadeIn();
				$("#successmessage").fadeOut(2000);
			}

			var clipboard = new ClipboardJS('.btn');

			clipboard.on('success', function(e) {
				console.info('Action:', e.action);
				console.info('Text:', e.text);
				console.info('Trigger:', e.trigger);

				$("#successmessage").html('Code&nbsp;'+e.text+'&nbsp;copied<br>to&nbsp;clipboard.');
				$("#successmessage").fadeIn();
				$("#successmessage").fadeOut(3000);

				e.clearSelection();
			});

		</script>

		<div id="successmessage"></div>

		<div style="padding-top:30px;">
		Due to the fact that StraboMicro project files can be quite large and difficult to share, this interface has been created to allow StraboMicro users to share project files using a small, simple code. To share this project with another user, simply copy and paste the code below and send it to the intended recipient. The recipient can then use the File -> Open Shared Project File option in the StraboMicro application to open this project.
		<span style="color:#d61d0f; font-weight:bold;">Please be aware that this system DOES NOT allow two users to work on the same project.</span> The project file downloaded using this code is unique and not tied to the original file.
		</div>

		<div>
			<div align="center" style="padding-top:40px; font-size:1.2em;">
				To share this project file, use the following code:
			</div>

				<p>Share Code: <input type="text" id="copybox" value="<?php echo $sharekey?>" readonly></p>
			<div style="text-align:center;">
				<p><input class="primary btn" type="submit" name="accountsubmit" value="Copy Link" data-clipboard-text="<?php echo $sharekey?>"></p>
			</div>
		</div>

	<div class="bottomSpacer"></div>

	</div>
</div>

<?php
include 'includes/mfooter.php';

?>