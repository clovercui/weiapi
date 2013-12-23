<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shanhuhai
 * Date: 13-12-6
 * Time: 下午4:48
 * To change this template use File | Settings | File Templates.
 */
class Dispatcher{
    private $keyword = '';
    private $module;
    private $param = '';
    private $specialKeywords = array();

    private static $moduleIns = array();

    public function __construct($keyword = ''){
        $this->keyword = trim($keyword);
        $this->specialKeywords = include(ROOT_PATH . 'config/special_keywords.php');
    }

    private function analyze(){
        if($data = $this->analyzeSpecial()){
            returnJson(array('state'=>1, 'data'=>$data));
        };

        if($this->keyword == '') {
            $this->module = 'default';
            return;
        }

        $tags = $this->specialParse();
        if(empty($tags)){
            $tags = $this->getTags($this->keyword);
        }

        $mayFuncs = explode(',', $tags);
        foreach($mayFuncs as $key => $value){
            $pinyin = '';
            if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $value)){
                $pinyin = pinyin($value);
            }
            if(file_exists(LIB_PATH.'module/'.$pinyin.'.php')){
                $this->module = $pinyin;
                unset($mayFuncs[$key]);
                $mayFuncs = array_values($mayFuncs);
                $this->param = $mayFuncs;
                break;
            }
        }



        /* 旧的解析方法
        preg_match('/([^\s\:#@]+)[\s\:#@]*(.+)?/', $this->keyword, $matches);
        $paramNum = count($matches);
        if($paramNum >=2 ){
            $this->module = pinyin($matches[1]);
            if($paramNum >=3){
                $this->param = $matches[2];
            }
        } else {
            $this->module = 'default';
        }*/

    }

    private function analyzeSpecial(){
        if(array_key_exists($this->keyword,$this->specialKeywords)){
            return $this->specialKeywords[$this->keyword];
        }
        return false;
    }

    public function run(){
        $this->analyze();
        $className = 'Module_'.ucfirst($this->module);
        try{
            $instance = new $className();
            $result = $instance->reply($this->param);
            $data = array(
                'state'=>1,
                'data'=> $result
            );
        } catch (Exception $e){
            $error = $e->getMessage();
            $data = array(
                'state'=>0,
                'error'=>$error
            );
        }
        returnJson($data);
    }

    private function getTags($title, $num = 10){
        $pscwsPath = LIB_PATH.'vendor/Pscws/';
        require_once($pscwsPath.'Pscws4.class.php');
        $pscws = new PSCWS4();
        $pscws->set_dict($pscwsPath . 'etc/dict.utf8.xdb');
        $pscws->set_rule($pscwsPath . 'etc/rules.utf8.ini');
        $pscws->set_ignore(true);
        $pscws->send_text($title);
        $words = $pscws->get_tops($num);
        $pscws->close();
        $tags = array();
        foreach ($words as $val) {
            $tags[] = $val['word'];
        }
        return implode(',', $tags);
    }

    private function specialParse(){
        $keyword = $this->keyword;
        if(preg_match('/(音乐|听歌)/', $keyword)){
            $keyword = str_replace(array('听歌', '音乐'), array('',''), $keyword);
            $keyword = trim($keyword);
            $keyword = preg_split('/#|@|\*|\s|+/', $keyword);
            array_unshift($keyword, '听歌');
            $keyword = implode(',', $keyword);
            return $keyword;
        }
        if(preg_match('/(火车)[\sa-zA-Z0-9]{2,6}/', $keyword, $matches)){
            return $this->str2param($matches[1], $keyword);
        }
        if(preg_match('/(域名|计算|手机|翻译|彩票|藏头诗|朗读)/', $keyword, $matches)){
            return $this->str2param($matches[1], $keyword);
        }
        return false;

    }


    private function str2param($func, $keyword){
        $keyword = str_replace($func, '', $keyword);
        $keyword = trim($keyword);
        $keyword = implode(',', array($func,$keyword));
        return $keyword;
    }
}