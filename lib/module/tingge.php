<?php
class Module_Tingge extends Module_Base{
    public function response($content = ''){

        $tmp = explode(' ', $content);
        $song = $tmp[0];
        $singer = $tmp[1];
        $xml = file_get_contents('http://box.zhangmen.baidu.com/x?op=12&count=1&title='.urlencode($song).'$$'.urlencode($singer).'$$$$');
        $xml = new SimpleXMLElement($xml);
        $encodeUrl =  $xml->xpath('/result/url/encode');
        $decodeUrl = $xml->xpath('/result/url/decode');
        $return = '';
        if(!empty($encodeUrl)) {
            $encodeUrl = $encodeUrl[0];
            $decodeUrl = $decodeUrl[0];

            $tmp = explode('/', $encodeUrl);
            array_pop($tmp);
            array_push($tmp, $decodeUrl);
            $return = implode('/', $tmp);
        }
        return $return;
    }

    private function html($mp3Url){
        return '<video src="'.$mp3Url.'" controls="controls"></video>';
    }
}