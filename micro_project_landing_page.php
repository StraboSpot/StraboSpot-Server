<?php
/**
 * File: micro_project_landing_page.php
 * Description: Application landing page
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


session_start();

if($_SESSION['userpkey']!=""){
	$userpkey = $_SESSION['userpkey'];
}else{
	$userpkey = 0;
}

$p = $_GET['p'];
include("prepare_connections.php");

$project_pkey = $db->get_var("select id from micro_projectmetadata where strabo_id='$p' and (ispublic or userpkey = $userpkey)");

if(is_dir("straboMicroFiles/$project_pkey/webImages")){
	$murl = "https://strabospot.org/straboMicroView/view?p=$project_pkey";
}else{
	$murl = "https://strabospot.org/microproject?id=$project_pkey";
}

if($project_pkey == ""){
	include("includes/mheader.php");
?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">
						<header class="major">
							<h2>Project Not Found.</h2>
						</header>
					<div class="bottomSpacer"></div>
					</div>
				</div>
<?php
	include("includes/mfooter.php");
	exit();
}

header("Location: $murl");

?>

?>