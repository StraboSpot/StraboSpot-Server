<?php
/**
 * File: savesearch.php
 * Description: Search interface for querying and filtering data
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


include("searchWorker.php");
$json = file_get_contents("php://input");

$data = json_decode($json);

/*
{
	"searchType": "results",
	"params": [
		{
			"num": 0,
			"qualifier": "and",
			"constraints": [
				{
					"constraintType": "keyword",
					"constraintValue": "zircon"
				}
			]
		}
	],
	"searchName": "ddd",
	"userpkey": "3"
}
*/

$searchName = $data->searchName ?? '';
$saveJSON = json_encode($data);

$db->prepare_query("
	INSERT INTO fullsearches (
								user_pkey,
								search_name,
								search_json
							) VALUES (
								$1,
								$2,
								$3
							)
", array($userpkey, $searchName, $saveJSON));

$out = new stdClass();
$out->message = "Success! Search Saved.";

header('Content-Type: application/json');
$out = json_encode($out, JSON_PRETTY_PRINT);
echo $out;

/*
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO strabodbuser;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO strabodbuser;
*/

?>