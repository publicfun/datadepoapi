<?php
namespace Datadepo\Api;

class ApiException extends \Exception {}

/**
 * Thrown when configuration, section or variables not found or is not readable
 */
class ConfigurationException extends ApiException {}

/**
 * Thrown when stop signal from datadepo is received
 */
class DataDepoStoppedException extends ApiException {}

/**
 * Thrown when pid file is allready created
 */
class DataDepoRunningException extends ApiException {}