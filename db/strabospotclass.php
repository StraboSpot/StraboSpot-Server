<?php

/*
******************************************************************
StraboSpot Class
Author: Jason Ash (jasonash@ku.edu)
Description: This class performs JSON transforms from/to the Neo4j
				database
******************************************************************
*/

class StraboSpot
{

 	public function StraboSpot($neodb,$userpkey,$db){
 		$this->neodb=$neodb;
 		$this->userpkey=$userpkey;
 		$this->db=$db;
 	}
 
 
 
 	public function setuserpkey($userpkey){
 		$this->userpkey=$userpkey;
 	}



 	public function setneohandler($neodb){
 		$this->neodb=$neodb;
 	}



 	public function setdbhandler($db){
 		$this->db=$db;
 	}

 	public function setuuid($uuid){
 		$this->uuid=$uuid;
 	}

	public function logToFile($string,$label){
		if(is_writable("log.txt")){
			if($label==""){$label="LogToFile";}
			file_put_contents ("log.txt", "\n\n$label $string \n", FILE_APPEND);
			//file_put_contents ("/var/www/db/log.txt", $string, FILE_APPEND);
			//file_put_contents ("/var/www/db/log.txt", "\n\n************************************************************************************************************************\n\n", FILE_APPEND);
		}
	}

	public function dumpVar($var){
		echo "<pre>";
		print_r($var);
		echo "<pre>";
	}

	function escape($json){ //escapes json to javascript notation for neo4j parameters
		if($this->isJson($json)){

			$delim="";
			$returnstring="";
			$json=json_decode($json,true);
			//$this->dumpVar($json);exit();
			foreach($json as $key=>$value){
				if(is_string($value)){
					$value=addslashes($value);
				}
				
				if(is_bool($value)){
					$returnstring = $returnstring.$delim.$key.":".$value;
				}elseif(is_string($value)){
					$returnstring = $returnstring.$delim.$key.":"."\"".$value."\"";
				}else{
					$returnstring = $returnstring.$delim.$key.":".$value;
				}
	
				$delim=",";
	
		}

			return "{".$returnstring."}";
		
		}else{
		
			return $json;
		
		}

	}

	function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	public function createId(){
	
		$time = time();
		$extra = rand(111,999);
		$id = (int) $time.$extra;
		return $id;
	}

	public function getSingleSpot($feature_id){

		$querystring="MATCH (s:Spot {id:$feature_id,userpkey:$this->userpkey}) optional match (s)-[b:HAS_IMAGE]-(i:Image) with s, collect(i) as i RETURN s,i;";

		$records = $this->neodb->get_results($querystring);

		$count=count($records);
		
		if($count > 0){
		
			$record = $records[0];
			$data = $this->singleSpotJSON($record);

		}else{
			//Error, sample not found
			$data = new StdClass();
			$data->Error = "Feature $feature_id not found.";
		}
	
		return $data;

	}



	public function findSpot($spot_id){
	
		$querystring = "MATCH (n:Spot) WHERE (n.id = $spot_id) and n.userpkey = $this->userpkey RETURN id(n);";

		$spotid = $this->neodb->get_var($querystring);

		if($spotid != ""){
			return $spotid;
		}else{
			return false;
		}
	
	}



	public function findImage($image_id){
	
		$querystring = "MATCH (n:Image) WHERE n.id = $image_id RETURN count(*);";
		
		$body = $this->neodb->query($querystring);
		
		$count = (int)$body->results[0]->data[0]->row[0];

		if($count > 0){
			return true;
		}else{
			return false;
		}
	
	}


	public function deleteSingleSpot($id){

		$this->neodb->query("match (sp:Spot {id:$id,userpkey:$this->userpkey})-[r:IS_RELATED_TO]-() delete r;");
		$this->neodb->query("match (sp:Spot {id:$id,userpkey:$this->userpkey})-[r:IS_TAGGED]->() delete r;");
		
		$this->neodb->query("match (sp:Spot {id:$id,userpkey:$this->userpkey})
							optional match (ds:Dataset)-[dsetr:HAS_SPOT]->(sp)
							optional match (spat)-[orrr:RTREE_REFERENCE]->(sp)
							optional match (sp)-[hir:HAS_IMAGE]->(img:Image)
							optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)
							optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)-[aor:HAS_ASSOCIATED_ORIENTATION]->(ao:Orientation)
							optional match (sp)-[rur:HAS_ROCK_UNIT]->(ru:RockUnit)
							optional match (sp)-[htr:HAS_TRACE]->(trace:Trace)
							optional match (sp)-[sr:HAS_SAMPLE]->(s:Sample)
							optional match (sp)-[ofr:HAS_OTHER_FEATURE]->(of:OtherFeature)
							optional match (sp)-[tdr:HAS_3D_STRUCTURE]->(td:_3DStructure)
							optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)
							optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)-[rr:HAS_RELATIONSHIP]->(r:Relationship)
							delete aor,ao,or,o,rur,ru,htr,trace,sr,s,ofr,of,tdr,td,rr,r,ir,i,hir,img,orrr,dsetr,sp");
	}



	public function getMySpots(){

		//get the features from neo4j
		$querystring = "MATCH (s:Spot) WHERE s.userpkey = $this->userpkey optional match (s)-[b:HAS_IMAGE]-(i:Image) with s, collect(i) as i RETURN s,i;";
		$json = $this->getFeatureCollection($querystring);
		return $json;

	}
	
	
	
	public function deleteMySpots(){
		
		//delete datasets
		$this->neodb->query("match (sp:Spot {userpkey:$this->userpkey})
							optional match (ds:Dataset)-[dsetr:HAS_SPOT]->(sp)
							optional match (spat)-[orrr:RTREE_REFERENCE]->(sp)
							optional match (sp)-[hir:HAS_IMAGE]->(img:Image)
							optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)
							optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)-[aor:HAS_ASSOCIATED_ORIENTATION]->(ao:Orientation)
							optional match (sp)-[rur:HAS_ROCK_UNIT]->(ru:RockUnit)
							optional match (sp)-[htr:HAS_TRACE]->(trace:Trace)
							optional match (sp)-[sr:HAS_SAMPLE]->(s:Sample)
							optional match (sp)-[ofr:HAS_OTHER_FEATURE]->(of:OtherFeature)
							optional match (sp)-[tdr:HAS_3D_STRUCTURE]->(td:_3DStructure)
							optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)
							optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)-[rr:HAS_RELATIONSHIP]->(r:Relationship)
							delete aor,ao,or,o,rur,ru,htr,trace,sr,s,ofr,of,tdr,td,rr,r,ir,i,hir,img,orrr,dsetr,sp");
	
	}
	
	

	public function singleSpotJSON($record){

		$s = $record->get("s");
		$featuredata=$s->values();

		$imagedata = $record->get("i");

		$data = new StdClass();
		$data->properties = new StdClass();
		
		if(count($imagedata) > 0){
			$imagecount=0;
			$imagestuff=array();
			foreach($imagedata as $thisimage){
				$imagestuff[$imagecount] = new StdClass();
				$id = $thisimage->values();
				foreach($id as $key=>$value){
					if($key!="userpkey" && $key!="origfilename" && $key!="filename" && $key!="imagesha1" ){
						$imagestuff[$imagecount]->$key=$value;
					}
				}
				if($id->annotated=="1"){
					$imagestuff[$imagecount]->annotated=true;
				}else{
					$imagestuff[$imagecount]->annotated=false;
				}
				$imagestuff[$imagecount]->self="https://strabospot.org/db/image/".$id["id"];
				$imagecount++;
			}
			
			$data->properties->images=$imagestuff;
		}

/*
				$imagestuff[$imagecount]->title=$id["title"];
				$imagestuff[$imagecount]->caption=$id["caption"];
				$imagestuff[$imagecount]->width=$id["width"];
				$imagestuff[$imagecount]->height=$id["height"];
				$imagestuff[$imagecount]->annotated=$id["annotated"];
				$imagestuff[$imagecount]->self="https://strabospot.org/db/image/".$id["id"];
*/

		$wkt=$featuredata["wkt"];

		$origwkt=$featuredata["origwkt"];
		$gtype=$featuredata["gtype"];
		$date=$featuredata["date"];

		$name=$featuredata["name"];

		$userpkey=$featuredata["userpkey"];
		$feature_id=$featuredata["id"];



		if($this->isarc){
			if($origwkt!=""){
				$mywkt = geoPHP::load($origwkt,"wkt");
				$newjson = $mywkt->out('json');
				$newjson = json_decode($newjson);
				$data->original_geometry=$newjson;
			}
			if($wkt!=""){
				$mywkt = geoPHP::load($wkt,"wkt");
				$newjson = $mywkt->out('json');
				$newjson = json_decode($newjson);
				$data->geometry=$newjson;
			}
		}else{
			if($wkt!=""){
				$mywkt = geoPHP::load($origwkt,"wkt");
				$newjson = $mywkt->out('json');
				$newjson = json_decode($newjson);
				$data->geometry=$newjson;
			}
		}
		
		foreach($featuredata as $key=>$value){
		
			if(	$key != "wkt" &&
				$key != "origwkt" &&
				$key != "gtype" &&
				$key != "bbox" &&
				$key != "userpkey"
				){

				$key=str_replace("json_","",$key);

				if(is_array(json_decode($value)) || is_object(json_decode($value))){
				
					$value=json_decode($value);
				
				}
				
				eval("\$data->properties->$key=\$value;");	
			
			}

		}

		$data->type="Feature";
		$data->properties->self="https://strabospot.org/db/feature/$feature_id";

		return $data;

	}


	public function singleSpotJSONFromFeatureData($featuredata,$imagedata){

		//print_r($imagedata);exit();
		
		if(count($imagedata) > 0){
			$imagecount=0;
			$imagestuff=array();
			foreach($imagedata as $id){
				foreach($id as $key=>$value){
					if($key!="userpkey" && $key!="origfilename" && $key!="filename" && $key!="imagesha1" ){
						$imagestuff[$imagecount][$key]=$value;
					}
				}
				if($id->annotated=="1"){
					$imagestuff[$imagecount]['annotated']=true;
				}else{
					$imagestuff[$imagecount]['annotated']=false;
				}
				$imagestuff[$imagecount]['self']="https://strabospot.org/db/image/".$id->id;
				$imagecount++;
			}
			$data['properties']['images']=$imagestuff;
		}

		

		if($this->isarc){
			if($featuredata->origwkt!=""){
				$mywkt = geoPHP::load($featuredata->origwkt,"wkt");
				$newjson = $mywkt->out('json');
				$newjson = json_decode($newjson);
				$data['original_geometry']=$newjson;
				$wkt=$featuredata->wkt;
			}
		}else{
			$wkt=$featuredata->origwkt;
		}

		$gtype=$featuredata->gtype;
		$date=$featuredata->date;

		$name=$featuredata->name;


		$userpkey=$featuredata->userpkey;
		$feature_id=$featuredata->id;

		if($wkt!=""){
			$mywkt = geoPHP::load($wkt,"wkt");
			$newjson = $mywkt->out('json');
			$newjson = json_decode($newjson);
			$data['geometry']=$newjson;
		}



		foreach($featuredata as $key=>$value){
		
			if(	$key != "wkt" &&
				$key != "origwkt" &&
				$key != "gtype" &&
				$key != "bbox" &&
				$key != "geometrytype" &&
				$key != "userpkey"
				){

				$key=str_replace("json_","",$key);

				if(is_array(json_decode($value)) || is_object(json_decode($value))){
				
					$value=json_decode($value);
				
				}
				
				eval("\$data['properties']['$key']=\$value;");	
			
			}

		}

		$data['type']="Feature";
		$data['properties']['self']="https://strabospot.org/db/feature/$feature_id";

		return $data;

	}


	public function insertSpot($injson,$thisid=null){

		$spotstarttime=microtime(true);

		$upload = json_decode($injson);

		$featuretype=$upload->type;
		
		$dbaction="new";

		$properties = $upload->properties;

		foreach($properties as $key=>$value){
			if($key=="id"){
				$thisid=(int)$value;
			}
		}

		if($thisid==""){
			foreach($properties as $key=>$value){
				if($key=="self"){
					$thisid = (int)end(explode("/",$value));
				}
			}
			
		}

		$this->logToFile($thisid,"SPOT");

		if(!is_int($thisid)){

			$dbaction = "interror";
		
		}elseif($thisid != ""){
		
			$spotid=$this->findSpot($thisid);
			if($spotid){

				$dbaction = "update";

			}
		
		}

		
		//************************************************
		//create Spot jason and others here


		$newspot = array();

		/*
		*********************************************************
		    This section is a bit confusing. Most Spots coming in
		    will be as a member of a feature collection via the 
		    datasetspots controller. When these spots come in, the
		    fixIncomingBasemaps function rolls through all spots 
		    and creates "wkt" and "origwkt" properties to turn pixel coordinates
		    into real-world coordinates by getting the closest parent
		    real-world coordinates. In this case, we don't want to use
		    the geometry element to create the wkt. If the wkt property
		    is not present, we should use the geometry element to create
		    a wkt property.
		***********************************************************
		*/
		
		$hasgeometry="no";
		if($properties->wkt!=""){
			$wkt=$properties->wkt;
			$newspot["geometrytype"]=$upload->geometry->type;
			$newspot["wkt"]=$wkt;
			$hasgeometry="yes";
		}else{
			//use geoPHP to get WKT
			$mygeojson=$upload->geometry;
			$mygeojson=trim(json_encode($mygeojson));
			try {
				$mywkt=geoPHP::load($mygeojson,"json");
				$wkt = $mywkt->out('wkt');
				$newspot["geometrytype"]=$upload->geometry->type;
				$newspot["wkt"]=$wkt;
				$hasgeometry="yes";
			} catch (Exception $e) {
				$hasgeometry="no";
			}

		}

		if($properties->origwkt!=""){
			$newspot["origwkt"]=$properties->origwkt;
		}else{
			$newspot["origwkt"]=$wkt;
		}

		$newspot['userpkey']=$this->userpkey;
		
		$newspot["id"]=$thisid;
		
		if($properties->name!=""){$newspot["name"]=$properties->name;}
		if($properties->date!=""){$newspot["date"]=$properties->date;}
		if($properties->notes!=""){$newspot["notes"]=$properties->notes;}
		if($properties->time!=""){$newspot["time"]=$properties->time;}
		if($properties->modified_timestamp!=""){$newspot["modified_timestamp"]=$properties->modified_timestamp;}
		if($properties->images_notes!=""){$newspot["images_notes"]=$properties->images_notes;}
		if($properties->spot_radius_units!=""){$newspot["spot_radius_units"]=$properties->spot_radius_units;}
		if($properties->spot_radius!=""){$newspot["spot_radius"]=$properties->spot_radius;}
		if($properties->image_basemap!=""){$newspot["image_basemap"]=$properties->image_basemap;}

		if($properties->orientation_data!=""){$newspot["json_orientation_data"]=json_encode($properties->orientation_data);}
		if($properties->rock_unit!=""){$newspot["json_rock_unit"]=json_encode($properties->rock_unit);}
		if($properties->samples!=""){$newspot["json_samples"]=json_encode($properties->samples);}
		if($properties->other_features!=""){$newspot["json_other_features"]=json_encode($properties->other_features);}
		if($properties->_3d_structures!=""){$newspot["json__3d_structures"]=json_encode($properties->_3d_structures);}
		if($properties->inferences!=""){$newspot["json_inferences"]=json_encode($properties->inferences);}
		if($properties->nest!=""){$newspot["json_nest"]=json_encode($properties->nest);}
		if($properties->trace!=""){$newspot["json_trace"]=json_encode($properties->trace);}

		if($properties->images!=""){$images=$properties->images;}
		
		foreach($properties as $key=>$value){
			if(!in_array($key, array("id","self","wkt","origwkt","name","date","notes","time","modified_timestamp","images_notes","spot_radius_units","spot_radius","image_basemap","orientation_data","rock_unit","samples","other_features","_3d_structures","inferences","nest","trace","images"))){
				if((is_array($value)||is_object($value))){$value=json_encode($value);}
				$newspot["$key"]=$value;
			}
		}
		

		if($dbaction=="new"){
		
			$this->logToFile("new","SPOT");

			//********************************************************************
			// OK, we have some JSON formed and ready for Neo4j.
			// Let's POST to the REST API to create a node
			//********************************************************************
			$injson=json_encode($newspot);
			$spotid = $this->neodb->createNode($injson,"Spot");
			
			//********************************************************************
			// now add new node to spatial layer
			//********************************************************************
			if($hasgeometry=="yes"){
				//JMA 07/02/2018 Do we need spatial? Remove this for now
				//$this->neodb->addNodeToSpatial($spotid);
			}

			//********************************************************************
			// Now, load the additional elements...
			//********************************************************************
			$this->loadAdditionalNodes($spotid,$properties);

			//********************************************************************
			// Now, load images...
			//********************************************************************
			$this->loadImages($spotid,$images);



			//********************************************************************
			// Finally, return original upload data + "self"
			//********************************************************************
	
			$upload->properties->self="https://strabospot.org/db/feature/$thisid";
		
			$upload->properties->id=$thisid;

			$data=$upload;

		}elseif($dbaction=="update"){
			
			
		
			//********************************************************************
			// existing feature was found, update here
			// get feature so we can gather up bbox_abc and gtype
			//********************************************************************
			$querystring="match (s:Spot) 
							WHERE s.id = $thisid and s.userpkey = $this->userpkey 
							optional match (s)-[r:HAS_IMAGE]->(i:Image)
							with s, collect(i) as i
							RETURN s,i;";

			$record=$this->neodb->getRecord($querystring);

			$s = $record->get("s");
			$s = $s->values();

			$bbox = json_encode($s["bbox"]);

			$dbmodified = (int)$s["modified_timestamp"];

			if($s["gtype"]!=""){
				$newspot["gtype"] = $s["gtype"];
			}
			
			$injson=json_encode($newspot,JSON_PRETTY_PRINT);

			if($featuretype==""){

				// bad body sent, error
				$data["Error"] = "Invalid body JSON sent.";

			}else{

				$inmodified = (int)$newspot["modified_timestamp"];

				//echo "dbmodified: $dbmodified inmodified: $inmodified";exit();
				
				if($dbmodified > 0 && $inmodified > 0 && $dbmodified > $inmodified){

					//********************************************************************
					//db side is newer, let's return spot here, using $record from above
					//********************************************************************
					$data = $this->singleSpotJson($record);

					$this->logToFile("just return","SPOT");

				}else{

					//********************************************************************
					//db side is not newer, let's update spot here
					//********************************************************************

					$this->logToFile("actually update","SPOT");

					$this->logToFile($injson,"INJSON");
					
					//first, delete existing relationships
					$this->neodb->query("match (sp:Spot {id:$thisid,userpkey:$this->userpkey})
										optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)
										optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)-[aor:HAS_ASSOCIATED_ORIENTATION]->(ao:Orientation)
										optional match (sp)-[rur:HAS_ROCK_UNIT]->(ru:RockUnit)
										optional match (sp)-[htr:HAS_TRACE]->(trace:Trace)
										optional match (sp)-[hir:HAS_IMAGE]->(image:Image)
										optional match (sp)-[sr:HAS_SAMPLE]->(s:Sample)
										optional match (sp)-[ofr:HAS_OTHER_FEATURE]->(of:OtherFeature)
										optional match (sp)-[tdr:HAS_3D_STRUCTURE]->(td:_3DStructure)
										optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)
										optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)-[rr:HAS_RELATIONSHIP]->(r:Relationship)
										delete aor,ao,or,o,rur,ru,htr,trace,sr,s,ofr,of,tdr,td,rr,r,ir,i,hir
										");
										
										
					$this->neodb->updateNode($spotid,$injson,"Spot");

					if($hasgeometry=="yes"){
						//JMA 07/02/2018 Do we need spatial? Remove this for now
						//$this->neodb->addNodeToSpatial($spotid);
					}

					if($bbox!=""){
						$this->neodb->query("match (s:Spot) where id(s)=$spotid set s.bbox=$bbox return id(s)");
					}

					//********************************************************************
					//now load additional nodes
					//********************************************************************
					$this->loadAdditionalNodes($spotid,$properties);

					//********************************************************************
					// Now, load images...
					//********************************************************************
					$this->loadImages($spotid,$images);


					$upload->properties->self="https://strabospot.org/db/feature/$thisid";
					$data=$upload;
				
				}

			}//end if featuretype==""
		
		}elseif($dbaction=="interror"){
		
			$data["Error"] = "Feature ID must be integer.";
		
		}
		
		$totalspottime = microtime(true)-$spotstarttime;
		$this->logToFile("insertSpot took: ".$totalspottime." secs","TOTAL SPOT TIME");
		
		return $data;
	
	}


	public function buildDatasetRelationships($datasetid){
	
		$record = $this->neodb->getRecord("match (p:Project)-[r:HAS_DATASET]->(d:Dataset) where d.id=$datasetid and d.userpkey=$this->userpkey return p");
		$projectid = $this->neodb->get_var("match (p:Project)-[HAS_DATASET]->(d:Dataset) where d.id=$datasetid return p.id");
		
		$this->buildRelationshipsFromRecord($record,$projectid,$datasetid);
	
	}

	public function deleteDatasetRelationships($datasetid){
	
		//echo "datasetid: $datasetid";exit();
		$this->neodb->query("match ()-[r:IS_TAGGED]->() where r.datasetid=$datasetid and r.userpkey=$this->userpkey delete r");
		$this->neodb->query("match ()-[r:IS_RELATED_TO]->() where r.datasetid=$datasetid and r.userpkey=$this->userpkey delete r");
	
	}

	public function buildProjectRelationships($projectid){

		$record = $this->neodb->getRecord("match (p:Project) where p.id=$projectid and p.userpkey=$this->userpkey return p");
		$datasetid = $this->neodb->get_var("match (p:Project)-[HAS_DATASET]->(d:Dataset) where p.id=$projectid return d.id");
		
		$projectstarttime=microtime(true);
		
		$this->buildRelationshipsFromRecord($record,$projectid,$datasetid);
		
		$totalprojecttime = microtime(true)-$projectstarttime;
		$this->logToFile("buildRelationshipsFromRecord took: ".$totalprojecttime." secs","Project Time");
	
	}


	public function buildRelationshipsFromRecord($record,$projectid,$datasetid){ //accepts project record and creates relationships/tags

		if($record!=""){

			$values = $record->get("p")->values();
			$projectid = (int)$values["id"];
			
			$projectstarttime=microtime(true);
			
			$json_relationships = $values["json_relationships"];
			if($json_relationships!=""){
				$relationships = json_decode($json_relationships);
				//print_r($relationships);
				foreach($relationships as $r){



					$sidea = $r->a;
					$fromids = $this->getIDsFromArray($sidea);
					
					$sideb = $r->b;
					$toids = $this->getIDsFromArray($sideb);
					
					$types = $r->types;
					
					//echo "****************************************************\n";
					//echo "$reljson\n\n";
					
					foreach($fromids as $fromid){
					
						foreach($types as $type){
						
							foreach($toids as $toid){
							
								unset($reljson);
								if($r->id!=""){$reljson["id"]=(int)$r->id;}
								if($r->name!=""){$reljson["name"]=$r->name;}
								if($r->deformation_event!=""){$reljson["deformation_event"]=$r->deformation_event;}
								$reljson["datasetid"]=(int)$datasetid; //don't need this. Let's deal with tags/relationships at project level ??? 
								$reljson["projectid"]=(int)$projectid;
								$reljson["userpkey"]=$this->userpkey;
								$reljson["relationship_type"]=$type;
								$reljson = json_encode($reljson);
								$reljson = $this->escape($reljson);

								$this->neodb->query("MATCH (a".$fromid[1]." { id:".$fromid[0].", userpkey:$this->userpkey }), (b".$toid[1]." { id:".$toid[0].", userpkey:$this->userpkey }) CREATE UNIQUE (a)-[r:IS_RELATED_TO $reljson]-(b) RETURN r");
							
							}
						
						}
					
					}
					
				
				}
			}

			$totalprojecttime = microtime(true)-$projectstarttime;
			$this->logToFile("relationships took: ".$totalprojecttime." secs","Project Time");


			$projectstarttime=microtime(true);

			$json_tags = $values["json_tags"];
			if($json_tags!=""){
			
				$tags = json_decode($json_tags);
				foreach($tags as $t){

					$toid=(int)$t->id;
					
					unset($tagjson);
					$tagjson["id"]=(int)$t->id;
					$tagjson["datasetid"]=(int)$datasetid;
					$tagjson["projectid"]=(int)$projectid;
					$tagjson["userpkey"]=$this->userpkey;
					$tagjson = json_encode($tagjson);
					$tagjson = $this->escape($tagjson);
					
					$fromids = $this->getIDsFromArray($t);

					foreach($fromids as $fromid){
						if($fromid[1]==""){$fromid[1]=":Strabo";}
						$this->neodb->query("MATCH (a".$fromid[1]." { id:".$fromid[0].", userpkey:$this->userpkey }), (b:Tag { id:".$toid.", userpkey:$this->userpkey }) CREATE UNIQUE (a)-[r:IS_TAGGED $tagjson]-(b) RETURN r");
					}
					
				}
			
			}
			
			$totalprojecttime = microtime(true)-$projectstarttime;
			$this->logToFile("tags took: ".$totalprojecttime." secs","Project Time");
		
		}
	}



















	public function getIDsFromArray($array){ //returns array of spot/feature/tag ids from a relationships "side" (a/b)

		$ids = array();

		$spots = $array->spots;
		if($spots){
			foreach($spots as $s){
				$ids[]=array((int)$s,":Spot");
			}
		}

		$features = $array->features;
		if($features){
			foreach($features as $feat){
				foreach($feat as $f){
					$ids[]=array((int)$f,"");
				}
			}
		}

		$tags = $array->tags;
		if($tags){
			foreach($tags as $t){
				$ids[]=array((int)$t,":Tag");
			}
		}		
		
		
		return $ids;
	}




	public function loadImages($spotid,$images){
	
		if($images!=""){
		
			//print_r($images);exit();

			foreach($images as $image){
			
				if($image->id){
					$imageid=(int)$image->id;
				}
				
				if($image->straboid){
					$imageid=(int)$image->straboid;
				}
				
				unset($injson);
				
				foreach($image as $key=>$value){
					if($key!="self" && $value!=null){
						if($key=="straboid"){$key=id;}
						if($key=="id"){
							$injson[$key]=(int)$value;
						}else{
							$injson[$key]=$value;
						}
					}
				}
				
				$injson["userpkey"]=$this->userpkey;
		
				//********************************************************************
				// check to see if image already exists
				//********************************************************************
				if($imageid!=""){
					$querystring = "MATCH (n:Image) WHERE n.id=$imageid and n.userpkey = $this->userpkey RETURN id(n);";
					$neoid = $this->neodb->get_var($querystring);
				}else{
					$neoid="";
				}
				
				if($neoid!=""){

					$querystring = "MATCH (n:Image) WHERE n.id=$imageid and n.userpkey = $this->userpkey RETURN n;";
					$body = $this->neodb->getNode($querystring);

					foreach($body as $key=>$value){
						if($value != ""){
							eval("\$injson['$key'] = \$value;");
							//echo "$key : $value \n";
						}
					}

					$injson = json_encode($injson);

					$self = $this->neodb->updateNode($neoid,$injson,"Image");

					//********************************************************************
					// Now link image to spot
					//********************************************************************
					$this->neodb->addRelationship($spotid, $neoid, "HAS_IMAGE","Spot","Image");

				}else{

					//image doesn't exist, create new image here

					$injson=json_encode($injson);

					//********************************************************************
					// OK, we have some JSON formed and ready for Neo4j.
					// Let's create a node
					//********************************************************************
					$imageid = $this->neodb->createNode($injson,"Image");

					//********************************************************************
					// Now link image to spot
					//********************************************************************
					$this->neodb->addRelationship($spotid, $imageid, "HAS_IMAGE","Spot","Image");

				}
			
			}

		
		}
	
	}





	public function loadAdditionalNodes($spotid,$properties){

		//$this->dumpVar($properties);
		//orientation_data
		//print_r($properties->orientation_data);exit();
		if($properties->orientation_data!=""){
			//associated_orientation
			foreach($properties->orientation_data as $od){
				unset($thisod);
				unset($associated_orientations);
				foreach($od as $key=>$value){
					if($key=="id"){$value = (int)$value;}
					if($key!="associated_orientation"){
						if($value!=""){
							if(is_array($value)){$value=json_encode($value);}
							$thisod[$key]=$value;
						}
					}else{
						$associated_orientations=$value;
					}
				}
				
				//put in node
				$thisod["userpkey"]=$this->userpkey;
				$ojson = json_encode($thisod);
				$oid = $this->neodb->createNode($ojson,"Orientation");
				$this->neodb->addRelationship($spotid, $oid, "HAS_ORIENTATION","Spot","Orientation");
				
				if($associated_orientations){
					foreach($associated_orientations as $ao){
						unset($thisao);
						foreach($ao as $key=>$value){
							if($key=="id"){$value = (int)$value;}
							if($value!=""){
								if(is_array($value)){$value=json_encode($value);}
								$thisao[$key]=$value;
							}
						}
						$thisao["userpkey"]=$this->userpkey;
						$aojson = json_encode($thisao);
						$aoid = $this->neodb->createNode($aojson,"Orientation");
						$this->neodb->addRelationship($oid, $aoid, "HAS_ASSOCIATED_ORIENTATION","Orientation","Orientation");
					}
				}
			}
		}

		//rock_unit
		if($properties->rock_unit!=""){
			unset($thisru);
			foreach($properties->rock_unit as $key=>$value){
				if($key=="id"){$value = (int)$value;}
				if($value!=""){
					if(is_array($value)){$value=json_encode($value);}
					$thisru[$key]=$value;
				}
			}
			
			//put in node
			$thisru["userpkey"]=$this->userpkey;
			$rujson = json_encode($thisru);
			$ruid = $this->neodb->createNode($rujson,"RockUnit");
			$this->neodb->addRelationship($spotid, $ruid, "HAS_ROCK_UNIT","Spot","RockUnit");

		}

		//samples
		//print_r($properties->samples);exit();
		if($properties->samples!=""){
			foreach($properties->samples as $samp){
				unset($thissamp);
				foreach($samp as $key=>$value){
					if($key=="id"){$value = (int)$value;}
					if($value!=""){
						if(is_array($value)){$value=json_encode($value);}
						$thissamp[$key]=$value;
					}
				}
				
				//put in node
				$thissamp["userpkey"]=$this->userpkey;
				$sampjson = json_encode($thissamp);
				$sampid = $this->neodb->createNode($sampjson,"Sample");
				$this->neodb->addRelationship($spotid, $sampid, "HAS_SAMPLE","Spot","Sample");

			}
		}

		//other_features
		//print_r($properties->other_features);exit();
		if($properties->other_features!=""){
			foreach($properties->other_features as $of){
				unset($thisof);
				foreach($of as $key=>$value){
					if($key=="id"){$value = (int)$value;}
					if($value!=""){
						if(is_array($value)){$value=json_encode($value);}
						$thisof[$key]=$value;
					}
				}
				
				//put in node
				$thisof["userpkey"]=$this->userpkey;
				$ofjson = json_encode($thisof);
				$ofid = $this->neodb->createNode($ofjson,"OtherFeature");
				$this->neodb->addRelationship($spotid, $ofid, "HAS_OTHER_FEATURE","Spot","OtherFeature");

			}
		}

		//_3d_structures
		//print_r($properties->_3d_structures);exit();
		if($properties->_3d_structures!=""){
			foreach($properties->_3d_structures as $tds){
				unset($thistds);
				foreach($tds as $key=>$value){
					if($key=="id"){$value = (int)$value;}
					if($value!=""){
						if(is_array($value)){$value=json_encode($value);}
						$thistds[$key]=$value;
					}
				}
				
				//put in node
				$thistds["userpkey"]=$this->userpkey;
				$tdsjson = json_encode($thistds);
				$tdsid = $this->neodb->createNode($tdsjson,"_3DStructure");
				$this->neodb->addRelationship($spotid, $tdsid, "HAS_3D_STRUCTURE","Spot","_3DStructure");

			}
		}

		//inferences
		if($properties->inferences!=""){
			unset($thisinference);
			unset($inferencerelationships);
			foreach($properties->inferences as $key=>$value){
				if($key=="id"){$value = (int)$value;}
				if($key!="relationships"){
					if($value!=""){
						if(is_array($value)){$value=json_encode($value);}
						$thisinference[$key]=$value;
					}
				}else{
					$inferencerelationships=$value;
				}
			}
			
			//put in node
			$thisinference["userpkey"]=$this->userpkey;
			$infrerencejson = json_encode($thisinference);
			$inferenceid = $this->neodb->createNode($infrerencejson,"Inference");
			$this->neodb->addRelationship($spotid, $inferenceid, "HAS_INFERENCE","Spot","Inference");
			
			if($inferencerelationships){
				foreach($inferencerelationships as $ir){
					unset($thisir);
					foreach($ir as $key=>$value){
						//echo "$key $value ".is_int($value)."\n";
						
						if(is_numeric($value)){$value=(real)$value;}
						if($value!=""){
							if(is_array($value)){$value=json_encode($value);}
							$thisir[$key]=$value;
						}
					}

					$thisir["userpkey"]=$this->userpkey;
					$irjson = json_encode($thisir);
					$irid = $this->neodb->createNode($irjson,"Relationship");
					$this->neodb->addRelationship($inferenceid, $irid, "HAS_RELATIONSHIP","Inference","Relationship");

				}
			}

		}

		//trace
		if($properties->trace!=""){
			unset($thistrace);
			foreach($properties->trace as $key=>$value){
				if($key=="id"){$value = (int)$value;}
				if($value!=""){
					if(is_array($value)){$value=json_encode($value);}
					$thistrace[$key]=$value;
				}
			}
			
			//put in node
			$thistrace["userpkey"]=$this->userpkey;
			$tracejson = json_encode($thistrace);
			$traceid = $this->neodb->createNode($tracejson,"Trace");
			$this->neodb->addRelationship($spotid, $traceid, "HAS_TRACE","Spot","Trace");

		}

	}


	public function getSingleDataset($feature_id){

		$querystring = "MATCH (n:Dataset) WHERE n.id = $feature_id RETURN n;";
		$featuredata = $this->neodb->getNode($querystring);

		$count=count($featuredata);

		if($count > 0){

			$data = $this->singleDatasetJSON($featuredata);
			//print_r($data);exit();

		}else{
			//Error, sample not found
			$data["Error"] = "Dataset $feature_id not found.";
		}

		return $data;

	}



	public function singleDatasetJson($featuredata){

			$id = $featuredata["id"];
			
			foreach($featuredata as $key=>$value){
			
				if(	$key != "wkt" &&
					$key != "gtype" &&
					$key != "bbox_abc" &&
					$key != "featuretype" &&
					$key != "geometrytype" &&
					$key != "coordinates" &&
					$key != "userpkey" &&
					$key != "datecreated" 
					){
					
					if($key=="featurename"){$key="name";}
					
					eval("\$data['$key']=\$value;");	
				
				}

			}
			
			if($data['id']==""){$data['id']=$id;}

			$data['self']="https://strabospot.org/db/dataset/$id";
			
			return $data;
	}



	public function findDataset($feature_id){
	
		$querystring = "MATCH (n:Dataset) WHERE n.id = $feature_id and n.userpkey = $this->userpkey RETURN id(n);";

		$datasetid = $this->neodb->get_var($querystring);

		if($datasetid != ""){
			return $datasetid;
		}else{
			return false;
		}
	
	}

	public function getProjectFromDatasetId($datasetid){
	
		$projectid = $this->getProjectID($datasetid);
		if($projectid){
			return $this->getProject($projectid);
		}else{
			return null;
		}
	
	}

	public function getProjectId($datasetid){
	
		if($this->userpkey!=99999){
			$userpkeystring=" {userpkey:$this->userpkey}";
		}
		
		//$query = "match (p:Project".$userpkeystring.")-[:HAS_DATASET]->(d:Dataset {id:$datasetid,userpkey:$this->userpkey}) return p.id";
		
		$query = "match (p:Project".$userpkeystring.")-[:HAS_DATASET]->(d:Dataset {id:$datasetid}) return p.id";
		
		$datasetid = $this->neodb->get_var($query);
		
		//echo "datasetid: $datasetid";exit();
		
		return $datasetid;
	
	}

	public function getImageFilenameOld($imageid){
	
		//$filename = $this->neodb->get_var("match (i:Image {userpkey:$this->userpkey,id:$imageid}) return i.filename");
		$filename = $this->neodb->get_var("match (i:Image {id:$imageid}) return i.filename"); //open up due to search
		return $filename;
	
	}

	public function getImageFilename($imageid){
	
		$foundfilename="";
		//$filename = $this->neodb->get_var("match (i:Image {userpkey:$this->userpkey,id:$imageid}) return i.filename");
		$filenames = $this->neodb->get_results("match (i:Image {id:$imageid}) return i.filename"); //open up due to search

		foreach($filenames as $fn){
			$foundfile = $fn->get("i.filename");
			if(file_exists("/var/www/dbimages/$foundfile")){
				$foundfilename=$foundfile;
			}
		}

		return $foundfilename;
	
	}



	public function getProjectIdFromSpotId($spotid){
	
		$projectid = $this->neodb->get_var("match (p:Project {userpkey:$this->userpkey})-[:HAS_DATASET]->(d:Dataset)-[HAS_SPOT]->(s:Spot {id:$spotid,userpkey:$this->userpkey}) return p.id");
		return $datasetid;
	
	}

	public function deleteSingleDataset($datasetid){

		//first, get projecid and create version
		if($projectid=$this->getProjectId($datasetid)){
			$this->createVersion($projectid);
		}
		
		//next, delete images;

		$rows=$this->neodb->query("match (d:Dataset)
						where d.userpkey=$this->userpkey and d.id=$datasetid
						match (d)-[dsetr:HAS_SPOT]->(sp:Spot)
						match (sp)-[hir:HAS_IMAGE]->(img:Image)
						return img;
						");

		if(count($rows)>0){
			foreach($rows as $row){
				$image=$row->get("img");
				$image=(object)$image->values();
				//print_r($image);
				$filename=$image->filename;
				//unlink("/var/www/dbimages/$filename");
			}
		}

		$this->neodb->query("match ()-[r:IS_RELATED_TO {datasetid:$datasetid,userpkey:$this->userpkey}]-() delete r;");
		
		$this->neodb->query("match ()-[rr:IS_TAGGED {datasetid:$datasetid,userpkey:$this->userpkey}]-() delete rr;");
		
		$this->neodb->query("match (d:Dataset)
						where d.userpkey=$this->userpkey and d.id=$datasetid
						OPTIONAL MATCH (p)-[hdr:HAS_DATASET]-(d)
						optional match (d)-[dsetr:HAS_SPOT]->(sp:Spot)
						optional match (spat)-[orrr:RTREE_REFERENCE]->(sp)
						optional match (sp)-[hir:HAS_IMAGE]->(img:Image)
						optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)
						optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)-[aor:HAS_ASSOCIATED_ORIENTATION]->(ao:Orientation)
						optional match (sp)-[rur:HAS_ROCK_UNIT]->(ru:RockUnit)
						optional match (sp)-[htr:HAS_TRACE]->(trace:Trace)
						optional match (sp)-[sr:HAS_SAMPLE]->(s:Sample)
						optional match (sp)-[ofr:HAS_OTHER_FEATURE]->(of:OtherFeature)
						optional match (sp)-[tdr:HAS_3D_STRUCTURE]->(td:_3DStructure)
						optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)
						optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)-[rr:HAS_RELATIONSHIP]->(r:Relationship)
						delete rr,r,ir,i,tdr,td,ofr,of,sr,s,htr,trace,rur,ru,aor,ao,or,o,hir,img,orrr,dsetr,sp,hdr,d
						
						
						
						
						");

	}

	public function datasetExistsInOtherProject($datasetid,$projectid){
	
		$count = $this->neodb->get_var("match (p:Project)-[:HAS_DATASET]->(d:Dataset {id:$datasetid,userpkey:$this->userpkey}) where p.id <> $projectid return count(*)");
	
		if($count > 0){
			return true;
		}else{
			return false;
		}
	
	}

	public function spotExistsInOtherDataset($spotid,$datasetid){

		$count = $this->neodb->get_var("match (d:Dataset)-[:HAS_SPOT]->(s:Spot {id:$spotid,userpkey:$this->userpkey}) where d.id <> $datasetid return count(*)");
	
		if($count > 0){
			return true;
		}else{
			return false;
		}
	}

	public function insertDataset($injson,$feature_id=null){
        
        //get id from json in case $feature_id supplied is null (from url)
        $thisjson=json_decode($injson);
        if($thisjson->id!=""){
        	$feature_id = $thisjson->id;
        }
        
        if($feature_id!=""){
        	$dataset_id=$this->findDataset($feature_id);
        }

		if($dataset_id){

			//feature found, update
			//********************************************************************
			// get existing dataset so we can gather up datecreated
			//********************************************************************
			$querystring = "MATCH (n) WHERE n.id = $feature_id and n.userpkey = $this->userpkey RETURN n;";
			$node = $this->neodb->getNode($querystring);

			foreach($node as $key=>$value){
				//echo "$key : $value\n";
				if($key!="featureid" && $key!="self"){
					eval("\$$key=\$value;");
				}
			}

			//********************************************************************
			//need to add some error checking here
			//********************************************************************
			
			$upload = json_decode($injson);
			
			unset($injson);
	
			unset($upload->apiformat);

			foreach($upload as $key=>$value){
				if($key!="id" && $key!="featureid" && $key!="self" ){
					if(is_array($value)){
						$value=json_encode($value);
					}
					eval("\$injson['$key']=\$value;");
				}
			}

			if($id!=""){
				$injson['id']=(int)$id;
			}
			
			if($datecreated==""){
				$datecreated=time();
			}
			
			$injson['datecreated']=$datecreated;
			$injson['datasettype']="app";
			$injson['userpkey']=$this->userpkey;
			$injson=json_encode($injson);

			//********************************************************************
			// OK, we have some JSON formed and ready for Neo4j.
			// Let's PUT to the REST API to update attributes for node
			//********************************************************************
			
			//print_r($injson);exit();
			
			$self = $this->neodb->updateNode($dataset_id,$injson,"Dataset");

			$upload->self="https://strabospot.org/db/dataset/$feature_id";

			$data=$upload;

		}else{
			//Either feature_id not provided, or not found, let's put new dataset in
			if($feature_id==""){
				$mytime=time();
				$myrand = rand(1000,9999);
				$feature_id= $mytime.$myrand;
			}
			
			$feature_id = (int) $feature_id;
	
			//********************************************************************
			//need to add some error checking here
			//********************************************************************
	
			$upload = json_decode($injson);
	
			unset($injson);

			foreach($upload as $key=>$value){
				if($key!="id" && $key!="featureid" && $key!="self"){
					if(is_array($value)){
						$value=json_encode($value);
					}
					eval("\$injson['$key']=\$value;");
				}
			}

			$datecreated=time();	
			$injson['datecreated'] = $datecreated;

			$injson['id']=$feature_id;
			$injson['userpkey']=$this->userpkey;

			$injson['datasettype']="app";
			
			$injson=json_encode($injson,JSON_PRETTY_PRINT);

			//********************************************************************
			// OK, we have some JSON formed and ready for Neo4j.
			// Let's create a node
			//********************************************************************
			$newdatasetid = $this->neodb->createNode($injson,"Dataset");
	
			$upload->datasettype="app";
			$upload->self="https://strabospot.org/db/dataset/$feature_id";

			header("Feature created", true, 201);
			$data=$upload;


		}
        
        return $data;

	}



	public function getDatasetSpots($feature_id){

		//get the features from neo4j
		$querystring = "match (a:Dataset)-[r:HAS_SPOT]->(s:Spot) where a.userpkey=$this->userpkey and a.id=$feature_id optional match (s)-[c:HAS_IMAGE]-(i:Image) with s, collect(i) as i RETURN s,i;";
		$json = $this->getFeatureCollection($querystring);
		return $json;

	}

	public function getDatasetSpotsLabBook($feature_id){

		//get the features from neo4j
		$querystring = "match (a:Dataset)-[r:HAS_SPOT]->(s:Spot) where a.userpkey=$this->userpkey and a.id=$feature_id optional match (s)-[c:HAS_IMAGE]-(i:Image) with s, collect(i) as i RETURN s,i order by s.id desc;";
		$json = $this->getFeatureCollection($querystring);
		return $json;

	}

	public function getDatasetSpotIds($feature_id){

		//get the features from neo4j
		$querystring = "match (d:Dataset)-[r:HAS_SPOT]->(s:Spot) where d.userpkey=$this->userpkey and d.id=$feature_id optional match (s)-[c:HAS_IMAGE]-(i:Image) with s, collect(i) as i RETURN s,i;";
		$json = $this->getFeatureCollection($querystring);
		foreach($json['features'] as $f){
			$outarray[]=$f['properties']['id'];
		}
		
		return $outarray;
		
	}


	public function getDatasetRawData($feature_id){

		//get the data from neo4j
		// don't need this, this request comes from my_data page $feature_id = $this->straboIDToID($feature_id);
		$querystring = "match (a:Dataset)-[r:HAS_SPOT]->(b) where a.userpkey=$this->userpkey and a.id=$feature_id return b;";
		$rows = $this->neodb->query($querystring);
		
		if($rows){
			$featuredata=array();
			foreach($rows as $row){
				$values = $row->get("b")->values();
				$featuredata[]=$values;
			}
		}

		return $featuredata;

	}































	public function getDatasetSpotsSearch($ingtype=null, $get){

		$dsids = $get['dsids'];
		$range = $get['range'];
		$envelope = $get['envelope'];
		$getuserpkey = $get['userpkey'];

		if($range=="envelope"){
			$parts = explode(",",$envelope);
			$left = $parts[0]-$offset;
			$top = $parts[1]+$offset;
			$right = $parts[2]+$offset;
			$bottom = $parts[3]-$offset;
			$envelopestring="CALL spatial.bbox('geom',{longitude:$left,latitude:$bottom},{longitude:$right, latitude:$top}) YIELD node as s";
		}

		if($get["hasimage"]=="yes"){
			$imagestring = "match (s)-[:HAS_IMAGE]->(i:Image)";
		}else{
			$imagestring = "optional match (s)-[:HAS_IMAGE]->(i:Image)";
		}

		if($get["hasorientation"]=="yes"){
			$orientationstring = "match (s)-[:HAS_ORIENTATION]->(o:Orientation)";
		}

		if($get["hassample"]=="yes"){
			$samplestring = "match (s)-[:HAS_SAMPLE]->(samp:Sample)";
		}

		if($get["has3dstructure"]=="yes"){
			$_3dstructurestring = "match (s)-[:HAS_3D_STRUCTURE]->(td:_3DStructure)";
		}

		if($get["hasotherfeature"]=="yes"){
			$_3dstructurestring = "match (s)-[:HAS_OTHER_FEATURE]->(hof:OtherFeature)";
		}

		if($getuserpkey!=""){
			$userpkeystring = "and d.userpkey = $getuserpkey";
		}


		/*
		$querystring="
		match (p:Project {public:1})-[HAS_DATASET]->(d:Dataset) 
		match (d)-[:HAS_SPOT]->(s:Spot)
		$imagestring
		$orientationstring
		$samplestring
		$_3dstructurestring
		return distinct(d), count(s) as c
		";
		*/

		// !! also look for envelope
		
		//get all features from neo4j
		/*
		$querystring = "
				match (a:Dataset)-[r:HAS_SPOT]->(s:Spot) where a.id in [$dsids]
				optional match (s)-[c:HAS_IMAGE]-(i:Image)
				with s, collect(i) as i
				RETURN s,i;
			";
		*/

		$querystring = "
				$envelopestring
				match (d:Dataset)-[r:HAS_SPOT]->(s:Spot) where d.id in [$dsids] $userpkeystring
				$imagestring
				$orientationstring
				$samplestring
				$_3dstructurestring
				with s, collect(i) as i
				RETURN s,i order by s.id;
			";

		
		//set a class variable to tell the other functions that this is an arc query.
		$this->isarc = true;
		
		$json = $this->getFeatureCollection($querystring);


		if($ingtype == "point" || $ingtype == "line" || $ingtype == "polygon"){
			$json = $this->stripJSON($json,$ingtype);
		}

		return $json;

	}











































	public function getDatasetSpotsArc($feature_id, $ingtype=null){

		$feature_id = $this->straboIDToID($feature_id,"Dataset");
		
		//get all features from neo4j
		$querystring = "match (a:Dataset)-[r:HAS_SPOT]->(s:Spot) where a.userpkey=$this->userpkey and id(a)=$feature_id optional match (s)-[c:HAS_IMAGE]-(i:Image) with s, collect(i) as i RETURN s,i;";

		//set a class variable to tell the other functions that this is an arc query.
		$this->isarc = true;
		
		$json = $this->getFeatureCollection($querystring);
		
		/*
		if($ingtype == "point"){$extraquery = " and b.gtype = 1 ";}
		if($ingtype == "line"){$extraquery = " and b.gtype = 2 ";}
		if($ingtype == "polygon"){$extraquery = " and b.gtype = 3 ";}
		*/
		
		if($ingtype == "point" || $ingtype == "line" || $ingtype == "polygon"){
			$json = $this->stripJSON($json,$ingtype);
		}
		
		
		return $json;

	}

	
	public function stripJSON($json,$ingtype){
		if($ingtype!=""){
			$ingtype = strtolower($ingtype);
			if($ingtype=="point"){$looking="point";}
			if($ingtype=="line"){$looking="linestring";}
			if($ingtype=="polygon"){$looking="polygon";}

			$newjson['type']="FeatureCollection";

			$x=0;
			foreach($json['features'] as $f){
				$thisgeomtype = strtolower($f['geometry']->type);
				
				if($thisgeomtype==$looking){
					$newjson['features'][$x]=$f;
					$x++;
				}
			}
		
			if($x>0){
				return $newjson;
			}else{
				return null;
			}
		}else{
			return $json;
		}
		
	}
	

	public function getFields($jsonarray,$prefix=null){
	
		//this is a recursive function to return distinct fields contained within a json document
		
		//print_r($jsonarray);
		
		$jsonarray =  (array) $jsonarray;
		
		foreach($jsonarray as $key=>$value){
			
			$reject = array(	"features",
								"properties",
								"images",
								"geometry",
								"title",
								"caption",
								"width",
								"height",
								"annotated",
								"type",
								"coordinates",
								"orientation_data",
								"associated_orientation",
								"image_basemap"
								);
			
			if(is_string($key)&&!in_array($key,$reject)){
			//$fields .= "$prefix$key\n";
			$fields .= "$key\n";
			}
			
			if((is_array($value)||is_object($value))){

				if(is_string($key)){
					$showprefix=$key."_";
				}else{
					$showprefix=null;
				}
				$fields .= $this->getFields($value,$showprefix);
			}
		}
		
		
		
		return $fields;
		
	}


	public function getDatasetFields($feature_id, $ingtype=null){

		/*
		if($ingtype == "point"){$extraquery = " and (s.gtype = 1) ";}
		if($ingtype == "line"){$extraquery = " and (s.gtype = 2) ";}
		if($ingtype == "polygon"){$extraquery = " and (s.gtype = 3) ";}
		*/
		
		if($ingtype == "point"){$extraquery = " and s.wkt =~ '.*POINT.*' ";}
		if($ingtype == "line"){$extraquery = " and s.wkt =~ '.*LINE.*' ";}
		if($ingtype == "polygon"){$extraquery = " and s.wkt =~ '.*POLYGON.*' ";}

		//get the feature from neo4j
		$querystring = "match (d:Dataset)-[r:HAS_SPOT]->(s:Spot) where d.userpkey=$this->userpkey and d.id=$feature_id $extraquery optional match (s)-[c:HAS_IMAGE]-(i:Image) with s, collect(i) as i RETURN s,i;";

		$json = $this->getFeatureCollection($querystring);

		$fields=$this->getFields($json);
		
		$fields = explode("\n",$fields);
		$fields = array_unique($fields);
		
		$returnfields=array();
		
		foreach($fields as $f){
			if($f!=""){
				$returnfields[]=$f;
			}
		}

		if(count($returnfields)==0){
			$returnfields=null;
		}

		return $returnfields;

	}



	public function getDatasetName($feature_id){

		$querystring = "match (a:Dataset) where a.userpkey=$this->userpkey and a.id=$feature_id RETURN a.name;";
		$datasetname = $this->neodb->get_var($querystring);

		return $datasetname;
	
	}



	public function getFeatureCollection($querystring){

		$rows = $this->neodb->get_results($querystring);
		
		$featuredata = $this->recordsToFeatureData($rows);

		$count=count($rows);

		if($count > 0){
		
			$data['type']="FeatureCollection";
			
			$x=0;
			
			//fix features with image basemaps
			/*
			if($this->isarc==true){
				$featuredata = $this->fixBasemaps($featuredata);
			}
			*/
			
			foreach($featuredata as $fd){
			
				$imagedata=$fd->row[1];
				$fd=$fd->row[0];

				$data['features'][$x]=$this->singleSpotJSONFromFeatureData($fd,$imagedata);
			
				$x++;
			}
			
			return $data;

		}else{
			
		}
	
	}


	public function recordsToFeatureData($records){

		if(count($records)>0){

			foreach($records as $record){
				unset($res);
				$res = new stdClass();
				unset($row);
				unset($imagearray);
				$s=$record->get("s");
				$s=$s->values();
				$row[0]=(object)$s;
				$images=$record->get("i");
				if(count($images)>0){
					foreach($images as $i){
						$i=(object)$i->values();
						$imagearray[]=$i;
					}
				}
				$row[1]=$imagearray;
				$res->row=$row;
				$results[]=$res;
			}
			
			return $results;
		
		}else{
		
			return null;
		
		}
	
	}


	public function fixBasemaps($featuredata){//this function turns pixel coordinates into real-world coordinates
	
		$newfeaturedata = $featuredata;
		
		$x=0;
		foreach($featuredata as $fd){
		
			$id = $fd->row[0]->id;
			$origwkt = $fd->row[0]->wkt;
			$newfeaturedata[$x]->row[0]->origwkt=$origwkt;
			$wkt = $this->fixWKT($featuredata,$id);
			$newfeaturedata[$x]->row[0]->wkt=$wkt;

		$x++;
		}
	
		return $newfeaturedata;
	}

	
	public function fixWKT($featuredata,$id,$imageid){
	
		if($id!=""){//look for imageid
			foreach($featuredata as $fd){
				$image_basemap = $fd->row[0]->image_basemap;
				$thisid = $fd->row[0]->id;
				if($thisid==$id){
					if($image_basemap!=""){
						$wkt = $this->fixWKT($featuredata,null,$image_basemap);
					}else{
						$wkt = $fd->row[0]->wkt;
					}
				}
			}
		}elseif($imageid!=""){ //look for image

			foreach($featuredata as $fd){
				$image_basemap = $fd->row[0]->image_basemap;
				$thisid = $fd->row[0]->id;
				
				$images = $fd->row[1];
				foreach($images as $i){
					$thisimageid = $i->id;
					if($thisimageid==$imageid){
						if($image_basemap!=""){
							$wkt = $this->fixWKT($featuredata,null,$image_basemap);
						}else{
							$wkt = $fd->row[0]->wkt;
						}
					}
				}
			}

		}
		
		return $wkt;
		
	}


	public function fixIncomingBasemaps($featuredata){//this function turns pixel coordinates into real-world coordinates

		$newfeaturedata = $featuredata;

		$x=0;
		foreach($featuredata as $fd){

			$id = $fd->properties->id;

			$hasgeometry="yes";
		
			//use geoPHP to get WKT
			$mygeojson=$fd->geometry;
			$mygeojson=trim(json_encode($mygeojson));
		
			try {
				$mywkt=geoPHP::load($mygeojson,"json");
				$wkt = $mywkt->out('wkt');
				$newfeaturedata[$x]->properties->wkt=$wkt;
				$newfeaturedata[$x]->properties->origwkt=$wkt;
			} catch (Exception $e) {
				$hasgeometry="no";
			}



		$x++;
		}

		$x=0;
		foreach($newfeaturedata as $fd){

			$wkt=$fd->properties->wkt;
			
			$id = $fd->properties->id;
			
			if($wkt!=""){
				$fixedwkt = $this->fixIncomingWKT($newfeaturedata,$id);
				$newfeaturedata[$x]->properties->wkt=$fixedwkt;
			}
			$x++;
		}

		return $newfeaturedata;
	}


	public function fixIncomingWKT($featuredata,$id,$imageid=""){
	
		if($id!=""){//look for imageid
			
			foreach($featuredata as $fd){
				$image_basemap = $fd->properties->image_basemap;
				$thisid = $fd->properties->id;
				if($thisid==$id){
					if($image_basemap!=""){
						$wkt = $this->fixIncomingWKT($featuredata,null,$image_basemap);
					}else{
						$wkt = $fd->properties->wkt;
					}
				}
			}
			
		}elseif($imageid!=""){ //look for image

			foreach($featuredata as $fd){
				$image_basemap = $fd->properties->image_basemap;
				$thisid = $fd->properties->id;
				
				$images = $fd->properties->images;
				if($images!=""){
					foreach($images as $i){
						
						if($i->straboid!=""){
							$thisimageid = $i->straboid;
						}else{
							$thisimageid = $i->id;
						}

						if($thisimageid==$imageid){
							if($image_basemap!=""){
								$wkt = $this->fixIncomingWKT($featuredata,null,$image_basemap);
							}else{
								$wkt = $fd->properties->wkt;
							}
						}
					}
				}
			}

		}
		
		return $wkt;
		
	}

	//recursively gets real-world coordinates from pixel-coordinates for a single spot
	//relies on graph db to get image basemaps and spots
	public function fixSpotCoordinates($imageid){
		
		$querystring = "match (s:Spot)-[HAS_IMAGE]->(i:Image) where s.userpkey=$this->userpkey and i.id=$imageid return s;";
		$spot = $this->neodb->getNode($querystring);
		
		$image_basemap = $spot['image_basemap'];
		$wkt = $spot['wkt'];
		
		if($image_basemap==""){
			return $wkt;
		}else{
			return $this->fixSpotCoordinates($image_basemap);
		}

	}

	public function findSpotInDataset($dataset_id,$spot_id){

		$querystring = "match (a:Dataset)-[r:CONTAINS]->(b:Spot) where a.userpkey=$this->userpkey and a.id=$dataset_id and b.id=$spot_id return count(*);";
		$body = $this->neodb->query($querystring);
	
		$count = $body->results[0]->data[0]->row[0];


		if($count == 0){
			return false;
		}else{
			return true;
		}

	}

	public function addSpotToDataset($datasetid,$to,$relationshiptype="HAS_SPOT"){

		$spotstarttime = microtime(true);
		
		$datasetid = $this->straboIDToID($datasetid,"Dataset");
		$id = $this->straboIDToID($to,"Spot");
		
		$totalspottime = microtime(true)-$spotstarttime; $this->logToFile("straboidtoid took: ".$totalspottime." secs","DATASET SPOT TIME");
		
		//$count = $this->neodb->get_var("match (from:Dataset)-[r:HAS_SPOT]-(to:Spot) where id(from)=$datasetid and id(to)=$id return count(r)");
		
		/*
		if($count==0){
			$self = $this->neodb->addRelationship($datasetid,$id,$relationshiptype,"Dataset","Spot");
		}else{
			$self = null;
		}
		*/
		
		$self = $this->neodb->addRelationship($datasetid,$id,$relationshiptype,"Dataset","Spot");
		
		return $self;	
	}



	public function straboIDToID($id,$type){
	
		$querystring = "match (a:$type) where a.userpkey=$this->userpkey and a.id=$id return id(a);";
		$id = $this->neodb->get_var($querystring);
		return $id;
	}

	
	public function deleteDatasetReltationships($feature_id){
		$projectid=$this->neodb->get_var("match (p:Project)-[HAS_DATASET]->(d:Dataset) where d.id=$feature_id return p.id");
		$this->deleteProjectReltationships($projectid);
	}

	public function deleteProjectReltationships($feature_id){
		$this->neodb->query("match ()-[r:IS_RELATED_TO {projectid:$feature_id,userpkey:$this->userpkey}]-() delete r;");
		$this->neodb->query("match ()-[rr:IS_TAGGED {projectid:$feature_id,userpkey:$this->userpkey}]-() delete rr;");
	}

	public function deleteDatasetSpots($feature_id){

		$this->neodb->query("optional match ()-[r:IS_RELATED_TO {datasetid:$feature_id,userpkey:$this->userpkey}]-(), ()-[rr:IS_TAGGED {datasetid:$feature_id,userpkey:$this->userpkey}]-() delete r,rr;");
		$this->neodb->query("match (d:Dataset {id:$feature_id,userpkey:$this->userpkey})-[dr:HAS_SPOT]->(sp:Spot)
							optional match (ds:Dataset)-[dsetr:HAS_SPOT]->(sp)
							optional match (spat)-[orrr:RTREE_REFERENCE]->(sp)
							optional match (sp)-[hir:HAS_IMAGE]->(img:Image)
							optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)
							optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)-[aor:HAS_ASSOCIATED_ORIENTATION]->(ao:Orientation)
							optional match (sp)-[rur:HAS_ROCK_UNIT]->(ru:RockUnit)
							optional match (sp)-[htr:HAS_TRACE]->(trace:Trace)
							optional match (sp)-[sr:HAS_SAMPLE]->(s:Sample)
							optional match (sp)-[ofr:HAS_OTHER_FEATURE]->(of:OtherFeature)
							optional match (sp)-[tdr:HAS_3D_STRUCTURE]->(td:_3DStructure)
							optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)
							optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)-[rr:HAS_RELATIONSHIP]->(r:Relationship)
							delete aor,ao,or,o,rur,ru,htr,trace,sr,s,ofr,of,tdr,td,rr,r,ir,i,hir,img,orrr,dsetr,sp");

	}



	public function getImageInfo($image_id){
	
		$querystring = "MATCH (n:Image) WHERE n.id = $image_id and n.userpkey = $this->userpkey RETURN n limit 1;";
		$featuredata = $this->neodb->getNode($querystring);

		$data = new stdClass();
		$data->count = count($featuredata);
		$data->filename = $featuredata["filename"];
		$data->origfilename = $featuredata["origfilename"];
		$data->extension = end(explode(".",$featuredata["origfilename"]));
		
		return $data;
	}



	public function deleteImage($image_id){

		$querystring = "MATCH (n:Image) where (n.id = $image_id ) and n.userpkey = $this->userpkey optional match (a:Spot)-[b:HAS_IMAGE]-(n) DELETE b,n;";

		$this->neodb->query($querystring);
	
	}



	public function insertImage($post,$file){
	
		$imagefilename = $file['name'];
		$imagefiletype = $file['type'];
		$imagefiletmp_name = $file['tmp_name'];
		$imagefilesize = $file['size'];

		
		/*
		*******************************************************
		calculate sha1 hash for file comparison
		the strabospot app is unable to track file name, so we
		use sha1 hash to check for image existence
		** deprecated, but leaving in. JMA 02/23/2016
		*******************************************************
		*/
		$imagesha1 = sha1_file($imagefiletmp_name);

		$error="";
		
		$id=(int)$post['id'];

		if($imagefiletmp_name==""){
			$error.="No image file provided. ";
		}

		if($id==""){
			$error.="ID must be provided. ";
		}

        if(isset($request->url_elements[2])) {

			//error here
			// do nothing, this is not a supported action
			header("Bad Request", true, 400);
			$data["Error"] = $request->url_elements[3]." not supported.";

        }elseif($error!=""){

			//error here
			header("Bad Request", true, 400);
			$data["Error"] = $error;

        }else{

			//********************************************************************
			// check to see if image already exists
			//********************************************************************
			if($id!=""){
				$querystring = "MATCH (n:Image) WHERE n.id=$id and n.userpkey = $this->userpkey RETURN id(n);";
				$neoid = $this->neodb->get_var($querystring);
			}else{
				$neoid="";
			}

			if($neoid!=""){
				
				$querystring = "MATCH (n:Image) WHERE n.id=$id and n.userpkey = $this->userpkey RETURN n;";
				$body = $this->neodb->getNode($querystring);

				$filename = $body["filename"];
				if($filename==""){
					$filename = $this->db->get_var("SELECT nextval('image_seq');");
				}
	
				unset($injson);
				
				foreach($body as $key=>$value){
					if($value != ""){
						eval("\$injson['$key'] = \$value;");
						//echo "$key : $value \n";
					}
				}

				$injson['filename'] = $filename;
				$injson['origfilename'] = $imagefilename;
				$injson['userpkey']=$this->userpkey;
				$injson['imagesha1']=$imagesha1;
				$injson['id']=$id;

				$injson = json_encode($injson);

				$self = $this->neodb->updateNode($neoid,$injson,"Image");

				//********************************************************************
				// Now move image to folder
				//********************************************************************
				move_uploaded_file ( $imagefiletmp_name , "dbimages/$filename" );

				header("Image updated", true, 201);
				$data['self']="https://strabospot.org/db/image/$id";
				$data['id']=$id;

			}else{

				//image doesn't exist, create new image here
				//********************************************************************
				// OK, save image
				//********************************************************************
				$image_id = $this->db->get_var("SELECT nextval('image_seq');");
			
				$newfilename = $image_id;
	
				unset($injson);

				$injson['filename'] = $newfilename;
				$injson['origfilename'] = $imagefilename;
				$injson['userpkey']=$this->userpkey;
				$injson['imagesha1']=$imagesha1;
				$injson['id']=$id;

				$imagefiletype=explode("/",$imagefiletype);
				$imagefiletype=$imagefiletype[1];

				$injson=json_encode($injson);

				//********************************************************************
				// OK, we have some JSON formed and ready for Neo4j.
				// Let's POST to the REST API to create a node
				//********************************************************************
	
				$imageid = $this->neodb->createNode($injson,"Image");
				
				//********************************************************************
				// Now move image to folder
				//********************************************************************
				move_uploaded_file ( $imagefiletmp_name , "dbimages/$newfilename" );

				header("Image created", true, 201);
				$data['self']="https://strabospot.org/db/image/$id";
				$data['id']=$id;

			}

        }

        return $data;
	
	}



	public function getMyDatasets(){
	
		//get the feature from neo4j
		$querystring = "MATCH (d:Dataset) WHERE d.userpkey = $this->userpkey RETURN d;";
		$rows = $this->neodb->get_results($querystring);

		$count=count($rows);

		if($count > 0){

			$x=0;
			
			foreach($rows as $fd){
			
				$fd=$fd->get("d");
				$fd=(object)$fd->values();

				foreach($fd as $key=>$value){
					if($key!="userpkey" && $key!="datecreated"){
						$data['datasets'][$x][$key]=$value;
					}
				}
				
				$data['datasets'][$x]['self']="https://strabospot.org/db/dataset/".$data['datasets'][$x]['id'];

				$x++;
			}

		}
	
		return $data;
	
	}
	
	
	
	public function getMyProjects(){
	
		//get the feature from neo4j
		$querystring = "MATCH (n:Project) WHERE n.userpkey = $this->userpkey RETURN n;";
		$rows = $this->neodb->get_results($querystring);
	
		$count=count($rows);

		if($count > 0){

			$x=0;
			
			foreach($rows as $row){
			
				$fd=$row->get("n");
				$fd=(object)$fd->values();
				
				//print_r($fd);exit();

				$name=$fd->desc_project_name;
				$id=$fd->id;

				$projecttype = $fd->projecttype;

				$bigjson = $fd->jsonstring;
				$bigjson = json_decode($bigjson);
				
				$modified_timestamp = $bigjson->modified_timestamp;
				$date = $bigjson->date;
				
				$data['projects'][$x]['name']=$name;
				$data['projects'][$x]['id']=$id;

				if($modified_timestamp!=""){
					$data['projects'][$x]['modified_timestamp']=$modified_timestamp;
				}

				if($date!=""){
					$data['projects'][$x]['date']=$date;
				}

				$data['projects'][$x]['self']="https://strabospot.org/db/project/$id";
				$data['projects'][$x]['projecttype']=$projecttype;

				$x++;
			}

		}
		
		return $data;
	
	}
	
	
	
	public function getProfile(){

		//get the feature from neo4j
		$querystring = "MATCH (u:User) WHERE u.userpkey = $this->userpkey optional match (u)-[r:HAS_PROFILE]->(p:Profile) RETURN u,p;";
		$row = $this->neodb->getRecord($querystring);

		$u=$row->get("u")->values();
		

/*
(u
    [userpkey] => 3
    [firstname] => Jason
    [profileimage] => 51411780.jpg
    [email] => jasonash@ku.edu
    [lastname] => Ash
)
Array
(p
    [userpkey] => 3
    [profiletype] => app
    [datecreated] => 1473778089
    [mapbox_access_token] => lkajsdflkj
)
*/
		

		$data = new stdClass();
		
		$data->name=$u["firstname"]." ".$u["lastname"];

		foreach($u as $key=>$value){
		
			if(	$key != "userpkey" &&
				$key != "datecreated" &&
				$key != "firstname" &&
				$key != "lastname" &&
				$key != "profiletype" &&
				$key != "profileimage" &&
				$key != "id"
				){
								
				eval("\$data->$key=\$value;");

			}

		}

		if($row->get("p")!=""){
			$p=$row->get("p")->values();
			foreach($p as $key=>$value){
		
				if(	$key != "userpkey" &&
					$key != "datecreated" &&
					$key != "firstname" &&
					$key != "lastname" &&
					$key != "profiletype" 
					){
								
					eval("\$data->$key=\$value;");

				}
			}
		}

		return $data;
	
	}
	
	
	
	public function deleteProfile(){
	
		$querystring = "MATCH (u:User)-[r:HAS_PROFILE]->(p:Profile) WHERE u.userpkey = $this->userpkey delete r,p;";
		$this->neodb->query($querystring);
	
	}
	
	
	
	public function insertProfile($injson){

		$upload = json_decode($injson);

		//delete profile first
		$querystring = "match (u:User)-[r:HAS_PROFILE]->(p:Profile) where u.userpkey=$this->userpkey  DELETE r,p;";
		$this->neodb->query($querystring);

		//put in profile
		foreach($upload as $key=>$value){
			if($key!="id" && $key!="featureid" && $key!="self"){
				eval("\$$key=\$value;");
			}
		}

		//********************************************************************
		//need to add some error checking here
		//********************************************************************

		unset($injson);

		$newupload['name']=$strabousername;

		foreach($upload as $key=>$value){
			if($key!="id" && $key!="featureid" && $key!="self"){
				if(is_array($value)){
					$value=json_encode($value);
				}
				eval("\$injson['$key']=\$value;");
				eval("\$newupload['$key']=\$value;");

				//echo("\$injson['$key']=$value;\n");
			}
		}
		
		unset($upload->id);
		unset($upload->featureid);
		unset($upload->self);

		$injson['userpkey']=$this->userpkey;
		
		$datecreated=time();
		
		$injson['datecreated']=$datecreated;
		
		$injson['profiletype']="app";

		$injson=json_encode($injson);

		//********************************************************************
		// OK, we have some JSON formed and ready for Neo4j.
		// Let's POST to the REST API to create a node
		//********************************************************************

		$profileid = $this->neodb->createNode($injson,"Profile");

		$userid = $this->neodb->get_var("match (u:User) where u.userpkey=$this->userpkey return id(u)");

		$this->neodb->addRelationship($userid, $profileid, "HAS_PROFILE","User","Profile");

		$data=$upload;
		
		return $data;
	
	}



	public function getProfileImageName(){
	
		$imagename = $this->neodb->get_var("match (a:User) where a.userpkey = $this->userpkey return a.profileimage");
		
		return $imagename;
	
	}



	public function deleteProfileImage(){
	
		$filename=$this->neodb->get_var("match (a:User) where a.userpkey = $this->userpkey return a.profileimage");
		
		
		if($filename!=""){
			
			unlink("profileimages/$filename");
		
			//delete image here
			$this->neodb->get_var("match (a:User) where a.userpkey = $this->userpkey remove a.profileimage");
		
		}
	
	}



	public function insertProfileImage($post,$file){

		$imagefilename = $file['name'];
		$imagefiletype = $file['type'];
		$imagefiletmp_name = $file['tmp_name'];
		$imagefilesize = $file['size'];
		$extension = end(explode(".",$imagefilename));

		$error="";

		$feature_id=(int)$post['feature_id'];
		$image_type=$post['image_type'];

		
		if($imagefiletmp_name==""){
			$error.="No image file provided. ";
		}

        if($error!=""){

			//error here
			$data["Error"] = $error;

        }else{

			//*******************************************************************
			// OK, save image
			//*******************************************************************
			
			//first, delete image if it exists
			$filename=$this->neodb->get_var("match (a:User) where a.userpkey = $this->userpkey return a.profileimage");

			if($filename!=""){

				if(file_exists("profileimages/$filename")){
					unlink("profileimages/$filename");
				}
			}
		
			$image_id = rand(11111111,99999999);
		
			$newfilename = $image_id.".".$extension;
			
			move_uploaded_file ( $imagefiletmp_name , "/opt/www/profileimages/$newfilename" );
			
			//update db here
			$filename=$this->neodb->query("match (a:User) where a.userpkey = $this->userpkey set a.profileimage='$newfilename'");

        
        }

        return $data;

	}


	public function projectExists($projectid){
		$count = $this->neodb->get_var("match (p:Project {id:$projectid,userpkey:$this->userpkey}) return count(p)");
		if($count>0){
			return true;
		}else{
			return false;
		}
	}


	public function deleteProject($project_id){
	
		//********************************************************************
		// check for feature with userid and id
		//********************************************************************

		//first, create version
		if($this->projectExists($project_id)){
			$this->createVersion($project_id);
		}

		//then, delete images;

		$rows=$this->neodb->query("match (p:Project)
						where p.userpkey=$this->userpkey and p.id=$project_id
						MATCH (p)-[hdr:HAS_DATASET]-(d)
						match (d)-[dsetr:HAS_SPOT]->(sp:Spot)
						match (sp)-[hir:HAS_IMAGE]->(img:Image)
						return img;

						");

		if(count($rows)>0){
			foreach($rows as $row){
				$image=$row->get("img");
				$image=(object)$image->values();
				$filename=$image->filename;
				//unlink("/var/www/dbimages/$filename");
			}
		}

		//now, delete nodes and relationships
		// try this instead maybe? MATCH (p:Project {id:14725081177733})-[*0..]->(x) DETACH DELETE x,p
		
		$this->neodb->query("match ()-[r:IS_RELATED_TO {projectid:$project_id,userpkey:$this->userpkey}]-() delete r;");
		
		$this->neodb->query("match ()-[rr:IS_TAGGED {projectid:$project_id,userpkey:$this->userpkey}]-() delete rr;");
		
		$this->neodb->query("match (p:Project)
						where p.userpkey=$this->userpkey and p.id=$project_id
						optional match (u)-[ur:HAS_PROJECT]->(p)
						optional match (p)-[pr]->(pof:Relationship) 
						optional match (p)-[prr]->(pru:RockUnit)
						optional match (p)-[rt]->(t:Tag)
						OPTIONAL MATCH (p)-[hdr:HAS_DATASET]-(d)
						optional match (d)-[dsetr:HAS_SPOT]->(sp:Spot)
						optional match (spat)-[orrr:RTREE_REFERENCE]->(sp)
						optional match (sp)-[hir:HAS_IMAGE]->(img:Image)
						optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)
						optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)-[aor:HAS_ASSOCIATED_ORIENTATION]->(ao:Orientation)
						optional match (sp)-[rur:HAS_ROCK_UNIT]->(ru:RockUnit)
						optional match (sp)-[htr:HAS_TRACE]->(trace:Trace)
						optional match (sp)-[sr:HAS_SAMPLE]->(s:Sample)
						optional match (sp)-[ofr:HAS_OTHER_FEATURE]->(of:OtherFeature)
						optional match (sp)-[tdr:HAS_3D_STRUCTURE]->(td:_3DStructure)
						optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)
						optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)-[rr:HAS_RELATIONSHIP]->(r:Relationship)
						delete rr,r,ir,i,tdr,td,ofr,of,sr,s,htr,trace,rur,ru,aor,ao,or,o,hir,img,orrr,dsetr,sp,hdr,d,rt,t,prr,pru,pr,pof,ur,p
						
						
						
						
						");
	
	}

/*
match (p:Project)
						where p.userpkey=$this->userpkey and p.id=$project_id
						optional match (u:User)-[ur:HAS_PROJECT]->(p:Project)
						optional match (p:Project)-[pr:HAS_OTHER_FEATURE]->(pof:OtherFeature) 
						optional match (p:Project)-[prr:HAS_ROCK_UNIT]->(pru:RockUnit)
						optional match (p:Project)-[rt:HAS_TAG]->(t:Tag)
						OPTIONAL MATCH (p:Project)-[phd:HAS_DATASET]->(d:Dataset)

						delete phd,rt,t,prr,pru,pr,pof,ur,p



match (p:Project)
						where p.userpkey=$this->userpkey and p.id=$project_id
						optional match (u)-[ur:HAS_PROJECT]->(p)
						optional match (p)-[pr]->(pof:OtherFeature) 
						optional match (p)-[prr]->(pru:RockUnit)
						optional match (p)-[rt]->(t:Tag)
						OPTIONAL MATCH (p)-[hdrr:HAS_DATASET]-(d)
						optional match (d)-[dsetr:HAS_SPOT]->(sp:Spot)
						optional match (spat)-[orrr:RTREE_REFERENCE]->(sp)
						optional match (sp)-[hir:HAS_IMAGE]->(img:Image)
						optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)
						optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)-[aor:HAS_ASSOCIATED_ORIENTATION]->(ao:Orientation)
						optional match (sp)-[rur:HAS_ROCK_UNIT]->(ru:RockUnit)
						optional match (sp)-[htr:HAS_TRACE]->(trace:Trace)
						optional match (sp)-[sr:HAS_SAMPLE]->(s:Sample)
						optional match (sp)-[ofr:HAS_OTHER_FEATURE]->(of:OtherFeature)
						optional match (sp)-[tdr:HAS_3D_STRUCTURE]->(td:_3DStructure)
						optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)
						optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)-[rr:HAS_RELATIONSHIP]->(r:Relationship)
						delete rr,r,ir,i,tdr,td,ofr,of,sr,s,htr,trace,rur,ru,aor,ao,or,o,hir,img,orrr,dsetr,sp,hdr,d,rt,t,prr,pru,pr,pof,ur,p
						
						
						
*/

	public function insertProject($injson,$thisid=null){

		if($thisid!=""){
			$thisid = (int) $thisid;
		}

		$upload = json_decode($injson);
		
		$dbaction="new";
	
		foreach($upload as $key=>$value){
			if($key=="id"){
				$thisid= (int) $value;
			}
		}


		foreach($upload as $key=>$value){
			if($key=="modified_timestamp"){
				$incoming_timestamp=(int)$value;
			}
		}

		if($thisid==""){
			foreach($upload as $key=>$value){
				if($key=="self"){
					$thisid = (int) end(explode("/",$value));
				}
			}
			
		}
		
		if($thisid != ""){
			
			$record=$this->neodb->getRecord("match (p:Project {id:$thisid, userpkey:$this->userpkey}) return id(p) as id,p");
			if($record){
				$projectid=$record->get("id");
				$server_timestamp = $record->get("p")->values()["modified_timestamp"];
				//echo "server_timestamp: $server_timestamp incoming_timestamp:$incoming_timestamp";exit();
				
				if($incoming_timestamp > $server_timestamp){
					$dbaction="update";
				}else{
					$dbaction="donothing";
				}
				
				//echo "server_timestamp: $server_timestamp incoming_timestamp: $incoming_timestamp dbaction: $dbaction";exit();
			}
		}

		//exit();

		//echo "dbaction: $dbaction thisid: $thisid projectid: $projectid";exit();

		//create json here once for insertion later
		//MATCH (n:Project) WHERE (n.id = 14725081177733 or n.id = 14725081177733) and n.userpkey = 3 RETURN count(*);
		
		//print_r($upload);

		$newproject = array();
		
		// first the spot-level items
		// ************************************************************
		$newproject["id"]=$thisid;
		$newproject['userpkey']=$this->userpkey;
		if($upload->modified_timestamp!=""){ $newproject["modified_timestamp"] = (int) $upload->modified_timestamp; }
		if($upload->date!=""){ $newproject["date"]=$upload->date; }
		
		if($upload->preferences!=""){ $newproject["preferences"]=json_encode($upload->preferences); }

		if($upload->preferences->public){
			$newproject['public']=true;
		}
		
		if($upload->tools!=""){ $newproject["tools"]=json_encode($upload->tools); }
		
		if(count($upload->description)>0){
			foreach($upload->description as $key=>$value){
				if(is_array($value)||is_object($value)){
					$value = json_encode($value);
				}
				
				$newproject["desc_".$key]=$value;
			}
		}
		

		//also look for other_maps here.
		if($upload->other_maps!=""){ $newproject["other_maps"]=json_encode($upload->other_maps); }
		

		//also create json_ entries for the higher=level items.
		if($upload->description!=""){ $newproject["json_description"]=json_encode($upload->description); }
		if($upload->other_features!=""){ $newproject["json_other_features"]=json_encode($upload->other_features); }
		if($upload->rock_units!=""){ $newproject["json_rock_units"]=json_encode($upload->rock_units); }
		if($upload->tags!=""){ $newproject["json_tags"]=json_encode($upload->tags); }
		if($upload->relationships!=""){ $newproject["json_relationships"]=json_encode($upload->relationships); }

		
		// now other_features - don't put in separate nodes? 09-20-2016
		// ************************************************************
		/*
		if(count($upload->other_features)>0){
			$otherfeatures=array();
			foreach($upload->other_features as $of){
				$otherfeatures[]["feature_name"]=$of;
			}
		}
		*/

		// now rock_units
		// ************************************************************
		if(count($upload->rock_units)>0){
			$rockunits=array();
			$runum=0;
			foreach($upload->rock_units as $ru){
				$rockunits[$runum]["userpkey"]=$this->userpkey;
				foreach($ru as $key=>$value){
					if(is_array($value)||is_object($value)){ $value = json_encode($value); }
					if($key=="id"){
						$rockunits[$runum][$key]=(int)$value;
					}else{
						if($value!=""){
							$rockunits[$runum][$key]=$value;
						}
					}
				}
				$runum++;
			}
		}

		// now tags
		// ************************************************************
		if(count($upload->tags)>0){
			$tags=array();
			$tagnum=0;
			foreach($upload->tags as $t){
				$tags[$tagnum]["userpkey"]=$this->userpkey;
				foreach($t as $key=>$value){
					if(is_array($value)||is_object($value)){ $value = json_encode($value); }
					if($key=="id"){
						$tags[$tagnum][$key]=(int)$value;
					}else{
						if($value!=""){
							$tags[$tagnum][$key]=$value;
						}
					}
				}
				$tagnum++;
			}
		}


		// now relationships
		// ************************************************************
		if(count($upload->relationships)>0){
			$relationships=array();
			$relnum=0;
			foreach($upload->relationships as $rel){

				$relationships[$relnum]["userpkey"]=$this->userpkey;
				foreach($rel as $key=>$value){
					if(is_array($value)||is_object($value)){ $value = json_encode($value); }
					if($key=="id"){
						$relationships[$relnum][$key]=(int)$value;
					}else{
						if($value!=""){
							$relationships[$relnum][$key]=$value;
						}
					}
				}
				$relnum++;
			}
		}

		$newproject['projecttype']="app";
		$newproject['uploaddate']=time();

		$newprojectjson = json_encode($newproject);
		
		//echo $newprojectjson;exit();

		if($dbaction=="new"){

			$this->logToFile("New Project");
			
			//********************************************************************
			// create new project node, and then add other nodes
			//********************************************************************
			$projectid = $this->neodb->createNode($newprojectjson,"Project");
			$userid = $this->neodb->get_var("match (a:User) where a.userpkey=".$this->userpkey." return id(a)");
			$this->neodb->addRelationship($userid, $projectid, "HAS_PROJECT","User","Project");
		
		}elseif($dbaction=="update"){
		
			/*
			if($uuid = $this->createVersion($thisid)){
				$this->logToFile("Version created: $uuid.");
			}else{
				$this->logToFile("Version creation failed.");
			}
			*/
			
			$this->logToFile("Update Project");

			//********************************************************************
			// existing project was found, update here
			// remove relationships and other nodes for insertion afterwards
			// we don't want to delete the node, because it might have spots
			// associated with it.
			//********************************************************************

			$projectstarttime=microtime(true);

			$this->deleteProjectReltationships($thisid);

			$this->neodb->query("match (a:Project)-[r]-(of:Relationship) where id(a)=$projectid and a.userpkey=$this->userpkey delete r,of;");
			$this->neodb->query("match (a:Project)-[rr]-(ru:RockUnit) where id(a)=$projectid and a.userpkey=$this->userpkey delete rr,ru;");
			$this->neodb->query("match (a:Project)-[rt]-(t:Tag) where id(a)=$projectid and a.userpkey=$this->userpkey delete rt,t;");
						
			$this->neodb->updateNode($projectid,$newprojectjson,"Project");

			$totalprojecttime = microtime(true)-$projectstarttime;
			$this->logToFile("Update project took: ".$totalprojecttime." secs","Project Time");

		}

		$projectstarttime=microtime(true);

		if($dbaction=="new" || $dbaction=="update"){
			//put in other features - don't put in separate nodes? 09-20-2016
			/*
			if($otherfeatures){
				foreach($otherfeatures as $of){
					$json = json_encode($of);
					$ofid = $this->neodb->createNode($json,"OtherFeature");
					$this->neodb->addRelationship($projectid, $ofid, "HAS_OTHER_FEATURE");
					//echo "otherid: $ofid\n";
				}
			}
			*/

			//put in rockunits
			if($rockunits){
				foreach($rockunits as $ru){
					$json = json_encode($ru);
					$ruid = $this->neodb->createNode($json,"RockUnit");
					$this->neodb->addRelationship($projectid, $ruid, "HAS_ROCK_UNIT","Project","RockUnit");
					//echo "ruid: $ruid\n";
				}
			}

			//put in tags
			if($tags){
				foreach($tags as $tag){
					$json = json_encode($tag);
					$tagid = $this->neodb->createNode($json,"Tag");
					$this->neodb->addRelationship($projectid, $tagid, "HAS_TAG","Project","Tag"); //was HAS_TAG (10-28-2016 JMA)
					//echo "tagid: $tagid\n";
				}
			}

			//put in relationships
			if($relationships){
				foreach($relationships as $relationship){
					$json = json_encode($relationship);
					$relationshipid = $this->neodb->createNode($json,"Relationship");
					$this->neodb->addRelationship($projectid, $relationshipid, "HAS_RELATIONSHIP","Project","Relationship");
					//echo "tagid: $tagid\n";
				}
			}
		}
		
		$totalprojecttime = microtime(true)-$projectstarttime;
		$this->logToFile("rockunits, tags, and relationships took: ".$totalprojecttime." secs","Project Time");


		$projectstarttime=microtime(true);

		if($dbaction=="update"){
			$this->buildProjectRelationships($thisid);
		}

		$totalprojecttime = microtime(true)-$projectstarttime;
		$this->logToFile("buildprojectrelationships took: ".$totalprojecttime." secs","Project Time");



		$upload->id=$thisid;
		$upload->self="https://strabospot.org/db/project/$thisid";
		$data=$upload;

		return $data;
	
	}
	
	public function getProject($feature_id){

		if($this->userpkey!=99999){
			$userpkeystring=" and n.userpkey = $this->userpkey";
		}

		$data = new stdClass();
		
		//get the feature from neo4j
		$querystring = "MATCH (n:Project) WHERE n.id = $feature_id".$userpkeystring." RETURN n;";

		$records = $this->neodb->query($querystring);

		//print_r($records);exit();
		
		$count=count($records);
		
		if($count > 0){
			$record = $records[0];
			
			$project = $record->get("n");
			$properties = $project->values();
			
			//print_r($properties);exit();
			
			foreach($properties as $key=>$value){
				if( substr($key,0,5)!="desc_" && $key!="userpkey" && $key!="id" && $key!="public" 
					&& $key!="json_rock_units" && $key!="json_tags" && $key!="json_other_features" && $key!="json_description" && $key!="json_relationships" 
					&& $key!="preferences" && $key!="tools" && $key!="uploaddate" && $key!="other_maps" && $key!="centroid"
					){
					$data->$key=$value;
					//$data->$key=json_decode($value);
				}
			}
			
			if($properties["preferences"]!=""){$data->preferences=json_decode($properties["preferences"]);}
			if($properties["tools"]!=""){$data->tools=json_decode($properties["tools"]);}
			
			if($properties["other_maps"]!=""){$data->other_maps=json_decode($properties["other_maps"]);}
			if($properties["json_rock_units"]!=""){$data->rock_units=json_decode($properties["json_rock_units"]);}
			if($properties["json_tags"]!=""){$data->tags=json_decode($properties["json_tags"]);}
			if($properties["json_other_features"]!=""){$data->other_features=json_decode($properties["json_other_features"]);}
			if($properties["json_description"]!=""){$data->description=json_decode($properties["json_description"]);}
			if($properties["json_relationships"]!=""){$data->relationships=json_decode($properties["json_relationships"]);}

			$data->id = (int) $feature_id;;

			$data->self="https://strabospot.org/db/project/$feature_id";
			
			//print_r($data);exit();
			
		}else{
			//Error, sample not found
			
			$data->Error = "Project $feature_id not found.";
		}

		return $data;
	
	}
	
	public function getTagsFromDatasetIds($feature_ids){

		$tags = [];

		$data = new stdClass();
		
		//get the feature from neo4j
		$querystring = "MATCH (n:Project)-[HAS_DATASET]->(d:Dataset) WHERE d.id in [$feature_ids] RETURN distinct(n);";

		$records = $this->neodb->query($querystring);

		//$this->dumpVar($records);exit();
		
		$count=count($records);
		
		if($count > 0){

			foreach($records as $record){
			
				$project = $record->get("n");
				$properties = $project->values();

				if($properties["json_tags"]!=""){
					$thistags=json_decode($properties["json_tags"]);
					foreach($thistags as $thistag){
						array_push($tags,$thistag);
					}
				}
				
			}
		}

		if(count($tags)==0) $tags="";
		
		return $tags;
	
	}



	public function getProjectDatasets($feature_id){

		//get the feature from neo4j
		$querystring = "match (p:Project)-[r:HAS_DATASET]->(d:Dataset) where p.userpkey=$this->userpkey and p.id=$feature_id RETURN d;";
		$featuredata = $this->neodb->get_results($querystring);

		$count=count($featuredata);
	
		if($count > 0){
		
			$x=0;
			
			foreach($featuredata as $fd){
			
				$fd=$fd->get("d")->values();
				
				$data['datasets'][$x]=$this->singleDatasetJSON($fd);

				$x++;
			}

		}
	
		return $data;

	}
	
	
	
	public function findProject($feature_id){
	
		$querystring = "MATCH (n:Project) WHERE n.id = $feature_id and n.userpkey = $this->userpkey RETURN id(n);";

		$projectid = $this->neodb->get_var($querystring);

		if($projectid != ""){
			return $projectid;
		}else{
			return false;
		}
	
	}
	
	
	
	public function singleProjectJSON($fd){
	
		$name=$fd->name;
		$id=$fd->id;

		$datecreated = date("F j, Y, g:i a T P", $fd->datecreated);
		$datasettype = $fd->datasettype;

		$data['name']=$name;
		$data['datecreated']=$datecreated;
		$data['self']="https://strabospot.org/db/dataset/$id";
		$data['datasettype']=$datasettype;
		
		return $data;
	
	}
	
	

	public function findDatasetInProject($projectid,$datasetid){

		$querystring = "match (a:Project)-[r:HAS_DATASET]->(b) where a.userpkey=$this->userpkey and a.id=$projectid and b.id=$datasetid return count(*);";
		$count = $this->neodb->get_var($querystring);

		if($count == 0){
			return false;
		}else{
			return true;
		}

	}



	public function addDatasetToProject($from,$to,$relationshiptype){
	
		$from = $this->straboIDToID($from,"Project");
		$to = $this->straboIDToID($to,"Dataset");
		$self = $this->neodb->addRelationship($from,$to,$relationshiptype,"Project","Dataset");
		return $self;	
	
	}



	public function deleteProjectDatasets($project_id){
	
		//need to delete dataset and all spots beneath it too
		//$querystring = "match (p:Project)-[r:HAS_DATASET]->(d) where p.userpkey=$this->userpkey and (p.id=$project_id) DELETE r,d;";
		//$body = $this->neodb->query($querystring);
		//return $body;

		$this->neodb->query("optional match ()-[r:IS_RELATED_TO {projectid:$project_id,userpkey:$this->userpkey}]-(), ()-[rr:IS_TAGGED {projectid:$project_id,userpkey:$this->userpkey}]-() delete r,rr;");
		$this->neodb->query("match (p:Project)
						where p.userpkey=$this->userpkey and p.id=$project_id
						optional match (u)-[ur:HAS_PROJECT]->(p)
						optional match (p)-[pr]->(pof:Relationship) 
						optional match (p)-[prr]->(pru:RockUnit)
						optional match (p)-[rt]->(t:Tag)
						OPTIONAL MATCH (p)-[hdr:HAS_DATASET]-(d)
						optional match (d)-[dsetr:HAS_SPOT]->(sp:Spot)
						optional match (spat)-[orrr:RTREE_REFERENCE]->(sp)
						optional match (sp)-[hir:HAS_IMAGE]->(img:Image)
						optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)
						optional match (sp)-[or:HAS_ORIENTATION]->(o:Orientation)-[aor:HAS_ASSOCIATED_ORIENTATION]->(ao:Orientation)
						optional match (sp)-[rur:HAS_ROCK_UNIT]->(ru:RockUnit)
						optional match (sp)-[htr:HAS_TRACE]->(trace:Trace)
						optional match (sp)-[sr:HAS_SAMPLE]->(s:Sample)
						optional match (sp)-[ofr:HAS_OTHER_FEATURE]->(of:OtherFeature)
						optional match (sp)-[tdr:HAS_3D_STRUCTURE]->(td:_3DStructure)
						optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)
						optional match (sp)-[ir:HAS_INFERENCE]->(i:Inference)-[rr:HAS_RELATIONSHIP]->(r:Relationship)
						delete rr,r,ir,i,tdr,td,ofr,of,sr,s,htr,trace,rur,ru,aor,ao,or,o,hir,img,orrr,dsetr,sp,hdr,d
						
						
						
						
						");

	}



	public function getPolygonSpots($polygon){

		//get the features from neo4j
		$querystring = "START n = node:strabospot(\\\"withinWKTGeometry:POLYGON(($polygon))\\\") MATCH (n:Feature) return n limit 10;";
		$json = $this->getFeatureCollection($querystring);
		return $json;

	}

	
	
	public function fixGeometry($image_id,$data){
	
		//this is a recursive function to find the root geometry of a spot
		
		//find image with id
		$features = $data['features'];
		foreach($features as $f){
			
			foreach($f['properties']['images'] as $i){
				if($i['id']==$image_id){
					$geometry = $f['geometry'];
					$image_basemap = $f['properties']['image_basemap'];
				}
			}
		}
		
		if($image_basemap==""){
			return $geometry;
		}else{
			return $this->fixGeometry($image_basemap,$data);
		}
	
	}



	public function fixJSONCoords($data){
		
		//This function accepts a featureclass data object and returns a data object with
		//fixed WKT elements, with non-pixel coordinates.
		
		if($data!=""){
			$newdata = array();
			$newdata['type']="FeatureCollection";
			$newdata['features']=array();
		
			foreach($data['features'] as $f){
		
				if($f['properties']['image_basemap']!=""){
			
					$f['geometry']=$this->fixGeometry($f['properties']['image_basemap'],$data);
			
				}
			
				$newdata['features'][]=$f;
		
			}
		

			return $newdata;
		}
	
	}


	public function findImageFile($feature_id){
	
		$querystring = "MATCH (n:Image) WHERE n.id = $feature_id and n.userpkey = $this->userpkey RETURN n.filename;";
		
		$filename = $this->neodb->get_var($querystring);

		if($filename!="" && file_exists("dbimages/$filename")){
			return true;
		}else{
			return false;
		}
	
	}


	public function getProjectImages($projectid){
		if($projectid){
			$images = $this->neodb->get_results("match (p:Project {id:$projectid,userpkey:$this->userpkey})-[:HAS_DATASET]->(d:Dataset)-[r:HAS_SPOT]->(s:Spot)-[:HAS_IMAGE]->(i:Image) return i;");
		}
		
		return $images;
	}


	public function switchVersion($uuid){ //switches project to saved version
		
		if(file_exists("/var/www/versions/$uuid")){
			
			$row=$this->db->get_row("select * from versions where uuid='$uuid' and userpkey=$this->userpkey");
			$projectid = $row->projectid;
			
			if($projectid!=""){
			
				//log usage here
				$this->db->query("	insert into verlog (userpkey, dateactivated, projectid, uuid) 
												values ($this->userpkey, now(), '$projectid', '$uuid')
								");

				//first, delete project
				$this->deleteProject($projectid);
			
				$json = file_get_contents("/var/www/versions/$uuid");
				$json = gzdecode($json);
				$project = json_decode($json);
			
				$datasets = $project->versiondatasets;
			
				unset($project->versiondatasets);
				unset($project->self);
			
				//insert project here
				$projectjson = json_encode($project);
				$pdata = $this->insertProject($projectjson);

				foreach($datasets as $dataset){
				
					$spots = $dataset->versionspots->features;

					$spots=$this->fixIncomingBasemaps($spots);

					unset($dataset->versionspots);
					unset($dataset->self);
					$datasetid=$dataset->id;
					$datasetjson = json_encode($dataset);

					//insert dataset here
					$datasetdata = $this->insertDataset($datasetjson);

					$this->addDatasetToProject($projectid,$datasetid,"HAS_DATASET");

					//loop over spots and put in images first, then spot
					foreach($spots as $spot){
				
						//$this->dumpVar($spot);exit();
						
						unset($spot->properties->self);
					
						if($spot->properties->images){
							foreach($spot->properties->images as $i){
							
								unset($i->self);
							
								$imageid = $i->id;
							
								$filename = $this->db->get_var("select nextval('image_seq')");
								$imagesha1 = sha1_file("/var/www/versions/images/$imageid");
							
								$i->userpkey=$this->userpkey;
								$i->origfilename = "image.jpeg";
								$i->imagesha1 = $imagesha1;
								$i->filename = "$filename";

								$imagejson=json_encode($i);
								$newid = $this->neodb->createNode($imagejson,"Image");
								copy("/var/www/versions/images/$imageid","/var/www/dbimages/$filename");

							}
						}
						
						$spotid = $spot->properties->id;

						$spotjson = json_encode($spot);
						$this->insertSpot($spotjson);

						$this->addSpotToDataset($datasetid,$spotid);
						
						//$this->dumpVar($spot);exit();
				
					}
					
					$this->buildDatasetRelationships($datasetid);	
					$this->setDatasetCenter($datasetid);
					$this->setProjectCenter($projectid);
				}
				
			}else{
				return false;
			}
			
		}else{
			return false;
		}
		
		return true;
	
	}


	public function createVersion($projectid){ //creates a version of a project for backup purposes
	
		$project = $this->getProject($projectid);
		
		$projectname = pg_escape_string($project->description->project_name);
		
		$spotcount = 0;

		if($project){
		
			//first, copy over images
			$images = $this->getProjectImages($projectid);

			if(count($images)>0){
				foreach ($images as $image){
					$i=$image->get("i");
					$i=(object)$i->values();
					
					$id = $i->id;
					$filename = $i->filename;

					if(!file_exists("/var/www/versions/images/$id")){
						copy("/var/www/dbimages/$filename","/var/www/versions/images/$id");
					}
				}
			}

			$datasetnum=0;
			$datasets = $this->getProjectDatasets($projectid);
			$datasets = $datasets['datasets'];
		
			$datasetcount=count($datasets);
			
			foreach($datasets as $d){
			
				$spots = $this->getDatasetSpots($d['id']);

				$spotcount=$spotcount+count($spots['features']);
				
				if($spots){
					$datasets[$datasetnum]["versionspots"]=$spots;
				}
			
				$datasetnum++;

			}
		
			$project->versiondatasets=$datasets;
			
			$project = json_encode($project,JSON_PRETTY_PRINT);

			$project = gzencode($project);
		
			$uuid = $this->uuid->v4();
			
			file_put_contents ("/var/www/versions/$uuid", $project);
			
			$this->db->query("	insert into versions (	projectid,
														datecreated,
														uuid,
														userpkey,
														projectname,
														spotcount,
														datasetcount
													)values(
														'$projectid',
														now(),
														'$uuid',
														$this->userpkey,
														'$projectname',
														$spotcount,
														$datasetcount
													)
			");
		
			return $uuid;
		
		}else{
		
			return false;
		
		}
	
	}

	public function setProjectCenter($projectid){
		//gathers geometries for project and calculates center

		$pointarray = array();
		
		$rows = $this->neodb->get_results("match (p:Project {id:$projectid, userpkey:$this->userpkey})-[HAS_DATASET]->(d:Dataset)-[HAS_SPOT]->(s:Spot) return s");
		
		foreach($rows as $row){

			$s=$row->get("s");
			$s=$s->values();

			$wkt = $s['wkt'];
			$strat_section_id = $s['strat_section_id'];

			if($wkt!="" && $strat_section_id==""){

				$mywkt = geoPHP::load($wkt,"wkt");
				$centroid = $mywkt->centroid();
				$out = $centroid->out("wkt");
				$out = str_replace("POINT ","",$out);
				$pointarray[]=$out;
			
			}
		
		}
	
		$allpoints = implode(", ",$pointarray);
		
		if($allpoints!=""){
		
			$allpoints = "MultiPoint($allpoints)";

			$mywkt = geoPHP::load($allpoints,"wkt");
			$centroid = $mywkt->centroid();
			$center = $centroid->out("wkt");

			
			//echo "center: $center";exit();
			
			
			if($center!=""){
				$this->neodb->query("match (p:Project {id:$projectid, userpkey:$this->userpkey}) set p.centroid='$center'");
			}
		
		}
		
		
	
	}


	public function setDatasetCenter($datasetid){
		
		$pointarray = array();
		
		$rows = $this->neodb->get_results("match (d:Dataset {id:$datasetid, userpkey:$this->userpkey})-[HAS_SPOT]->(s:Spot) return s");
		
		foreach($rows as $row){

			$s=$row->get("s");
			$s=$s->values();

			$wkt = $s['wkt'];
			$strat_section_id = $s['strat_section_id'];

			if($wkt!="" && $strat_section_id==""){

				$mywkt = geoPHP::load($wkt,"wkt");
				$centroid = $mywkt->centroid();
				$out = $centroid->out("wkt");
				$out = str_replace("POINT ","",$out);
				$pointarray[]=$out;
			
			}
		
		}

		$allpoints = implode(", ",$pointarray);
		
		if($allpoints!=""){
		
			$allpoints = "MultiPoint($allpoints)";

			$mywkt = geoPHP::load($allpoints,"wkt");
			$centroid = $mywkt->centroid();
			$center = $centroid->out("wkt");

			//echo "center: $center";exit();

			if($center!=""){
				$this->neodb->query("match (d:Dataset {id:$datasetid, userpkey:$this->userpkey}) set d.centroid='$center'");
			}
		
		}
	
	}

}