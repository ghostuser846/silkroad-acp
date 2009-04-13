// Insert chains into sortable div
function build_chains(xml) {
    // Clear container
    $("#chains_container div").remove();
    // Build NotRunning chains
    $("NotRunning Chain", xml).each(function(id) {
        var chain = $("NotRunning Chain", xml).get(id);
        var class_ = "chain_container";
        if ($("IsCompleted", chain).text() == "1") class_ = "chain_container_completed";
        $("#chains_container").append("<div id=\"chainwithid_" + 
            $("ID", chain).text() + "\" class=\"" + class_ + "\">" + 
            "<table width=\"100%\"><tr>" + 
            "<td width=\"10%\">" + $("ID", chain).text() + "</td>" + 
            "<td width=\"30%\"><b>" + $("Name", chain).text() + "</b></td>" + 
            "<td width=\"20%\">" + $("Machine", chain).text() + "</td>" + 
            "<td width=\"20%\">" + $("Config", chain).text() + "</td>" + 
            "<td width=\"10%\">" + $("IsCompleted", chain).text() + "</td>" + 
            "<td width=\"10%\"><input type=\"checkbox\" id=\"remove_" + 
            $("ID", chain).text() +"\" class=\"remove_this_chain\">" +
            "</tr></table></div>");
    });
    // Build running chains
    var accumulator = "";
    $("Running Chain", xml).each(function(id) {
        var chain = $("Running Chain", xml).get(id);
        accumulator += "<div class=\"running_chain_container\">" + 
            "<table width=\"100%\"><tr>" + 
            "<td width=\"10%\">" + $("ID", chain).text() + "</td>" + 
            "<td width=\"40%\"><b>" + $("Name", chain).text() + "</b><u> is running</u></td>" + 
            "<td width=\"20%\">" + $("Machine", chain).text() + "</td>" + 
            "<td width=\"20%\">" + $("Config", chain).text() + "</td>" + 
            "<td width=\"10%\">" + $("IsCompleted", chain).text() + "</td>" + 
            "</tr></table></div>";
    });
    $("#running_chains_container div").remove();
    $("#running_chains_container").prepend(accumulator);
}

// Build form for adding new chain
function build_form_add(xml) {
    $("#form_container #form_add select.form_add_select option").remove();
    $("Machines Element", xml).each(function(id) {
        var element = $("Machines Element", xml).get(id);
        $("#add_form_machine").append("<option value=\"" + $("ID", element).text() + "\">" + 
            $("Name", element).text() + "</option>");
    });
    $("TestPlans Element", xml).each(function(id) {
        var element = $("TestPlans Element", xml).get(id);
        $("#add_form_testplan").append("<option value=\"" + $("ID", element).text() + "\">" + 
            $("Name", element).text() + "</option>");
    });
    $("Configurations Element", xml).each(function(id) {
        var element = $("Configurations Element", xml).get(id);
        $("#add_form_config").append("<option value=\"" + $("ID", element).text() + "\">" + 
            $("Name", element).text() + "</option>");
    });
    $("#add_form_iscompleted").append("<option value=\"0\">0</option><option value=\"1\">1</option>");
    $("#form_add, #button_add_cancel, #button_add_ok").toggle("slow");
    $(".button").toggle("fast");
    $("#button_accept_rotations").toggle("fast");
}

// Verify server's status xml
function is_ok(xml) {
    if ($("Status", xml).text() == "OK")
        return true;
    return false;
}

