<?php
namespace Datadepo\Api\Synchronizers;
use Datadepo\Api;

class SuppliersSynchronizer extends AbstractSynchronizer
{
  
  /**
   * @return Api\DataDepoResponse
   */
  protected function makeSync()
  {
    $config = $this->dataStore->getConfig('suppliers_last', 'suppliers_rows');
    return $this->callSync('suppliers', $config, $this->iniConfiguration->get('limits', 'suppliers'));
  }
  
  /**
   * @retrun Api\Structures\SupplierLine
   */
  protected function wrapLine($line)
  {
    return new Api\Structures\SupplierLine($line);
  }
  
  /**
   * @param Api\Collector $collector
   */
  protected function processChunk(Api\Collector $collector)
  {
    /* @var $temp Api\Structures\BusinessLine[] */
    $actual = $this->dataStore->getChecksums('suppliers', $collector->getPrimaryKeys());
    
    $this->dataStore->startChunkProcess();
    
    /* @var $line Api\Structures\BusinessLine */
    foreach ($collector as $code => $line) {
      if (!isset($actual[$code])) {
        $this->dataStore->insertRow($line);
      }
      elseif ($actual[$code] != $line->getChecksum()) {
        $this->dataStore->updateRow($line);
      }
    }
    $this->dataStore->endChunkProcess();
  }
  
}