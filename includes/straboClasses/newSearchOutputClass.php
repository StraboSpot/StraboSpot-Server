<?php

/*
******************************************************************
Strabo Output Class
Author: Jason Ash (jasonash@ku.edu)
Description: This class handles output options for the Strabo
				System.
******************************************************************
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
		
			$prefix = "foo";
		
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

			$prefix = "foo";
		
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

	public function gatherOtherFeatures($otherfeatures){

		//echo "here are the otherfeatures: "; $this->dumpVar($otherfeatures);
		
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

	public function fixSpot($spot){

		$id = $spot['properties']['id'];
	
		unset($spot['original_geometry']);
	
		if($spot['properties']['orientation_data']){
			$orientations = $this->gatherOrientations($spot['properties']['orientation_data']);
			foreach($orientations as $key=>$value){
				$spot['properties'][$key]=$value;
			}
		}

		//dumpVar($orientations);
	
		if($spot['properties']['_3d_structures']){
			$structures = $this->gatherStructures($spot['properties']['_3d_structures']);
			foreach($structures as $key=>$value){
				$spot['properties'][$key]=$value;
			}
		}

		//dumpVar($structures);

		if($spot['properties']['samples']){
			$samples = $this->gatherSamples($spot['properties']['samples']);
			foreach($samples as $key=>$value){
				$spot['properties'][$key]=$value;
			}
		}

		//dumpVar($samples);

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
				//$this->dumpVar($image);
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
			
			//$this->dumpVar($linejson);exit();
			
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
	
	
			//exit();
	
			/*
			echo "polygonjson:";
			$this->dumpVar($polygonjson);
			echo "pointjson:";
			$this->dumpVar($pointjson);
			echo "linejson:";
			$this->dumpVar($linejson);
			exit();
			*/
	
			if($polygonjson!="" || $pointjson!="" || $linejson!=""){
	
				//get new randnum for ogrtemp
				$randnum=$this->strabo->db->get_var("select nextval('file_seq')");
		
				//make directory in ogrtemp to hold data
				mkdir("ogrtemp/$randnum");
				mkdir("ogrtemp/$randnum/data");
		
				//file_put_contents("ogrtemp/$randnum/foo.json", 'testing here');
		
				//if polygonjson != "" write json file and create shapefile
				if($polygonjson!=""){
					file_put_contents("ogrtemp/$randnum/polygon.json", $polygonjson);
					exec("ogr2ogr -nlt POLYGON -f \"ESRI Shapefile\" -skipfailures ogrtemp/$randnum/data/polygons.shp ogrtemp/$randnum/polygon.json 2>&1",$results);
					//unlink("ogrtemp/$randnum/polygon.json");
				}

				//if linejson != "" write json file and create shapefile
				if($linejson!=""){
					file_put_contents("ogrtemp/$randnum/line.json", $linejson);
					//exec("ogr2ogr -nlt LINESTRING -skipfailures ogrtemp/$randnum/data/lines.shp ogrtemp/$randnum/line.json OGRGeoJSON 2>&1",$results);
					exec("ogr2ogr -nlt LINESTRING -f \"ESRI Shapefile\" -skipfailures ogrtemp/$randnum/data/lines.shp ogrtemp/$randnum/line.json 2>&1",$results);
					unlink("ogrtemp/$randnum/line.json");
				}

				//if pointjson != "" write json file and create shapefile
				if($pointjson!=""){
					file_put_contents("ogrtemp/$randnum/point.json", $pointjson);
					//exec("ogr2ogr -nlt POINT -skipfailures ogrtemp/$randnum/data/points.shp ogrtemp/$randnum/point.json OGRGeoJSON 2>&1",$results);
					exec("ogr2ogr -nlt POINT -f \"ESRI Shapefile\" -skipfailures ogrtemp/$randnum/data/points.shp ogrtemp/$randnum/point.json 2>&1",$results);
					unlink("ogrtemp/$randnum/point.json");
				}

				//create zip file
				exec("zip -j ogrtemp/$randnum/strabo$randnum.zip ogrtemp/$randnum/data/* 2>&1",$results);
				
				//$this->dumpVar($results);exit();

				//force download of file
				header("Content-Type: application/zip");
				header("Content-Disposition: attachment; filename=search_download.zip");
				header("Content-Length: " . filesize("ogrtemp/$randnum/strabo$randnum.zip"));

				readfile("ogrtemp/$randnum/strabo$randnum.zip");
		
				//remove temp directory
				if($randnum!=""){
					//exec("rm -rf ogrtemp/$randnum",$results);
				}

			}else{
				echo "No data found for this dataset.";
			}
	
		}
	
	}
	





























































	public function devshapefileOut(){

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->getTagsFromDatasetIds($dsids);

			$polygonjson = $this->strabo->getDatasetSpotsSearch('polygon',$this->get);
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
	
			$pointjson = $this->strabo->getDatasetSpotsSearch('point',$this->get);
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
	
			$linejson = $this->strabo->getDatasetSpotsSearch('line',$this->get);
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
	
	
			//exit();
	
			
			echo "polygonjson:";
			$this->dumpVar($polygonjson);
			echo "pointjson:";
			$this->dumpVar($pointjson);
			echo "linejson:";$this->dumpVar($linejson);
			exit();
			
	
			if($polygonjson!="" || $pointjson!="" || $linejson!=""){
	
				//get new randnum for ogrtemp
				$randnum=$this->strabo->db->get_var("select nextval('file_seq')");
		
				//make directory in ogrtemp to hold data
				mkdir("ogrtemp/$randnum");
				mkdir("ogrtemp/$randnum/data");
		
				//file_put_contents("ogrtemp/$randnum/foo.json", 'testing here');
		
				//if polygonjson != "" write json file and create shapefile
				if($polygonjson!=""){
					file_put_contents("ogrtemp/$randnum/polygon.json", $polygonjson);
					exec("ogr2ogr -nlt POLYGON -f \"ESRI Shapefile\" -skipfailures ogrtemp/$randnum/data/polygons.shp ogrtemp/$randnum/polygon.json 2>&1",$results);
					//unlink("ogrtemp/$randnum/polygon.json");
				}

				//if linejson != "" write json file and create shapefile
				if($linejson!=""){
					file_put_contents("ogrtemp/$randnum/line.json", $linejson);
					//exec("ogr2ogr -nlt LINESTRING -skipfailures ogrtemp/$randnum/data/lines.shp ogrtemp/$randnum/line.json OGRGeoJSON 2>&1",$results);
					exec("ogr2ogr -nlt LINESTRING -f \"ESRI Shapefile\" -skipfailures ogrtemp/$randnum/data/lines.shp ogrtemp/$randnum/line.json 2>&1",$results);
					unlink("ogrtemp/$randnum/line.json");
				}

				//if pointjson != "" write json file and create shapefile
				if($pointjson!=""){
					file_put_contents("ogrtemp/$randnum/point.json", $pointjson);
					//exec("ogr2ogr -nlt POINT -skipfailures ogrtemp/$randnum/data/points.shp ogrtemp/$randnum/point.json OGRGeoJSON 2>&1",$results);
					exec("ogr2ogr -nlt POINT -f \"ESRI Shapefile\" -skipfailures ogrtemp/$randnum/data/points.shp ogrtemp/$randnum/point.json 2>&1",$results);
					unlink("ogrtemp/$randnum/point.json");
				}

				//create zip file
				exec("zip -j ogrtemp/$randnum/strabo$randnum.zip ogrtemp/$randnum/data/* 2>&1",$results);
				
				//$this->dumpVar($results);exit();

				//force download of file
				header("Content-Type: application/zip");
				header("Content-Disposition: attachment; filename=search_download.zip");
				header("Content-Length: " . filesize("ogrtemp/$randnum/strabo$randnum.zip"));

				readfile("ogrtemp/$randnum/strabo$randnum.zip");
		
				//remove temp directory
				if($randnum!=""){
					//exec("rm -rf ogrtemp/$randnum",$results);
				}

			}else{
				echo "No data found for this dataset.";
			}
	
		}
	
	}
	























































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
			
			/*
			echo "filedate: $filedate<br>";
			echo "kmljson:";
			$this->dumpVar($kmljson);
			exit();
			*/
	
			if($kmljson!=""){
	
				//get new randnum for ogrtemp
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
				//header("Content-Length: " . filesize("ogrtemp/$randnum/strabo$randnum.kml"));

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
			
			//$this->dumpVar($this->alltags);exit();
			
			//need to find a new way to get data based on search query passed in
			//$data = $this->strabo->getDatasetSpotsSearch(null,$this->get);
			$data = $this->strabo->newSearchGetDatasetSpotsSearch(null, $this->get);
			
			
			
			//$this->dumpVar($data);exit();

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


			/*
			first, roll over each feature looking for planar orientation data
			store in named array $columns['orientation']['planar'], colnum start with colnum 8
			*/

			
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



			/*

			foreach($data['features'] as $feature){

				$orientationdatas = $feature['properties']['orientation_data'];
				if($orientationdatas){
					foreach($orientationdatas as $orientationdata){

						if($orientationdata->type=="tabular_orientation"){
							$tabular_orientation_count++;
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
							}
						}

					}
				}
	
				$x++;
	
			}

			foreach($data['features'] as $feature){

				$orientationdatas = $feature['properties']['orientation_data'];
				if($orientationdatas){
					foreach($orientationdatas as $orientationdata){

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
							}
						}

					}
				}
	
				$x++;
	
			}

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
		
						$associatedorientations = $orientationdata->associated_orientation;
						if($associatedorientations){
							foreach($associatedorientations as $associatedorientation){
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
							}
						}

					}
				}
	
				$x++;
	
			}

			*/



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

			//all data should be in $columns now
			//$this->dumpVar($columns);//exit();

			/** PHPExcel */
			include 'PHPExcel.php';

			/** PHPExcel_Writer_Excel2007 */
			include 'PHPExcel/Writer/Excel2007.php';

			// Create new PHPExcel object
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
				//$sheet->write(3,$colnum,$this->fix_column_name($key),$formatwhiteblue);
	
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
				
	
				/*
				if($geometry!=$original_geometry){
					$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,5),$original_geometry);
				}
				*/

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
				
				
				/*
				We also need to keep track of counts here so we can repeat the row if necessary to include all data
					linear_orientation_count
					planar_orientation_count
					tabular_orientation_count
					other_features_count
					samples_count
					traces_count
					_3d_structure_count
				
					also, let's build a new array arrayMultipleData to store all of the data
				*/
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
				
				
				/*
				echo "linear_orientation_count: $linear_orientation_count<br>";
				echo "planar_orientation_count: $planar_orientation_count<br>";
				echo "tabular_orientation_count: $tabular_orientation_count<br>";
				echo "other_features_count: $other_features_count<br>";
				echo "samples_count: $samples_count<br>";
				echo "traces_count: $traces_count<br>";
				echo "maxcount: $maxcount<br>";
				$this->dumpVar($arrayMultipleData);
				exit();
				*/

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
										//echo "$otype $orkey goes in $orval<br>";
										$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,$orval),$arrayMultipleData[$otype][$spotrepeat][$orkey]);
									}
								}
							}else{
								foreach($value as $newkey=>$column){
									//echo "$key $newkey goes in $column<br>";
									$objPHPExcel->getActiveSheet()->SetCellValue($this->rowcol($rownum,$column),$arrayMultipleData[$key][$spotrepeat][$newkey]);
								}
							}
						}
					}
					
					$rownum++;
					
					//exit();
				
				}



//exit();






























































				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				

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

			//$this->dumpVar($spots);exit();


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
		
						//$this->dumpVar($or);
						
						if($or['orientation_type']=="planar_orientation" || $or['type']=="planar_orientation"){
						
							//echo "found inside planar<br>";
			
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
	

		//include_once("straboModelClass/straboModelClass.php");
		//$sm = new straboModelClass();

		if($this->get['dsids']!=""){







			//new
			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->newSearchGetTagsFromDatasetIds($dsids);
			$json = $this->strabo->newSearchGetDatasetSpotsSearch(null,$this->get);



			//$this->dumpVar($json);exit();






			//$dsids=$this->get['dsids'];
			//$this->alltags = $this->strabo->getTagsFromDatasetIds($dsids);
			//$json = $this->strabo->getDatasetSpotsSearch(null,$this->get);
			
			$dsname = $this->strabo->getDatasetName($dsids);
			
			$dailynotes = $this->strabo->getDailyNotesFromDatasetID($dsids);

			$spots = $json['features'];
			
			//$this->dumpVar($spots);exit();
			
			//unix time 1567364734  10 digits
			//id 15609769344266     14 digits
			
			//get day for each spot
			
			//create date array to loop over

			
			//$this->dumpVar($spotdates);


			//$datestring = date("l, F j, Y", $ut);
			
			//$this->dumpVar($spots);exit();


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
					

				
					//echo $spot['properties']['id']." $datestring<br>";
			
				}
				
				//$this->dumpVar($spotdates);exit();
	
				require('includes/PDF_LabBook.php');

				$pdf = new PDF_MemImage('P','mm','Letter');
				$pdf->AddPage();

				if($dsname != ""){
					$pdf->datasetTitle($dsname);
				}

				//$this->dumpVar($spotdates);exit();
				
				foreach($spotdates as $spotdate){
				
					$showdate = $spotdate->showformat;
					$pdf->dayTitle($showdate);
					
					//look for daily notes (format in data is YYYY-MM-DD)
					$lookdate = $spotdate->lookformat;
					
					//$this->dumpVar($dailynotes);exit();
					
					if($dailynotes!=""){
						foreach($dailynotes as $dn){
							$dndate = substr($dn->date,0,10);
							
							//echo $dndate;exit();
							
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
			
							//$strabo->dumpVar($spot);
							$modified = (string) $spot['id'];
							$modified = substr($modified,0,10);
							$modified = date("F j, Y",$modified);
							$pdf->valueRow("Created",$modified,15);

							$modified = (string) $spot['modified_timestamp'];
							$modified = substr($modified,0,10);
							$modified = date("F j, Y",$modified);
							$pdf->valueRow("Last Modified",$modified,15);

							//$this->dumpVar($rawspot['geometry']->type);exit();
							
							if($rawspot['geometry']->type=="Point"){
								$pdf->valueRow("Longitude",$rawspot['geometry']->coordinates[0],15);
								$pdf->valueRow("Latitude",$rawspot['geometry']->coordinates[1],15);
							}else{
								//$pdf->valueRow("foo","bar",15);
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
											//$pdf->Ln(5);
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
									

									
									//$this->dumpVar($o);

									$pdf->Ln(1);

									//echo "id: ".$o['id']."<br>";
									
									$filename = $this->strabo->getImageFilename($o['id']);
									
									//echo "filename: $filename";exit();
									//15246709942290
									
									//$pdf->valueRow("Found Filename",$filename,15);
									//$pdf->valueRow("File id",$o['id'],15);
									if($filename){
										$gdimage = $this->gdThumb($filename);
										if($gdimage){
											$pdf->GDImage($gdimage, 20, null, 60);
											//$pdf->cell(0,2,'','',1,L);
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
							//echo "date doesn't match<br>";
						}
			
					}//end foreach spots

				}// end foreach spotdates


				$filedate = date("m_d_Y");
				$pdfname="StraboSpot_Field_Book_$filedate.pdf";
				$pdf->Output($pdfname,"D");
				//$pdf->Output();
		
			}else{
	
				echo "No spots found for this search.";

	
			}

		} //end if dsids

	}














	public function newfieldbookOut(){

		//include_once("straboModelClass/straboModelClass.php");
		//$sm = new straboModelClass();

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->getTagsFromDatasetIds($dsids);

			$json = $this->strabo->getDatasetSpotsSearch(null,$this->get);
			
			$dsname = $this->strabo->getDatasetName($dsids);
			
			$dailynotes = $this->strabo->getDailyNotesFromDatasetID($dsids);

			$spots = $json['features'];
			
			//$this->dumpVar($spots);exit();
			
			//unix time 1567364734  10 digits
			//id 15609769344266     14 digits
			
			//get day for each spot
			
			//create date array to loop over

			
			//$this->dumpVar($spotdates);


			//$datestring = date("l, F j, Y", $ut);
			
			//$this->dumpVar($spots);exit();


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
					

				
					//echo $spot['properties']['id']." $datestring<br>";
			
				}
				
				//$this->dumpVar($spotdates);exit();
	
				require('includes/PDF_LabBook.php');

				$pdf = new PDF_MemImage('P','mm','Letter');
				$pdf->AddPage();

				if($dsname != ""){
					$pdf->datasetTitle($dsname);
				}

				//$this->dumpVar($spotdates);exit();
				
				foreach($spotdates as $spotdate){
				
					$showdate = $spotdate->showformat;
					$pdf->dayTitle($showdate);
					
					//look for daily notes (format in data is YYYY-MM-DD)
					$lookdate = $spotdate->lookformat;
					
					//$this->dumpVar($dailynotes);exit();
					
					if($dailynotes!=""){
						foreach($dailynotes as $dn){
							$dndate = substr($dn->date,0,10);
							
							//echo $dndate;exit();
							
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
			
							//$strabo->dumpVar($spot);
							$modified = (string) $spot['id'];
							$modified = substr($modified,0,10);
							$modified = date("F j, Y",$modified);
							$pdf->valueRow("Created",$modified,15);

							$modified = (string) $spot['modified_timestamp'];
							$modified = substr($modified,0,10);
							$modified = date("F j, Y",$modified);
							$pdf->valueRow("Last Modified",$modified,15);

							//$this->dumpVar($rawspot['geometry']->type);exit();
							
							if($rawspot['geometry']->type=="Point"){
								$pdf->valueRow("Longitude",$rawspot['geometry']->coordinates[0],15);
								$pdf->valueRow("Latitude",$rawspot['geometry']->coordinates[1],15);
							}else{
								//$pdf->valueRow("foo","bar",15);
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
											//$pdf->Ln(5);
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
									

									
									//$this->dumpVar($o);

									$pdf->Ln(1);

									$filename = $this->strabo->getImageFilename($o['id']);
									//$pdf->valueRow("Found Filename",$filename,15);
									if($filename){
										$gdimage = $this->gdThumb($filename);
										if($gdimage){
											$pdf->GDImage($gdimage, 20, null, 60);
											//$pdf->cell(0,2,'','',1,L);
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
								//$this->dumpVar($groupedrows); exit();
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
							//echo "date doesn't match<br>";
						}
			
					}//end foreach spots

				}// end foreach spotdates


				$filedate = date("m_d_Y");
				$pdfname="StraboSpot_Field_Book_$filedate.pdf";
				//$pdf->Output($pdfname,"D");
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

		//include_once("straboModelClass/straboModelClass.php");
		//$sm = new straboModelClass();

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->getTagsFromDatasetIds($dsids);

			$json = $this->strabo->getDatasetSpotsSearch(null,$this->get);

			$spots = $json['features'];

			if(count($spots)>0){
	
				require('includes/PDF_LabBook.php');

				$pdf = new PDF_MemImage('P','mm','Letter');
				$pdf->AddPage();

				//$pdf->datasetTitle($datasetname);

				foreach($spots as $spot){
		
					$spot = $spot['properties'];
			
					$id = $spot['id'];
			
					$spotname = $spot['name'];
					if($spot['geometrytype']){
						$spotname .= " (".$spot['geometrytype'].")";
					}
			
					$pdf->spotTitle($spotname);
			
					//$strabo->dumpVar($spot);
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
									//$pdf->Ln(5);
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
							//$pdf->valueRow("Found Filename",$filename,15);
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

		//include_once("straboModelClass/straboModelClass.php");
		//$sm = new straboModelClass();

		if($this->get['dsids']!=""){

			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->getTagsFromDatasetIds($dsids);

			$json = $this->strabo->getDatasetSpotsSearch(null,$this->get);

			$spots = $json['features'];

			if(count($spots)>0){
	
				require('includes/PDF_LabBook.php');

				$pdf = new PDF_MemImage('P','mm','Letter');
				$pdf->AddPage();

				//$pdf->datasetTitle($datasetname);

				foreach($spots as $spot){
		
					$spot = $spot['properties'];
			
					$id = $spot['id'];
			
					$spotname = $spot['name'];
					if($spot['geometrytype']){
						$spotname .= " (".$spot['geometrytype'].")";
					}
			
					$pdf->spotTitle($spotname);
			
					//$strabo->dumpVar($spot);
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
									//$pdf->Ln(5);
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
							//$pdf->valueRow("Found Filename",$filename,15);
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






































function buildKMLIcon($id,$randnum,$strike,$showval,$trend){ //new card icon
	
	
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


	//header('Content-Type: image/png');
	//imagepng($arrow);
	//imagepng($finalimage);
	
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

		//header('Content-Type: image/png');
		//imagepng($arrow);
		//imagepng($backbox);
		imagepng($backbox, "ogrtemp/$randnum/data/files/$id.png");

	}

	public function buildCustomPoint($spot,$randnum){

/*
strike
dip
trend
plunge
*/

		//$pointstyle="<Style><IconStyle><Icon><href>files/dot.png</href></Icon></IconStyle></Style>";
		//$this->dumpVar($spot);
		
		
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
		
		/*
		echo "id: $id<br>";
		echo "randnum: $randnum<br>";
		echo "strike: $strike<br>";
		echo "dip: $dip<br>";
		echo "trend: $trend<br>";
		echo "plunge: $plunge<br>";
		echo "<br>";
		*/
		
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



		//include_once("straboModelClass/straboModelClass.php");
		//$sm = new straboModelClass();

		if($this->get['dsids']!=""){





			$dsids=$this->get['dsids'];
			$this->alltags = $this->strabo->newSearchGetTagsFromDatasetIds($dsids);
			$json = $this->strabo->newSearchGetDatasetSpotsSearch(null, $this->get);



			






			$spots = $json['features'];

			//$this->dumpVar($spots);exit();
			
			if(count($spots)>0){

				//get new randnum for ogrtemp
				$randnum=$this->strabo->db->get_var("select nextval('file_seq')");
		
				//make directory in ogrtemp to hold data
				mkdir("ogrtemp/$randnum");
				mkdir("ogrtemp/$randnum/data");
				mkdir("ogrtemp/$randnum/data/files");

				copy("assets/files/kmlfiles/bubblehead.jpg","ogrtemp/$randnum/data/files/bubblehead.jpg");
				
				//copy("files/kmlfiles/dot.png","ogrtemp/$randnum/data/files/dot.png");
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

				foreach($spots as $spot){

					//use geoPHP to get WKT
					$mygeojson=$spot['geometry'];
					
					
					$mygeotype = $mygeojson['type'];
					
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
					
					//echo "mygeotype: $mygeotype";exit();
					
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
							//echo "$kmlgeo\n\n";
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
			
						//$strabo->dumpVar($spot);
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
							//$pointstyle="<Style><IconStyle><Icon><href>files/dot.png</href></Icon></IconStyle></Style>";
						}else{
							$pointstyle="";
						}
						
						$html.="]]>\n</description>\n<styleUrl>#".$thisstyle."</styleUrl>".$pointstyle."\n$kmlgeo\n</Placemark>\n\n";

					}//end if geotype != ""
			
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
				
				
				//exit();
				//force download of file
				header("Content-Type: application/kmz");
				header("Content-Disposition: attachment; filename=strabo_$filedate.kmz");
				header("Content-Length: " . filesize("ogrtemp/$randnum/data/strabo_$filedate.kmz"));

				readfile("ogrtemp/$randnum/data/strabo_$filedate.kmz");

				//remove temp directory
				if($randnum!=""){
					//exec("rm -rf ogrtemp/$randnum",$results);
				}
		
			}else{
	
				echo "No spots found for this search.";

	
			}

		} //end if dsids

	}



}
?>