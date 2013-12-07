<?php
class Module_Tianqi extends Module_Base{
    public function response($content = ''){
        return Util_OpenApi::ask('天气'.$content);
    }
}