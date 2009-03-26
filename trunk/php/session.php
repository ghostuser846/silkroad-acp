<?php
    class SilkRoadSession {
        public static function checkSessionVars() {
            if (!isset($_SESSION["silkroad_host"]) || !isset($_SESSION["silkroad_login"]) || !isset($_SESSION["silkroad_pass"])) {
                return false;
            }
            return true;
        }

        public static function outputErrorMessage() {
            echo "<div>Incorrect host/login/pass. <a href=\"./login.php\">Try again</a></div>";
        }
    }
?>

