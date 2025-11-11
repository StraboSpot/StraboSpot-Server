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

include("logincheck.php");

$userpkey = (int)$_SESSION['userpkey'];

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

$apparatus_pkey = $db->get_var("SELECT nextval('exp_apparatus_pkey_seq')");

$num_sensors = 5;

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

		$irow = $db->get_row_prepared("

		SELECT
		pkey AS institute_pkey,
		institute_name,
		CASE
			WHEN admincount > 0 THEN 'yes'
			ELSE 'no'
		END is_pi
		FROM (
		SELECT
		pkey,
		institute_name,
		(SELECT count(*) FROM instrument_users WHERE users_pkey = $1 AND institution_pkey = i.pkey) AS admincount
		FROM institute i WHERE i.pkey = $2
		) foo

		", array($userpkey, $institute_pkey));

		if(!in_array($userpkey, $admin_pkeys)){
			$isadmin = false;
		}else{
			$isadmin = true;
		}

		if(!$isadmin && $irow->is_pi == "no"){
			exit();
		}

		$json = $_POST["jsonData"];

		$db->prepare_query("
			INSERT INTO exp_apparatus
					(	pkey,
						name,
						type,
						sub_type,
						json,
						institute_pkey,
						userpkey
					)	VALUES	(
						$1,
						$2,
						$3,
						$4,
						$5,
						$6,
						$7
					)
		", array($apparatus_pkey, $name, $type, $sub_type, $json, $institute_pkey, $userpkey));

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

$institute_pkey = $_GET['i'] ?? '';
if(!is_numeric($institute_pkey) || $institute_pkey == "") exit();
$institute_pkey = (int)$institute_pkey;

$irow = $db->get_row_prepared("

SELECT
pkey AS institute_pkey,
institute_name,
CASE
	WHEN admincount > 0 THEN 'yes'
	ELSE 'no'
END is_pi
FROM (
SELECT
pkey,
institute_name,
(SELECT count(*) FROM instrument_users WHERE users_pkey = $1 AND institution_pkey = i.pkey) AS admincount
FROM institute i WHERE i.pkey = $2
) foo

", array($userpkey, $institute_pkey));

$institute_name = $irow->institute_name;

if(!in_array($userpkey, $admin_pkeys)){
	//only admins for now...
	$isadmin = false;
}else{
	$isadmin = true;
}

if(!$isadmin && $irow->is_pi == "no"){
	exit();
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
<?php

$irows = $db->get_results("select * from institute order by institute_name");

?>

<div id="successwrapper" style="display:none;">
	<div class="rowdiv">
		<h2>Success!</h2>
	</div>
	<div class="rowdiv">
		Apparartus added successfully to <?php echo $institute_name?>.
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
		<h2>Add Apparatus for <?php echo $institute_name?></h2>
	</div>

	<div class="rowdiv">
		<h2>Facility Information</h2>
	</div>

	<input type="hidden" id="institute_pkey" value="<?php echo $institute_pkey?>">

	<!--
	<div class="rowdiv">
		Institute: <span class="redred">*</span>
		<select name="institute_pkey" id="institute_pkey">
			<option value="">Select...</option>
			<?php
			foreach($irows as $row){
			?>
			<option value="<?php echo $row->pkey?>"><?php echo $row->institute_name?></option>
			<?php
			}
			?>
		</select>
	</div>
	<div class="rowdiv" style="font-size:.9em;">
		If you need an institute added to the list above, please contact <a href="mailto:mailto:test@example.com?subject=Need Institute Added to Strabo Database&body=Hello%2C%0A%0APlease%20add%20the%20following%20institute%20to%20the%20Strabo%20Database%3A%0A%0AInstitute%20Type%3A%20%28Government%20or%20Education%29%0AInstitute%20Name%3A%0A%0AThanks%2C%0A%0A<?php echo $_SESSION['firstname']?>%20<?php echo $_SESSION['lastname']?>%0A">test@example.com</a>
	</div>
	-->
	<div class="rowdiv">
		Lab Name: <input type="text" id="lab_name" name="lab_name"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Facility ID: <input type="text" id="facility_id" name="facility_id">
	</div>

	<div class="rowdiv">
		Website: <input type="text" id="website" name="website" placeholder="https://ku.edu">
	</div>

	<div class="rowdiv rowheader">Address</div>

	<div class="rowdiv">
		Country:

		<select name="country" id="country">
			<option value="">Select...</option>
			<option value="United States">United States</option>
			<option value="Afghanistan">Afghanistan</option>
			<option value="Åland Islands">Åland Islands</option>
			<option value="Albania">Albania</option>
			<option value="Algeria">Algeria</option>
			<option value="American Samoa">American Samoa</option>
			<option value="Andorra">Andorra</option>
			<option value="Angola">Angola</option>
			<option value="Anguilla">Anguilla</option>
			<option value="Antarctica">Antarctica</option>
			<option value="Antigua and Barbuda">Antigua and Barbuda</option>
			<option value="Argentina">Argentina</option>
			<option value="Armenia">Armenia</option>
			<option value="Aruba">Aruba</option>
			<option value="Australia">Australia</option>
			<option value="Austria">Austria</option>
			<option value="Azerbaijan">Azerbaijan</option>
			<option value="Bahamas">Bahamas</option>
			<option value="Bahrain">Bahrain</option>
			<option value="Bangladesh">Bangladesh</option>
			<option value="Barbados">Barbados</option>
			<option value="Belarus">Belarus</option>
			<option value="Belgium">Belgium</option>
			<option value="Belize">Belize</option>
			<option value="Benin">Benin</option>
			<option value="Bermuda">Bermuda</option>
			<option value="Bhutan">Bhutan</option>
			<option value="Bolivia, Plurinational State of">Bolivia, Plurinational State of</option>
			<option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
			<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
			<option value="Botswana">Botswana</option>
			<option value="Bouvet Island">Bouvet Island</option>
			<option value="Brazil">Brazil</option>
			<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
			<option value="Brunei Darussalam">Brunei Darussalam</option>
			<option value="Bulgaria">Bulgaria</option>
			<option value="Burkina Faso">Burkina Faso</option>
			<option value="Burundi">Burundi</option>
			<option value="Cambodia">Cambodia</option>
			<option value="Cameroon">Cameroon</option>
			<option value="Canada">Canada</option>
			<option value="Cape Verde">Cape Verde</option>
			<option value="Cayman Islands">Cayman Islands</option>
			<option value="Central African Republic">Central African Republic</option>
			<option value="Chad">Chad</option>
			<option value="Chile">Chile</option>
			<option value="China">China</option>
			<option value="Christmas Island">Christmas Island</option>
			<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
			<option value="Colombia">Colombia</option>
			<option value="Comoros">Comoros</option>
			<option value="Congo">Congo</option>
			<option value="Congo, the Democratic Republic of the">Congo, the Democratic Republic of the</option>
			<option value="Cook Islands">Cook Islands</option>
			<option value="Costa Rica">Costa Rica</option>
			<option value="Côte d'Ivoire">Côte d'Ivoire</option>
			<option value="Croatia">Croatia</option>
			<option value="Cuba">Cuba</option>
			<option value="Curaçao">Curaçao</option>
			<option value="Cyprus">Cyprus</option>
			<option value="Czech Republic">Czech Republic</option>
			<option value="Denmark">Denmark</option>
			<option value="Djibouti">Djibouti</option>
			<option value="Dominica">Dominica</option>
			<option value="Dominican Republic">Dominican Republic</option>
			<option value="Ecuador">Ecuador</option>
			<option value="Egypt">Egypt</option>
			<option value="El Salvador">El Salvador</option>
			<option value="Equatorial Guinea">Equatorial Guinea</option>
			<option value="Eritrea">Eritrea</option>
			<option value="Estonia">Estonia</option>
			<option value="Ethiopia">Ethiopia</option>
			<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
			<option value="Faroe Islands">Faroe Islands</option>
			<option value="Fiji">Fiji</option>
			<option value="Finland">Finland</option>
			<option value="France">France</option>
			<option value="French Guiana">French Guiana</option>
			<option value="French Polynesia">French Polynesia</option>
			<option value="French Southern Territories">French Southern Territories</option>
			<option value="Gabon">Gabon</option>
			<option value="Gambia">Gambia</option>
			<option value="Georgia">Georgia</option>
			<option value="Germany">Germany</option>
			<option value="Ghana">Ghana</option>
			<option value="Gibraltar">Gibraltar</option>
			<option value="Greece">Greece</option>
			<option value="Greenland">Greenland</option>
			<option value="Grenada">Grenada</option>
			<option value="Guadeloupe">Guadeloupe</option>
			<option value="Guam">Guam</option>
			<option value="Guatemala">Guatemala</option>
			<option value="Guernsey">Guernsey</option>
			<option value="Guinea">Guinea</option>
			<option value="Guinea-Bissau">Guinea-Bissau</option>
			<option value="Guyana">Guyana</option>
			<option value="Haiti">Haiti</option>
			<option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
			<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
			<option value="Honduras">Honduras</option>
			<option value="Hong Kong">Hong Kong</option>
			<option value="Hungary">Hungary</option>
			<option value="Iceland">Iceland</option>
			<option value="India">India</option>
			<option value="Indonesia">Indonesia</option>
			<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
			<option value="Iraq">Iraq</option>
			<option value="Ireland">Ireland</option>
			<option value="Isle of Man">Isle of Man</option>
			<option value="Israel">Israel</option>
			<option value="Italy">Italy</option>
			<option value="Jamaica">Jamaica</option>
			<option value="Japan">Japan</option>
			<option value="Jersey">Jersey</option>
			<option value="Jordan">Jordan</option>
			<option value="Kazakhstan">Kazakhstan</option>
			<option value="Kenya">Kenya</option>
			<option value="Kiribati">Kiribati</option>
			<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
			<option value="Korea, Republic of">Korea, Republic of</option>
			<option value="Kuwait">Kuwait</option>
			<option value="Kyrgyzstan">Kyrgyzstan</option>
			<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
			<option value="Latvia">Latvia</option>
			<option value="Lebanon">Lebanon</option>
			<option value="Lesotho">Lesotho</option>
			<option value="Liberia">Liberia</option>
			<option value="Libya">Libya</option>
			<option value="Liechtenstein">Liechtenstein</option>
			<option value="Lithuania">Lithuania</option>
			<option value="Luxembourg">Luxembourg</option>
			<option value="Macao">Macao</option>
			<option value="Macedonia, the former Yugoslav Republic of">Macedonia, the former Yugoslav Republic of</option>
			<option value="Madagascar">Madagascar</option>
			<option value="Malawi">Malawi</option>
			<option value="Malaysia">Malaysia</option>
			<option value="Maldives">Maldives</option>
			<option value="Mali">Mali</option>
			<option value="Malta">Malta</option>
			<option value="Marshall Islands">Marshall Islands</option>
			<option value="Martinique">Martinique</option>
			<option value="Mauritania">Mauritania</option>
			<option value="Mauritius">Mauritius</option>
			<option value="Mayotte">Mayotte</option>
			<option value="Mexico">Mexico</option>
			<option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
			<option value="Moldova, Republic of">Moldova, Republic of</option>
			<option value="Monaco">Monaco</option>
			<option value="Mongolia">Mongolia</option>
			<option value="Montenegro">Montenegro</option>
			<option value="Montserrat">Montserrat</option>
			<option value="Morocco">Morocco</option>
			<option value="Mozambique">Mozambique</option>
			<option value="Myanmar">Myanmar</option>
			<option value="Namibia">Namibia</option>
			<option value="Nauru">Nauru</option>
			<option value="Nepal">Nepal</option>
			<option value="Netherlands">Netherlands</option>
			<option value="New Caledonia">New Caledonia</option>
			<option value="New Zealand">New Zealand</option>
			<option value="Nicaragua">Nicaragua</option>
			<option value="Niger">Niger</option>
			<option value="Nigeria">Nigeria</option>
			<option value="Niue">Niue</option>
			<option value="Norfolk Island">Norfolk Island</option>
			<option value="Northern Mariana Islands">Northern Mariana Islands</option>
			<option value="Norway">Norway</option>
			<option value="Oman">Oman</option>
			<option value="Pakistan">Pakistan</option>
			<option value="Palau">Palau</option>
			<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
			<option value="Panama">Panama</option>
			<option value="Papua New Guinea">Papua New Guinea</option>
			<option value="Paraguay">Paraguay</option>
			<option value="Peru">Peru</option>
			<option value="Philippines">Philippines</option>
			<option value="Pitcairn">Pitcairn</option>
			<option value="Poland">Poland</option>
			<option value="Portugal">Portugal</option>
			<option value="Puerto Rico">Puerto Rico</option>
			<option value="Qatar">Qatar</option>
			<option value="Réunion">Réunion</option>
			<option value="Romania">Romania</option>
			<option value="Russian Federation">Russian Federation</option>
			<option value="Rwanda">Rwanda</option>
			<option value="Saint Barthélemy">Saint Barthélemy</option>
			<option value="Saint Helena, Ascension and Tristan da Cunha">Saint Helena, Ascension and Tristan da Cunha</option>
			<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
			<option value="Saint Lucia">Saint Lucia</option>
			<option value="Saint Martin (French part)">Saint Martin (French part)</option>
			<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
			<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
			<option value="Samoa">Samoa</option>
			<option value="San Marino">San Marino</option>
			<option value="Sao Tome and Principe">Sao Tome and Principe</option>
			<option value="Saudi Arabia">Saudi Arabia</option>
			<option value="Senegal">Senegal</option>
			<option value="Serbia">Serbia</option>
			<option value="Seychelles">Seychelles</option>
			<option value="Sierra Leone">Sierra Leone</option>
			<option value="Singapore">Singapore</option>
			<option value="Sint Maarten (Dutch part)">Sint Maarten (Dutch part)</option>
			<option value="Slovakia">Slovakia</option>
			<option value="Slovenia">Slovenia</option>
			<option value="Solomon Islands">Solomon Islands</option>
			<option value="Somalia">Somalia</option>
			<option value="South Africa">South Africa</option>
			<option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
			<option value="South Sudan">South Sudan</option>
			<option value="Spain">Spain</option>
			<option value="Sri Lanka">Sri Lanka</option>
			<option value="Sudan">Sudan</option>
			<option value="Suriname">Suriname</option>
			<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
			<option value="Swaziland">Swaziland</option>
			<option value="Sweden">Sweden</option>
			<option value="Switzerland">Switzerland</option>
			<option value="Syrian Arab Republic">Syrian Arab Republic</option>
			<option value="Taiwan, Province of China">Taiwan, Province of China</option>
			<option value="Tajikistan">Tajikistan</option>
			<option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
			<option value="Thailand">Thailand</option>
			<option value="Timor-Leste">Timor-Leste</option>
			<option value="Togo">Togo</option>
			<option value="Tokelau">Tokelau</option>
			<option value="Tonga">Tonga</option>
			<option value="Trinidad and Tobago">Trinidad and Tobago</option>
			<option value="Tunisia">Tunisia</option>
			<option value="Turkey">Turkey</option>
			<option value="Turkmenistan">Turkmenistan</option>
			<option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
			<option value="Tuvalu">Tuvalu</option>
			<option value="Uganda">Uganda</option>
			<option value="Ukraine">Ukraine</option>
			<option value="United Arab Emirates">United Arab Emirates</option>
			<option value="United Kingdom">United Kingdom</option>
			<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
			<option value="Uruguay">Uruguay</option>
			<option value="Uzbekistan">Uzbekistan</option>
			<option value="Vanuatu">Vanuatu</option>
			<option value="Venezuela, Bolivarian Republic of">Venezuela, Bolivarian Republic of</option>
			<option value="Viet Nam">Viet Nam</option>
			<option value="Virgin Islands, British">Virgin Islands, British</option>
			<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
			<option value="Wallis and Futuna">Wallis and Futuna</option>
			<option value="Western Sahara">Western Sahara</option>
			<option value="Yemen">Yemen</option>
			<option value="Zambia">Zambia</option>
			<option value="Zimbabwe">Zimbabwe</option>
		</select>

		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span name="stateprovincewrapper" id="stateprovincewrapper" style="width: 300px;">&nbsp;</span>
	</div>

	<div class="rowdiv">
		Address Line 1: <input type="text" id="address1" name="address1" size="60">
	</div>

	<div class="rowdiv">
		Address Line 2: <input type="text" id="address2" name="address2" size="60">
	</div>

	<div class="rowdiv">
		City: <input type="text" id="city" name="city"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Postal Code: <input type="text" id="zip" name="zip">
	</div>

	<div class="rowdiv rowheader">Facility Contact</div>

	<div class="rowdiv">
		First Name: <input type="text" id="contact_first_name" name="contact_first_name"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Last Name: <input type="text" id="contact_last_name" name="contact_last_name">
	</div>

	<div class="rowdiv">
		Title: <input type="text" id="contact_title" name="contact_title"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		Email: <input type="text" id="contact_email" name="contact_email">
	</div>

	<div class="rowdiv">
		<h2>Apparatus Information</h2>
	</div>

	<div class="rowdiv">
		<div style="float:left;padding-left:100px;">
			Apparatus Type: <span class="redred">*</span>
			<select name="apparatus_type" id="apparatus_type">
				<option value="">Select...</option>
				<option value="Hydrostatic Loading Apparatuses">Hydrostatic Loading Apparatuses</option>
				<option value="Uniaxial Apparatus">Uniaxial Apparatus</option>
				<option value="Biaxial Apparatus">Biaxial Apparatus</option>
				<option value="Triaxial (Conventional) Apparatus">Triaxial (Conventional) Apparatus</option>
				<option value="True Triaxial Apparatus">True Triaxial Apparatus</option>
				<option value="Rotary Shear Apparatus">Rotary Shear Apparatus</option>
				<option value="Split Hopkinson Pressure Bar">Split Hopkinson Pressure Bar</option>
				<option value="Indenter">Indenter</option>
				<option value="Nanoindenter">Nanoindenter</option>
				<option value="Load Stamp">Load Stamp</option>
				<option value="Viscosimeter">Viscosimeter</option>
			</select>
		</div>
		<div style="float:left;padding-left:30px;display:none;" name="apparatus_subtype_wrapper" id="apparatus_subtype_wrapper">
			Apparatus Subtype: <span class="redred">*</span>
			<select name="apparatus_subtype" id="apparatus_subtype">
				<option value="">Select...</option>
			</select>
		</div>
		<div style="clear:left"></div>
	</div>

	<div class="rowdiv">
		Apparatus Name: <span class="redred">*</span>
		<input type="text" id="apparatus_name">
	</div>

	<div class="rowdiv" style="padding-top:20px;">
	<div style="float:left;padding-right:20px;font-size:1.3em;padding-top:60px;padding-left:170px;">Upload Rig Photo:</div><div style="float:left;" class="dropzone" id="rigPhoto"></div>
	<div style="float:left;padding-right:20px;font-size:1.3em;padding-top:60px;padding-left:20px;">Upload Rig Schematic:</div><div style="float:left;" class="dropzone" id="rigSchematic"></div>
	<div style="clear:left;"></div>
	</div>

	<div class="rowdiv rowheader" style="padding-top:15px;">Possible Test Types</div>

	<div style="border:1px dashed rgba(50, 50, 50, 0.5);border-radius:10px;padding:20px;width:300px;margin:auto;margin-top:10px;">
		<div style="font-weight:bold;">Static:</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_hip" id="checkbox_hip"> HIP</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_synthesis" id="checkbox_synthesis"> Synthesis</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_depositionevaporation" id="checkbox_depositionevaporation"> Deposition/Evaporation</div>

		<div style="font-weight:bold;">Indentation:</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_indentationcreep" id="checkbox_indentationcreep"> Indentation-Creep</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_hardness" id="checkbox_hardness"> Hardness</div>

		<div style="font-weight:bold;">Pore Structure:</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_permeability" id="checkbox_permeability"> Permeability</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_resistivity" id="checkbox_resistivity"> Resistivity</div>

		<div style="font-weight:bold;">Shear Deformation:</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_simpleshear" id="checkbox_simpleshear"> Simple Shear</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_rotaryshear" id="checkbox_rotaryshear"> Rotary Shear</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_pureshear" id="checkbox_pureshear"> Pure Shear</div>

		<div style="font-weight:bold;">Uniaxial Deformation:</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_constantstress" id="checkbox_constantstress"> Constant Stress</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_constantrate" id="checkbox_constantrate"> Constant Rate</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_loadingunloading" id="checkbox_loadingunloading"> Loading/Unloading</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_stepping" id="checkbox_stepping"> Stepping</div>
		<div style="padding-left:20px;"><input type="checkbox" name="checkbox_extension" id="checkbox_extension"> Extension</div>
	</div>

	<div class="rowdiv rowheader" style="padding-top:25px;">Apparatus Capabilities, Sensors, and Corrections</div>

	<div style="padding-top:20px;margin-left:25px;margin-right:25px;">
		<div class="checkheader"><input type="checkbox" id="checkbox_confiningpressure"> Confining Pressure</div>
		<div class="checkbody" id="confiningpressure_wrapper">
			<div><input type="checkbox" id="cp_servo_controlled"> Servo Controlled</div>
			<div class="rowdivv">
				Confining Medium:
				<select id="cp_confiningmediumone">
					<option value="">Select...</option>
					<option value="Solid">Solid</option>
					<option value="Liquid">Liquid</option>
					<option value="Gas">Gas</option>
				</select>
				<select id="cp_confiningmediumtwo" style="display:none;">
					<option value="">Select...</option>
				</select>
				<input type="text" id="cp_confiningmediumother" placeholder="Enter other type..." style="display:none;">
			</div>
			<div class="rowdivv">
				Min Confining Pressure:
				<input type="text" class="only-numeric" id="cp_minconfiningpressure">
				<select id="cp_minconfiningpressure_unit">
					<option value="kPa">kPa</option>
					<option value="MPa" selected>MPa</option>
					<option value="GPa">GPa</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max Confining Pressure:
				<input type="text" class="only-numeric" id="cp_maxconfiningpressure">
				<select id="cp_maxconfiningpressure_unit">
					<option value="kPa">kPa</option>
					<option value="MPa" selected>MPa</option>
					<option value="GPa">GPa</option>
				</select>
			</div>
			<div class="separator" style="margin-top:5px;">
			Sensor(s)
			</div>

			<?php
			for($cp_sensor_num = 1; $cp_sensor_num <= $num_sensors; $cp_sensor_num++){
			?>

			<div class="rowdivv" id="cp_sensor_wrapper_<?php echo $cp_sensor_num?>" <?php if($cp_sensor_num > 1){?> style="display:none;"<?php }?>>

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
							<option value="Pressure Cell">Pressure Cell</option>
							<option value="Analog Oil Pressure Gauge">Analog Oil Pressure Gauge</option>
							<option value="X-Ray Diffraction">X-Ray Diffraction</option>
							<option value="Ruby Fluorescence">Ruby Fluorescence</option>
							<option value="Other">Other</option>
						</select>
						&nbsp;&nbsp;&nbsp;
						<input type="text" id="cp_sensorothertype_<?php echo $cp_sensor_num?>" style="display:none;" placeholder="Other Sensor Type...">
					</div>
					<div class="rowdivv">
						Sensor Location:
						<select id="cp_sensorlocation_<?php echo $cp_sensor_num?>">
							<option value="">Select...</option>
							<option value="Internal">Internal</option>
							<option value="External">External</option>
						</select>
						&nbsp;&nbsp;&nbsp;
						Calibration: <input type="text" id="cp_sensorcalibration_<?php echo $cp_sensor_num?>">
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<textarea id="cp_sensornotes_<?php echo $cp_sensor_num?>" style="width:400px;"></textarea>
					</div>
				</div>
				<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div><div style="float:left;" class="dropzone" id="cp_sensorfile_<?php echo $cp_sensor_num?>"></div>
				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

			<div class="rowdiv" id="cpaddsensorbutton">
				 <button onclick="add_cp_sensor(); return false;">Add Additional Sensor</button>
			</div>
		</div>

		<div class="checkheader"><input type="checkbox" id="checkbox_temperaturefurnacecryostat"> Temperature (Furnace/Cryostat)</div>
		<div class="checkbody" id="temperaturefurnacecryostat_wrapper">
			<div><input type="checkbox" id="temp_furnace"> Furnace&nbsp;&nbsp;&nbsp;<input type="checkbox" id="temp_cryostat"> Cryostat</div>
			<div><input type="checkbox" id="temp_servo_controlled"> Servo Controlled</div>

			<div class="rowdivv">
				Min Temperature:
				<input type="text" class="only-numeric" id="temp_mintemperature">
				<select id="temp_mintemperature_unit">
					<option value="°C">°C</option>
					<option value="K" >K</option>
					<option value="°F">°F</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max Temperature:
				<input type="text" class="only-numeric" id="temp_maxtemperature">
				<select id="temp_maxtemperature_unit">
					<option value="°C">°C</option>
					<option value="K" >K</option>
					<option value="°F">°F</option>
				</select>
			</div>

			<div class="separator" style="margin-top:5px;">
			Sensor(s)
			</div>

			<?php
			for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
			?>

			<div class="rowdivv" id="temp_sensor_wrapper_<?php echo $sensor_num?>" <?php if($sensor_num > 1){?> style="display:none;"<?php }?>>

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
							<option value="Resistance Temperature Detector (RTD)">Resistance Temperature Detector (RTD)</option>
							<option value="Thermocouple">Thermocouple</option>
							<option value="Thermistor">Thermistor</option>
							<option value="Furnace Power with Power-Temperature Calibration">Furnace Power with Power-Temperature Calibration</option>
							<option value="Other">Other</option>
						</select>
						<span id="temp_thermocoupletypespan_<?php echo $sensor_num?>" style="padding-left:10px;display:none;">
							Thermocouple Type:
							<select id="temp_thermocoupletype_<?php echo $sensor_num?>" style="max-width:120px;">
								<option value="">Select...</option>
								<option value="Nickel Alloy">Nickel Alloy</option>
								<option value="Platinum/Rhodium-Alloy">Platinum/Rhodium-Alloy</option>
								<option value="Tungsten/Rhenium-Alloy">Tungsten/Rhenium-Alloy</option>
								<option value="Chromel-gold/iron-alloy">Chromel-gold/iron-alloy </option>
								<option value="Type P (noble-melat alloy) or "Platinel II" ">Type P (noble-melat alloy) or "Platinel II"</option>
								<option value="Platinum/Molybdenum-alloy">Platinum/Molybdenum-alloy</option>
								<option value="Iridium/rhodium alloy">Iridium/rhodium alloy</option>
								<option value="Pure noble-metal (Au-Pt, Pt-Pd)">Pure noble-metal (Au-Pt, Pt-Pd)</option>
								<option value="High Temperature Irradiation Resistant">High Temperature Irradiation Resistant</option>
							</select>
						</span>
						<span id="temp_thermocouplesubtypespan_<?php echo $sensor_num?>" style="padding-left:10px;display:none;">
							Sub Type:
							<select id="temp_thermocouplesubtype_<?php echo $sensor_num?>" style="max-width:120px;">
								<option value="">Select...</option>
							</select>
						</span>
						<span id="temp_rtdspan_<?php echo $sensor_num?>" style="padding-left:10px;display:none;">
							RTD Type:
							<select id="temp_rtdtype_<?php echo $sensor_num?>" style="max-width:120px;">
								<option value="">Select...</option>
								<option value="Pt">Pt</option>
								<option value="Ni">Ni</option>
								<option value="Cu">Cu</option>
							</select>
						</span>
						<span id="temp_thermistortypespan_<?php echo $sensor_num?>" style="padding-left:10px;display:none;">
							Thermistor Type:
							<select id="temp_thermistortype_<?php echo $sensor_num?>" style="max-width:120px;">
								<option value="">Select...</option>
								<option value="Negative Temperature Coefficient (NTC)">Negative Temperature Coefficient (NTC)</option>
								<option value="Positive Temperature Coefficient (PTC)">Positive Temperature Coefficient (PTC)</option>
							</select>
						</span>

						<span id="temp_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:none;">
							<input type="text" id="temp_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type...">
						</span>
					</div>
					<div class="rowdivv">
						Sensor Location:
						<select id="temp_sensorlocation_<?php echo $sensor_num?>">
							<option value="">Select...</option>
							<option value="Internal">Internal</option>
							<option value="External">External</option>
						</select>
						&nbsp;&nbsp;&nbsp;
						Calibration: <input type="text" id="temp_sensorcalibration_<?php echo $sensor_num?>">
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<textarea id="temp_sensornotes_<?php echo $sensor_num?>" style="width:400px;"></textarea>
					</div>
				</div>
				<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div><div style="float:left;" class="dropzone" id="temp_sensorfile_<?php echo $sensor_num?>"></div>
				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

			<div class="rowdiv" id="tempaddsensorbutton">
				 <button onclick="add_temp_sensor(); return false;">Add Additional Sensor</button>
			</div>

		</div>

		<div class="checkheader"><input type="checkbox" id="checkbox_displacement"> Displacement</div>
		<div class="checkbody" id="displacement_wrapper">
			<input type="checkbox" id="checkbox_dispax"> Axial
			&nbsp;&nbsp;
			<input type="checkbox" id="checkbox_disprot"> Rotary
			&nbsp;&nbsp;
			<input type="checkbox" id="checkbox_dispvol"> Volumetric
			&nbsp;&nbsp;
			<input type="checkbox" id="checkbox_dispshear"> Shear

			<div class="displacementbox" id="dispax_wrapper" style="display:none;">
				<div class="rowheader">Axial</div>
				<div class="rowdivv">
					Min Displacement:
					<input type="text" class="only-numeric" id="dispax_mindisplacement">
					<select id="dispax_mindisplacement_unit">
						<option value="µm">µm</option>
						<option value="mm">mm</option>
						<option value="cm" selected>cm</option>
						<option value="m">m</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Displacement:
					<input type="text" class="only-numeric" id="dispax_maxdisplacement">
					<select id="dispax_maxdisplacement_unit">
						<option value="µm">µm</option>
						<option value="mm">mm</option>
						<option value="cm" selected>cm</option>
						<option value="m">m</option>
					</select>
				</div>
				<div class="rowdivv">
					Min Displacement Rate:
					<input type="text" class="only-numeric" id="dispax_mindisplacementrate">
					<select id="dispax_mindisplacementrate_unit">
						<option value="µm/s">µm/s</option>
						<option value="mm/s">mm/s</option>
						<option value="cm/s">cm/s</option>
						<option value="m/s" selected>m/s</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Displacement Rate:
					<input type="text" class="only-numeric" id="dispax_maxdisplacementrate">
					<select id="dispax_maxdisplacementrate_unit">
						<option value="µm/s">µm/s</option>
						<option value="mm/s">mm/s</option>
						<option value="cm/s">cm/s</option>
						<option value="m/s" selected>m/s</option>
					</select>
				</div>

				<div class="separator" style="margin-top:5px;">
				Sensor(s)
				</div>

				<?php
				for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
				?>

				<div class="rowdivv" id="dispax_sensor_wrapper_<?php echo $sensor_num?>" <?php if($sensor_num > 1){?> style="display:none;"<?php }?>>

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
								<option value="DCDT">DCDT</option>
								<option value="LVDT">LVDT</option>
								<option value="Cantilever">Cantilever</option>
								<option value="Direct Contact Strain Gauge">Direct Contact Strain Gauge</option>
								<option value="Extensometer">Extensometer</option>
								<option value="Fiber Optic">Fiber Optic</option>
								<option value="Laser">Laser</option>
								<option value="Radiograph">Radiograph</option>
								<option value="Other">Other</option>
							</select>
							<span id="dispax_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:none;">
								<input type="text" id="dispax_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type...">
							</span>
						</div>
						<div class="rowdivv">
							Sensor Location:
							<select id="dispax_sensorlocation_<?php echo $sensor_num?>">
								<option value="">Select...</option>
								<option value="Internal">Internal</option>
								<option value="External">External</option>
							</select>
							&nbsp;&nbsp;&nbsp;
							Calibration: <input type="text" id="dispax_sensorcalibration_<?php echo $sensor_num?>">
						</div>
						<div class="rowdivv">
							Sensor Notes:
							<textarea id="dispax_sensornotes_<?php echo $sensor_num?>" style="width:400px;"></textarea>
						</div>
					</div>
					<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div><div style="float:left;" class="dropzone" id="dispax_sensorfile_<?php echo $sensor_num?>"></div>
					<div style="clear:left;"></div>
				</div>

				<?php
				}//end foreach
				?>

				<div class="rowdiv" id="dispaxaddsensorbutton">
					 <button onclick="add_dispax_sensor(); return false;">Add Additional Sensor</button>
				</div>

			</div>

			<div class="displacementbox" id="disprot_wrapper" style="display:none;">
				<div class="rowheader">Rotary</div>
				<div class="rowdivv">
					<input type="checkbox" id="disprot_solid_cylinder"> Solid Cylinder
					&nbsp;&nbsp;&nbsp;
					<input type="checkbox" id="disprot_annular"> Annular (Ring-shaped)
				</div>
				<div class="rowdivv">
					<input type="checkbox" id="disprot_servo_controlled"> Servo Controlled
				</div>
				<div class="rowdivv">
					Min Sample Diameter:
					<input type="text" class="only-numeric" id="disprot_minsamplediameter">
					<select id="disprot_minsamplediameter_unit">
						<option value="mm">mm</option>
						<option value="cm" selected>cm</option>
						<option value="m">m</option>
						<option value="inches">inches</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Sample Diameter:
					<input type="text" class="only-numeric" id="disprot_maxsamplediameter">
					<select id="disprot_maxsamplediameter_unit">
						<option value="mm">mm</option>
						<option value="cm" selected>cm</option>
						<option value="m">m</option>
						<option value="inches">inches</option>
					</select>
				</div>
				<div class="rowdivv">
					Min Sample Thickness:
					<input type="text" class="only-numeric" id="disprot_minsamplethickness">
					<select id="disprot_minsamplethickness_unit">
						<option value="mm">mm</option>
						<option value="cm" selected>cm</option>
						<option value="m">m</option>
						<option value="inches">inches</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Sample Thickness:
					<input type="text" class="only-numeric" id="disprot_maxsamplethickness">
					<select id="disprot_maxsamplethickness_unit">
						<option value="mm">mm</option>
						<option value="cm" selected>cm</option>
						<option value="m">m</option>
						<option value="inches">inches</option>
					</select>
				</div>
				<div class="rowdivv">
					Min Displacement:
					<input type="text" class="only-numeric" id="disprot_mindisplacement">
					<select id="disprot_mindisplacement_unit">
						<option value="µm">µm</option>
						<option value="mm">mm</option>
						<option value="cm" selected>cm</option>
						<option value="m">m</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Displacement:
					<input type="text" class="only-numeric" id="disprot_maxdisplacement">
					<select id="disprot_maxdisplacement_unit">
						<option value="µm">µm</option>
						<option value="mm">mm</option>
						<option value="cm" selected>cm</option>
						<option value="m">m</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" id="disprot_infinitemaxdisplacement">&nbsp;Infinite
				</div>
				<div class="rowdivv">
					Min Rotation Rate:
					<input type="text" class="only-numeric" id="disprot_minrotationrate">
					<select id="disprot_minrotation_unit">
						<option value="revolutions/min">revolutions/min</option>
						<option value="revolutions/s" selected>revolutions/s</option>
						<option value="revolutions/hour">revolutions/hour</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					Max Rotation Rate:
					<input type="text" class="only-numeric" id="disprot_maxrotationrate">
					<select id="disprot_maxrotation_unit">
						<option value="revolutions/min">revolutions/min</option>
						<option value="revolutions/s" selected>revolutions/s</option>
						<option value="revolutions/hour">revolutions/hour</option>
					</select>
				</div>

				<div class="separator" style="margin-top:5px;">
				Sensor(s)
				</div>

				<?php
				for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
				?>

				<div class="rowdivv" id="disprot_sensor_wrapper_<?php echo $sensor_num?>" <?php if($sensor_num > 1){?> style="display:none;"<?php }?>>

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
								<option value="DCDT">DCDT</option>
								<option value="LVDT">LVDT</option>
								<option value="Cantilever">Cantilever</option>
								<option value="Direct Contact Strain Gauge">Direct Contact Strain Gauge</option>
								<option value="Extensometer">Extensometer</option>
								<option value="Fiber Optic">Fiber Optic</option>
								<option value="Laser">Laser</option>
								<option value="Radiograph">Radiograph</option>
								<option value="Potentiometer">Potentiometer</option>
								<option value="Resolver">Resolver</option>
								<option value="Encoder">Encoder</option>
								<option value="RVDT">RVDT</option>
								<option value="Other">Other</option>
							</select>
							<span id="disprot_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:none;">
								<input type="text" id="disprot_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type...">
							</span>
						</div>
						<div class="rowdivv">
							Sensor Location:
							<select id="disprot_sensorlocation_<?php echo $sensor_num?>">
								<option value="">Select...</option>
								<option value="Internal">Internal</option>
								<option value="External">External</option>
							</select>
							&nbsp;&nbsp;&nbsp;
							Calibration: <input type="text" id="disprot_sensorcalibration_<?php echo $sensor_num?>">
						</div>
						<div class="rowdivv">
							Sensor Notes:
							<textarea id="disprot_sensornotes_<?php echo $sensor_num?>" style="width:400px;"></textarea>
						</div>
					</div>
					<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div><div style="float:left;" class="dropzone" id="disprot_sensorfile_<?php echo $sensor_num?>"></div>
					<div style="clear:left;"></div>
				</div>

				<?php
				}//end foreach
				?>

				<div class="rowdiv" id="disprotaddsensorbutton">
					 <button onclick="add_disprot_sensor(); return false;">Add Additional Sensor</button>
				</div>
			</div>

			<div class="displacementbox" id="dispvol_wrapper" style="display:none;">
				<div class="rowheader">Volumetric</div>

				<div class="separator" style="margin-top:5px;">
				Sensor(s)
				</div>

				<?php
				for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
				?>

				<div class="rowdivv" id="dispvol_sensor_wrapper_<?php echo $sensor_num?>" <?php if($sensor_num > 1){?> style="display:none;"<?php }?>>

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
								<option value="DCDT">DCDT</option>
								<option value="LVDT">LVDT</option>
								<option value="Cantilever">Cantilever</option>
								<option value="Direct Contact Strain Gauge">Direct Contact Strain Gauge</option>
								<option value="Extensometer">Extensometer</option>
								<option value="Fiber Optic">Fiber Optic</option>
								<option value="Laser">Laser</option>
								<option value="Radiograph">Radiograph</option>
								<option value="Potentiometer">Potentiometer</option>
								<option value="Other">Other</option>
							</select>
							<span id="dispvol_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:none;">
								<input type="text" id="dispvol_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type...">
							</span>
						</div>
						<div class="rowdivv">
							Sensor Location:
							<select id="dispvol_sensorlocation_<?php echo $sensor_num?>">
								<option value="">Select...</option>
								<option value="Internal">Internal</option>
								<option value="External">External</option>
							</select>
							&nbsp;&nbsp;&nbsp;
							Calibration: <input type="text" id="dispvol_sensorcalibration_<?php echo $sensor_num?>">
						</div>
						<div class="rowdivv">
							Sensor Notes:
							<textarea id="dispvol_sensornotes_<?php echo $sensor_num?>" style="width:400px;"></textarea>
						</div>
					</div>
					<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div><div style="float:left;" class="dropzone" id="dispvol_sensorfile_<?php echo $sensor_num?>"></div>
					<div style="clear:left;"></div>
				</div>

				<?php
				}//end foreach
				?>

				<div class="rowdiv" id="dispvoladdsensorbutton">
					 <button onclick="add_dispvol_sensor(); return false;">Add Additional Sensor</button>
				</div>

			</div>

			<div class="displacementbox" id="dispshear_wrapper" style="display:none;">
				<div class="rowheader">Shear</div>
				<div class="separator" style="margin-top:5px;">
				Sensor(s)
				</div>

				<?php
				for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
				?>

				<div class="rowdivv" id="dispshear_sensor_wrapper_<?php echo $sensor_num?>" <?php if($sensor_num > 1){?> style="display:none;"<?php }?>>

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
								<option value="DCDT">DCDT</option>
								<option value="LVDT">LVDT</option>
								<option value="Cantilever">Cantilever</option>
								<option value="Direct Contact Strain Gauge">Direct Contact Strain Gauge</option>
								<option value="Extensometer">Extensometer</option>
								<option value="Fiber Optic">Fiber Optic</option>
								<option value="Laser">Laser</option>
								<option value="Radiograph">Radiograph</option>
								<option value="Potentiometer">Potentiometer</option>
								<option value="Resolver">Resolver</option>
								<option value="Encoder">Encoder</option>
								<option value="RVDT">RVDT</option>
								<option value="Other">Other</option>
							</select>
							<span id="dispshear_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:none;">
								<input type="text" id="dispshear_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type...">
							</span>
						</div>
						<div class="rowdivv">
							Sensor Location:
							<select id="dispshear_sensorlocation_<?php echo $sensor_num?>">
								<option value="">Select...</option>
								<option value="Internal">Internal</option>
								<option value="External">External</option>
							</select>
							&nbsp;&nbsp;&nbsp;
							Calibration: <input type="text" id="dispshear_sensorcalibration_<?php echo $sensor_num?>">
						</div>
						<div class="rowdivv">
							Sensor Notes:
							<textarea id="dispshear_sensornotes_<?php echo $sensor_num?>" style="width:400px;"></textarea>
						</div>
					</div>
					<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div><div style="float:left;" class="dropzone" id="dispshear_sensorfile_<?php echo $sensor_num?>"></div>
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

		<div class="checkheader"><input type="checkbox" id="checkbox_force"> Force</div>
		<div class="checkbody" id="force_wrapper">

			<div class="rowdivv">
				Max Force:
				<input type="text" class="only-numeric" id="force_maxforce">
				<select id="force_maxforce_unit">
					<option value="N">N</option>
					<option value="kN">kN</option>
				</select>
			</div>

			<div class="separator" style="margin-top:5px;">
			Sensor(s)
			</div>

			<?php
			for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
			?>

			<div class="rowdivv" id="force_sensor_wrapper_<?php echo $sensor_num?>" <?php if($sensor_num > 1){?> style="display:none;"<?php }?>>

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
							<option value="Force Gauge">Force Gauge</option>
							<option value="Capacitance">Capacitance</option>
							<option value="LVDT">LVDT</option>
							<option value="DCDT"">DCDT"</option>
							<option value="XRay Distortion">XRay Distortion</option>
							<option value="Other">Other</option>
						</select>
						<span id="force_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:none;">
							<input type="text" id="force_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type...">
						</span>
					</div>
					<div class="rowdivv">
						Sensor Location:
						<select id="force_sensorlocation_<?php echo $sensor_num?>">
							<option value="">Select...</option>
							<option value="Internal">Internal</option>
							<option value="External">External</option>
						</select>
						&nbsp;&nbsp;&nbsp;
						Calibration: <input type="text" id="force_sensorcalibration_<?php echo $sensor_num?>">
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<textarea id="force_sensornotes_<?php echo $sensor_num?>" style="width:400px;"></textarea>
					</div>
				</div>
				<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div><div style="float:left;" class="dropzone" id="force_sensorfile_<?php echo $sensor_num?>"></div>
				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

			<div class="rowdiv" id="forceaddsensorbutton">
				 <button onclick="add_force_sensor(); return false;">Add Additional Sensor</button>
			</div>

		</div>

		<div class="checkheader"><input type="checkbox" id="checkbox_torque"> Torque</div>
		<div class="checkbody" id="torque_wrapper">

			<div class="rowdivv">
				Max Torque:
				<input type="text" class="only-numeric" id="torque_maxtorque">
				<select id="torque_maxtorque_unit">
					<option value="N">N</option>
					<option value="kN">kN</option>
				</select>
			</div>

			<div class="separator" style="margin-top:5px;">
			Sensor(s)
			</div>

			<?php
			for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
			?>

			<div class="rowdivv" id="torque_sensor_wrapper_<?php echo $sensor_num?>" <?php if($sensor_num > 1){?> style="display:none;"<?php }?>>

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
							<option value="Solid Shaft">Solid Shaft</option>
							<option value="Hollow Shaft">Hollow Shaft</option>
							<option value="Lever Arm with Spring">Lever Arm with Spring</option>
							<option value="Other">Other</option>
						</select>
						<span id="torque_othergeometryofelasticelementspan_<?php echo $sensor_num?>" style="padding-left:20px;display:none;">
							<input type="text" id="torque_othergeometryofelasticelement_<?php echo $sensor_num?>" placeholder="Other Geometry...">
						</span>
					</div>
					<div class="rowdivv" style="white-space: nowrap;">
						Distortion Sensor Type:
						<select id="torque_sensortype_<?php echo $sensor_num?>" style="max-width:200px;">
							<option value="">Select...</option>
							<option value="Strain Gauges">Strain Gauges</option>
							<option value="LVDT">LVDT</option>
							<option value="Dial Gauge">Dial Gauge</option>
							<option value="Potentiometer">Potentiometer</option>
							<option value="Other">Other</option>
						</select>
						<span id="torque_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:none;">
							<input type="text" id="torque_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type...">
						</span>
						<span id="torque_numberofstraingaugesspan_<?php echo $sensor_num?>" style="padding-left:20px;display:none;">
							Number of Strain Gauges: <input type="text" class="only-numeric" id="torque_numberofstraingauges_<?php echo $sensor_num?>">
						</span>
					</div>
					<div class="rowdivv">
						Sensor Location:
						<select id="torque_sensorlocation_<?php echo $sensor_num?>">
							<option value="">Select...</option>
							<option value="Internal">Internal</option>
							<option value="External">External</option>
						</select>
						&nbsp;&nbsp;&nbsp;
						Calibration: <input type="text" id="torque_sensorcalibration_<?php echo $sensor_num?>">
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<textarea id="torque_sensornotes_<?php echo $sensor_num?>" style="width:400px;"></textarea>
					</div>
				</div>
				<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div><div style="float:left;" class="dropzone" id="torque_sensorfile_<?php echo $sensor_num?>"></div>
				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

			<div class="rowdiv" id="torqueaddsensorbutton">
				 <button onclick="add_torque_sensor(); return false;">Add Additional Sensor</button>
			</div>

		</div>

		<div class="checkheader"><input type="checkbox" id="checkbox_porefluids"> Pore Fluids</div>
		<div class="checkbody" id="porefluids_wrapper">
			<div class="rowdivv">
				<input type="checkbox" id="pore_undrainedonly"> Undrained Only
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="pore_measured"> Measured
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="pore_servocontrolled"> Servo-controlled
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="pore_flowthrough"> Flowthrough
			</div>
			<div class="rowdivv">
				Min Pore Fluid Pressure:
				<input type="text" class="only-numeric" id="pore_minporefluidpressure">
				<select id="pore_minporefluidpressureunit">
					<option value="kPa">kPa</option>
					<option value="MPa" selected>MPa</option>
					<option value="GPa">GPa</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max Pore Fluid Pressure:
				<input type="text" class="only-numeric" id="pore_maxporefluidpressure">
				<select id="pore_maxporefluidpressureunit">
					<option value="kPa">kPa</option>
					<option value="MPa" selected>MPa</option>
					<option value="GPa">GPa</option>
				</select>
			</div>

			<div class="separator" style="margin-top:5px;">
			Sensor(s)
			</div>

			<?php
			for($sensor_num = 1; $sensor_num <= $num_sensors; $sensor_num++){
			?>

			<div class="rowdivv" id="pore_sensor_wrapper_<?php echo $sensor_num?>" <?php if($sensor_num > 1){?> style="display:none;"<?php }?>>

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
							<option value="pore Gauge">pore Gauge</option>
							<option value="Capacitance">Capacitance</option>
							<option value="LVDT">LVDT</option>
							<option value="DCDT">DCDT</option>
							<option value="XRay Distortion">XRay Distortion</option>
							<option value="Other">Other</option>
						</select>
						<span id="pore_othersensortypespan_<?php echo $sensor_num?>" style="padding-left:20px;display:none;">
							<input type="text" id="pore_othersensortype_<?php echo $sensor_num?>" placeholder="Other Sensor Type...">
						</span>
					</div>
					<div class="rowdivv">
						Sensor Location:
						<select id="pore_sensorlocation_<?php echo $sensor_num?>">
							<option value="">Select...</option>
							<option value="Internal">Internal</option>
							<option value="External">External</option>
						</select>
						&nbsp;&nbsp;&nbsp;
						Calibration: <input type="text" id="pore_sensorcalibration_<?php echo $sensor_num?>">
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<textarea id="pore_sensornotes_<?php echo $sensor_num?>" style="width:400px;"></textarea>
					</div>
				</div>
				<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div><div style="float:left;" class="dropzone" id="pore_sensorfile_<?php echo $sensor_num?>"></div>
				<div style="clear:left;"></div>
			</div>

			<?php
			}//end foreach
			?>

			<div class="rowdiv" id="poreaddsensorbutton">
				 <button onclick="add_pore_sensor(); return false;">Add Additional Sensor</button>
			</div>

		</div>

		<div class="checkheader"><input type="checkbox" id="checkbox_acousticemissions"> Acoustic Emissions</div>
		<div class="checkbody" id="acousticemissions_wrapper">
			<div class="rowdivv">
				<input type="checkbox" id="acoustic_continuousstreaming"> Continuous Streaming
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="acoustic_triggeredrecording"> Triggered Recording
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="acoustic_momentanalysiscalibratedamplitude"> Moment analysis Calibrated Amplitude
			</div>
			<div class="rowdivv">
				<input type="checkbox" id="acoustic_transmissivityreflectivity"> Acoustic transmissivity / reflectivity
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="acoustic_aecount"> AE Count
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="acoustic_tomography"> Tomography
			</div>
			<div class="rowdivv">
				<input type="checkbox" id="acoustic_momentanalysisrelativeamplitude"> Moment analysis relative amplitude
			</div>

			<div class="rowdivv">
				Frequency Range:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Min:
				<input type="text" class="only-numeric" id="acoustic_minfrequencyrange">
				<select id="acoustic_minfrequencyrangeunit">
					<option value="Hz">Hz</option>
					<option value="kHz">kHz</option>
					<option value="MHz">MHz</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max:
				<input type="text" class="only-numeric" id="acoustic_maxfrequencyrange">
				<select id="acoustic_maxfrequencyrangeunit">
					<option value="Hz">Hz</option>
					<option value="kHz">kHz</option>
					<option value="MHz">MHz</option>
				</select>
			</div>
			<div class="rowdivv">
				Sensor Notes:
				<textarea id="acoustic_sensornotes" style="width:400px;"></textarea>
			</div>

		</div>

		<div class="checkheader"><input type="checkbox" id="checkbox_elasticwave"> In-situ Elastic Wave Velocities</div>
		<div class="checkbody" id="elasticwave_wrapper">
			<div class="rowdivv">
				<input type="checkbox" id="elastic_piezoelectricsensors"> Piezoelectric Sensors
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Piezoelectric Transducer Type: <input type="text" id="elastic_piezoelectricyransduceryype">
			</div>
			<div class="rowdivv">
				<input type="checkbox" id="elastic_pwave"> P-Wave
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="elastic_swave"> S-Wave
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="elastic_s1wave"> S1-Wave
				&nbsp;&nbsp;&nbsp;<input type="checkbox" id="elastic_s2wave"> S2-Wave
			</div>
			<div class="rowdivv">
				Frequency Range:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Min:
				<input type="text" class="only-numeric" id="elastic_minfrequencyrange">
				<select id="elastic_minfrequencyrangeunit">
					<option value="Hz">Hz</option>
					<option value="kHz">kHz</option>
					<option value="MHz">MHz</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Max:
				<input type="text" class="only-numeric" id="elastic_maxfrequencyrange">
				<select id="elastic_maxfrequencyrangeunit">
					<option value="Hz">Hz</option>
					<option value="kHz">kHz</option>
					<option value="MHz">MHz</option>
				</select>
			</div>
			<div class="rowdivv">
				Sensor Notes:
				<textarea id="elastic_sensornotes" style="width:400px;"></textarea>
			</div>

		</div>

		<div class="checkheader"><input type="checkbox" id="checkbox_electricalconductivity"> Electrical Conductivity</div>
		<div class="checkbody" id="electricalconductivity_wrapper">

			<div class="rowdivv">
				<div style="float:left;width:600px;">
					<div class="rowdivv">
						<input type="checkbox" id="electrical_frequencydependent"> Frequency Dependent
					</div>
					<div class="rowdivv">
						Amplitude of Test Pulse:
						<select id="electrical_amplitude">
							<option value="">Select...</option>
							<option value="Sine Wave">Sine Wave</option>
							<option value="Square Wave">Square Wave</option>
							<option value="Trangle Wave">Trangle Wave</option>
						</select>
					</div>
					<div class="rowdivv">
						Electrode Type:
						<select id="electrical_electrodetype">
							<option value="">Select...</option>
							<option value="Ag/AgCl Electrode">Ag/AgCl Electrode</option>
							<option value="Cu Electrode">Cu Electrode</option>
							<option value="Pb Electrode">Pb Electrode</option>
							<option value="Other Electrode">Other Electrode</option>
						</select>
						<input type="text" id="electrical_otherelectrodetype" placeholder="Other Electrode Type..." style="display:none;max-width:150px;">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Num Electrodes:
						<select id="electrical_numberofelectrodes">
							<option value="">Select...</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
						</select>
					</div>

					<div class="rowdivv">
						Calibration: <input type="text" id="electrical_sensorcalibration">
					</div>
					<div class="rowdivv">
						Sensor Notes:
						<textarea id="electrical_sensornotes" style="width:400px;"></textarea>
					</div>
				</div>
				<div style="float:left;padding-right:10px;font-size:1em;padding-top:60px;padding-left:17px;">Calibration File:</div><div style="float:left;" class="dropzone" id="electrical_sensorfile"></div>
				<div style="clear:left;"></div>
			</div>
		</div>
	</div>

	<div class="rowdiv rowheader" style="padding-top:25px;">General Apparatus Description and Notes</div>
	<div class="notesbox" style="text-align:center;margin-left:30px;margin-right:30px;">
		<textarea id="apparatus_description_notes" rows="10" style="width:100%;"></textarea>
	</div>

	<div class="rowdiv rowheader" style="padding-top:25px;">
		<button onclick="doSubmit(); return false;">Submit</button>
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

			$.post( "add_apparatus.php", { jsonData: formJSON })
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

</script>

<div id="errormodal" class="modal">
  <p class="errorbar">Error!</p>
  <p id="errors"></p>
</div>

<?php
include 'includes/footer.php';
?>
