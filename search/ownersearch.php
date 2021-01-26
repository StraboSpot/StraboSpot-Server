<?
session_start();

//$_SESSION[userpkey] => 3;

if($_SESSION['userpkey']!=""){
	$userpkey = $_SESSION['userpkey'];
}else{
	$userpkey = 0;
}

include("../includes/config.inc.php");
include("../db.php");

//$db->dumpVar($_SESSION);exit();

$term = strtolower($_GET['term']);

$out = [];

if($term != ""){

	$rows = $db->get_results("
								select 
								users.pkey, 
								firstname, 
								lastname 
								from 
								users, project
								where 
								(project.ispublic = true or project.user_pkey = $userpkey)
								and users.pkey = project.user_pkey
								and lower(lastname)||', '||lower(firstname) like '%$term%'
								and users.active=TRUE
								group by users.pkey, firstname, lastname order by lastname, firstname
	
	
							");
	
	$srows = $db->get_results("
								select 
								pkey, firstname, lastname 
								from 
								users where lower(lastname)||', '||lower(firstname) like '%$term%' and active=TRUE order by lastname, firstname
	
	
							");
	


	foreach($rows as $row){
		$thisuser = [];
		$lastname = $row->lastname;
		$firstname = $row->firstname;
		$pkey = $row->pkey;
		$thisuser['id'] = $pkey;
		$thisuser['label'] = "$lastname, $firstname";
		$thisuser['value'] = "$pkey";
		$out[] = $thisuser;
	}
}

header('Content-Type: application/json');
$out = json_encode($out, JSON_PRETTY_PRINT);
echo $out;





?>