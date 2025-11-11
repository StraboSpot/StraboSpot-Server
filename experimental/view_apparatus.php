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

//include("../logincheck.php");
include("../prepare_connections.php");

$uuid = $_GET['u'];
if($uuid=="")die();

$credentials = $_SESSION['credentials'];

//Check to see if apparatus exists
$row = $db->get_row("select * from apprepo.apparatus where uuid = '$uuid'");
if($row->pkey == ""){
	echo "apparatus not found.";exit();
}

if($row->type == "Other Apparatus" && $row->other_type != ""){
	$showtype = $row->other_type;
}else{
	$showtype = $row->type;
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

<div class="topTitle">Apparatus</div>
<div id="bigWindow">
	<fieldset class="mainFS">
		<legend class="mainFSLegend">Apparatus Info</legend>
		<div class="formRow">
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Apparatus Name</label>
					<div><?php echo $row->name?></div>
				</div>
			</div>
			<div class="formCell w33">
				<div class="formPart">
					<label class="formLabel">Apparatus Type</label>
					<div><?php echo $showtype?></div>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Location</label>
					<div><?php echo $row->location?></div>
				</div>
			</div>
			<div class="formCell w16">
				<div class="formPart">
					<label class="formLabel">Apparatus ID</label>
					<div><?php echo $row->apparatus_id?></div>
				</div>
			</div>
		</div>
		<div class="formRow">
			<div class="formCell w100">
				<div class="formPart">
					<label class="formLabel">Description</label>
					<div><?php echo $row->description?></div>
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
					<div><?php echo implode("; ", $features)?></div>


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
				</tr>
<?php
$count = count($parameters);

if($count == 0){
?>
<div style="padding-top:3px;">No Parameters Provided for this Apparatus.</div>
<?php
}
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
		<td class="paramTable">
			<?php echo $param->type?>
		</td>
		<td class="paramTable"><?php echo $param->min?></td>
		<td class="paramTable"><?php echo $param->max?></td>
		<td class="paramTable">
			<?php echo $param->unit?>
		</td>
		<td class="paramTable">
			<?php echo $param->prefix?>
		</td>
		<td class="paramTable"><?php echo $param->note?></td>
	</tr>
<?php
}
?>
			</table>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>

<?php
if(count($documents) != 999){
?>

	<fieldset class="mainFS">
		<legend class="mainFSLegend">Apparatus Documents</legend>
		<div id="docsWrapper">
			<div class="subDataHolder" id="documentRows">

<?php
$count = count($documents);

if($count == 0){
?>
<div style="padding-top:3px;">No Documents Provided for this Apparatus.</div>
<?php
}


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
	$path = $doc->path;
	//Get existing file name
	//$fileName = $db->get_var("select original_filename from apprepo.apparatus_document where uuid = '$uuid'");

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




				<div class="docRow">
					<div class="docCell" style="width:1160px;">
						<div class="formRow">
							<div class="formCell 16">
								<div class="formPart">
									<label class="formLabel">Document Type </label>
									<div><?php echo $doc->type?></div>
									<?php
									if($doc->type == "Other" && $doc->other_type != ""){
									?>
									<div><?php echo $doc->other_type?></div>
									<?php
									}
									?>
								</div>
							</div>
							<div class="formCell w16">
								<div class="formPart">
									<label class="formLabel">Document Format </label>
									<div><?php echo $doc->format?></div>
									<?php
									if($doc->format == "Other" && $doc->other_format != ""){
									?>
									<div><?php echo $doc->other_format?></div>
									<?php
									}
									?>
								</div>
							</div>
							<div class="formCell w50">
								<div class="formPart">
									<label class="formLabel">File</label>
									<div><a href="<?php echo $path?>" target="_blank"><?php echo $path?></a></div>
								</div>
							</div>
							<div class="formCell w16">
								<div class="formPart">
									<label class="formLabel">Document ID</label>
									<div><?php echo $doc->id?></div>
									<input id="uuid" type="hidden" value="<?php echo $doc->uuid?>"></input>
								</div>
							</div>
						</div>
						<div class="formRow">
							<div class="formCells w100">
								<div class="formPart">
									<label class="formLabel">Description</label>
									<div><?php echo $doc->description?></div>
								</div>
							</div>
						</div>
					</div>
					<div class="clearLeft"></div>
				</div>





<?php
//doSubmitEditApparatus()
}
?>


			</div>
		</div>
	</fieldset>
	<div class="fsSpacer"></div>
<?php
} // if count documents > 0
?>
</div>

	<div class="fsSpacer"></div>

	<div style="text-align:center;">
		<button class="submitButton" style="vertical-align:middle;" onclick="javascript:history.back();"><span>Back </span></button>
	</div>

<input type="hidden" id="apparatusPkey" value="<?php echo $pkey?>">

<div style="display:none;">
	<div class="docRow" id="sourceDocumentRow">
		<div class="docCell" style="width:1160px;">
			<div class="formRow">
				<div class="formCell 16">
					<div class="formPart">
						<label class="formLabel">Document Type <span class="reqStar">*</span></label>
						<select class="formControl formSelect" name="docFormat">
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
						<label class="formLabel">Document Format <span class="reqStar">*</span></label>
						<select class="formControl formSelect" name="docFormat">
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



<table id="holdingTable" style="display: none;"/>
<div id="holdingDiv" style="display: none;"/>


<?php
include("../includes/footer.php");
?>