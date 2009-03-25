<?php
    function check_session_vars() {
        if (!isset($_SESSION["silkroad_host"]) || !isset($_SESSION["silkroad_login"]) || !isset($_SESSION["silkroad_pass"])) {
            return false;
        }
        return true;
    }
?>

