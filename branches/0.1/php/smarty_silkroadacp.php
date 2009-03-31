<?php
    require("./Smarty-2.6.22/libs/Smarty.class.php");
    class SmartySilkRoadACP extends Smarty {
        function SmartySilkRoadACP() {
            $this->Smarty();
            $this->template_dir = "./templates/templates";
            $this->compile_dir = "./templates/templates_c";
            $this->config_dir = "./templates/configs";
            $this->cache_dir = "./templates/cache";
            //$this->caching = true;
            $this->assign("app_name", "SilkRoad ACP");
        }
    }
?>

