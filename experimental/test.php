<?php
/**
 * File: test.php
 * Description: Testing and development utility file
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include_once("../adminkeys.php");

//Check for Login Timeout Here
include("apiLoginCheck.php");

include("../prepare_connections.php");
include("../expdb/straboexpclass.php");

$exp = new StraboExp($neodb,$userpkey,$db);

//pass along uuid class
$uuid = new UUID();
$exp->setuuid($uuid);


//$exp->createProjectVersion(74);


//$exp->updateProjectKeywords(126);

$prows = $db->get_results("select * from straboexp.project order by pkey");

$db->dumpVar($prows);

/*
foreach($prows as $p){
	$project_pkey = $p->pkey;
	$exp->updateProjectKeywords($project_pkey);
	echo "$project_pkey<br>";
}
*/











exit();
include("../prepare_connections.php");

$json = new stdClass();
$json->quoteVal = "That's a good idea. \"ok\" I'll give it a try.";
$json = json_encode($json, JSON_PRETTY_PRINT);

$db->dumpVar($json);



exit();


include("../includes/header.php");
?>

<div id="bigWindow">
here
</div>

<?php
include("../includes/footer.php");
?>