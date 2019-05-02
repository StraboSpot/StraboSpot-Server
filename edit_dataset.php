<?

include("logincheck.php");
include("includes/config.inc.php");
include("db.php");
include("neodb.php");

$userpkey=(int)$_SESSION['userpkey'];

$id = $_GET['id']; 

$rows = $neodb->query("match (a:Dataset) where a.id=$id and a.userpkey = $userpkey return distinct(a) limit 1;");

//$neodb->dumpVar($rows);//exit();


$row = (object)$rows[0]->get("a")->values();

//$neodb->dumpVar($row);exit();


$name = $row->name;

if($name==""){
	include 'includes/header.php';
	?>
	<h2>Dataset not found.</h2>
	<?
	include 'includes/footer.php';
	exit();
}



//echo "datasetname: $name";exit();


if($_POST['submit']!=""){




	//print_r($_POST);

	foreach($_POST as $key=>$value){
		eval("\$$key=\$value;");
	}


	if($name==""){
		include 'includes/header.php';
		echo "Error! Dataset Name cannot be blank.";
		include 'includes/footer.php';
		exit();
	}

	$name=addslashes($name);
	
	//echo "match (a:Dataset) where a.id=$id and a.userpkey = $userpkey set a.name='$name'";exit();
	
	//update here
	$neodb->query("match (a:Dataset) where a.id=$id and a.userpkey = $userpkey set a.name='$name'");
	
	include 'includes/header.php';
	
	?>
	<h2>Dataset Updated Successfully.</h2>

	<br>
	<br>
	
	<a href="my_data">Continue...</a>





	<?

	include 'includes/footer.php';

	exit();
}










include 'includes/header.php';

?>

<script type="text/javascript">

	function showdiv(myid) {
		document.getElementById(myid).style.display='block';
	}
	
	function validateForm(){
	
		var myerror='';
		var mydelim=''
	
		if(document.getElementById('name').value==""){
			myerror=myerror+mydelim+'Dataset Name cannot be blank.';
			mydelim='\n';
		}
		
		if(myerror!=""){
			alert(myerror);
			return false;
		}
	
	}

</script>

<h2>Edit Dataset</h2>

<div style="padding-left:20px">

Items with (<span style="color:red;font-weight:bold;">*</span>) are required.

</div>


<br>


<form method="POST" onsubmit="return validateForm()">

<h3>Dataset Description</h3><br>
<hr>
<br>

<table>

	<tr><td nowrap><div align="right">Dataset Name:<span style="color:red;font-weight:bold;">*</span></div></td><td nowrap><input type="text" id="name" name="name" value="<?=$name?>"></td></tr>

</table>



<br>
<hr>
<br>



<input type="submit" value="Submit" name="submit">

<input type="hidden" name="datasetid" value="<?=$id?>">



</form>





<?
include 'includes/footer.php';
?>