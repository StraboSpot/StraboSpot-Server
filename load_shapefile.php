<?php
/**
 * File: load_shapefile.php
 * Description: Loads and imports shapefile geographic data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

//14878844758700
//1487884568
// id is unix time + 4 rand digits

set_time_limit(300);

include("logincheck.php");

$starttime=time();

include("shapefuncs.php");
include("logincheck.php");
include("includes/datamodel.php");

include("prepare_connections.php");

function mytime(){
	$string = microtime();
	$parts=explode(" ",$string);
	$newtime = $parts[1].substr($parts[0],2,3);
	return (int)$newtime;
}

function getid(){
	$string = time();
	$newtime = $string.rand(1111,9999);
	return (int)$newtime;
}

function get_zip_originalsize($filename) {
	$size = 0;
	$resource = zip_open($filename);
	while ($dir_resource = zip_read($resource)) {
		$size += zip_entry_filesize($dir_resource);
	}
	zip_close($resource);

	return $size;
}

function validateplanartype($intype){

	$featuretypes=array("bedding","contact","foliation","fracture","vein","fault","shear_zone_boundary");

	if(in_array($intype,$featuretypes)){
		return true;
	}else{
		return false;
	}
}

function validatelineartype($intype){

	$featuretypes=array("groove_marks","parting_lineat","magmatic_miner_1","xenolith_encla","intersection","pencil_cleav","mineral_align","deformed_marke","rodding","boudin","mullions","fold_hinge","striations","slickenlines","slickenfibers","mineral_streak","vorticity_axis","flow_transport","vergence","vector");

	if(in_array($intype,$featuretypes)){
		return true;
	}else{
		return false;
	}
}

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

if($_POST['submitfile']!="" || $_POST['submitarc']!=""){

	//file has been submitted. Check it here and save it for
	//processing shapefiles

	if($error==""){

		if($_POST['submitfile']!=""){
			//should check from filename because 'type' can vary

			$pos = strpos($_FILES['zipfile']['name'],".zip");

			if($pos === false) {
				$error.=$errordelim."Wrong file type detected. File must be zip archive.";$errordelim="<br>";
			}
		}elseif($_POST['arcid']!=""){

			$arcid = $_POST['arcid'] ?? '';
			$arcid = preg_replace('/[^a-zA-Z0-9\-]/', '', $arcid);
			$frow = $db->get_row_prepared("SELECT * FROM arcfiles WHERE arcid=$1", array($arcid));

			$pos = strpos($frow->name,".zip");

			if($pos === false) {

				include 'includes/mheader.php';

				echo "<h2>Load Shapefile</h2><br>";

				echo "Wrong file type detected. File must be zip archive.";

				include 'includes/mfooter.php';
				exit();

			}

		}
	}

	if($error==""){
		//unzip file here

		$projectid=$_POST['projectid'];

		$randnum=$db->get_var("select nextval('file_seq')");

		if($_POST['submitfile']!=""){
			$filename=str_replace(" ","_",$_FILES['zipfile']['name']);
			$tempname=$_FILES['zipfile']['tmp_name'];
			$orig_filename=str_replace(" ","_",$_FILES['zipfile']['name']);
		}elseif($_POST['arcid']!=""){
			$arcid = $_POST['arcid'];

			if($arcid==""){echo "arcid \"$arcid\" not found.";exit();}
			$filename = str_replace(" ","_",$frow->name);
			$tempname = "arcfiles/".$frow->pkey;
			$orig_filename = str_replace(" ","_",$frow->name);
		}

		mkdir("ziptemp/$randnum");

		$mydir="ziptemp/$randnum";

		$newfilename="ziptemp/$randnum"."/".$filename;

		copy ( $tempname , "$newfilename" );

		$linuxfilename=str_replace(" ","\ ",$newfilename);
		$linuxfilename=str_replace("(","\(",$linuxfilename);
		$linuxfilename=str_replace(")","\)",$linuxfilename);

		$linuxfilename=escapeshellcmd($newfilename);

	}

	if($error==""){

		$size = get_zip_originalsize("$newfilename");

		if($size > 20000000){

			$error.=$errordelim."Zip file is too large. (".number_format($size)." bytes uncompressed)";$errordelim="<br>";

		}
	}

	if($error==""){

		//unzip file
		exec("/usr/bin/unzip $linuxfilename -d ziptemp/$randnum/");
		exec("/bin/chmod -R 777 ziptemp/$randnum/");

		//delete macosx stuff
		exec("/usr/bin/find ziptemp/$randnum/ -name '__MACOSX' -exec rm -rf {} \;",$foo);

		//rename all files here, replacing spaces
		$files = scandir("ziptemp/$randnum/");
		foreach($files as $file){
			if(is_file("ziptemp/$randnum/$file")){
				$extension = pathinfo("ziptemp/$randnum/$file",  PATHINFO_EXTENSION);
				if($extension != "zip"){
					if (strpos($file, ' ') !== false) {
						$newfilename = str_replace(" ", "_", $file);
						rename("ziptemp/$randnum/$file", "ziptemp/$randnum/$newfilename");
					}
				}
			}elseif(is_dir("ziptemp/$randnum/$file") && $file!="." && $file !=".."){

				$newfilename = str_replace(" ", "_", $file);
				rename("ziptemp/$randnum/$file", "ziptemp/$randnum/$newfilename");

			}
		}

	}

	if($error==""){

		$shapefiles=array();

		//get a list of samples here

		exec("/usr/bin/find ziptemp/$randnum/ -name '*.shp'",$shapefiles);

		if(count($shapefiles)==0){

			$error.=$errordelim."No shapefiles (.shp) found in zip file.";$errordelim="<br>";

		}
	}

	if($error==""){

		$dbs=array();

		//get a list of samples here

		exec("/usr/bin/find ziptemp/$randnum/ -name '*.dbf'",$dbs);

		if(count($dbs)==0){

			$error.=$errordelim."No DBF (.dbf) files found in zip file.";$errordelim="<br>";

		}
	}

	if($error==""){

		$prjs=array();

		//get a list of samples here

		exec("/usr/bin/find ziptemp/$randnum/ -name '*.prj'",$prjs);

		if(count($prjs)==0){

			$error.=$errordelim."No Projection (.prj) files found in zip file.";$errordelim="<br>";

		}
	}

	if($error==""){

		// File is OK. If there is one .shp file, just move forward and put it in.
		// If there is more than one .shp file, display list of files for submission.

		if(count($shapefiles)>1){
			//show list here

			include 'includes/mheader.php';

			//verify form here.
			?>

			<script type="text/javascript">

				function formvalidate(){
					var go='no';

					for (var i = 0; i < document.shapeform.elements.length; i++ ) {
						if (document.shapeform.elements[i].type == 'checkbox') {
							if (document.shapeform.elements[i].checked == true) {
								go='yes';
							}
						}
					}

					if(go=='no'){
						alert("You must check at least one shapefile.");
						return false;
					}else{
						return true;
					}
				}

			</script>

			<?php

			echo "<h2>Select Shapefile(s)</h2><br>";

			echo "The following shapefile(s) where found in your zip file.<br>";
			echo "Please choose the file(s) you would like to add to your project:<br><br>";

			echo "<form name=\"shapeform\" style=\"display:inline; margin:0px; padding:0px;\" method=\"POST\" onsubmit=\"return formvalidate();\">";

			echo "<table>";

				$sn=0;
				foreach($shapefiles as $sf){

					$showname = basename($sf);

					?>
					<tr>
					<td><input type="checkbox" name="shapefilename<?php echo $sn?>" value="<?php echo $sf?>" checked="true"></td>
					<td><?php echo $showname?></td>
					</tr>

					<?php

					$sn++;
				}

			?>
			</table><br>

			<input type="hidden" name="randnum" value="<?php echo $randnum?>">
			<input type="hidden" name="projectid" value="<?php echo $projectid?>">
			<input type="hidden" name="arcid" value="<?php echo $arcid?>">

			<input class="primary" type="submit" name="indsubmit" value="Submit">

			</form>

			<?php

			include 'includes/mfooter.php';
			exit();
		}else{
			$putinone="yes";
		}

	}

}//end if submit

//check columns here for errors

if($_POST['columnsubmit']!=""){

	include_once("includes/straboClasses/straboModelClass.php");

	$smodel = new straboModelClass();

	$datasetname=$_POST['datasetname'];
	$spotnameprefix=$_POST['spotnameprefix'];
	$shapefilename=$_POST['shapefilename'];
	$randnum=$_POST['randnum'];
	$projectid=$_POST['projectid'];
	$arcid=$_POST['arcid'];
	$featuretype=$_POST['featuretype'];
	$filecolumns=$_POST['filecolumns'];
	$epsg=$_POST['epsg'];
	$fixedcolumns=$_POST['fixedcolumns'];
	$shapefilearray=$_POST['shapefilearray'];

	$smodel->setUserColumns($fixedcolumns);

	if(file_exists("ogrtemp/$randnum")){
		unlink("ogrtemp/$randnum");
	}

	exec("ogr2ogr -f GeoJSON ogrtemp/$randnum \"$shapefilename\" 2>&1",$results);

	$error = "";

	if(count($results)>0){
		$error = implode("<br>",$results);
	}

	//load json
	$shapefilejson = file_get_contents("ogrtemp/$randnum");
	$shapefilejson = json_decode($shapefilejson,true);
	$shapefilejson = $shapefilejson['features'];
	$totalcount = count($shapefilejson);
	if(file_exists("ogrtemp/$randnum")) unlink("ogrtemp/$randnum");

	//check shapefilejson here

	//foreach row, check each column and record error if necessary
	foreach($shapefilejson as $j){
		$properties = $j["properties"];
		foreach($properties as $key=>$value){
			if($value!=""){
				$strabocol = $smodel->userColumnToStraboColumn($key);
				if($strabocol){
					if(in_array($strabocol,$smodel->columns_with_other_option)){ //don't check columns that have "other" option
					}elseif($smodel->isControlled($strabocol)){
						if(!$newval = $smodel->fitsControlled($strabocol,$value)){
							$vocaberror[$strabocol]=$smodel->createCVError($strabocol,$value);
						}
					}elseif($smodel->isNumericTyped($strabocol)){
						if(!is_numeric($value)){
							$vocaberror[$strabocol]=$smodel->createNumericTypeError($strabocol,$value);
						}
					}
				}
			}
		}

	}

	if($vocaberror){
		$vocaberror=$smodel->implodeCVError($vocaberror);
	}

	

}

if($_POST['indsubmit']!="" || $putinone=="yes" || ($vocaberror!="" && $_POST['columnsubmit']!="")){

	//OK, this is either an individual file, or a filename has been posted.

	//need to look for lowest shapefile and keep the rest in an array

	$fromlist = false;
	for($x=1;$x<100;$x++){
		if($_POST['shapefilename'.$x]!=""){
			$fromlist=true;
		}
	}

	$firstoneset=false;

	if($fromlist){
		//create array and pull off first value

		for($x=0;$x<100;$x++){

			if(!$firstoneset){
				if($_POST['shapefilename'.$x]!=""){
					$shapefilename=$_POST['shapefilename'.$x];
					$firstoneset=true;
				}
			}else{

				if($_POST['shapefilename'.$x]!=""){
					$shapefilearray.=$sfdelim.$_POST['shapefilename'.$x];
					$sfdelim="***";
				}

			}
		}

	}elseif($_POST['shapefilearray']!="" && !$vocaberror){

		if($vocaberroro){
			$_POST['shapefilearray']="$shapefilename"."***".$_POST['shapefilearray'];
		}

		$sfarray=explode("***",$_POST['shapefilearray']);
		$shapefilename=$sfarray[0];
		for($x=1;$x<100;$x++){
			if($sfarray[$x]!=""){
				$shapefilearray.=$sfdelim.$sfarray[$x];
				$sfdelim="***";
			}
		}
	}else{
		if($shapefilename==""){
			$shapefilename=$shapefiles[0];
		}
	}

	if($_POST['randnum']!=""){
		$randnum=$_POST['randnum'];
	}

	if($_POST['projectid']!=""){
		$projectid=$_POST['projectid'];
	}

	if($_POST['arcid']!=""){
		$arcid=$_POST['arcid'];
	}

	if($_POST['shapefilename']!=""){
		$arcid=$_POST['shapefilename'];
	}

	//let's try to get the projection here

	//first, find the .prj file
	$prjs=array();

	$prjfilename = str_replace(".shp",".prj",$shapefilename);

	if(file_exists($prjfilename)){

		//now we have a custom python script instead of calling a web service
		exec("/srv/app/www/prj2epsg.py \"$prjfilename\"",$epsg);
		$epsg=$epsg[0];

		exec("/srv/app/www/prj2wkt.py \"$prjfilename\"", $wkt);
		$wkt = $wkt[0];

/*
Bad:
epsg: None
wkt: GEOGCS["GCS_GDA_1994",DATUM["Geocentric_Datum_of_Australia_1994",SPHEROID["GRS_1980",6378137.0,298.257222101]],PRIMEM["Greenwich",0.0],UNIT["Degree",0.0174532925199433]]

Good:
epsg: 4326
wkt: GEOGCS["GCS_WGS_1984",DATUM["WGS_1984",SPHEROID["WGS_84",6378137.0,298.257223563]],PRIMEM["Greenwich",0.0],UNIT["Degree",0.0174532925199433]]

*/
		if($epsg=="None"){

			//OK, do another check, but this time from postgres
			exec("/srv/app/www/prj2wkt.py ".escapeshellarg($prjfilename), $wkt);
			$wkt = $wkt[0];

			$newepsg = $db->get_var_prepared("SELECT srid FROM spatial_ref_sys WHERE srtext LIKE $1 LIMIT 1", array('%'.$wkt.'%'));

			if($newepsg == ""){
				//error here
				include 'includes/mheader.php';
				?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Unknown Shapefile Projection</h2>
						</header>

				<div style="color:red;">Error!</div>

				<div style="padding-top:10px;">
				Your shapefile appears to have an unknown shapefile projection. Here is the projection found by the StraboSpot Server:
				</div>

				<div style="color:#a22330;padding-top:20px;">
				<?php echo $wkt?>
				</div>

				<div style="padding-top:20px;">
				Please try re-projecting your shapefile to a more common SRS, such as EPSG:4326.
				</div>

				<div style="padding-top:20px;">
				Regards,
				</div>

				<div style="padding-top:20px;">
				The StraboSpot Team
				</div>

					<div class="bottomSpacer"></div>

					</div>
				</div>
				<?php

				include 'includes/mfooter.php';

				

				exit();
			}else{
				$epsg = $newepsg;
			}
		}

		

	}else{

		$error.=$errordelim."Projection file (".basename($prjfilename).") not found in zip file.";$errordelim="<br>";

	}

	if($error==""){

		/*
		Let's try to figure out what kind of file this is :
		Attitudes	:	point
		Rocks		:	point or poly
		Contact		:	line
		Folds		:	line
		Notes		:	point
		*/

		///****************************************************************************************************************
		///****************************************************************************************************************

		require_once('ShapeFile.inc.php');
		try {
			$shp = new ShapeFile($shapefilename, array('noparts' => false));

			$shapetype=$shp->shp_type;

			while ($record = $shp->getNext()) {

				$dataarray = $record->getDbfData();
				break;

			}

		} catch (ShapeFileException $e) {
			exit('Error '.$e->getCode().': '.$e->getMessage());
		}

		$filecolumns=array();
		foreach($dataarray as $key=>$value){
			//if(strtolower($key)!="objectid" && strtolower($key)!="shape_leng" && strtolower($key)!="shape_area" && strtolower($key)!="souerce" && strtolower($key)!="labelout" && strtolower($key)!="deleted"){
			if(strtolower($key)!="souerce" && strtolower($key)!="labelout" && strtolower($key)!="deleted"){
				$filecolumns[]=$key;
			}
		}

		///****************************************************************************************************************
		///****************************************************************************************************************

		$fcarray=$filecolumns;

		include 'includes/mheader.php';

	?>

			<script type="text/javascript">

			function formvalidate(){
				//alert('hey');

				var index;
				var mystring="";
				var mydelim="";
		<?php
		$mydelim="";
		$mystring="";
		foreach($fcarray as $fc){
			$mystring.=$mydelim.'"'.$fc.'"';
			$mydelim=",";
		}
		$mystring = "var columns = [$mystring];";
		?>
				<?php echo $mystring?>
				//var columns = ["period", "epoch", "notes"];
				for	(index = 0; index < columns.length; index++) {
					mystring += mydelim + columns[index] + ":" + document.forms["columnform"][columns[index]+"_select"].value;
					mydelim=";";
				}

				document.forms["columnform"]["fixedcolumns"].value=mystring;

			}

			</script>

	<?php

		if($vocaberror!=""){
			?>

			<fieldset style="border: 1px solid red; background-color:#fcf0ef; padding: 8px; padding-bottom:0px; margin: 8px 0">
				<legend style="color:red;"><strong>Error!</strong></legend>
				<div><?php echo $vocaberror?></div>
				<br>
			</fieldset>

			<br>

			<?php
			/*
			<form action="">
				<input type="submit" value="Continue" />
			</form>

			include 'includes/footer.php';
			exit();
			*/
		}

		$showname = basename($shapefilename);

		$shpname=basename($shapefilename);

		?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Define Columns for Shapefile:<br><?php echo $showname?></h2>
						</header>

		<form name="columnform" method="POST" onsubmit="return formvalidate();">

		Dataset Name: <input type="text" name="datasetname" value="<?php echo $shpname?>"><br>
		Spot Name Prefix: (optional) <input type="text" name="spotnameprefix" value="<?php echo $spotnameprefix?>" size="10">
		<br><br>

		<p>
		Use the form below to define columns for upload. A best guess has been performed on the file columns.
		</p>

		<p>
		If your shapefile column does not fit into any of the provided column names, you may select "Custom Column" to retain the shapefile information at strabospot.org.
		</p>

		<?php
		if($smodel->dumpVar){
		}
		?>

		<div class="mytable">
			<table style="padding-top:20px;">
				<thead>
					<tr>
						<th><strong>Shapefile Column&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></th><th><strong>StraboSpot Column</strong></th>
					</tr>
				</thead>
		<?php

		include_once("includes/straboClasses/straboModelClass.php");

		if(!$smodel){
			$smodel = new straboModelClass();
		}

		foreach($fcarray as $fc){

		?>

				<tr>
					<td><?php echo $fc?></td>
					<td>
						<?php echo $smodel->getSelect("$fc")?>
					</td>
				</tr>
		<?php
		}
		?>

			</table>

		</div>
		<div style="padding-top:20px;" align="center">
			<input class="primary" type="submit" name="columnsubmit" value="Submit">
		</div>
		<input type="hidden" name="shapefilename" value ="<?php echo $shapefilename?>">
		<input type="hidden" name="randnum" value ="<?php echo $randnum?>">
		<input type="hidden" name="projectid" value ="<?php echo $projectid?>">
		<input type="hidden" name="arcid" value ="<?php echo $arcid?>">
		<input type="hidden" name="featuretype" value ="<?php echo $featuretype?>">
		<input type="hidden" name="filecolumns" value ="<?php echo $filecolumns?>">
		<input type="hidden" name="epsg" value ="<?php echo $epsg?>">
		<input type="hidden" name="fixedcolumns" value="">
		<input type="hidden" name="shapefilearray" value="<?php echo $shapefilearray?>">
		</form>
					<div class="bottomSpacer"></div>

					</div>
				</div>
		<?php

		include 'includes/mfooter.php';

		exit();

	}else{

		//show error here
		include 'includes/mheader.php';

		?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Error</h2>
						</header>

		<div style="color:red;"><?php echo $error?></div><br>

		Please try your upload again.

					<div class="bottomSpacer"></div>

					</div>
				</div>
		<?php

		include 'includes/mfooter.php';
		exit();

	}

}

if($_POST['columnsubmit']!=""){

	include_once("includes/straboClasses/straboModelClass.php");

	if(!$smodel){
		$smodel = new straboModelClass();
	}

	include 'includes/mheader.php';

	?>

			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Loading Shapefile...</h2>
						</header>

	<div id="progress" style="width:100%;border:1px solid #ccc;"></div>
	<!-- Progress information -->
	<div id="information" style="width"></div>

	<div id="continuediv" style="padding-top:20px;"></div>

	<?php

	$shapefilename=$_POST['shapefilename'];
	$shapefilearray=$_POST['shapefilearray'];
	$randnum=$_POST['randnum'];
	$projectid=$_POST['projectid'];
	$arcid=$_POST['arcid'];
	$featuretype=$_POST['featuretype'];
	$filecolumns=$_POST['filecolumns'];
	$fixedcolumns=$_POST['fixedcolumns'];
	$epsg=$_POST['epsg'];
	$userpkey=(int)$_SESSION['userpkey'];
	$datasetname = $_POST['datasetname'];
	$spotnameprefix = $_POST['spotnameprefix'];

	$dbcols=array();

	$parts=explode(";",$fixedcolumns);
	foreach($parts as $part){
		$bits=explode(":",$part);
		if($bits[1]!=""){
			$dbcols[$bits[1]]=$bits[0];
		}
	}

	$smodel->setDBCols($dbcols);

	$projectvars = $neodb->getNode("match (p:Project) where p.id=$projectid and p.userpkey=$userpkey return p");
	$smodel->setProjectVars($projectvars);

	$t=0;

	/*
	OK, let's use ogr2ogr to get shapefile data into JSON. This method works better than the PHP library

	Sample command to run:

	ogr2ogr -f GeoJSON foo/12345 ogrtest/rocks.shp

	where foo/12345 is the output and orgtest/rocks.shp is the input

	*/

	if(file_exists("ogrtemp/$randnum")){
		unlink("ogrtemp/$randnum");
	}

	$workshapefilename = str_replace(" ", "\\ ", $shapefilename);

	exec("ogr2ogr -f GeoJSON ogrtemp/$randnum \"$workshapefilename\" 2>&1",$results);

	$error = "";

	if(count($results)>0){
		$error = implode("<br>",$results);
	}

	//load json
	$json = file_get_contents("ogrtemp/$randnum");

	$json = json_decode($json,true);

	$json = $json['features'];

	$totalcount = count($json);

	if(file_exists("ogrtemp/$randnum")){
		unlink("ogrtemp/$randnum");
	}

	require_once('ShapeFile.inc.php');
	try {
		$shp = new ShapeFile($shapefilename, array('noparts' => false));

		$shapetype=$shp->shp_type;

		$step=floor($totalcount/100);
		if($step==0){$step=1;}

		if($totalcount > 0){

			//create a "Dataset" node here to group all of the features.
			unset($injson);
			$injson['userpkey']=$userpkey;
			$datasetid=$smodel->getId();

			$injson['id']=$datasetid;
			$injson['datecreated']=time();
			$injson['folder']=$randnum;
			$shpname=explode("/",$shapefilename);
			$shpname=$shpname[2];
			$injson['shapefile']=$shpname;
			$injson['name']=$datasetname;
			$injson['featuretype']=$featuretype;
			$injson['datasettype']="shapefile";
			$injson=json_encode($injson);

			//********************************************************************
			// OK, we have some JSON formed and ready for Neo4j.
			// Let's POST to the REST API to create a node
			//********************************************************************
			$id = $neodb->createNode($injson,"Dataset");

			//********************************************************************
			// Now add dataset to project.
			//********************************************************************
			$updateprojectid = $strabo->straboIDToID($projectid,"Project");
			$neodb->addRelationship($updateprojectid,$id,"HAS_DATASET");

			$groupid = $id;

		}

		$foundspots = [];

		foreach($json as $onesample){

			$geometry = $onesample['geometry'];

			if($geometry != ""){

				$thisnewid = $smodel->getId();

				$putinfeature="yes";

				$newshapetype=$shapetype;
				if($newshapetype > 10){$newshapetype = $newshapetype - 10;}

				//dumpVar($geometry);
				if($geometry!=""){
					$geometry = json_encode($geometry);
					$myjson = geoPHP::load($geometry,"json");
					$mywkt = $myjson->out('wkt');
				}else{
					$putinfeatur="no";
				}

				if($mywkt!=""){
					$myjson = geoPHP::load($geometry,"json");
					$mywkt = $myjson->out('wkt');
				}

				unset($injson);

				$injson['type']="Feature";

				$uploaddate = time();

				$geom = $mywkt;

				//blank geoms not allowed
				if($geom==""){$putinfeature="no";}

				//broken values from ESRI contain "+" sign

				if(strpos($geom,"+")>0){
					$putinfeature="no";
				}

				$fixedgeom = "";

				if($geom != ""){

					if($epsg != "" && $epsg!=""){

						// $epsg is cast to int earlier, $geom is from shapefile parsing
						$fixedgeom=$db->get_var_prepared("SELECT ST_AsText(ST_Transform(ST_GeomFromText($1,$2),4326)) as geom", array($geom, (int)$epsg));

					}else{

						$fixedgeom="empty";

					}

				}

				if($fixedgeom!=""){
					$mywkt = geoPHP::load($fixedgeom,"wkt");
					$mygeometry = $mywkt->out('json');
				}

				$onesampleproperties = $onesample['properties'];

				$smodel->setSampleProperties($onesampleproperties);

				/***********************************
					Get spot level properties
				***********************************/
				$spotvars = $smodel->get_vars("spot");

				foreach($spotvars as $key=>$value){
					if($key=="name"){
						$value = strval($value);
					}else{
						$value = $smodel->fixCast($value);
					}

					eval("\$injson['properties']['$key']=\$value;");
				}

				/***********************************
					Get orientation data
				***********************************/
				unset($planarorientation);
				$planarorientation=array();
				if($smodel->hasPlanarOrientationData()){
					$planarorientation["id"]=$smodel->getId();
					$planarorientation["type"]="planar_orientation";
					$planar_orientation_vars = $smodel->get_vars("planar_orientation");

					foreach($planar_orientation_vars as $key=>$value){
						if($key=="feature_type"){
							if($newvalue = $smodel->fitsControlled("planar_orientation_feature_type",$value)){
								$planarorientation['feature_type']=$newvalue;
							}else{
								if(trim($value=="")){$value="not given";}$oothers.="planar value: $value<br>";
								$planarorientation['feature_type']="other";
								$planarorientation['other_feature']=$value;
							}
						}elseif($key=="movement"){
							if($newvalue = $smodel->fitsControlled("planar_orientation_movement",$value)){
								$planarorientation['movement']=$newvalue;
							}else{
								$planarorientation['movement']="other";
								$planarorientation['other_movement']=$value;

							}
						}else{
							$value = $smodel->fixCast($value);
							eval("\$planarorientation['$key']=\$value;");
						}
					}

					if($planarorientation['feature_type']==""){ $planarorientation['feature_type']="other"; $planarorientation['other_feature']="not given"; }

					if($smodel->hasLinearOrientationData()){
						unset($linearorientation);
						$linearorientation=array();
						$linearorientation["id"]=$smodel->getId();
						$linearorientation["type"]="linear_orientation";
						$linear_orientation_vars = $smodel->get_vars("linear_orientation");
						foreach($linear_orientation_vars as $key=>$value){
							if($key=="feature_type"){
								if($newvalue = $smodel->fitsControlled("linear_orientation_feature_type",$value)){
									$linearorientation['feature_type']=$newvalue;
								}else{
									if(trim($value=="")){$value="not given";}$oothers.="inside linear value: $value<br>";
									$linearorientation['feature_type']="other";
									$linearorientation['other_feature']=$value;
								}
							}else{
								$value = $smodel->fixCast($value);
								eval("\$linearorientation['$key']=\$value;");
							}
						}

						if($linearorientation['feature_type']==""){ $linearorientation['feature_type']="other"; $linearorientation['other_feature']="not given"; }

						$planarorientation["associated_orientation"][]=$linearorientation;
					}

					$injson['properties']["orientation_data"][]=$planarorientation;

				}elseif($smodel->hasLinearOrientationData()){

					unset($linearorientation);
					$linearorientation=array();
					$linearorientation["id"]=$smodel->getId();
					$linearorientation["type"]="linear_orientation";
					$linear_orientation_vars = $smodel->get_vars("linear_orientation");
					foreach($linear_orientation_vars as $key=>$value){
						if($key=="feature_type"){
							if($newvalue = $smodel->fitsControlled("linear_orientation_feature_type",$value)){
								$linearorientation['feature_type']=$newvalue;
							}else{
								if(trim($value=="")){$value="not given";}$oothers.="outside linear value: $value<br>";
								$linearorientation['feature_type']="other";
								$linearorientation['other_feature']=$value;
							}
						}else{
							$value = $smodel->fixCast($value);
							eval("\$linearorientation['$key']=\$value;");
						}
					}

					if($linearorientation['feature_type']==""){ $linearorientation['feature_type']="other"; $linearorientation['other_feature']="not given"; }

					$injson['properties']["orientation_data"][]=$linearorientation;

				}

				unset($tabularzoneorientation);
				$tabularzoneorientation=array();
				if($smodel->hasTabularZoneOrientationData()){
					$tabularzoneorientation["id"]=$smodel->getId();
					$tabularzoneorientation["type"]="tabular_orientation";
					$tabular_zone_orientation_vars = $smodel->get_vars("tabular_zone_orientation");
					foreach($tabular_zone_orientation_vars as $key=>$value){
						if($key=="facing_defined_by"){
							if($newvalue = $smodel->fitsControlled("tabular_zone_orientation_facing_defined_by",$value)){
								$tabularzoneorientation['facing_defined_by']=$newvalue;
							}else{
								$tabularzoneorientation['facing_defined_by']="other";
								$tabularzoneorientation['other_facing_defined_by']=$value;
							}
						}elseif($key=="feature_type"){
							if($newvalue = $smodel->fitsControlled("tabular_zone_orientation_feature_type",$value)){
								$tabularzoneorientation['feature_type']=$newvalue;
							}else{
								$tabularzoneorientation['feature_type']="other";
								$tabularzoneorientation['other_feature']=$value;
							}
						}elseif($key=="intrusive_body_type"){
							if($newvalue = $smodel->fitsControlled("tabular_zone_orientation_intrusive_body_type",$value)){
								$tabularzoneorientation['intrusive_body_type']=$newvalue;
							}else{
								$tabularzoneorientation['intrusive_body_type']="other";
								$tabularzoneorientation['other_intrusive_body']=$value;
							}
						}elseif($key=="vein_fill"){
							if($newvalue = $smodel->fitsControlled("tabular_zone_orientation_vein_fill",$value)){
								$tabularzoneorientation['vein_fill']=$newvalue;
							}else{
								$tabularzoneorientation['vein_fill']="other";
								$tabularzoneorientation['other_vein_fill']=$value;
							}
						}elseif($key=="vein_array"){
							if($newvalue = $smodel->fitsControlled("tabular_zone_orientation_vein_array",$value)){
								$tabularzoneorientation['vein_array']=$newvalue;
							}else{
								$tabularzoneorientation['vein_array']="other";
								$tabularzoneorientation['other_vein_array']=$value;
							}
						}elseif($key=="fault_or_sz"){
							if($newvalue = $smodel->fitsControlled("tabular_zone_orientation_fault_or_sz",$value)){
								$tabularzoneorientation['fault_or_sz']=$newvalue;
							}else{
								$tabularzoneorientation['fault_or_sz']="other";
								$tabularzoneorientation['other_fault_or_sz']=$value;
							}
						}elseif($key=="movement"){
							if($newvalue = $smodel->fitsControlled("tabular_zone_orientation_movement",$value)){
								$tabularzoneorientation['movement']=$newvalue;
							}else{
								$tabularzoneorientation['movement']="other";
								$tabularzoneorientation['other_movement']=$value;
							}
						}elseif($key=="movement_justification"){
							if($newvalue = $smodel->fitsControlled("tabular_zone_orientation_movement_justification",$value)){
								$tabularzoneorientation['movement_justification']=$newvalue;
							}else{
								$tabularzoneorientation['movement_justification']="other";
								$tabularzoneorientation['other_movement_justification']=$value;
							}
						}elseif($key=="dir_indicators"){
							if($newvalue = $smodel->fitsControlled("tabular_zone_orientation_dir_indicators",$value)){
								$tabularzoneorientation['dir_indicators']=$newvalue;
							}else{
								$tabularzoneorientation['dir_indicators']="other";
								$tabularzoneorientation['other_dir_indicators']=$value;
							}
						}elseif($key=="enveloping_surface_geometry"){
							if($newvalue = $smodel->fitsControlled("tabular_zone_orientation_enveloping_surface_geometry",$value)){
								$tabularzoneorientation['enveloping_surface_geometry']=$newvalue;
							}else{
								$tabularzoneorientation['enveloping_surface_geometry']="other";
								$tabularzoneorientation['other_surface_geometry']=$value;
							}
						}else{
							$value = $smodel->fixCast($value);
							eval("\$tabularzoneorientation['$key']=\$value;");
						}
					}

					if($tabularzoneorientation['feature_type']=="") $tabularzoneorientation['feature_type']="other";

					$injson['properties']["orientation_data"][]=$tabularzoneorientation;

				}

				/***********************************
						Get 3D Structure data
				***********************************/
				if($smodel->has3dStructureData()){

					if($smodel->hasFabricData()){
					unset($thisstruct);
					$thisstruct=array();
					$thisstruct["id"]=$smodel->getId();
					$thisstruct["type"]="fabric";
					$struct_vars = $smodel->get_vars("fabric");
					foreach($struct_vars as $key=>$value){
						if($key=="feature_type"){
							if($newvalue = $smodel->fitsControlled("fabric_feature_type",$value)){
								$thisstruct['feature_type']=$newvalue;
							}else{
								$thisstruct['feature_type']="other_fabric";
								$thisstruct['other_description']=$value;
							}
						}else{
							$value = $smodel->fixCast($value);
							eval("\$thisstruct['$key']=\$value;");
						}
					}
					$injson['properties']["_3d_structures"][]=$thisstruct;
					}

					if($smodel->hasFoldData()){
					unset($thisstruct);
					$thisstruct=array();
					$thisstruct["id"]=$smodel->getId();
					$thisstruct["type"]="fold";
					$struct_vars = $smodel->get_vars("fold");
					foreach($struct_vars as $key=>$value){
						if($key=="feature_type"){
							if($newvalue = $smodel->fitsControlled("fold_feature_type",$value)){
								$thisstruct['feature_type']=$newvalue;
							}else{
								$thisstruct['feature_type']="other";
								$thisstruct['other_dominant_geometry']=$value;
							}
						}else{
							$value = $smodel->fixCast($value);
							eval("\$thisstruct['$key']=\$value;");
						}
					}
					$injson['properties']["_3d_structures"][]=$thisstruct;
					}

					if($smodel->hasTensorData()){
					unset($thisstruct);
					$thisstruct=array();
					$thisstruct["id"]=$smodel->getId();
					$thisstruct["type"]="tensor";
					$struct_vars = $smodel->get_vars("tensor");
					foreach($struct_vars as $key=>$value){
						if($key=="ellipsoid_type"){
							if($newvalue = $smodel->fitsControlled("tensor_ellipsoid_type",$value)){
								$thisstruct['ellipsoid_type']=$newvalue;
							}else{
								$thisstruct['ellipsoid_type']="other";
								$thisstruct['other_ellipsoid_type']=$value;
							}
						}elseif($key=="non_ellipsoidal_type"){
							if($newvalue = $smodel->fitsControlled("tensor_non_ellipsoidal_type",$value)){
								$thisstruct['non_ellipsoidal_type']=$newvalue;
							}else{
								$thisstruct['non_ellipsoidal_type']="other";
								$thisstruct['other_non_ellipsoidal_type']=$value;
							}
						}elseif($key=="ellipse_type"){
							if($newvalue = $smodel->fitsControlled("tensor_ellipse_type",$value)){
								$thisstruct['ellipse_type']=$newvalue;
							}else{
								$thisstruct['ellipse_type']="other";
								$thisstruct['other_ellipse_type']=$value;
							}
						}else{
							$value = $smodel->fixCast($value);
							eval("\$thisstruct['$key']=\$value;");
						}
					}
					$injson['properties']["_3d_structures"][]=$thisstruct;
					}

					if($smodel->hasOther3dStructureData()){
					unset($thisstruct);
					$thisstruct=array();
					$thisstruct["id"]=$smodel->getId();
					$thisstruct["type"]="other";
					$struct_vars = $smodel->get_vars("other_3d_structure");
					foreach($struct_vars as $key=>$value){
						if($key=="feature_type"){
							if($newvalue = $smodel->fitsControlled("other_3d_structure_feature_type",$value)){
								$thisstruct['feature_type']=$newvalue;
							}else{
								$thisstruct['feature_type']="other_3d_structure";
								$thisstruct['other_structure_description']=$value;
							}
						}else{
							$value = $smodel->fixCast($value);
							eval("\$thisstruct['$key']=\$value;");
						}
					}
					$injson['properties']["_3d_structures"][]=$thisstruct;
					}

				}

				/***********************************
						Get Rock Unit data
				***********************************/
				if($smodel->hasRockUnitData()){
					unset($thisstruct);
					$thisstruct=array();
					$struct_vars = $smodel->get_vars("rock_unit");
					foreach($struct_vars as $key=>$value){
						if($key=="sediment_type"){
							if($newvalue = $smodel->fitsControlled("rock_unit_sediment_type",$value)){
								$thisstruct['sediment_type']=$newvalue;
							}else{
								$thisstruct['sediment_type']="other";
								$thisstruct['other_sediment_type']=$value;
							}
						}elseif($key=="sedimentary_rock_type"){
							if($newvalue = $smodel->fitsControlled("rock_unit_sedimentary_rock_type",$value)){
								$thisstruct['sedimentary_rock_type']=$newvalue;
							}else{
								$thisstruct['sedimentary_rock_type']="other";
								$thisstruct['other_sedimentary_rock_type']=$value;
							}
						}elseif($key=="volcanic_rock_type"){
							if($newvalue = $smodel->fitsControlled("rock_unit_volcanic_rock_type",$value)){
								$thisstruct['volcanic_rock_type']=$newvalue;
							}else{
								$thisstruct['volcanic_rock_type']="other";
								$thisstruct['other_volcanic_rock_type']=$value;
							}
						}elseif($key=="plutonic_rock_types"){
							if($newvalue = $smodel->fitsControlled("rock_unit_plutonic_rock_types",$value)){
								$thisstruct['plutonic_rock_types']=$newvalue;
							}else{
								$thisstruct['plutonic_rock_types']="other";
								$thisstruct['other_plutonic_rock_type']=$value;
							}
						}elseif($key=="metamorphic_rock_types"){
							if($newvalue = $smodel->fitsControlled("rock_unit_metamorphic_rock_types",$value)){
								$thisstruct['metamorphic_rock_types']=$newvalue;
							}else{
								$thisstruct['metamorphic_rock_types']="other";
								$thisstruct['other_metamorphic_rock_type']=$value;
							}
						}elseif($key=="metamorphic_grade"){
							if($newvalue = $smodel->fitsControlled("rock_unit_metamorphic_grade",$value)){
								$thisstruct['metamorphic_grade']=$newvalue;
							}else{
								$thisstruct['metamorphic_grade']="other";
								$thisstruct['other_metamorphic_grade']=$value;
							}
						}elseif($key=="epoch"){
							if($newvalue = $smodel->fitsControlled("rock_unit_epoch",$value)){
								$thisstruct['epoch']=$newvalue;
							}else{
								$thisstruct['epoch']="other";
								$thisstruct['other_epoch']=$value;
							}
						}else{
							$value = $smodel->fixCast($value);
							eval("\$thisstruct['$key']=\$value;");
						}
					}

					if($thisstruct['rock_type']=="igneous"){
						if($thisstruct['igneous_rock_class']==""){
							$thisstruct['igneous_rock_class']="";
						}
					}

					if($putinfeature=="yes"){
						$smodel->addRockUnitTag($thisstruct,$thisnewid);
						$tagsfound="yes";
					}
				}

				/***********************************
					  Get Trace data
				***********************************/
				if($smodel->hasTraceData()){
					unset($thisstruct);
					$thisstruct=array();
					$thisstruct["trace_feature"]=true;
					$struct_vars = $smodel->get_vars("trace");

					foreach($struct_vars as $key=>$value){
						if($key=="quality"){
							if($newvalue = $smodel->fitsControlled("trace_trace_quality",$value)){
								$thisstruct['trace_quality']=$newvalue;
							}else{
								$thisstruct['trace_quality']="other";
								$thisstruct['other_trace_quality']=$value;
							}
						}elseif($key=="contact_type"){
							if($newvalue = $smodel->fitsControlled("trace_contact_type",$value)){
								$thisstruct['contact_type']=$newvalue;
							}else{
								$thisstruct['contact_type']="other";
								$thisstruct['other_contact_type']=$value;
							}
						}elseif($key=="depositional_contact_type"){
							if($newvalue = $smodel->fitsControlled("trace_depositional_contact_type",$value)){
								$thisstruct['depositional_contact_type']=$newvalue;
							}else{
								$thisstruct['depositional_contact_type']="other";
								$thisstruct['other_depositional_type']=$value;
							}
						}elseif($key=="intrusive_contact_type"){
							if($newvalue = $smodel->fitsControlled("trace_intrusive_contact_type",$value)){
								$thisstruct['intrusive_contact_type']=$newvalue;
							}else{
								$thisstruct['intrusive_contact_type']="other";
								$thisstruct['other_intrusive_contact']=$value;
							}
						}elseif($key=="metamorphic_contact_type"){
							if($newvalue = $smodel->fitsControlled("trace_metamorphic_contact_type",$value)){
								$thisstruct['metamorphic_contact_type']=$newvalue;
							}else{
								$thisstruct['metamorphic_contact_type']="other";
								$thisstruct['other_metamorphic_contact']=$value;
							}
						}elseif($key=="shear_sense"){
							if($newvalue = $smodel->fitsControlled("trace_shear_sense",$value)){
								$thisstruct['shear_sense']=$newvalue;
							}else{
								$thisstruct['shear_sense']="other";
								$thisstruct['other_shear_sense']=$value;
							}
						}elseif($key=="other_structural_zones"){
							if($newvalue = $smodel->fitsControlled("trace_other_structural_zones",$value)){
								$thisstruct['other_structural_zones']=$newvalue;
							}else{
								$thisstruct['other_structural_zones']="other";
								$thisstruct['other_other_structural_zone']=$value;
							}
						}elseif($key=="fold_type"){
							if($newvalue = $smodel->fitsControlled("trace_fold_type",$value)){
								$thisstruct['fold_type']=$newvalue;
							}else{
								$thisstruct['fold_type']="other";
								$thisstruct['other_fold_type']=$value;
							}
						}elseif($key=="attitude"){
							if($newvalue = $smodel->fitsControlled("trace_fold_attitude",$value)){
								$thisstruct['attitude']=$newvalue;
							}else{
								$thisstruct['attitude']="other";
								$thisstruct['other_attitude']=$value;
							}
						}elseif($key=="geomorphic_feature"){
							if($newvalue = $smodel->fitsControlled("trace_geomorphic_feature",$value)){
								$thisstruct['geomorphic_feature']=$newvalue;
							}else{
								$thisstruct['geomorphic_feature']="other";
								$thisstruct['other_geomorphic_feature']=$value;
							}
						}elseif($key=="antropogenic_feature"){
							if($newvalue = $smodel->fitsControlled("trace_antropogenic_feature",$value)){
								$thisstruct['antropogenic_feature']=$newvalue;
							}else{
								$thisstruct['antropogenic_feature']="other";
								$thisstruct['other_antropogenic_feature']=$value;
							}
						}elseif($key=="other_feature"){
							if($newvalue = $smodel->fitsControlled("trace_other_feature",$value)){
								$thisstruct['other_feature']=$newvalue;
							}else{
								$thisstruct['other_feature']="other";
								$thisstruct['other_other_feature']=$value;
							}
						}elseif($key=="character"){
							if($newvalue = $smodel->fitsControlled("trace_trace_character",$value)){
								$thisstruct['character']=$newvalue;
							}else{
								$thisstruct['character']="other";
								$thisstruct['other_character']=$value;
							}
						}elseif($key=="type"){
							if($newvalue = $smodel->fitsControlled("trace_trace_type",$value)){
								$thisstruct['trace_type']=$newvalue;
							}else{
								$thisstruct['trace_type']="other_feature";
								$thisstruct['other_feature']="other";
								$thisstruct['other_other_feature']=$value;
							}
						}elseif($key=="geologic_structure_type"){
							if($newvalue = $smodel->fitsControlled("trace_geologic_structure_type",$value)){
								$thisstruct['geologic_structure_type']=$newvalue;
							}else{
								$thisstruct['geologic_structure_type']="other";
								$thisstruct['cother_feature']="other";
								$thisstruct['cother_other_feature']=$value;
							}
						}else{
							$value = $smodel->fixCast($value);
							eval("\$thisstruct['$key']=\$value;");
						}

					}

					if($thisstruct['trace_type']==""){

						if($thisstruct['contact_type']!=""){
							$thisstruct['trace_type']="contact";
						}else{
							$thisstruct['trace_type']="other_feature";
							$thisstruct['other_feature']="other";
							$thisstruct['other_other_feature']="not given";
						}
					}

					$injson['properties']["trace"]=$thisstruct;
				}

				/***********************************
					  Get Other Feature data
				***********************************/
				if($smodel->hasOtherFeatureData()){
					unset($thisstruct);
					$thisstruct=array();
					$thisstruct["id"]=$smodel->getId();
					$struct_vars = $smodel->get_vars("other_features");
					foreach($struct_vars as $key=>$value){
						$value = $smodel->fixCast($value);
						eval("\$thisstruct['$key']=\$value;");
					}
					$injson['properties']["other_features"][]=$thisstruct;
				}

				/***********************************
						Get Sample data
				***********************************/
				if($smodel->hasSampleData()){
					unset($thisstruct);
					$thisstruct=array();
					$thisstruct["id"]=$smodel->getId();
					$struct_vars = $smodel->get_vars("sample");
					foreach($struct_vars as $key=>$value){
						if($key=="material_type"){
							if($newvalue = $smodel->fitsControlled("sample_material_type",$value)){
								$thisstruct['material_type']=$newvalue;
							}else{
								$thisstruct['material_type']="other";
								$thisstruct['other_material_type']=$value;
							}
						}elseif($key=="main_sampling_purpose"){
							if($newvalue = $smodel->fitsControlled("sample_main_sampling_purpose",$value)){
								$thisstruct['main_sampling_purpose']=$newvalue;
							}else{
								$thisstruct['main_sampling_purpose']="other";
								$thisstruct['other_sampling_purpose']=$value;
							}
						}else{
							$value = $smodel->fixCast($value);
							eval("\$thisstruct['$key']=\$value;");
						}
					}
					$injson['properties']["samples"][]=$thisstruct;
				}

				/***********************************
						 Get custom fields
				***********************************/
				if($smodel->hasCustomFields()){
					unset($customfields);
					$customfields=array();
					$customfieldvars = $smodel->get_vars("sfcustom");
					$injson['properties']["custom_fields"]=$customfieldvars;
				}

				/***********************************
						Get Tag data
				***********************************/
				if($smodel->hasTagData()){
					unset($thisstruct);
					$thisstruct=array();
					$struct_vars = $smodel->get_vars("tag");
					foreach($struct_vars as $key=>$value){
						$value = $smodel->fixCast($value);
						eval("\$thisstruct['$key']=\$value;");
					}

					if($putinfeature=="yes"){
						$smodel->addTag($thisstruct,$thisnewid);
						$tagsfound="yes";
					}
				}

				$mytime=time();
				$myrand = rand(1000,9999);

				$injson_straboid= $smodel->getId();
				$injson_time = date("c");
				$injson_modified_timestamp = $mytime*1000;
				$injson_mydate = $injson_time;
				$injson_myname = $mytime.$myrand;

				$injson['properties']['id']=$thisnewid;
				$injson['properties']['time'] = $injson_time;
				$injson['properties']['modified_timestamp'] = mytime();
				$injson['properties']['date'] = $injson_mydate;

				if($injson['properties']['name']==""){
					$injson['properties']['name'] = $spotnameprefix.(string)$t;
				}

				$injson['geometry']=json_decode($mygeometry);

				$injson=json_encode($injson,JSON_PRETTY_PRINT);

				if($putinfeature=="yes"){

					$strabo->insertSpot($injson);
					$strabo->addSpotToDataset($datasetid,$thisnewid);
					$foundspots[] = $thisnewid;

				}else{//end if putinfeature == yes

				}

				//determine if this is 1/100 and display update if needed
				if($t % $step == 0){

					// Calculate the percentage
					$percent = intval($t/$totalcount * 100)."%";

					// Javascript for updating the progress bar and information
					echo '<script language="javascript">
					document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
					document.getElementById("information").innerHTML="'.$percent.' loaded.";
					</script>';

					// This is for the buffer achieve the minimum size in order to flush data
					echo str_repeat(' ',1024*128);

					// Send output to browser immediately
					flush();

				}

				$t++;

			}//end if geometry
		}//end foreach json

		if($tagsfound=="yes"){

			// Javascript for updating the progress bar and information
			echo '<script language="javascript">
			document.getElementById("progress").innerHTML="<div style=\"width:50%;background-color:#ddd;\">&nbsp;</div>";
			document.getElementById("information").innerHTML="Building Tag Relationships...";
			</script>';

			// This is for the buffer achieve the minimum size in order to flush data
			echo str_repeat(' ',1024*128);

			// Send output to browser immediately
			flush();

			

			$pid = $strabo->straboIDToID($projectid,"Project");

			//Query to get spot ids
			//17176789102895

			$safe_projectid = addslashes($projectid);
			$sids = array();
			$rows = $neodb->get_results("match (p:Project)-[HAS_DATASET]->(d:Dataset)-[HAS_SPOT]->(s:Spot) where p.id = $safe_projectid return distinct(s.id) as spotid;");
			foreach($rows as $row){
				$spotid=$row->get("spotid");
				$sids[] = $spotid;
			}

			$existingtags = $smodel->projectvars['json_tags'];
			if($existingtags != ""){
				$existingtags = json_decode($existingtags);

				$newfixtags = array();
				foreach($existingtags as $thistag){
					$goodspots = array();
					$oldspots = $thistag->spots;
					foreach($oldspots as $thisspot){
						if(in_array($thisspot, $sids)) $goodspots[] = $thisspot;
					}
					$thistag->spots = $goodspots;

					if(count($thistag->spots) > 0){
						$newfixtags[] = $thistag;
					}
				}

				$smodel->projectvars['json_tags'] = json_encode($newfixtags);

			}

			//update project

			$neodb->updateNode($pid,json_encode($smodel->projectvars),"Project");
			$strabo->buildProjectRelationships($projectid);
		}

		//now find centers for dataset and project
		$strabo->setProjectCenter($projectid);
		$strabo->setDatasetCenter($datasetid);

		//also build PG dataset
		$strabo->buildPgDataset($datasetid); //need to re-implement JMA 02282020

		$percent = 100;

		echo '<script language="javascript">
		document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
		document.getElementById("information").innerHTML="Shapefile Load Complete.";
		</script>';

	} catch (ShapeFileException $e) {
		exit('Error '.$e->getCode().': '.$e->getMessage());
	}

	// Tell user that the process is completed

	$timetook = time()-$starttime;

	$persamp = $timetook/$totalcount;

	echo '<script language="javascript">document.getElementById("information").innerHTML="<p>Shapefile Load Complete.</p><p>'.$t.' spots loaded in '.$timetook.' seconds.</p><p>('.$persamp.' per spot)</p>"</script>';

	if($shapefilearray!=""){

		$sfarray=explode("***",$shapefilearray);
		$sfname=$sfarray[0];

		$showname = basename($sfname);

		$newhtml="<form method=\"POST\"><input type=\"hidden\" name=\"shapefilearray\" value=\"$shapefilearray\"><input type=\"hidden\" name=\"randnum\" value=\"$randnum\"><input type=\"hidden\" name=\"projectid\" value=\"$projectid\"><input type=\"hidden\" name=\"arcid\" value=\"$arcid\"><input type=\"hidden\" name=\"featuretype\" value=\"$featuretype\"><input type=\"hidden\" name=\"filecolumns\" value=\"$filecolumns\"><input type=\"hidden\" name=\"fixedcolumns\" value=\"$fixedcolumns\"><input type=\"hidden\" name=\"epsg\" value=\"$epsg\"><input type=\"hidden\" name=\"userpkey\" value=\"$userpkey\"><input type=\"hidden\" name=\"datasetname\" value=\"$datasetname\"><input type=\"hidden\" name=\"spotnameprefix\" value=\"$spotnameprefix\"><input type=\"submit\" name=\"indsubmit\" value=\"Continue to load $showname\"></form>";

	}else{

		$newhtml = "<a href=\"my_field_data\">Continue</a>";

	}

	echo '<script language="javascript">document.getElementById("continuediv").innerHTML=\''.$newhtml.'\'</script>';

	if($arcid!=""){
	}

	?>
					<div class="bottomSpacer"></div>

					</div>
				</div>
	<?php

	include 'includes/mfooter.php';

	exit();
}

if($error!=""){
	$error = "<div style=\"color:#e44c65;\">$error</div><br>";
}

include 'includes/mheader.php';

?>
			<!-- Main -->
				<div id="main" class="wrapper style1">
					<div class="container">

						<header class="major">
							<h2>Shapefile Upload</h2>
						</header>
<?php

//check for projects here
$projectrows = $strabo->getMyProjects();
$projectrows = $projectrows["projects"];

if(count($projectrows)==0){
	?>
	No Projects found. You must create a project in which to store your shapefile. Click <a href="new_project">here</a> to add project.
	<?php
	exit();
}

?>

	<script type="text/javascript">

	function formvalidate(){
		var errors='';

		var e = document.getElementById("projectid");
		var projectid = e.options[e.selectedIndex].value;

		if(projectid=="" || projectid==null){errors=errors+'Project must be selected.\n';}

		if(document.forms["uploadform"]["zipfile"].value=="" || document.forms["uploadform"]["zipfile"].value==null){errors=errors+'Shapefile must be provided.\n';}

		if(errors!="" && errors!=null){
			alert(errors);
			return false;
		}
	}

	function doClickFile() {

		let FC = document.getElementById("zipfile");
		FC.click();

	}

	function changeFileName() {
		let fullPath = document.getElementById("zipfile").value;
		var filename = fullPath.replace(/^.*[\\/]/, '')
		document.getElementById("filename").value = filename;
	}

	</script>

	<?php echo $error?>

	<form name="uploadform" method="POST" onsubmit="return formvalidate();" enctype="multipart/form-data">

<div class="row gtr-uniform gtr-50">
	<div class="col-12">
		<h3>Project:</h3>
	</div>
	<div class="col-12">
		<select name="projectid" id="projectid">
			<option value="">Select...
			<?php
			foreach($projectrows as $pr){
			?>
			<option value="<?php echo $pr["id"]?>"><?php echo $pr["name"]?>
			<?php
			}
			?>
		</select>
	</div>
	<div class="col-12">
		<h3>Shapefile (.zip):</h3>
	</div>
	<div class="col-12">
		<input type="text" id="filename" placeholder="Choose File..." onclick="doClickFile();" readonly>
	</div>
	<div class="col-12">
		<ul class="actions">
			<li><input class="primary" type="submit" name="submitfile" value="Submit"></li>
			<li><input class="primary" type="reset" value="Reset"></li>
		</ul>
	</div>

</div>

<!--

<input type="file" id="docFile" class="formControl" onchange="exper_uploadSampleFile(0)">

-->

		<input type="file" id="zipfile" name="zipfile" accept=".zip" style="display:none;" onchange="changeFileName();">

		<input type="hidden" name="filename">
	</form>
					<div class="bottomSpacer"></div>

					</div>
				</div>
<?php
include 'includes/mfooter.php';
?>
