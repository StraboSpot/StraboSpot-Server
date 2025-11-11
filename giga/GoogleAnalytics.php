<?php
/**
 * File: GoogleAnalytics.php
 * Description: GoogleAnalytics class
 *
 * @package    StraboSpot Web Site
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  2025 StraboSpot
 * @license    https://opensource.org/licenses/MIT MIT License
 * @link       https://strabospot.org
 */

require_once('AbstractAnalytics.php');

class GoogleAnalytics extends AbstractAnalytics {

	protected $google_host = 'https://www.google-analytics.com/collect';
	protected $google_debug_host = 'https://www.google-analytics.com/debug/collect';

	
	protected $trackingId = '';

	/*
	The name of the application, this is sent to the Google servers
	*/
	protected $appName = 'GigaBrowser';

	public function __construct($TrackingID, $ApplicationName, $debug) {
		parent::__construct($debug);

		$this->trackingId = $TrackingID;
		$this->appName = $ApplicationName;
	}

	protected function getHost() {
	  if( $this->debug )
		  return $this->google_debug_host;
	  return $this->google_host;
	}

	private function getCommonDataArray($url, $title){
		// Standard params
		$v   = 1;
		$cid = $this->_ParseOrCreateAnalyticsCookie();

		return array(
			'v'   => $v,
			'tid' => $this->trackingId,
			'cid' => $cid,
			'an'  => $this->appName,
			'dt'  => $title,
			'dl'  => $url,
			'ua'  => $this->_getUserAgent(),
			'dr'  => $this->_getReferer(),
			'uip' => $this->_getRemoteIP(),
			'av'  => '1.0'
		);
	}

	protected function GetHitRequest($url, $title) {
		// Create the pageview data
		$data = $this->getCommonDataArray($url, $title);
		$data['t'] = 'pageview';

		// Send PageView hit as POST
		return $data;
	}

	protected function GetErrorRequest($url, $title, $errorcode){
		// Create the error data
		$data = $this->getCommonDataArray($url, $title);
		$data['t']   = 'exception';
		$data['exd'] = $errorcode;
		$data['exf'] = '1';

		return $data;
	}

  // Gets the current Analytics session identifier or
	private function _ParseOrCreateAnalyticsCookie() {
	  if (isset($_COOKIE['_ga'])) {
		  // An analytics cookie is found
		  list($version, $domainDepth, $cid1, $cid2) = preg_split('[\.]', $_COOKIE["_ga"], 4);
		  $contents = array(
			'version' => $version,
			'domainDepth' => $domainDepth,
			'cid' => $cid1 . '.' . $cid2
		  );
		  $cid = $contents['cid'];
	  } else {
		  $cid1 = mt_rand(0, 2147483647);
		  $cid2 = mt_rand(0, 2147483647);

		  $cid = $cid1 . '.' . $cid2;
		  setcookie('_ga', 'GA1.2.' . $cid, time() + 60 * 60 * 24 * 365 * 2, '/');
	  }
	  return $cid;
	}
}
?>