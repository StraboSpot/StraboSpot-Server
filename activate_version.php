<?
include("logincheck.php");

include("prepare_connections.php");

$uuid = $_GET['uuid'];

if($uuid==""){
	exit();
};

include("includes/header.php");

?>


<div id="mymessage" align ="center" style="font-size:1.9em;"><img src="assets/js/images/box.gif">Activating Version...</div>
<div id="continue" align ="center" style="font-size:1.9em;display:none;"><a href="my_data">Continue<a></div>


<?



// This is for the buffer achieve the minimum size in order to flush data
echo str_repeat(' ',1024*128);

// Send output to browser immediately
flush();

//sleep(3);

$strabo->switchVersion("$uuid");

echo "\n".'<script type="text/javascript">
document.getElementById("mymessage").innerHTML="Success! Version loaded successfully.";
document.getElementById("continue").style.display="block";
</script>';



// Send output to browser immediately
echo str_repeat(' ',1024*128);
flush();




include("includes/footer.php");
?>