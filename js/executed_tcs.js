$(document).ready(function(e) {
    $("#date_from").datepicker({
        showOn: 'button', 
        buttonImage: '../img/calendar/calendar.gif', 
        buttonImageOnly: true,
        dateFormat: "yy-mm-dd"
    });
    $("#date_to").datepicker({
        showOn: 'button', 
        buttonImage: '../img/calendar/calendar.gif', 
        buttonImageOnly: true,
        dateFormat: "yy-mm-dd"
    });
});

