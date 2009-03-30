<?php
    if (isset($_POST["silkroad_host"]) && isset($_POST["silkroad_login"]) && isset($_POST["silkroad_pass"])) {
        $link = mysql_connect($_POST["silkroad_host"], $_POST["silkroad_login"], $_POST["silkroad_pass"]);
        if ($link != false) {
            if (mysql_select_db("silkroad", $link)) {
                session_start();
                $_SESSION["silkroad_host"] = $_POST["silkroad_host"];
                $_SESSION["silkroad_login"] = $_POST["silkroad_login"];
                $_SESSION["silkroad_pass"] = $_POST["silkroad_pass"];
                header("Location: chains.php");
                exit;
            } else {
                echo "<div>Incorrect host/login/pass. <a href=\"./login.php\">Try again</a></div>";
                exit;
            }
        } else {
            echo "<div>Incorrect host/login/pass. <a href=\"./login.php\">Try again</a></div>";
            exit;
        }
    }
    if (isset($_GET["action"]) && ($_GET["action"] == "logout")) {
        session_start();
        session_destroy();
        header("Location: login.php?action=logged_out");
        exit;
    }
    if (isset($_REQUEST[session_name()])) session_start();
    if (isset($_SESSION["silkroad_host"]) && isset($_SESSION["silkroad_login"]) && isset($_SESSION["silkroad_pass"])) return;
    else header("Location: login.php?action=pls_choose");
    exit;
?>

