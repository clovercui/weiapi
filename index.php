<?php
//$msg = $_REQUEST['msg'];
$msg = 'xiaohua@sss';
$msg = urldecode($msg);

preg_match('/([^\s@]+)[\s@](.+)/', $msg, $matches);

$module = $matches[1];
$module = hanzi2pinyin($module);

$content = $matches[2];
require_once("lib/{$module}.php");
$className = ucfirst($module);
$instance = new $className();
echo $instance->reply($content);


function hanzi2pinyin($hanzi){
    return $hanzi;
    $pinyin = '';

    return $pinyin;
}