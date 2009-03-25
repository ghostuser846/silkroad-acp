<?php
    session_start();
    require("./smarty_silkroadacp.php");    
    if (!isset($_SESSION["silkroad_host"]) || !isset($_SESSION["silkroad_login"]) || !isset($_SESSION["silkroad_pass"]))
        header("Location: ../index.html");
    $smarty = new SmartySilkRoadACP();
    $smarty->assign("app_page_name", "Chains");
    $smarty->assign("js_scripts", array("jquery-1.3.2.min.js", "jquery-ui-1.7.1.custom.min.js", "events_handlers.js", "init_and_events.js"));
    $smarty->assign("css_files", array("chains.css"));
    $smarty->display("header.tpl");
    $smarty->assign("bodies", array("chains"));
    $smarty->display("body.tpl");
    /*session_start();
    echo "<div>" . $_SESSION["silkroad_login"] . "@" . $_SESSION["silkroad_host"] . "</div>"*/
?>

