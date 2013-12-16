<?php
class Module_Xiaohua extends Module_Base{
    public function response($content = array()){
        return Util_OpenApi::ask('笑话 ');
    }

    public function readCache(){
        if(LOCAL_DATA_MODE){
            $this->connectDb();
            $sql = "SELECT * FROM w_data WHERE module='{$this->module}' ORDER BY rand() LIMIT 1";
            $result = mysql_query($sql, $this->link);
            $result = mysql_fetch_assoc($result);
            if(!empty($result)) {
                return $result['response'];
            } else {
                return false;
            }
        }
        return false;
    }

    public function writeCache($response){
        $this->connectDb();
        $data['module'] = $this->module;
        $data['param'] = md5($response);
        $data['response'] = $response;

        $fields = implode(',', array_keys($data));
        foreach($data as &$value){
            $value = "'".addslashes($value)."'";
        }
        $values = implode(',', $data);
        $sql = "INSERT INTO w_data ($fields) VALUES($values) ON DUPLICATE KEY UPDATE `response` = VALUES(`response`)";

        $result = mysql_query($sql, $this->link) or die(mysql_error());
        return $result;
    }
}