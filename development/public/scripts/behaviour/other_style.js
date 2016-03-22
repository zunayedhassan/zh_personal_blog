
function SetMinFooterHeight() {
    var wrapperMinHeight = window.innerHeight - ($("footer").height() - 86);
    $("#wrapper").css("min-height", wrapperMinHeight + "px");
}

$(document).ready(function() {    
    SetMinFooterHeight();
    
    $(window).resize(
        function() {
            SetMinFooterHeight();
        }
    );
});