<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read string $groupName
 * @property-read string $titleName
 * @property-read string $value
 */
class ParameterLine extends AbstractStructure
{
  
  /**
   * @param array $data
   */
  public function __construct($data)
  {
    $this->data = $data;
  }
  
  /**
   * @return string
   */
  public function getJson()
  {
    return json_encode($this->data);
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