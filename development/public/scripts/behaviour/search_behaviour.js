window.PerformSearch = function() {
    $(document).ready(function() {
        var searchKeyword = $.trim($("#search").val());
        
        if (searchKeyword.length > 0) {
            DisplayPage("search", "contentBody", "keyword=" + searchKeyword);
        }
        else {
            $("#search")
                    .effect("shake", 500, callback)
                    .css({ "font-size": "larger", "font-family": "caviar_dreamsregular", "height": "2em" });
            
            $("#search").val("");
        }
    });
    
    return false;
}

// callback function to bring a hidden box back
function callback() {
        setTimeout(function() {
                $("#shake").removeAttr("style").hide().fadeIn();
        }, 1000);
};