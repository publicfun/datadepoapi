<?php
namespace Datadepo\Api\Structures;


/**
 * @property-read string $supplierIdName
 * @property-read string $supplierCode
 * @property-read StoreLine $store
 * @property-read PriceLine[] $prices
 * @property-read VariantLine[] $variants
 */
class BusinessSupplierLine extends AbstractStructure
{
  
  /** @var string */
  protected $idName;
  
  /** @var StoreLine */
  private $_store;
  
  /** @var PriceLine[] */
  private $_prices;
  
  /** @var VariantLine[] */
  private $_variants;
  
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
  public function getJson()
  {
    return json_encode($this->data);
  }
  
  /**
   * @return string
   */
  public function getPrimary()
  {
    return $this->getSupplierIdName();
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
    if ($this->_store === NULL) {
      $this->_store = new StoreLine($this->data->store);
    }
    return $this->_store;
  }
  
  /**
   * @return PriceLine[]
   */
  public function getPrices()
  {
    if ($this->_prices === NULL) {
      $this->_prices = array();
      foreach ($this->data->prices as $currency => $data) {
        $this->_prices[$currency] = new PriceLine($data, $currency);
      }
    }
    return $this->_prices;
  }
  
  /**
   * @return VariantLine[]
   */
  public function getVariants()
  {
    if ($this->_variants === NULL) {
      $this->_variants = array();
      if (isset($this->data->variants)) {
        foreach ($this->data->variants as $hash => $data) {
          $this->_variants[$hash] = new VariantLine($data);
        }
      }
    }
    return $this->_variants;
  }

  
}
