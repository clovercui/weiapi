<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shanhuhai
 * Date: 13-12-7
 * Time: 下午3:43
 * To change this template use File | Settings | File Templates.
 */
require_once('common.php');
class WeiApi {
    public static $dispatch = null;

    public  static function reply($keyword){
        self::getDispatch();
        self::$dispatch->reply($keyword);
    }

    public static function getDispatch(){
        if(is_null(self::$dispatch)) {
            self::$dispatch = new Dispatcher();
        }
        return self::$dispatch;
    }
}