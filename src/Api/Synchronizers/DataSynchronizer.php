<?php
namespace Datadepo\Api\Synchronizers;
use Datadepo\Api;

class DataSynchronizer extends AbstractSynchronizer
{
  
  /**
   * @return Api\DataDepoResponse
   */
  protected function makeSync()
  {
    $config = $this->dataStore->getConfig('data_last', 'data_rows');
    return $this->callSync('data', $config, $this->iniConfiguration->get('limits', 'data'));
  }
  
  /**
   * @retrun Api\Structures\DataLine
   */
  protected function wrapLine($line)
  {
    return new Api\Structures\DataLine($line);
  }
  
  /**
   * @param Api\Collector $collector
   */
  protected function processChunk(Api\Collector $collector)
  {
    /* @var $temp Api\Structures\BusinessLine[] */
    $actual = $this->dataStore->getChecksums('data', $collector->getPrimaryKeys());
    
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