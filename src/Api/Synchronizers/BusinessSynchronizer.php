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
   * @param array $checksums
   * @parma array $temp
   */
  protected function processChunk($checksums, $temp)
  {
    /* @var $temp Api\Structures\BusinessLine[] */
    $actual = $this->dataStore->getChecksums('business', array_keys($checksums));
    
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