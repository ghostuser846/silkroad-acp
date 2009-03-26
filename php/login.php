<?php
    require("./smarty_silkroadacp.php");
    $smarty = new SmartySilkRoadACP();
    $smarty->assign("css_files", array("global.css"));
    $smarty->display("header.tpl");
    $smarty->display("body.tpl");
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

