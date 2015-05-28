<?php
namespace Datadepo\Api;

class IniConfiguration
{
  
  /** @var string */
  protected $iniPath;
  
  /** @var array */
  protected $settings;
  
  /** @var array */
  protected $rewriteOptions;
  
  /**
   * @param string $iniPath
   * @param array $rewriteOptions
   */
  public function __construct($iniPath, array $rewriteOptions = array())
  {
    $this->iniPath = $iniPath;
    $this->rewriteOptions = $rewriteOptions;
  }
  
  /**
   * @param string $section
   * @param string $variable
   * @return mixed
   */
  public function get($section, $variable = NULL)
  {
    $this->read();
    if (!isset($this->settings[$section])) {
      throw new ConfigurationException('Section ' . $section . ' not found (' . $this->iniPath . ')');
    }
    $sectionData = $this->settings[$section];
    if ($variable !== NULL) {
      if (!isset($sectionData[$variable])) {
        throw new ConfigurationException('Variable '.$variable.' not found in ' . $section . ' ('.$this->iniPath.')');
      }
      return $sectionData[$variable];
    }
    return $sectionData;
  }
  
  /**
   * @return string
   */
  public function getTempPath()
  {
    $temp = $this->get('local', 'tempPath');
    if (!is_writable($temp)) {
      throw new ApiException('Directory ' . $temp . ' is not writable or doesn\'t exist.');
    }
    return $temp;
  }
  
  /**
   */
  protected function read()
  {
    if ($this->settings !== NULL) {
      return;
    }
    
    if (!is_file($this->iniPath)) {
      throw new ConfigurationException('Ini file ' . $this->iniPath . ' not found.');
    }
    
    $this->settings = parse_ini_file($this->iniPath, TRUE);
    $this->settings = array_replace_recursive($this->settings, $this->rewriteOptions);
  }
  
}