<?php
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


class Util_OpenApi{
    public static $url = 'http://api.ajaxsns.com/api.php?key=free&appid=0&msg=';

    public static function ask($keyword){
        $response = curl_get_content(self::$url.urldecode($keyword), 'http://www.baidu.com');
        return self::analyze($response);
    }

    public static function analyze($response){
        $response = json_decode($response, true);
        if($response){
            return $response['content'];
        } else {
            return '';
        }
    }
}
