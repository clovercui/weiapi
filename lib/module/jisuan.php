<?php
class Module_Jisuan extends Module_Base{
    public function response($content = ''){
        if(!preg_match('/[0-9\+\-\*%\/]+/', $content)){
            return '非法的表达式';
        }
        $string =  'return '.$content.';';
        $result = eval($string);
        return $result;

    }
}