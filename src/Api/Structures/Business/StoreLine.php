<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read string $state
 * @property-read \DateTime $upcomingDate
 * @property-read integer $count
 * @property-read array $note
 * @property-read integer $availability
 * @property-read string $availabilityText
 * @property-read integer $availabilityMin
 * @property-read integer $availabilityMax
 */
class StoreLine extends AbstractStructure
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
  public function getState()
  {
    return $this->data->state;
  }
  
  /**
   * @return string
   */
  public function getUpcomingDate()
  {
    return $this->data->upcomingDate !== NULL ? new \DateTime($this->data->upcomingDate) : NULL;
  }
  
  /**
   * @return string
   */
  public function getCount()
  {
    return $this->data->count;
  }
  
  /**
   * @return string
   */
  public function getNote()
  {
    return $this->data->note !== NULL ? json_decode($this->data->note) : NULL;
  }
  
  /**
   * @return string
   */
  public function getAvailability()
  {
    return $this->data->availability;
  }
  
  /**
   * @return string
   */
  public function getAvailabilityText()
  {
    return $this->data->availabilityText;
  }
  
  /**
   * @return string
   */
  public function getAvailabilityMin()
  {
    return $this->data->availabilityMin;
  }
  
  /**
   * @return string
   */
  public function getAvailabilityMax()
  {
    return $this->data->availabilityMax;
  }

  
}