<?
include("logincheck.php");

//print_r($_SESSION);

$userpkey = $_SESSION['userpkey'];

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];


include 'includes/header.php';
//get groups based on userpkey
?>

<style type="text/css">

.rowdiv {
	text-align:center;
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
  /*background-color: #4CAF50;*/ /* Green */
  /*border: none;*/
  /*color: white;*/
  padding: 3px 20px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 14px;
}

</style>


<script src='/assets/js/jquery/jquery.min.js'></script>
<script src="/assets/js/featherlight/featherlight.js"></script>

<?

if($_POST['submit']!=""){
	$ipkey = $_POST['i'];

	//$db->dumpVar($_POST);exit();
	
	if($ipkey == "" || !is_numeric($ipkey)){
		exit();
	}

/*
Array
(
    [instrument_name] => asdf
    [instrument_type] => Scanning Transmission Electron Microscopy (STEM)
    [instrument_brand] => ASD
    [instrument_model] => asd
    [university] => ADS
    [instrument_lab] => asd
    [data_collection_software] => ADS
    [data_collection_software_version] => ads
    [post_processing_software] => ADS
    [post_processing_software_version] => asdf
    [filament_type] => asdf
    [detectortype0] => asdf
    [detectormake0] => asdf
    [detectormodel0] => asdf
    [detectortype1] => asdf
    [detectormake1] => asdf
    [detectormodel1] => asfd
    [detectortype2] => 
    [detectormake2] => 
    [detectormodel2] => 
    [detectortype3] => 
    [detectormake3] => 
    [detectormodel3] => 
    [detectortype4] => 
    [detectormake4] => 
    [detectormodel4] => 
    [detectortype5] => 
    [detectormake5] => 
    [detectormodel5] => 
    [detectortype6] => 
    [detectormake6] => 
    [detectormodel6] => 
    [detectortype7] => 
    [detectormake7] => 
    [detectormodel7] => 
    [detectortype8] => 
    [detectormake8] => 
    [detectormodel8] => 
    [detectortype9] => 
    [detectormake9] => 
    [detectormodel9] => 
    [detectortype10] => 
    [detectormake10] => 
    [detectormodel10] => 
    [submit] => Save
    [i] => 1
*/

	//check values
	$institution_pkey = $_POST["i"];
	$instrument_name=$_POST["instrument_name"];
	$instrument_type=$_POST["instrument_type"];
	$instrument_brand=$_POST["instrument_brand"];
	$instrument_model=$_POST["instrument_model"];
	$university=$_POST["university"];
	$instrument_lab=$_POST["instrument_lab"];
	$data_collection_software=$_POST["data_collection_software"];
	$data_collection_software_version=$_POST["data_collection_software_version"];
	$post_processing_software=$_POST["post_processing_software"];
	$post_processing_software_version=$_POST["post_processing_software_version"];
	$filament_type=$_POST["filament_type"];
	$instrument_notes=$_POST["instrument_notes"];
	

	$detectortype0=$_POST["detectortype0"]; $detectormake0=$_POST["detectormake0"]; $detectormodel0=$_POST["detectormodel0"];
	$detectortype1=$_POST["detectortype1"]; $detectormake1=$_POST["detectormake1"]; $detectormodel1=$_POST["detectormodel1"];
	$detectortype2=$_POST["detectortype2"]; $detectormake2=$_POST["detectormake2"]; $detectormodel2=$_POST["detectormodel2"];
	$detectortype3=$_POST["detectortype3"]; $detectormake3=$_POST["detectormake3"]; $detectormodel3=$_POST["detectormodel3"];
	$detectortype4=$_POST["detectortype4"]; $detectormake4=$_POST["detectormake4"]; $detectormodel4=$_POST["detectormodel4"];
	$detectortype5=$_POST["detectortype5"]; $detectormake5=$_POST["detectormake5"]; $detectormodel5=$_POST["detectormodel5"];
	$detectortype6=$_POST["detectortype6"]; $detectormake6=$_POST["detectormake6"]; $detectormodel6=$_POST["detectormodel6"];
	$detectortype7=$_POST["detectortype7"]; $detectormake7=$_POST["detectormake7"]; $detectormodel7=$_POST["detectormodel7"];
	$detectortype8=$_POST["detectortype8"]; $detectormake8=$_POST["detectormake8"]; $detectormodel8=$_POST["detectormodel8"];
	$detectortype9=$_POST["detectortype9"]; $detectormake9=$_POST["detectormake9"]; $detectormodel9=$_POST["detectormodel9"];
	$detectortype10 =$_POST["detectortype10 "]; $detectormake10 =$_POST["detectormake10 "]; $detectormodel10 =$_POST["detectormodel10 "];

	
	
	$error = "";
	
	if($instrument_name=="" || $instrument_type==""){
		$error = "Instrument Name and Instrument Type are required!";
	}
	
	if($error==""){
	
		//put in database here and show success message
		$instrument_pkey = $db->get_var("select nextval('instrument_pkey_seq')");
		
		$db->query("
				insert into instrument values 
				(
					$instrument_pkey,
					$institution_pkey,
					'$instrument_name',
					'$instrument_type',
					'$instrument_brand',
					'$instrument_model',
					'$university',
					'$instrument_lab',
					'$data_collection_software',
					'$data_collection_software_version',
					'$post_processing_software',
					'$post_processing_software_version',
					'$filament_type',
					'$instrument_notes'
				)
		");
		
		if($detectortype0!=""){$db->query("insert into instrument_detector values ( nextval('instrument_detector_pkey_seq'), $instrument_pkey, '$detectortype0', '$detectormake0', '$detectormodel0' ) "); }
		if($detectortype1!=""){$db->query("insert into instrument_detector values ( nextval('instrument_detector_pkey_seq'), $instrument_pkey, '$detectortype1', '$detectormake1', '$detectormodel1' ) "); }
		if($detectortype2!=""){$db->query("insert into instrument_detector values ( nextval('instrument_detector_pkey_seq'), $instrument_pkey, '$detectortype2', '$detectormake2', '$detectormodel2' ) "); }
		if($detectortype3!=""){$db->query("insert into instrument_detector values ( nextval('instrument_detector_pkey_seq'), $instrument_pkey, '$detectortype3', '$detectormake3', '$detectormodel3' ) "); }
		if($detectortype4!=""){$db->query("insert into instrument_detector values ( nextval('instrument_detector_pkey_seq'), $instrument_pkey, '$detectortype4', '$detectormake4', '$detectormodel4' ) "); }
		if($detectortype5!=""){$db->query("insert into instrument_detector values ( nextval('instrument_detector_pkey_seq'), $instrument_pkey, '$detectortype5', '$detectormake5', '$detectormodel5' ) "); }
		if($detectortype6!=""){$db->query("insert into instrument_detector values ( nextval('instrument_detector_pkey_seq'), $instrument_pkey, '$detectortype6', '$detectormake6', '$detectormodel6' ) "); }
		if($detectortype7!=""){$db->query("insert into instrument_detector values ( nextval('instrument_detector_pkey_seq'), $instrument_pkey, '$detectortype7', '$detectormake7', '$detectormodel7' ) "); }
		if($detectortype8!=""){$db->query("insert into instrument_detector values ( nextval('instrument_detector_pkey_seq'), $instrument_pkey, '$detectortype8', '$detectormake8', '$detectormodel8' ) "); }
		if($detectortype9!=""){$db->query("insert into instrument_detector values ( nextval('instrument_detector_pkey_seq'), $instrument_pkey, '$detectortype9', '$detectormake9', '$detectormodel9' ) "); }
		if($detectortype10!=""){$db->query("insert into instrument_detector values ( nextval('instrument_detector_pkey_seq'), $instrument_pkey, '$detectortype10', '$detectormake10', '$detectormodel10' ) "); }

		?>
		


<div class="rowdiv">
	<h2 style="color:#009933;">Success!</h2>
</div>

<div class="rowdiv">
	Instrument has been successfully added.
</div>

<div class="rowdiv">
	<a href="instrumentcatalog">Continue</a>
</div>



		
		<?
	
		exit();
	}

}else{
	$ipkey = $_GET['i'];

	if($ipkey == "" || !is_numeric($ipkey)){
		exit();
	}
}

$instcount = $db->get_var("
	select count(*)
	from 
	instrument_users 
	where 
	users_pkey = $userpkey
	and institution_pkey = $ipkey;
");


if(!in_array($userpkey, $admin_pkeys) && $instcount == 0){
	exit();
}

$irow = $db->get_row("select * from instrument_institution where pkey = $ipkey");

?>

<form method="POST" onsubmit="return validateForm()">

<?
if($error!=""){
?>
<div class="rowdiv" style="color:red; font-size:1.5em;"><?=$error?></div>
<?
}
?>

<div class="rowdiv">
	<h2>Add Instrument - <?=$irow->name?></h2>
</div>

<div class="rowdiv">
	<div>Instrument Name: <span class="redred">*</span><input type="text" name="instrument_name" id="instrument_name" placeholder="e.g. SEM 1"></div>
</div>






<div class="rowdiv">
	<div>Instrument Type: <span class="redred">*</span><select name="instrument_type" id="instrument_type">
		<option value="">Select...</option>
		<option value="Optical Microscopy">Optical Microscopy</option>
		<option value="Transmission Electron Microscopy (TEM)">Transmission Electron Microscopy (TEM)</option>
		<option value="Scanning Transmission Electron Microscopy (STEM)">Scanning Transmission Electron Microscopy (STEM)</option>
		<option value="Scanning Electron Microscopy (SEM)">Scanning Electron Microscopy (SEM)</option>
		<option value="Electron Microprobe">Electron Microprobe</option>
		<option value="Fourier Transform Infrared Spectroscopy (FTIR)">Fourier Transform Infrared Spectroscopy (FTIR)</option>
		<option value="Raman Spectroscopy">Raman Spectroscopy</option>
		<option value="Atomic Force Microscopy (AFM)">Atomic Force Microscopy (AFM)</option>
	</select></div>
</div>

<div class="rowdiv">
	<div class="rowheader">Instrument Make:</div>
<div>

<div class="rowdiv">
	Brand: <input type="text" name="instrument_brand" placeholder="e.g. JEOL, Zeiss">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Model: <input type="text" name="instrument_model" placeholder="e.g. HM5000">
</div>

<div class="rowdiv">
	<div class="rowheader">Instrument Location:</div>
<div>

<div class="rowdiv">
	University: <input type="text" name="university" placeholder="e.g. Texas A&M">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Lab: <input type="text" name="instrument_lab" placeholder="e.g. Geo Lab">
</div>

<div class="rowdiv">
	<div class="rowheader">Software (Data Collection):</div>
<div>

<div class="rowdiv">
	Application: <input type="text" name="data_collection_software" placeholder="e.g. Aztec">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Version: <input type="text" name="data_collection_software_version" placeholder="e.g. 1.2.3">
</div>

<div class="rowdiv">
	<div class="rowheader">Software (Post-Processing):</div>
<div>

<div class="rowdiv">
	Application: <input type="text" name="post_processing_software" placeholder="e.g. Aztec">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Version: <input type="text" name="post_processing_software_version" placeholder="e.g. 1.2.3">
</div>

<div id="detectordetail" style="display:none;">
	<div class="rowdiv">
		Filament Type: <input type="text" name="filament_type">
	</div>
	
	<div class="rowdiv">
		<div class="rowheader">Detectors:</div>
	</div>
	
	<div class="rowdiv" id="detectorrow0" style="display:block;">Type: <input type="text" name="detectortype0" id="detectortype0" placeholder="e.g. EBSD, Spectrometer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Make: <input type="text" name="detectormake0" id="detectormake0" placeholder="e.g. Oxford">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Model: <input type="text" name="detectormodel0" id="detectormodel0" placeholder="e.g. Nordlys"></div>
	<div class="rowdiv" id="detectorrow1" style="display:none;">Type: <input type="text" name="detectortype1" id="detectortype1" placeholder="e.g. EBSD, Spectrometer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Make: <input type="text" name="detectormake1" id="detectormake1" placeholder="e.g. Oxford">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Model: <input type="text" name="detectormodel1" id="detectormodel1" placeholder="e.g. Nordlys"></div>
	<div class="rowdiv" id="detectorrow2" style="display:none;">Type: <input type="text" name="detectortype2" id="detectortype2" placeholder="e.g. EBSD, Spectrometer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Make: <input type="text" name="detectormake2" id="detectormake2" placeholder="e.g. Oxford">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Model: <input type="text" name="detectormodel2" id="detectormodel2" placeholder="e.g. Nordlys"></div>
	<div class="rowdiv" id="detectorrow3" style="display:none;">Type: <input type="text" name="detectortype3" id="detectortype3" placeholder="e.g. EBSD, Spectrometer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Make: <input type="text" name="detectormake3" id="detectormake3" placeholder="e.g. Oxford">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Model: <input type="text" name="detectormodel3" id="detectormodel3" placeholder="e.g. Nordlys"></div>
	<div class="rowdiv" id="detectorrow4" style="display:none;">Type: <input type="text" name="detectortype4" id="detectortype4" placeholder="e.g. EBSD, Spectrometer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Make: <input type="text" name="detectormake4" id="detectormake4" placeholder="e.g. Oxford">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Model: <input type="text" name="detectormodel4" id="detectormodel4" placeholder="e.g. Nordlys"></div>
	<div class="rowdiv" id="detectorrow5" style="display:none;">Type: <input type="text" name="detectortype5" id="detectortype5" placeholder="e.g. EBSD, Spectrometer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Make: <input type="text" name="detectormake5" id="detectormake5" placeholder="e.g. Oxford">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Model: <input type="text" name="detectormodel5" id="detectormodel5" placeholder="e.g. Nordlys"></div>
	<div class="rowdiv" id="detectorrow6" style="display:none;">Type: <input type="text" name="detectortype6" id="detectortype6" placeholder="e.g. EBSD, Spectrometer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Make: <input type="text" name="detectormake6" id="detectormake6" placeholder="e.g. Oxford">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Model: <input type="text" name="detectormodel6" id="detectormodel6" placeholder="e.g. Nordlys"></div>
	<div class="rowdiv" id="detectorrow7" style="display:none;">Type: <input type="text" name="detectortype7" id="detectortype7" placeholder="e.g. EBSD, Spectrometer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Make: <input type="text" name="detectormake7" id="detectormake7" placeholder="e.g. Oxford">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Model: <input type="text" name="detectormodel7" id="detectormodel7" placeholder="e.g. Nordlys"></div>
	<div class="rowdiv" id="detectorrow8" style="display:none;">Type: <input type="text" name="detectortype8" id="detectortype8" placeholder="e.g. EBSD, Spectrometer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Make: <input type="text" name="detectormake8" id="detectormake8" placeholder="e.g. Oxford">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Model: <input type="text" name="detectormodel8" id="detectormodel8" placeholder="e.g. Nordlys"></div>
	<div class="rowdiv" id="detectorrow9" style="display:none;">Type: <input type="text" name="detectortype9" id="detectortype9" placeholder="e.g. EBSD, Spectrometer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Make: <input type="text" name="detectormake9" id="detectormake9" placeholder="e.g. Oxford">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Model: <input type="text" name="detectormodel9" id="detectormodel9" placeholder="e.g. Nordlys"></div>
	<div class="rowdiv" id="detectorrow10" style="display:none;">Type: <input type="text" name="detectortype10" id="detectortype10" placeholder="e.g. EBSD, Spectrometer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Make: <input type="text" name="detectormake10" id="detectormake10" placeholder="e.g. Oxford">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Model: <input type="text" name="detectormodel10" id="detectormodel10" placeholder="e.g. Nordlys"></div>

	<div class="rowdiv">
		 <button onclick="adddetectorrow(); return false;" class="button">Add Additional Detector</button> 
	</div>
</div>

<div class="rowdiv">
	<div class="rowheader">Notes:</div>
<div>

<div class="rowdiv">
	<textarea name="instrument_notes" rows="5" cols="60"></textarea>
<div>

<div style="padding-top:10px; width:800px; text-align:right;">
	<input type="submit" value="Save" name="submit" class="button">
</div>

<input type="hidden" name="i" value="<?=$ipkey?>">

</form>

<script type='text/javascript'>
	addrownum = 1;
	
	function adddetectorrow(){
		$("#detectorrow" + addrownum).show();
		addrownum++;
	}
	
	function validateForm(){
		var instrumentName = $("#instrument_name").val();
		var instrumentType = $( "#instrument_type" ).val();
		if(instrumentName=="" || instrumentType==""){
			alert("Instrument Type and Instrument Name are required!");
			return false;
		}
	}
	
	function checkform(){
		var instrumentName = $("#instrument_name").val();
		var instrumentType = $( "#instrument_type" ).val();
		
		if(instrumentType != "Transmission Electron Microscopy (TEM)" && instrumentType != "Scanning Transmission Electron Microscopy (STEM)" && instrumentType != "Scanning Electron Microscopy (SEM)" && instrumentType != "Electron Microprobe"){
			//clear and hide
			$("#detectordetail").hide();
			$("#detectortype0").val(""); $("#detectormake0").val(""); $("#detectormodel0").val("");
			$("#detectortype1").val(""); $("#detectormake1").val(""); $("#detectormodel1").val(""); $("#detectorrow1").hide();
			$("#detectortype2").val(""); $("#detectormake2").val(""); $("#detectormodel2").val(""); $("#detectorrow2").hide();
			$("#detectortype3").val(""); $("#detectormake3").val(""); $("#detectormodel3").val(""); $("#detectorrow3").hide();
			$("#detectortype4").val(""); $("#detectormake4").val(""); $("#detectormodel4").val(""); $("#detectorrow4").hide();
			$("#detectortype5").val(""); $("#detectormake5").val(""); $("#detectormodel5").val(""); $("#detectorrow5").hide();
			$("#detectortype6").val(""); $("#detectormake6").val(""); $("#detectormodel6").val(""); $("#detectorrow6").hide();
			$("#detectortype7").val(""); $("#detectormake7").val(""); $("#detectormodel7").val(""); $("#detectorrow7").hide();
			$("#detectortype8").val(""); $("#detectormake8").val(""); $("#detectormodel8").val(""); $("#detectorrow8").hide();
			$("#detectortype9").val(""); $("#detectormake9").val(""); $("#detectormodel9").val(""); $("#detectorrow9").hide();
			$("#detectortype10").val(""); $("#detectormake10").val(""); $("#detectormodel10").val(""); $("#detectorrow10").hide();

		}else{
			$("#detectordetail").show();
		}
		
	}
	
	$("#instrument_type").change(function() {
		console.log( "Handler for .change() called." );
		checkform();
	});
	
	$("#instrument_name").keyup(function() {
		console.log( "Handler for .change() called." );
		checkform();
	});
	
</script>

<?
include 'includes/footer.php';
?>
