<?php
class Module_Yuming extends Module_Base{
    public function response($content = ''){
        return Util_OpenApi::ask('域名'.$content);
    }
}