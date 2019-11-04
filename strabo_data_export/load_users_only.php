<?PHP
/*
Load users created from export_data.php script
Warning! This script is destructive. It destroys
the neo4j database and repopulates with data
in the "exported_data" folder.

CALL spatial.addWKTLayer('geom','wkt')

match (n) detach delete n;

*/

exit();

include_once("../includes/config.inc.php");
include_once('../geophp/geoPHP.inc');
include("../db.php");
include("../neodb.php");
include("../db/strabospotclass.php");
$strabo = new StraboSpot($neodb,$userpkey,$db);

echo "Clearing the database";

//first, destroy all nodes and relationships:
$neodb->query("match (n) detach delete n;");

echo "...\n";

echo "Creating indexes";

$neodb->query("CREATE INDEX ON :Dataset(id)");
$neodb->query("CREATE INDEX ON :Image(id)");
$neodb->query("CREATE INDEX ON :Orientation(id)");
$neodb->query("CREATE INDEX ON :Project(id)");
$neodb->query("CREATE INDEX ON :RockUnit(id)");
$neodb->query("CREATE INDEX ON :Sample(id)");
$neodb->query("CREATE INDEX ON :Spot(id)");
$neodb->query("CREATE INDEX ON :Tag(id)");
$neodb->query("CREATE INDEX ON :Trace(id)");
$neodb->query("CREATE INDEX ON :User(userpkey)");
$neodb->query("CREATE INDEX ON :_3DStructure(id)");

echo "...\n";

//now, add the spatial layer
//$neodb->query("CALL spatial.addWKTLayer('geom','wkt')");

//exit();

$users=file_get_contents("exported_data/users.json");
$users=json_decode($users);

foreach($users as $user){

	echo "Loading $user->firstname $user->lastname ($user->pkey)";
	
	if($user->pkey != "foo"){//  load all users
	//if($user->pkey != 8){//  don't load Joe's data, it's just too much.
	//if($user->pkey == 3){//  don't load Joe's data, it's just too much.
		unset($thisuser);
		$thisuser = array();

		$userpkey = (int)$user->pkey;
		
		$strabo->setuserpkey($userpkey);

		$thisuser["userpkey"]=(int)$userpkey;
		$thisuser["firstname"]=$user->firstname;
		$thisuser["lastname"]=$user->lastname;
		$thisuser["email"]=$user->email;
		if($user->profileimage){
			$thisuser["profileimage"]=$user->profileimage;
		}

		$thisuser = json_encode($thisuser);

		$userid = $neodb->createNode($thisuser,"User");
		

		
	}
	
	echo "...\n";
}





?>