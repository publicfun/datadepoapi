<?php
namespace Datadepo\Api\Synchronizers;
use Datadepo\Api;



abstract class AbstractSynchronizer
{
  
  /** @var Api\DataStores\IDataStore */
  protected $dataStore;
  
  /** @var Api\IniConfiguration */
  protected $iniConfiguration;
  
  /** @var Api\ApiWrapper */
  private $_wrapper;
  
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
   * @param Api\Collector $collector
   */
  abstract protected function processChunk(Api\Collector $collector);
  
  
  /**
   * @return Api\DataDepoResponse
   */
  public function sync()
  {
    try {
      $running = $this->runningState(); //check running file
      $this->suspendedState(); //check suspended state
      $response = $this->makeSync();
      $running->delete(); //delete running file
      return $response;
    } 
    catch (Api\DataDepoRunningException $ex) {
      return new Api\DataDepoResponse(Api\DataDepoResponse::CODE_RUNNING);
    }
    catch (Api\DataDepoSuspendedException $ex) {
      $running->delete();
      return new Api\DataDepoResponse(Api\DataDepoResponse::CODE_SUSPENDED);
    }
    catch (\Exception $ex) {
      if ($running !== NULL) {
        $running->delete();
      }
      throw $ex;
    }
  }
  
  /**
   * @return Api\ApiWrapper
   */
  public function getWrapper()
  {
    if ($this->_wrapper === NULL) {
      $this->_wrapper = new Api\ApiWrapper($this->iniConfiguration->get('datadepo'), $this->iniConfiguration->get('account'));
    }
    return $this->_wrapper;
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
   * @throws Api\DataDepoSuspendedException
   */
  protected function suspendedState()
  {
    $config = $this->dataStore->getConfig('suspendedToTime');
    if (isset($config['suspendedToTime']) && $config['suspendedToTime'] >= time()) {
      throw new Api\DataDepoSuspendedException;
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
      $response = $this->getWrapper()->request('data', $name, array('lastDownload' => $config['last']));
      
      //analyze response
      switch ($this->getWrapper()->analyzeResponse($response)) {
        case 'url':
          //continue to download
          $this->getWrapper()->download($response->url, $filePath);
          break;
        case 'suspend':
          //suspend processing for time in response
          return $this->suspend($response->until);
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
    $collector = $this->createCollector();
    $count = 0;
    
    //skip line
    $file->seek($config['rows']+1);
    while ((!$file->eof() || $file->current() !== FALSE) && $count < $limit) {
      //add line to front
      $line = $this->wrapLine($file->current());
      $collector->add($line);
      $count++;
      
      //process
      if ($count % $processCount === 0) {
        $this->processChunk($collector);
        $collector = $this->createCollector();
      }
      $file->next();
    }
    
    //sync rest
    if (count($collector) > 0) {
      $this->processChunk($collector);
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
  
  /**
   * @return Api\Collector
   */
  protected function createCollector()
  {
    return new Api\Collector;
  }
  
  /**
   * @param string $date
   * @return array
   */
  protected function suspend($date)
  {
    $this->dataStore->setConfig('suspendedToTime', strtotime($date));
    $this->getWrapper()->request('put', 'suspend', array('until' => $date));
    return new Api\DataDepoResponse(Api\DataDepoResponse::CODE_SUSPENDED);
  }
  
}