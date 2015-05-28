<?php
namespace Datadepo\Api\DataStores;
use Datadepo\Api\Structures;

interface IDataStore
{
  
  /**
   * @param \Datadepo\Api\IniConfiguration $configuration
   * @return void
   */
  function setIniConfiguration(\Datadepo\Api\IniConfiguration $configuration);
  
  /**
   * @return void
   */
  function connect();
  
  /**
   * @param string $names,...
   * @return array
   */
  function getConfig($names);
  
  /**
   * @param string $name
   * @param mixed $value
   * @return void
   */
  function setConfig($name, $value);
  
  /**
   * @param string $name
   * @param array $codes
   * @return array
   */
  function getChecksums($name, $codes);
  
  
  /* Data lines insert / update */
  
  /**
   * @return void
   */
  function startChunkProcess();
  
  /**
   * @return void
   */
  function endChunkProcess();
  
  /**
   * @param Structures\AbstractStructure $line
   * @return void
   */
  function insertRow(Structures\AbstractStructure $line);
  
  /**
   * @param Structures\AbstractStructure $line
   * @return void
   */
  function updateRow(Structures\AbstractStructure $line);
  
}