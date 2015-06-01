<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read integer $primary
 * @property-read integer $idId
 * @property-read string $name
 * @property-read string $accountNumberPrefix
 * @property-read string $accountNumber
 * @property-read string $accountNumberPostfix
 * @property-read string $accountNumberFull
 */
class SupplierBankAccountLine extends AbstractStructure
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
  public function getAccountNumberPrefix()
  {
    return $this->data->accountNumberPrefix;
  }
  
  /**
   * @return string
   */
  public function getAccountNumber()
  {
    return $this->data->accountNumber;
  }
  
  /**
   * @return string
   */
  public function getAccountNumberPostfix()
  {
    return $this->data->accountNumberPostfix;
  }
  
  /**
   * @return string
   */
  public function getAccountNumberFull()
  {
    return ($this->accountNumberPrefix ? $this->accountNumberPrefix . '-' : '') . $this->accountNumber . '/' . $this->accountNumberPostfix;
  }

  
}