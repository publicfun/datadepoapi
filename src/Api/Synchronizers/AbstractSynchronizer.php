<?php
namespace Datadepo\Api\Synchronizers;
use Datadepo\Api;




abstract class AbstractSynchronizer
{
  
  /** @var Api\DataStores\IDataStore */
  protected $dataStore;
  
  /** @var Api\IniConfiguration */
  protected $iniConfiguration;
  
  /**
   * @param Api\DataStores\IDataStore $dataStore
   * @param Api\IniConfiguration $iniConfiguration
   */
  public function __construct(Api\DataStores\IDataStore $dataStore, Api\IniConfiguration $iniConfiguration)
  {
    $this->dataStore = $dataStore;
    $this->iniConfiguration = $iniConfiguration;
  }
  
  /**
   * @return Api\DataDepoResponse
   */
  abstract protected function makeSync();
  
  /**
   * @retrun Api\Structures\AbstractStructure
   */
  abstract protected function wrapLine($line);
  
  /**
   * @param array $checksums
   * @parma array $temp
   */
  abstract protected function processChunk($checksums, $temp);
  
  
  /**
   * @return Api\DataDepoResponse
   */
  public function sync()
  {
    try {
      $running = $this->runningState(); //check running file
      $this->stoppedState(); //check stopped state
      $response = $this->makeSync();
      $running->delete(); //delete running file
      return $response;
    } 
    catch (Api\DataDepoRunningException $ex) {
      return new Api\DataDepoResponse(Api\DataDepoResponse::CODE_RUNNING);
    }
    catch (Api\DataDepoStoppedException $ex) {
      $running->delete();
      return new Api\DataDepoResponse(Api\DataDepoResponse::CODE_STOPPED);
    }
    catch (\Exception $ex) {
      if ($running !== NULL) {
        $running->delete();
      }
      throw $ex;
    }
  }
  
  
  /* States */
  
  /**
   * @throws Api\DataDepoRunningException
   */
  protected function runningState()
  {
    $running = new Api\RunningFiles($this->iniConfiguration, $this->getRunningFileName());
    if ($running->exists()) {
      throw new Api\DataDepoRunningException;
    }
    $running->create();
    return $running;
  }
  
  /**
   * @return string
   */
  protected function getRunningFileName()
  {
    $rf = new \ReflectionClass(get_class($this));
    return strtolower($rf->getShortName());
  }
  
  /**
   * @throws Api\DataDepoStoppedException
   */
  protected function stoppedState()
  {
    $config = $this->dataStore->getConfig('stoppedToTime');
    if (isset($config['stoppedToTime']) && $config['stoppedToTime'] >= time()) {
      throw new Api\DataDepoStoppedException;
    }
    return FALSE;
  }
  
  
  
  /* Sync */
  
  /**
   * @param string $name
   * @param array $configRaw
   * @param integer $limit
   */
  protected function callSync($name, $configRaw, $limit)
  {
    //normalize config
    $config = array('last' => isset($configRaw[$name . '_last']) ? $configRaw[$name . '_last'] : 0,
                    'rows' => isset($configRaw[$name . '_rows']) ? $configRaw[$name . '_rows'] : 0);
    
    $filePath = $this->iniConfiguration->getTempPath() . '/' . $name . '.data';
    if ($config['rows'] == 0) {
      //create new request
      $wrapper = new Api\ApiWrapper($this->iniConfiguration->get('datadepo'), $this->iniConfiguration->get('account'));
      $response = $wrapper->request('data', $name, array('lastDownload' => $config['last']));
      
      //analyze response
      switch ($wrapper->analyzeResponse($response)) {
        case 'url':
          //continue to download
          $wrapper->download($response->url, $filePath);
          break;
        case 'stop':
          //stop processing for time in response
          $this->stopProcessing($response['date']);
          break;
        default:
          throw new Api\ApiException('Response from datadepo is unknow');
      }
    }

    //open file and read header
    $file = new \SplFileObject($filePath);
    $header = json_decode($file->fgets(), TRUE);
    
    //check number of lines in downloaded file
    if ($config['rows'] == 0) {
      $file->seek(PHP_INT_MAX);
      $linesTotal = $file->key()+1;
      if ($header['numRecords'] != $linesTotal-1) {
        throw new Api\ApiException('Incompleted file downloaded in ' . $name . ' synchronizer');
      }
      $file->rewind();
    }
    
    
    $processCount = $this->iniConfiguration->get('limits', 'processCount');
    
    //read file lines
    $fileIterator = new \LimitIterator($file, $config['rows']+1, $limit);
    $count = 0;
    $checksums = array();
    $temp = array();
    foreach ($fileIterator as $line) {
      if (!$line) { continue; }
      
      //add line to front
      $line = $this->wrapLine($line);
      $checksums[$line->getPrimary()] = $line->getChecksum();
      $temp[$line->getPrimary()] = $line;
      $count++;
      
      //process
      if ($count % $processCount === 0) {
        $this->processChunk($checksums, $temp);
        $checksums = array();
        $temp = array();
      }
    }
    
    //sync rest
    if ($checksums) {
      $this->processChunk($checksums, $temp);
    }

    //check num processed
    if ($count == $limit) {
      $this->dataStore->setConfig($name . '_rows', $config['rows']+$count);
    }
    else {
      $this->dataStore->setConfig($name . '_rows', 0);
      $this->dataStore->setConfig($name . '_last', $header['generatedAt']);
    }
    
    return new Api\DataDepoResponse(Api\DataDepoResponse::CODE_OK, NULL, array('processed' => $count));
  }
  
}