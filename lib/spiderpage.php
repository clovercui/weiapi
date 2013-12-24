<?php
abstract class SpiderPage{
    protected $taskConfig;

    private $catid;
    private $fields;

    /*
     *
     */
    public function __construct( array $config = array()){
        if(!empty($config)){
            $this->setConfig($config);
        }
    }

    public function setConfig(array $config = array()){
        $this->catid = $config['catid'];
        $this->fields = $config['fields'];
        $this->domain = isset($config['domain']) && !empty($config['domain'])
            ? $config['domain'] : $this->taskConfig['domain'];
    }

    public function readList(){
        $processFile = 'ios.process';
        $processFilePath = CACHE_PATH.'taskdata/'.TASK_NAME.'/';
        $processData = readCache($processFile, $processFilePath);
        if(empty($processData)){
            $processData = array(
                'current'=>0,
                'url'=>'',
                'filename'=>''
            );
            writeCache($processFile, $processData, $processFilePath);
        }
        $current = $processData['current'];

        $listName = 'list_' . $this->catid . '.php';
        $urls = readCache($listName, CACHE_PATH.'taskdata/'.TASK_NAME.'/');

        $end = count($urls)-1;

        while($current<=$end) {
            $tmp = explode('|', $urls[$current]);
            $nowUrl = $tmp[0];
            $thumb = $tmp[1];
            if(strpos($nowUrl, 'http://') === false) {
                $nowUrl =  $this->domain.$nowUrl;
            }

            $fileName = 'con_' . url2fileName( $nowUrl ) . '.html';

            $processData['current'] = $current;
            $processData['url'] = $nowUrl;
            $processData['filename'] = $fileName;

            writeCache($processFile, $processData, $processFilePath);

            $content = fetchUrl($nowUrl, ROOT_PATH.'cache/html/'.TASK_NAME.'/'.$fileName,
                $this->taskConfig['charset'], $this->taskConfig['domain'],
                $this->taskConfig['cachePage']);

            require_once(ROOT_PATH.'lib/vendor/simple_html_dom.php');
            $dom = str_get_html($content);

            $data = array();

            $exist = $this->checkExists($dom, $content);
            if($exist){
                $current++;
                continue;
            }
            foreach($this->fields as $key => $value){
                if(in_array($key,
                    array('thumb','source','catid','from', 'created'))){
                    continue;
                }
                $callBack = 'get_'.$key;
                $data[$key] = $value !== ''
                    ? $value
                    : $this->$callBack($dom, $data, $content, $nowUrl);
            }

            if(!empty($thumb)){
                $thumb = saveRemoteFile($thumb);
            }
            $data['thumb'] = $thumb;
            $data['source'] = $nowUrl;
            $data['catid'] = $this->catid;
            $data['from'] = TASK_NAME;
            $data['created'] = time();
            $this->saveData($data , $dom);
            if(DEBUG_MODE){
                echo "data:\n";
                var_dump($data);
                echo "insert error:\n";
                echo mysql_error()."\n";
                echo "page debug mode.\n";
                exit;
            }
            $current++;
        }
        echo("page " .$this->catid ." done.\n");

    }

    protected function checkExists($dom, $con){
        return false;
    }

    abstract protected function saveData($data, $dom);
}
