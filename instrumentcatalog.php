<?php
/**
 * File: instrumentcatalog.php
 * Description: StraboMicro Instrument Catalog
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

$userpkey = $_SESSION['userpkey'];

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

$instcount = $db->get_var_prepared("
	SELECT count(*) FROM instrument_users WHERE users_pkey = $1
", array($userpkey));

if(!in_array($userpkey, $admin_pkeys) && $instcount == 0){
	exit();
}

if(in_array($userpkey, $admin_pkeys)){
	$institutionrows = $db->get_results("
		SELECT * FROM institute ORDER BY institute_name
	");
}else{
	$institutionrows = $db->get_results_prepared("
		SELECT ii.*
		FROM
		instrument_users iu,
		institute ii
		WHERE
		iu.institution_pkey = ii.pkey
		AND iu.users_pkey = $1
		ORDER BY institute_name
	", array($userpkey));
}

include 'includes/mheader.php';
//get groups based on userpkey
?>

<style type="text/css">

</style>

<script src='/assets/js/jquery/jquery.min.js'></script>
<script src="/assets/js/featherlight/featherlight.js"></script>

<script type='text/javascript'>

</script>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>StraboMicro Instrument Catalog</h2>
						</header>

<div align="center">
	If you need an institute added to the catalog, please click <a href="mailto:strabospot@gmail.com?subject=Need Institute Added to Strabo Micro Instrument Catalog&body=Hello%2C%0A%0APlease%20add%20the%20following%20institute%20to%20the%20Strabo%20Micro%20Instrument%20Catalog%3A%0A%0AInstitute%20Type%3A%20%28Government%20or%20Education%29%0AInstitute%20Name%3A%0A%0AStrabo%20Account%3A <?php echo $username?>%0A%0AThanks%2C%0A%0A<?php echo $_SESSION['firstname']?>%20<?php echo $_SESSION['lastname']?>%0A">here</a>.
</div>

<?php

foreach($institutionrows as $irow){
	?>

	<h3 style="font-size:1.7em;"><?php echo $irow->institute_name?> Instruments: <strong><a href="add_instrument?i=<?php echo $irow->pkey?>">+</a></strong></h3>

	<?php
	$instrumentrows = $db->get_results_prepared("
		SELECT i.*
		FROM
		instrument i
		WHERE i.institution_pkey = $1
	", array($irow->pkey));

	if(count($instrumentrows) > 0){
	?>

	<div class="strabotable" style="margin-left:0px;margin-bottom:10px;">

		<table>

			<tr>
				<td>&nbsp;</td>
				<td>Name</td>
				<td class="hideSmall">Type</td>
				<td class="hideSmall">Brand</td>
				<td class="hideSmall">Model</td>
			</tr>

			<?php
			foreach($instrumentrows as $row){
			?>
			<tr>
				<td style="width:60px;" nowrap><a href="edit_instrument?ii=<?php echo $row->pkey?>">edit</a> <a href="delete_instrument?ii=<?php echo $row->pkey?>" onclick="return confirm('Are you sure you want to delete this instrument?')">delete</a></td>
				<td nowrap><?php echo $row->instrumentname?></td>
				<td class="hideSmall" nowrap><?php echo $row->instrumenttype?></td>
				<td class="hideSmall" nowrap><?php echo $row->instrumentbrand?></td>
				<td class="hideSmall" nowrap><?php echo $row->instrumentmodel?></td>
			</tr>
			<?php
			}
			?>

		</table>
	</div>

	<?php
	}else{
	?>
	<div>No instruments yet exist for <?php echo $irow->institute_name?>. Please click <a href="add_instrument?i=<?php echo $irow->pkey?>">here</a> to add an instrument.</div>
	<?php
	}
}

?>

						<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include 'includes/mfooter.php';
?>

