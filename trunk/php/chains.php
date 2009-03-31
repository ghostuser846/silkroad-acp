<?php
    require_once("./choose_db.php");
    require_once("./smarty_silkroadacp.php");
    $smarty = new SmartySilkRoadACP();
    $smarty->assign("app_page_name", "Chains");
    $smarty->assign("js_scripts", array("jquery-1.3.2.min.js", "jquery-ui-1.7.1.custom.min.js", "global.js", "init_and_events.js", "events_handlers.js"));
    $smarty->assign("css_files", array("jquery-ui-1.7.1.custom.css", "chains.css", "global.css"));
    $smarty->display("header.tpl");
    $smarty->assign("sr_login", $_SESSION["silkroad_login"]);
    $smarty->assign("sr_host", $_SESSION["silkroad_host"]);
    $smarty->assign("bodies", array("main_menu", "empty_space_with_page_name", "chains"));
    $smarty->display("body.tpl");
    $smarty->display("footer.tpl");
?>

