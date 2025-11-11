<?php
/**
 * File: edit_facility.php
 * Description: Facility editing interface
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../prepare_connections.php");

$pkey = isset($_GET['fpk']) ? (int)$_GET['fpk'] : 0;

if($pkey == 0) exit();

$credentials = $_SESSION['credentials'];

if(!in_array($userpkey, $admin_pkeys)){
	$is_admin = false;
}else{
	$is_admin = true;
}

//Check to be sure we are authorized to edit this facility
if($is_admin){
	$row = $db->get_row_prepared("SELECT * FROM apprepo.facility WHERE pkey = $1", array($pkey));
}else{
	//$row = $db->get_row("select * from apprepo.facility where pkey = $pkey and userpkey = $userpkey");
	$row = $db->get_row_prepared("SELECT * FROM apprepo.facility WHERE pkey = $1 AND pkey IN (SELECT facility_pkey FROM apprepo.facility_users WHERE users_pkey = $2)", array($pkey, $userpkey));
}

if($row->pkey == ""){
	echo "facility not found.";exit();
}

include("../includes/header.php");
?>
<link rel="stylesheet" href="/experimental/experimental.css" type="text/css" />
<script src='/experimental/experimental.js'></script>
<script src='/assets/js/jquery/jquery.min.js'></script>

<div class="topTitle">Edit Facility</div>
<div id="bigWindow">
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Facility Info</legend>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Facility Name <span class="reqStar">*</span></label>
					<input id="facilityName" class="formControl" type="text" value="<?php echo $row->name?>"></input>
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel">Facility Type <span class="reqStar">*</span></label>
					<select class="formControl formSelect" name="facilityType" id="facilityType" onchange="exper_handleFacilityTypeChange();">
						<option value="">Select...</option>
						<option value="University Lab"<?php if($row->type=="University Lab") echo " selected";?>>University Lab</option>
						<option value="Government Facility"<?php if($row->type=="Government Facility") echo " selected";?>>Government Facility</option>
						<option value="Private Industry Lab"<?php if($row->type=="Private Industry Lab") echo " selected";?>>Private Industry Lab</option>
						<option value="Shared Facility"<?php if($row->type=="Shared Facility") echo " selected";?>>Shared Facility</option>
						<option value="Military"<?php if($row->type=="Military") echo " selected";?>>Military</option>
						<option value="Other"<?php if($row->type=="Other") echo " selected";?>>Other</option>
					</select>

					<?php
					if($row->type == "Other"){
						$showtype = "inline";
					}else{
						$showtype = "none";
					}
					?>

					<div id ="otherFacilityTypeHolder" style="white-space:nowrap;display:<?php echo $showtype?>;padding-top:5px;">
						<input id="otherFacilityType" class="formControl" type="text" value="<?php echo $row->other_type?>" placeholder="other facility type...">
					</div>

				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Facility ID</label>
					<input id="facilityId" class="formControl" type="text" value="<?php echo $row->facility_id?>"></input>
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel">Facility Website</label>
					<input id="facilityWebsite" class="formControl" type="text" value="<?php echo $row->facility_website?>"></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w50">
				<div class="formPart">
					<label class="formLabel">Institute Name <span class="reqStar">*</span></label>
					<input id="instituteName" class="formControl" type="text" value="<?php echo $row->institute?>"></input>
				</div>
			</div>
			<div class="formCell w50">
				<div class="formPart">
					<label class="formLabel">Department</label>
					<input id="department" class="formControl" type="text" value="<?php echo $row->department?>"></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel">Description</label>
					<input id="facilityDescription" class="formControl" type="text" value="<?php echo $row->facility_desc?>"></input>
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
					<input id="street" class="formControl" type="text" value="<?php echo $row->street?>"></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Building/Apartment</label>
					<input id="buildingApartment" class="formControl" type="text" value="<?php echo $row->building?>"></input>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Postal Code</label>
					<input id="postalCode" class="formControl" type="text" oninput="this.value = fixZip(this.value);" value="<?php echo $row->postcode?>" />
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">City</label>
					<input id="city" class="formControl" type="text" value="<?php echo $row->city?>"></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">State</label>
					<input id="state" class="formControl" type="text" value="<?php echo $row->state?>"></input>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Country</label>
					<input id="country" class="formControl" type="text" value="<?php echo $row->country?>"></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Latitude (decimal degrees)</label>
					<input id="latitude" class="formControl" type="text" oninput="this.value = constrainDecimal(this.value);" value="<?php echo $row->latitude?>"/>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Longitude (decimal degrees)</label>
					<input id="longitude" class="formControl" type="text" oninput="this.value = constrainDecimal(this.value);" value="<?php echo $row->longitude?>"/>
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
					<input id="firstName" class="formControl" type="text" value="<?php echo $row->contact_firstname?>"></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Last Name</label>
					<input id="lastName" class="formControl" type="text" value="<?php echo $row->contact_lastname?>"></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Affiliation</label>
					<select class="formControl formSelect" name="affiliation" id="affiliation">
						<option value="">Select...</option>
						<option value="Student"<?php if($row->contact_affil == "Student") echo " selected";?>>Student</option>
						<option value="Researcher"<?php if($row->contact_affil == "Researcher") echo " selected";?>>Researcher</option>
						<option value="Lab Manager"<?php if($row->contact_affil == "Lab Manager") echo " selected";?>>Lab Manager</option>
						<option value="Principal Investigator"<?php if($row->contact_affil == "Principal Investigator") echo " selected";?>>Principal Investigator</option>
						<option value="Technical Associate"<?php if($row->contact_affil == "Technical Associate") echo " selected";?>>Technical Associate</option>
						<option value="Faculty"<?php if($row->contact_affil == "Faculty") echo " selected";?>>Faculty</option>
						<option value="Professor"<?php if($row->contact_affil == "Professor") echo " selected";?>>Professor</option>
						<option value="Visitor"<?php if($row->contact_affil == "Visitor") echo " selected";?>>Visitor</option>
						<option value="Service User"<?php if($row->contact_affil == "Service User") echo " selected";?>>Service User</option>
						<option value="External User"<?php if($row->contact_affil == "External User") echo " selected";?>>External User</option>
					</select>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Email</label>
					<input id="email" class="formControl" type="text" value="<?php echo $row->contact_email?>"></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Phone</label>
					<input id="phone" class="formControl" type="text" value="<?php echo $row->contact_phone?>"></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Website</label>
					<input id="website" class="formControl" type="text" value="<?php echo $row->contact_website?>"></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">ORCID ID</label>
					<input id="orcid" class="formControl" type="text" value="<?php echo $row->contact_id?>"></input>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
	<div style="text-align:center;">
		<button class="submitButton" style="vertical-align:middle;" onclick="javascript:history.back();"><span>Back </span></button>
		<button class="submitButton" style="vertical-align:middle;margin-left:150px;" onclick="doSubmitEditFacility(<?php echo $pkey?>)"><span>Save </span></button>
	</div>
</div>

<?php
include("../includes/footer.php");
?>