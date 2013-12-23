<?php

require('../common.php');

$configFile = $argv[1];
if(!file_exists($configFile)){
    echo "$configFile is not exist.\n";
    exit;
}
$taskConfig = yaml_parse_file($configFile);
//var_dump($taskConfig);exit;
/*通过当前栏目名称来获取catid start*/
$tableName = 'dc_'.$taskConfig['busiName'];

$link = connectMysql($dbConfig);
foreach($taskConfig['lists'] as & $val){
    list($urlReg, $firstUrl, $end, $orginCatname, $catname) = explode('|', $val);
    $sql = "SELECT * FROM dc_categorys WHERE name='$catname'";
    $result = mysql_query($sql, $link) or logInfo($sql.':'.mysql_error(), 'DB_ERROR');
    $data = mysql_fetch_assoc($result);
    $val .= '|'.$data['catid'];
}
/*end*/

$taskName = $taskConfig['taskName'];
$domain = $taskConfig['domain'];
$charset = $taskConfig['charset'];
$fileDomain = $taskConfig['fileDomain'];
$listNum = count($taskConfig['lists']);
$fields = $taskConfig['fields'];
$parseCss = $taskConfig['parseCss'];
$listType = $taskConfig['listType'];
$dislocation = $taskConfig['dislocation'];
$fields = explode(',', $fields);

if(is_dir(ROOT_PATH.'tasks/'.$taskName)){
    echo "task exists\n";
    exit;
}
@mkdir(ROOT_PATH.'tasks/'.$taskName, 0775);
@mkdir(ROOT_PATH.'tasks/'.$taskName.'/cache', 0775);
@mkdir(ROOT_PATH.'tasks/'.$taskName.'/static', 0755);
@mkdir(ROOT_PATH.'tasks/'.$taskName.'/lib', 0755);


$content = file_get_contents('./tpl/taskPage.tpl');
$callbackFuncStr = '';
foreach($fields as $f){
    if(in_array($f,array('thumb','source', 'catid', 'from', 'created'))){
        continue;
    }
    $callbackFuncStr .= '
    protected function get_'.$f.'($dom, $data){
        $return = $dom->find(\'\', 0)->innertext();'.
        ($f == 'content' ? '
        $return = saveContentPic($return);
        ' : '
        ')
        .'$return = trim($return);
        return $return;
    }
    ';
}
$content = preg_replace('/<\{callbackFuncs\}>/iU', $callbackFuncStr, $content);
$content = preg_replace('/<\{tableName\}>/iU', $tableName, $content);
file_put_contents( ROOT_PATH.'tasks/'.$taskName.'/lib/taskpage.php', $content);

copy('./tpl/taskList.tpl', ROOT_PATH.'tasks/'.$taskName.'/lib/tasklist.php');

$content = file_get_contents('./tpl/config.tpl');
$content = preg_replace('/<\{taskName\}>/iU', $taskName, $content );
$content = preg_replace('/<\{rootPath\}>/iU', ROOT_PATH, $content );
$content = preg_replace('/<\{domain\}>/iU', $domain, $content );
$content = preg_replace('/<\{charset\}>/iU', $charset, $content );
$content = preg_replace('/<\{fileDomain\}>/iU', $fileDomain, $content );
$tmp = array();
foreach($fields as $f){
    $tmp[$f] = '';
}
$fields = $tmp;
$listConfigs = array();
if(!empty($taskConfig)){
    foreach($taskConfig['lists'] as $c){
        list($urlReg, $firstUrl, $end, $originCatename, $catname, $catid) = explode('|', $c);
        $listConfigs[] = array(
            'catid'=>$catid,
            'urlReg'=>$urlReg,
            'firstUrl'=>$firstUrl,
            'start'=>'1',
            'end'=>$end,
            'updateEnd'=>'0',
            'parseCss'=>$parseCss,
            'listType'=>$listType,
            'dislocation'=> $dislocation ,
            'domain'=>'',
            'fields'=>$fields,
        );
    }

} else{
    $listConfigs = array_fill(0, $listNum, array(
        'catid'=>1,
        'urlReg'=>'{\d}',
        'firstUrl'=>'',
        'start'=>'1',
        'end'=>'0',
        'updateEnd'=>'0',
        'parseCss'=>'',
        'listType'=>'html',
        'dislocation'=> 0 ,
        'domain'=>'',
        'fields'=>$fields,
    ));
}
$content = preg_replace('/<\{listConfigs\}>/iU', var_export($listConfigs, true), $content );

file_put_contents(ROOT_PATH.'tasks/'.$taskName.'/config.php', $content);

copy('./tpl/fetchList.tpl', ROOT_PATH.'tasks/'.$taskName.'/fetchList.php');
copy('./tpl/fetchPage.tpl', ROOT_PATH.'tasks/'.$taskName.'/fetchPage.php');


copy('./tpl/update.tpl', ROOT_PATH.'tasks/'.$taskName.'/update.php');
touch(ROOT_PATH.'tasks/'.$taskName.'/publish.php');


