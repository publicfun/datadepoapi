<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read string $code
 * @property-read VariantOptionLine[] $options
 * @property-read StoreLine $store
 * @property-read PriceLine[] $prices
 */
class VariantLine extends AbstractStructure
{
  
  /** @var VariantOptionLine[] */
  private $_options;
  
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
   * @return VariantOptionLine[]
   */
  public function getOptions()
  {
    if ($this->_options === NULL) {
      $this->_options = array();
      foreach ($this->data->options as $idName => $option) {
        $this->_options[$idName] = new VariantOptionLine($option);
      }
    }
    return $this->_options;
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