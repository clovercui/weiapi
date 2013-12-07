<?php
class Module_Jisuan extends Module_Base{
    public function response($content = ''){
        return Util_OpenApi::ask('计算'.$content);
    }
}