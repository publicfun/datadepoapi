<?php
namespace Datadepo\Api\Synchronizers;
use Datadepo\Api;

class BusinessSynchronizer extends AbstractSynchronizer
{
  
  /**
   * @return Api\DataDepoResponse
   */
  protected function makeSync()
  {
    $config = $this->dataStore->getConfig('business_last', 'business_rows');
    return $this->callSync('business', $config, $this->iniConfiguration->get('limits', 'business'));
  }
  
  /**
   * @retrun Api\Structures\BusinessLine
   */
  protected function wrapLine($line)
  {
    return new Api\Structures\BusinessLine($line);
  }
  
  /**
   * @param Api\Collector $collector
   */
  protected function processChunk(Api\Collector $collector)
  {
    /* @var $temp Api\Structures\BusinessLine[] */
    $actual = $this->dataStore->getChecksums('business', $collector->getPrimaryKeys());
    
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