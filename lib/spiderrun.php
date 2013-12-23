<?php
class SpiderRun{
    protected $taskConfig;

    static private $listIns;
    static private $pageIns;

    static private $listConfigs;

    static public function getLists(array $listConfigs){
        self::$listConfigs = $listConfigs;
        self::$listIns = new TaskList();
        foreach(self::$listConfigs as $config){
            self::$listIns->setConfig($config);
            self::$listIns->getList();
        }
        echo "lists done.\n";
    }

    static public function readLists(array $listConfigs){
        self::$listConfigs = $listConfigs;
        self::$pageIns = new TaskPage();
        foreach(self::$listConfigs as $config){
            self::$pageIns->setConfig($config);
            self::$pageIns->readList();
        }
        echo "pages done\n";
    }
}
