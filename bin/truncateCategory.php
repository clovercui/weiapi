<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shanhuhai
 * Date: 13-12-20
 * Time: 下午2:52
 * To change this template use File | Settings | File Templates.
 */

require('../common.php');
$dbConfig = require_once(ROOT_PATH . 'config/local.db.php');
$link = connectMysql($dbConfig);
$sql = "TRUNCATE TABLE `dc_categorys`";
mysql_query($sql, $link)
    or die("$sql execute failed:".mysql_error($link));
mysql_close($link);
