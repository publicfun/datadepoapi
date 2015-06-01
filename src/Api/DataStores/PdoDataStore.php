<?php
namespace Datadepo\Api\DataStores;
use Datadepo\Api;

class PdoDataStore implements IDataStore
{
  
  /** @var \PDO */
  protected $pdo;
  
  /** @var Api\IniConfiguration */
  protected $iniConfiguration;

  /**
   * @param \PDO $pdo
   */
  public function __construct(\PDO $pdo = NULL) 
  {
    $this->pdo = $pdo;
  }
  
  /**
   * @param Api\IniConfiguration $iniConfiguration
   */
  public function setIniConfiguration(Api\IniConfiguration $iniConfiguration)
  {
    $this->iniConfiguration = $iniConfiguration;
  }
  
  /**
   */
  public function connect() 
  {
    //allready connected
    if ($this->pdo instanceof \PDO) {
      return;
    }
    
    //create PDO connection from INI file section [PDO]
    $settings = $this->iniConfiguration->get('PDO');
    $this->pdo = new \PDO($settings['dsn'], $settings['user'], $settings['pwd'], $this->getPdoOptions($settings));
    $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  }
  
  /**
   * @param string $names,...
   */
  public function getConfig($names)
  {
    $names = func_get_args();
    $query = $this->pdo->query("SELECT name, value 
                                FROM datadepo_config 
                                WHERE name IN (" . $this->extractForIn($names) . ")");
    
    $values = array();
    foreach ($query->fetchAll() as $row) {
      $values[$row['name']] = $row['value'];
    }
    return $values;
  }
  
  /**
   * @param string $name
   * @param mixed $value
   */
  public function setConfig($name, $value)
  {
    $this->insertOrUpdate('datadepo_config', array('name' => $name, 'value' => $value));
  }
  
  /**
   * @param string $name
   * @param array $codes
   */
  public function getChecksums($name, $codes)
  {
    $keyColumnName = 'code';
    if (in_array($name, array('suppliers', 'categories'))) {
      $keyColumnName = 'datadepo_id';
    }
    
    $query = $this->pdo->query("SELECT $keyColumnName, checksum 
                                FROM `datadepo_$name` 
                                WHERE $keyColumnName IN (".  $this->extractForIn($codes).")");
    
    $rows = array();
    while($row = $query->fetch(\PDO::FETCH_ASSOC)) {
      $rows[$row[$keyColumnName]] = $row['checksum'];
    }
    return $rows;
  }
  
  
  /* Data lines insert / update */
  
  /**
   */
  public function startChunkProcess()
  {
    $this->pdo->beginTransaction();
  }
  
  /**
   */
  public function endChunkProcess()
  {
    $this->pdo->commit();
  }
    
  /**
   * @param Api\Structures\AbstractStructure $line
   */
  public function insertRow(Api\Structures\AbstractStructure $line)
  {
    $table = NULL;
    switch (TRUE) {
      case $line instanceof Api\Structures\DataLine:
        $this->processDataLine($line);
        break;
      case $line instanceof Api\Structures\BusinessLine:
        $this->processBusinessLine($line);
        break;
      case $line instanceof Api\Structures\SupplierLine:
        $this->processSupplierLine($line);
        break;
      case $line instanceof Api\Structures\CategoryLine:
        $this->processCategoryLine($line);
        break;
    }
  }
  
  /**
   * @param Api\Structures\AbstractStructure $line
   */
  public function updateRow(Api\Structures\AbstractStructure $line)
  {
    $this->insertRow($line);
  }
  
  /**
   * @param Api\Structures\DataLine $line
   */
  protected function processDataLine(Api\Structures\DataLine $line)
  {
    if ($line->getDeleted()) {
      $data = array('code' => $line->getPrimary(),
                    'json' => $line->getJson(),
                    'checksum' => $line->getChecksum(),
                    'deleted' => 1);
    }
    else {
      $data = array('project' => $line->getProject(),
                    'code' => $line->getPrimary(),
                    'name' => $line->getName(),
                    'name_sub' => $line->getNameSub(),
                    'pair_value' => $line->getPairValue(),
                    'ean' => $line->getEan(),
                    'isbn' => $line->getIsbn(),
                    'description' => $line->getDescription(),
                    'category_id' => $line->getCategoryId(),
                    'json' => $line->getJson(),
                    'deleted' => 0,
          
                    //checksums
                    'checksum' => $line->getChecksum(),
                    'checksum_images' => $line->getChecksumImages(),
                    'checksum_parameters' => $line->getChecksumParameters(),
                    'checksum_related' => $line->getChecksumRelated());
    }
    $this->insertOrUpdate('datadepo_data', $data);
  }
  
  /**
   * @param Api\Structures\BusinessLine $line
   */
  protected function processBusinessLine(Api\Structures\BusinessLine $line)
  {
    $data = array('code' => $line->getPrimary(),
                  'supplier_set' => $line->getSupplierSet(),
                  'json' => $line->getJson(),
                  'checksum' => $line->getChecksum());
    $this->insertOrUpdate('datadepo_business', $data);
  }
  
  /**
   * @param Api\Structures\SupplierLine $line
   */
  protected function processSupplierLine(Api\Structures\SupplierLine $line)
  {
    $data = array('project' => $line->getProject(),
                  'datadepo_id' => $line->getDataDepoId(),
                  'name' => $line->getName(),
                  'deleted' => intval($line->getDeleted()),
                  'json' => $line->getJson(),
                  'checksum' => $line->getChecksum());
    $this->insertOrUpdate('datadepo_suppliers', $data);
  }
  
  /**
   * @param Api\Structures\CategoryLine $line
   */
  protected function processCategoryLine(Api\Structures\CategoryLine $line)
  {
    $data = array('project' => $line->getProject(),
                  'datadepo_id' => $line->getDataDepoId(),
                  'parent_id' => $line->getParentId(),
                  'position' => $line->getPosition(),
                  'name' => $line->getName(),
                  'name_path' => $line->getNamePath(),
                  'active' => intval($line->getActive()),
                  'deleted' => intval($line->getDeleted()),
                  'json' => $line->getJson(),
                  'checksum' => $line->getChecksum()); 
    $this->insertOrUpdate('datadepo_categories', $data);
  }
  
  
  
  
  /* Helpers */
  
  /**
   * @param array $settings
   * @return array
   */
  protected function getPdoOptions($settings)
  {
    $opts = array();
    if (isset($settings['opts'])) {
      foreach ($settings['opts'] as $const => $value) {
        $opts[constant('\PDO::' . $const)] = $value;
      }
    }
    return $opts;
  }
  
  /**
   * @param array $values
   * @return array
   */
  protected function extractForIn($values)
  {
    $pdo = $this->pdo;
    return implode(', ', array_map(function($i) use ($pdo) { return $pdo->quote($i); }, $values));
  }
  
  /**
   * @param string $table
   * @param array $data
   */
  protected function insertOrUpdate($table, $data)
  {
    $binds = array_combine(array_map(function($input) { return ':' . $input; }, array_keys($data)), $data);
    $updateBinds = array_map(function($input) { return $input . ' = :' . $input; }, array_keys($data));
    
    
    $query = $this->pdo->prepare("INSERT INTO $table (".  implode(', ', array_keys($data)).") 
                                  VALUES (".  implode(', ', array_keys($binds)).")
                                  ON DUPLICATE KEY UPDATE " . implode(', ', $updateBinds));
    $query->execute($binds);
  }
  
}