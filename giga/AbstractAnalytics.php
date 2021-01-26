<?
abstract class AbstractAnalytics {

    protected $debug = false;

    public function __construct($debug) {
          $this->debug = $debug;
    }

    /*
    Sends a normal page-view type tracking request to the analytics server
    */
    public function Track($title) {
        if (!method_exists($this, "GetHitRequest")) {
            throw new Exception( "Missing GetHitRequest function");
        }

        $response = $this->_URLPost(
                    $this->getHost(), 
                    $this->GetHitRequest($this->getUrlPath(), 
                                         $title));
        if( $this->debug )
            echo $response;
        return $response;
    }

    /*
    Sends a exception type tracking request to the analytics server
    */
    public function Error($title, $errorcode) {
        if (!method_exists($this, "GetErrorRequest")) {
            throw new Exception( "Missing GetErrorRequest function");
        }

        $response = $this->_URLPost(
                    $this->getHost(), 
                    $this->GetErrorRequest($this->getUrlPath(), 
                                           $title, 
                                           $errorcode));
        if( $this->debug )
            echo $response;
        return $response;
    }

    /*
    Gets the analytics host name (e.g. https://www.google-analytics.com)
    */
    abstract protected function getHost();

    /*
    Gets the full url to the requested resource
    */
    protected function getUrlPath() {
        return 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . 
           '://' . 
           "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    }

    /*
    Gets the user agent attached to the original request
    */
    protected function _getUserAgent() {
        return array_key_exists('HTTP_USER_AGENT', $_SERVER) 
            ? $_SERVER['HTTP_USER_AGENT'] : "";
    }

    /*
    Gets the http referer for the original request
    */
    protected function _getReferer() {
        return array_key_exists('HTTP_REFERER', $_SERVER) 
          ? $_SERVER['HTTP_REFERER'] : "";
    }

    /* 
    Gets the remote ip address for the original request
    */
    protected function _getRemoteIP() {
        return array_key_exists('REMOTE_ADDR', $_SERVER) 
          ? $_SERVER['REMOTE_ADDR'] : "";
    }

    /*
    Performs a POST request of the data in $data_array to the URL in $url
    */
    private function _URLPost($url, $data_array) { 
      // Need to encode spaces, otherwise services such
      // as Google will return 400 bad request!
      $url = str_replace(" ", "%20", $url);

      // Construct the contexts for the POST requests
      $opts = array(
        'https'=>array(
        'method'=>"POST",
        'header'=>
          "Accept: application/json, text/javascript, */*; q=0.01\r\n".
          "Content-type: application/x-www-form-urlencoded; charset=UTF-8\r\n".
          "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.155 Safari/537.36\r\n".
          "Referer: https://api.example.com/\r\n",
        'content' => http_build_query($data_array)
        )
        ,
        'http'=>array(
        'method'=>"POST",
        'header'=>
          "Accept: application/json, text/javascript, */*; q=0.01\r\n".
          "Content-type: application/x-www-form-urlencoded; charset=UTF-8\r\n".
          "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.155 Safari/537.36\r\n".
          "Referer: https://api.example.com/\r\n",
        'content' => http_build_query($data_array)
        )
      );

      $context = stream_context_create($opts);
      $result = null;
      $dh = fopen("$url",'rb', false, $context);
      if( !$dh )
        return null;

      if( $dh !== false )
        $result = stream_get_contents($dh);

      fclose($dh);

      return $result; 
  }
}
?>