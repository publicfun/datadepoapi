<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read integer $primary
 * @property-read integer $idId
 * @property-read string $name
 * @property-read string $position
 * @property-read string $phone1
 * @property-read string $phone2
 * @property-read string $email
 */
class SupplierPersonLine extends AbstractStructure
{
  
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
  public function getJson()
  {
    return json_encode($this->data);
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