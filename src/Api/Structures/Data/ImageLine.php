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
  
  /** @var array */
  protected $data;
  
  /** @var array */
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
    return $this->idName;
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