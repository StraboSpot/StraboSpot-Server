<?
/*
$json = '{"name":"Canada","children":[{"name":"Newfoundland","children":[{"name":"St. Johns"}]},{"name":"PEI","children":[{"name":"Charlottetown"}]},{"name":"Nova Scotia","children":[{"name":"Halifax"}]},{"name":"New Brunswick","children":[{"name":"Fredericton"}]},{"name":"Quebec","children":[{"name":"Montreal"},{"name":"Quebec City"}]},{"name":"Ontario","children":[{"name":"Toronto"},{"name":"Ottawa"}]},{"name":"Manitoba","children":[{"name":"Winnipeg"}]},{"name":"Saskatchewan","children":[{"name":"Regina"}]},{"name":"Nunavuet","children":[{"name":"Iqaluit"}]},{"name":"NWT","children":[{"name":"Yellowknife"}]},{"name":"Alberta","children":[{"name":"Edmonton"}]},{"name":"British Columbia","children":[{"name":"Victoria"},{"name":"Vancouver"}]},{"name":"Yukon","children":[{"name":"Whitehorse"}]}]}';
//$json = json_encode($json, JSON_PRETTY_PRINT);
header('Content-Type: application/json');
echo $json;
exit();
*/

function dumpVar($var){
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

$file = $_GET['file'].".xlsx";

if($file == ""){
	$file = "unset";
}

ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set('display_errors', true);

require_once '../simplexlsx/src/SimpleXLSX.php';

if ( $xlsx = SimpleXLSX::parse($file)) {
	
	$data = $xlsx->rows();
	//dumpVar($data);
	$formattedData = parseData($data);
	//dumpVar($formattedData);
	
	$outData['name'] = "Interval";
	$outData['children'] = $formattedData;
	
	$outJSON = json_encode($outData, JSON_PRETTY_PRINT);
	//dumpVar($outJSON);
	
	header('Content-Type: application/json');
	echo $outJSON;

} else {
	echo SimpleXLSX::parseError();
}

function parseData($data, $column = 0, $rowstart = 0, $rowend = 10000){
	
	$formattedData = [];
	$dataNum = 0;
	
	for($row = $rowstart; $row < $rowend; $row ++){
	
		if($data[$row][$column]!=""){
			$formattedData[$dataNum]['name'] = $data[$row][$column];
			if($data[$row][$column+1]!=""){
				$newRowStart = $row;
				$newRowEnd = getNewEnd($data, $column, $newRowStart);
				//$formattedData[$dataNum]['start'] = $newRowStart;
				//$formattedData[$dataNum]['end'] = $newRowEnd;
				$formattedData[$dataNum]['children'] = parseData($data, $column + 1, $newRowStart, $newRowEnd);
			}
			
			$dataNum++;
		}
	
	}
	
	return $formattedData;
}

function getNewEnd($data, $column, $startRow){
	$returnNum = $startRow;
	for($x = $startRow + 1; $x < 10000; $x++){
		if($data[$x][$column]!=""){
			$returnNum = $x;
			break;
		}
	}
	
	return $returnNum;
}

?>