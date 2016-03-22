$(document).ready(function(e) {
	/* Animation for: Search Field */
	var searchField = $("#searchSection form input[type='search']");
	var defaultSearchFieldWidth = $(searchField).css("width");
	
    $(searchField).on("focus", function() {
		$(this).animate({ "width": "13em" });
	});
	
	$(searchField).on("blur", function() {
		$(this).animate({ "width": defaultSearchFieldWidth });
	});

    /* Main navigation animation */
    // If big screen
    if ($(window).width() >= 1024) {
        $("#mainNavigation ul li a").hover(
            function(e) {
                $(this).animate({ "margin-right": "-15px" });
            },

            function(e) {
                $(this).animate({ "margin-right": "0px" });
            }
        );
    }
    // Otherwise
    else {
        $("#mainNavigation ul li a").hover(
            function(e) {
                $(this).css("text-decoration", "underline");
            },

            function(e) {
                $(this).css("text-decoration", "none");
            }
        );
    }

	/* Footer page navigation animation */
	$("#footerNavigation ul li a").hover(
		function(e) {
			$(this).css("color", "#fcaf3e");
			$(this).animate({"margin-left": "0.5em"});
		},
		
		function(e) {
			$(this).css("color", "#ccc");
			$(this).animate({"margin-left": "0em"});
		}
	);
	
	/* Footer backToTop element animation */
	$("#backToTop a").hover(
		function(e) {
			$(this).css("color", "#fcaf3e");
		},
		
		function(e) {
			$(this).css("color", "#ccc");
		}
	);
});