<?php
$data = file_get_contents('http://g.dev/weiapi/index.php?msg='.urlencode($_REQUEST['msg']));
$data = json_decode($data, true);

$mp3Url = $data['data'];

$html5 = <<<HTML
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<video src="$mp3Url" controls="controls" autoplay="true"></video>
</body>
</html>
HTML;

echo $html5;
