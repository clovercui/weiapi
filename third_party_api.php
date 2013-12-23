<?php
$keyword = '我爱听歌ee ';
$xml = '';
$dict = array('听歌', '翻译', '天气', '快递', '火车');

$hit = false;
foreach($dict as $value){
    if(strpos($keyword, $value) !== false){
        $hit = true;
        break;
    }
}

if($hit){
    $url = 'http://api.weijuju.com/api/0612263792C1226480612ed9e-3';
	$data = $xml;
	curl_post($url, $data);
}

function curl_post($url , $data){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
