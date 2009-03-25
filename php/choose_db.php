<?php
    foreach ($_POST as $key => $value)
        $$key = $value;
    $link = mysql_connect($silkroad_host, $silkroad_login, $silkroad_pass) or header("Location: chains.php");
    if (mysql_select_db("silkroad", $link)) {
        mysql_close($link);
    } else {
        mysql_close($link);
        header("Location: chains.php");
    }
    session_start();
    $_SESSION["silkroad_host"] = $silkroad_host;
    $_SESSION["silkroad_login"] = $silkroad_login;
    $_SESSION["silkroad_pass"] = $silkroad_pass;
    header("Location: chains.php");
?>

