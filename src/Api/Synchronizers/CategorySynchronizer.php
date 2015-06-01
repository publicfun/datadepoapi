<?php
namespace Datadepo\Api\Synchronizers;
use Datadepo\Api;

class CategorySynchronizer extends AbstractSynchronizer
{
  
  /**
   * @return Api\DataDepoResponse
   */
  protected function makeSync()
  {
    $config = $this->dataStore->getConfig('categories_last', 'categories_rows');
    return $this->callSync('categories', $config, $this->iniConfiguration->get('limits', 'categories'));
  }
  
  /**
   * @retrun Api\Structures\CategoryLine
   */
  protected function wrapLine($line)
  {
    return new Api\Structures\CategoryLine($line);
  }
  
  /**
   * @param Api\Collector $collector
   */
  protected function processChunk(Api\Collector $collector)
  {
    /* @var $temp Api\Structures\BusinessLine[] */
    $actual = $this->dataStore->getChecksums('categories', $collector->getPrimaryKeys());
    
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