<?php
/**
 * File: TestController.php
 * Description: TestController class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class TestController extends MyController
{
	public function getAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function deleteAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}

	public function postAction($request) {

		$injson = '{
  "id": 17580326089240,
  "description": {
	"start_date": "2025-09-16T14:20:08.502Z",
	"gps_datum": "WGS84 (Default)",
	"magnetic_declination": 0,
	"project_name": "Project test",
	"notes": "Some project notes here",
	"purpose_of_study": "Purpose of study",
	"area_of_interest": "Area of interest",
	"grant_id": "Grant I’d"
  },
  "date": "2025-09-16T14:23:28.924Z",
  "modified_timestamp": 1758033332557,
  "other_features": [
	"geomorphic",
	"hydrologic",
	"paleontological",
	"igneous",
	"metamorphic",
	"sedimentological",
	"other"
  ],
  "relationship_types": [
	"cross-cuts",
	"mutually cross-cuts",
	"is cut by",
	"is younger than",
	"is older than",
	"is lower metamorphic grade than",
	"is higher metamorphic grade than",
	"is included within",
	"includes",
	"merges with"
  ],
  "templates": {},
  "useContinuousTagging": false,
  "preferences": {
	"starting_number_for_spot": 2
  },
	  "tags": [
		{
		  "type": "geologic_unit",
		  "name": "One geo unit",
		  "unit_label_abbreviation": "One",
		  "map_unit_name": "Form name",
		  "member_name": "Mem name",
		  "submember_name": "Sub name",
		  "id": 17580331944552,
		  "spots": [
			17580331325666
		  ]
		},
		{
		  "type": "documentation",
		  "name": "One tag",
		  "documentation_type": "observation_timing",
		  "notes": "Some notes",
		  "id": 17580333124418,
		  "spots": [
			17580331325666
		  ]
		},
		{
			"type": "documentation",
			"name": "Test two",
			"documentation_type": "observation_timing",
			"notes": "Tag two notes",
			"id": 17580369790107,
			"spots": [
				45678
			]
		}
	  ],
	"templates": {
		"notes": {
			"isInUse": true,
			"templates": [
				{
					"id": "cfb8d723-ad7b-415f-80a8-1586429f3111",
					"name": "Notes template two",
					"values": {
						"note": "Missing Online"
					}
				},
				{
					"id": "27577dd5-8e78-4678-8995-fd8a8e0ba222",
					"name": "Test template",
					"values": {
						"note": "Missing Online two"
					}
				}
			],
			"active": [
				{
					"id": "cfb8d723-ad7b-415f-80a8-1586429f3111",
					"name": "Missing Online",
					"values": {
						"note": "Test two"
					}
				}
			]
		},
		"useMeasurementTemplates": true,
		"measurementTemplates": [
			{
				"id": "24f3a3e6-0855-4f73-a25d-535264635333",
				"name": "test three",
				"values": {
					"type": "linear_orientation"
				}
			}
		],
		"activeMeasurementTemplates": [
			{
				"id": "24f3a3e6-0855-4f73-a25d-535264635333",
				"name": "test three",
				"values": {
					"type": "linear_orientation"
				}
			}
		],
		"minerals": {
			"isInUse": true,
			"templates": [
				{
					"id": "3ee2a535-cd52-40dd-a947-79bdb9a60adc",
					"name": "Mineral template one",
					"values": {
						"mineral_abbrev": "Fff"
					}
				}
			],
			"active": [
				{
					"id": "3ee2a535-cd52-40dd-a947-79bdb9a60adc",
					"name": "Mineral template one",
					"values": {
						"mineral_abbrev": "Fff"
					}
				}
			]
		},
		"lithologies": {
			"isInUse": true,
			"templates": [
				{
					"id": "93048068-0419-4cc5-9631-9f86e7cd9670",
					"name": "Sed template",
					"values": {
						"primary_lithology": "siliciclastic"
					}
				},
				{
					"id": "aff887f4-cc66-4225-9a5c-189e90c77a2d",
					"name": "Sed template two",
					"values": {
						"primary_lithology": "evaporite"
					}
				}
			],
			"active": [
				{
					"id": "93048068-0419-4cc5-9631-9f86e7cd9670",
					"name": "Sed template",
					"values": {
						"primary_lithology": "siliciclastic"
					}
				},
				{
					"id": "aff887f4-cc66-4225-9a5c-189e90c77a2d",
					"name": "Sed template two",
					"values": {
						"primary_lithology": "evaporite"
					}
				}
			]
		}
	},
  "reports": [
	{
	  "report_privacy": "anyone",
	  "report_type": "question",
	  "subject": "Test report2",
	  "notes": "Some notes2",
	  "id": "905d7329-4ab3-45d6-99da-8b6b255555",
	  "created_timestamp": 1758049333706,
	  "updated_timestamp": 1758049364705,
	  "images": [
	  ],
	  "spots": [
		17580331325666
	  ],
	  "tags": [
		17580370509484
	  ]
	}
  ]
}';

		$injson = $this->strabo->combineProject($injson);

		print_r($injson);

		exit();
	}

	public function bkuppostAction20250916($request) {

		$username=$_SERVER['PHP_AUTH_USER'];
		$remoteip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		$rand = rand(111111,999999);
		$userpkey = $this->strabo->userpkey;
		$id = str_repeat("0", 16 - strlen($userpkey)) . $userpkey;

		$params = array(
			'action_name' => 'Strabo Upload',
			'url' => 'https://strabospot.org/strabo_upload',
			'idsite' => '1',
			'rand' => $rand,
			'uid' => $username,
			'rec' => '1',
			'apiv' => '1',
			'_id' => $id,
			'send_image' => '0',
			'token_auth' => '01e0d17a086d20a2c2ee04064d0d6bc7',
			'cip' => $remoteip
		);

		$endpoint = 'https://stats.strabospot.org/matomo.php';

		$url = $endpoint . '?' . http_build_query($params);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);

		exit();
	}

	public function bkuppostAction($request) {

		$username=$_SERVER['PHP_AUTH_USER'];
		$password=$_SERVER['PHP_AUTH_PW'];

		echo "REQUEST: ".ucfirst($request->url_elements[1])."\n\n";
		echo "REQUEST_URI: ".$_SERVER["REQUEST_URI"]."\n\n";
		echo "username: $username\n\n";
		echo "Raw Input:\n".$rawinput;
		echo "Request Method: ".$_SERVER['REQUEST_METHOD'];

		exit();
	}    public function optionsAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}    public function copyAction($request) {

		header("Bad Request", true, 400);
		$data["Error"] = "Bad Request.";

		return $data;
	}}
