<?php
class Module_Fanyi extends Module_Base{
    public function response($content = ''){
        return Util_OpenApi::ask('翻译 '.$content);
    }
}