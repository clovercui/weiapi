<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shanhuhai
 * Date: 13-12-6
 * Time: 上午12:58
 * To change this template use File | Settings | File Templates.
 */
require_once('component.php');
class Xiaohua extends Component{

    public function reply($content = ''){
        return '珊瑚海，何时苦尽甘来'.$content;
    }

}