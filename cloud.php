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

$catid = isset($_REQUEST['catid']) ? intval($_REQUEST['catid']) : 4;
$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
$pageSize = isset($_REQUEST['pagesize']) ? intval($_REQUEST['pagesize']) : 1;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : 'desc';

if($catid<0){
    $data = array('state'=>0, 'msg'=>'valid catid');
    returnJson($data);
}

$db = Core_Db::getInstance();

$cateInfo = Util_Category::getCategory($catid);
if(empty($cateInfo['parentid'])){
    $result = $db->page("SELECT * FROM `dc_categorys` WHERE `catid`=:catid ORDER BY `catid` $order", $page, $pageSize, array('catid'=>$catid));
} else {
    $result = Util_Category::getChildren($catid);
}
$data = array('state'=>1, 'data'=> $result);
returnJson($data);