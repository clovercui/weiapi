<?php
$keyword = '我爱听歌ee ';
$xml =file_get_contents('aa.log');
 
$dict = array('听歌', '翻译', '天气', '快递', '火车');

$hit = false;
foreach($dict as $value){
    if(strpos($keyword, $value) !== false){
        $hit = true;
        break;
    }
}

if($hit){
	$url = "http://g.dev/test/curl_post_receive.php";
	$data = $xml;
echo 	curl_post($url, $data);
}
function curl_post($url , $data){
    $ch = curl_init();
//$header[] = "Content-type: text/xml";//定义content-type为xml
    curl_setopt($ch, CURLOPT_URL, $url);
// curl_setopt($ch, CURLOPT_HEADER, 0); //定义是否显示状态头 1：显示 ； 0：不显示
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//定义请求类型
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 //   curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
