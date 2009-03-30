$(document).ready(function(e) {
    $("#form_add, #button_add_cancel, #button_add_ok").toggle("fast");
    // Initializing the sortable DIV
    $("#chains_container").sortable();
    $("#chains_container").disableSelection();
    $("#chains_container").bind("sortupdate", function(event, ui) {
        alert("Chains were modified");
    });
    $("#form_container div.button").bind("mouseenter", function(e) {
        $(this).css("background-color", "yellow");
    });
    $("#form_container div.button").bind("mouseleave", function(e) {
        $(this).css("background-color", "white");
    });
    $("#form_container div.button_action").bind("mouseenter", function(e) {
        $(this).css("background-color", "red");
    });
    $("#form_container div.button_action").bind("mouseleave", function(e) {
        $(this).css("background-color", "blue");
    });
    $("#form_container div#button_get").bind("click", function(e) {
        $("#chains_container div").remove();
        $("#running_chains_container div").remove();
        $("#chains_delete_zone div").remove();
        $("#chains_container").prepend("<div class=\"message_container\">loading ...</div>");
        $("#running_chains_container").prepend("<div class=\"message_container\">loading ...</div>");
        $.post("../php/get_test_chains.php", {
            action: "get_chains"
            }, function(xml) {
                build_chains(xml);
        });
    });
    $("#form_container div#button_delete").bind("click", function(e) {
        var counter = 0;
        $("input.remove_this_chain:checked").each(function() {
            counter++;
        });
        alert(counter);
    });
    $("#form_container div#button_add").bind("click", function(e) {
        $("#form_container").prepend("<div class=\"message_container\">loading ...</div>");
        $.post("../php/get_test_chains.php", {
            action: "get_data_for_add"
            }, function(xml) {
                build_form_add(xml);
                $("#form_container div.message_container").remove();
        });
    });
    $("#button_add_cancel").bind("click", function(e) {
        $("#form_add, #button_add_cancel, #button_add_ok").toggle("fast");
        $(".button").toggle("fast");
        $("#form_container #form_add select.form_add_select option").remove();
    });
    $("#button_add_ok").bind("click", function(e) {
        $.post("../php/get_test_chains.php", {
            action: "add_chain",
            machine: $("#add_form_machine").val(),
            testplan: $("#add_form_testplan").val(),
            config: $("#add_form_config").val(),
            iscompleted: $("#add_form_iscompleted").val()
            }, function(xml) {
                if (is_ok(xml)) alert("Chain was added successfully");
                else alert("Error while adding new chain");
        });
        $("#form_add, #button_add_cancel, #button_add_ok").toggle("fast");
        $(".button").toggle("fast");
        $("#form_container #form_add select.form_add_select option").remove();
        $("#form_container div#button_get").trigger("click");
    });
    $("#form_container div#button_get").trigger("click");
});

