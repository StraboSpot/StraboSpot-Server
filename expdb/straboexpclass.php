<?php
/**
 * File: straboexpclass.php
 * Description: StraboExp class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class StraboExp
{

	 public function StraboExp($neodb,$userpkey,$db){
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

	 public function setadminkeys($admin_pkeys){
		 $this->admin_pkeys=$admin_pkeys;
	 }

	public function logToFile($var,$label=null){
		if(is_writable("/srv/app/www/expdb/uploadLog.txt")){
			if($label==""){$label="LogToFile";}
			$out = print_r($var, true);
			file_put_contents ("/srv/app/www/expdb/uploadLog.txt", "\n\n$label\n$out \n", FILE_APPEND);
		}
	}

	public function doTest(){
		echo "Hello World!";exit();
	}

	public function getRandString(){
		$returnString = "";
		$chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 't', 'u', 'v', 'w', 'x', 'y', 'z', '2', '3', '4', '6', '7', '8', '9');
		for($x=0; $x<6; $x++){
			$returnString .= $chars[rand(0,count($chars)-1)];
		}

		$count = $this->db->get_var("select count(*) from micro_projectmetadata where sharekey = '$returnString'");

		if($count == 0){
			return $returnString;
		}else{
			return $this->getRandString();
		}
	}

	public function dumpVar($var){
		echo "<pre>";
		print_r($var);
		echo "<pre>";
	}

	function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	public function createId(){
		$time = time();
		$extra = rand(1111,9999);
		$id = (int) $time.$extra;
		return $id;
	}

	public function fixFileName($string){
		$string = str_replace( array( '\'', '"', ',' , ';', '<', '>' ), '', $string);
		$string = str_replace(" ", "_", $string);
		return $string;
	}

	public function insertApparatus($json, $facility_pkey, $files, $apparatus_pkey=""){

		$app = $json->apparatus;
		$name = pg_escape_string($app->name);
		$type = pg_escape_string($app->type);
		$other_type = pg_escape_string($app->other_type);
		$location = pg_escape_string($app->location);
		$id = pg_escape_string($app->id);
		$description = pg_escape_string($app->description);
		if($app->features != ""){
			$features = pg_escape_string(implode("; ", $app->features));
		}
		$parameters = $app->parameters;
		$documents = $app->documents;

		if($apparatus_pkey != ""){
			//need to update apparatus here

			//check to see if this is a valid apparatus
			if(!in_array($this->userpkey, $this->admin_pkeys)){
				$row = $this->db->get_row("select * from apprepo.apparatus where pkey = $apparatus_pkey and facility_pkey in (select facility_pkey from apprepo.facility_users where users_pkey = $this->userpkey)");
			}else{
				$row = $this->db->get_row("select * from apprepo.apparatus where pkey = $apparatus_pkey");
			}

			if($row->pkey == ""){
				//error
				$data = new stdClass();
				$data->Error = "Apparatus not found: $apparatus_pkey";
				return $data;
			}else{
				//OK, update here
				$modified_timestamp = time();
				$created_timestamp = (int) $row->created_timestamp;

				//create json for storage
				$storejson = $app;
				$storejson->uuid = $row->uuid;
				$storejson->created_timestamp = (int) $row->created_timestamp;
				$storejson->modified_timestamp = (int) $modified_timestamp;
				$storejson = pg_escape_string(json_encode($storejson, JSON_PRETTY_PRINT));

				//update query here for apparatus

				$this->db->query("
					update apprepo.apparatus set
						created_timestamp = $created_timestamp,
						modified_timestamp = $modified_timestamp,
						name = '$name',
						type = '$type',
						other_type = '$other_type',
						location = '$location',
						apparatus_id = '$id',
						description = '$description',
						json = '$storejson'
					where pkey = $apparatus_pkey;
				");

				//remove paramters and docs and re-enter
				$this->db->query("delete from apprepo.apparatus_parameter where apparatus_pkey = $apparatus_pkey");
				$this->db->query("delete from apprepo.apparatus_document where apparatus_pkey = $apparatus_pkey");

				//Put in parameters
				if($parameters != ""){
					foreach($parameters as $par){
						$parameter_pkey = $this->db->get_var("select nextval('apprepo.parameter_pkey_seq');");

						$created_timestamp = time();
						$modified_timestamp = time();

						$type = pg_escape_string($par->type);
						$min = pg_escape_string($par->min);
						$max = pg_escape_string($par->max);
						$unit = pg_escape_string($par->unit);
						$prefix = pg_escape_string($par->prefix);
						$note = pg_escape_string($par->note);

						$this->db->query("
							insert into apprepo.apparatus_parameter
									values (
										$parameter_pkey,
										$apparatus_pkey,
										$this->userpkey,
										$created_timestamp,
										$modified_timestamp,
										'$type',
										'$min',
										'$max',
										'$unit',
										'$prefix',
										'$note'
										);
						");

					}
				}

				//Put in documents
				if($documents != ""){
					foreach($documents as $doc){
						$document_pkey = $this->db->get_var("select nextval('apprepo.document_pkey_seq');");

						$created_timestamp = time();
						$modified_timestamp = time();

						$type = pg_escape_string($doc->type);
						$other_type = pg_escape_string($doc->other_type);
						$format = pg_escape_string($doc->format);
						$other_format = pg_escape_string($doc->other_format);
						$id = pg_escape_string($doc->id);
						$path = pg_escape_string($doc->path);
						$description = pg_escape_string($doc->description);
						$uuid = pg_escape_string($doc->uuid);

						if($files[$uuid]["name"] != ""){
							$original_filename = pg_escape_string($files[$uuid]["name"]);
							$tmp_name = pg_escape_string($files[$uuid]["tmp_name"]);

							//Move file here
							move_uploaded_file($tmp_name, "/srv/app/www/experimental/expimages/$uuid");
						}else{
							$original_filename = "";
						}

						$this->db->query("
							insert into apprepo.apparatus_document
									values (
										$document_pkey,
										$apparatus_pkey,
										$this->userpkey,
										$created_timestamp,
										$modified_timestamp,
										'$type',
										'$format',
										'$id',
										'$path',
										'$description',
										'',
										'$uuid',
										'$path',
										'$other_type',
										'$other_format'
										);
						");

					}
				}

				$app->modified_timestamp = $modified_timestamp;
				$app->created_timestamp = $created_timestamp;

				return $app;

			}

		}else{

			//check to see if this is a valid facility
			if(!in_array($this->userpkey, $this->admin_pkeys)){
				$row = $this->db->get_row("select * from apprepo.facility where pkey = $facility_pkey and pkey in (select facility_pkey from apprepo.facility_users where users_pkey = $this->userpkey)");
			}else{
				$row = $this->db->get_row("select * from apprepo.facility where pkey = $facility_pkey");
			}

			if($row->pkey == ""){
				//error
				$data = new stdClass();
				$data->Error = "Facility not found.";
				return $data;
			}else{
				//OK, facility exists. Put in apparatus.
				$apparatus_pkey = $this->db->get_var("select nextval('apprepo.apparatus_pkey_seq');");
				$uuid = $this->uuid->v4();
				$userpkey = $this->userpkey;
				$created_timestamp = time();
				$modified_timestamp = time();

				$app->uuid = $uuid;

				//create json for storage
				$storejson = $app;
				$storejson->uuid = $uuid;
				$storejson->created_timestamp = $created_timestamp;
				$storejson->modified_timestamp = $modified_timestamp;
				$storejson = pg_escape_string(json_encode($storejson, JSON_PRETTY_PRINT));

				

				$this->db->query("
					insert into apprepo.apparatus values (
						$apparatus_pkey,
						$facility_pkey,
						$userpkey,
						$created_timestamp,
						$modified_timestamp,
						'$name',
						'$type',
						'$location',
						'$id',
						'$description',
						'$features',
						'$storejson',
						'$uuid',
						'$other_type' )
				");

				//Put in parameters
				if($parameters != ""){
					foreach($parameters as $par){
						$parameter_pkey = $this->db->get_var("select nextval('apprepo.parameter_pkey_seq');");

						$created_timestamp = time();
						$modified_timestamp = time();

						$type = pg_escape_string($par->type);
						$min = pg_escape_string($par->min);
						$max = pg_escape_string($par->max);
						$unit = pg_escape_string($par->unit);
						$prefix = pg_escape_string($par->prefix);
						$note = pg_escape_string($par->note);

						$this->db->query("
							insert into apprepo.apparatus_parameter
									values (
										$parameter_pkey,
										$apparatus_pkey,
										$userpkey,
										$created_timestamp,
										$modified_timestamp,
										'$type',
										'$min',
										'$max',
										'$unit',
										'$prefix',
										'$note'
										);
						");

					}
				}

				//Put in documents
				if($documents != ""){
					foreach($documents as $doc){
						$document_pkey = $this->db->get_var("select nextval('apprepo.document_pkey_seq');");

						$created_timestamp = time();
						$modified_timestamp = time();

						$type = pg_escape_string($doc->type);
						$format = pg_escape_string($doc->format);
						$id = pg_escape_string($doc->id);
						$path = pg_escape_string($doc->path);
						$description = pg_escape_string($doc->description);
						$uuid = pg_escape_string($doc->uuid);

						if($files[$uuid]["name"] != ""){
							$original_filename = pg_escape_string($files[$uuid]["name"]);
							$tmp_name = pg_escape_string($files[$uuid]["tmp_name"]);

							//Move file here
							move_uploaded_file($tmp_name, "/srv/app/www/experimental/expimages/$uuid");
						}else{
							$original_filename = "";
						}

						$this->db->query("
							insert into apprepo.apparatus_document
									values (
										$document_pkey,
										$apparatus_pkey,
										$userpkey,
										$created_timestamp,
										$modified_timestamp,
										'$type',
										'$format',
										'$id',
										'$path',
										'$description',
										'',
										'$uuid',
										'$original_filename'
										);
						");

					}
				}

				return $app;

			}

		}

	}

	public function insertFacility($json, $facility_pkey = ""){

		$fac = $json->facility;

		//gather data from submitted json
		$street = pg_escape_string($fac->address->street);
		$building = pg_escape_string($fac->address->building);
		$postcode = pg_escape_string($fac->address->postcode);
		$city = pg_escape_string($fac->address->city);
		$state = pg_escape_string($fac->address->state);
		$country = pg_escape_string($fac->address->country);
		$latitude = pg_escape_string($fac->address->latitude);
		$longitude = pg_escape_string($fac->address->longitude);

		$contact_firstname = pg_escape_string($fac->contact->firstname);
		$contact_lastname = pg_escape_string($fac->contact->lastname);
		$contact_affil = pg_escape_string($fac->contact->affiliation);
		$contact_email = pg_escape_string($fac->contact->email);
		$contact_phone = pg_escape_string($fac->contact->phone);
		$contact_website = pg_escape_string($fac->contact->website);
		$contact_id = pg_escape_string($fac->contact->id);

		$institute = pg_escape_string($fac->institute);
		$department = pg_escape_string($fac->department);
		$name = pg_escape_string($fac->name);
		$type = pg_escape_string($fac->type);
		$other_type = pg_escape_string($fac->other_type);
		$facility_id = pg_escape_string($fac->id);
		$facility_website = pg_escape_string($fac->website);
		$facility_desc = pg_escape_string($fac->description);

		if($facility_pkey != ""){
			//update facility here if uuid exists
			//first, check for existence, then update if exists. Otherwise error.

			//Check for admin too

			if(!in_array($this->userpkey, $this->admin_pkeys)){
				$row = $this->db->get_row("select * from apprepo.facility where pkey = $facility_pkey and pkey in (select facility_pkey from apprepo.facility_users where users_pkey = $this->userpkey)");
			}else{
				$row = $this->db->get_row("select * from apprepo.facility where pkey = $facility_pkey");
			}

			if($row->pkey == ""){
				//error
				$data = new stdClass();
				$data->Error = "Facility not found.";
				return $data;
			}else{
				//update
				$modified_timestamp = time();
				$created_timestamp = (int) $row->created_timestamp;

				//create json for storage
				$storejson = $fac;
				$storejson->uuid = $row->uuid;
				$storejson->created_timestamp = (int) $row->created_timestamp;
				$storejson->modified_timestamp = (int) $modified_timestamp;
				$storejson = json_encode($storejson, JSON_PRETTY_PRINT);

				$this->db->query("
					update apprepo.facility set
						created_timestamp = $created_timestamp,
						modified_timestamp = $modified_timestamp,
						institute = '$institute',
						department = '$department',
						name = '$name',
						type = '$type',
						other_type = '$other_type',
						facility_id = '$facility_id',
						facility_website = '$facility_website',
						facility_desc = '$facility_desc',
						street = '$street',
						building = '$building',
						postcode = '$postcode',
						city = '$city',
						state = '$state',
						country = '$country',
						latitude = '$latitude',
						longitude = '$longitude',
						contact_firstname = '$contact_firstname',
						contact_lastname = '$contact_lastname',
						contact_affil = '$contact_affil',
						contact_email = '$contact_email',
						contact_phone = '$contact_phone',
						contact_website = '$contact_website',
						contact_id = '$contact_id',
						json = '$storejson'
					where pkey = $row->pkey;
				");

				$fac->modified_timestamp = $modified_timestamp;
				$fac->created_timestamp = $created_timestamp;

				return $fac;
			}

		}else{

			$pkey = $this->db->get_var("select nextval('apprepo.facility_pkey_seq');");
			$uuid = $this->uuid->v4();
			$userpkey = $this->userpkey;
			$created_timestamp = time();
			$modified_timestamp = time();

			$fac->uuid = $uuid;

			//create json for storage
			$storejson = $fac;
			$storejson->uuid = $uuid;
			$storejson->created_timestamp = $created_timestamp;
			$storejson->modified_timestamp = $modified_timestamp;
			$storejson = json_encode($storejson, JSON_PRETTY_PRINT);

			$this->db->query("
				insert into apprepo.facility values (
					$pkey,
					$userpkey,
					$created_timestamp,
					$modified_timestamp,
					'$institute',
					'$department',
					'$name',
					'$type',
					'$facility_id',
					'$facility_website',
					'$facility_desc',
					'$street',
					'$building',
					'$postcode',
					'$city',
					'$state',
					'$country',
					'$latitude',
					'$longitude',
					'$contact_firstname',
					'$contact_lastname',
					'$contact_affil',
					'$contact_email',
					'$contact_phone',
					'$contact_website',
					'$contact_id',
					'$storejson',
					'$uuid',
					'$other_type'
				);
			");

			return $fac;

		}

	}

	public function getFacility($uuid){

		$row = $this->db->get_row("select * from straboexp_facility where uuid = '$uuid'");

		if($row->pkey == ""){
			//show error
			$data = new stdClass();
			$data->Error = "Facility $uuid not found.";
			return $data;
		}else{
			$data = json_decode($row->json);

			$dt = new DateTime("@$row->created_timestamp");
			$data->created_time = $dt->format('r')." (GMT)";

			$dt = new DateTime("@$row->modified_timestamp");
			$data->modified_time = $dt->format('r')." (GMT)";

			$data->uuid = $row->uuid;

			return $data;
		}

	}

	public function origgetKeywords($var){

		if(is_object($var)){
			foreach($var as $key=>$value){
				if($key!="id" && $key!="date" && $key!="strat_section_id" && $key!="modified_timestamp" && $key!="self" && $key!="time" && $key!="image_type"){
					$out .= " " . $this->getKeywords($value);
				}
			}
		}elseif(is_array($var)){
			if(array_keys($var) !== range(0, count($var) - 1)){
				//associative
				foreach($var as $key=>$value){
					if($key!="id" && $key!="date" && $key!="strat_section_id" && $key!="modified_timestamp" && $key!="self" && $key!="time" && $key!="image_type"){
						$out .= " " . $this->getKeywords($value);
					}
				}
			}else{
				foreach($var as $v){
					$out .= " " . $this->getKeywords($v);
				}
			}

		}else{
			if(!is_numeric($var) && !is_bool($var)){
				$out .= " " . $var;
			}
		}

		return $out;
	}

	public function getKeywords($var){

		if(is_object($var)){
			foreach($var as $key=>$value){
				if($key!="id" && $key!="date" && $key!="strat_section_id" && $key!="modified_timestamp" && $key!="self" && $key!="time" && $key!="image_type"){
					if($value != ""){
						$out .= "*****" . $this->getKeywords($value);
					}
				}
			}
		}elseif(is_array($var)){
			if(array_keys($var) !== range(0, count($var) - 1)){
				//associative
				foreach($var as $key=>$value){
					if($key!="id" && $key!="date" && $key!="strat_section_id" && $key!="modified_timestamp" && $key!="self" && $key!="time" && $key!="image_type"){
						if($value != ""){
							$out .= "*****" . $this->getKeywords($value);
						}
					}
				}
			}else{
				foreach($var as $v){
					if($v != ""){
					$out .= "*****" . $this->getKeywords($v);
					}
				}
			}

		}else{
			if(!is_numeric($var) && !is_bool($var)){
				if($var != ""){
					$out .= "*****" . $var;
				}
			}
		}

		return $out;
	}

	public function updateProjectKeywords($project_pkey){

		$keywords = "";

		$prow = $this->db->get_row("select * from straboexp.project where pkey = $project_pkey");
		if($prow->pkey != ""){
			$keywords .= "*****" . $prow->name . "*****" . $prow->notes;
			$userpkey = $prow->userpkey;

			$urow = $this->db->get_row("select * from users where pkey = $userpkey");
			$keywords .= "*****" . $urow->lastname . "*****" . $urow->firstname;

			$exprows = $this->db->get_results("select * from straboexp.experiment where project_pkey = $project_pkey");
			foreach($exprows as $exprow){
				$json = $exprow->json;
				$json = json_decode($json);
				$keywords .= $this->getKeywords($json);
			}

			$parts = explode("*****", $keywords);
			$distparts = [];

			foreach($parts as $p){
				if($p != ""){
					if(!in_array($p, $distparts)) $distparts[] = $p;
				}
			}

			$finalwords = pg_escape_string(implode(" ", $distparts));

			$this->db->query("update straboexp.project set keywords = to_tsvector('$finalwords') where pkey = $project_pkey;");
		}

	}

	public function insertProject($json, $project_pkey = ""){

		$proj = $json->project;

		//gather data from submitted json
		$name = pg_escape_string($proj->name);
		$description = pg_escape_string($proj->description);

		if($project_pkey != ""){
			//first, check for existence, then update if exists. Otherwise error.

			//Check for admin too

			if(!in_array($this->userpkey, $this->admin_pkeys)){
				$row = $this->db->get_row("select * from straboexp.project where userpkey = $this->userpkey and pkey = $project_pkey");
			}else{
				$row = $this->db->get_row("select * from straboexp.project where pkey = $project_pkey");
			}

			if($row->pkey == ""){
				//error
				$data = new stdClass();
				$data->Error = "Project not found.";
				return $data;
			}else{
				//update
				$modified_timestamp = time();
				$created_timestamp = (int) $row->created_timestamp;

				//create json for storage
				$storejson = $proj;
				$storejson->uuid = $row->uuid;
				$storejson->created_timestamp = (int) $row->created_timestamp;
				$storejson->modified_timestamp = (int) $modified_timestamp;
				$storejson = json_encode($storejson, JSON_PRETTY_PRINT);

				$this->db->query("
					update straboexp.project set
						modified_timestamp = now(),
						name = '$name',
						notes = '$description'
					where pkey = $row->pkey;
				");

				$this->updateProjectKeywords($row->pkey);

				$proj->modified_timestamp = $modified_timestamp;
				$proj->created_timestamp = $created_timestamp;

				return $proj;
			}

		}else{

			$pkey = $this->db->get_var("select nextval('straboexp.project_pkey_seq');");

			if($proj->uuid != ""){
				$uuid = $proj->uuid;
			}else{
				$uuid = $this->uuid->v4();
			}

			$userpkey = $this->userpkey;

			if($proj->created_timestamp != ""){
				$created_timestamp = $proj->created_timestamp;
			}else{
				$created_timestamp = time();
			}

			$modified_timestamp = time();

			$proj->uuid = $uuid;

			//create json for storage
			$storejson = $proj;
			$storejson->uuid = $uuid;
			$storejson->created_timestamp = $created_timestamp;
			$storejson->modified_timestamp = $modified_timestamp;
			$storejson = json_encode($storejson, JSON_PRETTY_PRINT);

			$this->db->query("
				insert into straboexp.project values (
					$pkey,
					$userpkey,
					'$uuid',
					now(),
					now(),
					'$name',
					'$description',
					false,
					null
				);
			");

			$this->updateProjectKeywords($pkey);

			return $proj;

		}

	}

	public function createProjectVersion($project_pkey){
		$prow = $this->db->get_row("select * from straboexp.project where pkey = $project_pkey");
		if($prow->pkey != ""){

			$ver = new stdClass();
			$ver->project->userpkey = $prow->userpkey;
			$ver->project->uuid = $prow->uuid;
			$ver->project->created_timestamp = $prow->created_timestamp;
			$ver->project->name = $prow->name;
			$ver->project->notes = $prow->notes;
			$ver->project->ispublic = $prow->ispublic;

			$experiments = [];
			$erows = $this->db->get_results("select * from straboexp.experiment where project_pkey = $project_pkey");
			$experimentcount = count($erows);

			foreach($erows as $erow){
				$json = $erow->json;
				$json = json_decode($json);

				$json->created_timestamp = $erow->created_timestamp;
				$json->modified_timestamp = $erow->modified_timestamp;
				$json->experimentid = $erow->id;

				//Add experiment id here for versioning

				$experiments[] = $json;
			}
			$ver->experiments = $experiments;
			$verjson = pg_escape_string(json_encode($ver, JSON_PRETTY_PRINT));

			$uuid = $prow->uuid;
			$verpkey = $this->db->get_var("select nextval('straboexp.versions_pkey_seq')");

			$this->db->query("
								insert into straboexp.versions (
																pkey,
																uuid,
																userpkey,
																projectname,
																experimentcount,
																json
															)values(
																$verpkey,
																'$uuid',
																$this->userpkey,
																'$prow->name',
																$experimentcount,
																'$verjson'
															)
			");

		}
	}

	public function restoreVersion($versionpkey){

		//first, delete project in case it exists,
		$vrow = $this->db->get_row("select * from straboexp.versions where userpkey = $this->userpkey and pkey = $versionpkey");
		if($vrow->pkey != ""){

			$json = json_decode($vrow->json);
			$project = $json->project;
			$experiments = $json->experiments;

			//create a version first before restoring
			$project_pkey = $this->db->get_var("select pkey from straboexp.project where uuid='$project->uuid' and userpkey = $this->userpkey");
			if($project_pkey != ""){
			}

			$this->db->query("delete from straboexp.project where uuid='$project->uuid' and userpkey = $this->userpkey");

			$project_pkey = $this->db->get_var("select nextval('straboexp.project_pkey_seq')");
			$this->db->query("
				insert into straboexp.project values (
					$project_pkey,
					$this->userpkey,
					'$project->uuid',
					'$project->created_timestamp',
					now(),
					'$project->name',
					'$project->notes',
					'$project->ispublic',
					null
				)
			");

			foreach($experiments as $exp){

				//get experiment id from json and send it along to insertExperiment

				$experiment_id = $exp->experimentid;
				unset($exp->experimentid);

				$this->insertExperiment($exp, $project_pkey, null, $experiment_id, null);
			}

			$this->updateProjectKeywords($project_pkey);

		}else{
			exit("version not found.");
		}

	}

	public function getExperimentalApparatuses(){

		$facilities = [];

		$frows = $this->db->get_results("select * from apprepo.facility order by institute");
		foreach($frows as $frow){
			$fpkey = $frow->pkey;
			$arows = $this->db->get_results("select * from apprepo.apparatus where facility_pkey = $fpkey order by modified_timestamp desc");
			if(count($arows) > 0){
				$f = json_decode($frow->json);
				$f->id = $fpkey;
				$f->created_timestamp = date("D, M j Y G:i:s T ", $f->created_timestamp);
				$f->modified_timestamp = date("D, M j Y G:i:s T ", $f->modified_timestamp);

				$apps = [];
				foreach($arows as $arow){
					$a = json_decode($arow->json);
					$a->id = $arow->pkey;
					$a->created_timestamp = date("D, M j Y G:i:s T ", $a->created_timestamp);
					$a->modified_timestamp = date("D, M j Y G:i:s T ", $a->modified_timestamp);
					$apps[] = $a;
				}

				$f->apparatuses = $apps;

				$facilities[] = $f;

			}
		}

		$data = new stdClass();
		$data->facilities = $facilities;
		return $data;
	}

	public function getApprepoFacilityAlone($pkey){
		$row = $this->db->get_row("select * from apprepo.facility where pkey = $pkey");
		if($row->pkey == ""){
			//error
			$data = new stdClass();
			$data->Error = "Facility not found.";
			return $data;
		}else{
			$data = json_decode($row->json);
			$data->created_timestamp = date("D, M j Y G:i:s T ", $data->created_timestamp);
			$data->modified_timestamp = date("D, M j Y G:i:s T ", $data->modified_timestamp);
			return $data;
		}

	}

	public function getApprepoApparatus($pkey){
		$row = $this->db->get_row("select * from apprepo.apparatus where pkey = $pkey");
		if($row->pkey == ""){
			//error
			$data = new stdClass();
			$data->Error = "Apparatus not found.";
			return $data;
		}else{
			$data = json_decode($row->json);
			$data->created_timestamp = date("D, M j Y G:i:s T ", $data->created_timestamp);
			$data->modified_timestamp = date("D, M j Y G:i:s T ", $data->modified_timestamp);
			return $data;
		}

	}

	public function oldinsertApparatus20230424($json, $uuid){

		$app = json_decode($json);

		$name = $app->name;
		$type = $app->type;
		$location = $app->location;
		$apparatus_id = $app->id;
		$description = $app->description;
		if($app->features != ""){
			$features = implode(";;", $app->features);
		}
		$parameters = $app->parameters;
		$facility_uuid = $app->facility_uuid;

		//Check for facility
		if($facility_uuid == ""){
			//error
			$data = new stdClass();
			$data->Error = "No facility uuid provided.";
			return $data;
		}else{
			//check for facility existence here
			$facility_pkey = $this->db->get_var("select * from straboexp_facility where uuid = '$facility_uuid'");
			if($facility_pkey == ""){
				//error
				$data = new stdClass();
				$data->Error = "Facility $facility_uuid not found.";
				return $data;
			}
		}

		if($uuid != ""){
			//update apparatus here if uuid exists
			//first, check for existence, then update if exists. Otherwise error.

			$row = $this->db->get_row("select * from straboexp_apparatus where userpkey = $this->userpkey and uuid = '$uuid'");
			if($row->pkey == ""){
				//error
				$data = new stdClass();
				$data->Error = "Apparatus $uuid not found.";
				return $data;
			}else{
				//update apparatus
				$apparatus_pkey = $row->pkey;

				$modified_timestamp = time();

				$app->created_timestamp = intval($row->created_timestamp);
				$app->modified_timestamp = intval($modified_timestamp);

				$storejson = json_encode($app, JSON_PRETTY_PRINT);

				$this->db->query("
					update straboexp_apparatus set
					name = '$name',
					type = '$type',
					location = '$location',
					apparatus_id = '$apparatus_id',
					description = '$description',
					features = '$features',
					modified_timestamp = $modified_timestamp,
					json = '$storejson'
					where pkey = $apparatus_pkey;
				");

				//delete parameters to reload
				$apparatus_pkey = $row->pkey;
				$this->db->query("delete from straboexp_parameter where apparatus_pkey = $apparatus_pkey");

				if($parameters != ""){
					foreach($parameters as $p){

						$parameter_pkey = $this->db->get_var("select nextval('parameter_pkey_seq');");

						$type = $p-> type;
						$min = $p-> min;
						$max = $p-> max;
						$unit = $p-> unit;
						$prefix = $p-> prefix;
						$note = $p-> note;

						$this->db->query("
							insert into straboexp_parameter (
								pkey,
								apparatus_pkey,
								userpkey,
								type,
								min,
								max,
								unit,
								prefix,
								note
							) values (
								$parameter_pkey,
								$apparatus_pkey,
								$this->userpkey,
								'$type',
								'$min',
								'$max',
								'$unit',
								'$prefix',
								'$note'
							)
						");
					}
				}
			}

			return $app;

		}else{

			$apparatus_pkey = $this->db->get_var("select nextval('apparatus_pkey_seq');");
			$apparatus_uuid = $this->uuid->v4();
			$userpkey = $this->userpkey;
			$created_timestamp = time();
			$modified_timestamp = time();

			$fac->uuid = $uuid;

			//create json for storage
			$storejson = clone $app;
			$storejson->uuid = $apparatus_uuid;
			$storejson->facility_uuid = $facility_uuid;
			$storejson->created_timestamp = $created_timestamp;
			$storejson->modified_timestamp = $modified_timestamp;
			unset($storejson->parameters);
			$storejson = json_encode($storejson, JSON_PRETTY_PRINT);

			$this->db->query("
				insert into straboexp.straboexp_apparatus (
					pkey,
					uuid,
					facility_pkey,
					userpkey,
					created_timestamp,
					modified_timestamp,
					name,
					type,
					location,
					apparatus_id,
					description,
					features,
					json
				) values (
					$apparatus_pkey,
					'$apparatus_uuid',
					$facility_pkey,
					'$userpkey',
					$created_timestamp,
					$modified_timestamp,
					'$name',
					'$type',
					'$location',
					'$apparatus_id',
					'$description',
					'$features',
					'$storejson'
				);
			");

			//Also load parameters

			$newparams = [];

			if($parameters != ""){
				foreach($parameters as $p){

					$parameter_pkey = $this->db->get_var("select nextval('parameter_pkey_seq');");
					$parameter_uuid = $this->uuid->v4();
					$created_timestamp = time();
					$modified_timestamp = time();

					$type = pg_escape_string($p-> type);
					$min = pg_escape_string($p-> min);
					$max = pg_escape_string($p-> max);
					$unit = pg_escape_string($p-> unit);
					$prefix = pg_escape_string($p-> prefix);
					$note = pg_escape_string($p-> note);

					$p->uuid = pg_escape_string($parameter_uuid);
					$p->created_timestamp = $created_timestamp;
					$p->modified_timestamp = $modified_timestamp;

					$newparams[] = $p;

					$this->db->query("
						insert into straboexp_parameter (
							pkey,
							apparatus_pkey,
							userpkey,
							type,
							min,
							max,
							unit,
							prefix,
							note
						) values (
							$parameter_pkey,
							$apparatus_pkey,
							$userpkey,
							'$type',
							'$min',
							'$max',
							'$unit',
							'$prefix',
							'$note'
						)
					");

/*
pkey
apparatus_pkey
uuid
userpkey
created_timestamp
modified_timestamp
type
min
max
unit
prefix
note
*/

				}
			}

			$outjson = json_decode($storejson);

			if($parameters != ""){
				$outjson->parameters = $newparams;
			}

			//OK, for now
			return $outjson;

		}

	}

	public function getMyProjects(){

		$projects = [];

		$rows = $this->db->get_results("selessct
										id,
										strabo_id,
										name,
										TO_CHAR(uploaddate, 'mm/dd/yyyy HH:MMPM TZ OF') as uploaddate
										from micro_projectmetadata where userpkey = $this->userpkey");
		foreach($rows as $row){
			$p = new stdClass();
			$p->id = $row->strabo_id;
			$p->name = $row->name;
			$p->self = "https://strabospot.org/microdb/project/".$row->strabo_id;
			$p->uploaddate = $row->uploaddate;
			$p->bytes = filesize($_SERVER['DOCUMENT_ROOT']."/straboMicroFiles/".$row->id."/project.zip");

			$projects[] = $p;
		}

		$data['projects'] = $projects;

		return $data;

	}

	public function deleteProject($projectid) {

	}

	public function deleteExperiment($experiment_pkey){
		$experiment_row = $this->db->get_row("select * from straboexp.experiment where pkey = $experiment_pkey and userpkey = $this->userpkey");
		if($experiment_row->pkey == "")die("Experiment Not Found");

		$this->db->query("delete from straboexp.experiment where pkey = $experiment_pkey");

	}

	public function deleteExperimentFiles($experiment_pkey){
		$experiment_row = $this->db->get_row("select * from straboexp.experiment where pkey = $experiment_pkey and userpkey = $this->userpkey");
		if($experiment_row->pkey == "")die("Experiment Not Found");

		

	}

	public function insertExperiment($json, $project_pkey, $files, $experiment_id, $experiment_pkey=""){

		//************************************************************************************************************************
		//************************************************************************************************************************
		//************************************************************************************************************************
		//************************************************************************************************************************
		//$this->db->query("delete from straboexp.experiment");
		//************************************************************************************************************************
		//************************************************************************************************************************
		//************************************************************************************************************************
		//************************************************************************************************************************

		//TODO: ******************
		//If experiment_pkey set, delete experiment first and then rewrite -
		//  first gather created date and uuid to keep them the same.

		//Also update project modified_timestamp to now();

		//Get project row
		$project_row = $this->db->get_row("select * from straboexp.project where pkey = $project_pkey and userpkey = $this->userpkey");
		$project_name = $project_row->name;

		if($project_row->pkey == ""){
			die("Project Not Found.");
		}

		//First, enter new experiment ... gather new pkey and experiment id from json
		$experiment_pkey = $this->db->get_var("select nextval('straboexp.experiment_pkey_seq');");

		if($experiment_id == ""){
			if($json->experiment->id != ""){
				$experiment_id = pg_escape_string($json->experiment->id);
			}else{
				$experiment_id = "Unnamed";
			}
		}else{
			$experiment_id = pg_escape_string($experiment_id);
		}

		unset($json->experiment_id);

		if($json->created_timestamp != ""){
			$created_timestamp = "'".$json->created_timestamp."'";
		}else{
			$created_timestamp = "now()";
		}

		if($json->modified_timestamp != ""){
			$modified_timestamp = "'".$json->modified_timestamp."'";
		}else{
			$modified_timestamp = "now()";
		}

		if($json->uuid != ""){
			$experiment_uuid = $json->uuid;
		}else{
			$experiment_uuid = $this->uuid->v4();
		}

		unset($json->uuid);
		unset($json->created_timestamp);
		unset($json->modified_timestamp);

		$storejson = pg_escape_string(json_encode($json, JSON_PRETTY_PRINT));

		$this->db->query("
			insert into straboexp.experiment values (
				$experiment_pkey,
				$project_pkey,
				$this->userpkey,
				'$experiment_id',
				$created_timestamp,
				$modified_timestamp,
				'$storejson',
				'$experiment_uuid'
			);
		");

		//Insert Facility
		if($json->facility != ""){
			$facility_pkey = $this->db->get_var("select nextval('straboexp.facility_pkey_seq');");
			$fac = $json->facility;

			$institute = pg_escape_string($fac->institute);
			$department = pg_escape_string($fac->department);
			$name = pg_escape_string($fac->name);
			$type = pg_escape_string($fac->type);
			$other_type = pg_escape_string($fac->other_type);
			$facility_id = pg_escape_string($fac->id);
			$facility_website = pg_escape_string($fac->website);
			$facility_desc = pg_escape_string($fac->description);
			$fac_uuid = $this->uuid->v4();

			$add = $fac->address;
			$street = pg_escape_string($add->street);
			$building = pg_escape_string($add->building);
			$postcode = pg_escape_string($add->postcode);
			$city = pg_escape_string($add->city);
			$state = pg_escape_string($add->state);
			$country = pg_escape_string($add->country);
			$latitude = pg_escape_string($add->latitude);
			$longitude = pg_escape_string($add->longitude);

			$contact = $fac->contact;
			$contact_firstname = pg_escape_string($contact->firstname);
			$contact_lastname = pg_escape_string($contact->lastname);
			$contact_affiliation = pg_escape_string($contact->affiliation);
			$contact_email = pg_escape_string($contact->email);
			$contact_phone = pg_escape_string($contact->phone);
			$contact_website = pg_escape_string($contact->website);
			$contact_id = pg_escape_string($contact->id);

			$fac_json = pg_escape_string(json_encode($fac, JSON_PRETTY_PRINT));

			$this->db->query("
				insert into straboexp.facility values (
					$facility_pkey,
					$experiment_pkey,
					$this->userpkey,
					'$institute',
					'$department',
					'$name',
					'$type',
					'$facility_id',
					'$facility_website',
					'$facility_desc',
					'$street',
					'$building',
					'$postcode',
					'$city',
					'$state',
					'$country',
					'$latitude',
					'$longitude',
					'$contact_firstname',
					'$contact_lastname',
					'$contact_affiliation',
					'$contact_email',
					'$contact_phone',
					'$contact_website',
					'$contact_id',
					'$fac_json',
					'$fac_uuid',
					'$other_type'
				)
			");

		}

		//Insert Apparatus
		if($json->apparatus != ""){
			$apparatus_pkey = $this->db->get_var("select nextval('straboexp.apparatus_pkey_seq');");
			$app = $json->apparatus;

			$name = pg_escape_string($app->name);
			$type = pg_escape_string($app->type);
			$other_type = pg_escape_string($app->other_type);
			$location = pg_escape_string($app->location);
			$apparatus_id = pg_escape_string($app->id);
			$description = pg_escape_string($app->description);
			$features = pg_escape_string(implode("; ", $app->features));
			$app_json = pg_escape_string(json_encode($app, JSON_PRETTY_PRINT));
			$app_uuid = $this->uuid->v4();

			$this->db->query("
				insert into straboexp.apparatus values (
					$apparatus_pkey,
					$experiment_pkey,
					$this->userpkey,
					'$name',
					'$type',
					'$location',
					'$apparatus_id',
					'$description',
					'$features',
					'$app_json',
					'$app_uuid',
					'$other_type'
				)
			");

			if($app->parameters != ""){
				foreach($app->parameters as $param){
					$parameter_pkey = $this->db->get_var("select nextval('straboexp.parameter_pkey_seq');");

					$type = pg_escape_string($param->type);
					$min = pg_escape_string($param->min);
					$max = pg_escape_string($param->max);
					$unit = pg_escape_string($param->unit);
					$prefix = pg_escape_string($param->prefix);
					$note = pg_escape_string($param->note);

					$this->db->query("
						insert into straboexp.apparatus_parameter values (
							$parameter_pkey,
							$apparatus_pkey,
							$this->userpkey,
							'$type',
							'$min',
							'$max',
							'$unit',
							'$prefix',
							'$note'
						)
					");

				}
			}

			if($app->documents != ""){
				foreach($app->documents as $document){
					$document_pkey = $this->db->get_var("select nextval('straboexp.document_pkey_seq');");
					$type = pg_escape_string($document->type);
					$other_type = pg_escape_string($document->other_type);
					$format = pg_escape_string($document->format);
					$other_format = pg_escape_string($document->other_format);
					$path = pg_escape_string($document->path);
					$id = pg_escape_string($document->id);
					$uuid = pg_escape_string($document->uuid);
					$description = pg_escape_string($document->description);

					if($files[$uuid]["name"] != ""){
						$original_filename = pg_escape_string($files[$uuid]["name"]);
						$tmp_name = pg_escape_string($files[$uuid]["tmp_name"]);

						$path = $original_filename;

						//Move file here
						move_uploaded_file($tmp_name, "/srv/app/www/experimental/expimages/$uuid");
					}else{
						$original_filename = $path;
					}

					$this->db->query("
						insert into straboexp.document
						(
							pkey,
							apparatus_pkey,
							userpkey,
							type,
							other_type,
							format,
							other_format,
							id,
							path,
							description,
							uuid,
							original_filename
						) values (
							$document_pkey,
							$apparatus_pkey,
							$this->userpkey,
							'$type',
							'$other_type',
							'$format',
							'$other_format',
							'$id',
							'$path',
							'$description',
							'$uuid',
							'$original_filename'
						)
					");

				}
			}

		}

		//Insert DAQ
		if($json->daq != ""){
			$daq_pkey = $this->db->get_var("select nextval('straboexp.daq_pkey_seq');");
			$daq = $json->daq;
			$name = pg_escape_string($daq->name);
			$type = pg_escape_string($daq->type);
			$location = pg_escape_string($daq->location);
			$description = pg_escape_string($daq->description);

			$daq_json = pg_escape_string(json_encode($daq, JSON_PRETTY_PRINT));

			$this->db->query("
				insert into straboexp.daq values (
					$daq_pkey,
					$experiment_pkey,
					$this->userpkey,
					'$name',
					'$type',
					'$location',
					'$description',
					'$daq_json'
				)
			");

			if($daq->devices != ""){
				foreach($daq->devices as $daq_device){
					$daq_device_pkey = $this->db->get_var("select nextval('straboexp.daq_device_pkey_seq');");
					$name = pg_escape_string($daq_device->name);
					$this->db->query("
						insert into straboexp.daq_device values (
							$daq_device_pkey,
							$daq_pkey,
							$this->userpkey,
							'$name'
						)
					");

					if($daq_device->channels != ""){
						foreach($daq_device->channels as $daq_device_channel){
							$daq_device_channel_pkey = $this->db->get_var("select nextval('straboexp.daq_device_channel_pkey_seq');");
							$header_type = pg_escape_string($daq_device_channel->header->type);
							$other_header_type = pg_escape_string($daq_device_channel->header->other_type);
							$header_spec_a = pg_escape_string($daq_device_channel->header->spec_a);
							$header_spec_b = pg_escape_string($daq_device_channel->header->spec_b);
							$header_spec_c = pg_escape_string($daq_device_channel->header->spec_c);
							$header_unit = pg_escape_string($daq_device_channel->header->unit);
							$number = pg_escape_string($daq_device_channel->number);
							$type = pg_escape_string($daq_device_channel->type);
							$configuration = pg_escape_string($daq_device_channel->configuration);
							$note = pg_escape_string($daq_device_channel->note);
							$resolution = pg_escape_string($daq_device_channel->resolution);
							$range_low = pg_escape_string($daq_device_channel->range_low);
							$range_high = pg_escape_string($daq_device_channel->range_high);
							$rate = pg_escape_string($daq_device_channel->rate);
							$filter = pg_escape_string($daq_device_channel->filter);
							$gain = pg_escape_string($daq_device_channel->gain);
							$sensor_template = pg_escape_string($daq_device_channel->sensor->template);
							$sensor_detail = pg_escape_string($daq_device_channel->sensor->detail);
							$sensor_type = pg_escape_string($daq_device_channel->sensor->type);
							$sensor_id = pg_escape_string($daq_device_channel->sensor->id);
							$sensor_model = pg_escape_string($daq_device_channel->sensor->model);
							$sensor_version = pg_escape_string($daq_device_channel->sensor->version);
							$sensor_number = pg_escape_string($daq_device_channel->sensor->number);
							$sensor_serial = pg_escape_string($daq_device_channel->sensor->serial);
							$cal_template = pg_escape_string($daq_device_channel->calibration->template);
							$cal_input = pg_escape_string($daq_device_channel->calibration->input);
							$cal_unit = pg_escape_string($daq_device_channel->calibration->unit);
							$cal_excitation = pg_escape_string($daq_device_channel->calibration->excitation);
							$cal_date = pg_escape_string($daq_device_channel->calibration->date);
							$cal_note = pg_escape_string($daq_device_channel->calibration->note);

							$this->db->query("
								insert into straboexp.daq_device_channel values (
									$daq_device_channel_pkey,
									$daq_device_pkey,
									$this->userpkey,
									'$header_type',
									'$header_spec_a',
									'$header_spec_b',
									'$header_spec_c',
									'$header_unit',
									'$number',
									'$type',
									'$configuration',
									'$note',
									'$resolution',
									'$range_low',
									'$range_high',
									'$rate',
									'$filter',
									'$gain',
									'$sensor_template',
									'$sensor_detail',
									'$sensor_type',
									'$sensor_id',
									'$sensor_model',
									'$sensor_version',
									'$sensor_number',
									'$sensor_serial',
									'$cal_template',
									'$cal_input',
									'$cal_unit',
									'$cal_excitation',
									'$cal_date',
									'$cal_note',
									'$other_header_type'
								)
							");

							if($daq_device_channel->data != ""){
								foreach($daq_device_channel->data as $data){
									$daq_device_channel_data_pkey = $this->db->get_var("select nextval('straboexp.daq_device_channel_data_pkey_seq');");
									$aval = pg_escape_string($data->a);
									$bval = pg_escape_string($data->b);
									$this->db->query("
										insert into straboexp.daq_device_channel_data values (
											$daq_device_channel_data_pkey,
											$daq_device_channel_pkey,
											$this->userpkey,
											'$aval',
											'$bval'
										)
									");

								}
							}

						}
					}

					if($daq_device->documents != ""){

						//$this->logToFile("made it to documents.\n");
						//$this->logToFile($files);

						foreach($daq_device->documents as $document){
							$document_pkey = $this->db->get_var("select nextval('straboexp.document_pkey_seq');");
							$type = pg_escape_string($document->type);
							$other_type = pg_escape_string($document->other_type);
							$format = pg_escape_string($document->format);
							$other_format = pg_escape_string($document->other_format);
							$path = pg_escape_string($document->path);
							$id = pg_escape_string($document->id);
							$uuid = pg_escape_string($document->uuid);
							$description = pg_escape_string($document->description);

							if($files[$uuid]["name"] != ""){
								$original_filename = pg_escape_string($files[$uuid]["name"]);
								$tmp_name = pg_escape_string($files[$uuid]["tmp_name"]);

								$path = $original_filename;

								//Move file here

								//$this->logToFile("moving file: $tmp_name to /srv/app/www/experimental/expimages/$uuid \n");

								move_uploaded_file($tmp_name, "/srv/app/www/experimental/expimages/$uuid");
							}else{
								$original_filename = $path;
							}

							$this->db->query("
								insert into straboexp.document
								(
									pkey,
									daq_device_pkey,
									userpkey,
									type,
									other_type,
									format,
									other_format,
									id,
									path,
									description,
									uuid,
									original_filename
								) values (
									$document_pkey,
									$daq_device_pkey,
									$this->userpkey,
									'$type',
									'$other_type',
									'$format',
									'$other_format',
									'$id',
									'$path',
									'$description',
									'$uuid',
									'$original_filename'
								)
							");

						}
					}
				}
			}
		}

		//Insert Sample
		if($json->sample != ""){
			$sample_pkey = $this->db->get_var("select nextval('straboexp.sample_pkey_seq');");
			$sample = $json->sample;
			$name = pg_escape_string($sample->name);
			$igsn = pg_escape_string($sample->igsn);
			$id = pg_escape_string($sample->id);
			$description = pg_escape_string($sample->description);

			$sample_json = pg_escape_string(json_encode($sample, JSON_PRETTY_PRINT));

			if($sample->parent != ""){
				$parent_name = pg_escape_string($sample->parent->name);
				$parent_igsn = pg_escape_string($sample->parent->igsn);
				$parent_id = pg_escape_string($sample->parent->id);
				$parent_description = pg_escape_string($sample->parent->description);

			}
			if($sample->material != ""){
				$material = $sample->material;
				$material_type = pg_escape_string($material->material->type);
				$material_name = pg_escape_string($material->material->name);
				$material_state = pg_escape_string($material->material->state);
				$material_note = pg_escape_string($material->material->note);
				$provenance_formation = pg_escape_string($material->provenance->formation);
				$provenance_member = pg_escape_string($material->provenance->member);
				$provenance_submember = pg_escape_string($material->provenance->submember);
				$provenance_source = pg_escape_string($material->provenance->source);
				$provenance_loc_street = pg_escape_string($material->provenance->location->street);
				$provenance_loc_building = pg_escape_string($material->provenance->location->building);
				$provenance_loc_postcode = pg_escape_string($material->provenance->location->postcode);
				$provenance_loc_city = pg_escape_string($material->provenance->location->city);
				$provenance_loc_state = pg_escape_string($material->provenance->location->state);
				$provenance_loc_country = pg_escape_string($material->provenance->location->country);
				$provenance_loc_latitude = pg_escape_string($material->provenance->location->latitude);
				$provenance_loc_longitude = pg_escape_string($material->provenance->location->longitude);
				$texture_bedding = pg_escape_string($material->texture->bedding);
				$texture_lineation = pg_escape_string($material->texture->lineation);
				$texture_foliation = pg_escape_string($material->texture->foliation);
				$texture_fault = pg_escape_string($material->texture->fault);

			}

			$this->db->query("
				insert into straboexp.sample values (
					$sample_pkey,
					$experiment_pkey,
					$this->userpkey,
					'$name',
					'$igsn',
					'$id',
					'$description',
					'$parent_name',
					'$parent_igsn',
					'$parent_id',
					'$parent_description',
					'$material_type',
					'$material_name',
					'$material_state',
					'$material_note',
					'$provenance_formation',
					'$provenance_member',
					'$provenance_submember',
					'$provenance_source',
					'$provenance_loc_street',
					'$provenance_loc_building',
					'$provenance_loc_postcode',
					'$provenance_loc_city',
					'$provenance_loc_state',
					'$provenance_loc_country',
					'$provenance_loc_latitude',
					'$provenance_loc_longitude',
					'$texture_bedding',
					'$texture_lineation',
					'$texture_foliation',
					'$texture_fault',
					'$sample_json'
				)
			");

			//sample composition
			if($material->composition != ""){
				foreach($material->composition as $composition){
					$composition_pkey = $this->db->get_var("select nextval('straboexp.sample_composition_pkey_seq');");
					$mineral = pg_escape_string($composition->mineral);
					$fraction = pg_escape_string($composition->fraction);
					$unit = pg_escape_string($composition->unit);
					$grainsize = pg_escape_string($composition->grainsize);

					$this->db->query("
						insert into straboexp.sample_composition values (
							$composition_pkey,
							$sample_pkey,
							$this->userpkey,
							'$mineral',
							'$fraction',
							'$unit',
							'$grainsize'
						)
					");

				}
			}

			if($sample->parameters != ""){
				foreach($sample->parameters as $param){
					$parameter_pkey = $this->db->get_var("select nextval('straboexp.sample_parameter_pkey_seq');");
					$control = pg_escape_string($param->control);
					$other_control = pg_escape_string($param->other_control);
					$value = pg_escape_string($param->value);
					$unit = pg_escape_string($param->unit);
					$prefix = pg_escape_string($param->prefix);
					$note = pg_escape_string($param->note);

					/*
					$this->db->query("
						insert into straboexp.sample_parameter values (
							$parameter_pkey,
							$sample_pkey,
							$this->userpkey,
							'$control',
							'$value',
							'$unit',
							'$prefix',
							'$note',
							'$other_control'
						)
					");
					*/

					$this->db->query("
						insert into straboexp.sample_parameter values (
							$parameter_pkey,
							$sample_pkey,
							$this->userpkey,
							'$control',
							'$value',
							'$unit',
							'$prefix',
							'$note'
						)
					");

				}
			}

			if($sample->documents != ""){
				foreach($sample->documents as $document){
					$document_pkey = $this->db->get_var("select nextval('straboexp.document_pkey_seq');");
					$type = pg_escape_string($document->type);
					$other_type = pg_escape_string($document->other_type);
					$format = pg_escape_string($document->format);
					$other_format = pg_escape_string($document->other_format);
					$path = pg_escape_string($document->path);
					$id = pg_escape_string($document->id);
					$uuid = pg_escape_string($document->uuid);
					$description = pg_escape_string($document->description);

					if($files[$uuid]["name"] != ""){
						$original_filename = pg_escape_string($files[$uuid]["name"]);
						$tmp_name = pg_escape_string($files[$uuid]["tmp_name"]);

						$path = $original_filename;

						//Move file here
						move_uploaded_file($tmp_name, "/srv/app/www/experimental/expimages/$uuid");
					}else{
						$original_filename = $path;
					}

					$this->db->query("
						insert into straboexp.document
						(
							pkey,
							sample_pkey,
							userpkey,
							type,
							other_type,
							format,
							other_format,
							id,
							path,
							description,
							uuid,
							original_filename
						) values (
							$document_pkey,
							$sample_pkey,
							$this->userpkey,
							'$type',
							'$other_type',
							'$format',
							'$other_format',
							'$id',
							'$path',
							'$description',
							'$uuid',
							'$original_filename'
						)
					");
				}
			}
		}

		//Insert Experiment Setup
		if($json->experiment != ""){
			$experiment_setup_pkey = $this->db->get_var("select nextval('straboexp.experiment_setup_pkey_seq');");
			$exp = $json->experiment;
			$exp_json = pg_escape_string(json_encode($exp, JSON_PRETTY_PRINT));
			$title = pg_escape_string($exp->title);
			$project = $project_name;
			$id = pg_escape_string($exp->id);
			$ieda = pg_escape_string($exp->ieda);

			$start_date = pg_escape_string($exp->start_date);
			if($start_date == ""){
				$start_date = "null";
			}else{
				$start_date = "'".$start_date."'";
			}

			$end_date = pg_escape_string($exp->end_date);
			if($end_date == ""){
				$end_date = "null";
			}else{
				$end_date = "'".$end_date."'";
			}

			$description = pg_escape_string($exp->description);
			$features = pg_escape_string(implode("; ", $exp->features));
			$author_firstname = pg_escape_string($exp->author->firstname);
			$author_lastname = pg_escape_string($exp->author->lastname);
			$author_affiliation = pg_escape_string($exp->author->affiliation);
			$author_email = pg_escape_string($exp->author->email);
			$author_phone = pg_escape_string($exp->author->phone);
			$author_website = pg_escape_string($exp->author->website);
			$author_id = pg_escape_string($exp->author->id);

			$this->db->query("
				insert into straboexp.experiment_setup values (
					$experiment_setup_pkey,
					$experiment_pkey,
					$this->userpkey,
					'$title',
					'$project',
					'$id',
					'$ieda',
					$start_date,
					$end_date,
					'$description',
					'$features',
					'$author_firstname',
					'$author_lastname',
					'$author_affiliation',
					'$author_email',
					'$author_phone',
					'$author_website',
					'$author_id',
					'$exp_json'
				)
			");

			if($exp->geometry != ""){
				foreach($exp->geometry as $geom){
					$geometry_pkey = $this->db->get_var("select nextval('straboexp.experiment_setup_geometry_pkey_seq');");
					$order = pg_escape_string($geom->order);
					$type = pg_escape_string($geom->type);
					$geometry = pg_escape_string($geom->geometry);
					$material = pg_escape_string($geom->material);

					$this->db->query("
						insert into straboexp.experiment_setup_geometry values (
							$geometry_pkey,
							$experiment_setup_pkey,
							$this->userpkey,
							'$order',
							'$type',
							'$geometry',
							'$material'
						)
					");

					if($geom->dimensions != ""){
						foreach($geom->dimensions as $dim){
							$dimension_pkey = $this->db->get_var("select nextval('straboexp.experiment_setup_geometry_dimension_pkey_seq');");
							$variable = pg_escape_string($dim->variable);
							$value = pg_escape_string($dim->value);
							$unit = pg_escape_string($dim->unit);
							$prefix = pg_escape_string($dim->prefix);
							$note = pg_escape_string($dim->note);

							$this->db->query("
								insert into straboexp.experiment_setup_geometry_dimension values (
									$dimension_pkey,
									$geometry_pkey,
									$this->userpkey,
									'$variable',
									'$value',
									'$unit',
									'$prefix',
									'$note'
								)
							");
						}
					}
				}
			}

			if($exp->protocol != ""){
				foreach($exp->protocol as $protocol){
					$protocol_pkey = $this->db->get_var("select nextval('straboexp.experiment_setup_protocol_pkey_seq');");
					$description = pg_escape_string($protocol->description);
					$objective = pg_escape_string($protocol->objective);

					$this->db->query("
						insert into straboexp.experiment_setup_protocol values (
							$protocol_pkey,
							$experiment_setup_pkey,
							$this->userpkey,
							'$description',
							'$objective'
						)
					");

					if($protocol->parameters != ""){
						foreach($protocol->parameters as $parameter){
							$parameter_pkey = $this->db->get_var("select nextval('straboexp.experiment_setup_protocol_parameter_pkey_seq');");
							$control = pg_escape_string($parameter->control);
							$value = pg_escape_string($parameter->value);
							$unit = pg_escape_string($parameter->unit);
							$note = pg_escape_string($parameter->note);

							$this->db->query("
								insert into straboexp.experiment_setup_protocol_parameter values (
									$parameter_pkey,
									$protocol_pkey,
									$this->userpkey,
									'$control',
									'$value',
									'$unit',
									'$note'
								)
							");
						}
					}
				}
			}

			if($exp->documents != ""){
				foreach($exp->documents as $document){
					$document_pkey = $this->db->get_var("select nextval('straboexp.document_pkey_seq');");
					$type = pg_escape_string($document->type);
					$other_type = pg_escape_string($document->other_type);
					$format = pg_escape_string($document->format);
					$other_format = pg_escape_string($document->other_format);
					$path = pg_escape_string($document->path);
					$id = pg_escape_string($document->id);
					$uuid = pg_escape_string($document->uuid);
					$description = pg_escape_string($document->description);

					if($files[$uuid]["name"] != ""){
						$original_filename = pg_escape_string($files[$uuid]["name"]);
						$tmp_name = pg_escape_string($files[$uuid]["tmp_name"]);

						$path = $original_filename;

						//Move file here
						move_uploaded_file($tmp_name, "/srv/app/www/experimental/expimages/$uuid");
					}else{
						$original_filename = $path;
					}

					$this->db->query("
						insert into straboexp.document
						(
							pkey,
							experiment_setup_pkey,
							userpkey,
							type,
							other_type,
							format,
							other_format,
							id,
							path,
							description,
							uuid,
							original_filename
						) values (
							$document_pkey,
							$experiment_setup_pkey,
							$this->userpkey,
							'$type',
							'$other_type',
							'$format',
							'$other_format',
							'$id',
							'$path',
							'$description',
							'$uuid',
							'$original_filename'
						)
					");
				}
			}

		}

		//Insert Data
		if($json->data != ""){
			$dataSection = $json->data;
			if($dataSection->datasets != ""){
				foreach($dataSection->datasets as $dataset){
					$dataset_pkey = $this->db->get_var("select nextval('straboexp.dataset_pkey_seq');");

					$data = pg_escape_string($dataset->data);
					$type = pg_escape_string($dataset->type);
					$other_type = pg_escape_string($dataset->other_type);
					$format = pg_escape_string($dataset->format);
					$other_format = pg_escape_string($dataset->other_format);
					$id =pg_escape_string( $dataset->id);
					$uuid = pg_escape_string($dataset->uuid);
					$path = pg_escape_string($dataset->path);
					$rating = pg_escape_string($dataset->rating);
					$description = pg_escape_string($dataset->description);

					if($files[$uuid]["name"] != ""){
						$original_filename = pg_escape_string($files[$uuid]["name"]);
						$tmp_name = pg_escape_string($files[$uuid]["tmp_name"]);

						$path = $original_filename;

						//Move file here
						move_uploaded_file($tmp_name, "/srv/app/www/experimental/expimages/$uuid");
					}else{
						$original_filename = $path;
					}

					$this->db->query("
								insert into straboexp.dataset
									(
										pkey,
										experiment_pkey,
										userpkey,
										data,
										type,
										other_type,
										format,
										other_format,
										id,
										path,
										rating,
										description,
										uuid,
										original_filename
									)
									values
									(
										$dataset_pkey,
										$experiment_pkey,
										$this->userpkey,
										'$data',
										'$type',
										'$other_type',
										'$format',
										'$other_format',
										'$id',
										'$path',
										'$rating',
										'$description',
										'$uuid',
										'$path'
									)
								");

					if($dataset->headers != ""){
						foreach($dataset->headers as $header){
							$dataset_header_pkey = $this->db->get_var("select nextval('straboexp.dataset_header_pkey_seq');");

							$header_type = pg_escape_string($header->header->header);
							$spec_a = pg_escape_string($header->header->spec_a);
							$spec_b = pg_escape_string($header->header->spec_b);
							$spec_c = pg_escape_string($header->header->spec_c);
							$unit = pg_escape_string($header->header->unit);
							$type = pg_escape_string($header->type);
							$channel_num = pg_escape_string($header->number);
							$note = pg_escape_string($header->note);
							$rating = pg_escape_string($header->rating);

							$this->db->query("
								insert into straboexp.dataset_header
								(
									pkey,
									dataset_pkey,
									userpkey,
									header_type,
									spec_a,
									spec_b,
									spec_c,
									unit,
									type,
									channel_num,
									note,
									rating
								)values(
									$dataset_header_pkey,
									$dataset_pkey,
									$this->userpkey,
									'$header_type',
									'$spec_a',
									'$spec_b',
									'$spec_c',
									'$unit',
									'$type',
									'$channel_num',
									'$note',
									'$rating'
								)
							");
						}
					}

					if($dataset->parameters != ""){
						foreach($dataset->parameters as $parameter){
							$dataset_parameter_pkey = $this->db->get_var("select nextval('straboexp.dataset_parameter_pkey_seq');");

							$control = pg_escape_string($parameter->control);
							$value = pg_escape_string($parameter->value);
							$error = pg_escape_string($parameter->error);
							$unit = pg_escape_string($parameter->unit);
							$prefix = pg_escape_string($parameter->prefix);
							$note = pg_escape_string($parameter->note);

							$this->db->query("
									insert into straboexp.dataset_parameter
									(
										pkey,
										dataset_pkey,
										userpkey,
										control,
										value,
										error,
										unit,
										prefix,
										note
									)values(
										$dataset_parameter_pkey,
										$dataset_pkey,
										$this->userpkey,
										'$control',
										'$value',
										'$error',
										'$unit',
										'$prefix',
										'$note'
									)
							");

						}
					}

					if($dataset->fluid != ""){
						if($dataset->fluid->phases != ""){
							foreach($dataset->fluid->phases as $phase){
								$dataset_phase_pkey = $this->db->get_var("select nextval('straboexp.dataset_phase_pkey_seq');");

								$composition = pg_escape_string($phase->composition);
								$component = pg_escape_string($phase->component);
								$fraction = pg_escape_string($phase->fraction);
								$activity = pg_escape_string($phase->activity);
								$fugacity = pg_escape_string($phase->fugacity);
								$unit = pg_escape_string($phase->unit);

								$this->db->query("
											insert into straboexp.dataset_phase
											(
												pkey,
												dataset_pkey,
												userpkey,
												composition,
												component,
												fraction,
												activity,
												fugacity,
												unit
											)values(
												$dataset_phase_pkey,
												$dataset_pkey,
												$this->userpkey,
												'$composition',
												'$component',
												'$fraction',
												'$activity',
												'$fugacity',
												'$unit'
											)
								");

								//solutes
								if($phase->solutes != ""){
									foreach($phase->solutes as $solute){
										$solute_pkey = $this->db->get_var("select nextval('straboexp.dataset_phase_solute_pkey_seq');");

										$component = pg_escape_string($solute->component);
										$value = pg_escape_string($solute->value);
										$error = pg_escape_string($solute->error);
										$unit = pg_escape_string($solute->unit);

										$this->db->query("
													insert into straboexp.dataset_phase_solute
													(
														pkey,
														phase_pkey,
														userpkey,
														component,
														value,
														error,
														unit
													)values(
														$solute_pkey,
														$dataset_phase_pkey,
														$this->userpkey,
														'$component',
														'$value',
														'$error',
														'$unit'
													)
										");

									}
								}

							}
						}
					}

				}
			}
		}

		$this->updateProjectKeywords($project_pkey);

		$this->db->query("update straboexp.project set modified_timestamp = now() where pkey = $project_pkey");

		return $json;
	}

}