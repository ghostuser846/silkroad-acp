// Insert chains into sortable DIV
function build_chains(xml) {
    var accumulator = "";
    $("Chain", xml).each(function(id) {
        var chain = $("Chain", xml).get(id);
        accumulator += "<div class=\"chain_container\">" + 
            "<table width=\"100%\"><tr>" + 
            "<td width=\"10%\">" + $("ID", chain).text() + "</td>" + 
            "<td width=\"30%\"><b>" + $("Name", chain).text() + "</b></td>" + 
            "<td width=\"20%\">" + $("Machine", chain).text() + "</td>" + 
            "<td width=\"20%\">" + $("Config", chain).text() + "</td>" + 
            "<td width=\"10%\">" + $("IsCompleted", chain).text() + "</td>" + 
            "<td width=\"10%\"><a class=\"td_delete_chain\">X</a></td>" + 
            "</tr></table></div>";
    });
    $("#chains_container div").remove();
    $("#chains_container").prepend(accumulator);
}

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
}

function is_ok(xml) {
    if ($("Status", xml).text() == "OK")
        return true;
    return false;
}

