<?php /* Smarty version 2.6.22, created on 2009-04-01 07:02:51
         compiled from body_executed_tcs.tpl */ ?>
<form>
    <div id="main_search_area" class="search_area">
        <div id="dates">
            From Date: <input id="date_from" type="text" size="10" readonly /> To Date: <input id="date_to" type="text" size="10" readonly />
        </div>
        <div id="statuses">
            <input id="checkbox_passed" name="checkbox_passed_name" value="checkbox_passed_name" type="checkbox" />Passed
            <input id="checkbox_failed" name="checkbox_failed_name" value="checkbox_failed_name" type="checkbox" checked="checked" />Failed
            <input id="checkbox_nc" name="checkbox_nc_name" value="checkbox_nc_name" type="checkbox" checked="checked" />NotCompleted
        </div>
        <div id="specify_testplans" title="You haven't chosen testplans yet.">
            Specify TestPlans ... (<span id="number_choosed">0</span>)
        </div>
        <div id="testrun">
            TestRun: <input id="testrun_textfield" name="testrun_textfield_name" type="text" size="10" maxlength="10" />
        </div>
        <div class="clear"></div>
        <div id="search_buttons">
            <div class="button_action" id="search_button_search">Search</div>
            <div class="button_action" id="search_button_clear">Clear</div>
            <div class="clear"></div>
        </div>
    </div>
</form>
<div id="testcases"></div>
<div id="dialog_choose_testplans">
    <select id="testplans_selector" multiple="multiple" />
</div>
