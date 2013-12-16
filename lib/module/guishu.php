<?php
class Module_Guishu extends Module_Base{
    public function response($content = array()){
        return Util_OpenApi::ask('归属'.$content);
    }
}