<?php
    session_start();
    if (!isset($_SESSION["silkroad_host"]) || !isset($_SESSION["silkroad_login"]) || !isset($_SESSION["silkroad_pass"]))
        header("Location: ../index.html");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <title>SilkRoad ACP</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript" src="../js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="../js/jquery-ui-1.7.1.custom.min.js"></script>
        <script type="text/javascript" src="../js/events_handlers.js"></script>
        <script type="text/javascript" src="../js/init_and_events.js"></script>
        <style type="text/css">
            body {
                background-color: grey;
            }
            #chains_container {
                border: 4px inset black;
                width: 75%;
                position: relative;
                background-color: white;
                left: 12.5%;
            }
            .chain_container {
                border: 2px solid blue;
                text-align: center;
                background-color: white;
                cursor: move;
            }
            .message_container {
                border: 3px double red;
                text-align: center;
                background-color: white;
            }
            #form_container {
                border: 4px solid red;
                width: 75%;
                position: relative;
                left: 12.5%;
                text-align: center;
                background-color: white;
            }
            .button {
                float: left;
                margin: 2px;
                padding: 2px;
                border: 3px solid black;
                cursor: pointer;
                background-color: white;
            }
            .button_action {
                float: left;
                margin: 2px;
                padding: 2px;
                border: 3px solid black;
                cursor: pointer;
                background-color: blue;
            }
            .clear {
                clear: both;
            }
            .td_delete_chain {
                color: red;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <?php
            session_start();
            echo "<div>" . $_SESSION["silkroad_login"] . "@" . $_SESSION["silkroad_host"] . "</div>"
        ?>
        <div id="form_container">
            <div id="button_get" class="button">Get 'Em!!</div>
            <div id="button_add" class="button">Add new Chain</div>
            <div id="button_add_ok" class="button_action">OK</div>
            <div id="button_add_cancel" class="button_action">Cancel</div>
            <div class="button">Test</div>
            <div class="clear"></div>
            <div id="form_add">
                Machine
                <select id="add_form_machine" class="form_add_select"></select>
                Testplan
                <select id="add_form_testplan" class="form_add_select"></select>
                Config
                <select id="add_form_config" class="form_add_select"></select>
                IsCompleted
                <select id="add_form_iscompleted" class="form_add_select"></select>
            </div>
        </div>
        <div id="chains_container">
            <div class="message_container">Nothing to show</div>
        </div>
    </body>
</html>

