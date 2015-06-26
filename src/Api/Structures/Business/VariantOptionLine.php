<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read string $title
 * @property-read string $value
 * @property-read ImageLine $optionImage
 */
class VariantOptionLine extends AbstractStructure
{
  
  /** @var ImageLine */
  private $_optionImage;
  
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
  public function getTitle()
  {
    return $this->data->title;
  }
  
  /**
   * @return string
   */
  public function getValue()
  {
    return $this->data->value;
  }
  
  /**
   * @return ImageLine
   */
  public function getOptionImage()
  {
    if ($this->_optionImage === NULL && isset($this->data->optionImage)) {
      $this->_optionImage = new ImageLine($this->data->optionImage);
    }
    return $this->_optionImage;
  }
  
}