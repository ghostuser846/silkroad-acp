<?php
    if (isset($_REQUEST[session_name()])) session_start();
    if (isset($_SESSION["silkroad_host"]) && isset($_SESSION["silkroad_login"]) && isset($_SESSION["silkroad_pass"])) {
        Header("Location: chains.php");
        exit;
    }
    require("./smarty_silkroadacp.php");
    $smarty = new SmartySilkRoadACP();
    $smarty->assign("css_files", array("global.css"));
    $smarty->display("header.tpl");
    $smarty->display("body.tpl");
    if (isset($_GET["action"])) {
        if ($_GET["action"] == "pls_choose")
            echo "<center><div style=\"color: red;\">In order to use SilkRoadACP You should specify following params:</div></center>";
        if ($_GET["action"] == "logged_out")
            echo "<center><div style=\"color: blue;\">You were successfully logged out.</div></center>";
    }
    echo "
        <center>
            <div class=\"login_page_form\">
                <form action=\"./choose_db.php\" method=\"POST\" name=\"choose_db\">
                    <table>
                        <tr>
                            <td>Host</td>
                            <td><input type=\"text\" name=\"silkroad_host\"/></td>
                        </tr>
                        <tr>
                            <td>Login</td>
                            <td><input type=\"text\" name=\"silkroad_login\" /></td>
                        </tr>
                        <tr>
                            <td>Pass</td>
                            <td><input type=\"password\" name=\"silkroad_pass\" /></td>
                        </tr>
                    </table>
                    <input type=\"submit\" value=\"Proceed\" />
                </form>
            </div>
        </center>";
    $smarty->display("footer.tpl");
?>

