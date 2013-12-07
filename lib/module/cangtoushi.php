<?php
class Module_Cangtoushi extends Module_Base{

    public function response($content = ''){
        return Util_OpenApi::ask('藏头诗'.$content);
    }

}