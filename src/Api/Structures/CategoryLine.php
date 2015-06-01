<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read integer $primary
 * @property-read integer $dataDepoId
 * @property-read bool $deleted
 * @property-read string $project
 * @property-read integer $parentId
 * @property-read integer $position
 * @property-read string $name
 * @property-read string $namePath
 * @property-read string $urlPath
 * @property-read bool $active
 */
class CategoryLine extends AbstractStructure
{
  
  /**
   * @return integer
   */
  public function getPrimary()
  {
    return $this->getDataDepoId();
  }
  
  /**
   * @return integer
   */
  public function getDataDepoId()
  {
    return $this->data->id;
  }
  
  /**
   * @return bool
   */
  public function getDeleted()
  {
    return (bool)$this->data->deleted;
  }
  
  /**
   * @return string
   */
  public function getProject()
  {
    return $this->data->project;
  }
  
  /**
   * @return integer
   */
  public function getParentId()
  {
    return $this->data->parentId;
  }
  
  /**
   * @return integer
   */
  public function getPosition()
  {
    return $this->data->position;
  }
  
  /**
   * @return string
   */
  public function getName()
  {
    return $this->data->name;
  }
  
  /**
   * @return string
   */
  public function getNamePath()
  {
    return $this->data->namePath;
  }
  
  /**
   * @return string
   */
  public function getUrlPath()
  {
    return $this->data->urlPath;
  }
  
  /**
   * @return bool
   */
  public function getActive()
  {
    return (bool)$this->data->active;
  }
  
  
}