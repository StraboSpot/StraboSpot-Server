<?php
/**
 * File: edit_institute.php
 * Description: Edits records in institute table(s)
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

if(!in_array($userpkey, $admin_pkeys)){ //restric to admins
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

.rowheader {
	
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

</style>

<script src='/assets/js/jquery/jquery.min.js'></script>
<script src="/assets/js/featherlight/featherlight.js"></script>

<div class="rowdiv">
	<h2>Edit Institute</h2>
</div>
<?php

if($_POST['submit']!=""){

	$institute_type = $_POST['institute_type'];
	$institute_name = $_POST['institute_name'];
	$lab_name = $_POST['lab_name'];
	$facility_id = $_POST['facility_id'];
	$website = $_POST['website'];
	$country = $_POST['country'];
	$state = $_POST['state'];
	$address1 = $_POST['address1'];
	$address2 = $_POST['address2'];
	$city = $_POST['city'];
	$zip = $_POST['zip'];
	$contact_first_name = $_POST['contact_first_name'];
	$contact_last_name = $_POST['contact_last_name'];
	$contact_title = $_POST['contact_title'];
	$contact_email = $_POST['contact_email'];
	$pkey = $_POST['pkey'];

//
//

	$db->prepare_query("UPDATE institute SET
					institute_type = $1,
					institute_name = $2,
					lab_name = $3,
					facility_id = $4,
					website = $5,
					country = $6,
					state = $7,
					address1 = $8,
					address2 = $9,
					city = $10,
					zip = $11,
					contact_first_name = $12,
					contact_last_name = $13,
					contact_title = $14,
					contact_email = $15
					WHERE pkey = $16
	", array(
		$institute_type, $institute_name, $lab_name, $facility_id,
		$website, $country, $state, $address1, $address2,
		$city, $zip, $contact_first_name, $contact_last_name,
		$contact_title, $contact_email, $pkey
	));

?>
	<div>Success! Institute Saved.</div>
	<div style="padding-top:20px;"><a href="/institutes">Continue</a></div>
<?php

	include 'includes/footer.php';
	exit();
}

//check institute for existence
$pkey = $_GET['p'] ?? '';
$pkey = (int)$pkey;

if($pkey == 0){
	echo "Error. Institute not provided.";
	include 'includes/footer.php';
	exit();
}

$row = $db->get_row_prepared("SELECT * FROM institute WHERE pkey=$1", array($pkey));

if($db->num_rows == 0){
	echo "Error. Institute not found.";
	include 'includes/footer.php';
	exit();
}

$institute_type = $row->institute_type;
$institute_name = $row->institute_name;
$lab_name = $row->lab_name;
$facility_id = $row->facility_id;
$website = $row->website;
$country = $row->country;
$state = $row->state;
$address1 = $row->address1;
$address2 = $row->address2;
$city = $row->city;
$zip = $row->zip;
$contact_first_name = $row->contact_first_name;
$contact_last_name = $row->contact_last_name;
$contact_title = $row->contact_title;
$contact_email = $row->contact_email;

?>

<form method="POST" onsubmit="return validateForm()">

<div class="rowdiv">
	Institute Name: <span class="redred">*</span> <input type="text" name="institute_name" id="institute_name" value="<?php echo $institute_name?>">
</div>

<div class="rowdiv">
	Institute Type: <span class="redred">*</span>
	<select name="institute_type" id="institute_type">
		<option value="">Select...</option>
		<option value="University Lab"<?php if($institute_type=="University Lab") echo " selected"; ?>>University Lab</option>
		<option value="Government Facility"<?php if($institute_type=="Government Facility") echo " selected"; ?>>Government Facility</option>
		<option value="Private Industry Lab"<?php if($institute_type=="Private Industry Lab") echo " selected"; ?>>Private Industry Lab</option>
	</select>
</div>

<div style="padding-top:10px; width:800px; text-align:right;">
	<input type="submit" value="Save" name="submit" class="button">
</div>

<input type="hidden" name="pkey" value="<?php echo $pkey?>">

</form>

<script type='text/javascript'>

	function validateForm(){
		var error = "";

		if($("#institute_type").val()==""){
			error += "Institute type cannot be blank.\n";
		}

		if($("#institute_name").val()==""){
			error += "Institute name cannot be blank.\n";
		}

		if(error!=""){
			alert(error);
			return false;
		}
	}

	$("#country").change(function () {
		var selectedcountry = $("#country").val();
		if( selectedcountry != "" ){
			if( selectedcountry == "United States" ){
				$("#stateprovincewrapper").html(stateselectorhtml);
			}else{
				$("#stateprovincewrapper").html(statetextfieldhtml);
			}
		}else{
			$("#stateprovincewrapper").html("");
		}
	});

	var statetextfieldhtml = 'State/Province: <input type="text" name="state">';

	var stateselectorhtml = 'State: <select name="state"> \
		<option value="">Select...</option> \
		<option value="Alabama">Alabama</option> \
		<option value="Alaska">Alaska</option> \
		<option value="Arizona">Arizona</option> \
		<option value="Arkansas">Arkansas</option> \
		<option value="California">California</option> \
		<option value="Colorado">Colorado</option> \
		<option value="Connecticut">Connecticut</option> \
		<option value="Delaware">Delaware</option> \
		<option value="District Of Columbia">District Of Columbia</option> \
		<option value="Florida">Florida</option> \
		<option value="Georgia">Georgia</option> \
		<option value="Hawaii">Hawaii</option> \
		<option value="Idaho">Idaho</option> \
		<option value="Illinois">Illinois</option> \
		<option value="Indiana">Indiana</option> \
		<option value="Iowa">Iowa</option> \
		<option value="Kansas">Kansas</option> \
		<option value="Kentucky">Kentucky</option> \
		<option value="Louisiana">Louisiana</option> \
		<option value="Maine">Maine</option> \
		<option value="Maryland">Maryland</option> \
		<option value="Massachusetts">Massachusetts</option> \
		<option value="Michigan">Michigan</option> \
		<option value="Minnesota">Minnesota</option> \
		<option value="Mississippi">Mississippi</option> \
		<option value="Missouri">Missouri</option> \
		<option value="Montana">Montana</option> \
		<option value="Nebraska">Nebraska</option> \
		<option value="Nevada">Nevada</option> \
		<option value="New Hampshire">New Hampshire</option> \
		<option value="New Jersey">New Jersey</option> \
		<option value="New Mexico">New Mexico</option> \
		<option value="New York">New York</option> \
		<option value="North Carolina">North Carolina</option> \
		<option value="North Dakota">North Dakota</option> \
		<option value="Ohio">Ohio</option> \
		<option value="Oklahoma">Oklahoma</option> \
		<option value="Oregon">Oregon</option> \
		<option value="Pennsylvania">Pennsylvania</option> \
		<option value="Rhode Island">Rhode Island</option> \
		<option value="South Carolina">South Carolina</option> \
		<option value="South Dakota">South Dakota</option> \
		<option value="Tennessee">Tennessee</option> \
		<option value="Texas">Texas</option> \
		<option value="Utah">Utah</option> \
		<option value="Vermont">Vermont</option> \
		<option value="Virginia">Virginia</option> \
		<option value="Washington">Washington</option> \
		<option value="West Virginia">West Virginia</option> \
		<option value="Wisconsin">Wisconsin</option> \
		<option value="Wyoming">Wyoming</option> \
	</select>';

</script>

<?php
include 'includes/footer.php';
?>