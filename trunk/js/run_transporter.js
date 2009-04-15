$(document).ready(function(e) {
    $("#transport_button").hide();
    $("#test_connections_button").bind("click", function(e) {
        var badInput = false;
        $("input.not_empty").each(function() {
            if (($(this).val() == "")) {
                alert("Destination hostnames and usernames can't be empty.");
                badInput = true;
                return false;
            }
        });
        if (badInput) return;
        $("#test_connections_button").hide();
        $("#test_result").text("Testing ...");
        $.post("../php/run_transporter_backdoor.php", {
            action: "test_connection",
            dest_host: $("#destination_hostname").val(),
            dest_user: $("#destination_user").val(),
            dest_pass: $("#destination_password").val(),
            source_host: $("#source_hostname").val(),
            source_user: $("#source_user").val(),
            source_pass: $("#source_password").val()
            }, function(xml) {
                processConnectionTestXML(xml);
        });
    });
    $("#clear_button").bind("click", function() {
        $("input").removeAttr("disabled");
        $("#run_to_transport, #destination_config").attr("disabled", "disabled");
        $("#transport_button").hide();
        $("#test_connections_button").show();
        $("#destination_config option").remove();
        $("#run_to_transport").val("");
    });
    $("#transport_button").bind("click", function() {
        if (($("#run_to_transport").val() == "") || (!/^[0-9]+$/.test($("#run_to_transport").val()))) {
            alert("Please, provide correct TestRun value. It has to be up to 10 digits and should not be empty.")
            return;
        }
        $("#transport_button").hide();
        $.post("../php/run_transporter_backdoor.php", {
            action: "transport_run",
            dest_host: $("#destination_hostname").val(),
            dest_user: $("#destination_user").val(),
            dest_pass: $("#destination_password").val(),
            source_host: $("#source_hostname").val(),
            source_user: $("#source_user").val(),
            source_pass: $("#source_password").val(),
            run: $("#run_to_transport").val(),
            config: $("#destination_config").val()
            }, function(xml) {
                $("#dialog_view_log").text($("TransportLog", xml).text());
                $("#dialog_view_log").dialog("open");
                $("#transport_button").show();
        });
    });
    $("#dialog_view_log").dialog({
        bgiframe: true,
        modal: true,
        height: 400,
        width: 600,
        closeOnEscape: true,
        autoOpen: false,
        hide: "explode",
        show: "fold",
        title: "View Transfer Log",
        buttons: {
            "OK": function() {
                $(this).dialog("close");
            },
        }
    });
});

function processConnectionTestXML(xml) {
    if ($("Result", xml).text() == "Success") {
        $("#test_result").removeClass("bad_connect");
        $("#test_result").addClass("good_connect");
        $("#test_result").text("OK!!");
        $("input:not(:disabled)").attr("disabled", "disabled");
        $("#run_to_transport, #destination_config").removeAttr("disabled");
        $("#destination_config option").remove();
        $.post("../php/run_transporter_backdoor.php", {
            action: "get_dest_configs",
            host: $("#destination_hostname").val(),
            user: $("#destination_user").val(),
            pass: $("#destination_password").val()
            }, function(xml) {
                $("Config", xml).each(function(id) {
                    $("#destination_config").append("<option value=\"" + $("ID", this).text() + "\">" + 
                        $("Name", this).text() + "</option>");
                });
                $("#transport_button").show();
        });
    }
    if ($("Result", xml).text() == "Failure") {
        $("#test_result").removeClass("good_connect");
        $("#test_result").addClass("bad_connect");
        $("#test_result").text("Can't connect");
        $("#test_connections_button").show();
    }
}
