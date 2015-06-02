<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read string $supplier
 * @property-read string $type
 * @property-read string $name
 * @property-read integer $count
 * @property-read ImageLine[] $images
 */
class RelatedLine extends AbstractStructure
{
  
  /** @var string */
  private $_images = NULL;
  
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
  public function getPrimary()
  {
    return $this->data->primary;
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
  public function getSupplier()
  {
    return $this->data->supplier;
  }
  
  /**
   * @return string
   */
  public function getType()
  {
    return $this->data->type;
  }
  
  /**
   * @return string
   */
  public function getName()
  {
    return $this->data->name;
  }
  
  /**
   * @return integer
   */
  public function getCount()
  {
    return $this->data->count;
  }
  
  /**
   * @return ImageLine[]
   */
  public function getImages()
  {
    if ($this->_images === NULL) {
      $this->_images = array();
      if (property_exists($this->data, 'images')) {
        foreach ($this->data->images as $idName => $data) {
          $this->_images[$idName] = new ImageLine($data);
        }
      }
    }
    return $this->_images;
  }

  
}