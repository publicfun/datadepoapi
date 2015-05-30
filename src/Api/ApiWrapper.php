<?php
namespace Datadepo\Api;

class ApiWrapper
{
  
  /** @var array */
  protected $settingsDatadepo;
  
  /** @var array */
  protected $settingsAccount;

  /**
   * @param array $settingsDatadepo
   * @param array $settingsAccount
   */
  public function __construct($settingsDatadepo, $settingsAccount)
  {
    $this->settingsDatadepo = $settingsDatadepo;
    $this->settingsAccount = $settingsAccount;
  }
 
  /**
   * @param string $type
   * @param string $actionType
   * @param array $params
   * @return \stdClass
   */
  public function request($type, $actionType, array $params = array())
  {
    $url = $this->settingsDatadepo['apiUrl'] . '/' . $this->settingsDatadepo['version']. '/' . $type . '/' . $actionType . '/';
    list($socket) = $this->createFsock($url, $params);
    $contents = "";
    while (!feof($socket)) {
      $contents .= fgets($socket, 4096);
    }
    fclose($socket);
    
    $json = json_decode($contents);
    if (!$json instanceof \stdClass) {
      throw new ApiException('Unrecognized response from datadepo');
    }
    return $json;
  }
  
  /**
   * @param \stdClass $json
   * @return string|null
   */
  public function analyzeResponse($json)
  {
    if (isset($json->url)) {
      return 'url';
    }
    elseif (isset($json->suspend)) {
      return 'suspend';
    }
    return NULL;
  }
  
  /**
   * @param string $url
   * @param string $filePath
   */
  public function download($url, $filePath)
  {
    list($socket) = $this->createFsock($url);

    //write data to file
    umask(0000);
    $fp = fopen($filePath, 'w');
    while (!feof($socket)) {
      fwrite($fp, fread($socket, 4096));
    }
    fclose($fp);
    fclose($socket);
    chmod($filePath, 0777);
  }
  
  
  
  
  
  /**
   * @param string $url
   * @param array $params
   * @return resource
   * @throws Exception
   */
  protected function createFsock($url, $params = FALSE)
  {
    $url = parse_url($url);
    if (!isset($url['host']) || !isset($url['path'])) {
      throw new ApiException('Invalid URL');
    }
    
    $host = $url['host'];
    $path = $url['path'] . (isset($url['query']) ? '?' .  $url['query'] : '');
    $socket = fsockopen($host, 80, $errno, $errstr, $this->settingsDatadepo['connectTimeout']);
    if (!$socket) {
      throw new ApiException($errstr);
    }
    stream_set_timeout($socket, $this->settingsDatadepo['connectTimeout']); 
    
    //add authentication informations to post
    $postData = FALSE;
    if ($params !== FALSE) {
      foreach (array('user', 'pwd') as $name) {
        if (isset($this->settingsAccount[$name])) {
          $params[$name] = $this->settingsAccount[$name];
        }
      }
      $postData = http_build_query($params);
    }
    
    $http  = "POST ".$path." HTTP/1.0\r\n";
    $http .= "Host: ".$host."\r\n";
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
      $http .= "User-Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
    }
    $http .= "Content-Type: application/x-www-form-urlencoded\r\n";
    if ($postData) {
      $http .= "Content-length: " . strlen($postData) . "\r\n";
    }
    $http .= "Connection: close\r\n\r\n";
    if ($postData) {
      $http .= $postData . "\r\n\r\n";
    }
    fwrite($socket, $http);	
    
    //remove header
    $header = '';
    
    do {
      $c = fgets ( $socket, 4096 );
      if ($c === FALSE) {
        throw new ApiException('Connection timeout');
      }
			$header .= $c;
		} 
    while (strpos($header, "\r\n\r\n") === FALSE);
    return array($socket, $header);
  }
  
}