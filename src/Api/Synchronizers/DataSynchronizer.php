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
   * @param array $checksums
   * @parma array $temp
   */
  protected function processChunk($checksums, $temp)
  {
    /* @var $temp Api\Structures\DataLine[] */
    $actual = $this->dataStore->getChecksums('data', array_keys($checksums));
    
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