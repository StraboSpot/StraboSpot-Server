<?
include("logincheck.php");

$userpkey = $_SESSION['userpkey'];

//echo "userpkey: $userpkey";exit();

$instrument_pkey = $_GET['ii'];
if($instrument_pkey == "" || !is_numeric($instrument_pkey)){
	exit();
}

include("prepare_connections.php");

$credentials = $_SESSION['credentials'];

$instcount = $db->get_var("
	select count(*) from instrument_users where users_pkey = $userpkey;
");

$admin_pkeys = array(3,9,500);

if(!in_array($userpkey, $admin_pkeys) && $instcount == 0){
	exit();
}

if(in_array($userpkey, $admin_pkeys)){
	$db->query("
		delete from instrument where pkey = $instrument_pkey;
	");
}else{
	$institutionrow = $db->get_row("
		select ii.*
		from
		instrument i,
		instrument_users iu,
		instrument_institution ii
		where
		ii.pkey = i.institution_pkey
		and iu.institution_pkey = ii.pkey
		and iu.users_pkey = $userpkey
		and i.pkey = $instrument_pkey
	");
	
	if($institutionrow->pkey != ""){
		$db->query("delete from instrument where pkey = $instrument_pkey;");
	}
	
}

header("Location: instrumentcatalog");
?>