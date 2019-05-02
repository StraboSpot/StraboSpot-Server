<?
session_start();

if($_SESSION['loggedin']=="yes"){

	include("prepare_connections.php");
	$userpkey = $_SESSION['userpkey'];
	
	$projectid = $_GET['projectid'];
	$state = $_GET['state'];
	
	if($userpkey!="" && $projectid!="" && $state!=""){
	
		/*
		if($state=="public"){
			$neodb->query("match (p:Project {id:$projectid,userpkey:$userpkey}) set p.public = true;");
			//echo "match (p:Project {id:$projectid,userpkey:$userpkey}) set p.public = true;";
		}else{
			$neodb->query("match (p:Project {id:$projectid,userpkey:$userpkey}) remove p.public;");
			//echo "match (p:Project {id:$projectid,userpkey:$userpkey}) remove p.public;";
		}
		*/
		
		//$id = $strabo->createId();

		$p = $strabo->getProject($projectid);
		
		//$p = json_encode($p,JSON_PRETTY_PRINT);
		//$strabo->dumpVar($p);exit();
		
		unset($p->self);

		if($state=="public"){
			if(!$p->preferences->public){
				$p->preferences->public = true;
				$p->modified_timestamp=(int)$strabo->createId();
				$p = json_encode($p,JSON_PRETTY_PRINT);
				$strabo->insertProject($p);
			}
		}else{
			if($p->preferences->public){
				$neodb->query("match (p:Project {id:$projectid,userpkey:$userpkey}) remove p.public;");
				unset($p->preferences->public);
				unset($p->preferences->Sharing);
				$p->modified_timestamp=(int)$strabo->createId();
				$p = json_encode($p,JSON_PRETTY_PRINT);
				$strabo->insertProject($p);
			}
		}

	}	

}

?>