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

include("logincheck.php");

$userpkey = $_SESSION['userpkey'];

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

if(!in_array($userpkey, $admin_pkeys)){
	//only admins for now...
	$is_admin = false;
}else{
	$is_admin = true;
}

if($_POST['jsonData']!=""){

	//todo check for errors?

	if($error==""){

		$d = $_POST["jsonData"];
		$d = json_decode($d);
		$apparatus_pkey = $d->apparatus_pkey;
		$name = $d->apparatus_name;
		$type = $d->apparatus_type;
		$sub_type = $d->apparatus_subtype;
		$institute_pkey = $d->institute_pkey;
		$json = $_POST["jsonData"] ?? '';

		$apparatus_pkey = isset($d->apparatus_pkey) ? (int)$d->apparatus_pkey : 0;
		$arow = $db->get_row_prepared("SELECT * FROM exp_apparatus WHERE pkey = $1", array($apparatus_pkey));

		if(!$is_admin && $arow->userpkey != $userpkey){
			echo "not authorized";
			exit();
		}else{
			$db->prepare_query("UPDATE exp_apparatus SET
						name = $1,
						type = $2,
						sub_type = $3,
						json = $4,
						last_modified = now()
						WHERE
						pkey = $5
			", array($name, $type, $sub_type, $json, $apparatus_pkey));
		}
	}

	if(is_writeable("log.txt")){
		$data = print_r($d, true);
		file_put_contents("log.txt", $data, FILE_APPEND);
	}

	$out["result"] = "success";
	header('Content-Type: application/json');
	echo json_encode($out);

	exit();
}

$apparatus_pkey = isset($_GET['a']) ? (int)$_GET['a'] : 0;

if($apparatus_pkey == 0){
	echo "No apparatus provided.";
	exit();
}

$arow = $db->get_row_prepared("SELECT * FROM exp_apparatus WHERE pkey = $1", array($apparatus_pkey));

if(!$is_admin && $arow->userpkey != $userpkey){
	echo "not authorized";
	exit();
}

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

<div id="successwrapper" style="display:none;">
	<div class="rowdiv">
		<h2>Success!</h2>
	</div>
	<div class="rowdiv">
		Apparartus saved successfully.
	</div>
	<div class="rowdiv">
		<a href="apparatus_repository">Continue</a>
	</div>
</div>

<div id="wholewrapper">

	<!--<form method="POST" onsubmit="return validateForm()">-->

	<?php
	if($error!=""){
	?>
	<div class="rowdiv" style="color:red; font-size:1.5em;"><?php echo $error?></div>
	<?php
	}
	?>

	<div class="rowdiv">
		<h2>Edit Apparatus</h2>
	</div>

	<div class="rowdiv">
		<h2>Facility Information</h2>
	</div>

	<input type="hidden" id="institute_pkey" value="<?php echo $j->institute_pkey?>">

	<div class="rowdiv">
		Lab Name: <input type="text" id="lab_name" name="lab_name" value="<?php echo $j->lab_name?>"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Facility ID: <input type="text" id="facility_id" name="facility_id" value="<?php echo $j->facility_id?>">
	</div>

	<div class="rowdiv">
		Website: <input type="text" id="website" name="website" placeholder="https://ku.edu" value="<?php echo $j->website?>">
	</div>

	<div class="rowdiv rowheader">Address</div>

	<div class="rowdiv">
		Country:

		<select name="country" id="country">
			<option value="">Select...</option>
			<option value="United States"<?php if($j->country=="United States"){echo " selected";}?>>United States</option>
			<option value="Afghanistan"<?php if($j->country=="Afghanistan"){echo " selected";}?>>Afghanistan</option>
			<option value="Åland Islands"<?php if($j->country=="Åland Islands"){echo " selected";}?>>Åland Islands</option>
			<option value="Albania"<?php if($j->country=="Albania"){echo " selected";}?>>Albania</option>
			<option value="Algeria"<?php if($j->country=="Algeria"){echo " selected";}?>>Algeria</option>
			<option value="American Samoa"<?php if($j->country=="American Samoa"){echo " selected";}?>>American Samoa</option>
			<option value="Andorra"<?php if($j->country=="Andorra"){echo " selected";}?>>Andorra</option>
			<option value="Angola"<?php if($j->country=="Angola"){echo " selected";}?>>Angola</option>
			<option value="Anguilla"<?php if($j->country=="Anguilla"){echo " selected";}?>>Anguilla</option>
			<option value="Antarctica"<?php if($j->country=="Antarctica"){echo " selected";}?>>Antarctica</option>
			<option value="Antigua and Barbuda"<?php if($j->country=="Antigua and Barbuda"){echo " selected";}?>>Antigua and Barbuda</option>
			<option value="Argentina"<?php if($j->country=="Argentina"){echo " selected";}?>>Argentina</option>
			<option value="Armenia"<?php if($j->country=="Armenia"){echo " selected";}?>>Armenia</option>
			<option value="Aruba"<?php if($j->country=="Aruba"){echo " selected";}?>>Aruba</option>
			<option value="Australia"<?php if($j->country=="Australia"){echo " selected";}?>>Australia</option>
			<option value="Austria"<?php if($j->country=="Austria"){echo " selected";}?>>Austria</option>
			<option value="Azerbaijan"<?php if($j->country=="Azerbaijan"){echo " selected";}?>>Azerbaijan</option>
			<option value="Bahamas"<?php if($j->country=="Bahamas"){echo " selected";}?>>Bahamas</option>
			<option value="Bahrain"<?php if($j->country=="Bahrain"){echo " selected";}?>>Bahrain</option>
			<option value="Bangladesh"<?php if($j->country=="Bangladesh"){echo " selected";}?>>Bangladesh</option>
			<option value="Barbados"<?php if($j->country=="Barbados"){echo " selected";}?>>Barbados</option>
			<option value="Belarus"<?php if($j->country=="Belarus"){echo " selected";}?>>Belarus</option>
			<option value="Belgium"<?php if($j->country=="Belgium"){echo " selected";}?>>Belgium</option>
			<option value="Belize"<?php if($j->country=="Belize"){echo " selected";}?>>Belize</option>
			<option value="Benin"<?php if($j->country=="Benin"){echo " selected";}?>>Benin</option>
			<option value="Bermuda"<?php if($j->country=="Bermuda"){echo " selected";}?>>Bermuda</option>
			<option value="Bhutan"<?php if($j->country=="Bhutan"){echo " selected";}?>>Bhutan</option>
			<option value="Bolivia, Plurinational State of"<?php if($j->country=="Bolivia, Plurinational State of"){echo " selected";}?>>Bolivia, Plurinational State of</option>
			<option value="Bonaire, Sint Eustatius and Saba"<?php if($j->country=="Bonaire, Sint Eustatius and Saba"){echo " selected";}?>>Bonaire, Sint Eustatius and Saba</option>
			<option value="Bosnia and Herzegovina"<?php if($j->country=="Bosnia and Herzegovina"){echo " selected";}?>>Bosnia and Herzegovina</option>
			<option value="Botswana"<?php if($j->country=="Botswana"){echo " selected";}?>>Botswana</option>
			<option value="Bouvet Island"<?php if($j->country=="Bouvet Island"){echo " selected";}?>>Bouvet Island</option>
			<option value="Brazil"<?php if($j->country=="Brazil"){echo " selected";}?>>Brazil</option>
			<option value="British Indian Ocean Territory"<?php if($j->country=="British Indian Ocean Territory"){echo " selected";}?>>British Indian Ocean Territory</option>
			<option value="Brunei Darussalam"<?php if($j->country=="Brunei Darussalam"){echo " selected";}?>>Brunei Darussalam</option>
			<option value="Bulgaria"<?php if($j->country=="Bulgaria"){echo " selected";}?>>Bulgaria</option>
			<option value="Burkina Faso"<?php if($j->country=="Burkina Faso"){echo " selected";}?>>Burkina Faso</option>
			<option value="Burundi"<?php if($j->country=="Burundi"){echo " selected";}?>>Burundi</option>
			<option value="Cambodia"<?php if($j->country=="Cambodia"){echo " selected";}?>>Cambodia</option>
			<option value="Cameroon"<?php if($j->country=="Cameroon"){echo " selected";}?>>Cameroon</option>
			<option value="Canada"<?php if($j->country=="Canada"){echo " selected";}?>>Canada</option>
			<option value="Cape Verde"<?php if($j->country=="Cape Verde"){echo " selected";}?>>Cape Verde</option>
			<option value="Cayman Islands"<?php if($j->country=="Cayman Islands"){echo " selected";}?>>Cayman Islands</option>
			<option value="Central African Republic"<?php if($j->country=="Central African Republic"){echo " selected";}?>>Central African Republic</option>
			<option value="Chad"<?php if($j->country=="Chad"){echo " selected";}?>>Chad</option>
			<option value="Chile"<?php if($j->country=="Chile"){echo " selected";}?>>Chile</option>
			<option value="China"<?php if($j->country=="China"){echo " selected";}?>>China</option>
			<option value="Christmas Island"<?php if($j->country=="Christmas Island"){echo " selected";}?>>Christmas Island</option>
			<option value="Cocos (Keeling) Islands"<?php if($j->country=="Cocos (Keeling) Islands"){echo " selected";}?>>Cocos (Keeling) Islands</option>
			<option value="Colombia"<?php if($j->country=="Colombia"){echo " selected";}?>>Colombia</option>
			<option value="Comoros"<?php if($j->country=="Comoros"){echo " selected";}?>>Comoros</option>
			<option value="Congo"<?php if($j->country=="Congo"){echo " selected";}?>>Congo</option>
			<option value="Congo, the Democratic Republic of the"<?php if($j->country=="Congo, the Democratic Republic of the"){echo " selected";}?>>Congo, the Democratic Republic of the</option>
			<option value="Cook Islands"<?php if($j->country=="Cook Islands"){echo " selected";}?>>Cook Islands</option>
			<option value="Costa Rica"<?php if($j->country=="Costa Rica"){echo " selected";}?>>Costa Rica</option>
			<option value="Côte d'Ivoire"<?php if($j->country=="Côte d'Ivoire"){echo " selected";}?>>Côte d'Ivoire</option>
			<option value="Croatia"<?php if($j->country=="Croatia"){echo " selected";}?>>Croatia</option>
			<option value="Cuba"<?php if($j->country=="Cuba"){echo " selected";}?>>Cuba</option>
			<option value="Curaçao"<?php if($j->country=="Curaçao"){echo " selected";}?>>Curaçao</option>
			<option value="Cyprus"<?php if($j->country=="Cyprus"){echo " selected";}?>>Cyprus</option>
			<option value="Czech Republic"<?php if($j->country=="Czech Republic"){echo " selected";}?>>Czech Republic</option>
			<option value="Denmark"<?php if($j->country=="Denmark"){echo " selected";}?>>Denmark</option>
			<option value="Djibouti"<?php if($j->country=="Djibouti"){echo " selected";}?>>Djibouti</option>
			<option value="Dominica"<?php if($j->country=="Dominica"){echo " selected";}?>>Dominica</option>
			<option value="Dominican Republic"<?php if($j->country=="Dominican Republic"){echo " selected";}?>>Dominican Republic</option>
			<option value="Ecuador"<?php if($j->country=="Ecuador"){echo " selected";}?>>Ecuador</option>
			<option value="Egypt"<?php if($j->country=="Egypt"){echo " selected";}?>>Egypt</option>
			<option value="El Salvador"<?php if($j->country=="El Salvador"){echo " selected";}?>>El Salvador</option>
			<option value="Equatorial Guinea"<?php if($j->country=="Equatorial Guinea"){echo " selected";}?>>Equatorial Guinea</option>
			<option value="Eritrea"<?php if($j->country=="Eritrea"){echo " selected";}?>>Eritrea</option>
			<option value="Estonia"<?php if($j->country=="Estonia"){echo " selected";}?>>Estonia</option>
			<option value="Ethiopia"<?php if($j->country=="Ethiopia"){echo " selected";}?>>Ethiopia</option>
			<option value="Falkland Islands (Malvinas)"<?php if($j->country=="Falkland Islands (Malvinas)"){echo " selected";}?>>Falkland Islands (Malvinas)</option>
			<option value="Faroe Islands"<?php if($j->country=="Faroe Islands"){echo " selected";}?>>Faroe Islands</option>
			<option value="Fiji"<?php if($j->country=="Fiji"){echo " selected";}?>>Fiji</option>
			<option value="Finland"<?php if($j->country=="Finland"){echo " selected";}?>>Finland</option>
			<option value="France"<?php if($j->country=="France"){echo " selected";}?>>France</option>
			<option value="French Guiana"<?php if($j->country=="French Guiana"){echo " selected";}?>>French Guiana</option>
			<option value="French Polynesia"<?php if($j->country=="French Polynesia"){echo " selected";}?>>French Polynesia</option>
			<option value="French Southern Territories"<?php if($j->country=="French Southern Territories"){echo " selected";}?>>French Southern Territories</option>
			<option value="Gabon"<?php if($j->country=="Gabon"){echo " selected";}?>>Gabon</option>
			<option value="Gambia"<?php if($j->country=="Gambia"){echo " selected";}?>>Gambia</option>
			<option value="Georgia"<?php if($j->country=="Georgia"){echo " selected";}?>>Georgia</option>
			<option value="Germany"<?php if($j->country=="Germany"){echo " selected";}?>>Germany</option>
			<option value="Ghana"<?php if($j->country=="Ghana"){echo " selected";}?>>Ghana</option>
			<option value="Gibraltar"<?php if($j->country=="Gibraltar"){echo " selected";}?>>Gibraltar</option>
			<option value="Greece"<?php if($j->country=="Greece"){echo " selected";}?>>Greece</option>
			<option value="Greenland"<?php if($j->country=="Greenland"){echo " selected";}?>>Greenland</option>
			<option value="Grenada"<?php if($j->country=="Grenada"){echo " selected";}?>>Grenada</option>
			<option value="Guadeloupe"<?php if($j->country=="Guadeloupe"){echo " selected";}?>>Guadeloupe</option>
			<option value="Guam"<?php if($j->country=="Guam"){echo " selected";}?>>Guam</option>
			<option value="Guatemala"<?php if($j->country=="Guatemala"){echo " selected";}?>>Guatemala</option>
			<option value="Guernsey"<?php if($j->country=="Guernsey"){echo " selected";}?>>Guernsey</option>
			<option value="Guinea"<?php if($j->country=="Guinea"){echo " selected";}?>>Guinea</option>
			<option value="Guinea-Bissau"<?php if($j->country=="Guinea-Bissau"){echo " selected";}?>>Guinea-Bissau</option>
			<option value="Guyana"<?php if($j->country=="Guyana"){echo " selected";}?>>Guyana</option>
			<option value="Haiti"<?php if($j->country=="Haiti"){echo " selected";}?>>Haiti</option>
			<option value="Heard Island and McDonald Islands"<?php if($j->country=="Heard Island and McDonald Islands"){echo " selected";}?>>Heard Island and McDonald Islands</option>
			<option value="Holy See (Vatican City State)"<?php if($j->country=="Holy See (Vatican City State)"){echo " selected";}?>>Holy See (Vatican City State)</option>
			<option value="Honduras"<?php if($j->country=="Honduras"){echo " selected";}?>>Honduras</option>
			<option value="Hong Kong"<?php if($j->country=="Hong Kong"){echo " selected";}?>>Hong Kong</option>
			<option value="Hungary"<?php if($j->country=="Hungary"){echo " selected";}?>>Hungary</option>
			<option value="Iceland"<?php if($j->country=="Iceland"){echo " selected";}?>>Iceland</option>
			<option value="India"<?php if($j->country=="India"){echo " selected";}?>>India</option>
			<option value="Indonesia"<?php if($j->country=="Indonesia"){echo " selected";}?>>Indonesia</option>
			<option value="Iran, Islamic Republic of"<?php if($j->country=="Iran, Islamic Republic of"){echo " selected";}?>>Iran, Islamic Republic of</option>
			<option value="Iraq"<?php if($j->country=="Iraq"){echo " selected";}?>>Iraq</option>
			<option value="Ireland"<?php if($j->country=="Ireland"){echo " selected";}?>>Ireland</option>
			<option value="Isle of Man"<?php if($j->country=="Isle of Man"){echo " selected";}?>>Isle of Man</option>
			<option value="Israel"<?php if($j->country=="Israel"){echo " selected";}?>>Israel</option>
			<option value="Italy"<?php if($j->country=="Italy"){echo " selected";}?>>Italy</option>
			<option value="Jamaica"<?php if($j->country=="Jamaica"){echo " selected";}?>>Jamaica</option>
			<option value="Japan"<?php if($j->country=="Japan"){echo " selected";}?>>Japan</option>
			<option value="Jersey"<?php if($j->country=="Jersey"){echo " selected";}?>>Jersey</option>
			<option value="Jordan"<?php if($j->country=="Jordan"){echo " selected";}?>>Jordan</option>
			<option value="Kazakhstan"<?php if($j->country=="Kazakhstan"){echo " selected";}?>>Kazakhstan</option>
			<option value="Kenya"<?php if($j->country=="Kenya"){echo " selected";}?>>Kenya</option>
			<option value="Kiribati"<?php if($j->country=="Kiribati"){echo " selected";}?>>Kiribati</option>
			<option value="Korea, Democratic People's Republic of"<?php if($j->country=="Korea, Democratic People's Republic of"){echo " selected";}?>>Korea, Democratic People's Republic of</option>
			<option value="Korea, Republic of"<?php if($j->country=="Korea, Republic of"){echo " selected";}?>>Korea, Republic of</option>
			<option value="Kuwait"<?php if($j->country=="Kuwait"){echo " selected";}?>>Kuwait</option>
			<option value="Kyrgyzstan"<?php if($j->country=="Kyrgyzstan"){echo " selected";}?>>Kyrgyzstan</option>
			<option value="Lao People's Democratic Republic"<?php if($j->country=="Lao People's Democratic Republic"){echo " selected";}?>>Lao People's Democratic Republic</option>
			<option value="Latvia"<?php if($j->country=="Latvia"){echo " selected";}?>>Latvia</option>
			<option value="Lebanon"<?php if($j->country=="Lebanon"){echo " selected";}?>>Lebanon</option>
			<option value="Lesotho"<?php if($j->country=="Lesotho"){echo " selected";}?>>Lesotho</option>
			<option value="Liberia"<?php if($j->country=="Liberia"){echo " selected";}?>>Liberia</option>
			<option value="Libya"<?php if($j->country=="Libya"){echo " selected";}?>>Libya</option>
			<option value="Liechtenstein"<?php if($j->country=="Liechtenstein"){echo " selected";}?>>Liechtenstein</option>
			<option value="Lithuania"<?php if($j->country=="Lithuania"){echo " selected";}?>>Lithuania</option>
			<option value="Luxembourg"<?php if($j->country=="Luxembourg"){echo " selected";}?>>Luxembourg</option>
			<option value="Macao"<?php if($j->country=="Macao"){echo " selected";}?>>Macao</option>
			<option value="Macedonia, the former Yugoslav Republic of"<?php if($j->country=="Macedonia, the former Yugoslav Republic of"){echo " selected";}?>>Macedonia, the former Yugoslav Republic of</option>
			<option value="Madagascar"<?php if($j->country=="Madagascar"){echo " selected";}?>>Madagascar</option>
			<option value="Malawi"<?php if($j->country=="Malawi"){echo " selected";}?>>Malawi</option>
			<option value="Malaysia"<?php if($j->country=="Malaysia"){echo " selected";}?>>Malaysia</option>
			<option value="Maldives"<?php if($j->country=="Maldives"){echo " selected";}?>>Maldives</option>
			<option value="Mali"<?php if($j->country=="Mali"){echo " selected";}?>>Mali</option>
			<option value="Malta"<?php if($j->country=="Malta"){echo " selected";}?>>Malta</option>
			<option value="Marshall Islands"<?php if($j->country=="Marshall Islands"){echo " selected";}?>>Marshall Islands</option>
			<option value="Martinique"<?php if($j->country=="Martinique"){echo " selected";}?>>Martinique</option>
			<option value="Mauritania"<?php if($j->country=="Mauritania"){echo " selected";}?>>Mauritania</option>
			<option value="Mauritius"<?php if($j->country=="Mauritius"){echo " selected";}?>>Mauritius</option>
			<option value="Mayotte"<?php if($j->country=="Mayotte"){echo " selected";}?>>Mayotte</option>
			<option value="Mexico"<?php if($j->country=="Mexico"){echo " selected";}?>>Mexico</option>
			<option value="Micronesia, Federated States of"<?php if($j->country=="Micronesia, Federated States of"){echo " selected";}?>>Micronesia, Federated States of</option>
			<option value="Moldova, Republic of"<?php if($j->country=="Moldova, Republic of"){echo " selected";}?>>Moldova, Republic of</option>
			<option value="Monaco"<?php if($j->country=="Monaco"){echo " selected";}?>>Monaco</option>
			<option value="Mongolia"<?php if($j->country=="Mongolia"){echo " selected";}?>>Mongolia</option>
			<option value="Montenegro"<?php if($j->country=="Montenegro"){echo " selected";}?>>Montenegro</option>
			<option value="Montserrat"<?php if($j->country=="Montserrat"){echo " selected";}?>>Montserrat</option>
			<option value="Morocco"<?php if($j->country=="Morocco"){echo " selected";}?>>Morocco</option>
			<option value="Mozambique"<?php if($j->country=="Mozambique"){echo " selected";}?>>Mozambique</option>
			<option value="Myanmar"<?php if($j->country=="Myanmar"){echo " selected";}?>>Myanmar</option>
			<option value="Namibia"<?php if($j->country=="Namibia"){echo " selected";}?>>Namibia</option>
			<option value="Nauru"<?php if($j->country=="Nauru"){echo " selected";}?>>Nauru</option>
			<option value="Nepal"<?php if($j->country=="Nepal"){echo " selected";}?>>Nepal</option>
			<option value="Netherlands"<?php if($j->country=="Netherlands"){echo " selected";}?>>Netherlands</option>
			<option value="New Caledonia"<?php if($j->country=="New Caledonia"){echo " selected";}?>>New Caledonia</option>
			<option value="New Zealand"<?php if($j->country=="New Zealand"){echo " selected";}?>>New Zealand</option>
			<option value="Nicaragua"<?php if($j->country=="Nicaragua"){echo " selected";}?>>Nicaragua</option>
			<option value="Niger"<?php if($j->country=="Niger"){echo " selected";}?>>Niger</option>
			<option value="Nigeria"<?php if($j->country=="Nigeria"){echo " selected";}?>>Nigeria</option>
			<option value="Niue"<?php if($j->country=="Niue"){echo " selected";}?>>Niue</option>
			<option value="Norfolk Island"<?php if($j->country=="Norfolk Island"){echo " selected";}?>>Norfolk Island</option>
			<option value="Northern Mariana Islands"<?php if($j->country=="Northern Mariana Islands"){echo " selected";}?>>Northern Mariana Islands</option>
			<option value="Norway"<?php if($j->country=="Norway"){echo " selected";}?>>Norway</option>
			<option value="Oman"<?php if($j->country=="Oman"){echo " selected";}?>>Oman</option>
			<option value="Pakistan"<?php if($j->country=="Pakistan"){echo " selected";}?>>Pakistan</option>
			<option value="Palau"<?php if($j->country=="Palau"){echo " selected";}?>>Palau</option>
			<option value="Palestinian Territory, Occupied"<?php if($j->country=="Palestinian Territory, Occupied"){echo " selected";}?>>Palestinian Territory, Occupied</option>
			<option value="Panama"<?php if($j->country=="Panama"){echo " selected";}?>>Panama</option>
			<option value="Papua New Guinea"<?php if($j->country=="Papua New Guinea"){echo " selected";}?>>Papua New Guinea</option>
			<option value="Paraguay"<?php if($j->country=="Paraguay"){echo " selected";}?>>Paraguay</option>
			<option value="Peru"<?php if($j->country=="Peru"){echo " selected";}?>>Peru</option>
			<option value="Philippines"<?php if($j->country=="Philippines"){echo " selected";}?>>Philippines</option>
			<option value="Pitcairn"<?php if($j->country=="Pitcairn"){echo " selected";}?>>Pitcairn</option>
			<option value="Poland"<?php if($j->country=="Poland"){echo " selected";}?>>Poland</option>
			<option value="Portugal"<?php if($j->country=="Portugal"){echo " selected";}?>>Portugal</option>
			<option value="Puerto Rico"<?php if($j->country=="Puerto Rico"){echo " selected";}?>>Puerto Rico</option>
			<option value="Qatar"<?php if($j->country=="Qatar"){echo " selected";}?>>Qatar</option>
			<option value="Réunion"<?php if($j->country=="Réunion"){echo " selected";}?>>Réunion</option>
			<option value="Romania"<?php if($j->country=="Romania"){echo " selected";}?>>Romania</option>
			<option value="Russian Federation"<?php if($j->country=="Russian Federation"){echo " selected";}?>>Russian Federation</option>
			<option value="Rwanda"<?php if($j->country=="Rwanda"){echo " selected";}?>>Rwanda</option>
			<option value="Saint Barthélemy"<?php if($j->country=="Saint Barthélemy"){echo " selected";}?>>Saint Barthélemy</option>
			<option value="Saint Helena, Ascension and Tristan da Cunha"<?php if($j->country=="Saint Helena, Ascension and Tristan da Cunha"){echo " selected";}?>>Saint Helena, Ascension and Tristan da Cunha</option>
			<option value="Saint Kitts and Nevis"<?php if($j->country=="Saint Kitts and Nevis"){echo " selected";}?>>Saint Kitts and Nevis</option>
			<option value="Saint Lucia"<?php if($j->country=="Saint Lucia"){echo " selected";}?>>Saint Lucia</option>
			<option value="Saint Martin (French part)"<?php if($j->country=="Saint Martin (French part)"){echo " selected";}?>>Saint Martin (French part)</option>
			<option value="Saint Pierre and Miquelon"<?php if($j->country=="Saint Pierre and Miquelon"){echo " selected";}?>>Saint Pierre and Miquelon</option>
			<option value="Saint Vincent and the Grenadines"<?php if($j->country=="Saint Vincent and the Grenadines"){echo " selected";}?>>Saint Vincent and the Grenadines</option>
			<option value="Samoa"<?php if($j->country=="Samoa"){echo " selected";}?>>Samoa</option>
			<option value="San Marino"<?php if($j->country=="San Marino"){echo " selected";}?>>San Marino</option>
			<option value="Sao Tome and Principe"<?php if($j->country=="Sao Tome and Principe"){echo " selected";}?>>Sao Tome and Principe</option>
			<option value="Saudi Arabia"<?php if($j->country=="Saudi Arabia"){echo " selected";}?>>Saudi Arabia</option>
			<option value="Senegal"<?php if($j->country=="Senegal"){echo " selected";}?>>Senegal</option>
			<option value="Serbia"<?php if($j->country=="Serbia"){echo " selected";}?>>Serbia</option>
			<option value="Seychelles"<?php if($j->country=="Seychelles"){echo " selected";}?>>Seychelles</option>
			<option value="Sierra Leone"<?php if($j->country=="Sierra Leone"){echo " selected";}?>>Sierra Leone</option>
			<option value="Singapore"<?php if($j->country=="Singapore"){echo " selected";}?>>Singapore</option>
			<option value="Sint Maarten (Dutch part)"<?php if($j->country=="Sint Maarten (Dutch part)"){echo " selected";}?>>Sint Maarten (Dutch part)</option>
			<option value="Slovakia"<?php if($j->country=="Slovakia"){echo " selected";}?>>Slovakia</option>
			<option value="Slovenia"<?php if($j->country=="Slovenia"){echo " selected";}?>>Slovenia</option>
			<option value="Solomon Islands"<?php if($j->country=="Solomon Islands"){echo " selected";}?>>Solomon Islands</option>
			<option value="Somalia"<?php if($j->country=="Somalia"){echo " selected";}?>>Somalia</option>
			<option value="South Africa"<?php if($j->country=="South Africa"){echo " selected";}?>>South Africa</option>
			<option value="South Georgia and the South Sandwich Islands"<?php if($j->country=="South Georgia and the South Sandwich Islands"){echo " selected";}?>>South Georgia and the South Sandwich Islands</option>
			<option value="South Sudan"<?php if($j->country=="South Sudan"){echo " selected";}?>>South Sudan</option>
			<option value="Spain"<?php if($j->country=="Spain"){echo " selected";}?>>Spain</option>
			<option value="Sri Lanka"<?php if($j->country=="Sri Lanka"){echo " selected";}?>>Sri Lanka</option>
			<option value="Sudan"<?php if($j->country=="Sudan"){echo " selected";}?>>Sudan</option>
			<option value="Suriname"<?php if($j->country=="Suriname"){echo " selected";}?>>Suriname</option>
			<option value="Svalbard and Jan Mayen"<?php if($j->country=="Svalbard and Jan Mayen"){echo " selected";}?>>Svalbard and Jan Mayen</option>
			<option value="Swaziland"<?php if($j->country=="Swaziland"){echo " selected";}?>>Swaziland</option>
			<option value="Sweden"<?php if($j->country=="Sweden"){echo " selected";}?>>Sweden</option>
			<option value="Switzerland"<?php if($j->country=="Switzerland"){echo " selected";}?>>Switzerland</option>
			<option value="Syrian Arab Republic"<?php if($j->country=="Syrian Arab Republic"){echo " selected";}?>>Syrian Arab Republic</option>
			<option value="Taiwan, Province of China"<?php if($j->country=="Taiwan, Province of China"){echo " selected";}?>>Taiwan, Province of China</option>
			<option value="Tajikistan"<?php if($j->country=="Tajikistan"){echo " selected";}?>>Tajikistan</option>
			<option value="Tanzania, United Republic of"<?php if($j->country=="Tanzania, United Republic of"){echo " selected";}?>>Tanzania, United Republic of</option>
			<option value="Thailand"<?php if($j->country=="Thailand"){echo " selected";}?>>Thailand</option>
			<option value="Timor-Leste"<?php if($j->country=="Timor-Leste"){echo " selected";}?>>Timor-Leste</option>
			<option value="Togo"<?php if($j->country=="Togo"){echo " selected";}?>>Togo</option>
			<option value="Tokelau"<?php if($j->country=="Tokelau"){echo " selected";}?>>Tokelau</option>
			<option value="Tonga"<?php if($j->country=="Tonga"){echo " selected";}?>>Tonga</option>
			<option value="Trinidad and Tobago"<?php if($j->country=="Trinidad and Tobago"){echo " selected";}?>>Trinidad and Tobago</option>
			<option value="Tunisia"<?php if($j->country=="Tunisia"){echo " selected";}?>>Tunisia</option>
			<option value="Turkey"<?php if($j->country=="Turkey"){echo " selected";}?>>Turkey</option>
			<option value="Turkmenistan"<?php if($j->country=="Turkmenistan"){echo " selected";}?>>Turkmenistan</option>
			<option value="Turks and Caicos Islands"<?php if($j->country=="Turks and Caicos Islands"){echo " selected";}?>>Turks and Caicos Islands</option>
			<option value="Tuvalu"<?php if($j->country=="Tuvalu"){echo " selected";}?>>Tuvalu</option>
			<option value="Uganda"<?php if($j->country=="Uganda"){echo " selected";}?>>Uganda</option>
			<option value="Ukraine"<?php if($j->country=="Ukraine"){echo " selected";}?>>Ukraine</option>
			<option value="United Arab Emirates"<?php if($j->country=="United Arab Emirates"){echo " selected";}?>>United Arab Emirates</option>
			<option value="United Kingdom"<?php if($j->country=="United Kingdom"){echo " selected";}?>>United Kingdom</option>
			<option value="United States Minor Outlying Islands"<?php if($j->country=="United States Minor Outlying Islands"){echo " selected";}?>>United States Minor Outlying Islands</option>
			<option value="Uruguay"<?php if($j->country=="Uruguay"){echo " selected";}?>>Uruguay</option>
			<option value="Uzbekistan"<?php if($j->country=="Uzbekistan"){echo " selected";}?>>Uzbekistan</option>
			<option value="Vanuatu"<?php if($j->country=="Vanuatu"){echo " selected";}?>>Vanuatu</option>
			<option value="Venezuela, Bolivarian Republic of"<?php if($j->country=="Venezuela, Bolivarian Republic of"){echo " selected";}?>>Venezuela, Bolivarian Republic of</option>
			<option value="Viet Nam"<?php if($j->country=="Viet Nam"){echo " selected";}?>>Viet Nam</option>
			<option value="Virgin Islands, British"<?php if($j->country=="Virgin Islands, British"){echo " selected";}?>>Virgin Islands, British</option>
			<option value="Virgin Islands, U.S."<?php if($j->country=="Virgin Islands, U.S."){echo " selected";}?>>Virgin Islands, U.S.</option>
			<option value="Wallis and Futuna"<?php if($j->country=="Wallis and Futuna"){echo " selected";}?>>Wallis and Futuna</option>
			<option value="Western Sahara"<?php if($j->country=="Western Sahara"){echo " selected";}?>>Western Sahara</option>
			<option value="Yemen"<?php if($j->country=="Yemen"){echo " selected";}?>>Yemen</option>
			<option value="Zambia"<?php if($j->country=="Zambia"){echo " selected";}?>>Zambia</option>
			<option value="Zimbabwe"<?php if($j->country=="Zimbabwe"){echo " selected";}?>>Zimbabwe</option>
		</select>

		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span name="stateprovincewrapper" id="stateprovincewrapper" style="width: 300px;">
			<?php
			if($j->country=="United States"){
				//select with us states
				?>
				State: <select id="state" name="state">
					<option value="">Select...</option>
					<option value="Alabama"<?php if($d->state=="Alabama"){echo " selected";}?>>Alabama</option>
					<option value="Alaska"<?php if($d->state=="Alaska"){echo " selected";}?>>Alaska</option>
					<option value="Arizona"<?php if($d->state=="Arizona"){echo " selected";}?>>Arizona</option>
					<option value="Arkansas"<?php if($d->state=="Arkansas"){echo " selected";}?>>Arkansas</option>
					<option value="California"<?php if($d->state=="California"){echo " selected";}?>>California</option>
					<option value="Colorado"<?php if($d->state=="Colorado"){echo " selected";}?>>Colorado</option>
					<option value="Connecticut"<?php if($d->state=="Connecticut"){echo " selected";}?>>Connecticut</option>
					<option value="Delaware"<?php if($d->state=="Delaware"){echo " selected";}?>>Delaware</option>
					<option value="District Of Columbia"<?php if($d->state=="District Of Columbia"){echo " selected";}?>>District Of Columbia</option>
					<option value="Florida"<?php if($d->state=="Florida"){echo " selected";}?>>Florida</option>
					<option value="Georgia"<?php if($d->state=="Georgia"){echo " selected";}?>>Georgia</option>
					<option value="Hawaii"<?php if($d->state=="Hawaii"){echo " selected";}?>>Hawaii</option>
					<option value="Idaho"<?php if($d->state=="Idaho"){echo " selected";}?>>Idaho</option>
					<option value="Illinois"<?php if($d->state=="Illinois"){echo " selected";}?>>Illinois</option>
					<option value="Indiana"<?php if($d->state=="Indiana"){echo " selected";}?>>Indiana</option>
					<option value="Iowa"<?php if($d->state=="Iowa"){echo " selected";}?>>Iowa</option>
					<option value="Kansas"<?php if($d->state=="Kansas"){echo " selected";}?>>Kansas</option>
					<option value="Kentucky"<?php if($d->state=="Kentucky"){echo " selected";}?>>Kentucky</option>
					<option value="Louisiana"<?php if($d->state=="Louisiana"){echo " selected";}?>>Louisiana</option>
					<option value="Maine"<?php if($d->state=="Maine"){echo " selected";}?>>Maine</option>
					<option value="Maryland"<?php if($d->state=="Maryland"){echo " selected";}?>>Maryland</option>
					<option value="Massachusetts"<?php if($d->state=="Massachusetts"){echo " selected";}?>>Massachusetts</option>
					<option value="Michigan"<?php if($d->state=="Michigan"){echo " selected";}?>>Michigan</option>
					<option value="Minnesota"<?php if($d->state=="Minnesota"){echo " selected";}?>>Minnesota</option>
					<option value="Mississippi"<?php if($d->state=="Mississippi"){echo " selected";}?>>Mississippi</option>
					<option value="Missouri"<?php if($d->state=="Missouri"){echo " selected";}?>>Missouri</option>
					<option value="Montana"<?php if($d->state=="Montana"){echo " selected";}?>>Montana</option>
					<option value="Nebraska"<?php if($d->state=="Nebraska"){echo " selected";}?>>Nebraska</option>
					<option value="Nevada"<?php if($d->state=="Nevada"){echo " selected";}?>>Nevada</option>
					<option value="New Hampshire"<?php if($d->state=="New Hampshire"){echo " selected";}?>>New Hampshire</option>
					<option value="New Jersey"<?php if($d->state=="New Jersey"){echo " selected";}?>>New Jersey</option>
					<option value="New Mexico"<?php if($d->state=="New Mexico"){echo " selected";}?>>New Mexico</option>
					<option value="New York"<?php if($d->state=="New York"){echo " selected";}?>>New York</option>
					<option value="North Carolina"<?php if($d->state=="North Carolina"){echo " selected";}?>>North Carolina</option>
					<option value="North Dakota"<?php if($d->state=="North Dakota"){echo " selected";}?>>North Dakota</option>
					<option value="Ohio"<?php if($d->state=="Ohio"){echo " selected";}?>>Ohio</option>
					<option value="Oklahoma"<?php if($d->state=="Oklahoma"){echo " selected";}?>>Oklahoma</option>
					<option value="Oregon"<?php if($d->state=="Oregon"){echo " selected";}?>>Oregon</option>
					<option value="Pennsylvania"<?php if($d->state=="Pennsylvania"){echo " selected";}?>>Pennsylvania</option>
					<option value="Rhode Island"<?php if($d->state=="Rhode Island"){echo " selected";}?>>Rhode Island</option>
					<option value="South Carolina"<?php if($d->state=="South Carolina"){echo " selected";}?>>South Carolina</option>
					<option value="South Dakota"<?php if($d->state=="South Dakota"){echo " selected";}?>>South Dakota</option>
					<option value="Tennessee"<?php if($d->state=="Tennessee"){echo " selected";}?>>Tennessee</option>
					<option value="Texas"<?php if($d->state=="Texas"){echo " selected";}?>>Texas</option>
					<option value="Utah"<?php if($d->state=="Utah"){echo " selected";}?>>Utah</option>
					<option value="Vermont"<?php if($d->state=="Vermont"){echo " selected";}?>>Vermont</option>
					<option value="Virginia"<?php if($d->state=="Virginia"){echo " selected";}?>>Virginia</option>
					<option value="Washington"<?php if($d->state=="Washington"){echo " selected";}?>>Washington</option>
					<option value="West Virginia"<?php if($d->state=="West Virginia"){echo " selected";}?>>West Virginia</option>
					<option value="Wisconsin"<?php if($d->state=="Wisconsin"){echo " selected";}?>>Wisconsin</option>
					<option value="Wyoming"<?php if($d->state=="Wyoming"){echo " selected";}?>>Wyoming</option>
				</select>
				<?php
			}elseif($j->country!=""){
				//text box
				?>
				State/Province: <input type="text" id="state" name="state" value="<?php echo $j->state?>">
				<?php
			}
			?>
		</span>
	</div>

	<div class="rowdiv">
		Address Line 1: <input type="text" id="address1" name="address1" size="60" value="<?php echo $j->address1?>">
	</div>

	<div class="rowdiv">
		Address Line 2: <input type="text" id="address2" name="address2" size="60" value="<?php echo $j->address2?>">
	</div>

	<div class="rowdiv">
		City: <input type="text" id="city" name="city" value="<?php echo $j->city?>"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Postal Code: <input type="text" id="zip" name="zip" value="<?php echo $j->zip?>">
	</div>

	<div class="rowdiv rowheader">Facility Contact</div>

	<div class="rowdiv">
		First Name: <input type="text" id="contact_first_name" name="contact_first_name" value="<?php echo $j->contact_first_name?>"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Last Name: <input type="text" id="contact_last_name" name="contact_last_name" value="<?php echo $j->contact_last_name?>">
	</div>

	<div class="rowdiv">
		Title: <input type="text" id="contact_title" name="contact_title" value="<?php echo $j->contact_title?>"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Email: <input type="text" id="contact_email" name="contact_email" value="<?php echo $j->contact_email?>">
	</div>

	<div class="rowdiv">
		<h2>Apparatus Information</h2>
	</div>

	<div class="rowdiv">
		<div style="float:left;padding-left:100px;">
			Apparatus Type: <span class="redred">*</span>
			<select name="apparatus_type" id="apparatus_type">
				<option value="">Select...</option>
				<option value="Hydrostatic Loading Apparatuses"<?php if($j->apparatus_type=="Hydrostatic Loading Apparatuses"){echo " selected";}?>>Hydrostatic Loading Apparatuses</option>
				<option value="Uniaxial Apparatus"<?php if($j->apparatus_type=="Uniaxial Apparatus"){echo " selected";}?>>Uniaxial Apparatus</option>
				<option value="Biaxial Apparatus"<?php if($j->apparatus_type=="Biaxial Apparatus"){echo " selected";}?>>Biaxial Apparatus</option>
				<option value="Triaxial (Conventional) Apparatus"<?php if($j->apparatus_type=="Triaxial (Conventional) Apparatus"){echo " selected";}?>>Triaxial (Conventional) Apparatus</option>
				<option value="True Triaxial Apparatus"<?php if($j->apparatus_type=="True Triaxial Apparatus"){echo " selected";}?>>True Triaxial Apparatus</option>
				<option value="Rotary Shear Apparatus"<?php if($j->apparatus_type=="Rotary Shear Apparatus"){echo " selected";}?>>Rotary Shear Apparatus</option>
				<option value="Split Hopkinson Pressure Bar"<?php if($j->apparatus_type=="Split Hopkinson Pressure Bar"){echo " selected";}?>>Split Hopkinson Pressure Bar</option>
				<option value="Indenter"<?php if($j->apparatus_type=="Indenter"){echo " selected";}?>>Indenter</option>
				<option value="Nanoindenter"<?php if($j->apparatus_type=="Nanoindenter"){echo " selected";}?>>Nanoindenter</option>
				<option value="Load Stamp"<?php if($j->apparatus_type=="Load Stamp"){echo " selected";}?>>Load Stamp</option>
				<option value="Viscosimeter"<?php if($j->apparatus_type=="Viscosimeter"){echo " selected";}?>>Viscosimeter</option>
			</select>
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
			Apparatus Subtype: <span class="redred">*</span>
			<select name="apparatus_subtype" id="apparatus_subtype">
				<option value="">Select...</option>

			<?php
			if($j->apparatus_type == "Hydrostatic Loading Apparatuses"){
				?>
				<option value="Multi Anvil Cell"<?php if($j->apparatus_subtype=="Multi Anvil Cell"){echo " selected";}?>>Multi Anvil Cell</option>
				<option value="Diamond Anvil Cell"<?php if($j->apparatus_subtype=="Diamond Anvil Cell"){echo " selected";}?>>Diamond Anvil Cell</option>
				<option value="Piston Cylinder"<?php if($j->apparatus_subtype=="Piston Cylinder"){echo " selected";}?>>Piston Cylinder</option>
				<option value="Other Hydrostatic Loading Apparatus"<?php if($j->apparatus_subtype=="Other Hydrostatic Loading Apparatus"){echo " selected";}?>>Other Hydrostatic Loading Apparatus</option>
				<?php
			}elseif($j->apparatus_type == "Uniaxial Apparatus"){
				?>
				<option value="Dead Load Creep Apparatus"<?php if($j->apparatus_subtype=="Dead Load Creep Apparatus"){echo " selected";}?>>Dead Load Creep Apparatus</option>
				<option value="Other Uniaxial Apparatus"<?php if($j->apparatus_subtype=="Other Uniaxial Apparatus"){echo " selected";}?>>Other Uniaxial Apparatus</option>
				<?php
			}elseif($j->apparatus_type == "Biaxial Apparatus"){
				?>
				<option value="Biaxial Direct (and Double Direct) Shear Apparatus"<?php if($j->apparatus_subtype=="Biaxial Direct (and Double Direct) Shear Apparatus"){echo " selected";}?>>Biaxial Direct (and Double Direct) Shear Apparatus</option>
				<option value="Shear Box"<?php if($j->apparatus_subtype=="Shear Box"){echo " selected";}?>>Shear Box</option>
				<option value="Other Biaxial Apparatus"<?php if($j->apparatus_subtype=="Other Biaxial Apparatus"){echo " selected";}?>>Other Biaxial Apparatus</option>
				<?php
			}elseif($j->apparatus_type == "Triaxial (Conventional) Apparatus"){
				?>
				<option value="Paterson Apparatus"<?php if($j->apparatus_subtype=="Paterson Apparatus"){echo " selected";}?>>Paterson Apparatus</option>
				<option value="Griggs Apparatus"<?php if($j->apparatus_subtype=="Griggs Apparatus"){echo " selected";}?>>Griggs Apparatus</option>
				<option value="Heard Apparatus"<?php if($j->apparatus_subtype=="Heard Apparatus"){echo " selected";}?>>Heard Apparatus</option>
				<option value="D-DIA"<?php if($j->apparatus_subtype=="D-DIA"){echo " selected";}?>>D-DIA</option>
				<option value="Paris-Edinburgh Rig"<?php if($j->apparatus_subtype=="Paris-Edinburgh Rig"){echo " selected";}?>>Paris-Edinburgh Rig</option>
				<option value="Other Gas Medium Apparatus"<?php if($j->apparatus_subtype=="Other Gas Medium Apparatus"){echo " selected";}?>>Other Gas Medium Apparatus</option>
				<option value="Other Liquid Medium Apparatus"<?php if($j->apparatus_subtype=="Other Liquid Medium Apparatus"){echo " selected";}?>>Other Liquid Medium Apparatus</option>
				<option value="Other Solid Medium Apparatus"<?php if($j->apparatus_subtype=="Other Solid Medium Apparatus"){echo " selected";}?>>Other Solid Medium Apparatus</option>
				<?php
			}elseif($j->apparatus_type == "Rotary Shear Apparatus"){
				?>
				<option value="Rotary Shear Friction Apparatus"<?php if($j->apparatus_subtype=="Rotary Shear Friction Apparatus"){echo " selected";}?>>Rotary Shear Friction Apparatus</option>
				<option value="Double Torsion Apparatus"<?php if($j->apparatus_subtype=="Double Torsion Apparatus"){echo " selected";}?>>Double Torsion Apparatus</option>
				<option value="Large Volume Torsion Apparatus (LVT)"<?php if($j->apparatus_subtype=="Large Volume Torsion Apparatus (LVT)"){echo " selected";}?>>Large Volume Torsion Apparatus (LVT)</option>
				<option value="Rheometer"<?php if($j->apparatus_subtype=="Rheometer"){echo " selected";}?>>Rheometer</option>
				<option value="Rotational Drickamer Apparatus (RDA)"<?php if($j->apparatus_subtype=="Rotational Drickamer Apparatus (RDA)"){echo " selected";}?>>Rotational Drickamer Apparatus (RDA)</option>
				<option value="Other Rotary Shear Apparatus"<?php if($j->apparatus_subtype=="Other Rotary Shear Apparatus"){echo " selected";}?>>Other Rotary Shear Apparatus</option>
				<?php
			}elseif($j->apparatus_type == "Indenter"){
				?>
				<option value="Vickers Hardness Tester"<?php if($j->apparatus_subtype=="Vickers Hardness Tester"){echo " selected";}?>>Vickers Hardness Tester</option>
				<option value="Chevron Notch Test"<?php if($j->apparatus_subtype=="Chevron Notch Test"){echo " selected";}?>>Chevron Notch Test</option>
				<option value="Indentation Cell"<?php if($j->apparatus_subtype=="Indentation Cell"){echo " selected";}?>>Indentation Cell</option>
				<option value="Point Load"<?php if($j->apparatus_subtype=="Point Load"){echo " selected";}?>>Point Load</option>
				<option value="Creep Cell"<?php if($j->apparatus_subtype=="Creep Cell"){echo " selected";}?>>Creep Cell</option>
				<?php
			}elseif($j->apparatus_type == "Nanoindenter"){
				?>
				<option value="Cube corner"<?php if($j->apparatus_subtype=="Cube corner"){echo " selected";}?>>Cube corner</option>
				<option value="Bercovitch"<?php if($j->apparatus_subtype=="Bercovitch"){echo " selected";}?>>Bercovitch</option>
				<option value="Spherical"<?php if($j->apparatus_subtype=="Spherical"){echo " selected";}?>>Spherical</option>
				<option value="Micropillar"<?php if($j->apparatus_subtype=="Micropillar"){echo " selected";}?>>Micropillar</option>
				<?php
			}
			?>
			</select>
		</div>
		<div style="clear:left"></div>
	</div>

	<div class="rowdiv">
		Apparatus Name: <span class="redred">*</span>
		<input type="text" id="apparatus_name" value="<?php echo $j->apparatus_name?>">
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
		$schematic_filename = $db->get_var("select original_file_name from exp_images where type='schematic' and apparatus_pkey = $apparatus_pkey");
	}

	?>

	<div class="rowdiv" style="padding-top:20px;">
	<div style="float:left;padding-right:20px;font-size:1.3em;padding-top:60px;padding-left:170px;display:<?php echo $dropzone_rig_photo_display?>;" id="rigDZPhotoLabel">Upload Rig Photo:</div><div style="float:left;display:<?php echo $dropzone_rig_photo_display?>;" class="dropzone" id="rigPhoto"></div>
	<div style="float:left;padding-right:20px;font-size:1.3em;padding-top:60px;padding-left:170px;display:<?php echo $static_rig_photo_display?>;" id="rigStaticPhotoLabel">Rig Photo:</div>
	<div style="float:left;display:<?php echo $static_rig_photo_display?>;" id="rigStaticPhoto">
		<div><a href="/apparatus_photo_<?php echo $apparatus_pkey?>_large.jpg" data-featherlight="image"><img src="/apparatus_photo_<?php echo $apparatus_pkey?>_small.jpg"></a></div>
		<div><a href="javascript:deletePhoto();">DELETE</a></div>
	</div>

	<div style="float:left;padding-right:20px;font-size:1.3em;padding-top:60px;padding-left:20px;display:<?php echo $dropzone_rig_schematic_display?>;" id="rigDZSchematicLabel">Upload Rig Schematic:</div><div style="float:left;display:<?php echo $dropzone_rig_schematic_display?>;" class="dropzone" id="rigSchematic"></div>
	<div style="float:left;padding-right:20px;font-size:1.3em;padding-top:60px;padding-left:20px;display:<?php echo $static_rig_schematic_display?>;" id="rigStaticSchematicLabel">Rig Schematic:</div>
	<div style="float:left;display:<?php echo $static_rig_schematic_display?>;vertical-align:middle;padding-top:50px;" id="rigStaticSchematic">
		<div><a href="/apparatus_schematic_<?php echo $apparatus_pkey?>">Download<?php echo $schematic_filenameddd?></a></div>
		<div><a href="javascript:deleteSchematic();">DELETE</a></div>
	</div>

	<div style="clear:left;"></div>
	</div>

	<div class="rowdiv rowheader" style="padding-top:15px;">Possible Test Types</div>

	<div style="border:1px dashed rgba(50, 50, 50, 0.5);border-radius:10px;padding:20px;width:300px;margin:auto;margin-top:10px;">
		<div style="font-weight:bold;">Static:</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_hip" id="checkbox_hip"<?php if($j->test_types->hip == 1){echo " checked";}?>> HIP</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_synthesis" id="checkbox_synthesis"<?php if($j->test_types->synthesis == 1){echo " checked";}?>> Synthesis</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_depositionevaporation" id="checkbox_depositionevaporation"<?php if($j->test_types->depositionevaporation == 1){echo " checked";}?>> Deposition/Evaporation</div>

		<div style="font-weight:bold;">Indentation:</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_indentationcreep" id="checkbox_indentationcreep"<?php if($j->test_types->indentationcreep == 1){echo " checked";}?>> Indentation-Creep</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_hardness" id="checkbox_hardness"<?php if($j->test_types->hardness == 1){echo " checked";}?>> Hardness</div>

		<div style="font-weight:bold;">Pore Structure:</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_permeability" id="checkbox_permeability"<?php if($j->test_types->permeability == 1){echo " checked";}?>> Permeability</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_resistivity" id="checkbox_resistivity"<?php if($j->test_types->resistivity == 1){echo " checked";}?>> Resistivity</div>

		<div style="font-weight:bold;">Shear Deformation:</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_simpleshear" id="checkbox_simpleshear"<?php if($j->test_types->simpleshear == 1){echo " checked";}?>> Simple Shear</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_rotaryshear" id="checkbox_rotaryshear"<?php if($j->test_types->rotaryshear == 1){echo " checked";}?>> Rotary Shear</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_pureshear" id="checkbox_pureshear"<?php if($j->test_types->pureshear == 1){echo " checked";}?>> Pure Shear</div>

		<div style="font-weight:bold;">Uniaxial Deformation:</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_constantstress" id="checkbox_constantstress"<?php if($j->test_types->constantstress == 1){echo " checked";}?>> Constant Stress</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_constantrate" id="checkbox_constantrate"<?php if($j->test_types->constantrate == 1){echo " checked";}?>> Constant Rate</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_loadingunloading" id="checkbox_loadingunloading"<?php if($j->test_types->loadingunloading == 1){echo " checked";}?>> Loading/Unloading</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_stepping" id="checkbox_stepping"<?php if($j->test_types->stepping == 1){echo " checked";}?>> Stepping</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_extension" id="checkbox_extension"<?php if($j->test_types->extension == 1){echo " checked";}?>> Extension</div>
	</div>

	<div class="rowdiv rowheader" style="padding-top:25px;">Apparatus Capabilities, Sensors, and Corrections</div>

	<?php
	if($j->confining_pressure != ""){
		$confining_checked = " checked";
		$confining_display = " style=\"display:block;\"";
	}
	?>

	<div style="padding-top:20px;margin-left:25px;margin-right:25px;">
		<div class="checkheader"><input type="checkbox" id="checkbox_confiningpressure"<?php echo $confining_checked?>> Confining Pressure</div>
		<div class="checkbody" id="confiningpressure_wrapper"<?php echo $confining_display?>>
			<div><input type="checkbox" id="cp_servo_controlled"<?php if($j->confining_pressure->servo_controlled == 1) echo " checked";?>> Servo Controlled</div>
			<div class="rowdivv">
				Confining Medium:
				<select id="cp_confiningmediumone">
					<option value="">Select...</option>
					<option value="Solid"<?php if($j->confining_pressure->confiningmediumone == "Solid"){echo " selected";}?>>Solid</option>
					<option value="Liquid"<?php if($j->confining_pressure->confiningmediumone == "Liquid"){echo " selected";}?>>Liquid</option>
					<option value="Gas"<?php if($j->confining_pressure->confiningmediumone == "Gas"){echo " selected";}?>>Gas</option>
				</select>
				<?php
				if($j->confining_pressure->confiningmediumone != ""){
					$cm1show = "inline";
				}else{
					$cm1show = "none";
				}
				?>
				<select id="cp_confiningmediumtwo" style="display:<?php echo $cm1show?>;">
					<option value="">Select...</option>
					<?php
					//set up different options
					if($j->confining_pressure->confiningmediumone == "Solid"){
						?>
						<option value="Salt: NaCl"<?php if($j->confining_pressure->confiningmediumtwo == "Salt: NaCl"){echo " selected";}?>>Salt: NaCl</option>
						<option value="Salt: KCl"<?php if($j->confining_pressure->confiningmediumtwo == "Salt: KCl"){echo " selected";}?>>Salt: KCl</option>
						<option value="Diamond Anvil"<?php if($j->confining_pressure->confiningmediumtwo == "Diamond Anvil"){echo " selected";}?>>Diamond Anvil</option>
						<option value="Other"<?php if($j->confining_pressure->confiningmediumtwo == "Other"){echo " selected";}?>>Other</option>
						<?php

					}elseif($j->confining_pressure->confiningmediumone == "Liquid"){
						?>
						<option value="Silicon Oil"<?php if($j->confining_pressure->confiningmediumtwo == "Silicon Oil"){echo " selected";}?>>Silicon Oil</option>
						<option value="Hydraulic Oil"<?php if($j->confining_pressure->confiningmediumtwo == "Hydraulic Oil"){echo " selected";}?>>Hydraulic Oil</option>
						<option value="Water"<?php if($j->confining_pressure->confiningmediumtwo == "Water"){echo " selected";}?>>Water</option>
						<option value="Melt"<?php if($j->confining_pressure->confiningmediumtwo == "Melt"){echo " selected";}?>>Melt</option>
						<option value="Other"<?php if($j->confining_pressure->confiningmediumtwo == "Other"){echo " selected";}?>>Other</option>
						<?php
					}elseif($j->confining_pressure->confiningmediumone == "Gas"){
						?>
						<option value="Argon"<?php if($j->confining_pressure->confiningmediumtwo == "Argon"){echo " selected";}?>>Argon</option>
						<option value="Nitrogen"<?php if($j->confining_pressure->confiningmediumtwo == "Nitrogen"){echo " selected";}?>>Nitrogen</option>
						<option value="Other"<?php if($j->confining_pressure->confiningmediumtwo == "Other"){echo " selected";}?>>Other</option>
						<?php
					}
					?>
				</select>
				<?php
				if($j->confining_pressure->confiningmediumtwo == "Other"){
					$cmothershow = "inline";
				}else{
					$cmothershow = "none";
				}
				?>
				<input type="text" id="cp_confiningmediumother" value="<?php echo $j->confining_pressure->confiningmediumother?>" placeholder="Enter other type..." style="display:<?php echo $cmothershow?>;">
			</div>
			<div class="rowdivv">
				Min Confining Pressure:
				<input type="text" value="<?php echo $j->confining_pressure->minconfiningpressure?>" class="only-numeric" id="cp_minconfiningpressure">
				<?php
				$thisselected = "MPa";
				if($j->confining_pressure->minconfiningpressureunit == "kPa") $thisselected = "kPa";
				if($j->confining_pressure->minconfiningpressureunit == "MPa") $thisselected = "MPa";
				if($j->confining_pressure->minconfiningpressureunit == "GPa") $thisselected = "GPa";
				?>
				<select id="cp_minconfiningpressure_unit">
					<option value="kPa"<?php if($thisselected=="kPa"){echo " selected";}?>>kPa</option>
					<option value="MPa"<?php if($thisselected=="MPa"){echo " selected";}?>>MPa</option>
					<option value="GPa"<?php if($thisselected=="GPa"){echo " selected";}?>>GPa</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max Confining Pressure:
				<input type="text" value="<?php echo $j->confining_pressure->maxconfiningpressure?>" class="only-numeric" id="cp_maxconfiningpressure">
				<?php
				$thisselected = "MPa";
				if($j->confining_pressure->maxconfiningpressureunit == "kPa") $thisselected = "kPa";
				if($j->confining_pressure->maxconfiningpressureunit == "MPa") $thisselected = "MPa";
				if($j->confining_pressure->maxconfiningpressureunit == "GPa") $thisselected = "GPa";
				?>
				<select id="cp_maxconfiningpressure_unit">
					<option value="kPa"<?php if($thisselected=="kPa"){echo " selected";}?>>kPa</option>
					<option value="MPa"<?php if($thisselected=="MPa"){echo " selected";}?>>MPa</option>
					<option value="GPa"<?php if($thisselected=="GPa"){echo " selected";}?>>GPa</option>
				</select>
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

				<?php if($cp_sensor_num > 1){?>
				<div><a href="javascript:removecpsensor<?php echo $cp_sensor_num?>();">Remove</a></div>
				<?php }?>
				<div style="float:left;width:600px;">
					<div class="rowdivv">
						Sensor Type:
						<select id="cp_sensortype_<?php echo $cp_sensor_num?>">
							<option value="">Select...</option>
							<option value="Pressure Cell"<?php if($thissensor->sensortype=="Pressure Cell"){echo " selected";}?>>Pressure Cell</option>
							<option value="Analog Oil Pressure Gauge"<?php if($thissensor->sensortype=="Analog Oil Pressure Gauge"){echo " selected";}?>>Analog Oil Pressure Gauge</option>
							<option value="X-Ray Diffraction"<?php if($thissensor->sensortype=="X-Ray Diffraction"){echo " selected";}?>>X-Ray Diffraction</option>
							<option value="Ruby Fluorescence"<?php if($thissensor->sensortype=="Ruby Fluorescence"){echo " selected";}?>>Ruby Fluorescence</option>
							<option value="Other"<?php if($thissensor->sensortype=="Other"){echo " selected";}?>>Other</option>
						</select>
						&nbsp;&nbsp;&nbsp;
						<?php
						if($thissensor->sensortype!="Other"){
							$otherstyle=" style=\"display:none;\"";
						}else{
							$otherstyle="";
						}
						?>
						<input type="text" id="cp_sensorothertype_<?php echo $cp_sensor_num?>" value="<?php echo $thissensor->sensorothertype?>" <?php echo $otherstyle?> placeholder="Other Sensor Type...">
					</div>
					<div class="rowdivv">
						Sensor Location:
						<select id="cp_sensorlocation_<?php echo $cp_sensor_num?>">
							<option value="">Select...</option>
							<option value="Internal"<?php if($thissensor->sensorlocation=="Internal"){echo " selected";}?>>Internal</option>
							<option value="External"<?php if($thissensor->sensorlocation=="External"){echo " selected";}?>>External</option>
						</select>
						&nbsp;&nbsp;&nbsp;
						Calibration: <input type="text" id="cp_sensorcalibration_<?php echo $cp_sensor_num?>" value="<?php echo $thissensor->sensorcalibration?>">
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<textarea id="cp_sensornotes_<?php echo $cp_sensor_num?>" style="width:400px;"><?php echo $thissensor->sensornotes?></textarea>
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

				<div style="float:left;display:<?php echo $dzshow?>;" class="dropzone" id="cp_sensorfile_<?php echo $cp_sensor_num?>"></div>
				<div style="text-align:center;float:left;vertical-align:middle;padding-top:50px;display:<?php echo $imgshow?>;" id="cp_sensorlink_<?php echo $cp_sensor_num?>">
					<a href="/sensor_calibration_file/cpsensor/<?php echo $apparatus_pkey?>/<?php echo $cp_sensor_num?>">Download</a><br>
					<a href="javascript:deleteCalibration('cp', <?php echo $cp_sensor_num?>);">Delete</a>
				</div>

				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

			<div class="rowdiv" id="cpaddsensorbutton">
				 <button onclick="add_cp_sensor(); return false;">Add Additional Sensor</button>
			</div>
		</div>

	<?php
	if($j->temp != ""){
		$temp_checked = " checked";
		$temp_display = " style=\"display:block;\"";
	}
	?>

		<div class="checkheader"><input type="checkbox" id="checkbox_temperaturefurnacecryostat"<?php echo $temp_checked?>> Temperature (Furnace/Cryostat)</div>
		<div class="checkbody" id="temperaturefurnacecryostat_wrapper"<?php echo $temp_display?>>
			<div><input type="checkbox" id="temp_furnace"<?php if($j->temp->furnace!=""){echo " checked";}?>> Furnace
			&nbsp;&nbsp;&nbsp;<input type="checkbox" id="temp_cryostat"<?php if($j->temp->cryostat!=""){echo " checked";}?>> Cryostat</div>
			<div><input type="checkbox" id="temp_servo_controlled"<?php if($j->temp->servocontrolled!=""){echo " checked";}?>> Servo Controlled</div>

			<div class="rowdivv">
				Min Temperature:
				<input type="text" class="only-numeric" id="temp_mintemperature" value="<?php echo $j->temp->mintemperature?>">
				<select id="temp_mintemperature_unit">
					<option value="°C"<?php if($j->temp->mintemperatureunit=="°C"){echo " selected";}?>>°C</option>
					<option value="K"<?php if($j->temp->mintemperatureunit=="K"){echo " selected";}?>>K</option>
					<option value="°F"<?php if($j->temp->mintemperatureunit=="°F"){echo " selected";}?>>°F</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max Temperature:
				<input type="text" class="only-numeric" id="temp_maxtemperature" value="<?php echo $j->temp->maxtemperature?>">
				<select id="temp_maxtemperature_unit">
					<option value="°C"<?php if($j->temp->maxtemperatureunit=="°C"){echo " selected";}?>>°C</option>
					<option value="K"<?php if($j->temp->maxtemperatureunit=="K"){echo " selected";}?>>K</option>
					<option value="°F"<?php if($j->temp->maxtemperatureunit=="°F"){echo " selected";}?>>°F</option>
				</select>
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

				<?php if($sensor_num > 1){?>
				<div><a href="javascript:removetempsensor<?php echo $sensor_num?>();">Remove</a></div>
				<?php }?>
				<div style="float:left;width:610px;">
					<div class="rowdivv" style="white-space: nowrap;">
						Sensor Type:
						<select id="temp_sensortype_<?php echo $sensor_num?>" style="max-width:120px;">
							<option value="">Select...</option>
							<option value="Resistance Temperature Detector (RTD)"<?php if($thissensor->sensortype=="Resistance Temperature Detector (RTD)"){echo " selected";}?>>Resistance Temperature Detector (RTD)</option>
							<option value="Thermocouple"<?php if($thissensor->sensortype=="Thermocouple"){echo " selected";}?>>Thermocouple</option>
							<option value="Thermistor"<?php if($thissensor->sensortype=="Thermistor"){echo " selected";}?>>Thermistor</option>
							<option value="Furnace Power with Power-Temperature Calibration"<?php if($thissensor->sensortype=="Furnace Power with Power-Temperature Calibration"){echo " selected";}?>>Furnace Power with Power-Temperature Calibration</option>
							<option value="Other"<?php if($thissensor->sensortype=="Other"){echo " selected";}?>>Other</option>
						</select>

						<?php
						if($thissensor->sensortype=="Thermocouple"){
							$display="inline";
						}else{
							$display="none";
						}
						?>

						<span id="temp_thermocoupletypespan_<?php echo $sensor_num?>" style="padding-left:10px;display:<?php echo $display?>;">
							Thermocouple Type:
							<select id="temp_thermocoupletype_<?php echo $sensor_num?>" style="max-width:120px;">
								<option value="">Select...</option>
								<option value="Nickel Alloy"<?php if($thissensor->thermocoupletype=="Nickel Alloy"){echo " selected";}?>>Nickel Alloy</option>
								<option value="Platinum/Rhodium-Alloy"<?php if($thissensor->thermocoupletype=="Platinum/Rhodium-Alloy"){echo " selected";}?>>Platinum/Rhodium-Alloy</option>
								<option value="Tungsten/Rhenium-Alloy"<?php if($thissensor->thermocoupletype=="Tungsten/Rhenium-Alloy"){echo " selected";}?>>Tungsten/Rhenium-Alloy</option>
								<option value="Chromel-gold/iron-alloy"<?php if($thissensor->thermocoupletype=="Chromel-gold/iron-alloy"){echo " selected";}?>>Chromel-gold/iron-alloy </option>
								<option value="Type P (noble-melat alloy) or Platinel II"<?php if($thissensor->thermocoupletype=="Type P (noble-melat alloy) or Platinel II"){echo " selected";}?>>Type P (noble-melat alloy) or Platinel II</option>
								<option value="Platinum/Molybdenum-alloy"<?php if($thissensor->thermocoupletype=="Platinum/Molybdenum-alloy"){echo " selected";}?>>Platinum/Molybdenum-alloy</option>
								<option value="Iridium/rhodium alloy"<?php if($thissensor->thermocoupletype=="Iridium/rhodium alloy"){echo " selected";}?>>Iridium/rhodium alloy</option>
								<option value="Pure noble-metal (Au-Pt, Pt-Pd)"<?php if($thissensor->thermocoupletype=="Pure noble-metal (Au-Pt, Pt-Pd)"){echo " selected";}?>>Pure noble-metal (Au-Pt, Pt-Pd)</option>
								<option value="High Temperature Irradiation Resistant"<?php if($thissensor->thermocoupletype=="High Temperature Irradiation Resistant"){echo " selected";}?>>High Temperature Irradiation Resistant</option>
							</select>
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
							Sub Type:
							<select id="temp_thermocouplesubtype_<?php echo $sensor_num?>" style="max-width:120px;">
								<option value="">Select...</option>
								<?php
									//todo: set up sub types
									if($thissensor->thermocoupletype == "Nickel Alloy"){
										?>
										<option value="Type E"<?php if($thissensor->thermocouplesubtype=="Type E"){echo " selected";}?>>Type E</option>
										<option value="Type J"<?php if($thissensor->thermocouplesubtype=="Type J"){echo " selected";}?>>Type J</option>
										<option value="Type K"<?php if($thissensor->thermocouplesubtype=="Type K"){echo " selected";}?>>Type K</option>
										<option value="Type M"<?php if($thissensor->thermocouplesubtype=="Type M"){echo " selected";}?>>Type M</option>
										<option value="Type N"<?php if($thissensor->thermocouplesubtype=="Type N"){echo " selected";}?>>Type N</option>
										<option value="Type T"<?php if($thissensor->thermocouplesubtype=="Type T"){echo " selected";}?>>Type T</option>
										<?php
									} else if($thissensor->thermocoupletype == "Platinum/Rhodium-Alloy"){
										?>
										<option value="Type B"<?php if($thissensor->thermocouplesubtype=="Type B"){echo " selected";}?>>Type B</option>
										<option value="Type R"<?php if($thissensor->thermocouplesubtype=="Type R"){echo " selected";}?>>Type R</option>
										<option value="Type S"<?php if($thissensor->thermocouplesubtype=="Type S"){echo " selected";}?>>Type S</option>
										<?php
									} else if($thissensor->thermocoupletype == "Tungsten/Rhenium-Alloy"){
										?>
										<option value="Type C"<?php if($thissensor->thermocouplesubtype=="Type C"){echo " selected";}?>>Type C</option>
										<option value="Type D"<?php if($thissensor->thermocouplesubtype=="Type D"){echo " selected";}?>>Type D</option>
										<option value="Type G"<?php if($thissensor->thermocouplesubtype=="Type G"){echo " selected";}?>>Type G</option>
										<?php
									}

								?>
							</select>
						</span>

						<?php
						if($thissensor->sensortype=="Resistance Temperature Detector (RTD)"){
							$display="inline";
						}else{
							$display="none";
						}
						?>

						<span id="temp_rtdspan_<?php echo $sensor_num?>" style="padding-left:10px;display:<?php echo $display?>;">
							RTD Type:
							<select id="temp_rtdtype_<?php echo $sensor_num?>" style="max-width:120px;">
								<option value="">Select...</option>
								<option value="Pt"<?php if($thissensor->rtdtype=="Pt"){echo " selected";}?>>Pt</option>
								<option value="Ni"<?php if($thissensor->rtdtype=="Ni"){echo " selected";}?>>Ni</option>
								<option value="Cu"<?php if($thissensor->rtdtype=="Cu"){echo " selected";}?>>Cu</option>
							</select>
						</span>

						<?php
						if($thissensor->sensortype=="Thermistor"){
							$display="inline";
						}else{
							$display="none";
						}
						?>

						<span id="temp_thermistortypespan_<?php echo $sensor_num?>" style="padding-left:10px;display:<?php echo $display?>;">
							Thermistor Type:
							<select id="temp_thermistortype_<?php echo $sensor_num?>" style="max-width:120px;">
								<option value="">Select...</option>
								<option value="Negative Temperature Coefficient (NTC)"<?php if($thissensor->thermistortype=="Negative Temperature Coefficient (NTC)"){echo " selected";}?>>Negative Temperature Coefficient (NTC)</option>
								<option value="Positive Temperature Coefficient (PTC)"<?php if($thissensor->thermistortype=="Positive Temperature Coefficient (PTC)"){echo " selected";}?>>Positive Temperature Coefficient (PTC)</option>
							</select>
						</span>

						<?php
						if($thissensor->sensortype=="Other"){
							$display="inline";
						}else{
							$display="none";
						}
						?>

						<span id="temp_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
							<input type="text" id="temp_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type..." value="<?php echo $thissensor->othersensortype?>">
						</span>
					</div>
					<div class="rowdivv">
						Sensor Location:
						<select id="temp_sensorlocation_<?php echo $sensor_num?>">
							<option value="">Select...</option>
							<option value="Internal"<?php if($thissensor->sensorlocation=="Internal"){echo " selected";}?>>Internal</option>
							<option value="External"<?php if($thissensor->sensorlocation=="External"){echo " selected";}?>>External</option>
						</select>
						&nbsp;&nbsp;&nbsp;
						Calibration: <input type="text" id="temp_sensorcalibration_<?php echo $sensor_num?>" value="<?php echo $thissensor->sensorcalibration?>">
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<textarea id="temp_sensornotes_<?php echo $sensor_num?>" style="width:400px;"><?php echo $thissensor->sensornotes?></textarea>
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

				<div style="float:left;display:<?php echo $dzshow?>;" class="dropzone" id="temp_sensorfile_<?php echo $sensor_num?>"></div>
				<div style="text-align:center;float:left;vertical-align:middle;padding-top:50px;display:<?php echo $imgshow?>;" id="temp_sensorlink_<?php echo $sensor_num?>">
					<a href="/sensor_calibration_file/tempsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
					<a href="javascript:deleteCalibration('temp', <?php echo $sensor_num?>);">Delete</a>
				</div>

				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

			<div class="rowdiv" id="tempaddsensorbutton">
				 <button onclick="add_temp_sensor(); return false;">Add Additional Sensor</button>
			</div>

		</div>

	<?php
	if($j->displacement != ""){
		$displacement_checked = " checked";
		$displacement_display = " style=\"display:block;\"";
	}
	?>

		<div class="checkheader"><input type="checkbox" id="checkbox_displacement"<?php echo $displacement_checked?>> Displacement</div>
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

			<input type="checkbox" id="checkbox_dispax"<?php echo $displacement_axial_checked?>> Axial
			&nbsp;&nbsp;
			<input type="checkbox" id="checkbox_disprot"<?php echo $displacement_rotary_checked?>> Rotary
			&nbsp;&nbsp;
			<input type="checkbox" id="checkbox_dispvol"<?php echo $displacement_volumetric_checked?>> Volumetric
			&nbsp;&nbsp;
			<input type="checkbox" id="checkbox_dispshear"<?php echo $displacement_shear_checked?>> Shear

			<div class="displacementbox" id="dispax_wrapper"<?php echo $displacement_axial_display?>>
				<div class="rowheader">Axial</div>
				<div class="rowdivv">
					Min Displacement:
					<input type="text" class="only-numeric" id="dispax_mindisplacement" value="<?php echo $j->displacement->axial->mindisplacement?>">
					<select id="dispax_mindisplacement_unit">
						<option value="µm"<?php if($j->displacement->axial->mindisplacementunit == "µm"){echo " selected";}?>>µm</option>
						<option value="mm"<?php if($j->displacement->axial->mindisplacementunit == "mm"){echo " selected";}?>>mm</option>
						<option value="cm"<?php if($j->displacement->axial->mindisplacementunit == "cm" || $j->displacement->axial->mindisplacementunit == "" ){echo " selected";}?>>cm</option>
						<option value="m"<?php if($j->displacement->axial->mindisplacementunit == "m"){echo " selected";}?>>m</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Displacement:
					<input type="text" class="only-numeric" id="dispax_maxdisplacement" value="<?php echo $j->displacement->axial->maxdisplacement?>">
					<select id="dispax_maxdisplacement_unit">
						<option value="µm"<?php if($j->displacement->axial->maxdisplacementunit == "µm"){echo " selected";}?>>µm</option>
						<option value="mm"<?php if($j->displacement->axial->maxdisplacementunit == "mm"){echo " selected";}?>>mm</option>
						<option value="cm"<?php if($j->displacement->axial->maxdisplacementunit == "cm" || $j->displacement->axial->maxdisplacementunit == "" ){echo " selected";}?>>cm</option>
						<option value="m"<?php if($j->displacement->axial->maxdisplacementunit == "m"){echo " selected";}?>>m</option>
					</select>
				</div>
				<div class="rowdivv">
					Min Displacement Rate:
					<input type="text" class="only-numeric" id="dispax_mindisplacementrate" value="<?php echo $j->displacement->axial->mindisplacementrate?>">
					<select id="dispax_mindisplacementrate_unit">
						<option value="µm/s"<?php if($j->displacement->axial->mindisplacementrateunit == "µm/s"){echo " selected";}?>>µm/s</option>
						<option value="mm/s"<?php if($j->displacement->axial->mindisplacementrateunit == "mm/s"){echo " selected";}?>>mm/s</option>
						<option value="cm/s"<?php if($j->displacement->axial->mindisplacementrateunit == "cm/s"){echo " selected";}?>>cm/s</option>
						<option value="m/s"<?php if($j->displacement->axial->mindisplacementrateunit == "m/s" || $j->displacement->axial->mindisplacementrateunit == ""){echo " selected";}?>>m/s</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Displacement Rate:
					<input type="text" class="only-numeric" id="dispax_maxdisplacementrate" value="<?php echo $j->displacement->axial->maxdisplacementrate?>">
					<select id="dispax_maxdisplacementrate_unit">
						<option value="µm/s"<?php if($j->displacement->axial->maxdisplacementrateunit == "µm/s"){echo " selected";}?>>µm/s</option>
						<option value="mm/s"<?php if($j->displacement->axial->maxdisplacementrateunit == "mm/s"){echo " selected";}?>>mm/s</option>
						<option value="cm/s"<?php if($j->displacement->axial->maxdisplacementrateunit == "cm/s"){echo " selected";}?>>cm/s</option>
						<option value="m/s"<?php if($j->displacement->axial->maxdisplacementrateunit == "m/s" || $j->displacement->axial->maxdisplacementrateunit == ""){echo " selected";}?>>m/s</option>
					</select>
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

					<?php if($sensor_num > 1){?>
					<div><a href="javascript:removedispaxsensor<?php echo $sensor_num?>();">Remove</a></div>
					<?php }?>
					<div style="float:left;width:600px;">
						<div class="rowdivv" style="white-space: nowrap;">
							Sensor Type:
							<select id="dispax_sensortype_<?php echo $sensor_num?>" style="max-width:200px;">
								<option value="">Select...</option>
								<option value="DCDT"<?php if($thissensor->sensortype=="DCDT"){echo " selected";}?>>DCDT</option>
								<option value="LVDT"<?php if($thissensor->sensortype=="LVDT"){echo " selected";}?>>LVDT</option>
								<option value="Cantilever"<?php if($thissensor->sensortype=="Cantilever"){echo " selected";}?>>Cantilever</option>
								<option value="Direct Contact Strain Gauge"<?php if($thissensor->sensortype=="Direct Contact Strain Gauge"){echo " selected";}?>>Direct Contact Strain Gauge</option>
								<option value="Extensometer"<?php if($thissensor->sensortype=="Extensometer"){echo " selected";}?>>Extensometer</option>
								<option value="Fiber Optic"<?php if($thissensor->sensortype=="Fiber Optic"){echo " selected";}?>>Fiber Optic</option>
								<option value="Laser"<?php if($thissensor->sensortype=="Laser"){echo " selected";}?>>Laser</option>
								<option value="Radiograph"<?php if($thissensor->sensortype=="Radiograph"){echo " selected";}?>>Radiograph</option>
								<option value="Other"<?php if($thissensor->sensortype=="Other"){echo " selected";}?>>Other</option>
							</select>
							<?php
								if($thissensor->sensortype=="Other"){
									$display = "inline";
								}else{
									$display = "none";
								}
							?>
							<span id="dispax_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
								<input type="text" id="dispax_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type..." value="<?php echo $thissensor->othersensortype?>">
							</span>
						</div>
						<div class="rowdivv">
							Sensor Location:
							<select id="dispax_sensorlocation_<?php echo $sensor_num?>">
								<option value="">Select...</option>
								<option value="Internal"<?php if($thissensor->sensorlocation=="Internal"){echo " selected";}?>>Internal</option>
								<option value="External"<?php if($thissensor->sensorlocation=="External"){echo " selected";}?>>External</option>
							</select>
							&nbsp;&nbsp;&nbsp;
							Calibration: <input type="text" id="dispax_sensorcalibration_<?php echo $sensor_num?>" value="<?php echo $thissensor->sensorcalibration?>">
						</div>
						<div class="rowdivv">
							Sensor Notes:
							<textarea id="dispax_sensornotes_<?php echo $sensor_num?>" style="width:400px;"><?php echo $thissensor->sensornotes?></textarea>
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

					<div style="float:left;display:<?php echo $dzshow?>;" class="dropzone" id="dispax_sensorfile_<?php echo $sensor_num?>"></div>
					<div style="text-align:center;float:left;vertical-align:middle;padding-top:50px;display:<?php echo $imgshow?>;" id="dispax_sensorlink_<?php echo $sensor_num?>">
						<a href="/sensor_calibration_file/dispaxsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
						<a href="javascript:deleteCalibration('dispax', <?php echo $sensor_num?>);">Delete</a>
					</div>

					<div style="clear:left;"></div>
				</div>

				<?php
				}//end foreach
				?>

				<div class="rowdiv" id="dispaxaddsensorbutton">
					 <button onclick="add_dispax_sensor(); return false;">Add Additional Sensor</button>
				</div>

			</div>

			<div class="displacementbox" id="disprot_wrapper"<?php echo $displacement_rotary_display?>>
				<div class="rowheader">Rotary</div>
				<div class="rowdivv">
					<input type="checkbox" id="disprot_solid_cylinder"<?php if($j->displacement->rotary->solidcylinder!=""){echo " checked";}?>> Solid Cylinder
					&nbsp;&nbsp;&nbsp;
					<input type="checkbox" id="disprot_annular"<?php if($j->displacement->rotary->annular!=""){echo " checked";}?>> Annular (Ring-shaped)
				</div>
				<div class="rowdivv">
					<input type="checkbox" id="disprot_servo_controlled"<?php if($j->displacement->rotary->servocontrolled!=""){echo " checked";}?>> Servo Controlled
				</div>
				<div class="rowdivv">
					Min Sample Diameter:
					<input type="text" class="only-numeric" id="disprot_minsamplediameter" value="<?php echo $j->displacement->rotary->minsamplediameter?>">
					<select id="disprot_minsamplediameter_unit">
						<option value="mm"<?php if($j->displacement->rotary->minsamplediameterunit=="mm"){echo " selected";}?>>mm</option>
						<option value="cm"<?php if($j->displacement->rotary->minsamplediameterunit=="cm" || $j->displacement->rotary->minsamplediameterunit==""){echo " selected";}?>>cm</option>
						<option value="m"<?php if($j->displacement->rotary->minsamplediameterunit=="m"){echo " selected";}?>>m</option>
						<option value="inches"<?php if($j->displacement->rotary->minsamplediameterunit=="inches"){echo " selected";}?>>inches</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Sample Diameter:
					<input type="text" class="only-numeric" id="disprot_maxsamplediameter" value="<?php echo $j->displacement->rotary->maxsamplediameter?>">
					<select id="disprot_maxsamplediameter_unit">
						<option value="mm"<?php if($j->displacement->rotary->maxsamplediameterunit=="mm"){echo " selected";}?>>mm</option>
						<option value="cm"<?php if($j->displacement->rotary->maxsamplediameterunit=="cm" || $j->displacement->rotary->maxsamplediameterunit==""){echo " selected";}?>>cm</option>
						<option value="m"<?php if($j->displacement->rotary->maxsamplediameterunit=="m"){echo " selected";}?>>m</option>
						<option value="inches"<?php if($j->displacement->rotary->maxsamplediameterunit=="inches"){echo " selected";}?>>inches</option>
					</select>
				</div>
				<div class="rowdivv">
					Min Sample Thickness:
					<input type="text" class="only-numeric" id="disprot_minsamplethickness" value="<?php echo $j->displacement->rotary->minsamplethickness?>">
					<select id="disprot_minsamplethickness_unit">
						<option value="mm"<?php if($j->displacement->rotary->minsamplethicknessunit=="mm"){echo " selected";}?>>mm</option>
						<option value="cm"<?php if($j->displacement->rotary->minsamplethicknessunit=="cm" || $j->displacement->rotary->minsamplethicknessunit==""){echo " selected";}?>>cm</option>
						<option value="m"<?php if($j->displacement->rotary->minsamplethicknessunit=="m"){echo " selected";}?>>m</option>
						<option value="inches"<?php if($j->displacement->rotary->minsamplethicknessunit=="inches"){echo " selected";}?>>inches</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Sample Thickness:
					<input type="text" class="only-numeric" id="disprot_maxsamplethickness" value="<?php echo $j->displacement->rotary->maxsamplethickness?>">
					<select id="disprot_maxsamplethickness_unit">
						<option value="mm"<?php if($j->displacement->rotary->maxsamplethicknessunit=="mm"){echo " selected";}?>>mm</option>
						<option value="cm"<?php if($j->displacement->rotary->maxsamplethicknessunit=="cm" || $j->displacement->rotary->maxsamplethicknessunit==""){echo " selected";}?>>cm</option>
						<option value="m"<?php if($j->displacement->rotary->maxsamplethicknessunit=="m"){echo " selected";}?>>m</option>
						<option value="inches"<?php if($j->displacement->rotary->maxsamplethicknessunit=="inches"){echo " selected";}?>>inches</option>
					</select>
				</div>
				<div class="rowdivv">
					Min Displacement:
					<input type="text" class="only-numeric" id="disprot_mindisplacement" value="<?php echo $j->displacement->rotary->mindisplacement?>">
					<select id="disprot_mindisplacement_unit">
						<option value="µm"<?php if($j->displacement->rotary->mindisplacementunit=="µm"){echo " selected";}?>>µm</option>
						<option value="mm"<?php if($j->displacement->rotary->mindisplacementunit=="mm"){echo " selected";}?>>mm</option>
						<option value="cm"<?php if($j->displacement->rotary->mindisplacementunit=="cm" || $j->displacement->rotary->mindisplacementunit==""){echo " selected";}?>>cm</option>
						<option value="m"<?php if($j->displacement->rotary->mindisplacementunit=="m"){echo " selected";}?>>m</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Displacement:
					<input type="text" class="only-numeric" id="disprot_maxdisplacement" value="<?php echo $j->displacement->rotary->maxdisplacement?>">
					<select id="disprot_maxdisplacement_unit">
						<option value="µm"<?php if($j->displacement->rotary->maxdisplacementunit=="µm"){echo " selected";}?>>µm</option>
						<option value="mm"<?php if($j->displacement->rotary->maxdisplacementunit=="mm"){echo " selected";}?>>mm</option>
						<option value="cm"<?php if($j->displacement->rotary->maxdisplacementunit=="cm" || $j->displacement->rotary->maxdisplacementunit==""){echo " selected";}?>>cm</option>
						<option value="m"<?php if($j->displacement->rotary->maxdisplacementunit=="m"){echo " selected";}?>>m</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" id="disprot_infinitemaxdisplacement"<?php if($j->displacement->rotary->infinitemaxdisplacement!=""){echo " checked";}?>>&nbsp;Infinite
				</div>
				<div class="rowdivv">
					Min Rotation Rate:
					<input type="text" class="only-numeric" id="disprot_minrotationrate" value="<?php echo $j->displacement->rotary->minrotationrate?>">
					<select id="disprot_minrotation_unit">
						<option value="revolutions/min"<?php if($j->displacement->rotary->minrotationunit=="revolutions/min"){echo " selected";}?>>revolutions/min</option>
						<option value="revolutions/s"<?php if($j->displacement->rotary->minrotationunit=="revolutions/s" || $j->displacement->rotary->minrotationunit==""){echo " selected";}?>>revolutions/s</option>
						<option value="revolutions/hour"<?php if($j->displacement->rotary->minrotationunit=="revolutions/hour"){echo " selected";}?>>revolutions/hour</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Rotation Rate:
					<input type="text" class="only-numeric" id="disprot_maxrotationrate" value="<?php echo $j->displacement->rotary->maxrotationrate?>">
					<select id="disprot_maxrotation_unit">
						<option value="revolutions/min"<?php if($j->displacement->rotary->maxrotationunit=="revolutions/min"){echo " selected";}?>>revolutions/min</option>
						<option value="revolutions/s"<?php if($j->displacement->rotary->maxrotationunit=="revolutions/s" || $j->displacement->rotary->maxrotationunit==""){echo " selected";}?>>revolutions/s</option>
						<option value="revolutions/hour"<?php if($j->displacement->rotary->maxrotationunit=="revolutions/hour"){echo " selected";}?>>revolutions/hour</option>
					</select>
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

					<?php if($sensor_num > 1){?>
					<div><a href="javascript:removedisprotsensor<?php echo $sensor_num?>();">Remove</a></div>
					<?php }?>
					<div style="float:left;width:600px;">
						<div class="rowdivv" style="white-space: nowrap;">
							Sensor Type:
							<select id="disprot_sensortype_<?php echo $sensor_num?>" style="max-width:200px;">
								<option value="">Select...</option>
								<option value="DCDT"<?php if($thissensor->sensortype=="DCDT"){echo " selected";}?>>DCDT</option>
								<option value="LVDT"<?php if($thissensor->sensortype=="LVDT"){echo " selected";}?>>LVDT</option>
								<option value="Cantilever"<?php if($thissensor->sensortype=="Cantilever"){echo " selected";}?>>Cantilever</option>
								<option value="Direct Contact Strain Gauge"<?php if($thissensor->sensortype=="Direct Contact Strain Gauge"){echo " selected";}?>>Direct Contact Strain Gauge</option>
								<option value="Extensometer"<?php if($thissensor->sensortype=="Extensometer"){echo " selected";}?>>Extensometer</option>
								<option value="Fiber Optic"<?php if($thissensor->sensortype=="Fiber Optic"){echo " selected";}?>>Fiber Optic</option>
								<option value="Laser"<?php if($thissensor->sensortype=="Laser"){echo " selected";}?>>Laser</option>
								<option value="Radiograph"<?php if($thissensor->sensortype=="Radiograph"){echo " selected";}?>>Radiograph</option>
								<option value="Potentiometer"<?php if($thissensor->sensortype=="Potentiometer"){echo " selected";}?>>Potentiometer</option>
								<option value="Resolver"<?php if($thissensor->sensortype=="Resolver"){echo " selected";}?>>Resolver</option>
								<option value="Encoder"<?php if($thissensor->sensortype=="Encoder"){echo " selected";}?>>Encoder</option>
								<option value="RVDT"<?php if($thissensor->sensortype=="RVDT"){echo " selected";}?>>RVDT</option>
								<option value="Other"<?php if($thissensor->sensortype=="Other"){echo " selected";}?>>Other</option>
							</select>
							<?php
								if($thissensor->sensortype=="Other"){
									$display = "inline";
								}else{
									$display = "none";
								}
							?>
							<span id="disprot_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
								<input type="text" id="disprot_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type..." value="<?php echo $thissensor->othersensortype?>">
							</span>
						</div>
						<div class="rowdivv">
							Sensor Location:
							<select id="disprot_sensorlocation_<?php echo $sensor_num?>">
								<option value="">Select...</option>
								<option value="Internal"<?php if($thissensor->sensorlocation=="Internal"){echo " selected";}?>>Internal</option>
								<option value="External"<?php if($thissensor->sensorlocation=="External"){echo " selected";}?>>External</option>
							</select>
							&nbsp;&nbsp;&nbsp;
							Calibration: <input type="text" id="disprot_sensorcalibration_<?php echo $sensor_num?>" value="<?php echo $thissensor->sensorcalibration?>">
						</div>
						<div class="rowdivv">
							Sensor Notes:
							<textarea id="disprot_sensornotes_<?php echo $sensor_num?>" style="width:400px;"><?php echo $thissensor->sensornotes?></textarea>
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

					<div style="float:left;display:<?php echo $dzshow?>;" class="dropzone" id="disprot_sensorfile_<?php echo $sensor_num?>"></div>
					<div style="text-align:center;float:left;vertical-align:middle;padding-top:50px;display:<?php echo $imgshow?>;" id="disprot_sensorlink_<?php echo $sensor_num?>">
						<a href="/sensor_calibration_file/disprotsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
						<a href="javascript:deleteCalibration('disprot', <?php echo $sensor_num?>);">Delete</a>
					</div>

					<div style="clear:left;"></div>
				</div>

				<?php
				}//end foreach
				?>

				<div class="rowdiv" id="disprotaddsensorbutton">
					 <button onclick="add_disprot_sensor(); return false;">Add Additional Sensor</button>
				</div>
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

					<?php if($sensor_num > 1){?>
					<div><a href="javascript:removedispvolsensor<?php echo $sensor_num?>();">Remove</a></div>
					<?php }?>
					<div style="float:left;width:600px;">
						<div class="rowdivv" style="white-space: nowrap;">
							Sensor Type:
							<select id="dispvol_sensortype_<?php echo $sensor_num?>" style="max-width:200px;">
								<option value="">Select...</option>
								<option value="DCDT"<?php if($thissensor->sensortype=="DCDT"){echo " selected";}?>>DCDT</option>
								<option value="LVDT"<?php if($thissensor->sensortype=="LVDT"){echo " selected";}?>>LVDT</option>
								<option value="Cantilever"<?php if($thissensor->sensortype=="Cantilever"){echo " selected";}?>>Cantilever</option>
								<option value="Direct Contact Strain Gauge"<?php if($thissensor->sensortype=="Direct Contact Strain Gauge"){echo " selected";}?>>Direct Contact Strain Gauge</option>
								<option value="Extensometer"<?php if($thissensor->sensortype=="Extensometer"){echo " selected";}?>>Extensometer</option>
								<option value="Fiber Optic"<?php if($thissensor->sensortype=="Fiber Optic"){echo " selected";}?>>Fiber Optic</option>
								<option value="Laser"<?php if($thissensor->sensortype=="Laser"){echo " selected";}?>>Laser</option>
								<option value="Radiograph"<?php if($thissensor->sensortype=="Radiograph"){echo " selected";}?>>Radiograph</option>
								<option value="Potentiometer"<?php if($thissensor->sensortype=="Potentiometer"){echo " selected";}?>>Potentiometer</option>
								<option value="Other"<?php if($thissensor->sensortype=="Other"){echo " selected";}?>>Other</option>
							</select>
							<?php
								if($thissensor->sensortype=="Other"){
									$display = "inline";
								}else{
									$display = "none";
								}
							?>
							<span id="dispvol_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
								<input type="text" id="dispvol_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type..." value="<?php echo $thissensor->othersensortype?>">
							</span>
						</div>
						<div class="rowdivv">
							Sensor Location:
							<select id="dispvol_sensorlocation_<?php echo $sensor_num?>">
								<option value="">Select...</option>
								<option value="Internal"<?php if($thissensor->sensorlocation=="Internal"){echo " selected";}?>>Internal</option>
								<option value="External"<?php if($thissensor->sensorlocation=="External"){echo " selected";}?>>External</option>
							</select>
							&nbsp;&nbsp;&nbsp;
							Calibration: <input type="text" id="dispvol_sensorcalibration_<?php echo $sensor_num?>" value="<?php echo $thissensor->sensorcalibration?>">
						</div>
						<div class="rowdivv">
							Sensor Notes:
							<textarea id="dispvol_sensornotes_<?php echo $sensor_num?>" style="width:400px;"><?php echo $thissensor->sensornotes?></textarea>
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

					<div style="float:left;display:<?php echo $dzshow?>;" class="dropzone" id="dispvol_sensorfile_<?php echo $sensor_num?>"></div>
					<div style="text-align:center;float:left;vertical-align:middle;padding-top:50px;display:<?php echo $imgshow?>;" id="dispvol_sensorlink_<?php echo $sensor_num?>">
						<a href="/sensor_calibration_file/dispvolsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
						<a href="javascript:deleteCalibration('dispvol', <?php echo $sensor_num?>);">Delete</a>
					</div>

					<div style="clear:left;"></div>
				</div>

				<?php
				}//end foreach
				?>

				<div class="rowdiv" id="dispvoladdsensorbutton">
					 <button onclick="add_dispvol_sensor(); return false;">Add Additional Sensor</button>
				</div>

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

					<?php if($sensor_num > 1){?>
					<div><a href="javascript:removedispshearsensor<?php echo $sensor_num?>();">Remove</a></div>
					<?php }?>
					<div style="float:left;width:600px;">
						<div class="rowdivv" style="white-space: nowrap;">
							Sensor Type:
							<select id="dispshear_sensortype_<?php echo $sensor_num?>" style="max-width:200px;">
								<option value="">Select...</option>
								<option value="DCDT"<?php if($thissensor->sensortype=="DCDT"){echo " selected";}?>>DCDT</option>
								<option value="LVDT"<?php if($thissensor->sensortype=="LVDT"){echo " selected";}?>>LVDT</option>
								<option value="Cantilever"<?php if($thissensor->sensortype=="Cantilever"){echo " selected";}?>>Cantilever</option>
								<option value="Direct Contact Strain Gauge"<?php if($thissensor->sensortype=="Direct Contact Strain Gauge"){echo " selected";}?>>Direct Contact Strain Gauge</option>
								<option value="Extensometer"<?php if($thissensor->sensortype=="Extensometer"){echo " selected";}?>>Extensometer</option>
								<option value="Fiber Optic"<?php if($thissensor->sensortype=="Fiber Optic"){echo " selected";}?>>Fiber Optic</option>
								<option value="Laser"<?php if($thissensor->sensortype=="Laser"){echo " selected";}?>>Laser</option>
								<option value="Radiograph"<?php if($thissensor->sensortype=="Radiograph"){echo " selected";}?>>Radiograph</option>
								<option value="Potentiometer"<?php if($thissensor->sensortype=="Potentiometer"){echo " selected";}?>>Potentiometer</option>
								<option value="Resolver"<?php if($thissensor->sensortype=="Resolver"){echo " selected";}?>>Resolver</option>
								<option value="Encoder"<?php if($thissensor->sensortype=="Encoder"){echo " selected";}?>>Encoder</option>
								<option value="RVDT"<?php if($thissensor->sensortype=="RVDT"){echo " selected";}?>>RVDT</option>
								<option value="Other"<?php if($thissensor->sensortype=="Other"){echo " selected";}?>>Other</option>
							</select>
							<?php
								if($thissensor->sensortype=="Other"){
									$display = "inline";
								}else{
									$display = "none";
								}
							?>
							<span id="dispshear_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
								<input type="text" id="dispshear_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type..." value="<?php echo $thissensor->othersensortype?>">
							</span>
						</div>
						<div class="rowdivv">
							Sensor Location:
							<select id="dispshear_sensorlocation_<?php echo $sensor_num?>">
								<option value="">Select...</option>
								<option value="Internal"<?php if($thissensor->sensorlocation=="Internal"){echo " selected";}?>>Internal</option>
								<option value="External"<?php if($thissensor->sensorlocation=="External"){echo " selected";}?>>External</option>
							</select>
							&nbsp;&nbsp;&nbsp;
							Calibration: <input type="text" id="dispshear_sensorcalibration_<?php echo $sensor_num?>" value="<?php echo $thissensor->sensorcalibration?>">
						</div>
						<div class="rowdivv">
							Sensor Notes:
							<textarea id="dispshear_sensornotes_<?php echo $sensor_num?>" style="width:400px;"><?php echo $thissensor->sensornotes?></textarea>
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

					<div style="float:left;display:<?php echo $dzshow?>;" class="dropzone" id="dispshear_sensorfile_<?php echo $sensor_num?>"></div>
					<div style="text-align:center;float:left;vertical-align:middle;padding-top:50px;display:<?php echo $imgshow?>;" id="dispshear_sensorlink_<?php echo $sensor_num?>">
						<a href="/sensor_calibration_file/dispshearsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
						<a href="javascript:deleteCalibration('dispshear', <?php echo $sensor_num?>);">Delete</a>
					</div>

					<div style="clear:left;"></div>
				</div>

				<?php
				}//end foreach
				?>

				<div class="rowdiv" id="dispshearaddsensorbutton">
					 <button onclick="add_dispshear_sensor(); return false;">Add Additional Sensor</button>
				</div>
			</div>
		</div>

	<?php
	if($j->force != ""){
		$force_checked = " checked";
		$force_display = " style=\"display:block;\"";
	}
	?>

		<div class="checkheader"><input type="checkbox" id="checkbox_force"<?php echo $force_checked?>> Force</div>
		<div class="checkbody" id="force_wrapper"<?php echo $force_display?>>

			<div class="rowdivv">
				Max Force:
				<input type="text" class="only-numeric" id="force_maxforce" value="<?php echo $j->force->maxforce?>">
				<select id="force_maxforce_unit">
					<option value="N"<?php if($j->force->maxforceunit=="N"){echo " selected";}?>>N</option>
					<option value="kN"<?php if($j->force->maxforceunit=="kN"){echo " selected";}?>>kN</option>
				</select>
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

				<?php if($sensor_num > 1){?>
				<div><a href="javascript:removeforcesensor<?php echo $sensor_num?>();">Remove</a></div>
				<?php }?>
				<div style="float:left;width:600px;">
					<div class="rowdivv" style="white-space: nowrap;">
						Sensor Type:
						<select id="force_sensortype_<?php echo $sensor_num?>" style="max-width:200px;">
							<option value="">Select...</option>
							<option value="Force Gauge"<?php if($thissensor->sensortype=="Force Gauge"){echo " selected";}?>>Force Gauge</option>
							<option value="Capacitance"<?php if($thissensor->sensortype=="Capacitance"){echo " selected";}?>>Capacitance</option>
							<option value="LVDT"<?php if($thissensor->sensortype=="LVDT"){echo " selected";}?>>LVDT</option>
							<option value="DCDT""<?php if($thissensor->sensortype=="DCDT"){echo " selected";}?>>DCDT"</option>
							<option value="XRay Distortion"<?php if($thissensor->sensortype=="XRay Distortion"){echo " selected";}?>>XRay Distortion</option>
							<option value="Other"<?php if($thissensor->sensortype=="Other"){echo " selected";}?>>Other</option>
						</select>
						<?php
							if($thissensor->sensortype=="Other"){
								$display = "inline";
							}else{
								$display = "none";
							}
						?>
						<span id="force_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
							<input type="text" id="force_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type..." value="<?php echo $thissensor->othersensortype?>">
						</span>
					</div>
					<div class="rowdivv">
						Sensor Location:
						<select id="force_sensorlocation_<?php echo $sensor_num?>">
							<option value="">Select...</option>
							<option value="Internal"<?php if($thissensor->sensorlocation=="Internal"){echo " selected";}?>>Internal</option>
							<option value="External"<?php if($thissensor->sensorlocation=="External"){echo " selected";}?>>External</option>
						</select>
						&nbsp;&nbsp;&nbsp;
						Calibration: <input type="text" id="force_sensorcalibration_<?php echo $sensor_num?>" value="<?php echo $thissensor->sensorcalibration?>">
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<textarea id="force_sensornotes_<?php echo $sensor_num?>" style="width:400px;"><?php echo $thissensor->sensornotes?></textarea>
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

				<div style="float:left;display:<?php echo $dzshow?>;" class="dropzone" id="force_sensorfile_<?php echo $sensor_num?>"></div>
				<div style="text-align:center;float:left;vertical-align:middle;padding-top:50px;display:<?php echo $imgshow?>;" id="force_sensorlink_<?php echo $sensor_num?>">
					<a href="/sensor_calibration_file/forcesensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
					<a href="javascript:deleteCalibration('force', <?php echo $sensor_num?>);">Delete</a>
				</div>

				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

			<div class="rowdiv" id="forceaddsensorbutton">
				 <button onclick="add_force_sensor(); return false;">Add Additional Sensor</button>
			</div>

		</div>

	<?php
	if($j->torque != ""){
		$torque_checked = " checked";
		$torque_display = " style=\"display:block;\"";
	}
	?>

		<div class="checkheader"><input type="checkbox" id="checkbox_torque"<?php echo $torque_checked?>> Torque</div>
		<div class="checkbody" id="torque_wrapper"<?php echo $torque_display?>>

			<div class="rowdivv">
				Max Torque:
				<input type="text" class="only-numeric" id="torque_maxtorque" value="<?php echo $j->torque->maxtorque?>">
				<select id="torque_maxtorque_unit">
					<option value="N"<?php if($j->torque->maxtorqueunit=="N"){echo " selected";}?>>N</option>
					<option value="kN"<?php if($j->torque->maxtorqueunit=="N"){echo " selected";}?>>kN</option>
				</select>
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

				<?php if($sensor_num > 1){?>
				<div><a href="javascript:removetorquesensor<?php echo $sensor_num?>();">Remove</a></div>
				<?php }?>
				<div style="float:left;width:600px;">
					<div class="rowdivv">Torque Cell/Sensor Characteristics:</div>
					<div class="rowdivv" style="white-space: nowrap;">
						Geometry of Elastic Element:
						<select id="torque_geometryofelasticelement_<?php echo $sensor_num?>" style="max-width:200px;">
							<option value="">Select...</option>
							<option value="Solid Shaft"<?php if($thissensor->geometryofelasticelement=="Solid Shaft"){echo " selected";}?>>Solid Shaft</option>
							<option value="Hollow Shaft"<?php if($thissensor->geometryofelasticelement=="Hollow Shaft"){echo " selected";}?>>Hollow Shaft</option>
							<option value="Lever Arm with Spring"<?php if($thissensor->geometryofelasticelement=="Lever Arm with Spring"){echo " selected";}?>>Lever Arm with Spring</option>
							<option value="Other"<?php if($thissensor->geometryofelasticelement=="Other"){echo " selected";}?>>Other</option>
						</select>
						<?php
						if($thissensor->geometryofelasticelement=="Other"){
							$display="inline";
						}else{
							$display="none";
						}
						?>
						<span id="torque_othergeometryofelasticelementspan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
							<input type="text" id="torque_othergeometryofelasticelement_<?php echo $sensor_num?>" placeholder="Other Geometry..." value="<?php echo $thissensor->othergeometryofelasticelement?>">
						</span>
					</div>
					<div class="rowdivv" style="white-space: nowrap;">
						Distortion Sensor Type:
						<select id="torque_sensortype_<?php echo $sensor_num?>" style="max-width:200px;">
							<option value="">Select...</option>
							<option value="Strain Gauges"<?php if($thissensor->sensortype=="Strain Gauges"){echo " selected";}?>>Strain Gauges</option>
							<option value="LVDT"<?php if($thissensor->sensortype=="LVDT"){echo " selected";}?>>LVDT</option>
							<option value="Dial Gauge"<?php if($thissensor->sensortype=="Dial Gauge"){echo " selected";}?>>Dial Gauge</option>
							<option value="Potentiometer"<?php if($thissensor->sensortype=="Potentiometer"){echo " selected";}?>>Potentiometer</option>
							<option value="Other"<?php if($thissensor->sensortype=="Other"){echo " selected";}?>>Other</option>
						</select>
						<?php
						if($thissensor->sensortype=="Other"){
							$display="inline";
						}else{
							$display="none";
						}
						?>
						<span id="torque_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
							<input type="text" id="torque_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type..." value="<?php echo $thissensor->othersensortype?>">
						</span>
						<?php
						if($thissensor->sensortype=="Strain Gauges"){
							$display="inline";
						}else{
							$display="none";
						}
						?>
						<span id="torque_numberofstraingaugesspan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
							Number of Strain Gauges: <input type="text" class="only-numeric" id="torque_numberofstraingauges_<?php echo $sensor_num?>" value="<?php echo $thissensor->numberofstraingauges?>">
						</span>
					</div>
					<div class="rowdivv">
						Sensor Location:
						<select id="torque_sensorlocation_<?php echo $sensor_num?>">
							<option value="">Select...</option>
							<option value="Internal"<?php if($thissensor->sensorlocation=="Internal"){echo " selected";}?>>Internal</option>
							<option value="External"<?php if($thissensor->sensorlocation=="External"){echo " selected";}?>>External</option>
						</select>
						&nbsp;&nbsp;&nbsp;
						Calibration: <input type="text" id="torque_sensorcalibration_<?php echo $sensor_num?>" value="<?php echo $thissensor->sensorcalibration?>">
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<textarea id="torque_sensornotes_<?php echo $sensor_num?>" style="width:400px;"><?php echo $thissensor->sensornotes?></textarea>
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

				<div style="float:left;display:<?php echo $dzshow?>;" class="dropzone" id="torque_sensorfile_<?php echo $sensor_num?>"></div>
				<div style="text-align:center;float:left;vertical-align:middle;padding-top:50px;display:<?php echo $imgshow?>;" id="torque_sensorlink_<?php echo $sensor_num?>">
					<a href="/sensor_calibration_file/torquesensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
					<a href="javascript:deleteCalibration('torque', <?php echo $sensor_num?>);">Delete</a>
				</div>

				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

			<div class="rowdiv" id="torqueaddsensorbutton">
				 <button onclick="add_torque_sensor(); return false;">Add Additional Sensor</button>
			</div>

		</div>

	<?php
	if($j->pore != ""){
		$pore_checked = " checked";
		$pore_display = " style=\"display:block;\"";
	}
	?>

		<div class="checkheader"><input type="checkbox" id="checkbox_porefluids"<?php echo $pore_checked?>> Pore Fluids</div>
		<div class="checkbody" id="porefluids_wrapper"<?php echo $pore_display?>>
			<div class="rowdivv">
				<input type="checkbox" id="pore_undrainedonly"<?php if($j->pore->undrainedonly!=""){echo " checked";}?>> Undrained Only
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="pore_measured"<?php if($j->pore->measured!=""){echo " checked";}?>> Measured
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="pore_servocontrolled"<?php if($j->pore->servocontrolled!=""){echo " checked";}?>> Servo-controlled
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="pore_flowthrough"<?php if($j->pore->flowthrough!=""){echo " checked";}?>> Flowthrough
			</div>
			<div class="rowdivv">
				Min Pore Fluid Pressure:
				<input type="text" class="only-numeric" id="pore_minporefluidpressure" value="<?php echo $j->pore->minporefluidpressure?>">
				<select id="pore_minporefluidpressureunit">
					<option value="kPa"<?php if($j->pore->minporefluidpressureunit=="kPa"){echo " selected";}?>>kPa</option>
					<option value="MPa"<?php if($j->pore->minporefluidpressureunit=="MPa" || $j->pore->minporefluidpressureunit==""){echo " selected";}?>>MPa</option>
					<option value="GPa"<?php if($j->pore->minporefluidpressureunit=="GPa"){echo " selected";}?>>GPa</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max Pore Fluid Pressure:
				<input type="text" class="only-numeric" id="pore_maxporefluidpressure" value="<?php echo $j->pore->maxporefluidpressure?>">
				<select id="pore_maxporefluidpressureunit">
					<option value="kPa"<?php if($j->pore->maxporefluidpressureunit=="kPa"){echo " selected";}?>>kPa</option>
					<option value="MPa"<?php if($j->pore->maxporefluidpressureunit=="MPa" || $j->pore->maxporefluidpressureunit==""){echo " selected";}?>>MPa</option>
					<option value="GPa"<?php if($j->pore->maxporefluidpressureunit=="GPa"){echo " selected";}?>>GPa</option>
				</select>
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

				<?php if($sensor_num > 1){?>
				<div><a href="javascript:removeporesensor<?php echo $sensor_num?>();">Remove</a></div>
				<?php }?>
				<div style="float:left;width:600px;">
					<div class="rowdivv" style="white-space: nowrap;">
						Sensor Type:
						<select id="pore_sensortype_<?php echo $sensor_num?>" style="max-width:200px;">
							<option value="">Select...</option>
							<option value="pore Gauge"<?php if($thissensor->sensortype=="pore Gauge"){echo " selected";}?>>pore Gauge</option>
							<option value="Capacitance"<?php if($thissensor->sensortype=="Capacitance"){echo " selected";}?>>Capacitance</option>
							<option value="LVDT"<?php if($thissensor->sensortype=="LVDT"){echo " selected";}?>>LVDT</option>
							<option value="DCDT"<?php if($thissensor->sensortype=="DCDT"){echo " selected";}?>>DCDT</option>
							<option value="XRay Distortion"<?php if($thissensor->sensortype=="XRay Distortion"){echo " selected";}?>>XRay Distortion</option>
							<option value="Other"<?php if($thissensor->sensortype=="Other"){echo " selected";}?>>Other</option>
						</select>
						<?php
						if($thissensor->sensortype=="Other"){
							$display="inline";
						}else{
							$display="none";
						}
						?>
						<span id="pore_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:<?php echo $display?>;">
							<input type="text" id="pore_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type..." value="<?php echo $thissensor->othersensortype?>">
						</span>
					</div>
					<div class="rowdivv">
						Sensor Location:
						<select id="pore_sensorlocation_<?php echo $sensor_num?>">
							<option value="">Select...</option>
							<option value="Internal"<?php if($thissensor->sensorlocation=="Internal"){echo " selected";}?>>Internal</option>
							<option value="External"<?php if($thissensor->sensorlocation=="External"){echo " selected";}?>>External</option>
						</select>
						&nbsp;&nbsp;&nbsp;
						Calibration: <input type="text" id="pore_sensorcalibration_<?php echo $sensor_num?>" value="<?php echo $thissensor->sensorcalibration?>">
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<textarea id="pore_sensornotes_<?php echo $sensor_num?>" style="width:400px;"><?php echo $thissensor->sensornotes?></textarea>
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

				<div style="float:left;display:<?php echo $dzshow?>;" class="dropzone" id="pore_sensorfile_<?php echo $sensor_num?>"></div>
				<div style="text-align:center;float:left;vertical-align:middle;padding-top:50px;display:<?php echo $imgshow?>;" id="pore_sensorlink_<?php echo $sensor_num?>">
					<a href="/sensor_calibration_file/poresensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>">Download</a><br>
					<a href="javascript:deleteCalibration('pore', <?php echo $sensor_num?>);">Delete</a>
				</div>

				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

			<div class="rowdiv" id="poreaddsensorbutton">
				 <button onclick="add_pore_sensor(); return false;">Add Additional Sensor</button>
			</div>

		</div>

	<?php
	if($j->acoustic != ""){
		$acoustic_checked = " checked";
		$acoustic_display = " style=\"display:block;\"";
	}
	?>

		<div class="checkheader"><input type="checkbox" id="checkbox_acousticemissions"<?php echo $acoustic_checked?>> Acoustic Emissions</div>
		<div class="checkbody" id="acousticemissions_wrapper"<?php echo $acoustic_display?>>
			<div class="rowdivv">
				<input type="checkbox" id="acoustic_continuousstreaming"<?php if($j->acoustic->continuousstreaming != ""){echo " checked";}?>> Continuous Streaming
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="acoustic_triggeredrecording"<?php if($j->acoustic->triggeredrecording != ""){echo " checked";}?>> Triggered Recording
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="acoustic_momentanalysiscalibratedamplitude"<?php if($j->acoustic->momentanalysiscalibratedamplitude != ""){echo " checked";}?>> Moment analysis Calibrated Amplitude
			</div>
			<div class="rowdivv">
				<input type="checkbox" id="acoustic_transmissivityreflectivity"<?php if($j->acoustic->transmissivityreflectivity != ""){echo " checked";}?>> Acoustic transmissivity / reflectivity
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="acoustic_aecount"<?php if($j->acoustic->aecount != ""){echo " checked";}?>> AE Count
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="acoustic_tomography"<?php if($j->acoustic->tomography != ""){echo " checked";}?>> Tomography
			</div>
			<div class="rowdivv">
				<input type="checkbox" id="acoustic_momentanalysisrelativeamplitude"<?php if($j->acoustic->momentanalysisrelativeamplitude != ""){echo " checked";}?>> Moment analysis relative amplitude
			</div>

			<div class="rowdivv">
				Frequency Range:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Min:
				<input type="text" class="only-numeric" id="acoustic_minfrequencyrange" value="<?php echo $j->acoustic->minfrequencyrange?>">
				<select id="acoustic_minfrequencyrangeunit">
					<option value="Hz"<?php if($j->acoustic->minfrequencyrange == "Hz"){echo " selected";}?>>Hz</option>
					<option value="kHz"<?php if($j->acoustic->minfrequencyrange == "kHz"){echo " selected";}?>>kHz</option>
					<option value="MHz"<?php if($j->acoustic->minfrequencyrange == "MHz"){echo " selected";}?>>MHz</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max:
				<input type="text" class="only-numeric" id="acoustic_maxfrequencyrange">
				<select id="acoustic_maxfrequencyrangeunit">
					<option value="Hz"<?php if($j->acoustic->maxfrequencyrange == "Hz"){echo " selected";}?>>Hz</option>
					<option value="kHz"<?php if($j->acoustic->maxfrequencyrange == "kHz"){echo " selected";}?>>kHz</option>
					<option value="MHz"<?php if($j->acoustic->maxfrequencyrange == "MHz"){echo " selected";}?>>MHz</option>
				</select>
			</div>
			<div class="rowdivv">
				Sensor Notes:
				<textarea id="acoustic_sensornotes" style="width:400px;"><?php echo $j->acoustic->sensornotes?></textarea>
			</div>

		</div>

	<?php
	if($j->elastic != ""){
		$elastic_checked = " checked";
		$elastic_display = " style=\"display:block;\"";
	}
	?>

		<div class="checkheader"><input type="checkbox" id="checkbox_elasticwave"<?php echo $elastic_checked?>> In-situ Elastic Wave Velocities</div>
		<div class="checkbody" id="elasticwave_wrapper"<?php echo $elastic_display?>>
			<div class="rowdivv">
				<input type="checkbox" id="elastic_piezoelectricsensors"<?php if($j->elastic->piezoelectricsensors != ""){echo " checked";}?>> Piezoelectric Sensors
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Piezoelectric Transducer Type: <input type="text" id="elastic_piezoelectricyransduceryype" value="<?php echo $j->elastic->piezoelectricyransduceryype?>">
			</div>
			<div class="rowdivv">
				<input type="checkbox" id="elastic_pwave"<?php if($j->elastic->pwave != ""){echo " checked";}?>> P-Wave
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="elastic_swave"<?php if($j->elastic->swave != ""){echo " checked";}?>> S-Wave
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="elastic_s1wave"<?php if($j->elastic->s1wave != ""){echo " checked";}?>> S1-Wave
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="elastic_s2wave"<?php if($j->elastic->s2wave != ""){echo " checked";}?>> S2-Wave
			</div>
			<div class="rowdivv">
				Frequency Range:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Min:
				<input type="text" class="only-numeric" id="elastic_minfrequencyrange" value="<?php echo $j->elastic->minfrequencyrange?>">
				<select id="elastic_minfrequencyrangeunit">
					<option value="Hz"<?php if($j->elastic->minfrequencyrangeunit == "Hz"){echo " selected";}?>>Hz</option>
					<option value="kHz"<?php if($j->elastic->minfrequencyrangeunit == "kHz"){echo " selected";}?>>kHz</option>
					<option value="MHz"<?php if($j->elastic->minfrequencyrangeunit == "MHz"){echo " selected";}?>>MHz</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max:
				<input type="text" class="only-numeric" id="elastic_maxfrequencyrange" value="<?php echo $j->elastic->maxfrequencyrange?>">
				<select id="elastic_maxfrequencyrangeunit">
					<option value="Hz"<?php if($j->elastic->maxfrequencyrangeunit == "Hz"){echo " selected";}?>>Hz</option>
					<option value="kHz"<?php if($j->elastic->maxfrequencyrangeunit == "kHz"){echo " selected";}?>>kHz</option>
					<option value="MHz"<?php if($j->elastic->maxfrequencyrangeunit == "MHz"){echo " selected";}?>>MHz</option>
				</select>
			</div>
			<div class="rowdivv">
				Sensor Notes:
				<textarea id="elastic_sensornotes" style="width:400px;"><?php echo $j->elastic->sensornotes?></textarea>
			</div>

		</div>

	<?php
	if($j->electrical != ""){
		$electrical_checked = " checked";
		$electrical_display = " style=\"display:block;\"";
	}
	?>

		<div class="checkheader"><input type="checkbox" id="checkbox_electricalconductivity"<?php echo $electrical_checked?>> Electrical Conductivity</div>
		<div class="checkbody" id="electricalconductivity_wrapper"<?php echo $electrical_display?>>

			<div class="rowdivv">
				<div style="float:left;width:600px;">
					<div class="rowdivv">
						<input type="checkbox" id="electrical_frequencydependent"<?php if($j->electrical->frequencydependent != ""){echo " checked";}?>> Frequency Dependent
					</div>
					<div class="rowdivv">
						Amplitude of Test Pulse:
						<select id="electrical_amplitude">
							<option value="">Select...</option>
							<option value="Sine Wave"<?php if($j->electrical->amplitude == "Sine Wave"){echo " selected";}?>>Sine Wave</option>
							<option value="Square Wave"<?php if($j->electrical->amplitude == "Square Wave"){echo " selected";}?>>Square Wave</option>
							<option value="Trangle Wave"<?php if($j->electrical->amplitude == "Trangle Wave"){echo " selected";}?>>Trangle Wave</option>
						</select>
					</div>
					<div class="rowdivv">
						Electrode Type:
						<select id="electrical_electrodetype">
							<option value="">Select...</option>
							<option value="Ag/AgCl Electrode"<?php if($j->electrical->electrodetype == "Ag/AgCl Electrode"){echo " selected";}?>>Ag/AgCl Electrode</option>
							<option value="Cu Electrode"<?php if($j->electrical->electrodetype == "Cu Electrode"){echo " selected";}?>>Cu Electrode</option>
							<option value="Pb Electrode"<?php if($j->electrical->electrodetype == "Pb Electrode"){echo " selected";}?>>Pb Electrode</option>
							<option value="Other Electrode"<?php if($j->electrical->electrodetype == "Other Electrode"){echo " selected";}?>>Other Electrode</option>
						</select>
						<?php
						if($j->electrical->electrodetype == "Other Electrode"){
							$display="inline";
						}else{
							$display="none";
						}
						?>
						<input type="text" id="electrical_otherelectrodetype" placeholder="Other Electrode Type..." style="display:<?php echo $display?>;max-width:150px;" value="<?php echo $j->electrical->otherelectrodetype?>">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Num Electrodes:
						<select id="electrical_numberofelectrodes">
							<option value="">Select...</option>
							<option value="2"<?php if($j->electrical->numberofelectrodes == "2"){echo " selected";}?>>2</option>
							<option value="3"<?php if($j->electrical->numberofelectrodes == "3"){echo " selected";}?>>3</option>
							<option value="4"<?php if($j->electrical->numberofelectrodes == "4"){echo " selected";}?>>4</option>
						</select>
					</div>

					<div class="rowdivv">
						Calibration: <input type="text" id="electrical_sensorcalibration" value="<?php echo $j->electrical->sensorcalibration?>">
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<textarea id="electrical_sensornotes" style="width:400px;"><?php echo $j->electrical->sensornotes?></textarea>
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

				<div style="float:left;display:<?php echo $dzshow?>;" class="dropzone" id="electrical_sensorfile"></div>
				<div style="text-align:center;float:left;vertical-align:middle;padding-top:50px;display:<?php echo $imgshow?>;" id="electrical_sensorlink">
					<a href="/sensor_calibration_file/electrical/<?php echo $apparatus_pkey?>">Download</a><br>
					<a href="javascript:deleteElectrical();">Delete</a>
				</div>

				<div style="clear:left;"></div>
			</div>
		</div>
	</div>

	<div class="rowdiv rowheader" style="padding-top:25px;">General Apparatus Description and Notes</div>
	<div class="notesbox" style="text-align:center;margin-left:30px;margin-right:30px;">
		<textarea id="apparatus_description_notes" rows="10" style="width:100%;"><?php echo $j->apparatusdescriptionnotes?></textarea>
	</div>

	<div class="rowdiv rowheader" style="padding-top:25px;">
		<button onclick="doSubmit(); return false;">Save Changes</button>
		<!--<a onclick="doSubmit();">here</a>-->
	</div>

	<!--</form>-->

</div>

<script src="/assets/js/apparatus/add_apparatus_<?php echo $apparatus_pkey?>.js"></script>
<script src="/addtest.js"></script>

<script type='text/javascript'>

	Dropzone.autoDiscover = false;
	// or disable for specific dropzone:

	var rigSchematicDropZone = null;
	var rigPhotoDropZone = null;

	$(function() {

		rigPhotoDropZone = new Dropzone("#rigPhoto", {
			url: "/expimageupload/photo/<?php echo $apparatus_pkey?>",
			addRemoveLinks: true,
			maxFiles: 1,
			acceptedFiles: "image
			console.log("file removed:");
			console.log(file);
			$.get( "/expimagedelete/photo/<?php echo $apparatus_pkey?>" );
		});

		//****************************************************************************************

		rigSchematicDropZone = new Dropzone("#rigSchematic", {
			url: "/expimageupload/schematic/<?php echo $apparatus_pkey?>",
			addRemoveLinks: true,
			maxFiles: 1
		});

		rigSchematicDropZone.on("removedfile", function(file) {
			/* Maybe display some more file information on your page */
			console.log("file removed:");
			console.log(file);
			$.get( "/expimagedelete/schematic/<?php echo $apparatus_pkey?>" );
		});

		//****************************************************************************************

		<?php
		for($cp_sensor_num = 1; $cp_sensor_num <= $num_sensors; $cp_sensor_num++){
		?>

		cpSensorFile<?php echo $cp_sensor_num?>DropZone = new Dropzone("#cp_sensorfile_<?php echo $cp_sensor_num?>", {
			url: "/expimageupload/cpsensor/<?php echo $apparatus_pkey?>/<?php echo $cp_sensor_num?>",
			addRemoveLinks: true,
			maxFiles: 1
		});

		cpSensorFile<?php echo $cp_sensor_num?>DropZone.on("removedfile", function(file) {
			/* Maybe display some more file information on your page */
			console.log("file removed:");
			console.log(file);
			$.get( "/expimagedelete/cpsensor/<?php echo $apparatus_pkey?>/<?php echo $cp_sensor_num?>" );
		});

		<?php
		}
		?>

		//****************************************************************************************

		<?php
		for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
		?>

		tempSensorFile<?php echo $sensor_num?>DropZone = new Dropzone("#temp_sensorfile_<?php echo $sensor_num?>", {
			url: "/expimageupload/tempsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>",
			addRemoveLinks: true,
			maxFiles: 1
		});

		tempSensorFile<?php echo $sensor_num?>DropZone.on("removedfile", function(file) {
			/* Maybe display some more file information on your page */
			console.log("file removed:");
			console.log(file);
			$.get( "/expimagedelete/tempsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>" );
		});

		<?php
		}
		?>

		//****************************************************************************************

		<?php
		for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
		?>

		dispaxSensorFile<?php echo $sensor_num?>DropZone = new Dropzone("#dispax_sensorfile_<?php echo $sensor_num?>", {
			url: "/expimageupload/dispaxsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>",
			addRemoveLinks: true,
			maxFiles: 1
		});

		dispaxSensorFile<?php echo $sensor_num?>DropZone.on("removedfile", function(file) {
			/* Maybe display some more file information on your page */
			console.log("file removed:");
			console.log(file);
			$.get( "/expimagedelete/dispaxsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>" );
		});

		<?php
		}
		?>

		//****************************************************************************************

		<?php
		for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
		?>

		disprotSensorFile<?php echo $sensor_num?>DropZone = new Dropzone("#disprot_sensorfile_<?php echo $sensor_num?>", {
			url: "/expimageupload/disprotsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>",
			addRemoveLinks: true,
			maxFiles: 1
		});

		disprotSensorFile<?php echo $sensor_num?>DropZone.on("removedfile", function(file) {
			/* Maybe display some more file information on your page */
			console.log("file removed:");
			console.log(file);
			$.get( "/expimagedelete/disprotsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>" );
		});

		<?php
		}
		?>

		//****************************************************************************************

		<?php
		for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
		?>

		dispvolSensorFile<?php echo $sensor_num?>DropZone = new Dropzone("#dispvol_sensorfile_<?php echo $sensor_num?>", {
			url: "/expimageupload/dispvolsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>",
			addRemoveLinks: true,
			maxFiles: 1
		});

		dispvolSensorFile<?php echo $sensor_num?>DropZone.on("removedfile", function(file) {
			/* Maybe display some more file information on your page */
			console.log("file removed:");
			console.log(file);
			$.get( "/expimagedelete/dispvolsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>" );
		});

		<?php
		}
		?>

		//****************************************************************************************

		<?php
		for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
		?>

		dispshearSensorFile<?php echo $sensor_num?>DropZone = new Dropzone("#dispshear_sensorfile_<?php echo $sensor_num?>", {
			url: "/expimageupload/dispshearsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>",
			addRemoveLinks: true,
			maxFiles: 1
		});

		dispshearSensorFile<?php echo $sensor_num?>DropZone.on("removedfile", function(file) {
			/* Maybe display some more file information on your page */
			console.log("file removed:");
			console.log(file);
			$.get( "/expimagedelete/dispshearsensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>" );
		});

		<?php
		}
		?>

		//****************************************************************************************

		<?php
		for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
		?>

		forceSensorFile<?php echo $sensor_num?>DropZone = new Dropzone("#force_sensorfile_<?php echo $sensor_num?>", {
			url: "/expimageupload/forcesensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>",
			addRemoveLinks: true,
			maxFiles: 1
		});

		forceSensorFile<?php echo $sensor_num?>DropZone.on("removedfile", function(file) {
			/* Maybe display some more file information on your page */
			console.log("file removed:");
			console.log(file);
			$.get( "/expimagedelete/forcesensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>" );
		});

		<?php
		}
		?>

		//****************************************************************************************

		<?php
		for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
		?>

		torqueSensorFile<?php echo $sensor_num?>DropZone = new Dropzone("#torque_sensorfile_<?php echo $sensor_num?>", {
			url: "/expimageupload/torquesensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>",
			addRemoveLinks: true,
			maxFiles: 1
		});

		torqueSensorFile<?php echo $sensor_num?>DropZone.on("removedfile", function(file) {
			/* Maybe display some more file information on your page */
			console.log("file removed:");
			console.log(file);
			$.get( "/expimagedelete/torquesensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>" );
		});

		<?php
		}
		?>

		//****************************************************************************************

		<?php
		for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
		?>

		poreSensorFile<?php echo $sensor_num?>DropZone = new Dropzone("#pore_sensorfile_<?php echo $sensor_num?>", {
			url: "/expimageupload/poresensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>",
			addRemoveLinks: true,
			maxFiles: 1
		});

		poreSensorFile<?php echo $sensor_num?>DropZone.on("removedfile", function(file) {
			/* Maybe display some more file information on your page */
			console.log("file removed:");
			console.log(file);
			$.get( "/expimagedelete/poresensor/<?php echo $apparatus_pkey?>/<?php echo $sensor_num?>" );
		});

		<?php
		}
		?>

		//****************************************************************************************

		electricalDropZone = new Dropzone("#electrical_sensorfile", {
			url: "/expimageupload/electrical/<?php echo $apparatus_pkey?>",
			addRemoveLinks: true,
			maxFiles: 1
		});

		electricalDropZone.on("removedfile", function(file) {
			/* Maybe display some more file information on your page */
			console.log("file removed:");
			console.log(file);
			$.get( "/expimagedelete/electrical/<?php echo $apparatus_pkey?>" );
		});

		$(document).ready(function() {
			$(".only-numeric").bind("keypress", function (e) {
				var keyCode = e.which ? e.which : e.keyCode
				if ((keyCode >= 48 && keyCode <= 57) || keyCode == 46) {

				}else{
					return false;
				}
			});
		});

	})

	function doSubmit(){

		var error = checkFormData();

		if(error == ""){

			//gather and submit data
			var formData = gatherFormData();
			formData.apparatus_pkey = <?php echo $apparatus_pkey?>;

			var formJSON = JSON.stringify(formData, null, 2); // spacing level = 2

			$.post( "edit_apparatus.php", { jsonData: formJSON })
				.done(function( data ) {
				console.log( "Data POSTed: " + JSON.stringify(data, null, 2));
			});

			console.log(formJSON);

			$("#wholewrapper").hide();
			$("#successwrapper").show();

		}else{

			//show error
			$("#errors").html(error);

			$('#errormodal').modal();

		}

	}

	

	function deleteCalibration(type, sensornum){
		var apparatus_pkey = <?php echo $apparatus_pkey?>;

		var r = confirm("Are you sure you want to delete this calibration?");

		if(r == true){
			$("#"+type+"_sensorlink_"+sensornum).hide();
			$("#"+type+"_sensorfile_"+sensornum).show();
			$.get( "/expimagedelete/" + type + "sensor/<?php echo $apparatus_pkey?>/" + sensornum );
		}

		//https://strabospot.org/expimagedelete/forcesensor/613/1

	};

	function deletePhoto(){
		var apparatus_pkey = <?php echo $apparatus_pkey?>;

		var r = confirm("Are you sure you want to delete the apparatus photo?");

		if(r == true){
			$.get( "/expimagedelete/photo/<?php echo $apparatus_pkey?>" );
			$("#rigDZPhotoLabel").show();
			$("#rigPhoto").show();
			$("#rigStaticPhotoLabel").hide();
			$("#rigStaticPhoto").hide();
		}

	};

	function deleteSchematic(){
		var apparatus_pkey = <?php echo $apparatus_pkey?>;

		var r = confirm("Are you sure you want to delete the apparatus schematic?");

		if(r == true){
			$.get( "/expimagedelete/schematic/<?php echo $apparatus_pkey?>" );
			$("#rigDZSchematicLabel").show();
			$("#rigSchematic").show();
			$("#rigStaticSchematicLabel").hide();
			$("#rigStaticSchematic").hide();
		}

	};

	function deleteElectrical(){
		var apparatus_pkey = <?php echo $apparatus_pkey?>;

		//electrical_sensorfile
		//electrical_sensorlink

		//first, delete file on server:

		var r = confirm("Are you sure you want to delete this calibration?");

		if(r == true){
			$.get( "/expimagedelete/electrical/<?php echo $apparatus_pkey?>" );

			//electrical_sensorfile
			//electrical_sensorlink

			$("#electrical_sensorfile").show();
			$("#electrical_sensorlink").hide();
		}

	};

</script>

<div id="errormodal" class="modal">
  <p class="errorbar">Error!</p>
  <p id="errors"></p>
</div>

<?php
include 'includes/footer.php';
?>
