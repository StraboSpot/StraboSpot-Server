<?

$id=time().rand(1111,9999);

include("logincheck.php");
include("includes/header.php");




?>


<h3>Image Upload Test</h3>

<div style="width:600px;">
Use the form below to test image upload into the StraboSpot Database.<br>
Form Method: POST<br>
Form enctype: multipart/form-data<br>
Endpoint: https://strabospot.org/db/image
</div>

<br><br>

<form method="post" action="/db/image" enctype="multipart/form-data">

<table>

	<tr><td>id</td><td><input type="text" name="id" value="<?=$id?>"></td></tr>

	<tr><td>modified_timestamp</td><td><input type="text" name="modified_timestamp" value="<?=substr($id,0,10)?>"></td></tr>

	<tr><td>image_file</td><td><input type="file" name="image_file"></td></tr>
	
	<tr><td colspan="2"><div align="center"><input type="submit" name="Submit" value="Submit"></div></td></tr>

</table>

</form>



















<?
include 'includes/footer.php';
?>