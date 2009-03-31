<?php
    require_once("./choose_db.php");
    require_once("./smarty_silkroadacp.php");
    $smarty = new SmartySilkRoadACP();
    $smarty->assign("app_page_name", "Executed Test Cases");
    $smarty->assign("js_scripts", array("jquery-1.3.2.min.js", "jquery-ui-1.7.1.custom.min.js", "global.js", "executed_tcs.js"));
    $smarty->assign("css_files", array("jquery-ui-1.7.1.custom.css", "executed_tcs.css", "global.css"));
    $smarty->display("header.tpl");
    $smarty->assign("sr_login", $_SESSION["silkroad_login"]);
    $smarty->assign("sr_host", $_SESSION["silkroad_host"]);
    $smarty->assign("bodies", array("main_menu", "empty_space_with_page_name", "executed_tcs"));
    $smarty->display("body.tpl");
    $smarty->display("footer.tpl");
?>

