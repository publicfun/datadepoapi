<?php
namespace Datadepo\Api\Structures;

/**
 */
class SupplierPersonLine extends AbstractStructure
{
  
  /** @var array */
  protected $data;
  
  /** @var integer */
  protected $inId;
  
  /**
   * @param array $data
   * @param integer $inId
   */
  public function __construct($data, $inId)
  {
    $this->data = $data;
    $this->inId = $inId;
  }
  
  /**
   * @return string
   */
  public function getPrimary()
  {
    return $this->getInId();
  }
  
  /**
   * Primary key for bank account
   * @return string
   */
  public function getInId()
  {
    return $this->inId;
  }
  
  /**
   * @return array
   */
  public function getData()
  {
    $data = array('in_id' => $this->getInId(),
                  'name' => $this->getName(),
                  'position' => $this->getPosition(),
                  'phone1' => $this->getPhone1(),
                  'phone2' => $this->getPhone2(),
                  'email' => $this->getEmail());
    return $data;
  }
  
  /**
   * @return string
   */
  public function getName()
  {
    return $this->data->name;
  }
  
  /**
   * @return string
   */
  public function getPosition()
  {
    return $this->data->position;
  }
  
  /**
   * @return string
   */
  public function getPhone1()
  {
    return $this->data->phone1;
  }
  
  /**
   * @return string
   */
  public function getPhone2()
  {
    return $this->data->phone2;
  }
  
  /**
   * @return string
   */
  public function getEmail()
  {
    return $this->data->email;
  }
  
}