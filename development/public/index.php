<?php
/**
 * FILE NAME:       index.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            September 1, 2013
 * LAST EDITED:     October 13, 2013 11:30 AM
 * 
 * PURPOSE:         To provide default UI for all other pages,
 *                  including most CSS and JavaScript files
 *                  (depends on needly basis).
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/

    // Starting session
    session_start();
    
    // Importing required library
    include("./application/settings.php");
    include("./application/database.php");
    include("./application/common_tools.php");
    include("./application/upload_image.php");
    include("./application/upload_multiple_image_files.php");

    // If administrator pressed into Sign In/Sign Out button, then do required 
    // login/logout operation
    PerformLogin("loginInput", "passwordInput", "loginSubmitButton");
    
    // If administrator just in case, check on 'Remember Me' during Sign In 
    // operation, then remember that session for 30 days. Otherwise, remember 
    // that session for 10 minitues
    HelpToRememberPassword("forgotPassword", "forgotPasswordSubmitButton");
    
    // If administrator on the way for uploading an image, then perform
    // required upload operation.
    UploadImage();
    
    // If administrator on the way for uploading multiple images, then perform
    // required upload operation.
    UploadMultipleImageFiles();
?>
<!doctype html>
<html itemscope itemtype ="http://schema.org/WebPage">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Personal website of Mohammod Zunayed Hassan">
        <meta name="keywords" content="Blog, Programming, Coding, Software, Review, Share">
        <meta name="author" content="Mohammod Zunayed Hassan">
        <meta http-equiv="content-type" content="text/html;charset=UTF-8">
        
        <title>Zunayed Hassan's Personal Blog</title>
        
        <!-- External Stylesheets -->
        
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="http://zunayedhassan.3owl.com/icons/favicon.ico">
        
        <!-- Default stylesheets for all required pages: Desktop, Tablet and Mobile -->
        <link rel="stylesheet" media="all" href="stylesheets/default.css" />
        <link rel="stylesheet" media="(min-width: 1024px)" href="stylesheets/desktop.css" />
        <link rel="stylesheet" media="(min-width: 641px) and (max-width: 1023px)" href="stylesheets/tablet.css" />
        <link rel="stylesheet" media="(max-width: 640px)" href="stylesheets/mobile.css" />
        
        <!--
        FILE NAME:      jquery.ui.all.css
        DEPENDENCY FOR: jQuery UI
        -->
        <link rel="stylesheet" href="scripts/behaviour/themes/base/jquery.ui.all.css">
        <link rel="stylesheet" href="scripts/behaviour/css/dark-hive/jquery-ui-1.10.3.custom.css">
        
        <!--
        FILE NAME:      login_dialog_style.css
        PURPOSE:        To provide style for login UI.
        -->
        <link rel="stylesheet" href="stylesheets/login_dialog_style.css">
        
        <!--
        FILE NAME:      tomorrow-night.css
        DEPENDENCY FOR: highlight.pack.js
        
        PURPOSE:        Make code highlighted according to 'tommorrow-night'
                        theme.
        -->
        <link rel="stylesheet" href="stylesheets/highlight/tomorrow-night.css">
        
        <!-- RSS -->
        <link rel="alternate" href="feed.php" title="Zunayed Hassan RSS Feed" type="application/rss+xml" />
        
        
        <!-- External JavaScript Library -->
        
        <!--
        FILE NAME:      jquery-2.0.3.js
        DEPENDENCY:     JavaScript
        VERSION:        2.0.3
        SOURCE:         http://jquery.com/
        
        PURPOSE:        jQuery is a fast, small, and feature-rich JavaScript
                        library.
        -->
        <script type="text/javascript" src="scripts/jquery-2.0.3.js"></script>
        
        <!--
        LIBRARY NAME:   jQuery UI
        DEPENDENCY:     jQuery
        VERSION:        1.10.3
        SOURCE:         http://jqueryui.com/
        
        PURPOSE:        jQuery UI is a curated set of user interface
                        interactions, effects, widgets, and themes built on top
                        of the jQuery JavaScript Library. 
        -->
        <script type="text/javascript" src="scripts/animation/default_animation.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.core.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.widget.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.mouse.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.button.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.draggable.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.accordion.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.position.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.effect.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.effect-shake.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.dialog.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.resizable.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.tabs.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.resizable.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.menu.js"></script>
        <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.dialog.js"></script>
        
        <!--
        FILE NAME:      settings.js
        PURPOSE:        Provide default settings for all other JavaScript
                        library.
        -->
        <script type="text/javascript" src="scripts/behaviour/settings.js"></script>
        
        <!--
        FILE NAME:      search_behaviour.js
        DEPENDENCY:     jQuery

        PURPOSE:        Search animation, validation and redirection to
                        searching operation.
        -->
        <script type="text/javascript" src="scripts/behaviour/search_behaviour.js"></script>
        
        <!--
        FILE NAME:      common_tools.js
        DEPENDENCY:     jQuery

        PURPOSE:        Various common functionalities required for all over
                        the sites.
        -->
        <script type="text/javascript" src="scripts/behaviour/common_tools.js"></script>
        
        <!--
        NOTE:           Validation library for login
        DEPENDENCY:     jQuery
        -->
        <script type="text/javascript" src="scripts/behaviour/login_form_validator.js"></script>
        <script type="text/javascript" src="scripts/behaviour/email_address_validator.js"></script>
        <script type="text/javascript" src="scripts/behaviour/login_dialog_behaviour.js"></script>
        
        <!--
        FILE NAME:      ajax_behaviour.js
        DEPENDENCY:     jQuery

        PURPOSE:        Helps to load page within index.php along with required
                        styles and animations.
        -->
        <script type="text/javascript" src="scripts/behaviour/ajax_behaviour.js"></script>
        
        <!--
        FILE NAME:      other_styles.js
        DEPENDENCY:     jQuery

        PURPOSE:        Some discreet but important behavior.
        -->
        <script type="text/javascript" src="scripts/behaviour/other_style.js"></script>
        
        <!--
        LIBRARY NAME:   jquery.flex.js
        SOURCE:         http://jsonenglish.com/projects/flex/
        DEPENDENCY:     jQuery

        PURPOSE:        Animation plugin for photo gallery
        -->
        <script type="text/javascript" src="scripts/jquery.flex.js"></script>
        
        <!--
        LIBRARY NAME:   highlight.pack.js
        SOURCE:         http://softwaremaniacs.org/soft/highlight/en/

        PURPOSE:        To colorized code which are posted on blog
        -->
        <script type="text/javascript" src="scripts/highlight.pack.js"></script>
        <script type="text/javascript">hljs.initHighlightingOnLoad();</script>
        
        <!--
        LIBRARY NAME:  Google Map API
        VERSION:       3
        SOURCE:        https://developers.google.com/maps/

        PURPOSE:       The Google Maps Javascript API lets you embed Google Maps in your own web pages.
        -->
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCI3Vhx-HOk9bglrlDhbwUId9Q135xCAR0&sensor=true"></script>
        
        
        <script type="text/javascript">
            /** 
             *   FUNCTION NAME:  SetMap
             *   PARAMETER:      (float) lat, (float) long
             *   RETURN:         None
             *   
             *   PURPOSE:        To set map where Google Map is implemented
             *                   based on geo-coordinates.
             **/
            function SetMap(lat, long) {
                var map = null;
                var geocoder = new google.maps.Geocoder();
                var latlng = new google.maps.LatLng(lat, long);

                var mapOptions = {
                    zoom: 14,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }

                map = new google.maps.Map(document.getElementById(lat + "_" + long), mapOptions);

                map.setCenter(latlng);
                var marker = new google.maps.Marker({
                    map: map,
                    position: latlng
                });
            }
            
            
            /* If the browser is firefox, then change some styles based on
             * version.
             * */
            var firefoxBrowserVersion = navigator.userAgent.toLowerCase().split("firefox/");
            
            if (firefoxBrowserVersion.length > 1) {
                var majorVersion = parseInt(firefoxBrowserVersion[1].split(".")[0]);
                
                // If Firefox Version is 10+
                if (majorVersion >= 10) {
                    $(document).ready(function() {
                        // Then, change some styles
                        $("#mainNavigation ul li:not(:first-child)").css({
                            "marginTop": "-30px",
                            "width": "4em"
                        });
                    });
                }
            }
        </script>
    </head>
    
    <body>
    	<div id="wrapper">
            <div id="pageHeader">
                <header id="mainHeader" <?php echo(isset($_SESSION["logged_in"]) ? "style='color: #999800;'" : ""); ?> >
                    <h1>Zunayed<br/>Hassan</h1>
                </header>
                
                <!-- Social Link -->
                <section id="followMe">
                    <header class="otherContent">
                        <h2>Stay in touch...</h2>
                    </header>
                    
                    <ul>
                        <!-- Facebook -->
                        <li><a href="https://www.facebook.com/zunayedhassan"><img alt="Facebook" src="icons/facebook-32x32.png" title="Facebook" /></a></li>
                        <!-- Twitter -->
                        <li><a href="https://twitter.com/zunayedhassan"><img alt="Twitter" src="icons/twitter-32x32.png" title="Twitter" /></a></li>
                        <!-- Google Plus -->
                        <li><a href="https://plus.google.com/114541443160139307481"><img alt="Google +" src="icons/googleplus-32x32.png" title="Google +" /></a></li>
                        <!-- Linked In -->
                        <li><a href="http://bd.linkedin.com/pub/mohammod-zunayed-hassan/76/269/814/"><img alt="Linked In" src="icons/LinkedIn-32x32.png" title="Linked In" /></a></li>
                        <!-- RSS -->
                        <li><a href="feed.php"><img alt="RSS" src="icons/feed-32x32.png" title="Subscribe" /></a></li>
                    </ul>
                </section>
                
                <!-- Search -->
                <section id="searchSection">
                    <header class="otherContent">
                        <h2>Looking for something?</h2>
                    </header>
        
                    <form class="otherContent" onsubmit="return window.PerformSearch();">
                        <input id="search" type="search" placeholder="type & hit 'enter'" />
                    </form>
                </section>
            </div>
            
            <div id="middleContent">
                <style type="text/css">
                    /**
                     *  PURPOSE:        Styles for blog navigation
                     *  DEPENDENCY FOR: blog_content.php
                     **/
                    .blogNavigation {
                        background: #353535;
                        position: fixed;
                        top: 30%;
                    }
                    
                    .blogNavigation:hover {
                        background: #356aa0;
                    }
                    
                    .blogNavigation:active {
                        background: #006e2e;
                    }
                    
                    #blogNavigationLeft {
                        left: 0px;
                    }
                    
                    #blogNavigationLeft div {
                        background: url('icons/Arrowhead-Left-01.png') no-repeat;
                        height: 32px;
                        width: 32px;
                    }
                    
                    #blogNavigationRight {
                        right: 0px;
                    }
                    
                    #blogNavigationRight div {
                        background: url('icons/Arrowhead-Right-01.png') no-repeat;
                        height: 32px;
                        width: 32px;
                    }
                </style>
                
                <script type="text/javascript">
                    $(document).ready(function() {
                        // At first blog navigation buttons will be hidden
                        $("#blogNavigationLeft").hide();
                        $("#blogNavigationRight").hide();
                    });
                </script>
                
                <!-- Previous Post -->
                <nav class='blogNavigation' id="blogNavigationLeft" title="Previous Article">
                    <div></div>
                </nav>
                
                <!-- Next Post -->
                <nav class='blogNavigation' id="blogNavigationRight" title="Next Article">
                    <div></div>
                </nav>
                
                <!-- Main Menu -->
                <nav id="mainNavigation">
                    <header class="otherContent">
                        <h2 class="hidden">Site Navigation</h2>
                    </header>
                    
                    <ul>
                        <!-- Blog -->
                        <li id="blogNav"><a href="#?section=blog" onclick="return DisplayPage('blog', 'contentBody');">Blog</a></li>
                        <!-- Recent Tour -->
                        <li id="recentTourNav"><a href="#?section=recent_tour" onclick="return DisplayPage('recent_tour', 'contentBody');">Recent Tour</a></li>
                        <!-- Portfolio -->
                        <li id="portfolioNav"><a href="#?section=portfolio" onclick="return DisplayPage('portfolio', 'contentBody');">Portfolio</a></li>
                        <!-- About Me -->
                        <li id="aboutMeNav" itemscope itemtype ="http://schema.org/AboutPage"><a itemprop="url" href="#?section=about_me" onclick="return DisplayPage('about_me', 'contentBody');">About Me</a></li>

                        <?php
                        // If user is logged in then show this option
                        if (isset($_SESSION["logged_in"]) and $_SESSION["logged_in"])
                        {
                            ?>
                            <!-- Configure -->
                            <li id="configureNav"><a href="#?section=configure" onclick="return DisplayPage('configure', 'contentBody');">Configure</a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </nav>
                
                <!-- This is where other pages will load -->
                <div id="contentBody">
                    <script type="text/javascript">
                        /**
                         *  NOTE:
                         *      1. This page is hugely based on Ajax technology
                         *  and all the page user wants to visit will load
                         *  within index.php file. Which means no matter what
                         *  link user clicked, the address would be same as
                         *  http://<your_site_name>/index.php, which we don't
                         *  want.
                         *  
                         *      2. So, we want no matter which link user click
                         *  the address will be change at the address bar, but
                         *  user will stay at the same page (index.php).
                         *  
                         *      3. But just in case if user wants to visit to a
                         *  a particular page, then user can also be able to 
                         *  paste the link that already in the addressbar or 
                         *  restored from browser session and just immediatly
                         *  go in there.
                         *  
                         *  
                         *  PURPOSE:
                         *      This script will detect based on current
                         *  URL and navigate through that particular
                         *  page.
                         **/
                        var currentUrl = (window.location.href).split("#?section=")[1];
                                                
                        if (currentUrl !== undefined) {
                            // If blog_content then show particular article
                            if (currentUrl.match(/blog_content_/g) !== null) {
                                var contentId = currentUrl.split("blog_content_")[1];
                                DisplayPage("blog_content", "contentBody", "content_id=" + contentId);
                            }
                            // If blog page, the show that particular page
                            else if (currentUrl.match(/page_/g) !== null) {
                                var pageNumber = currentUrl.split("page_")[1];
                                DisplayPage("blog", "contentBody", "page=" + pageNumber);
                            }
                            // If search result, then show search page
                            else if (currentUrl.match(/search/g) !== null) {
                                var search = currentUrl.split("search&")[1];
                                DisplayPage("search", "contentBody", search);
                            }
                            // If recent tour, then show that particular page
                            else if (currentUrl.match(/recent_tour/g) !== null) {
                                var recentTourPageInfo = currentUrl.split("recent_tour&page=");
                                
                                // If nothing is mentioned then show page 1
                                if (recentTourPageInfo.length > 1) {
                                    var pageNumber = recentTourPageInfo[1];
                                    DisplayPage("recent_tour", "contentBody", "page=" + pageNumber);
                                }
                                // Otherwise, show the requested page
                                else {
                                    DisplayPage("recent_tour", "contentBody");
                                }
                            }
                            // If nothing is mentioned
                            // (like: http://<your_page_name>/index.php) then
                            // show blog page
                            else {
                                DisplayPage(currentUrl, 'contentBody');
                            }
                        }
                        // If nothing is mentioned
                        // (like: http://<your_page_name>) then show blog page.
                        else {
                            DisplayPage("blog", 'contentBody');
                        }
                    </script>
                </div>
            </div>
        </div>

        <!-- --------------------------------------------------------------- -->
        <!--                            Dialog                               -->
        <!-- --------------------------------------------------------------- -->

        <!--
            When a dialog box will open, this portion of element will work as
            semi transparent wall between 
        -->
        <div id="disabledWall" class="hideByDefault"></div>

        <!-- Login Dialog Box -->
        <div id="loginDialog" class="hideByDefault">
            <div title="Close" class="darkHiveDefaultBackground hideByDefault"><div></div></div>
            <div id="loginHeader" class="darkHiveDefaultBackground">Login</div>

            <div id="loginFormAccordion">
                <h3><?php echo(isset($_SESSION["logged_in"]) ? "Account" : "Identify yourself..."); ?></h3>
                <div>
                    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" class="darkHiveDefaultBackground" method="post" onsubmit="return IsLoginFormValid('loginInput', 'passwordInput', 'loginSubmitButton');">
                        <?php
                            // If user is not logged in
                            if (!isset($_SESSION["logged_in"]) or !$_SESSION["logged_in"])
                            {
                                ?>
                                    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" class="darkHiveDefaultBackground" method="post" onsubmit="return IsLoginFormValid('loginInput', 'passwordInput', 'loginSubmitButton');">
                                        <div id="loginBody">
                                            <p id="loginValidationTips" class="hideByDefault">Please, check your user name and password. Password length must be at least <script type="text/javascript">document.write(Settings.MIN_PASSWORD_LENGTH);</script> characters. Also, user name and password allowed only from a-z, A-Z, 0-9 and hyphen ('-')</p>

                                            <!-- Username -->
                                            <label for="loginInput">Username</label><br/>
                                            <input type="text" id="loginInput" name="loginInput" maxlength="<?php echo(Settings::$MAX_LOGIN_NAME_LENGTH); ?>" />

                                            <br/><br/>

                                            <!-- Password -->
                                            <label for="passwordInput">Password</label><br/>
                                            <input type="password" id="passwordInput" name="passwordInput" />
                                        </div>

                                        <div class="darkHiveDefaultBackground">
                                            <!-- Remember Me -->
                                            <input type="checkbox" id="rememberMe" name="rememberMe" value="1" /><label class="checkbox" for="rememberMe"><span></span>Remember Me</label>
                                            
                                            <!-- Submit -->
                                            <input type="submit" id="loginSubmitButton" name="loginSubmitButton" class="buttonStyle" value="<?php echo(!isset($_SESSION["logged_in"]) ? "Sign In" : "Sign Out"); ?>" />
                                        </div>
                                    </form>
                                <?php
                            }
                            // If user is logged in
                            else
                            {
                                ?>
                                    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" class="darkHiveDefaultBackground" method="post" onsubmit="return IsLoginFormValid('loginInput', 'passwordInput', 'loginSubmitButton');">
                                        <div class="darkHiveDefaultBackground" style="margin-top: -1em;">
                                            <!-- Submit -->
                                            <input type="submit" id="loginSubmitButton" name="loginSubmitButton" class="buttonStyle" value="<?php echo(!isset($_SESSION["logged_in"]) ? "Sign In" : "Sign Out"); ?>" style="margin-left: 30%; width: 40%;" />
                                        </div>
                                    </form>
                                <?php
                            }
                        ?>
                    </form>
                </div>

                <h3>Forgot password?</h3>
                <div id="forgotPasswordBody">
                    <form action="<?php echo($_SERVER['PHP_SELF']); ?>" method="post" onsubmit="return IsEmailAddressValid('forgotPassword');" class="darkHiveDefaultBackground">
                        <p id="forgotPasswordValidatorTips" class="hideByDefault">Please, type email address correctly</p>

                        <!-- Email Address -->
                        <label for="forgotPassword">Your email address</label><br/>
                        <input type="email" id="forgotPassword" name="forgotPassword" />

                        <div class="darkHiveDefaultBackground">
                            <!-- Submit -->
                            <input type="submit" id="forgotPasswordSubmitButton" name="forgotPasswordSubmitButton" class="buttonStyle" value="Send Email" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- --------------------------------------------------------------- -->
            
        <footer>
            <!-- Footer Navigation -->
            <section id="footerNavigation">
                <h2 class="otherContent">Look around...</h2>
                
                <ul>
                    <!-- Blog -->
                    <li><a href="#?section=blog" onclick="return DisplayPage('blog', 'contentBody');">Blog</a></li>
                    <!-- Recent Tour -->
                    <li><a href="#?section=recent_tour" onclick="return DisplayPage('recent_tour', 'contentBody');">Recent Tour</a></li>
                    <!-- Portfolio -->
                    <li><a href="#?section=portfolio" onclick="return DisplayPage('portfolio', 'contentBody');">Portfolio</a></li>
                    <!-- About Me -->
                    <li><a href="#?section=about_me" onclick="return DisplayPage('about_me', 'contentBody');">About Me</a></li>
                </ul>
            </section>
            
            <!-- Social Links -->
            <section id="footerSocial">
                <h2 class="otherContent">Stay in touch...</h2>
                
                <ul>
                    <!-- Facebook -->
                    <li><a href="https://www.facebook.com/zunayedhassan"><img alt="Facebook" src="icons/facebook-32x32.png" /></a></li>
                    <!-- Twitter -->
                    <li><a href="https://twitter.com/zunayedhassan"><img alt="Twitter" src="icons/twitter-32x32.png" /></a></li>
                    <!-- Google Plus -->
                    <li><a href="https://plus.google.com/114541443160139307481"><img alt="Google +" src="icons/googleplus-32x32.png" /></a></li>
                    <!-- Linked In -->
                    <li><a href="http://bd.linkedin.com/pub/mohammod-zunayed-hassan/76/269/814/"><img alt="Linked In" src="icons/LinkedIn-32x32.png" /></a></li>
                    <!-- RSS -->
                    <li><a href="feed.php"><img alt="RSS" src="icons/feed-32x32.png" /></a></li>
                </ul>
            </section>
            
            <!-- Go Back to Top -->
            <section id="backToTop">
                <a onclick="ScrollToElement('body');"><b><span>↑</span> Back to Top</b></a>
            </section>
        </footer>
    </body>
</html>

