<?php
require('../common.php');

$busiName = trim($argv[1]); //业务名称
if(empty($busiName) || !Util_Valid::isChinese($busiName)){
    echo "bussiness name is invalid.\n";
    exit;
}
$busiAlias = pinyin($busiName);
$categorys = $argv[2]; //栏目
if(empty($categorys)){
    echo "Categorys is invalid,please divide category name by \"|\" ";
    exit;
}
$source = isset($argv[3]) ? $argv[3] : ''; //来源站

$debugMode = empty($argv[4]) ? 0 : 1;
if($debugMode){
    $dbConfig = require_once(ROOT_PATH.'config/online.db.php');
} else {
    $dbConfig = require_once(ROOT_PATH.'config/local.db.php');
}
$link = connectMysql($dbConfig);

$sql = "CREATE TABLE IF NOT EXISTS `datacloud`.`dc_{$busiAlias}` (
	`id` mediumint UNSIGNED AUTO_INCREMENT,
	`catid` mediumint UNSIGNED,
	`title` varchar(100) COMMENT '标题',
	`tags` varchar(30) COMMENT '标签，用逗号分割',
	`content` text COMMENT '内容',
	`content_source` text COMMENT '未经处理的内容',
	`thumb` varchar(100) COMMENT '缩略图',
	`thumb_source` varchar(100) COMMENT '缩略图源地址',
	`from` varchar(10) COMMENT '来源',
	`created` int(10) UNSIGNED,
	PRIMARY KEY (`id`),
	INDEX `title` (catid, `title`, `from`)
);";
mysql_query($sql, $link)
    or die("$sql execute failed:".mysql_error($link));

$findSql = "SELECT `name` FROM `dc_categorys` WHERE name='$busiName'";
$query = mysql_query($findSql, $link)
    or die("$findSql execute failed:".mysql_error($link));
$result = mysql_fetch_assoc($query);
if(!empty($result)){
    die('busiName exists');
}

$parentSql = "INSERT INTO `dc_categorys`(`name`, `alias`, `parentid`) VALUES('$busiName', '$busiAlias',0)";
mysql_query($parentSql, $link)
    or die("$parentSql execute failed:".mysql_error($link));

$lastId = mysql_insert_id($link);
$categorys = explode('|', $categorys);
$tmp = array();
$tmp2 = array();
foreach($categorys as $c){
    if(empty($c)) {
        continue;
    }
    $pinyin = pinyin($c);
    $tmp = "('$c', '$pinyin', $lastId)";

    $sql = "INSERT INTO `dc_categorys`(`name`, `alias`, `parentid`) VALUES".$tmp;
    mysql_query($sql, $link)
        or die("$sql execute failed:".mysql_error($link));
    $thisId = mysql_insert_id($link);
    $tmp2[] = $c.'|'.$thisId.'|||';
}

mysql_close($link);
