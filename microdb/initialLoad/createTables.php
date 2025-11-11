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

foreach($files as $file){
	if($file != "." && $file != ".."){
		//dumpVar($file);

		$table_name = str_replace("Type","", $file);
		$table_name = str_replace(".txt", "", $table_name);
		$varname = strtolower("this".$table_name);
		$idname = strtolower($table_name."_id");
		$table_name = "micro_".strtolower($table_name);
		//$table_name = str_replace("Info", "", $table_name);

		//dumpVar($table_name);

		$lines = file_get_contents("JavaFiles/$file");
		$lines = explode("\n", $lines);

		//dumpVar($lines);

		echo "******$table_name<br>";


		echo "\$$idname = \$this->db->get_var(\"select nextval('micro_".$idname."_seq')\");<br>";
		echo "\$query = \"\";<br>";
		echo "\$vars = [];<br>";
		echo "\$vals = [];<br>";



		foreach($lines as $line){

			$line = trim($line);

			if (strpos($line, 'public') !== false && substr($line, -1) == ";" && strpos($line, '[]') == false) {




				$line = str_replace(";", "", $line);
				$line = trim($line);

				$parts = explode(" ", $line);

				//dumpVar($parts);

				if($parts[1] == "Boolean" || $parts[1] == "Double" || $parts[1] == "Long"){
					echo "if(\$$varname->$parts[2]!=\"\"){ ";
					echo "\$vars[]='".strtolower($parts[2])."'; ";
					echo "\$vals[]= \$$varname->$parts[2]; }<br>";
				}elseif($parts[1] == "String"){
					echo "if(\$$varname->$parts[2]!=\"\") {";
					echo "\$vars[]='".strtolower($parts[2])."'; ";
					echo "\$vals[]= \"'\$$varname->$parts[2]'\"; }<br>";
				}elseif($parts[1] == "SimpleCoordType" ||$parts[1] == "Polygon"){
					echo "if(\$$varname->$parts[2]!=\"\") {";
					echo "\$vars[]='".strtolower($parts[2])."'; ";
					echo "\$vals[]= \"'\$$varname->$parts[2]'\"; }//fix this *********<br>";
				}



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

		echo "\$query = \"insert into $table_name (\\n\";<br>";
		echo "\$query .= implode(\",\\n\", \$vars);<br>";
		echo "\$query .= \") values (\\n\";<br>";
		echo "\$query .= implode(\",\\n\", \$vals);<br>";
		echo "\$query .= \")\\n\";<br>";

		echo "<br><br>";

	}
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