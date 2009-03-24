<?php
    session_start();
    foreach ($_POST as $key => $value)
        $$key = $value;
    $_SESSION["silkroad_host"] = $silkroad_host;
    $_SESSION["silkroad_login"] = $silkroad_login;
    $_SESSION["silkroad_pass"] = $silkroad_pass;
    header("Location: chains.php");
?>

