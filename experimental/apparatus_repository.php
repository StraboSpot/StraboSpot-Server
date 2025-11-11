<?php
/**
 * File: apparatus_repository.php
 * Description: Apparatus repository browser for viewing available equipment
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

//include("../logincheck.php");

include_once("../adminkeys.php");

session_start();

include("../prepare_connections.php");

if($_SESSION['loggedin']=="yes"){

	$userpkey = $_SESSION['userpkey'];
	$credentials = $_SESSION['credentials'];

}else{
	$userpkey = 99999;
}

if(!in_array($userpkey, $admin_pkeys)){
	$is_admin = false;
}else{
	$is_admin = true;
}



include('../includes/header.php');

//get groups based on userpkey
?>
<link rel="stylesheet" href="/experimental/experimental.css" type="text/css" />
<style type="text/css">

.rowdiv {
	text-align:center;
	padding-top:5px;
}

.rowdivv {
	/*text-align:center;*/
	padding-top:5px;
}

.rowheader {
	font-weight:bold;
	color:#333;
	font-size:1.2em;
}

.redred {
	color:#ab1424;
	font-weigth:bold;
	padding-right:5px;
}

.button {
  /*background-color: #4CAF50;*/ /* Green */
  /*border: none;*/
  /*color: white;*/
  padding: 3px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
}

.checkheader {
	font-size:1.3em;
}

.checkbody {
	border:1px dashed rgba(50, 50, 50, 0.5);border-radius:10px;padding:10px;display:none;margin-bottom:20px;
}

.displacementbox {
	border:1px dashed #CCC;border-radius:5px;padding:10px;display:none;margin-bottom:10px;margin-top:5px;;
}

.notesbox {
	border:1px dashed rgba(50, 50, 50, 0.5);border-radius:10px;padding:20px;margin-bottom:20px;
}

.separator {
  display: flex;
  align-items: center;
  text-align: center;
}

.separator::before,
.separator::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid #666;
}

.separator:not(:empty)::before {
  margin-right: .25em;
}

.separator:not(:empty)::after {
  margin-left: .25em;
}

.only-numeric {
	width:100px;
}

.errorbar {
	color:#bf342c;
	font-weight:bold;
	font-size:1.2em;
}
</style>

<link rel="stylesheet" href="/assets/js/dropzone/dropzone.css" type="text/css" />
<script src='/assets/js/jquery/jquery.min.js'></script>

<script src="/assets/js/featherlight/featherlight.js"></script>
<script src="/assets/js/dropzone/dropzone.js"></script>

<script src="/assets/js/jquery-modal/jquery.modal.min.js"></script>
<link rel="stylesheet" href="/assets/js/jquery-modal/jquery.modal.min.css" type="text/css" />
<?php

$irows = $db->get_results("

select
pkey as facility_pkey,
name,
institute,
department,
CASE
	WHEN admincount > 0 THEN 'yes'
	ELSE 'no'
END is_pi
from (
select
pkey,
name,
institute,
department,
(select count(*) from apprepo.facility_users where users_pkey = $userpkey and facility_pkey = i.pkey) as admincount
from apprepo.facility i order by name
) foo
order by name

");

?>

	<div class="rowdiv">
		<div class="topTitle">Experimental Apparatus Repository</div>
	</div>

<?php
if($is_admin){
?>

	<div class="rowdiv" style="padding-bottom:15px;">
		<a href="add_facility"><strong>Add New Facility</strong></a>
	</div>

<?php
}else{
	if($_SESSION['loggedin']=="yes"){
?>
	<div class="rowdiv" style="font-size:.9em;padding-bottom:15px;">
		If you need an institute added to the list below, please <a href="mailto:strabospot@gmail.com?subject=Need Institute Added to StraboSpot Experimental Apparatus Repository&body=Hello%2C%0A%0APlease%20add%20the%20following%20institute%20to%20the%20Strabo%20Experimental%20Apparatus%20Repository%3A%0A%0ALab%20or%20Facility%20Name%3A%20%0AInstitute%20Name%3A%20%0A%0AStraboSpot%20Account%3A%20<?php echo $_SESSION['username']?>%0A%0AThanks%2C%0A<?php echo $_SESSION['firstname']?>%20<?php echo $_SESSION['lastname']?>%0A">click here.</a>
	</div>
<?php
	}else{
?>
	<div class="rowdiv" style="font-size:.9em;padding-bottom:15px;">
		If you need an institute added to the list below, please <a href="mailto:strabospot@gmail.com?subject=Need Institute Added to StraboSpot Experimental Apparatus Repository&body=Hello%2C%0A%0APlease%20add%20the%20following%20institute%20to%20the%20Strabo%20Experimental%20Apparatus%20Repository%3A%0A%0ALab%20or%20Facility%20Name%3A%20%0AInstitute%20Name%3A%20%0A%0AStraboSpot%20Account%20Email%3A%20<?php echo $_SESSION['username']?>%0A%0A">click here.</a>
	</div>
<?php
	}
}
?>

<?php
	foreach($irows as $irow){


	?>
	<div class="facilityHeader"><?php echo $irow->institute?></div>
	<div>
		<span class="facilityHeader"><?php echo $irow->name?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
		<span><strong><a href="view_facility?fpk=<?php echo $irow->facility_pkey?>">View</a></strong></span>
	<?php if($irow->is_pi == "yes" || $is_admin){?>
		 | <span><strong><a href="edit_facility?fpk=<?php echo $irow->facility_pkey?>">Edit</a></strong></span> |
		<span><strong><a href="delete_facility?fpk=<?php echo $irow->facility_pkey?>" OnClick="return confirm('Are you sure you want to delete this facility?\nAll apparatuses will also be deleted.')">Delete</a></strong></span> |
		<span><strong><a href="add_apparatus?fpk=<?php echo $irow->facility_pkey?>">Add Apparatus</a></strong></span>
	<?php }?>
	</div>

	<?php

		$arows = $db->get_results("select	*
									from apprepo.apparatus where facility_pkey = $irow->facility_pkey order by name");

		if(count($arows) > 0){

		?>
			<div class="strabotable" style="margin-left:0px;margin-bottom:20px;">
				<table>
					<tr>
						<td>&nbsp;</td>
						<td>Apparatus&nbsp;Name</td>
						<td>Apparatus&nbsp;Type</td>
						<td>Last&nbsp;Modified</td>
					</tr>

					<?php
					foreach($arows as $row){
					if($row->type == "Other Apparatus" && $row->other_type != ""){
						$showtype = $row->other_type;
					}else{
						$showtype = $row->type;
					}
					?>
					<tr>
						<td style="vertical-align:top;width:80px;text-align:center;" nowrap>
							<a href="view_apparatus?u=<?php echo $row->uuid?>">view</a>
							<?php if($row->userpkey == $userpkey || $is_admin){?>
							&nbsp;&nbsp;<a href="edit_apparatus?apk=<?php echo $row->pkey?>">edit</a>
							&nbsp;&nbsp;<a href="delete_apparatus?apk=<?php echo $row->pkey?>" onclick="return confirm('Are you sure you want to delete this apparatus?')">delete</a>
							<?php }?>
						</td>
						<td style="vertical-align:top;" nowrap><?php echo $row->name?></td>
						<td style="vertical-align:top;" nowrap><?php echo $showtype?></td>
						<td style="vertical-align:top;" nowrap><?php echo date("D, M j Y G:i:s T ", $row->modified_timestamp);  ?></td>
					</tr>
					<?php
					}
					?>

				</table>
			</div>

		<?php

		}else{
			?>
			<div style="padding-bottom:20px;padding-top:10px;">
			No apparatuses yet exist for <?php echo $irow->name?>.
			<?php if($irow->is_pi == "yes" || $is_admin){?>Please click <a href="add_apparatus?fpk=<?php echo $irow->facility_pkey?>">here</a> to add an apparatus.<?php }?>
			</div>
			<?php
		}

	}

?>

	<div class="fsSpacer"></div>

	<div style="text-align:center;margin-bottom:100px;">
		<button class="submitButton" style="vertical-align:middle;" onclick="window.location.href = '/experimental'"><span>Back </span></button>
	</div>

<div id="bigimagemodal" class="modal">

</div>

<script type='text/javascript'>
	function showImage(imageNum){
		var imagehtml = '<img src="/apparatus_photo_576_large.jpg">';
		$("#bigimagemodal").html(imagehtml);
		$("#bigimagemodal").modal();
	}
</script>






<?php
include('../includes/footer.php');
?>