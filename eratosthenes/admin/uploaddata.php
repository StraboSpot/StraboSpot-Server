<?php
/**
 * File: uploaddata.php
 * Description: Handles uploaddata operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

//accept xlsx file and parse it into json file.

function dumpVar($var){
	echo "<pre>\n";
	print_r($var);
	echo "\n</pre>";
}

if(isset($_POST["submit"])){

	if(!$_FILES['fileToUpload']['error']){

		$pathinfo = pathinfo($_FILES['fileToUpload']['name']);
		if($pathinfo['extension']=="xlsx"){

			/** Create a new Xls Reader  **/
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($_FILES['fileToUpload']['tmp_name']);

			$data = $spreadsheet->getActiveSheet()
				->rangeToArray(
					'A4:P2000',     // The worksheet range that we want to retrieve
					NULL,        // Value that should be returned for empty cells
					TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
					TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
					FALSE         // Should the array be indexed by cell row and cell column
				);

			if($data[1996][0]=='eratostherus'){
				$measurements = [];
				foreach($data as $row){
					$thismeasurement = [];
					if($row[0]!="" && $row[0]!='eratostherus'){
						$thismeasurement['date_of_measurement']=$row[0];
						$thismeasurement['time_of_measurement']=$row[1];
						$thismeasurement['location_1']=$row[2];
						$thismeasurement['location_1_stick_length']=$row[3];
						$thismeasurement['location_1_stick_length_err']=$row[4];
						$thismeasurement['location_1_shadow']=$row[5];
						$thismeasurement['location_1_shadow_err']=$row[6];
						$thismeasurement['location_1_angle']=$row[7];
						$thismeasurement['location_2']=$row[8];
						$thismeasurement['location_2_stick_length']=$row[9];
						$thismeasurement['location_2_stick_length_err']=$row[10];
						$thismeasurement['location_2_shadow']=$row[11];
						$thismeasurement['location_2_shadow_err']=$row[12];
						$thismeasurement['location_2_angle']=$row[13];
						$thismeasurement['calculated_circumference']=$row[14];
						$thismeasurement['percent_accurate']=$row[15];

						$measurements[]=$thismeasurement;
					}
				}

				$measurements;
				$measurements = json_encode($measurements,JSON_PRETTY_PRINT);

				move_uploaded_file($_FILES['fileToUpload']['tmp_name'], "../data/EratosthenesData.xlsx");
				file_put_contents("../data/measurements.json", $measurements);

				include("adminheader.php");
				echo "Success! XLSX file has been uploaded successfully.";
				echo "<br><br><div class=\"linkheading\"><a href=\".\">Continue</a></div>";
				include("adminfooter.php");
				exit();
			}else{
				include("adminheader.php");
				?>
				Error: Invalid sheet. Please use the sheet found <a href="../data/EratosthenesData.xlsx">here</a>.
				<?php
				echo "<br><br><div class=\"linkheading\"><a href=\".\">Continue</a></div>";
				include("adminfooter.php");
				exit();
			}
		}else{
			include("adminheader.php");
			echo "Error: wrong file type. must be xlsx";
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

Data is uploaded to the Eratosthenes Project using the XLSX template found <a href="../data/EratosthenesData.xlsx">here</a>.<br>
Please note that the data is completely overwritten when uploaded, so all data<br>
must be preserved in the master spreadsheet. It would be best to download the<br>
spreadsheet each time data is added using this <a href="../data/EratosthenesData.xlsx">link</a>, add data, and then upload the <br>
entire dataset.<br><br>

<form method="post" enctype="multipart/form-data">
	<br>
	Select XLSX file to upload:<br><br>
	<input type="file" name="fileToUpload" id="fileToUpload"><br><br>
	<input type="submit" value="Upload" name="submit">
</form>

<?php
include("adminfooter.php");
?>