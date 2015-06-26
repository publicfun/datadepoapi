<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read string $title
 * @property-read string $value
 * @property-read ImageLine $titleImage
 */
class VariantOptionLine extends AbstractStructure
{
  
  /** @var ImageLine */
  private $_titleImage;
  
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
  public function getTitleImage()
  {
    if ($this->_titleImage === NULL && isset($this->data->titleImage)) {
      $this->_titleImage = new ImageLine($this->data->titleImage);
    }
    return $this->_titleImage;
  }
  
}