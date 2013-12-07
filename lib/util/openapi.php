<?php



class Util_OpenApi{
    public static $url = 'http://api.ajaxsns.com/api.php?key=free&appid=0&msg=';

    public static function ask($keyword){
        $response = curl_get_content(self::$url.urldecode($keyword), 'http://api.ajaxsns.com/');
        //var_dump($keyword);exit;
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
