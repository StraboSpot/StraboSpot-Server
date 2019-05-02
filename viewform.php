<?
include("logincheck.php");
include("includes/config.inc.php");
include("db.php");

$userpkey=(int)$_SESSION['userpkey'];
$userlevel=(int)$_SESSION['userlevel'];

$pkey=$_GET['id'];

$content = $db->get_var("select content from kobo where pkey = $pkey");

if($content==""){exit();}

include 'includes/header.php';

echo $content;

include 'includes/footer.php';

?>