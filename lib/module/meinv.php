<?php
class Module_Meinv extends Module_Base{
    public function response($content = array()){
        $content = '';
        if(!empty($content)){
            $content = $content[0];
        }
        return $content;
    }
}