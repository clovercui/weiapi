<?php
class Module_Xiaohua extends Module_Base{
    public function response($content = ''){
        return Util_OpenApi::ask('笑话 '.$content);
    }
}