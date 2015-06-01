<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read string $groupName
 * @property-read string $titleName
 * @property-read string $value
 */
class ParameterLine extends AbstractStructure
{
  
  /** @var array */
  protected $data;
  
  /** @var array */
  protected $idName;
  
  /**
   * @param array $data
   * @param string $idName
   */
  public function __construct($data, $idName)
  {
    $this->data = $data;
    $this->idName = $idName;
  }
  
  /**
   * @return string
   */
  public function getPrimary()
  {
    return $this->idName;
  }
  
  /**
   * @return array
   */
  public function getData()
  {
    return $this->data;
  }
  
  /**
   * @return string
   */
  public function getGroupName()
  {
    return isset($this->data->groupName) ? $this->data->groupName : NULL;
  }
  
  /**
   * @return string
   */
  public function getTitleName()
  {
    return isset($this->data->titleName) ? $this->data->titleName : NULL;
  }
  
  /**
   * @return string
   */
  public function getValue()
  {
    return $this->data->value;
  }
  

  
}