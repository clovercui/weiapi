<?php
class TaskList extends SpiderList{
    public function __construct($config = array()){
        global $settings;
        $this->taskConfig = $settings;
        parent::__construct($config);
    }

    protected function handleJson($content){

    }

    protected function parseList($dom){

    }

    protected function parseThumb($a, $key, $dom){
        return '';
        /*
        $return =  $a->find('img', 0)->src;
        return $return;
        */
    }
}
