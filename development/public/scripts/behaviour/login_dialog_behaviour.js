function DisableScrolling(e) {
    var scrollTo = null;

    if (e.type == 'mousewheel') {
        scrollTo = (e.originalEvent.wheelDelta * -1);
    }
    else if (e.type == 'DOMMouseScroll') {
        scrollTo = 1000 * e.originalEvent.detail;
    }

    if (scrollTo) {
        e.preventDefault();
        $(this).scrollTop(scrollTo + $(this).scrollTop());
    }
}

function HideLoginDialog() {
    $("html")
        .css("overflow", "visible")
        .unbind("mousewheel DOMMouseScroll", DisableScrolling);

    $("#loginDialog").fadeOut();
    $("#disabledWall").fadeOut();
}

$(document).ready(function() {
    $(".hideByDefault").hide();

    $("#loginDialog").hover(
        function() {
            $("#loginDialog div[title='Close']").fadeIn();
        },

        function() {
            $("#loginDialog div[title='Close']").fadeOut();
        }
    );

    $("#loginDialog div[title='Close']").click(HideLoginDialog);

    $("#mainHeader h1").click(
        function() {
            $("html")
                .css("overflow", "hidden")
                .bind('mousewheel DOMMouseScroll', DisableScrolling);

            $("html").bind("keydown", function(keyEvent) {
                if (keyEvent.keyCode == 27) {
                    HideLoginDialog();
                }
            });


            var windowWidth = $(window).width();
            var windowHeight = screen.height;

            $("#disabledWall")
                .css({
                    "position":         "absolute",
                    "left":             "0px",
                    "top":              "0px",
                    "width":            windowWidth + "px",
                    "height":           windowHeight + "px",
                    "backgroundColor":  "rgba(0, 0, 0, 0.75)"
                })
                .fadeIn()
                .click(HideLoginDialog);


            if (windowWidth >= 400) {
                $("#loginDialog").css("width", "300px");
            }
            else {
                $("#loginDialog").css("width", "75%");
            }

            var loginDialogWidth = $("#loginDialog").width();
            var loginDialogHeight = $("#loginDialog").height();

            var loginDialogPositionX = (windowWidth - loginDialogWidth) / 2;
            var loginDialogPositionY = (windowHeight - loginDialogHeight) / 2;

            $("#loginDialog")
                .css({
                    "position": "absolute",
                    "left":     loginDialogPositionX + "px",
                    "top":      loginDialogPositionY + "px"
                })
                .fadeIn()
                .draggable();

            $("#loginInput").focus();
        }
    );


    $("#loginFormAccordion")
        .accordion({
            collapsible: true,
            heightStyle: "300"
        })
        .css("fontFamily", "'caviar_dreamsregular', sans-serif");
});