<?php
namespace Datadepo\Api;
use Datadepo\Api\Structures;

class Collector implements \Countable, \Iterator
{
 
  /** @var Structures\AbstractStructure[] */
  protected $lines;
  
  /**
   * @param Structures\AbstractStructure $line
   * @return Collector
   */
  public function add(Structures\AbstractStructure $line)
  {
    $this->lines[$line->getPrimary()] = $line;
    return $this;
  }
  
  /**
   * @return array
   */
  public function getPrimaryKeys()
  {
    return array_keys($this->lines);
  }
  
  /**
   * @return array
   */
  public function getChecksums()
  {
    $checksums = array();
    foreach ($this->lines as $primary => $line) {
      $checksums[$primary] = $line->getChecksum();
    }
    return $checksums;
  }
  
  /**
   * @return Structures\AbstractStructure[]
   */
  public function getLines()
  {
    return $this->lines;
  }
  
  
  /* \Countable and \Iterator implementation */
  
  /**
   * @param string $code
   * @return Structures\AbstractStructure
   */
  public function getLine($code)
  {
    if (!isset($this->lines[$code])) {
      return NULL;
    }
    return $this->lines[$code];
  }
  
  /**
   * @return integer
   */
  public function count()
  {
    return count($this->lines);
  }
  
  /**
   * @return Structures\AbstractStructure
   */
  public function rewind() 
  {
    return reset($this->lines);
  }
  
  /**
   * @return Structures\AbstractStructure
   */
  public function current() 
  {
    return current($this->lines);
  }
  
  /**
   * @return string
   */
  public function key() 
  {
    return key($this->lines);
  }
  
  /**
   * @return Structures\AbstractStructure
   */
  public function next() 
  {
    return next($this->lines);
  }
  
  /**
   * @return bool
   */
  public function valid() 
  {
    return current($this->lines) !== FALSE;
  }

}