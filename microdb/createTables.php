<?php
/**
 * File: createTables.php
 * Description: Handles createTables operations
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

//Create Tables

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

$files = scandir("JavaFiles");

//dumpVar($files);

$found = [];

foreach($files as $file){
	if($file != "." && $file != ".."){
		//dumpVar($file);

		$table_name = str_replace("Type","", $file);
		$table_name = str_replace(".txt", "", $table_name);
		$table_name = "micro_".strtolower($table_name);
		//$table_name = str_replace("Info", "", $table_name);

		//dumpVar($table_name);

		$lines = file_get_contents("JavaFiles/$file");
		$lines = explode("\n", $lines);

		//dumpVar($lines);

		$sql = "";
		//$sql = "CREATE TABLE $table_name (\n";
		//$sql .= "\tid SERIAL PRIMARY KEY,\n";

		foreach($lines as $line){

			if (strpos($line, 'public') !== false && strpos($line, ';') !== false) {

				$line = str_replace(";", "", $line);
				$line = trim($line);

				$parts = explode(" ", $line);

				//dumpVar($parts);

				if($parts[1] == "String"){
					$datatype = "VARCHAR";
				}elseif($parts[1] == "Double"){
					$datatype = "DOUBLE PRECISION";
				}elseif($parts[1] == "Boolean"){
					$datatype = "BOOLEAN";
				}else{
					$datatype = $parts[1];
				}

				$sql .= strtolower($parts[2])."\n";

				$thisval = strtolower($parts[2]);
				$thisval = ucfirst($parts[2]);

				if(!in_array($thisval, $found)){
					if (strpos($thisval, '(') == false){
						$found[] = $thisval;
					}
				}

			}
		}

		//$sql = substr($sql, 0, -2);
		//$sql .= "\n);\n\n";



		//dumpVar($sql);



	}
}

sort($found);
foreach($found as $f){
	echo "$f<br>";
}


exit();

/*





foreach($files as $file){
	if($file != "." && $file != ".."){
		//dumpVar($file);

		$table_name = str_replace("Type","", $file);
		$table_name = str_replace(".txt", "", $table_name);
		$table_name = "micro_".strtolower($table_name);
		//$table_name = str_replace("Info", "", $table_name);

		//dumpVar($table_name);

		$lines = file_get_contents("JavaFiles/$file");
		$lines = explode("\n", $lines);

		//dumpVar($lines);



		foreach($lines as $line){

			if (strpos($line, 'public') !== false && strpos($line, ';') !== false) {


				echo "$line<br>";


				$line = str_replace(";", "", $line);
				$line = trim($line);

				$parts = explode(" ", $line);

				//dumpVar($parts);

				if($parts[1] == "String"){
					$datatype = "VARCHAR";
				}elseif($parts[1] == "Double"){
					$datatype = "DOUBLE PRECISION";
				}elseif($parts[1] == "Boolean"){
					$datatype = "BOOLEAN";
				}else{
					$datatype = $parts[1];
				}



			}
		}

		echo "<br>";

	}
}









foreach($files as $file){
	if($file != "." && $file != ".."){
		//dumpVar($file);

		$table_name = str_replace("Type","", $file);
		$table_name = str_replace(".txt", "", $table_name);
		$table_name = "micro_".strtolower($table_name);
		//$table_name = str_replace("Info", "", $table_name);

		//dumpVar($table_name);

		$lines = file_get_contents("JavaFiles/$file");
		$lines = explode("\n", $lines);

		//dumpVar($lines);

		$sql = "CREATE TABLE $table_name (\n";
		$sql .= "\tid SERIAL PRIMARY KEY,\n";

		foreach($lines as $line){

			if (strpos($line, 'public') !== false && strpos($line, ';') !== false) {

				$line = str_replace(";", "", $line);
				$line = trim($line);

				$parts = explode(" ", $line);

				//dumpVar($parts);

				if($parts[1] == "String"){
					$datatype = "VARCHAR";
				}elseif($parts[1] == "Double"){
					$datatype = "DOUBLE PRECISION";
				}elseif($parts[1] == "Boolean"){
					$datatype = "BOOLEAN";
				}else{
					$datatype = $parts[1];
				}

				$sql .= "\t".strtolower($parts[2])." ".$datatype.",\n";

			}
		}

		$sql = substr($sql, 0, -2);
		$sql .= "\n);\n\n";

		dumpVar($sql);

	}
}


*/
?>