<?php

define('DATADEPO_PATH', __DIR__ . '/Api');

//core
include_once DATADEPO_PATH . '/DataStores/IDataStore.php';
include_once DATADEPO_PATH . '/Exceptions.php';
include_once DATADEPO_PATH . '/DataDepoResponse.php';
include_once DATADEPO_PATH . '/IniConfiguration.php';
include_once DATADEPO_PATH . '/RunningFiles.php';
include_once DATADEPO_PATH . '/ApiWrapper.php';
include_once DATADEPO_PATH . '/DataDepoSync.php';
include_once DATADEPO_PATH . '/Collector.php';

//synchronizers
include_once DATADEPO_PATH . '/Synchronizers/AbstractSynchronizer.php';
include_once DATADEPO_PATH . '/Synchronizers/BusinessSynchronizer.php';
include_once DATADEPO_PATH . '/Synchronizers/CategorySynchronizer.php';
include_once DATADEPO_PATH . '/Synchronizers/DataSynchronizer.php';
include_once DATADEPO_PATH . '/Synchronizers/SuppliersSynchronizer.php';

//structures
include_once DATADEPO_PATH . '/Structures/AbstractStructure.php';
include_once DATADEPO_PATH . '/Structures/BusinessLine.php';
include_once DATADEPO_PATH . '/Structures/CategoryLine.php';
include_once DATADEPO_PATH . '/Structures/DataLine.php';
include_once DATADEPO_PATH . '/Structures/SupplierLine.php';
include_once DATADEPO_PATH . '/Structures/Business/BusinessSupplierLine.php';
include_once DATADEPO_PATH . '/Structures/Business/PriceLine.php';
include_once DATADEPO_PATH . '/Structures/Business/StoreLine.php';
include_once DATADEPO_PATH . '/Structures/Supplier/SupplierBankAccountLine.php';
include_once DATADEPO_PATH . '/Structures/Supplier/SupplierPersonLine.php';