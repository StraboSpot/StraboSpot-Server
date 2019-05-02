<?
include("logincheck.php");
include("includes/header.php");
?>


<h3>Profile Image Upload Test</h3>

<div style="width:600px;">
Use the form below to test profile image upload into the StraboSpot Database.<br>
Form Method: POST<br>
Form enctype: multipart/form-data
</div>

<br><br>

<form method="POST" action="/db/profileimage" enctype="multipart/form-data">

<table>

	<tr><td>image_file</td><td><input type="file" name="image_file"></td></tr>
	
	<tr><td colspan="2"><div align="center"><input type="submit" name="Submit" value="Submit"></div></td></tr>

</table>

</form>



<?
include 'includes/footer.php';
?>