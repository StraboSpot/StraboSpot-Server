<?php
/**
 * File: uploadkmz.php
 * Description: Handles uploadkmz operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


//accept KMZ file and parse it into points and lines for map display.
//store as .json file

function dumpVar($var){
	echo "<pre>\n";
	print_r($var);
	echo "\n</pre>";
}

if(isset($_POST["submit"])){

	exec("rm -rf ziptemp

					$pointsout['type']='FeatureCollection';
					$pointsout['features']=$pointfeatures;
					$pointsout = json_encode($pointsout,JSON_PRETTY_PRINT);

					$linesout['type']='FeatureCollection';
					$linesout['features']=$linefeatures;
					$linesout = json_encode($linesout,JSON_PRETTY_PRINT);

					exec("rm ../data/lines.json");
					exec("rm ../data/points.json");
					exec("rm ../data/EratosthenesMap.kmz");

					move_uploaded_file($_FILES['fileToUpload']['tmp_name'], "../data/EratosthenesMap.kmz");
					file_put_contents("../data/lines.json", $linesout);
					file_put_contents("../data/points.json", $pointsout);

					include("adminheader.php");
					echo "Success! KMZ file has been uploaded successfully.";
					echo "<br><br><div class=\"linkheading\"><a href=\".\">Continue</a></div>";
					include("adminfooter.php");
					exit();
				}else{
					include("adminheader.php");
					echo "Error: No data found in KMZ file.";
					echo "<br><br><div class=\"linkheading\"><a href=\".\">Continue</a></div>";
					include("adminfooter.php");
					exit();
				}

			} else {
				include("adminheader.php");
				echo 'Error: Unzip of KMZ failed, code:' . $res;
				echo "<br><br><div class=\"linkheading\"><a href=\".\">Continue</a></div>";
				include("adminfooter.php");
				exit();
			}

		}else{
			include("adminheader.php");
			echo "Error: wrong file type. must be kmz";
			echo "<br><br><div class=\"linkheading\"><a href=\".\">Continue</a></div>";
			include("adminfooter.php");
			exit();
		}
	}else{
		include("adminheader.php");
		echo "Error: ".$_FILES['fileToUpload']['error'];
		echo "<br><br><div class=\"linkheading\"><a href=\".\">Continue</a></div>";
		include("adminfooter.php");
		exit();
	}

	exit();
}

include("adminheader.php");
?>

Uploading a KMZ file will completely replace the existing map. It would be best to first<br>
download the existing map <a href="../data/EratosthenesMap.kmz">here</a> and then modify and re-upload.<br><br>

<form method="post" enctype="multipart/form-data">
	Select KMZ file to upload:<br><br>
	<input type="file" name="fileToUpload" id="fileToUpload"><br><br>
	<input type="submit" value="Upload" name="submit">
</form>

<?php
include("adminfooter.php");
?>