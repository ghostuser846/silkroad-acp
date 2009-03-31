$(document).ready(function(e) {
    $("#main_menu div.main_menu_element").bind("mouseenter", function(e) {
        $(this).css("background-color", "green");
    });
    $("#main_menu div.main_menu_element").bind("mouseleave", function(e) {
        $(this).css("background-color", "white");
    });
    $("#main_menu div.main_menu_element").bind("click", function(e) {
        switch ($(this).text()) {
            case "Chains": {
                document.location = "chains.php";
                break;
            }
            case "Executed TCs": {
                document.location = "executed_tcs.php";
                break;
            }
            default: {
                document.location = "login.php";
                break;
            }
        }
    });
});

