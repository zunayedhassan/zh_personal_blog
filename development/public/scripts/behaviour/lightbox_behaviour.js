function positionLightBoxImage() {
    var top = ($(window).height() - $('#lightbox').height()) / 2;
    var left = ($(window).width() - $('#lightbox').width()) / 2;

    $('#lightbox')
        .css({
            'top': $(document).scrollTop() + top + "px",
            'left': left + "px"
        })
        .fadeIn();
}

function removeLightbox() {
    $('#overlay, #lightbox')
        .fadeOut('slow', function() {
            $(this).remove();
            $('body').css('overflow-y', 'auto'); // show scrollbars!
        });
}

$(document).ready(function() {
    $("a.lightbox").click(function(e) {
        // Hide scrollbars!
        $("body").css("overflow-y", "hidden");

        $("<div id='overlay'></div>")
            .css('top', $(document).scrollTop())
            .css("opacity", "0")
            .animate(
            {
                "opacity": "0.8"
            },

            "slow"
        )
            .appendTo("body");

        $('<div id="lightbox"></div>')
            .hide()
            .appendTo('body');

        var currentlyClickedImage = $("#lightbox");

        $("<img />")
            .attr("src", $(this).attr("href"))
            .load(function() {
                var imageWidth = $(currentlyClickedImage).width();
                var imageHeight = $(currentlyClickedImage).height();

                var windowWidth = $(window).width();
                var windowHeight = $(window).height();

                var tempImageWidth = $(currentlyClickedImage).width();
                var tempImageHeight = $(currentlyClickedImage).height();

                if (imageHeight >= imageWidth) {
                    if (imageHeight >= windowHeight) {
                        tempImageHeight = Math.floor((windowHeight * 0.80));
                        $("#lightbox img").height(tempImageHeight);
                    }
                }
                else {
                    if (imageWidth >= windowWidth) {
                        tempImageWidth = Math.floor((windowWidth * 0.80));
                        $("#lightbox img").width(tempImageWidth);
                    }
                }

                positionLightBoxImage();
            })
            .click(function() {
                removeLightbox();
            })
            .appendTo("#lightbox");

        /* Lightbox overlay */
        $("#overlay").css(
            {
                "position": "fixed",
                "top": "0",
                "left": "0",
                "height": "100%",
                "width": "100%",
                "background": "black url('images/loading.gif') no-repeat scroll center center"
            }
        ).click(function() {
                removeLightbox();
            }
        );

        $("#lightbox").css("position", "absolute");

        $(document).bind("keydown", function(keyEvent) {
            // ESCAPE
            if (keyEvent.keyCode == 27) {
                removeLightbox();
            }
        });

        return false;
    });
});