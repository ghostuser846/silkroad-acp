<?php
    session_start();
    require("./session.php");
    if (!check_session_vars()) {
        echo "<div>Incorrect host/login/pass. <a href=\"./login.php\">Try again</a></div>";
    } else {
        require("./smarty_silkroadacp.php");
        $smarty = new SmartySilkRoadACP();
        $smarty->assign("app_page_name", "Chains");
        $smarty->assign("js_scripts", array("jquery-1.3.2.min.js", "jquery-ui-1.7.1.custom.min.js", "events_handlers.js", "init_and_events.js"));
        $smarty->assign("css_files", array("chains.css", "global.css"));
        $smarty->display("header.tpl");
        $smarty->assign("bodies", array("chains"));
        $smarty->display("body.tpl");
        $smarty->display("footer.tpl");
    }
    /*session_start();
    echo "<div>" . $_SESSION["silkroad_login"] . "@" . $_SESSION["silkroad_host"] . "</div>"*/
?>

