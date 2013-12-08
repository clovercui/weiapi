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
        $this->keyword = $keyword;
        $this->specialKeywords = include(ROOT_PATH.'config/special_keywords.php');
    }

    private function analyze(){
        if($data = $this->analyzeSpecial()){
            returnJson($data);
        };

        if($this->keyword == '') {
            $this->module = 'default';
            return;
        }

        preg_match('/([^\s\:#@]+)[\s\:#@]*(.+)?/', $this->keyword, $matches);
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

    public function reply($keyword){
        $this->keyword = $keyword;
        $this->analyze();

        $className = 'Module_'.ucfirst($this->module);

        $instance = null;
        if(array_key_exists($this->module, self::$moduleIns)){
            $instance = self::$moduleIns[$this->module];
        } else {
            $instance = new $className();
            self::$moduleIns[$this->module] = $instance;
        }

        return $instance->reply($this->param);
    }
}