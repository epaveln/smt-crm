$(document).ready( function() {
    // Select all
    $("A[href='#select_all']").click( function() {
        $("#" + $(this).attr('rel') + " INPUT[type='checkbox']").attr('checked', true);
        return false;
    });
    // Select none
    $("A[href='#select_none']").click( function() {
        $("#" + $(this).attr('rel') + " INPUT[type='checkbox']").attr('checked', false);
        return false;
    });
    // Invert selection
    $("A[href='#invert_selection']").click( function() {
        $("#" + $(this).attr('rel') + " INPUT[type='checkbox']").each( function() {
            $(this).attr('checked', !$(this).attr('checked'));
        });
        return false;
    });
});