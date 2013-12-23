<?php
function SimSimi($keyword)
{ //小黄鸡接口

    $url = "http://www.simsimi.com/";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($ch);
    list($header, $body) = explode("\r\n\r\n", $content);
    preg_match("/set\-cookie:([^\r\n]*);/iU", $header, $matches);
    $cookie = $matches[1];
    curl_close($ch);

//----------- 抓 取 回 复 ----------//
    $url = "http://www.simsimi.com/func/req?lc=ch&msg=$keyword";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_REFERER, "http://www.simsimi.com/talk.htm?lc=ch");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    $content = json_decode(curl_exec($ch), 1);
    curl_close($ch);

    if ($content['result'] == '100') {
        $content['response'];
        return $content['response'];
    } else {
        return '我还不会回答这个问题...';
    }
}

echo SimSimi('你好');