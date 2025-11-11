<?php
/**
 * File: jsbuilder.php
 * Description: Handles jsbuilder operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "<pre>";
}


//file_get_contents($_FILES['uploadedfile']['tmp_name']);

if($_POST['submit']!=""){

$content = file_get_contents($_FILES['fileToUpload']['tmp_name']);

$showtop = "false";

$rows = explode("\n", $content);
foreach($rows as $row){

	$columns = explode(",", $row);
	$type = $columns[0];
	$name = $columns[1];
	$label = $columns[2];
	$smallindent = 25;
	$bigindent = 30;

	if($type == "begin_group"){
		echo "\$pdf->valueTitle(\"$label: \", $smallindent);\n\n";
	}

	if(substr($type, 0, 10) == "select_one"){
		//addTabValueRow = function (label,value,bordertop)
		echo "if(\$content->".$name."!=\"\"){\n";
		echo "\t\$pdf->valueRow(\"$label\",\$content->".$name.",$bigindent);\n";
		echo "}\n\n";
		$showtop = "true";
	}

	if(substr($type, 0, 15) == "select_multiple"){
		//addTabValueRow = function (label,value,bordertop)
		echo "if(\$content->".$name."!=\"\"){\n";
		echo "\t\$pdf->valueRow(\"$label\",implode(\$content->".$name.", \", \"),$bigindent);\n";
		echo "}\n\n";
		$showtop = "true";
	}

	if($type == "decimal"){
		//addTabValueRow = function (label,value,bordertop)
		echo "if(\$content->".$name."!=\"\"){\n";
		echo "\t\$pdf->valueRow(\"$label\",\$content->".$name.",$bigindent);\n";
		echo "}\n\n";
		$showtop = "true";
	}

	if($type == "text"){
		//addTabValueRow = function (label,value,bordertop)
		echo "if(\$content->".$name."!=\"\"){\n";
		echo "\t\$pdf->valueRow(\"$label\",\$content->".$name.",$bigindent);\n";
		echo "}\n\n";
		$showtop = "true";
	}

}



exit();
}



?>
<form method="post" enctype="multipart/form-data">
  Select CSV to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload" name="submit">
</form>



<?php
/*
<?php

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "<pre>";
}


//file_get_contents($_FILES['uploadedfile']['tmp_name']);

if($_POST['submit']!=""){

$content = file_get_contents($_FILES['fileToUpload']['tmp_name']);

$showtop = "false";

$rows = explode("\n", $content);
foreach($rows as $row){

	$columns = explode(",", $row);
	$type = $columns[0];
	$name = $columns[1];
	$label = $columns[2];

	if($type == "begin_group"){
		echo "tabhtml += addTabTitleRow('$label');\n\n";
	}

	if(substr($type, 0, 10) == "select_one"){
		//addTabValueRow = function (label,value,bordertop)
		echo "if(content.".$name."){\n";
		echo "\ttabhtml += addTabValueRow('$label', content.".$name.", $showtop);\n";
		echo "}\n\n";
		$showtop = "true";
	}

	if(substr($type, 0, 15) == "select_multiple"){
		//addTabValueRow = function (label,value,bordertop)
		echo "if(content.".$name."){\n";
		echo "\ttabhtml += addTabValueRow('$label', content.".$name.".join(', '), $showtop);\n";
		echo "}\n\n";
		$showtop = "true";
	}

	if($type == "decimal"){
		//addTabValueRow = function (label,value,bordertop)
		echo "if(content.".$name."){\n";
		echo "\ttabhtml += addTabValueRow('$label', content.".$name.", $showtop);\n";
		echo "}\n\n";
		$showtop = "true";
	}

	if($type == "text"){
		//addTabValueRow = function (label,value,bordertop)
		echo "if(content.".$name."){\n";
		echo "\ttabhtml += addTabValueRow('$label', content.".$name.", $showtop);\n";
		echo "}\n\n";
		$showtop = "true";
	}

}



exit();
}



?>
<form method="post" enctype="multipart/form-data">
  Select CSV to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload" name="submit">
</form>
*/
?>