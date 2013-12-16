<?php
class Module_Tingge extends Module_Base{
    public function response($content = ''){
        return $this->xiamiApi($content);

    }

    private function xiamiApi($content){
        $song_info = file_get_contents('http://nnlife.duapp.com/xiami.php?key='.urlencode($content));
        $song = json_decode($song_info, true);
        return $song['song']['song_location'];
    }

    private function baiduApi($content){
        $tmp = explode(' ', $content);
        $song = $tmp[0];
        if(count($tmp)){
            $paramStr = urlencode($song).'$$';
        } else {
            $singer = $tmp[1];
            $paramStr = urlencode($song).'$$'.urlencode($singer).'$$$$';
        }

        $xml = file_get_contents('http://box.zhangmen.baidu.com/x?op=12&count=1&title='.$paramStr);
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