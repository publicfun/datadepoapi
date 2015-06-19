<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read string $currency
 * @property-read double $tax
 * @property-read double $rabat
 * @property-read double $margin
 * @property-read double $priceRetail
 * @property-read double $price
 * @property-read double $pricePhe
 * @property-read double $priceMin
 * @property-read double $priceSale
 * @property-read double $priceSaleFinal
 */
class PriceLine extends AbstractStructure
{
  
  /** @var string */
  protected $currency;
  
  /**
   * @param array $data
   * @param string $currency
   */
  public function __construct($data, $currency)
  {
    $this->data = $data;
    $this->currency = $currency;
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
    return $this->getCurrency();
  }
  
  /**
   * @return string
   */
  public function getCurrency()
  {
    return $this->currency;
  }
  
  /**
   * @return double
   */
  public function getTax()
  {
    return $this->data->tax;
  }
  
  /**
   * @return double
   */
  public function getRabat()
  {
    return $this->data->rabat;
  }
  
  /**
   * @return double
   */
  public function getMargin()
  {
    return $this->data->margin;
  }
  
  /**
   * Recommended MOC
   * @return double
   */
  public function getPriceRetail()
  {
    return $this->data->priceRetail;
  }
  
  /**
   * Order price
   * @return double
   */
  public function getPrice()
  {
    return $this->data->price;
  }
  
  /**
   * Additional dues (is not included in sale price)
   * @return double
   */
  public function getPricePhe()
  {
    return $this->data->pricePhe;
  }
  
  /**
   * Minimal sale price
   * @return double
   */
  public function getPriceMin()
  {
    return $this->data->priceMin;
  }
  
  /**
   * Sale price without dues and price add
   * @return double
   */
  public function getPriceSale()
  {
    return $this->data->priceSale;
  }
  
  /**
   * Sale price (include dues and price add)
   * @return double
   */
  public function getPriceSaleFinal()
  {
    return $this->getPriceSale() + $this->getPricePhe();
  }
  
}