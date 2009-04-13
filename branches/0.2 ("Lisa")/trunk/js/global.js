$(document).ready(function(e) {
    // Highlight focused button in main menu
    $("#main_menu div.main_menu_element").bind("mouseenter", function(e) {
        $(this).css("background-color", "green");
    });
    $("#main_menu div.main_menu_element").bind("mouseleave", function(e) {
        $(this).css("background-color", "white");
    });
    // Main menu click handlers
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
    // Highlight focused "action" button in other menu (eg in Search panel)
    $("div.button_action").bind("mouseenter", function(e) {
        $(this).css("background-color", "red");
    });
    $("div.button_action").bind("mouseleave", function(e) {
        $(this).css("background-color", "blue");
    });
});

