<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shanhuhai
 * Date: 13-12-20
 * Time: 上午11:52
 * To change this template use File | Settings | File Templates.
 */
class Util_Valid{
    public static function isChinese($keyword){
        return preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $keyword);
    }

}