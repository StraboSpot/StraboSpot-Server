<?php
/**
 * File: edit_apparatus.php
 * Description: Apparatus editing interface
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../logincheck.php");
include("../prepare_connections.php");

$pkey = $_GET['apk'];
if($pkey=="")die();

$credentials = $_SESSION['credentials'];

if(!in_array($userpkey, $admin_pkeys)){
	$is_admin = false;
}else{
	$is_admin = true;
}

//Check to be sure we are authorized to edit this apparatus
if($is_admin){
	$row = $db->get_row("select * from apprepo.apparatus where pkey = $pkey");
}else{
	$row = $db->get_row("select * from apprepo.apparatus where pkey = $pkey and userpkey = $userpkey");
}

if($row->pkey == ""){
	echo "apparatus not found.";exit();
}

$json = $row->json;
$json = json_decode($json);


$name = $json->name;
$type = $json->type;
$location = $json->location;
$apparatus_id = $json->id;
$description = $json->description;
$features = $json->features;
$parameters = $json->parameters;
$documents = $json->documents;


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

<div class="topTitle">Edit Apparatus</div>
<div id="bigWindow">
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Apparatus Info</legend>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Apparatus Name <span class="reqStar">*</span></label>
					<input id="apparatusName" class="formControl" type="text" value="<?php echo $name?>"></input>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Apparatus Type <span class="reqStar">*</span></label>
					<select class="formControl formSelect" name="apparatusType" id="apparatusType" onchange="exper_handleApparatusTypeChange();">
						<option value="">Select...</option>
						<option value="Uniaxial"<?php if($row->type=="Uniaxial")echo " selected";?>>Uniaxial</option>
						<option value="Triaxial (conventional)"<?php if($row->type=="Triaxial (conventional)")echo " selected";?>>Triaxial (conventional)</option>
						<option value="Biaxial"<?php if($row->type=="Biaxial")echo " selected";?>>Biaxial</option>
						<option value="True Triaxial"<?php if($row->type=="True Triaxial")echo " selected";?>>True Triaxial</option>
						<option value="Direct Shear"<?php if($row->type=="Direct Shear")echo " selected";?>>Direct Shear</option>
						<option value="Shear Box"<?php if($row->type=="Shear Box")echo " selected";?>>Shear Box</option>
						<option value="Rotary Shear Friction Apparatus"<?php if($row->type=="Rotary Shear Friction Apparatus")echo " selected";?>>Rotary Shear Friction Apparatus</option>
						<option value="Creep Cell"<?php if($row->type=="Creep Cell")echo " selected";?>>Creep Cell</option>
						<option value="Indentation Cell"<?php if($row->type=="Indentation Cell")echo " selected";?>>Indentation Cell</option>
						<option value="Piston Cylinder"<?php if($row->type=="Piston Cylinder")echo " selected";?>>Piston Cylinder</option>
						<option value="Multi Anvil"<?php if($row->type=="Multi Anvil")echo " selected";?>>Multi Anvil</option>
						<option value="D-DIA"<?php if($row->type=="D-DIA")echo " selected";?>>D-DIA</option>
						<option value="Rotational Drickamer Apparatus (RDA)"<?php if($row->type=="Rotational Drickamer Apparatus (RDA)")echo " selected";?>>Rotational Drickamer Apparatus (RDA)</option>
						<option value="Large Volume Torsion Apparatus (LVT)"<?php if($row->type=="Large Volume Torsion Apparatus (LVT)")echo " selected";?>>Large Volume Torsion Apparatus (LVT)</option>
						<option value="1 Atm Gas Mixing Furnace"<?php if($row->type=="1 Atm Gas Mixing Furnace")echo " selected";?>>1 Atm Gas Mixing Furnace</option>
						<option value="Vacuum Furnace"<?php if($row->type=="Vacuum Furnace")echo " selected";?>>Vacuum Furnace</option>
						<option value="Other Gas Medium Apparatus"<?php if($row->type=="Other Gas Medium Apparatus")echo " selected";?>>Other Gas Medium Apparatus</option>
						<option value="Other Solid Medium Apparatus"<?php if($row->type=="Other Solid Medium Apparatus")echo " selected";?>>Other Solid Medium Apparatus</option>
						<option value="Other Liquid Medium Apparatus"<?php if($row->type=="Other Liquid Medium Apparatus")echo " selected";?>>Other Liquid Medium Apparatus</option>
						<option value="Vickers Hardness Tester"<?php if($row->type=="Vickers Hardness Tester")echo " selected";?>>Vickers Hardness Tester</option>
						<option value="Nanoindenter"<?php if($row->type=="Nanoindenter")echo " selected";?>>Nanoindenter</option>
						<option value="Diamond Anvil Cell"<?php if($row->type=="Diamond Anvil Cell")echo " selected";?>>Diamond Anvil Cell</option>
						<option value="Brazilian Test"<?php if($row->type=="Brazilian Test")echo " selected";?>>Brazilian Test</option>
						<option value="Paterson Apparatus"<?php if($row->type=="Paterson Apparatus")echo " selected";?>>Paterson Apparatus</option>
						<option value="Heard Apparatus"<?php if($row->type=="Heard Apparatus")echo " selected";?>>Heard Apparatus</option>
						<option value="Griggs Apparatus"<?php if($row->type=="Griggs Apparatus")echo " selected";?>>Griggs Apparatus</option>
						<option value="Schmidt Hammer"<?php if($row->type=="Schmidt Hammer")echo " selected";?>>Schmidt Hammer</option>
						<option value="Split Hopkinson Pressure Bar"<?php if($row->type=="Split Hopkinson Pressure Bar")echo " selected";?>>Split Hopkinson Pressure Bar</option>
						<option value="Double Torsion apparatus"<?php if($row->type=="Double Torsion apparatus")echo " selected";?>>Double Torsion apparatus</option>
						<option value="Point Load"<?php if($row->type=="Point Load")echo " selected";?>>Point Load</option>
						<option value="Atomic Force Microscope"<?php if($row->type=="Atomic Force Microscope")echo " selected";?>>Atomic Force Microscope</option>
						<option value="Rheometer"<?php if($row->type=="Rheometer")echo " selected";?>>Rheometer</option>
						<option value="Permeameter"<?php if($row->type=="Permeameter")echo " selected";?>>Permeameter</option>
						<option value="Pycnometer"<?php if($row->type=="Pycnometer")echo " selected";?>>Pycnometer</option>
						<option value="Viscosimeter"<?php if($row->type=="Viscosimeter")echo " selected";?>>Viscosimeter</option>
						<option value="Laser Profilometer"<?php if($row->type=="Laser Profilometer")echo " selected";?>>Laser Profilometer</option>
						<option value="Light Interferometer"<?php if($row->type=="Light Interferometer")echo " selected";?>>Light Interferometer</option>
						<option value="Load Stamp"<?php if($row->type=="Load Stamp")echo " selected";?>>Load Stamp</option>
						<option value="Chevron Notch Test"<?php if($row->type=="Chevron Notch Test")echo " selected";?>>Chevron Notch Test</option>
						<option value="Paris-Edinburgh Rig"<?php if($row->type=="Paris-Edinburgh Rig")echo " selected";?>>Paris-Edinburgh Rig</option>
						<option value="NER Rig"<?php if($row->type=="NER Rig")echo " selected";?>>NER Rig</option>
						<option value="Sanchez Rig"<?php if($row->type=="Sanchez Rig")echo " selected";?>>Sanchez Rig</option>
						<option value="Micro-Deformation Cell"<?php if($row->type=="Micro-Deformation Cell")echo " selected";?>>Micro-Deformation Cell</option>
						<option value="Ancillary Equipment"<?php if($row->type=="Ancillary Equipment")echo " selected";?>>Ancillary Equipment</option>
						<option value="Commercial Apparatus"<?php if($row->type=="Commercial Apparatus")echo " selected";?>>Commercial Apparatus</option>
						<option value="Other Apparatus"<?php if($row->type=="Other Apparatus")echo " selected";?>>Other Apparatus</option>
					</select>

					<?php
					if($row->type == "Other Apparatus"){
						$showtype = "inline";
					}else{
						$showtype = "none";
					}
					?>

					<div id ="otherApparatusTypeHolder" style="white-space:nowrap;display:<?php echo $showtype?>;padding-top:5px;">
						<input id="otherApparatusType" class="formControl" type="text" value="<?php echo $row->other_type?>" placeholder="other aparatus type...">
					</div>

				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Location</label>
					<input id="apparatusLocation" class="formControl" type="text" value="<?php echo $location?>"></input>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Apparatus ID</label>
					<input id="apparatusId" class="formControl" type="text" value="<?php echo $apparatus_id?>"></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel">Description</label>
					<textarea class="formControl" data-schemaformat="markdown" id="apparatusDescription"><?php echo $description?></textarea>
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
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_loading"<?php if(in_array("Loading",$features)) echo " checked";?>>&nbsp;Loading</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_unloading"<?php if(in_array("Unloading",$features)) echo " checked";?>>&nbsp;Unloading</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_heating"<?php if(in_array("Heating",$features)) echo " checked";?>>&nbsp;Heating</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_cooling"<?php if(in_array("Cooling",$features)) echo " checked";?>>&nbsp;Cooling</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_high_temperature"<?php if(in_array("High Temperature",$features)) echo " checked";?>>&nbsp;High Temperature</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_ultra-high_temperature"<?php if(in_array("Ultra-High Temperature",$features)) echo " checked";?>>&nbsp;Ultra-High Temperature</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_low_temperature"<?php if(in_array("Low Temperature",$features)) echo " checked";?>>&nbsp;Low Temperature</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_sub-zero_temperature"<?php if(in_array("Sub-Zero Temperature",$features)) echo " checked";?>>&nbsp;Sub-Zero Temperature</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_high_pressure"<?php if(in_array("High Pressure",$features)) echo " checked";?>>&nbsp;High Pressure</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_ultra-high_pressure"<?php if(in_array("Ultra-High Pressure",$features)) echo " checked";?>>&nbsp;Ultra-High Pressure</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hydrostatic_tests"<?php if(in_array("Hydrostatic Tests",$features)) echo " checked";?>>&nbsp;Hydrostatic Tests</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hip"<?php if(in_array("HIP",$features)) echo " checked";?>>&nbsp;HIP</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_synthesis"<?php if(in_array("Synthesis",$features)) echo " checked";?>>&nbsp;Synthesis</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_deposition_evaporation"<?php if(in_array("Deposition/Evaporation",$features)) echo " checked";?>>&nbsp;Deposition/Evaporation</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_mineral_reactions"<?php if(in_array("Mineral Reactions",$features)) echo " checked";?>>&nbsp;Mineral Reactions</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hydrothermal_reactions"<?php if(in_array("Hydrothermal Reactions",$features)) echo " checked";?>>&nbsp;Hydrothermal Reactions</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_elasticity"<?php if(in_array("Elasticity",$features)) echo " checked";?>>&nbsp;Elasticity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_local_axial_strain"<?php if(in_array("Local Axial Strain",$features)) echo " checked";?>>&nbsp;Local Axial Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_local_radial_strain"<?php if(in_array("Local Radial Strain",$features)) echo " checked";?>>&nbsp;Local Radial Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_elastic_moduli"<?php if(in_array("Elastic Moduli",$features)) echo " checked";?>>&nbsp;Elastic Moduli</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_yield_strength"<?php if(in_array("Yield Strength",$features)) echo " checked";?>>&nbsp;Yield Strength</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_failure_strength"<?php if(in_array("Failure Strength",$features)) echo " checked";?>>&nbsp;Failure Strength</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_strength"<?php if(in_array("Strength",$features)) echo " checked";?>>&nbsp;Strength</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_extension"<?php if(in_array("Extension",$features)) echo " checked";?>>&nbsp;Extension</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_creep"<?php if(in_array("Creep",$features)) echo " checked";?>>&nbsp;Creep</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_friction"<?php if(in_array("Friction",$features)) echo " checked";?>>&nbsp;Friction</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_frictional_sliding"<?php if(in_array("Frictional Sliding",$features)) echo " checked";?>>&nbsp;Frictional Sliding</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_slide_hold_slide"<?php if(in_array("Slide Hold Slide",$features)) echo " checked";?>>&nbsp;Slide Hold Slide</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_stepping"<?php if(in_array("Stepping",$features)) echo " checked";?>>&nbsp;Stepping</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_pure_shear"<?php if(in_array("Pure Shear",$features)) echo " checked";?>>&nbsp;Pure Shear</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_simple_shear"<?php if(in_array("Simple Shear",$features)) echo " checked";?>>&nbsp;Simple Shear</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_rotary_shear"<?php if(in_array("Rotary Shear",$features)) echo " checked";?>>&nbsp;Rotary Shear</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_torsion"<?php if(in_array("Torsion",$features)) echo " checked";?>>&nbsp;Torsion</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_viscosity"<?php if(in_array("Viscosity",$features)) echo " checked";?>>&nbsp;Viscosity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_indentation"<?php if(in_array("Indentation",$features)) echo " checked";?>>&nbsp;Indentation</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hardness"<?php if(in_array("Hardness",$features)) echo " checked";?>>&nbsp;Hardness</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_dynamic_tests"<?php if(in_array("Dynamic Tests",$features)) echo " checked";?>>&nbsp;Dynamic Tests</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hydraulic_fracturing"<?php if(in_array("Hydraulic Fracturing",$features)) echo " checked";?>>&nbsp;Hydraulic Fracturing</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hydrothermal_fracturing"<?php if(in_array("Hydrothermal Fracturing",$features)) echo " checked";?>>&nbsp;Hydrothermal Fracturing</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_shockwave"<?php if(in_array("Shockwave",$features)) echo " checked";?>>&nbsp;Shockwave</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_reactive_flow"<?php if(in_array("Reactive Flow",$features)) echo " checked";?>>&nbsp;Reactive Flow</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_pore_fluid_control"<?php if(in_array("Pore Fluid Control",$features)) echo " checked";?>>&nbsp;Pore Fluid Control</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_pore_fluid_chemistry"<?php if(in_array("Pore Fluid Chemistry",$features)) echo " checked";?>>&nbsp;Pore Fluid Chemistry</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_pore_volume_compaction"<?php if(in_array("Pore Volume Compaction",$features)) echo " checked";?>>&nbsp;Pore Volume Compaction</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_storage_capacity"<?php if(in_array("Storage Capacity",$features)) echo " checked";?>>&nbsp;Storage Capacity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_permeability"<?php if(in_array("Permeability",$features)) echo " checked";?>>&nbsp;Permeability</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_steady-state_permeability"<?php if(in_array("Steady-State Permeability",$features)) echo " checked";?>>&nbsp;Steady-State Permeability</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_transient_permeability"<?php if(in_array("Transient Permeability",$features)) echo " checked";?>>&nbsp;Transient Permeability</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_hydraulic_conductivity"<?php if(in_array("Hydraulic Conductivity",$features)) echo " checked";?>>&nbsp;Hydraulic Conductivity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_drained_undrained_pore_fluid"<?php if(in_array("Drained/Undrained Pore Fluid",$features)) echo " checked";?>>&nbsp;Drained/Undrained Pore Fluid</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_uniaxial_stress_strain"<?php if(in_array("Uniaxial Stress/Strain",$features)) echo " checked";?>>&nbsp;Uniaxial Stress/Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_biaxial_stress_strain"<?php if(in_array("Biaxial Stress/Strain",$features)) echo " checked";?>>&nbsp;Biaxial Stress/Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_triaxial_stress_strain"<?php if(in_array("Triaxial Stress/Strain",$features)) echo " checked";?>>&nbsp;Triaxial Stress/Strain</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_differential_stress"<?php if(in_array("Differential Stress",$features)) echo " checked";?>>&nbsp;Differential Stress</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_true_triaxial"<?php if(in_array("True Triaxial",$features)) echo " checked";?>>&nbsp;True Triaxial</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_resistivity"<?php if(in_array("Resistivity",$features)) echo " checked";?>>&nbsp;Resistivity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_electrical_resistivity"<?php if(in_array("Electrical Resistivity",$features)) echo " checked";?>>&nbsp;Electrical Resistivity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_electrical_capacitance"<?php if(in_array("Electrical Capacitance",$features)) echo " checked";?>>&nbsp;Electrical Capacitance</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_streaming_potential"<?php if(in_array("Streaming Potential",$features)) echo " checked";?>>&nbsp;Streaming Potential</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_acoustic_velocity"<?php if(in_array("Acoustic Velocity",$features)) echo " checked";?>>&nbsp;Acoustic Velocity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_acoustic_events"<?php if(in_array("Acoustic Events",$features)) echo " checked";?>>&nbsp;Acoustic Events</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_p-wave_velocity"<?php if(in_array("P-Wave Velocity",$features)) echo " checked";?>>&nbsp;P-Wave Velocity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_s-wave_velocity"<?php if(in_array("S-Wave Velocity",$features)) echo " checked";?>>&nbsp;S-Wave Velocity</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_source_location"<?php if(in_array("Source Location",$features)) echo " checked";?>>&nbsp;Source Location</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_tomography"<?php if(in_array("Tomography",$features)) echo " checked";?>>&nbsp;Tomography</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_in-situ_x-ray"<?php if(in_array("In-Situ X-Ray",$features)) echo " checked";?>>&nbsp;In-Situ X-Ray</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_infrared"<?php if(in_array("Infrared",$features)) echo " checked";?>>&nbsp;Infrared</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_raman"<?php if(in_array("Raman",$features)) echo " checked";?>>&nbsp;Raman</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_visual"<?php if(in_array("Visual",$features)) echo " checked";?>>&nbsp;Visual</label></div>
					<div class="checkbox" style="display: inline-block; margin-right: 20px;"><label><input type="checkbox" class="je-checkbox" id="f_other"<?php if(in_array("Other",$features)) echo " checked";?>>&nbsp;Other</label></div>




				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Apparatus Parameters</legend>
		<div class="subDataHolder" id="paramsSubDiv">
<?php
if($parameters != ""){
	$display = "inline";
}else{
	$display = "none";
}
/*

	[2] => stdClass Object
		(
			[type] => Confining Pressure
			[min] => 3
			[max] => 6
			[unit] => degC
			[prefix] => -
			[note] => 3
		)
*/
?>
			<table class="subDataTable" id="paramsTable" style="display:<?php echo $display?>;">
				<tr data-isHeader=true>
					<th>Name</th>
					<th>Minimum</th>
					<th>Maximum</th>
					<th>Unit</th>
					<th>Prefix</th>
					<th>Detail/Note</th>
					<th>&nbsp;</th>
				</tr>
<?php
$count = count($parameters);
for($x = 0; $x < $count; $x++){
	$param = $parameters[$x];

	$buttonNum = $x+1;


	//Set up up/down button visibility
	$up = "inline";
	$down = "inline";
	if($x == 0){
		$up = "none";
		if($count == 1) $down = "none";
	}elseif($x == $count - 1){
		$down = "none";
		if($count == 2) $up = "none";
	}

?>
	<tr>
		<td>
			<select class="formControl formSelect" name="paramName">
				<option value="Confining Pressure"<?php if($param->type=="Confining Pressure") echo " selected";?>>Confining Pressure</option>
				<option value="Effective Pressure"<?php if($param->type=="Effective Pressure") echo " selected";?>>Effective Pressure</option>
				<option value="Pore Pressure"<?php if($param->type=="Pore Pressure") echo " selected";?>>Pore Pressure</option>
				<option value="Temperature"<?php if($param->type=="Temperature") echo " selected";?>>Temperature</option>
				<option value="σ1-Displacement"<?php if($param->type=="σ1-Displacement") echo " selected";?>>σ1-Displacement</option>
				<option value="σ2-Displacement"<?php if($param->type=="σ2-Displacement") echo " selected";?>>σ2-Displacement</option>
				<option value="σ3-Displacement"<?php if($param->type=="σ3-Displacement") echo " selected";?>>σ3-Displacement</option>
				<option value="σ1-Load"<?php if($param->type=="σ1-Load") echo " selected";?>>σ1-Load</option>
				<option value="σ2-Load"<?php if($param->type=="σ2-Load") echo " selected";?>>σ2-Load</option>
				<option value="σ3-Load"<?php if($param->type=="σ3-Load") echo " selected";?>>σ3-Load</option>
				<option value="Displacement Rate"<?php if($param->type=="Displacement Rate") echo " selected";?>>Displacement Rate</option>
				<option value="Loading Rate"<?php if($param->type=="Loading Rate") echo " selected";?>>Loading Rate</option>
				<option value="Stiffness"<?php if($param->type=="Stiffness") echo " selected";?>>Stiffness</option>
				<option value="Sample Diameter"<?php if($param->type=="Sample Diameter") echo " selected";?>>Sample Diameter</option>
				<option value="Sample Length"<?php if($param->type=="Sample Length") echo " selected";?>>Sample Length</option>
				<option value="Permeability"<?php if($param->type=="Permeability") echo " selected";?>>Permeability</option>
			</select>
		</td>
		<td><input id="paramMin" class="formControl" type="text" value="<?php echo $param->min?>"></input></td>
		<td><input id="paramMax" class="formControl" type="text" value="<?php echo $param->max?>"></input></td>
		<td>
			<select class="formControl formSelect" name="paramUnit">
				<option value="degC"<?php if($param->unit=="degC") echo " selected";?>>degC</option>
				<option value="degK"<?php if($param->unit=="degK") echo " selected";?>>degK</option>
				<option value="sec"<?php if($param->unit=="sec") echo " selected";?>>sec</option>
				<option value="min"<?php if($param->unit=="min") echo " selected";?>>min</option>
				<option value="hour"<?php if($param->unit=="hour") echo " selected";?>>hour</option>
				<option value="Volt"<?php if($param->unit=="Volt") echo " selected";?>>Volt</option>
				<option value="mV"<?php if($param->unit=="mV") echo " selected";?>>mV</option>
				<option value="Amperage"<?php if($param->unit=="Amperage") echo " selected";?>>Amperage</option>
				<option value="mA"<?php if($param->unit=="mA") echo " selected";?>>mA</option>
				<option value="Ohm"<?php if($param->unit=="Ohm") echo " selected";?>>Ohm</option>
				<option value="Pa"<?php if($param->unit=="Pa") echo " selected";?>>Pa</option>
				<option value="MPa"<?php if($param->unit=="MPa") echo " selected";?>>MPa</option>
				<option value="GPa"<?php if($param->unit=="GPa") echo " selected";?>>GPa</option>
				<option value="bar"<?php if($param->unit=="bar") echo " selected";?>>bar</option>
				<option value="kbar"<?php if($param->unit=="kbar") echo " selected";?>>kbar</option>
				<option value="N"<?php if($param->unit=="N") echo " selected";?>>N</option>
				<option value="kN"<?php if($param->unit=="kN") echo " selected";?>>kN</option>
				<option value="g"<?php if($param->unit=="g") echo " selected";?>>g</option>
				<option value="mg"<?php if($param->unit=="mg") echo " selected";?>>mg</option>
				<option value="μg"<?php if($param->unit=="μg") echo " selected";?>>μg</option>
				<option value="m"<?php if($param->unit=="m") echo " selected";?>>m</option>
				<option value="cm"<?php if($param->unit=="cm") echo " selected";?>>cm</option>
				<option value="mm"<?php if($param->unit=="mm") echo " selected";?>>mm</option>
				<option value="μm"<?php if($param->unit=="μm") echo " selected";?>>μm</option>
				<option value="Hz"<?php if($param->unit=="Hz") echo " selected";?>>Hz</option>
				<option value="kHz"<?php if($param->unit=="kHz") echo " selected";?>>kHz</option>
				<option value="MHz"<?php if($param->unit=="MHz") echo " selected";?>>MHz</option>
				<option value="Pa·s"<?php if($param->unit=="Pa·s") echo " selected";?>>Pa·s</option>
				<option value="Darcy"<?php if($param->unit=="Darcy") echo " selected";?>>Darcy</option>
				<option value="mDarcy"<?php if($param->unit=="mDarcy") echo " selected";?>>mDarcy</option>
				<option value="m-1"<?php if($param->unit=="m-1") echo " selected";?>>m-1</option>
				<option value="m2"<?php if($param->unit=="m2") echo " selected";?>>m2</option>
				<option value="milistrain"<?php if($param->unit=="milistrain") echo " selected";?>>milistrain</option>
				<option value="mm·sec-1"<?php if($param->unit=="mm·sec-1") echo " selected";?>>mm·sec-1</option>
				<option value="N·sec-1"<?php if($param->unit=="N·sec-1") echo " selected";?>>N·sec-1</option>
				<option value="sec-1"<?php if($param->unit=="sec-1") echo " selected";?>>sec-1</option>
				<option value="kN·mm-1"<?php if($param->unit=="kN·mm-1") echo " selected";?>>kN·mm-1</option>
				<option value="%"<?php if($param->unit=="%") echo " selected";?>>%</option>
				<option value="count"<?php if($param->unit=="count") echo " selected";?>>count</option>
				<option value="cc"<?php if($param->unit=="cc") echo " selected";?>>cc</option>
				<option value="mm3"<?php if($param->unit=="mm3") echo " selected";?>>mm3</option>
			</select>
		</td>
		<td>
			<select class="formControl formSelect" name="paramPrefix">
				<option value="1E+1"<?php if($param->prefix=="1E+1") echo " selected";?>>1E+1</option>
				<option value="1E+2"<?php if($param->prefix=="1E+2") echo " selected";?>>1E+2</option>
				<option value="1E+3"<?php if($param->prefix=="1E+3") echo " selected";?>>1E+3</option>
				<option value="1E+4"<?php if($param->prefix=="1E+4") echo " selected";?>>1E+4</option>
				<option value="1E+5"<?php if($param->prefix=="1E+5") echo " selected";?>>1E+5</option>
				<option value="1E+6"<?php if($param->prefix=="1E+6") echo " selected";?>>1E+6</option>
				<option value="-"<?php if($param->prefix=="-") echo " selected";?>>-</option>
				<option value="1E-1"<?php if($param->prefix=="1E-1") echo " selected";?>>1E-1</option>
				<option value="1E-2"<?php if($param->prefix=="1E-2") echo " selected";?>>1E-2</option>
				<option value="1E-3"<?php if($param->prefix=="1E-3") echo " selected";?>>1E-3</option>
				<option value="1E-4"<?php if($param->prefix=="1E-4") echo " selected";?>>1E-4</option>
				<option value="1E-5"<?php if($param->prefix=="1E-5") echo " selected";?>>1E-5</option>
				<option value="1E-6"<?php if($param->prefix=="1E-6") echo " selected";?>>1E-6</option>
			</select>
		</td>
		<td><input id="paramNote" class="formControl" type="text" value="<?php echo $param->note?>"></td>
		<td>
			<div style="white-space: nowrap;">
				<button class="squareButton" onclick="deleteApparatusParam(<?php echo $buttonNum?>)"><image src="/experimental/buttonImages/icons8-trash-30.png" height="20" width="20" /></button>
				<button class="squareButton" onclick="moveApparatusParamUp(<?php echo $buttonNum?>)" style="display:<?php echo $up?>"><image src="/experimental/buttonImages/icons8-up-30.png" height="20" width="20"/></button>
				<button class="squareButton" onclick="moveApparatusParamDown(<?php echo $buttonNum?>)" style="display:<?php echo $down?>"><image src="/experimental/buttonImages/icons8-down-30.png" height="20" width="20"/></button>
			</div>
		</td>
	</tr>
<?php
}
?>
			</table>
		</div>
		<div style="padding-left:5px;"><button class="smallerButton" style="vertical-align:middle" onclick="addApparatusParamRow();"><span>Add Parameter </span></button></div>
	</fieldset>
	<div class="fsSpacer"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Apparatus Documents</legend>
		<div id="docsWrapper">
			<div class="subDataHolder" id="documentRows">

<?php
$count = count($documents);
for($x = 0; $x < $count; $x++){
	$doc = $documents[$x];

	$buttonNum = $x+1;


	//Set up up/down button visibility
	$up = "inline";
	$down = "inline";
	if($x == 0){
		$up = "none";
		if($count == 1) $down = "none";
	}elseif($x == $count - 1){
		$down = "none";
		//if($count == 2) $up = "none";
	}


	$uuid = $doc->uuid;
	//Get existing file name
	//$fileName = $db->get_var("select original_filename from apprepo.apparatus_document where uuid = '$uuid'");
	$path = $doc->path;

	/*
stdClass Object
(
	[type] => Diagram
	[format] => txt
	[id] => doc_id_1gdf
	[uuid] => 8db225a5-10b4-47ac-a7ce-7df4e6c93788
	[description] => Lorem ipsum dolor sit amet...
)
<?php if($doc->type=="") echo " selected";?>
	*/

?>




				<div class="docRow" id="docRow-<?php echo $buttonNum?>">
					<div class="docCell" style="width:1160px;">
						<div class="formRow">
							<div class="formCell 16">
								<div class="formPart">
									<label class="formLabel">Document Type <span class="reqStar">*</span></label>
									<select class="formControl formSelect" name="docType" id="docType" onchange="exper_handleApparatusDocTypeChange(<?php echo $buttonNum?>);">
										<option value="Manual"<?php if($doc->type=="Manual") echo " selected";?>>Manual</option>
										<option value="Diagram"<?php if($doc->type=="Diagram") echo " selected";?>>Diagram</option>
										<option value="Picture"<?php if($doc->type=="Picture") echo " selected";?>>Picture</option>
										<option value="Video"<?php if($doc->type=="Video") echo " selected";?>>Video</option>
										<option value="Data"<?php if($doc->type=="Data") echo " selected";?>>Data</option>
										<option value="Software"<?php if($doc->type=="Software") echo " selected";?>>Software</option>
										<option value="ASTM"<?php if($doc->type=="ASTM") echo " selected";?>>ASTM</option>
										<option value="Publication"<?php if($doc->type=="Publication") echo " selected";?>>Publication</option>
										<option value="Other"<?php if($doc->type=="Other") echo " selected";?>>Other</option>
									</select>

									<?php
									if($doc->type == "Other"){
										$showtype = "inline";
									}else{
										$showtype = "none";
									}
									?>

									<div id ="otherDocTypeHolder" style="white-space:nowrap;display:<?php echo $showtype?>;padding-top:5px;">
										<input id="otherDocType" class="formControl" style="width:110px;" type="text" value="<?php echo $doc->other_type?>" placeholder="doc type...">
									</div>
								</div>
							</div>
							<div class="formCell w16">
								<div class="formPart">
									<label class="formLabel">Document Format <span class="reqStar">*</span></label>
									<select class="formControl formSelect" id="docFormat" onchange="exper_handleApparatusDocFormatChange(<?php echo $buttonNum?>);">
										<option value="jpg"<?php if($doc->format=="jpg") echo " selected";?>>jpg</option>
										<option value="png"<?php if($doc->format=="png") echo " selected";?>>png</option>
										<option value="txt"<?php if($doc->format=="txt") echo " selected";?>>txt</option>
										<option value="csv"<?php if($doc->format=="csv") echo " selected";?>>csv</option>
										<option value="zip"<?php if($doc->format=="zip") echo " selected";?>>zip</option>
										<option value="rar"<?php if($doc->format=="rar") echo " selected";?>>rar</option>
										<option value="pdf"<?php if($doc->format=="pdf") echo " selected";?>>pdf</option>
										<option value="docx"<?php if($doc->format=="docx") echo " selected";?>>docx</option>
										<option value="Other"<?php if($doc->format=="Other") echo " selected";?>>Other</option>
									</select>

									<?php
									if($doc->format == "Other"){
										$showformat = "inline";
									}else{
										$showformat = "none";
									}
									?>

									<div id ="otherDocFormatHolder" style="white-space:nowrap;display:<?php echo $showformat?>;padding-top:5px;">
										<input id="otherDocFormat" class="formControl" type="text" value="<?php echo $doc->other_format?>" placeholder="enter document format here...">
									</div>

								</div>
							</div>
							<div class="formCell w50">
								<div class="formPart" id="fileHolder">


									<label class="formLabel">File Uploaded</label>
									<div>
										<a href="<?php echo $path?>" target="_blank"><?php echo $path?></a>
									</div>
									<div>
										<a id="deleteLink" href="javascript:void(0);" onclick="exper_deleteApparatusFile(<?php echo $buttonNum?>)">(Delete File)</a>
									</div>



								</div>
							</div>
							<div class="formCell w16">
								<div class="formPart">
									<label class="formLabel">Document ID</label>
									<input id="DocumentId" class="formControl" type="text" value="<?php echo $doc->id?>"></input>
									<input id="uuid" type="hidden" value="<?php echo $doc->uuid?>"></input>
									<input id="originalFilename" type="hidden" value="<?php echo $doc->path?>"></input>
								</div>
							</div>
						</div>
						<div class="formRow">
							<div class="formCell w100">
								<div class="formPart">
									<label class="formLabel">Description</label>
									<textarea class="formControl docDescText" data-schemaformat="markdown" id="docDescription"><?php echo $doc->description?></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="docCell" style="width:25px;">
						<div style="padding-top:10px;"><button class="squareButton squareButtonBottom" onclick="deleteApparatusDocument(<?php echo $buttonNum?>)"><image src="/experimental/buttonImages/icons8-trash-30.png" height="20" width="20" /></button></div>
						<div><button class="squareButton squareButtonBottom" onclick="moveApparatusDocumentUp(<?php echo $buttonNum?>)" style="display:<?php echo $up?>"><image src="/experimental/buttonImages/icons8-up-30.png" height="20" width="20" /></button></div>
						<div><button class="squareButton squareButtonBottom" onclick="moveApparatusDocumentDown(<?php echo $buttonNum?>)" style="display:<?php echo $down?>"><image src="/experimental/buttonImages/icons8-down-30.png" height="20" width="20" /></button></div>
					</div>
					<div class="clearLeft"></div>
				</div>





<?php
//doSubmitEditApparatus()
}
?>


			</div>
		</div>
		<div style="padding-left:5px;"><button class="smallerButton" style="vertical-align:middle" onclick="addApparatusDocument();"><span>Add Document </span></button></div>
	</fieldset>
	<div class="fsSpacer"></div>
	<div style="text-align:center;">
		<button class="submitButton" style="vertical-align:middle;margin-right:150px;" onclick="history.back();"><span>Cancel </span></button>
		<button class="submitButton" style="vertical-align:middle" onclick="doSubmitEditApparatus()"><span>Save Changes </span></button>
	</div>


<input type="hidden" id="apparatusPkey" value="<?php echo $pkey?>">

	<div style="display:none;">
		<div class="docRow" id="sourceApparatusDocumentRow">
			<div class="appDocCell" style="width:1140px;">
				<div class="formRow">
					<div class="formCell 16">
						<div class="formPart">
							<label class="formLabel">Document Type <span class="reqStar">*</span></label>
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
							<label class="formLabel">Document Format <span class="reqStar">*</span></label>
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
							<label class="formLabel">Choose File <span class="reqStar">*</span></label>
							<input type="file" id="docFile" class="formControl">
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

<div style="display:none;">
	<div class="docRow" id="sourceDocumentRow">
		<div class="docCell" style="width:1160px;">
			<div class="formRow">
				<div class="formCell 16">
					<div class="formPart">
						<label class="formLabel">Document Type <span class="reqStar">*</span></label>
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
						<label class="formLabel">Document Format <span class="reqStar">*</span></label>
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
					<div class="formPart">
						<label class="formLabel">Choose File <span class="reqStar">*</span></label>
						<input type="file" class="formControl"/>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel">Document ID</label>
						<input id="DocumentId" class="formControl" type="text" value="id here"></input>
						<input id="uuid" type="hidden" value=""></input>
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w100">
					<div class="formPart">
						<label class="formLabel">Description</label>
						<textarea class="formControl docDescText" data-schemaformat="markdown" id="docDescription">Lorem ipsum dolor sit amet... </textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="docCell" style="width:25px;">
			<div style="padding-top:10px;"><button class="squareButton squareButtonBottom"><image src="/experimental/buttonImages/icons8-trash-30.png" height="20" width="20" /></button></div>
			<div><button class="squareButton squareButtonBottom"><image src="/experimental/buttonImages/icons8-up-30.png" height="20" width="20" /></button></div>
			<div><button class="squareButton squareButtonBottom"><image src="/experimental/buttonImages/icons8-down-30.png" height="20" width="20" /></button></div>
		</div>
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