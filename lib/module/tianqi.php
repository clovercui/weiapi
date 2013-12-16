<?php
class Module_Tianqi extends Module_Base{
    public function response($content = array()){
        $content = $content[0];
        return Util_OpenApi::ask('天气'.$content);
    }
}