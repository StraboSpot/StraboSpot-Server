<?php
/**
 * File: newSearchOutputClass.php
 * Description: newSearchOutputClass class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class newSearchOutputClass
{

	 public function newSearchOutputClass($strabo,$get){
		$this->strabo=$strabo;
		$this->get=$get;
		$this->querybuilder = $querybuilder;
	 }

	public function dumpVar($var){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}

	public function gatherOrientations($orientations){

		$this->neworientations = array();
		$this->planar_orientation_num = 0;
		$this->linear_orientation_num = 0;
		$this->tabular_zone_orientation_num = 0;

		$returnorientations = $this->buildOrientations($orientations);
		return $returnorientations;

	}

	public function buildOrientations($orientations){

		foreach($orientations as $or){

			$type = $or->type;

			if($type=="planar_orientation"){
				$this->planar_orientation_num++;
				if($this->planar_orientation_num==1){
					$prefix="po_";
				}else{
					$prefix="po_".$this->planar_orientation_num."_";
				}
			}

			if($type=="linear_orientation"){
				$this->linear_orientation_num++;
				if($this->linear_orientation_num==1){
					$prefix="lo_";
				}else{
					$prefix="lo_".$this->linear_orientation_num."_";
				}
			}

			if($type=="tabular_orientation"){
				$this->tabular_zone_orientation_num++;
				if($this->tabular_zone_orientation_num==1){
					$prefix="to_";
				}else{
					$prefix="to_".$this->tabular_zone_orientation_num."_";
				}
			}

			foreach($or as $key=>$value){

				if($key != "type" && $key != "id" && $key != "associated_orientation"){

					$this->neworientations[$prefix.$key]=$value;

				}

			}

			if($or->associated_orientation){
				$this->buildOrientations($or->associated_orientation);
			}

		}

		return $this->neworientations;

	}

	public function buildStructures($structures){

		foreach($structures as $struct){

			$type = $struct->type;

			$prefix = "foo";

			if($type=="fabric"){
				$this->fabric_num++;
				if($this->fabric_num==1){
					$prefix="fab_";
				}else{
					$prefix="fab_".$this->fabric_num."_";
				}
			}

			if($type=="fold"){
				$this->fold_num++;
				if($this->fold_num==1){
					$prefix="fold_";
				}else{
					$prefix="fold_".$this->fold_num."_";
				}
			}

			if($type=="tensor"){
				$this->tensor_num++;
				if($this->tensor_num==1){
					$prefix="tns_";
				}else{
					$prefix="tns_".$this->tensor_num."_";
				}
			}

			if($type=="other"){
				$this->other_num++;
				if($this->other_num==1){
					$prefix="_3d_";
				}else{
					$prefix="_3d_".$this->other_num."_";
				}
			}

			foreach($struct as $key=>$value){

				if($key != "type" && $key != "id" && $key != "associated_orientation"){

					$this->newstructures[$prefix.$key]=$value;

				}

			}
		}

		return $newstructures;

	}

	public function gatherSamples($samples){

		$this->newsamples = array();
		$this->sample_num = 0;

		$returnsamples = $this->buildSamples($samples);

		return $returnsamples;

	}

	public function buildSamples($samples){

		foreach($samples as $samp){

			$sample_num++;
			if($sample_num==1){
				$prefix="samp_";
			}else{
				$prefix="samp_".$sample_num."_";
			}

			foreach($samp as $key=>$value){

				if($key != "id"){

					$this->newsamples[$prefix.$key]=$value;

				}
			}
		}

		return $newsamples;

	}

	public function gatherTephras($tephras){

		$this->newtephras = array();
		$this->tephra_num = 0;

		$returntephras = $this->buildTephras($tephras);

		return $returntephras;

	}

	public function buildTephras($tephras){

		foreach($tephras as $tephra){

			$prefix = "foo";

			$tephra_num++;
			if($tephra_num==1){
				$prefix="tephra_";
			}else{
				$prefix="tephra_".$tephra_num."_";
			}

			foreach($tephra as $key=>$value){

				if($key != "id"){

					$rawvalue = $value;
					if(is_array($rawvalue)){
						$showval = implode(", ", $rawvalue);
					}else{
						$showval = $rawvalue;
					}

					$this->newtephras[$prefix.$key]=$showval;

				}
			}
		}

		return $this->newtephras;

	}

	public function gatherOtherFeatures($otherfeatures){

		$this->newotherfeatures = array();
		$this->otherfeature_num = 0;

		$returnotherfeatures = $this->buildOtherFeatures($otherfeatures);

		return $returnotherfeatures;

	}

	public function gatherStructures($structures){

		$this->newstructures = array();
		$this->fabric_num = 0;
		$this->fold_num = 0;
		$this->tensor_num = 0;
		$this->other_num = 0;

		$returnstructures = $this->buildStructures($structures);

		return $returnstructures;

	}

	public function buildOtherFeatures($otherfeatures){

		foreach($otherfeatures as $of){

			$otherfeature_num++;
			if($otherfeature_num==1){
				$prefix="of_";
			}else{
				$prefix="of_".$otherfeature_num."_";
			}

			foreach($of as $key=>$value){

				if($key != "id"){

					$newotherfeatures[$prefix.$key]=$value;

				}
			}
		}

		return $newotherfeatures;

	}

	public function fixSpot20240531($spot){

		$id = $spot['properties']['id'];

		unset($spot['original_geometry']);

		if($spot['properties']['orientation_data']){
			$orientations = $this->gatherOrientations($spot['properties']['orientation_data']);
			foreach($orientations as $key=>$value){
				$spot['properties'][$key]=$value;
			}
		}

		if($spot['properties']['_3d_structures']){
			$structures = $this->gatherStructures($spot['properties']['_3d_structures']);
			foreach($structures as $key=>$value){
				$spot['properties'][$key]=$value;
			}
		}

		if($spot['properties']['samples']){
			$samples = $this->gatherSamples($spot['properties']['samples']);
			foreach($samples as $key=>$value){
				$spot['properties'][$key]=$value;
			}
		}

		if($spot['properties']['other_features']){
			$otherfeatures = $this->gatherOtherFeatures($spot['properties']['other_features']);
			foreach($otherfeatures as $key=>$value){
				$spot['properties'][$key]=$value;
			}
		}

		//surface feature
		if($spot['properties']['surface_feature']){
			foreach($spot['properties']['surface_feature'] as $key=>$value){
				$spot['properties']['sf_'.$key]=$value;
			}
			unset($spot['properties']['surface_feature']);
		}

		//trace
		if($spot['properties']['trace']){
			foreach($spot['properties']['trace'] as $key=>$value){
				if($key != "id" && $key != "date" ){
					$spot['properties']['tr_'.$key]=$value;
				}
			}
			unset($spot['properties']['trace']);
		}

		//rock units
		if($this->alltags){
			foreach($this->alltags as $tag){
				$found = "no";
				if($tag->spots){
					foreach($tag->spots as $spotid){
						if($spotid == $id){
							$found = "yes";
						}
					}
				}

				if($found == "yes"){
					if($tag->type=="geologic_unit"){
						foreach($tag as $key=>$value){
							if($key != "date" && $key != "spots" && $key != "features" && $key != "id" ){
								$spot['properties']['ru_'.$key]=$value;
							}
						}
					}
				}
			}
		}

		if($spot['properties']['custom_fields']){
			foreach($spot['properties']['custom_fields'] as $key=>$value){
				if($key != "idaaaaa" && $key != "dateaaaaa" ){
					$spot['properties']['cust_'.$key]=$value;
				}
			}
			unset($spot['properties']['custom_fields']);
		}

		$images = "";
		$imagesdelim = "";
		if($spot['properties']['images']){
			foreach($spot['properties']['images'] as $image){
				$images.=$imagesdelim."https://strabospot.org/pi/".$image['id'];
				$imagesdelim=";";
			}
		}

		unset($spot['properties']['images']);

		if($images!=""){
			$spot['properties']['images'] = $images;
		}

		unset($spot['properties']['date']);
		unset($spot['properties']['time']);
		unset($spot['properties']['id']);
		unset($spot['properties']['self']);
		unset($spot['properties']['modified_timestamp']);
		unset($spot['properties']['samples']);
		unset($spot['properties']['_3d_structures']);
		unset($spot['properties']['geometrytype']);
		unset($spot['properties']['orientation_data']);
		unset($spot['properties']['other_features']);

		unset($spot['properties']['image_basemap']);

		$spot['properties']['spot_name']=$spot['properties']['name'];

		unset($spot['properties']['name']);

		return $spot;
	}

	public function fixSpot($spot){

		$id = $spot['properties']['id'];

		unset($spot['original_geometry']);

		if($spot['properties']['orientation_data']){
			$orientations = $this->gatherOrientations($spot['properties']['orientation_data']);
			foreach($orientations as $key=>$value){
				$spot['properties'][$key]=$value;
			}
		}

		if($spot['properties']['_3d_structures']){
			$structures = $this->gatherStructures($spot['properties']['_3d_structures']);
			foreach($structures as $key=>$value){
				$spot['properties'][$key]=$value;
			}
		}

		if($spot['properties']['samples']){
			$samples = $this->gatherSamples($spot['properties']['samples']);
			foreach($samples as $key=>$value){
				$spot['properties'][$key]=$value;
			}
		}

		if($spot['properties']['other_features']){
			$otherfeatures = $this->gatherOtherFeatures($spot['properties']['other_features']);
			foreach($otherfeatures as $key=>$value){
				$spot['properties'][$key]=$value;
			}
		}

		//surface feature
		if($spot['properties']['surface_feature']){
			foreach($spot['properties']['surface_feature'] as $key=>$value){
				$spot['properties']['sf_'.$key]=$value;
			}
			unset($spot['properties']['surface_feature']);
		}

		//trace
		if($spot['properties']['trace']){
			foreach($spot['properties']['trace'] as $key=>$value){
				if($key != "id" && $key != "date" ){
					$spot['properties']['tr_'.$key]=$value;
				}
			}
			unset($spot['properties']['trace']);
		}

		if($spot['properties']['tephra']){
			$tephras = $this->gatherTephras($spot['properties']['tephra']);
			foreach($tephras as $key=>$value){
				$spot['properties'][$key]=$value;
			}
			unset($spot['properties']['tephra']);
		}

		//rock units
		if($this->alltags){
			foreach($this->alltags as $tag){
				$found = "no";
				if($tag->spots){
					foreach($tag->spots as $spotid){
						if($spotid == $id){
							$found = "yes";
						}
					}
				}

				if($found == "yes"){
					if($tag->type=="geologic_unit"){
						foreach($tag as $key=>$value){
							if($key != "date" && $key != "spots" && $key != "features" && $key != "id" ){
								$spot['properties']['ru_'.$key]=$value;
							}
						}
					}
				}
			}
		}

		if($spot['properties']['custom_fields']){
			foreach($spot['properties']['custom_fields'] as $key=>$value){
				if($key != "idaaaaa" && $key != "dateaaaaa" ){
					$spot['properties']['cust_'.$key]=$value;
				}
			}
			unset($spot['properties']['custom_fields']);
		}

		$images = "";
		$imagesdelim = "";
		if($spot['properties']['images']){
			foreach($spot['properties']['images'] as $image){
				$images.=$imagesdelim."https://strabospot.org/pi/".$image['id'];
				$imagesdelim=";";
			}
		}

		unset($spot['properties']['images']);

		if($images!=""){
			$spot['properties']['images'] = $images;
		}

		unset($spot['properties']['date']);
		unset($spot['properties']['time']);
		unset($spot['properties']['id']);
		unset($spot['properties']['self']);
		unset($spot['properties']['modified_timestamp']);
		unset($spot['properties']['samples']);
		unset($spot['properties']['_3d_structures']);
		unset($spot['properties']['geometrytype']);
		unset($spot['properties']['orientation_data']);
		unset($spot['properties']['other_features']);
		unset($spot['properties']['symbology']);

		unset($spot['properties']['image_basemap']);

		$spot['properties']['spot_name']=$spot['properties']['name'];

		unset($spot['properties']['name']);

		return $spot;
	}

	public function rowcol($row,$col){
		$cols = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI","AJ","AK","AL","AM","AN","AO","AP","AQ","AR","AS","AT","AU","AV","AW","AX","AY","AZ","BA","BB","BC","BD","BE","BF","BG","BH","BI","BJ","BK","BL","BM","BN","BO","BP","BQ","BR","BS","BT","BU","BV","BW","BX","BY","BZ","CA","CB","CC","CD","CE","CF","CG","CH","CI","CJ","CK","CL","CM","CN","CO","CP","CQ","CR","CS","CT","CU","CV","CW","CX","CY","CZ","DA","DB","DC","DD","DE","DF","DG","DH","DI","DJ","DK","DL","DM","DN","DO","DP","DQ","DR","DS","DT","DU","DV","DW","DX","DY","DZ","EA","EB","EC","ED","EE","EF","EG","EH","EI","EJ","EK","EL","EM","EN","EO","EP","EQ","ER","ES","ET","EU","EV","EW","EX","EY","EZ");
		$colletter = $cols[$col];
		$row=$row+1;
		return $colletter.$row;
	}

	public function fix_column_name($name){
		$newstring="";
		$delim="";
		$parts = explode("_",$name);
		foreach($parts as $part){
			$part = ucfirst($part);
			$newstring.=$delim.$part;
			$delim=" ";
		}

		if($name=="id"){$newstring="ID";}

		return $newstring;
	}

	public function fixLabel($label){
		$returnlabel = "";
		$delim = "";
		$labels = explode("_",$label);
		foreach($labels as $label){
			$label = ucfirst($label);
			$returnlabel.=$delim.$label;
			$delim=" ";
		}
		$returnlabel = trim($returnlabel);
		return $returnlabel;
	}

	public function gdThumb($filename){

		if(file_exists("/srv/app/www/dbimages/$filename")){

			$thumbwidth = 300;

			$src = imagecreatefromjpeg("/srv/app/www/dbimages/$filename");
			list($origwidth, $origheight) = getimagesize("/srv/app/www/dbimages/$filename");

			$ratio = $origheight / $origwidth;

			$thumbheight = round($thumbwidth * $ratio);

			$tmp = imagecreatetruecolor($thumbwidth, $thumbheight);

			$filename = '/path/to/images/' . $_FILES['file']['name'];

			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $thumbwidth, $thumbheight, $origwidth, $origheight);

			return $tmp;

		}else{

			return null;

		}
	}

	public function shapefileOut(){

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->newSearchGetTagsFromDatasetIds($dsids);
			$polygonjson = $this->strabo->newSearchGetDatasetSpotsSearch('polygon',$this->get);

			if($polygonjson!=""){

				$newjson = array();
				$newjson['type']="FeatureCollection";
				$features = array();
				foreach($polygonjson['features'] as $spot){
					$spot = $this->fixSpot($spot);
					$features[]=$spot;
				}

				$newjson['features']=$features;
				$polygonjson = json_encode($newjson,JSON_PRETTY_PRINT);
			}

			$pointjson = $this->strabo->newSearchGetDatasetSpotsSearch('point',$this->get);
			if($pointjson!=""){
				$newjson = array();
				$newjson['type']="FeatureCollection";
				$features = array();
				foreach($pointjson['features'] as $spot){
					$spot = $this->fixSpot($spot);
					$features[]=$spot;
				}

				$newjson['features']=$features;
				$pointjson = json_encode($newjson,JSON_PRETTY_PRINT);
			}

			$linejson = $this->strabo->newSearchGetDatasetSpotsSearch('linestring',$this->get);

			if($linejson!=""){

				$newjson = array();
				$newjson['type']="FeatureCollection";
				$features = array();
				foreach($linejson['features'] as $spot){
					$spot = $this->fixSpot($spot);
					$features[]=$spot;
				}

				$newjson['features']=$features;
				$linejson = json_encode($newjson,JSON_PRETTY_PRINT);
			}

			if($polygonjson!="" || $pointjson!="" || $linejson!=""){

				$randnum=$this->strabo->db->get_var("select nextval('file_seq')");

				//make directory in ogrtemp to hold data
				mkdir("ogrtemp/$randnum");
				mkdir("ogrtemp/$randnum/data");

				if($polygonjson!=""){
					file_put_contents("ogrtemp/$randnum/polygon.json", $polygonjson);
					exec("ogr2ogr -nlt POLYGON -f \"ESRI Shapefile\" -skipfailures ogrtemp/$randnum/data/polygons.shp ogrtemp/$randnum/polygon.json 2>&1",$results);
				}

				if($linejson!=""){
					file_put_contents("ogrtemp/$randnum/line.json", $linejson);
					exec("ogr2ogr -nlt LINESTRING -f \"ESRI Shapefile\" -skipfailures ogrtemp/$randnum/data/lines.shp ogrtemp/$randnum/line.json 2>&1",$results);
					unlink("ogrtemp/$randnum/line.json");
				}

				if($pointjson!=""){
					file_put_contents("ogrtemp/$randnum/point.json", $pointjson);
					exec("ogr2ogr -nlt POINT -f \"ESRI Shapefile\" -skipfailures ogrtemp/$randnum/data/points.shp ogrtemp/$randnum/point.json 2>&1",$results);
					unlink("ogrtemp/$randnum/point.json");
				}

				//create zip file
				exec("zip -j ogrtemp/$randnum/strabo$randnum.zip ogrtemp/$randnum/data/* 2>&1",$results);

				//force download of file
				header("Content-Type: application/zip");
				header("Content-Disposition: attachment; filename=search_download.zip");
				header("Content-Length: " . filesize("ogrtemp/$randnum/strabo$randnum.zip"));

				readfile("ogrtemp/$randnum/strabo$randnum.zip");

				//remove temp directory
				if($randnum!=""){
				}

			}else{
				echo "No data found for this dataset.";
			}

		}

	}

	public function devshapefileOut(){

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->newSearchGetTagsFromDatasetIds($dsids);
			$polygonjson = $this->strabo->newSearchGetDatasetSpotsSearch('polygon',$this->get);

			if($polygonjson!=""){

				$newjson = array();
				$newjson['type']="FeatureCollection";
				$features = array();
				foreach($polygonjson['features'] as $spot){
					$spot = $this->fixSpot($spot);
					$features[]=$spot;
				}

				$newjson['features']=$features;
				$polygonjson = json_encode($newjson,JSON_PRETTY_PRINT);
			}

			$pointjson = $this->strabo->newSearchGetDatasetSpotsSearch('point',$this->get);
			if($pointjson!=""){
				$newjson = array();
				$newjson['type']="FeatureCollection";
				$features = array();
				foreach($pointjson['features'] as $spot){
					$spot = $this->fixSpot($spot);
					$features[]=$spot;
				}

				$newjson['features']=$features;
				$pointjson = json_encode($newjson,JSON_PRETTY_PRINT);
			}

			$linejson = $this->strabo->newSearchGetDatasetSpotsSearch('linestring',$this->get);

			if($linejson!=""){

				$newjson = array();
				$newjson['type']="FeatureCollection";
				$features = array();
				foreach($linejson['features'] as $spot){
					$spot = $this->fixSpot($spot);
					$features[]=$spot;
				}

				$newjson['features']=$features;
				$linejson = json_encode($newjson,JSON_PRETTY_PRINT);
			}

			echo "polygonjson:";
			$this->dumpVar($polygonjson);
			echo "pointjson:";
			$this->dumpVar($pointjson);
			echo "linejson:";
			$this->dumpVar($linejson);
			exit();

			if($polygonjson!="" || $pointjson!="" || $linejson!=""){

				$randnum=$this->strabo->db->get_var("select nextval('file_seq')");

				//make directory in ogrtemp to hold data
				mkdir("ogrtemp/$randnum");
				mkdir("ogrtemp/$randnum/data");

				if($polygonjson!=""){
					file_put_contents("ogrtemp/$randnum/polygon.json", $polygonjson);
					exec("ogr2ogr -nlt POLYGON -f \"ESRI Shapefile\" -skipfailures ogrtemp/$randnum/data/polygons.shp ogrtemp/$randnum/polygon.json 2>&1",$results);
				}

				if($linejson!=""){
					file_put_contents("ogrtemp/$randnum/line.json", $linejson);
					exec("ogr2ogr -nlt LINESTRING -f \"ESRI Shapefile\" -skipfailures ogrtemp/$randnum/data/lines.shp ogrtemp/$randnum/line.json 2>&1",$results);
					unlink("ogrtemp/$randnum/line.json");
				}

				if($pointjson!=""){
					file_put_contents("ogrtemp/$randnum/point.json", $pointjson);
					exec("ogr2ogr -nlt POINT -f \"ESRI Shapefile\" -skipfailures ogrtemp/$randnum/data/points.shp ogrtemp/$randnum/point.json 2>&1",$results);
					unlink("ogrtemp/$randnum/point.json");
				}

				//create zip file
				exec("zip -j ogrtemp/$randnum/strabo$randnum.zip ogrtemp/$randnum/data/* 2>&1",$results);

				//force download of file
				header("Content-Type: application/zip");
				header("Content-Disposition: attachment; filename=search_download.zip");
				header("Content-Length: " . filesize("ogrtemp/$randnum/strabo$randnum.zip"));

				readfile("ogrtemp/$randnum/strabo$randnum.zip");

				//remove temp directory
				if($randnum!=""){
				}

			}else{
				echo "No data found for this dataset.";
			}

		}

	}

				//make directory in ogrtemp to hold data
				//create zip file
				//force download of file
				//remove temp directory

	public function oldkmlOut(){

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->getTagsFromDatasetIds($dsids);

			$kmljson = $this->strabo->getDatasetSpotsSearch(null,$this->get);

			if($kmljson!=""){

				$newjson = array();
				$newjson['type']="FeatureCollection";
				$features = array();
				foreach($kmljson['features'] as $spot){
					$spot = $this->fixSpot($spot);
					$features[]=$spot;
				}

				$newjson['features']=$features;

				$kmljson = json_encode($newjson,JSON_PRETTY_PRINT);
			}

			$filedate = date("m_d_Y");

			if($kmljson!=""){

				$randnum=$this->strabo->db->get_var("select nextval('file_seq')");

				//make directory in ogrtemp to hold data
				mkdir("ogrtemp/$randnum");

				file_put_contents("ogrtemp/$randnum/data.json", $kmljson);
				exec("ogr2ogr -f \"KML\" ogrtemp/$randnum/strabo$randnum.kml ogrtemp/$randnum/data.json 2>&1",$results);

				unlink("ogrtemp/$randnum/data.json");

				$filecontent = file_get_contents("ogrtemp/$randnum/strabo$randnum.kml");
				$lines = explode("\n",$filecontent);

				foreach($lines as $line){

					$line = trim($line);

					if($line=="</Schema>"){
						$file.='</Schema>'."\n".'<Style id="highlightPlacemark"><IconStyle><Icon><href>http://maps.google.com/mapfiles/kml/shapes/placemark_circle_highlight.png</href></Icon></IconStyle></Style><Style id="normalPlacemark"><IconStyle><Icon><href>http://maps.google.com/mapfiles/kml/shapes/placemark_circle.png</href></Icon></IconStyle></Style><StyleMap id="exampleStyleMap"><Pair><key>normal</key><styleUrl>#normalPlacemark</styleUrl></Pair><Pair><key>highlight</key><styleUrl>#highlightPlacemark</styleUrl></Pair></StyleMap>'."\n";
					}elseif($line=="<Placemark>"){
						$file.='<Placemark>'."\n".'<styleUrl>#exampleStyleMap</styleUrl><Style><LineStyle><color>641400FF</color><width>5</width></LineStyle><PolyStyle><color>641400FF</color><colorMode>normal</colorMode><fill>1</fill><outline>1</outline></PolyStyle></Style>'."\n";
					}elseif(substr($line,0,7)=="<Style>"){

					}else{
						$file.="$line\n";
					}

				}

				//force download of file
				$datasetname = str_replace(" ","_",$datasetname);

				header("Content-Type: application/kml");
				header("Content-Disposition: attachment; filename=strabo_search_$filedate.kml");

				echo $file;

				//remove temp directory
				if($randnum!=""){
					exec("rm -rf ogrtemp/$randnum",$results);
				}

			}else{
				echo "No data found for this dataset.";
			}

		}

	}

	public function xlsOut(){

			if($this->get['dsids']!=""){

				$dsids=$this->get['dsids'];
				$this->alltags = $this->strabo->newSearchGetTagsFromDatasetIds($dsids);

				$data = $this->strabo->newSearchGetDatasetSpotsSearch(null, $this->get);

				if(count($data)==0){
				echo "no data found for dataset $id";exit();
				}

				$columns['spot']['name']=0;
				$columns['spot']['date']=1;
				$columns['spot']['self']=2;
				$columns['spot']['notes']=3;
				$columns['spot']['geometry']=4;
				$columns['spot']['original_geometry']=5;
				$columns['spot']['Latitude']=6;
				$columns['spot']['Longitude']=7;
				$columns['spot']['Altitude(m)']=8;

				$colnum=9;

				$x=0;

				$arrayMultipleData = [];

				foreach($data['features'] as $feature){

					$orientationdatas = $feature['properties']['orientation_data'];
					if($orientationdatas){
						foreach($orientationdatas as $orientationdata){

							$orientationdata = json_decode(json_encode($orientationdata));

							if($orientationdata->type=="linear_orientation"){
								foreach($orientationdata as $key=>$value){
									if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
										if($key=="feature_type"){$key="linear_feature_type";}
										if(!$columns['orientation']['linear'][$key]){
											$columns['orientation']['linear'][$key]=$colnum;
											$colnum++;
										}
									}
								}
							}

							if($orientationdata->type=="planar_orientation"){
								foreach($orientationdata as $key=>$value){
									if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
										if($key=="feature_type"){$key="planar_feature_type";}
										if(!$columns['orientation']['planar'][$key]){
											$columns['orientation']['planar'][$key]=$colnum;
											$colnum++;
										}
									}
								}
							}

							if($orientationdata->type=="tabular_orientation"){
								foreach($orientationdata as $key=>$value){
									if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
										if($key=="feature_type"){$key="tabular_feature_type";}
										if(!$columns['orientation']['tabular'][$key]){
											$columns['orientation']['tabular'][$key]=$colnum;
											$colnum++;
										}
									}
								}
							}

							$associatedorientations = $orientationdata->associated_orientation;
							if($associatedorientations){
								foreach($associatedorientations as $associatedorientation){
									if($associatedorientation->type=="planar_orientation"){
										foreach($associatedorientation as $key=>$value){
											if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
												if($key=="feature_type"){$key="planar_feature_type";}
												if(!$columns['orientation']['planar'][$key]){
													$columns['orientation']['planar'][$key]=$colnum;
													$colnum++;
												}
											}
										}

									}

									if($associatedorientation->type=="linear_orientation"){
										foreach($associatedorientation as $key=>$value){
											if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
												if($key=="feature_type"){$key="linear_feature_type";}
												if(!$columns['orientation']['linear'][$key]){
													$columns['orientation']['linear'][$key]=$colnum;
													$colnum++;
												}
											}
										}
									}

									if($associatedorientation->type=="tabular_orientation"){
										foreach($associatedorientation as $key=>$value){
											if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
												if($key=="feature_type"){$key="tabular_feature_type";}
												if(!$columns['orientation']['tabular'][$key]){
													$columns['orientation']['tabular'][$key]=$colnum;
													$colnum++;
												}
											}
										}
									}
								}
							}
						}
					}

					$x++;

				}

				foreach($data['features'] as $feature){

					$pet = $feature['properties']['pet'];
					if($pet){
						if($pet->metamorphic){

							foreach($pet->metamorphic as $met){

								foreach($met as $key=>$value){
									if($key != "id"){
										if(!$columns['pet']['metamorphic'][$key]){
											$columns['pet']['metamorphic'][$key]=$colnum;
											$colnum++;
										}
									}
								}

							}
						}
					}

					$x++;

				}

				foreach($data['features'] as $feature){

					$pet = $feature['properties']['pet'];
					if($pet){
						if($pet->igneous){

							foreach($pet->igneous as $ig){

								foreach($ig as $key=>$value){
									if($key != "id"){
										if(!$columns['pet']['igneous'][$key]){
											$columns['pet']['igneous'][$key]=$colnum;
											$colnum++;
										}
									}
								}

							}
						}
					}

					$x++;

				}

				foreach($data['features'] as $feature){

					$pet = $feature['properties']['pet'];
					if($pet){
						if($pet->minerals){

							foreach($pet->minerals as $min){

								foreach($min as $key=>$value){
									if($key != "id"){
										if(!$columns['pet']['minerals'][$key]){
											$columns['pet']['minerals'][$key]=$colnum;
											$colnum++;
										}
									}
								}

							}
						}
					}

					$x++;

				}

				foreach($data['features'] as $feature){

					$other_features = $feature['properties']['other_features'];
					if($other_features){
						foreach($other_features as $other_feature){

							foreach($other_feature as $key=>$value){
								if($key != "id"){
									if(!$columns['other_features'][$key]){
										$columns['other_features'][$key]=$colnum;
										$colnum++;
									}
								}
							}

						}
					}

					$x++;

				}

				foreach($data['features'] as $feature){

					$samples = $feature['properties']['samples'];
					if($samples){
						foreach($samples as $sample){

							foreach($sample as $key=>$value){
								if($key != "id"){
									if(!$columns['samples'][$key]){
										$columns['samples'][$key]=$colnum;
										$colnum++;
									}
								}
							}

						}
					}

					$x++;

				}

				foreach($data['features'] as $feature){

					$trace = $feature['properties']['trace'];
					if($trace){
						foreach($trace as $key=>$value){

							if(!$columns['trace'][$key]){
								$columns['trace'][$key]=$colnum;
								$colnum++;
							}
						}
					}

					$x++;

				}

				foreach($data['features'] as $feature){

					$tephra = $feature['properties']['tephra'];
					if($tephra){
						foreach($tephra as $t){
							foreach($t as $key=>$value){
								if($key != "id"){
									if(!$columns['tephra'][$key]){
										$columns['tephra'][$key]=$colnum;
										$colnum++;
									}
								}
							}
						}
					}

					$x++;

				}

				foreach($data['features'] as $feature){

					$customfields = $feature['properties']['custom_fields'];
					if($customfields){
						foreach($customfields as $key=>$value){

							if(!$columns['custom_fields'][$key]){
								$columns['custom_fields'][$key]=$colnum;
								$colnum++;
							}
						}
					}

					$x++;

				}

				//also add tags to sheet!
				//need to add a column for each key of each tag and then put an X in the corresponding cells for a given spot

	// 							}
	// 						}
	// 					}
	// 				}
	// 			}

				$tagarray = [];
				if($this->alltags != ""){

					foreach($this->alltags as $currenttag){
						foreach($currenttag as $key=>$value){
							if($key!="id" && $key!="spots"){
								if(!in_array($key, $tagarray)){
									$tagarray[]=$key;
								}
							}
						}

						if(!$columns['tags'][$currenttag->id]){
							$columns['tags'][$currenttag->id]=$colnum;
							$colnum++;
						}

					}

					$tagnum = 0;
					$tagscolumns = [];
					foreach($tagarray as $tag){
						$tagscolumns[$tag] = $tagnum;
						$tagnum++;
					}

				}

				/** PHPExcel */
				include 'PHPExcel.php';

				/** PHPExcel_Writer_Excel2007 */
				include 'PHPExcel/Writer/Excel2007.php';

				$objPHPExcel = new PHPExcel();

				// Set properties
				$objPHPExcel->getProperties()->setCreator("strabospot.org");
				$objPHPExcel->getProperties()->setLastModifiedBy("strabospot.org");
				$objPHPExcel->getProperties()->setTitle("StraboSpot.org Download");
				$objPHPExcel->getProperties()->setSubject("StraboSpot.org Download");
				$objPHPExcel->getProperties()->setDescription("StraboSpot.org Download");

				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('Spots');

				// Add some data
				$objPHPExcel->setActiveSheetIndex(0);

				$objPHPExcel->getActiveSheet()->SetCellValue('A1', "StraboSpot Dataset Download: $datasetname");

				$colnum=0;

				foreach($columns['spot'] as $key=>$value){

					if($key=="geometry"){$key="Real World Coordinates";}
					if($key=="original_geometry"){$key="Pixel Coordinates";}

					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), $this->fix_column_name($key));

					$thiswidth=strlen($key)-1;
					if($thisheader=="Age (Ma)"){$thiswidth="12";}
					if($thiswidth<10){
						$thiswidth=10;
					}

					$colnum++;
				}

				if($columns['orientation']['tabular']){
					foreach($columns['orientation']['tabular'] as $key=>$value){

						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Tabular Orientation ".$this->fix_column_name($key));

						$thiswidth=strlen($key)-1;
						if($thiswidth<10){
							$thiswidth=10;
						}

						$colnum++;

					}
				}

				if($columns['orientation']['planar']){
					foreach($columns['orientation']['planar'] as $key=>$value){

						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Planar Orientation ".$this->fix_column_name($key));

						$thiswidth=strlen($key)-1;
						if($thiswidth<10){
							$thiswidth=10;
						}

						$colnum++;

					}
				}

				if($columns['orientation']['linear']){
					foreach($columns['orientation']['linear'] as $key=>$value){

						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Linear Orientation ".$this->fix_column_name($key));

						$thiswidth=strlen($key)-1;
						if($thiswidth<10){
							$thiswidth=10;
						}

						$colnum++;

					}
				}

				if($columns['pet']['metamorphic']){
					foreach($columns['pet']['metamorphic'] as $key=>$value){

						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Metamorphic ".$this->fix_column_name($key));

						$thiswidth=strlen($key)-1;
						if($thiswidth<10){
							$thiswidth=10;
						}

						$colnum++;

					}
				}

				if($columns['pet']['igneous']){
					foreach($columns['pet']['igneous'] as $key=>$value){

						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Igneous ".$this->fix_column_name($key));

						$thiswidth=strlen($key)-1;
						if($thiswidth<10){
							$thiswidth=10;
						}

						$colnum++;

					}
				}

				if($columns['pet']['minerals']){
					foreach($columns['pet']['igneous'] as $key=>$value){

						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Mineral ".$this->fix_column_name($key));

						$thiswidth=strlen($key)-1;
						if($thiswidth<10){
							$thiswidth=10;
						}

						$colnum++;

					}
				}

				if($columns['other_features']){
					foreach($columns['other_features'] as $key=>$value){

						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Other Feature ".$this->fix_column_name($key));

						$thiswidth=strlen($key)-1;
						if($thisheader=="Age (Ma)"){$thiswidth="12";}
						if($thiswidth<10){
							$thiswidth=10;
						}

						$colnum++;
					}
				}

				if($columns['samples']){
					foreach($columns['samples'] as $key=>$value){

						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Sample ".$this->fix_column_name($key));

						$thiswidth=strlen($key)-1;
						if($thisheader=="Age (Ma)"){$thiswidth="12";}
						if($thiswidth<10){
							$thiswidth=10;
						}

						$colnum++;
					}
				}

				if($columns['trace']){
					foreach($columns['trace'] as $key=>$value){

						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Trace ".$this->fix_column_name($key));

						$thiswidth=strlen($key)-1;
						if($thisheader=="Age (Ma)"){$thiswidth="12";}
						if($thiswidth<10){
							$thiswidth=10;
						}

						$colnum++;
					}
				}

				if($columns['tephra']){
					foreach($columns['tephra'] as $key=>$value){

						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Tephra ".$this->fix_column_name($key));

						$thiswidth=strlen($key)-1;
						if($thisheader=="Age (Ma)"){$thiswidth="12";}
						if($thiswidth<10){
							$thiswidth=10;
						}

						$colnum++;
					}
				}

				if($columns['custom_fields']){
					foreach($columns['custom_fields'] as $key=>$value){

						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), $this->fix_column_name($key));

						$thiswidth=strlen($key)-1;
						if($thisheader=="Age (Ma)"){$thiswidth="12";}
						if($thiswidth<10){
							$thiswidth=10;
						}

						$colnum++;
					}
				}

				if($columns['tags']){
					foreach($columns['tags'] as $key=>$value){

						$taglabel = "";
						foreach($this->alltags as $tag){

							if($key == $tag->id){
								$taglabel = "Tag:".$tag->name;
							}
						}

						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), $taglabel);

						$colnum++;
					}
				}

				//write data
				$rownum=4;
				foreach($data['features'] as $feature){

					//use geoPHP to get WKT
					$mygeojson=$feature['geometry'];
					$mygeojson=trim(json_encode($mygeojson));

					try {
						$mywkt=geoPHP::load($mygeojson,"json");
						$wkt = $mywkt->out('wkt');
						$geometry=$wkt;
					} catch (Exception $e) {
						$geometry="";
					}

					//use geoPHP to get WKT
					$mygeojson=$feature['original_geometry'];
					$mygeojson=trim(json_encode($mygeojson));

					try {
						$mywkt=geoPHP::load($mygeojson,"json");
						$wkt = $mywkt->out('wkt');
						$original_geometry=$wkt;
					} catch (Exception $e) {
						$original_geometry="";
					}

					$spotid = $feature['properties']['id'];
					$spotname = (string)$feature['properties']['name'];
					$spotdate = $feature['properties']['date'];
					$spotself = $feature['properties']['self'];
					$spotnotes = $feature['properties']['notes'];
					$altitude = $feature['properties']['altitude'];

					$latitude = "";
					$longitude = "";

					if(strtolower(substr($geometry,0,5))=="point"){
						$lonlatgeom=$geometry;
						$lonlatgeom=strtolower($lonlatgeom);
						$lonlatgeom=str_replace("point ","",$lonlatgeom);
						$lonlatgeom=str_replace("(","",$lonlatgeom);
						$lonlatgeom=str_replace(")","",$lonlatgeom);
						$lonlatgeom=explode(" ",$lonlatgeom);
						$longitude=$lonlatgeom[0];
						$latitude=$lonlatgeom[1];
					}

					unset($arrayMultipleData);
					$maxcount = 0;
					$linear_orientation_count = 0;
					$planar_orientation_count = 0;
					$tabular_orientation_count = 0;
					$_3d_structure_count = 0;
					$other_features_count = 0;
					$samples_count = 0;
					$traces_count = 0;
					$tephras_count = 0;
					$metamorphics_count = 0;
					$igneouses_count = 0;
					$minerals_count = 0;
					$tags_count = 0;

					$orientationdatas = $feature['properties']['orientation_data'];

					if($orientationdatas){
						foreach($orientationdatas as $orientationdata){

							$orientationdata = json_decode(json_encode($orientationdata));

							if($orientationdata->type=="linear_orientation"){
								foreach($orientationdata as $key=>$value){
									if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
										if($key=="feature_type"){$key="linear_feature_type";}
										$arrayMultipleData['linear'][$linear_orientation_count][$key]=$value;
									}
								}
								$linear_orientation_count++;
							}

							if($orientationdata->type=="planar_orientation"){
								foreach($orientationdata as $key=>$value){
									if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
										if($key=="feature_type"){$key="planar_feature_type";}
										$arrayMultipleData['planar'][$planar_orientation_count][$key]=$value;
									}
								}
								$planar_orientation_count++;
							}

							if($orientationdata->type=="tabular_orientation"){
								foreach($orientationdata as $key=>$value){
									if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
										if($key=="feature_type"){$key="tabular_feature_type";}
										$arrayMultipleData['tabular'][$tabular_orientation_count][$key]=$value;
									}
								}
								$tabular_orientation_count++;
							}

							$associatedorientations = $orientationdata->associated_orientation;
							if($associatedorientations){
								foreach($associatedorientations as $associatedorientation){
									if($associatedorientation->type=="planar_orientation"){
										foreach($associatedorientation as $key=>$value){
											if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
												if($key=="feature_type"){$key="planar_feature_type";}
												$arrayMultipleData['planar'][$planar_orientation_count][$key]=$value;
											}
										}
										$planar_orientation_count++;
									}

									if($associatedorientation->type=="linear_orientation"){
										foreach($associatedorientation as $key=>$value){
											if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
												if($key=="feature_type"){$key="linear_feature_type";}
												$arrayMultipleData['linear'][$linear_orientation_count][$key]=$value;
											}
										}
										$linear_orientation_count++;
									}

									if($associatedorientation->type=="tabular_orientation"){
										foreach($associatedorientation as $key=>$value){
											if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
												if($key=="feature_type"){$key="tabular_feature_type";}
												$arrayMultipleData['tabular'][$tabular_orientation_count][$key]=$value;
											}
										}
										$tabular_orientation_count++;
									}
								}
							}
						}
					}

					$metamorphics = $feature['properties']['pet']->metamorphic;
					if($metamorphics){
						foreach($metamorphics as $metamorphic){

							foreach($metamorphic as $key=>$value){
								if($key != "id" && $key != "" ){
									$arrayMultipleData['metamorphic'][$metamorphics_count][$key]=$value;
								}
							}
							$metamorphics_count++;
						}
					}

					$igneouses = $feature['properties']['pet']->igneous;
					if($igneouses){
						foreach($igneouses as $igneous){

							foreach($igneous as $key=>$value){
								if($key != "id" && $key != "" ){
									$arrayMultipleData['igneous'][$igneouses_count][$key]=$value;
								}
							}
							$igneouses_count++;
						}
					}

					$minerals = $feature['properties']['pet']->minerals;
					if($minerals){
						foreach($minerals as $mineral){

							foreach($mineral as $key=>$value){
								if($key != "id" && $key != "" ){
									$arrayMultipleData['mineral'][$minerals_count][$key]=$value;
								}
							}
							$minerals_count++;
						}
					}

					$other_features = $feature['properties']['other_features'];
					if($other_features){
						foreach($other_features as $other_feature){
							foreach($other_feature as $key=>$value){
								if($key != "id"){
									$arrayMultipleData['other_features'][$other_features_count][$key]=$value;
								}
							}
							$other_features_count++;
						}
					}

					$samples = $feature['properties']['samples'];
					if($samples){
						foreach($samples as $sample){
							foreach($sample as $key=>$value){
								if($key != "id"){
									$arrayMultipleData['samples'][$samples_count][$key]=$value;
								}
							}
							$samples_count++;
						}
					}

					$trace = $feature['properties']['trace'];
					if($trace){
						foreach($trace as $key=>$value){
							$arrayMultipleData['trace'][$traces_count][$key]=$value;
						}
						$traces_count++;
					}

					$tephras = $feature['properties']['tephra'];
					if($tephras){
						foreach($tephras as $tephra){
							foreach($tephra as $key=>$value){
								if($key != "id"){
									$arrayMultipleData['tephra'][$tephras_count][$key]=$value;
								}
							}
							$tephras_count++;
						}
					}

					if($linear_orientation_count > $maxcount) $maxcount = $linear_orientation_count;
					if($planar_orientation_count > $maxcount) $maxcount = $planar_orientation_count;
					if($tabular_orientation_count > $maxcount) $maxcount = $tabular_orientation_count;
					if($other_features_count > $maxcount) $maxcount = $other_features_count;
					if($samples_count > $maxcount) $maxcount = $samples_count;
					if($traces_count > $maxcount) $maxcount = $traces_count;
					if($tephras_count > $maxcount) $maxcount = $tephras_count;
					if($metamorphics_count > $maxcount) $maxcount = $metamorphics_count;
					if($igneouses_count > $maxcount) $maxcount = $igneouses_count;
					if($minerals_count > $maxcount) $maxcount = $minerals_count;

					if($maxcount==0) $maxcount = 1;

					for($spotrepeat = 0; $spotrepeat < $maxcount; $spotrepeat ++){

						//write out each row here
						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,0), $spotname);
						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,1), $spotdate);
						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,2), $spotself);
						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,3),$spotnotes);
						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,4),$geometry);

						if($geometry!=$original_geometry){
							$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,5),$original_geometry);
						}

						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,6),$latitude);
						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,7),$longitude);
						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,8),$altitude);

						foreach($columns as $key=>$value){
							if($key!="spot"){
								if($key == "orientation" || $key == "pet"){
									foreach($value as $otype=>$ors){
										foreach($ors as $orkey=>$orval){
											$rawVal = $arrayMultipleData[$otype][$spotrepeat][$orkey];
											if(is_array($rawVal)){
												$showVal = implode(", ", $rawVal);
											}else{
												$showVal = $rawVal;
											}

											$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,$orval),$showVal);
										}
									}
								}else{
									foreach($value as $newkey=>$column){

										$rawVal = $arrayMultipleData[$key][$spotrepeat][$newkey];
										if(is_array($rawVal)){
											$showVal = implode(", ", $rawVal);
										}else{
											$showVal = $rawVal;
										}

										$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,$column),$showVal);
									}
								}
							}
						}

						foreach($columns as $key=>$value){
							if($key=="tags"){
								//determine if the spot belongs in the tag and mark X if it is
								foreach($value as $tagid=>$colnum){
									foreach($this->alltags as $tag){
										if($tagid == $tag->id){

											if(in_array($spotid, $tag->spots)){
												$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,$colnum),"X");
											}

											if($tag->features != null){
												foreach($tag->features as $snum=>$other){
													if($snum == $spotid){
														$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,$colnum),"X");
													}
												}
											}
										}
									}
								}
							}
						}

						$rownum++;

					}

				}//end foreach feature

				//Add tags if necessary
				// Rename sheet
				if($this->alltags != ""){
					$tagsWorkSheet = $objPHPExcel->createSheet(1); //Setting index when creating

					//Write cells
					$tagsWorkSheet->SetCellValue('A1', 'Tags:');

					// Rename sheet
					$tagsWorkSheet->setTitle("Tag Details");

					$rownum = 2;
					$colnum = 0;
					foreach($tagscolumns as $key=>$value){
						$tagsWorkSheet->SetCellValue($this->rowcol($rownum,$value),$this->fix_column_name($key));
					}

					$rownum = 3;
					foreach($this->alltags as $tag){
						foreach($tag as $key=>$value){
							if($key!="id" && $key!="spots" && $key!="features"){
								if(is_array($value)){
									$showvalue = implode(",", $value);
								}else{
									$showvalue = $value;
								}

								$tagsWorkSheet->SetCellValue($this->rowcol($rownum,$tagscolumns[$key]),$showvalue);
							}
						}
						$rownum++;
					}

				}

			}

			// Save Excel 2007 file
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

			// We'll be outputting an excel file
			header('Content-type: application/vnd.ms-excel');

			$filedate = date("m_d_Y");

			// It will be called file.xls
			header('Content-Disposition: attachment; filename="'."StraboSpot_Output_".$filedate.".xlsx".'"');

			// Write file to the browser
			$objWriter->save('php://output');

		}

	public function xlsOut20240531(){

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->newSearchGetTagsFromDatasetIds($dsids);

			$data = $this->strabo->newSearchGetDatasetSpotsSearch(null, $this->get);

			$this->dumpVar($data);exit();

			if(count($data)==0){
			echo "no data found for dataset $id";exit();
			}

			$columns['spot']['name']=0;
			$columns['spot']['date']=1;
			$columns['spot']['self']=2;
			$columns['spot']['notes']=3;
			$columns['spot']['geometry']=4;
			$columns['spot']['original_geometry']=5;
			$columns['spot']['Latitude']=6;
			$columns['spot']['Longitude']=7;

			$colnum=8;

			$x=0;

			$arrayMultipleData = [];

			foreach($data['features'] as $feature){

				$orientationdatas = $feature['properties']['orientation_data'];
				if($orientationdatas){
					foreach($orientationdatas as $orientationdata){

						if($orientationdata->type=="linear_orientation"){
							foreach($orientationdata as $key=>$value){
								if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
									if($key=="feature_type"){$key="linear_feature_type";}
									if(!$columns['orientation']['linear'][$key]){
										$columns['orientation']['linear'][$key]=$colnum;
										$colnum++;
									}
								}
							}
						}

						if($orientationdata->type=="planar_orientation"){
							foreach($orientationdata as $key=>$value){
								if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
									if($key=="feature_type"){$key="planar_feature_type";}
									if(!$columns['orientation']['planar'][$key]){
										$columns['orientation']['planar'][$key]=$colnum;
										$colnum++;
									}
								}
							}
						}

						if($orientationdata->type=="tabular_orientation"){
							foreach($orientationdata as $key=>$value){
								if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
									if($key=="feature_type"){$key="tabular_feature_type";}
									if(!$columns['orientation']['tabular'][$key]){
										$columns['orientation']['tabular'][$key]=$colnum;
										$colnum++;
									}
								}
							}
						}

						$associatedorientations = $orientationdata->associated_orientation;
						if($associatedorientations){
							foreach($associatedorientations as $associatedorientation){
								if($associatedorientation->type=="planar_orientation"){
									foreach($associatedorientation as $key=>$value){
										if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
											if($key=="feature_type"){$key="planar_feature_type";}
											if(!$columns['orientation']['planar'][$key]){
												$columns['orientation']['planar'][$key]=$colnum;
												$colnum++;
											}
										}
									}

								}

								if($associatedorientation->type=="linear_orientation"){
									foreach($associatedorientation as $key=>$value){
										if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
											if($key=="feature_type"){$key="linear_feature_type";}
											if(!$columns['orientation']['linear'][$key]){
												$columns['orientation']['linear'][$key]=$colnum;
												$colnum++;
											}
										}
									}
								}

								if($associatedorientation->type=="tabular_orientation"){
									foreach($associatedorientation as $key=>$value){
										if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
											if($key=="feature_type"){$key="tabular_feature_type";}
											if(!$columns['orientation']['tabular'][$key]){
												$columns['orientation']['tabular'][$key]=$colnum;
												$colnum++;
											}
										}
									}
								}
							}
						}
					}
				}

				$x++;

			}

			foreach($data['features'] as $feature){

				$other_features = $feature['properties']['other_features'];
				if($other_features){
					foreach($other_features as $other_feature){

						foreach($other_feature as $key=>$value){
							if($key != "id"){
								if(!$columns['other_features'][$key]){
									$columns['other_features'][$key]=$colnum;
									$colnum++;
								}
							}
						}

					}
				}

				$x++;

			}

			foreach($data['features'] as $feature){

				$samples = $feature['properties']['samples'];
				if($samples){
					foreach($samples as $sample){

						foreach($sample as $key=>$value){
							if($key != "id"){
								if(!$columns['samples'][$key]){
									$columns['samples'][$key]=$colnum;
									$colnum++;
								}
							}
						}

					}
				}

				$x++;

			}

			foreach($data['features'] as $feature){

				$trace = $feature['properties']['trace'];
				if($trace){
					foreach($trace as $key=>$value){

						if(!$columns['trace'][$key]){
							$columns['trace'][$key]=$colnum;
							$colnum++;
						}
					}
				}

				$x++;

			}

			foreach($data['features'] as $feature){

				$customfields = $feature['properties']['custom_fields'];
				if($customfields){
					foreach($customfields as $key=>$value){

						if(!$columns['custom_fields'][$key]){
							$columns['custom_fields'][$key]=$colnum;
							$colnum++;
						}
					}
				}

				$x++;

			}

			/** PHPExcel */
			include 'PHPExcel.php';

			/** PHPExcel_Writer_Excel2007 */
			include 'PHPExcel/Writer/Excel2007.php';

			$objPHPExcel = new PHPExcel();

			// Set properties
			$objPHPExcel->getProperties()->setCreator("strabospot.org");
			$objPHPExcel->getProperties()->setLastModifiedBy("strabospot.org");
			$objPHPExcel->getProperties()->setTitle("StraboSpot.org Download");
			$objPHPExcel->getProperties()->setSubject("StraboSpot.org Download");
			$objPHPExcel->getProperties()->setDescription("StraboSpot.org Download");

			// Rename sheet
			$objPHPExcel->getActiveSheet()->setTitle('Spots');

			// Add some data
			$objPHPExcel->setActiveSheetIndex(0);

			$objPHPExcel->getActiveSheet()->SetCellValue('A1', "StraboSpot Dataset Download: $datasetname");

			$colnum=0;

			foreach($columns['spot'] as $key=>$value){

				if($key=="geometry"){$key="Real World Coordinates";}
				if($key=="original_geometry"){$key="Pixel Coordinates";}

				$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), $this->fix_column_name($key));

				$thiswidth=strlen($key)-1;
				if($thisheader=="Age (Ma)"){$thiswidth="12";}
				if($thiswidth<10){
					$thiswidth=10;
				}

				$colnum++;
			}

			if($columns['orientation']['tabular']){
				foreach($columns['orientation']['tabular'] as $key=>$value){

					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Tabular Orientation ".$this->fix_column_name($key));

					$thiswidth=strlen($key)-1;
					if($thiswidth<10){
						$thiswidth=10;
					}

					$colnum++;

				}
			}

			if($columns['orientation']['planar']){
				foreach($columns['orientation']['planar'] as $key=>$value){

					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Planar Orientation ".$this->fix_column_name($key));

					$thiswidth=strlen($key)-1;
					if($thiswidth<10){
						$thiswidth=10;
					}

					$colnum++;

				}
			}

			if($columns['orientation']['linear']){
				foreach($columns['orientation']['linear'] as $key=>$value){

					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Linear Orientation ".$this->fix_column_name($key));

					$thiswidth=strlen($key)-1;
					if($thiswidth<10){
						$thiswidth=10;
					}

					$colnum++;

				}
			}

			if($columns['other_features']){
				foreach($columns['other_features'] as $key=>$value){

					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Other Feature ".$this->fix_column_name($key));

					$thiswidth=strlen($key)-1;
					if($thisheader=="Age (Ma)"){$thiswidth="12";}
					if($thiswidth<10){
						$thiswidth=10;
					}

					$colnum++;
				}
			}

			if($columns['samples']){
				foreach($columns['samples'] as $key=>$value){

					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Sample ".$this->fix_column_name($key));

					$thiswidth=strlen($key)-1;
					if($thisheader=="Age (Ma)"){$thiswidth="12";}
					if($thiswidth<10){
						$thiswidth=10;
					}

					$colnum++;
				}
			}

			if($columns['trace']){
				foreach($columns['trace'] as $key=>$value){

					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), "Trace ".$this->fix_column_name($key));

					$thiswidth=strlen($key)-1;
					if($thisheader=="Age (Ma)"){$thiswidth="12";}
					if($thiswidth<10){
						$thiswidth=10;
					}

					$colnum++;
				}
			}

			if($columns['custom_fields']){
				foreach($columns['custom_fields'] as $key=>$value){

					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol(3,$value), $this->fix_column_name($key));

					$thiswidth=strlen($key)-1;
					if($thisheader=="Age (Ma)"){$thiswidth="12";}
					if($thiswidth<10){
						$thiswidth=10;
					}

					$colnum++;
				}
			}

			//write data
			$rownum=4;
			foreach($data['features'] as $feature){

				//use geoPHP to get WKT
				$mygeojson=$feature['geometry'];
				$mygeojson=trim(json_encode($mygeojson));

				try {
					$mywkt=geoPHP::load($mygeojson,"json");
					$wkt = $mywkt->out('wkt');
					$geometry=$wkt;
				} catch (Exception $e) {
					$geometry="";
				}

				//use geoPHP to get WKT
				$mygeojson=$feature['original_geometry'];
				$mygeojson=trim(json_encode($mygeojson));

				try {
					$mywkt=geoPHP::load($mygeojson,"json");
					$wkt = $mywkt->out('wkt');
					$original_geometry=$wkt;
				} catch (Exception $e) {
					$original_geometry="";
				}

				$spotname = (string)$feature['properties']['name'];
				$spotdate = $feature['properties']['date'];
				$spotself = $feature['properties']['self'];
				$spotnotes = $feature['properties']['notes'];

				$latitude = "";
				$longitude = "";

				if(strtolower(substr($geometry,0,5))=="point"){
					$lonlatgeom=$geometry;
					$lonlatgeom=strtolower($lonlatgeom);
					$lonlatgeom=str_replace("point ","",$lonlatgeom);
					$lonlatgeom=str_replace("(","",$lonlatgeom);
					$lonlatgeom=str_replace(")","",$lonlatgeom);
					$lonlatgeom=explode(" ",$lonlatgeom);
					$longitude=$lonlatgeom[0];
					$latitude=$lonlatgeom[1];
				}

				$maxcount = 0;
				$linear_orientation_count = 0;
				$planar_orientation_count = 0;
				$tabular_orientation_count = 0;
				$_3d_structure_count = 0;
				$other_features_count = 0;
				$samples_count = 0;
				$traces_count = 0;

				$orientationdatas = $feature['properties']['orientation_data'];
				if($orientationdatas){
					foreach($orientationdatas as $orientationdata){

						if($orientationdata->type=="linear_orientation"){
							foreach($orientationdata as $key=>$value){
								if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
									if($key=="feature_type"){$key="linear_feature_type";}
									$arrayMultipleData['linear'][$linear_orientation_count][$key]=$value;
								}
							}
							$linear_orientation_count++;
						}

						if($orientationdata->type=="planar_orientation"){
							foreach($orientationdata as $key=>$value){
								if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
									if($key=="feature_type"){$key="planar_feature_type";}
									$arrayMultipleData['planar'][$planar_orientation_count][$key]=$value;
								}
							}
							$planar_orientation_count++;
						}

						if($orientationdata->type=="tabular_orientation"){
							foreach($orientationdata as $key=>$value){
								if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
									if($key=="feature_type"){$key="tabular_feature_type";}
									$arrayMultipleData['tabular'][$tabular_orientation_count][$key]=$value;
								}
							}
							$tabular_orientation_count++;
						}

						$associatedorientations = $orientationdata->associated_orientation;
						if($associatedorientations){
							foreach($associatedorientations as $associatedorientation){
								if($associatedorientation->type=="planar_orientation"){
									foreach($associatedorientation as $key=>$value){
										if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
											if($key=="feature_type"){$key="planar_feature_type";}
											$arrayMultipleData['planar'][$planar_orientation_count][$key]=$value;
										}
									}
									$planar_orientation_count++;
								}

								if($associatedorientation->type=="linear_orientation"){
									foreach($associatedorientation as $key=>$value){
										if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
											if($key=="feature_type"){$key="linear_feature_type";}
											$arrayMultipleData['linear'][$linear_orientation_count][$key]=$value;
										}
									}
									$linear_orientation_count++;
								}

								if($associatedorientation->type=="tabular_orientation"){
									foreach($associatedorientation as $key=>$value){
										if($key!="associated_orientation" && $key != "id" && $key != "type" && $key != "" ){
											if($key=="feature_type"){$key="tabular_feature_type";}
											$arrayMultipleData['tabular'][$tabular_orientation_count][$key]=$value;
										}
									}
									$tabular_orientation_count++;
								}
							}
						}
					}
				}

				$other_features = $feature['properties']['other_features'];
				if($other_features){
					foreach($other_features as $other_feature){
						foreach($other_feature as $key=>$value){
							if($key != "id"){
								$arrayMultipleData['other_features'][$other_features_count][$key]=$value;
							}
						}
						$other_features_count++;
					}
				}

				$samples = $feature['properties']['samples'];
				if($samples){
					foreach($samples as $sample){
						foreach($sample as $key=>$value){
							if($key != "id"){
								$arrayMultipleData['samples'][$samples_count][$key]=$value;
							}
						}
						$samples_count++;
					}
				}

				$trace = $feature['properties']['trace'];
				if($trace){
					foreach($trace as $key=>$value){
						$arrayMultipleData['trace'][$traces_count][$key]=$value;
					}
					$traces_count++;
				}

				if($linear_orientation_count > $maxcount) $maxcount = $linear_orientation_count;
				if($planar_orientation_count > $maxcount) $maxcount = $planar_orientation_count;
				if($tabular_orientation_count > $maxcount) $maxcount = $tabular_orientation_count;
				if($other_features_count > $maxcount) $maxcount = $other_features_count;
				if($samples_count > $maxcount) $maxcount = $samples_count;
				if($traces_count > $maxcount) $maxcount = $traces_count;

				if($maxcount==0) $maxcount = 1;

				for($spotrepeat = 0; $spotrepeat < $maxcount; $spotrepeat ++){

					//write out each row here
					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,0), $spotname);
					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,1), $spotdate);
					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,2), $spotself);
					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,3),$spotnotes);
					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,4),$geometry);

					if($geometry!=$original_geometry){
						$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,5),$original_geometry);
					}

					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,6),$latitude);
					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,7),$longitude);

					foreach($columns as $key=>$value){
						if($key!="spot"){
							if($key == "orientation"){
								foreach($value as $otype=>$ors){
									foreach($ors as $orkey=>$orval){
										$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,$orval),$arrayMultipleData[$otype][$spotrepeat][$orkey]);
									}
								}
							}else{
								foreach($value as $newkey=>$column){
									$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,$column),$arrayMultipleData[$key][$spotrepeat][$newkey]);
								}
							}
						}
					}

					$rownum++;

				}

			}//end foreach feature

		}

		// Save Excel 2007 file
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

		// We'll be outputting an excel file
		header('Content-type: application/vnd.ms-excel');

		$filedate = date("m_d_Y");

		// It will be called file.xls
		header('Content-Disposition: attachment; filename="'."StraboSpot_Output_".$filedate.".xlsx".'"');

		// Write file to the browser
		$objWriter->save('php://output');

	}

	public function debugOut(){

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->newSearchGetTagsFromDatasetIds($dsids);
			$spots = $this->strabo->newSearchGetDatasetSpotsSearch(null,$this->get);
			$spots = $spots['features'];

			$this->dumpVar($spots);exit();

		}

	}

	public function stereonetOut(){

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->newSearchGetTagsFromDatasetIds($dsids);
			$spots = $this->strabo->newSearchGetDatasetSpotsSearch(null,$this->get);
			$spots = $spots['features'];

			if(count($spots)==0){
			echo "no data found for dataset $id";exit();
			}

			$headers = array (
				"No.",
				"Type",
				"Structure",
				"Color",
				"Trd/Strk",
				"Plg/Dip",
				"Longitude",
				"Latitude",
				"Horiz ± m",
				"Elevation",
				"Elev ± m",
				"Time",
				"Day",
				"Month",
				"Year",
				"Notes"
			);

			unset($lines);
			unset($planes);

			$count = count($spots);

			foreach($spots as $spot){

				$spot = json_decode(json_encode($spot), true);

				if($spot['properties']['orientation_data']){

					$longitude = 999;
					$latitude = 99;
					$trendstrike = "";
					$plungedip = "";

					//check if spot is point and set lat/lon if so
					if($spot['geometry']['type'] == "Point"){
						$longitude = $spot['geometry']['coordinates'][0];
						$latitude = $spot['geometry']['coordinates'][1];
					}

					foreach($spot['properties']['orientation_data'] as $or){

						if($or['orientation_type']=="planar_orientation" || $or['type']=="planar_orientation"){

							if($or['strike'] != "" && $or['dip'] != ""){

								$trendstrike = $or['strike'];
								$plungedip = $or['dip'];
								$notes = $or['notes'];
								$row = array(
												"",
												"P",
												"$datasetname Planes",
												"000000000",
												"$trendstrike",
												"$plungedip",
												"$longitude",
												"$latitude",
												"",
												"0",
												"",
												"",
												"0",
												"0",
												"0",
												"$notes"
											);
								$planes[] = $row;
							}

						}elseif($or['orientation_type']=="linear_orientation" || $or['type']=="linear_orientation"){

							if($or['trend'] != "" && $or['plunge'] != ""){

								$trendstrike = $or['trend'];
								$plungedip = $or['plunge'];
								$notes = $or['notes'];
								$row = array(
												"",
												"L",
												"$datasetname Lines",
												"000000000",
												"$trendstrike",
												"$plungedip",
												"$longitude",
												"$latitude",
												"",
												"0",
												"",
												"",
												"0",
												"0",
												"0",
												"$notes"
											);
								$lines[] = $row;
							}

						}

					}

				}

			}

			if($lines != "" || $planes != ""){

				$recordnum = 1;

				$out[]=implode("\t",$headers);
				if($planes!=""){
					foreach($planes as $plane){
						$plane[0]=$recordnum;
						$out[]=implode("\t",$plane);;
						$recordnum++;
					}
				}
				if($lines!=""){
					foreach($lines as $line){
						$line[0]=$recordnum;
						$out[]=implode("\t",$line);;
						$recordnum++;
					}
				}

				$out = implode("\n",$out);

				$filedate = date("m_d_Y");
				$outname="StraboSpot_Search_$filedate.txt";

				header("Content-disposition: attachment; filename=$outname");
				header('Content-type: text/plain');

				echo $out;

			}else{

				include("includes/header.php");
				echo "Sorry, no orientation data found for this dataset.";
				include("includes/footer.php");

			}

		} //end if dsids

	}

	/*

	Change this...

	Get rid of image metadata
	Order by spot creation
	Add notes to top of each spot
	Add Lat/Lon to each spot (if point),
	Add delimter for each day.

	NEED TO ADD DAILY NOTES!!!

	*/

	public function fieldbookOut(){

		if($this->get['dsids']!=""){

			//new
			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->newSearchGetTagsFromDatasetIds($dsids);
			$json = $this->strabo->newSearchGetDatasetSpotsSearch(null,$this->get);

			$dsname = $this->strabo->getDatasetName($dsids);

			$dailynotes = $this->strabo->getDailyNotesFromDatasetID($dsids);

			$spots = $json['features'];

			//unix time 1567364734  10 digits
			//id 15609769344266     14 digits

			//get day for each spot

			//create date array to loop over

			if(count($spots)>0){

				$spotdates = [];
				$founddates = [];
				foreach($spots as $spot){

					$ut = substr($spot['properties']['id'], 0 ,10);

					$datestring = date("l, F j, Y", $ut);

					if(!in_array($datestring, $founddates)){
						$founddates[] = $datestring;
						$showformat = date("l, F j, Y", $ut);
						$lookformat = date("Y-m-d", $ut);
						$thisobj = new stdClass();
						$thisobj->showformat = $showformat;
						$thisobj->lookformat = $lookformat;
						$spotdates[] = $thisobj;
					}

				}

				require('includes/PDF_LabBook.php');

				$pdf = new PDF_MemImage('P','mm','Letter');

				$pdf->AddFont('msjh','','msjh.ttf',true);

				$pdf->AddPage();

				if($dsname != ""){
					$pdf->datasetTitle($dsname);
				}

				foreach($spotdates as $spotdate){

					$showdate = $spotdate->showformat;
					$pdf->dayTitle($showdate);

					//look for daily notes (format in data is YYYY-MM-DD)
					$lookdate = $spotdate->lookformat;

					if($dailynotes!=""){
						foreach($dailynotes as $dn){
							$dndate = substr($dn->date,0,10);

							if($dndate == $lookdate){
								$pdf->dailyNotesRow("Daily Setup Notes",$dn->notes,10);
							}
						}
					}

					foreach($spots as $spot){

						$rawspot = $spot;

						$spot = $spot['properties'];

						$id = $spot['id'];

						$ut = substr($id, 0 ,10);

						$thisspotdate = date("l, F j, Y", $ut);

						if($thisspotdate == $spotdate->showformat){

							$spotname = $spot['name'];
							if($spot['geometrytype']){
								$spotname .= " (".$spot['geometrytype'].")";
							}

							$pdf->spotTitle($spotname);

							$modified = (string) $spot['id'];
							$modified = substr($modified,0,10);
							$modified = date("F j, Y",$modified);
							$pdf->valueRow("Created",$modified,15);

							$modified = (string) $spot['modified_timestamp'];
							$modified = substr($modified,0,10);
							$modified = date("F j, Y",$modified);
							$pdf->valueRow("Last Modified",$modified,15);

							if($rawspot['geometry']->type=="Point" && $rawspot['properties']['strat_section_id']==""){
								$pdf->valueRow("Longitude",$rawspot['geometry']->coordinates[0],15);
								$pdf->valueRow("Latitude",$rawspot['geometry']->coordinates[1],15);
							}else{
							}

							if($spot['notes']){
								$notes = $spot['notes'];
								$pdf->notesRow("Notes",$notes,15);
							}

							if($spot['surface_feature']){
								foreach($spot['surface_feature'] as $key=>$value){
									$key = $this->fixLabel($key);
									if(is_string($value)){
										$value = $this->fixLabel($value);
									}
									$pdf->valueRow($key,$value,15);
								}
							}

							if($spot['orientation_data']){
								$pdf->valueRow("Orientations","",15);
								foreach($spot['orientation_data'] as $o){
									$pdf->valueTitle($this->fixLabel($o['type']).": ",20);
									foreach($o as $key=>$value){
										if($key!="id" && $key!="associated_orientation" && $key!="type"){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,20);
										}
									}

									if($o->associated_orientation){
										$pdf->valueRow("Associated Orientation Data","",20);
										foreach($o->associated_orientation as $ao){
											$pdf->valueTitle($this->fixLabel($ao['type']).": ",30);
											foreach($ao as $key=>$value){
												if($key!="id" && $key!="associated_orientation" && $key!="type"){
													$key = $this->fixLabel($key);
													if(is_string($value)){
														$value = $this->fixLabel($value);
													}
													$pdf->valueRow($key,$value,30);
												}
											}
											$pdf->Ln(1);
										}
									}

									$pdf->Ln(1);
								}
							}

							if($spot['_3d_structures']){
								$pdf->valueRow("3D Structures","",15);
								foreach($spot['_3d_structures'] as $o){
									$pdf->valueTitle($this->fixLabel($o->type).": ",20);
									foreach($o as $key=>$value){
										if($key!="id" && $key!="type"){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,20);
										}
									}

									$pdf->Ln(1);
								}
							}

							if($spot['samples']){
								$pdf->valueRow("Samples","",15);
								foreach($spot['samples'] as $o){
									$pdf->valueTitle($this->fixLabel($o['label']).": ",20);
									foreach($o as $key=>$value){
										if($key!="id" && $key!="label"){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,20);
										}
									}

									$pdf->Ln(1);
								}
							}

							if($spot['tephra']){
								$pdf->valueRow("Tephra Intervals","",15);
								foreach($spot['tephra'] as $o){
									$pdf->valueTitle("Interval: ",20);
									foreach($o as $key=>$value){
										if($key!="id" && $key!="label"){
											$key = $this->fixLabel($key);
											if(is_array($value)){
												$value = implode(", ", $value);
											}elseif(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,20);
										}
									}

									$pdf->Ln(1);
								}
							}

							if($spot['other_features']){
								$pdf->valueRow("Other Features","",15);
								foreach($spot['other_features'] as $o){
									$pdf->valueTitle($this->fixLabel($o->label).": ",20);
									foreach($o as $key=>$value){
										if($key!="id" && $key!="label"){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,20);
										}
									}

									$pdf->Ln(1);
								}
							}

							$hastags = "no";

							if($this->alltags){
								foreach($this->alltags as $tag){
									$found = "no";
									if($tag->spots){
										if($tag->type!="geologic_unit"){
											foreach($tag->spots as $spotid){
												if($spotid == $id){
													$hastags = "yes";
												}
											}
										}
									}

								}
							}

							if($hastags == "yes"){

								$pdf->valueRow("Tags","",15);

								if($this->alltags){
									foreach($this->alltags as $tag){
										$found = "no";
										if($tag->spots){
											if($tag->type!="geologic_unit"){
												foreach($tag->spots as $spotid){
													if($spotid == $id){
														$found = "yes";
													}
												}
											}
										}

										if($found == "yes"){

											$pdf->valueTitle($tag->name,20);
											foreach($tag as $key=>$value){

												if($key != "date" && $key != "spots" && $key != "features" && $key != "id" && $key != "name" ){
													$key = $this->fixLabel($key);
													if(is_string($value)){
														$value = $this->fixLabel($value);
													}elseif(is_array($value)){
														$value = implode(", ", $value);
													}
													$pdf->valueRow($key,$value,20);
												}

											}

											$pdf->Ln(1);

										}
									}
								}

							}

							$hastags = "no";

							if($this->alltags){
								foreach($this->alltags as $tag){
									$found = "no";
									if($tag->spots){
										if($tag->type=="geologic_unit"){
											foreach($tag->spots as $spotid){
												if($spotid == $id){
													$hastags = "yes";
												}
											}
										}
									}

								}
							}

							if($hastags == "yes"){

								$pdf->valueRow("Geologic Unit(s)","",15);

								if($this->alltags){
									foreach($this->alltags as $tag){
										$found = "no";
										if($tag->spots){
											if($tag->type=="geologic_unit"){
												foreach($tag->spots as $spotid){
													if($spotid == $id){
														$found = "yes";
													}
												}
											}
										}

										if($found == "yes"){

											$pdf->valueTitle($tag->name,20);
											foreach($tag as $key=>$value){

												if($key != "date" && $key != "spots" && $key != "features" && $key != "id" && $key != "name" ){
													$key = $this->fixLabel($key);
													if(is_string($value)){
														$value = $this->fixLabel($value);
													}elseif(is_array($value)){
														$value = implode(", ", $value);
													}
													$pdf->valueRow($key,$value,20);
												}

											}

											$pdf->Ln(1);

										}
									}
								}

							}

							if($spot['sed']){
								$pdf->valueRow("Sed","",15);
								if($spot['sed']->strat_section){
									$pdf->valueTitle("Strat Section: ", 20);
									$content = $spot['sed']->strat_section;

									if($content->section_well_name!=""){
										$pdf->valueRow("Section/Well Name",$content->section_well_name,25);
									}

									if($content->column_profile!=""){
										$pdf->valueRow("Column Profile",$content->column_profile,25);
									}

									if($content->column_y_axis_units!=""){
										$pdf->valueRow("Column Y-Axis Units",$content->column_y_axis_units,25);
									}

									if($content->section_type!=""){
										$pdf->valueRow("Section Type",$content->section_type,25);
									}

									if($content->what_core_repository!=""){
										$pdf->valueRow("What Core Repository?",$content->what_core_repository,25);
									}

									if($content->type_of_corer!=""){
										$pdf->valueRow("Type of corer",$content->type_of_corer,25);
									}

									if($content->depth_from_surface_to_start_of!=""){
										$pdf->valueRow("Depth from surface to start of core",$content->depth_from_surface_to_start_of,25);
									}

									if($content->total_core_length!=""){
										$pdf->valueRow("Total core length",$content->total_core_length,25);
									}

									if($content->location_locality!=""){
										$pdf->valueRow("Location/Locality",$content->location_locality,25);
									}

									if($content->basin!=""){
										$pdf->valueRow("Basin",$content->basin,25);
									}

									if($content->age!=""){
										$pdf->valueRow("Age",$content->age,25);
									}

									if($content->purpose!=""){
										$pdf->valueRow("Purpose",implode($content->purpose, ", "),25);
									}

									if($content->other_purpose!=""){
										$pdf->valueRow("Other Purpose",$content->other_purpose,25);
									}

									if($content->project_description!=""){
										$pdf->valueRow("Project Description",$content->project_description,25);
									}

									if($content->dates_of_work!=""){
										$pdf->valueRow("Dates of Work",$content->dates_of_work,25);
									}

									if($content->scale_of_interest!=""){
										$pdf->valueRow("Scale of Interest",implode($content->scale_of_interest, ", "),25);
									}

									if($content->other_scale_of_interest!=""){
										$pdf->valueRow("Other Scale of Interest",$content->other_scale_of_interest,25);
									}

									if($content->obs_interval_bed_obs_scale!=""){
										$pdf->valueRow("Observation Interval (average bed/observation scale)",$content->obs_interval_bed_obs_scale,25);
									}

									if($content->how_is_section_georeferenced!=""){
										$pdf->valueRow("How is the Section Georeferenced?",$content->how_is_section_georeferenced,25);
									}

									if($content->strat_section_notes!=""){
										$pdf->valueRow("Notes",$content->strat_section_notes,25);
									}

									//strat section link here
									$pdf->httpLink("Download Strat Section", 25, "https://strabospot.org/pstrat_section?id=".$spot['id']."&did=".$dsids);

								}

								if($spot['sed']->lithologies){
									$pdf->valueTitle("Lithologies: ", 20);
									$contents = $spot['sed']->lithologies;
									foreach($contents as $content){
										$pdf->valueTitle("Primary Lithology: ", 25);

										if($content->primary_lithology!=""){
											$pdf->valueRow("Primary Lithology",$content->primary_lithology,30);
										}

										if($content->siliciclastic_type!=""){
											$pdf->valueRow("Siliciclastic Type",$content->siliciclastic_type,30);
										}

										if($content->dunham_classification!=""){
											$pdf->valueRow("Dunham Classification",$content->dunham_classification,30);
										}

										if($content->grain_type!=""){
											$pdf->valueRow("Grain Type",$content->grain_type,30);
										}

										if($content->evaporite_type!=""){
											$pdf->valueRow("Evaporite type",implode($content->evaporite_type, ", "),30);
										}

										if($content->other_evaporite_type!=""){
											$pdf->valueRow("Other Evaporite Type",$content->other_evaporite_type,30);
										}

										if($content->organic_coal_lithologies!=""){
											$pdf->valueRow("Organic/Coal Lithologies",implode($content->organic_coal_lithologies, ", "),30);
										}

										if($content->other_organic_coal_lithology!=""){
											$pdf->valueRow("Other Organic/Coal Lithology",$content->other_organic_coal_lithology,30);
										}

										if($content->volcaniclastic_type!=""){
											$pdf->valueRow("Volcaniclastic type",implode($content->volcaniclastic_type, ", "),30);
										}

										if($content->other_volcaniclastic_type!=""){
											$pdf->valueRow("Other Volcaniclastic Type",$content->other_volcaniclastic_type,30);
										}

										if($content->report_presence_of_particle_ag!=""){
											$pdf->valueRow("Report presence of particle aggregates ",$content->report_presence_of_particle_ag,30);
										}

										if($content->componentry!=""){
											$pdf->valueRow("Componentry",implode($content->componentry, ", "),30);
										}

										if($content->approximate_relative_abundance!=""){
											$pdf->valueRow("Approximate relative abundances of clasts",$content->approximate_relative_abundance,30);
										}

										if($content->phosphorite_type!=""){
											$pdf->valueRow("Phosphorite type",implode($content->phosphorite_type, ", "),30);
										}

										if($content->other_phosphorite_type!=""){
											$pdf->valueRow("Other Phosphorite Type",$content->other_phosphorite_type,30);
										}

										$pdf->valueTitle("Lithification & Color: ", 25);

										if($content->relative_resistance_weather!=""){
											$pdf->valueRow("Relative resistance (weathering profile)",$content->relative_resistance_weather,30);
										}

										if($content->lithification!=""){
											$pdf->valueRow("Lithification",$content->lithification,30);
										}

										if($content->evidence_of_deposit_alteration!=""){
											$pdf->valueRow("Evidence of deposit alteration",$content->evidence_of_deposit_alteration,30);
										}

										if($content->evidence_of_clast_alteration!=""){
											$pdf->valueRow("Evidence of clast alteration",$content->evidence_of_clast_alteration,30);
										}

										if($content->fresh_color!=""){
											$pdf->valueRow("Fresh Color",$content->fresh_color,30);
										}

										if($content->weathered_color!=""){
											$pdf->valueRow("Weathered Color",$content->weathered_color,30);
										}

										if($content->color_appearance!=""){
											$pdf->valueRow("Color Appearance",implode($content->color_appearance, ", "),30);
										}

										if($content->other_color_appearance!=""){
											$pdf->valueRow("Other Color Appearance",$content->other_color_appearance,30);
										}

										if($content->notes!=""){
											$pdf->valueRow("Notes",$content->notes,30);
										}

										$pdf->valueTitle("Composition: ", 25);

										if($content->minerals_present!=""){
											$pdf->valueRow("Minerals Present",implode($content->minerals_present, ", "),30);
										}

										if($content->other_minerals!=""){
											$pdf->valueRow("Other Mineral(s)",$content->other_minerals,30);
										}

										if($content->sandstone_type_dott!=""){
											$pdf->valueRow("Dott Classification",implode($content->sandstone_type_dott, ", "),30);
										}

										if($content->sandstone_type_folk_mcbride!=""){
											$pdf->valueRow("Folk/McBride Classification",implode($content->sandstone_type_folk_mcbride, ", "),30);
										}

										if($content->sandstone_modifier!=""){
											$pdf->valueRow("Sandstone modifier",implode($content->sandstone_modifier, ", "),30);
										}

										if($content->other_sandstone_modifier!=""){
											$pdf->valueRow("Other Sandstone Modifier",$content->other_sandstone_modifier,30);
										}

										if($content->skeletal_carbonate_components!=""){
											$pdf->valueRow("Skeletal Carbonate Components",implode($content->skeletal_carbonate_components, ", "),30);
										}

										if($content->other_skeletal_carbonate_component!=""){
											$pdf->valueRow("Other Skeletal Carbonate Component",$content->other_skeletal_carbonate_component,30);
										}

										if($content->skeletal_general_percent!=""){
											$pdf->valueRow("Skeletal (General) percent",$content->skeletal_general_percent,30);
										}

										if($content->mollusc_percent!=""){
											$pdf->valueRow("Mollusc percent",$content->mollusc_percent,30);
										}

										if($content->brachiopod_percent!=""){
											$pdf->valueRow("Brachiopod percent",$content->brachiopod_percent,30);
										}

										if($content->coral_percent!=""){
											$pdf->valueRow("Coral percent",$content->coral_percent,30);
										}

										if($content->echinoderm_percent!=""){
											$pdf->valueRow("Echinoderm percent",$content->echinoderm_percent,30);
										}

										if($content->bryozoan_percent!=""){
											$pdf->valueRow("Bryozoan percent",$content->bryozoan_percent,30);
										}

										if($content->calcareous_algae_percent!=""){
											$pdf->valueRow("Calcareous Algae percent",$content->calcareous_algae_percent,30);
										}

										if($content->foraminifera_percent!=""){
											$pdf->valueRow("Foraminifera percent",$content->foraminifera_percent,30);
										}

										if($content->stromatolite_percent!=""){
											$pdf->valueRow("Stromatolite percent",$content->stromatolite_percent,30);
										}

										if($content->thrombolite_percent!=""){
											$pdf->valueRow("Thrombolite percent",$content->thrombolite_percent,30);
										}

										if($content->dendrolite_percent!=""){
											$pdf->valueRow("Dendrolite percent",$content->dendrolite_percent,30);
										}

										if($content->leiolite_percent!=""){
											$pdf->valueRow("Leiolite percent",$content->leiolite_percent,30);
										}

										if($content->other_skeletal_carbonate_component_percent!=""){
											$pdf->valueRow("Other Skeletal Carbonate Component percent",$content->other_skeletal_carbonate_component_percent,30);
										}

										if($content->non_skeletal_carbonate_compone!=""){
											$pdf->valueRow("Non-Skeletal Carbonate Components",implode($content->non_skeletal_carbonate_compone, ", "),30);
										}

										if($content->other_non_skeletal_carbonate_component!=""){
											$pdf->valueRow("Other Non-Skeletal Carbonate Component",$content->other_non_skeletal_carbonate_component,30);
										}

										if($content->mud_percent!=""){
											$pdf->valueRow("Mud percent",$content->mud_percent,30);
										}

										if($content->cement_percent!=""){
											$pdf->valueRow("Cement percent",$content->cement_percent,30);
										}

										if($content->intraclast_percent!=""){
											$pdf->valueRow("Intraclast percent",$content->intraclast_percent,30);
										}

										if($content->peloid_percent!=""){
											$pdf->valueRow("Peloid percent",$content->peloid_percent,30);
										}

										if($content->ooid_percent!=""){
											$pdf->valueRow("Ooid percent",$content->ooid_percent,30);
										}

										if($content->oncoid_percent!=""){
											$pdf->valueRow("Oncoid percent",$content->oncoid_percent,30);
										}

										if($content->pisoid_percent!=""){
											$pdf->valueRow("Pisoid percent",$content->pisoid_percent,30);
										}

										if($content->coated_grian_percent!=""){
											$pdf->valueRow("Coated Grian percent",$content->coated_grian_percent,30);
										}

										if($content->grapestone_percent!=""){
											$pdf->valueRow("Grapestone percent",$content->grapestone_percent,30);
										}

										if($content->giant_ooid_percent!=""){
											$pdf->valueRow("Giant Ooid percent",$content->giant_ooid_percent,30);
										}

										if($content->seafloor_precipitate_percent!=""){
											$pdf->valueRow("Seafloor Precipitate percent",$content->seafloor_precipitate_percent,30);
										}

										if($content->molar_tooth_percent!=""){
											$pdf->valueRow("Molar Tooth percent",$content->molar_tooth_percent,30);
										}

										if($content->other_non_skeletal_component_percent!=""){
											$pdf->valueRow("Other Non-Skeletal Component percent",$content->other_non_skeletal_component_percent,30);
										}

										if($content->clay_or_mudstone_type!=""){
											$pdf->valueRow("Claystone or Mudstone Type",implode($content->clay_or_mudstone_type, ", "),30);
										}

										if($content->other_claystone_mudstone!=""){
											$pdf->valueRow("Other Claystone/Mudstone",$content->other_claystone_mudstone,30);
										}

										if($content->siliceous_mudstone_percent!=""){
											$pdf->valueRow("Siliceous mudstone percent",$content->siliceous_mudstone_percent,30);
										}

										if($content->siliceous_calcareous_mudstone_percent!=""){
											$pdf->valueRow("Siliceous calcareous mudstone percent",$content->siliceous_calcareous_mudstone_percent,30);
										}

										if($content->siliceous_volcanicla_tic_mudstone_percent!=""){
											$pdf->valueRow("Siliceous volcaniclastic mudstone percent",$content->siliceous_volcanicla_tic_mudstone_percent,30);
										}

										if($content->calcareous_mudstone_percent!=""){
											$pdf->valueRow("Calcareous mudstone percent",$content->calcareous_mudstone_percent,30);
										}

										if($content->black_shale_percent!=""){
											$pdf->valueRow("Black shale percent",$content->black_shale_percent,30);
										}

										if($content->red_clay_percent!=""){
											$pdf->valueRow("Red clay percent",$content->red_clay_percent,30);
										}

										if($content->red_mudstone_percent!=""){
											$pdf->valueRow("Red mudstone percent",$content->red_mudstone_percent,30);
										}

										if($content->green_mudstone_percent!=""){
											$pdf->valueRow("Green mudstone percent",$content->green_mudstone_percent,30);
										}

										if($content->variegated_mudstone_percent!=""){
											$pdf->valueRow("Variegated mudstone percent",$content->variegated_mudstone_percent,30);
										}

										if($content->marl_percent!=""){
											$pdf->valueRow("Marl percent",$content->marl_percent,30);
										}

										if($content->sarl_percent!=""){
											$pdf->valueRow("Sarl percent",$content->sarl_percent,30);
										}

										if($content->argillaceous_mudstone_percent!=""){
											$pdf->valueRow("Argillaceous mudstone percent",$content->argillaceous_mudstone_percent,30);
										}

										if($content->conglomerate_composition!=""){
											$pdf->valueRow("Conglomerate/Breccia Composition",implode($content->conglomerate_composition, ", "),30);
										}

										if($content->clast_composition!=""){
											$pdf->valueRow("Clast Composition",implode($content->clast_composition, ", "),30);
										}

										if($content->other_clast_types!=""){
											$pdf->valueRow("Other Clast Type(s)",$content->other_clast_types,30);
										}

										if($content->intrusive_igneous_clast_percent!=""){
											$pdf->valueRow("Intrusive igneous clast percent",$content->intrusive_igneous_clast_percent,30);
										}

										if($content->extrusive_igneous_clast_percent!=""){
											$pdf->valueRow("Volcanic clast percent",$content->extrusive_igneous_clast_percent,30);
										}

										if($content->metamorphic_clast_percent!=""){
											$pdf->valueRow("Metamorphic clast percent",$content->metamorphic_clast_percent,30);
										}

										if($content->mudstone_clast_percent!=""){
											$pdf->valueRow("Mudstone clast percent",$content->mudstone_clast_percent,30);
										}

										if($content->siltstone_clast_percent!=""){
											$pdf->valueRow("Siltstone clast percent",$content->siltstone_clast_percent,30);
										}

										if($content->sandstone_clast_percent!=""){
											$pdf->valueRow("Sandstone clast percent",$content->sandstone_clast_percent,30);
										}

										if($content->conglomerate_clast_percent!=""){
											$pdf->valueRow("Conglomerate clast percent",$content->conglomerate_clast_percent,30);
										}

										if($content->limestone_clast_percent!=""){
											$pdf->valueRow("Limestone clast percent",$content->limestone_clast_percent,30);
										}

										if($content->dolostone_clast_percent!=""){
											$pdf->valueRow("Dolostone clast percent",$content->dolostone_clast_percent,30);
										}

										if($content->wackestone_clast_percent!=""){
											$pdf->valueRow("Wackestone clast percent",$content->wackestone_clast_percent,30);
										}

										if($content->packstone_clast_percent!=""){
											$pdf->valueRow("Packstone clast percent",$content->packstone_clast_percent,30);
										}

										if($content->grainstone_clast_percent!=""){
											$pdf->valueRow("Grainstone clast percent",$content->grainstone_clast_percent,30);
										}

										if($content->boundstone_clast_percent!=""){
											$pdf->valueRow("Boundstone clast percent",$content->boundstone_clast_percent,30);
										}

										if($content->other_clast_percent!=""){
											$pdf->valueRow("Other clast percent",$content->other_clast_percent,30);
										}

										if($content->matrix_composition!=""){
											$pdf->valueRow("Matrix composition",implode($content->matrix_composition, ", "),30);
										}

										if($content->other_matrix_types!=""){
											$pdf->valueRow("Other Matrix Type(s)",$content->other_matrix_types,30);
										}

										if($content->intrusive_igneous_matrix_percent!=""){
											$pdf->valueRow("Intrusive igneous matrix percent",$content->intrusive_igneous_matrix_percent,30);
										}

										if($content->extrusive_igneous_matrix_percent!=""){
											$pdf->valueRow("Extrusive igneous matrix percent",$content->extrusive_igneous_matrix_percent,30);
										}

										if($content->metamorphic_igneous_matrix_percent!=""){
											$pdf->valueRow("Metamorphic igneous matrix percent",$content->metamorphic_igneous_matrix_percent,30);
										}

										if($content->mudstone_matrix_percent!=""){
											$pdf->valueRow("Mudstone matrix percent",$content->mudstone_matrix_percent,30);
										}

										if($content->siltstone_matrix_percent!=""){
											$pdf->valueRow("Siltstone matrix percent",$content->siltstone_matrix_percent,30);
										}

										if($content->sandstone_matrix_percent!=""){
											$pdf->valueRow("Sandstone matrix percent",$content->sandstone_matrix_percent,30);
										}

										if($content->conglomerate_matrix_percent!=""){
											$pdf->valueRow("Conglomerate matrix percent",$content->conglomerate_matrix_percent,30);
										}

										if($content->carbonate_matrix_type!=""){
											$pdf->valueRow("Carbonate Matrix Type",implode($content->carbonate_matrix_type, ", "),30);
										}

										if($content->limestone_matrix_percent!=""){
											$pdf->valueRow("Limestone matrix percent",$content->limestone_matrix_percent,30);
										}

										if($content->dolostone_matrix_percent!=""){
											$pdf->valueRow("Dolostone matrix percent",$content->dolostone_matrix_percent,30);
										}

										if($content->skeletal_matrix_percent!=""){
											$pdf->valueRow("Skeletal matrix percent",$content->skeletal_matrix_percent,30);
										}

										if($content->wackestone_matrix_percent!=""){
											$pdf->valueRow("Wackestone matrix percent",$content->wackestone_matrix_percent,30);
										}

										if($content->packstone_matrix_percent!=""){
											$pdf->valueRow("Packstone matrix percent",$content->packstone_matrix_percent,30);
										}

										if($content->grainstone_matrix_percent!=""){
											$pdf->valueRow("Grainstone matrix percent",$content->grainstone_matrix_percent,30);
										}

										if($content->boundstone_matrix_percent!=""){
											$pdf->valueRow("Boundstone matrix percent",$content->boundstone_matrix_percent,30);
										}

										if($content->other_carbonate_matrix_percent!=""){
											$pdf->valueRow("Other carbonate matrix percent",$content->other_carbonate_matrix_percent,30);
										}

										if($content->other_matrix_percent!=""){
											$pdf->valueRow("Other matrix percent",$content->other_matrix_percent,30);
										}

										if($content->volcaniclastic_type!=""){
											$pdf->valueRow("Volcaniclastic type",implode($content->volcaniclastic_type, ", "),30);
										}

										if($content->other_volcaniclastic_type!=""){
											$pdf->valueRow("Other Volcaniclastic Type",$content->other_volcaniclastic_type,30);
										}

										if($content->glass_percent!=""){
											$pdf->valueRow("Glass percent",$content->glass_percent,30);
										}

										if($content->crystals_percent!=""){
											$pdf->valueRow("Crystals percent",$content->crystals_percent,30);
										}

										if($content->lithic_fragments_percent!=""){
											$pdf->valueRow("Lithic fragments percent",$content->lithic_fragments_percent,30);
										}

										if($content->volcanic_mudstone_percent!=""){
											$pdf->valueRow("Volcanic mudstone percent",$content->volcanic_mudstone_percent,30);
										}

										if($content->volcanic_sandstone_percent!=""){
											$pdf->valueRow("Volcanic sandstone percent",$content->volcanic_sandstone_percent,30);
										}

										if($content->lapillistone_percent!=""){
											$pdf->valueRow("Lapillistone percent",$content->lapillistone_percent,30);
										}

										if($content->agglomerate_percent!=""){
											$pdf->valueRow("Agglomerate percent",$content->agglomerate_percent,30);
										}

										if($content->volcanic_breccia_percent!=""){
											$pdf->valueRow("Volcanic breccia percent",$content->volcanic_breccia_percent,30);
										}

										if($content->bentonite_percent!=""){
											$pdf->valueRow("Bentonite percent",$content->bentonite_percent,30);
										}

										if($content->tuff_percent!=""){
											$pdf->valueRow("Tuff percent",$content->tuff_percent,30);
										}

										if($content->welded_tuff_percent!=""){
											$pdf->valueRow("Welded tuff percent",$content->welded_tuff_percent,30);
										}

										if($content->ignimbrite_percent!=""){
											$pdf->valueRow("Ignimbrite percent",$content->ignimbrite_percent,30);
										}

										if($content->other_volcaniclastic_type_percent!=""){
											$pdf->valueRow("Other volcaniclastic type percent",$content->other_volcaniclastic_type_percent,30);
										}

										if($content->evaporite_type!=""){
											$pdf->valueRow("Evaporite type",implode($content->evaporite_type, ", "),30);
										}

										if($content->gypsum_anhydrite_primary_percent!=""){
											$pdf->valueRow("Gypsum - anhydrite primary percent",$content->gypsum_anhydrite_primary_percent,30);
										}

										if($content->gypsum_anhydrite_primary_type!=""){
											$pdf->valueRow("Gypsum - anhydrite primary type",implode($content->gypsum_anhydrite_primary_type, ", "),30);
										}

										if($content->gypsum_anhydrite_diagenetic_percent!=""){
											$pdf->valueRow("Gypsum - anhydrite diagenetic percent",$content->gypsum_anhydrite_diagenetic_percent,30);
										}

										if($content->gypsum_anhydrite_diagenetic_type!=""){
											$pdf->valueRow("Gypsum - anhydrite diagenetic type",implode($content->gypsum_anhydrite_diagenetic_type, ", "),30);
										}

										if($content->halite_primary_percent!=""){
											$pdf->valueRow("Halite - primary percent",$content->halite_primary_percent,30);
										}

										if($content->halite_primary_type!=""){
											$pdf->valueRow("Halite - primary type",implode($content->halite_primary_type, ", "),30);
										}

										if($content->halite_diagenetic_percent!=""){
											$pdf->valueRow("Halite - diagenetic percent",$content->halite_diagenetic_percent,30);
										}

										if($content->halite_diagenetic_type!=""){
											$pdf->valueRow("Halite - diagenetic type",implode($content->halite_diagenetic_type, ", "),30);
										}

										if($content->phosphorite_type!=""){
											$pdf->valueRow("Phosphorite type",implode($content->phosphorite_type, ", "),30);
										}

										if($content->organic_coal_lithologies!=""){
											$pdf->valueRow("Organic/Coal Lithologies",implode($content->organic_coal_lithologies, ", "),30);
										}

										if($content->amber_percent!=""){
											$pdf->valueRow("Amber percent",$content->amber_percent,30);
										}

										if($content->peat_percent!=""){
											$pdf->valueRow("Peat percent",$content->peat_percent,30);
										}

										if($content->lignite_percent!=""){
											$pdf->valueRow("Lignite percent",$content->lignite_percent,30);
										}

										if($content->subbituminous_percent!=""){
											$pdf->valueRow("Subbituminous percent",$content->subbituminous_percent,30);
										}

										if($content->bituminous_percent!=""){
											$pdf->valueRow("Bituminous percent",$content->bituminous_percent,30);
										}

										if($content->coal_ball_percent!=""){
											$pdf->valueRow("Coal ball percent",$content->coal_ball_percent,30);
										}

										if($content->tar_percent!=""){
											$pdf->valueRow("Tar percent",$content->tar_percent,30);
										}

										if($content->notes!=""){
											$pdf->valueRow("Notes",$content->notes,30);
										}

										$pdf->valueTitle("Texture: ", 25);

										if($content->mud_silt_grain_size!=""){
											$pdf->valueRow("Mudstone/Siltstone Grain Size",$content->mud_silt_grain_size,30);
										}

										if($content->sand_grain_size!=""){
											$pdf->valueRow("Sandstone Grain Size",$content->sand_grain_size,30);
										}

										if($content->congl_grain_size!=""){
											$pdf->valueRow("Conglomerate Grain Size",$content->congl_grain_size,30);
										}

										if($content->breccia_grain_size!=""){
											$pdf->valueRow("Breccia Grain Size",$content->breccia_grain_size,30);
										}

										if($content->grain_size_range!=""){
											$pdf->valueRow("Grain Size Range",implode($content->grain_size_range, ", "),30);
										}

										if($content->clay_percent!=""){
											$pdf->valueRow("Clay percent",$content->clay_percent,30);
										}

										if($content->silt_percent!=""){
											$pdf->valueRow("Silt percent",$content->silt_percent,30);
										}

										if($content->sand_very_fine_percent!=""){
											$pdf->valueRow("Sand - very fine percent",$content->sand_very_fine_percent,30);
										}

										if($content->sand_fine_lower_percent!=""){
											$pdf->valueRow("Sand - fine lower percent",$content->sand_fine_lower_percent,30);
										}

										if($content->sand_fine_upper_percent!=""){
											$pdf->valueRow("Sand - fine upper percent",$content->sand_fine_upper_percent,30);
										}

										if($content->sand_medium_lower_percent!=""){
											$pdf->valueRow("Sand - medium lower percent",$content->sand_medium_lower_percent,30);
										}

										if($content->sand_medium_upper_percent!=""){
											$pdf->valueRow("Sand - medium upper percent",$content->sand_medium_upper_percent,30);
										}

										if($content->sand_coarse_lower_percent!=""){
											$pdf->valueRow("Sand - coarse lower percent",$content->sand_coarse_lower_percent,30);
										}

										if($content->sand_coarse_upper_percent!=""){
											$pdf->valueRow("Sand - coarse upper percent",$content->sand_coarse_upper_percent,30);
										}

										if($content->sand_very_coarse_percent!=""){
											$pdf->valueRow("Sand - very coarse percent",$content->sand_very_coarse_percent,30);
										}

										if($content->granule_percent!=""){
											$pdf->valueRow("Granule percent",$content->granule_percent,30);
										}

										if($content->pebble_percent!=""){
											$pdf->valueRow("Pebble percent",$content->pebble_percent,30);
										}

										if($content->cobble_percent!=""){
											$pdf->valueRow("Cobble percent",$content->cobble_percent,30);
										}

										if($content->boulder_percent!=""){
											$pdf->valueRow("Boulder percent",$content->boulder_percent,30);
										}

										if($content->maximum_clast_size_cm!=""){
											$pdf->valueRow("Maximum Clast Size (cm)",$content->maximum_clast_size_cm,30);
										}

										if($content->minimum_clast_size_cm!=""){
											$pdf->valueRow("Minimum Clast Size (cm)",$content->minimum_clast_size_cm,30);
										}

										if($content->average_clast_size_cm!=""){
											$pdf->valueRow("Average Clast Size (cm)",$content->average_clast_size_cm,30);
										}

										if($content->matrix_size!=""){
											$pdf->valueRow("Matrix Size",implode($content->matrix_size, ", "),30);
										}

										if($content->character!=""){
											$pdf->valueRow("Character",implode($content->character, ", "),30);
										}

										if($content->sorting!=""){
											$pdf->valueRow("Sorting",implode($content->sorting, ", "),30);
										}

										if($content->rounding!=""){
											$pdf->valueRow("Rounding",implode($content->rounding, ", "),30);
										}

										if($content->shape!=""){
											$pdf->valueRow("Shape",implode($content->shape, ", "),30);
										}

										if($content->other_shape!=""){
											$pdf->valueRow("Other Shape",$content->other_shape,30);
										}

										if($content->notes!=""){
											$pdf->valueRow("Notes",$content->notes,30);
										}

										if($content->stratification!=""){
											$pdf->valueRow("Stratification",implode($content->stratification, ", "),30);
										}

										if($content->laminae_thickness_i_select_more_than_one!=""){
											$pdf->valueRow("Laminae Thickness",implode($content->laminae_thickness_i_select_more_than_one, ", "),30);
										}

										if($content->lamination_character!=""){
											$pdf->valueRow("Lamination Character",implode($content->lamination_character, ", "),30);
										}

										if($content->bedding_thickness!=""){
											$pdf->valueRow("Bedding Thickness",implode($content->bedding_thickness, ", "),30);
										}

										if($content->bedding_character!=""){
											$pdf->valueRow("Bedding character",implode($content->bedding_character, ", "),30);
										}

										if($content->package_geometry!=""){
											$pdf->valueRow("Package Geometry",implode($content->package_geometry, ", "),30);
										}

										if($content->package_lateral_extent!=""){
											$pdf->valueRow("Package Lateral Extent",$content->package_lateral_extent,30);
										}

										if($content->package_bedding_trends!=""){
											$pdf->valueRow("Package Bedding Trends",$content->package_bedding_trends,30);
										}

										if($content->other_bedding_trend!=""){
											$pdf->valueRow("Other bedding trend",$content->other_bedding_trend,30);
										}

										if($content->shape_of_lower_contacts!=""){
											$pdf->valueRow("Shape of lower contact(s)",implode($content->shape_of_lower_contacts, ", "),30);
										}

										if($content->character_of_lower_contacts!=""){
											$pdf->valueRow("Character of lower contact(s)",implode($content->character_of_lower_contacts, ", "),30);
										}

										if($content->lower_contact_relief!=""){
											$pdf->valueRow("Lower contact relief",$content->lower_contact_relief,30);
										}

										if($content->shape_of_upper_contacts!=""){
											$pdf->valueRow("Shape of upper contact(s)",implode($content->shape_of_upper_contacts, ", "),30);
										}

										if($content->character_of_upper_contacts!=""){
											$pdf->valueRow("Character of upper contact(s)",implode($content->character_of_upper_contacts, ", "),30);
										}

										if($content->upper_contact_relief!=""){
											$pdf->valueRow("Upper Contact Relief",$content->upper_contact_relief,30);
										}

										if($content->interbed_proportion!=""){
											$pdf->valueRow("Interbed Relative Proportion (%)",$content->interbed_proportion,30);
										}

										if($content->interbed_thickness!=""){
											$pdf->valueRow("Interbed Thickness",$content->interbed_thickness,30);
										}

										if($content->interbed_proportion_change!=""){
											$pdf->valueRow("Interbed Proportion Change (Up Section)",$content->interbed_proportion_change,30);
										}

										if($content->interbed_thickness_change!=""){
											$pdf->valueRow("Interbed Thickness Change (Up Section)",$content->interbed_thickness_change,30);
										}

										if($content->Notes!=""){
											$pdf->valueRow("Notes",$content->Notes,30);
										}

									}

								}

								if($spot['sed']->bedding){
									$pdf->valueTitle("Bedding: ", 20);
									$content = $spot['sed']->bedding;

									if($content->interbed_proportion_change!=""){
										$pdf->valueRow("Interbed Proportion Change (Up Section)",$content->interbed_proportion_change,25);
									}

									if($content->interbed_proportion!=""){
										$pdf->valueRow("Lithology 1: Interbed Relative Proportion (%)",$content->interbed_proportion,25);
									}

									if($content->lithology_at_bottom_contact!=""){
										$pdf->valueRow("Which Lithology is at Bottom Contact?",$content->lithology_at_bottom_contact,25);
									}

									if($content->lithology_at_top_contact!=""){
										$pdf->valueRow("Which Lithology is at Top Contact?",$content->lithology_at_top_contact,25);
									}

									if($content->thickness_of_individual_beds!=""){
										$pdf->valueRow("Thickness of Individual Beds",$content->thickness_of_individual_beds,25);
									}

									if($content->package_thickness_units!=""){
										$pdf->valueRow("Package Beds Thickness Units",$content->package_thickness_units,25);
									}

									if($content->package_bedding_trends!=""){
										$pdf->valueRow("Package Bedding Trends",$content->package_bedding_trends,25);
									}

									if($content->other_bedding_trend!=""){
										$pdf->valueRow("Other Package Bedding Trend",$content->other_bedding_trend,25);
									}

									$beds = $spot['sed']->bedding->beds;
									foreach($beds as $content){

										if($content->package_geometry!=""){
											$pdf->valueRow("Bed Geometry",implode($content->package_geometry, ", "),25);
										}

										$pdf->valueTitle("Lower Contact: ", 25);

										if($content->shape_of_lower_contacts!=""){
											$pdf->valueRow("Shape of lower contact",implode($content->shape_of_lower_contacts, ", "),30);
										}

										if($content->character_of_lower_contacts!=""){
											$pdf->valueRow("Character of lower contact(s)",implode($content->character_of_lower_contacts, ", "),30);
										}

										if($content->lower_contact_relief!=""){
											$pdf->valueRow("Lower contact relief",$content->lower_contact_relief,30);
										}

										$pdf->valueTitle("Upper Contact: ", 25);

										if($content->shape_of_upper_contacts!=""){
											$pdf->valueRow("Shape of upper contact",implode($content->shape_of_upper_contacts, ", "),30);
										}

										if($content->character_of_upper_contacts!=""){
											$pdf->valueRow("Character of upper contact",implode($content->character_of_upper_contacts, ", "),30);
										}

										if($content->upper_contact_relief!=""){
											$pdf->valueRow("Upper Contact Relief",$content->upper_contact_relief,30);
										}

										$pdf->valueTitle("Interbed Thickness: ", 25);

										if($content->avg_thickness!=""){
											$pdf->valueRow("Average Thickness",$content->avg_thickness,30);
										}

										if($content->max_thickness!=""){
											$pdf->valueRow("Maximum Thickness",$content->max_thickness,30);
										}

										if($content->min_thickness!=""){
											$pdf->valueRow("Minimum Thickness",$content->min_thickness,30);
										}

										if($content->interbed_thickness_units!=""){
											$pdf->valueRow("Interbed Thickness Units",$content->interbed_thickness_units,30);
										}

										if($content->notes!=""){
											$pdf->valueRow("Notes",$content->notes,30);
										}

									}
								}

								if($spot['sed']->structures){
									$pdf->valueTitle("Structures: ", 20);
									$contents = $spot['sed']->structures;
									foreach($contents as $content){

										if($content->massive_structureless!=""){
											$pdf->valueRow("Massive/Structureless?",$content->massive_structureless,25);
										}

										$pdf->valueTitle("Cross Bedding: ", 25);

										if($content->cross_bedding_type!=""){
											$pdf->valueRow("Cross Bedding Type",implode($content->cross_bedding_type, ", "),30);
										}

										if($content->cross_bedding_height_cm!=""){
											$pdf->valueRow("Cross Bedding Height (cm)",$content->cross_bedding_height_cm,30);
										}

										if($content->cross_bedding_width_cm!=""){
											$pdf->valueRow("Cross Bedding Width (cm)",$content->cross_bedding_width_cm,30);
										}

										if($content->cross_bedding_thickness_cm!=""){
											$pdf->valueRow("Cross Bedding Thickness (cm)",$content->cross_bedding_thickness_cm,30);
										}

										if($content->cross_bedding_spacing_cm!=""){
											$pdf->valueRow("Cross Bedding Spacing (cm)",$content->cross_bedding_spacing_cm,30);
										}

										$pdf->valueTitle("Ripple Lamination: ", 25);

										if($content->ripple_lamination_type!=""){
											$pdf->valueRow("Ripple Lamination Type",implode($content->ripple_lamination_type, ", "),30);
										}

										if($content->other_ripple_lamination_type!=""){
											$pdf->valueRow("Other Ripple Lamination Type",$content->other_ripple_lamination_type,30);
										}

										if($content->ripple_lamination_height_mm!=""){
											$pdf->valueRow("Ripple Lamination Height (mm)",$content->ripple_lamination_height_mm,30);
										}

										if($content->ripple_lamination_width_mm!=""){
											$pdf->valueRow("Ripple Lamination Width (mm)",$content->ripple_lamination_width_mm,30);
										}

										if($content->ripple_lamination_thick_mm!=""){
											$pdf->valueRow("Ripple Lmation Thickness (mm)",$content->ripple_lamination_thick_mm,30);
										}

										if($content->ripple_lamination_spacing_mm!=""){
											$pdf->valueRow("Ripple Lamination Spacing (mm)",$content->ripple_lamination_spacing_mm,30);
										}

										$pdf->valueTitle("Horizontal Bedding: ", 25);

										if($content->horizontal_bedding_type!=""){
											$pdf->valueRow("Horizontal Bedding Type",implode($content->horizontal_bedding_type, ", "),30);
										}

										if($content->other_horizontal_bedding_type!=""){
											$pdf->valueRow("Other Horizontal Bedding Type",$content->other_horizontal_bedding_type,30);
										}

										$pdf->valueTitle("Graded Bedding: ", 25);

										if($content->graded_bedding_type!=""){
											$pdf->valueRow("Graded Bedding Type",$content->graded_bedding_type,30);
										}

										$pdf->valueTitle("Deformation Structures: ", 25);

										if($content->deformation_structures!=""){
											$pdf->valueRow("Deformation Structure Type",implode($content->deformation_structures, ", "),30);
										}

										if($content->other_deformation_structure_type!=""){
											$pdf->valueRow("Other Deformation Structure Type",$content->other_deformation_structure_type,30);
										}

										$pdf->valueTitle("Lags: ", 25);

										if($content->lag_type!=""){
											$pdf->valueRow("Lag Type",implode($content->lag_type, ", "),30);
										}

										if($content->other_lag_type!=""){
											$pdf->valueRow("Other Lag Type",$content->other_lag_type,30);
										}

										if($content->clast_composition!=""){
											$pdf->valueRow("Clast Composition",$content->clast_composition,30);
										}

										if($content->clast_size!=""){
											$pdf->valueRow("Clast Size",$content->clast_size,30);
										}

										if($content->layer_thickness_shape!=""){
											$pdf->valueRow("Layer Thickness/Shape",$content->layer_thickness_shape,30);
										}

										$pdf->valueTitle("Other Common Structures: ", 25);

										if($content->other_common_structures!=""){
											$pdf->valueRow("Other Common Structure Type",implode($content->other_common_structures, ", "),30);
										}

										if($content->bouma_sequence_part!=""){
											$pdf->valueRow("Bouma Sequence Part",implode($content->bouma_sequence_part, ", "),30);
										}

										if($content->bioturbation_index!=""){
											$pdf->valueRow("Bioturbation Index",$content->bioturbation_index,30);
										}

										if($content->bedding_plane_features!=""){
											$pdf->valueRow("Bedding plane features",implode($content->bedding_plane_features, ", "),30);
										}

										if($content->other_bedding_plane_feature!=""){
											$pdf->valueRow("Other Bedding Plane Feature",$content->other_bedding_plane_feature,30);
										}

										if($content->bedding_plane_features_scale!=""){
											$pdf->valueRow("Bedding Plane Features Scale",$content->bedding_plane_features_scale,30);
										}

										if($content->bedding_plane_features_orientation!=""){
											$pdf->valueRow("Bedding Plane Features Orientation",$content->bedding_plane_features_orientation,30);
										}

										if($content->bedform_type!=""){
											$pdf->valueRow("Bedform Type",implode($content->bedform_type, ", "),30);
										}

										if($content->other_bedform_type!=""){
											$pdf->valueRow("Other Bedform Type",$content->other_bedform_type,30);
										}

										if($content->bedform_scale!=""){
											$pdf->valueRow("Bedform Scale",$content->bedform_scale,30);
										}

										if($content->crest_orientation_azimuth_0_360!=""){
											$pdf->valueRow("Crest Orientation",$content->crest_orientation_azimuth_0_360,30);
										}

										$pdf->valueTitle("Pedogenic Structures: ", 25);

										if($content->paleosol_horizons!=""){
											$pdf->valueRow("Master Paleosol Horizons",implode($content->paleosol_horizons, ", "),30);
										}

										if($content->other_horizon!=""){
											$pdf->valueRow("Other Horizon",$content->other_horizon,30);
										}

										if($content->o_horizon_thickness_cm!=""){
											$pdf->valueRow("O Horizon thickness (cm)",$content->o_horizon_thickness_cm,30);
										}

										if($content->a_horizon_thickness_cm!=""){
											$pdf->valueRow("A Horizon thickness (cm)",$content->a_horizon_thickness_cm,30);
										}

										if($content->e_horizon_thickness_cm!=""){
											$pdf->valueRow("E Horizon thickness (cm)",$content->e_horizon_thickness_cm,30);
										}

										if($content->b_horizon_thickness_cm!=""){
											$pdf->valueRow("B Horizon thickness (cm)",$content->b_horizon_thickness_cm,30);
										}

										if($content->k_horizon_thickness_cm!=""){
											$pdf->valueRow("K Horizon thickness (cm)",$content->k_horizon_thickness_cm,30);
										}

										if($content->c_horizon_thickness_cm!=""){
											$pdf->valueRow("C Horizon thickness (cm)",$content->c_horizon_thickness_cm,30);
										}

										if($content->r_horizon_thickness_cm!=""){
											$pdf->valueRow("R Horizon thickness (cm)",$content->r_horizon_thickness_cm,30);
										}

										if($content->compound_thickness_cm!=""){
											$pdf->valueRow("Compound thickness (cm)",$content->compound_thickness_cm,30);
										}

										if($content->composite_thickness_cm!=""){
											$pdf->valueRow("Composite thickness (cm)",$content->composite_thickness_cm,30);
										}

										if($content->paleosol_structures!=""){
											$pdf->valueRow("Paleosol structures",implode($content->paleosol_structures, ", "),30);
										}

										if($content->other_structure!=""){
											$pdf->valueRow("Other Paleosol Structure",$content->other_structure,30);
										}

										if($content->additional_modifiers!=""){
											$pdf->valueRow("Additional modifiers",$content->additional_modifiers,30);
										}

										if($content->paleosol_classification!=""){
											$pdf->valueRow("Paleosol classification",implode($content->paleosol_classification, ", "),30);
										}

										if($content->notes!=""){
											$pdf->valueRow("Notes",$content->notes,30);
										}

									}

								}

								if($spot['sed']->diagenesis){
									$pdf->valueTitle("Diagenesis: ", 20);
									$contents = $spot['sed']->diagenesis;
									foreach($contents as $content){

										$pdf->valueTitle("Cement: ", 25);

										if($content->cement_composition!=""){
											$pdf->valueRow("Cement Mineralogy",implode($content->cement_composition, ", "),30);
										}

										if($content->other_cement_composition!=""){
											$pdf->valueRow("Other Cement Mineralogy",$content->other_cement_composition,30);
										}

										$pdf->valueTitle("Veins: ", 25);

										if($content->vein_type!=""){
											$pdf->valueRow("Vein Type",$content->vein_type,30);
										}

										if($content->vein_width!=""){
											$pdf->valueRow("Vein Width (cm)",$content->vein_width,30);
										}

										if($content->vein_length!=""){
											$pdf->valueRow("Vein Length (cm)",$content->vein_length,30);
										}

										if($content->vein_orientation!=""){
											$pdf->valueRow("Vein Orientation",$content->vein_orientation,30);
										}

										if($content->vein_mineralogy!=""){
											$pdf->valueRow("Vein Mineralogy",$content->vein_mineralogy,30);
										}

										if($content->other_vein_mineralogy!=""){
											$pdf->valueRow("Other Vein Mineralogy",$content->other_vein_mineralogy,30);
										}

										$pdf->valueTitle("Fractures: ", 25);

										if($content->fracture_type!=""){
											$pdf->valueRow("Fracture Type",$content->fracture_type,30);
										}

										if($content->fracture_width!=""){
											$pdf->valueRow("Fracture Width (cm)",$content->fracture_width,30);
										}

										if($content->fracture_length!=""){
											$pdf->valueRow("Fracture Length (cm)",$content->fracture_length,30);
										}

										if($content->fracture_orientation!=""){
											$pdf->valueRow("Fracture Orientation",$content->fracture_orientation,30);
										}

										if($content->fracture_mineralogy!=""){
											$pdf->valueRow("Fracture Mineralogy",$content->fracture_mineralogy,30);
										}

										if($content->other_fracture_mineralogy!=""){
											$pdf->valueRow("Other Fracture Mineralogy",$content->other_fracture_mineralogy,30);
										}

										$pdf->valueTitle("Nodules/Concretions: ", 25);

										if($content->nodules_concretions_size!=""){
											$pdf->valueRow("Nodules/Concretions Size",$content->nodules_concretions_size,30);
										}

										if($content->min!=""){
											$pdf->valueRow("Min",$content->min,30);
										}

										if($content->max!=""){
											$pdf->valueRow("Max",$content->max,30);
										}

										if($content->average!=""){
											$pdf->valueRow("Average",$content->average,30);
										}

										if($content->nodules_concretions_shape!=""){
											$pdf->valueRow("Nodules/Concretions Shape",implode($content->nodules_concretions_shape, ", "),30);
										}

										if($content->other_nodules_concretion_shape!=""){
											$pdf->valueRow("Other Nodule/Concretions Shape",$content->other_nodules_concretion_shape,30);
										}

										if($content->spacing!=""){
											$pdf->valueRow("Spacing",$content->spacing,30);
										}

										if($content->nodules_concretions_type!=""){
											$pdf->valueRow("Nodules/Concretions Type",$content->nodules_concretions_type,30);
										}

										if($content->other_nodules_concretions_type!=""){
											$pdf->valueRow("Other Nodules/Concretions Type",$content->other_nodules_concretions_type,30);
										}

										if($content->nodules_concretions_comp!=""){
											$pdf->valueRow("Nodules/Concretions Composition",implode($content->nodules_concretions_comp, ", "),30);
										}

										if($content->other_nodules_concretion_comp!=""){
											$pdf->valueRow("Other Nodules/Concretions Composition",$content->other_nodules_concretion_comp,30);
										}

										$pdf->valueTitle("Replacement: ", 25);

										if($content->replacement_type!=""){
											$pdf->valueRow("Replacement Type",$content->replacement_type,30);
										}

										if($content->other_replacement_type!=""){
											$pdf->valueRow("Other Replacement Type",$content->other_replacement_type,30);
										}

										$pdf->valueTitle("Recrystallization: ", 25);

										if($content->recrystallization_type!=""){
											$pdf->valueRow("Recrystallization Type",$content->recrystallization_type,30);
										}

										if($content->other_recrystallization_type!=""){
											$pdf->valueRow("Other Recrystallization Type",$content->other_recrystallization_type,30);
										}

										$pdf->valueTitle("Other Diagenetic Features: ", 25);

										if($content->other_diagenetic_features!=""){
											$pdf->valueRow("Other Diagenetic Features",implode($content->other_diagenetic_features, ", "),30);
										}

										if($content->other_features!=""){
											$pdf->valueRow("Other Features",$content->other_features,30);
										}

										$pdf->valueTitle("Porosity type: ", 25);

										if($content->fabric_selective!=""){
											$pdf->valueRow("Fabric Selective",implode($content->fabric_selective, ", "),30);
										}

										if($content->other_fabric_selective!=""){
											$pdf->valueRow("Other Fabric Selective",$content->other_fabric_selective,30);
										}

										if($content->non_selective!=""){
											$pdf->valueRow("Non-Frabric Selective",implode($content->non_selective, ", "),30);
										}

										if($content->other_non_selective!=""){
											$pdf->valueRow("Other Non-Fabric Selective",$content->other_non_selective,30);
										}

										$pdf->valueTitle("Carbonate Desiccation and Dissolution: ", 25);

										if($content->carbonate_desicc_and_diss!=""){
											$pdf->valueRow("Carbonate Desiccation and Dissolution Type",implode($content->carbonate_desicc_and_diss, ", "),30);
										}

										if($content->other_carbonate_desicc_diss!=""){
											$pdf->valueRow("Other Carbonate Desiccation and Dissolution Type",$content->other_carbonate_desicc_diss,30);
										}

										if($content->notes!=""){
											$pdf->valueRow("Notes",$content->notes,30);
										}

									}
								}

								if($spot['sed']->fossils){
									$pdf->valueTitle("Fossils: ", 20);
									$contents = $spot['sed']->fossils;
									foreach($contents as $content){

										$pdf->valueTitle("Body: ", 25);

										if($content->invertebrate!=""){
											$pdf->valueRow("Invertebrate",implode($content->invertebrate, ", "),30);
										}

										if($content->other_invertebrate!=""){
											$pdf->valueRow("Other Invertebrate",$content->other_invertebrate,30);
										}

										if($content->mollusca!=""){
											$pdf->valueRow("Mollusc",implode($content->mollusca, ", "),30);
										}

										if($content->other_mollusca!=""){
											$pdf->valueRow("Other Mollusc",$content->other_mollusca,30);
										}

										if($content->arthropoda!=""){
											$pdf->valueRow("Arthropod",implode($content->arthropoda, ", "),30);
										}

										if($content->other_anthropoda!=""){
											$pdf->valueRow("Other Arthropod",$content->other_anthropoda,30);
										}

										if($content->echinodermata!=""){
											$pdf->valueRow("Echinoderm",implode($content->echinodermata, ", "),30);
										}

										if($content->other_echinodermata!=""){
											$pdf->valueRow("Other Echinoderm",$content->other_echinodermata,30);
										}

										if($content->cnidaria!=""){
											$pdf->valueRow("Cnidarian",implode($content->cnidaria, ", "),30);
										}

										if($content->other_cnidaria!=""){
											$pdf->valueRow("Other Cnidarian",$content->other_cnidaria,30);
										}

										if($content->chordate!=""){
											$pdf->valueRow("Chordate",$content->chordate,30);
										}

										if($content->other_chordata!=""){
											$pdf->valueRow("Other Chordate",$content->other_chordata,30);
										}

										if($content->protista!=""){
											$pdf->valueRow("Protist",implode($content->protista, ", "),30);
										}

										if($content->other_protista!=""){
											$pdf->valueRow("Other Protist",$content->other_protista,30);
										}

										if($content->calcimicrobe!=""){
											$pdf->valueRow("Calcimicrobe",implode($content->calcimicrobe, ", "),30);
										}

										if($content->other_calcimicrobe!=""){
											$pdf->valueRow("Other Calcimicrobe",$content->other_calcimicrobe,30);
										}

										if($content->plant_algae!=""){
											$pdf->valueRow("Plant/algae",implode($content->plant_algae, ", "),30);
										}

										if($content->other_plant_algae!=""){
											$pdf->valueRow("Other Plant/Algae",$content->other_plant_algae,30);
										}

										if($content->green_algae!=""){
											$pdf->valueRow("Green Algae",implode($content->green_algae, ", "),30);
										}

										if($content->other_green_algae!=""){
											$pdf->valueRow("Other Green Algae",$content->other_green_algae,30);
										}

										if($content->vertebrate!=""){
											$pdf->valueRow("Vertebrate",implode($content->vertebrate, ", "),30);
										}

										if($content->other_vertebrate!=""){
											$pdf->valueRow("Other Vertebrate",$content->other_vertebrate,30);
										}

										if($content->faunal_assemblage!=""){
											$pdf->valueRow("Faunal assemblage",$content->faunal_assemblage,30);
										}

										if($content->other_faunal_assemblage!=""){
											$pdf->valueRow("Other faunal assemblage",$content->other_faunal_assemblage,30);
										}

										$pdf->valueTitle("Trace: ", 25);

										if($content->diversity!=""){
											$pdf->valueRow("Diversity",$content->diversity,30);
										}

										if($content->descriptive!=""){
											$pdf->valueRow("Descriptive",implode($content->descriptive, ", "),30);
										}

										if($content->other_descriptive!=""){
											$pdf->valueRow("Other Descriptive",$content->other_descriptive,30);
										}

										if($content->burrow_fill_type!=""){
											$pdf->valueRow("Burrow Fill Type",implode($content->burrow_fill_type, ", "),30);
										}

										if($content->other_burrow_fill!=""){
											$pdf->valueRow("Other Burrow Fill Type",$content->other_burrow_fill,30);
										}

										if($content->behavioral_grouping!=""){
											$pdf->valueRow("Behavioral grouping",$content->behavioral_grouping,30);
										}

										if($content->other_behavioral_grouping!=""){
											$pdf->valueRow("Other Behavioral Grouping",$content->other_behavioral_grouping,30);
										}

										if($content->ichnofacies!=""){
											$pdf->valueRow("Ichnofacies",$content->ichnofacies,30);
										}

										if($content->other_ichnofacies!=""){
											$pdf->valueRow("Other Ichnofacies",$content->other_ichnofacies,30);
										}

										if($content->list_of_specific_types!=""){
											$pdf->valueRow("List of specific types",$content->list_of_specific_types,30);
										}

										$pdf->valueTitle("Biogenic Growth Structures: ", 25);

										if($content->dominant_component!=""){
											$pdf->valueRow("Dominant component",$content->dominant_component,30);
										}

										if($content->other_dominant_component!=""){
											$pdf->valueRow("Other Dominant Component",$content->other_dominant_component,30);
										}

										if($content->microbial_reef_or_skelatal_mic!=""){
											$pdf->valueRow("Microbial Reef or Skeletal-Microbial Reef Type",implode($content->microbial_reef_or_skelatal_mic, ", "),30);
										}

										if($content->other_microbial_or_skeletal_mi!=""){
											$pdf->valueRow("Other Microbial or Skeletal Microbial Reef",$content->other_microbial_or_skeletal_mi,30);
										}

										if($content->mud_mound!=""){
											$pdf->valueRow("Mud Mound Type",implode($content->mud_mound, ", "),30);
										}

										if($content->other_mud_mound!=""){
											$pdf->valueRow("Other Mud Mound",$content->other_mud_mound,30);
										}

										$pdf->valueTitle("Biogenic Growth Structure Scale and Orientation: ", 25);

										if($content->height!=""){
											$pdf->valueRow("Height",$content->height,30);
										}

										if($content->width!=""){
											$pdf->valueRow("Width",$content->width,30);
										}

										if($content->shape!=""){
											$pdf->valueRow("Shape",$content->shape,30);
										}

										if($content->type!=""){
											$pdf->valueRow("Type",$content->type,30);
										}

										if($content->other_type!=""){
											$pdf->valueRow("Other Type",$content->other_type,30);
										}

										if($content->accessory_structures!=""){
											$pdf->valueRow("Accessory Structures",implode($content->accessory_structures, ", "),30);
										}

										if($content->other_accessory_structure!=""){
											$pdf->valueRow("Other Accessory Structure",$content->other_accessory_structure,30);
										}

										if($content->notes!=""){
											$pdf->valueRow("Notes",$content->notes,30);
										}
									}
								}

								if($spot['sed']->interpretations){
									$pdf->valueTitle("Interpretations: ", 20);
									$contents = $spot['sed']->interpretations;
									foreach($contents as $content){

										$pdf->valueTitle("Process Interpretation: ", 25);

										if($content->energy!=""){
											$pdf->valueRow("Energy",$content->energy,30);
										}

										if($content->other_energy!=""){
											$pdf->valueRow("Other Energy",$content->other_energy,30);
										}

										if($content->sediment_transport!=""){
											$pdf->valueRow("Sediment Transport",implode($content->sediment_transport, ", "),30);
										}

										if($content->other_sediment_transport!=""){
											$pdf->valueRow("Other Sediment Transport",$content->other_sediment_transport,30);
										}

										if($content->fluidization!=""){
											$pdf->valueRow("Fluidization",$content->fluidization,30);
										}

										if($content->other_fluidization!=""){
											$pdf->valueRow("Other Fluidization",$content->other_fluidization,30);
										}

										if($content->miscellaneous!=""){
											$pdf->valueRow("Miscellaneous",implode($content->miscellaneous, ", "),30);
										}

										if($content->other_miscellaneous!=""){
											$pdf->valueRow("Other Misc. Process",$content->other_miscellaneous,30);
										}

										$pdf->valueTitle("Environment Interpretation: ", 25);

										if($content->general!=""){
											$pdf->valueRow("General",implode($content->general, ", "),30);
										}

										if($content->clastic!=""){
											$pdf->valueRow("Clastic",implode($content->clastic, ", "),30);
										}

										if($content->alluvial_fan_environments!=""){
											$pdf->valueRow("Alluvial fan environments",implode($content->alluvial_fan_environments, ", "),30);
										}

										if($content->other_alluvial_fan!=""){
											$pdf->valueRow("Other Alluvial Fan",$content->other_alluvial_fan,30);
										}

										if($content->eolian_environments!=""){
											$pdf->valueRow("Eolian environments",implode($content->eolian_environments, ", "),30);
										}

										if($content->other_eolian!=""){
											$pdf->valueRow("Other Eolian",$content->other_eolian,30);
										}

										if($content->fluvial_environments!=""){
											$pdf->valueRow("Fluvial environments",implode($content->fluvial_environments, ", "),30);
										}

										if($content->other_fluvial!=""){
											$pdf->valueRow("Other Fluvial",$content->other_fluvial,30);
										}

										if($content->shallow_marine_clastic_environ!=""){
											$pdf->valueRow("Shallow marine clastic environments",implode($content->shallow_marine_clastic_environ, ", "),30);
										}

										if($content->other_shallow_marine!=""){
											$pdf->valueRow("Other Shallow Marine",$content->other_shallow_marine,30);
										}

										if($content->deep_marine_environments!=""){
											$pdf->valueRow("Deep marine environments",implode($content->deep_marine_environments, ", "),30);
										}

										if($content->other_deep_marine!=""){
											$pdf->valueRow("Other Deep Marine",$content->other_deep_marine,30);
										}

										if($content->glacial_and_proglacial_environ!=""){
											$pdf->valueRow("Glacial and proglacial environments",implode($content->glacial_and_proglacial_environ, ", "),30);
										}

										if($content->other_glacial!=""){
											$pdf->valueRow("Other Glacial",$content->other_glacial,30);
										}

										if($content->lake_environments!=""){
											$pdf->valueRow("Lake environments",implode($content->lake_environments, ", "),30);
										}

										if($content->other_lake!=""){
											$pdf->valueRow("Other Lake",$content->other_lake,30);
										}

										if($content->other_clastic!=""){
											$pdf->valueRow("Other Clastic",$content->other_clastic,30);
										}

										if($content->carbonates!=""){
											$pdf->valueRow("Carbonates",implode($content->carbonates, ", "),30);
										}

										if($content->factory!=""){
											$pdf->valueRow("Factory",implode($content->factory, ", "),30);
										}

										if($content->carbonate!=""){
											$pdf->valueRow("Environment",implode($content->carbonate, ", "),30);
										}

										if($content->other_carbonate_environment!=""){
											$pdf->valueRow("Other Carbonate Environment",$content->other_carbonate_environment,30);
										}

										if($content->lake_subenvironments!=""){
											$pdf->valueRow("Lake Subenvironments",implode($content->lake_subenvironments, ", "),30);
										}

										if($content->other_carbonate_lake_subenvironment!=""){
											$pdf->valueRow("Other Carbonate Lake Subenvironment",$content->other_carbonate_lake_subenvironment,30);
										}

										if($content->tidal_flat_subenvironments!=""){
											$pdf->valueRow("Tidal Flat Subenvironments",implode($content->tidal_flat_subenvironments, ", "),30);
										}

										if($content->other_tidal_flat!=""){
											$pdf->valueRow("Other Tidal Flat",$content->other_tidal_flat,30);
										}

										if($content->reef_subenvironments!=""){
											$pdf->valueRow("Reef Subenvironments",implode($content->reef_subenvironments, ", "),30);
										}

										if($content->other_reef!=""){
											$pdf->valueRow("Other Reef",$content->other_reef,30);
										}

										if($content->detailed_carbonate_env_interpr!=""){
											$pdf->valueRow("Detailed carbonate environmental interpretations",$content->detailed_carbonate_env_interpr,30);
										}

										if($content->tectonic_setting!=""){
											$pdf->valueRow("Tectonic Setting",implode($content->tectonic_setting, ", "),30);
										}

										if($content->other_tectonic_setting!=""){
											$pdf->valueRow("Other Tectonic Setting",$content->other_tectonic_setting,30);
										}

										$pdf->valueTitle("Sedimentary Surfaces (for line spots only): ", 25);

										if($content->geometry!=""){
											$pdf->valueRow("Geometry",$content->geometry,30);
										}

										if($content->relief!=""){
											$pdf->valueRow("Relief",$content->relief,30);
										}

										if($content->relief_scale!=""){
											$pdf->valueRow("Relief Scale",$content->relief_scale,30);
										}

										if($content->extent!=""){
											$pdf->valueRow("Extent",$content->extent,30);
										}

										if($content->extent_scale!=""){
											$pdf->valueRow("Extent Scale",$content->extent_scale,30);
										}

										if($content->type!=""){
											$pdf->valueRow("Type",implode($content->type, ", "),30);
										}

										if($content->other_type!=""){
											$pdf->valueRow("Other Type",$content->other_type,30);
										}

										if($content->stratal_termination!=""){
											$pdf->valueRow("Stratal Termination",$content->stratal_termination,30);
										}

										$pdf->valueTitle("Sedimentary Surface Interpretation: ", 25);

										if($content->general_surfaces!=""){
											$pdf->valueRow("General Surfaces",$content->general_surfaces,30);
										}

										if($content->sequence_stratigraphic_surfaces!=""){
											$pdf->valueRow("Sequence Stratigraphic Surfaces",$content->sequence_stratigraphic_surfaces,30);
										}

										if($content->other_sequence_stratigraphic_surface!=""){
											$pdf->valueRow("Other Sequence Stratigraphic Surface",$content->other_sequence_stratigraphic_surface,30);
										}

										if($content->named!=""){
											$pdf->valueRow("Named",$content->named,30);
										}

										$pdf->valueTitle("Architecture Interpretation: ", 25);

										if($content->description!=""){
											$pdf->valueRow("Description",implode($content->description, ", "),30);
										}

										if($content->stacking_sequence_stratigraphy!=""){
											$pdf->valueRow("Stacking/Sequence Stratigraphy",implode($content->stacking_sequence_stratigraphy, ", "),30);
										}

										if($content->other_stacking_sequence_stratigraphy!=""){
											$pdf->valueRow("Other Stacking/Sequence Stratigraphy",$content->other_stacking_sequence_stratigraphy,30);
										}

										if($content->fluvial_architectural_elements!=""){
											$pdf->valueRow("Fluvial Architectural Elements",implode($content->fluvial_architectural_elements, ", "),30);
										}

										if($content->other_fluvial_element!=""){
											$pdf->valueRow("Other Fluvial Element",$content->other_fluvial_element,30);
										}

										if($content->lacustrine_architecture_interpretation!=""){
											$pdf->valueRow("Lacustrine Architecture Interpretation",implode($content->lacustrine_architecture_interpretation, ", "),30);
										}

										if($content->other_lacustrine_architecture_interpretation!=""){
											$pdf->valueRow("Other Lacustrine Architecture Interpretation",$content->other_lacustrine_architecture_interpretation,30);
										}

										if($content->carbonate_platform_geometry!=""){
											$pdf->valueRow("Carbonate Platform Geometry",implode($content->carbonate_platform_geometry, ", "),30);
										}

										if($content->other_platform_geometry!=""){
											$pdf->valueRow("Other Platform Geometry",$content->other_platform_geometry,30);
										}

										if($content->deep_water_architctural_element!=""){
											$pdf->valueRow("Deep-Water Architectural Elements",implode($content->deep_water_architctural_element, ", "),30);
										}

										if($content->other_deep_water_architectural_element!=""){
											$pdf->valueRow("Other Deep-Water Architectural Element",$content->other_deep_water_architectural_element,30);
										}

										if($content->carbonate_margin_geometry!=""){
											$pdf->valueRow("Carbonate Margin Geometry",implode($content->carbonate_margin_geometry, ", "),30);
										}

										if($content->other_carbonate_margin_geometry!=""){
											$pdf->valueRow("Other Carbonate Margin Geometry",$content->other_carbonate_margin_geometry,30);
										}

										if($content->notes!=""){
											$pdf->valueRow("Notes",$content->notes,30);
										}

									}
								}

							}

							if($spot['pet']){
								if($spot['pet']->metamorphic){
									$pdf->valueTitle("Metamorphic Rock(s): ", 15);
									$rocks = $spot['pet']->metamorphic;

									$rockNum = 1;
									foreach($rocks as $r){
										$rockString = "";
										$rockString .= ", " . $r->metamorphic_rock_type;
										$rockString .= ", " . implode(", ", $r->facies);
										$rockString .= ", " . $r->protolith;
										$rockString .= ", " . implode(", ", $r->zone);

										$pdf->valueRow($rockNum, $rockString, 20);
										$rockNum++;
									}

									if($content->section_well_name!=""){
										$pdf->valueRow("Section/Well Name",$content->section_well_name,25);
									}
								}

								if($spot['pet']->igneous){
									$pdf->valueTitle("Igneous Rock(s): ", 15);
									$rocks = $spot['pet']->igneous;

									$rockNum = 1;
									foreach($rocks as $r){
										$rockString = "";
										if($r->igneous_rock_class) $rockString .= ", " . $r->igneous_rock_class;
										if($r->volcanic_rock_type) $rockString .= ", " . $r->volcanic_rock_type;
										if($r->occurence_volcanic) $rockString .= ", " . $r->occurence_volcanic;
										if($r->plutonic_rock_type) $rockString .= ", " . $r->plutonic_rock_type;
										if($r->occurence_plutonic) $rockString .= ", " . $r->occurence_plutonic;
										if($r->texture_volcanic) $rockString .= ", " . implode(", ", $r->texture_volcanic);
										if($r->texture_plutonic) $rockString .= ", " . implode(", ", $r->texture_plutonic);
										if($r->color_index_volc) $rockString .= ", " . $r->color_index_volc;
										if($r->color_index_pluton) $rockString .= ", " . $r->color_index_pluton;
										if($r->color_index_source_volc) $rockString .= ", " . $r->color_index_source_volc;
										if($r->color_index_source_pluton) $rockString .= ", " . $r->color_index_source_pluton;
										if($r->alteration_volcanic) $rockString .= ", " . implode(", ", $r->alteration_volcanic);
										if($r->alteration_plutonic) $rockString .= ", " . implode(", ", $r->alteration_plutonic);

										$pdf->valueRow($rockNum, $rockString, 20);
										$rockNum++;
									}

									if($content->section_well_name!=""){
										$pdf->valueRow("Section/Well Name",$content->section_well_name,25);
									}
								}

								if($spot['pet']->minerals){
									$pdf->valueTitle("Mineral(s): ", 15);
									$rocks = $spot['pet']->minerals;

									$rockNum = 1;
									foreach($rocks as $r){
										$rockString = "";
										if($r->full_mineral_name) $rockString .= ", " . $r->full_mineral_name;
										if($r->igneous_or_metamorphic == "ig_min"){
											$rockString .= " (Igneous)";
										}else{
											$rockString .= " (Metamorphic)";
										}
										if($r->average_grain_size_mm) $rockString .= ", Avg Size: " . $r->average_grain_size_mm . "mm";
										if($r->maximum_grain_size_mm) $rockString .= ", Max Size: " . $r->maximum_grain_size_mm . "mm";
										if($r->modal) $rockString .= ", Modal: " . $r->modal . "%";
										if($r->mineral_notes) $rockString .= " " . $r->mineral_notes;

										$pdf->valueRow($rockNum, $rockString, 20);
										$rockNum++;
									}

									if($content->section_well_name!=""){
										$pdf->valueRow("Section/Well Name",$content->section_well_name,25);
									}
								}

							}

							if($spot['images']){

								$pdf->valueRow("Images","",15);
								$pdf->Ln(1);
								foreach($spot['images'] as $o){
									if($o['title']){
										$thistitle = $this->fixLabel($o['title']);
										$pdf->valueTitle($thistitle.": ",20);
									}else{
										$thistitle = $o['id'];
									}

									foreach($o as $key=>$value){
										if($value != ""){
											if($key!="id" && $key!="self" && $key!="annotated" && $key!="title" && $key!="width" && $key!="height" && $key!="image_type" && $key!="caption" ){
												$key = $this->fixLabel($key);
												if(is_string($value)){
													$value = $this->fixLabel($value);
												}
												$pdf->valueRow($key,$value,20);
											}
										}
									}

									$pdf->Ln(1);

									$filename = $this->strabo->getImageFilename($o['id']);

									if($filename){
										$gdimage = $this->gdThumb($filename);
										if($gdimage){
											$pdf->GDImage($gdimage, 20, null, 60);
										}
									}

									if($o['caption'] != ""){
										$pdf->imageCaptionRow("Caption", $o['caption'], 20);
									}

									$pdf->Ln(1);
									$pdf->Ln(1);
									$pdf->Ln(1);
									$pdf->Ln(1);
									$pdf->Ln(1);

								}
							}

							$pdf->Ln(10);

						}else{ //end if date matches
						}

					}//end foreach spots

				}// end foreach spotdates

				$filedate = date("m_d_Y");
				$pdfname="StraboSpot_Field_Book_$filedate.pdf";
				$pdf->Output($pdfname,"D"); //Download this is the final

			}else{

				echo "No spots found for this search.";

			}

		} //end if dsids

	}

	public function newfieldbookOut(){

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->getTagsFromDatasetIds($dsids);

			$json = $this->strabo->getDatasetSpotsSearch(null,$this->get);

			$dsname = $this->strabo->getDatasetName($dsids);

			$dailynotes = $this->strabo->getDailyNotesFromDatasetID($dsids);

			$spots = $json['features'];

			//unix time 1567364734  10 digits
			//id 15609769344266     14 digits

			//get day for each spot

			//create date array to loop over

			if(count($spots)>0){

				$spotdates = [];
				$founddates = [];
				foreach($spots as $spot){

					$ut = substr($spot['properties']['id'], 0 ,10);

					$datestring = date("l, F j, Y", $ut);

					if(!in_array($datestring, $founddates)){
						$founddates[] = $datestring;
						$showformat = date("l, F j, Y", $ut);
						$lookformat = date("Y-m-d", $ut);
						$thisobj = new stdClass();
						$thisobj->showformat = $showformat;
						$thisobj->lookformat = $lookformat;
						$spotdates[] = $thisobj;
					}

				}

				require('includes/PDF_LabBook.php');

				$pdf = new PDF_MemImage('P','mm','Letter');
				$pdf->AddPage();

				if($dsname != ""){
					$pdf->datasetTitle($dsname);
				}

				foreach($spotdates as $spotdate){

					$showdate = $spotdate->showformat;
					$pdf->dayTitle($showdate);

					//look for daily notes (format in data is YYYY-MM-DD)
					$lookdate = $spotdate->lookformat;

					if($dailynotes!=""){
						foreach($dailynotes as $dn){
							$dndate = substr($dn->date,0,10);

							if($dndate == $lookdate){
								$pdf->dailyNotesRow("Daily Setup Notes",$dn->notes,10);
							}
						}
					}

					foreach($spots as $spot){

						$rawspot = $spot;

						$spot = $spot['properties'];

						$id = $spot['id'];

						$ut = substr($id, 0 ,10);

						$thisspotdate = date("l, F j, Y", $ut);

						if($thisspotdate == $spotdate->showformat){

							$spotname = $spot['name'];
							if($spot['geometrytype']){
								$spotname .= " (".$spot['geometrytype'].")";
							}

							$pdf->spotTitle($spotname);

							$modified = (string) $spot['id'];
							$modified = substr($modified,0,10);
							$modified = date("F j, Y",$modified);
							$pdf->valueRow("Created",$modified,15);

							$modified = (string) $spot['modified_timestamp'];
							$modified = substr($modified,0,10);
							$modified = date("F j, Y",$modified);
							$pdf->valueRow("Last Modified",$modified,15);

							if($rawspot['geometry']->type=="Point"){
								$pdf->valueRow("Longitude",$rawspot['geometry']->coordinates[0],15);
								$pdf->valueRow("Latitude",$rawspot['geometry']->coordinates[1],15);
							}else{
							}

							if($spot['notes']){
								$notes = $spot['notes'];
								$pdf->notesRow("Notes",$notes,15);
							}

							if($spot['surface_feature']){
								foreach($spot['surface_feature'] as $key=>$value){
									$key = $this->fixLabel($key);
									if(is_string($value)){
										$value = $this->fixLabel($value);
									}
									$pdf->valueRow($key,$value,15);
								}
							}

							if($spot['orientation_data']){
								$pdf->valueRow("Orientations","",15);
								foreach($spot['orientation_data'] as $o){
									$pdf->valueTitle($this->fixLabel($o->type).": ",20);
									foreach($o as $key=>$value){
										if($key!="id" && $key!="associated_orientation" && $key!="type"){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,20);
										}
									}

									if($o->associated_orientation){
										$pdf->valueRow("Associated Orientation Data:","",20);
										foreach($o->associated_orientation as $ao){
											$pdf->valueTitle($this->fixLabel($ao->type).": ",30);
											foreach($ao as $key=>$value){
												if($key!="id" && $key!="associated_orientation" && $key!="type"){
													$key = $this->fixLabel($key);
													if(is_string($value)){
														$value = $this->fixLabel($value);
													}
													$pdf->valueRow($key,$value,30);
												}
											}
											$pdf->Ln(1);
										}
									}

									$pdf->Ln(1);
								}
							}

							if($spot['_3d_structures']){
								$pdf->valueRow("3D Structures","",15);
								foreach($spot['_3d_structures'] as $o){
									$pdf->valueTitle($this->fixLabel($o->type).": ",20);
									foreach($o as $key=>$value){
										if($key!="id" && $key!="type"){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,20);
										}
									}

									$pdf->Ln(1);
								}
							}

							if($spot['samples']){
								$pdf->valueRow("Samples","",15);
								foreach($spot['samples'] as $o){
									$pdf->valueTitle($this->fixLabel($o->label).": ",20);
									foreach($o as $key=>$value){
										if($key!="id" && $key!="label"){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,20);
										}
									}

									$pdf->Ln(1);
								}
							}

							if($spot['other_features']){
								$pdf->valueRow("Other Features","",15);
								foreach($spot['other_features'] as $o){
									$pdf->valueTitle($this->fixLabel($o->label).": ",20);
									foreach($o as $key=>$value){
										if($key!="id" && $key!="label"){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,20);
										}
									}

									$pdf->Ln(1);
								}
							}

							if($project->tags){
								foreach($project->tags as $tag){
									$found = "no";
									if($tag->spots){
										foreach($tag->spots as $spotid){
											if($spotid == $id){
												$found = "yes";
											}
										}
									}

									if($found == "yes"){
										if($tag->type=="geologic_unit"){

											$pdf->valueRow("Rock Unit","",15);

											foreach($tag as $key=>$value){
												if($key != "date" && $key != "spots" && $key != "features" && $key != "id" ){
													$key = $this->fixLabel($key);
													if(is_string($value)){
														$value = $this->fixLabel($value);
													}
													$pdf->valueRow($key,$value,20);
												}
											}
										}
									}
								}
							}

							$hastags = "no";

							if($this->alltags){
								foreach($this->alltags as $tag){
									$found = "no";
									if($tag->spots){
										if($tag->type!="geologic_unit"){
											foreach($tag->spots as $spotid){
												if($spotid == $id){
													$hastags = "yes";
												}
											}
										}
									}

								}
							}

							if($hastags == "yes"){

								$pdf->valueRow("Tags","");

								if($this->alltags){
									foreach($this->alltags as $tag){
										$found = "no";
										if($tag->spots){
											if($tag->type!="geologic_unit"){
												foreach($tag->spots as $spotid){
													if($spotid == $id){
														$found = "yes";
													}
												}
											}
										}

										if($found == "yes"){

											$pdf->valueTitle($tag->name,20);
											foreach($tag as $key=>$value){

												if($key != "date" && $key != "spots" && $key != "features" && $key != "id" && $key != "name" ){
													$key = $this->fixLabel($key);
													if(is_string($value)){
														$value = $this->fixLabel($value);
													}
													$pdf->valueRow($key,$value,20);
												}

											}

											$pdf->Ln(1);

										}
									}
								}

							}

							if($spot['images']){
								$pdf->valueRow("Images","",15);
								$pdf->Ln(1);
								foreach($spot['images'] as $o){
									if($o['title']){
										$thistitle = $this->fixLabel($o['title']);
										$pdf->valueTitle($thistitle.": ",20);
									}else{
										$thistitle = $o['id'];
									}

									foreach($o as $key=>$value){
										if($key!="id" && $key!="self" && $key!="annotated" && $key!="title" && $key!="width" && $key!="height" && $key!="image_type" && $key!="caption" ){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,20);
										}
									}

									$pdf->Ln(1);

									$filename = $this->strabo->getImageFilename($o['id']);
									if($filename){
										$gdimage = $this->gdThumb($filename);
										if($gdimage){
											$pdf->GDImage($gdimage, 20, null, 60);
										}
									}

									if($o['caption'] != ""){
										$pdf->imageCaptionRow("Caption", $o['caption'], 20);
									}

									$pdf->Ln(1);
									$pdf->Ln(1);
									$pdf->Ln(1);
									$pdf->Ln(1);
									$pdf->Ln(1);

								}
							}

							if($spot['pet']){
								$pdf->valueRow("IG/Met","",15);
								$pet = (array) $spot['pet'];
								$outlines = $this->varToPDF($spot['pet'], 20);
								$groupedrows = $this->groupVarToPDF($outlines);
								foreach($groupedrows as $gr){
									if($gr[2]=="label"){
										$pdf->valueRow($gr[1],"",$gr[0]);
										$lastlabel = $gr[1];
									}else{
										if (strpos(strtolower($lastlabel), 'notes') !== false) {
											$pdf->petNotesRow("",$gr[1]." notesrow",$gr[0]);
										}else{
											$pdf->lowValueRow($gr[1],"",$gr[0]);
										}
										$lastlabel = $gr[1];
									}

								}
							}

							$pdf->Ln(10);

						}else{ //end if date matches
						}

					}//end foreach spots

				}// end foreach spotdates

				$filedate = date("m_d_Y");
				$pdfname="StraboSpot_Field_Book_$filedate.pdf";
				$pdf->Output();

			}else{

				echo "No spots found for this search.";

			}

		} //end if dsids

	}

	public function varToPDF($var, $indent){
		$rows = [];
		if(is_array($var)){
			foreach($var as $v){
				$newrows = $this->varToPDF($v, $indent + 2);
				foreach($newrows as $newrow){
					$rows[]=$newrow;
				}
			}
		}elseif(is_object($var)){
			foreach($var as $key=>$v){
				if($key!="id"){
					$rows[] = array($indent, $key, 'label');
					$newrows = $this->varToPDF($v, $indent + 2);
					foreach($newrows as $newrow){
						$rows[]=$newrow;
					}
				}
			}
		}else{
			if($var != "id"){
				$rows[] = array($indent, $var, 'value');
			}
		}

		return $rows;
	}

	public function groupVarToPDF($array){
		$currentindent = $array[0][0];
		$outrows = [];
		$valarray = [];

		foreach($array as $row){
			if($row[0] != $currentindent){
				if($lasttype == "label"){
					$thislabel = implode(", ", $valarray);
					$thislabel = $this->fixLabel($thislabel);
					$outrows[] = array($currentindent, $thislabel, $lasttype);
				}else{
					$outrows[] = array($currentindent, implode(", ", $valarray), $lasttype);
				}

				$valarray = [];
				$currentindent = $row[0];
			}
			$valarray[] = $row[1];
			$lasttype = $row[2];
		}

		$outrows[] = array($currentindent, implode(", ", $valarray), $lasttype);

		return $outrows;
	}

	public function old09052019fieldbookOut(){

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->getTagsFromDatasetIds($dsids);

			$json = $this->strabo->getDatasetSpotsSearch(null,$this->get);

			$spots = $json['features'];

			if(count($spots)>0){

				require('includes/PDF_LabBook.php');

				$pdf = new PDF_MemImage('P','mm','Letter');
				$pdf->AddPage();

				foreach($spots as $spot){

					$spot = $spot['properties'];

					$id = $spot['id'];

					$spotname = $spot['name'];
					if($spot['geometrytype']){
						$spotname .= " (".$spot['geometrytype'].")";
					}

					$pdf->spotTitle($spotname);

					$modified = (string) $spot['id'];
					$modified = substr($modified,0,10);
					$modified = date("c",$modified);
					$pdf->valueRow("Created",$modified);

					$modified = (string) $spot['modified_timestamp'];
					$modified = substr($modified,0,10);
					$modified = date("c",$modified);
					$pdf->valueRow("Last Modified",$modified);

					if($spot['surface_feature']){
						foreach($spot['surface_feature'] as $key=>$value){
							$key = $this->fixLabel($key);
							if(is_string($value)){
								$value = $this->fixLabel($value);
							}
							$pdf->valueRow($key,$value);
						}
					}

					if($spot['orientation_data']){
						$pdf->valueRow("Orientations","");
						foreach($spot['orientation_data'] as $o){
							$pdf->valueTitle($this->fixLabel($o->type).": ",15);
							foreach($o as $key=>$value){
								if($key!="id" && $key!="associated_orientation" && $key!="type"){
									$key = $this->fixLabel($key);
									if(is_string($value)){
										$value = $this->fixLabel($value);
									}
									$pdf->valueRow($key,$value,15);
								}
							}

							if($o->associated_orientation){
								$pdf->valueRow("Associated Orientation Data:","",15);
								foreach($o->associated_orientation as $ao){
									$pdf->valueTitle($this->fixLabel($ao->type).": ",25);
									foreach($ao as $key=>$value){
										if($key!="id" && $key!="associated_orientation" && $key!="type"){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,25);
										}
									}
									$pdf->Ln(1);
								}
							}

							$pdf->Ln(1);
						}
					}

					if($spot['_3d_structures']){
						$pdf->valueRow("3D Structures","");
						foreach($spot['_3d_structures'] as $o){
							$pdf->valueTitle($this->fixLabel($o->type).": ",15);
							foreach($o as $key=>$value){
								if($key!="id" && $key!="type"){
									$key = $this->fixLabel($key);
									if(is_string($value)){
										$value = $this->fixLabel($value);
									}
									$pdf->valueRow($key,$value,15);
								}
							}

							$pdf->Ln(1);
						}
					}

					if($spot['samples']){
						$pdf->valueRow("Samples","");
						foreach($spot['samples'] as $o){
							$pdf->valueTitle($this->fixLabel($o->label).": ",15);
							foreach($o as $key=>$value){
								if($key!="id" && $key!="label"){
									$key = $this->fixLabel($key);
									if(is_string($value)){
										$value = $this->fixLabel($value);
									}
									$pdf->valueRow($key,$value,15);
								}
							}

							$pdf->Ln(1);
						}
					}

					if($spot['other_features']){
						$pdf->valueRow("Other Features","");
						foreach($spot['other_features'] as $o){
							$pdf->valueTitle($this->fixLabel($o->label).": ",15);
							foreach($o as $key=>$value){
								if($key!="id" && $key!="label"){
									$key = $this->fixLabel($key);
									if(is_string($value)){
										$value = $this->fixLabel($value);
									}
									$pdf->valueRow($key,$value,15);
								}
							}

							$pdf->Ln(1);
						}
					}

					if($project->tags){
						foreach($project->tags as $tag){
							$found = "no";
							if($tag->spots){
								foreach($tag->spots as $spotid){
									if($spotid == $id){
										$found = "yes";
									}
								}
							}

							if($found == "yes"){
								if($tag->type=="geologic_unit"){

									$pdf->valueRow("Rock Unit","");

									foreach($tag as $key=>$value){
										if($key != "date" && $key != "spots" && $key != "features" && $key != "id" ){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,15);
										}
									}
								}
							}
						}
					}

					$hastags = "no";

					if($this->alltags){
						foreach($this->alltags as $tag){
							$found = "no";
							if($tag->spots){
								if($tag->type!="geologic_unit"){
									foreach($tag->spots as $spotid){
										if($spotid == $id){
											$hastags = "yes";
										}
									}
								}
							}

						}
					}

					if($hastags == "yes"){

						$pdf->valueRow("Tags","");

						if($this->alltags){
							foreach($this->alltags as $tag){
								$found = "no";
								if($tag->spots){
									if($tag->type!="geologic_unit"){
										foreach($tag->spots as $spotid){
											if($spotid == $id){
												$found = "yes";
											}
										}
									}
								}

								if($found == "yes"){

									$pdf->valueTitle($tag->name,15);
									foreach($tag as $key=>$value){

										if($key != "date" && $key != "spots" && $key != "features" && $key != "id" && $key != "name" ){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,15);
										}

									}

									$pdf->Ln(1);

								}
							}
						}

					}

					if($spot['images']){
						$pdf->valueRow("Images","");
						foreach($spot['images'] as $o){
							if($o['title']){
								$thistitle = $this->fixLabel($o['title']);
							}else{
								$thistitle = $o['id'];
							}
							$pdf->valueTitle($thistitle.": ",15);
							foreach($o as $key=>$value){
								if($key!="id" && $key!="self" && $key!="annotated" && $key!="title"){
									$key = $this->fixLabel($key);
									if(is_string($value)){
										$value = $this->fixLabel($value);
									}
									$pdf->valueRow($key,$value,15);
								}
							}

							$pdf->Ln(1);

							$filename = $this->strabo->getImageFilename($o['id']);
							if($filename){
								$gdimage = $this->gdThumb($filename);
								if($gdimage){
									$pdf->GDImage($gdimage, 15, null, 60);
									$pdf->cell(0,2,'','',1,L);
								}
							}

							$pdf->Ln(1);
						}
					}

					$pdf->Ln(10);

				}

				$filedate = date("m_d_Y");
				$pdfname="StraboSpot_Search_$filedate.pdf";
				$pdf->Output($pdfname,"D");

			}else{

				echo "No spots found for this search.";

			}

		} //end if dsids

	}

	public function fieldbookOutdev(){

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->getTagsFromDatasetIds($dsids);

			$json = $this->strabo->getDatasetSpotsSearch(null,$this->get);

			$spots = $json['features'];

			if(count($spots)>0){

				require('includes/PDF_LabBook.php');

				$pdf = new PDF_MemImage('P','mm','Letter');
				$pdf->AddPage();

				foreach($spots as $spot){

					$spot = $spot['properties'];

					$id = $spot['id'];

					$spotname = $spot['name'];
					if($spot['geometrytype']){
						$spotname .= " (".$spot['geometrytype'].")";
					}

					$pdf->spotTitle($spotname);

					$modified = (string) $spot['id'];
					$modified = substr($modified,0,10);
					$modified = date("c",$modified);
					$pdf->valueRow("Created",$modified);

					$modified = (string) $spot['modified_timestamp'];
					$modified = substr($modified,0,10);
					$modified = date("c",$modified);
					$pdf->valueRow("Last Modified",$modified);

					if($spot['surface_feature']){
						foreach($spot['surface_feature'] as $key=>$value){
							$key = $this->fixLabel($key);
							if(is_string($value)){
								$value = $this->fixLabel($value);
							}
							$pdf->valueRow($key,$value);
						}
					}

					if($spot['orientation_data']){
						$pdf->valueRow("Orientations","");
						foreach($spot['orientation_data'] as $o){
							$pdf->valueTitle($this->fixLabel($o->type).": ",15);
							foreach($o as $key=>$value){
								if($key!="id" && $key!="associated_orientation" && $key!="type"){
									$key = $this->fixLabel($key);
									if(is_string($value)){
										$value = $this->fixLabel($value);
									}
									$pdf->valueRow($key,$value,15);
								}
							}

							if($o->associated_orientation){
								$pdf->valueRow("Associated Orientation Data:","",15);
								foreach($o->associated_orientation as $ao){
									$pdf->valueTitle($this->fixLabel($ao->type).": ",25);
									foreach($ao as $key=>$value){
										if($key!="id" && $key!="associated_orientation" && $key!="type"){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,25);
										}
									}
									$pdf->Ln(1);
								}
							}

							$pdf->Ln(1);
						}
					}

					if($spot['_3d_structures']){
						$pdf->valueRow("3D Structures","");
						foreach($spot['_3d_structures'] as $o){
							$pdf->valueTitle($this->fixLabel($o->type).": ",15);
							foreach($o as $key=>$value){
								if($key!="id" && $key!="type"){
									$key = $this->fixLabel($key);
									if(is_string($value)){
										$value = $this->fixLabel($value);
									}
									$pdf->valueRow($key,$value,15);
								}
							}

							$pdf->Ln(1);
						}
					}

					if($spot['samples']){
						$pdf->valueRow("Samples","");
						foreach($spot['samples'] as $o){
							$pdf->valueTitle($this->fixLabel($o->label).": ",15);
							foreach($o as $key=>$value){
								if($key!="id" && $key!="label"){
									$key = $this->fixLabel($key);
									if(is_string($value)){
										$value = $this->fixLabel($value);
									}
									$pdf->valueRow($key,$value,15);
								}
							}

							$pdf->Ln(1);
						}
					}

					if($spot['other_features']){
						$pdf->valueRow("Other Features","");
						foreach($spot['other_features'] as $o){
							$pdf->valueTitle($this->fixLabel($o->label).": ",15);
							foreach($o as $key=>$value){
								if($key!="id" && $key!="label"){
									$key = $this->fixLabel($key);
									if(is_string($value)){
										$value = $this->fixLabel($value);
									}
									$pdf->valueRow($key,$value,15);
								}
							}

							$pdf->Ln(1);
						}
					}

					if($project->tags){
						foreach($project->tags as $tag){
							$found = "no";
							if($tag->spots){
								foreach($tag->spots as $spotid){
									if($spotid == $id){
										$found = "yes";
									}
								}
							}

							if($found == "yes"){
								if($tag->type=="geologic_unit"){

									$pdf->valueRow("Rock Unit","");

									foreach($tag as $key=>$value){
										if($key != "date" && $key != "spots" && $key != "features" && $key != "id" ){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,15);
										}
									}
								}
							}
						}
					}

					$hastags = "no";

					if($this->alltags){
						foreach($this->alltags as $tag){
							$found = "no";
							if($tag->spots){
								if($tag->type!="geologic_unit"){
									foreach($tag->spots as $spotid){
										if($spotid == $id){
											$hastags = "yes";
										}
									}
								}
							}

						}
					}

					if($hastags == "yes"){

						$pdf->valueRow("Tags","");

						if($this->alltags){
							foreach($this->alltags as $tag){
								$found = "no";
								if($tag->spots){
									if($tag->type!="geologic_unit"){
										foreach($tag->spots as $spotid){
											if($spotid == $id){
												$found = "yes";
											}
										}
									}
								}

								if($found == "yes"){

									$pdf->valueTitle($tag->name,15);
									foreach($tag as $key=>$value){

										if($key != "date" && $key != "spots" && $key != "features" && $key != "id" && $key != "name" ){
											$key = $this->fixLabel($key);
											if(is_string($value)){
												$value = $this->fixLabel($value);
											}
											$pdf->valueRow($key,$value,15);
										}

									}

									$pdf->Ln(1);

								}
							}
						}

					}

					if($spot['images']){
						$pdf->valueRow("Images","");
						foreach($spot['images'] as $o){
							if($o['title']){
								$thistitle = $this->fixLabel($o['title']);
							}else{
								$thistitle = $o['id'];
							}
							$pdf->valueTitle($thistitle.": ",15);
							foreach($o as $key=>$value){
								if($key!="id" && $key!="self" && $key!="annotated" && $key!="title"){
									$key = $this->fixLabel($key);
									if(is_string($value)){
										$value = $this->fixLabel($value);
									}
									$pdf->valueRow($key,$value,15);
								}
							}

							$pdf->Ln(1);

							$filename = $this->strabo->getImageFilename($o['id']);
							if($filename){
								$gdimage = $this->gdThumb($filename);
								if($gdimage){
									$pdf->GDImage($gdimage, 15, null, 60);
									$pdf->cell(0,2,'','',1,L);
								}
							}

							$pdf->Ln(1);
						}
					}

					$pdf->Ln(10);

				}

				$filedate = date("m_d_Y");
				$pdfname="StraboSpot_Search_$filedate.pdf";
				$pdf->Output($pdfname,"D");

			}else{

				echo "No spots found for this search.";

			}

		} //end if dsids

	}

function buildKMLIcon($id,$randnum,$strike,$showval,$trend){ 

	$finalimage = imagecreatefrompng("assets/files/kmlfiles/bubbleicon/blankbubble.png");

	imagealphablending($finalimage, true);
	imagesavealpha($finalimage, true);

	if($strike!="" && $strike!=0 && is_numeric($strike)){

		$strike = round($strike);

		$arrow = imagecreatefrompng("assets/files/kmlfiles/bubbleicon/planar_bar.png");

		// Transparent Background
		imagealphablending($arrow, true);
		$transparency = imagecolorallocatealpha($arrow, 0, 0, 0, 127);
		imagesavealpha($arrow, true);

		// Drawing over
		$black = imagecolorallocate($arrow, 0, 0, 0);
		$fontcolor = imagecolorallocate($arrow, 255, 255, 255);//255, 176, 0

		$arrow = imagerotate($arrow, ($strike*-1), $transparency);

		//crop to 100x100 again
		$sizex = imagesx($arrow);
		$sizey = imagesy($arrow);

		$halfx = round($sizex/2);
		$halfy = round($sizey/2);

		$locx = 50 - $halfx;
		$locy = 40 - $halfy;

		imagecopy($finalimage, $arrow, $locx, $locy, 0, 0, $sizex, $sizey);

	}

	if($trend!="" && $trend!=0 && is_numeric($trend)){

		$trend = round($trend);

		$trendbar = imagecreatefrompng("assets/files/kmlfiles/bubbleicon/linear_arrow.png");

		// Transparent Background
		imagealphablending($trendbar, true);
		$transparency = imagecolorallocatealpha($trendbar, 0, 0, 0, 127);
		imagesavealpha($trendbar, true);

		// Drawing over
		$black = imagecolorallocate($arrow, 0, 0, 0);
		$fontcolor = imagecolorallocate($trendbar, 255, 255, 255);//255, 176, 0

		$trendbar = imagerotate($trendbar, ($trend*-1), $transparency);

		//crop to 100x100 again
		$sizex = imagesx($trendbar);
		$sizey = imagesy($trendbar);

		$halfx = round($sizex/2);
		$halfy = round($sizey/2);

		$locx = 50 - $halfx;
		$locy = 40 - $halfy;

		imagecopy($finalimage, $trendbar, $locx, $locy, 0, 0, $sizex, $sizey);

	}

	if($showval!="" && is_numeric($showval)){

		$showval = round($showval);

		$diplabel = imagecreatetruecolor(100, 100);

		// Transparent Background
		imagealphablending($diplabel, true);
		$diplabeltransparency = imagecolorallocatealpha($diplabel, 0, 0, 0, 127);
		imagefill($diplabel, 0, 0, $diplabeltransparency);
		imagesavealpha($diplabel, true);

		// Drawing over
		$black = imagecolorallocate($diplabel, 0, 0, 0);

		imagettftext($diplabel, 16, 0, 50, 85, $black, "assets/files/kmlfiles/roadway.ttf", "$showval");

		imagecopy($finalimage, $diplabel, 0, 0, 0, 0, 100, 100);

	}

	imagepng($finalimage, "ogrtemp/$randnum/data/files/$id.png");

}

	public function ffbuildKMLIcon($id,$randnum,$strike,$showval,$trend){ //outline
		$backbox = imagecreatetruecolor(100, 100);

		// Transparent Background
		imagealphablending($backbox, true);
		$backboxtransparency = imagecolorallocatealpha($backbox, 0, 0, 0, 127);
		imagefill($backbox, 0, 0, $backboxtransparency);
		imagesavealpha($backbox, true);

		if($strike!="" && $strike!=0 && is_numeric($strike)){

			$strike = round($strike);

			$arrow = imagecreatetruecolor(100, 100);

			// Transparent Background
			imagealphablending($arrow, true);
			$transparency = imagecolorallocatealpha($arrow, 0, 0, 0, 127);
			imagefill($arrow, 0, 0, $transparency);
			imagesavealpha($arrow, true);

			// Drawing over
			$black = imagecolorallocate($arrow, 0, 0, 0);
			$fontcolor = imagecolorallocate($arrow, 255, 255, 255);//255, 176, 0

			// set up array of points for arrow
			$whitevalues = array(
						45,0,
						45,100,
						55,100,
						55,60,
						66,50,
						55,40,
						55,0,
						45,0
						);

			imagefilledpolygon($arrow, $whitevalues, 8, $fontcolor);

			// set up array of points for arrow
			$blackvalues = array(
						48,3,
						48,97,
						52,97,
						52,58,
						62,50,
						52,42,
						52,3,
						48,3
						);

			imagefilledpolygon($arrow, $blackvalues, 8, $black);

			$arrow = imagerotate($arrow, ($strike*-1), $transparency);

			//crop to 100x100 again
			$sizex = imagesx($arrow);
			$sizey = imagesy($arrow);

			$xoffset = round(($sizex-100)/2);
			$yoffset = round(($sizex-100)/2);

			$arrow = imagecrop($arrow, ['x' => $xoffset, 'y' => $yoffset, 'width' => 100, 'height' => 100]);

			imagecopy($backbox, $arrow, 0, 0, 0, 0, 100, 100);

		}

		if($trend!="" && is_numeric($trend)){

			$trend = round($trend);

			$trendbar = imagecreatetruecolor(100, 100);

			// Transparent Background
			imagealphablending($trendbar, true);
			$trendtransparency = imagecolorallocatealpha($trendbar, 0, 0, 0, 127);
			imagefill($trendbar, 0, 0, $trendtransparency);
			imagesavealpha($trendbar, true);

			// Drawing over
			$black = imagecolorallocate($trendbar, 0, 0, 0);
			$fontcolor = imagecolorallocate($trendbar, 255, 255, 255);//255, 176, 0

			// set up array of points for arrow
			$whitevalues = array(
						50,0,
						37,18,
						45,13,
						45,100,
						55,100,
						55,13,
						63,18,
						50,0
						);
			imagefilledpolygon($trendbar, $whitevalues, 8, $fontcolor);

			// set up array of points for arrow
			$blackvalues = array(
						50,2,
						40,15,
						48,10,
						48,97,
						52,97,
						52,10,
						60,15,
						50,2
						);
			imagefilledpolygon($trendbar, $blackvalues, 8, $black);

			$trendbar = imagerotate($trendbar, ($trend*-1), $trendtransparency);

			//crop to 100x100 again
			$sizex = imagesx($trendbar);
			$sizey = imagesy($trendbar);

			$xoffset = round(($sizex-100)/2);
			$yoffset = round(($sizex-100)/2);

			$trendbar = imagecrop($trendbar, ['x' => $xoffset, 'y' => $yoffset, 'width' => 100, 'height' => 100]);

			imagecopy($backbox, $trendbar, 0, 0, 0, 0, 100, 100);

		}

		if($showval!="" && is_numeric($showval)){

			$showval = round($showval);

			$diplabel = imagecreatetruecolor(100, 100);

			// Transparent Background
			imagealphablending($diplabel, true);
			$diplabeltransparency = imagecolorallocatealpha($diplabel, 0, 0, 0, 127);
			imagefill($diplabel, 0, 0, $diplabeltransparency);
			imagesavealpha($diplabel, true);

			// Drawing over
			$black = imagecolorallocate($diplabel, 0, 0, 0);

			imagettftext($diplabel, 24, 0, 50, 31, $fontcolor, "files/kmlfiles/roadway.ttf", "$showval");

			imagecopy($backbox, $diplabel, 0, 0, 0, 0, 100, 100);

		}

		imagepng($backbox, "ogrtemp/$randnum/data/files/$id.png");

	}

	public function buildCustomPoint($spot,$randnum){

/*
strike
dip
trend
plunge
*/

		$id=$spot['id'];
		foreach($spot['orientation_data'] as $od){
			foreach($od as $key=>$value){
				if($key=="strike") $strike=$value;
				if($key=="dip") $dip=$value;
				if($key=="trend") $trend=$value;
				if($key=="plunge") $plunge=$value;

			}

			foreach($od->associated_orientation as $ao){
				foreach($ao as $key=>$value){
					if($key=="strike") $strike=$value;
					if($key=="dip") $dip=$value;
					if($key=="trend") $trend=$value;
					if($key=="plunge") $plunge=$value;
				}
			}

		}

		if($strike!="" || $trend!=""){

			if($dip!=""){
				$showval=$dip;
			}elseif($plunge!=""){
				$showval=$plunge;
			}

			$this->buildKMLIcon($id,$randnum,$strike,$showval,$trend);

			$pointstyle="<Style><IconStyle><Icon><href>files/$id.png</href></Icon></IconStyle></Style>";
		}else{
			$pointstyle="";
		}

		return $pointstyle;

	}

	public function kmlOut(){

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->newSearchGetTagsFromDatasetIds($dsids);
			$json = $this->strabo->newSearchGetDatasetSpotsSearch(null, $this->get);

			$spots = $json['features'];

			if(count($spots)>0){

				$randnum=$this->strabo->db->get_var("select nextval('file_seq')");

				//make directory in ogrtemp to hold data
				mkdir("ogrtemp/$randnum");
				mkdir("ogrtemp/$randnum/data");
				mkdir("ogrtemp/$randnum/data/files");

				copy("assets/files/kmlfiles/bubblehead.jpg","ogrtemp/$randnum/data/files/bubblehead.jpg");

				copy("assets/files/kmlfiles/bubbleicon/dotbubble.png","ogrtemp/$randnum/data/files/dot.png");

				copy("assets/files/kmlfiles/mysavedplaces_closed.png","ogrtemp/$randnum/data/files/mysavedplaces_closed.png");
				copy("assets/files/kmlfiles/mysavedplaces_open.png","ogrtemp/$randnum/data/files/mysavedplaces_open.png");
				copy("assets/files/kmlfiles/overlay.jpg","ogrtemp/$randnum/data/files/overlay.jpg");
				copy("assets/files/kmlfiles/rock.jpg","ogrtemp/$randnum/data/files/rock.jpg");

	/*

	files/kmlfiles

	bubblehead.jpg
	dot.png
	mysavedplaces_closed.png
	mysavedplaces_open.png
	overlay.jpg
	rock.jpg

	Polygon
	Point
	LineString
	MultiPolygon
	MultiLineString

	*/

				$pointHtml = $this->getKMLHtml($spots, "point");
				$lineHtml = $this->getKMLHtml($spots, "line");
				$polygonHtml = $this->getKMLHtml($spots, "polygon");

				foreach($spots as $spot){

					//use geoPHP to get WKT
					$mygeojson=$spot['geometry'];

					$mygeotype = $mygeojson->type;

					//pick a style for KML feature
					if($mygeotype=="Polygon" || $mygeotype=="MultiPolygon"){
						$thisstyle="m_strabo_polygon";
					}elseif($mygeotype=="LineString" || $mygeotype=="MultiLineString"){
						$thisstyle="m_strabo_line";
					}elseif($mygeotype=="Point"){
						$thisstyle="m_strabo_point";
					}else{
						$thisstyle="m_strabo_point";
					}

					if($mygeotype!=""){

						if($spot['properties']['name']!=""){
							$spotname = $spot['properties']['name'];
						}else{
							$spotname = $spot['properties']['id'];
						}

						if($mygeotype=="Polygon" || $mygeotype=="MultiPolygon"){
							//test polystyle override here
							$color = $this->getTagColor($spot['properties']['id'], $this->alltags);

							if($color!=""){
								$newcolor = "#88". substr($color, 5, 2) . substr($color, 3, 2) . substr($color, 1, 2);
								$polystyle = "<Style><PolyStyle><color>$newcolor</color><outline>0</outline></PolyStyle></Style>";
							}else{
								$polystyle = "<Style><PolyStyle><color>#4bDC7878</color><outline>0</outline></PolyStyle></Style>";
							}

						}

						$spotname = htmlspecialchars($spotname);

						$html.="<Placemark>\n<name>$spotname</name>\n<description>\n<![CDATA[\n";

						$html.="<img style=\"max-width:300px;\" src=\"files/bubblehead.jpg\">\n";

						$mygeojson=trim(json_encode($mygeojson));

						try {
							$mywkt=geoPHP::load($mygeojson,"json");
							$kmlgeo = $mywkt->out('kml');
						} catch (Exception $e) {
							$kmlgeo="";
						}

						$spot = $spot['properties'];

						$id = $spot['id'];

						$spotname = $spot['name'];
						if($spot['geometrytype']){
							$spotname .= " (".$spot['geometrytype'].")";
						}

						$html.="<div class=\"spotTitle\">Spot Name: $spotname</div>\n<br>\n";

						$modified = (string) $spot['id'];
						$modified = substr($modified,0,10);
						$modified = date("c",$modified);
						$html.="<div>Created: $modified</div>\n";

						$modified = (string) $spot['modified_timestamp'];
						$modified = substr($modified,0,10);
						$modified = date("c",$modified);
						$html.="<div>Last Modified: $modified</div>\n";

						if($spot['surface_feature']){
							foreach($spot['surface_feature'] as $key=>$value){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}

						if($spot['orientation_data']){
							$html.="<div>Orientations:</div>\n";
							$html.="<div class=\"leftPad\">\n";
							foreach($spot['orientation_data'] as $o){
								$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->type).": "."</div>\n";
								foreach($o as $key=>$value){
									if($key!="id" && $key!="associated_orientation" && $key!="type"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

								if($o->associated_orientation){
									$html.="<div>Orientations:</div>\n";
									$html.="<div class=\"leftPad\">\n";
									foreach($o->associated_orientation as $ao){
										$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->type).": "."</div>\n";
										foreach($ao as $key=>$value){
											if($key!="id" && $key!="associated_orientation" && $key!="type"){
												$key = $this->fixLabel($key);
												if(is_string($value)){
													$value = $this->fixLabel($value);
												}
												$html.="<div>$key: $value</div>\n";
											}
										}

									}

									$html.="</div>\n"; //end leftPad
								}

							}

							$html.="</div>\n"; //end leftPad
						}

						if($spot['_3d_structures']){
							$html.="<div>3D Structures:</div>\n";
							$html.="<div class=\"leftPad\">\n";
							foreach($spot['_3d_structures'] as $o){
								$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->type).": "."</div>\n";
								foreach($o as $key=>$value){
									if($key!="id" && $key!="type"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

							}

							$html.="</div>\n"; //end leftPad
						}

						if($spot['trace']){
							$html.="<div>Trace:</div>\n";
							$html.="<div class=\"leftPad\">\n";

							foreach($spot['trace'] as $key=>$value){
								if($key!="id" && $key!="label"){
									if($key!="trace_feature"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}
							}

							$html.="</div>\n"; //end leftPad
						}

						if($spot['samples']){
							$html.="<div>Samples:</div>\n";
							$html.="<div class=\"leftPad\">\n";
							foreach($spot['samples'] as $o){
								$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->label).": "."</div>\n";
								foreach($o as $key=>$value){
									if($key!="id" && $key!="label"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

							}

							$html.="</div>\n"; //end leftPad
						}

						if($spot['other_features']){
							$html.="<div>Other Features:</div>\n";
							$html.="<div class=\"leftPad\">\n";
							foreach($spot['other_features'] as $o){
								$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->label).": "."</div>\n";
								foreach($o as $key=>$value){
									if($key!="id" && $key!="label"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

							}

							$html.="</div>\n"; //end leftPad
						}

						if($project->tags){
							foreach($project->tags as $tag){
								$found = "no";
								if($tag->spots){
									foreach($tag->spots as $spotid){
										if($spotid == $id){
											$found = "yes";
										}
									}
								}

								if($found == "yes"){
									if($tag->type=="geologic_unit"){

										$html.="<div>Rock Unit:</div>\n";
										$html.="<div class=\"leftPad\">\n";
										foreach($tag as $key=>$value){
											if($key != "date" && $key != "spots" && $key != "features" && $key != "id" ){
												$key = $this->fixLabel($key);
												if(is_string($value)){
													$value = $this->fixLabel($value);
												}
												$html.="<div>$key: $value</div>\n";
											}
										}

										$html.="</div>\n"; //end leftPad
									}
								}
							}
						}

						$hastags = "no";

						if($this->alltags){
							foreach($this->alltags as $tag){
								$found = "no";
								if($tag->spots){
									if($tag->type!="geologic_unit"){
										foreach($tag->spots as $spotid){
											if($spotid == $id){
												$hastags = "yes";
											}
										}
									}
								}

							}
						}

						if($hastags == "yes"){

							$html.="<div>Tags:</div>\n";
							$html.="<div class=\"leftPad\">\n";

							if($this->alltags){
								foreach($this->alltags as $tag){
									$found = "no";
									if($tag->spots){
										if($tag->type!="geologic_unit"){
											foreach($tag->spots as $spotid){
												if($spotid == $id){
													$found = "yes";
												}
											}
										}
									}

									if($found == "yes"){

										$html.="<div class=\"sectionTitle\">".$tag->name.": "."</div>\n";
										foreach($tag as $key=>$value){

											if($key != "date" && $key != "spots" && $key != "features" && $key != "id" && $key != "name" ){
												$key = $this->fixLabel($key);
												if(is_string($value)){
													$value = $this->fixLabel($value);
												}
												$html.="<div>$key: $value</div>\n";
											}

										}

									}
								}
							}

							$html.="</div>\n"; //end leftPad

						}

						if($spot['images']){
							$html.="<div>Images:</div>\n";
							$html.="<div class=\"leftPad\">\n";
							foreach($spot['images'] as $o){
								if($o['title']){
									$thistitle = $this->fixLabel($o['title']);
								}else{
									$thistitle = $o['id'];
								}
								$html.="<div class=\"sectionTitle\">".$thistitle.": "."</div>\n";
								foreach($o as $key=>$value){
									if($key!="id" && $key!="self" && $key!="annotated" && $key!="title"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

								$imageid = $o['id'];

								$filename = $this->strabo->getImageFilename($imageid);

								if($filename){
									$gdimage = $this->gdThumb($filename);
									if($gdimage){
										//write image to folder here (imagecreatetruecolor)
										imagejpeg($gdimage, "ogrtemp/$randnum/data/files/$imageid.jpg");

										$html.="<div><a href=\"https://www.strabospot.org/geimage/$imageid\"><img src=\"files/$imageid.jpg\"></a></div>\n";
									}
								}

							}

							$html.="</div>\n"; //end leftPad
						}

						if($mygeotype=="Point"){
							//build custom icon here if needed
							$customstyle=$this->buildCustomPoint($spot,$randnum);
							if($customstyle!=""){
								$pointstyle=$customstyle;
							}else{
								$pointstyle="";
							}
						}else{
							$pointstyle="";
						}

						$html.="]]>\n</description>\n<styleUrl>#".$thisstyle."</styleUrl>".$pointstyle.$polystyle."\n$kmlgeo\n</Placemark>\n\n";

					}

				}// end foreach spot

					$stylestring = '				<html><head>
										<style type="text/css">
											html{
												margin:0px;
												padding:0px;
											}
											body{
												margin:2px;
												font-family: \'Open sans\', sans-serif;
												font-size: 13px;
												color: #666666;
												background-color: #ffffff;
												width:700px;
											}
											.spotTitle{
												font-weight:bold;
												font-size:15px;
											}
											.leftPad{
												padding-left:20px;
											}
											.sectionTitle{
												text-decoration:underline;
											}
										</style>
										</head>
										<body>$[description]</body>
									</html>';

									$html='<?xml version="1.0" encoding="UTF-8"?>
									<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
									<Document>
										<name>StraboSpot Data</name>

										<StyleMap id="m_strabo_point">
											<Pair>
												<key>normal</key>
												<styleUrl>#s_strabo_point</styleUrl>
											</Pair>
											<Pair>
												<key>highlight</key>
												<styleUrl>#s_strabo_point_hl</styleUrl>
											</Pair>
										</StyleMap>

										<Style id="s_strabo_point">
											<IconStyle>
												<scale>1.7</scale>
												<Icon>
													<href>files/dot.png</href>
												</Icon>
												<hotSpot x="50" y="50" xunits="pixels" yunits="pixels"/>
												<heading>360</heading>
											</IconStyle>
											<LineStyle>
												<color>ff000000</color>
												<width>1.5</width>
											</LineStyle>
											<PolyStyle>
												<color>4bDC7878</color>
											</PolyStyle>
										</Style>

										<Style id="s_strabo_point_hl">
											<IconStyle>
												<scale>1.9</scale>
												<Icon>
													<href>files/dot.png</href>
												</Icon>
												<hotSpot x="50" y="50" xunits="pixels" yunits="pixels"/>
												<heading>360</heading>
											</IconStyle>
											<LineStyle>
												<color>ff000000</color>
												<width>1.5</width>
											</LineStyle>
											<PolyStyle>
												<color>64DC7878</color>
											</PolyStyle>
											<BalloonStyle>
												<text><![CDATA[
												'.$stylestring.'
												]]></text>
											</BalloonStyle>
										</Style>

										<StyleMap id="m_strabo_line">
											<Pair>
												<key>normal</key>
												<styleUrl>#s_strabo_line</styleUrl>
											</Pair>
											<Pair>
												<key>highlight</key>
												<styleUrl>#s_strabo_line_hl</styleUrl>
											</Pair>
										</StyleMap>

										<Style id="s_strabo_line">
											<LineStyle>
												<color>ff000000</color>
												<width>2</width>
											</LineStyle>
										</Style>

										<Style id="s_strabo_line_hl">
											<LineStyle>
												<color>ff000000</color>
												<width>4</width>
											</LineStyle>
											<BalloonStyle>
												<text><![CDATA[
												'.$stylestring.'
												]]></text>
											</BalloonStyle>
										</Style>

										<StyleMap id="m_strabo_polygon">
											<Pair>
												<key>normal</key>
												<styleUrl>#s_strabo_polygon</styleUrl>
											</Pair>
											<Pair>
												<key>highlight</key>
												<styleUrl>#s_strabo_polygon_hl</styleUrl>
											</Pair>
										</StyleMap>

										<Style id="s_strabo_polygon">
											<LineStyle>
												<color>ff000000</color>
												<width>1.5</width>
											</LineStyle>
											<PolyStyle>
												<color>4bDC7878</color>
											</PolyStyle>
										</Style>

										<Style id="s_strabo_polygon_hl">
											<LineStyle>
												<color>ff000000</color>
												<width>1.5</width>
											</LineStyle>
											<PolyStyle>
												<color>641478FF</color>
											</PolyStyle>
											<BalloonStyle>
												<text><![CDATA[
												'.$stylestring.'
												]]></text>
											</BalloonStyle>
										</Style>
										';

if($pointHtml != ""){
	$html .=	'								<Folder>
											<name>Points</name>
											<open>0</open>
											<Style>
												<ListStyle>
													<listItemType>check</listItemType>
													<ItemIcon>
														<state>open</state>
														<href>files/mysavedplaces_open.png</href>
													</ItemIcon>
													<ItemIcon>
														<state>closed</state>
														<href>files/mysavedplaces_closed.png</href>
													</ItemIcon>
													<bgColor>14F0C814</bgColor>
													<maxSnippetLines>0</maxSnippetLines>
												</ListStyle>
											</Style> <!--placemarks here -->

									'.$pointHtml.'

							<ScreenOverlay>
								  <Style>
									<ListStyle>
									  <ItemIcon>
										<href>
										  <a href="http://www.earthchemportal.org">EarthChem</a>
										</href>
									  </ItemIcon>
									  <listItemType>checkHideChildren</listItemType>
									</ListStyle>
								  </Style>
								  <name>
									<a href="https://www.strabospot.org">StraboSpot</a>
								  </name>
								  <Icon>
									<href>files/overlay.jpg</href>
								  </Icon>
								  <overlayXY x="0.01" y="0.035" xunits="fraction" yunits="fraction" />
								  <screenXY x="0.01" y="0.035" xunits="fraction" yunits="fraction" />
								  <size x="300" y="55" xunits="pixels" yunits="pixels" />
								</ScreenOverlay>

							</Folder>
							';
}

if($lineHtml != ""){
	$html .=	'								<Folder>
											<name>Lines</name>
											<open>0</open>
											<Style>
												<ListStyle>
													<listItemType>check</listItemType>
													<ItemIcon>
														<state>open</state>
														<href>files/mysavedplaces_open.png</href>
													</ItemIcon>
													<ItemIcon>
														<state>closed</state>
														<href>files/mysavedplaces_closed.png</href>
													</ItemIcon>
													<bgColor>14F0C814</bgColor>
													<maxSnippetLines>0</maxSnippetLines>
												</ListStyle>
											</Style> <!--placemarks here -->

									'.$lineHtml.'

							<ScreenOverlay>
								  <Style>
									<ListStyle>
									  <ItemIcon>
										<href>
										  <a href="http://www.earthchemportal.org">EarthChem</a>
										</href>
									  </ItemIcon>
									  <listItemType>checkHideChildren</listItemType>
									</ListStyle>
								  </Style>
								  <name>
									<a href="https://www.strabospot.org">StraboSpot</a>
								  </name>
								  <Icon>
									<href>files/overlay.jpg</href>
								  </Icon>
								  <overlayXY x="0.01" y="0.035" xunits="fraction" yunits="fraction" />
								  <screenXY x="0.01" y="0.035" xunits="fraction" yunits="fraction" />
								  <size x="300" y="55" xunits="pixels" yunits="pixels" />
								</ScreenOverlay>

							</Folder>
							';
}

if($polygonHtml != ""){
	$html .=	'								<Folder>
											<name>Polygons</name>
											<open>0</open>
											<Style>
												<ListStyle>
													<listItemType>check</listItemType>
													<ItemIcon>
														<state>open</state>
														<href>files/mysavedplaces_open.png</href>
													</ItemIcon>
													<ItemIcon>
														<state>closed</state>
														<href>files/mysavedplaces_closed.png</href>
													</ItemIcon>
													<bgColor>14F0C814</bgColor>
													<maxSnippetLines>0</maxSnippetLines>
												</ListStyle>
											</Style> <!--placemarks here -->

									'.$polygonHtml.'

							<ScreenOverlay>
								  <Style>
									<ListStyle>
									  <ItemIcon>
										<href>
										  <a href="http://www.earthchemportal.org">EarthChem</a>
										</href>
									  </ItemIcon>
									  <listItemType>checkHideChildren</listItemType>
									</ListStyle>
								  </Style>
								  <name>
									<a href="https://www.strabospot.org">StraboSpot</a>
								  </name>
								  <Icon>
									<href>files/overlay.jpg</href>
								  </Icon>
								  <overlayXY x="0.01" y="0.035" xunits="fraction" yunits="fraction" />
								  <screenXY x="0.01" y="0.035" xunits="fraction" yunits="fraction" />
								  <size x="300" y="55" xunits="pixels" yunits="pixels" />
								</ScreenOverlay>

							</Folder>
							';
}

$html.='

						</Document>
						</kml>';

				file_put_contents("ogrtemp/$randnum/data/doc.kml", $html);

				$filedate = date("m_d_Y");

				exec("cd ogrtemp/$randnum/data; zip -r strabo_$filedate.kmz doc.kml files 2>&1",$results);

					//zip -r foo.zip doc.kml files

				//force download of file
				header("Content-Type: application/kmz");
				header("Content-Disposition: attachment; filename=strabo_$filedate.kmz");
				header("Content-Length: " . filesize("ogrtemp/$randnum/data/strabo_$filedate.kmz"));

				readfile("ogrtemp/$randnum/data/strabo_$filedate.kmz");

				//remove temp directory
				if($randnum!=""){
				}

			}else{

				echo "No spots found for this search.";

			}

		} //end if dsids

	}

public function getKMLHtml($inSpots, $geoType){

		$spots = [];
		foreach($inSpots as $spot){

			$mygeojson=$spot['geometry'];
			$mygeotype = $mygeojson->type;

			if($geoType == "polygon"){
				if($mygeotype=="Polygon" || $mygeotype=="MultiPolygon"){
					$spots[] = $spot;
				}
			}elseif($geoType == "line"){
				if($mygeotype=="LineString" || $mygeotype=="MultiLineString"){
					$spots[] = $spot;
				}
			}elseif($geoType == "point"){

				if($mygeotype=="Polygon" || $mygeotype=="MultiPolygon"){
				}elseif($mygeotype=="LineString" || $mygeotype=="MultiLineString"){
				}elseif($mygeotype=="Point"){
					$spots[] = $spot;
				}else{
					$spots[] = $spot;
				}
			}
		}

		foreach($spots as $spot){

			//use geoPHP to get WKT
			$mygeojson=$spot['geometry'];

			$mygeotype = $mygeojson->type;

			//pick a style for KML feature
			if($mygeotype=="Polygon" || $mygeotype=="MultiPolygon"){
				$thisstyle="m_strabo_polygon";
			}elseif($mygeotype=="LineString" || $mygeotype=="MultiLineString"){
				$thisstyle="m_strabo_line";
			}elseif($mygeotype=="Point"){
				$thisstyle="m_strabo_point";
			}else{
				$thisstyle="m_strabo_point";
			}

			if($mygeotype!=""){

				if($spot['properties']['name']!=""){
					$spotname = $spot['properties']['name'];
				}else{
					$spotname = $spot['properties']['id'];
				}

				if($mygeotype=="Polygon" || $mygeotype=="MultiPolygon"){
					//test polystyle override here
					$color = $this->getTagColor($spot['properties']['id'], $this->alltags);

					if($color!=""){
						$newcolor = "#88". substr($color, 5, 2) . substr($color, 3, 2) . substr($color, 1, 2);
						$polystyle = "<Style><PolyStyle><color>$newcolor</color><outline>0</outline></PolyStyle></Style>";
					}else{
						$polystyle = "<Style><PolyStyle><color>#4bDC7878</color><outline>0</outline></PolyStyle></Style>";
					}

				}

				$spotname = htmlspecialchars($spotname);

				$html.="<Placemark>\n<name>$spotname</name>\n<description>\n<![CDATA[\n";

				$html.="<img style=\"max-width:300px;\" src=\"files/bubblehead.jpg\">\n";

				$mygeojson=trim(json_encode($mygeojson));

				try {
					$mywkt=geoPHP::load($mygeojson,"json");
					$kmlgeo = $mywkt->out('kml');
				} catch (Exception $e) {
					$kmlgeo="";
				}

				$spot = $spot['properties'];

				$id = $spot['id'];

				$spotname = $spot['name'];
				if($spot['geometrytype']){
					$spotname .= " (".$spot['geometrytype'].")";
				}

				$html.="<div class=\"spotTitle\">Spot Name: $spotname</div>\n<br>\n";

				$modified = (string) $spot['id'];
				$modified = substr($modified,0,10);
				$modified = date("c",$modified);
				$html.="<div>Created: $modified</div>\n";

				$modified = (string) $spot['modified_timestamp'];
				$modified = substr($modified,0,10);
				$modified = date("c",$modified);
				$html.="<div>Last Modified: $modified</div>\n";

				if($spot['surface_feature']){
					foreach($spot['surface_feature'] as $key=>$value){
						$key = $this->fixLabel($key);
						if(is_string($value)){
							$value = $this->fixLabel($value);
						}
						$html.="<div>$key: $value</div>\n";
					}
				}

				if($spot['orientation_data']){
					$html.="<div>Orientations:</div>\n";
					$html.="<div class=\"leftPad\">\n";
					foreach($spot['orientation_data'] as $o){
						$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->type).": "."</div>\n";
						foreach($o as $key=>$value){
							if($key!="id" && $key!="associated_orientation" && $key!="type"){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}

						if($o->associated_orientation){
							$html.="<div>Orientations:</div>\n";
							$html.="<div class=\"leftPad\">\n";
							foreach($o->associated_orientation as $ao){
								$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->type).": "."</div>\n";
								foreach($ao as $key=>$value){
									if($key!="id" && $key!="associated_orientation" && $key!="type"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

							}

							$html.="</div>\n"; //end leftPad
						}

					}

					$html.="</div>\n"; //end leftPad
				}

				if($spot['pet']){
					if($spot['pet']->metamorphic){
						$html.="<div>Metamorphic Rock(s):</div>\n";
						$html.="<div class=\"leftPad\">\n";
						if($spot['pet']->metamorphic){

							$rocks = $spot['pet']->metamorphic;

							$rockNum = 1;
							foreach($rocks as $r){
								$rockString = "";
								if($r->metamorphic_rock_type != "") $rockString .= "" . $r->metamorphic_rock_type;
								if($r->facies != "") $rockString .= ", " . implode(", ", $r->facies);
								if($r->protolith != "") $rockString .= ", " . $r->protolith;
								if($r->zone != "") $rockString .= ", " . implode(", ", $r->zone);

								$html.="<div class=\"\">".$rockNum.": ".$rockString."</div>\n";
								$rockNum++;
							}

						}

						$html.="</div>\n"; //end leftPad
					}

					if($spot['pet']->igneous){
						$html.="<div>Igneous Rock(s):</div>\n";
						$html.="<div class=\"leftPad\">\n";
						if($spot['pet']->metamorphic){

							$rocks = $spot['pet']->igneous;

							$rockNum = 1;
							foreach($rocks as $r){
								$rockString = "";
								if($r->igneous_rock_class) $rockString .= "" . $r->igneous_rock_class;
								if($r->volcanic_rock_type) $rockString .= ", " . $r->volcanic_rock_type;
								if($r->occurence_volcanic) $rockString .= ", " . $r->occurence_volcanic;
								if($r->plutonic_rock_type) $rockString .= ", " . $r->plutonic_rock_type;
								if($r->occurence_plutonic) $rockString .= ", " . $r->occurence_plutonic;
								if($r->texture_volcanic) $rockString .= ", " . implode(", ", $r->texture_volcanic);
								if($r->texture_plutonic) $rockString .= ", " . implode(", ", $r->texture_plutonic);
								if($r->color_index_volc) $rockString .= ", " . $r->color_index_volc;
								if($r->color_index_pluton) $rockString .= ", " . $r->color_index_pluton;
								if($r->color_index_source_volc) $rockString .= ", " . $r->color_index_source_volc;
								if($r->color_index_source_pluton) $rockString .= ", " . $r->color_index_source_pluton;
								if($r->alteration_volcanic) $rockString .= ", " . implode(", ", $r->alteration_volcanic);
								if($r->alteration_plutonic) $rockString .= ", " . implode(", ", $r->alteration_plutonic);

								$html.="<div class=\"\">".$rockNum.": ".$rockString."</div>\n";
								$rockNum++;
							}

						}

						$html.="</div>\n"; //end leftPad
					}

					if($spot['pet']->igneous){
						$html.="<div>Mineral(s):</div>\n";
						$html.="<div class=\"leftPad\">\n";
						if($spot['pet']->minerals){

							$rocks = $spot['pet']->minerals;

							$rockNum = 1;
							foreach($rocks as $r){
								$rockString = "";
								if($r->full_mineral_name) $rockString .= ", " . $r->full_mineral_name;
								if($r->igneous_or_metamorphic == "ig_min"){
									$rockString .= " (Igneous)";
								}else{
									$rockString .= " (Metamorphic)";
								}
								if($r->average_grain_size_mm) $rockString .= ", Avg Size: " . $r->average_grain_size_mm . "mm";
								if($r->maximum_grain_size_mm) $rockString .= ", Max Size: " . $r->maximum_grain_size_mm . "mm";
								if($r->modal) $rockString .= ", Modal: " . $r->modal . "%";
								if($r->mineral_notes) $rockString .= " " . $r->mineral_notes;

								$html.="<div class=\"\">".$rockNum.": ".$rockString."</div>\n";
								$rockNum++;
							}

						}

						$html.="</div>\n"; //end leftPad
					}

				}

				if($spot['_3d_structures']){
					$html.="<div>3D Structures:</div>\n";
					$html.="<div class=\"leftPad\">\n";
					foreach($spot['_3d_structures'] as $o){
						$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->type).": "."</div>\n";
						foreach($o as $key=>$value){
							if($key!="id" && $key!="type"){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}

					}

					$html.="</div>\n"; //end leftPad
				}

				if($spot['trace']){
					$html.="<div>Trace:</div>\n";
					$html.="<div class=\"leftPad\">\n";

					foreach($spot['trace'] as $key=>$value){
						if($key!="id" && $key!="label"){
							if($key!="trace_feature"){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}
					}

					$html.="</div>\n"; //end leftPad
				}

				if($spot['samples']){
					$html.="<div>Samples:</div>\n";
					$html.="<div class=\"leftPad\">\n";
					foreach($spot['samples'] as $o){
						$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->label).": "."</div>\n";
						foreach($o as $key=>$value){
							if($key!="id" && $key!="label"){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}

					}

					$html.="</div>\n"; //end leftPad
				}

				if($spot['tephra']){
					$html.="<div>Tephra Intervals:</div>\n";
					$html.="<div class=\"leftPad\">\n";
					foreach($spot['tephra'] as $o){
						$html.="<div class=\"sectionTitle\">Interval:</div>\n";
						foreach($o as $key=>$value){
							if($key!="id" && $key!="label"){
								$key = $this->fixLabel($key);
								if(is_array($value)){
									$value = implode(", ", $value);
								}elseif(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}

					}

					$html.="</div>\n"; //end leftPad
				}

				if($spot['other_features']){
					$html.="<div>Other Features:</div>\n";
					$html.="<div class=\"leftPad\">\n";
					foreach($spot['other_features'] as $o){
						$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->label).": "."</div>\n";
						foreach($o as $key=>$value){
							if($key!="id" && $key!="label"){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}

					}

					$html.="</div>\n"; //end leftPad
				}

				if($project->tags){
					foreach($project->tags as $tag){
						$found = "no";
						if($tag->spots){
							foreach($tag->spots as $spotid){
								if($spotid == $id){
									$found = "yes";
								}
							}
						}

						if($found == "yes"){
							if($tag->type=="geologic_unit"){

								$html.="<div>Rock Unit:</div>\n";
								$html.="<div class=\"leftPad\">\n";
								foreach($tag as $key=>$value){
									if($key != "date" && $key != "spots" && $key != "features" && $key != "id" ){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

								$html.="</div>\n"; //end leftPad
							}
						}
					}
				}

				$hastags = "no";

				if($this->alltags){
					foreach($this->alltags as $tag){
						$found = "no";
						if($tag->spots){
							if($tag->type!="geologic_unit"){
								foreach($tag->spots as $spotid){
									if($spotid == $id){
										$hastags = "yes";
									}
								}
							}
						}

					}
				}

				if($hastags == "yes"){

					$html.="<div>Tags:</div>\n";
					$html.="<div class=\"leftPad\">\n";

					if($this->alltags){
						foreach($this->alltags as $tag){
							$found = "no";
							if($tag->spots){
								if($tag->type!="geologic_unit"){
									foreach($tag->spots as $spotid){
										if($spotid == $id){
											$found = "yes";
										}
									}
								}
							}

							if($found == "yes"){

								$html.="<div class=\"sectionTitle\">".$tag->name.": "."</div>\n";
								foreach($tag as $key=>$value){

									if($key != "date" && $key != "spots" && $key != "features" && $key != "id" && $key != "name" ){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}

								}

							}
						}
					}

					$html.="</div>\n"; //end leftPad

				}

				if($spot['images']){
					$html.="<div>Images:</div>\n";
					$html.="<div class=\"leftPad\">\n";
					foreach($spot['images'] as $o){
						if($o['title']){
							$thistitle = $this->fixLabel($o['title']);
						}else{
							$thistitle = $o['id'];
						}
						$html.="<div class=\"sectionTitle\">".$thistitle.": "."</div>\n";
						foreach($o as $key=>$value){
							if($key!="id" && $key!="self" && $key!="annotated" && $key!="title"){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}

						$imageid = $o['id'];

						$filename = $this->strabo->getImageFilename($imageid);

						if($filename){
							$gdimage = $this->gdThumb($filename);
							if($gdimage){
								//write image to folder here (imagecreatetruecolor)
								imagejpeg($gdimage, "ogrtemp/$randnum/data/files/$imageid.jpg");

								$html.="<div><a href=\"https://www.strabospot.org/geimage/$imageid\"><img src=\"files/$imageid.jpg\"></a></div>\n";
							}
						}

					}

					$html.="</div>\n"; //end leftPad
				}

				if($mygeotype=="Point"){
					//build custom icon here if needed
					$customstyle=$this->buildCustomPoint($spot,$randnum);
					if($customstyle!=""){
						$pointstyle=$customstyle;
					}else{
						$pointstyle="";
					}
				}else{
					$pointstyle="";
				}

				$html.="]]>\n</description>\n<styleUrl>#".$thisstyle."</styleUrl>".$pointstyle.$polystyle."\n$kmlgeo\n</Placemark>\n\n";

			}

		}// end foreach spot

		return $html;
	}

	public function getKMLHtml20240531($inSpots, $geoType){

		$spots = [];
		foreach($inSpots as $spot){

			$mygeojson=$spot['geometry'];
			$mygeotype = $mygeojson->type;

			if($geoType == "polygon"){
				if($mygeotype=="Polygon" || $mygeotype=="MultiPolygon"){
					$spots[] = $spot;
				}
			}elseif($geoType == "line"){
				if($mygeotype=="LineString" || $mygeotype=="MultiLineString"){
					$spots[] = $spot;
				}
			}elseif($geoType == "point"){

				if($mygeotype=="Polygon" || $mygeotype=="MultiPolygon"){
				}elseif($mygeotype=="LineString" || $mygeotype=="MultiLineString"){
				}elseif($mygeotype=="Point"){
					$spots[] = $spot;
				}else{
					$spots[] = $spot;
				}
			}
		}

		foreach($spots as $spot){

			//use geoPHP to get WKT
			$mygeojson=$spot['geometry'];

			$mygeotype = $mygeojson->type;

			//pick a style for KML feature
			if($mygeotype=="Polygon" || $mygeotype=="MultiPolygon"){
				$thisstyle="m_strabo_polygon";
			}elseif($mygeotype=="LineString" || $mygeotype=="MultiLineString"){
				$thisstyle="m_strabo_line";
			}elseif($mygeotype=="Point"){
				$thisstyle="m_strabo_point";
			}else{
				$thisstyle="m_strabo_point";
			}

			if($mygeotype!=""){

				if($spot['properties']['name']!=""){
					$spotname = $spot['properties']['name'];
				}else{
					$spotname = $spot['properties']['id'];
				}

				if($mygeotype=="Polygon" || $mygeotype=="MultiPolygon"){
					//test polystyle override here
					$color = $this->getTagColor($spot['properties']['id'], $this->alltags);

					if($color!=""){
						$newcolor = "#88". substr($color, 5, 2) . substr($color, 3, 2) . substr($color, 1, 2);
						$polystyle = "<Style><PolyStyle><color>$newcolor</color><outline>0</outline></PolyStyle></Style>";
					}else{
						$polystyle = "<Style><PolyStyle><color>#4bDC7878</color><outline>0</outline></PolyStyle></Style>";
					}

				}

				$spotname = htmlspecialchars($spotname);

				$html.="<Placemark>\n<name>$spotname</name>\n<description>\n<![CDATA[\n";

				$html.="<img style=\"max-width:300px;\" src=\"files/bubblehead.jpg\">\n";

				$mygeojson=trim(json_encode($mygeojson));

				try {
					$mywkt=geoPHP::load($mygeojson,"json");
					$kmlgeo = $mywkt->out('kml');
				} catch (Exception $e) {
					$kmlgeo="";
				}

				$spot = $spot['properties'];

				$id = $spot['id'];

				$spotname = $spot['name'];
				if($spot['geometrytype']){
					$spotname .= " (".$spot['geometrytype'].")";
				}

				$html.="<div class=\"spotTitle\">Spot Name: $spotname</div>\n<br>\n";

				$modified = (string) $spot['id'];
				$modified = substr($modified,0,10);
				$modified = date("c",$modified);
				$html.="<div>Created: $modified</div>\n";

				$modified = (string) $spot['modified_timestamp'];
				$modified = substr($modified,0,10);
				$modified = date("c",$modified);
				$html.="<div>Last Modified: $modified</div>\n";

				if($spot['surface_feature']){
					foreach($spot['surface_feature'] as $key=>$value){
						$key = $this->fixLabel($key);
						if(is_string($value)){
							$value = $this->fixLabel($value);
						}
						$html.="<div>$key: $value</div>\n";
					}
				}

				if($spot['orientation_data']){
					$html.="<div>Orientations:</div>\n";
					$html.="<div class=\"leftPad\">\n";
					foreach($spot['orientation_data'] as $o){
						$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->type).": "."</div>\n";
						foreach($o as $key=>$value){
							if($key!="id" && $key!="associated_orientation" && $key!="type"){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}

						if($o->associated_orientation){
							$html.="<div>Orientations:</div>\n";
							$html.="<div class=\"leftPad\">\n";
							foreach($o->associated_orientation as $ao){
								$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->type).": "."</div>\n";
								foreach($ao as $key=>$value){
									if($key!="id" && $key!="associated_orientation" && $key!="type"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

							}

							$html.="</div>\n"; //end leftPad
						}

					}

					$html.="</div>\n"; //end leftPad
				}

				if($spot['pet']){
					if($spot['pet']->metamorphic){
						$html.="<div>Metamorphic Rock(s):</div>\n";
						$html.="<div class=\"leftPad\">\n";
						if($spot['pet']->metamorphic){

							$rocks = $spot['pet']->metamorphic;

							$rockNum = 1;
							foreach($rocks as $r){
								$rockString = "";
								if($r->metamorphic_rock_type != "") $rockString .= "" . $r->metamorphic_rock_type;
								if($r->facies != "") $rockString .= ", " . implode(", ", $r->facies);
								if($r->protolith != "") $rockString .= ", " . $r->protolith;
								if($r->zone != "") $rockString .= ", " . implode(", ", $r->zone);

								$html.="<div class=\"\">".$rockNum.": ".$rockString."</div>\n";
								$rockNum++;
							}

						}

						$html.="</div>\n"; //end leftPad
					}

					if($spot['pet']->igneous){
						$html.="<div>Igneous Rock(s):</div>\n";
						$html.="<div class=\"leftPad\">\n";
						if($spot['pet']->metamorphic){

							$rocks = $spot['pet']->igneous;

							$rockNum = 1;
							foreach($rocks as $r){
								$rockString = "";
								if($r->igneous_rock_class) $rockString .= "" . $r->igneous_rock_class;
								if($r->volcanic_rock_type) $rockString .= ", " . $r->volcanic_rock_type;
								if($r->occurence_volcanic) $rockString .= ", " . $r->occurence_volcanic;
								if($r->plutonic_rock_type) $rockString .= ", " . $r->plutonic_rock_type;
								if($r->occurence_plutonic) $rockString .= ", " . $r->occurence_plutonic;
								if($r->texture_volcanic) $rockString .= ", " . implode(", ", $r->texture_volcanic);
								if($r->texture_plutonic) $rockString .= ", " . implode(", ", $r->texture_plutonic);
								if($r->color_index_volc) $rockString .= ", " . $r->color_index_volc;
								if($r->color_index_pluton) $rockString .= ", " . $r->color_index_pluton;
								if($r->color_index_source_volc) $rockString .= ", " . $r->color_index_source_volc;
								if($r->color_index_source_pluton) $rockString .= ", " . $r->color_index_source_pluton;
								if($r->alteration_volcanic) $rockString .= ", " . implode(", ", $r->alteration_volcanic);
								if($r->alteration_plutonic) $rockString .= ", " . implode(", ", $r->alteration_plutonic);

								$html.="<div class=\"\">".$rockNum.": ".$rockString."</div>\n";
								$rockNum++;
							}

						}

						$html.="</div>\n"; //end leftPad
					}

					if($spot['pet']->igneous){
						$html.="<div>Mineral(s):</div>\n";
						$html.="<div class=\"leftPad\">\n";
						if($spot['pet']->minerals){

							$rocks = $spot['pet']->minerals;

							$rockNum = 1;
							foreach($rocks as $r){
								$rockString = "";
								if($r->full_mineral_name) $rockString .= ", " . $r->full_mineral_name;
								if($r->igneous_or_metamorphic == "ig_min"){
									$rockString .= " (Igneous)";
								}else{
									$rockString .= " (Metamorphic)";
								}
								if($r->average_grain_size_mm) $rockString .= ", Avg Size: " . $r->average_grain_size_mm . "mm";
								if($r->maximum_grain_size_mm) $rockString .= ", Max Size: " . $r->maximum_grain_size_mm . "mm";
								if($r->modal) $rockString .= ", Modal: " . $r->modal . "%";
								if($r->mineral_notes) $rockString .= " " . $r->mineral_notes;

								$html.="<div class=\"\">".$rockNum.": ".$rockString."</div>\n";
								$rockNum++;
							}

						}

						$html.="</div>\n"; //end leftPad
					}

				}

				if($spot['_3d_structures']){
					$html.="<div>3D Structures:</div>\n";
					$html.="<div class=\"leftPad\">\n";
					foreach($spot['_3d_structures'] as $o){
						$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->type).": "."</div>\n";
						foreach($o as $key=>$value){
							if($key!="id" && $key!="type"){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}

					}

					$html.="</div>\n"; //end leftPad
				}

				if($spot['trace']){
					$html.="<div>Trace:</div>\n";
					$html.="<div class=\"leftPad\">\n";

					foreach($spot['trace'] as $key=>$value){
						if($key!="id" && $key!="label"){
							if($key!="trace_feature"){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}
					}

					$html.="</div>\n"; //end leftPad
				}

				if($spot['samples']){
					$html.="<div>Samples:</div>\n";
					$html.="<div class=\"leftPad\">\n";
					foreach($spot['samples'] as $o){
						$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->label).": "."</div>\n";
						foreach($o as $key=>$value){
							if($key!="id" && $key!="label"){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}

					}

					$html.="</div>\n"; //end leftPad
				}

				if($spot['other_features']){
					$html.="<div>Other Features:</div>\n";
					$html.="<div class=\"leftPad\">\n";
					foreach($spot['other_features'] as $o){
						$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->label).": "."</div>\n";
						foreach($o as $key=>$value){
							if($key!="id" && $key!="label"){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}

					}

					$html.="</div>\n"; //end leftPad
				}

				if($project->tags){
					foreach($project->tags as $tag){
						$found = "no";
						if($tag->spots){
							foreach($tag->spots as $spotid){
								if($spotid == $id){
									$found = "yes";
								}
							}
						}

						if($found == "yes"){
							if($tag->type=="geologic_unit"){

								$html.="<div>Rock Unit:</div>\n";
								$html.="<div class=\"leftPad\">\n";
								foreach($tag as $key=>$value){
									if($key != "date" && $key != "spots" && $key != "features" && $key != "id" ){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

								$html.="</div>\n"; //end leftPad
							}
						}
					}
				}

				$hastags = "no";

				if($this->alltags){
					foreach($this->alltags as $tag){
						$found = "no";
						if($tag->spots){
							if($tag->type!="geologic_unit"){
								foreach($tag->spots as $spotid){
									if($spotid == $id){
										$hastags = "yes";
									}
								}
							}
						}

					}
				}

				if($hastags == "yes"){

					$html.="<div>Tags:</div>\n";
					$html.="<div class=\"leftPad\">\n";

					if($this->alltags){
						foreach($this->alltags as $tag){
							$found = "no";
							if($tag->spots){
								if($tag->type!="geologic_unit"){
									foreach($tag->spots as $spotid){
										if($spotid == $id){
											$found = "yes";
										}
									}
								}
							}

							if($found == "yes"){

								$html.="<div class=\"sectionTitle\">".$tag->name.": "."</div>\n";
								foreach($tag as $key=>$value){

									if($key != "date" && $key != "spots" && $key != "features" && $key != "id" && $key != "name" ){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}

								}

							}
						}
					}

					$html.="</div>\n"; //end leftPad

				}

				if($spot['images']){
					$html.="<div>Images:</div>\n";
					$html.="<div class=\"leftPad\">\n";
					foreach($spot['images'] as $o){
						if($o['title']){
							$thistitle = $this->fixLabel($o['title']);
						}else{
							$thistitle = $o['id'];
						}
						$html.="<div class=\"sectionTitle\">".$thistitle.": "."</div>\n";
						foreach($o as $key=>$value){
							if($key!="id" && $key!="self" && $key!="annotated" && $key!="title"){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}

						$imageid = $o['id'];

						$filename = $this->strabo->getImageFilename($imageid);

						if($filename){
							$gdimage = $this->gdThumb($filename);
							if($gdimage){
								//write image to folder here (imagecreatetruecolor)
								imagejpeg($gdimage, "ogrtemp/$randnum/data/files/$imageid.jpg");

								$html.="<div><a href=\"https://www.strabospot.org/geimage/$imageid\"><img src=\"files/$imageid.jpg\"></a></div>\n";
							}
						}

					}

					$html.="</div>\n"; //end leftPad
				}

				if($mygeotype=="Point"){
					//build custom icon here if needed
					$customstyle=$this->buildCustomPoint($spot,$randnum);
					if($customstyle!=""){
						$pointstyle=$customstyle;
					}else{
						$pointstyle="";
					}
				}else{
					$pointstyle="";
				}

				$html.="]]>\n</description>\n<styleUrl>#".$thisstyle."</styleUrl>".$pointstyle.$polystyle."\n$kmlgeo\n</Placemark>\n\n";

			}

		}// end foreach spot

		return $html;
	}

	public function bkupkmlOut20231014b(){

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->newSearchGetTagsFromDatasetIds($dsids);
			$json = $this->strabo->newSearchGetDatasetSpotsSearch(null, $this->get);

			$spots = $json['features'];

			if(count($spots)>0){

				$randnum=$this->strabo->db->get_var("select nextval('file_seq')");

				//make directory in ogrtemp to hold data
				mkdir("ogrtemp/$randnum");
				mkdir("ogrtemp/$randnum/data");
				mkdir("ogrtemp/$randnum/data/files");

				copy("assets/files/kmlfiles/bubblehead.jpg","ogrtemp/$randnum/data/files/bubblehead.jpg");

				copy("assets/files/kmlfiles/bubbleicon/dotbubble.png","ogrtemp/$randnum/data/files/dot.png");

				copy("assets/files/kmlfiles/mysavedplaces_closed.png","ogrtemp/$randnum/data/files/mysavedplaces_closed.png");
				copy("assets/files/kmlfiles/mysavedplaces_open.png","ogrtemp/$randnum/data/files/mysavedplaces_open.png");
				copy("assets/files/kmlfiles/overlay.jpg","ogrtemp/$randnum/data/files/overlay.jpg");
				copy("assets/files/kmlfiles/rock.jpg","ogrtemp/$randnum/data/files/rock.jpg");


				foreach($spots as $spot){

					//use geoPHP to get WKT
					$mygeojson=$spot['geometry'];

					$mygeotype = $mygeojson->type;

					//pick a style for KML feature
					if($mygeotype=="Polygon" || $mygeotype=="MultiPolygon"){
						$thisstyle="m_strabo_polygon";
					}elseif($mygeotype=="LineString" || $mygeotype=="MultiLineString"){
						$thisstyle="m_strabo_line";
					}elseif($mygeotype=="Point"){
						$thisstyle="m_strabo_point";
					}else{
						$thisstyle="m_strabo_point";
					}

					if($mygeotype!=""){

						if($spot['properties']['name']!=""){
							$spotname = $spot['properties']['name'];
						}else{
							$spotname = $spot['properties']['id'];
						}

						$html.="<Placemark>\n<name>$spotname</name>\n<description>\n<![CDATA[\n";

						$html.="<img style=\"max-width:300px;\" src=\"files/bubblehead.jpg\">\n";

						$mygeojson=trim(json_encode($mygeojson));

						try {
							$mywkt=geoPHP::load($mygeojson,"json");
							$kmlgeo = $mywkt->out('kml');
						} catch (Exception $e) {
							$kmlgeo="";
						}

						$spot = $spot['properties'];

						$id = $spot['id'];

						$spotname = $spot['name'];
						if($spot['geometrytype']){
							$spotname .= " (".$spot['geometrytype'].")";
						}

						$html.="<div class=\"spotTitle\">Spot Name: $spotname</div>\n<br>\n";

						$modified = (string) $spot['id'];
						$modified = substr($modified,0,10);
						$modified = date("c",$modified);
						$html.="<div>Created: $modified</div>\n";

						$modified = (string) $spot['modified_timestamp'];
						$modified = substr($modified,0,10);
						$modified = date("c",$modified);
						$html.="<div>Last Modified: $modified</div>\n";

						if($spot['surface_feature']){
							foreach($spot['surface_feature'] as $key=>$value){
								$key = $this->fixLabel($key);
								if(is_string($value)){
									$value = $this->fixLabel($value);
								}
								$html.="<div>$key: $value</div>\n";
							}
						}

						if($spot['orientation_data']){
							$html.="<div>Orientations:</div>\n";
							$html.="<div class=\"leftPad\">\n";
							foreach($spot['orientation_data'] as $o){
								$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->type).": "."</div>\n";
								foreach($o as $key=>$value){
									if($key!="id" && $key!="associated_orientation" && $key!="type"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

								if($o->associated_orientation){
									$html.="<div>Orientations:</div>\n";
									$html.="<div class=\"leftPad\">\n";
									foreach($o->associated_orientation as $ao){
										$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->type).": "."</div>\n";
										foreach($ao as $key=>$value){
											if($key!="id" && $key!="associated_orientation" && $key!="type"){
												$key = $this->fixLabel($key);
												if(is_string($value)){
													$value = $this->fixLabel($value);
												}
												$html.="<div>$key: $value</div>\n";
											}
										}

									}

									$html.="</div>\n"; //end leftPad
								}

							}

							$html.="</div>\n"; //end leftPad
						}

						if($spot['_3d_structures']){
							$html.="<div>3D Structures:</div>\n";
							$html.="<div class=\"leftPad\">\n";
							foreach($spot['_3d_structures'] as $o){
								$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->type).": "."</div>\n";
								foreach($o as $key=>$value){
									if($key!="id" && $key!="type"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

							}

							$html.="</div>\n"; //end leftPad
						}

						if($spot['trace']){
							$html.="<div>Trace:</div>\n";
							$html.="<div class=\"leftPad\">\n";

							foreach($spot['trace'] as $key=>$value){
								if($key!="id" && $key!="label"){
									if($key!="trace_feature"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}
							}

							$html.="</div>\n"; //end leftPad
						}

						if($spot['samples']){
							$html.="<div>Samples:</div>\n";
							$html.="<div class=\"leftPad\">\n";
							foreach($spot['samples'] as $o){
								$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->label).": "."</div>\n";
								foreach($o as $key=>$value){
									if($key!="id" && $key!="label"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

							}

							$html.="</div>\n"; //end leftPad
						}

						if($spot['other_features']){
							$html.="<div>Other Features:</div>\n";
							$html.="<div class=\"leftPad\">\n";
							foreach($spot['other_features'] as $o){
								$html.="<div class=\"sectionTitle\">".$this->fixLabel($o->label).": "."</div>\n";
								foreach($o as $key=>$value){
									if($key!="id" && $key!="label"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

							}

							$html.="</div>\n"; //end leftPad
						}

						if($project->tags){
							foreach($project->tags as $tag){
								$found = "no";
								if($tag->spots){
									foreach($tag->spots as $spotid){
										if($spotid == $id){
											$found = "yes";
										}
									}
								}

								if($found == "yes"){
									if($tag->type=="geologic_unit"){

										$html.="<div>Rock Unit:</div>\n";
										$html.="<div class=\"leftPad\">\n";
										foreach($tag as $key=>$value){
											if($key != "date" && $key != "spots" && $key != "features" && $key != "id" ){
												$key = $this->fixLabel($key);
												if(is_string($value)){
													$value = $this->fixLabel($value);
												}
												$html.="<div>$key: $value</div>\n";
											}
										}

										$html.="</div>\n"; //end leftPad
									}
								}
							}
						}

						$hastags = "no";

						if($this->alltags){
							foreach($this->alltags as $tag){
								$found = "no";
								if($tag->spots){
									if($tag->type!="geologic_unit"){
										foreach($tag->spots as $spotid){
											if($spotid == $id){
												$hastags = "yes";
											}
										}
									}
								}

							}
						}

						if($hastags == "yes"){

							$html.="<div>Tags:</div>\n";
							$html.="<div class=\"leftPad\">\n";

							if($this->alltags){
								foreach($this->alltags as $tag){
									$found = "no";
									if($tag->spots){
										if($tag->type!="geologic_unit"){
											foreach($tag->spots as $spotid){
												if($spotid == $id){
													$found = "yes";
												}
											}
										}
									}

									if($found == "yes"){

										$html.="<div class=\"sectionTitle\">".$tag->name.": "."</div>\n";
										foreach($tag as $key=>$value){

											if($key != "date" && $key != "spots" && $key != "features" && $key != "id" && $key != "name" ){
												$key = $this->fixLabel($key);
												if(is_string($value)){
													$value = $this->fixLabel($value);
												}
												$html.="<div>$key: $value</div>\n";
											}

										}

									}
								}
							}

							$html.="</div>\n"; //end leftPad

						}

						if($spot['images']){
							$html.="<div>Images:</div>\n";
							$html.="<div class=\"leftPad\">\n";
							foreach($spot['images'] as $o){
								if($o['title']){
									$thistitle = $this->fixLabel($o['title']);
								}else{
									$thistitle = $o['id'];
								}
								$html.="<div class=\"sectionTitle\">".$thistitle.": "."</div>\n";
								foreach($o as $key=>$value){
									if($key!="id" && $key!="self" && $key!="annotated" && $key!="title"){
										$key = $this->fixLabel($key);
										if(is_string($value)){
											$value = $this->fixLabel($value);
										}
										$html.="<div>$key: $value</div>\n";
									}
								}

								$imageid = $o['id'];

								$filename = $this->strabo->getImageFilename($imageid);

								if($filename){
									$gdimage = $this->gdThumb($filename);
									if($gdimage){
										//write image to folder here (imagecreatetruecolor)
										imagejpeg($gdimage, "ogrtemp/$randnum/data/files/$imageid.jpg");

										$html.="<div><a href=\"https://www.strabospot.org/geimage/$imageid\"><img src=\"files/$imageid.jpg\"></a></div>\n";
									}
								}

							}

							$html.="</div>\n"; //end leftPad
						}

						if($mygeotype=="Point"){
							//build custom icon here if needed
							$customstyle=$this->buildCustomPoint($spot,$randnum);
							if($customstyle!=""){
								$pointstyle=$customstyle;
							}else{
								$pointstyle="";
							}
						}else{
							$pointstyle="";
						}

						$html.="]]>\n</description>\n<styleUrl>#".$thisstyle."</styleUrl>".$pointstyle."\n$kmlgeo\n</Placemark>\n\n";

					}

				}// end foreach spot

					$stylestring = '				<html><head>
										<style type="text/css">
											html{
												margin:0px;
												padding:0px;
											}
											body{
												margin:2px;
												font-family: \'Open sans\', sans-serif;
												font-size: 13px;
												color: #666666;
												background-color: #ffffff;
												width:700px;
											}
											.spotTitle{
												font-weight:bold;
												font-size:15px;
											}
											.leftPad{
												padding-left:20px;
											}
											.sectionTitle{
												text-decoration:underline;
											}
										</style>
										</head>
										<body>$[description]</body>
									</html>';

									$html='<?xml version="1.0" encoding="UTF-8"?>
									<kml xmlns="http://www.opengis.net/kml/2.2" xmlns:gx="http://www.google.com/kml/ext/2.2" xmlns:kml="http://www.opengis.net/kml/2.2" xmlns:atom="http://www.w3.org/2005/Atom">
									<Document>
										<name>StraboSpot Data</name>

										<StyleMap id="m_strabo_point">
											<Pair>
												<key>normal</key>
												<styleUrl>#s_strabo_point</styleUrl>
											</Pair>
											<Pair>
												<key>highlight</key>
												<styleUrl>#s_strabo_point_hl</styleUrl>
											</Pair>
										</StyleMap>

										<Style id="s_strabo_point">
											<IconStyle>
												<scale>1.7</scale>
												<Icon>
													<href>files/dot.png</href>
												</Icon>
												<hotSpot x="50" y="50" xunits="pixels" yunits="pixels"/>
												<heading>360</heading>
											</IconStyle>
											<LineStyle>
												<color>ff000000</color>
												<width>1.5</width>
											</LineStyle>
											<PolyStyle>
												<color>4bDC7878</color>
											</PolyStyle>
										</Style>

										<Style id="s_strabo_point_hl">
											<IconStyle>
												<scale>1.9</scale>
												<Icon>
													<href>files/dot.png</href>
												</Icon>
												<hotSpot x="50" y="50" xunits="pixels" yunits="pixels"/>
												<heading>360</heading>
											</IconStyle>
											<LineStyle>
												<color>ff000000</color>
												<width>1.5</width>
											</LineStyle>
											<PolyStyle>
												<color>64DC7878</color>
											</PolyStyle>
											<BalloonStyle>
												<text><![CDATA[
												'.$stylestring.'
												]]></text>
											</BalloonStyle>
										</Style>

										<StyleMap id="m_strabo_line">
											<Pair>
												<key>normal</key>
												<styleUrl>#s_strabo_line</styleUrl>
											</Pair>
											<Pair>
												<key>highlight</key>
												<styleUrl>#s_strabo_line_hl</styleUrl>
											</Pair>
										</StyleMap>

										<Style id="s_strabo_line">
											<LineStyle>
												<color>ff000000</color>
												<width>2</width>
											</LineStyle>
										</Style>

										<Style id="s_strabo_line_hl">
											<LineStyle>
												<color>ff000000</color>
												<width>4</width>
											</LineStyle>
											<BalloonStyle>
												<text><![CDATA[
												'.$stylestring.'
												]]></text>
											</BalloonStyle>
										</Style>

										<StyleMap id="m_strabo_polygon">
											<Pair>
												<key>normal</key>
												<styleUrl>#s_strabo_polygon</styleUrl>
											</Pair>
											<Pair>
												<key>highlight</key>
												<styleUrl>#s_strabo_polygon_hl</styleUrl>
											</Pair>
										</StyleMap>

										<Style id="s_strabo_polygon">
											<LineStyle>
												<color>ff000000</color>
												<width>1.5</width>
											</LineStyle>
											<PolyStyle>
												<color>4bDC7878</color>
											</PolyStyle>
										</Style>

										<Style id="s_strabo_polygon_hl">
											<LineStyle>
												<color>ff000000</color>
												<width>1.5</width>
											</LineStyle>
											<PolyStyle>
												<color>641478FF</color>
											</PolyStyle>
											<BalloonStyle>
												<text><![CDATA[
												'.$stylestring.'
												]]></text>
											</BalloonStyle>
										</Style>

										<Folder>
											<name>Strabo Data</name>
											<open>1</open>
											<Style>
												<ListStyle>
													<listItemType>check</listItemType>
													<ItemIcon>
														<state>open</state>
														<href>files/mysavedplaces_open.png</href>
													</ItemIcon>
													<ItemIcon>
														<state>closed</state>
														<href>files/mysavedplaces_closed.png</href>
													</ItemIcon>
													<bgColor>14F0C814</bgColor>
													<maxSnippetLines>0</maxSnippetLines>
												</ListStyle>
											</Style>
									'.$html;

				$html.='		<ScreenOverlay>
								  <Style>
									<ListStyle>
									  <ItemIcon>
										<href>
										  <a href="http://www.earthchemportal.org">EarthChem</a>
										</href>
									  </ItemIcon>
									  <listItemType>checkHideChildren</listItemType>
									</ListStyle>
								  </Style>
								  <name>
									<a href="httpS://www.strabospot.org">StraboSpot</a>
								  </name>
								  <Icon>
									<href>files/overlay.jpg</href>
								  </Icon>
								  <overlayXY x="0.01" y="0.035" xunits="fraction" yunits="fraction" />
								  <screenXY x="0.01" y="0.035" xunits="fraction" yunits="fraction" />
								  <size x="300" y="55" xunits="pixels" yunits="pixels" />
								</ScreenOverlay>

							</Folder>
						</Document>
						</kml>';

				file_put_contents("ogrtemp/$randnum/data/doc.kml", $html);

				$filedate = date("m_d_Y");

				exec("cd ogrtemp/$randnum/data; zip -r strabo_$filedate.kmz doc.kml files 2>&1",$results);

					//zip -r foo.zip doc.kml files

				//force download of file
				header("Content-Type: application/kmz");
				header("Content-Disposition: attachment; filename=strabo_$filedate.kmz");
				header("Content-Length: " . filesize("ogrtemp/$randnum/data/strabo_$filedate.kmz"));

				readfile("ogrtemp/$randnum/data/strabo_$filedate.kmz");

				//remove temp directory
				if($randnum!=""){
				}

			}else{

				echo "No spots found for this search.";

			}

		} //end if dsids

	}

	public function getTagColor($id, $tags){
		if($id == "" || $tags == ""){
			return "";
		}

		$outcolor = "";

		foreach($tags as $tag){
			if(in_array($id, $tag->spots)){
				$outcolor = $tag->color;
			}
		}

		return $outcolor;
	}

	public function jsonOut(){

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->newSearchGetTagsFromDatasetIds($dsids);
			$json = $this->strabo->newSearchGetDatasetSpotsSearch(null, $this->get);

			$spots = $json['features'];

			if(count($spots)>0){

				header('Content-disposition: attachment; filename=StraboOutput.json');
				header('Content-type: application/json');
				echo json_encode($json, JSON_PRETTY_PRINT);

			}else{

				echo "No spots found for this search.";

			}

		} //end if dsids

	}

}
?>