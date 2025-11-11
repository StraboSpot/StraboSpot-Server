<?php
/**
 * File: view_facility.php
 * Description: Displays facility information and details
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

//include("../logincheck.php");
include("../prepare_connections.php");

$pkey = isset($_GET['fpk']) ? (int)$_GET['fpk'] : 0;

if($pkey == 0) exit();

$credentials = $_SESSION['credentials'];

if(!in_array($userpkey, $admin_pkeys)){
	$is_admin = false;
}else{
	$is_admin = true;
}

//Check to be sure we facility exists
$row = $db->get_row_prepared("SELECT * FROM apprepo.facility WHERE pkey = $1", array($pkey));

if($row->pkey == ""){
	echo "facility not found.";exit();
}


if($row->type == "Other" && $row->other_type != ""){
	$showtype = $row->other_type;
}else{
	$showtype = $row->type;
}

include("../includes/header.php");
?>
<link rel="stylesheet" href="/experimental/experimental.css" type="text/css" />
<script src='/experimental/experimental.js'></script>
<script src='/assets/js/jquery/jquery.min.js'></script>

<div class="topTitle">Facility</div>
<div id="bigWindow">
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Facility Info</legend>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Facility Name</label>
					<div style="white-space: nowrap;"><?php echo $row->name?></div>
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel">Facility Type</label>
					<div><?php echo $showtype?></div>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Facility ID</label>
					<div><?php echo $row->facility_id?></div>
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel">Facility Website</label>
					<div>
						<?php
						if($row->facility_website != ""){
						?>
						<a href="<?php echo $row->facility_website?>" target="_blank">View</a>
						<?php
						}else{
						?>
						Not Provided
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w50">
				<div class="formPart">
					<label class="formLabel">Institute Name</label>
					<div><?php echo $row->institute?></div>
				</div>
			</div>
			<div class="formCell w50">
				<div class="formPart">
					<label class="formLabel">Department</label>
					<div><?php echo $row->department?></div>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel">Description</label>
					<div><?php echo $row->facility_desc?></div>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Address</legend>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Street + Number</label>
					<div><?php echo $row->street?></div>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Building/Apartment</label>
					<div><?php echo $row->building?></div>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Postal Code</label>
					<div><?php echo $row->postcode?></div>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">City</label>
					<div><?php echo $row->city?></div>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">State</label>
					<div><?php echo $row->state?></div>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Country</label>
					<div><?php echo $row->country?></div>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Latitude (decimal degrees)</label>
					<div><?php echo $row->latitude?></div>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Longitude (decimal degrees)</label>
					<div><?php echo $row->longitude?></div>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Contact</legend>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">First Name</label>
					<div><?php echo $row->contact_firstname?></div>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Last Name</label>
					<div><?php echo $row->contact_lastname?></div>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Affiliation</label>
					<div><?php echo $row->contact_affil?></div>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Email</label>
					<div>
						<?php
						if($row->contact_email != ""){
						?>
						<a href="mailto: <?php echo $row->contact_email?>"><?php echo $row->contact_email?></a>
						<?php
						}else{
						?>
						Not Provided
						<?php
						}
						?>
					</div>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Phone</label>
					<div><?php echo $row->contact_phone?></div>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Website</label>
					<div>
						<?php
						if($row->contact_website != ""){
						?>
						<a href="<?php echo $row->contact_website?>" target="_blank">View</a>
						<?php
						}else{
						?>
						Not Provided
						<?php
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">ORCID ID</label>
					<div><?php echo $row->contact_id?></div>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
	<div style="text-align:center;">
		<button class="submitButton" style="vertical-align:middle;" onclick="javascript:history.back();"><span>Back </span></button>
	</div>
</div>

<?php
include("../includes/footer.php");
?>