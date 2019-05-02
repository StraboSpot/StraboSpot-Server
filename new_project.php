<?
include("logincheck.php");

include("prepare_connections.php");



if($_POST['submit']!=""){


/*

<table>

	<tr><td nowrap><div align="right">Project Name:</div></td><td nowrap><?=$json->description->project_name?></td></tr>
	<tr><td nowrap><div align="right">Start Date:</div></td><td nowrap><?=$json->description->start_date?></td></tr>
	<tr><td nowrap><div align="right">End Date:</div></td><td nowrap><?=$json->description->end_date?></td></tr>
	<tr><td nowrap><div align="right">Purpose of Study:</div></td><td nowrap><?=$json->description->purpose_of_study?></td></tr>
	<tr><td nowrap><div align="right">Other Team Members:</div></td><td nowrap><?=$json->description->other_team_members?></td></tr>
	<tr><td nowrap><div align="right">Area of Interest:</div></td><td nowrap><?=$json->description->area_of_interest?></td></tr>
	<tr><td nowrap><div align="right">Spot Prefix Label:</div></td><td nowrap><?=$json->preferences->spot_prefix?></td></tr>
	<tr><td nowrap><div align="right">Starting Number for Spots:</div></td><td nowrap><?=$json->preferences->starting_number_for_spot?></td></tr>
	<tr><td nowrap><div align="right">Sample Prefix Label:</div></td><td nowrap><?=$json->preferences->sample_prefix?></td></tr>
	<tr><td nowrap><div align="right">Instruments Used:</div></td><td nowrap><?=$json->description->instruments?></td></tr>
	<tr><td nowrap><div align="right">GPS Datum:</div></td><td nowrap><?=$json->description->gps_datum?></td></tr>
	<tr><td nowrap><div align="right">Magnetic Declination:</div></td><td nowrap><?=$json->description->magnetic_declination?></td></tr>
	<tr><td valign="top" nowrap><div align="right">Notes:</div></td><td nowrap><?=$json->description->notes?></td></tr>

</table>
*/


	//print_r($_POST);

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

	/*
	$date = date_create();
	date_timezone_set($date, timezone_open('UTC'));
	$data['date'] = date_format($date, "Y-m-d\TH:i:s\Z");
	$data['modified_timestamp'] = date_format($date, "Y-m-d\TH:i:s\Z");
	*/

	$date = date_create();
	date_timezone_set($date, timezone_open('UTC'));
	$data['date'] = date_format($date, "Y-m-d\TH:i:s\Z");

	$data['modified_timestamp'] = time()*1000;

	//$data = json_encode($data);

	unset($injson);

	unset($upload['id']);
	unset($upload['featureid']);
	unset($upload['self']);

	//$injson['jsonstring']=$data;
	
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

	/*
	"date": "2017-01-17T14:00:30.187Z",
	"name": "default",
	"id": "14846616301876",
	"modified_timestamp": "1484661630187",
	"datasettype": "app",
	"self": "http://strabospot.org/db/dataset/14846616301876"
	*/
	
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

	//print_r($injson);exit();
	
	$injson=json_encode($injson);
	
	$datasetid = $neodb->createNode($injson,"Dataset");
	
	
	//echo "datasetid: $datasetid";exit();
	
	$neodb->addRelationship($projectid, $datasetid, "HAS_DATASET");








	include 'includes/header.php';
	
	?>
	<h2>Project Created Successfully.</h2>

	<br>
	<br>
	
	<?
	$arcid=$_POST['arcid'];
	if($arcid!=""){
	?>
	<a href="loadarcshapefile?arcid=<?=$arcid?>">Click Here to continue loading shapefile...</a>
	<?
	}else{
	?>
	<a href="my_data">Continue...</a>
	<?
	}
	?>




	<?

	include 'includes/footer.php';

	exit();
}










include 'includes/header.php';

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

<h2>Create New Project</h2>

<div style="padding-left:20px">

Items with (<span style="color:red;font-weight:bold;">*</span>) are required.

</div>


<br>


<form method="POST" onsubmit="return validateForm()">

<h3>Project Description</h3><br>
<hr>
<br>

<table>

	<tr><td nowrap><div align="right">Project Name:<span style="color:red;font-weight:bold;">*</span></div></td><td nowrap><input type="text" id="projectname" name="projectname" value="<?=$_POST['projectname']?>"></td></tr>
	<tr><td nowrap><div align="right">Start Date:</div></td><td nowrap><input type="text" name="startdate" value="<?=$_POST['startdate']?>"></td></tr>
	<tr><td nowrap><div align="right">End Date:</div></td><td nowrap><input type="text" name="enddate" value="<?=$_POST['enddate']?>"></td></tr>
	<tr><td nowrap><div align="right">Purpose of Study:</div></td><td nowrap><input type="text" name="purposeofstudy" value="<?=$_POST['purposeofstudy']?>"></td></tr>
	<tr><td nowrap><div align="right">Other Team Members:</div></td><td nowrap><input type="text" name="otherteammembers" value="<?=$_POST['otherteammembers']?>"></td></tr>
	<tr><td nowrap><div align="right">Area of Interest:</div></td><td nowrap><input type="text" name="areaofinterest" value="<?=$_POST['areaofinterest']?>"></td></tr>
	<tr><td nowrap><div align="right">Spot Prefix Label:</div></td><td nowrap><input type="text" name="spotprefixlabel" value="<?=$_POST['spotprefixlabel']?>"></td></tr>
	<tr><td nowrap><div align="right">Starting Number for Spots:</div></td><td nowrap><input type="text" name="startingnumberforspots" value="<?=$_POST['startingnumberforspots']?>"></td></tr>
	<tr><td nowrap><div align="right">Sample Prefix Label:</div></td><td nowrap><input type="text" name="sampleprefixlabel" value="<?=$_POST['sampleprefixlabel']?>"></td></tr>
	<tr><td nowrap><div align="right">Instruments Used:</div></td><td nowrap><input type="text" name="instrumentsused" value="<?=$_POST['instrumentsused']?>"></td></tr>
	<tr><td nowrap><div align="right">GPS Datum:</div></td><td nowrap><input type="text" name="gpsdatum" value="<?=$_POST['gpsdatum']?>"></td></tr>
	<tr><td nowrap><div align="right">Magnetic Declination:</div></td><td nowrap><input type="text" name="magneticdeclination" value="<?=$_POST['magneticdeclination']?>"></td></tr>
	<tr><td valign="top" nowrap><div align="right">Notes:</div></td><td nowrap><textarea name="notes" rows="5" cols="21"><?=$_POST['notes']?></textarea></td></tr>

</table>

<br>
<hr>
<br>






<input type="hidden" name="arcid" value="<?=$_GET['arcid']?>">


<input type="submit" value="Submit" name="submit">
</form>





<?
include 'includes/footer.php';
?>