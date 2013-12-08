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

class WeiApi {
    public static $dispatch = null;

    public  static function reply($keyword){
        self::getDispatch();
        return self::$dispatch->reply($keyword);
    }

    public static function getDispatch(){
        if(is_null(self::$dispatch)) {
            self::$dispatch = new Dispatcher();
        }
        return self::$dispatch;
    }
}