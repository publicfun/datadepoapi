<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read string $supplierSet
 * @property-read BusinessSupplierLine[] $suppliers
 */
class BusinessLine extends AbstractStructure
{
  
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
    static $suppliers = NULL;
    if ($suppliers === NULL) {
      $suppliers = array();
      foreach ($this->data->suppliers as $idName => $data) {
        $suppliers[$idName] = new SupplierLine($data, $idName);
      }
    }
    return $suppliers;
  }
  
  
}