<?php
/**
 * File: add_facility.php
 * Description: Creates new facility records
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../includes/header.php");
?>
<link rel="stylesheet" href="/experimental/experimental.css" type="text/css" />
<script src='/experimental/experimental.js'></script>
<script src='/assets/js/jquery/jquery.min.js'></script>

<div class="topTitle">Add New Facility</div>


<!--<a onClick="doTest();">do test</a>-->

<div id="bigWindow">
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Facility Info</legend>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Facility Name <span class="reqStar">*</span></label>
					<input id="facilityName" class="formControl" type="text" value=""></input>
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel">Facility Type <span class="reqStar">*</span></label>
					<select class="formControl formSelect" name="facilityType" id="facilityType" onchange="exper_handleFacilityTypeChange();">
						<option value="">Select...</option>
						<option value="University Lab">University Lab</option>
						<option value="Government Facility">Government Facility</option>
						<option value="Private Industry Lab">Private Industry Lab</option>
						<option value="Shared Facility">Shared Facility</option>
						<option value="Military">Military</option>
						<option value="Other">Other</option>
					</select>
					<div id ="otherFacilityTypeHolder" style="white-space:nowrap;display:none;padding-top:5px;">
						<input id="otherFacilityType" class="formControl" type="text" value="" placeholder="other facility type...">
					</div>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Facility ID</label>
					<input id="facilityId" class="formControl" type="text" value=""></input>
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel">Facility Website</label>
					<input id="facilityWebsite" class="formControl" type="text" value=""></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w50">
				<div class="formPart">
					<label class="formLabel">Institute Name <span class="reqStar">*</span></label>
					<input id="instituteName" class="formControl" type="text" value=""></input>
				</div>
			</div>
			<div class="formCell w50">
				<div class="formPart">
					<label class="formLabel">Department</label>
					<input id="department" class="formControl" type="text" value=""></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel">Description</label>
					<input id="facilityDescription" class="formControl" type="text" value=""></input>
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
					<input id="street" class="formControl" type="text" value=""></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Building/Apartment</label>
					<input id="buildingApartment" class="formControl" type="text" value=""></input>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Postal Code</label>
					<input id="postalCode" class="formControl" type="text" oninput="this.value = fixZip(this.value);" value="" />
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">City</label>
					<input id="city" class="formControl" type="text" value=""></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">State</label>
					<input id="state" class="formControl" type="text" value=""></input>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Country</label>
					<input id="country" class="formControl" type="text" value=""></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Latitude (decimal degrees)</label>
					<input id="latitude" class="formControl" type="text" oninput="this.value = constrainDecimal(this.value);" value=""/>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Longitude (decimal degrees)</label>
					<input id="longitude" class="formControl" type="text" oninput="this.value = constrainDecimal(this.value);" value=""/>
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
					<input id="firstName" class="formControl" type="text" value=""></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Last Name</label>
					<input id="lastName" class="formControl" type="text" value=""></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Affiliation</label>
					<select class="formControl formSelect" name="affiliation" id="affiliation">
						<option value="">Select...</option>
						<option value="Student">Student</option>
						<option value="Researcher">Researcher</option>
						<option value="Lab Manager">Lab Manager</option>
						<option value="Principal Investigator">Principal Investigator</option>
						<option value="Technical Associate">Technical Associate</option>
						<option value="Faculty">Faculty</option>
						<option value="Professor">Professor</option>
						<option value="Visitor">Visitor</option>
						<option value="Service User">Service User</option>
						<option value="External User">External User</option>
					</select>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Email</label>
					<input id="email" class="formControl" type="text" value=""></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Phone</label>
					<input id="phone" class="formControl" type="text" value=""></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Website</label>
					<input id="website" class="formControl" type="text" value=""></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">ORCID ID</label>
					<input id="orcid" class="formControl" type="text" value=""></input>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
	<div style="text-align:center;">
		<button class="submitButton" style="vertical-align:middle;margin-right:150px;" onclick="javascript:history.back();"><span>Cancel </span></button>
		<button class="submitButton" style="vertical-align:middle" onclick="doSubmitNewFacility()"><span>Submit </span></button>
	</div>
</div>

<?php
include("../includes/footer.php");
?>