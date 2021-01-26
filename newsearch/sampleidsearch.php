<?
session_start();

if($_SESSION['userpkey']!=""){
	$userpkey = $_SESSION['userpkey'];
}else{
	$userpkey = 0;
}

include("../includes/config.inc.php");
include("../db.php");

$term = strtolower($_GET['term']);

$out = [];

if($term != ""){

	//$rows = $db->get_results("select pkey, firstname, lastname from users where lower(lastname)||', '||lower(firstname) like '%$term%' and active=TRUE order by lastname, firstname");
	
	
	$rows = $db->get_results("
								select
								sample_id
								from
								users u,
								project p,
								sample s
								
								
								where
								u.pkey = p.user_pkey
								and p.project_pkey = s.project_pkey
								and (p.ispublic = true or p.user_pkey = $userpkey)
								 
								and lower(sample_id) like '%$term%'
								group by sample_id
								order by sample_id
							
							");
	
	
	//$rows = $db->get_results("select sample_id from sample where lower(sample_id) like '%$term%' group by sample_id order by sample_id");

	foreach($rows as $row){
		$thissample = [];
		$thissample['label'] = $row->sample_id;
		$thissample['value'] = $row->sample_id;
		//$lastname = $row->lastname;
		//$firstname = $row->firstname;
		//$pkey = $row->pkey;
		//$thisuser['id'] = $pkey;
		//$thisuser['label'] = "$lastname, $firstname";
		//$thisuser['value'] = "$lastname, $firstname";
		$out[] = $thissample;
	}
}

header('Content-Type: application/json');
$out = json_encode($out, JSON_PRETTY_PRINT);
echo $out;





?>