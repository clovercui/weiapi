<?php
class Module_Default extends Module_Base{
    public function response($content = ''){
        $content = $content[0];
        return Util_OpenApi::ask('随便聊聊');
    }
}