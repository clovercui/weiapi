<?php
class Module_Fanyi extends Module_Base{
    public function response($content = ''){
        $url = "http://openapi.baidu.com/public/2.0/bmt/translate?client_id=kylV2rmog90fKNbMTuVsL934&q=" . $content . "&from=auto&to=auto";
        $json = curl_get_content($url);
        $json = json_decode($json, true);
        $str = $json['trans_result'][0]['dst'];

        return $str;
    }
}