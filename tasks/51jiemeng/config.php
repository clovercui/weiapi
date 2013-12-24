<?php

//define('DEBUG_MODE', true);
define('DEBUG_MODE', false);
define('UPDATE_MODE', false);
//define('DEBUG_MODE', true);

define('TASK_NAME', '51jiemeng');
require_once('/Users/shanhuhai/wwwroot/weiapi/easyspider.php');

define('FILE_PATH', ROOT_PATH.'tasks/'.TASK_NAME.'/image/');
define('FILE_DOMAIN', 'http://**');

global $dbConfig;
$settings = array(
    'domain'=>'http://www.51jiemeng.com',
    'anyPageUrl'=>'',
    'charset'=>'gbk',
    'cacheList'=>0,
    'cachePage'=>0,
    'reverseList'=>0,
    'dbConfig'=> $dbConfig
);

$listConfigs = array (
  0 => 
  array (
    'catid' => '2',
    'urlReg' => '/more/3_1553_{\d}.shtml',
    'firstUrl' => '/more/3_1553.shtml',
    'start' => '1',
    'end' => '11',
    'updateEnd' => '0',
    'parseCss' => '.list_dream a',
    'listType' => 'html',
    'dislocation' => 1,
    'domain' => '',
    'fields' => 
    array (
      'title' => '',
      'tags' => '',
      'content' => '',
      'thumb' => '',
      'source' => '',
      'from' => '',
      'created' => '',
    ),
  ),
  1 => 
  array (
    'catid' => '3',
    'urlReg' => '/more/3_1557_{\d}.shtml',
    'firstUrl' => '/more/3_1557.shtml',
    'start' => '1',
    'end' => '6',
    'updateEnd' => '0',
    'parseCss' => '.list_dream a',
    'listType' => 'html',
    'dislocation' => 1,
    'domain' => '',
    'fields' => 
    array (
      'title' => '',
      'tags' => '',
      'content' => '',
      'thumb' => '',
      'source' => '',
      'from' => '',
      'created' => '',
    ),
  ),
  2 => 
  array (
    'catid' => '4',
    'urlReg' => '/more/3_1554_{\d}.shtml',
    'firstUrl' => '/more/3_1554.shtml',
    'start' => '1',
    'end' => '4',
    'updateEnd' => '0',
    'parseCss' => '.list_dream a',
    'listType' => 'html',
    'dislocation' => 1,
    'domain' => '',
    'fields' => 
    array (
      'title' => '',
      'tags' => '',
      'content' => '',
      'thumb' => '',
      'source' => '',
      'from' => '',
      'created' => '',
    ),
  ),
  3 => 
  array (
    'catid' => '5',
    'urlReg' => '/more/3_1558_{\d}.shtml',
    'firstUrl' => '/more/3_1558.shtml',
    'start' => '1',
    'end' => '13',
    'updateEnd' => '0',
    'parseCss' => '.list_dream a',
    'listType' => 'html',
    'dislocation' => 1,
    'domain' => '',
    'fields' => 
    array (
      'title' => '',
      'tags' => '',
      'content' => '',
      'thumb' => '',
      'source' => '',
      'from' => '',
      'created' => '',
    ),
  ),
  4 => 
  array (
    'catid' => '6',
    'urlReg' => '/more/3_1559_{\d}.shtml',
    'firstUrl' => '/more/3_1559.shtml',
    'start' => '1',
    'end' => '8',
    'updateEnd' => '0',
    'parseCss' => '.list_dream a',
    'listType' => 'html',
    'dislocation' => 1,
    'domain' => '',
    'fields' => 
    array (
      'title' => '',
      'tags' => '',
      'content' => '',
      'thumb' => '',
      'source' => '',
      'from' => '',
      'created' => '',
    ),
  ),
  5 => 
  array (
    'catid' => '7',
    'urlReg' => '/more/3_1556_{\d}.shtml',
    'firstUrl' => '/more/3_1556.shtml',
    'start' => '1',
    'end' => '20',
    'updateEnd' => '0',
    'parseCss' => '.list_dream a',
    'listType' => 'html',
    'dislocation' => 1,
    'domain' => '',
    'fields' => 
    array (
      'title' => '',
      'tags' => '',
      'content' => '',
      'thumb' => '',
      'source' => '',
      'from' => '',
      'created' => '',
    ),
  ),
  6 => 
  array (
    'catid' => '8',
    'urlReg' => '/more/3_1555_{\d}.shtml',
    'firstUrl' => '/more/3_1555.shtml',
    'start' => '1',
    'end' => '35',
    'updateEnd' => '0',
    'parseCss' => '.list_dream a',
    'listType' => 'html',
    'dislocation' => 1,
    'domain' => '',
    'fields' => 
    array (
      'title' => '',
      'tags' => '',
      'content' => '',
      'thumb' => '',
      'source' => '',
      'from' => '',
      'created' => '',
    ),
  ),
  7 => 
  array (
    'catid' => '9',
    'urlReg' => '/more/3_1560_{\d}.shtml',
    'firstUrl' => '/more/3_1560.shtml',
    'start' => '1',
    'end' => '6',
    'updateEnd' => '0',
    'parseCss' => '.list_dream a',
    'listType' => 'html',
    'dislocation' => 1,
    'domain' => '',
    'fields' => 
    array (
      'title' => '',
      'tags' => '',
      'content' => '',
      'thumb' => '',
      'source' => '',
      'from' => '',
      'created' => '',
    ),
  ),
  8 => 
  array (
    'catid' => '10',
    'urlReg' => '/more/3_1562_{\d}.shtml',
    'firstUrl' => '/more/3_1562.shtml',
    'start' => '1',
    'end' => '11',
    'updateEnd' => '0',
    'parseCss' => '.list_dream a',
    'listType' => 'html',
    'dislocation' => 1,
    'domain' => '',
    'fields' => 
    array (
      'title' => '',
      'tags' => '',
      'content' => '',
      'thumb' => '',
      'source' => '',
      'from' => '',
      'created' => '',
    ),
  ),
  9 => 
  array (
    'catid' => '11',
    'urlReg' => '/more/3_1561_{\d}.shtml',
    'firstUrl' => '/more/3_1561.shtml',
    'start' => '1',
    'end' => '7',
    'updateEnd' => '0',
    'parseCss' => '.list_dream a',
    'listType' => 'html',
    'dislocation' => 1,
    'domain' => '',
    'fields' => 
    array (
      'title' => '',
      'tags' => '',
      'content' => '',
      'thumb' => '',
      'source' => '',
      'from' => '',
      'created' => '',
    ),
  ),
);

