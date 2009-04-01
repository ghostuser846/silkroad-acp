$(document).ready(function(e) {
    $("#date_from").datepicker({
        showOn: 'button', 
        buttonImage: '../img/calendar/calendar.gif', 
        buttonImageOnly: true,
        dateFormat: "yy/mm/dd",
        onClose: function(date) {
                        compareDates(date, this);
                    }
    });
    $("#date_to").datepicker({
        showOn: 'button', 
        buttonImage: '../img/calendar/calendar.gif', 
        buttonImageOnly: true,
        dateFormat: "yy/mm/dd",
        onClose: function(date) {
                        compareDates(date, this);
                    }
    });
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
                    $("#specify_testplans").append("<input class=\"hidden_id\" type=\"hidden\" value=\"" +
                        $(this).attr("value") + "\" />" +
                        "<input class=\"hidden_plan\" type=\"hidden\" value=\"" +
                        $(this).text() + "\" />");
                    tip += $(this).text() + ", ";
                });
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
    $("#specify_testplans").bind("click", function(e) {
        $("#dialog_choose_testplans").dialog("open");
        $.post("../php/get_executed_tcs.php", {
            action: "get_plans"
            }, function(xml) {
                $("#dialog_choose_testplans select option").remove();
                $("TestPlan", xml).each(function(id) {
                    var element = $("TestPlan", xml).get(id);
                    $("#dialog_choose_testplans select").append("<option value=\"" + 
                        $("ID", element).text() + "\">" + $("Name", element).text() + "</option>");
                });
        });
    });
    $("#search_button_clear").bind("click", function(e) {
        $("#date_from, #date_to, #testrun_textfield").attr("value", "");
        $("#checkbox_passed").removeAttr("checked");
        $("#checkbox_failed, #checkbox_nc").attr("checked", "checked");
        $("#specify_testplans input:hidden").remove();
        $("#specify_testplans").attr("title", "You haven't chosen testplans yet.");
        $("#number_choosed").text(getNumberOfChoosedTestPlans());
    });
    $("#search_button_search").bind("click", function(e) {
        if (($("#testrun_textfield").val() != "") && (!/^[0-9]+$/.test($("#testrun_textfield").val()))) {
            alert("Please, provide correct TestRun value. It has to be up to 10 digits.")
            return;
        }
        if (!($("#checkbox_passed").attr("checked") || $("#checkbox_failed").attr("checked") || $("#checkbox_nc").attr("checked"))) {
            alert("Specify at least one status.")
            return;
        }
        var fromDate = "";
        var toDate = "";
        var isPassed = "";
        var isFailed = "";
        var isNotCompleted = "";
        var testPlans = "";
        var testRun = "";
        var statuses = "";
        ($("#date_from").val() != "") ? (fromDate = $("#date_from").val()) : (fromDate = "na");
        ($("#date_to").val() != "") ? (toDate = $("#date_to").val()) : (toDate = "na");
        ($("#checkbox_passed").attr("checked")) ? (isPassed = "y") : (isPassed = "n");
        ($("#checkbox_failed").attr("checked")) ? (isFailed = "y") : (isFailed = "n");
        ($("#checkbox_nc").attr("checked")) ? (isNotCompleted = "y") : (isNotCompleted = "n");
        ($("#testrun_textfield").val() != "") ? (testRun = $("#testrun_textfield").val()) : (testRun = "na");
        $("#specify_testplans input.hidden_id:hidden").each(function() {
            testPlans += $(this).val() + ",";
        });
        (testPlans == "") ? (testPlans = "na") : (testPlans = testPlans.substring(0, testPlans.length - 1));
        /*alert("from: " + fromDate + "\n" + 
                "to: " + toDate + "\n" +
                "passed: " + isPassed + "\n" +
                "failed: " + isFailed + "\n" +
                "nc: " + isNotCompleted + "\n" +
                "testrun: " + testRun + "\n" +
                "plans: " + testPlans + "\n");*/
        $.post("../php/get_executed_tcs.php", {
            action: "get_tests",
            from_date: fromDate,
            to_date: toDate,
            passed: isPassed,
            failed: isFailed,
            nc: isNotCompleted,
            test_plans: testPlans,
            test_run: testRun
            }, function(xml) {
        });
    });
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

function getNumberOfChoosedTestPlans() {
    var count = 0;
    $("#specify_testplans input.hidden_id:hidden").each(function() {
        count++;
    });
    return count;
}

