<?php
namespace Datadepo\Api\Structures;


/**
 * @property-read string $supplierIdName
 * @property-read string $supplierCode
 * @property-read StoreLine $store
 * @property-read PriceLine[] $prices
 */
class BusinessSupplierLine extends AbstractStructure
{
  
  /** @var array */
  protected $data;
  
  /** @var string */
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
    return $this->getSupplierIdName();
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
  public function getSupplierIdName()
  {
    return $this->idName;
  }
  
  /**
   * @return string
   */
  public function getSupplierCode()
  {
    return $this->data->store->supplier_code;
  }
  
  /**
   * @return StoreLine
   */
  public function getStore()
  {
    static $store = NULL;
    if ($store === NULL) {
      $store = new StoreLine($this->data->store);
    }
    return $store;
  }
  
  /**
   * @return PriceLine[]
   */
  public function getPrices()
  {
    static $prices = NULL;
    if ($prices === NULL) {
      $prices = array();
      foreach ($this->data->prices as $currency => $data) {
        $prices[$currency] = new PriceLine($data, $currency);
      }
    }
    return $prices;
  }

  
}