<?php
    session_start();
    require_once("./session.php");
    if (!SilkRoadSession::checkSessionVars()) {
        SilkRoadSession::outputErrorMessage();
    } else {
        require_once("./smarty_silkroadacp.php");
        $smarty = new SmartySilkRoadACP();
        $smarty->assign("app_page_name", "Chains");
        $smarty->assign("js_scripts", array("jquery-1.3.2.min.js", "jquery-ui-1.7.1.custom.min.js", "events_handlers.js", "init_and_events.js", "global.js"));
        $smarty->assign("css_files", array("chains.css", "global.css"));
        $smarty->display("header.tpl");
        $smarty->assign("bodies", array("main_menu", "empty_space_with_page_name", "chains"));
        $smarty->display("body.tpl");
        $smarty->display("footer.tpl");
    }
?>

