<?
/*
match (u:User) where u.firstname =~ 'wash.*' return u;

*/

exit();
include_once "./includes/config.inc.php";
include("db.php");
include("neodb.php");


$names = array(

"fc1",
"fc2",
"fc3",
"fc4",
"fc5",
"fc6",
"fc7",
"fc8",
"fc9",
"fc10",
"fc11",
"fc12",
"fc13",
"fc14",
"fc15",
"fc16",
"fc17",
"fc18",
"fc19",
"fc20",
"fc1-17",
"fc2-17",
"fc3-17",
"fc4-17",
"fc5-17",
"fc6-17",
"fc7-17",
"fc8-17",
"fc9-17",
"fc10-17",
"fc11-17",
"fc12-17",
"fc13-17",
"fc14-17",
"fc15-17",
"fc16-17",
"fc17-17",
"fc18-17",
"fc19-17",
"fc20-17",
"fc21-17",
"fc22-17",
"fc23-17",
"fc24-17",
"fc25-17",
"fc1-18",
"fc2-18",
"fc3-18",
"fc4-18",
"fc5-18",
"fc6-18",
"fc7-18",
"fc8-18",
"fc9-18",
"fc10-18",
"fc11-18",
"fc12-18",
"fc13-18",
"fc14-18",
"fc15-18",
"fc16-18",
"fc17-18",
"fc18-18",
"fc19-18",
"fc20-18",
"fc21-18",
"fc22-18",
"fc23-18",
"fc24-18",
"fc25-18",
"fc26-18",
"fc27-18",
"fc28-18",
"fc29-18",
"fc30-18",
"fc31-18",
"fc32-18",
"fc33-18",
"fc34-18",
"fc35-18"

);

foreach($names as $name){

	$db->query("update users set password = crypt('rockrockrock', gen_salt('md5')) where email='$name';");
	echo "name: $name done.<br>";


}






?>