<?php

	/**********************************************************************
	*  Author: Jason Ash (jasonash@ku.edu)
	*  Web...: http://jasonash.com
	*  Name..: ezneo4j
	*  Desc..: ezneo4j is an abstraction library to make
	*          it very easy to deal with neo4j databases.
				
	*/

	require_once 'includes/classes/vendor/autoload.php';

	use GraphAware\Neo4j\Client\ClientBuilder;



	class ezneo4j
	{

		var $neousername = false;
		var $neopassword = false;
		var $neohost = false;
		var $neoport = false;
		var $neomode = false; //default for REST or bolt for bolt

		/**********************************************************************
		*  Constructor
		*/

		function ezneo4j($neousername='', $neopassword='', $neohost='', $neoport='', $neomode='')
		{
			$this->neousername = $neousername;
			$this->neopassword = $neopassword;
			$this->neohost = $neohost;
			$this->neoport = $neoport;
			$this->neomode = $neomode;
			
			if($neomode=="bolt"){
				$protocol="bolt";
			}else{
				$protocol="http";
			}

			$this->client = ClientBuilder::create()
				->addConnection("$neomode", "$protocol://$neousername:$neopassword@$neohost:$neoport") // Example for BOLT connection configuration (port is optional)
				->build();

		}

		/**********************************************************************
		*  Test Function.
		*/

		function ntest()
		{
			$result = $this->client->run("match (a) return count(a);");
			$records = $result->getRecords();
			return($records);
		}

		function dumpVar($var){
			echo "<pre>";
			print_r($var);
			echo "</pre>";
		}

		function logToFile($string,$label){
			if(is_writable("/var/www/db2/log.txt")){
				if($label==""){$label="LogToFile";}
				file_put_contents ("/var/www/db2/log.txt", "\n\n$label $string \n", FILE_APPEND);

			}
		}

		function isJson($string) {
			json_decode($string);
			return (json_last_error() == JSON_ERROR_NONE);
		}

		function escape($json){
			if($this->isJson($json)){

				$delim="";
				$returnstring="";
				$json=json_decode($json,true);
				//$this->dumpVar($json);exit();
				foreach($json as $key=>$value){
					if(is_string($value)){
						$value=addslashes($value);
					}
					
					if(is_bool($value) && $value!=""){
						$returnstring = $returnstring.$delim.$key.":".$value;
					}elseif(is_string($value)){
						$returnstring = $returnstring.$delim.$key.":"."\"".$value."\"";
					}elseif(!$value){
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

		function query($querystring=''){
			$result = $this->client->run("$querystring");
			$records = $result->getRecords();
			return($records);
		}

		//alias for readability
		function get_results($querystring){ 
			return $this->query($querystring);
		}

		function get_var($querystring=''){
			$records = $this->query($querystring);
			$record = $records[0];
			if($record){
				$value = $record->value($record->keys()[0]);
				return $value;
			}else{
				return "";
			}
		}

		/**********************************************************************
		*  Create new node.
		*/
		function createNode($injson='',$label=null){
			if($label){$label=":$label";}
			$injson = $this->escape($injson); //convert to javascript notation for neo4j
			$result = $this->client->run("Create (n$label $injson) return id(n)");
			$record = $result->getRecord();
			$id = $record->value('id(n)');
			$this->client->run("match (n) where id(n)=$id set n :Strabo return n;");
			return($id);
		}

		/**********************************************************************
		*  Get Single Node
		*/
		function getNode($querystring=''){
			$result = $this->client->run("$querystring");
			$records = $result->getRecords();
			$record=$records[0];

			if($record!=""){
				$value = $record->value($record->keys()[0]);
				$values = $value->values();
				return $values;
			}else{
				return null;
			}
			
		}

		/**********************************************************************
		*  Get Single Record (possibly including multiple nodes)
		*/
		function getRecord($querystring=''){
			$result = $this->client->run("$querystring");
			$records = $result->getRecords();
			$record=$records[0];
			
			if($record){
				return $record;
			}else{
				return null;
			}
			
		}


		/**********************************************************************
		*  Add node to spatial layer.
		*/
		
		function addNodeToSpatial($id){
			$count = $this->get_var("match ()-[r:RTREE_REFERENCE]->(s:Spot) where id(s)=$id return count(r);");
			if($count==0){
				$result = $this->client->run("match (n) where id(n)=$id with n call spatial.addNode('geom',n) YIELD node as r return id(r)");
				$record = $result->getRecord();
				$id = $record->value('id(r)');
			}
			return($id);
		}

		/**********************************************************************
		*  Add ID attribute to node based on internal Neo4j id.
		*/
		
		function addIdToNode($id=null){
			$result = $this->client->run("match (n) where id(n)=$id set n.id=id(n) return id(n)");
			$record = $result->getRecord();
			$id = $record->value('id(n)');
			return($id);
		}

		/**********************************************************************
		*  Add label to node.
		*/
		
		function addLabelToNode_deprecated($id=null,$label=''){
			//this should be deprecated due to the generation of label when creating node
			$result = $this->client->run("match (n) where id(n)=$id set n :$label return id(n)");
			$record = $result->getRecord();
			$id = $record->value('id(n)');
			return($id);
		}
		
		/**********************************************************************
		*  Update node attributes.
		*/
		
		function updateNode($id=null,$injson='',$label=""){
			$injson = $this->escape($injson); //convert to javascript notation for neo4j
			
			if($label!=""){$label=":".$label;}
			
			$this->logToFile("match (n".$label.") where id(n)=$id SET n = $injson return id(n)","NEO4J");
			
			$result = $this->client->run("match (n".$label.") where id(n)=$id SET n = $injson return id(n)");
			$record = $result->getRecord();
			$id = $record->value('id(n)');
			return($id);
		}
		
		/**********************************************************************
		*  Add relationship (edge) between two nodes.
		*  From and To are ids of nodes
		*/
		
		function addRelationship($from=null, $to=null, $type='', $sidealabel="", $sideblabel="" ){
		
			if($sidealabel!=""){$sidealabel=":".$sidealabel;}
			if($sideblabel!=""){$sideblabel=":".$sideblabel;}
		
			$result = $this->client->run("MATCH (from".$sidealabel."), (to".$sideblabel.") where id(from)=$from and id(to)=$to CREATE UNIQUE (from)-[r:$type]->(to) return id(r)");
			$record = $result->getRecord();
			$id = $record->value('id(r)');
			return($id);
		}

		
		

	}
