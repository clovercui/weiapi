<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shanhuhai
 * Date: 13-12-20
 * Time: 下午5:07
 * To change this template use File | Settings | File Templates.
 */
class Util_Category{
    private static $cacheFielName = 'category';
    private static $categoryTableName = 'dc_categorys';

    public static function getCategory($catid = 0){
        $categorys = self::getAllCategorys();
        return $categorys[$catid];
    }

    public static function getCategorysTree(){
        $categorys = self::getAllCategorys();
        $return = array();
        foreach($categorys as $value){
            if(empty($value['parentid'])){
                $value['children'] = array();
                $return[$value['catid']]= $value;
            } else {
                $return[$value['parentid']]['children'][] = $value ;
            }
        }
        return $return;
    }

    public static function getAllCategorys(){
        $categorys = readCache(self::$cacheFielName);
        if(empty($categorys)){
            $db = Core_Db::getInstance();
            $data = $db->select("SELECT * FROM ".self::$categoryTableName." WHERE 1");
            $categorys = array();
            foreach($data as $value){
                $categorys[$value['catid']] = $value;
            }
            writeCache(self::$cacheFielName, $categorys);
        }
        return $categorys;
    }

    public static function getChildren($catid){
        $categoryTree = self::getCategorysTree();
        return $categoryTree[$catid]['children'];
    }

}