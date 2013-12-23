<?php

define('DEBUG_MODE', true);
//define('DEBUG_MODE', false);
define('UPDATE_MODE', false);
//define('DEBUG_MODE', true);

define('TASK_NAME', '<{taskName}>');
require_once('<{rootPath}>easyspider.php');

define('FILE_PATH', ROOT_PATH.'tasks/'.TASK_NAME.'/image/');
define('FILE_DOMAIN', '<{fileDomain}>');

global $dbConfig;
$settings = array(
    'domain'=>'<{domain}>',
    'anyPageUrl'=>'',
    'charset'=>'<{charset}>',
    'cacheList'=>0,
    'cachePage'=>0,
    'reverseList'=>0,
    'dbConfig'=> $dbConfig
);

$listConfigs = <{listConfigs}>;

