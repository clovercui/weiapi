<?php

function pinyin($str, $charset = "utf-8", $ishead = 0){
    $restr = '';
    $str = trim($str);

    if ($charset == "utf-8") {
        $str = iconv("utf-8", "gb2312", $str);
    }

    $slen = strlen($str);

    $pinyins = array();
    if ($slen < 2) {
        return $str;
    }
    $fp = fopen(__DIR__.'/pinyin.dat', 'r');

    if(false == $fp) {
        exit('pinyin.dat open failed');
    }
    while (!feof($fp)) {
        $line = trim(fgets($fp));
        $pinyins[$line[0] . $line[1]] = substr($line, 3, strlen($line) - 3);
    }
    fclose($fp);

    for ($i = 0; $i < $slen; $i++) {
        if (ord($str[$i]) > 0x80) {
            $c = $str[$i] . $str[$i + 1];
            $i++;
            if (isset($pinyins[$c])) {
                if ($ishead == 0) {
                    $restr .= $pinyins[$c];
                } else {
                    $restr .= $pinyins[$c][0];
                }
            } else {
                $restr .= "_";
            }
        } else if (preg_match("/[a-z0-9]/i", $str[$i])) {
            $restr .= $str[$i];
        } else {
            $restr .= "_";
        }
    }
    return $restr;
}

function returnJson($data){
    header('Content-type:text/html;Charset=utf-8');
    echo isset($_GET['callback']) ? $_GET['callback'] . '(' . json_encode($data) . ')' : json_encode($data);
    exit;
}

function connectMysql($config){
    $link = mysql_connect($config['host'], $config['username'], $config['password'])
    or die('Could not connect: ' . mysql_error());
    mysql_query("set names utf8", $link);
    mysql_select_db($config['dbname'])
    or die('Could not select database'."\n");
    return $link;
}

function curl_get_content($url, $refer = ''){
    $ch = curl_init();
    $options = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_URL => $url,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_REFERER => $refer,
        CURLOPT_USERAGENT => "Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)"
    );
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    curl_close($ch);
    return $content;
}

function writeFile($file, $data){
    $result = @file_put_contents($file, $data);
    $result && chmod($file, 0755);
    return $result;
}

function writeCache($file, $array, $path = null){
    if(!is_array($array)) return false;
    $array = "<?php\nreturn ".var_export($array, true).";";
    $cachefile = ($path ? $path : CACHE_PATH).$file;
    $strlen = writeFile($cachefile, $array);
    return $strlen;
}

function readCache($file, $path = null){
    if(!$path) $path = CACHE_PATH;
    $cachefile = $path.$file;
    return @include $cachefile;
}

function config($configName, $key = null, $default = null) {
    $config = require_once(ROOT_PATH.'/config/'.$configName.'.php');
    return is_null($key) ? $config : (isset($config[$key]) ? $config[$key] : $default);
}

function url2fileName($url){
    $urlinfo =parse_url( $url);
    $urlinfo['path'] = str_replace(array('/','?'), array('_','_'), $urlinfo['path']);
    return implode('_', $urlinfo);
}