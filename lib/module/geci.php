<?php
class Module_Geci extends Module_Base{
    public function response($content = ''){
        return Util_OpenApi::ask('歌词'.$content);
    }
}