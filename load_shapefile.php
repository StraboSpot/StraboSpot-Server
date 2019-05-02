<?
//14878844758700
//1487884568
// id is unix time + 4 rand digits




include("logincheck.php");

/*
include("includes/straboClasses/straboModelClass.php");

$sm = new straboModelClass();

//$sm->dumpVar($sm->controlledlist);
//$sm->dumpVar($sm->fields);


$foundvars=array();
foreach($sm->fields as $var){
	$type=$var['type'];
	$name=$var['name'];
	if(!in_array($type,$foundvars) && substr($type,0,10)!="select_one" ){
		$foundvars[]=$type;
	}
}

$sm->dumpVar($foundvars);


exit();
*/



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
			
			$arcid=$_POST['arcid'];
			$frow = $db->get_row("select * from arcfiles where arcid='$arcid'");

			$pos = strpos($frow->name,".zip");
			
			if($pos === false) {
			
				include 'includes/header.php';
		
				echo "<h2>Load Shapefile</h2><br>";		

				echo "Wrong file type detected. File must be zip archive.";
			
				include 'includes/footer.php';
				exit();
			
			}
			
		}
	}
	

	if($error==""){
		//unzip file here
		//var_dump($_FILES);exit();
		
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
			
			include 'includes/header.php';
			
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
			
			
			<?
			
			
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
					<td><input type="checkbox" name="shapefilename<?=$sn?>" value="<?=$sf?>" checked="true"></td>
					<td><?=$showname?></td>
					</tr>

					<?
				
					$sn++;
				}

			?>
			</table><br>

			<input type="hidden" name="randnum" value="<?=$randnum?>">
			<input type="hidden" name="projectid" value="<?=$projectid?>">
			<input type="hidden" name="arcid" value="<?=$arcid?>">
			
			<input type="submit" name="indsubmit" value="Submit">
			
			</form>
			
			<?
			
			include 'includes/footer.php';
			exit();
		}else{
			$putinone="yes";
		}
	
	}


}//end if submit





//check columns here for errors

if($_POST['columnsubmit']!=""){

	include_once("includes/straboClasses/straboModelClass.php");

	$sm = new straboModelClass();

	//$sm->dumpVar($sm->fields);exit();
	
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
	
	$sm->setUserColumns($fixedcolumns);

	if(file_exists("ogrtemp/$randnum")){
		unlink("ogrtemp/$randnum");
	}

	exec("ogr2ogr -f GeoJSON ogrtemp/$randnum $shapefilename 2>&1",$results);

	$error = "";
	
	if(count($results)>0){
		$error = implode("<br>",$results);
		echo $error;
	}
	
	//load json
	$shapefilejson = file_get_contents("ogrtemp/$randnum");
	$shapefilejson = json_decode($shapefilejson,true);
	$shapefilejson = $shapefilejson['features'];
	$totalcount = count($shapefilejson);
	if(file_exists("ogrtemp/$randnum")) unlink("ogrtemp/$randnum");
	
	//check shapefilejson here
	
	//$sm->dumpVar($sm->columns_with_other_option);
	
	//foreach row, check each column and record error if necessary
	foreach($shapefilejson as $j){
		$properties = $j["properties"];
		foreach($properties as $key=>$value){
			if($value!=""){
				$strabocol = $sm->userColumnToStraboColumn($key);
				if($strabocol){
					if(in_array($strabocol,$sm->columns_with_other_option)){ //don't check columns that have "other" option
					}elseif($sm->isControlled($strabocol)){
						if(!$newval = $sm->fitsControlled($strabocol,$value)){
							$vocaberror[$strabocol]=$sm->createCVError($strabocol,$value);
						}
					}elseif($sm->isNumericTyped($strabocol)){
						if(!is_numeric($value)){
							$vocaberror[$strabocol]=$sm->createNumericTypeError($strabocol,$value);
						}
					}
				}
			}
		}

	}

	if($vocaberror){
		$vocaberror=$sm->implodeCVError($vocaberror);
	}
	
	/*
	if($newvalue = $sm->fitsControlled("planar_orientation_feature_type","bedding")){
		echo "newval: $newvalue yes";
	}else{
		echo "no";
	}exit();
	*/
	
	//$sm->dumpVar($sm->filevocab);exit();
	
	//echo "vocaberror: $vocaberror";
	
	//exit();

	//$vocaberror = "error totalcount: $totalcount";

	//$sm->dumpVar($shapefilejson);exit();
	//$sm->dumpVar($sm->controlledlist);exit();
}



if($_POST['indsubmit']!="" || $putinone=="yes" || ($vocaberror!="" && $_POST['columnsubmit']!="")){

	//print_r($_POST);exit();
	
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



	//echo "shapefilename: $shapefilename<br>";
	//echo "shapefilearray: $shapefilearray<br>";





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
	
	//echo "prjfilename $prjfilename";

	if(file_exists($prjfilename)){

		//now we have a custom python script instead of calling a web service
		exec("/home/jasonash/prj2epsg.py $prjfilename",$epsg);
		$epsg=$epsg[0];
		
		/*
		echo "epsg:";
		dumpVar($epsg);
		exit();
		*/
		
		/*
		
		//use REST service to get projection
		$url = "http://prj2epsg.org/search.json";
		$headers = array(
			"Content-Type: application/json"
		);

		//$wkt=urlencode('PROJCS["NAD_1983_UTM_Zone_11N",GEOGCS["GCS_North_American_1983",DATUM["D_North_American_1983",SPHEROID["GRS_1980",6378137.0,298.257222101]],PRIMEM["Greenwich",0.0],UNIT["Degree",0.0174532925199433]],PROJECTION["Transverse_Mercator"],PARAMETER["False_Easting",500000.0],PARAMETER["False_Northing",0.0],PARAMETER["Central_Meridian",-117.0],PARAMETER["Scale_Factor",0.9996],PARAMETER["Latitude_Of_Origin",0.0],UNIT["Meter",1.0]],VERTCS["NAD_1983",DATUM["D_North_American_1983",SPHEROID["GRS_1980",6378137.0,298.257222101]],PARAMETER["Vertical_Shift",0.0],PARAMETER["Direction",1.0],UNIT["Meter",1.0]]');
		$wkt=urlencode(file_get_contents($prjfilename));
		//$wkt=file_get_contents($prjfilename);

		$str= "?mode=wkt&terms=".$wkt;

		$rest = curl_init();
		curl_setopt($rest,CURLOPT_URL,$url.$str);
		curl_setopt($rest,CURLOPT_HTTPHEADER,$headers);
		curl_setopt($rest,CURLOPT_RETURNTRANSFER, true);
		curl_setopt($rest, CURLOPT_VERBOSE, 1);
		curl_setopt($rest, CURLOPT_HEADER, 1);
		curl_setopt($rest, CURLOPT_TIMEOUT, 10); //timeout in seconds
		$response = curl_exec($rest);
		$header_size = curl_getinfo($rest, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
		$body = json_decode($body);
		
		//echo $url.$str;exit();

		$epsg = $body->codes[0]->code;
		
		//echo "epsg: $epsg<br>"; //exit();

		if($epsg==""){
			$error.=$errordelim."Bad Projection (.prj) file or conversion timeout.";$errordelim="<br>";
		}
		
		*/


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
			
			//echo "shapetype: $shapetype";exit();
		
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




		include 'includes/header.php';

	?>

			<script type="text/javascript">

			function formvalidate(){
				//alert('hey');
			
				var index;
				var mystring="";
				var mydelim="";
		<?
		$mydelim="";
		$mystring="";
		foreach($fcarray as $fc){
			$mystring.=$mydelim.'"'.$fc.'"';
			$mydelim=",";
		}
		$mystring = "var columns = [$mystring];";
		?>
				<?=$mystring?>
				//var columns = ["period", "epoch", "notes"];
				for	(index = 0; index < columns.length; index++) {
					mystring += mydelim + columns[index] + ":" + document.forms["columnform"][columns[index]+"_select"].value;
					mydelim=";";
				} 
			
				document.forms["columnform"]["fixedcolumns"].value=mystring;

			}
	
			</script>

	<?

		if($vocaberror!=""){
			?>

			<fieldset style="border: 1px solid red; background-color:#fcf0ef; padding: 8px; padding-bottom:0px; margin: 8px 0">
				<legend style="color:red;"><strong>Error!</strong></legend>
				<div><?=$vocaberror?></div>
				<br>
			</fieldset>

			<br>



			<?
			/*
			<form action="">
				<input type="submit" value="Continue" />
			</form>

			include 'includes/footer.php';
			exit();
			*/
		}
		
		$showname = basename($shapefilename);
		
		echo "<h2>Define Columns for Shapefile:<br>$showname</h2><br>";
		//echo "<h1>Feature Type: ".ucfirst($featuretype)."</h1><br>";

		$shpname=basename($shapefilename);

		?>

		<form name="columnform" method="POST" onsubmit="return formvalidate();">
		
		
		Dataset Name: <input type="text" name="datasetname" value="<?=$shpname?>"><br>
		Spot Name Prefix: (optional) <input type="text" name="spotnameprefix" value="<?=$spotnameprefix?>" size="10">
		<br><br>

	
		Use the form below to define columns for upload:<br>
		A best guess has been performed on the file columns.<br><br>
		
		If your shapefile column does not fit into any of the<br>
		provided column names, you may select "Custom Column"<br>
		to retain the shapefile information at strabospot.org.<br>
	
		<?
		if($sm->dumpVar){
			//$sm->dumpVar($sm->usercolumns);
		}
		?>
		

		<div class="mytable">
			<table style="padding-top:20px;">
				<tr>
					<td><strong>Shapefile Column&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td><td><strong>StraboSpot Column</strong></td>
				</tr>

		<?
	
		include_once("includes/straboClasses/straboModelClass.php");

		if(!$sm){
			$sm = new straboModelClass();
		}

		foreach($fcarray as $fc){
	

		?>

				<tr>
					<td><?=$fc?></td>
					<td>
						<?=$sm->getSelect("$fc")?>
					</td>
				</tr>
		<?
		}
		?>

			</table>
			

			
		</div>
		<div style="padding-top:20px;" align="center">
			<input type="submit" name="columnsubmit" value="Submit">
		</div>
		<input type="hidden" name="shapefilename" value ="<?=$shapefilename?>">
		<input type="hidden" name="randnum" value ="<?=$randnum?>">
		<input type="hidden" name="projectid" value ="<?=$projectid?>">
		<input type="hidden" name="arcid" value ="<?=$arcid?>">
		<input type="hidden" name="featuretype" value ="<?=$featuretype?>">
		<input type="hidden" name="filecolumns" value ="<?=$filecolumns?>">
		<input type="hidden" name="epsg" value ="<?=$epsg?>">
		<input type="hidden" name="fixedcolumns" value="">
		<input type="hidden" name="shapefilearray" value="<?=$shapefilearray?>">
		</form>

		<?

		include 'includes/footer.php';

		exit();





	
	}else{
	
		//show error here
		include 'includes/header.php';
		
		
		?>
		<h2>Error:</h2><br>
		
		<div style="color:red;"><?=$error?></div><br>
		
		Please try your upload again.
		
		
		<?
		
		
		include 'includes/footer.php';
		exit();
	
	}

}







if($_POST['columnsubmit']!=""){

	//$sm->dumpVar($sm->filevocab);exit();
	
	//dprint($_POST);exit();
	
	include_once("includes/straboClasses/straboModelClass.php");

	if(!$sm){
		$sm = new straboModelClass();
	}

	//$sm->dumpVar($_POST);exit();

	include 'includes/header.php';
		
	?>

	<h2>Loading Shapefile...</h2><br>

	<div id="progress" style="width:500px;border:1px solid #ccc;"></div>
	<!-- Progress information -->
	<div id="information" style="width"></div>
	
	<div id="continuediv" style="padding-top:20px;"></div>
	
	<?
	
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
	
	//echo "fixedcolumns: $fixedcolumns<br><br>";exit();
	
	$parts=explode(";",$fixedcolumns);
	foreach($parts as $part){
		$bits=explode(":",$part);
		if($bits[1]!=""){
			$dbcols[$bits[1]]=$bits[0];
		}
	}

	$sm->setDBCols($dbcols);

	//$sm->dumpVar($sm->dbcols);

	//get project here and pass it to $sm so we can update tags
	$projectvars = $neodb->getNode("match (p:Project) where p.id=$projectid and p.userpkey=$userpkey return p");
	$sm->setProjectVars($projectvars);

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

	exec("ogr2ogr -f GeoJSON ogrtemp/$randnum $shapefilename 2>&1",$results);

	$error = "";
	
	if(count($results)>0){
		$error = implode("<br>",$results);
		echo $error;
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
			$datasetid=$sm->getId();
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

		//echo "datasetid: $datasetid";

		foreach($json as $onesample){

			$thisnewid = $sm->getId();
			
			$putinfeature="yes";

			$newshapetype=$shapetype;
			if($newshapetype > 10){$newshapetype = $newshapetype - 10;}

			$geometry = $onesample['geometry'];
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

					$fixedgeom=$db->get_var("SELECT ST_AsText(ST_Transform(ST_GeomFromText('$geom',$epsg),4326)) as geom;");
			
				}else{
			
					$fixedgeom="empty";
			
				}
			
			}

			if($fixedgeom!=""){
				$mywkt = geoPHP::load($fixedgeom,"wkt");
				$mygeometry = $mywkt->out('json');
			}

			$onesampleproperties = $onesample['properties'];
			
			$sm->setSampleProperties($onesampleproperties);
			
			//$sm->dumpVar($sm->sampleproperties);exit();

			/***********************************
			    Get spot level properties
			***********************************/
			$spotvars = $sm->get_vars("spot");
			foreach($spotvars as $key=>$value){
				$value = $sm->fixCast($value);
				eval("\$injson['properties']['$key']=\$value;");
			}
			
			/***********************************
			    Get orientation data
			***********************************/
			unset($planarorientation);
			$planarorientation=array();
			if($sm->hasPlanarOrientationData()){
				$planarorientation["id"]=$sm->getId();
				$planarorientation["type"]="planar_orientation";
				$planar_orientation_vars = $sm->get_vars("planar_orientation");

				foreach($planar_orientation_vars as $key=>$value){
					if($key=="feature_type"){
						if($newvalue = $sm->fitsControlled("planar_orientation_feature_type",$value)){
							$planarorientation['feature_type']=$newvalue;
						}else{
							if(trim($value=="")){$value="not given";}$oothers.="planar value: $value<br>";
							$planarorientation['feature_type']="other";
							$planarorientation['other_feature']=$value;
						}
					}elseif($key=="movement"){
						if($newvalue = $sm->fitsControlled("planar_orientation_movement",$value)){
							$planarorientation['movement']=$newvalue;
						}else{
							$planarorientation['movement']="other";
							$planarorientation['other_movement']=$value;
							
						}
					}else{
						$value = $sm->fixCast($value);
						eval("\$planarorientation['$key']=\$value;");
					}
				}
				
				if($planarorientation['feature_type']==""){ $planarorientation['feature_type']="other"; $planarorientation['other_feature']="not given"; }
				//if($planarorientation['label']=="") $planarorientation['label']="no label";
				
				//$sm->dumpVar($planarorientation);
				
				if($sm->hasLinearOrientationData()){
					unset($linearorientation);
					$linearorientation=array();
					$linearorientation["id"]=$sm->getId();
					$linearorientation["type"]="linear_orientation";
					$linear_orientation_vars = $sm->get_vars("linear_orientation");
					foreach($linear_orientation_vars as $key=>$value){
						if($key=="feature_type"){
							if($newvalue = $sm->fitsControlled("linear_orientation_feature_type",$value)){
								$linearorientation['feature_type']=$newvalue;
							}else{
								if(trim($value=="")){$value="not given";}$oothers.="inside linear value: $value<br>";
								$linearorientation['feature_type']="other";
								$linearorientation['other_feature']=$value;
							}
						}else{
							$value = $sm->fixCast($value);
							eval("\$linearorientation['$key']=\$value;");
						}
					}
					
					if($linearorientation['feature_type']==""){ $linearorientation['feature_type']="other"; $linearorientation['other_feature']="not given"; }
					//if($linearorientation['label']=="") $linearorientation['label']="no label";

					//$sm->dumpVar($linearorientation);
					
					$planarorientation["associated_orientation"][]=$linearorientation;
				}
				
				$injson['properties']["orientation_data"][]=$planarorientation;
			
			}elseif($sm->hasLinearOrientationData()){
			
				unset($linearorientation);
				$linearorientation=array();
				$linearorientation["id"]=$sm->getId();
				$linearorientation["type"]="linear_orientation";
				$linear_orientation_vars = $sm->get_vars("linear_orientation");
				foreach($linear_orientation_vars as $key=>$value){
					if($key=="feature_type"){
						if($newvalue = $sm->fitsControlled("linear_orientation_feature_type",$value)){
							$linearorientation['feature_type']=$newvalue;
						}else{
							if(trim($value=="")){$value="not given";}$oothers.="outside linear value: $value<br>";
							$linearorientation['feature_type']="other";
							$linearorientation['other_feature']=$value;
						}
					}else{
						$value = $sm->fixCast($value);
						eval("\$linearorientation['$key']=\$value;");
					}
				}
				
				if($linearorientation['feature_type']==""){ $linearorientation['feature_type']="other"; $linearorientation['other_feature']="not given"; }
				//if($linearorientation['label']=="") $linearorientation['label']="no label";
				
				$injson['properties']["orientation_data"][]=$linearorientation;

			}
			
			unset($tabularzoneorientation);
			$tabularzoneorientation=array();
			if($sm->hasTabularZoneOrientationData()){
				$tabularzoneorientation["id"]=$sm->getId();
				$tabularzoneorientation["type"]="tabular_orientation";
				$tabular_zone_orientation_vars = $sm->get_vars("tabular_zone_orientation");
				foreach($tabular_zone_orientation_vars as $key=>$value){
					if($key=="facing_defined_by"){
						if($newvalue = $sm->fitsControlled("tabular_zone_orientation_facing_defined_by",$value)){
							$tabularzoneorientation['facing_defined_by']=$newvalue;
						}else{
							$tabularzoneorientation['facing_defined_by']="other";
							$tabularzoneorientation['other_facing_defined_by']=$value;
						}
					}elseif($key=="feature_type"){
						if($newvalue = $sm->fitsControlled("tabular_zone_orientation_feature_type",$value)){
							$tabularzoneorientation['feature_type']=$newvalue;
						}else{
							$tabularzoneorientation['feature_type']="other";
							$tabularzoneorientation['other_feature']=$value;
						}
					}elseif($key=="intrusive_body_type"){
						if($newvalue = $sm->fitsControlled("tabular_zone_orientation_intrusive_body_type",$value)){
							$tabularzoneorientation['intrusive_body_type']=$newvalue;
						}else{
							$tabularzoneorientation['intrusive_body_type']="other";
							$tabularzoneorientation['other_intrusive_body']=$value;
						}
					}elseif($key=="vein_fill"){
						if($newvalue = $sm->fitsControlled("tabular_zone_orientation_vein_fill",$value)){
							$tabularzoneorientation['vein_fill']=$newvalue;
						}else{
							$tabularzoneorientation['vein_fill']="other";
							$tabularzoneorientation['other_vein_fill']=$value;
						}
					}elseif($key=="vein_array"){
						if($newvalue = $sm->fitsControlled("tabular_zone_orientation_vein_array",$value)){
							$tabularzoneorientation['vein_array']=$newvalue;
						}else{
							$tabularzoneorientation['vein_array']="other";
							$tabularzoneorientation['other_vein_array']=$value;
						}
					}elseif($key=="fault_or_sz"){
						if($newvalue = $sm->fitsControlled("tabular_zone_orientation_fault_or_sz",$value)){
							$tabularzoneorientation['fault_or_sz']=$newvalue;
						}else{
							$tabularzoneorientation['fault_or_sz']="other";
							$tabularzoneorientation['other_fault_or_sz']=$value;
						}
					}elseif($key=="movement"){
						if($newvalue = $sm->fitsControlled("tabular_zone_orientation_movement",$value)){
							$tabularzoneorientation['movement']=$newvalue;
						}else{
							$tabularzoneorientation['movement']="other";
							$tabularzoneorientation['other_movement']=$value;
						}
					}elseif($key=="movement_justification"){
						if($newvalue = $sm->fitsControlled("tabular_zone_orientation_movement_justification",$value)){
							$tabularzoneorientation['movement_justification']=$newvalue;
						}else{
							$tabularzoneorientation['movement_justification']="other";
							$tabularzoneorientation['other_movement_justification']=$value;
						}
					}elseif($key=="dir_indicators"){
						if($newvalue = $sm->fitsControlled("tabular_zone_orientation_dir_indicators",$value)){
							$tabularzoneorientation['dir_indicators']=$newvalue;
						}else{
							$tabularzoneorientation['dir_indicators']="other";
							$tabularzoneorientation['other_dir_indicators']=$value;
						}
					}elseif($key=="enveloping_surface_geometry"){
						if($newvalue = $sm->fitsControlled("tabular_zone_orientation_enveloping_surface_geometry",$value)){
							$tabularzoneorientation['enveloping_surface_geometry']=$newvalue;
						}else{
							$tabularzoneorientation['enveloping_surface_geometry']="other";
							$tabularzoneorientation['other_surface_geometry']=$value;
						}
					}else{
						$value = $sm->fixCast($value);
						eval("\$tabularzoneorientation['$key']=\$value;");
					}
				}

				if($tabularzoneorientation['feature_type']=="") $tabularzoneorientation['feature_type']="other";
				//if($tabularzoneorientation['label']=="") $tabularzoneorientation['label']="no label";

				$injson['properties']["orientation_data"][]=$tabularzoneorientation;
			
			}

			/***********************************
			        Get 3D Structure data
			***********************************/
			if($sm->has3dStructureData()){

				if($sm->hasFabricData()){
				unset($thisstruct);
				$thisstruct=array();
				$thisstruct["id"]=$sm->getId();
				$thisstruct["type"]="fabric";
				$struct_vars = $sm->get_vars("fabric");
				foreach($struct_vars as $key=>$value){
					if($key=="feature_type"){
						if($newvalue = $sm->fitsControlled("fabric_feature_type",$value)){
							$thisstruct['feature_type']=$newvalue;
						}else{
							$thisstruct['feature_type']="other_fabric";
							$thisstruct['other_description']=$value;
						}
					}else{
						$value = $sm->fixCast($value);
						eval("\$thisstruct['$key']=\$value;");
					}
				}
				//if($thisstruct['label']=="") $thisstruct['label']="no label";
				$injson['properties']["_3d_structures"][]=$thisstruct;
				}

				if($sm->hasFoldData()){
				unset($thisstruct);
				$thisstruct=array();
				$thisstruct["id"]=$sm->getId();
				$thisstruct["type"]="fold";
				$struct_vars = $sm->get_vars("fold");
				foreach($struct_vars as $key=>$value){
					if($key=="feature_type"){
						if($newvalue = $sm->fitsControlled("fold_feature_type",$value)){
							$thisstruct['feature_type']=$newvalue;
						}else{
							$thisstruct['feature_type']="other";
							$thisstruct['other_dominant_geometry']=$value;
						}
					}else{
						$value = $sm->fixCast($value);
						eval("\$thisstruct['$key']=\$value;");
					}
				}
				//if($thisstruct['label']=="") $thisstruct['label']="no label";
				$injson['properties']["_3d_structures"][]=$thisstruct;
				}

				if($sm->hasTensorData()){
				unset($thisstruct);
				$thisstruct=array();
				$thisstruct["id"]=$sm->getId();
				$thisstruct["type"]="tensor";
				$struct_vars = $sm->get_vars("tensor");
				foreach($struct_vars as $key=>$value){
					if($key=="ellipsoid_type"){
						if($newvalue = $sm->fitsControlled("tensor_ellipsoid_type",$value)){
							$thisstruct['ellipsoid_type']=$newvalue;
						}else{
							$thisstruct['ellipsoid_type']="other";
							$thisstruct['other_ellipsoid_type']=$value;
						}
					}elseif($key=="non_ellipsoidal_type"){
						if($newvalue = $sm->fitsControlled("tensor_non_ellipsoidal_type",$value)){
							$thisstruct['non_ellipsoidal_type']=$newvalue;
						}else{
							$thisstruct['non_ellipsoidal_type']="other";
							$thisstruct['other_non_ellipsoidal_type']=$value;
						}
					}elseif($key=="ellipse_type"){
						if($newvalue = $sm->fitsControlled("tensor_ellipse_type",$value)){
							$thisstruct['ellipse_type']=$newvalue;
						}else{
							$thisstruct['ellipse_type']="other";
							$thisstruct['other_ellipse_type']=$value;
						}
					}else{
						$value = $sm->fixCast($value);
						eval("\$thisstruct['$key']=\$value;");
					}
				}
				//if($thisstruct['label']=="") $thisstruct['label']="no label";
				$injson['properties']["_3d_structures"][]=$thisstruct;
				}

				if($sm->hasOther3dStructureData()){
				unset($thisstruct);
				$thisstruct=array();
				$thisstruct["id"]=$sm->getId();
				$thisstruct["type"]="other";
				$struct_vars = $sm->get_vars("other_3d_structure");
				foreach($struct_vars as $key=>$value){
					if($key=="feature_type"){
						if($newvalue = $sm->fitsControlled("other_3d_structure_feature_type",$value)){
							$thisstruct['feature_type']=$newvalue;
						}else{
							$thisstruct['feature_type']="other_3d_structure";
							$thisstruct['other_structure_description']=$value;
						}
					}else{
						$value = $sm->fixCast($value);
						eval("\$thisstruct['$key']=\$value;");
					}
				}
				//if($thisstruct['label']=="") $thisstruct['label']="no label";
				$injson['properties']["_3d_structures"][]=$thisstruct;
				}


			}

			/***********************************
			        Get Rock Unit data
			***********************************/
			if($sm->hasRockUnitData()){
				unset($thisstruct);
				$thisstruct=array();
				$struct_vars = $sm->get_vars("rock_unit");
				foreach($struct_vars as $key=>$value){
					if($key=="sediment_type"){
						if($newvalue = $sm->fitsControlled("rock_unit_sediment_type",$value)){
							$thisstruct['sediment_type']=$newvalue;
						}else{
							$thisstruct['sediment_type']="other";
							$thisstruct['other_sediment_type']=$value;
						}
					}elseif($key=="sedimentary_rock_type"){
						if($newvalue = $sm->fitsControlled("rock_unit_sedimentary_rock_type",$value)){
							$thisstruct['sedimentary_rock_type']=$newvalue;
						}else{
							$thisstruct['sedimentary_rock_type']="other";
							$thisstruct['other_sedimentary_rock_type']=$value;
						}
					}elseif($key=="volcanic_rock_type"){
						if($newvalue = $sm->fitsControlled("rock_unit_volcanic_rock_type",$value)){
							$thisstruct['volcanic_rock_type']=$newvalue;
						}else{
							$thisstruct['volcanic_rock_type']="other";
							$thisstruct['other_volcanic_rock_type']=$value;
						}
					}elseif($key=="plutonic_rock_types"){
						if($newvalue = $sm->fitsControlled("rock_unit_plutonic_rock_types",$value)){
							$thisstruct['plutonic_rock_types']=$newvalue;
						}else{
							$thisstruct['plutonic_rock_types']="other";
							$thisstruct['other_plutonic_rock_type']=$value;
						}
					}elseif($key=="metamorphic_rock_types"){
						if($newvalue = $sm->fitsControlled("rock_unit_metamorphic_rock_types",$value)){
							$thisstruct['metamorphic_rock_types']=$newvalue;
						}else{
							$thisstruct['metamorphic_rock_types']="other";
							$thisstruct['other_metamorphic_rock_type']=$value;
						}
					}elseif($key=="metamorphic_grade"){
						if($newvalue = $sm->fitsControlled("rock_unit_metamorphic_grade",$value)){
							$thisstruct['metamorphic_grade']=$newvalue;
						}else{
							$thisstruct['metamorphic_grade']="other";
							$thisstruct['other_metamorphic_grade']=$value;
						}
					}elseif($key=="epoch"){
						if($newvalue = $sm->fitsControlled("rock_unit_epoch",$value)){
							$thisstruct['epoch']=$newvalue;
						}else{
							$thisstruct['epoch']="other";
							$thisstruct['other_epoch']=$value;
						}
					}else{
						$value = $sm->fixCast($value);
						eval("\$thisstruct['$key']=\$value;");
					}
				}
				
				if($thisstruct['rock_type']=="igneous"){
					if($thisstruct['igneous_rock_class']==""){
						$thisstruct['igneous_rock_class']="";
					}
				}
				
				if($putinfeature=="yes"){
					$sm->addRockUnitTag($thisstruct,$thisnewid);
					$tagsfound="yes";
				}
			}
			
			/***********************************
			      Get Trace data
			***********************************/
			if($sm->hasTraceData()){
				unset($thisstruct);
				$thisstruct=array();
				//$thisstruct["id"]=$sm->getId(); //no id needed for trace?
				$thisstruct["trace_feature"]=true;
				$struct_vars = $sm->get_vars("trace");
				
				//$sm->dumpVar($struct_vars);
				
				foreach($struct_vars as $key=>$value){
					if($key=="quality"){
						if($newvalue = $sm->fitsControlled("trace_trace_quality",$value)){
							$thisstruct['trace_quality']=$newvalue;
						}else{
							$thisstruct['trace_quality']="other";
							$thisstruct['other_trace_quality']=$value;
						}
					}elseif($key=="contact_type"){
						if($newvalue = $sm->fitsControlled("trace_contact_type",$value)){
							$thisstruct['contact_type']=$newvalue;
						}else{
							$thisstruct['contact_type']="other";
							$thisstruct['other_contact_type']=$value;
						}
					}elseif($key=="depositional_contact_type"){
						if($newvalue = $sm->fitsControlled("trace_depositional_contact_type",$value)){
							$thisstruct['depositional_contact_type']=$newvalue;
						}else{
							$thisstruct['depositional_contact_type']="other";
							$thisstruct['other_depositional_type']=$value;
						}
					}elseif($key=="intrusive_contact_type"){
						if($newvalue = $sm->fitsControlled("trace_intrusive_contact_type",$value)){
							$thisstruct['intrusive_contact_type']=$newvalue;
						}else{
							$thisstruct['intrusive_contact_type']="other";
							$thisstruct['other_intrusive_contact']=$value;
						}
					}elseif($key=="metamorphic_contact_type"){
						if($newvalue = $sm->fitsControlled("trace_metamorphic_contact_type",$value)){
							$thisstruct['metamorphic_contact_type']=$newvalue;
						}else{
							$thisstruct['metamorphic_contact_type']="other";
							$thisstruct['other_metamorphic_contact']=$value;
						}
					}elseif($key=="shear_sense"){
						if($newvalue = $sm->fitsControlled("trace_shear_sense",$value)){
							$thisstruct['shear_sense']=$newvalue;
						}else{
							$thisstruct['shear_sense']="other";
							$thisstruct['other_shear_sense']=$value;
						}
					}elseif($key=="other_structural_zones"){
						if($newvalue = $sm->fitsControlled("trace_other_structural_zones",$value)){
							$thisstruct['other_structural_zones']=$newvalue;
						}else{
							$thisstruct['other_structural_zones']="other";
							$thisstruct['other_other_structural_zone']=$value;
						}
					}elseif($key=="fold_type"){
						if($newvalue = $sm->fitsControlled("trace_fold_type",$value)){
							$thisstruct['fold_type']=$newvalue;
						}else{
							$thisstruct['fold_type']="other";
							$thisstruct['other_fold_type']=$value;
						}
					}elseif($key=="attitude"){
						if($newvalue = $sm->fitsControlled("trace_fold_attitude",$value)){
							$thisstruct['attitude']=$newvalue;
						}else{
							$thisstruct['attitude']="other";
							$thisstruct['other_attitude']=$value;
						}
					}elseif($key=="geomorphic_feature"){
						if($newvalue = $sm->fitsControlled("trace_geomorphic_feature",$value)){
							$thisstruct['geomorphic_feature']=$newvalue;
						}else{
							$thisstruct['geomorphic_feature']="other";
							$thisstruct['other_geomorphic_feature']=$value;
						}
					}elseif($key=="antropogenic_feature"){
						if($newvalue = $sm->fitsControlled("trace_antropogenic_feature",$value)){
							$thisstruct['antropogenic_feature']=$newvalue;
						}else{
							$thisstruct['antropogenic_feature']="other";
							$thisstruct['other_antropogenic_feature']=$value;
						}
					}elseif($key=="other_feature"){
						if($newvalue = $sm->fitsControlled("trace_other_feature",$value)){
							$thisstruct['other_feature']=$newvalue;
						}else{
							$thisstruct['other_feature']="other";
							$thisstruct['other_other_feature']=$value;
						}
					}elseif($key=="character"){
						if($newvalue = $sm->fitsControlled("trace_trace_character",$value)){
							$thisstruct['character']=$newvalue;
						}else{
							$thisstruct['character']="other";
							$thisstruct['other_character']=$value;
						}
					}elseif($key=="type"){
						if($newvalue = $sm->fitsControlled("trace_trace_type",$value)){
							$thisstruct['trace_type']=$newvalue;
						}else{
							$thisstruct['trace_type']="other_feature";
							$thisstruct['other_feature']="other";
							$thisstruct['other_other_feature']=$value;
						}
					}else{
						$value = $sm->fixCast($value);
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
			if($sm->hasOtherFeatureData()){
				unset($thisstruct);
				$thisstruct=array();
				$thisstruct["id"]=$sm->getId();
				$struct_vars = $sm->get_vars("other_features");
				foreach($struct_vars as $key=>$value){
					$value = $sm->fixCast($value);
					eval("\$thisstruct['$key']=\$value;");
				}
				$injson['properties']["other_features"][]=$thisstruct;
			}

			/***********************************
			        Get Sample data
			***********************************/
			if($sm->hasSampleData()){
				unset($thisstruct);
				$thisstruct=array();
				$thisstruct["id"]=$sm->getId();	
				$struct_vars = $sm->get_vars("sample");
				foreach($struct_vars as $key=>$value){
					if($key=="material_type"){
						if($newvalue = $sm->fitsControlled("sample_material_type",$value)){
							$thisstruct['material_type']=$newvalue;
						}else{
							$thisstruct['material_type']="other";
							$thisstruct['other_material_type']=$value;
						}
					}elseif($key=="main_sampling_purpose"){
						if($newvalue = $sm->fitsControlled("sample_main_sampling_purpose",$value)){
							$thisstruct['main_sampling_purpose']=$newvalue;
						}else{
							$thisstruct['main_sampling_purpose']="other";
							$thisstruct['other_sampling_purpose']=$value;
						}
					}else{
						$value = $sm->fixCast($value);
						eval("\$thisstruct['$key']=\$value;");
					}
				}
				$injson['properties']["samples"][]=$thisstruct;
			}



			/***********************************
			         Get custom fields
			***********************************/
			if($sm->hasCustomFields()){
				unset($customfields);
				$customfields=array();
				$customfieldvars = $sm->get_vars("sfcustom");
				$injson['properties']["custom_fields"]=$customfieldvars;
			}

			/***********************************
			        Get Tag data
			***********************************/
			if($sm->hasTagData()){
				unset($thisstruct);
				$thisstruct=array();
				$struct_vars = $sm->get_vars("tag");
				foreach($struct_vars as $key=>$value){
					$value = $sm->fixCast($value);
					eval("\$thisstruct['$key']=\$value;");
				}
				
				if($putinfeature=="yes"){
					$sm->addTag($thisstruct,$thisnewid);
					$tagsfound="yes";
				}
			}





			$mytime=time();
			$myrand = rand(1000,9999);

			$injson_straboid= $sm->getId();
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

			//$sm->dumpVar($injson);

			if($putinfeature=="yes"){

				$strabo->insertSpot($injson);
				$strabo->addSpotToDataset($datasetid,$thisnewid);

			}else{//end if putinfeature == yes

				//dprint($onesample);

			}

			//$neodb->dumpVar($injson);exit();

			//exit();

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

			//if($t > 200){ echo $oothers; break; }

			$t++;
		}
		

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

			//update project
			$pid = $strabo->straboIDToID($projectid,"Project");
			$neodb->updateNode($pid,json_encode($sm->projectvars),"Project");
			$strabo->buildProjectRelationships($projectid);
		}
		
		
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
	
	echo '<script language="javascript">document.getElementById("information").innerHTML="Shapefile Load Complete. '.$totalcount.' spots loaded in '.$timetook.' seconds. ('.$persamp.' per spot)"</script>';

	if($shapefilearray!=""){
	
		$sfarray=explode("***",$shapefilearray);
		$sfname=$sfarray[0];
		
		$showname = basename($sfname);
		
		$newhtml="<form method=\"POST\"><input type=\"hidden\" name=\"shapefilearray\" value=\"$shapefilearray\"><input type=\"hidden\" name=\"randnum\" value=\"$randnum\"><input type=\"hidden\" name=\"projectid\" value=\"$projectid\"><input type=\"hidden\" name=\"arcid\" value=\"$arcid\"><input type=\"hidden\" name=\"featuretype\" value=\"$featuretype\"><input type=\"hidden\" name=\"filecolumns\" value=\"$filecolumns\"><input type=\"hidden\" name=\"fixedcolumns\" value=\"$fixedcolumns\"><input type=\"hidden\" name=\"epsg\" value=\"$epsg\"><input type=\"hidden\" name=\"userpkey\" value=\"$userpkey\"><input type=\"hidden\" name=\"datasetname\" value=\"$datasetname\"><input type=\"hidden\" name=\"spotnameprefix\" value=\"$spotnameprefix\"><input type=\"submit\" name=\"indsubmit\" value=\"Continue to load $showname\"></form>";

	}else{
	
		$newhtml = "<a href=\"my_data\">Shapefile Load Complete. Continue</a>";
	
	}
	
	echo '<script language="javascript">document.getElementById("continuediv").innerHTML=\''.$newhtml.'\'</script>';

	if($arcid!=""){
		//$arcpkey=$db->get_var("select pkey from arcfiles where arcid='$arcid'");
		//unlink("arcfiles/$arcpkey");
		//$db->query("delete from arcfiles where arcid='$arcid'");
	}
	
	//echo "datasetid: $datasetid";
	
	include 'includes/footer.php';

	exit();	
}




if($error!=""){
	$error = "<div style=\"color:red;\">$error</div><br>";
}

include 'includes/header.php';


//check for projects here
$projectrows = $strabo->getMyProjects();
$projectrows = $projectrows["projects"];

//$neodb->dumpVar($projectrows);exit();


if(count($projectrows)==0){
	?>
	No Projects found. You must create a project in which to store your shapefile. Click <a href="new_project">here</a> to add project.
	<?
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
	
	
	</script>

	<h2>Shapefile Upload</h2><br>

	<?=$error?>

	<form name="uploadform" method="POST" onsubmit="return formvalidate();" enctype="multipart/form-data">
		
		<table>
			<tr>
				<td>
					Project:
				</td>
				<td>
					&nbsp;&nbsp;
					<select name="projectid" id="projectid">
						<option value="">Select...
						<?
						foreach($projectrows as $pr){
						?>
						<option value="<?=$pr["id"]?>"><?=$pr["name"]?>
						<?
						}
						?>
					</select>
				</td>
			</tr>
			<tr>
				<td>Shape File (.zip):</td><td>&nbsp;&nbsp;<input type="file" name="zipfile" accept=".zip" size="40" ></td>
			</tr>
		</table>
	


		<br><br>
	
		<input type="submit" name="submitfile" value="Submit">
		
	
		<input type="hidden" name="filename">
	</form>

<?
include 'includes/footer.php';
?>
