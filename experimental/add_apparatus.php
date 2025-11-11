<?php
/**
 * File: add_apparatus.php
 * Description: Creates new apparatus records
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../prepare_connections.php");

$fpkey = $_GET['fpk'];

$credentials = $_SESSION['credentials'];

if(!in_array($userpkey, $admin_pkeys)){
	$is_admin = false;
}else{
	$is_admin = true;
}

//Check to be sure we are authorized to edit this facility
if($is_admin){
	$row = $db->get_row("select * from apprepo.facility where pkey = $fpkey");
}else{
	$row = $db->get_row("select * from apprepo.facility where pkey = $fpkey and pkey in (select facility_pkey from apprepo.facility_users where users_pkey = $userpkey)");
}

if($row->pkey == ""){
	echo "facility not found.";exit();
}

include("../includes/header.php");
?>
<link rel="stylesheet" href="/experimental/experimental.css" type="text/css" />
<script src='/experimental/experimental.js'></script>
<script src='/assets/js/jquery/jquery.min.js'></script>

<div id="progressBox">
	<div id="grayOut"></div>
	<div id="uploadingMessage">
		<div><image src="/assets/js/images/box.gif"> Uploading. Please wait...</div>
		<div id="progressDigit">0%</div>
		<div id="progressBar"></div>
	</div>
</div>

<div class="topTitle">Add Apparatus</div>
<div id="bigWindow">
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Apparatus Info</legend>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Apparatus Name <span class="reqStar">*</span></label>
					<input id="apparatusName" class="formControl" type="text" value=""></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Apparatus Type <span class="reqStar">*</span></label>
					<select class="formControl formSelect" name="apparatusType" id="apparatusType" onchange="exper_handleApparatusTypeChange();">
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
						<input id="otherApparatusType" class="formControl" type="text" value="<?php echo $row->other_type?>" placeholder="other aparatus type...">
					</div>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Location</label>
					<input id="apparatusLocation" class="formControl" type="text" value=""></input>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Apparatus ID</label>
					<input id="apparatusId" class="formControl" type="text" value=""></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel">Description</label>
					<textarea class="formControl" data-schemaformat="markdown" id="apparatusDescription"></textarea>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Apparatus Features</legend>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_loading" checked>&nbsp;Loading</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_unloading" checked>&nbsp;Unloading</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_heating">&nbsp;Heating</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_cooling">&nbsp;Cooling</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_high_temperature">&nbsp;High Temperature</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_ultra-high_temperature">&nbsp;Ultra-High Temperature</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_low_temperature">&nbsp;Low Temperature</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_sub-zero_temperature">&nbsp;Sub-Zero Temperature</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_high_pressure">&nbsp;High Pressure</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_ultra-high_pressure">&nbsp;Ultra-High Pressure</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hydrostatic_tests">&nbsp;Hydrostatic Tests</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hip">&nbsp;HIP</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_synthesis">&nbsp;Synthesis</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_deposition_evaporation">&nbsp;Deposition/Evaporation</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_mineral_reactions">&nbsp;Mineral Reactions</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hydrothermal_reactions">&nbsp;Hydrothermal Reactions</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_elasticity">&nbsp;Elasticity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_local_axial_strain">&nbsp;Local Axial Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_local_radial_strain">&nbsp;Local Radial Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_elastic_moduli">&nbsp;Elastic Moduli</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_yield_strength">&nbsp;Yield Strength</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_failure_strength">&nbsp;Failure Strength</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_strength">&nbsp;Strength</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_extension">&nbsp;Extension</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_creep">&nbsp;Creep</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_friction">&nbsp;Friction</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_frictional_sliding">&nbsp;Frictional Sliding</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_slide_hold_slide">&nbsp;Slide Hold Slide</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_stepping">&nbsp;Stepping</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_pure_shear">&nbsp;Pure Shear</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_simple_shear">&nbsp;Simple Shear</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_rotary_shear">&nbsp;Rotary Shear</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_torsion">&nbsp;Torsion</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_viscosity">&nbsp;Viscosity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_indentation">&nbsp;Indentation</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hardness">&nbsp;Hardness</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_dynamic_tests">&nbsp;Dynamic Tests</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hydraulic_fracturing">&nbsp;Hydraulic Fracturing</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hydrothermal_fracturing">&nbsp;Hydrothermal Fracturing</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_shockwave">&nbsp;Shockwave</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_reactive_flow">&nbsp;Reactive Flow</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_pore_fluid_control">&nbsp;Pore Fluid Control</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_pore_fluid_chemistry">&nbsp;Pore Fluid Chemistry</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_pore_volume_compaction">&nbsp;Pore Volume Compaction</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_storage_capacity">&nbsp;Storage Capacity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_permeability">&nbsp;Permeability</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_steady-state_permeability">&nbsp;Steady-State Permeability</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_transient_permeability">&nbsp;Transient Permeability</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hydraulic_conductivity">&nbsp;Hydraulic Conductivity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_drained_undrained_pore_fluid">&nbsp;Drained/Undrained Pore Fluid</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_uniaxial_stress_strain">&nbsp;Uniaxial Stress/Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_biaxial_stress_strain">&nbsp;Biaxial Stress/Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_triaxial_stress_strain">&nbsp;Triaxial Stress/Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_differential_stress">&nbsp;Differential Stress</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_true_triaxial">&nbsp;True Triaxial</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_resistivity">&nbsp;Resistivity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_electrical_resistivity">&nbsp;Electrical Resistivity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_electrical_capacitance">&nbsp;Electrical Capacitance</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_streaming_potential">&nbsp;Streaming Potential</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_acoustic_velocity">&nbsp;Acoustic Velocity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_acoustic_events">&nbsp;Acoustic Events</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_p-wave_velocity">&nbsp;P-Wave Velocity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_s-wave_velocity">&nbsp;S-Wave Velocity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_source_location">&nbsp;Source Location</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_tomography">&nbsp;Tomography</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_in-situ_x-ray">&nbsp;In-Situ X-Ray</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_infrared">&nbsp;Infrared</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_raman">&nbsp;Raman</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_visual">&nbsp;Visual</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_other">&nbsp;Other</label></div>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Apparatus Parameters</legend>
		<div class="subDataHolder" id="paramsSubDiv">
			<table class="subDataTable" id="paramsTable" style="display:none;">
				<tr data-isHeader=true>
					<th>Name</th>
					<th>Minimum</th>
					<th>Maximum</th>
					<th>Unit</th>
					<th>Prefix</th>
					<th>Detail/Note</th>
					<th>&nbsp;</th>
				</tr>
			</table>
		</div>
		<div style="padding-left:5px;"><button class="smallerButton" style="vertical-align:middle" onclick="addApparatusParamRow();"><span>Add Parameter </span></button></div>
	</fieldset>
	<div class="fsSpacer"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Apparatus Documents</legend>
		<div id="docsWrapper">
			<div class="subDataHolder" id="documentRows"></div>
		</div>
		<div style="padding-left:5px;"><button class="smallerButton" style="vertical-align:middle" onclick="addApparatusDocument();"><span>Add Document </span></button></div>
	</fieldset>
	<div class="fsSpacer"></div>
	<div style="text-align:center;">
		<button class="submitButton" style="vertical-align:middle;margin-right:150px;" onclick="history.back();"><span>Cancel </span></button>
		<button class="submitButton" style="vertical-align:middle" onclick="doSubmitNewApparatus()"><span>Submit </span></button>
	</div>


	<input type="hidden" id="facilityPkey" value="<?php echo $fpkey?>">

	<div style="display:none;">
		<div class="docRow" id="sourceDocumentRow">
			<div class="docCell" style="width:1160px;">
				<div class="formRow">
					<div class="formCell 16">
						<div class="formPart">
							<label class="formLabel">Document Type <span class="reqStar">*</span></label>
							<select class="formControl formSelect" name="docType" id="docType">
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
							<label class="formLabel">Document Format <span class="reqStar">*</span></label>
							<select class="formControl formSelect" name="docFormat" id="docFormat">
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
						<div class="formPart">
							<label class="formLabel">Choose File <span class="reqStar">*</span></label>
							<input type="file" class="formControl"/>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">Document ID</label>
							<input id="DocumentId" class="formControl" type="text" value=""></input>
							<input id="uuid" type="hidden" value=""></input>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w100">
						<div class="formPart">
							<label class="formLabel">Description</label>
							<textarea class="formControl docDescText" data-schemaformat="markdown" id="docDescription"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="docCell" style="width:25px;">
				<div style="padding-top:10px;"><button class="squareButton squareButtonBottom"><image src="/experimental/buttonImages/icons8-trash-30.png" height="20" width="20" /></button></div>
				<div><button class="squareButton squareButtonBottom"><image src="/experimental/buttonImages/icons8-up-30.png" height="20" width="20" /></button></div>
				<div><button class="squareButton squareButtonBottom"><image src="/experimental/buttonImages/icons8-down-30.png" height="20" width="20" /></button></div>
			</div>
			<div class="clearLeft"></div>
		</div>
	</div>

	<div style="display:none;">
		<div class="docRow" id="sourceApparatusDocumentRow">
			<div class="appDocCell" style="width:1140px;">
				<div class="formRow">
					<div class="formCell 16">
						<div class="formPart">
							<label class="formLabel">Document Type <span class="reqStar">*</span></label>
							<select class="formControl formSelect" name="docType" id="docType">
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
							<label class="formLabel">Document Format <span class="reqStar">*</span></label>
							<select class="formControl formSelect" name="docFormat" id="docFormat">
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
							<label class="formLabel">Choose File <span class="reqStar">*</span></label>
							<input type="file" class="formControl">
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">Document ID</label>
							<input id="DocumentId" class="formControl" type="text" value="">
							<input id="uuid" type="hidden" value="">
							<input id="originalFilename" type="hidden" value="">
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w100">
						<div class="formPart">
							<label class="formLabel">Description</label>
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
		<tr id="sourceParamRow">
			<td>
				<select class="formControl formSelect" name="paramName">
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
			<td><input id="paramMin" class="formControl" type="text" value=""></input></td>
			<td><input id="paramMax" class="formControl" type="text" value=""></input></td>
			<td>
				<select class="formControl formSelect" name="paramUnit">
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
				<select class="formControl formSelect" name="paramPrefix">
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
			</td>
			<td><input id="paramNote" class="formControl" type="text" value=""></td>
			<td>
				<div style="white-space: nowrap;">
					<button class="squareButton"><image src="/experimental/buttonImages/icons8-trash-30.png" height="20" width="20" /></button>
					<button class="squareButton"><image src="/experimental/buttonImages/icons8-up-30.png" height="20" width="20" /></button>
					<button class="squareButton"><image src="/experimental/buttonImages/icons8-down-30.png" height="20" width="20" /></button>
				</div>
			</td>
		</tr>
	</table>

	<table id="holdingTable" style="display: none;"></table>
	<div id="holdingDiv" style="display: none;"></div>

</div>
<?php
include("../includes/footer.php");
?>