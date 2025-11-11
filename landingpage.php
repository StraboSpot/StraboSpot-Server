<?php
/**
 * File: landingpage.php
 * Description: Landing page: StraboField Landing Page
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("includes/mheader.php");
?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>StraboField Landing Page</h2>
						</header>
<?php

$dsid = isset($_GET['dsid']) ? (int)$_GET['dsid'] : 0;

$userpkey = $_SESSION['userpkey'];

//check to see if dataset is public. show error if not

$safe_dsid = addslashes($dsid);
$safe_userpkey = addslashes($userpkey);

$pcount = $neodb->get_var("match (p:Project)-[HAS_DATASET]->(d:Dataset) where d.id = $safe_dsid and p.userpkey = $safe_userpkey and (p.public = true or p.public = 1) return count(p)");

if($pcount == 0){
	$existcount = $neodb->get_var("match (p:Project)-[HAS_DATASET]->(d:Dataset) where d.id = $safe_dsid and p.userpkey = $safe_userpkey return count(p)");
	if($existcount > 0){
		//private
		?>
			<div style="text-align: center; text-transform: uppercase; font-family: 'Raleway', sans-serif; font-size: 20px;">Error: Project is set to private.<br>Please make sure that project is set public to attain public link.</div>
		<?php
	}else{
		//not exists
		?>
			<div style="text-align: center; text-transform: uppercase; font-family: 'Raleway', sans-serif; font-size: 20px;">Dataset not found.</div>

		<?php
	}
	?>
					<div class="bottomSpacer"></div>

					</div>
				</div>
	<?php
	include("includes/mfooter.php");
	exit();
}

function generateRandomString() {
	$characters = '23456789abcdefghjkmnpqrstuvwxyz';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < 5; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

//load hash or create new
$hash = $db->get_var_prepared("SELECT hash FROM landing_pages WHERE datasetid=$1", array($dsid));
if($hash==""){
	//generate new
	$foundHash = false;
	while($foundHash != true){
		$newhash = generateRandomString();
		$hashcount = $db->get_var_prepared("SELECT count(*) FROM landing_pages WHERE hash = $1", array($newhash));
		if($hashcount == 0){
			$hash = $newhash;
			$db->prepare_query("INSERT INTO landing_pages (hash, datasetid) VALUES ($1, $2)", array($hash, $dsid));
			$foundHash = true;
		}

	}
}

?>

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
		$("#successmessage").fadeOut(2000);

		e.clearSelection();
	});

</script>

<div id="successmessage"></div>

<div>
	<div align="center" style="padding-top:10px;">
		Use this URL as a landing page for publication:
	</div>
	<div align="center" style="padding-top:5px;">
		<input id="copybox" type="text" value="https://strabospot.org/d/<?php echo $hash?>" readonly>

		<!--<a href="javascript:void(0);" onclick="myCopy();"><img class="copybutton" src="/includes/images/copy-icon.png"></img></a>-->

			<button style="margin-top:20px;" class="btn" data-clipboard-text="https://strabospot.org/d/<?php echo $hash?>">
				<img class="copybutton" src="/includes/images/copy-icon.png"></img>
			</button>

	</div>
</div>
					<div class="bottomSpacer"></div>

					</div>
				</div>
<?php

include("includes/mfooter.php");

?>