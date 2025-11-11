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

exit();
include("logincheck.php");

$userpkey = $_SESSION['userpkey'];
$username = $_SESSION['username'];

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

if(!in_array($userpkey, $admin_pkeys)){
	$is_admin = false;
}else{
	$is_admin = true;
}

include 'includes/header.php';

//get groups based on userpkey
?>

<style type="text/css">

.rowdiv {
	text-align:center;
	padding-top:5px;
}

.rowdivv {
	
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
   /* Green */
  
  
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
pkey as institute_pkey,
institute_name,
CASE
	WHEN admincount > 0 THEN 'yes'
	ELSE 'no'
END is_pi
from (
select
pkey,
institute_name,
(select count(*) from instrument_users where users_pkey = $userpkey and institution_pkey = i.pkey) as admincount
from institute i order by institute_name
) foo
order by institute_name

");
?>

	<div class="rowdiv">
		<h2>Strabo Micro Instrument Catalog</h2>
	</div>

	<div class="rowdiv" style="font-size:.9em;padding-bottom:15px;">
		If you need an institute added to the list below, please contact <a href="mailto:strabospot@gmail.com?subject=Need Institute Added to Strabo Micro Instrument Catalog&body=Hello%2C%0A%0APlease%20add%20the%20following%20institute%20to%20the%20Strabo%20Micro%20Instrument%20Catalog%3A%0A%0AInstitute%20Type%3A%20%28Government%20or%20Education%29%0AInstitute%20Name%3A%0A%0AStrabo%20Account%3A <?php echo $username?>%0A%0AThanks%2C%0A%0A<?php echo $_SESSION['firstname']?>%20<?php echo $_SESSION['lastname']?>%0A">strabospot@gmail.com</a>
	</div>

<?php
	foreach($irows as $irow){

	?>
	<h3 style="font-size:1.7em;"><?php echo $irow->institute_name?>: <?php if($irow->is_pi == "yes" || $is_admin){?><strong><a href="add_apparatus?i=<?php echo $irow->institute_pkey?>">+</a></strong><?php }?></h3>
	<?php

		$arows = $db->get_results("select	pkey,
											name,
											type,
											to_char(last_modified, 'Mon DD, YYYY') as last_modified
									from exp_apparatus where institute_pkey = $irow->institute_pkey order by name");

		if(count($arows) > 0){

		?>
			<div class="strabotable" style="margin-left:0px;margin-bottom:10px;">

				<table>

					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>Apparatus&nbsp;Name</td>
						<td>Apparatus&nbsp;Type-Subtype</td>
						<td>Last&nbsp;Modified</td>
						<td>Schematic</td>
					</tr>

					<?php
					foreach($arows as $row){
					?>
					<tr>
						<td style="vertical-align:top;width:80px;text-align:center;" nowrap>
							<a href="view_apparatus?a=<?php echo $row->pkey?>">view</a>
							<?php if($row->userpkey == $userpkey || $is_admin){?>
							<a href="edit_apparatus?a=<?php echo $row->pkey?>">edit</a>
							<a href="delete_apparatus?a=<?php echo $row->pkey?>" onclick="return confirm('Are you sure you want to delete this apparatus?')">delete</a>
							<?php }?>
						</td>
						<td style="vertical-align:top;width:110px;" nowrap>
							<?php
							if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/photo_".$row->pkey)){
							?>
							<a href="/apparatus_photo_<?php echo $row->pkey?>_large.jpg" data-featherlight="image"><img src="/apparatus_photo_<?php echo $row->pkey?>_small.jpg"></a>
							<?php
							}else{
							?>
							<img src="/apparatus_photo_<?php echo $row->pkey?>_small.jpg">
							<?php
							}
							?>
						</td>
						<td style="vertical-align:top;" nowrap><?php echo $row->name?></td>
						<td style="vertical-align:top;" nowrap><?php echo $row->type?><?php if($row->sub_type!=""){echo "-".$row->sub_type;}?></td>
						<td style="vertical-align:top;" nowrap><?php echo $row->last_modified?></td>
						<td style="vertical-align:top;text-align:center;" nowrap>
							<?php
							if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/schematic_".$row->pkey)){
								?>
								<a href="/apparatus_schematic_<?php echo $row->pkey?>">download</a>
								<?php
							}else{
								?>
								N/A
								<?php
							}
							?>
						</td>

					</tr>
					<?php
					}
					?>

				</table>
			</div>

		<?php

		}else{
			?>
			<div style="padding-bottom:20px;">
			No apparatuses yet exist for <?php echo $irow->institute_name?>.
			<?php if($irow->is_pi == "yes" || $is_admin){?>Please clicke <a href="add_apparatus?i=<?php echo $irow->institute_pkey?>">here</a> to add an apparatus.<?php }?>
			</div>
			<?php
		}

	}

?>

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
include 'includes/footer.php';
?>