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
   * @param array $checksums
   * @parma array $temp
   */
  protected function processChunk($checksums, $temp)
  {
    /* @var $temp Api\Structures\CategoryLine[] */
    $actual = $this->dataStore->getChecksums('categories', array_keys($checksums));
    
    $this->dataStore->startChunkProcess();
    foreach ($checksums as $code => $checksum) {
      if (!isset($actual[$code])) {
        $this->dataStore->insertRow($temp[$code]);
      }
      elseif ($actual[$code] != $checksum) {
        $this->dataStore->updateRow($temp[$code]);
      }
    }
    $this->dataStore->endChunkProcess();
  }
  
}