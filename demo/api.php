<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shanhuhai
 * Date: 13-12-7
 * Time: 下午4:18
 * To change this template use File | Settings | File Templates.
 */
require_once(dirname(__DIR__).'/api.php');

if(isset($argv[1])) {
    $msg = $argv[1];
} else {
    $msg = '听歌 春天里 汪峰';
}
WeiApi::reply($msg);