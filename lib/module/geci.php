<?php
class Module_Geci extends Module_Base{
    public function response($content = ''){
        $tmp = explode(' ', $content);
        $song = $tmp[0];
        if(count($tmp)){
            $paramStr = urlencode($song);
        } else {
            $singer = $tmp[1];
            $paramStr = urlencode($song).'/'.urlencode($singer);
        }
        $url = 'http://geci.me/api/lyric/'.$paramStr;
        $response = curl_get_content($url);
        $response = json_decode($response, true);

        $lrcUrl = $response['result'][0]['lrc'];
        $lrcContent = curl_get_content($lrcUrl);
        return $lrcContent;
    }
}