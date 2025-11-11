<?php
/**
 * File: add_experiment.php
 * Description: Creates new experimental records
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../prepare_connections.php");

$project_pkey = isset($_GET['ppk']) ? (int)$_GET['ppk'] : 0;
if($project_pkey == 0) die("Project not found.");
$count = $db->get_var_prepared("SELECT count(*) FROM straboexp.project WHERE pkey = $1 AND userpkey = $2", array($project_pkey, $userpkey));
if($count == 0) die("Project not found.");

include("../includes/header.php");
?>
<link rel="stylesheet" href="experimental.css" type="text/css" />
<link rel="stylesheet" href="hint.css" type="text/css" />
<script src='experimental.js'></script>
<script src='experimental_data_fields.js'></script>
<script src='tooltips.js'></script>
<script src='/assets/js/jquery/jquery.min.js'></script>



<div style="display:none;">
	<input type="text" id="projectPkey" value="<?php echo $project_pkey?>">
	<input type="text" id="experimentUUID" value="<?php echo $project_pkey?>">
	<a id="downloadAnchorElem"></a>
</div>

<div id="progressBox">
	<div id="grayOut"></div>
	<div id="uploadingMessage">
		<div><image src="/assets/js/images/box.gif"/> Uploading. Please wait...</div>
		<div id="progressDigit">0%</div>
		<div id="progressBar"></div>
	</div>
</div>

<div style="margin-top:-30px; text-align:right;margin-right:100px;">
	<button id="downloadButton" class="downloadButton" onclick="downloadProject();">
		<img id="downloadImage" title="Download Experiment" src="/experimental/buttonImages/empty.png" width="40" height="40">
	</button>
</div>

<div class="topTitle">Add Experiment</div>

<div id="chooseExperimentModal" style="display:none;">
	<div class="grayOut"></div>
	<div class="redCloseBox" onclick="exper_closeChooseExperimentModal();">X</div>
	<div class="modalBox">
		<div class="topTitle" style="padding-top:10px;">Choose Experiment</div>
		<div id="experimentList"></div>
	</div>
</div>

<div id="apparatusModal" style="display:none;">
	<div class="grayOut"></div>
	<div class="redCloseBox" onclick="exper_CloseApparatusModal();">X</div>
	<div class="modalBox">
		<div class="topTitle" style="padding-top:10px;">Choose Apparatus</div>
		<div class="rowdiv" style="text-align:center;font-size:.9em;padding-bottom:15px;">
			If you need an institute added to the list below, please contact <a href="mailto:strabospot@gmail.com?subject=Need Institute Added to StraboSpot Experimental Apparatus Repository&body=Hello%2C%0A%0APlease%20add%20the%20following%20institute%20to%20the%20Strabo%20Database%3A%0A%0AFacility%20Name%3A%0AInstitute%20Name%3A%0A%0AThanks%2C%0A<?php echo $_SESSION['firstname']?>%20<?php echo $_SESSION['lastname']?>%0A%0AStraboSpot%20Account%3A%20<?php echo $_SESSION['username']?>">strabospot@gmail.com</a>
		</div>
		<div id="apparatusList"></div>
	</div>
</div>

<div id="loadingExperimentBox" style="display:none;">
	<div id="grayOut"></div>
	<div id="loadingExperimentMessage">
		<div><image src="/assets/js/images/box.gif"/> Loading Experiment...</div>
	</div>
</div>


<!-- Data Copied Modal -->
<div id="dataCopiedModal" style="display:none;">
	<div class="copiedMessage">
		Data Copied to Clipboard.
	</div>
</div>

<!-- Download JSON Modal -->
<div id="downloadJSONModal" style="display:none;">
	<div class="grayOut"></div>
	<div class="redCloseBox" onclick="exper_doCancelDownloadJSON();">X</div>
	<div class="modalBox" id="downloadJSONModalBox">
		<div class="topTitle" style="padding-top:10px;">Download Experiment</div>
		<div>
			<fieldset class="mainFS">
				<legend class="mainFSLegend">Experiment JSON</legend>
				<div class="formRow">
					<div class="formCell w100">
						<textarea class="jsonTextarea" id="projectJSONText" readonly></textarea>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<div style="text-align:center;display:block;">
				<button class="submitButton" style="vertical-align:middle;margin-right:75px;" onclick="exper_doCancelDownloadJSON();"><span>Cancel </span></button>
				<button class="submitButton" style="vertical-align:middle;margin-right:75px;" onclick="exper_doCopyProjectJSON();"><span>Copy </span></button>
				<button class="submitButton" style="vertical-align:middle" onclick="exper_doDownloadProjectJSON()"><span>Save </span></button>
			</div>
		</div>
	</div>
</div>











































































































<!-- Facility Apparatus Modal -->
<div id="facilityApparatusModal" style="display:none;">
	<div class="grayOut"></div>
	<div class="redCloseBox" onclick="exper_doCancelFacilityApparatusEdit();">X</div>
	<div class="modalBox" id="facilityApparatusModalBox">
		<div class="topTitle" style="padding-top:10px;">Facility and Apparatus Info</div>
		<div>










			<fieldset class="mainFS">
				<legend class="mainFSLegend">Facility Info</legend>
				<div class="formRow">
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="facilityName">Facility Name <span class="reqStar">*</span></label>
							<input id="facilityName" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="facilityType">Facility Type <span class="reqStar">*</span></label>
							<select class="formControl formSelect" name="facilityType" id="facilityType">
								<option value="">Select...</option>
								<option value="University Lab">University Lab</option>
								<option value="Government Facility">Government Facility</option>
								<option value="Private Industry Lab">Private Industry Lab</option>
								<option value="Shared Facility">Shared Facility</option>
								<option value="Military">Military</option>
								<option value="Other">Other</option>
							</select>
							<div id ="otherFacilityTypeHolder" style="white-space:nowrap;display:none;padding-top:5px;">
								<input id="otherFacilityType" class="formControl tooltipper" exp-code="otherFacilityType" type="text" value="" placeholder="other facility type...">
							</div>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="facilityId">Facility ID</label>
							<input id="facilityId" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="facilityWebsite">Facility Website</label>
							<input id="facilityWebsite" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="instituteName">Institute Name <span class="reqStar">*</span></label>
							<input id="instituteName" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="department">Department</label>
							<input id="department" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w100">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="facilityDescription">Description</label>
							<input id="facilityDescription" class="formControl" type="text" value="">
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
							<label class="formLabel tooltipper" exp-code="street">Street + Number</label>
							<input id="street" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="buildingApartment">Building/Apartment</label>
							<input id="buildingApartment" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="postalCode">Postal Code</label>
							<input id="postalCode" class="formControl" type="text" oninput="this.value = fixZip(this.value);" value="">
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="city">City</label>
							<input id="city" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="state">State</label>
							<input id="state" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="country">Country</label>
							<input id="country" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="latitude">Latitude (decimal degrees)</label>
							<input id="latitude" class="formControl" type="text" oninput="this.value = constrainDecimal(this.value);" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="longitude">Longitude (decimal degrees)</label>
							<input id="longitude" class="formControl" type="text" oninput="this.value = constrainDecimal(this.value);" value="">
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
							<label class="formLabel tooltipper" exp-code="firstName">First Name</label>
							<input id="firstName" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="lastName">Last Name</label>
							<input id="lastName" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="affiliation">Affiliation</label>
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
							<label class="formLabel tooltipper" exp-code="email">Email</label>
							<input id="email" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="phone">Phone</label>
							<input id="phone" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="website">Website</label>
							<input id="website" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="orcid">ORCID ID</label>
							<input id="orcid" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
			</fieldset>

			<div class="fsSpacer"></div>



	<fieldset class="mainFS">
		<legend class="mainFSLegend">Apparatus Info</legend>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="apparatusName">Apparatus Name <span class="reqStar">*</span></label>
					<input id="apparatusName" class="formControl" type="text" value="">
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="apparatusType">Apparatus Type <span class="reqStar">*</span></label>
					<select class="formControl formSelect" name="apparatusType" id="apparatusType">
						<option value="">Select...</option>
						<option value="Uniaxial">Uniaxial</option>
						<option value="Triaxial (conventional)">Triaxial (conventional)</option>
						<option value="Biaxial">Biaxial</option>
						<option value="True Triaxial">True Triaxial</option>
						<option value="Direct Shear">Direct Shear</option>
						<option value="Shear Box">Shear Box</option>
						<option value="Rotary Shear Friction Apparatus">Rotary Shear Friction Apparatus</option>
						<option value="Creep Cell">Creep Cell</option>
						<option value="Indentation Cell">Indentation Cell</option>
						<option value="Piston Cylinder">Piston Cylinder</option>
						<option value="Multi Anvil">Multi Anvil</option>
						<option value="D-DIA">D-DIA</option>
						<option value="Rotational Drickamer Apparatus (RDA)">Rotational Drickamer Apparatus (RDA)</option>
						<option value="Large Volume Torsion Apparatus (LVT)">Large Volume Torsion Apparatus (LVT)</option>
						<option value="1 Atm Gas Mixing Furnace">1 Atm Gas Mixing Furnace</option>
						<option value="Vacuum Furnace">Vacuum Furnace</option>
						<option value="Other Gas Medium Apparatus">Other Gas Medium Apparatus</option>
						<option value="Other Solid Medium Apparatus">Other Solid Medium Apparatus</option>
						<option value="Other Liquid Medium Apparatus">Other Liquid Medium Apparatus</option>
						<option value="Vickers Hardness Tester">Vickers Hardness Tester</option>
						<option value="Nanoindenter">Nanoindenter</option>
						<option value="Diamond Anvil Cell">Diamond Anvil Cell</option>
						<option value="Brazilian Test">Brazilian Test</option>
						<option value="Paterson Apparatus">Paterson Apparatus</option>
						<option value="Heard Apparatus">Heard Apparatus</option>
						<option value="Griggs Apparatus">Griggs Apparatus</option>
						<option value="Schmidt Hammer">Schmidt Hammer</option>
						<option value="Split Hopkinson Pressure Bar">Split Hopkinson Pressure Bar</option>
						<option value="Double Torsion apparatus">Double Torsion apparatus</option>
						<option value="Point Load">Point Load</option>
						<option value="Atomic Force Microscope">Atomic Force Microscope</option>
						<option value="Rheometer">Rheometer</option>
						<option value="Permeameter">Permeameter</option>
						<option value="Pycnometer">Pycnometer</option>
						<option value="Viscosimeter">Viscosimeter</option>
						<option value="Laser Profilometer">Laser Profilometer</option>
						<option value="Light Interferometer">Light Interferometer</option>
						<option value="Load Stamp">Load Stamp</option>
						<option value="Chevron Notch Test">Chevron Notch Test</option>
						<option value="Paris-Edinburgh Rig">Paris-Edinburgh Rig</option>
						<option value="NER Rig">NER Rig</option>
						<option value="Sanchez Rig">Sanchez Rig</option>
						<option value="Micro-Deformation Cell">Micro-Deformation Cell</option>
						<option value="Ancillary Equipment">Ancillary Equipment</option>
						<option value="Commercial Apparatus">Commercial Apparatus</option>
						<option value="Other Apparatus">Other Apparatus</option>
					</select>
					<div id ="otherApparatusTypeHolder" style="white-space:nowrap;display:none;padding-top:5px;">
						<input id="otherApparatusType" class="formControl" type="text" value="" placeholder="other aparatus type...">
					</div>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="apparatusLocation">Location</label>
					<input id="apparatusLocation" class="formControl" type="text" value="">
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="apparatusId">Apparatus ID</label>
					<input id="apparatusId" class="formControl" type="text" value="">
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="apparatusDescription">Description</label>
					<textarea class="formControl" data-schemaformat="markdown" id="apparatusDescription"></textarea>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend tooltipper" exp-code="apparatusFeatures">Apparatus Features</legend>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_loading" checked>&nbsp;Loading</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_unloading" checked>&nbsp;Unloading</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_heating">&nbsp;Heating</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_cooling">&nbsp;Cooling</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_high_temperature">&nbsp;High Temperature</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_ultra-high_temperature">&nbsp;Ultra-High Temperature</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_low_temperature">&nbsp;Low Temperature</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_sub-zero_temperature">&nbsp;Sub-Zero Temperature</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_high_pressure">&nbsp;High Pressure</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_ultra-high_pressure">&nbsp;Ultra-High Pressure</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_hydrostatic_tests">&nbsp;Hydrostatic Tests</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_hip">&nbsp;HIP</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_synthesis">&nbsp;Synthesis</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_deposition_evaporation">&nbsp;Deposition/Evaporation</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_mineral_reactions">&nbsp;Mineral Reactions</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_hydrothermal_reactions">&nbsp;Hydrothermal Reactions</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_elasticity">&nbsp;Elasticity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_local_axial_strain">&nbsp;Local Axial Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_local_radial_strain">&nbsp;Local Radial Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_elastic_moduli">&nbsp;Elastic Moduli</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_yield_strength">&nbsp;Yield Strength</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_failure_strength">&nbsp;Failure Strength</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_strength">&nbsp;Strength</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_extension">&nbsp;Extension</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_creep">&nbsp;Creep</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_friction">&nbsp;Friction</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_frictional_sliding">&nbsp;Frictional Sliding</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_slide_hold_slide">&nbsp;Slide Hold Slide</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_stepping">&nbsp;Stepping</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_pure_shear">&nbsp;Pure Shear</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_simple_shear">&nbsp;Simple Shear</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_rotary_shear">&nbsp;Rotary Shear</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_torsion">&nbsp;Torsion</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_viscosity">&nbsp;Viscosity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_indentation">&nbsp;Indentation</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_hardness">&nbsp;Hardness</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_dynamic_tests">&nbsp;Dynamic Tests</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_hydraulic_fracturing">&nbsp;Hydraulic Fracturing</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_hydrothermal_fracturing">&nbsp;Hydrothermal Fracturing</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_shockwave">&nbsp;Shockwave</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_reactive_flow">&nbsp;Reactive Flow</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_pore_fluid_control">&nbsp;Pore Fluid Control</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_pore_fluid_chemistry">&nbsp;Pore Fluid Chemistry</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_pore_volume_compaction">&nbsp;Pore Volume Compaction</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_storage_capacity">&nbsp;Storage Capacity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_permeability">&nbsp;Permeability</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_steady-state_permeability">&nbsp;Steady-State Permeability</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_transient_permeability">&nbsp;Transient Permeability</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_hydraulic_conductivity">&nbsp;Hydraulic Conductivity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_drained_undrained_pore_fluid">&nbsp;Drained/Undrained Pore Fluid</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_uniaxial_stress_strain">&nbsp;Uniaxial Stress/Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_biaxial_stress_strain">&nbsp;Biaxial Stress/Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_triaxial_stress_strain">&nbsp;Triaxial Stress/Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_differential_stress">&nbsp;Differential Stress</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_true_triaxial">&nbsp;True Triaxial</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_resistivity">&nbsp;Resistivity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_electrical_resistivity">&nbsp;Electrical Resistivity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_electrical_capacitance">&nbsp;Electrical Capacitance</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_streaming_potential">&nbsp;Streaming Potential</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_acoustic_velocity">&nbsp;Acoustic Velocity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_acoustic_events">&nbsp;Acoustic Events</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_p-wave_velocity">&nbsp;P-Wave Velocity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_s-wave_velocity">&nbsp;S-Wave Velocity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_source_location">&nbsp;Source Location</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_tomography">&nbsp;Tomography</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_in-situ_x-ray">&nbsp;In-Situ X-Ray</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_infrared">&nbsp;Infrared</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_raman">&nbsp;Raman</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_visual">&nbsp;Visual</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="appf_other">&nbsp;Other</label></div>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend"><label class="tooltipper" exp-code="apparatusParameters">Apparatus Parameters</label></legend>
		<div class="subDataHolder" id="paramsSubDiv">
			<table class="subDataTable" id="paramsTable" style="display:none;">
				<tbody><tr data-isheader="true">
					<th><label class="tooltipper" exp-code="apparatusParameterName">Name</label></th>
					<th><label class="tooltipper" exp-code="apparatusParameterMin">Minimum</label></th>
					<th><label class="tooltipper" exp-code="apparatusParameterMax">Maximum</label></th>
					<th><label class="tooltipper" exp-code="apparatusParameterUnit">Unit</label></th>
					<th><label class="tooltipper" exp-code="apparatusParameterPrefix">Prefix</label></th>
					<th><label class="tooltipper" exp-code="apparatusParameterNote">Detail/Note</label></th>
					<th>&nbsp;</th>
				</tr>

































			</tbody></table>
		</div>
		<div style="padding-left:5px;"><button class="smallerButton" style="vertical-align:middle" onclick="addApparatusParamRow();"><span>Add Parameter </span></button></div>
	</fieldset>
	<div class="fsSpacer"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Apparatus Documents</legend>
		<div id="docsWrapper">
			<div class="subDataHolder" id="documentRows">












			</div>
		</div>
		<div style="padding-left:5px;"><button class="smallerButton" style="vertical-align:middle" onclick="addApparatusDocument();"><span>Add Document </span></button></div>
	</fieldset>
	<div class="fsSpacer"></div>

	<div style="display:none;">
		<div class="docRow" id="sourceApparatusDocumentRow">
			<div class="appDocCell" style="width:1140px;">
				<div class="formRow">
					<div class="formCell 16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="docType">Document Type <span class="reqStar">*</span></label>
							<select class="formControl formSelect" id="docType">
								<option value="Manual">Manual</option>
								<option value="Diagram">Diagram</option>
								<option value="Picture">Picture</option>
								<option value="Video">Video</option>
								<option value="Data">Data</option>
								<option value="Software">Software</option>
								<option value="ASTM">ASTM</option>
								<option value="Publication">Publication</option>
								<option value="Other">Other</option>
							</select>
							<div id ="otherDocTypeHolder" style="white-space:nowrap;display:none;padding-top:5px;">
								<input id="otherDocType" class="formControl" style="width:110px;" type="text" value="" placeholder="doc type...">
							</div>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="docFormat">Document Format <span class="reqStar">*</span></label>
							<select class="formControl formSelect" id="docFormat">
								<option value="jpg">jpg</option>
								<option value="png">png</option>
								<option value="txt">txt</option>
								<option value="csv">csv</option>
								<option value="zip">zip</option>
								<option value="rar">rar</option>
								<option value="pdf">pdf</option>
								<option value="docx">docx</option>
								<option value="Other">Other</option>
							</select>
							<div id ="otherDocFormatHolder" style="white-space:nowrap;display:none;padding-top:5px;">
								<input id="otherDocFormat" class="formControl" type="text" value="" placeholder="enter document format here...">
							</div>
						</div>
					</div>
					<div class="formCell w50">
						<div class="formPart" id="fileHolder">
							<label class="formLabel tooltipper" exp-code="docFile">Choose File <span class="reqStar">*</span></label>
							<input type="file" id="docFile" class="formControl">
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="documentId">Document ID</label>
							<input id="documentId" class="formControl" type="text" value="">
							<input id="uuid" type="hidden" value="">
							<input id="originalFilename" type="hidden" value="">
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w100">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="docDescription">Description</label>
							<textarea class="formControl docDescText" data-schemaformat="markdown" id="docDescription"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="docCell" style="width:25px;">
				<div style="padding-top:10px;"><button class="squareButton squareButtonBottom"><img src="/experimental/buttonImages/icons8-trash-30.png" height="20" width="20"></button></div>
				<div><button class="squareButton squareButtonBottom"><img src="/experimental/buttonImages/icons8-up-30.png" height="20" width="20"></button></div>
				<div><button class="squareButton squareButtonBottom"><img src="/experimental/buttonImages/icons8-down-30.png" height="20" width="20"></button></div>
			</div>
			<div class="clearLeft"></div>
		</div>
	</div>

	<table class="subDataTable" style="display:none;">
		<tbody><tr id="sourceParamRow">
			<td>
				<select class="formControl formSelect" name="paramName" id="paramName">
					<option value="Confining Pressure">Confining Pressure</option>
					<option value="Effective Pressure">Effective Pressure</option>
					<option value="Pore Pressure">Pore Pressure</option>
					<option value="Temperature">Temperature</option>
					<option value="σ1-Displacement">σ1-Displacement</option>
					<option value="σ2-Displacement">σ2-Displacement</option>
					<option value="σ3-Displacement">σ3-Displacement</option>
					<option value="σ1-Load">σ1-Load</option>
					<option value="σ2-Load">σ2-Load</option>
					<option value="σ3-Load">σ3-Load</option>
					<option value="Displacement Rate">Displacement Rate</option>
					<option value="Loading Rate">Loading Rate</option>
					<option value="Stiffness">Stiffness</option>
					<option value="Sample Diameter">Sample Diameter</option>
					<option value="Sample Length">Sample Length</option>
					<option value="Permeability">Permeability</option>
				</select>
			</td>
			<td><input id="paramMin" class="formControl" type="text" value=""></td>
			<td><input id="paramMax" class="formControl" type="text" value=""></td>
			<td>
				<select class="formControl formSelect" name="paramUnit" id="paramUnit">
					<option value="degC">degC</option>
					<option value="degK">degK</option>
					<option value="sec">sec</option>
					<option value="min">min</option>
					<option value="hour">hour</option>
					<option value="Volt">Volt</option>
					<option value="mV">mV</option>
					<option value="Amperage">Amperage</option>
					<option value="mA">mA</option>
					<option value="Ohm">Ohm</option>
					<option value="Pa">Pa</option>
					<option value="MPa">MPa</option>
					<option value="GPa">GPa</option>
					<option value="bar">bar</option>
					<option value="kbar">kbar</option>
					<option value="N">N</option>
					<option value="kN">kN</option>
					<option value="g">g</option>
					<option value="mg">mg</option>
					<option value="μg">μg</option>
					<option value="m">m</option>
					<option value="cm">cm</option>
					<option value="mm">mm</option>
					<option value="μm">μm</option>
					<option value="Hz">Hz</option>
					<option value="kHz">kHz</option>
					<option value="MHz">MHz</option>
					<option value="Pa·s">Pa·s</option>
					<option value="Darcy">Darcy</option>
					<option value="mDarcy">mDarcy</option>
					<option value="m-1">m-1</option>
					<option value="m2">m2</option>
					<option value="milistrain">milistrain</option>
					<option value="mm·sec-1">mm·sec-1</option>
					<option value="N·sec-1">N·sec-1</option>
					<option value="sec-1">sec-1</option>
					<option value="kN·mm-1">kN·mm-1</option>
					<option value="%">%</option>
					<option value="count">count</option>
					<option value="cc">cc</option>
					<option value="mm3">mm3</option>
				</select>
			</td>
			<td>
				<select class="formControl formSelect" name="paramPrefix" id="paramPrefix">
					<option value="1E+1">1E+1</option>
					<option value="1E+2">1E+2</option>
					<option value="1E+3">1E+3</option>
					<option value="1E+4">1E+4</option>
					<option value="1E+5">1E+5</option>
					<option value="1E+6">1E+6</option>
					<option value="-" selected="">-</option>
					<option value="1E-1">1E-1</option>
					<option value="1E-2">1E-2</option>
					<option value="1E-3">1E-3</option>
					<option value="1E-4">1E-4</option>
					<option value="1E-5">1E-5</option>
					<option value="1E-6">1E-6</option>
				</select>
			</td>
			<td><input id="paramNote" class="formControl" type="text" value=""></td>
			<td>
				<div style="white-space: nowrap;">
					<button class="squareButton"><img src="/experimental/buttonImages/icons8-trash-30.png" height="20" width="20"></button>
					<button class="squareButton"><img src="/experimental/buttonImages/icons8-up-30.png" height="20" width="20"></button>
					<button class="squareButton"><img src="/experimental/buttonImages/icons8-down-30.png" height="20" width="20"></button>
				</div>
			</td>
		</tr>
	</tbody></table>

	<table id="holdingTable" style="display: none;"></table>
	<div id="holdingDiv" style="display: none;"></div>




			<div style="text-align:center;">
				<button class="submitButton" style="vertical-align:middle;margin-right:150px;" onclick="exper_doCancelFacilityApparatusEdit();"><span>Cancel </span></button>
				<button class="submitButton" style="vertical-align:middle" onclick="exper_doSaveFacilityApparatusInfo()"><span>Save </span></button>

			</div>









		</div>
	</div>
</div>
















































































<!-- DAQ Modal -->
<div id="daqModal" style="display:none;">
	<div class="grayOut"></div>
	<div class="redCloseBox" onclick="exper_doCancelDAQEdit();">X</div>
	<div class="modalBox" id="daqModalBox">
		<div class="topTitle" style="padding-top:10px;">DAQ Info</div>
		<div>
			<fieldset class="mainFS">
					<legend class="mainFSLegend">DAQ Info</legend>
					<div class="formRow">
						<div class="formCell w50">
							<div class="formPart">
								<label class="formLabel tooltipper" exp-code="daqGroupName">DAQ Group Name <span class="reqStar">*</span></label>
								<input id="daqGroupName" class="formControl" type="text" value="">
							</div>
						</div>
						<div class="formCell w33">
							<div class="formPart">
								<label class="formLabel tooltipper" exp-code="daqType">DAQ Type <span class="reqStar">*</span></label>
								<select class="formControl formSelect" name="daqType" id="daqType">
									<option value="Standard">Standard</option>
									<option value="Conventional">Conventional</option>
									<option value="Proprietary">Proprietary</option>
								</select>
							</div>
						</div>
						<div class="formCell w16">
							<div class="formPart">
								<label class="formLabel tooltipper" exp-code="daqLocation">Location</label>
								<input id="daqLocation" class="formControl" type="text" value="">
							</div>
						</div>
					</div>
					<div class="formRow">
						<div class="formCell w100">
							<div class="formPart">
								<label class="formLabel tooltipper" exp-code="daqDescription">Description</label>
								<textarea class="formControl" data-schemaformat="markdown" id="daqDescription"></textarea>
							</div>
						</div>
					</div>
				</fieldset>
				<div class="fsSpacer"></div>
				<fieldset class="mainFS">
					<legend class="mainFSLegend">DAQ Devices</legend>
					<div>
						<div class="subDataHolder" style="margin-top:0px;" id="daq_devices">
							<!-- Devices Here -->
						</div>
					</div>
					<div style="padding-left:5px;"><button class="smallerButton" style="vertical-align:middle" onclick="exper_addDAQDevice();"><span>Add Device </span></button></div>
				</fieldset>
				<div class="fsSpacer"></div>
				<div style="text-align:center;">
					<button class="submitButton" style="vertical-align:middle;margin-right:150px;" onclick="exper_doCancelDAQEdit()"><span>Cancel </span></button>
					<button class="submitButton" style="vertical-align:middle" onclick="exper_doSaveDAQInfo()"><span>Save </span></button>
				</div>
		</div>
	</div>
</div>

<!-- DAQ Device -->
<div style="display:none;" class="daqDevice">
	<!-- One of these for each DAQ Device -->
	<div class="deviceRow" id="sourceDAQDeviceRow">
		<div class="formCell" style="width:1140px;padding-left:0px !important;">
			<div class="formRow">
				<div class="formCell w100">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="deviceName">Device Name <span class="reqStar">*</span></label>
						<input id="deviceName" class="formControl" type="text" value="">
						<input id="uuid" type="hidden" value="">
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w100">
					<div class="formPart">

						<!-- Channels -->
						<fieldset class="mainFS" style="margin-top:10px">
							<legend class="subFSLegend">Device (DAQ) Channels <button id="addChannelButton" class="fsButton" style="vertical-align:middle" onclick="exper_addDAQDeviceChannel();"><span>Add Channel </span></button></legend>
							<div>
								<div class="subDataHolder" style="padding-left:0px !important;">
									<!-- Channels Here -->
									<div style="float:left;">
										<div id="channelButtonHolder"><!-- buttons here --></div>
									</div>
									<div style="float:left;padding-left:5px;">
										<div id="channelHolder"><!-- each channel in here --></div>
									</div>
									<div style="clear:both;"></div>
								</div>
							</div>
						</fieldset>
						<!-- Documents -->
						<fieldset class="mainFS" style="margin-top:10px">
							<legend class="subFSLegend">Device Documents <button id="addDocumentButton" class="fsButton" style="vertical-align:middle" onclick="exper_addDAQDeviceDocument();"><span>Add Document </span></button></legend>
							<div>
								<div class="subDataHolder" style="padding-left:0px !important;">
									<!-- Documents Here -->
									<div style="float:left;">
										<div id="documentButtonHolder"><!-- buttons here --></div>
									</div>
									<div style="float:left;padding-left:5px;">
										<div id="documentHolder"><!-- each document in here --></div>
									</div>
									<div style="clear:both;"></div>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
		</div>
		<div class="docCell" style="width:25px;">
			<div style="padding-top:10px;"><button id="deleteButton" class="squareButton squareButtonBottom" onclick="deleteApparatusDocument(1);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
			<div><button id="upButton" class="squareButton squareButtonBottom" onclick="moveApparatusDocumentUp(1);" style="display: inline;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
			<div><button id="downButton" class="squareButton squareButtonBottom" onclick="moveApparatusDocumentDown(1);" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
		</div>
		<div style="clear:both;"></div>
	</div>
	<!-- end device -->
</div>

<!-- Side Button -->
<div style="display:none;">
	<div><button id="sourceSideBarButton" class="sideBarButton" style="vertical-align:middle"><span>Channel Num</span></button></div>
</div>

<!-- DAQ Channel -->
<div style="display:none;">
	<!-- One of these for Each DAQ Channel -->
	<div class="deviceRow" style="margin-top:5px;width:960px;" id="sourceDAQChannelRow">
		<div class="formCell" style="width:890px;padding-left:0px !important;">
			<div class="formRow">
				<div class="formCell w50">
					<div class="formPart">
						<label class="formLabel midHeader tooltipper" exp-code="channelHeader">Channel Header<span class="reqStar">*</span></label>
						<select class="formControl formSelect" name="channelHeader" id="channelHeader">
							<option value="">Select...</option>
							<option value="Time">Time</option>
							<option value="Temperature">Temperature</option>
							<option value="Pressure">Pressure</option>
							<option value="Strain">Strain</option>
							<option value="Displacement">Displacement</option>
							<option value="Stress">Stress</option>
							<option value="Load">Load</option>
							<option value="Electrical">Electrical</option>
							<option value="Chemistry">Chemistry</option>
							<option value="Other">Other</option>
						</select>
					</div>
				</div>

				<div class="formCell w50">
					<div class="formPart" id="otherChannelHeaderHolder" style="display:none;">
						<label class="formLabel midHeader">&nbsp;</label>
						<input id="otherChannelHeader" class="formControl" type="text" value="" placeholder = "Other Channel Header...">
					</div>
				</div>

			</div>
			<div class="formRow">
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="specifierA">Specifier A<span class="reqStar">*</span></label>
						<div id="specAHolder">
							<select class="formControl formSelect" name="specifierA" id="specifierA">

							</select>
						</div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="specifierB">Specifier B</label>
						<div id="specBHolder">
							<select class="formControl formSelect" name="specifierB" id="specifierB">

							</select>
						</div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="otherSpecifier">Other Specifier</label>
						<input id="otherSpecifier" class="formControl" type="text" value="">
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelUnit">Unit<span class="reqStar">*</span></label>
						<div id="unitHolder">
							<select class="formControl formSelect" name="channelUnit" id="channelUnit">

							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w100">
					<div class="formPart">
						<label class="formLabel midHeader">Channel Information</label>
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelNum">Channel #</label>
						<select class="formControl formSelect" name="channelNum" id="channelNum">
							<option value="">Select...</option>
							<option value="0">0</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							<option value="31">31</option>
							<option value="32">32</option>
						</select>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelType">Type</label>
						<select class="formControl formSelect" name="channelType" id="channelType">
							<option value="">Select...</option>
							<option value="Calculated">Calculated</option>
							<option value="Analog Input">Analog Input</option>
							<option value="Analog Output">Analog Output</option>
							<option value="Digital Input">Digital Input</option>
							<option value="Digital Output">Digital Output</option>
							<option value="System Data">System Data</option>
							<option value="System Clock">System Clock</option>
						</select>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelConfiguration">Configuration</label>
						<select class="formControl formSelect" name="channelConfiguration" id="channelConfiguration">
							<option value="">Select...</option>
							<option value="System">System</option>
							<option value="Differential">Differential</option>
							<option value="Single Ended">Single Ended</option>
							<option value="Referenced Single Ended">Referenced Single Ended</option>
							<option value="Serial">Serial</option>
							<option value="Parallel">Parallel</option>
							<option value="Single">Single</option>
							<option value="Line">Line</option>
						</select>
					</div>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelNote">Note</label>
						<input id="channelNote" class="formControl" type="text" value="">
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelResBit">Res [bit]</label>
						<input id="channelResBit" class="formControl" type="text" value="">
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelMin">Min</label>
						<input id="channelMin" class="formControl" type="text" value="">
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelMax">Max</label>
						<input id="channelMax" class="formControl" type="text" value="">
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelRate">Rate</label>
						<input id="channelRate" class="formControl" type="text" value="">
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelFilter">Filter</label>
						<input id="channelFilter" class="formControl" type="text" value="">
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelGain">Gain</label>
						<select class="formControl formSelect" name="channelGain" id="channelGain">
							<option value="">Select...</option>
							<option value="x1">x1</option>
							<option value="x2">x2</option>
							<option value="x5">x5</option>
							<option value="x10">x10</option>
							<option value="x20">x20</option>
							<option value="x25">x25</option>
							<option value="x50">x50</option>
							<option value="x100">x100</option>
						</select>
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w100">
					<div class="formPart">
						<label class="formLabel midHeader">Sensor/Actuator Information</label>
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelSensorActuator">Sensor/Actuator</label>
						<select class="formControl formSelect" name="channelSensorActuator" id="channelSensorActuator">
							<option value="">Select...</option>
							<option value="Clock">Clock</option>
							<option value="LVDT">LVDT</option>
							<option value="Load Cell">Load Cell</option>
							<option value="Capacitive Load Cell">Capacitive Load Cell</option>
							<option value="Pressure Transducer">Pressure Transducer</option>
							<option value="Thermocouple">Thermocouple</option>
							<option value="Hall Sensor">Hall Sensor</option>
							<option value="P-Wave Sensor">P-Wave Sensor</option>
							<option value="S-Wave Sensor">S-Wave Sensor</option>
							<option value="Encoder">Encoder</option>
							<option value="Strain Gauge">Strain Gauge</option>
							<option value="Thermistor">Thermistor</option>
							<option value="Force Gauge">Force Gauge</option>
							<option value="DCDT">DCDT</option>
							<option value="pH-Meter">pH-Meter</option>
							<option value="Flow Meter">Flow Meter</option>
							<option value="--">--</option>
							<option value="Linear Motor">Linear Motor</option>
							<option value="Servo Motor">Servo Motor</option>
							<option value="Step Motor">Step Motor</option>
							<option value="Actuator">Actuator</option>
							<option value="Heater">Heater</option>
							<option value="Power">Power</option>
							<option value="Trigger">Trigger</option>
						</select>
					</div>
				</div>
				<div class="formCell w66">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelSensorTemplate">IEEE Sensor Template</label>
						<select class="formControl formSelect" name="channelSensorTemplate" id="channelSensorTemplate">
							<option value="">Select...</option>
							<option value="Accelerometer &amp; Force">Accelerometer &amp; Force</option>
							<option value="Charge Amplifier (w/ attached accelerometer)">Charge Amplifier (w/ attached accelerometer)</option>
							<option value="Charge Amplifier (w/ attached force transducer)">Charge Amplifier (w/ attached force transducer)</option>
							<option value="Microphone with built-in preamplifier">Microphone with built-in preamplifier</option>
							<option value="Microphone Preamplfiers (w/ attached microphone)">Microphone Preamplfiers (w/ attached microphone)</option>
							<option value="Microphones (capacitive)">Microphones (capacitive)</option>
							<option value="High-Level Voltage Output Sensors">High-Level Voltage Output Sensors</option>
							<option value="Current Loop Output Sensors">Current Loop Output Sensors</option>
							<option value="Resistance Sensors">Resistance Sensors</option>
							<option value="Bridge Sensors">Bridge Sensors</option>
							<option value="LVDT/RVDT Sensors">LVDT/RVDT Sensors</option>
							<option value="Strain Gage">Strain Gage</option>
							<option value="Thermocouple">Thermocouple</option>
							<option value="Resistance Temperature Detectors (RTDs)">Resistance Temperature Detectors (RTDs)</option>
							<option value="Thermistor">Thermistor</option>
							<option value="Potentiometric Voltage Divider">Potentiometric Voltage Divider</option>
						</select>
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelSensorType">Type</label>
						<select class="formControl formSelect" name="channelSensorType" id="channelSensorType">
							<option value="">Select...</option>
							<option value="Active">Active</option>
							<option value="Passive">Passive</option>
						</select>
					</div>
				</div>
				<div class="formCell w50">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelSensorManufacturer">Manufacturer ID</label>
						<input id="channelSensorManufacturer" class="formControl" type="text" value="">
					</div>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelSensorModelNum">Model #</label>
						<input id="channelSensorModelNum" class="formControl" type="text" value="">
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelSensorVersionLetter">Version Letter</label>
						<input id="channelSensorVersionLetter" class="formControl" type="text" value="">
					</div>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelSensorVersionNum">Version #</label>
						<input id="channelSensorVersionNum" class="formControl" type="text" value="">
					</div>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelSensorSerialNum">Serial #</label>
						<input id="channelSensorSerialNum" class="formControl" type="text" value="">
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w100">
					<div class="formPart">
						<label class="formLabel midHeader">Calibration Information</label>
						<div style="width:670px;margin-left:25px;">
							Data can be entered as Pairs: Calibration Table-Input:Unit; Linear Regression1 Input@0:Input/Unit;
							Linear Regression2 u=(x*a0+a1)*a2+a3; Polynomial-Base:Exponent); Frequency Response Table-Frequency:Amplitude
						</div>
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelCalibrationTemplate">Template</label>
						<select class="formControl formSelect" name="channelCalibrationTemplate" id="channelCalibrationTemplate">
							<option value="">Select...</option>
							<option value="Input:Unit">Input:Unit</option>
							<option value="Input@0:Input/Unit">Input@0:Input/Unit</option>
							<option value="(a0:a1)(a2:a3)">(a0:a1)(a2:a3)</option>
							<option value="Base:Exponent">Base:Exponent</option>
							<option value="Frequency:Amplitude">Frequency:Amplitude</option>
						</select>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelCalibrationInput">Input</label>
						<select class="formControl formSelect" name="channelCalibrationInput" id="channelCalibrationInput">
							<option value="">Select...</option>
							<option value="degC">degC</option>
							<option value="degK">degK</option>
							<option value="sec">sec</option>
							<option value="min">min</option>
							<option value="hour">hour</option>
							<option value="Volt">Volt</option>
							<option value="mV">mV</option>
							<option value="Amperage">Amperage</option>
							<option value="mA">mA</option>
							<option value="Ohm">Ohm</option>
							<option value="Pa">Pa</option>
							<option value="MPa">MPa</option>
							<option value="GPa">GPa</option>
							<option value="bar">bar</option>
							<option value="kbar">kbar</option>
							<option value="N">N</option>
							<option value="kN">kN</option>
							<option value="g">g</option>
							<option value="mg">mg</option>
							<option value="μg">μg</option>
							<option value="m">m</option>
							<option value="cm">cm</option>
							<option value="mm">mm</option>
							<option value="μm">μm</option>
							<option value="Hz">Hz</option>
							<option value="kHz">kHz</option>
							<option value="MHz">MHz</option>
							<option value="Pa·s">Pa·s</option>
							<option value="Darcy">Darcy</option>
							<option value="mDarcy">mDarcy</option>
							<option value="m-1">m-1</option>
							<option value="m2">m2</option>
							<option value="milistrain">milistrain</option>
							<option value="mm·sec-1">mm·sec-1</option>
							<option value="N·sec-1">N·sec-1</option>
							<option value="sec-1">sec-1</option>
							<option value="kN·mm-1">kN·mm-1</option>
							<option value="%">%</option>
							<option value="count">count</option>
							<option value="cc">cc</option>
							<option value="mm3">mm3</option>
						</select>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelCalibrationUnit">Unit</label>
						<select class="formControl formSelect" name="channelCalibrationUnit" id="channelCalibrationUnit">
							<option value="">Select...</option>
							<option value="degC">degC</option>
							<option value="degK">degK</option>
							<option value="sec">sec</option>
							<option value="min">min</option>
							<option value="hour">hour</option>
							<option value="Volt">Volt</option>
							<option value="mV">mV</option>
							<option value="Amperage">Amperage</option>
							<option value="mA">mA</option>
							<option value="Ohm">Ohm</option>
							<option value="Pa">Pa</option>
							<option value="MPa">MPa</option>
							<option value="GPa">GPa</option>
							<option value="bar">bar</option>
							<option value="kbar">kbar</option>
							<option value="N">N</option>
							<option value="kN">kN</option>
							<option value="g">g</option>
							<option value="mg">mg</option>
							<option value="μg">μg</option>
							<option value="m">m</option>
							<option value="cm">cm</option>
							<option value="mm">mm</option>
							<option value="μm">μm</option>
							<option value="Hz">Hz</option>
							<option value="kHz">kHz</option>
							<option value="MHz">MHz</option>
							<option value="Pa·s">Pa·s</option>
							<option value="Darcy">Darcy</option>
							<option value="mDarcy">mDarcy</option>
							<option value="m-1">m-1</option>
							<option value="m2">m2</option>
							<option value="milistrain">milistrain</option>
							<option value="mm·sec-1">mm·sec-1</option>
							<option value="N·sec-1">N·sec-1</option>
							<option value="sec-1">sec-1</option>
							<option value="kN·mm-1">kN·mm-1</option>
							<option value="%">%</option>
							<option value="count">count</option>
							<option value="cc">cc</option>
							<option value="mm3">mm3</option>
						</select>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelCalibrationExcitation">Excitation</label>
						<input id="channelCalibrationExcitation" class="formControl" type="text" value="">
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w40">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelCalibrationDate">Date</label>
						<input type="date" class="formControl" id="channelCalibrationDate" value="">
					</div>
				</div>
				<div class="formCell w60">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="channelCalibrationNote">Note</label>
						<input id="channelCalibrationNote" class="formControl" type="text" value="">
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w100">
					<div class="formPart">
						<fieldset class="mainFS">
							<legend class="subFSLegend">Data <button class="fsButton" style="vertical-align:middle" onclick="addDAQDeviceChannel();"><span>Add Data </span></button></legend>
							<div>
								<div class="subDataHolder" id="daqDeviceDataRows">
									<!-- Datas Here -->
								</div>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		<div class="docCell" style="width:25px;">
			<div style="padding-top:10px;"><button id="deleteButton" class="squareButton squareButtonBottom" onclick="alert('delete');"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
			<div><button id="upButton" class="squareButton squareButtonBottom" onclick="alert('up');" style="display: inline;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
			<div><button id="downButton" class="squareButton squareButtonBottom" onclick="alert('down');" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
		</div>
		<div style="clear:both;"></div>
	</div>
	<!-- end channel -->
</div>

<!-- Document -->
<div style="display:none;">
	<!-- One of these for each Document -->
	<div class="deviceRow" id="sourceDocumentRow" style="margin-top:5px; width:960px !important;">
		<div class="formCell" style="width:900px;padding-left:0px !important;">
			<div class="formRow">
				<div class="formCell 16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="docType">Document Type <span class="reqStar">*</span></label>
						<select class="formControl formSelect" id="docType">
							<option value="Manual">Manual</option>
							<option value="Diagram">Diagram</option>
							<option value="Picture">Picture</option>
							<option value="Video">Video</option>
							<option value="Data">Data</option>
							<option value="Software">Software</option>
							<option value="ASTM">ASTM</option>
							<option value="Publication">Publication</option>
							<option value="Other">Other</option>
						</select>
						<div id ="otherDocTypeHolder" style="white-space:nowrap;display:none;padding-top:5px;">
							<input id="otherDocType" class="formControl" style="width:110px;" type="text" value="" placeholder="doc type...">
						</div>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="docFormat">Document Format <span class="reqStar">*</span></label>
						<select class="formControl formSelect" id="docFormat">
							<option value="jpg">jpg</option>
							<option value="png">png</option>
							<option value="txt">txt</option>
							<option value="csv">csv</option>
							<option value="zip">zip</option>
							<option value="rar">rar</option>
							<option value="pdf">pdf</option>
							<option value="docx">docx</option>
							<option value="Other">Other</option>
						</select>
						<div id ="otherDocFormatHolder" style="white-space:nowrap;display:none;padding-top:5px;">
							<input id="otherDocFormat" class="formControl" type="text" value="" placeholder="enter document format here...">
						</div>
					</div>
				</div>
				<div class="formCell w50">
					<div class="formPart" id="fileHolder">
						<label class="formLabel tooltipper" exp-code="docFile">Choose File <span class="reqStar">*</span></label>
						<input type="file" id="docFile" class="formControl">
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="docId">Document ID</label>
						<input id="docId" class="formControl" type="text" value="">
						<input id="uuid" type="hidden" value="">
						<input id="originalFilename" type="hidden" value="">
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w100">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="docDescription">Description</label>
						<textarea class="formControl docDescText" data-schemaformat="markdown" id="docDescription"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="docCell" style="width:25px;">
			<div style="padding-top:10px;"><button id="deleteButton" class="squareButton squareButtonBottom" onclick="deleteDAQDeviceDocument(1);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
			<div><button id="upButton" class="squareButton squareButtonBottom" onclick="moveDAQDeviceDocumentUp(1);" style="display: inline;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
			<div><button id="downButton" class="squareButton squareButtonBottom" onclick="moveDAQDeviceDocumentDown(1);" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
		</div>
		<div class="clearLeft"></div>
	</div>
</div>

<!-- DAQ Data -->
<div style="display:none;">
	<!-- One of these for each DAQ data row -->
	<div class="formRow" id="sourceDataRow">
		<div>
			<div class="dataLeft">A: <input id="dataAVal" class="formControlData" type="text" value=""></div>
			<div class="dataLeft">B: <input id="dataBVal" class="formControlData" type="text" value=""></div>
			<button id="deleteButton" class="squareButton" style="margin-left:20px;" onclick="deleteDAQDeviceDocument(1);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
			<button id="upButton" class="squareButton" onclick="moveDAQDeviceDocumentUp(1);" style="display: inline;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
			<button id="downButton" class="squareButton" onclick="moveDAQDeviceDocumentDown(1);" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
		</div>
	</div>
</div>

<!-- Sample Modal -->
<div id="sampleModal" style="display:none;">
	<div class="grayOut"></div>
	<div class="redCloseBox" onclick="exper_doCancelSampleEdit();">X</div>
	<div class="modalBox" id="sampleModalBox">

	</div>
</div>

<!-- Text Input -->
<div style="display:none;">
	<input id="sourceTextInput" class="formControl" type="text" value="">
</div>

<!-- Soil -->
<div style="display:none;">
	<select class="formControl formSelect"  id="sourceSelectSoil">
		<option value="Other">Other</option>
		<option value="Bentonite">Bentonite</option>
	</select>
</div>

<!-- Mineral -->
<div style="display:none;">
	<select class="formControl formSelect"  id="sourceSelectMineral">
		<option value="Other">Other</option>
		<option value="Actinolite">Actinolite</option>
		<option value="Aegirine">Aegirine</option>
		<option value="Albite">Albite</option>
		<option value="Alkali - feldspar">Alkali - feldspar</option>
		<option value="Amphibole">Amphibole</option>
		<option value="Andesine">Andesine</option>
		<option value="Annite">Annite</option>
		<option value="Anorthite">Anorthite</option>
		<option value="Apatite">Apatite</option>
		<option value="Apophyllite">Apophyllite</option>
		<option value="Astrophyllite">Astrophyllite</option>
		<option value="Augite">Augite</option>
		<option value="Axinite">Axinite</option>
		<option value="Baryte">Baryte</option>
		<option value="Beryl">Beryl</option>
		<option value="Biopyribole">Biopyribole</option>
		<option value="Biopyrobole">Biopyrobole</option>
		<option value="Biotite">Biotite</option>
		<option value="Brookite">Brookite</option>
		<option value="Calcic pyroxene">Calcic pyroxene</option>
		<option value="Calcite">Calcite</option>
		<option value="Carbon">Carbon</option>
		<option value="Carbonates">Carbonates</option>
		<option value="Cassiterite">Cassiterite</option>
		<option value="Celestine">Celestine</option>
		<option value="Celsian">Celsian</option>
		<option value="Chain silicates">Chain silicates</option>
		<option value="Chalcocite">Chalcocite</option>
		<option value="Chalcopyrite">Chalcopyrite</option>
		<option value="Corundum">Corundum</option>
		<option value="Cristobalite">Cristobalite</option>
		<option value="Cummingtonite">Cummingtonite</option>
		<option value="Diamond">Diamond</option>
		<option value="Diaspore">Diaspore</option>
		<option value="Diopside">Diopside</option>
		<option value="Dolomite">Dolomite</option>
		<option value="Ekermannite">Ekermannite</option>
		<option value="Enstatite">Enstatite</option>
		<option value="Epidote">Epidote</option>
		<option value="Epidote Group">Epidote Group</option>
		<option value="Feldspar">Feldspar</option>
		<option value="Fe - Mg clinoamphibole">Fe - Mg clinoamphibole</option>
		<option value="Ferrosilite">Ferrosilite</option>
		<option value="Fe - Ti oxide">Fe - Ti oxide</option>
		<option value="Galena">Galena</option>
		<option value="Garnet">Garnet</option>
		<option value="Gedrite">Gedrite</option>
		<option value="Gibbsite">Gibbsite</option>
		<option value="Glauconite">Glauconite</option>
		<option value="Glaucophane">Glaucophane</option>
		<option value="Grunerite">Grunerite</option>
		<option value="Gypsum">Gypsum</option>
		<option value="Hedenbergite">Hedenbergite</option>
		<option value="Hematite">Hematite</option>
		<option value="Hercynite">Hercynite</option>
		<option value="Hornblende">Hornblende</option>
		<option value="Humite">Humite</option>
		<option value="Hydroxides">Hydroxides</option>
		<option value="Jadeite">Jadeite</option>
		<option value="Kaersutite">Kaersutite</option>
		<option value="Kalsilite">Kalsilite</option>
		<option value="Kaolinite">Kaolinite</option>
		<option value="Katophorite">Katophorite</option>
		<option value="K feldspar">K feldspar</option>
		<option value="K - feldspar">K - feldspar</option>
		<option value="Kyanite">Kyanite</option>
		<option value="Labradorite">Labradorite</option>
		<option value="Larnite">Larnite</option>
		<option value="Lawsonite">Lawsonite</option>
		<option value="Lepidocrocite">Lepidocrocite</option>
		<option value="Lepidolite">Lepidolite</option>
		<option value="Leucite">Leucite</option>
		<option value="Limonite">Limonite</option>
		<option value="Magnesite">Magnesite</option>
		<option value="Magnetite">Magnetite</option>
		<option value="Margarite">Margarite</option>
		<option value="Melilite">Melilite</option>
		<option value="Merwinite">Merwinite</option>
		<option value="Mica">Mica</option>
		<option value="Microcline">Microcline</option>
		<option value="Monazite">Monazite</option>
		<option value="Montmorillonite">Montmorillonite</option>
		<option value="Mullite">Mullite</option>
		<option value="Muscovite">Muscovite</option>
		<option value="Native Elements">Native Elements</option>
		<option value="Nepheline">Nepheline</option>
		<option value="Oligoclase">Oligoclase</option>
		<option value="Olivine">Olivine</option>
		<option value="Omphacite">Omphacite</option>
		<option value="Opaque">Opaque</option>
		<option value="Orthoamphibole">Orthoamphibole</option>
		<option value="Ortho - and Ring Silicates">Ortho - and Ring Silicates</option>
		<option value="Orthoclase">Orthoclase</option>
		<option value="Orthopyroxene">Orthopyroxene</option>
		<option value="Other">Other</option>
		<option value="Oxides">Oxides</option>
		<option value="Paragonite">Paragonite</option>
		<option value="Periclase">Periclase</option>
		<option value="Perovskite">Perovskite</option>
		<option value="Perthite">Perthite</option>
		<option value="Phengite">Phengite</option>
		<option value="Phlogopite">Phlogopite</option>
		<option value="Phosphates">Phosphates</option>
		<option value="Piemontite">Piemontite</option>
		<option value="Plagioclase">Plagioclase</option>
		<option value="Prehnite">Prehnite</option>
		<option value="Pumpellyite">Pumpellyite</option>
		<option value="Pyrite">Pyrite</option>
		<option value="Pyroxmangite">Pyroxmangite</option>
		<option value="Pyrrhotite">Pyrrhotite</option>
		<option value="Quartz">Quartz</option>
		<option value="Rankinite">Rankinite</option>
		<option value="Rhodochrosite">Rhodochrosite</option>
		<option value="Rhodonite">Rhodonite</option>
		<option value="Richterite">Richterite</option>
		<option value="Riebeckite">Riebeckite</option>
		<option value="Rutile">Rutile</option>
		<option value="Sanidine">Sanidine</option>
		<option value="Sapphirine">Sapphirine</option>
		<option value="Scapolite">Scapolite</option>
		<option value="Sericite">Sericite</option>
		<option value="Spinel">Spinel</option>
		<option value="Spinel Group">Spinel Group</option>
		<option value="Spodumene">Spodumene</option>
		<option value="Spurrite">Spurrite</option>
		<option value="Staurolite">Staurolite</option>
		<option value="Stilpnomelane">Stilpnomelane</option>
		<option value="Sulphates">Sulphates</option>
		<option value="Sulphides">Sulphides</option>
		<option value="Talc">Talc</option>
		<option value="Tectosilicates">Tectosilicates</option>
		<option value="Tilleyite">Tilleyite</option>
		<option value="Titanite">Titanite</option>
		<option value="Topaz">Topaz</option>
		<option value="Tourmaline">Tourmaline</option>
		<option value="Tremolite">Tremolite</option>
		<option value="Tridymite">Tridymite</option>
		<option value="Vermiculite">Vermiculite</option>
		<option value="Vesuvanite">Vesuvanite</option>
		<option value="White Mica">White Mica</option>
		<option value="Wollastonite">Wollastonite</option>
		<option value="Wonesite">Wonesite</option>
		<option value="Xenotime">Xenotime</option>
		<option value="Zeolite">Zeolite</option>
		<option value="Zircon">Zircon</option>
		<option value="Zoisite">Zoisite</option>
	</select>
</div>

<!-- Igneous -->
<div style="display:none;">
	<select class="formControl formSelect"  id="sourceSelectIgneous">
		<option value="Other">Other</option>
		<option value="alkali feldspar granite">alkali feldspar granite</option>
		<option value="andesite">andesite</option>
		<option value="anorthosite">anorthosite</option>
		<option value="aplite">aplite</option>
		<option value="diabase">diabase</option>
		<option value="diorite">diorite</option>
		<option value="granite">granite</option>
		<option value="harzburgite">harzburgite</option>
		<option value="lapilli tuff">lapilli tuff</option>
		<option value="norite">norite</option>
		<option value="pegmatite">pegmatite</option>
		<option value="peridotite">peridotite</option>
		<option value="quartz monzonite">quartz monzonite</option>
		<option value="rhyolite">rhyolite</option>
		<option value="syenite">syenite</option>
		<option value="tonalite">tonalite</option>
		<option value="trachyandesite">trachyandesite</option>
		<option value="tuff breccia">tuff breccia</option>
	</select>
</div>

<!-- Sedimentary -->
<div style="display:none;">
	<select class="formControl formSelect"  id="sourceSelectSedimentary">
		<option value="Other">Other</option>
		<option value="breccia">breccia</option>
		<option value="chert">chert</option>
		<option value="conglomerate">conglomerate</option>
		<option value="dolostone">dolostone</option>
		<option value="coal">coal</option>
		<option value="organic">organic</option>
		<option value="phosphatic">phosphatic</option>
		<option value="siltstone">siltstone</option>
		<option value="volcaniclastic">volcaniclastic</option>
		<option value="sandstone">sandstone</option>
		<option value="limestone">limestone</option>
		<option value="shale">shale</option>
	</select>
</div>

<!-- Metamorphic -->
<div style="display:none;">
	<select class="formControl formSelect"  id="sourceSelectMetamorphic">
		<option value="Other">Other</option>
		<option value="slate">slate</option>
		<option value="amphibolite">amphibolite</option>
		<option value="blackwall">blackwall</option>
		<option value="blueschist">blueschist</option>
		<option value="garnetite">garnetite</option>
		<option value="glaucophanite">glaucophanite</option>
		<option value="granulite">granulite</option>
		<option value="greenstone">greenstone</option>
		<option value="hornfels">hornfels</option>
		<option value="metaarkose">metaarkose</option>
		<option value="metabasite">metabasite</option>
		<option value="metacarbonate">metacarbonate</option>
		<option value="metagranite">metagranite</option>
		<option value="metagraywacke">metagraywacke</option>
		<option value="metaigneous">metaigneous</option>
		<option value="meta-iron formation">meta-iron formation</option>
		<option value="meta-ultramafic">meta-ultramafic</option>
		<option value="orthogneiss">orthogneiss</option>
		<option value="paragneiss">paragneiss</option>
		<option value="phyllite">phyllite</option>
		<option value="phyllonite">phyllonite</option>
		<option value="pyroxenite">pyroxenite</option>
		<option value="quartzite">quartzite</option>
		<option value="schist">schist</option>
		<option value="serpentinite">serpentinite</option>
		<option value="skarn">skarn</option>
	</select>
</div>

<!-- Epos -->
<div style="display:none;">
	<select class="formControl formSelect"  id="sourceSelectEpos">
		<option value="Other">Other</option>
		<option value="acidic_igneous_material">acidic_igneous_material</option>
		<option value="acidic_igneous_rock">acidic_igneous_rock</option>
		<option value="alkali_feldspar_granite">alkali_feldspar_granite</option>
		<option value="alkali_feldspar_rhyolite">alkali_feldspar_rhyolite</option>
		<option value="alkali_feldspar_syenite">alkali_feldspar_syenite</option>
		<option value="alkali_feldspar_syenitic_rock">alkali_feldspar_syenitic_rock</option>
		<option value="alkali_feldspar_trachyte">alkali_feldspar_trachyte</option>
		<option value="alkali_feldspar_trachytic_rock">alkali_feldspar_trachytic_rock</option>
		<option value="alkali - olivine_basalt">alkali - olivine_basalt</option>
		<option value="amphibolite">amphibolite</option>
		<option value="andesite">andesite</option>
		<option value="anorthosite">anorthosite</option>
		<option value="anorthositic_rock">anorthositic_rock</option>
		<option value="anthracite_coal">anthracite_coal</option>
		<option value="anthropogenic_material">anthropogenic_material</option>
		<option value="anthropogenic_unconsolidated_material">anthropogenic_unconsolidated_material</option>
		<option value="aphanite">aphanite</option>
		<option value="aplite">aplite</option>
		<option value="arenite">arenite</option>
		<option value="ash_and_lapilli">ash_and_lapilli</option>
		<option value="ash_breccia_bomb_or_block_tephra">ash_breccia_bomb_or_block_tephra</option>
		<option value="ash_tuff_lapillistone_and_lapilli_tuff">ash_tuff_lapillistone_and_lapilli_tuff</option>
		<option value="basalt">basalt</option>
		<option value="basanite">basanite</option>
		<option value="basanitic_foidite">basanitic_foidite</option>
		<option value="basic_igneous_material">basic_igneous_material</option>
		<option value="basic_igneous_rock">basic_igneous_rock</option>
		<option value="bauxite">bauxite</option>
		<option value="biogenic_sediment">biogenic_sediment</option>
		<option value="biogenic_silica_sedimentary_rock">biogenic_silica_sedimentary_rock</option>
		<option value="bituminous_coal">bituminous_coal</option>
		<option value="boninite">boninite</option>
		<option value="boulder_gravel_size_sediment">boulder_gravel_size_sediment</option>
		<option value="boundstone">boundstone</option>
		<option value="breccia">breccia</option>
		<option value="breccia_gouge_series">breccia_gouge_series</option>
		<option value="calcareous_carbonate_sediment">calcareous_carbonate_sediment</option>
		<option value="calcareous_carbonate_sedimentary_material">calcareous_carbonate_sedimentary_material</option>
		<option value="calcareous_carbonate_sedimentary_rock">calcareous_carbonate_sedimentary_rock</option>
		<option value="carbonate_mud">carbonate_mud</option>
		<option value="carbonate_mudstone">carbonate_mudstone</option>
		<option value="carbonate_ooze">carbonate_ooze</option>
		<option value="carbonate_rich_mud">carbonate_rich_mud</option>
		<option value="carbonate_rich_mudstone">carbonate_rich_mudstone</option>
		<option value="carbonate_sediment">carbonate_sediment</option>
		<option value="carbonate_sedimentary_material">carbonate_sedimentary_material</option>
		<option value="carbonate_sedimentary_rock">carbonate_sedimentary_rock</option>
		<option value="carbonate_wackestone">carbonate_wackestone</option>
		<option value="carbonatite">carbonatite</option>
		<option value="cataclasite_series">cataclasite_series</option>
		<option value="chalk">chalk</option>
		<option value="chemical_sedimentary_material">chemical_sedimentary_material</option>
		<option value="chlorite_actinolite_epidote_metamorphic_rock">chlorite_actinolite_epidote_metamorphic_rock</option>
		<option value="clastic_conglomerate">clastic_conglomerate</option>
		<option value="clastic_mudstone">clastic_mudstone</option>
		<option value="clastic_sandstone">clastic_sandstone</option>
		<option value="clastic_sediment">clastic_sediment</option>
		<option value="clastic_sedimentary_material">clastic_sedimentary_material</option>
		<option value="clastic_sedimentary_rock">clastic_sedimentary_rock</option>
		<option value="clay">clay</option>
		<option value="claystone">claystone</option>
		<option value="coal">coal</option>
		<option value="cobble_gravel_size_sediment">cobble_gravel_size_sediment</option>
		<option value="composite_genesis_material">composite_genesis_material</option>
		<option value="composite_genesis_rock">composite_genesis_rock</option>
		<option value="compound_material">compound_material</option>
		<option value="crystalline_carbonate">crystalline_carbonate</option>
		<option value="dacite">dacite</option>
		<option value="diamictite">diamictite</option>
		<option value="diamicton">diamicton</option>
		<option value="diorite">diorite</option>
		<option value="dioritic_rock">dioritic_rock</option>
		<option value="dioritoid">dioritoid</option>
		<option value="doleritic_rock">doleritic_rock</option>
		<option value="dolomitic_or_magnesian_sedimentary_material">dolomitic_or_magnesian_sedimentary_material</option>
		<option value="dolomitic_or_magnesian_sedimentary_rock">dolomitic_or_magnesian_sedimentary_rock</option>
		<option value="dolomitic_sediment">dolomitic_sediment</option>
		<option value="dolostone">dolostone</option>
		<option value="duricrust">duricrust</option>
		<option value="eclogite">eclogite</option>
		<option value="evaporite">evaporite</option>
		<option value="exotic_alkaline_rock">exotic_alkaline_rock</option>
		<option value="exotic_composition_igneous_rock">exotic_composition_igneous_rock</option>
		<option value="exotic_evaporite">exotic_evaporite</option>
		<option value="fault_related_material">fault_related_material</option>
		<option value="fine_grained_igneous_rock">fine_grained_igneous_rock</option>
		<option value="foid_bearing_alkali_feldspar_syenite">foid_bearing_alkali_feldspar_syenite</option>
		<option value="foid_bearing_alkali_feldspar_trachyte">foid_bearing_alkali_feldspar_trachyte</option>
		<option value="foid_bearing_anorthosite">foid_bearing_anorthosite</option>
		<option value="foid_bearing_diorite">foid_bearing_diorite</option>
		<option value="foid_bearing_gabbro">foid_bearing_gabbro</option>
		<option value="foid_bearing_latite">foid_bearing_latite</option>
		<option value="foid_bearing_monzodiorite">foid_bearing_monzodiorite</option>
		<option value="foid_bearing_monzogabbro">foid_bearing_monzogabbro</option>
		<option value="foid_bearing_monzonite">foid_bearing_monzonite</option>
		<option value="foid_bearing_syenite">foid_bearing_syenite</option>
		<option value="foid_bearing_trachyte">foid_bearing_trachyte</option>
		<option value="foid_diorite">foid_diorite</option>
		<option value="foid_dioritoid">foid_dioritoid</option>
		<option value="foid_gabbro">foid_gabbro</option>
		<option value="foid_gabbroid">foid_gabbroid</option>
		<option value="foid_monzodiorite">foid_monzodiorite</option>
		<option value="foid_monzogabbro">foid_monzogabbro</option>
		<option value="foid_monzosyenite">foid_monzosyenite</option>
		<option value="foid_syenite">foid_syenite</option>
		<option value="foid_syenitoid">foid_syenitoid</option>
		<option value="foidite">foidite</option>
		<option value="foiditoid">foiditoid</option>
		<option value="foidolite">foidolite</option>
		<option value="foliated_metamorphic_rock">foliated_metamorphic_rock</option>
		<option value="fragmental_igneous_material">fragmental_igneous_material</option>
		<option value="fragmental_igneous_rock">fragmental_igneous_rock</option>
		<option value="framestone">framestone</option>
		<option value="gabbro">gabbro</option>
		<option value="gabbroic_rock">gabbroic_rock</option>
		<option value="gabbroid">gabbroid</option>
		<option value="generic_conglomerate">generic_conglomerate</option>
		<option value="generic_mudstone">generic_mudstone</option>
		<option value="generic_sandstone">generic_sandstone</option>
		<option value="glass_rich_igneous_rock">glass_rich_igneous_rock</option>
		<option value="glassy_igneous_rock">glassy_igneous_rock</option>
		<option value="glaucophane_lawsonite_epidote_metamorphic_rock">glaucophane_lawsonite_epidote_metamorphic_rock</option>
		<option value="gneiss">gneiss</option>
		<option value="grainstone">grainstone</option>
		<option value="granite">granite</option>
		<option value="granitoid">granitoid</option>
		<option value="granodiorite">granodiorite</option>
		<option value="granofels">granofels</option>
		<option value="granulite">granulite</option>
		<option value="gravel">gravel</option>
		<option value="gravel_size_sediment">gravel_size_sediment</option>
		<option value="high_magnesium_fine_grained_igneous_rocks">high_magnesium_fine_grained_igneous_rocks</option>
		<option value="hornblendite">hornblendite</option>
		<option value="hornfels">hornfels</option>
		<option value="hybrid_sediment">hybrid_sediment</option>
		<option value="hybrid_sedimentary_rock">hybrid_sedimentary_rock</option>
		<option value="igneous_material">igneous_material</option>
		<option value="igneous_rock">igneous_rock</option>
		<option value="impact_generated_material">impact_generated_material</option>
		<option value="impure_calcareous_carbonate_sediment">impure_calcareous_carbonate_sediment</option>
		<option value="impure_carbonate_sediment">impure_carbonate_sediment</option>
		<option value="impure_carbonate_sedimentary_rock">impure_carbonate_sedimentary_rock</option>
		<option value="impure_dolomitic_sediment">impure_dolomitic_sediment</option>
		<option value="impure_dolostone">impure_dolostone</option>
		<option value="impure_limestone">impure_limestone</option>
		<option value="intermediate_composition_igneous_material">intermediate_composition_igneous_material</option>
		<option value="intermediate_composition_igneous_rock">intermediate_composition_igneous_rock</option>
		<option value="iron_rich_sediment">iron_rich_sediment</option>
		<option value="iron_rich_sedimentary_material">iron_rich_sedimentary_material</option>
		<option value="iron_rich_sedimentary_rock">iron_rich_sedimentary_rock</option>
		<option value="kalsilitic_and_melilitic_rock">kalsilitic_and_melilitic_rock</option>
		<option value="komatiitic_rock">komatiitic_rock</option>
		<option value="latite">latite</option>
		<option value="latitic_rock">latitic_rock</option>
		<option value="lignite">lignite</option>
		<option value="limestone">limestone</option>
		<option value="marble">marble</option>
		<option value="material_formed_in_surficial_environment">material_formed_in_surficial_environment</option>
		<option value="metamorphic_rock">metamorphic_rock</option>
		<option value="metasomatic_rock">metasomatic_rock</option>
		<option value="mica_schist">mica_schist</option>
		<option value="migmatite">migmatite</option>
		<option value="monzodiorite">monzodiorite</option>
		<option value="monzodioritic_rock">monzodioritic_rock</option>
		<option value="monzogabbro">monzogabbro</option>
		<option value="monzogabbroic_rock">monzogabbroic_rock</option>
		<option value="monzogranite">monzogranite</option>
		<option value="monzonite">monzonite</option>
		<option value="monzonitic_rock">monzonitic_rock</option>
		<option value="mud">mud</option>
		<option value="mud_size_sediment">mud_size_sediment</option>
		<option value="mylonitic_rock">mylonitic_rock</option>
		<option value="natural_unconsolidated_material">natural_unconsolidated_material</option>
		<option value="non_clastic_siliceous_sediment">non_clastic_siliceous_sediment</option>
		<option value="non_clastic_siliceous_sedimentary_material">non_clastic_siliceous_sedimentary_material</option>
		<option value="non_clastic_siliceous_sedimentary_rock">non_clastic_siliceous_sedimentary_rock</option>
		<option value="ooze">ooze</option>
		<option value="organic_bearing_mudstone">organic_bearing_mudstone</option>
		<option value="organic_rich_sediment">organic_rich_sediment</option>
		<option value="organic_rich_sedimentary_material">organic_rich_sedimentary_material</option>
		<option value="organic_rich_sedimentary_rock">organic_rich_sedimentary_rock</option>
		<option value="orthogneiss">orthogneiss</option>
		<option value="packstone">packstone</option>
		<option value="paragneiss">paragneiss</option>
		<option value="peat">peat</option>
		<option value="pegmatite">pegmatite</option>
		<option value="peridotite">peridotite</option>
		<option value="phaneritic_igneous_rock">phaneritic_igneous_rock</option>
		<option value="phonolilte">phonolilte</option>
		<option value="phonolitic_basanite">phonolitic_basanite</option>
		<option value="phonolitic_foidite">phonolitic_foidite</option>
		<option value="phonolitic_tephrite">phonolitic_tephrite</option>
		<option value="phonolitoid">phonolitoid</option>
		<option value="phosphate_rich_sediment">phosphate_rich_sediment</option>
		<option value="phosphate_rich_sedimentary_material">phosphate_rich_sedimentary_material</option>
		<option value="phosphorite">phosphorite</option>
		<option value="phyllite">phyllite</option>
		<option value="phyllonite">phyllonite</option>
		<option value="porphyry">porphyry</option>
		<option value="pure_calcareous_carbonate_sediment">pure_calcareous_carbonate_sediment</option>
		<option value="pure_carbonate_mudstone">pure_carbonate_mudstone</option>
		<option value="pure_carbonate_sediment">pure_carbonate_sediment</option>
		<option value="pure_carbonate_sedimentary_rock">pure_carbonate_sedimentary_rock</option>
		<option value="pure_dolomitic_sediment">pure_dolomitic_sediment</option>
		<option value="pyroclastic_material">pyroclastic_material</option>
		<option value="pyroclastic_rock">pyroclastic_rock</option>
		<option value="pyroxenite">pyroxenite</option>
		<option value="quartz_alkali_feldspar_syenite">quartz_alkali_feldspar_syenite</option>
		<option value="quartz_alkali_feldspar_trachyte">quartz_alkali_feldspar_trachyte</option>
		<option value="quartz_anorthosite">quartz_anorthosite</option>
		<option value="quartz_diorite">quartz_diorite</option>
		<option value="quartz_gabbro">quartz_gabbro</option>
		<option value="quartz_latite">quartz_latite</option>
		<option value="quartz_monzodiorite">quartz_monzodiorite</option>
		<option value="quartz_monzogabbro">quartz_monzogabbro</option>
		<option value="quartz_monzonite">quartz_monzonite</option>
		<option value="quartz_rich_igneous_rock">quartz_rich_igneous_rock</option>
		<option value="quartz_syenite">quartz_syenite</option>
		<option value="quartz_trachyte">quartz_trachyte</option>
		<option value="quartzite">quartzite</option>
		<option value="residual_material">residual_material</option>
		<option value="rhyolite">rhyolite</option>
		<option value="rhyolitoid">rhyolitoid</option>
		<option value="rock">rock</option>
		<option value="rock_gypsum_or_anhydrite">rock_gypsum_or_anhydrite</option>
		<option value="rock_salt">rock_salt</option>
		<option value="sand">sand</option>
		<option value="sand_size_sediment">sand_size_sediment</option>
		<option value="sapropel">sapropel</option>
		<option value="schist">schist</option>
		<option value="sediment">sediment</option>
		<option value="sedimentary_material">sedimentary_material</option>
		<option value="sedimentary_rock">sedimentary_rock</option>
		<option value="serpentinite">serpentinite</option>
		<option value="shale">shale</option>
		<option value="silicate_mud">silicate_mud</option>
		<option value="silicate_mudstone">silicate_mudstone</option>
		<option value="siliceous_ooze">siliceous_ooze</option>
		<option value="silt">silt</option>
		<option value="siltstone">siltstone</option>
		<option value="skarn">skarn</option>
		<option value="slate">slate</option>
		<option value="spilite">spilite</option>
		<option value="syenite">syenite</option>
		<option value="syenitic_rock">syenitic_rock</option>
		<option value="syenitoid">syenitoid</option>
		<option value="syenogranite">syenogranite</option>
		<option value="tephra">tephra</option>
		<option value="tephrite">tephrite</option>
		<option value="tephritic_foidite">tephritic_foidite</option>
		<option value="tephritic_phonolite">tephritic_phonolite</option>
		<option value="tephritoid">tephritoid</option>
		<option value="tholeiitic_basalt">tholeiitic_basalt</option>
		<option value="tonalite">tonalite</option>
		<option value="trachyte">trachyte</option>
		<option value="trachytic_rock">trachytic_rock</option>
		<option value="trachytoid">trachytoid</option>
		<option value="travertine">travertine</option>
		<option value="tuff_breccia_agglomerate_or_pyroclastic_breccia">tuff_breccia_agglomerate_or_pyroclastic_breccia</option>
		<option value="tuffite">tuffite</option>
		<option value="ultrabasic_igneous_rock">ultrabasic_igneous_rock</option>
		<option value="ultramafic_igneous_rock">ultramafic_igneous_rock</option>
		<option value="unconsolidated_material">unconsolidated_material</option>
		<option value="wacke">wacke</option>
	</select>
</div>

<!-- Standards -->
<div style="display:none;">
	<select class="formControl formSelect"  id="sourceSelectStandards">
		<option value="Other">Other</option>
		<option value="Austin Chalk">Austin Chalk</option>
		<option value="Berea Sandstone">Berea Sandstone</option>
		<option value="Carrara Marble">Carrara Marble</option>
		<option value="Indiana Limestone">Indiana Limestone</option>
		<option value="Frederick Maryland Diabase">Frederick Maryland Diabase</option>
		<option value="Heavitree Quartzite">Heavitree Quartzite</option>
		<option value="Solnhofen Limestone">Solnhofen Limestone</option>
		<option value="Simpson Quartzite">Simpson Quartzite</option>
		<option value="Black Hills Quartzite">Black Hills Quartzite</option>
		<option value="Sioux Quartzite">Sioux Quartzite</option>
		<option value="Cheshire Quartzite">Cheshire Quartzite</option>
		<option value="Arkansas Novaculite">Arkansas Novaculite</option>
		<option value="Westerley Granite">Westerley Granite</option>
	</select>
</div>

<!-- Commodity -->
<div style="display:none;">
	<select class="formControl formSelect"  id="sourceSelectCommodity">
		<option value="Other">Other</option>
		<option value="agate">agate</option>
		<option value="aggregate">aggregate</option>
		<option value="alumina">alumina</option>
		<option value="aluminium">aluminium</option>
		<option value="aluminosilicate">aluminosilicate</option>
		<option value="alunite">alunite</option>
		<option value="amazonite">amazonite</option>
		<option value="amber">amber</option>
		<option value="amethyst">amethyst</option>
		<option value="andalusite">andalusite</option>
		<option value="anhydrite">anhydrite</option>
		<option value="anthophyllite">anthophyllite</option>
		<option value="anthracite">anthracite</option>
		<option value="antimony">antimony</option>
		<option value="apatite">apatite</option>
		<option value="apatite - gemstone">apatite - gemstone</option>
		<option value="aquamarine">aquamarine</option>
		<option value="arsenic">arsenic</option>
		<option value="asbestos">asbestos</option>
		<option value="asbestos - amphibole">asbestos - amphibole</option>
		<option value="asbestos - serpentine">asbestos - serpentine</option>
		<option value="asphalt">asphalt</option>
		<option value="barium">barium</option>
		<option value="baryte">baryte</option>
		<option value="basalt">basalt</option>
		<option value="base - metal">base - metal</option>
		<option value="bauxite">bauxite</option>
		<option value="bentonite">bentonite</option>
		<option value="beryl">beryl</option>
		<option value="beryllium">beryllium</option>
		<option value="bismuth">bismuth</option>
		<option value="black - coal">black - coal</option>
		<option value="borate">borate</option>
		<option value="boron">boron</option>
		<option value="brick - clay">brick - clay</option>
		<option value="bromine">bromine</option>
		<option value="brown - coal">brown - coal</option>
		<option value="cadmium">cadmium</option>
		<option value="calcite">calcite</option>
		<option value="carbonaceous - material">carbonaceous - material</option>
		<option value="carnallite">carnallite</option>
		<option value="carnelian">carnelian</option>
		<option value="cassiterite - gemstone">cassiterite - gemstone</option>
		<option value="cerium">cerium</option>
		<option value="cesium">cesium</option>
		<option value="chalcedony">chalcedony</option>
		<option value="chemical - compound - product">chemical - compound - product</option>
		<option value="chemical - oxide - product">chemical - oxide - product</option>
		<option value="chert">chert</option>
		<option value="chlorite">chlorite</option>
		<option value="chrome">chrome</option>
		<option value="chromite">chromite</option>
		<option value="chromium">chromium</option>
		<option value="chrysoberyl">chrysoberyl</option>
		<option value="chrysoprase">chrysoprase</option>
		<option value="chrysotile">chrysotile</option>
		<option value="citrine">citrine</option>
		<option value="clay">clay</option>
		<option value="coal">coal</option>
		<option value="coal - bed - methane">coal - bed - methane</option>
		<option value="cobalt">cobalt</option>
		<option value="copper">copper</option>
		<option value="corundum">corundum</option>
		<option value="corundum - gemstone">corundum - gemstone</option>
		<option value="crocidolite">crocidolite</option>
		<option value="crushed - rock">crushed - rock</option>
		<option value="cryolite">cryolite</option>
		<option value="diamond">diamond</option>
		<option value="diamond - gemstone">diamond - gemstone</option>
		<option value="diatomite">diatomite</option>
		<option value="dimension - stone">dimension - stone</option>
		<option value="diopside - enstatite">diopside - enstatite</option>
		<option value="dioptase">dioptase</option>
		<option value="direct - shipping - ore">direct - shipping - ore</option>
		<option value="direct - use - commodity">direct - use - commodity</option>
		<option value="dumortierite">dumortierite</option>
		<option value="dysprosium">dysprosium</option>
		<option value="emerald">emerald</option>
		<option value="epsomite">epsomite</option>
		<option value="erbium">erbium</option>
		<option value="euclase">euclase</option>
		<option value="europium">europium</option>
		<option value="evaporite">evaporite</option>
		<option value="feldspar">feldspar</option>
		<option value="feldspar - gemstone">feldspar - gemstone</option>
		<option value="ferrous - metal">ferrous - metal</option>
		<option value="fluorine">fluorine</option>
		<option value="fluorite">fluorite</option>
		<option value="foundry - sand">foundry - sand</option>
		<option value="frac - sand">frac - sand</option>
		<option value="fullers - earth">fullers - earth</option>
		<option value="gadolinium">gadolinium</option>
		<option value="gallium">gallium</option>
		<option value="garnet">garnet</option>
		<option value="garnet - gemstone">garnet - gemstone</option>
		<option value="gaseous - hydrocarbons">gaseous - hydrocarbons</option>
		<option value="gas - hydrate">gas - hydrate</option>
		<option value="gemstones">gemstones</option>
		<option value="germanium">germanium</option>
		<option value="glauconite">glauconite</option>
		<option value="gold">gold</option>
		<option value="granite">granite</option>
		<option value="graphite">graphite</option>
		<option value="greenstone">greenstone</option>
		<option value="gypsum">gypsum</option>
		<option value="hafnium">hafnium</option>
		<option value="halloysite">halloysite</option>
		<option value="heavy - rare - earth - oxide">heavy - rare - earth - oxide</option>
		<option value="heliodor">heliodor</option>
		<option value="hematite">hematite</option>
		<option value="hematite - gemstone">hematite - gemstone</option>
		<option value="hematite - ore">hematite - ore</option>
		<option value="holmium">holmium</option>
		<option value="hree">hree</option>
		<option value="ilmenite">ilmenite</option>
		<option value="indium">indium</option>
		<option value="industrial - material">industrial - material</option>
		<option value="industrial - mineral">industrial - mineral</option>
		<option value="industrial - rock">industrial - rock</option>
		<option value="iodine">iodine</option>
		<option value="iolite">iolite</option>
		<option value="iridium">iridium</option>
		<option value="iron">iron</option>
		<option value="iron - ore">iron - ore</option>
		<option value="iron - oxide">iron - oxide</option>
		<option value="jade">jade</option>
		<option value="jarosite">jarosite</option>
		<option value="kaolin">kaolin</option>
		<option value="kornerupine">kornerupine</option>
		<option value="kyanite">kyanite</option>
		<option value="kyanite - gemstone">kyanite - gemstone</option>
		<option value="lanthanum">lanthanum</option>
		<option value="laterite">laterite</option>
		<option value="lazulite">lazulite</option>
		<option value="lead">lead</option>
		<option value="leucoxene">leucoxene</option>
		<option value="light - rare - earth - oxide">light - rare - earth - oxide</option>
		<option value="lime">lime</option>
		<option value="limestone">limestone</option>
		<option value="liquid - hydrocarbons">liquid - hydrocarbons</option>
		<option value="lithium">lithium</option>
		<option value="lithium - oxide">lithium - oxide</option>
		<option value="lree">lree</option>
		<option value="lutetium">lutetium</option>
		<option value="magnesia">magnesia</option>
		<option value="magnesite">magnesite</option>
		<option value="magnesium">magnesium</option>
		<option value="magnetite">magnetite</option>
		<option value="magnetite - ore">magnetite - ore</option>
		<option value="malachite">malachite</option>
		<option value="manganese">manganese</option>
		<option value="manganese - ore">manganese - ore</option>
		<option value="marble">marble</option>
		<option value="mercury">mercury</option>
		<option value="metal">metal</option>
		<option value="metalloid">metalloid</option>
		<option value="mica">mica</option>
		<option value="miscellaneous - dimension - stones">miscellaneous - dimension - stones</option>
		<option value="molybdenite">molybdenite</option>
		<option value="molybdenum">molybdenum</option>
		<option value="monazite">monazite</option>
		<option value="moonstone">moonstone</option>
		<option value="morganite">morganite</option>
		<option value="moss - agate">moss - agate</option>
		<option value="natural - secondary - aggregate">natural - secondary - aggregate</option>
		<option value="neodymium">neodymium</option>
		<option value="nepheline - syenite">nepheline - syenite</option>
		<option value="nickel">nickel</option>
		<option value="niobium">niobium</option>
		<option value="niobium - pentoxide">niobium - pentoxide</option>
		<option value="nitrate">nitrate</option>
		<option value="non - metal">non - metal</option>
		<option value="obsidian">obsidian</option>
		<option value="ochre">ochre</option>
		<option value="oil">oil</option>
		<option value="oil - shale">oil - shale</option>
		<option value="olivine">olivine</option>
		<option value="olivine - gemstone">olivine - gemstone</option>
		<option value="onyx">onyx</option>
		<option value="opal">opal</option>
		<option value="organic - material">organic - material</option>
		<option value="osmium">osmium</option>
		<option value="palladium">palladium</option>
		<option value="palygorskite">palygorskite</option>
		<option value="peat">peat</option>
		<option value="perlite">perlite</option>
		<option value="phenakite">phenakite</option>
		<option value="phosphate - rock">phosphate - rock</option>
		<option value="phosphorous">phosphorous</option>
		<option value="phosphorous - pentoxide">phosphorous - pentoxide</option>
		<option value="platinum">platinum</option>
		<option value="platinum - group">platinum - group</option>
		<option value="potash">potash</option>
		<option value="potassium">potassium</option>
		<option value="pozzolan">pozzolan</option>
		<option value="praseodymium">praseodymium</option>
		<option value="precious - metal">precious - metal</option>
		<option value="prehnite">prehnite</option>
		<option value="primary - aggregate">primary - aggregate</option>
		<option value="produced - commodity">produced - commodity</option>
		<option value="promethium">promethium</option>
		<option value="pumice">pumice</option>
		<option value="pyrite">pyrite</option>
		<option value="pyrophyllite">pyrophyllite</option>
		<option value="quartz">quartz</option>
		<option value="quartz - gemstone">quartz - gemstone</option>
		<option value="radium">radium</option>
		<option value="rare - earth - element">rare - earth - element</option>
		<option value="rare - earth - oxide">rare - earth - oxide</option>
		<option value="recycled - aggregate">recycled - aggregate</option>
		<option value="reservoir - gas">reservoir - gas</option>
		<option value="rhenium">rhenium</option>
		<option value="rhodium">rhodium</option>
		<option value="rhodonite">rhodonite</option>
		<option value="riprap">riprap</option>
		<option value="rose - quartz">rose - quartz</option>
		<option value="rubidium">rubidium</option>
		<option value="ruby">ruby</option>
		<option value="ruthenium">ruthenium</option>
		<option value="rutile">rutile</option>
		<option value="salt">salt</option>
		<option value="samarium">samarium</option>
		<option value="sand">sand</option>
		<option value="sand - and - gravel">sand - and - gravel</option>
		<option value="sandstone">sandstone</option>
		<option value="saponite">saponite</option>
		<option value="sapphire">sapphire</option>
		<option value="sapphirine">sapphirine</option>
		<option value="scandium">scandium</option>
		<option value="scapolite">scapolite</option>
		<option value="selenium">selenium</option>
		<option value="sepiolite">sepiolite</option>
		<option value="sericite">sericite</option>
		<option value="serpentine">serpentine</option>
		<option value="shell - grit">shell - grit</option>
		<option value="silica">silica</option>
		<option value="silica - gemstone">silica - gemstone</option>
		<option value="silicon">silicon</option>
		<option value="sillimanite">sillimanite</option>
		<option value="silver">silver</option>
		<option value="sinhalite">sinhalite</option>
		<option value="slate">slate</option>
		<option value="smokey - quartz">smokey - quartz</option>
		<option value="soda - ash">soda - ash</option>
		<option value="sodalite">sodalite</option>
		<option value="spectrolite">spectrolite</option>
		<option value="spinel">spinel</option>
		<option value="spinel - gemstone">spinel - gemstone</option>
		<option value="spodumene">spodumene</option>
		<option value="spongolite">spongolite</option>
		<option value="staurolite">staurolite</option>
		<option value="strontianite">strontianite</option>
		<option value="strontium">strontium</option>
		<option value="sulphur">sulphur</option>
		<option value="sylvite">sylvite</option>
		<option value="talc">talc</option>
		<option value="tantalum">tantalum</option>
		<option value="tantalum - pentoxide">tantalum - pentoxide</option>
		<option value="tanzanite">tanzanite</option>
		<option value="tar - sand">tar - sand</option>
		<option value="tellurium">tellurium</option>
		<option value="terbium">terbium</option>
		<option value="thallium">thallium</option>
		<option value="thenardite">thenardite</option>
		<option value="thorium">thorium</option>
		<option value="thulium">thulium</option>
		<option value="tin">tin</option>
		<option value="titanium">titanium</option>
		<option value="topaz">topaz</option>
		<option value="tourmaline">tourmaline</option>
		<option value="tremolite - actinolite">tremolite - actinolite</option>
		<option value="tsavorite">tsavorite</option>
		<option value="tungsten">tungsten</option>
		<option value="turquoise">turquoise</option>
		<option value="uranium">uranium</option>
		<option value="uranium - oxide">uranium - oxide</option>
		<option value="vanadium">vanadium</option>
		<option value="vanadium - pentoxide">vanadium - pentoxide</option>
		<option value="variscite">variscite</option>
		<option value="vermiculite">vermiculite</option>
		<option value="vesuvianite">vesuvianite</option>
		<option value="white - firing - clay">white - firing - clay</option>
		<option value="wollastonite">wollastonite</option>
		<option value="xenotime - gemstone">xenotime - gemstone</option>
		<option value="ytterbium">ytterbium</option>
		<option value="yttrium">yttrium</option>
		<option value="yttrium - oxide">yttrium - oxide</option>
		<option value="zeolite">zeolite</option>
		<option value="zinc">zinc</option>
		<option value="zircon">zircon</option>
		<option value="zircon - gemstone">zircon - gemstone</option>
		<option value="zirconia">zirconia</option>
		<option value="zirconium">zirconium</option>
	</select>
</div>

<!-- Sample Modal -->
<div style="display:none;">
	<div id="sourceSampleModal">
		<div class="topTitle" style="padding-top:10px;">Sample Info</div>
		<div>
			<fieldset class="mainFS" style="padding-top: 0px !important;">
				<legend class="mainFSLegend">Sample Info</legend>
				<div class="formRow">
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleName">Sample Name <span class="reqStar">*</span></label>
							<input id="sampleName" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleIGSN">IGSN </label>
							<input id="sampleIGSN" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleID">Sample ID <span class="reqStar">*</span></label>
							<input id="sampleID" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w40">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleDescription">Description</label>
							<input id="sampleDescription" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w25" style="padding-left:20px;">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="parentSampleName">Parent Sample Name</label>
							<input id="parentSampleName" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="parentSampleIGSN">Parent IGSN </label>
							<input id="parentSampleIGSN" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="parentSampleID">Parent Sample ID</label>
							<input id="parentSampleID" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="parentSampleDescription">Parent Description</label>
							<input id="parentSampleDescription" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<fieldset class="mainFS" style="padding-top: 0px !important;">
				<legend class="mainFSLegend">Material</legend>
				<div class="formRow">
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="materialType">Material Type <span class="reqStar">*</span></label>
							<select class="formControl formSelect"  id="materialType" onchange="exper_updateSampleMaterialNameInput();">
								<option value="">Select...</option>
								<option value="Glass">Glass</option>
								<option value="Ice">Ice</option>
								<option value="Ceramic">Ceramic</option>
								<option value="Plastic">Plastic</option>
								<option value="Metal">Metal</option>
								<option value="Soil">Soil</option>
								<option value="Mineral">Mineral</option>
								<option value="Igneous Rock">Igneous Rock</option>
								<option value="Sedimentary Rock">Sedimentary Rock</option>
								<option value="Metamorphic Rock">Metamorphic Rock</option>
								<option value="Epos Lithologies">Epos Lithologies</option>
								<option value="Standards">Standards</option>
								<option value="Commodity">Commodity</option>
							</select>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="materialName"><span id="materialNameLabel" >Name</span> <span class="reqStar">*</span></label>
							<div id="materialNameHolder">
								<input id="materialName" class="formControl" type="text" value="">
							</div>
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="materialState">State </label>
							<select class="formControl formSelect"  id="materialState">
								<option value="">Select...</option>
								<option value="Homogeneous">Homogeneous</option>
								<option value="Heterogeneous">Heterogeneous</option>
								<option value="Powder/Gauge">Powder/Gauge</option>
								<option value="Discontinuous">Discontinuous</option>
								<option value="Continuous">Continuous</option>
								<option value="Composite">Composite</option>
							</select>
						</div>
					</div>
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="materialNote">Note</label>
							<input id="materialNote" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<!-- Mineralogy -->
			<fieldset class="mainFS" style="margin-top:10px">
				<legend class="mainFSLegend">Mineralogy <button class="fsButton" style="vertical-align:middle" onclick="exper_addSampleMineralPhase();"><span>Add Phase </span></button></legend>
				<div>
					<div class="subDataHolder" style="padding-left:0px !important;">
						<!-- Phases Here -->
						<div style="float:left;">
							<div id="sample_mineral_phase_buttons">
								<!-- buttons here -->
							</div>
						</div>
						<div style="float:left;padding-left:5px;">
							<div  id="sample_mineral_phases">
								<!-- each phase in here -->
							</div>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<fieldset class="mainFS" style="padding-top: 0px !important;">
				<legend class="mainFSLegend">Provenance</legend>
				<div class="formRow">
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="formationName">Formation Name</label>
							<input id="formationName" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="memberName">Member Name</label>
							<input id="memberName" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="subMemberName">Sub Member Name</label>
							<input id="subMemberName" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleSource">Source</label>
							<select class="formControl formSelect"  id="sampleSource">
								<option value="Surface">Surface</option>
								<option value="Quarry">Quarry</option>
								<option value="Well">Well</option>
							</select>
						</div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<fieldset class="mainFS" style="padding-top: 0px !important;">
				<legend class="mainFSLegend">Location</legend>
				<div class="formRow">
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleLocationStreet">Street + Number</label>
							<input id="sampleLocationStreet" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleLocationBuilding">Building - Apt</label>
							<input id="sampleLocationBuilding" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleLocationPostcode">Postal Code</label>
							<input id="sampleLocationPostcode" class="formControl" oninput="this.value = fixZip(this.value);" type="text" value="">
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleLocationCity">City</label>
							<input id="sampleLocationCity" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleLocationState">State</label>
							<input id="sampleLocationState" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleLocationCountry">Country</label>
							<input id="sampleLocationCountry" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleLocationLatitude">Latitude</label>
							<input id="sampleLocationLatitude" class="formControl" type="text" oninput="this.value = constrainDecimal(this.value);" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleLocationLongitude">Longitude</label>
							<input id="sampleLocationLongitude" class="formControl" type="text" oninput="this.value = constrainDecimal(this.value);" value="">
						</div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<fieldset class="mainFS" style="padding-top: 0px !important;">
				<legend class="mainFSLegend">Texture</legend>
				<div class="formRow">
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleTextureBedding">Bedding</label>
							<input id="sampleTextureBedding" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleTextureLineation">Lineation</label>
							<input id="sampleTextureLineation" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleTextureFoliation">Foliation</label>
							<input id="sampleTextureFoliation" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleTextureFault">Fault</label>
							<input id="sampleTextureFault" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<!-- Parameters -->
			<fieldset class="mainFS" style="margin-top:10px">
				<legend class="mainFSLegend">Parameters <button class="fsButton" style="vertical-align:middle" onclick="exper_addSampleParameter();"><span>Add Parameter </span></button></legend>
				<div>
					<div class="subDataHolder" style="padding-left:0px !important;">
						<!-- Phases Here -->
						<div style="float:left;">
							<div id="sample_parameter_buttons">
								<!-- buttons here -->
							</div>
						</div>
						<div style="float:left;padding-left:5px;">
							<div  id="sample_parameters">
								<!-- each parameter in here -->
							</div>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<!-- Documents -->
			<fieldset class="mainFS" style="margin-top:10px">
				<legend class="mainFSLegend">Documents <button id="addDocumentButton" class="fsButton" style="vertical-align:middle" onclick="exper_addSampleDocument();"><span>Add Document </span></button></legend>
				<div>
					<div class="subDataHolder" style="padding-left:0px !important;">
						<!-- Documents Here -->
						<div style="float:left;">
							<div id="sample_document_buttons">
								<!-- buttons here -->
							</div>
						</div>
						<div style="float:left;padding-left:5px;">
							<div  id="sample_documents">
								<!-- each document in here -->
							</div>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<div style="text-align:center;">
				<button class="submitButton" style="vertical-align:middle;margin-right:150px;" onclick="exper_doCancelSampleEdit()"><span>Cancel </span></button>
				<button class="submitButton" style="vertical-align:middle" onclick="exper_doSaveSampleInfo()"><span>Save </span></button>
			</div>
		</div>
	</div>
</div>

<!-- Sample Mineral -->
<div style="display:none;">
	<div class="deviceRow sample_mineral_phase_group" id="sourceSampleMineral" style="margin-top: 5px; width: 1065px !important; display: block;">
		<div class="formCell" style="padding-left:0px !important;">
			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="mineral">Mineral <span class="reqStar">*</span></label>
						<div id="mineralSelectHolder">
						<!-- Mineral Select Here --->
						</div>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="mineralFraction">Fraction</label>
						<input id="mineralFraction" class="formControl" type="text" value="">
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="mineralGrainSize">Grain Size [µm]</label>
						<input id="mineralGrainSize" class="formControl" type="text" value="">
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="mineralUnit">Unit</label>
						<select class="formControl formSelect" id="mineralUnit">
							<option value="Vol%">Vol%</option>
							<option value="Mol%">Mol%</option>
							<option value="Wt%">Wt%</option>
							<option value="MPa">MPa</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="docCell" style="width:120px; white-space: nowrap;">
			<button id="deleteButton" class="squareButton squareButtonBottom" onclick="exper_deleteSampleMineralPhase(99);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
			<button id="upButton" class="squareButton squareButtonBottom" onclick="exper_moveSampleMineralPhaseUp(99);"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
			<button id="downButton" class="squareButton squareButtonBottom" onclick="exper_moveSampleMineralPhaseDown(99);"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>

<!-- Sample Parameter -->
<div style="display:none;">
	<div class="deviceRow sample_parameter_group" id="sourceSampleParameter" style="margin-top: 5px; width: 1065px !important; display: block;">
		<div class="formCell" style="padding-left:0px !important;">
			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="parameterVariable">Variable <span class="reqStar">*</span></label>
						<div>
							<select class="formControl formSelect" id="parameterVariable">
								<option value="Weight">Weight</option>
								<option value="Connected Porosity">Connected Porosity</option>
								<option value="Unconnected Porosity">Unconnected Porosity</option>
								<option value="Total Porosity">Total Porosity</option>
								<option value="Density">Density</option>
								<option value="Permeability (Gas)">Permeability (Gas)</option>
								<option value="Permeability (Water)">Permeability (Water)</option>
								<option value="Temperature">Temperature</option>
								<option value="Humidity">Humidity</option>
								<option value="Fluid Saturation">Fluid Saturation</option>
								<option value="Stress">Stress</option>
								<option value="Other">Other</option>
							</select>

							<div id ="otherParameterControlHolder" style="white-space:nowrap;display:none;padding-top:5px;">
								<input id="otherParameterControl" class="formControl" type="text" value="" placeholder="other variable...">
							</div>

						</div>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="parameterValue">Value</label>
						<input id="parameterValue" class="formControl" type="text" value="">
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="parameterUnit">Unit</label>
						<select class="formControl formSelect" id="parameterUnit">
							<option value="degC">degC</option>
							<option value="degK">degK</option>
							<option value="sec">sec</option>
							<option value="min">min</option>
							<option value="hour">hour</option>
							<option value="Volt">Volt</option>
							<option value="mV">mV</option>
							<option value="Amperage">Amperage</option>
							<option value="mA">mA</option>
							<option value="Ohm">Ohm</option>
							<option value="Pa">Pa</option>
							<option value="MPa">MPa</option>
							<option value="GPa">GPa</option>
							<option value="bar">bar</option>
							<option value="kbar">kbar</option>
							<option value="N">N</option>
							<option value="kN">kN</option>
							<option value="g">g</option>
							<option value="mg">mg</option>
							<option value="μg">μg</option>
							<option value="m">m</option>
							<option value="cm">cm</option>
							<option value="mm">mm</option>
							<option value="μm">μm</option>
							<option value="Hz">Hz</option>
							<option value="kHz">kHz</option>
							<option value="MHz">MHz</option>
							<option value="Pa·s">Pa·s</option>
							<option value="Darcy">Darcy</option>
							<option value="mDarcy">mDarcy</option>
							<option value="m-1">m-1</option>
							<option value="m2">m2</option>
							<option value="milistrain">milistrain</option>
							<option value="mm·sec-1">mm·sec-1</option>
							<option value="N·sec-1">N·sec-1</option>
							<option value="sec-1">sec-1</option>
							<option value="kN·mm-1">kN·mm-1</option>
							<option value="%">%</option>
							<option value="count">count</option>
							<option value="cc">cc</option>
							<option value="mm3">mm3</option>
						</select>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="parameterPrefix">Prefix</label>
						<select class="formControl formSelect" id="parameterPrefix">
							<option value="1E+1">1E+1</option>
							<option value="1E+2">1E+2</option>
							<option value="1E+3">1E+3</option>
							<option value="1E+4">1E+4</option>
							<option value="1E+5">1E+5</option>
							<option value="1E+6">1E+6</option>
							<option value="-" selected>-</option>
							<option value="1E-1">1E-1</option>
							<option value="1E-2">1E-2</option>
							<option value="1E-3">1E-3</option>
							<option value="1E-4">1E-4</option>
							<option value="1E-5">1E-5</option>
							<option value="1E-6">1E-6</option>
						</select>
					</div>
				</div>
			</div>
			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w100">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="parameterNote">Note (Measurement and Treatment)</label>
						<div>
							<input id="parameterNote" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="docCell" style="width:120px; white-space: nowrap;">
			<button id="deleteButton" class="squareButton squareButtonBottom" onclick="exper_deleteSampleMineralPhase(0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
			<button id="upButton" class="squareButton squareButtonBottom" onclick="exper_moveSampleMineralPhaseUp(0);"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
			<button id="downButton" class="squareButton squareButtonBottom" onclick="exper_moveSampleMineralPhaseDown(0);"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>

<!-- Experiment Setup Modal -->
<div id="experimentSetupModal" style="display:none;">
	<div class="grayOut"></div>
	<div class="redCloseBox" onclick="exper_doCancelExperimentSetupEdit();">X</div>
	<div class="modalBox" id="experimentSetupModalBox">

	</div>
</div>

<!-- Source Experiment Setup -->
<div style="display:none;">
	<div id="sourceExperimentSetupModal">
		<div class="topTitle" style="padding-top:10px;">Experimental Setup Info</div>
		<div>
			<fieldset class="mainFS" style="padding-top: 0px !important;">
				<legend class="mainFSLegend">Experiment Info</legend>
				<div class="formRow">
					<div class="formCell w100">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="experimentTitle">Title <span class="reqStar">*</span></label>
							<input id="experimentTitle" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
				<div class="formRow">
					<!--
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="sampleName">Project</label>
							<input id="sampleName" class="formControl" type="text" value="sample name here">
						</div>
					</div>
					-->
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="experimentId">Experiment ID <span class="reqStar">*</span></label>
							<input id="experimentId" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="iedaId">IEDA ID</label>
							<input id="iedaId" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="experimentStartDate">Start Date</label>
							<input type="date" class="formControl" id="experimentStartDate" value="">
						</div>
					</div>
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="experimentEndDate">End Date</label>
							<input type="date" class="formControl" id="experimentEndDate" value="">
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w100">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="experimentDescription">Experiment Description</label>
							<textarea class="formControl docDescText" data-schemaformat="markdown" id="experimentDescription"></textarea>
						</div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>

			<fieldset class="mainFS" style="padding-top: 0px !important;">
				<legend class="mainFSLegend"><label class="tooltipper" exp-code="experimentTestFeatures">Test Features</Label></legend>
				<div class="formRow">
					<div class="formCell w100">
						<div class="formPart" id="experimentTestFeatures">
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_loading" checked>&nbsp;Loading</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_unloading" checked>&nbsp;Unloading</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_heating">&nbsp;Heating</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_cooling">&nbsp;Cooling</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_high_temperature">&nbsp;High Temperature</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_ultra-high_temperature">&nbsp;Ultra-High Temperature</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_low_temperature">&nbsp;Low Temperature</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_sub-zero_temperature">&nbsp;Sub-Zero Temperature</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_high_pressure">&nbsp;High Pressure</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_ultra-high_pressure">&nbsp;Ultra-High Pressure</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_hydrostatic_tests">&nbsp;Hydrostatic Tests</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_hip">&nbsp;HIP</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_synthesis">&nbsp;Synthesis</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_deposition_evaporation">&nbsp;Deposition/Evaporation</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_mineral_reactions">&nbsp;Mineral Reactions</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_hydrothermal_reactions">&nbsp;Hydrothermal Reactions</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_elasticity">&nbsp;Elasticity</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_local_axial_strain">&nbsp;Local Axial Strain</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_local_radial_strain">&nbsp;Local Radial Strain</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_elastic_moduli">&nbsp;Elastic Moduli</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_yield_strength">&nbsp;Yield Strength</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_failure_strength">&nbsp;Failure Strength</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_strength">&nbsp;Strength</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_extension">&nbsp;Extension</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_creep">&nbsp;Creep</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_friction">&nbsp;Friction</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_frictional_sliding">&nbsp;Frictional Sliding</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_slide_hold_slide">&nbsp;Slide Hold Slide</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_stepping">&nbsp;Stepping</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_pure_shear">&nbsp;Pure Shear</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_simple_shear">&nbsp;Simple Shear</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_rotary_shear">&nbsp;Rotary Shear</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_torsion">&nbsp;Torsion</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_viscosity">&nbsp;Viscosity</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_indentation">&nbsp;Indentation</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_hardness">&nbsp;Hardness</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_dynamic_tests">&nbsp;Dynamic Tests</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_hydraulic_fracturing">&nbsp;Hydraulic Fracturing</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_hydrothermal_fracturing">&nbsp;Hydrothermal Fracturing</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_shockwave">&nbsp;Shockwave</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_reactive_flow">&nbsp;Reactive Flow</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_pore_fluid_control">&nbsp;Pore Fluid Control</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_pore_fluid_chemistry">&nbsp;Pore Fluid Chemistry</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_pore_volume_compaction">&nbsp;Pore Volume Compaction</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_storage_capacity">&nbsp;Storage Capacity</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_permeability">&nbsp;Permeability</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_steady-state_permeability">&nbsp;Steady-State Permeability</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_transient_permeability">&nbsp;Transient Permeability</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_hydraulic_conductivity">&nbsp;Hydraulic Conductivity</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_drained_undrained_pore_fluid">&nbsp;Drained/Undrained Pore Fluid</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_uniaxial_stress_strain">&nbsp;Uniaxial Stress/Strain</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_biaxial_stress_strain">&nbsp;Biaxial Stress/Strain</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_triaxial_stress_strain">&nbsp;Triaxial Stress/Strain</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_differential_stress">&nbsp;Differential Stress</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_true_triaxial">&nbsp;True Triaxial</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_resistivity">&nbsp;Resistivity</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_electrical_resistivity">&nbsp;Electrical Resistivity</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_electrical_capacitance">&nbsp;Electrical Capacitance</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_streaming_potential">&nbsp;Streaming Potential</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_acoustic_velocity">&nbsp;Acoustic Velocity</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_acoustic_events">&nbsp;Acoustic Events</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_p-wave_velocity">&nbsp;P-Wave Velocity</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_s-wave_velocity">&nbsp;S-Wave Velocity</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_source_location">&nbsp;Source Location</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_tomography">&nbsp;Tomography</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_in-situ_x-ray">&nbsp;In-Situ X-Ray</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_infrared">&nbsp;Infrared</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_raman">&nbsp;Raman</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_visual">&nbsp;Visual</label></div>
							<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" onchange="exper_updateExperimentTestDropdowns()" id="f_other">&nbsp;Other</label></div>
						</div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>

			<fieldset class="mainFS" style="padding-top: 0px !important;">
				<legend class="mainFSLegend">Author</legend>
				<div class="formRow">
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="experimentFirstName">First Name</label>
							<input id="experimentFirstName" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="experimentLastName">Last Name</label>
							<input id="experimentLastName" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="experimentAffiliation">Affiliation</label>
							<select id="experimentAffiliation" class="formControl">
								<option value=""></option>
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
							<label class="formLabel tooltipper" exp-code="experimentEmail">Email</label>
							<input id="experimentEmail" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="experimentPhone">Phone</label>
							<input id="experimentPhone" class="formControl" type="text" value="">
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="experimentWebsite">Website</label>
							<input id="experimentWebsite" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w100">
						<div class="formPart">
							<label class="formLabel tooltipper" exp-code="experimentORCID">ORCID</label>
							<input id="experimentORCID" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>

			<!-- Geometries -->
			<fieldset class="mainFS" style="margin-top:10px">
				<legend class="mainFSLegend">Geometry <button class="fsButton" style="vertical-align:middle" onclick="exper_addExperimentGeometry();"><span>Add Geometry </span></button></legend>
				<div>
					<div class="subDataHolder" style="padding-left:0px !important;">

						<div style="float:left;">
							<div id="experiment_geometry_buttons" style="padding-top:5px;">
								<!-- Buttons Here -->

							</div>
						</div>
						<div style="float:left;padding-left:5px;">
							<div  id="experiment_geometries">
								<!-- Geometries Here -->
							</div>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>

			<!-- Protocol -->
			<fieldset class="mainFS" style="margin-top:10px">
				<legend class="mainFSLegend">Protocol <button class="fsButton" style="vertical-align:middle" onclick="exper_addExperimentProtocol();"><span>Add Step </span></button></legend>
				<div>
					<div class="subDataHolder" style="padding-left:0px !important;">

						<div style="float:left;">
							<div id="experiment_protocol_buttons" style="padding-top:5px;">
								<!-- Buttons Here -->
							</div>
						</div>
						<div style="float:left;padding-left:5px;">
							<div  id="experiment_protocols">
								<!-- Protocols Here -->
							</div>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>

			<!-- Documents -->
			<fieldset class="mainFS" style="margin-top:10px">
				<legend class="mainFSLegend">Documents <button class="fsButton" style="vertical-align:middle" onclick="exper_addExperimentDocument();"><span>Add Document </span></button></legend>
				<div>
					<div class="subDataHolder" style="padding-left:0px !important;">

						<div style="float:left;">
							<div id="experiment_document_buttons" style="padding-top:5px;">
								<!-- Buttons Here -->
							</div>
						</div>
						<div style="float:left;padding-left:5px;">
							<div  id="experiment_documents">
								<!-- Documents Here -->
							</div>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>

			<div style="text-align:center;">
				<button class="submitButton" style="vertical-align:middle;margin-right:150px;" onclick="exper_doCancelExperimentSetupEdit()"><span>Cancel </span></button>
				<button class="submitButton" style="vertical-align:middle" onclick="exper_doSaveExperimentInfo()"><span>Save </span></button>
			</div>
		</div>
	</div>
</div>

<!-- Source Experiment Protocol -->
<div style="display:none;">
	<div class="deviceRow experiment_protocol_group" id="sourceExperimentProtocol" style="margin-top: 5px; width: 1065px !important; display: block;">
		<div class="formCell" style="padding-left:0px !important;">
			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w33" style="display:none;">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="experimentProtocolNum">Step #</label>
						<div>
							<select class="formControl formSelect" id="experimentProtocolNum">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
								<option value="29">29</option>
								<option value="30">30</option>
								<option value="31">31</option>
								<option value="32">32</option>
								<option value="33">33</option>
								<option value="34">34</option>
								<option value="35">35</option>
								<option value="36">36</option>
								<option value="37">37</option>
								<option value="38">38</option>
								<option value="39">39</option>
								<option value="40">40</option>
								<option value="41">41</option>
								<option value="42">42</option>
								<option value="43">43</option>
								<option value="44">44</option>
								<option value="45">45</option>
								<option value="46">46</option>
								<option value="47">47</option>
								<option value="48">48</option>
								<option value="49">49</option>
								<option value="50">50</option>
								<option value="51">51</option>
								<option value="52">52</option>
								<option value="53">53</option>
								<option value="54">54</option>
								<option value="55">55</option>
								<option value="56">56</option>
								<option value="57">57</option>
								<option value="58">58</option>
								<option value="59">59</option>
								<option value="60">60</option>
								<option value="61">61</option>
								<option value="62">62</option>
								<option value="63">63</option>
								<option value="64">64</option>
							</select>
						</div>
					</div>
				</div>

				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="experimentProtocolTest">Step</label>
						<div>
							<select class="formControl formSelect" id="experimentProtocolTest">
								<option value="foo">foo</option>
								<option value="foo">foo</option>
								<option value="foo">foo</option>
								<option value="foo">foo</option>
								<option value="foo">foo</option>
								<option value="foo">foo</option>
								<option value="foo">foo</option>
							</select>
						</div>
					</div>
				</div>

				<div class="formCell w66">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="experimentProtocolObjective">Objective</label>
						<div>
							<input id="experimentProtocolObjective" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
			</div>

			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w100">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="experimentProtocolDescription">Description</label>
						<div>
							<input id="experimentProtocolDescription" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
			</div>

			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w100">

					<!-- Parameters -->
					<fieldset class="mainFS" style="margin-top:10px">
						<legend class="mainFSLegend">Parameters <button id="addParameterButton" class="fsButton" style="vertical-align:middle"><span>Add Parameter </span></button></legend>
						<div id="parameterRowsHolder" style="padding-left:5px;">

							<div class="frontBoxRow" style="font-weight:bold;">
								<div class="dataLeft w16">Variable</div>
								<div class="dataLeft w16">Value</div>
								<div class="dataLeft w16">Unit</div>
								<div class="dataLeft w33">Note</div>
								<div class="dataLeft w16">&nbsp;</div>
							</div>

							<div id="parameterRows"></div>
						</div>
					</fieldset>
					<div class="fsSpacer"></div>

				</div>
			</div>

		</div>
		<div class="docCell" style="width:120px; white-space: nowrap;">
			<button id="deleteButton" class="squareButton squareButtonBottom" onclick="exper_deleteExperimentProtocol(0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
			<button id="upButton" class="squareButton squareButtonBottom" onclick="exper_moveExperimentProtocolUp(0);"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
			<button id="downButton" class="squareButton squareButtonBottom" onclick="exper_moveExperimentProtocolDown(0);"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>

<!-- Experiment Protocol Paramter -->
<div style="display:none;">
	<div class="frontBoxRow" id="sourceExperimentProtocolParameter" style="font-weight:bold;">
		<div class="dataLeft w16">
			<select class="formControl formSelect" id="parameterVariable">
				<option value="Temperature T">Temperature T</option>
				<option value="Confining Pressure Pc">Confining Pressure Pc</option>
				<option value="Pore Pressure Pp">Pore Pressure Pp</option>
				<option value="Time t">Time t</option>
				<option value="Frequency">Frequency</option>
				<option value="Amplitude">Amplitude</option>
				<option value="Stress σ1">Stress σ1</option>
				<option value="Strain ε1">Strain ε1</option>
				<option value="Strain Rate ε1/dt">Strain Rate ε1/dt</option>
				<option value="Displacement Δs1">Displacement Δs1</option>
				<option value="Force F2">Force F2</option>
				<option value="Stress σ2">Stress σ2</option>
				<option value="Strain ε2">Strain ε2</option>
				<option value="Strain Rate ε2/dt">Strain Rate ε2/dt</option>
				<option value="Displacement Δs2">Displacement Δs2</option>
				<option value="Force F2">Force F2</option>
				<option value="Stress σ3">Stress σ3</option>
				<option value="Strain ε3">Strain ε3</option>
				<option value="Strain Rate ε3/dt">Strain Rate ε3/dt</option>
				<option value="Displacement Δs3">Displacement Δs3</option>
				<option value="Force F3">Force F3</option>
				<option value="Saturation">Saturation</option>
				<option value="Humidity">Humidity</option>
				<option value="Count">Count</option>
			</select>
		</div>
		<div class="dataLeft w16">
			<input id="parameterValue" class="formControl" type="text" value="">
		</div>
		<div class="dataLeft w16">
			<select class="formControl formSelect" id="parameterUnit">
				<option value="degC">degC</option>
				<option value="degK">degK</option>
				<option value="sec">sec</option>
				<option value="min">min</option>
				<option value="hour">hour</option>
				<option value="Volt">Volt</option>
				<option value="mV">mV</option>
				<option value="Amperage">Amperage</option>
				<option value="mA">mA</option>
				<option value="Ohm">Ohm</option>
				<option value="Pa">Pa</option>
				<option value="MPa">MPa</option>
				<option value="GPa">GPa</option>
				<option value="bar">bar</option>
				<option value="kbar">kbar</option>
				<option value="N">N</option>
				<option value="kN">kN</option>
				<option value="g">g</option>
				<option value="mg">mg</option>
				<option value="μg">μg</option>
				<option value="m">m</option>
				<option value="cm">cm</option>
				<option value="mm">mm</option>
				<option value="μm">μm</option>
				<option value="Hz">Hz</option>
				<option value="kHz">kHz</option>
				<option value="MHz">MHz</option>
				<option value="Pa·s">Pa·s</option>
				<option value="Darcy">Darcy</option>
				<option value="mDarcy">mDarcy</option>
				<option value="m-1">m-1</option>
				<option value="m2">m2</option>
				<option value="milistrain">milistrain</option>
				<option value="mm·sec-1">mm·sec-1</option>
				<option value="N·sec-1">N·sec-1</option>
				<option value="sec-1">sec-1</option>
				<option value="kN·mm-1">kN·mm-1</option>
				<option value="%">%</option>
				<option value="count">count</option>
				<option value="cc">cc</option>
				<option value="mm3">mm3</option>
			</select>
		</div>
		<div class="dataLeft w33">
			<input id="parameterNote" class="formControl" type="text" value="">
		</div>
		<div class="dataLeft w16">
			<button id="deleteParameterButton" class="squareButton squareButtonBottom"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
			<button id="upParameterButton" class="squareButton squareButtonBottom"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
			<button id="downParameterButton" class="squareButton squareButtonBottom"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
		</div>
	</div>
</div>

<!-- Experiment Geometry -->
<div style="display:none;">
	<div class="deviceRow experiment_geometry_group" id="sourceExperimentGeometry" style="margin-top: 5px; width: 1065px !important; display: block;">
		<div class="formCell" style="padding-left:0px !important;">
			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="experimentGeometryNum">Geometry #</label>
						<div>
							<select class="formControl formSelect" id="experimentGeometryNum">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
								<option value="29">29</option>
								<option value="30">30</option>
								<option value="31">31</option>
								<option value="32">32</option>
								<option value="33">33</option>
								<option value="34">34</option>
								<option value="35">35</option>
								<option value="36">36</option>
								<option value="37">37</option>
								<option value="38">38</option>
								<option value="39">39</option>
								<option value="40">40</option>
								<option value="41">41</option>
								<option value="42">42</option>
								<option value="43">43</option>
								<option value="44">44</option>
								<option value="45">45</option>
								<option value="46">46</option>
								<option value="47">47</option>
								<option value="48">48</option>
								<option value="49">49</option>
								<option value="50">50</option>
								<option value="51">51</option>
								<option value="52">52</option>
								<option value="53">53</option>
								<option value="54">54</option>
								<option value="55">55</option>
								<option value="56">56</option>
								<option value="57">57</option>
								<option value="58">58</option>
								<option value="59">59</option>
								<option value="60">60</option>
								<option value="61">61</option>
								<option value="62">62</option>
								<option value="63">63</option>
								<option value="64">64</option>
							</select>
						</div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="experimentGeometryMaterial">Material</label>
						<div>
							<select class="formControl formSelect" id="experimentGeometryMaterial">
								<option value="">Select...</option>
								<option value="Sample">Sample</option>
								<option value="PET">PET</option>
								<option value="PVC">PVC</option>
								<option value="PTFE">PTFE</option>
								<option value="Rubber">Rubber</option>
								<option value="Viton">Viton</option>
								<option value="Copper">Copper</option>
								<option value="Iron">Iron</option>
								<option value="Nickel">Nickel</option>
								<option value="Gold">Gold</option>
								<option value="Platinum">Platinum</option>
								<option value="Silver">Silver</option>
								<option value="Alumina">Alumina</option>
								<option value="Porous Alumina">Porous Alumina</option>
								<option value="Zirconia">Zirconia</option>
								<option value="PZT">PZT</option>
								<option value="Polyolefin">Polyolefin</option>
								<option value="Hardened Steel">Hardened Steel</option>
								<option value="Tungsten Carbide">Tungsten Carbide</option>
								<option value="Inconel">Inconel</option>
							</select>
						</div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="experimentGeometryType">Type</label>
						<div>
							<select class="formControl formSelect" id="experimentGeometryType">
								<option value="">Select...</option>
								<option value="Sample">Sample</option>
								<option value="Jacket">Jacket</option>
								<option value="Forcing Block">Forcing Block</option>
								<option value="Spacer">Spacer</option>
							</select>
						</div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel tooltipper" exp-code="experimentGeometryGeometry">Geometry</label>
						<div>
							<select class="formControl formSelect" id="experimentGeometryGeometry">
								<option value="">Select...</option>
								<option value="Cylinder">Cylinder</option>
								<option value="Rectangular">Rectangular</option>
								<option value="Circular">Circular</option>
								<option value="Precut">Precut</option>
								<option value="Dogbone">Dogbone</option>
								<option value="Split Cylinder">Split Cylinder</option>
								<option value="Tube">Tube</option>
							</select>
						</div>
					</div>
				</div>
			</div>

			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w100">

					<!-- Dimensions -->
					<fieldset class="mainFS" style="margin-top:10px">
						<legend class="mainFSLegend">Dimensions <button id="addDimensionButton" class="fsButton" style="vertical-align:middle"><span>Add Dimension </span></button></legend>
						<div id="dimensionRowsHolder" style="padding-left:5px;">

							<div class="frontBoxRow" style="font-weight:bold;">
								<div class="dataLeft w16">Variable</div>
								<div class="dataLeft w16">Value</div>
								<div class="dataLeft w16">Unit</div>
								<div class="dataLeft w16">Prefix</div>
								<div class="dataLeft w16">Note</div>
								<div class="dataLeft w16">&nbsp;</div>
							</div>

							<div id="dimensionRows"></div>
						</div>
					</fieldset>
					<div class="fsSpacer"></div>

				</div>
			</div>

		</div>
		<div class="docCell" style="width:120px; white-space: nowrap;">
			<button id="deleteButton" class="squareButton squareButtonBottom" onclick="exper_deleteExperimentGeometry(0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
			<button id="upButton" class="squareButton squareButtonBottom" onclick="exper_moveExperimentGeometryUp(0);"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
			<button id="downButton" class="squareButton squareButtonBottom" onclick="exper_moveExperimentGeometryDown(0);"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>

<!-- Experiment Geometry Dimension -->
<div style="display:none;">
	<div class="frontBoxRow" id="sourceExperimentGeometryDimension" style="font-weight:bold;">
		<div class="dataLeft w16">
			<select class="formControl formSelect" id="dimensionVariable">
				<option value="Length">Length</option>
				<option value="Diameter">Diameter</option>
				<option value="Width">Width</option>
				<option value="Span">Span</option>
				<option value="Height">Height</option>
				<option value="Wall Thickness">Wall Thickness</option>
				<option value="Bore Diameter">Bore Diameter</option>
				<option value="Fault Angle">Fault Angle</option>
			</select>
		</div>
		<div class="dataLeft w16">
			<input id="dimensionValue" class="formControl" type="text" value="">
		</div>
		<div class="dataLeft w16">
			<select class="formControl formSelect" id="dimensionUnit">
				<option value="degC">degC</option>
				<option value="degK">degK</option>
				<option value="sec">sec</option>
				<option value="min">min</option>
				<option value="hour">hour</option>
				<option value="Volt">Volt</option>
				<option value="mV">mV</option>
				<option value="Amperage">Amperage</option>
				<option value="mA">mA</option>
				<option value="Ohm">Ohm</option>
				<option value="Pa">Pa</option>
				<option value="MPa">MPa</option>
				<option value="GPa">GPa</option>
				<option value="bar">bar</option>
				<option value="kbar">kbar</option>
				<option value="N">N</option>
				<option value="kN">kN</option>
				<option value="g">g</option>
				<option value="mg">mg</option>
				<option value="μg">μg</option>
				<option value="m">m</option>
				<option value="cm">cm</option>
				<option value="mm">mm</option>
				<option value="μm">μm</option>
				<option value="Hz">Hz</option>
				<option value="kHz">kHz</option>
				<option value="MHz">MHz</option>
				<option value="Pa·s">Pa·s</option>
				<option value="Darcy">Darcy</option>
				<option value="mDarcy">mDarcy</option>
				<option value="m-1">m-1</option>
				<option value="m2">m2</option>
				<option value="milistrain">milistrain</option>
				<option value="mm·sec-1">mm·sec-1</option>
				<option value="N·sec-1">N·sec-1</option>
				<option value="sec-1">sec-1</option>
				<option value="kN·mm-1">kN·mm-1</option>
				<option value="%">%</option>
				<option value="count">count</option>
				<option value="cc">cc</option>
				<option value="mm3">mm3</option>
			</select>
		</div>
		<div class="dataLeft w16">
			<select class="formControl formSelect" id="dimensionPrefix">
				<option value="1E+1">1E+1</option>
				<option value="1E+2">1E+2</option>
				<option value="1E+3">1E+3</option>
				<option value="1E+4">1E+4</option>
				<option value="1E+5">1E+5</option>
				<option value="1E+6">1E+6</option>
				<option value="-" selected>-</option>
				<option value="1E-1">1E-1</option>
				<option value="1E-2">1E-2</option>
				<option value="1E-3">1E-3</option>
				<option value="1E-4">1E-4</option>
				<option value="1E-5">1E-5</option>
				<option value="1E-6">1E-6</option>
			</select>
		</div>
		<div class="dataLeft w16">
			<input id="dimensionNote" class="formControl" type="text" value="">
		</div>
		<div class="dataLeft w16">
			<button id="deleteDimensionButton" class="squareButton squareButtonBottom"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
			<button id="upDimensionButton" class="squareButton squareButtonBottom"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
			<button id="downDimensionButton" class="squareButton squareButtonBottom"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
		</div>
	</div>
</div>

<!-- Data -->
<!-- Data Modal -->
<div id="dataModal" style="display:none;">
	<div class="grayOut"></div>
	<div class="redCloseBox" onclick="exper_doCancelDataEdit();">X</div>
	<div class="modalBox" id="dataModalBox">

	</div>
</div>

<!-- Source Data -->
<div style="display:none;">
	<div id="sourceDataModal">
		<div class="topTitle" style="padding-top:10px;">Experimental Data</div>
		<div>

			<fieldset class="mainFS" style="margin-top:10px">
				<legend class="subFSLegend">Datasets <button id="addChannelButton" class="fsButton" style="vertical-align:middle" onclick="exper_addDataset();"><span>Add Dataset </span></button></legend>
				<div>
					<div class="subDataHolder" style="padding-left:0px !important;">
						<!-- Datasets Here -->
						<div style="float:left;">
							<div id="data_dataset_buttons" style="padding-top:5px;">
								<!-- buttons here -->
							</div>
						</div>
						<div style="float:left;padding-left:5px;">
							<div id="data_datasets" style="width:1000px;">
								<!-- each dataset in here -->
							</div>
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>

			<div style="text-align:center;">
				<button class="submitButton" style="vertical-align:middle;margin-right:150px;" onclick="exper_doCancelDataEdit()"><span>Cancel </span></button>
				<button class="submitButton" style="vertical-align:middle" onclick="exper_doSaveDataInfo()"><span>Save </span></button>
			</div>
		</div>
	</div>
</div>

<!-- Source Dataset Row -->
<div id="sourceDatasetRow" class="deviceRow data_dataset_group" id="data_dataset_0" style="margin-top: 5px; width: 1000px !important; display: block;display:none;">
	<div class="formCell" style="width:900px;padding-left:0px !important;">
		<div class="formRow">
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="dataFormat">Data<span class="reqStar">*</span></label>
					<select class="formControl formSelect" id="dataData" name="dataData" onchange="exper_dataRenameDatasetButton(0);">
						<option value="">Select...</option>
						<option value="Parameters">Parameters</option>
						<option value="Time Series">Time Series</option>
						<option value="Sample Description">Sample Description</option>
						<option value="Pore Fluid">Pore Fluid</option>
						<option value="Imaging">Imaging</option>
						<option value="EBSD">EBSD</option>
						<option value="WDS">WDS</option>
						<option value="Thin Section">Thin Section</option>
						<option value="Raman">Raman</option>
						<option value="Infrared Spectroscopy">Infrared Spectroscopy</option>
						<option value="TEM">TEM</option>
						<option value="CL">CL</option>
						<option value="Profilometry">Profilometry</option>
						<option value="Acid Etching">Acid Etching</option>
						<option value="Evaporative Grid">Evaporative Grid</option>
						<option value="Neutron Diffraction">Neutron Diffraction</option>
						<option value="X-Ray Spectra">X-Ray Spectra</option>
						<option value="X-Ray Graphs">X-Ray Graphs</option>
						<option value="Thermal Etching">Thermal Etching</option>
						<option value="Fiducal Marks">Fiducal Marks</option>
						<option value="XRCT">XRCT</option>
						<option value="CT Scan">CT Scan</option>
						<option value="SEM">SEM</option>
						<option value="EDS">EDS</option>
						<option value="EDX">EDX</option>
						<option value="Optical Microscopy">Optical Microscopy</option>
						<option value="Infrared">Infrared</option>
						<option value="Raman">Raman</option>
						<option value="XRD">XRD</option>
						<option value="XRF">XRF</option>
						<option value="Confocal Microscopy">Confocal Microscopy</option>
						<option value="Photoelasticity">Photoelasticity</option>
						<option value="Polarized Microscopy">Polarized Microscopy</option>
						<option value="Fluorescence">Fluorescence</option>
					</select>
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="dataType">Data Type <span class="reqStar">*</span></label>


					<div id ="dataTypeHolder">
						<select class="formControl formSelect" id="dataType" name="dataType">
							<option value="">Select...</option>
							<option value="Picture">Picture</option>
							<option value="Video">Video</option>
							<option value="Data">Data</option>
							<option value="Software">Software</option>
							<option value="Other">Other</option>
						</select>
					</div>

					<div id ="otherDataTypeHolder" style="white-space:nowrap;display:none;padding-top:5px;">
						<input id="otherDataType" class="formControl" type="text" value="" placeholder="enter other data type here...">
					</div>


				</div>
			</div>
			<div class="formCell w50">
				<div class="formPart" id="fileHolder">
					<label class="formLabel tooltipper" exp-code="dataFile">Choose File <span class="reqStar">*</span></label>
					<input type="file" id="dataFile" class="formControl">
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w50">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="dataId">Data ID</label>
					<input id="dataId" class="formControl" type="text" value="">
					<input id="uuid" type="hidden" value="">
					<input id="originalFilename" type="hidden" value="">
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="dataFormat">File Format</label>
					<select class="formControl formSelect" id="dataFormat" name="dataFormat">
						<option value="">Select...</option>
						<option value="text">text</option>
						<option value="csv">csv</option>
						<option value="zip">zip</option>
						<option value="rar">rar</option>
						<option value="Other">Other</option>
					</select>

					<div id ="otherDataFormatHolder" style="white-space:nowrap;display:none;padding-top:5px;">
						<input id="otherDataFormat" class="formControl" type="text" value="" placeholder="enter other data format here...">
					</div>

				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="dataQuality">Data Quality</label>
					<select class="formControl formSelect" id="dataQuality" name="dataQuality">
						<option value="">Select...</option>
						<option value="Low">Low</option>
						<option value="Acceptable">Acceptable</option>
						<option value="Good">Good</option>
						<option value="Very Good">Very Good</option>
						<option value="Exceptional">Exceptional</option>
					</select>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="dataDescription">Description</label>
					<textarea class="formControl docDescText" data-schemaformat="markdown" id="dataDescription"></textarea>
				</div>
			</div>
		</div>

		<!--Extra Data Here-->
		<div id="extraData"></div>
	</div>
	<div class="docCell" style="width:25px;">
		<div style="padding-top:10px;"><button id="deleteButton" class="squareButton squareButtonBottom" onclick="exper_deleteDAQDeviceDocument(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
		<div><button id="upButton" class="squareButton squareButtonBottom" onclick="exper_moveDAQDeviceDocumentUp(0, 0);" ><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
		<div><button id="downButton" class="squareButton squareButtonBottom" onclick="exper_moveDAQDeviceDocumentDown(0, 0);" ><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
	</div>
	<div style="clear:both;"></div>
</div>

<!-- Source Data Parameters Box -->
<div id = "sourceDataParametersBox" style="display:none;">
	<div class="formRow" style="width: 870px !important;">

		<!-- Parameters -->
		<fieldset class="subFS" style="margin-top:10px">
			<legend class="subFSLegend tooltipper" exp-code="dataParameterList">Parameter List <button id="addParameterButton" class="fsButton" style="vertical-align:middle" onclick="exper_addDataParameter(0);"><span>Add Parameter </span></button></legend>
			<div id="parameterRowsHolder" style="padding-left: 5px; display: none;">

				<div class="frontBoxRow" style="font-weight:bold;">
					<div class="dataLeft w25">Data</div>
					<div class="dataLeft w10">Value</div>
					<div class="dataLeft w10">Error</div>
					<div class="dataLeft w10">Unit</div>
					<div class="dataLeft w10">Prefix</div>
					<div class="dataLeft w16">Note</div>
					<div class="dataLeft w16">&nbsp;</div>
				</div>

				<div id="parameterRows">
					<!--Each Parameter Here-->

				</div>

			</div>

		</fieldset>
		<div class="fsSpacer"></div>


	</div>
</div>

<!-- Source Data Parameter Row -->
<div id="sourceDataParameterRow" style="display:none;">
	<div class="frontBoxRow" style="font-weight:normal;">
		<div class="dataLeft w25">
			<select class="formControl formSelect" id="parameterControl">
				<option value="Weight">Weight</option>
				<option value="Connected Porosity">Connected Porosity</option>
				<option value="Unconnected Porosity">Unconnected Porosity</option>
				<option value="Length">Length</option>
				<option value="Diameter">Diameter</option>
				<option value="Width">Width</option>
				<option value="Span">Span</option>
				<option value="Height">Height</option>
				<option value="Bore Diameter">Bore Diameter</option>
				<option value="Fault Angle">Fault Angle</option>
				<option value="Total Porosity">Total Porosity</option>
				<option value="Density">Density</option>
				<option value="Gas Permeability">Gas Permeability</option>
				<option value="Fluid Permeability">Fluid Permeability</option>
				<option value="Final Strain ε">Final Strain ε</option>
				<option value="Corrected Strain Rate ε/dt">Corrected Strain Rate ε/dt</option>
				<option value="Final Displacement Δs">Final Displacement Δs</option>
				<option value="Maximum Force F">Maximum Force F</option>
				<option value="Maximum Stress σ">Maximum Stress σ</option>
				<option value="Yield Stress σ">Yield Stress σ</option>
				<option value="Machine Stiffness N/mm">Machine Stiffness N/mm</option>
				<option value="Roughness">Roughness</option>
				<option value="Friction Parameter">Friction Parameter</option>
				<option value="Unconfined Compressive Strength (UCS)">Unconfined Compressive Strength (UCS)</option>
				<option value="Ultimate Tensile Strength">Ultimate Tensile Strength</option>
				<option value="Ultimate Shear Strength">Ultimate Shear Strength</option>
				<option value="True Tension Strength">True Tension Strength</option>
				<option value="Compressive Strength (σ2=σ3)">Compressive Strength (σ2=σ3)</option>
				<option value="Compressive Strength (σ1=σ2)">Compressive Strength (σ1=σ2)</option>
				<option value="True Triaxial Strength">True Triaxial Strength</option>
				<option value="Yield Strength">Yield Strength</option>
				<option value="Tensional Strength">Tensional Strength</option>
				<option value="Torsion Strength">Torsion Strength</option>
				<option value="Flow Strength">Flow Strength</option>
				<option value="Fracture Strength">Fracture Strength</option>
			</select>
		</div>
		<div class="dataLeft w10">
			<input id="parameterValue" class="formControl" type="text" value="">
		</div>
		<div class="dataLeft w10">
			<input id="parameterError" class="formControl" type="text" value="">
		</div>
		<div class="dataLeft w10">
			<select class="formControl formSelect" id="parameterUnit">
				<option value="degC">degC</option>
				<option value="degK">degK</option>
				<option value="sec">sec</option>
				<option value="min">min</option>
				<option value="hour">hour</option>
				<option value="Volt">Volt</option>
				<option value="mV">mV</option>
				<option value="Amperage">Amperage</option>
				<option value="mA">mA</option>
				<option value="Ohm">Ohm</option>
				<option value="Pa">Pa</option>
				<option value="MPa">MPa</option>
				<option value="GPa">GPa</option>
				<option value="bar">bar</option>
				<option value="kbar">kbar</option>
				<option value="N">N</option>
				<option value="kN">kN</option>
				<option value="g">g</option>
				<option value="mg">mg</option>
				<option value="μg">μg</option>
				<option value="m">m</option>
				<option value="cm">cm</option>
				<option value="mm">mm</option>
				<option value="μm">μm</option>
				<option value="Hz">Hz</option>
				<option value="kHz">kHz</option>
				<option value="MHz">MHz</option>
				<option value="Pa·s">Pa·s</option>
				<option value="Darcy">Darcy</option>
				<option value="mDarcy">mDarcy</option>
				<option value="m-1">m-1</option>
				<option value="m2">m2</option>
				<option value="milistrain">milistrain</option>
				<option value="mm·sec-1">mm·sec-1</option>
				<option value="N·sec-1">N·sec-1</option>
				<option value="sec-1">sec-1</option>
				<option value="kN·mm-1">kN·mm-1</option>
				<option value="%">%</option>
				<option value="count">count</option>
				<option value="cc">cc</option>
				<option value="mm3">mm3</option>
			</select>
		</div>
		<div class="dataLeft w10">
			<select class="formControl formSelect" id="parameterPrefix">
				<option value="1E+1">1E+1</option>
				<option value="1E+2">1E+2</option>
				<option value="1E+3">1E+3</option>
				<option value="1E+4">1E+4</option>
				<option value="1E+5">1E+5</option>
				<option value="1E+6">1E+6</option>
				<option value="-" selected>-</option>
				<option value="1E-1">1E-1</option>
				<option value="1E-2">1E-2</option>
				<option value="1E-3">1E-3</option>
				<option value="1E-4">1E-4</option>
				<option value="1E-5">1E-5</option>
				<option value="1E-6">1E-6</option>
			</select>
		</div>
		<div class="dataLeft w16">
			<input id="parameterNote" class="formControl" type="text" value="">
		</div>
		<div class="dataLeft w16">
			<button id="deleteParameterButton" class="squareButton squareButtonBottom" onclick="exper_deleteDataParameter(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
			<button id="upParameterButton" class="squareButton squareButtonBottom" onclick="exper_moveDataParameterUp(0, 0);"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
			<button id="downParameterButton" class="squareButton squareButtonBottom" onclick="exper_moveDataParameterDown(0, 0);"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
		</div>
	</div>
</div>

<!-- Source Data Phases Box -->
<div id = "sourceDataPhasesBox" style="display:none;">
	<div class="formRow" style="width: 870px !important;">
		<!-- Pore Fluid Phases -->
		<fieldset class="subFS" style="margin-top:10px">
			<legend class="subFSLegend">Pore Fluid Phases <button id="addPhaseButton" class="fsButton" style="vertical-align:middle" onclick="exper_addDatasetPhase(0);"><span>Add Phase </span></button></legend>
			<div>
				<div class="subDataHolder" style="padding-left:0px !important;">
					<!-- Phases Here -->
					<div style="float:left;">
						<div id="phase_buttons" style="padding-top:5px;">
							<!-- Phase Buttons Here -->
						</div>
					</div>
					<div style="float:left;padding-left:5px;">
						<div id="phases">
							<!-- Each Phase Here -->
						</div>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</fieldset>
		<div class="fsSpacer"></div>
	</div>
</div>

<!-- Source Data Phase Row -->
<div id="soureDataPhaseRow" style="margin-top: 5px; width: 720px !important; display: none;">
	<div class="formCell" style="width:660px;padding-left:0px !important;">
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="phaseComposition">Component</label>
					<input id="phaseComposition" class="formControl" type="text" value="">
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="phaseFraction">Fraction</label>
					<input id="phaseFraction" class="formControl" type="text" value="">
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="phaseActivity">Activity</label>
					<input id="phaseActivity" class="formControl" type="text" value="">
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="phaseFugacity">Fugacity</label>
					<input id="phaseFugacity" class="formControl" type="text" value="">
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="phaseUnit">Unit</label>
					<select class="formControl formSelect" id="phaseUnit">
						<option value="Vol%">Vol%</option>
						<option value="Mol%">Mol%</option>
						<option value="Wt%">Wt%</option>
						<option value="MPa">MPa</option>
					</select>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="phaseChemistryData">Chemistry Data</label>
					<select class="formControl formSelect" id="phaseChemistryData">
						<option value="Chemistry">Chemistry</option>
						<option value="None">None</option>
					</select>
				</div>
			</div>
		</div>
		<div id="solutesHolder" style="padding-left:10px;">
		</div>
	</div>
	<div class="docCell" style="width:25px;">
		<div style="padding-top:10px;"><button id="deletePhaseButton" class="squareButton squareButtonBottom" onclick="exper_deleteDatasetPhase(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
		<div><button id="upPhaseButton" class="squareButton squareButtonBottom" onclick="exper_moveDatasetPhaseUp(0, 0);"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
		<div><button id="downPhaseButton" class="squareButton squareButtonBottom" onclick="exper_moveDatasetPhaseDown(0, 0);"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
	</div>
	<div style="clear:both;"></div>
</div>

<!-- Source Data Solutes Box -->
<div id = "sourceDataSolutesBox" style="display:none;">
	<div class="formRow" style="width: 630px !important;">

		<!-- Solutes -->
		<fieldset class="subFS" style="margin-top:10px">
			<legend class="subFSLegend">Chemistry <button id="addSoluteButton" class="fsButton" style="vertical-align:middle" onclick="exper_addDataSolute(0,0);"><span>Add Solute </span></button></legend>
			<div id="soluteRowsHolder" style="padding-left: 5px; display: none;">

				<div class="frontBoxRow" style="font-weight:bold;">
					<div class="dataLeft w20">Component</div>
					<div class="dataLeft w20">Value</div>
					<div class="dataLeft w20">Error</div>
					<div class="dataLeft w20">Unit</div>
					<div class="dataLeft w20">&nbsp;</div>
				</div>

				<div id="soluteRows">
					<!--Each Solute Here-->
				</div>

			</div>

		</fieldset>
		<div class="fsSpacer"></div>

	</div>
</div>

<!-- Source Data Solute Row -->
<div id="sourceDataSoluteRow" style="display:none;">
	<div class="frontBoxRow" style="font-weight:normal;">
		<div class="dataLeft w20">
			<select class="formControl formSelect" id="soluteComponent">
				<option value="pH">pH</option>
				<option value="pOH">pOH</option>
				<option value="Na+">Na+</option>
				<option value="K+">K+</option>
				<option value="Ca++">Ca++</option>
				<option value="Mg++">Mg++</option>
				<option value="Sr++">Sr++</option>
				<option value="HCO3-">HCO3-</option>
				<option value="TOC">TOC</option>
				<option value="TIC">TIC</option>
				<option value="CO2(gas)">CO2(gas)</option>
				<option value="CO2(sol)">CO2(sol)</option>
				<option value="Resitivity">Resitivity</option>
				<option value="Temperature">Temperature</option>
			</select>
		</div>
		<div class="dataLeft w20">
			<input id="soluteValue" class="formControl" type="text" value="">
		</div>
		<div class="dataLeft w20">
			<input id="soluteError" class="formControl" type="text" value="">
		</div>
		<div class="dataLeft w20">
			<select class="formControl formSelect" id="soluteUnit">
				<option value="Vol%">Vol%</option>
				<option value="Mol%">Mol%</option>
				<option value="Wt%">Wt%</option>
				<option value="Mol/L">Mol/L</option>
				<option value="mMol/L">mMol/L</option>
				<option value="S/Mol">S/Mol</option>
				<option value="log [C]">log [C]</option>
				<option value="deg C">deg C</option>
			</select>
		</div>
		<div class="dataLeft w20">
			<button id="deleteSoluteButton" class="squareButton squareButtonBottom" onclick="exper_deleteDataParameter(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
			<button id="upSoluteButton" class="squareButton squareButtonBottom" onclick="exper_moveDataParameterUp(0, 0);"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
			<button id="downSoluteButton" class="squareButton squareButtonBottom" onclick="exper_moveDataParameterDown(0, 0);"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
		</div>
	</div>
</div>

<!-- Source Data Headers Box -->
<div id = "sourceDataHeadersBox" style="display:none;">
	<div class="formRow" style="width: 870px !important;">
		<!-- Time Series Headers -->
		<fieldset class="subFS" style="margin-top:10px">
			<legend class="subFSLegend">Data Headers <button id="addHeaderButton" class="fsButton" style="vertical-align:middle" onclick="exper_addDatasetHeader(0);"><span>Add Header </span></button></legend>
			<div>
				<div class="subDataHolder" style="padding-left:0px !important;">
					<!-- Phases Here -->
					<div style="float:left;">
						<div id="header_buttons" style="padding-top:5px;">
							<!-- Header Buttons Here -->
							</div>
					</div>
					<div style="float:left;padding-left:5px;">
						<div id="headers">
							<!-- Each Header Here -->
						</div>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</fieldset>
		<div class="fsSpacer"></div>
	</div>
</div>

<!-- Source Data Header Row -->
<div id="sourceDataHeaderRow" style="margin-top: 5px; width: 720px !important; display:none;">
	<div class="formCell" style="width:660px;padding-left:0px !important;">
		<div class="formRow">
			<div class="formCell w25" style="padding-top:10px;padding-bottom:10px;">
				<span class="formLabel tooltipper" exp-code="headerHeader">Header</span>
				<select class="formControl formSelect" id="headerHeader" onchange="exper_updateDataHeaderInputs(0, 0);">
					<option value="Time">Time</option>
					<option value="Temperature">Temperature</option>
					<option value="Pressure">Pressure</option>
					<option value="Strain">Strain</option>
					<option value="Displacement">Displacement</option>
					<option value="Stress">Stress</option>
					<option value="Load">Load</option>
					<option value="Electrical">Electrical</option>
					<option value="Chemistry">Chemistry</option>
					<option value="Other">Other</option>
				</select>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="headerSpecA">Specifier A</label>
					<div id="specAHolder">
						<input id="headerSpecA" class="formControl" type="text" value="">
					</div>
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="headerSpecB">Specifier B</label>
					<div id="specBHolder">
						<input id="headerSpecB" class="formControl" type="text" value="">
					</div>
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="headerSpecC">Other Specifier</label>
					<input id="headerSpecC" class="formControl" type="text" value="">
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="headerUnit">Unit</label>
					<div id="unitHolder">
						<select class="formControl formSelect" id="headerUnit">
							<option value="Vol%">Vol%</option>
							<option value="Mol%">Mol%</option>
							<option value="Wt%">Wt%</option>
							<option value="MPa">MPa</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div class="formRow">

			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="headerType">Type</label>
					<select class="formControl formSelect" id="headerType">
						<option value=""></option>
						<option value="Analog Input">Analog Input</option>
						<option value="Analog Output">Analog Output</option>
						<option value="Digital Input">Digital Input</option>
						<option value="Digital Output">Digital Output</option>
						<option value="System Data">System Data</option>
						<option value="System Clock">System Clock</option>
						<option value="Calculated">Calculated</option>
					</select>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="headerChannelNum">Channel #</label>
					<select class="formControl formSelect" id="headerChannelNum">
						<option value="0">0</option>
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						<option value="16">16</option>
						<option value="17">17</option>
						<option value="18">18</option>
						<option value="19">19</option>
						<option value="20">20</option>
						<option value="21">21</option>
						<option value="22">22</option>
						<option value="23">23</option>
						<option value="24">24</option>
						<option value="25">25</option>
						<option value="26">26</option>
						<option value="27">27</option>
						<option value="28">28</option>
						<option value="29">29</option>
						<option value="30">30</option>
						<option value="31">31</option>
						<option value="32">32</option>
					</select>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="headerDataQuality">Data Quality</label>
					<select class="formControl formSelect" id="headerDataQuality">
						<option value="Low">Low</option>
						<option value="Acceptable">Acceptable</option>
						<option value="Good">Good</option>
						<option value="Very Good">Very Good</option>
						<option value="Exceptional">Exceptional</option>
					</select>
				</div>
			</div>
		</div>
		<div class="formRow">

			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="headerNote">Notes</label>
					<textarea class="formControl" data-schemaformat="markdown" id="headerNote"></textarea>
				</div>
			</div>
		</div>
	</div>
	<div class="docCell" style="width:25px;">
		<div style="padding-top:10px;"><button id="deleteHeaderButton" class="squareButton squareButtonBottom" onclick="exper_deleteDatasetHeader(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
		<div><button id="upHeaderButton" class="squareButton squareButtonBottom" onclick="exper_moveDatasetHeaderUp(0, 0);"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
		<div><button id="downHeaderButton" class="squareButton squareButtonBottom" onclick="exper_moveDatasetHeaderDown(0, 0);"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
	</div>
	<div style="clear:both;"></div>
</div>

<!-- Main Page -->
<div id="bigWindow">

	<div style="padding-left:415px; padding-top:20px;">
		<div class="formRow">
			<div class="formCell w50">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="experimentId">Experiment ID<span class="reqStar">*</span></label>
					<input id="mainExperimentId" class="formControl experimentId" type="text" value="" ></input>
				</div>
			</div>
		</div>
	</div>

	<!--<div style="padding-top:25px;"></div>-->




	<div style="clear:both;text-align:center;">
		<button class="metadataButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal('all');"><span>Load All Data from Previous Experiment </span></button>
		<button class="sampleMetadataButton" style="vertical-align:middle" onclick="exper_loadExampleDataFromJSON('all');"><span>New User?<br>Load Example Data </span></button>
		<button class="metadataButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON('all');"><span>Load All Data from JSON File </span></button>
	</div>

	<div  style="clear:both;">&nbsp;</div>

	<fieldset class="mainFS" id="apparatusInfoFS">
		<legend class="mainFSLegend">Facility and Apparatus Info</legend>
		<div id="apparatusInfo">
			<div class="formRow">
				<span class="enterDataSpan">Enter Data: </span>
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditFacilityApparatus()"><span>Manually </span></button>
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal('facilityApparatus');"><span>from Previous Experiment  </span></button>
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON('facilityApparatus')"><span>From JSON File </span></button>
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openApparatusModal()"><span>From Apparatus Repository </span></button>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>

	<fieldset class="mainFS" id="daqInfoFS">
		<legend class="mainFSLegend">DAQ Info</legend>
		<div id="daqInfo">
			<div class="formRow">
				<span class="enterDataSpan">Enter Data: </span>
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditDAQ()"><span>Manually </span></button>
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal('daq');"><span>From Previous Experiment  </span></button>
				<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON('daq')"><span>From JSON File </span></button>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>

	<fieldset class="mainFS" id="sampleInfoFS">
		<legend class="mainFSLegend">Sample Info</legend>
		<div class="formRow" id="sampleInfo">
			<span class="enterDataSpan">Enter Data: </span>
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditSample()"><span>Manually </span></button>
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal('sample');"><span>From Previous Experiment  </span></button>
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON('sample')"><span>From JSON File </span></button>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>

	<fieldset class="mainFS" id="experimentalSetupInfoFS">
		<legend class="mainFSLegend">Experimental Setup Info</legend>
		<div class="formRow" id="experimentalSetupInfo">
			<span class="enterDataSpan">Enter Data: </span>
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditExperimentSetup()"><span>Manually </span></button>
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal('experiment');"><span>From Previous Experiment  </span></button>
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON('experiment')"><span>From JSON File </span></button>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>

	<fieldset class="mainFS" id="dataInfoFS">
		<legend class="mainFSLegend">Data</legend>
		<div class="formRow" id="dataInfo">
			<span class="enterDataSpan">Enter Data: </span>
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_doEditData()"><span>Manually </span></button>
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_openChooseExperimentModal('data');"><span>From Previous Experiment  </span></button>
			<button class="expSectionButton" style="vertical-align:middle" onclick="exper_loadDataFromJSON('data')"><span>From JSON File </span></button>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>

	<div class="fsSpacer"></div>
	<div style="text-align:center;">
		<button class="submitButton" style="vertical-align:middle" onclick="window.location.href = '/my_experimental_data';"><span>Cancel </span></button>
		<button class="submitButton" style="vertical-align:middle;margin-left:150px;" onclick="doSubmitNewExperiment()"><span>Save </span></button>
	</div>












	<!--

	doSubmitNewExperiment doDebug

	<div style="padding-top:400px;"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Project Info</legend>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="projectName">Project Name <span class="reqStar">*</span></label>
					<input id="projectName" class="formControl" type="text" value=""></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel tooltipper" exp-code="projectDescription">Description</span></label>
					<textarea class="formControl docDescText" data-schemaformat="markdown" id="projectDescription"></textarea>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
	<div style="text-align:center;"><button class="submitButton" style="vertical-align:middle" onclick="doSubmitNewProject()"><span>Submit </span></button></div>
	-->
</div>

<script>
exper_clearSampleData();
exper_clearExperimentSetupData();
exper_clearDataData();
exper_fixButtonsAndDivs();
setupExperimentalIdInputs();

//exper_openDataModal();
//exper_addDataset();
//loadFilledTestFile();
</script>



<?php
include("../includes/footer.php");
?>