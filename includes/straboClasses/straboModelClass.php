<?php
/**
 * File: straboModelClass.php
 * Description: straboModelClass class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


libxml_use_internal_errors(true);

class straboModelClass
{

	 public function straboModelClass(){

		 $this->columns_with_other_option = array("fabric_feature_type", "fold_feature_type", "linear_orientation_feature_type", "other_3d_structure_feature_type", "planar_orientation_feature_type", "planar_orientation_movement", "rock_unit_sediment_type", "rock_unit_sedimentary_rock_type", "rock_unit_volcanic_rock_type", "rock_unit_plutonic_rock_types", "rock_unit_metamorphic_rock_types", "rock_unit_metamorphic_grade", "rock_unit_epoch", "sample_material_type", "sample_main_sampling_purpose", "tabular_zone_orientation_facing_defined_by", "tabular_zone_orientation_feature_type", "tabular_zone_orientation_intrusive_body_type", "tabular_zone_orientation_vein_fill", "tabular_zone_orientation_vein_array", "tabular_zone_orientation_fault_or_sz", "tabular_zone_orientation_movement", "tabular_zone_orientation_movement_justification", "tabular_zone_orientation_dir_indicators", "tabular_zone_orientation_enveloping_surface_geometry", "tensor_ellipsoid_type", "tensor_non_ellipsoidal_type", "tensor_ellipse_type", "trace_trace_type", "trace_trace_quality", "trace_contact_type", "trace_depositional_contact_type", "trace_intrusive_contact_type", "trace_metamorphic_contact_type", "trace_shear_sense", "trace_other_structural_zones", "trace_fold_type", "trace_fold_attitude", "trace_geomorphic_feature", "trace_antropogenic_feature", "trace_other_feature", "trace_trace_character" );
		 $this->ignorelist = array("latitude_and_longitude","start","end");
		 $this->founditems = array(); //store found matches between shapefile vars and data model vars
		 $this->controlledlist = array();

		 $this->loadXLSFiles();
	 }

	public function dumpVar($var){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}

	function getId(){
		$string = time();
		$newtime = $string.rand(1111,9999);
		return (int)$newtime;
	}

	public function getGroupItems($var){
		$thisreturn=array();
		foreach($this->fields as $field){
			if($field['group']==$var){
				$thisreturn[]=$field;
			}
		}

		return $thisreturn;
	}

	public function hasOrientationData(){
		$hasodata=false;

		if(count($this->get_vars("planar_orientation")) > 0) $hasodata=true;
		if(count($this->get_vars("linear_orientation")) > 0) $hasodata=true;
		if(count($this->get_vars("tabular_zone_orientation")) > 0) $hasodata=true;

		return $hasodata;
	}

	public function hasPlanarOrientationData(){
		$hasodata=false;

		if(count($this->get_vars("planar_orientation")) > 0) $hasodata=true;

		return $hasodata;
	}

	public function hasLinearOrientationData(){
		$hasodata=false;

		if(count($this->get_vars("linear_orientation")) > 0) $hasodata=true;

		return $hasodata;
	}

	public function hasTabularZoneOrientationData(){
		$hasodata=false;

		if(count($this->get_vars("tabular_zone_orientation")) > 0) $hasodata=true;

		return $hasodata;
	}

	public function hasCustomFields(){
		$hasdata=false;

		if(count($this->get_vars("sfcustom")) > 0) $hasdata=true;

		return $hasdata;
	}

	public function hasTagData(){
		$hasdata=false;

		if(count($this->get_vars("tag")) > 0) $hasdata=true;

		return $hasdata;
	}

	public function hasRockUnitData(){
		$hasdata=false;

		if(count($this->get_vars("rock_unit")) > 0) $hasdata=true;

		return $hasdata;
	}

	public function hasTraceData(){
		$hasdata=false;

		if(count($this->get_vars("trace")) > 0) $hasdata=true;

		return $hasdata;
	}

	public function has3dStructureData(){
		$hasdata=false;

		if(count($this->get_vars("fabric")) > 0) $hasdata=true;
		if(count($this->get_vars("fold")) > 0) $hasdata=true;
		if(count($this->get_vars("tensor")) > 0) $hasdata=true;
		if(count($this->get_vars("other_3d_structure")) > 0) $hasdata=true;

		return $hasdata;
	}

	public function hasFabricData(){
		$hasdata=false;

		if(count($this->get_vars("fabric")) > 0) $hasdata=true;

		return $hasdata;
	}

	public function hasFoldData(){
		$hasdata=false;

		if(count($this->get_vars("fold")) > 0) $hasdata=true;

		return $hasdata;
	}

	public function hasTensorData(){
		$hasdata=false;

		if(count($this->get_vars("tensor")) > 0) $hasdata=true;

		return $hasdata;
	}

	public function hasOther3dStructureData(){
		$hasdata=false;

		if(count($this->get_vars("other_3d_structure")) > 0) $hasdata=true;

		return $hasdata;
	}

	public function hasSampleData(){
		$hasdata=false;

		if(count($this->get_vars("sample")) > 0) $hasdata=true;

		return $hasdata;
	}

	public function hasOtherFeatureData(){
		$hasdata=false;

		if(count($this->get_vars("other_feature")) > 0) $hasdata=true;

		return $hasdata;
	}

	public function isUserColumnSelected($var,$shapefilevar){

		if($this->usercolumns){
			foreach($this->usercolumns as $uc){

				if($uc["usercol"]==$shapefilevar && $uc["strabocol"]==$var){

					return true;
				}
			}
		}

		return false;
	}

	public function getSelectRows($var,$shapefilevar){

		$select="";
		$items = $this->getGroupItems("$var");

		$selecteditems = array();

		$userdefined="no";

		$lookshapefilevar = trim(strtolower($shapefilevar));

		$alreadyoutput="no";

		if($this->usercolumns){

			foreach($items as $item){
				$label = $item["label"];
				if($item["hint"]!="")$label.=" : ".$item["hint"];
				$name = $item["name"];

				if($this->isUserColumnSelected($var."_".$name,$shapefilevar)){
					$userdefined="yes";
				}

			}

			foreach($items as $item){
				$label = $item["label"];
				if($item["hint"]!="")$label.=" : ".$item["hint"];
				$name = $item["name"];

				$selected="";

				if($this->isUserColumnSelected($var."_".$name,$shapefilevar)){
					$selected=" selected";
				}

				$select.="<option value=\"".$var."_$name\"$selected>$label</option>\n";

			}

		}else{

			foreach($items as $item){
				$label = $item["label"];
				if($item["hint"]!="")$label.=" : ".$item["hint"];
				$name = $item["name"];

				$selected="";

				if(!in_array($name,$this->founditems) && $userdefined=="no"){
					if($lookshapefilevar==$name){
						$selected=" selected";
						$selecteditems[]=$name;
						$this->founditems[]=$name;
					}
				}

			}

			foreach($items as $item){
				$label = $item["label"];
				if($item["hint"]!="")$label.=" : ".$item["hint"];
				$name = $item["name"];

				$selected="";

				if(!in_array($name,$this->founditems) && $userdefined=="no"){
					if(substr(substr($name,0,strlen($lookshapefilevar))==$lookshapefilevar)){
						$selected=" selected";
						$this->founditems[]=$name;
						$selecteditems[]=$name;
					}
				}

			}

			foreach($items as $item){
				$label = $item["label"];
				if($item["hint"]!="")$label.=" : ".$item["hint"];
				$name = $item["name"];

				if(in_array($name,$selecteditems)){
					$selected=" selected";
				}else{
					$selected="";
				}

				$select.="<option value=\"".$var."_$name\"$selected>$label</option>\n";

			}

		}

		return $select;
	}

	public function setDBCols($dbcols){
		$this->dbcols=$dbcols;
	}

	public function setProjectVars($projectvars){
		$this->projectvars=$projectvars;
	}

	public function setSampleProperties($sampleproperties){
		$this->sampleproperties=$sampleproperties;
	}

	public function setUserColumns($uc){

		unset($this->usercolumns);
		$this->usercolumns=array();

		if($uc){

			$parts = explode(";",$uc);
			$arraynum=0;
			foreach($parts as $part){

				$bits = explode(":",$part);
				$usercol = $bits[0];
				$strabocol = $bits[1];

				if($usercol!="" && $strabocol!=""){
					$this->usercolumns[$arraynum]["usercol"]=$usercol;
					$this->usercolumns[$arraynum]["strabocol"]=$strabocol;
					$arraynum++;
				}

			}

		$this->setFileControlledVocabulary();

		}

	}

	public function userColumnToStraboColumn($usercolumn){
		$returnval=null;
		foreach($this->usercolumns as $u){
			if($usercolumn==$u["usercol"])$returnval=$u["strabocol"];
		}
		return $returnval;
	}

	public function setFileControlledVocabulary(){

		

		foreach($this->usercolumns as $uc){
			$strabocol = $uc["strabocol"];
			$colnum = $this->getColNum($strabocol);

			if($this->isColnumControlled($colnum)){

				foreach($this->controlledlist[$colnum] as $c){
					$this->filevocab["names"][$strabocol][]=$c["name"];
					$this->filevocab["labels"][$strabocol][]=$c["label"];
				}

			}

		}

	}

	public function fitsControlled($strabocol,$value){

		$value = strtolower(trim($value));

		foreach($this->filevocab["names"][$strabocol] as $thisval){
			$lookval = strtolower($thisval);

			if($lookval==$value){
				return $thisval;
			}
		}

		$x=0;
		foreach($this->filevocab["labels"][$strabocol] as $thisval){
			$lookval = strtolower($thisval);
			if($lookval==$value){
				return $this->filevocab["names"][$strabocol][$x];
			}
			$x++;
		}

		return false;

	}

	public function isNumericTyped($strabocol){

		foreach($this->fields as $f){
			if($f['strabolabel']==$strabocol){
				if($f['type']=="integer" || $f['type']=="decimal" ){
					return true;
				}
			}
		}

		return false;
	}

	public function createCVError($strabocol,$value){

		$label = $this->getLabelFromStraboCol($strabocol);

		$error = "The value provided for $label ($value) is not valid. See the <a href=\"controlledvocab#$strabocol\">controlled vocabulary</a> for more information.";

		return $error;
	}

	public function createNumericTypeError($strabocol,$value){

		$label = $this->getLabelFromStraboCol($strabocol);

		$error = "The value provided for $label should be numeric only. The value provided ($value) is not numeric.";

		return $error;
	}

	public function implodeCVError($vocaberror){

		foreach($vocaberror as $key=>$value){
			$thiserror .= $delim.$value; $delim="<br>\n";
		}

		return $thiserror;
	}

	public function getLabelFromStraboCol($strabocol){

		foreach($this->fields as $f){
			if($strabocol==$f["strabolabel"]) return $f["label"];
		}

		return null;

	}

	public function fixUnderscores($string){
		$parts = explode("_",$string);
		foreach($parts as $part){
			$finalstring.=ucfirst($part)." ";
		}
		return trim($finalstring);
	}

	public function isControlled($strabocol){
		$colnum = $this->getColNum($strabocol);
		return $this->isColnumControlled($colnum);
	}

	public function isColnumControlled($colnum){
		if($this->controlledlist[$colnum]!=""){
			return true;
		}else{
			return false;
		}
	}

	public function getColNum($varname){

		$num=9999999;

		foreach($this->fields as $f){
			if($f["strabolabel"]==$varname) $num = $f["num"];
		}

		return $num;

	}

	public function addTag($tag,$spotid){
		

		//find tag in project vars and update accordingly, or add if needed

		$tagname = $tag['name'];

		$json_tags = $this->projectvars["json_tags"];
		if($json_tags!=""){
			$json_tags=json_decode($json_tags,true);
		}else{
			$json_tags=array();
		}

		$fixed_tags = array();

		$tagfound="no";
		foreach($json_tags as $json_tag){
			if($json_tag["name"]==$tagname){
				//add spot to list
				array_push($json_tag["spots"],$spotid);
				$tagfound="yes";
			}

			array_push($fixed_tags,$json_tag);

		}

		if($tagfound=="no"){
			$json_tag=array();
			$json_tag["name"]=$tagname;
			$json_tag["id"]=$this->getId();
			$json_tag["type"]="other";
			$json_tag["spots"][]=$spotid;
			array_push($fixed_tags,$json_tag);
		}

		$this->projectvars["modified_timestamp"]=time();
		$this->projectvars["json_tags"]=json_encode($fixed_tags);

	}

	public function addRockUnitTag($rockunittag,$spotid){
		

		//find tag in project vars and update accordingly, or add if needed

		$tagname = $rockunittag['name'];
		if($tagname=="") $tagname="Rock Unit";

		$mapunitname = $rockunittag['map_unit_name'];
		$membername = $rockunittag['member_name'];

		$json_tags = $this->projectvars["json_tags"];
		if($json_tags!=""){
			$json_tags=json_decode($json_tags,true);
		}else{
			$json_tags=array();
		}

		$fixed_tags = array();

		$tagfound="no";
		foreach($json_tags as $json_tag){
			if($json_tag["name"]==$tagname && $json_tag["map_unit_name"]==$mapunitname && $json_tag["member_name"]==$membername ){
				//add spot to list
				if(!in_array($spotid, $json_tag["spots"])){
					array_push($json_tag["spots"],$spotid);
				}
				$tagfound="yes";
			}

			array_push($fixed_tags,$json_tag);

		}

		if($tagfound=="no"){
			$json_tag=array();
			$json_tag["name"]=$tagname;
			$json_tag["id"]=$this->getId();
			$json_tag["type"]="geologic_unit";
			$json_tag["spots"][]=$spotid;

			if($rockunittag["unit_label_abbreviation"]!="") $json_tag["unit_label_abbreviation"]=$rockunittag["unit_label_abbreviation"];
			if($rockunittag["map_unit_name"]!="") $json_tag["map_unit_name"]=$rockunittag["map_unit_name"];
			if($rockunittag["member_name"]!="") $json_tag["member_name"]=$rockunittag["member_name"];
			if($rockunittag["submember_name"]!="") $json_tag["submember_name"]=$rockunittag["submember_name"];
			if($rockunittag["absolute_age_of_geologic_unit"]!="") $json_tag["absolute_age_of_geologic_unit"]=$rockunittag["absolute_age_of_geologic_unit"];
			if($rockunittag["age_uncertainty"]!="") $json_tag["age_uncertainty"]=$rockunittag["age_uncertainty"];
			if($rockunittag["rock_type"]!="") $json_tag["rock_type"]=$rockunittag["rock_type"];
			if($rockunittag["sediment_type"]!="") $json_tag["sediment_type"]=$rockunittag["sediment_type"];
			if($rockunittag["other_sediment_type"]!="") $json_tag["other_sediment_type"]=$rockunittag["other_sediment_type"];
			if($rockunittag["sedimentary_rock_type"]!="") $json_tag["sedimentary_rock_type"]=$rockunittag["sedimentary_rock_type"];
			if($rockunittag["other_sedimentary_rock_type"]!="") $json_tag["other_sedimentary_rock_type"]=$rockunittag["other_sedimentary_rock_type"];
			if($rockunittag["igneous_rock_class"]!="") $json_tag["igneous_rock_class"]=$rockunittag["igneous_rock_class"];
			if($rockunittag["volcanic_rock_type"]!="") $json_tag["volcanic_rock_type"]=$rockunittag["volcanic_rock_type"];
			if($rockunittag["other_volcanic_rock_type"]!="") $json_tag["other_volcanic_rock_type"]=$rockunittag["other_volcanic_rock_type"];
			if($rockunittag["plutonic_rock_types"]!="") $json_tag["plutonic_rock_types"]=$rockunittag["plutonic_rock_types"];
			if($rockunittag["other_plutonic_rock_type"]!="") $json_tag["other_plutonic_rock_type"]=$rockunittag["other_plutonic_rock_type"];
			if($rockunittag["metamorphic_rock_types"]!="") $json_tag["metamorphic_rock_types"]=$rockunittag["metamorphic_rock_types"];
			if($rockunittag["other_metamorphic_rock_type"]!="") $json_tag["other_metamorphic_rock_type"]=$rockunittag["other_metamorphic_rock_type"];
			if($rockunittag["metamorphic_grade"]!="") $json_tag["metamorphic_grade"]=$rockunittag["metamorphic_grade"];
			if($rockunittag["other_metamorphic_grade"]!="") $json_tag["other_metamorphic_grade"]=$rockunittag["other_metamorphic_grade"];
			if($rockunittag["epoch"]!="") $json_tag["epoch"]=$rockunittag["epoch"];
			if($rockunittag["other_epoch"]!="") $json_tag["other_epoch"]=$rockunittag["other_epoch"];
			if($rockunittag["period"]!="") $json_tag["period"]=$rockunittag["period"];
			if($rockunittag["era"]!="") $json_tag["era"]=$rockunittag["era"];
			if($rockunittag["eon"]!="") $json_tag["eon"]=$rockunittag["eon"];
			if($rockunittag["age_modifier"]!="") $json_tag["age_modifier"]=$rockunittag["age_modifier"];
			if($rockunittag["description"]!="") $json_tag["description"]=$rockunittag["description"];
			if($rockunittag["notes"]!="") $json_tag["notes"]=$rockunittag["notes"];

			array_push($fixed_tags,$json_tag);
		}

		$this->projectvars["modified_timestamp"]=time();
		$this->projectvars["json_tags"]=json_encode($fixed_tags);

	}

	public function fixCast($val){
		$val=trim($val);
		if(is_numeric($val)){
			if(is_int($val)){
				return intval($val);
			}else{
				return floatval($val);
			}
		}else{
			return $val;
		}
	}

	public function get_vars($prefix){
		$returnarray=array();
		foreach($this->dbcols as $key=>$value){
			$len = strlen($prefix);
			if(substr($key,0,$len)==$prefix){
				if($this->sampleproperties[$value]!=""){
					$key = substr($key, strlen($prefix) + 1);
					$returnarray[$key]=$this->sampleproperties[$value];
				}
			}
		}

		return $returnarray;
	}

	public function getSelect($varname){

		$select="";
		$select.="<select name=\"".$varname."_select\">\n";
		$select.="<option value=\"\">Select...</option>\n";
		$select.="<option value=\"sfcustom_$varname\">Custom Column</option>\n";

		$select.="<optgroup label=\"Spot:\">\n";
		$select.=$this->getSelectRows("spot",$varname);

		$select.="<optgroup label=\"Orientation Data:\">\n";
		$select.="<optgroup label=\"Planar Orientation:\">\n";
		$select.=$this->getSelectRows("planar_orientation",$varname);
		$select.="<optgroup label=\"Linear Orientation:\">\n";
		$select.=$this->getSelectRows("linear_orientation",$varname);
		$select.="<optgroup label=\"Tabular Zone Orientation:\">\n";
		$select.=$this->getSelectRows("tabular_zone_orientation",$varname);

		$select.="<optgroup label=\"3D Structures:\">\n";
		$select.="<optgroup label=\"Fabric:\">\n";
		$select.=$this->getSelectRows("fabric",$varname);
		$select.="<optgroup label=\"Fold:\">\n";
		$select.=$this->getSelectRows("fold",$varname);
		$select.="<optgroup label=\"Tensor:\">\n";
		$select.=$this->getSelectRows("tensor",$varname);
		$select.="<optgroup label=\"Other:\">\n";
		$select.=$this->getSelectRows("other_3d_structure",$varname);

		$select.="<optgroup label=\"Rock Unit:\">\n";
		$select.=$this->getSelectRows("rock_unit",$varname);

		$select.="<optgroup label=\"Trace:\">\n";
		$select.=$this->getSelectRows("trace",$varname);

		$select.="<optgroup label=\"Sample:\">\n";
		$select.=$this->getSelectRows("sample",$varname);

		$select.="<optgroup label=\"Other Features:\">\n";
		$select.=$this->getSelectRows("other_features",$varname);

		$select.="<optgroup label=\"Tag:\">\n";
		$select.=$this->getSelectRows("tag",$varname);

		$select.="</select>";
		return $select;

	}

	public function loadXLSFiles(){

		 unset($this->fields);
		 $this->fields = array();
		 $this->fieldnum=0;

		$this->loadXLSFile("spot.xls","spot");
		$this->loadXLSFile("rock_unit.xls","rock_unit");
		$this->loadXLSFile("trace.xls","trace");

		$this->loadXLSFile("planar_orientation.xls","planar_orientation");
		$this->loadXLSFile("linear_orientation.xls","linear_orientation");
		$this->loadXLSFile("tabular_zone_orientation.xls","tabular_zone_orientation");

		$this->loadXLSFile("fabric.xls","fabric");
		$this->loadXLSFile("fold.xls","fold");
		$this->loadXLSFile("tensor.xls","tensor");
		$this->loadXLSFile("other_3d_structure.xls","other_3d_structure");

		$this->loadXLSFile("sample.xls","sample");

		$this->loadXLSFile("tephra.xls","tephra");

		$this->loadXLSFile("other_features.xls","other_features");

		$this->loadXLSFile("surface_feature.xls","surface_feature");

		$this->loadXLSFile("image_properties.xls","image");

		$this->loadXLSFile("tag.xls","tag");

	}

	public function getXLSRow($rows,$name){
		for($y=1;$y<20;$y++){
			if($rows[1][$y]==$name) return $y;
		}
		return false;
	}

	public function loadXLSFile($filename,$group){

		include_once 'excel_reader/excel_reader.php';
		$excel = new PhpExcelReader;

		$excel->read("includes/straboClasses/kobofiles/xls/$filename");

		$rows = $excel->sheets[0]["cells"];

		$namecol = $this->getXLSRow($rows,"name");
		$typecol = $this->getXLSRow($rows,"type");
		$labelcol = $this->getXLSRow($rows,"label");
		$hintcol = $this->getXLSRow($rows,"hint");

		foreach($rows as $row){

			$name = $row[$namecol];
			$type = $row[$typecol];
			$label = $row[$labelcol];
			$hint = $row[$hintcol];

			if(!in_array ( $name , $this->ignorelist ) && $name!="" && $type!="acknowledge" && $type!="begin group" && $hint!="hint"){

				$this->fields[$this->fieldnum]['group']=$group;
				$this->fields[$this->fieldnum]['name']=$name;
				$this->fields[$this->fieldnum]['label']=$label;
				$this->fields[$this->fieldnum]['hint']=$hint;
				$this->fields[$this->fieldnum]['num']=$this->fieldnum;
				$this->fields[$this->fieldnum]['type']=$type;
				$this->fields[$this->fieldnum]['strabolabel']=$group."_".$name;

				if(substr($type,0,10)=="select_one"){
					$selectvar = explode(" ",$type)[1];
					$this->addControlledItems($selectvar,$excel->sheets[1]["cells"]);
				}

				$this->fieldnum++;

			}
		}
	}

	public function addControlledItems($selectvar,$rows){

		$namecol = $this->getXLSRow($rows,"name");
		$labelcol = $this->getXLSRow($rows,"label");
		$listnamecol = $this->getXLSRow($rows,"list name");

		$varnum=0;

		foreach($rows as $row){
			$name = $row[$namecol];
			$label = $row[$labelcol];
			$listname = $row[$listnamecol];

			if( $listname==$selectvar ){
				$this->controlledlist[$this->fieldnum][$varnum]["name"]=$name;
				$this->controlledlist[$this->fieldnum][$varnum]["label"]=$label;
				$varnum++;
			}

		}

	}

}

?>