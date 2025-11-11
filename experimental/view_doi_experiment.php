<?php
/**
 * File: view_doi_experiment.php
 * Description: Displays Digital Object Identifier (DOI) information and metadata
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

//include("../logincheck.php");
include("../prepare_connections.php");

$project_uuid = isset($_GET['p']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', $_GET['p']) : '';
if($project_uuid == "") die("Project not found.");
$row = $db->get_row_prepared("SELECT * FROM dois WHERE uuid = $1", array($project_uuid));
if($row->pkey == "") die("Project not found.");

$experiment_uuid = isset($_GET['e']) ? preg_replace('/[^a-zA-Z0-9\-]/', '', $_GET['e']) : '';
if($experiment_uuid == "") die("Experiment not found.");

//Get Experiment from File
$json = file_get_contents("/srv/app/www/doi/doiFiles/$project_uuid/project.json");
$json = json_decode($json);

$expfound = false;
$experiments = $json->experiments;
foreach($experiments as $e){
	if($e->metadata->uuid == $experiment_uuid){
		$expfound = true;
		$json = $e;
	}
}

if(!$expfound) die("Experiment not found.");




$facility = $json->facility;
$apparatus = $json->apparatus;
$daq = $json->daq;
$sample = $json->sample;
$experiment = $json->experiment;
$dataData = $json->data;
$datasetid = $json->metadata->name;



include("../includes/header.php");
?>
<link rel="stylesheet" href="/experimental/experimental.css" type="text/css" />
<script src='/experimental/experimental.js'></script>
<script src='/assets/js/jquery/jquery.min.js'></script>

<div style="display:none;">
	<input type="text" id="experimentPkey" value="<?php echo $experiment_pkey?>">
	<input type="text" id="experimentUUID" value="">
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

<div id="loadingExperimentBox" style="display:none;">
	<div id="grayOut"></div>
	<div id="loadingExperimentMessage">
		<div><image src="/assets/js/images/box.gif"/> Loading Experiment...</div>
	</div>
</div>

<div style="margin-top:-30px; text-align:right;margin-right:100px;">
	<button id="downloadButton" class="downloadButton" onclick="downloadProject();">
		<img id="downloadImage" title="Download Project JSON" src="/experimental/buttonImages/empty.png" width="40" height="40">
	</button>
</div>

<div class="topTitle">Experiment: <?php echo $datasetid?></div>

<div style="display:none;"><input type="text" id="mainExperimentId" value="<?php echo $row->id?>"></input></div>

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
		<div id="apparatusList"></div>
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
		<div class="topTitle" style="padding-top:10px;">Download Project</div>
		<div>
			<fieldset class="mainFS">
				<legend class="mainFSLegend">Project JSON</legend>
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
							<label class="formLabel">Facility Name </label>
							<div class="formControl"><?php echo $facility->name?></div>
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel">Facility Type </label>
							<div class="formControl"><?php echo $facility->type?></div>
							<?php
							if($facility->other_type != ""){
							?>
							<div class="formControl"><?php echo $facility->other_type?></div>
							<?php
							}
							?>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">Facility ID</label>
							<div class="formControl"><?php echo $facility->id?></div>
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel">Facility Website</label>
							<div class="formControl"><a href="<?php echo $facility->website?>" target="_blank"><?php echo $facility->website?></a></div>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel">Institute Name </label>
							<div class="formControl"><?php echo $facility->institute?></div>
						</div>
					</div>
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel">Department</label>
							<div class="formControl"><?php echo $facility->department?></div>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w100">
						<div class="formPart">
							<label class="formLabel">Description</label>
							<div class="formControl"><?php echo $facility->description?></div>
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
							<div class="formControl"><?php echo $facility->address->street?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Building/Apartment</label>
							<div class="formControl"><?php echo $facility->address->building?></div>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">Postal Code</label>
							<div class="formControl"><?php echo $facility->address->postcode?></div>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">City</label>
							<div class="formControl"><?php echo $facility->address->city?></div>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">State</label>
							<div class="formControl"><?php echo $facility->address->state?></div>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">Country</label>
							<div class="formControl"><?php echo $facility->address->country?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Latitude (decimal degrees)</label>
							<div class="formControl"><?php echo $facility->address->latitude?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Longitude (decimal degrees)</label>
							<div class="formControl"><?php echo $facility->address->longitude?></div>
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
							<div class="formControl"><?php echo $facility->contact->firstname?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Last Name</label>
							<div class="formControl"><?php echo $facility->contact->lastname?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Affiliation</label>
							<div class="formControl"><?php echo $facility->contact->affiliation?></div>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Email</label>
							<div class="formControl"><?php echo $facility->contact->email?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Phone</label>
							<div class="formControl"><?php echo $facility->contact->phone?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Website</label>
							<div class="formControl"><a href="<?php echo $facility->contact->website?>" target="_blank"><?php echo $facility->contact->website?></a></div>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">ORCID ID</label>
							<div class="formControl"><?php echo $facility->contact->id?></div>
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
					<label class="formLabel">Apparatus Name </label>
					<div class="formControl"><?php echo $apparatus->name?></div>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Apparatus Type </label>
					<div class="formControl"><?php echo $apparatus->type?></div>
					<?php
					if($apparatus->other_type != ""){
					?>
					<div class="formControl"><?php echo $apparatus->other_type?></div>
					<?php
					}
					?>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Location</label>
					<div class="formControl"><?php echo $apparatus->location?></div>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Apparatus ID</label>
					<div class="formControl"><?php echo $apparatus->id?></div>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel">Description</label>
					<div class="formControl"><?php echo $apparatus->description?></div>
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
					<?php
					$features = implode(", ", $apparatus->features);
					?>
					<div class="formControl"><?php echo $features?></div>
				</div>
			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Apparatus Parameters</legend>
		<div class="subDataHolder" id="paramsSubDiv">
			<?php
			if($apparatus->parameters != ""){
				$apparatus_show = "inline";
			}else{
				$apparatus_show = "none";
			}
			?>
			<table class="subDataTable" id="paramsTable" style="display:<?php echo $apparatus_show?>;">
				<tbody><tr data-isheader="true">
					<th>Name</th>
					<th>Minimum</th>
					<th>Maximum</th>
					<th>Unit</th>
					<th>Prefix</th>
					<th>Detail/Note</th>
				</tr>

			<?php
			foreach($apparatus->parameters as $p){
			?>
			<tr>
			<td><?php echo $p->type?></td>
			<td><?php echo $p->min?></td>
			<td><?php echo $p->max?></td>
			<td><?php echo $p->unit?></td>
			<td><?php echo $p->prefix?></td>
			<td><?php echo $p->note?></td>
			</tr>
			<?php
			}
			?>

			</tbody></table>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Apparatus Documents</legend>
		<div id="docsWrapper">
			<div class="subDataHolder" id="documentRows">

			<?php
			if($apparatus->documents != ""){
				foreach($apparatus->documents as $d){
			?>


				<div class="docRow" id="">
					<div class="appDocCell" style="width:1140px;">
						<div class="formRow">
							<div class="formCell 16">
								<div class="formPart">
									<label class="formLabel">Document Type </label>
									<div class="formControl"><?php echo $d->type?></div>
									<?php
									if($d->other_type != ""){
									?>
									<div class="formControl"><?php echo $d->other_type?></div>
									<?php
									}
									?>
								</div>
							</div>
							<div class="formCell w16">
								<div class="formPart">
									<label class="formLabel">Document Format </label>
									<div class="formControl"><?php echo $d->format?></div>
									<?php
									if($d->other_format != ""){
									?>
									<div class="formControl"><?php echo $d->other_format?></div>
									<?php
									}
									?>
								</div>
							</div>
							<div class="formCell w50">
								<div class="formPart" id="fileHolder">
									<label class="formLabel">File </label>
									<div class="formControl"><a href="<?php echo $d->path?>" target="_blank"><?php echo $d->path?></a></div>
								</div>
							</div>
							<div class="formCell w16">
								<div class="formPart">
									<label class="formLabel">Document ID</label>
									<div class="formControl"><?php echo $d->id?></div>
								</div>
							</div>
						</div>
						<div class="formRow">
							<div class="formCell w100">
								<div class="formPart">
									<label class="formLabel">Description</label>
									<div class="formControl"><?php echo $d->description?></div>
								</div>
							</div>
						</div>
					</div>
					<div class="docCell" style="width:25px;display:none;">
						<div style="padding-top:10px;"><button class="squareButton squareButtonBottom"><img src="/experimental/buttonImages/icons8-trash-30.png" height="20" width="20"></button></div>
						<div><button class="squareButton squareButtonBottom"><img src="/experimental/buttonImages/icons8-up-30.png" height="20" width="20"></button></div>
						<div><button class="squareButton squareButtonBottom"><img src="/experimental/buttonImages/icons8-down-30.png" height="20" width="20"></button></div>
					</div>
					<div class="clearLeft"></div>
				</div>


			<?php
				}
			}
			?>




			</div>
		</div>
		<div style="padding-left:5px;display:none;"><button class="smallerButton" style="vertical-align:middle" onclick="addApparatusDocument();"><span>Add Document </span></button></div>
	</fieldset>
	<div class="fsSpacer"></div>

	<div style="display:none;">
		<div class="docRow" id="sourceApparatusDocumentRow">
			<div class="appDocCell" style="width:1140px;">
				<div class="formRow">
					<div class="formCell 16">
						<div class="formPart">
							<label class="formLabel">Document Type </label>
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
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">Document Format </label>
							<select class="formControl formSelect" id="docFormat">
								<option value="jpg">jpg</option>
								<option value="png">png</option>
								<option value="txt">txt</option>
								<option value="csv">csv</option>
								<option value="zip">zip</option>
								<option value="rar">rar</option>
								<option value="pdf">pdf</option>
								<option value="docx">docx</option>
							</select>
						</div>
					</div>
					<div class="formCell w50">
						<div class="formPart" id="fileHolder">
							<label class="formLabel">Choose File </label>
							<input type="file" id="docFile" class="formControl">
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">Document ID</label>
							<input id="documentId" class="formControl" type="text" value="">
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




			<div style="text-align:center;display:none;">
				<button class="submitButton" style="vertical-align:middle;margin-right:150px;" onclick="exper_doCancelFacilityApparatusEdit();"><span>Cancel </span></button>
				<button class="submitButton" style="vertical-align:middle" onclick="exper_doSaveFacilityApparatusInfo()"><span>Save </span></button>

			</div>









		</div>
	</div>
</div>











<!-- DAQ Modal -->
<div id="daqModal" style="display:none;">
	<div class="grayOut"></div>
	<div class="redCloseBox" onclick="exper_CloseDAQModal();">X</div>
	<div class="modalBox" id="daqModalBox">
		<div class="topTitle" style="padding-top:10px;">DAQ Info</div>
		<div>
			<fieldset class="mainFS">
					<legend class="mainFSLegend">DAQ Info</legend>
					<div class="formRow">
						<div class="formCell w50">
							<div class="formPart">
								<label class="formLabel">DAQ Group Name </label>
								<div class="formControl"><?php echo $daq->name?></div>
							</div>
						</div>
						<div class="formCell w33">
							<div class="formPart">
								<label class="formLabel">DAQ Type </label>
								<div class="formControl"><?php echo $daq->type?></div>
							</div>
						</div>
						<div class="formCell w16">
							<div class="formPart">
								<label class="formLabel">Location</label>
								<div class="formControl"><?php echo $daq->location?></div>
							</div>
						</div>
					</div>
					<div class="formRow">
						<div class="formCell w100">
							<div class="formPart">
								<label class="formLabel">Description</label>
								<div class="formControl"><?php echo $daq->description?></div>
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
							<?php
							if($daq->devices != ""){
								if(count($daq->devices) > 0){
									$daqNum = 0;
									foreach($daq->devices as $dd){
							?>

							<div class="deviceRow" id="daq_device_<?php echo $daqNum?>">
								<div class="formCell" style="width:1140px;padding-left:0px !important;">
									<div class="formRow">
										<div class="formCell w100">
											<div class="formPart">
												<label class="formLabel">Device Name </label>
												<div class="formControl"><?php echo $dd->name?></div>
											</div>
										</div>
									</div>
									<div class="formRow">
										<div class="formCell w100">
											<div class="formPart">

												<!-- Channels -->
												<fieldset class="mainFS" style="margin-top:10px">
													<legend class="subFSLegend">Device Channels <button id="addChannelButton" class="fsButton" style="vertical-align:middle;display:none;" onclick="exper_addDAQDeviceChannel();"><span>Add Channel </span></button></legend>
													<div>
														<div class="subDataHolder" style="padding-left:0px !important;">

														<?php
														if($dd->channels != ""){
														?>
															<!-- Channels Here -->
															<div style="float:left;">
																<div id="daq_device_channel_buttons_<?php echo $daqNum?>">
																	<!-- buttons here -->
																	<?php
																	$chNum = 0;
																	foreach($dd->channels as $ch){

																		$headerString = $ch->header->type;
																		$otherHeaderString = $ch->header->other_type;
																		$channelString = $ch->number;
																		$unitString = $ch->header->unit;

																		if($headerString == "Other"){
																			if($otherHeaderString != "") $headerString = $otherHeaderString;
																		}

																		if($headerString == "Time" && $channelString == ""){
																			if($unitString != ""){
																				$newButtonString = $headerString . " - " . $unitString;
																			}elseif($channelString != ""){
																				$newButtonString = $channelString . " - " . $headerString;
																			}else{
																				$newButtonString = $headerString;
																			}
																		}else{
																			if($channelString != ""){
																				$newButtonString = $channelString . " - " . $headerString;
																			}else{
																				$newButtonString = $headerString;
																			}
																		}

																	?>
																	<button id="daq_device_channel_button_<?php echo $daqNum?>_<?php echo $chNum?>" class="sideBarButton daq_device_channel_button_group_<?php echo $daqNum?> straboRedSelectedButton" style="vertical-align:middle" onclick="exper_daqSwitchToChannel(<?php echo $daqNum?>, <?php echo $chNum?>);"><span><?php echo $newButtonString?></span></button>
																	<?php
																		$chNum++;
																	}
																	?>
																</div>
															</div>
															<div style="float:left;padding-left:5px;">
																<div id="daq_device_channels_<?php echo $daqNum?>">
																	<!-- each channel in here -->
																	<?php
																	$chNum = 0;
																	foreach($dd->channels as $ch){
																	?>










	<div class="deviceRow" style="margin-top:5px;width:960px;" id="daq_device_channel_<?php echo $daqNum?>_<?php echo $chNum?>">
		<div class="formCell" style="width:890px;padding-left:0px !important;">
			<div class="formRow">
				<div class="formCell w50">
					<div class="formPart">
						<label class="formLabel midHeader">Channel Header</label>
						<div class="formControl"><?php echo $ch->header->type?></div>
					</div>
				</div>
				<?php
				if($ch->header->other_type != ""){
				?>
				<div class="formCell w50">
					<div class="formPart" id="otherChannelHeaderHolder" style="display:inline;">
						<label class="formLabel midHeader">&nbsp;</label>
						<div class="formControl"><?php echo $ch->header->other_type?></div>
					</div>
				</div>
				<?php
				}
				?>
			</div>
			<div class="formRow">
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Specifier A</label>
						<div class="formControl"><?php echo $ch->header->spec_a?></div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Specifier B</label>
						<div class="formControl"><?php echo $ch->header->spec_b?></div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Other Specifier</label>
						<div class="formControl"><?php echo $ch->header->spec_c?></div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Unit</label>
						<div class="formControl"><?php echo $ch->header->unit?></div>
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
						<label class="formLabel">Channel #</label>
						<div class="formControl"><?php echo $ch->number?></div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Type</label>
						<div class="formControl"><?php echo $ch->type?></div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Configuration</label>
						<div class="formControl"><?php echo $ch->configuration?></div>
					</div>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel">Note</label>
						<div class="formControl"><?php echo $ch->note?></div>
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel">Res [bit]</label>
						<div class="formControl"><?php echo $ch->resolution?></div>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel">Min</label>
						<div class="formControl"><?php echo $ch->range_low?></div>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel">Max</label>
						<div class="formControl"><?php echo $ch->range_high?></div>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel">Rate</label>
						<div class="formControl"><?php echo $ch->rate?></div>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel">Filter</label>
						<div class="formControl"><?php echo $ch->filter?></div>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel">Gain</label>
						<div class="formControl"><?php echo $ch->gain?></div>
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
						<label class="formLabel">Sensor/Actuator</label>
						<div class="formControl"><?php echo $ch->sensor->detail?></div>
					</div>
				</div>
				<div class="formCell w66">
					<div class="formPart">
						<label class="formLabel">IEEE Sensor Template</label>
						<div class="formControl"><?php echo $ch->sensor->template?></div>
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel">Type</label>
						<div class="formControl"><?php echo $ch->sensor->type?></div>
					</div>
				</div>
				<div class="formCell w50">
					<div class="formPart">
						<label class="formLabel">Manufacturer ID</label>
						<div class="formControl"><?php echo $ch->sensor->id?></div>
					</div>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel">Model #</label>
						<div class="formControl"><?php echo $ch->sensor->model?></div>
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel">Version Letter</label>
						<div class="formControl"><?php echo $ch->sensor->version?></div>
					</div>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel">Version #</label>
						<div class="formControl"><?php echo $ch->sensor->number?></div>
					</div>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel">Serial #</label>
						<div class="formControl"><?php echo $ch->sensor->serial?></div>
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
						<label class="formLabel">Template</label>
						<div class="formControl"><?php echo $ch->calibration->template?></div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Input</label>
						<div class="formControl"><?php echo $ch->calibration->input?></div>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel">Unit</label>
						<div class="formControl"><?php echo $ch->calibration->unit?></div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Excitation</label>
						<div class="formControl"><?php echo $ch->calibration->excitation?></div>
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w40">
					<div class="formPart">
						<label class="formLabel">Date</label>
						<div class="formControl"><?php echo $ch->calibration->date?></div>
					</div>
				</div>
				<div class="formCell w60">
					<div class="formPart">
						<label class="formLabel">Note</label>
						<div class="formControl"><?php echo $ch->calibration->note?></div>
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell w100">
					<div class="formPart">
						<fieldset class="mainFS">
							<legend class="subFSLegend">Data <button class="fsButton" style="vertical-align:middle;display:none;" onclick="addDAQDeviceChannel();"><span>Add Data </span></button></legend>
							<div>
								<div class="subDataHolder" id="daqDeviceDataRows">
									<!-- Datas Here -->
									<?php
									if($ch->data != "" && count($ch->data)>0 ){
										$dataNum = 0;
										foreach($ch->data as $data){
									?>
										<div class="formRow" id="daq_device_channel_data_0_0_0">
											<div class="formLabel">
												<div class="dataLeft marginTop">A: <span class="formControlData"><?php echo $data->a?></span></div>
												<div class="dataLeft marginTop">B: <span class="formControlData"><?php echo $data->b?></span></div>
												<div style="display:none;">
												<button id="deleteButton" class="squareButton" style="margin-left:20px;" onclick="exper_deleteDAQDeviceChannelData(0, 0, 0);"></button>
												<button id="upButton" class="squareButton" onclick="exper_moveDAQDeviceChannelDataUp(0, 0, 0);" style="display: none;"></button>
												<button id="downButton" class="squareButton" onclick="exper_moveDAQDeviceChannelDataDown(0, 0, 0);" style="display: inline;"></button>
												</div>
											</div>
										</div>
									<?php
											$dataNum++;
										}
									}else{
									?>
									No Data.
									<?php
									}
									?>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
			</div>
		</div>
		<div class="docCell" style="width:25px;display:none;">
			<div style="padding-top:10px;"><button id="deleteButton" class="squareButton squareButtonBottom" onclick="alert('delete');"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
			<div><button id="upButton" class="squareButton squareButtonBottom" onclick="alert('up');" style="display: inline;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
			<div><button id="downButton" class="squareButton squareButtonBottom" onclick="alert('down');" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
		</div>
		<div style="clear:both;"></div>
	</div>










																	<?php
																		$chNum++;
																	}
																	?>
																</div>
															</div>
															<div style="clear:both;"></div>
														<?php
														}else{
														?>
														No channels.
														<?php
														}
														?>

														</div>
													</div>
												</fieldset>
												<!-- Documents -->
												<fieldset class="mainFS" style="margin-top:10px">
													<legend class="subFSLegend">Device Documents <button id="addDocumentButton" class="fsButton" style="vertical-align:middle;display:none;" onclick="exper_addDAQDeviceDocument();"><span>Add Document </span></button></legend>
													<div>
														<div class="subDataHolder" style="padding-left:0px !important;">


														<?php
														if($dd->documents != ""){
														?>


															<!-- Documents Here -->
															<div style="float:left;">
																<div id="daq_device_document_buttons_<?php echo $daqNum?>">
																	<!-- buttons here -->

																	<?php
																	$docNum = 0;
																	foreach($dd->documents as $doc){
																	?>
																	<button id="daq_device_document_button_<?php echo $daqNum?>_<?php echo $docNum?>" class="sideBarButton daq_device_document_button_group_<?php echo $daqNum?> straboRedSelectedButton" style="vertical-align:middle" onclick="exper_daqSwitchToDocument(<?php echo $daqNum?>, <?php echo $chNum?>);"><span><?php echo $doc->type?></span></button>
																	<?php
																		$docNum++;
																	}
																	?>

																</div>
															</div>
															<div style="float:left;padding-left:5px;">
																<div id="daq_device_documents_<?php echo $daqNum?>">
																	<!-- each document in here -->

																	<?php
																	$docNum = 0;
																	foreach($dd->documents as $doc){
																	?>

<div class="deviceRow daq_device_document_group_<?php echo $daqNum?>" id="daq_device_document_<?php echo $daqNum?>_<?php echo $docNum?>" style="margin-top: 5px; width: 960px !important; display: block;">
	<div class="formCell" style="width:900px;padding-left:0px !important;">
		<div class="formRow">
			<div class="formCell 16">
				<div class="formPart">
					<label class="formLabel">Document Type </label>
					<div class="formControl"><?php echo $doc->type?></div>
					<?php
					if($doc->other_type != ""){
					?>
					<div class="formControl"><?php echo $doc->other_type?></div>
					<?php
					}
					?>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Document Format </label>
					<div class="formControl"><?php echo $doc->format?></div>
					<?php
					if($doc->other_format != ""){
					?>
					<div class="formControl"><?php echo $doc->other_format?></div>
					<?php
					}
					?>
				</div>
			</div>
			<div class="formCell w50">
				<div class="formPart" id="fileHolder">
					<label class="formLabel">File</label>
					<div class="formControl"><a href="<?php echo $doc->path?>" target="_blank"><?php echo $doc->path?></a></div>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Document ID</label>
					<div class="formControl"><?php echo $doc->id?></div>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel">Description</label>
					<div class="formControl"><?php echo $doc->description?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="docCell" style="width:25px;display:none;">
		<div style="padding-top:10px;"><button id="deleteButton" class="squareButton squareButtonBottom" onclick="exper_deleteDAQDeviceDocument(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
		<div><button id="upButton" class="squareButton squareButtonBottom" onclick="exper_moveDAQDeviceDocumentUp(0, 0);" style="display: none;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
		<div><button id="downButton" class="squareButton squareButtonBottom" onclick="exper_moveDAQDeviceDocumentDown(0, 0);" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
	</div>
	<div style="clear:both;"></div>
</div>

																	<?php
																		$docNum++;
																	}
																	?>

																</div>
															</div>
															<div style="clear:both;"></div>

														<?php
														}else{
														?>

														No Documents.

														<?php
														}
														?>

														</div>
													</div>
												</fieldset>
											</div>
										</div>
										<div style="clear:both;"></div>
									</div>
								</div>
								<div class="docCell" style="width:25px;display:none;">
									<div style="padding-top:10px;"><button id="deleteButton" class="squareButton squareButtonBottom" onclick="deleteApparatusDocument(1);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
									<div><button id="upButton" class="squareButton squareButtonBottom" onclick="moveApparatusDocumentUp(1);" style="display: inline;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
									<div><button id="downButton" class="squareButton squareButtonBottom" onclick="moveApparatusDocumentDown(1);" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
								</div>
								<div style="clear:both;"></div>
							</div>

							<?php
										$daqNum++;
									}
								}
							}else{
							?>
							No devices.
							<?php
							}
							?>

						</div>
					</div>
				</fieldset>
				<div class="fsSpacer"></div>
				<div style="text-align:center;"><button class="submitButton" style="vertical-align:middle;display:none;" onclick="exper_doSaveDAQInfo()"><span>Save </span></button></div>
		</div>
	</div>
</div>

<!-- Side Button -->
<div style="display:none;">
	<div><button id="sourceSideBarButton" class="sideBarButton" style="vertical-align:middle"><span>Channel Num</span></button></div>
</div>

<!-- Sample Modal -->
<div id="sampleModal" style="display:none;">
	<div class="grayOut"></div>
	<div class="redCloseBox" onclick="exper_CloseSampleModal();">X</div>
	<div class="modalBox" id="sampleModalBox">
	<div id="sourceSampleModal">
		<div class="topTitle" style="padding-top:10px;">Sample Info</div>
		<div>
			<fieldset class="mainFS" style="padding-top: 0px !important;">
				<legend class="mainFSLegend">Sample Info</legend>
				<div class="formRow">
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel">Sample Name </label>
							<div class="formControl"><?php echo $sample->name?></div>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">IGSN </label>
							<div class="formControl"><?php echo $sample->igsn?></div>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">Sample ID </label>
							<div class="formControl"><?php echo $sample->id?></div>
						</div>
					</div>
					<div class="formCell w40">
						<div class="formPart">
							<label class="formLabel">Description</label>
							<div class="formControl"><?php echo $sample->description?></div>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w25" style="padding-left:20px;">
						<div class="formPart">
							<label class="formLabel">Parent Sample Name</label>
							<div class="formControl"><?php echo $sample->parent->name?></div>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">Parent IGSN </label>
							<div class="formControl"><?php echo $sample->parent->igsn?></div>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">Parent Sample ID</label>
							<div class="formControl"><?php echo $sample->parent->id?></div>
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel">Parent Description</label>
							<div class="formControl"><?php echo $sample->parent->description?></div>
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
							<label class="formLabel">Material Type </label>
							<div class="formControl"><?php echo $sample->material->material->type?></div>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel"><span id="materialNameLabel" >Name</span> </label>
							<div id="materialNameHolder">
								<div class="formControl"><?php echo $sample->material->material->name?></div>
							</div>
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel">State </label>
							<div class="formControl"><?php echo $sample->material->material->state?></div>
						</div>
					</div>
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel">Note</label>
							<div class="formControl"><?php echo $sample->material->material->note?></div>
						</div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<!-- Mineralogy -->
			<fieldset class="mainFS" style="margin-top:10px">
				<legend class="mainFSLegend">Mineralogy <button class="fsButton" style="vertical-align:middle;display:none;" onclick="exper_addSampleMineralPhase();"><span>Add Phase </span></button></legend>
				<div>
					<div class="subDataHolder" style="padding-left:0px !important;">


<?php
if($sample->material->composition != "" && count($sample->material->composition) > 0){
?>

						<!-- Phases Here -->
						<div style="float:left;">
							<div id="sample_mineral_phase_buttons">
								<!-- buttons here -->



	<?php
	$compNum = 0;
	foreach($sample->material->composition as $comp){
	?>

<button id="sample_mineral_phase_button_<?php echo $compNum?>" class="sideBarButton sample_mineral_phase_button_group straboRedSelectedButton" style="vertical-align:middle" onclick="exper_sampleSwitchToMineral(<?php echo $compNum?>);"><span><?php echo $comp->mineral?></span></button>

	<?php
		$compNum++;
	}
	?>

							</div>
						</div>
						<div style="float:left;padding-left:5px;">
							<div  id="sample_mineral_phases">
								<!-- each phase in here -->

						<?php
						$compNum = 0;
						foreach($sample->material->composition as $comp){
						?>

						<div class="deviceRow sample_mineral_phase_group" id="sample_mineral_phase_0" style="margin-top: 5px; width: 1065px !important; display: block;">
							<div class="formCell" style="padding-left:0px !important;">
								<div class="formRow" style="width: 930px !important;">
									<div class="formCell w25">
										<div class="formPart">
											<label class="formLabel">Mineral </label>
											<div id="mineralSelectHolder">
											<select id="mineralName" style="display:none;"></select>
											<div class="formControl"><?php echo $comp->mineral?></div>
											</div>
										</div>
									</div>
									<div class="formCell w16">
										<div class="formPart">
											<label class="formLabel">Fraction</label>
											<div class="formControl"><?php echo $comp->fraction?></div>
										</div>
									</div>
									<div class="formCell w25">
										<div class="formPart">
											<label class="formLabel">Grain Size [µm]</label>
											<div class="formControl"><?php echo $comp->grainsize?></div>
										</div>
									</div>
									<div class="formCell w16">
										<div class="formPart">
											<label class="formLabel">Unit</label>
											<div class="formControl"><?php echo $comp->unit?></div>
										</div>
									</div>
								</div>
							</div>
							<div class="docCell" style="width:120px; white-space: nowrap;display:none;">
								<button id="deleteButton" class="squareButton squareButtonBottom" onclick="exper_deleteSampleMineralPhase(0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
								<button id="upButton" class="squareButton squareButtonBottom" onclick="exper_moveSampleMineralPhaseUp(0);" style="display: none;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
								<button id="downButton" class="squareButton squareButtonBottom" onclick="exper_moveSampleMineralPhaseDown(0);" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
							</div>
							<div style="clear:both;"></div>
						</div>

						<?php
							$compNum++;
						}
						?>


							</div>
						</div>
						<div style="clear:both;"></div>

<?php
}else{
?>
			No Mineralogy.
<?php
}
?>

					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<fieldset class="mainFS" style="padding-top: 0px !important;">
				<legend class="mainFSLegend">Provenance</legend>
				<div class="formRow">
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel">Formation Name</label>
							<div class="formControl"><?php echo $sample->material->provenance->formation?></div>
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel">Member Name</label>
							<div class="formControl"><?php echo $sample->material->provenance->member?></div>
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel">Sub Member Name</label>
							<div class="formControl"><?php echo $sample->material->provenance->submember?></div>
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel">Source</label>
							<div class="formControl"><?php echo $sample->material->provenance->source?></div>
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
							<label class="formLabel">Street + Number</label>
							<div class="formControl"><?php echo $sample->material->provenance->location->street?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Building - Apt</label>
							<div class="formControl"><?php echo $sample->material->provenance->location->building?></div>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">Postal Code</label>
							<div class="formControl"><?php echo $sample->material->provenance->location->postcode?></div>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">City</label>
							<div class="formControl"><?php echo $sample->material->provenance->location->city?></div>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">State</label>
							<div class="formControl"><?php echo $sample->material->provenance->location->state?></div>
						</div>
					</div>
					<div class="formCell w16">
						<div class="formPart">
							<label class="formLabel">Country</label>
							<div class="formControl"><?php echo $sample->material->provenance->location->country?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Latitude</label>
							<div class="formControl"><?php echo $sample->material->provenance->location->latitude?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Longitude</label>
							<div class="formControl"><?php echo $sample->material->provenance->location->longitude?></div>
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
							<label class="formLabel">Bedding</label>
							<div class="formControl"><?php echo $sample->material->texture->bedding?></div>
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel">Lineation</label>
							<div class="formControl"><?php echo $sample->material->texture->lineation?></div>
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel">Foliation</label>
							<div class="formControl"><?php echo $sample->material->texture->foliation?></div>
						</div>
					</div>
					<div class="formCell w25">
						<div class="formPart">
							<label class="formLabel">Fault</label>
							<div class="formControl"><?php echo $sample->material->texture->fault?></div>
						</div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<!-- Parameters -->
			<fieldset class="mainFS" style="margin-top:10px">
				<legend class="mainFSLegend">Parameters <button class="fsButton" style="vertical-align:middle;display:none;" onclick="exper_addSampleParameter();"><span>Add Parameter </span></button></legend>
				<div>
					<div class="subDataHolder" style="padding-left:0px !important;">


<?php
if($sample->parameters != "" && count($sample->parameters) > 0){
?>

						<!-- Phases Here -->
						<div style="float:left;">
							<div id="sample_parameter_buttons">
								<!-- buttons here -->

	<?php
	$parNum = 0;
	foreach($sample->parameters as $par){
	?>
		<button id="sample_parameter_button_<?php echo $parNum?>" class="sideBarButton sample_parameter_button_group straboRedSelectedButton" style="vertical-align:middle" onclick="exper_sampleSwitchToParameter(<?php echo $parNum?>);"><span><?php echo $par->control?></span></button>
	<?php
		$parNum++;
	}
	?>

							</div>
						</div>
						<div style="float:left;padding-left:5px;">
							<div  id="sample_parameters">
								<!-- each parameter in here -->

	<?php
	$parNum = 0;
	foreach($sample->parameters as $par){
	?>

	<div class="deviceRow sample_parameter_group" id="sample_parameter_<?php echo $parNum?>" style="margin-top: 5px; width: 1065px !important; display: block;">
		<div class="formCell" style="padding-left:0px !important;">
			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Variable </label>
						<div>
							<div class="formControl"><?php echo $par->control?></div>
							<?php
							if($par->other_control != ""){
							?>
							<div class="formControl"><?php echo $par->other_control?></div>
							<?php
							}
							?>
						</div>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel">Value</label>
						<div class="formControl"><?php echo $par->value?></div>
						<select id="parameterVariable" style="display:none;"></select>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Unit</label>
						<div class="formControl"><?php echo $par->unit?></div>
					</div>
				</div>
				<div class="formCell w16">
					<div class="formPart">
						<label class="formLabel">Prefix</label>
						<div class="formControl"><?php echo $par->prefix?></div>
					</div>
				</div>
			</div>
			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w100">
					<div class="formPart">
						<label class="formLabel">Note (Measurement and Treatment)</label>
						<div>
							<div class="formControl"><?php echo $par->note?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="docCell" style="width:120px; white-space: nowrap; display:none;">
			<button id="deleteButton" class="squareButton squareButtonBottom" onclick="exper_deleteSampleParameter(0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
			<button id="upButton" class="squareButton squareButtonBottom" onclick="exper_moveSampleParameterUp(0);" style="display: none;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
			<button id="downButton" class="squareButton squareButtonBottom" onclick="exper_moveSampleParameterDown(0);" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
		</div>
		<div style="clear:both;"></div>
	</div>


	<?php
		$parNum++;
	}
	?>
							</div>
						</div>
						<div style="clear:both;"></div>
<?php
}else{
?>

No parameters.

<?php
}
?>

					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<!-- Documents -->
			<fieldset class="mainFS" style="margin-top:10px">
				<legend class="mainFSLegend">Documents <button id="addDocumentButton" class="fsButton" style="vertical-align:middle;display:none;" onclick="exper_addSampleDocument();"><span>Add Document </span></button></legend>
				<div>
					<div class="subDataHolder" style="padding-left:0px !important;">
						<!-- Documents Here -->

														<?php
														if($sample->documents != ""){
														?>

															<!-- Documents Here -->
															<div style="float:left;">
																<div id="sample_document_buttons">
																	<!-- buttons here -->

																	<?php
																	$docNum = 0;
																	foreach($sample->documents as $doc){
																	?>
																	<button id="sample_document_button_<?php echo $docNum?>" class="sideBarButton sample_document_button_group straboRedSelectedButton" style="vertical-align:middle" onclick="exper_sampleSwitchToDocument(<?php echo $docNum?>);"><span><?php echo $doc->type?></span></button>
																	<?php
																		$docNum++;
																	}
																	?>

																</div>
															</div>
															<div style="float:left;padding-left:5px;">
																<div id="sample_documents">
																	<!-- each document in here -->

																	<?php
																	$docNum = 0;
																	foreach($sample->documents as $doc){
																	?>

													<div class="deviceRow sample_document_group" id="sample_document_<?php echo $docNum?>" style="margin-top: 5px; width: 960px !important; display: block;">
														<div class="formCell" style="width:900px;padding-left:0px !important;">
															<div class="formRow">
																<div class="formCell 16">
																	<div class="formPart">
																		<label class="formLabel">Document Type </label>
																		<div class="formControl"><?php echo $doc->type?></div>
																		<?php
																		if($doc->other_type != ""){
																		?>
																		<div class="formControl"><?php echo $doc->other_type?></div>
																		<?php
																		}
																		?>
																	</div>
																</div>
																<div class="formCell w16">
																	<div class="formPart">
																		<label class="formLabel">Document Format </label>
																		<div class="formControl"><?php echo $doc->format?></div>
																		<?php
																		if($doc->other_format != ""){
																		?>
																		<div class="formControl"><?php echo $doc->other_format?></div>
																		<?php
																		}
																		?>
																	</div>
																</div>
																<div class="formCell w50">
																	<div class="formPart" id="fileHolder">
																		<label class="formLabel">File</label>
																		<div class="formControl"><a href="<?php echo $doc->path?>" target="_blank"><?php echo $doc->path?></a></div>
																	</div>
																</div>
																<div class="formCell w16">
																	<div class="formPart">
																		<label class="formLabel">Document ID</label>
																		<div class="formControl"><?php echo $doc->id?></div>
																	</div>
																</div>
															</div>
															<div class="formRow">
																<div class="formCell w100">
																	<div class="formPart">
																		<label class="formLabel">Description</label>
																		<div class="formControl"><?php echo $doc->description?></div>
																	</div>
																</div>
															</div>
														</div>
														<div class="docCell" style="width:25px;display:none;">
															<div style="padding-top:10px;"><button id="deleteButton" class="squareButton squareButtonBottom" onclick="exper_deleteDAQDeviceDocument(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
															<div><button id="upButton" class="squareButton squareButtonBottom" onclick="exper_moveDAQDeviceDocumentUp(0, 0);" style="display: none;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
															<div><button id="downButton" class="squareButton squareButtonBottom" onclick="exper_moveDAQDeviceDocumentDown(0, 0);" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
														</div>
														<div style="clear:both;"></div>
													</div>

																	<?php
																		$docNum++;
																	}
																	?>

																</div>
															</div>
															<div style="clear:both;"></div>


														<?php
														}else{
														?>

														No Documents.

														<?php
														}
														?>

					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>
			<div style="text-align:center;display:none;"><button class="submitButton" style="vertical-align:middle" onclick="exper_doSaveSampleInfo()"><span>Save </span></button></div>
		</div>
	</div>
	</div>
</div>

<!-- Experiment Setup Modal -->
<div id="experimentSetupModal" style="display:none;">
	<div class="grayOut"></div>
	<div class="redCloseBox" onclick="exper_CloseExperimentSetupModal();">X</div>
	<div class="modalBox" id="experimentSetupModalBox">
	<div id="sourceExperimentSetupModal">
		<div class="topTitle" style="padding-top:10px;">Experimental Setup Info</div>
		<div>
			<fieldset class="mainFS" style="padding-top: 0px !important;">
				<legend class="mainFSLegend">Experiment Info</legend>
				<div class="formRow">
					<div class="formCell w100">
						<div class="formPart">
							<label class="formLabel">Title</label>
							<div class="formControl"><?php echo $experiment->title?></div>
						</div>
					</div>
				</div>
				<div class="formRow">
					<!--
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Project</label>
							<input id="sampleName" class="formControl" type="text" value="">
						</div>
					</div>
					-->
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel">Experiment ID </label>
							<div class="formControl"><?php echo $experiment->id?></div>
						</div>
					</div>
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel">IEDA ID</label>
							<div class="formControl"><?php echo $experiment->ieda?></div>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel">Start Date</label>
							<div class="formControl"><?php echo $experiment->start_date?></div>
						</div>
					</div>
					<div class="formCell w50">
						<div class="formPart">
							<label class="formLabel">End Date</label>
							<div class="formControl"><?php echo $experiment->end_date?></div>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w100">
						<div class="formPart">
							<label class="formLabel">Experiment Description</label>
							<div class="formControl"><?php echo $experiment->description?></div>
						</div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>

			<fieldset class="mainFS" style="padding-top: 0px !important;">
				<legend class="mainFSLegend">Test Features</legend>
				<div class="formRow">
					<div class="formCell w100">
						<div><?php echo implode($experiment->features, "; ")?></div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>

			<fieldset class="mainFS" style="padding-top: 0px !important;">
				<legend class="mainFSLegend">Author</legend>
				<div class="formRow">
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">First Name</label>
							<div class="formControl"><?php echo $experiment->author->firstname?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Last Name</label>
							<div class="formControl"><?php echo $experiment->author->lastname?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Affiliation</label>
							<div class="formControl"><?php echo $experiment->author->affiliation?></div>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Email</label>
							<div class="formControl"><?php echo $experiment->author->email?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Phone</label>
							<div class="formControl"><?php echo $experiment->author->phone?></div>
						</div>
					</div>
					<div class="formCell w33">
						<div class="formPart">
							<label class="formLabel">Website</label>
							<div class="formControl"><?php echo $experiment->author->website?></div>
						</div>
					</div>
				</div>
				<div class="formRow">
					<div class="formCell w100">
						<div class="formPart">
							<label class="formLabel">ORCID</label>
							<div class="formControl"><?php echo $experiment->author->id?></div>
						</div>
					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>

			<!-- Geometries -->
			<fieldset class="mainFS" style="margin-top:10px">
				<legend class="mainFSLegend">Geometry <button class="fsButton" style="vertical-align:middle;display:none;" onclick="exper_addExperimentGeometry();"><span>Add Geometry </span></button></legend>
				<div>
					<div class="subDataHolder" style="padding-left:0px !important;">

				<?php
				if($experiment->geometry != "" && count($experiment->geometry) > 0){
				?>

						<div style="float:left;">
							<div id="experiment_geometry_buttons" style="padding-top:5px;">
								<!-- Buttons Here -->


						<?php
						$geoNum=0;
						foreach($experiment->geometry as $geo){
						?>
						<button id="experiment_geometry_button_<?php echo $geoNum?>" class="sideBarButton experiment_geometry_button_group straboRedSelectedButton" style="vertical-align:middle" onclick="exper_experimentSwitchToGeometry(<?php echo $geoNum?>);"><span><?php echo $geo->type?> #<?php echo $geo->order?></span></button>
						<?php
							$geoNum++;
						}
						?>

							</div>
						</div>
						<div style="float:left;padding-left:5px;">
							<div  id="experiment_geometries">
								<!-- Geometries Here -->

							<?php
							$geoNum=0;
							foreach($experiment->geometry as $geo){
							?>

								<div class="deviceRow experiment_geometry_group" id="experiment_geometry_<?php echo $geoNum?>" style="margin-top: 5px; width: 1065px !important; display: block;">
									<div class="formCell" style="padding-left:0px !important;">
										<div class="formRow" style="width: 930px !important;">
											<div class="formCell w25">
												<div class="formPart">
													<label class="formLabel">Geometry #</label>
													<div>
														<div class="formControl"><?php echo $geo->order?></div>
														<select id="experimentGeometryNum" style="display:none;"></select>
													</div>
												</div>
											</div>
											<div class="formCell w25">
												<div class="formPart">
													<label class="formLabel">Material</label>
													<div>
														<div class="formControl"><?php echo $geo->material?></div>
													</div>
												</div>
											</div>
											<div class="formCell w25">
												<div class="formPart">
													<label class="formLabel">Type</label>
													<div>
														<div class="formControl"><?php echo $geo->type?></div>
														<select id="experimentGeometryType" style="display:none;"></select>
													</div>
												</div>
											</div>
											<div class="formCell w25">
												<div class="formPart">
													<label class="formLabel">Geometry</label>
													<div>
														<div class="formControl"><?php echo $geo->geometry?></div>
													</div>
												</div>
											</div>
										</div>

										<div class="formRow" style="width: 930px !important;">
											<div class="formCell w100">

												<!-- Dimensions -->
												<fieldset class="mainFS" style="margin-top:10px">
													<legend class="mainFSLegend">Dimensions <button id="addDimensionButton" class="fsButton" style="vertical-align:middle;display:none;" onclick="exper_addExperimentGeometryDimension(0);"><span>Add Dimension </span></button></legend>
													<div id="dimensionRowsHolders" style="padding-left: 5px; display: block !important;">

													<?php
													if($geo->dimensions != "" && count($geo->dimensions) > 0){
													?>

																				<div class="frontBoxRow" style="font-weight:bold;">
																					<div class="dataLeft w16">Variable</div>
																					<div class="dataLeft w16">Value</div>
																					<div class="dataLeft w16">Unit</div>
																					<div class="dataLeft w16">Prefix</div>
																					<div class="dataLeft w16">Note</div>
																					<div class="dataLeft w16">&nbsp;</div>
																				</div>

																				<div id="dimensionRows">

														<?php
														foreach($geo->dimensions as $dim){
														?>

															<div class="frontBoxRow" id="" style="font-weight:bold;">
																<div class="dataLeft w16">
																	<div class="formControl"><?php echo $dim->variable?></div>
																</div>
																<div class="dataLeft w16">
																	<div class="formControl"><?php echo $dim->value?></div>
																</div>
																<div class="dataLeft w16">
																	<div class="formControl"><?php echo $dim->unit?></div>
																</div>
																<div class="dataLeft w16">
																	<div class="formControl"><?php echo $dim->prefix?></div>
																</div>
																<div class="dataLeft w16">
																	<div class="formControl"><?php echo $dim->note?></div>
																</div>
																<div class="dataLeft w16" style="display:none;">
																	<button id="deleteDimensionButton" class="squareButton squareButtonBottom" onclick="exper_deleteExperimentGeometryDimension(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
																	<button id="upDimensionButton" class="squareButton squareButtonBottom" onclick="exper_moveExperimentGeometryDimensionUp(0, 0);" style="display: none;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
																	<button id="downDimensionButton" class="squareButton squareButtonBottom" onclick="exper_moveExperimentGeometryDimensionDown(0, 0);" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
																</div>
															</div>

														<?php
														}
														?>

																				</div>

													<?php
													}else{
													?>

													<div class="subDataHolder" style="padding-left:0px !important;">No Dimensions.</div>

													<?php
													}
													?>


													</div>
												</fieldset>
												<div class="fsSpacer"></div>

											</div>
										</div>

									</div>
									<div class="docCell" style="width:120px; white-space: nowrap; display:none;">
										<button id="deleteButton" class="squareButton squareButtonBottom" onclick="exper_deleteExperimentGeometry(0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
										<button id="upButton" class="squareButton squareButtonBottom" onclick="exper_moveExperimentGeometryUp(0);" style="display: none;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
										<button id="downButton" class="squareButton squareButtonBottom" onclick="exper_moveExperimentGeometryDown(0);" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
									</div>
									<div style="clear:both;"></div>
								</div>

							<?php
								$geoNum++;
							}
							?>

							</div>
						</div>
						<div style="clear:both;"></div>

				<?php
				}else{
				?>

				No Geometry Data.

				<?php
				}
				?>

					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>

			<!-- Protocol -->
			<fieldset class="mainFS" style="margin-top:10px">
				<legend class="mainFSLegend">Protocol <button class="fsButton" style="vertical-align:middle;display:none;" onclick="exper_addExperimentProtocol();"><span>Add Step </span></button></legend>
				<div>
					<div class="subDataHolder" style="padding-left:0px !important;">

<?php
if($experiment->protocol != "" && count($experiment->protocol) > 0){
?>

						<div style="float:left;">
							<div id="experiment_protocol_buttons" style="padding-top:5px;">
								<!-- Buttons Here -->


								<?php
								$protNum = 0;
								foreach($experiment->protocol as $prot){
								if($prot->test != ""){
									$buttonText = $prot->test;
								}else{
									$buttonText = "Step";
								}
								?>
									<button id="experiment_protocol_button_<?php echo $protNum?>" class="sideBarButton experiment_protocol_button_group straboRedSelectedButton" style="vertical-align:middle" onclick="exper_experimentSwitchToProtocol(<?php echo $protNum?>);"><span><?php echo $buttonText?></span></button>
								<?php
									$protNum++;
								}
								?>

							</div>
						</div>
						<div style="float:left;padding-left:5px;">
							<div  id="experiment_protocols">
								<!-- Protocols Here -->

								<?php
								$protNum = 0;
								foreach($experiment->protocol as $prot){
								?>

	<div class="deviceRow experiment_protocol_group" id="experiment_protocol_<?php echo $protNum?>" style="margin-top: 5px; width: 1065px !important; display: block;">
		<div class="formCell" style="padding-left:0px !important;">
			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w33" style="display:none;">
					<div class="formPart">
						<label class="formLabel">Step #</label>
						<div>
							<select class="formControl formSelect" id="experimentProtocolNum" onchange="exper_experimentRenameProtocolButton(0);">
								<option value="1">1</option>
							</select>
						</div>
					</div>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel">Step</label>
						<div>
							<div class="formControl"><?php echo $prot->test?></div>
						</div>
					</div>
				</div>
				<div class="formCell w66">
					<div class="formPart">
						<label class="formLabel">Objective</label>
						<div>
							<div class="formControl"><?php echo $prot->objective?></div>
						</div>
					</div>
				</div>
			</div>

			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w100">
					<div class="formPart">
						<label class="formLabel">Description</label>
						<div>
							<div class="formControl"><?php echo $prot->description?></div>
						</div>
					</div>
				</div>
			</div>

			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w100">

					<!-- Parameters -->
					<fieldset class="mainFS" style="margin-top:10px">
						<legend class="mainFSLegend">Parameters <button id="addParameterButton" class="fsButton" style="vertical-align:middle;display:none;" onclick="exper_addExperimentProtocolParameter(0);"><span>Add Parameter </span></button></legend>

<?php
if($prot->parameters != "" && count($prot->parameters) > 0){
?>

						<div id="parameterRowsHolder" style="padding-left: 5px;">
							<div class="frontBoxRow" style="font-weight:bold;">
								<div class="dataLeft w16">Variable</div>
								<div class="dataLeft w16">Value</div>
								<div class="dataLeft w16">Unit</div>
								<div class="dataLeft w33">Note</div>
								<div class="dataLeft w16">&nbsp;</div>
							</div>

							<div id="parameterRows">
	<?php
	$parNum = 0;
	foreach($prot->parameters as $par){
	?>

		<div class="frontBoxRow" id="experimentProtocolParameter_<?php echo $protNum?>_<?php echo $parNum?>" style="font-weight:bold;">
			<div class="dataLeft w16">
				<div class="formControl"><?php echo $par->control?></div>
			</div>
			<div class="dataLeft w16">
				<input id="parameterValue" class="formControl" type="text" value="">
			</div>
			<div class="dataLeft w16">
				<div class="formControl"><?php echo $par->unit?></div>
			</div>
			<div class="dataLeft w33">
				<div class="formControl"><?php echo $par->note?></div>
			</div>
			<div class="dataLeft w16" style="display:none;">
				<button id="deleteParameterButton" class="squareButton squareButtonBottom" onclick="exper_deleteExperimentProtocolParameter(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
				<button id="upParameterButton" class="squareButton squareButtonBottom" onclick="exper_moveExperimentProtocolParameterUp(0, 0);" style="display: none;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
				<button id="downParameterButton" class="squareButton squareButtonBottom" onclick="exper_moveExperimentProtocolParameterDown(0, 0);" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
			</div>
		</div>



	<?php
		$parNum = 0;
	}
	?>
							</div>
						</div>


<?php
}else{
?>
	<div class="subDataHolder" style="padding-left:0px !important;">No Parameters.</div>
<?php
}
?>

					</fieldset>
					<div class="fsSpacer"></div>

				</div>
			</div>

		</div>
		<div class="docCell" style="width:120px; white-space: nowrap;display:none;">
			<button id="deleteButton" class="squareButton squareButtonBottom" onclick="exper_deleteExperimentProtocol(0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
			<button id="upButton" class="squareButton squareButtonBottom" onclick="exper_moveExperimentProtocolUp(0);" style="display: none;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
			<button id="downButton" class="squareButton squareButtonBottom" onclick="exper_moveExperimentProtocolDown(0);" style="display: none;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
		</div>
		<div style="clear:both;"></div>
	</div>












								<?php
									$protNum++;
								}
								?>







							</div>
						</div>
						<div style="clear:both;"></div>



<?php
}else{
?>

No Protocol Data.

<?php
}
?>






					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>

			<!-- Documents -->
			<fieldset class="mainFS" style="margin-top:10px">
				<legend class="mainFSLegend">Documents <button class="fsButton" style="vertical-align:middle;display:none;" onclick="exper_addExperimentDocument();"><span>Add Document </span></button></legend>
				<div>
					<div class="subDataHolder" style="padding-left:0px !important;">

														<?php
														if($experiment->documents != ""){
														?>


															<!-- Documents Here -->
															<div style="float:left;">
																<div id="experiment_document_buttons">
																	<!-- buttons here -->

																	<?php
																	$docNum = 0;
																	foreach($experiment->documents as $doc){
																	?>
																	<button id="experiment_document_button_<?php echo $docNum?>" class="sideBarButton experiment_document_button_group straboRedSelectedButton" style="vertical-align:middle" onclick="exper_experimentSwitchToDocument(<?php echo $docNum?>);"><span><?php echo $doc->type?></span></button>
																	<?php
																		$docNum++;
																	}
																	?>

																</div>
															</div>
															<div style="float:left;padding-left:5px;">
																<div id="experiment_documents">
																	<!-- each document in here -->

																	<?php
																	$docNum = 0;
																	foreach($experiment->documents as $doc){
																	?>

													<div class="deviceRow experiment_document_group" id="experiment_document_<?php echo $docNum?>" style="margin-top: 5px; width: 960px !important; display: block;">
														<div class="formCell" style="width:900px;padding-left:0px !important;">
															<div class="formRow">
																<div class="formCell 16">
																	<div class="formPart">
																		<label class="formLabel">Document Type </label>
																		<div class="formControl"><?php echo $doc->type?></div>
																		<?php
																		if($doc->other_type != ""){
																		?>
																		<div class="formControl"><?php echo $doc->other_type?></div>
																		<?php
																		}
																		?>
																	</div>
																</div>
																<div class="formCell w16">
																	<div class="formPart">
																		<label class="formLabel">Document Format </label>
																		<div class="formControl"><?php echo $doc->format?></div>
																		<?php
																		if($doc->other_format != ""){
																		?>
																		<div class="formControl"><?php echo $doc->other_format?></div>
																		<?php
																		}
																		?>
																	</div>
																</div>
																<div class="formCell w50">
																	<div class="formPart" id="fileHolder">
																		<label class="formLabel">File</label>
																		<div class="formControl"><a href="<?php echo $doc->path?>" target="_blank"><?php echo $doc->path?></a></div>
																	</div>
																</div>
																<div class="formCell w16">
																	<div class="formPart">
																		<label class="formLabel">Document ID</label>
																		<div class="formControl"><?php echo $doc->id?></div>
																	</div>
																</div>
															</div>
															<div class="formRow">
																<div class="formCell w100">
																	<div class="formPart">
																		<label class="formLabel">Description</label>
																		<div class="formControl"><?php echo $doc->description?></div>
																	</div>
																</div>
															</div>
														</div>
														<div class="docCell" style="width:25px;display:none;">
															<div style="padding-top:10px;"><button id="deleteButton" class="squareButton squareButtonBottom" onclick="exper_deleteDAQDeviceDocument(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
															<div><button id="upButton" class="squareButton squareButtonBottom" onclick="exper_moveDAQDeviceDocumentUp(0, 0);" style="display: none;"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
															<div><button id="downButton" class="squareButton squareButtonBottom" onclick="exper_moveDAQDeviceDocumentDown(0, 0);" style="display: inline;"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
														</div>
														<div style="clear:both;"></div>
													</div>

																	<?php
																		$docNum++;
																	}
																	?>

																</div>
															</div>
															<div style="clear:both;"></div>


														<?php
														}else{
														?>

														No Documents.

														<?php
														}
														?>

					</div>
				</div>
			</fieldset>
			<div class="fsSpacer"></div>

			<div style="text-align:center;"><button class="submitButton" style="vertical-align:middle;display:none;" onclick="exper_doSaveExperimentInfo()"><span>Save </span></button></div>
		</div>
	</div>
	</div>
</div>

<!-- Source Experiment Setup -->
<div style="display:none;">

</div>

<!-- Source Experiment Protocol -->
<div style="display:none;">
	<div class="deviceRow experiment_protocol_group" id="sourceExperimentProtocol" style="margin-top: 5px; width: 1065px !important; display: block;">
		<div class="formCell" style="padding-left:0px !important;">
			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w33" style="display:none;">
					<div class="formPart">
						<label class="formLabel">Step #</label>
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
						<label class="formLabel">Step</label>
						<div>
							<select class="formControl formSelect" id="experimentProtocolTest">

							</select>
						</div>
					</div>
				</div>

				<div class="formCell w66">
					<div class="formPart">
						<label class="formLabel">Objective</label>
						<div>
							<input id="experimentProtocolObjective" class="formControl" type="text" value="">
						</div>
					</div>
				</div>
			</div>

			</div>

			<div class="formRow" style="width: 930px !important;">
				<div class="formCell w100">
					<div class="formPart">
						<label class="formLabel">Description</label>
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

<!-- Experiment Protocol Parameter -->
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
						<label class="formLabel">Geometry #</label>
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
						<label class="formLabel">Material</label>
						<div>
							<select class="formControl formSelect" id="experimentGeometryMaterial">
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
							</select>
						</div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Type</label>
						<div>
							<select class="formControl formSelect" id="experimentGeometryType">
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
						<label class="formLabel">Geometry</label>
						<div>
							<select class="formControl formSelect" id="experimentGeometryGeometry">
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


<!-- Data Modal -->
<div id="dataModal" style="display:none;">
	<div class="grayOut"></div>
	<div class="redCloseBox" onclick="exper_closeDataModal();">X</div>
	<div class="modalBox" id="dataModalBox">
		<div>
			<div id="sourceDataModal">
				<div class="topTitle" style="padding-top:10px;">Experimental Data</div>
				<div>

					<fieldset class="mainFS" style="margin-top:10px">
						<legend class="subFSLegend">Datasets</legend>
						<div>
							<div class="subDataHolder" style="padding-left:0px !important;">
								<!-- Datasets Here -->
								<div style="float:left;">
									<div id="data_dataset_buttons" style="padding-top:5px;">
										<!-- buttons here -->
										<?php
										foreach($dataData->datasets as $dataset){
										?>
										<button id="data_dataset_button_0" class="sideBarButton data_dataset_button_group straboRedSelectedButton" style="vertical-align:middle" onclick="exper_daqSwitchToDataset(<?php echo $daqNum?>, <?php echo $chNum?>);"><span><?php echo $dataset->data?></span></button>
										<?php
										}
										?>
									</div>
								</div>
								<div style="float:left;padding-left:5px;">
									<div id="data_datasets" style="width:1000px;">
										<!-- each dataset in here -->
										<?php
										$dNum = 0;
										foreach($dataData->datasets as $dataset){
										?>
											<div class="deviceRow data_dataset_group" id="data_dataset_0" style="margin-top: 5px; width: 1000px !important; display: block;display:none;">
												<div class="formCell" style="width:900px;padding-left:0px !important;">
													<div class="formRow">
														<div class="formCell w25">
															<div class="formPart">
																<label class="formLabel">Data</label>
																<div class="formControl"><?php echo $dataset->data?></div>
															</div>
														</div>
														<div class="formCell w25">
															<div class="formPart">
																<label class="formLabel">Data Type</label>
																<div class="formControl"><?php echo $dataset->type?></div>
																<?php
																if($dataset->other_type != ""){
																?>
																<div class="formControl"><?php echo $dataset->other_type?></div>
																<?php
																}
																?>
															</div>
														</div>
														<div class="formCell w50">
															<div class="formPart" id="fileHolder">
																<label class="formLabel">Data File</label>
																<div class="formControl"><a href="<?php echo $dataset->path?>" target="_blank"><?php echo $dataset->path?></a></div>
															</div>
														</div>
													</div>
													<div class="formRow">
														<div class="formCell w50">
															<div class="formPart">
																<label class="formLabel">Data ID</label>
																<div class="formControl"><?php echo $dataset->id?></div>
																<input id="uuid" type="hidden" value="">
															</div>
														</div>
														<div class="formCell w25">
															<div class="formPart">
																<label class="formLabel">File Format</label>
																<div class="formControl"><?php echo $dataset->format?></div>
																<?php
																if($dataset->other_format != ""){
																?>
																<div class="formControl"><?php echo $dataset->other_format?></div>
																<?php
																}
																?>
															</div>
														</div>
														<div class="formCell w25">
															<div class="formPart">
																<label class="formLabel">Data Quality</label>
																<div class="formControl"><?php echo $dataset->rating?></div>
															</div>
														</div>
													</div>
													<div class="formRow">
														<div class="formCell w100">
															<div class="formPart">
																<label class="formLabel">Description</label>
																<div class="formControl"><?php echo $dataset->description?></div>
															</div>
														</div>
													</div>

													<!--Extra Data Here-->
													<div id="extraData">

														<?php
														if($dataset->data == "Parameters"){
															if(count($dataset->parameters > 0)){
														?>
																<div id="data_parameters_<?php echo $dNum?>">
																	<div class="formRow" style="width: 870px !important;">

																		<!-- Parameters -->
																		<fieldset class="subFS" style="margin-top:10px">
																			<legend class="subFSLegend">Parameter List</legend>
																			<div id="parameterRowsHolder" style="padding-left: 5px;">

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
																					<?php
																					foreach($dataset->parameters as $p){
																					?>
																						<div id="sourceDataParameterRow">
																							<div class="frontBoxRow" style="font-weight:normal;">
																								<div class="dataLeft w25">
																									<div class="formControl"><?php echo $p->control?></div>
																								</div>
																								<div class="dataLeft w10">
																									<div class="formControl"><?php echo $p->value?></div>
																								</div>
																								<div class="dataLeft w10">
																									<div class="formControl"><?php echo $p->error?></div>
																								</div>
																								<div class="dataLeft w10">
																									<div class="formControl"><?php echo $p->unit?></div>
																								</div>
																								<div class="dataLeft w10">
																									<div class="formControl"><?php echo $p->prefix?></div>
																								</div>
																								<div class="dataLeft w33">
																									<div class="formControl"><?php echo $p->note?></div>
																								</div>
																								<div class="dataLeft w16" style="display:none;">
																									<button id="deleteParameterButton" class="squareButton squareButtonBottom" onclick="exper_deleteDataParameter(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
																									<button id="upParameterButton" class="squareButton squareButtonBottom" onclick="exper_moveDataParameterUp(0, 0);"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
																									<button id="downParameterButton" class="squareButton squareButtonBottom" onclick="exper_moveDataParameterDown(0, 0);"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
																								</div>
																							</div>
																						</div>
																					<?php
																					}
																					?>
																				</div>

																			</div>

																		</fieldset>
																		<div class="fsSpacer"></div>


																	</div>
																</div>
														<?php
															}
														}
														?>

														<?php
														if($dataset->data == "Time Series"){
															if(count($dataset->headers > 0)){
														?>

														<div id = "data_headers_<?php echo $dNum?>">
															<div class="formRow" style="width: 870px !important;">
																<!-- Time Series Headers -->
																<fieldset class="subFS" style="margin-top:10px">
																	<legend class="subFSLegend">Data Headers</legend>
																	<div>
																		<div class="subDataHolder" style="padding-left:0px !important;">
																			<!-- Headers Here -->
																			<div style="float:left;">
																				<div id="header_buttons" style="padding-top:5px;">
																					<!-- Header Buttons Here -->
																					<?php
																					if(count($dataset->headers == 0)){
																					?>
																					No Data Headers.
																					<?php
																					}
																					foreach($dataset->headers as $h){
																					?>
																					<button id="data_dataset_button_0" class="sideBarButton data_header_button_group_<?php echo $dNum?> straboRedSelectedButton" style="vertical-align:middle" onclick="exper_daqSwitchToDataset(<?php echo $daqNum?>, <?php echo $chNum?>);"><span><?php echo $h->header->header?></span></button>
																					<?php
																					}
																					?>
																				</div>
																			</div>
																			<div style="float:left;padding-left:5px;">
																				<div id="headers">
																					<!-- Each Header Here -->

																					<?php
																					$hNum = 0;
																					foreach($dataset->headers as $h){
																					?>

																					<div id="data_header_<?php echo $dNum?>_<?php echo $hNum?>" style="margin-top: 5px; width: 720px !important; display:block;">
																						<div class="formCell" style="width:660px;padding-left:0px !important;">
																							<div class="formRow">
																								<div class="formCell w25" style="padding-top:10px;padding-bottom:10px;">
																									<span class="formLabel">Header</span>
																									<div class="formControl"><?php echo $h->header->header?></div>
																								</div>
																							</div>
																							<div class="formRow">
																								<div class="formCell w25">
																									<div class="formPart">
																										<label class="formLabel">Specifier A</label>
																										<div id="specAHolder">
																											<div class="formControl"><?php echo $h->header->spec_a?></div>
																										</div>
																									</div>
																								</div>
																								<div class="formCell w25">
																									<div class="formPart">
																										<label class="formLabel">Specifier B</label>
																										<div id="specBHolder">
																											<div class="formControl"><?php echo $h->header->spec_b?></div>
																										</div>
																									</div>
																								</div>
																								<div class="formCell w25">
																									<div class="formPart">
																										<label class="formLabel">Other Specifier</label>
																										<div class="formControl"><?php echo $h->header->spec_c?></div>
																									</div>
																								</div>
																								<div class="formCell w25">
																									<div class="formPart">
																										<label class="formLabel">Unit</label>
																										<div id="unitHolder">
																											<div class="formControl"><?php echo $h->header->unit?></div>
																										</div>
																									</div>
																								</div>
																							</div>
																							<div class="formRow">

																								<div class="formCell w33">
																									<div class="formPart">
																										<label class="formLabel">Type</label>
																										<div class="formControl"><?php echo $h->type?></div>
																									</div>
																								</div>
																								<div class="formCell w33">
																									<div class="formPart">
																										<label class="formLabel">Channel #</label>
																										<div class="formControl"><?php echo $h->number?></div>
																									</div>
																								</div>
																								<div class="formCell w33">
																									<div class="formPart">
																										<label class="formLabel">Data Quality</label>
																										<div class="formControl"><?php echo $h->rating?></div>
																									</div>
																								</div>
																							</div>
																							<div class="formRow">

																								<div class="formCell w100">
																									<div class="formPart">
																										<label class="formLabel">Notes</label>
																										<div class="formControl"><?php echo $h->note?></div>
																									</div>
																								</div>
																							</div>
																						</div>
																						<div class="docCell" style="width:25px;display:none;">
																							<div style="padding-top:10px;"><button id="deleteHeaderButton" class="squareButton squareButtonBottom" onclick="exper_deleteDatasetHeader(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
																							<div><button id="upHeaderButton" class="squareButton squareButtonBottom" onclick="exper_moveDatasetHeaderUp(0, 0);"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
																							<div><button id="downHeaderButton" class="squareButton squareButtonBottom" onclick="exper_moveDatasetHeaderDown(0, 0);"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
																						</div>
																						<div style="clear:both;"></div>
																					</div>

																					<?php
																						$hNum++;
																					}
																					?>

																				</div>
																			</div>
																			<div style="clear:both;"></div>
																		</div>
																	</div>
																</fieldset>
																<div class="fsSpacer"></div>
															</div>
														</div>

														<?php
															}
														}
														?>

														<?php
														if($dataset->data == "Pore Fluid"){
															if($dataset->fluid != ""){
																if(count($dataset->fluid->phases > 0)){
														?>





<div id = "data_phases_<?php echo $dNum?>">
	<div class="formRow" style="width: 870px !important;">
		<!-- Pore Fluid Phases -->
		<fieldset class="subFS" style="margin-top:10px">
			<legend class="subFSLegend">Pore Fluid Phases</legend>
			<div>
				<div class="subDataHolder" style="padding-left:0px !important;">
					<!-- Phases Here -->
					<div style="float:left;">
						<div id="phase_buttons" style="padding-top:5px;">
							<!-- Phase Buttons Here -->

							<?php
							$phNum = 0;
							foreach($dataset->fluid->phases as $ph){
							?>
							<button id="data_phase_button_<?php echo $dNum?>_<?php echo $phNum?>" class="sideBarButton data_phase_button_group_<?php echo $dNum?> straboRedSelectedButton" style="vertical-align:middle" onclick="exper_daqSwitchToDataset(<?php echo $daqNum?>, <?php echo $chNum?>);"><span><?php echo $ph->component?></span></button>
							<?php
								$phNum++;
							}
							?>

						</div>
					</div>
					<div style="float:left;padding-left:5px;">
						<div id="phases">
							<!-- Each Phase Here -->

							<?php
							$phNum = 0;
							foreach($dataset->fluid->phases as $ph){
							?>

<div id="soureDataPhaseRow" style="margin-top: 5px; width: 720px !important; display: none;">
	<div class="formCell" style="width:660px;padding-left:0px !important;">
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Component</label>
					<div class="formControl"><?php echo $ph->component?></div>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Fraction</label>
					<div class="formControl"><?php echo $ph->fraction?></div>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Activity</label>
					<div class="formControl"><?php echo $ph->activity?></div>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Fugacity</label>
					<div class="formControl"><?php echo $ph->fugacity?></div>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Unit</label>
					<div class="formControl"><?php echo $ph->unit?></div>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Chemistry Data</label>
					<div class="formControl"><?php echo $ph->composition?></div>
				</div>
			</div>
		</div>
		<div id="solutesHolder" style="padding-left:10px;">

		<?php
		if($ph->solutes != "" && count($ph->solutes) > 0){
		?>

		<div id = "sourceDataSolutesBox" style="display:block;">
			<div class="formRow" style="width: 630px !important;">

				<!-- Solutes -->
				<fieldset class="subFS" style="margin-top:10px">
					<legend class="subFSLegend">Chemistry</legend>
					<div id="soluteRowsHolder" style="padding-left: 5px; display: block;">

						<div class="frontBoxRow" style="font-weight:bold;">
							<div class="dataLeft w20">Component</div>
							<div class="dataLeft w20">Value</div>
							<div class="dataLeft w20">Error</div>
							<div class="dataLeft w20">Unit</div>
							<div class="dataLeft w20">&nbsp;</div>
						</div>

						<div id="soluteRows">
							<!--Each Solute Here-->
							<?php
							foreach($ph->solutes as $sol){
							?>

							<div id="sourceDataSoluteRow" style="display:block;">
								<div class="frontBoxRow" style="font-weight:normal;">
									<div class="dataLeft w20">
										<div class="formControl"><?php echo $sol->component?></div>
									</div>
									<div class="dataLeft w20">
										<div class="formControl"><?php echo $sol->value?></div>
									</div>
									<div class="dataLeft w20">
										<div class="formControl"><?php echo $sol->error?></div>
									</div>
									<div class="dataLeft w20">
										<div class="formControl"><?php echo $sol->unit?></div>
									</div>
									<div class="dataLeft w20" style="display:none;">
										<button id="deleteSoluteButton" class="squareButton squareButtonBottom" onclick="exper_deleteDataParameter(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button>
										<button id="upSoluteButton" class="squareButton squareButtonBottom" onclick="exper_moveDataParameterUp(0, 0);"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button>
										<button id="downSoluteButton" class="squareButton squareButtonBottom" onclick="exper_moveDataParameterDown(0, 0);"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button>
									</div>
								</div>
							</div>

							<?php
							}
							?>
						</div>

					</div>

				</fieldset>
				<div class="fsSpacer"></div>

			</div>
		</div>

		<?php
		}
		?>

		</div>
	</div>
	<div class="docCell" style="width:25px;display:none;">
		<div style="padding-top:10px;"><button id="deletePhaseButton" class="squareButton squareButtonBottom" onclick="exper_deleteDatasetPhase(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
		<div><button id="upPhaseButton" class="squareButton squareButtonBottom" onclick="exper_moveDatasetPhaseUp(0, 0);"><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
		<div><button id="downPhaseButton" class="squareButton squareButtonBottom" onclick="exper_moveDatasetPhaseDown(0, 0);"><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
	</div>
	<div style="clear:both;"></div>
</div>

							<?php
								$phNum++;
							}
							?>

						</div>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</fieldset>
		<div class="fsSpacer"></div>
	</div>
</div>






														<?php
																}
															}
														}
														?>
													</div>
												</div>
												<div class="docCell" style="width:25px;">
													<div style="padding-top:10px;display:none;"><button id="deleteButton" class="squareButton squareButtonBottom" onclick="exper_deleteDAQDeviceDocument(0, 0);"><img src="/experimental/buttonImages/icons8-trash-30.png" width="20" height="20"></button></div>
													<div style="display:none;"><button id="upButton" class="squareButton squareButtonBottom" onclick="exper_moveDAQDeviceDocumentUp(0, 0);" ><img src="/experimental/buttonImages/icons8-up-30.png" width="20" height="20"></button></div>
													<div style="display:none;"><button id="downButton" class="squareButton squareButtonBottom" onclick="exper_moveDAQDeviceDocumentDown(0, 0);" ><img src="/experimental/buttonImages/icons8-down-30.png" width="20" height="20"></button></div>
												</div>
												<div style="clear:both;"></div>
											</div>
										<?php
											$dNum++;
										}
										?>
									</div>
								</div>
								<div style="clear:both;"></div>
							</div>
						</div>
					</fieldset>
					<div class="fsSpacer"></div>

					<div style="text-align:center;"><button class="submitButton" style="vertical-align:middle;display:none;" onclick="exper_doSaveDataInfo()"><span>Save </span></button></div>
				</div>
			</div>
		</div>
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

			<div style="text-align:center;"><button class="submitButton" style="vertical-align:middle" onclick="exper_doSaveDataInfo()"><span>Save </span></button></div>
		</div>
	</div>
</div>

<!-- Source Dataset Row -->
<div id="sourceDatasetRow" class="deviceRow data_dataset_group" id="data_dataset_0" style="margin-top: 5px; width: 1000px !important; display: block;display:none;">
	<div class="formCell" style="width:900px;padding-left:0px !important;">
		<div class="formRow">
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel">Data</label>
					<select class="formControl formSelect" id="dataData" name="dataData" onchange="exper_dataRenameDatasetButton(0);">
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
					<label class="formLabel">Data Type </label>
					<select class="formControl formSelect" id="dataType" name="dataType">
						<option value="Picture">Picture</option>
						<option value="Video">Video</option>
						<option value="Data">Data</option>
						<option value="Software">Software</option>
						<option value="Other">Other</option>
					</select>
				</div>
			</div>
			<div class="formCell w50">
				<div class="formPart" id="fileHolder">
					<label class="formLabel">Choose File </label>
					<input type="file" id="dataFile" class="formControl">
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w50">
				<div class="formPart">
					<label class="formLabel">Data ID</label>
					<input id="dataId" class="formControl" type="text" value="data id here">
					<input id="uuid" type="hidden" value="">
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel">File Format</label>
					<select class="formControl formSelect" id="dataFormat" name="dataFormat">
						<option value="text">text</option>
						<option value="csv">csv</option>
						<option value="zip">zip</option>
						<option value="rar">rar</option>
					</select>
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel">Data Quality</label>
					<select class="formControl formSelect" id="dataQuality" name="dataQuality">
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
					<label class="formLabel">Description</label>
					<textarea class="formControl docDescText" data-schemaformat="markdown" id="dataDescription">desc here</textarea>
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
			<legend class="subFSLegend">Parameter List <button id="addParameterButton" class="fsButton" style="vertical-align:middle" onclick="exper_addDataParameter(0);"><span>Add Parameter </span></button></legend>
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
			<input id="parameterValue" class="formControl" type="text" value="123.45">
		</div>
		<div class="dataLeft w10">
			<input id="parameterError" class="formControl" type="text" value="123.45">
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
			<input id="parameterNote" class="formControl" type="text" value="some notes here...">
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
					<label class="formLabel">Component</label>
					<input id="phaseComposition" class="formControl" type="text" value="component here">
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Fraction</label>
					<input id="phaseFraction" class="formControl" type="text" value="fraction here">
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Activity</label>
					<input id="phaseActivity" class="formControl" type="text" value="activity here">
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Fugacity</label>
					<input id="phaseFugacity" class="formControl" type="text" value="fugacity here">
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Unit</label>
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
					<label class="formLabel">Chemistry Data</label>
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
			<input id="soluteValue" class="formControl" type="text" value="123.45">
		</div>
		<div class="dataLeft w20">
			<input id="soluteError" class="formControl" type="text" value="123.45">
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
				<span class="formLabel">Header</span>
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
					<label class="formLabel">Specifier A</label>
					<div id="specAHolder">
						<input id="headerSpecA" class="formControl" type="text" value="component here">
					</div>
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel">Specifier B</label>
					<div id="specBHolder">
						<input id="headerSpecB" class="formControl" type="text" value="fraction here">
					</div>
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel">Other Specifier</label>
					<input id="headerSpecC" class="formControl" type="text" value="other specifier here">
				</div>
			</div>
			<div class="formCell w25">
				<div class="formPart">
					<label class="formLabel">Unit</label>
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
					<label class="formLabel">Type</label>
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
					<label class="formLabel">Channel #</label>
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
					<label class="formLabel">Data Quality</label>
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
					<label class="formLabel">Notes</label>
					<textarea class="formControl" data-schemaformat="markdown" id="headerNote">some description here...</textarea>
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

	<div style="padding-left:415px; padding-top:20px; display:none;">
		<div class="formRow">
			<div class="formCell w50">
				<div class="formPart">
					<div class="topTitle"><?php echo $row->id?></div>
				</div>
			</div>
		</div>
	</div>

	<!--<div style="padding-top:25px;"></div>-->



	<div  style="clear:both;">&nbsp;</div>

	<fieldset class="mainFS" id="apparatusInfoFS">
		<legend class="mainFSLegend">Apparatus Info</legend>

		<?php
		if($apparatus->name != ""){
		if($apparatus->type == "Other Apparatus" && $apparatus->other_type != ""){
			$showtype = $apparatus->other_type;
		}else{
			$showtype = $apparatus->type;
		}
		?>

		<div id="apparatusInfo">
			<div class="formRow">
 				<div class="formCell expButtonSpacer">
					<button class="squareButtonSmaller" onclick="exper_openFacilityApparatusModal();"><img title="View" src="/experimental/buttonImages/icons8-view-30.png" width="17" height="17"></button>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel">Apparatus Name</label>
						<div><?php echo $apparatus->name?></div>
					</div>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel">Apparatus Type</label>
						<div><?php echo $showtype?></div>
					</div>
				</div>
			</div>
			<div class="formRow">
				<div class="formCell expButtonSpacer">
					&nbsp;				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel">Institute</label>
						<div><?php echo $facility->institute?></div>
					</div>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel">Department</label>
						<div><?php echo $facility->department?></div>
					</div>
				</div>
			</div>
		</div>
		<?php
		}else{
		?>

		<div class="formRow">
			<div class="formCell w100">
				No Apparatus Data.
			</div>
		</div>

		<?php
		}
		?>

	</fieldset>
	<div class="fsSpacer"></div>

	<fieldset class="mainFS" id="daqInfoFS">
		<legend class="mainFSLegend">DAQ Info</legend>



		<?php
		if($daq->name != ""){
		?>



		<div id="daqInfo">
			<div class="formRow">
				<div class="formCell expButtonSpacer">
					<button class="squareButtonSmaller" onclick="exper_openDAQModal();"><img title="View" src="/experimental/buttonImages/icons8-view-30.png" width="17" height="17"></button>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel">Group Name</label>
						<div><?php echo $daq->name?></div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Type</label>
						<div><?php echo $daq->type?></div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Location</label>
						<div><?php echo $daq->location?></div>
					</div>
				</div>
			</div>
		</div>



		<?php
		}else{
		?>

		<div class="formRow">
			<div class="formCell w100">
				No DAQ Data.
			</div>
		</div>

		<?php
		}
		?>





	</fieldset>
	<div class="fsSpacer"></div>

	<fieldset class="mainFS" id="sampleInfoFS">
		<legend class="mainFSLegend">Sample Info</legend>


		<?php
		if($sample->name != ""){
			if($sample->igsn == "") $sample->igsn = "Not provided.";
		?>



		<div class="formRow" id="sampleInfo">
			<div class="formRow">
				<div class="formCell expButtonSpacer">
					<button class="squareButtonSmaller" onclick="exper_openSampleModal();"><img title="Edit" src="/experimental/buttonImages/icons8-view-30.png" width="17" height="17"></button>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel">Sample Name</label>
						<div><?php echo $sample->name?></div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">IGSN</label>
						<div><?php echo $sample->igsn?></div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Sample ID</label>
						<div><?php echo $sample->id?></div>
					</div>
				</div>
			</div>
		</div>



		<?php
		}else{
		?>

		<div class="formRow">
			<div class="formCell w100">
				No Sample Data.
			</div>
		</div>

		<?php
		}
		?>




	</fieldset>
	<div class="fsSpacer"></div>

	<fieldset class="mainFS" id="experimentalSetupInfoFS">
		<legend class="mainFSLegend">Experimental Setup Info</legend>



		<?php
		if($experiment->title != ""){
		?>



		<div class="formRow" id="experimentalSetupInfo">
			<div class="formRow">
				<div class="formCell expButtonSpacer">
					<button class="squareButtonSmaller" onclick="exper_openExperimentSetupModal();"><img title="Edit" src="/experimental/buttonImages/icons8-view-30.png" width="17" height="17"></button>
				</div>
				<div class="formCell w33">
					<div class="formPart">
						<label class="formLabel">Experiment Title</label>
						<div><?php echo $experiment->title?></div>
					</div>
				</div>
				<div class="formCell w25">
					<div class="formPart">
						<label class="formLabel">Experiment ID</label>
						<div><?php echo $experiment->id?></div>
					</div>
				</div>
			</div>
		</div>


		<?php
		}else{
		?>

		<div class="formRow">
			<div class="formCell w100">
				No Experiment Setup Data.
			</div>
		</div>

		<?php
		}
		?>



	</fieldset>
	<div class="fsSpacer"></div>

	<fieldset class="mainFS" id="dataInfoFS">
		<legend class="mainFSLegend">Data</legend>

		<?php
		if(count($dataData->datasets) > 0){
		?>






			<div class="formRow" id="dataInfo">
			  <div class="formRow">
				<div class="formCell expButtonSpacer">
				  <button class="squareButtonSmaller" onclick="exper_openDataModal();">
					<img
					  title="Edit"
					  src="/experimental/buttonImages/icons8-view-30.png"
					  width="17"
					  height="17"
					/>
				  </button>
				</div>
				<div class="formCell w75">
				  <div class="formPart">
					<div style="padding-left: 5px; display: block;">
					  <div class="frontBoxRow" style="font-weight:bold;">
						<div class="dataLeft w33">Dataset Id</div>
						<div class="dataLeft w33">Data Source</div>
						<div class="dataLeft w33">Data Type</div>
					  </div>
					  <div>
						<!--Each Dataset Here-->
						<?php
						foreach($dataData->datasets as $d){
							$showtype = $d->type;
							if($showtype=="Other"){
								if($d->other_type != ""){
									$showtype = $d->other_type;
								}
							}
						?>


						<div>
						  <div class="frontBoxRow">
							<div class="dataLeft w33"><?php echo $d->id?></div>
							<div class="dataLeft w33"><?php echo $d->data?></div>
							<div class="dataLeft w33"><?php echo $showtype?></div>
						  </div>
						</div>


						<?php
						}
						?>
					  </div>
					</div>
				  </div>
				</div>
			  </div>
			</div>






		<?php
		}else{
		?>

		<div class="formRow" id="dataInfo">
			<div class="formCell w100">
				No Data.
			</div>
		</div>

		<?php
		}
		?>


	</fieldset>
	<div class="fsSpacer"></div>

	<div style="text-align:center;">
		<!--<button class="submitButton" style="vertical-align:middle;" onclick="javascript:history.back();"><span>Back </span></button>-->
	</div>

	<div class="fsSpacer"></div>


	<!--
	<div style="padding-top:400px;"></div>
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Project Info</legend>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Project Name </label>
					<input id="projectName" class="formControl" type="text" value=""></input>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel">Description</span></label>
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
//exper_clearSampleData();
//exper_clearExperimentSetupData();
//exper_fixButtonsAndDivs();
//setupExperimentalIdInputs();
//exper_openDataModal();
document.addEventListener("DOMContentLoaded", function(event){
	exper_fetchDOIExperimentForViewOnly('<?php echo $project_uuid?>','<?php echo $experiment_uuid?>');
	exper_fixButtonsAndDivs();
	//exper_openFacilityApparatusModal();
});
</script>


<?php
include("../includes/footer.php");
?>