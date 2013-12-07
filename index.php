<?php
define('DEPLOY_MODE', false);
//define('DEPLOY_MODE', true);
define('LOCAL_DATA_MODE', false);
//define('LOCAL_DATA_MODE', false);

require('common.php');

if(DEPLOY_MODE){
    $dbConfig = require_once(ROOT_PATH.'config/online.db.php');
} else {
    $dbConfig = require_once(ROOT_PATH.'config/local.db.php');
}

$msg = '';
if(isset($_REQUEST['msg'])) {
    $msg = $_REQUEST['msg'];
    $msg = urldecode($msg);
} elseif(isset($argv[1])) {
    $msg = $argv[1];
} else {
    $msg = '听歌 春天里 汪峰';
}

$dispatch = new Dispatcher($msg);
$dispatch->run();