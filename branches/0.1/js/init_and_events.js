var is_rotating = false;

$(document).ready(function(e) {
    $("#form_add, #button_add_cancel, #button_add_ok, #button_accept_rotations").toggle("fast");
    // Initializing the sortable DIV
    $("#chains_container").sortable();
    $("#chains_container").disableSelection();
    $("#chains_container").bind("sortupdate", function(event, ui) {
        if (!is_rotating) $("#button_get, #button_delete, #button_delete_all, #button_add, #button_accept_rotations").toggle("fast");
        is_rotating = true;
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
    $("#form_container div#button_accept_rotations").bind("click", function(e) {
        if (!is_rotating) { alert("There wasn't any rotations"); return; }
        var accept = confirm("Do you want accept rotations?");
        if (accept) {
            var s = "";
            $("#chains_container div").each(function() {
                s += $(this).attr("id").substring(12, $(this).attr("id").length) + ",";
            });
            s = s.substring(0, s.length - 1);
            $.post("../php/get_test_chains.php", {
                action: "rotate_chains",
                rotations: s
                }, function(xml) {
                    if (is_ok(xml)) alert("Chains were rotated successfully");
                    else alert("Error while rotating chain");
            });
        }
        is_rotating = false;
        $("#form_container div#button_get").trigger("click");
        $("#button_get, #button_delete, #button_delete_all, #button_add, #button_accept_rotations").toggle("fast");
    });
    $("#form_container div#button_delete").bind("click", function(e) {
        var counter = 0;
        var chains_to_be_deleted = new Array();
        $("input.remove_this_chain:checked").each(function() {
            chains_to_be_deleted[counter++] = $(this).attr("id");
        });
        if (counter == 0) {
            alert("You should choose some chains which you want to delete.");
            return;
        } else {
            var s = "";
            for (var i = 0; i < chains_to_be_deleted.length; i++) {
                if (i == (chains_to_be_deleted.length - 1))
                    s += chains_to_be_deleted[i].substring(7, chains_to_be_deleted[i].length);
                else
                    s += chains_to_be_deleted[i].substring(7, chains_to_be_deleted[i].length) + ",";
            }
            var accept = confirm("Following chains will be delete. Are you sure you want to do this?\n" + s);
            if (accept) {                
                $.post("../php/get_test_chains.php", {
                    action: "delete_following",
                    chains: s
                    }, function(xml) {
                        $("#form_container div#button_get").trigger("click");
                        if (is_ok(xml)) alert("Chain(s) was(were) deleted successfully");
                        else alert("Error while deleting chain(s)");
                });
            }
        }
    });
    $("#form_container div#button_delete_all").bind("click", function(e) {
        var accept = confirm("Do you really want to delete all chains?");
        if (accept) {
            accept = confirm("Do you want to delete running chains too?")
            if (accept) {
                $.post("../php/get_test_chains.php", {
                    action: "delete_all"
                    }, function(xml) {
                        $("#form_container div#button_get").trigger("click");
                        if (is_ok(xml)) alert("All chains were deleted successfully");
                        else alert("Error while deleting chains");
                });
            } else {
                $.post("../php/get_test_chains.php", {
                    action: "delete_not_running"
                    }, function(xml) {
                        $("#form_container div#button_get").trigger("click");
                        if (is_ok(xml)) alert("Chain was added successfully");
                        else alert("Error while adding new chain");
                });
            }
        }
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
        $("#button_accept_rotations").toggle("fast");
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
        $("#button_accept_rotations").toggle("fast");
        $("#form_container #form_add select.form_add_select option").remove();
        $("#form_container div#button_get").trigger("click");
    });
    $("#form_container div#button_get").trigger("click");
});

