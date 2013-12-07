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