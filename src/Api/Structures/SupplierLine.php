<?php
namespace Datadepo\Api\Structures;

/**
 * @property-read integer $primary
 * @property-read integer $dataDepoId
 * @property-read bool $deleted
 * @property-read string $project
 * @property-read string $name
 * @property-read double $rabat
 * @property-read string $invoiceCompany
 * @property-read string $invoiceStreet
 * @property-read string $invoiceCity
 * @property-read string $invoiceZipCode
 * @property-read string $invoiceIco
 * @property-read string $invoiceDic
 * @property-read bool $isVatPayer
 * @property-read string $note
 * @property-read SupplierPersonLine[] $persons
 * @property-read SupplierBankAccountLine[] $bankAccounts
 */
class SupplierLine extends AbstractStructure
{
  
  /** @var SupplierPersonLine[] */
  private $_persons;
  
  /** @var SupplierBankAccountLine[] */
  private $_bankAccounts;
  
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
   * @return string
   */
  public function getName()
  {
    return $this->data->name;
  }
  
  /**
   * This column has only information character, do not calculate your prices using this column
   * @return double
   */
  public function getRabat()
  {
    return $this->data->rabat;
  }
  
  /**
   * @return string
   */
  public function getInvoiceCompany()
  {
    return $this->data->invoiceCompany;
  }
  
  /**
   * @return string
   */
  public function getInvoiceStreet()
  {
    return $this->data->invoiceStreet;
  }
  
  /**
   * @return string
   */
  public function getInvoiceCity()
  {
    return $this->data->invoiceCity;
  }
  
  /**
   * @return string
   */
  public function getInvoiceZipCode()
  {
    return $this->data->invoiceZipCode;
  }
  
  /**
   * @return string
   */
  public function getInvoiceIco()
  {
    return $this->data->invoiceIco;
  }
  
  /**
   * @return string
   */
  public function getInvoiceDic()
  {
    return $this->data->invoiceDic;
  }
  
  /**
   * @return bool
   */
  public function getIsVatPayer()
  {
    return (bool)$this->data->invoiceVat;
  }
  
  /**
   * @return string
   */
  public function getNote()
  {
    return $this->data->note;
  }
  
  /**
   * @return SupplierPersonLine[]
   */
  public function getPersons()
  {
    if ($this->_persons === NULL) {
      $this->_persons = array();
      foreach ($this->data->persons as $inId => $data) {
        $this->_persons[$inId] = new SupplierPersonLine($data, $inId);
      }
    }
    return $this->_persons;
  }
  
  /**
   * @return SupplierBankAccountLine[]
   */
  public function getBankAccounts()
  {
    if ($this->_bankAccounts === NULL) {
      $this->_bankAccounts = array();
      foreach ($this->data->bankAccounts as $inId => $data) {
        $this->_bankAccounts[$inId] = new SupplierBankAccountLine($data, $inId);
      }
    }
    return $this->_bankAccounts;
  }
  
  
}