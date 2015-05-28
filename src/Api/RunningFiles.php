<?php
namespace Datadepo\Api;

class RunningFiles
{
  
  /** @var IniConfiguration */
  protected $iniConfiguration;
  
  /** @var string */
  protected $filename;
  
  /**
   * @param IniConfiguration $iniConfiguration
   * @param string $filename
   */
  public function __construct(IniConfiguration $iniConfiguration, $filename)
  {
    $this->iniConfiguration = $iniConfiguration;
    $this->filename = $filename;
  }
  
  /**
   * @return bool
   */
  public function exists()
  {
    if (!$this->isActive()) {
      return FALSE;
    }
    $file = $this->getRunningFile();
    return is_file($file) && $this->isValid($file);
  }
  
  /**
   */
  public function create()
  {
    if (!$this->isActive()) {
      return FALSE;
    }
    file_put_contents($this->getRunningFile(), time());
  }
  
  /**
   */
  public function delete()
  {
    if (!$this->isActive()) {
      return FALSE;
    }
    unlink($this->getRunningFile());
  }
  
  
  /**
   * @return bool
   */
  protected function isActive()
  {
    return $this->iniConfiguration->get('local', 'createRunningFiles');
  }
  
  /**
   * @return string
   */
  protected function getRunningFile()
  {
    $tempPath = $this->iniConfiguration->getTempPath();
    return $tempPath . '/' . $this->filename . '.running';
  }
  
  /**
   * @param string $file
   * @reutrn bool
   */
  protected function isValid($file)
  {
    $c = file_get_contents($file);
    $validity = $this->iniConfiguration->get('local', 'runningFileValidity');
    return $c + $validity >= time();
  }
  
}