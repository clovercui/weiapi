<?php
abstract class Module_Base {
    protected $module ;
    protected $param;
    protected $link;

    private $cacheModules = array(
        'cangtoushi',
        'fanyi',
        'geci',
        'xiaohua',
        'tingge',
        'guishudi'
    );

    public function __construct(){
        $className = get_class($this);
        $this->module = strtolower(array_pop(explode('_', $className)));
    }

    public function reply($param){
        $this->param = $param;
        if(in_array($this->module, $this->cacheModules)){
            $response = $this->readCache();
            if(empty($response)){
                $response = $this->response($param);
                if(!empty($response)){
                    $this->writeCache($response);
                }
            }
        } else {
            $response = $this->response($param);
        }

        $response = str_replace('{br}', "\n", $response);
        return $response;
    }

    abstract public function response($content);

    protected function readCache(){
        $this->connectDb();
        $sql = "SELECT * FROM w_data WHERE module='{$this->module}' AND param='{$this->param}'";
        $result = mysql_query($sql, $this->link);
        $result = mysql_fetch_assoc($result);
        if(!empty($result)) {
            return $result['response'];
        } else {
            return false;
        }
    }

    protected function writeCache($response){
        $this->connectDb();
        $data['module'] = $this->module;
        $data['param'] = $this->param;
        $data['response'] = $response;

        $fields = implode(',', array_keys($data));
        foreach($data as &$value){
            $value = "'".addslashes($value)."'";
        }
        $values = implode(',', $data);
        $sql = "INSERT INTO w_data ($fields) VALUES($values)";

        $result = mysql_query($sql, $this->link);
        return $result;
    }

    protected function connectDb(){
        if(is_null($this->link)) {
            $dbConfig = require(ROOT_PATH.'config/db.php');
            $this->link =  connectMysql($dbConfig);
        }
    }

}