<?php
class Module_Geci extends Module_Base{
    public function response($content = ''){
        $content = str_replace(array(' ',' '), array('-','-'), $content);
        return Util_OpenApi::ask('歌词 '.$content);
    }
}