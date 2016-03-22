$(document).ready(function() {
    $(".flex").flex();

    var flexTempWidth = null;
    var flexOriginalWidth = null;
    var flexSpeed = 300;

    $(".flex a").hover(
        function() {
            flexTempWidth = $(this).attr("width");
            var childImage = $(this).find("img");
            flexOriginalWidth = $(childImage).width();

            $(childImage).animate({"width": flexTempWidth}, flexSpeed);
        },

        function() {
            var childImage = $(this).find("img");
            $(childImage).animate({"width": flexOriginalWidth}, flexSpeed);
            flexTempWidth = null;
            flexOriginalWidth = null;
        }
    );
});