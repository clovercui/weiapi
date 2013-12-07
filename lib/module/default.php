<?php
class Module_Default extends Module_Base{
    public function response($content = ''){
        return Util_OpenApi::ask('随便聊聊');
    }
}