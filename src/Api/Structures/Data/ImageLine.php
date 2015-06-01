<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read string $url
 * @property-read string $urlOriginal
 * @property-read bool $main
 * @property-read string $name
 * @property-read double $size
 * @property-read integer $width
 * @property-read integer $height
 * @property-read string $hash
 */
class ImageLine extends AbstractStructure
{
  
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
  public function getUrl()
  {
    return $this->data->url;
  }
  
  /**
   * @return string
   */
  public function getUrlOriginal()
  {
    return $this->data->urlOriginal;
  }
  
  /**
   * @return bool
   */
  public function getMain()
  {
    return (bool)$this->data->main;
  }
  
  /**
   * @return string
   */
  public function getName()
  {
    return $this->data->name;
  }
  
  /**
   * Size in kb
   * @return double
   */
  public function getSize()
  {
    return $this->data->size;
  }
  
  /**
   * @return integer
   */
  public function getWidth()
  {
    return $this->data->width;
  }
  
  /**
   * @return integer
   */
  public function getHeight()
  {
    return $this->data->height;
  }
  
  /**
   * @return string
   */
  public function getHash()
  {
    return $this->data->hash;
  }
  

  
}