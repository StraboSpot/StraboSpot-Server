<?php
/**
 * File: view_apparatus.php
 * Description: Displays apparatus information and specifications
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

if(!in_array($userpkey, $admin_pkeys)){
	//only admins for now...
}

if($_POST['jsonData']!=""){

	exit();
}

$apparatus_pkey = isset($_GET['a']) ? (int)$_GET['a'] : 0;

if($apparatus_pkey == 0){
	echo "No apparatus provided.";
	exit();
}

$arow = $db->get_row_prepared("SELECT * FROM exp_apparatus WHERE pkey = $1", array($apparatus_pkey));

if($arow->pkey == ""){
	echo "Apparatus not found.";
	exit();
}

$num_sensors = 5;

$json = $arow->json;
$j = json_decode($json);

function getSensor($sensors, $insensornum){
	foreach($sensors as $s){
		if($s->sensornum == $insensornum){
			return $s;
		}else{
		}
	}

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
	font-weight:bold;
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

<div id="wholewrapper">

	<!--<form method="POST" onsubmit="return validateForm()">-->

	<?php
	if($error!=""){
	?>
	<div class="rowdiv" style="color:red; font-size:1.5em;"><?php echo $error?></div>
	<?php
	}
	?>

	<!--
	<div class="rowdiv">
		<h2>Edit Apparatus</h2>
	</div>
	-->

	<div class="rowdiv">
		<h2>Facility Information</h2>
	</div>

	<input type="hidden" id="institute_pkey" value="<?php echo $j->institute_pkey?>">

	<div class="rowdiv">
		<?php if($j->lab_name!=""){echo $j->lab_name."<br>";}?>
		<?php if($j->lab_name!=""){echo $j->facility_id."<br>";}?>
		<?php if($j->lab_name!=""){echo "<a href=\"$j->website\" target=\"_blank\">$j->website</a><br>";}?>
	</div>

	<div class="rowdiv rowheader">Address</div>

	<div class="rowdiv">
		<?php if($j->address1!=""){echo $j->address1."<br>";}?>
		<?php if($j->address2!=""){echo $j->address2."<br>";}?>
		<?php if($j->city!=""){echo $j->city."<br>";}?>
		<?php if($j->state!=""){echo $j->state.", ";}?>
		<?php if($j->country!=""){echo $j->country."<br>";}?>
		<?php if($j->zip!=""){echo $j->zip."<br>";}?>
	</div>

	<div class="rowdiv rowheader">Facility Contact</div>

	<div class="rowdiv">
		<?php echo $j->contact_title?> <?php echo $j->contact_first_name?> <?php echo $j->contact_last_name?><br>
		<?php
		if($j->contact_email != ""){
		?>
		<a href="mailto:<?php echo $j->contact_email?>"><?php echo $j->contact_email?></a>
		<?php
		}
		?>
	</div>

	<div class="rowdiv">
		<h2>Apparatus Information</h2>
	</div>

	<div class="rowdiv">
		<div style="float:left;padding-left:100px;">
			Apparatus Type: <?php echo $j->apparatus_type?>
		</div>

		<?php
		$subtypedisplay = "none";
		if($j->apparatus_type == "Hydrostatic Loading Apparatuses"){
			$subtypedisplay = "block";
		}elseif($j->apparatus_type == "Uniaxial Apparatus"){
			$subtypedisplay = "block";
		}elseif($j->apparatus_type == "Biaxial Apparatus"){
			$subtypedisplay = "block";
		}elseif($j->apparatus_type == "Triaxial (Conventional) Apparatus"){
			$subtypedisplay = "block";
		}elseif($j->apparatus_type == "Rotary Shear Apparatus"){
			$subtypedisplay = "block";
		}elseif($j->apparatus_type == "Indenter"){
			$subtypedisplay = "block";
		}elseif($j->apparatus_type == "Nanoindenter"){
			$subtypedisplay = "block";
		}
		?>

		<div style="float:left;padding-left:30px;display:<?php echo $subtypedisplay?>;" name="apparatus_subtype_wrapper" id="apparatus_subtype_wrapper">
			Apparatus Subtype: <?php echo $j->apparatus_subtype?>
		</div>
		<div style="clear:left"></div>
	</div>

	<div class="rowdiv">
		Apparatus Name:
		<?php echo $j->apparatus_name?>
	</div>

	<?php
	$dropzone_rig_photo_display = "block";
	$static_rig_photo_display = "none";
	$dropzone_rig_schematic_display = "block";
	$static_rig_schematic_display = "none";

	if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/photo_".$apparatus_pkey)){
		$dropzone_rig_photo_display = "none";
		$static_rig_photo_display = "block";
	}

	if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/schematic_".$apparatus_pkey)){
		$dropzone_rig_schematic_display = "none";
		$static_rig_schematic_display = "block";
		$schematic_filename = $db->get_var_prepared("SELECT original_file_name FROM exp_images WHERE type='schematic' AND apparatus_pkey = $1", array($apparatus_pkey));
	}

	?>

	<div class="rowdiv" style="padding-top:20px;">
	<div style="float:left;padding-right:20px;font-size:1.3em;padding-top:60px;padding-left:170px;" id="rigStaticPhotoLabel">Rig Photo:</div>
	<div style="float:left;" id="rigStaticPhoto">
		<div><a href="/apparatus_photo_<?php echo $apparatus_pkey?>_large.jpg" data-featherlight="image"><img src="/apparatus_photo_<?php echo $apparatus_pkey?>_small.jpg"></a></div>
	</div>

	<div style="float:left;padding-right:20px;font-size:1.3em;padding-top:60px;padding-left:20px;display:<?php echo $dropzone_rig_schematic_display?>;" id="rigDZSchematicLabel">Rig Schematic: No Rig Schematic.</div><div style="float:left;display:<?php echo $dropzone_rig_schematic_display?>;"></div>
	<div style="float:left;padding-right:20px;font-size:1.3em;padding-top:60px;padding-left:20px;display:<?php echo $static_rig_schematic_display?>;" id="rigStaticSchematicLabel">Rig Schematic:</div>
	<div style="float:left;display:<?php echo $static_rig_schematic_display?>;vertical-align:middle;padding-top:63px;" id="rigStaticSchematic">
		<div><a href="/apparatus_schematic_<?php echo $apparatus_pkey?>">Download</a></div>
	</div>

	<div style="clear:left;"></div>
	</div>

	<div class="rowdiv rowheader" style="padding-top:15px;">Possible Test Types</div>

	<div style="border:1px dashed rgba(50, 50, 50, 0.5);border-radius:10px;padding:20px;width:300px;margin:auto;margin-top:10px;">
		<div style="font-weight:bold;">Static:</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_hip" id="checkbox_hip"<?php if($j->test_types->hip == 1){echo " checked";}?>> HIP</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_synthesis" id="checkbox_synthesis"<?php if($j->test_types->synthesis == 1){echo " checked";}?>> Synthesis</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_depositionevaporation" id="checkbox_depositionevaporation"<?php if($j->test_types->depositionevaporation == 1){echo " checked";}?>> Deposition/Evaporation</div>

		<div style="font-weight:bold;">Indentation:</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_indentationcreep" id="checkbox_indentationcreep"<?php if($j->test_types->indentationcreep == 1){echo " checked";}?>> Indentation-Creep</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_hardness" id="checkbox_hardness"<?php if($j->test_types->hardness == 1){echo " checked";}?>> Hardness</div>

		<div style="font-weight:bold;">Pore Structure:</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_permeability" id="checkbox_permeability"<?php if($j->test_types->permeability == 1){echo " checked";}?>> Permeability</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_resistivity" id="checkbox_resistivity"<?php if($j->test_types->resistivity == 1){echo " checked";}?>> Resistivity</div>

		<div style="font-weight:bold;">Shear Deformation:</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_simpleshear" id="checkbox_simpleshear"<?php if($j->test_types->simpleshear == 1){echo " checked";}?>> Simple Shear</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_rotaryshear" id="checkbox_rotaryshear"<?php if($j->test_types->rotaryshear == 1){echo " checked";}?>> Rotary Shear</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_pureshear" id="checkbox_pureshear"<?php if($j->test_types->pureshear == 1){echo " checked";}?>> Pure Shear</div>

		<div style="font-weight:bold;">Uniaxial Deformation:</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_constantstress" id="checkbox_constantstress"<?php if($j->test_types->constantstress == 1){echo " checked";}?>> Constant Stress</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_constantrate" id="checkbox_constantrate"<?php if($j->test_types->constantrate == 1){echo " checked";}?>> Constant Rate</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_loadingunloading" id="checkbox_loadingunloading"<?php if($j->test_types->loadingunloading == 1){echo " checked";}?>> Loading/Unloading</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_stepping" id="checkbox_stepping"<?php if($j->test_types->stepping == 1){echo " checked";}?>> Stepping</div>
		<div style="padding-left:20px;"><input type="checkbox" disabled name="checkbox_extension" id="checkbox_extension"<?php if($j->test_types->extension == 1){echo " checked";}?>> Extension</div>
	</div>

	<div class="rowdiv rowheader" style="padding-top:25px;">Apparatus Capabilities, Sensors, and Corrections</div>

	<?php
	if($j->confining_pressure != ""){
		$confining_checked = " checked";
		$confining_display = " style=\"display:block;\"";
	}else{
		$confining_display = " style=\"display:none;\"";
	}
	?>

	<div style="padding-top:20px;margin-left:25px;margin-right:25px;">
		<div class="checkheader"<?php echo $confining_display?>>Confining Pressure</div>
		<div class="checkbody" id="confiningpressure_wrapper"<?php echo $confining_display?>>
			<div><input type="checkbox" disabled id="cp_servo_controlled"<?php if($j->confining_pressure->servo_controlled == 1) echo " checked";?>> Servo Controlled</div>
			<div class="rowdivv">
				Confining Medium: <?php echo $j->confining_pressure->confiningmediumone?>
				<?php
				if($j->confining_pressure->confiningmediumone != ""){
					$cm1show = "inline";
				}else{
					$cm1show = "none";
				}
				?>
				<?php echo $j->confining_pressure->confiningmediumtwo?>
				<?php
				if($j->confining_pressure->confiningmediumtwo == "Other"){
					$cmothershow = "inline";
				}else{
					$cmothershow = "none";
				}
				?>
				<?php echo $j->confining_pressure->confiningmediumother?>
			</div>
			<div class="rowdivv">
				<?php
				if($j->confining_pressure->minconfiningpressure != ""){
				?>
				Min Confining Pressure:
				<?php echo $j->confining_pressure->minconfiningpressure?>
				<?php echo $j->confining_pressure->minconfiningpressureunit?>

				<?php
				}

				if($j->confining_pressure->minconfiningpressure != ""){
				?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max Confining Pressure:
				<?php echo $j->confining_pressure->maxconfiningpressure?>
				<?php echo $j->confining_pressure->maxconfiningpressureunit?>

				<?php
				}
				?>
			</div>
			<div class="separator" style="margin-top:5px;">
			Sensor(s)
			</div>

			<?php
			for($cp_sensor_num = 1; $cp_sensor_num <= $num_sensors; $cp_sensor_num++){

				$thissensor = getSensor($j->confining_pressure->sensors, $cp_sensor_num);

				if($cp_sensor_num > 1){
					if($thissensor->sensortype==""){
						$sensorstyle=" style=\"display:none;\"";
					}else{
						$sensorstyle="";
					}
				}

			?>

			<div class="rowdivv" id="cp_sensor_wrapper_<?php echo $cp_sensor_num?>" <?php echo $sensorstyle?>>

				<?php if($cp_sensor_num > 1){?>
				<div class="separator" style="margin-top:10px;margin-bottom:5px;"></div>
				<?php }?>

				<div style="float:left;width:600px;">
					<div class="rowdivv">
						Sensor Type: <?php echo $thissensor->sensortype ?>
						&nbsp;&nbsp;&nbsp;
						<?php echo $thissensor->sensorothertype?>
					</div>
					<div class="rowdivv">
						Sensor Location: <?php echo $thissensor->sensorlocation?>
						&nbsp;&nbsp;&nbsp;
						Calibration: <?php echo $thissensor->sensorcalibration?>
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<?php echo $thissensor->sensornotes?>
					</div>
				</div>
				<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div>

				<?php
				if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/cpsensor_".$apparatus_pkey."_".$cp_sensor_num)){
					$dzshow = "none";
					$imgshow = "inline";
				}else{
					$dzshow = "inline";
					$imgshow = "none";
				}
				?>

				<div style="float:left;display:<?php echo $dzshow?>;padding-top:60px;">N/A</div>
				<div style="text-align:center;float:left;vertical-align:middle;padding-top:60px;display:<?php echo $imgshow?>;">
					<a href="/sensor_calibration_file/cpsensor/<?php echo $apparatus_pkey?>/<?php echo $cp_sensor_num?>">Download</a><br>
				</div>

				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

		</div>

	<?php
	if($j->temp != ""){
		$temp_checked = " checked";
		$temp_display = " style=\"display:block;\"";
	}else{
		$temp_display = " style=\"display:none;\"";
	}
	?>

		<div class="checkheader"<?php echo $temp_display?>>Temperature (Furnace/Cryostat)</div>
		<div class="checkbody" id="temperaturefurnacecryostat_wrapper"<?php echo $temp_display?>>
			<div><input type="checkbox" disabled id="temp_furnace"<?php if($j->temp->furnace!=""){echo " checked";}?>> Furnace
			&nbsp;&nbsp;&nbsp;<input type="checkbox" disabled id="temp_cryostat"<?php if($j->temp->cryostat!=""){echo " checked";}?>> Cryostat</div>
			<div><input type="checkbox" disabled id="temp_servo_controlled"<?php if($j->temp->servocontrolled!=""){echo " checked";}?>> Servo Controlled</div>

			<div class="rowdivv">
				<?php
				if($j->temp->mintemperature != ""){
				?>
				Min Temperature:
				<?php echo $j->temp->mintemperature?>
				<?php echo $j->temp->mintemperatureunit?>
				<?php
				}

				if($j->temp->maxtemperature != ""){
				?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max Temperature:
				<?php echo $j->temp->maxtemperature?>
				<?php echo $j->temp->maxtemperatureunit?>
				<?php
				}
				?>
			</div>

			<div class="separator" style="margin-top:5px;">
			Sensor(s)
			</div>

			<?php
			$sensorstyle="";
			for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){

				$thissensor = getSensor($j->temp->sensors, $sensor_num);

				if($sensor_num > 1){
					if($thissensor->sensortype==""){
						$sensorstyle=" style=\"display:none;\"";
					}else{
						$sensorstyle="";
					}
				}
			?>

			<div class="rowdivv" id="temp_sensor_wrapper_<?php echo $sensor_num?>" <?php echo $sensorstyle?>>

				<?php if($sensor_num > 1){?>
				<div class="separator" style="margin-top:10px;margin-bottom:5px;"></div>
				<?php }?>

				<div style="float:left;width:610px;">
					<div class="rowdivv" style="white-space: nowrap;">
						Sensor Type: <?php echo $thissensor->sensortype?>

						<?php
						if($thissensor->sensortype=="Thermocouple"){
							$display="inline";
						}else{
							$display="none";
						}
						?>

						<span id="temp_thermocoupletypespan_<?php echo $sensor_num?>" style="padding-left:10px;display:<?php echo $display?>;">
							Thermocouple Type: <?php echo $thissensor->thermocoupletype?>
						</span>

						<?php
						if(	$thissensor->thermocoupletype == "Nickel Alloy" ||
							$thissensor->thermocoupletype == "Platinum/Rhodium-Alloy" ||
							$thissensor->thermocoupletype == "Tungsten/Rhenium-Alloy"
						){
							$display = "inline";
						}else{
							$display = "none";
						}
						?>

						<span id="temp_thermocouplesubtypespan_<?php echo $sensor_num?>" style="padding-left:10px;display:<?php echo $display?>;">
							Sub Type: <?php echo $thissensor->thermocouplesubtype?>
						</span>

						<?php
						if($thissensor->sensortype=="Resistance Temperature Detector (RTD)"){
							$display="inline";
						}else{
							$display="none";
						}
						?>

						<span id="temp_rtdspan_<?php echo $sensor_num?>" style="padding-left:10px;display:<?php echo $display?>;">
							RTD Type: <?php echo $thissensor->rtdtype?>
						</span>

						<?php
						if($thissensor->sensortype=="Thermistor"){
							$display="inline";
						}else{
							$display="none";
						}
						?>

						<span id="temp_thermistortypespan_<?php echo $sensor_num?>" style="padding-left:10px;display:<?php echo $display?>;">
							Thermistor Type: <?php echo $thissensor->thermistortype?>
						</span>

						<?php
						if($thissensor->sensortype=="Other"){
							$display="inline";
						}else{
							$display="none";
						}
						?>

						<span id="temp_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
							<?php echo $thissensor->othersensortype?>
						</span>
					</div>
					<div class="rowdivv">
						Sensor Location: <?php echo $thissensor->sensorlocation?>
						&nbsp;&nbsp;&nbsp;
						Calibration: <?php echo $thissensor->sensorcalibration?>
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<?php echo $thissensor->sensornotes?>
					</div>
				</div>
				<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div>

								<?php
				if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/tempsensor_".$apparatus_pkey."_".$sensor_num)){
					$dzshow = "none";
					$imgshow = "inline";
				}else{
					$dzshow = "inline";
					$imgshow = "none";
				}
				?>

				<div style="float:left;display:<?php echo $dzshow?>;padding-top:60px;">N/A</div>
				<div style="text-align:center;float:left;vertical-align:middle;padding-top:60px;display:<?php echo $imgshow?>;">
					<a href="/sensor_calibration_file/tempsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
				</div>

				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

		</div>

	<?php
	if($j->displacement != ""){
		$displacement_checked = " checked";
		$displacement_display = " style=\"display:block;\"";
	}else{
		$displacement_display = " style=\"display:none;\"";
	}
	?>

		<div class="checkheader"<?php echo $displacement_display?>>Displacement</div>
		<div class="checkbody" id="displacement_wrapper"<?php echo $displacement_display?>>

			<?php
			if($j->displacement->axial != ""){
				$displacement_axial_checked = " checked";
				$displacement_axial_display = " style=\"display:block;\"";
			}else{
				$displacement_axial_display = " style=\"display:none;\"";
			}
			if($j->displacement->rotary != ""){
				$displacement_rotary_checked = " checked";
				$displacement_rotary_display = " style=\"display:block;\"";
			}else{
				$displacement_rotary_display = " style=\"display:none;\"";
			}
			if($j->displacement->volumetric != ""){
				$displacement_volumetric_checked = " checked";
				$displacement_volumetric_display = " style=\"display:block;\"";
			}else{
				$displacement_volumetric_display = " style=\"display:none;\"";
			}
			if($j->displacement->shear != ""){
				$displacement_shear_checked = " checked";
				$displacement_shear_display = " style=\"display:block;\"";
			}else{
				$displacement_shear_display = " style=\"display:none;\"";
			}
			?>

			<!--
			<input type="checkbox" id="checkbox_dispax"<?php echo $displacement_axial_checked?>> Axial
			&nbsp;&nbsp;
			<input type="checkbox" id="checkbox_disprot"<?php echo $displacement_rotary_checked?>> Rotary
			&nbsp;&nbsp;
			<input type="checkbox" id="checkbox_dispvol"<?php echo $displacement_volumetric_checked?>> Volumetric
			&nbsp;&nbsp;
			<input type="checkbox" id="checkbox_dispshear"<?php echo $displacement_shear_checked?>> Shear
			-->

			<div class="displacementbox" id="dispax_wrapper"<?php echo $displacement_axial_display?>>
				<div class="rowheader">Axial</div>
				<div class="rowdivv">
					Min Displacement: <?php echo $j->displacement->axial->mindisplacement?> <?php echo $j->displacement->axial->mindisplacementunit?>

					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Displacement: <?php echo $j->displacement->axial->maxdisplacement?> <?php echo $j->displacement->axial->maxdisplacementunit?>
				</div>
				<div class="rowdivv">
					Min Displacement Rate: <?php echo $j->displacement->axial->mindisplacementrate?> <?php echo $j->displacement->axial->mindisplacementrateunit?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Displacement Rate: <?php echo $j->displacement->axial->maxdisplacementrate?> <?php echo $j->displacement->axial->maxdisplacementrateunit?>
				</div>

				<div class="separator" style="margin-top:5px;">
				Sensor(s)
				</div>

				<?php
				$sensorstyle="";
				for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
					$thissensor = getSensor($j->displacement->axial->sensors, $sensor_num);

					if($sensor_num > 1){
						if($thissensor->sensortype==""){
							$sensorstyle=" style=\"display:none;\"";
						}else{
							$sensorstyle="";
						}
					}
				?>

				<div class="rowdivv" id="dispax_sensor_wrapper_<?php echo $sensor_num?>" <?php echo $sensorstyle?>>

					<?php if($sensor_num > 1){?>
					<div class="separator" style="margin-top:10px;margin-bottom:5px;"></div>
					<?php }?>

					<div style="float:left;width:600px;">
						<div class="rowdivv" style="white-space: nowrap;">
							Sensor Type: <?php echo $thissensor->sensortype ?>
							<?php
								if($thissensor->sensortype=="Other"){
									$display = "inline";
								}else{
									$display = "none";
								}
							?>
							<span id="dispax_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
								<?php echo $thissensor->othersensortype?>
							</span>
						</div>
						<div class="rowdivv">
							Sensor Location: <?php echo $thissensor->sensorlocation?>
							&nbsp;&nbsp;&nbsp;
							Calibration: <?php echo $thissensor->sensorcalibration?>
						</div>
						<div class="rowdivv">
							Sensor Notes:
							<?php echo $thissensor->sensornotes?>
						</div>
					</div>

					<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div>

					<?php
					if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/dispaxsensor_".$apparatus_pkey."_".$sensor_num)){
						$dzshow = "none";
						$imgshow = "inline";
					}else{
						$dzshow = "inline";
						$imgshow = "none";
					}
					?>

					<div style="float:left;display:<?php echo $dzshow?>;padding-top:60px;">N/A</div>
					<div style="text-align:center;float:left;vertical-align:middle;padding-top:60px;display:<?php echo $imgshow?>;">
						<a href="/sensor_calibration_file/dispaxsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
					</div>

					<div style="clear:left;"></div>
				</div>

				<?php
				}//end foreach
				?>

			</div>

			<div class="displacementbox" id="disprot_wrapper"<?php echo $displacement_rotary_display?>>
				<div class="rowheader">Rotary</div>
				<div class="rowdivv">
					<input type="checkbox" disabled id="disprot_solid_cylinder"<?php if($j->displacement->rotary->solidcylinder!=""){echo " checked";}?>> Solid Cylinder
					&nbsp;&nbsp;&nbsp;
					<input type="checkbox" disabled id="disprot_annular"<?php if($j->displacement->rotary->annular!=""){echo " checked";}?>> Annular (Ring-shaped)
				</div>
				<div class="rowdivv">
					<input type="checkbox" disabled id="disprot_servo_controlled"<?php if($j->displacement->rotary->servocontrolled!=""){echo " checked";}?>> Servo Controlled
				</div>
				<div class="rowdivv">
					Min Sample Diameter:
					<?php echo $j->displacement->rotary->minsamplediameter?> <?php echo $j->displacement->rotary->minsamplediameterunit?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Sample Diameter:
					<?php echo $j->displacement->rotary->maxsamplediameter?> <?php echo $j->displacement->rotary->maxsamplediameterunit?>
				</div>
				<div class="rowdivv">
					Min Sample Thickness:
					<?php echo $j->displacement->rotary->minsamplethickness?> <?php echo $j->displacement->rotary->minsamplethicknessunit?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Sample Thickness:
					<?php echo $j->displacement->rotary->maxsamplethickness?> <?php echo $j->displacement->rotary->maxsamplethicknessunit?>
				</div>
				<div class="rowdivv">
					Min Displacement:
					<?php echo $j->displacement->rotary->mindisplacement?> <?php echo $j->displacement->rotary->mindisplacementunit?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Displacement:
					<?php echo $j->displacement->rotary->maxdisplacement?> <?php echo $j->displacement->rotary->maxdisplacementunit?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" disabled id="disprot_infinitemaxdisplacement"<?php if($j->displacement->rotary->infinitemaxdisplacement!=""){echo " checked";}?>>&nbsp;Infinite
				</div>
				<div class="rowdivv">
					Min Rotation Rate:
					<?php echo $j->displacement->rotary->minrotationrate?> <?php echo $j->displacement->rotary->minrotationunit?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Rotation Rate:
					<?php echo $j->displacement->rotary->maxrotationrate?> <?php echo $j->displacement->rotary->maxrotationunit?>
					<input type="text" class="only-numeric" id="disprot_maxrotationrate" value="<?php echo $j->displacement->rotary->maxrotationrate?>">
				</div>

				<div class="separator" style="margin-top:5px;">
				Sensor(s)
				</div>

				<?php
				$sensorstyle="";
				for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
					$thissensor = getSensor($j->displacement->axial->sensors, $sensor_num);

					if($sensor_num > 1){
						if($thissensor->sensortype==""){
							$sensorstyle=" style=\"display:none;\"";
						}else{
							$sensorstyle="";
						}
					}
				?>

				<div class="rowdivv" id="disprot_sensor_wrapper_<?php echo $sensor_num?>" <?php echo $sensorstyle?>>

					<?php if($sensor_num > 1){?>
					<div class="separator" style="margin-top:10px;margin-bottom:5px;"></div>
					<?php }?>

					<div style="float:left;width:600px;">
						<div class="rowdivv" style="white-space: nowrap;">
							Sensor Type: <?php echo $thissensor->sensortype?>
							<?php
								if($thissensor->sensortype=="Other"){
									$display = "inline";
								}else{
									$display = "none";
								}
							?>
							<span id="disprot_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
								<?php echo $thissensor->othersensortype?>
							</span>
						</div>
						<div class="rowdivv">
							Sensor Location: <?php echo $thissensor->sensorlocation?>
							&nbsp;&nbsp;&nbsp;
							Calibration: <?php echo $thissensor->sensorcalibration?>
						</div>
						<div class="rowdivv">
							Sensor Notes:
							<?php echo $thissensor->sensornotes?>
						</div>
					</div>

					<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div>

					<?php
					if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/disprotsensor_".$apparatus_pkey."_".$sensor_num)){
						$dzshow = "none";
						$imgshow = "inline";
					}else{
						$dzshow = "inline";
						$imgshow = "none";
					}
					?>

					<div style="float:left;display:<?php echo $dzshow?>;padding-top:60px;">N/A</div>
					<div style="text-align:center;float:left;vertical-align:middle;padding-top:60px;display:<?php echo $imgshow?>;">
						<a href="/sensor_calibration_file/disprotsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
					</div>

					<div style="clear:left;"></div>
				</div>

				<?php
				}//end foreach
				?>

			</div>

			<div class="displacementbox" id="dispvol_wrapper"<?php echo $displacement_volumetric_display?>>
				<div class="rowheader">Volumetric</div>

				<div class="separator" style="margin-top:5px;">
				Sensor(s)
				</div>

				<?php
				$sensorstyle="";
				for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
					$thissensor = getSensor($j->displacement->volumetric->sensors, $sensor_num);

					if($sensor_num > 1){
						if($thissensor->sensortype==""){
							$sensorstyle=" style=\"display:none;\"";
						}else{
							$sensorstyle="";
						}
					}
				?>

				<div class="rowdivv" id="dispvol_sensor_wrapper_<?php echo $sensor_num?>" <?php echo $sensorstyle?>>

					<?php if($sensor_num > 1){?>
					<div class="separator" style="margin-top:10px;margin-bottom:5px;"></div>
					<?php }?>

					<div style="float:left;width:600px;">
						<div class="rowdivv" style="white-space: nowrap;">
							Sensor Type: <?php echo $thissensor->sensortype?>
							<?php
								if($thissensor->sensortype=="Other"){
									$display = "inline";
								}else{
									$display = "none";
								}
							?>
							<span id="dispvol_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
								<?php echo $thissensor->othersensortype?>
							</span>
						</div>
						<div class="rowdivv">
							Sensor Location: <?php echo $thissensor->sensorlocation?>
							&nbsp;&nbsp;&nbsp;
							Calibration: <?php echo $thissensor->sensorcalibration?>
						</div>
						<div class="rowdivv">
							Sensor Notes:
							<?php echo $thissensor->sensornotes?>
						</div>
					</div>
					<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div>

					<?php
					if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/dispvolsensor_".$apparatus_pkey."_".$sensor_num)){
						$dzshow = "none";
						$imgshow = "inline";
					}else{
						$dzshow = "inline";
						$imgshow = "none";
					}
					?>

					<div style="float:left;display:<?php echo $dzshow?>;padding-top:60px;">N/A</div>
					<div style="text-align:center;float:left;vertical-align:middle;padding-top:60px;display:<?php echo $imgshow?>;">
						<a href="/sensor_calibration_file/dispvolsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
					</div>

					<div style="clear:left;"></div>
				</div>

				<?php
				}//end foreach
				?>

			</div>

			<div class="displacementbox" id="dispshear_wrapper"<?php echo $displacement_shear_display?>>
				<div class="rowheader">Shear</div>
				<div class="separator" style="margin-top:5px;">
				Sensor(s)
				</div>

				<?php
				$sensorstyle="";
				for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
					$thissensor = getSensor($j->displacement->shear->sensors, $sensor_num);

					if($sensor_num > 1){
						if($thissensor->sensortype==""){
							$sensorstyle=" style=\"display:none;\"";
						}else{
							$sensorstyle="";
						}
					}
				?>

				<div class="rowdivv" id="dispshear_sensor_wrapper_<?php echo $sensor_num?>" <?php echo $sensorstyle?>>

					<?php if($sensor_num > 1){?>
					<div class="separator" style="margin-top:10px;margin-bottom:5px;"></div>
					<?php }?>

					<div style="float:left;width:600px;">
						<div class="rowdivv" style="white-space: nowrap;">
							Sensor Type: <?php echo $thissensor->sensortype ?>
							<?php
								if($thissensor->sensortype=="Other"){
									$display = "inline";
								}else{
									$display = "none";
								}
							?>
							<span id="dispshear_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
								<?php echo $thissensor->othersensortype?>
							</span>
						</div>
						<div class="rowdivv">
							Sensor Location: <?php echo $thissensor->sensorlocation?>
							&nbsp;&nbsp;&nbsp;
							Calibration: <?php echo $thissensor->sensorcalibration?>
						</div>
						<div class="rowdivv">
							Sensor Notes:
							<?php echo $thissensor->sensornotes?>
						</div>
					</div>
					<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div>

					<?php
					if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/dispshearsensor_".$apparatus_pkey."_".$sensor_num)){
						$dzshow = "none";
						$imgshow = "inline";
					}else{
						$dzshow = "inline";
						$imgshow = "none";
					}
					?>

					<div style="float:left;display:<?php echo $dzshow?>;padding-top:60px;">N/A</div>
					<div style="text-align:center;float:left;vertical-align:middle;padding-top:60px;display:<?php echo $imgshow?>;">
						<a href="/sensor_calibration_file/dispshearsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
					</div>

					<div style="clear:left;"></div>
				</div>

				<?php
				}//end foreach
				?>

			</div>
		</div>

	<?php
	if($j->force != ""){
		$force_checked = " checked";
		$force_display = " style=\"display:block;\"";
	}else{
		$force_display = " style=\"display:none;\"";
	}
	?>

		<div class="checkheader"<?php echo $force_display?>>Force</div>
		<div class="checkbody" id="force_wrapper"<?php echo $force_display?>>

			<div class="rowdivv">
				Max Force:
				<?php echo $j->force->maxforce?>
				<?php echo $j->force->maxforceunit?>
			</div>

			<div class="separator" style="margin-top:5px;">
			Sensor(s)
			</div>

			<?php
			$sensorstyle="";
			for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
				$thissensor = getSensor($j->force->sensors, $sensor_num);

				if($sensor_num > 1){
					if($thissensor->sensortype==""){
						$sensorstyle=" style=\"display:none;\"";
					}else{
						$sensorstyle="";
					}
				}
			?>

			<div class="rowdivv" id="force_sensor_wrapper_<?php echo $sensor_num?>" <?php echo $sensorstyle?>>

				<?php if($sensor_num > 1){?>
				<div class="separator" style="margin-top:10px;margin-bottom:5px;"></div>
				<?php }?>

				<div style="float:left;width:600px;">
					<div class="rowdivv" style="white-space: nowrap;">
						Sensor Type: <?php echo $thissensor->sensortype ?>
						<?php
							if($thissensor->sensortype=="Other"){
								$display = "inline";
							}else{
								$display = "none";
							}
						?>
						<span id="force_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
							<?php echo $thissensor->othersensortype?>
						</span>
					</div>
					<div class="rowdivv">
						Sensor Location: <?php echo $thissensor->sensorlocation?>
						&nbsp;&nbsp;&nbsp;
						Calibration: <?php echo $thissensor->sensorcalibration?>
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<?php echo $thissensor->sensornotes?>
					</div>
				</div>
				<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div>

				<?php
				if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/forcesensor_".$apparatus_pkey."_".$sensor_num)){
					$dzshow = "none";
					$imgshow = "inline";
				}else{
					$dzshow = "inline";
					$imgshow = "none";
				}
				?>

				<div style="float:left;display:<?php echo $dzshow?>;padding-top:60px;">N/A</div>
				<div style="text-align:center;float:left;vertical-align:middle;padding-top:60px;display:<?php echo $imgshow?>;">
					<a href="/sensor_calibration_file/forcesensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
				</div>

				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

		</div>

	<?php
	if($j->torque != ""){
		$torque_checked = " checked";
		$torque_display = " style=\"display:block;\"";
	}else{
		$torque_display = " style=\"display:none;\"";
	}
	?>

		<div class="checkheader"<?php echo $torque_display?>>Torque</div>
		<div class="checkbody" id="torque_wrapper"<?php echo $torque_display?>>

			<div class="rowdivv">
				Max Torque:
				<?php echo $j->torque->maxtorque?> <?php echo $j->torque->maxtorqueunit?>
			</div>

			<div class="separator" style="margin-top:5px;">
			Sensor(s)
			</div>

			<?php
			$sensorstyle="";
			for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
				$thissensor = getSensor($j->torque->sensors, $sensor_num);

				if($sensor_num > 1){
					if($thissensor->sensortype==""){
						$sensorstyle=" style=\"display:none;\"";
					}else{
						$sensorstyle="";
					}
				}
			?>

			<div class="rowdivv" id="torque_sensor_wrapper_<?php echo $sensor_num?>" <?php echo $sensorstyle?>>

				<?php if($sensor_num > 1){?>
				<div class="separator" style="margin-top:10px;margin-bottom:5px;"></div>
				<?php }?>

				<div style="float:left;width:600px;">
					<div class="rowdivv">Torque Cell/Sensor Characteristics:</div>
					<div class="rowdivv" style="white-space: nowrap;">
						Geometry of Elastic Element: <?php echo $thissensor->geometryofelasticelement?>
						<?php
						if($thissensor->geometryofelasticelement=="Other"){
							$display="inline";
						}else{
							$display="none";
						}
						?>
						<span id="torque_othergeometryofelasticelementspan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
							<?php echo $thissensor->othergeometryofelasticelement?>
						</span>
					</div>
					<div class="rowdivv" style="white-space: nowrap;">
						Distortion Sensor Type: <?php echo $thissensor->sensortype?>
						<?php
						if($thissensor->sensortype=="Other"){
							$display="inline";
						}else{
							$display="none";
						}
						?>
						<span id="torque_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
							<?php echo $thissensor->othersensortype?>
						</span>
						<?php
						if($thissensor->sensortype=="Strain Gauges"){
							$display="inline";
						}else{
							$display="none";
						}
						?>
						<span id="torque_numberofstraingaugesspan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
							Number of Strain Gauges: <?php echo $thissensor->numberofstraingauges?>
						</span>
					</div>
					<div class="rowdivv">
						Sensor Location: <?php echo $thissensor->sensorlocation?>
						&nbsp;&nbsp;&nbsp;
						Calibration: <?php echo $thissensor->sensorcalibration?>
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<?php echo $thissensor->sensornotes?>
					</div>
				</div>
				<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div>

				<?php
				if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/torquesensor_".$apparatus_pkey."_".$sensor_num)){
					$dzshow = "none";
					$imgshow = "inline";
				}else{
					$dzshow = "inline";
					$imgshow = "none";
				}
				?>

				<div style="float:left;display:<?php echo $dzshow?>;padding-top:60px;">N/A</div>
				<div style="text-align:center;float:left;vertical-align:middle;padding-top:60px;display:<?php echo $imgshow?>;">
					<a href="/sensor_calibration_file/torquesensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
				</div>

				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

		</div>

	<?php
	if($j->pore != ""){
		$pore_checked = " checked";
		$pore_display = " style=\"display:block;\"";
	}else{
		$pore_display = " style=\"display:none;\"";
	}
	?>

		<div class="checkheader"<?php echo $pore_display?>>Pore Fluids</div>
		<div class="checkbody" id="porefluids_wrapper"<?php echo $pore_display?>>
			<div class="rowdivv">
				<input type="checkbox" disabled id="pore_undrainedonly"<?php if($j->pore->undrainedonly!=""){echo " checked";}?>> Undrained Only
				&nbsp;&nbsp;&nbsp;<input type="checkbox" disabled id="pore_measured"<?php if($j->pore->measured!=""){echo " checked";}?>> Measured
				&nbsp;&nbsp;&nbsp;<input type="checkbox" disabled id="pore_servocontrolled"<?php if($j->pore->servocontrolled!=""){echo " checked";}?>> Servo-controlled
				&nbsp;&nbsp;&nbsp;<input type="checkbox" disabled id="pore_flowthrough"<?php if($j->pore->flowthrough!=""){echo " checked";}?>> Flowthrough
			</div>
			<div class="rowdivv">
				Min Pore Fluid Pressure:
				<?php echo $j->pore->minporefluidpressure?> <?php echo $j->pore->minporefluidpressureunit?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max Pore Fluid Pressure:
				<?php echo $j->pore->maxporefluidpressure?> <?php echo $j->pore->maxporefluidpressureunit?>
			</div>

			<div class="separator" style="margin-top:5px;">
			Sensor(s)
			</div>

			<?php
			$sensorstyle="";
			for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
				$thissensor = getSensor($j->pore->sensors, $sensor_num);

				if($sensor_num > 1){
					if($thissensor->sensortype==""){
						$sensorstyle=" style=\"display:none;\"";
					}else{
						$sensorstyle="";
					}
				}
			?>

			<div class="rowdivv" id="pore_sensor_wrapper_<?php echo $sensor_num?>" <?php echo $sensorstyle?>>

				<?php if($sensor_num > 1){?>
				<div class="separator" style="margin-top:10px;margin-bottom:5px;"></div>
				<?php }?>

				<div style="float:left;width:600px;">
					<div class="rowdivv" style="white-space: nowrap;">
						Sensor Type: <?php echo $thissensor->sensortype?>
						<?php
						if($thissensor->sensortype=="Other"){
							$display="inline";
						}else{
							$display="none";
						}
						?>
						<span id="pore_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
							<?php echo $thissensor->othersensortype?>
						</span>
					</div>
					<div class="rowdivv">
						Sensor Location: <?php echo $thissensor->sensorlocation?>
						&nbsp;&nbsp;&nbsp;
						Calibration: <?php echo $thissensor->sensorcalibration?>
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<?php echo $thissensor->sensornotes?>
					</div>
				</div>
				<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div>

				<?php
				if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/poresensor_".$apparatus_pkey."_".$sensor_num)){
					$dzshow = "none";
					$imgshow = "inline";
				}else{
					$dzshow = "inline";
					$imgshow = "none";
				}
				?>

				<div style="float:left;display:<?php echo $dzshow?>;padding-top:60px;">N/A</div>
				<div style="text-align:center;float:left;vertical-align:middle;padding-top:60px;display:<?php echo $imgshow?>;">
					<a href="/sensor_calibration_file/poresensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
				</div>

				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

		</div>

	<?php
	if($j->acoustic != ""){
		$acoustic_checked = " checked";
		$acoustic_display = " style=\"display:block;\"";
	}else{
		$acoustic_display = " style=\"display:none;\"";
	}
	?>

		<div class="checkheader"<?php echo $acoustic_display?>>Acoustic Emissions</div>
		<div class="checkbody" id="acousticemissions_wrapper"<?php echo $acoustic_display?>>
			<div class="rowdivv">
				<input type="checkbox" disabled id="acoustic_continuousstreaming"<?php if($j->acoustic->continuousstreaming != ""){echo " checked";}?>> Continuous Streaming
				&nbsp;&nbsp;&nbsp;<input type="checkbox" disabled id="acoustic_triggeredrecording"<?php if($j->acoustic->triggeredrecording != ""){echo " checked";}?>> Triggered Recording
				&nbsp;&nbsp;&nbsp;<input type="checkbox" disabled id="acoustic_momentanalysiscalibratedamplitude"<?php if($j->acoustic->momentanalysiscalibratedamplitude != ""){echo " checked";}?>> Moment analysis Calibrated Amplitude
			</div>
			<div class="rowdivv">
				<input type="checkbox" disabled id="acoustic_transmissivityreflectivity"<?php if($j->acoustic->transmissivityreflectivity != ""){echo " checked";}?>> Acoustic transmissivity / reflectivity
				&nbsp;&nbsp;&nbsp;<input type="checkbox" disabled id="acoustic_aecount"<?php if($j->acoustic->aecount != ""){echo " checked";}?>> AE Count
				&nbsp;&nbsp;&nbsp;<input type="checkbox" disabled id="acoustic_tomography"<?php if($j->acoustic->tomography != ""){echo " checked";}?>> Tomography
			</div>
			<div class="rowdivv">
				<input type="checkbox" disabled id="acoustic_momentanalysisrelativeamplitude"<?php if($j->acoustic->momentanalysisrelativeamplitude != ""){echo " checked";}?>> Moment analysis relative amplitude
			</div>

			<div class="rowdivv">
				Frequency Range:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Min:
				<?php echo $j->acoustic->minfrequencyrange?> <?php echo $j->acoustic->minfrequencyrangeunit?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max:
				<?php echo $j->acoustic->maxfrequencyrange?> <?php echo $j->acoustic->maxfrequencyrangeunit?>
			</div>
			<div class="rowdivv">
				Sensor Notes:
				<?php echo $j->acoustic->sensornotes?>
			</div>

		</div>

	<?php
	if($j->elastic != ""){
		$elastic_checked = " checked";
		$elastic_display = " style=\"display:block;\"";
	}else{
		$elastic_display = " style=\"display:none;\"";
	}
	?>

		<div class="checkheader"<?php echo $elastic_display?>>In-situ Elastic Wave Velocities</div>
		<div class="checkbody" id="elasticwave_wrapper"<?php echo $elastic_display?>>
			<div class="rowdivv">
				<input type="checkbox" disabled id="elastic_piezoelectricsensors"<?php if($j->elastic->piezoelectricsensors != ""){echo " checked";}?>> Piezoelectric Sensors
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Piezoelectric Transducer Type: <?php echo $j->elastic->piezoelectricyransduceryype?>
			</div>
			<div class="rowdivv">
				<input type="checkbox" disabled id="elastic_pwave"<?php if($j->elastic->pwave != ""){echo " checked";}?>> P-Wave
				&nbsp;&nbsp;&nbsp;<input type="checkbox" disabled id="elastic_swave"<?php if($j->elastic->swave != ""){echo " checked";}?>> S-Wave
				&nbsp;&nbsp;&nbsp;<input type="checkbox" disabled id="elastic_s1wave"<?php if($j->elastic->s1wave != ""){echo " checked";}?>> S1-Wave
				&nbsp;&nbsp;&nbsp;<input type="checkbox" disabled id="elastic_s2wave"<?php if($j->elastic->s2wave != ""){echo " checked";}?>> S2-Wave
			</div>
			<div class="rowdivv">
				Frequency Range:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Min:
				<?php echo $j->elastic->minfrequencyrange?> <?php echo $j->elastic->minfrequencyrangeunit?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max:
				<?php echo $j->elastic->maxfrequencyrange?> <?php echo $j->elastic->maxfrequencyrangeunit?>
			</div>
			<div class="rowdivv">
				Sensor Notes:
				<?php echo $j->elastic->sensornotes?>
			</div>

		</div>

	<?php
	if($j->electrical != ""){
		$electrical_checked = " checked";
		$electrical_display = " style=\"display:block;\"";
	}else{
		$electrical_display = " style=\"display:none;\"";
	}
	?>

		<div class="checkheader"<?php echo $electrical_display?>>Electrical Conductivity</div>
		<div class="checkbody" id="electricalconductivity_wrapper"<?php echo $electrical_display?>>

			<div class="rowdivv">
				<div style="float:left;width:600px;">
					<div class="rowdivv">
						<input type="checkbox" disabled id="electrical_frequencydependent"<?php if($j->electrical->frequencydependent != ""){echo " checked";}?>> Frequency Dependent
					</div>
					<div class="rowdivv">
						Amplitude of Test Pulse: <?php echo $j->electrical->amplitude?>
					</div>
					<div class="rowdivv">
						Electrode Type: <?php echo $j->electrical->electrodetype?>
						<?php
						if($j->electrical->electrodetype == "Other Electrode"){
							$display="inline";
						}else{
							$display="none";
						}
						?>
						<?php echo $j->electrical->otherelectrodetype?>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Num Electrodes: <?php echo $j->electrical->numberofelectrodes?>
					</div>

					<div class="rowdivv">
						Calibration: <?php echo $j->electrical->sensorcalibration?>
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<?php echo $j->electrical->sensornotes?>
					</div>
				</div>
				<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div>

				<?php
				if(file_exists($_SERVER['DOCUMENT_ROOT']."/expimages/electrical_".$apparatus_pkey)){
					$dzshow = "none";
					$imgshow = "inline";
				}else{
					$dzshow = "inline";
					$imgshow = "none";
				}
				?>

				<div style="float:left;display:<?php echo $dzshow?>;padding-top:60px;">N/A</div>
				<div style="text-align:center;float:left;vertical-align:middle;padding-top:60px;display:<?php echo $imgshow?>;">
					<a href="/sensor_calibration_file/electrical/<?php echo $apparatus_pkey?>">Download</a><br>
				</div>

				<div style="clear:left;"></div>
			</div>
		</div>
	</div>

	<div class="rowdiv rowheader" style="padding-top:25px;">General Apparatus Description and Notes</div>
	<div class="notesbox" style="text-align:center;margin-left:30px;margin-right:30px;">
		<textarea readonly id="apparatus_description_notes" rows="10" style="width:100%;"><?php echo $j->apparatusdescriptionnotes?></textarea>
	</div>

	<!--</form>-->

</div>

<script src="/assets/js/apparatus/add_apparatus_<?php echo $apparatus_pkey?>.js"></script>
<script src="/addtest.js"></script>

<script type='text/javascript'>

</script>

<div id="errormodal" class="modal">
  <p class="errorbar">Error!</p>
  <p id="errors"></p>
</div>

<?php
include 'includes/footer.php';
?>
