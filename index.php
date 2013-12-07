<?php
header('Content-type:text/html;Charset=utf-8');
require('common.php');
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