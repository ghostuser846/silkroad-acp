<?php
    foreach ($_POST as $key => $value)
        $$key = $value;
    $link = mysql_connect($silkroad_host, $silkroad_login, $silkroad_pass);
    if ($link != false) {
        if (mysql_select_db("silkroad", $link)) {
            mysql_close($link);
            session_start();
            $_SESSION["silkroad_host"] = $silkroad_host;
            $_SESSION["silkroad_login"] = $silkroad_login;
            $_SESSION["silkroad_pass"] = $silkroad_pass;
            header("Location: chains.php");        
        } else {
            mysql_close($link);
            echo "<div>Incorrect host/login/pass. <a href=\"./login.php\">Try again</a></div>";
        }
    } else {
        echo "<div>Incorrect host/login/pass. <a href=\"./login.php\">Try again</a></div>";
    }
?>

