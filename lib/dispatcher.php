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

    private $specialKeywords = array(
        '帮助'=>'这是帮助文档',
        '尼玛'=>'去死',
    );

    public function __construct($keyword = ''){
        $this->keyword = $keyword;
        $this->analyze();
    }

    private function analyze(){
        if($this->keyword == '') {
            $this->module = 'default';
            return;
        }

        preg_match('/([^\s\:#@]+)[\s\:#@]*(.+)/', $this->keyword, $matches);
        $paramNum = count($matches);
        if($paramNum >=2 ){
            $this->module = pinyin($matches[1]);
            if($paramNum >=3){
                $this->param = $matches[2];
            }
        } else {
            $this->module = 'default';
        }

    }

    private function analyzeSpecial(){
        if(array_key_exists($this->keyword,$this->specialKeywords)){
            return $this->specialKeywords[$this->keyword];
        }
        return false;
    }

    public function run(){

        if($data = $this->analyzeSpecial()){
            returnJson($data);
        };

        $this->analyze();

        $data = array();
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
        return returnJson($data);
    }
}