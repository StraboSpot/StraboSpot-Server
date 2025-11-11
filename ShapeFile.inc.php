<?php
/**
 * File: ShapeFile.inc.php
 * Description: ShapeFile class definition
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

/**
	* This class is under GPL Licencense Agreement
	* @author Juan Carlos Gonzalez Ulloa <jgonzalez@innox.com.mx>
	* Innovacion Inteligente S de RL de CV (Innox)
	* Lopez Mateos Sur 2077 - Z16
	* Col. Jardines de Plaza del Sol
	* Guadalajara, Jalisco
	* CP 44510
	* Mexico
	*
	* Class to read SHP files and modify the DBF related information
	* Just create the object and all the records will be saved in $shp->records
	* Each record has the "shp_data" and "dbf_data" arrays with the record information
	* You can modify the DBF information using the $shp_record->setDBFInformation($data)
	* The $data must be an array with the DBF's row data.
	*
	* Performance issues:
* Because the class opens and fetches all the information (records/dbf info)
	* from the file, the loading time and memory amount neede may be way to much.
	* Example:
*   15 seconds loading a 210907 points shape file
	*   60Mb memory limit needed
	*   Athlon XP 2200+
	*   Mandrake 10 OS
	*
	*
	*
	* Edited by David Granqvist March 2008 for better performance on large files
	* This version only get the information it really needs
	* Get one record at a time to save memory, means that you can work with very large files.
	* Does not load the information until you tell it too (saves time)
	* Added an option to not read the polygon points can be handy sometimes, and saves time :-)
	*
	* Example:

	//sets the options to show the polygon points, 'noparts' => true would skip that and save time
	$options = array('noparts' => false);
	$shp = new ShapeFile("../../php/shapefile/file.shp",$options);

	//Dump the ten first records
	$i = 0;
	while ($record = $shp->getNext() and $i<10) {
		$dbf_data = $record->getDbfData();
		$shp_data = $record->getShpData();
		//Dump the information
		var_dump($dbf_data);
		var_dump($shp_data);
		$i++;
	}
	*
	*/

// Configuration
define("SHOW_ERRORS", true);
define("DEBUG", false);

// Constants
define("XY_POINT_RECORD_LENGTH", 16);

// Strings
define("ERROR_FILE_NOT_FOUND", "SHP File not found [%s]");
define("INEXISTENT_RECORD_CLASS", "Unable to determine shape record type [%i]");
define("INEXISTENT_FUNCTION", "Unable to find reading function [%s]");
define("INEXISTENT_DBF_FILE", "Unable to open (read/write) SHP's DBF file [%s]");
define("INCORRECT_DBF_FILE", "Unable to read SHP's DBF file [%s]");
define("UNABLE_TO_WRITE_DBF_FILE", "Unable to write DBF file [%s]");

class ShapeFile{
	private $file_name;
	private $fp;
	private $dbf_filename = null;
	//Starting position is 100 for the records
	private $fpos = 100;

	private $error_message = "";
	private $show_errors   = SHOW_ERRORS;

	private $shp_bounding_box = array();
	public $shp_type         = 0;

	public $records;

	function ShapeFile($file_name,$options){
		$this->options = $options;

		$this->file_name = $file_name;
		if(!is_readable($file_name)){
			return $this->setError( sprintf(ERROR_FILE_NOT_FOUND, $file_name) );
		}

		$this->fp = fopen($this->file_name, "rb");

		$this->_fetchShpBasicConfiguration();

		//Set the dbf filename
		$this->dbf_filename = processDBFFileName($this->file_name);

	}

	public function getError(){
		return $this->error_message;
	}

	function __destruct()
	{
		$this->closeFile();
	}

	// Data fetchers
	private function _fetchShpBasicConfiguration(){
		fseek($this->fp, 32, SEEK_SET);
		$this->shp_type = readAndUnpack("i", fread($this->fp, 4));

		$this->shp_bounding_box = readBoundingBox($this->fp);
	}

	public function getNext(){
		if (!feof($this->fp)) {
			fseek($this->fp, $this->fpos);
			$shp_record = new ShapeRecord($this->fp, $this->dbf_filename,$this->options);
			if($shp_record->getError() != ""){
				return false;
			}
			$this->fpos = $shp_record->getNextRecordPosition();
			return $shp_record;
		}
		return false;
	}

	

	

//Not Used

	// General functions
	private function setError($error){
		$this->error_message = $error;
		if($this->show_errors){
			echo $error."\n";
		}
		return false;
	}

	private function closeFile(){
		if($this->fp){
			fclose($this->fp);
		}
	}

}

/**
	* ShapeRecord
	*
	*/
class ShapeRecord{
	private $fp;
	private $fpos = null;

	private $dbf = null;

	private $record_number     = null;
	private $content_length    = null;
	private $record_shape_type = null;

	private $error_message     = "";

	private $shp_data = array();
	private $dbf_data = array();

	private $file_name = "";

	private $record_class = array(  0 => "RecordNull",
		1 => "RecordPoint",
		8 => "RecordMultiPoint",
		3 => "RecordPolyLine",
		5 => "RecordPolygon",
		13 => "RecordMultiPointZ",
		11 => "RecordPointZ");

	function ShapeRecord(&$fp, $file_name,$options){
		$this->fp = $fp;
		$this->fpos = ftell($fp);
		$this->options = $options;

		if (feof($fp)) {
			echo "end ";
			exit;
		}
		$this->_readHeader();

		$this->file_name = $file_name;

	}

	public function getNextRecordPosition(){
		$nextRecordPosition = $this->fpos + ((4 + $this->content_length )* 2);
		return $nextRecordPosition;
	}

	private function _readHeader(){
		$this->record_number     = readAndUnpack("N", fread($this->fp, 4));
		$this->content_length    = readAndUnpack("N", fread($this->fp, 4));
		$this->record_shape_type = readAndUnpack("i", fread($this->fp, 4));

	}

	private function getRecordClass(){
		if(!isset($this->record_class[$this->record_shape_type])){
			return $this->setError( sprintf(INEXISTENT_RECORD_CLASS, $this->record_shape_type) );
		}
		return $this->record_class[$this->record_shape_type];
	}

	private function setError($error){
		$this->error_message = $error;
		return false;
	}

	public function getError(){
		return $this->error_message;
	}

	public function getShpData(){
		$function_name = "read".$this->getRecordClass();

		if(function_exists($function_name)){
			$this->shp_data = $function_name($this->fp,$this->options);
		} else {
			$this->setError( sprintf(INEXISTENT_FUNCTION, $function_name) );
		}

		return $this->shp_data;
	}

	public function getDbfData(){

		$this->_fetchDBFInformation();

		return $this->dbf_data;
	}

	private function _openDBFFile($check_writeable = false){
		$check_function = $check_writeable ? "is_writable" : "is_readable";
		if($check_function($this->file_name)){
			$this->dbf = dbase_open($this->file_name, ($check_writeable ? 2 : 0));
			if(!$this->dbf){
				$this->setError( sprintf(INCORRECT_DBF_FILE, $this->file_name) );
			}
		} else {
			$this->setError( sprintf(INEXISTENT_DBF_FILE, $this->file_name) );
		}
	}

	private function _closeDBFFile(){
		if($this->dbf){
			dbase_close($this->dbf);
			$this->dbf = null;
		}
	}

	private function _fetchDBFInformation(){
		$this->_openDBFFile();
		if($this->dbf) {
			//En este punto salta un error si el registro 0 está vacio.
			//Ignoramos los errores, ja que aún así todo funciona perfecto.
			$this->dbf_data = @dbase_get_record_with_names($this->dbf, $this->record_number);
		} else {
			$this->setError( sprintf(INCORRECT_DBF_FILE, $this->file_name) );
		}
		$this->_closeDBFFile();
	}

	public function setDBFInformation($row_array){
		$this->_openDBFFile(true);
		if($this->dbf) {
			unset($row_array["deleted"]);

			if(!dbase_replace_record($this->dbf, array_values($row_array), $this->record_number)){
				$this->setError( sprintf(UNABLE_TO_WRITE_DBF_FILE, $this->file_name) );
			} else {
				$this->dbf_data = $row_array;
			}
		} else {
			$this->setError( sprintf(INCORRECT_DBF_FILE, $this->file_name) );
		}
		$this->_closeDBFFile();
	}
}

/**
	* Reading functions
	*/

function readRecordNull(&$fp, $read_shape_type = false,$options = null){
	$data = array();
	if($read_shape_type) $data += readShapeType($fp);
	return $data;
}
$point_count = 0;
function readRecordPoint(&$fp, $create_object = false,$options = null){
	global $point_count;
	$data = array();

	$data["x"] = readAndUnpack("d", fread($fp, 8));
	$data["y"] = readAndUnpack("d", fread($fp, 8));

	$point_count++;
	return $data;
}

function readRecordPointZ(&$fp, $create_object = false,$options = null){
	global $point_count;
	$data = array();

	$data["x"] = readAndUnpack("d", fread($fp, 8));
	$data["y"] = readAndUnpack("d", fread($fp, 8));

	$point_count++;
	return $data;
}

function readRecordPointZSP($data, &$fp){

	$data["z"] = readAndUnpack("d", fread($fp, 8));

	return $data;
}

function readRecordPointMSP($data, &$fp){

	$data["m"] = readAndUnpack("d", fread($fp, 8));

	return $data;
}

function readRecordMultiPoint(&$fp,$options = null){
	$data = readBoundingBox($fp);
	$data["numpoints"] = readAndUnpack("i", fread($fp, 4));

	for($i = 0; $i <= $data["numpoints"]; $i++){
		$data["points"][] = readRecordPoint($fp);
	}

	return $data;
}

function readRecordPolyLine(&$fp,$options = null){
	$data = readBoundingBox($fp);
	$data["numparts"]  = readAndUnpack("i", fread($fp, 4));
	$data["numpoints"] = readAndUnpack("i", fread($fp, 4));

	if (isset($options['noparts']) && $options['noparts']==true) {
		//Skip the parts
		$points_initial_index = ftell($fp)+4*$data["numparts"];
		$points_read = $data["numpoints"];
	}
	else{
		for($i=0; $i<$data["numparts"]; $i++){
			$data["parts"][$i] = readAndUnpack("i", fread($fp, 4));
		}

		$points_initial_index = ftell($fp);

		$points_read = 0;
		if($data["parts"]!=""){
			foreach($data["parts"] as $part_index => $point_index){
				if(!isset($data["parts"][$part_index]["points"]) || !is_array($data["parts"][$part_index]["points"])){
					$data["parts"][$part_index] = array();
					$data["parts"][$part_index]["points"] = array();
				}
				while( ! in_array( $points_read, $data["parts"]) && $points_read < $data["numpoints"] && !feof($fp)){
					$data["parts"][$part_index]["points"][] = readRecordPoint($fp, true);
					$points_read++;
				}
			}
		}
	}

	fseek($fp, $points_initial_index + ($points_read * XY_POINT_RECORD_LENGTH));

	return $data;
}

function readRecordMultiPointZ(&$fp,$options = null){
	$data = readBoundingBox($fp);
	$data["numparts"]  = readAndUnpack("i", fread($fp, 4));
	$data["numpoints"] = readAndUnpack("i", fread($fp, 4));
	$fileX = 44 + (4*$data["numparts"]);
	$fileY = $fileX + (16*$data["numpoints"]);
	$fileZ = $fileY + 16 + (8*$data["numpoints"]);
	

	if (isset($options['noparts']) && $options['noparts']==true) {
		//Skip the parts
		$points_initial_index = ftell($fp)+4*$data["numparts"];
		$points_read = $data["numpoints"];
	}
	else{
		for($i=0; $i<$data["numparts"]; $i++){
			$data["parts"][$i] = readAndUnpack("i", fread($fp, 4));
		}
		$points_initial_index = ftell($fp);

		$points_read = 0;
		foreach($data["parts"] as $part_index => $point_index){
			if(!isset($data["parts"][$part_index]["points"]) || !is_array($data["parts"][$part_index]["points"])){
				$data["parts"][$part_index] = array();
				$data["parts"][$part_index]["points"] = array();
			}
			while( ! in_array( $points_read, $data["parts"]) && $points_read < $data["numpoints"]){
				$data["parts"][$part_index]["points"][] = readRecordPoint($fp, true);
				$points_read++;
			}
		}

		$data['Zmin'] = readAndUnpack("d", fread($fp, 8));
		$data['Zmax'] = readAndUnpack("d", fread($fp, 8));

		foreach($data["parts"] as $part_index => $point_index){
			foreach($point_index["points"] as $n => $p){
				$data["parts"][$part_index]['points'][$n] = readRecordPointZSP($p, $fp, true);
			}
		}

		$data['Mmin'] = readAndUnpack("d", fread($fp, 8));
		$data['Mmax'] = readAndUnpack("d", fread($fp, 8));

		foreach($data["parts"] as $part_index => $point_index){
			foreach($point_index["points"] as $n => $p){
				$data["parts"][$part_index]['points'][$n] = readRecordPointMSP($p, $fp, true);
			}
		}
	}

	fseek($fp, $points_initial_index + ($points_read * XY_POINT_RECORD_LENGTH));

	return $data;
}

function readRecordPolygon(&$fp,$options = null){
	return readRecordPolyLine($fp,$options);
}

/**
	* General functions
	*/
function processDBFFileName($dbf_filename){
	if(!strstr($dbf_filename, ".")){
		$dbf_filename .= ".dbf";
	}

	if(substr($dbf_filename, strlen($dbf_filename)-3, 3) != "dbf"){
		$dbf_filename = substr($dbf_filename, 0, strlen($dbf_filename)-3)."dbf";
	}
	return $dbf_filename;
}

function readBoundingBox(&$fp){
	$data = array();
	$data["xmin"] = readAndUnpack("d",fread($fp, 8));
	$data["ymin"] = readAndUnpack("d",fread($fp, 8));
	$data["xmax"] = readAndUnpack("d",fread($fp, 8));
	$data["ymax"] = readAndUnpack("d",fread($fp, 8));

	return $data;
}

function readAndUnpack($type, $data){
	if(!$data) return $data;
	return current(unpack($type, $data));
}

function _d($debug_text){
	if(DEBUG){
		echo $debug_text."\n";
	}
}

function getArray($array){
	ob_start();
	print_r($array);
	$contents = ob_get_contents();
	ob_get_clean();
	return $contents;
}
?>