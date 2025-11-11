<?php
/**
 * File: microproject2.php
 * Description: Strabo Micro project viewer and management interface
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

SESSION_START();
include("prepare_connections.php");

include 'includes/header.php';
include 'microdb/microLandingClass.php';

?>

<style type='text/css'>
	#leftColumn{
		background-color:white;
		float: left;
		width: 220px;
		max-height: 500px;
		overflow: auto;
	}
	#centerColumn{
		background-color:white;
		float: left;
		width: 760px;
		padding-left:10px;
	}
	#rightColumn{
		background-color:white;
		float: left;
		width: 300px;
		height: 1000px;
		padding-left:5px;
	}
	#clearLeft{
		clear: left;
	}
	.projectWrapper{
		width:1500px !important;
	}

</style>
<?php

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$row = $db->get_row_prepared("SELECT * FROM micro_projectmetadata WHERE id = $1 AND (ispublic OR userpkey=$2)", array($id, $userpkey));

if($row->id == ""){
	echo "Error! Project not found.";
	exit();
}

$json = $row->projectjson;
$json = json_decode($json);
$json->pkey = $id;
$ml = new MicroLanding($json);

?>

<script>

	function switchToMicrograph(pkey, id){
		jQuery('#centerColumn').html('loading...&nbsp;');
		jQuery.get( "https://strabospot.org/micrographBigWindow?pkey="+pkey+"&micrograph_id="+id, function( data ) {
			jQuery( "#centerColumn" ).html( data );
		});

		jQuery('#sideDetails').attr('src', "/micrographDetailsPane?type=micrograph&pkey="+pkey+"&id="+id);
	}

	function showSpotDetails(pkey, id){
		jQuery('#sideDetails').attr('src', "/micrographDetailsPane?type=spot&pkey="+pkey+"&id="+id);
	}

	function testChangeContent(){

		var loc = "/micrographDetailsPane?type=micrograph&pkey=193&id=16384581687565";
		jQuery('#sideDetails').attr('src', loc);

	}

</script>

<div class="projectWrapper">

	<div style="float:left; width: 200px;">&nbsp;</div>
	<div style="float:left; width: 760px; padding-left:10px; text-align:center;"><h2>Project: <?php echo $json->name?></h2></div>
	<div style="float:left; width: 300px; text-align:center; vertical-align:baseline; font-size:1.2em; background-color:white;">
		<a href="/download_micro_file?project_id=<?php echo $id?>">
			<img src="/assets/files/micro_download.png" width="13px" style="vertical-align:baseline;"> Download
		</a>
	</div>
	<div style="clear:left;">&nbsp;</div>

	<div id="leftColumn">
		<?php
		$ml->sideBarHTML();
		?>
	</div>
	<div id="centerColumn">
		<?php
		$ml->showFirstMicrograph();
		?>
	</div>
	<div id="rightColumn">
		<?php
		$firstId = $ml->getFirstMicrographId();
		?>
		<iframe id="sideDetails" src="/micrographDetailsPane?type=micrograph&pkey=<?php echo $id?>&id=<?php echo $firstId?>" width="300" height="1000" frameborder="0" scrolling="no"></iframe>
	</div>
	<div id="clearLeft">
	</div>

<?php
?>

</div>

<?php
include 'includes/footer.php';
?>