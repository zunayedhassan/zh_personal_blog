<?php
/**
 * FILE NAME:       portfolio.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            September 27, 2013
 * LAST EDITED:     October 14, 2013 11:32 AM
 * 
 * PURPOSE:         It's a showcase of authors work on programming and design.
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            It's also basically a static page
 * 
 **/

// Importing required library
include("./application/common_tools.php");

// If user tries to open http://<your_page_name>/portfolio.php directly, then
// redirect to http://<your_page_name>/index.php and open as
// http://<your_page_name>/index.php#?section=portfolio, otherwise continue.
RedirectTo("portfolio");
?>

<section id="mainContent" itemscope itemtype ="http://schema.org/CollectionPage">
    <header class="otherContent">
        <h2 class="hidden">Main Content</h2>
    </header>
    
    <!-- External Stylesheets -->
    
    <link rel="stylesheet" href="stylesheets/portfolio_style.css">
    
    <!--
    FILE NAME:      buttons.css
    AUTHOR:         CHAD MAZZOLA
    SOURCE:         http://hellohappy.org/css3-buttons/

    PURPOSE:        Holds some purely CSS made button style
    -->
    <link rel="stylesheet" href="stylesheets/buttons.css">
    
    <!--
    FILE NAME:      lightbox_behaviour.js
    PURPOSE:        When an image is clicked then, this script will make a
                    semi transparent dark wall between the web page and 
                    clicked image and show that on center of the page. If user
                    click on mouse or press ESC, the image will disappear.
    -->
    <script type="text/javascript" src="scripts/behaviour/lightbox_behaviour.js"></script>
    
    <script type="text/javascript">
        $(document).ready(function() {
            // Blog navigation will be hedden by default
            $("#blogNavigationLeft").hide();
            $("#blogNavigationRight").hide();
        });
        
        // Changing page title
        document.title = "Portfolio: Zunayed Hassan's Personal Blog";
    </script>

    <article>
        <header>
            <h2>Welcome to My World</h2>
        </header>

        <div id="showcase">
            <h3>A Little Bit of My Work</h3>

            <div class="showcaseItem">
                <div class='singleImage'><figure><a href='images/users_images/garden_manager_ui_concept.png' class='lightbox'><img src='images/users_images/garden_manager_ui_concept.png'/></a></figure></div>

                <span>
                    <h4 itemprop="name">UI Design</h4>
                    <p>This is just a concept art of a smart Garden Management System. Users can access and operate their who garden management machinery through smart phone.</p>
                    <div>
                        <button class="skip" onclick="window.open('files/GardenManager_UI_Concept.pdf');">View</button>
                    </div>
                </span>
            </div>
            
            <div class="showcaseItem">
                <div class='singleImage'><figure><a href='images/users_images/deviant_dock_snapshot.png' class='lightbox'><img src='images/users_images/deviant_dock_snapshot.png'/></a></figure></div>

                <span>
                    <h4 itemprop="name">Deviant DOCK</h4>
                    <p>Deviant DOCK is a launcher which organizes your shortcut file, folder in you desktop. Obviously you can use and change animation, skins and position too.</p>
                    <p>All you need is just select any icon and choose its location and then save it. This also supports drag & drop feature too. You can try this software to enhance your desktops' appearance.</p>
                    <div>
                        <button class="skip" onclick="window.open('http://www.softpedia.com/get/Desktop-Enhancements/Other-Desktop-Enhancements/Deviant-DOCK.shtml');">Download</button>
                    </div>
                </span>
            </div>

            <div class="showcaseItem">
                <div class='singleImage'><figure><a href='images/users_images/library_management_system_snapshot.png' class='lightbox'><img src='images/users_images/library_management_system_snapshot.png'/></a></figure></div>

                <span>
                    <h4 itemprop="name">Library Management System</h4>
                    <p>A simple and easy to use Library Management System written in Java.</p>
                </span>
            </div>
            
        </div>
    </article>
</section>

