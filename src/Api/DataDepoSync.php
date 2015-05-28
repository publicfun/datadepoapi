<?php
namespace Datadepo\Api;

class DataDepoSync
{
  
  /** @var IniConfiguration */
  protected $iniConfiguration;
  
  /** @var DataStores\IDataStore */
  protected $dataStore;
  
  
  /** @var bool */
  private $dataStoreConnected = FALSE;
  
  /**
   * @param DataStores\IDataStore $dataStore
   * @param IniConfiguration $iniConfiguration
   */
  public function __construct(DataStores\IDataStore $dataStore, IniConfiguration $iniConfiguration)
  {
    $this->dataStore = $dataStore;
    $this->iniConfiguration = $iniConfiguration;
  }
  
  /**
   * Sync data (identificators, names, descriptions, parameters, images)
   * @return DataDepoResponse
   */
  public function data()
  {
    return $this->synchronize(new Synchronizers\DataSynchronizer($this->dataStore, $this->iniConfiguration));
  }
  
  /**
   * Sync business (suppliers, prices, store, variants)
   * @return DatadepoResponse
   */
  public function business()
  {
    return $this->synchronize(new Synchronizers\BusinessSynchronizer($this->dataStore, $this->iniConfiguration));
  }
  
  /**
   * Sync suppliers (invoice data, persons, bank accounts etc ...)
   * @return DatadepoResponse
   */
  public function suppliers()
  {
    return $this->synchronize(new Synchronizers\SuppliersSynchronizer($this->dataStore, $this->iniConfiguration));
  }
  
  /**
   * Sync categories tree (names, paths, depends etc ...)
   * @return DatadepoResponse
   */
  public function categories()
  {
    return $this->synchronize(new Synchronizers\CategorySynchronizer($this->dataStore, $this->iniConfiguration));
  }
  
  /**
   * @param Synchronizers\AbstractSynchronizer $synchronizer
   * @return DataDepoResponse
   */
  protected function synchronize(Synchronizers\AbstractSynchronizer $synchronizer)
  {
    $this->connect();
    return $synchronizer->sync();
  }
  
  
  /**
   */
  protected function connect()
  {
    if (!$this->dataStoreConnected) {
      $this->dataStore->setIniConfiguration($this->iniConfiguration);
      $this->dataStore->connect();
    }
  }
  
  
}