<?php /* Smarty version 2.6.22, created on 2009-04-15 06:58:16
         compiled from body_run_transporter.tpl */ ?>
<div>
    <table>
        <tr>
            <td>Source:</td>
            <td>Host</td>
            <td><input class="not_empty" id="source_hostname" name="source_hostname_name" type="text" size="16" maxlength="32" /></td>
            <td>User</td>
            <td><input class="not_empty" id="source_user" name="source_user_name" type="text" size="16" maxlength="32" /></td>
            <td>Password</td>
            <td><input id="source_password" name="source_password_name" type="password" size="16" maxlength="32" /></td>
            <td></td>
        </tr>
        <tr>
            <td>Destination:</td>
            <td>Host</td>
            <td><input class="not_empty" id="destination_hostname" name="destination_hostname_name" type="text" size="16" maxlength="32" value="psslab441" /></td>
            <td>User</td>
            <td><input class="not_empty" id="destination_user" name="destination_user_name" type="text" size="16" maxlength="32" value="root" /></td>
            <td>Password</td>
            <td><input id="destination_password" name="destination_password_name" type="password" size="16" maxlength="32" value="root" /></td>
            <td class="bad_connect" id="test_result">Not tested yet</td>
        </tr>
        <tr>
            <td></td>
            <td>Run:</td>
            <td><input disabled="disabled" id="run_to_transport" name="run_to_transport_name" type="text" size="12" maxlength="12" /></td>
            <td>Config</td>
            <td><select disabled="disabled" id="destination_config" name="destination_config_name" /></td>
            <td></td>
            <td></td>
            <td><div class="button_action" id="test_connections_button">Test connections</div></td>
            <td><div class="button_action" id="transport_button">Transport</div></td>
            <td><div class="button_action" id="clear_button">Clear</div></td>
        </tr>
    </table>
</div>
<div id="dialog_view_log">
</div>