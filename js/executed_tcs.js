var ajax_xml = null;
var current_page = 1;

$(document).ready(function(e) {
    // Initialize DatePicker "From"
    $("#date_from").datepicker({
        showOn: 'button', 
        buttonImage: '../img/calendar/calendar.gif', 
        buttonImageOnly: true,
        dateFormat: "yy/mm/dd",
        onClose: function(date) {
                        compareDates(date, this);
                    }
    });
    // Initialize DatePicker "To"
    $("#date_to").datepicker({
        showOn: 'button', 
        buttonImage: '../img/calendar/calendar.gif', 
        buttonImageOnly: true,
        dateFormat: "yy/mm/dd",
        onClose: function(date) {
                        compareDates(date, this);
                    }
    });
    // Initialize dialog "Choose Testplans"
    $("#dialog_choose_testplans").dialog({
        bgiframe: true,
        modal: true,
        height: 300,
        width: 300,
        closeOnEscape: false,
        autoOpen: false,
        hide: "explode",
        show: "fold",
        title: "Choose TestPlans",
        buttons: {
            "OK": function() {
                var tip = "You have chosen following testplans: ";
                $("#specify_testplans input:hidden").remove();
                $("#dialog_choose_testplans select option:selected").each(function() {
                    // Save chosen plans into hidden inputs
                    $("#specify_testplans").append("<input class=\"hidden_id\" type=\"hidden\" value=\"" +
                        $(this).attr("value") + "\" />" +
                        "<input class=\"hidden_plan\" type=\"hidden\" value=\"" +
                        $(this).text() + "\" />");
                    tip += $(this).text() + ", ";
                });
                // Remove space and comma
                $("#specify_testplans").attr("title", tip.substring(0, tip.length - 2));
                $(this).dialog("close");
            },
            "Cancel": function() {
                $(this).dialog("close");
            }
        },
        close: function() {
            $("#number_choosed").text(getNumberOfChoosedTestPlans());            
        }
    });
    // Initialize dialog "View Logs"
    $("#dialog_view_logs").dialog({
        bgiframe: true,
        modal: true,
        height: 400,
        width: 600,
        closeOnEscape: true,
        autoOpen: false,
        hide: "explode",
        show: "fold",
        title: "View Logs",
        buttons: {
            "OK": function() {
                $(this).dialog("close");
            },
        }
    });
    // Initialize accordion for View Logs dialog
    $("#logs_accordion").accordion({autoHeight: false, clearStyle: true});

    $("#specify_testplans").bind("click", function(e) {
        $("#dialog_choose_testplans").dialog("open");
        $.post("../php/get_executed_tcs.php", {
            action: "get_plans"
            }, function(xml) {
                // Remove all options from dialog's select
                $("#dialog_choose_testplans select option").remove();
                $("TestPlan", xml).each(function(id) {
                    var element = $("TestPlan", xml).get(id);
                    // Insert options into dialog's select
                    $("#dialog_choose_testplans select").append("<option value=\"" + 
                        $("ID", element).text() + "\">" + $("Name", element).text() + "</option>");
                });
        });
    });
    // Clear button click
    // Clear search form and set default params
    $("#search_button_clear").bind("click", function(e) {
        $("#date_from, #date_to, #testrun_textfield").attr("value", "");
        $("#checkbox_passed").removeAttr("checked");
        $("#checkbox_failed, #checkbox_nc").attr("checked", "checked");
        $("#specify_testplans input:hidden").remove();
        $("#specify_testplans").attr("title", "You haven't chosen testplans yet.");
        $("#number_choosed").text(getNumberOfChoosedTestPlans());
    });
    // Search button click
    $("#search_button_search").bind("click", function(e) {
        // Verify testrun value
        if (($("#testrun_textfield").val() != "") && (!/^[0-9]+$/.test($("#testrun_textfield").val()))) {
            alert("Please, provide correct TestRun value. It has to be up to 10 digits.")
            return;
        }
        // Verify statuses checkbox values
        if (!($("#checkbox_passed").attr("checked") || $("#checkbox_failed").attr("checked") || $("#checkbox_nc").attr("checked"))) {
            alert("Specify at least one status.")
            return;
        }
        var fromDate = "";
        var toDate = "";
        var testPlans = "";
        var testRun = "";
        var statuses = "";
        // Prepare data for sending to server
        ($("#date_from").val() != "") ? (fromDate = $("#date_from").val()) : (fromDate = "na");
        ($("#date_to").val() != "") ? (toDate = $("#date_to").val()) : (toDate = "na");
        ($("#checkbox_passed").attr("checked")) ? (statuses += "1,") : (statuses = statuses);
        ($("#checkbox_failed").attr("checked")) ? (statuses += "2,") : (statuses = statuses);
        ($("#checkbox_nc").attr("checked")) ? (statuses += "3,") : (statuses = statuses);
        ($("#testrun_textfield").val() != "") ? (testRun = $("#testrun_textfield").val()) : (testRun = "na");
        $("#specify_testplans input.hidden_id:hidden").each(function() {
            testPlans += $(this).val() + ",";
        });
        (testPlans == "") ? (testPlans = "na") : (testPlans = testPlans.substring(0, testPlans.length - 1));
        statuses = statuses.substring(0, statuses.length - 1);
        // Clear result space
        $("#testcases *").remove();
        $("#testcases").append("<div class=\"message_container\">loading ...</div>");
        $.post("../php/get_executed_tcs.php", {
            action: "get_tests",
            from_date: fromDate,
            to_date: toDate,
            statuses: statuses,
            test_plans: testPlans,
            test_run: testRun
            }, function(xml) {
                // Save xml into global var
                ajax_xml = xml;
                // Initialize page counter
                current_page = 1;
                // Build 1st result's page
                build_tests(ajax_xml);
        });
    });
    // Count chosen plans
    $("#number_choosed").text(getNumberOfChoosedTestPlans());
});

function compareDates(date, ptr) {
    if (($("#date_from").val() != "") && ($("#date_to").val() != "")) {
        var dateFrom = new Date(Date.parse($("#date_from").val()));
        var dateTo = new Date(Date.parse($("#date_to").val()));
        if (dateTo < dateFrom) {
            alert("'ToDate' should me more or equal to 'FromDate'");
            $(ptr).val("");
        }
    }
}

// Count chosen plans which located in hidden inputs
function getNumberOfChoosedTestPlans() {
    var count = 0;
    $("#specify_testplans input.hidden_id:hidden").each(function() {
        count++;
    });
    return count;
}

// Build result's page
function build_tests(xml) {
    // Accumulator for generating id for div.plan_container
    var plan_id = 0;
    // Accumulator for generating id for div.run_container
    var run_id = 0;
    // Var is needed to color even tests
    var even = true;
    var seq = 0;
    // Clear result's page
    $("#testcases *").remove();
    // Iterate plans
    $("TestPlan", xml).each(function(id) {
        var plan = $("TestPlan", xml).get(id);
        // Get plan's number in plans sequence. Each plan is allocated on one page.
        seq = $(this).attr("seq");
        // If this is it then output it's content to DOM
        if (seq == current_page) {
            $("#testcases").append("<div id=\"plan_container_" + plan_id++ + "\" class=\"plan_container\">" + 
                "<div class=\"plan_container_name\">" + $("PlanName", plan).text() + "</div></div>");
            $("TestRun", plan).each(function(id) {
                var run = $("TestRun", plan).get(id);
                $("#plan_container_" + (plan_id - 1)).append("<div id=\"run_container_" + run_id++ + 
                    "\" class=\"run_container\"><div class=\"run_container_number\">" + $("RunNumber", run).text() + 
                    "</div></div>");
                even = true;
                $("TestCase", run).each(function(id) {
                    var test = $("TestCase", run).get(id);
                    var table = "<table><tr id=\"" + $("ETID", test).text() + "\">" + 
                        "<td width=\"10%\">" + $("ID", test).text() + "<td>" + 
                        "<td width=\"60%\">" + $("Name", test).text() + "<td>" + 
                        "<td width=\"5%\">" + $("Status", test).text() + "<td>" + 
                        "<td width=\"10%\">" + $("Start", test).text() + "<td>" + 
                        "<td width=\"10%\">" + $("End", test).text() + "<td>" + 
                        "<td width=\"5%\"><span class=\"view_logs\">Logs</span><td>" + 
                        "</tr></table>";
                    if (even) { 
                        $("#run_container_" + (run_id - 1)).append("<div class=\"test_container\">" + 
                            table + "</div>");
                        even = false;
                    }
                    else {
                        $("#run_container_" + (run_id - 1)).append("<div class=\"test_container\"" + 
                            " style=\"background-color: #EEEECC;\">" + table + "</div>");
                        even = true;
                    }
                });
            });
        }
    });
    // Append "next" and "prev" links if needed
    $("#testcases").append("<div id=\"next_prev_page\"></div>");
    if (seq != 0) {
        if ((current_page == 1) && ($("Plans", xml).text() != 1))
            $("#next_prev_page").append("<span id=\"next_page\">Next</span>");
        else
            if ((current_page == $("Plans", xml).text()) && ($("Plans", xml).text() != 1))
                $("#next_prev_page").append("<span id=\"prev_page\">Prev</span>");
            else
                if ((current_page > 1) && (current_page < $("Plans", xml).text()))
                    $("#next_prev_page").append("<span id=\"prev_page\">Prev</span>    <span id=\"next_page\">Next</span>");
    }
    $("#next_prev_page").append("<span id=\"plan_of\">    (Plan #" + current_page + " of " + $("Plans", xml).text() + ")</span>");
    $("#testcases").append("<div class=\"search_result\">Plans: " + $("Plans", xml).text() + "; Testcases: " + $("Count", xml).text() + "</div>")
    // Hide all tests
    $("#testcases .test_container").hide();
    // Click on plan name
    $("#testcases div.plan_container_name").bind("click", function() {
        var runs = $(this).parent("div.plan_container").children("div.run_container");
        if (runs.is(":visible")) $(this).parent("div.plan_container").children("div.run_container").hide();
        else runs.show();
    });
    // Click on run number
    $("#testcases div.run_container_number").bind("click", function() {
        var tests = $(this).parent("div.run_container").children("div.test_container");
        if (tests.is(":visible")) tests.hide();
        else tests.show();
    });
    // Red border around focused plan or run
    $("div.run_container_number, div.plan_container_name").bind("mouseenter", function() {
        $(this).addClass("selected_plan_run");
    });
    $("div.run_container_number, div.plan_container_name").bind("mouseleave", function() {
        $(this).removeClass("selected_plan_run");
    });
    // Pagination
    $("#next_page").bind("click", function() {
        current_page++;
        build_tests(ajax_xml);
    });
    $("#prev_page").bind("click", function() {
        current_page--;
        build_tests(ajax_xml);
    });
    $("span.view_logs").bind("click", function() {
        $.post("../php/get_executed_tcs.php", {
            action: "get_logs",
            et_id: $(this).parent().parent().attr("id")
            }, function(xml) {
                $("#logs_from_silk").text($("SilkLog", xml).text());
                $("#logs_from_server").text($("SrvLog", xml).text());
                $("#logs_failure").text($("Failure", xml).text());
                $("#dialog_view_logs").dialog("open");
        });
    });
}

