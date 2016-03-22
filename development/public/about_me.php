<?php
/**
 * FILE NAME:       about_me.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            September 25, 2013
 * LAST EDITED:     October 13, 2013 03:17 PM
 * 
 * PURPOSE:         1. Retrieve data from about_me table on database
 *                  2. Show them accordingly.
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/

// Importing required library
include("./application/settings.php");
include("./application/database.php");
include("./application/common_tools.php");

// Create new database connection
$db = new Database();

// Get everything from about_me table and return only the first row as a result.
$aboutMyself = $db->GetQueryResult("SELECT * FROM about_me", true);

// If user tries to open http://<your_page_name>/about_me.php directly, then
// redirect to http://<your_page_name>/index.php and open as
// http://<your_page_name>/index.php#?section=about_me, otherwise continue.
RedirectTo("about_me");
?>

<!-- External stylesheet for about_me page -->
<link rel="stylesheet" href="stylesheets/about_me_style.css">

<script type="text/javascript">
    $(document).ready(function() {
        // By default, blog navigation will be disabled
        $("#blogNavigationLeft").hide();
        $("#blogNavigationRight").hide();
    });
    
    // Change the title of the page.
    document.title = "About Me: Zunayed Hassan's Personal Blog";
</script>

<script type="text/javascript">
    $(document).ready(function() {
        // blog navigation will be hidden as fading out
        $("#blogNavigationLeft").fadeOut();
        $("#blogNavigationRight").fadeOut();
        
        // If this page is opened by Internet Explorer browser, then make
        // some changes on style
        if (navigator.userAgent.toLowerCase().indexOf('msie') > -1) {
            $("article").css({
                "margin-top": "-2em",
                "margin-bottom": "4em"
            });
        }
    });
</script>

<section id="mainContent">
    <header class="otherContent">
        <h2 class="hidden">Main Content</h2>
    </header>

    <!-- About Me Article -->
    <article itemscope itemtype ="http://schema.org/Person">
        <header>
            <h2><?php echo($aboutMyself["title"]); ?></h2>
        </header>

        <?php
        echo($aboutMyself["content"]);
        ?>
    </article>
</section>


