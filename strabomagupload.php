<?
include("logincheck.php");

if($_SESSION['username']!="jasonash@ku.edu"){
	header("Location: /");
	exit();
}

include("includes/header.php");

if(isset($_POST["submit"])) {

	//print_r($_FILES);
	//Array ( [fileToUpload] => Array ( [name] => StraboCI.ipa [type] => application/octet-stream [tmp_name] => /tmp/phplayXaT [error] => 0 [size] => 2738405 ) ) 
	
	$file_name = $_FILES['fileToUpload']['name'];
	$tmp_name = $_FILES['fileToUpload']['tmp_name'];

	$file_type = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
	
	if($file_type == "ipa"){
		move_uploaded_file($tmp_name, "strabomag/strabomag.ipa");
		echo "Success!";
	}else{
		echo "wrong file type.";
	}

	include("includes/footer.php");
	exit();
}


?>




<form method="post" enctype="multipart/form-data">
    Select .ipa file to upload:<br>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
    <input type="submit" value="Upload" name="submit">
</form>




<?
include("includes/footer.php");
?>