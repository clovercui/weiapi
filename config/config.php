<?php

define('ROOT_PATH',dirname(__DIR__). '/');
define('DEPLOY_MODE', false);
if(DEPLOY_MODE){
    $dbConfig = require_once(ROOT_PATH.'config/online.db.php');
} else {
    $dbConfig = require_once(ROOT_PATH.'config/local.db.php');
}
