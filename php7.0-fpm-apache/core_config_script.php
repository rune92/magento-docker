<?php

$mageFilename = '../app/Mage.php';

if (!file_exists($mageFilename)) {
    echo $mageFilename." was not found";
    exit;
}

require_once $mageFilename;

Mage::setIsDeveloperMode(true);

Mage::app('admin');

/**
 * Get the resource model
 */
$resource = Mage::getSingleton('core/resource');

$table = "core_config_data";

$originalHttpHostName = 'http://www.istyle.eu/';
$originalHttpsHostName = 'https://www.istyle.eu/';

$localHostName = 'http://istyle.tld/';
/**
 * Retrieve the write connection
 */
$writeConnection = $resource->getConnection('core_write');

$query = "
		  UPDATE {$table} 
		  SET `value` = REPLACE(`value`, '{$originalHttpHostName}', '{$localHostName}') 
		  WHERE `value` LIKE '{$originalHttpHostName}';

		  UPDATE {$table} 
		  SET `value` = REPLACE(`value`, '{$originalHttpsHostName}', '{$localHostName}') 
		  WHERE `value` LIKE '{$originalHttpsHostName}';
		  ";

/**
* Execute the query
*/
$writeConnection->query($query);

echo "Origanal host name ({$originalHttpHostName}) has been changed to {$localHostName}!";