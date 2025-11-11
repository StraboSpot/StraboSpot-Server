<?php
/**
 * File: Request.php
 * Description: Request class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */


class Request {
	public $url_elements;
	public $verb;
	public $parameters;
	public $apiformat;

	public function __construct() {
		$this->verb = $_SERVER['REQUEST_METHOD'];

		if(isset($_SERVER['PATH_INFO'])){
			$this->url_elements = explode('/', $_SERVER['PATH_INFO']);
		}

		$this->parseIncomingParams();
		// initialise json as default apiformat
		$this->apiformat = 'json';
		if(isset($this->parameters['apiformat'])) {
			$this->apiformat = $this->parameters['apiformat'];
		}
		return true;
	}

	public function parseIncomingParams() {
		$parameters = array();

		// first of all, pull the GET vars
		if (isset($_SERVER['QUERY_STRING'])) {
			parse_str($_SERVER['QUERY_STRING'], $parameters);
		}

		// now how about PUT/POST bodies? These override what we got from GET
		$body = file_get_contents("php://input");

		$content_type = false;
		if(isset($_SERVER['CONTENT_TYPE'])) {
			$content_type = $_SERVER['CONTENT_TYPE'];
		}
		switch($content_type) {
			case "application/json":

				$body_params = json_decode($body);

				if($body_params) {
					foreach($body_params as $param_name => $param_value) {
						$parameters[$param_name] = $param_value;
					}
				}
				$parameters['apiformat'] = "json";
				break;
			case "application/x-www-form-urlencoded":
				parse_str($body, $postvars);
				foreach($postvars as $field => $value) {
					$parameters[$field] = $value;

				}
				$parameters['apiformat'] = "html";
				break;
			default:
				// we could parse other supported apiformats here
				break;
		}
		$this->parameters = $parameters;
	}
}
