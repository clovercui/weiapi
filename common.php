<?php
define('ROOT_PATH', __DIR__. '/');
require_once(ROOT_PATH.'lib/common/function.php');


$libPath = ROOT_PATH.'lib';
set_include_path(get_include_path().
PATH_SEPARATOR.$libPath);

spl_autoload_register(function($className){
    $classNameInfo = explode('_', $className);
    if(count($classNameInfo) > 1) {
        $className = str_replace('_', '/', $className);
    }
    $className = strtolower($className);
    $file = $className . ".php";
    require_once $file;
});