<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read string $supplierSet
 * @property-read BusinessSupplierLine[] $suppliers
 */
class BusinessLine extends AbstractStructure
{
  
  /** @var BusinessSupplierLine[] */
  private $_suppliers;
  
  /**
   * @return string
   */
  public function getPrimary()
  {
    return $this->data->code;
  }
  
  /**
   * @return array
   */
  public function getData()
  {
    $data = array('code' => $this->getPrimary(),
                  'supplier_set' => $this->getSupplierSet(),
                  'json' => $this->json,
                  'checksum' => $this->getChecksum());
    return $data;
  }
  
  /**
   * @return string
   */
  public function getSupplierSet()
  {
    return $this->data->supplierSet;
  }
  
  /**
   * @return BusinessSupplierLine[]
   */
  public function getSuppliers()
  {
    if ($this->_suppliers === NULL) {
      $this->_suppliers = array();
      foreach ($this->data->suppliers as $idName => $data) {
        $this->_suppliers[$idName] = new BusinessSupplierLine($data, $idName);
      }
    }
    return $this->_suppliers;
  }
  
  
}