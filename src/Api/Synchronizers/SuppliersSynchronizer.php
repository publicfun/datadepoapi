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
   * @param array $checksums
   * @parma array $temp
   */
  protected function processChunk($checksums, $temp)
  {
    /* @var $temp Api\Structures\SupplierLine[] */
    $actual = $this->dataStore->getChecksums('suppliers', array_keys($checksums));
    
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