<?php
/**
 * File: tooltipsscript.php
 * Description: Generates tooltip help content and scripts
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

include("../prepare_connections.php");

$spreadsheet_url="https://docs.google.com/spreadsheets/d/e/2PACX-1vTGVqG0V_YLP0SHRL2asnNDs7AMZXuFns2AjG4Ed-5ONaFlH1U3oXuofuWhJ1GXVTrUTFn2eH4g2PWM/pub?gid=0&single=true&output=csv";

if(!ini_set('default_socket_timeout', 15)) echo "<!-- unable to change socket timeout -->";

if (($handle = fopen($spreadsheet_url, "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$spreadsheet_data[] = $data;
	}
	fclose($handle);
}
else
	die("Problem reading csv");

$toolData = "let toolTipsData = [";

for($x = 1; $x < count($spreadsheet_data); $x++){
	$s = $spreadsheet_data[$x];
	$toolData .= "\n\t{headerId: \"".$s[0]."\", headerPos: \"".$s[1]."\", toolTip: \"".$s[2]."\", },";
}

$toolData .= "\n]";

header("Content-Type: application/javascript");

echo $toolData;

?>