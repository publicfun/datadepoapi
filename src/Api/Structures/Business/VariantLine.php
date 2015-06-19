<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read string $code
 * @property-read array $options
 * @property-read StoreLine $store
 * @property-read PriceLine[] $prices
 */
class VariantLine extends AbstractStructure
{
  
  /** @var StoreLine */
  private $_store;
  
  /** @var PriceLine[] */
  private $_prices;
  
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
  public function getCode()
  {
    return $this->data->code;
  }
  
  /**
   * @return string
   */
  public function getOptions()
  {
    return $this->data->options;
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
  
  
}