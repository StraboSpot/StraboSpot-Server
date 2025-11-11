<?php
/**
 * File: new_project.php
 * Description: Creates new projects with initial configuration
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("logincheck.php");

include("prepare_connections.php");

if($_POST['submit']!=""){

	foreach($_POST as $key=>$value){
		eval("\$$key=\$value;");
	}

	//build json
	unset($data);

	if($projectname!=""){$data['description']['project_name']=$projectname;}
	if($startdate!=""){$data['description']['start_date']=$startdate;}
	if($enddate!=""){$data['description']['end_date']=$enddate;}
	if($purposeofstudy!=""){$data['description']['purpose_of_study']=$purposeofstudy;}
	if($otherteammembers!=""){$data['description']['other_team_members']=$otherteammembers;}
	if($areaofinterest!=""){$data['description']['area_of_interest']=$areaofinterest;}
	if($spotprefixlabel!=""){$data['preferences']['spot_prefix']=$spotprefixlabel;}
	if($startingnumberforspots!=""){$data['preferences']['starting_number_for_spot']=$startingnumberforspots;}
	if($sampleprefixlabel!=""){$data['preferences']['sample_prefix']=$sampleprefixlabel;}
	if($instrumentsused!=""){$data['description']['instruments']=$instrumentsused;}
	if($gpsdatum!=""){$data['description']['gps_datum']=$gpsdatum;}
	if($magneticdeclination!=""){$data['description']['magnetic_declination']=$magneticdeclination;}
	if($notes!=""){$data['description']['notes']=$notes;}

	$data["preferences"]["orientation"]=false;
	$data["preferences"]["_3dstructures"]=false;
	$data["preferences"]["images"]=false;
	$data["preferences"]["nest"]=false;
	$data["preferences"]["samples"]=false;
	$data["preferences"]["other_features"]=false;
	$data["preferences"]["inferences"]=false;
	$data["preferences"]["tags"]=false;
	$data["preferences"]["right_hand_rule"]=false;
	$data["preferences"]["drop_down_to_finish_typing"]=false;

	$data['preferences']=json_encode($data['preferences']);
	$data['json_description']=json_encode($data['description']);

	foreach($data['description'] as $key=>$value){
		$data["desc_".$key]=$value;
	}

	unset($data['description']);

	

	$date = date_create();
	date_timezone_set($date, timezone_open('UTC'));
	$data['date'] = date_format($date, "Y-m-d\TH:i:s\Z");

	$data['modified_timestamp'] = time()*1000;

	unset($injson);

	unset($upload['id']);
	unset($upload['featureid']);
	unset($upload['self']);

	$injson = $data;

	$injson['userpkey']=$userpkey;

	$microtime = microtime(TRUE);
	$microtime = $microtime * 1000;
	$microtime = $microtime * 10;

	$injson['id']= $microtime;

	$datecreated=time();

	$injson['datecreated']=$datecreated;
	$injson['uploaddate']=$datecreated;

	$injson['projecttype']="web";

	$injson['projectname']=$projectname;

	$injson=json_encode($injson);

	//********************************************************************
	// create new project node, and link to user
	//********************************************************************
	$projectid = $neodb->createNode($injson,"Project");
	$userid = $neodb->get_var("match (a:User) where a.userpkey=".$userpkey." return id(a)");
	$neodb->addRelationship($userid, $projectid, "HAS_PROJECT");

	//********************************************************************
	// also create new dataset node, and link to project
	//********************************************************************

	unset($data);
	unset($injson);

	$date = date_create();
	date_timezone_set($date, timezone_open('UTC'));
	$data['date'] = date_format($date, "Y-m-d\TH:i:s\Z");

	$data['modified_timestamp'] = time()*1000;

	$data['name']="default";

	$data['datasettype']="web";

	$injson = $data;

	$injson['userpkey']=$userpkey;

	$microtime = microtime(TRUE);
	$microtime = $microtime * 1000;
	$microtime = $microtime * 10;

	$injson['id']= $microtime;

	$injson['datecreated']= time();

	$injson=json_encode($injson);

	$datasetid = $neodb->createNode($injson,"Dataset");

	$neodb->addRelationship($projectid, $datasetid, "HAS_DATASET");

	include 'includes/mheader.php';

	?>

				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Create New Field Project</h2>
						</header>

	<h2>Project Created Successfully.</h2>

	<br>
	<br>

	<?php
	$arcid=$_POST['arcid'];
	if($arcid!=""){
	?>
	<a href="loadarcshapefile?arcid=<?php echo $arcid?>">Click Here to continue loading shapefile...</a>
	<?php
	}else{
	?>
	<a href="my_field_data">Continue...</a>
	<?php
	}
	?>

					<div class="bottomSpacer"></div>

					</div>
				</div>

	<?php

	include 'includes/mfooter.php';

	exit();
}

include 'includes/mheader.php';

?>

<script type="text/javascript">

	function showdiv(myid) {
		document.getElementById(myid).style.display='block';
	}

	function validateForm(){

		var myerror='';
		var mydelim=''

		if(document.getElementById('projectname').value==""){
			myerror=myerror+mydelim+'Project Name cannot be blank.';
			mydelim='\n';
		}

		if(myerror!=""){
			alert(myerror);
			return false;
		}

	}

</script>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Create New Field Project</h2>
						</header>

<div>
Items with (<span style="color:red;font-weight:bold;">*</span>) are required.
</div>

<form method="POST" onsubmit="return validateForm()">

<div class="row gtr-uniform gtr-50 padTop15">
	<div class="col-3 col-12-xsmall textRight textLeftXSmall vMid "><h3>Project Name:<span style="color:red;font-weight:bold;">*</span></h3></div>
	<div class="col-9 col-12-xsmall"><input type="text" id="projectname" name="projectname" value="<?php echo $_POST['projectname']?>"></div>
</div>

<div class="row gtr-uniform gtr-50 padTop15">
	<div class="col-3 col-12-xsmall textRight textLeftXSmall vMid "><h3>Start Date:</h3></div>
	<div class="col-9 col-12-xsmall"><input type="text" name="startdate" value="<?php echo $_POST['startdate']?>"></div>
</div>

<div class="row gtr-uniform gtr-50 padTop15">
	<div class="col-3 col-12-xsmall textRight textLeftXSmall vMid "><h3>End Date:</h3></div>
	<div class="col-9 col-12-xsmall"><input type="text" name="enddate" value="<?php echo $_POST['enddate']?>"></div>
</div>

<div class="row gtr-uniform gtr-50 padTop15">
	<div class="col-3 col-12-xsmall textRight textLeftXSmall vMid "><h3>Purpose of Study:</h3></div>
	<div class="col-9 col-12-xsmall"><input type="text" name="purposeofstudy" value="<?php echo $_POST['purposeofstudy']?>"></div>
</div>

<div class="row gtr-uniform gtr-50 padTop15">
	<div class="col-3 col-12-xsmall textRight textLeftXSmall vMid "><h3>Other Team Members:</h3></div>
	<div class="col-9 col-12-xsmall"><input type="text" name="otherteammembers" value="<?php echo $_POST['otherteammembers']?>"></div>
</div>

<div class="row gtr-uniform gtr-50 padTop15">
	<div class="col-3 col-12-xsmall textRight textLeftXSmall vMid "><h3>Area of Interest:</h3></div>
	<div class="col-9 col-12-xsmall"><input type="text" name="areaofinterest" value="<?php echo $_POST['areaofinterest']?>"></div>
</div>

<div class="row gtr-uniform gtr-50 padTop15">
	<div class="col-3 col-12-xsmall textRight textLeftXSmall vMid "><h3>Spot Prefix Label:</h3></div>
	<div class="col-9 col-12-xsmall"><input type="text" name="spotprefixlabel" value="<?php echo $_POST['spotprefixlabel']?>"></div>
</div>

<div class="row gtr-uniform gtr-50 padTop15">
	<div class="col-3 col-12-xsmall textRight textLeftXSmall vMid "><h3>Starting Number for Spot:</h3></div>
	<div class="col-9 col-12-xsmall"><input type="text" name="startingnumberforspots" value="<?php echo $_POST['startingnumberforspots']?>"></div>
</div>

<div class="row gtr-uniform gtr-50 padTop15">
	<div class="col-3 col-12-xsmall textRight textLeftXSmall vMid "><h3>Sample Prefix Label:</h3></div>
	<div class="col-9 col-12-xsmall"><input type="text" name="sampleprefixlabel" value="<?php echo $_POST['sampleprefixlabel']?>"></div>
</div>

<div class="row gtr-uniform gtr-50 padTop15">
	<div class="col-3 col-12-xsmall textRight textLeftXSmall vMid "><h3>Instruments Used:</h3></div>
	<div class="col-9 col-12-xsmall"><input type="text" name="instrumentsused" value="<?php echo $_POST['instrumentsused']?>"></div>
</div>

<div class="row gtr-uniform gtr-50 padTop15">
	<div class="col-3 col-12-xsmall textRight textLeftXSmall vMid "><h3>GPS Datum:</h3></div>
	<div class="col-9 col-12-xsmall"><input type="text" name="gpsdatum" value="<?php echo $_POST['gpsdatum']?>"></div>
</div>

<div class="row gtr-uniform gtr-50 padTop15">
	<div class="col-3 col-12-xsmall textRight textLeftXSmall vMid "><h3>Magnetic Declination:</h3></div>
	<div class="col-9 col-12-xsmall"><input type="text" name="magneticdeclination" value="<?php echo $_POST['magneticdeclination']?>"></div>
</div>

<div class="row gtr-uniform gtr-50 padTop15">
	<div class="col-3 col-12-xsmall textRight textLeftXSmall vMid "><h3>Notes:</h3></div>
	<div class="col-9 col-12-xsmall"><textarea name="notes" rows="5" cols="21"><?php echo $_POST['notes']?></textarea></div>
</div>

<input type="hidden" name="arcid" value="<?php echo $_GET['arcid']?>">

<div style="text-align: center; margin-top:10px;">
	<input class="primary" type="submit" value="Submit" name="submit">
</div>

</form>

					<div class="bottomSpacer"></div>

					</div>
				</div>

<?php
include 'includes/mfooter.php';
?>