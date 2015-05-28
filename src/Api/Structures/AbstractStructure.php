<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read string $checksum
 * @property-read string $primary
 * @property-read array $data
 */
abstract class AbstractStructure
{
  
  /** @var string */
  protected $json;
  
  /** @var \stdClass */
  protected $data;
  
  /**
   * @param string $json
   */
  public function __construct($json)
  {
    $this->json = $json;
    $this->data = json_decode($this->json);
  }
  
  /**
   * @return string
   */
  public function getChecksum()
  {
    return md5($this->json);
  }
  
  /**
   * @return string
   */
  abstract public function getPrimary();
  
  /**
   * @return array
   */
  abstract public function getData();
  
  /**
   * @param string $name
   * @return mixed
   */
  public function __get($name)
  {
    return $this->{'get' . $name}();
  }
  
}