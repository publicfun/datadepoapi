<?php
namespace Datadepo\Api;

class DataDepoResponse
{
 
  const CODE_OK = 'ok';
  const CODE_STOPPED = 'stopped';
  const CODE_RUNNING = 'running';
  const CODE_ERROR = 'error';
  
  /** @var string */
  protected $code;
  
  /** @var string */
  protected $message;
  
  /** @var array */
  protected $counter;
  
  /**
   * @param string $code
   */
  public function __construct($code, $message = NULL, array $counter = NULL)
  {
    $this->code = $code;
    $this->message = $message;
    $this->counter = $counter;
  }
  
  /**
   * @return array
   */
  public function toArray()
  {
    return array('code' => $this->code, 'message' => $this->message, 'counter' => $this->coounter);
  }
  
  /**
   * @return string
   */
  public function toJson()
  {
    return json_encode($this->toArray());
  }
  
}