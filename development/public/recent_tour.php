<?php
/**
 * FILE NAME:       about_me.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            October 11, 2013
 * LAST EDITED:     October 14, 2013 12:00 PM
 * 
 * PURPOSE:         Check the tag 'tour' on database and shows all of them
 *                  in reversly publishing order in this section.
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/

?>

<!-- External stylesheet -->
<link rel="stylesheet" href='stylesheets/other_style.css'>

<!--
FILE NAME:      flex_style.css
DEPENDENCY FOR: jquery.flex.js

PURPOSE:        Show photo gallery (if exist) in rows and make white borders
                around pictures.
-->
<link rel="stylesheet" href="stylesheets/flex_style.css">

<!--
FILE NAME:      lightbox_behaviour.js
DEPENDENCY:     jQuery

PURPOSE:        When an image is clicked, it shows a semi transparent dark
                background and within that, it show the picture. If user
                presses ESC button or click on anywhere, the image disappears
                and shows back the page that user is supposed to see.
-->
<script type="text/javascript" src="scripts/behaviour/lightbox_behaviour.js"></script>

<!--
LIBRARY NAME:   highlight.pack.js
SOURCE:         http://softwaremaniacs.org/soft/highlight/en/

PURPOSE:        To colorized code which are posted on blog
-->
<script type="text/javascript" src="scripts/highlight.pack.js"></script>
<script type="text/javascript">hljs.initHighlighting();</script>

<!--
LIBRARY NAME:   jquery.flex.js
SOURCE:         http://jsonenglish.com/projects/flex/
DEPENDENCY:     jQuery

PURPOSE:        Animation plugin for photo gallery
-->
<script type="text/javascript" src="scripts/jquery.flex.js"></script>

<!--
FILE NAME:      flex_behaviour.js
DEPENDENCY:     jQuery

PURPOSE:        To setup some changes on animation. Its an customized extension
                of jquery.flex.js and it shows photos slightly different way
                than jquery.flex.js
-->
<script type="text/javascript" src="scripts/behaviour/flex_behaviour.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // Blog navigation will be disabled by default
        $("#blogNavigationLeft").fadeOut();
        $("#blogNavigationRight").fadeOut();
        
        // Changin page title
        document.title = "Recent Tour: Zunayed Hassan's Personal Blog";
        
        // Changing some styles for Internet Explorer
        if (navigator.userAgent.toLowerCase().indexOf('msie') > -1) {
            $("article").css({
                "margin-top": "-2em",
                "margin-bottom": "4em"
            });
        }
    });
</script>
<?php

// Importing libray
include("./application/settings.php");
include("./application/database.php");
include("./application/common_tools.php");

// If user tries to open http://<your_page_name>/recent_tour.php directly, then
// redirect to http://<your_page_name>/index.php and open as
// http://<your_page_name>/index.php#?section=recent_tour, otherwise continue.
$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$uriInfo = $_SERVER['REQUEST_URI'];

// Searching for '?_'
$uriInfo = explode("?_", $uriInfo);

if (count($uriInfo) <= 1)                   // Either '?' or '&_'
{
    // Searching for '&_'
    $uriInfo = $uriInfo[0];
    $uriInfo = explode("&_", $uriInfo);
    
    if (count($uriInfo) <= 1)               // Must be '?', but it also may include '&'
    {
        // Searching for '?'
        $uriInfo = $uriInfo[0];
        
        $uriInfo = explode("?", $uriInfo);
        
        if (count($uriInfo) <= 1)           // It doesn't have any query string
        {
            $uriInfo = "";
        }
        else                                // It must have query string
        {            
            $uriInfo = '&' . $uriInfo[1];
        }
                
        /* Redirect to a different page in the current directory that was requested */
        $host  = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'index.php#?section=recent_tour' . $uriInfo;

        echo("http://$host$uri/$extra");
        header("Location: http://$host$uri/$extra");
        exit;
    }
}

// Creating new database connection
$db = new Database();
$blogs = null;

// Get all articles' ids which are related to tour tag and sort them by
// publishing date in reversing order.
$blogIds = $db->GetQueryResult("SELECT blog_id FROM blog WHERE tag LIKE '%" . Settings::$TOUR_TAG . "%' ORDER BY published DESC;");

// Get the total number of articles
$totalNumberOfRows = $db->GetQueryResult("SELECT count(*) as total_rows FROM blog WHERE tag LIKE '%" . Settings::$TOUR_TAG . "%';", true);
$totalNumberOfRows = $totalNumberOfRows["total_rows"];

$pages =  null;  //GetContentForPages($totalNumberOfRows, $blogIds);

// If there is no article related to tour then, don't go any further.
if ($totalNumberOfRows > 0)
{
    // If total number of articles (related) is not more than 5 then, show
    // them. No need for pagination.
    if ($totalNumberOfRows <= Settings::$MAX_BLOG_CONTENT_SHOW_PER_PAGE)
    {
        // Get all articles from blog table which are related to 'tour' and
        // show them by publishing date in reverse order.
        $blogs = $db->GetQueryResult("SELECT * FROM blog WHERE tag LIKE '%" . Settings::$TOUR_TAG . "%' ORDER BY published DESC;");
        
        // Display them all
        DisplayArticle($blogs);
    }
    // Otherwise, total number of articles are more than 5, which means there
    // be more than a page required to show them all.
    else
    {
        // Organize articles for each pages
        $pages = GetContentForPages($totalNumberOfRows, $blogIds);
        
        // If user mentioned to see any page, then current page should be that
        // page
        if (isset($_GET["page"]))
        {
            $pageNumber = $_GET["page"];
            
            // But, if the page user mentioned isn't exist in recent tour
            // section then, show error mesage.
            if ($pageNumber > count($pages))
            {
                // Show error message
                ?>
                <section id="mainContent">
                    <header class="otherContent">
                        <h2 class="hidden">Main Content</h2>
                    </header>
                    
                    <article class='errorStyle'>
                        <header>
                            <h2>Sorry, page not found... :(</h2>
                        </header>

                        <div class="content">
                            <p>
                                The page you are looking for is not exist.
                            </p>
                        </div>
                    </article>
                </section>
                <?php
            }
            else
            {
                // Then show that particuler page
                $blogs = GetContentsFromPage($pages, $pageNumber); 
                DisplayArticle($blogs);
            }
        }
        else
        {
            // If page number isn't mentioned, then show only page 1
            $blogs = GetContentsFromPage($pages, 1); 
            DisplayArticle($blogs);
        }
    }
}
// There is not article, so pretend that you are sorry and show error message.
else
{
    ?>
    <section id="mainContent">
        <header class="otherContent">
            <h2 class="hidden">Main Content</h2>
        </header>
        
        <article class='errorStyle'>
            <header>
                <h2>Sorry, there is no article... :'(</h2>
            </header>

            <div class="content">
                <p>
                    It seems there isn't any article on this section. I'll share some of my thoughts soon and I'm really sorry.<br/>Please, try another time.
                </p>
            </div>
        </article>
    </section>
    <?php
}


// If total number of pages are more than one then, we need 
// Display pagination
if(($totalNumberOfRows > 0) and (count($pages) > 1))
{
    ?>
    <!-- Page Navigation -->
    <nav id="pageNavigation">
        <header class="otherContent">
            <h2 class="hidden">Page Navigation</h2>
        </header>
        
        <ul>
            <?php
                $currentPage = null;
                $limitPageNav = 7;
            
                // If user mentioned any page then current page should be that page.
                if (isset($_GET["page"]))
                {
                    $currentPage = $_GET["page"];
                    
                    // But, if the page user mentioned is not even exist, then
                    // current page is null
                    if ($currentPage > count($pages))
                    {
                        $currentPage = null;
                    }
                }
                
                
                // If there is a hope for displaying any page then proceed 
                // forward
                if ($currentPage != null)
                {
                    // Total number of pages
                    $totalPages = count($pages);

                    // If total number of pages are within 10 page then 
                    // show every page buttons
                    if ($totalPages <= ($limitPageNav + 3))
                    {       
                        // If current page is not the first page, then show
                        // previous page
                        if ($currentPage != 1)
                        {
                            ?>
                                <li><a style="margin-right: 1em;" href="#?section=recent_tour&page=<?php echo($currentPage - 1); ?>" onclick="DisplayPage('recent_tour', 'contentBody', 'page=<?php echo($currentPage - 1); ?>');">&laquo;</a></li>
                            <?php
                        }

                        // Now starting with page 1 to last page, display all
                        // the page buttons
                        for ($pageNumber = 1; $pageNumber <= count($pages); $pageNumber++)
                        {
                            // If current page button is current page, then
                            // show that slighly different way. Don't add link
                            // to that coresponding page.
                            if ($pageNumber == $currentPage)
                            {
                                ?>
                                    <li><a style="border: none; background: #a55400; text-decoration: none; color: #232323; cursor: default; font-weight: bold;"><?php echo($pageNumber); ?></a></li>
                                <?php
                            }
                            // Otherwise show page button along with their links
                            else
                            {
                                ?>
                                    <li><a href="#?section=recent_tour&page=<?php echo($pageNumber); ?>" onclick="DisplayPage('recent_tour', 'contentBody', '&page=<?php echo($pageNumber); ?>');"><?php echo($pageNumber); ?></a></li>
                                <?php
                            }
                        }

                        // If current page is not the last page, then show next
                        // page button navigation.
                        if ($currentPage != count($pages))
                        {
                            ?>
                                <li><a style="margin-left: 1em;" href="#?section=recent_tour&page=<?php echo($currentPage + 1); ?>" onclick="DisplayPage('recent_tour', 'contentBody', '&page=<?php echo($currentPage + 1); ?>');">&raquo;</a></li>
                            <?php
                        }
                    }
                    // If total number of pages are more than 10
                    else
                    {
                        // Define page button representation style
                        class Positions
                        {
                            const FIRST = 0;       // 1, 2, 3, 4, 5 ....... Last
                            const MIDDLE = 1;      // First ... 1, 2, 3 ... Last
                            const LAST = 3;        // First .... 96, 97, 98 Last
                        }

                        $distanceFromFirst = $currentPage - 1;
                        $distanceFromLast = count($pages) - $currentPage;


                        $currentPosition = null;

                        // Determining page button representation style
                        if (($distanceFromFirst >= 0) && ($distanceFromFirst <= ($limitPageNav - 2)))
                        {
                            $currentPosition = Positions::FIRST;
                        }
                        elseif (($distanceFromLast >= 0) && ($distanceFromLast <= ($limitPageNav - 2)))
                        {
                            $currentPosition = Positions::LAST;
                        }
                        else
                        {
                            $currentPosition = Positions::MIDDLE;
                        }

                        // Displaying page buttons according to their styles
                        switch ($currentPosition)
                        {
                            case Positions::FIRST:          // If situation is like this: 1, 2, 3, 4, 5 ....... Last

                                // If current page is not the first page, then show
                                // previous page
                                if ($currentPage != 1)
                                {
                                    ?>
                                        <li><a style="margin-right: 1em;" href="#?section=recent_tour&page=<?php echo($currentPage - 1); ?>" onclick="DisplayPage('recent_tour', 'contentBody', '&page=<?php echo($currentPage - 1); ?>');">&laquo;</a></li>
                                    <?php
                                }
                                
                                // Show page buttons upto page 7
                                for ($pageNumber = 1; $pageNumber <= $limitPageNav; $pageNumber++)
                                {
                                    if ($pageNumber == $currentPage)
                                    {
                                        ?>
                                            <li><a style="border: none; background: #a55400; text-decoration: none; color: #232323; cursor: default; font-weight: bold;"><?php echo($pageNumber); ?></a></li>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <li><a href="#?section=recent_tour&page=<?php echo($pageNumber); ?>" onclick="DisplayPage('recent_tour', 'contentBody', '&page=<?php echo($pageNumber); ?>');"><?php echo($pageNumber); ?></a></li>
                                        <?php
                                    }
                                }

                                ?>
                                    <li> . . . . . </li>
                                    <!-- Last page button -->
                                    <li><a href="#?section=recent_tour$page=<?php echo(count($pages)); ?>" onclick="DisplayPage('recent_tour', 'contentBody', '&page=<?php echo(count($pages)); ?>');">Last</a></li>
                                    <!-- Next page button -->
                                    <li><a style="margin-left: 1em;" href="#?section=recent_tour&page=<?php echo($currentPage + 1); ?>" onclick="DisplayPage('recent_tour', 'contentBody', '&page=<?php echo($currentPage + 1); ?>');">&raquo;</a></li>
                                <?php

                                break;


                            case Positions::MIDDLE:         // First ... 1, 2, 3 ... Last
                                ?>
                                    <!-- Previous page -->
                                    <li><a style="margin-right: 1em;" href="#?section=recent_tour&page=<?php echo($currentPage - 1); ?>" onclick="DisplayPage('recent_tour', 'contentBody', '&page=<?php echo($currentPage - 1); ?>');">&laquo;</a></li>
                                    <!-- First page -->
                                    <li><a href="#?section=recent_tour" onclick="DisplayPage('recent_tour', 'contentBody', '');">First</a></li>
                                    <li> . . . . . </li>
                                <?php

                                $middleStart = $currentPage - 3;
                                $middleEnd = $currentPage + 3;

                                // Show all other pages, starting from 3 steps
                                // before current page to 3 steps after current page
                                for ($pageNumber = $middleStart; $pageNumber <= $middleEnd; $pageNumber++)
                                {
                                    // If this is our current page button, then show
                                    // that, slightly differnt and don't include
                                    // page link.
                                    if ($pageNumber == $currentPage)
                                    {
                                        ?>
                                            <li><a style="border: none; background: #a55400; text-decoration: none; color: #232323; cursor: default; font-weight: bold;"><?php echo($pageNumber); ?></a></li>
                                        <?php
                                    }
                                    // For all other page buttons include page link
                                    else
                                    {
                                        ?>
                                            <li><a href="#?section=recent_tour&page=<?php echo($pageNumber); ?>" onclick="DisplayPage('recent_tour', 'contentBody', '&page=<?php echo($pageNumber); ?>');"><?php echo($pageNumber); ?></a></li>
                                        <?php
                                    }
                                }

                                ?>
                                    <li> . . . . . </li>
                                    <!-- Last Page -->
                                    <li><a href="#?section=recent_tour&page=<?php echo(count($pages)); ?>" onclick="DisplayPage('recent_tour', 'contentBody', '&page=<?php echo(count($pages)); ?>');">Last</a></li>
                                    <!-- Next Page -->
                                    <li><a style="margin-left: 1em;" href="#?section=recent_tour&page=<?php echo($currentPage + 1); ?>" onclick="DisplayPage('recent_tour', 'contentBody', '&page=<?php echo($currentPage + 1); ?>');">&raquo;</a></li>
                                <?php

                                break;

                            case Positions::LAST:           // If situation is like this: First .... 96, 97, 98 Last

                                ?>
                                    <!-- Previous Page -->
                                    <li><a style="margin-right: 1em;" href="#?section=recent_tour&page=<?php echo($currentPage - 1); ?>" onclick="DisplayPage('recent_tour', 'contentBody', '&page=<?php echo($currentPage - 1); ?>');">&laquo;</a></li>
                                    <!-- First Page -->
                                    <li><a href="#?section=recent_tour" onclick="DisplayPage('recent_tour', 'contentBody', '&page=1');">First</a></li>
                                    <li> . . . . . </li>
                                <?php

                                $lastStart = count($pages) - $limitPageNav;
                                $lastEnd = count($pages);

                                // Show all other pages starts from 7 steps before
                                // from last page to last page.
                                for ($pageNumber = $lastStart; $pageNumber <= $lastEnd; $pageNumber++)
                                {
                                    // If this is our current page, then show that
                                    // page button slightly different way and don't
                                    // include page link.
                                    if ($pageNumber == $currentPage)
                                    {
                                        ?>
                                            <li><a style="border: none; background: #a55400; text-decoration: none; color: #232323; cursor: default; font-weight: bold;"><?php echo($pageNumber); ?></a></li>
                                        <?php
                                    }
                                    // Otherwise, show page button along with
                                    // page link
                                    else
                                    {
                                        ?>
                                            <li><a href="#?section=recent_tour&page=<?php echo($pageNumber); ?>" onclick="DisplayPage('recent_tour', 'contentBody', '&page=<?php echo($pageNumber); ?>');"><?php echo($pageNumber); ?></a></li>
                                        <?php
                                    }
                                }

                                // If current page is not the last one, then show
                                // the last page button.
                                if ($currentPage != $lastEnd)
                                {
                                    ?>
                                        <li><a style="margin-left: 1em;" href="#?section=recent_tour&page=<?php echo($currentPage + 1); ?>" onclick="DisplayPage('recent_tour', 'contentBody', '&page=<?php echo($currentPage + 1); ?>');">&raquo;</a></li>
                                    <?php
                                }

                                break;
                        }
                    }
                }
            ?>
        </ul>
        
    </nav>
    <?php
}


/**  FUNCTION NAME:  DisplayArticle
 *   PARAMETER:      (list) blogs
 *   RETURN:         None
 *   
 *   PURPOSE:        To display all the articles that was given by the parameter
 **/
function DisplayArticle($blogs)
{
    ?>
    <section id="mainContent">
        <header class="otherContent">
            <h2 class="hidden">Main Content</h2>
        </header>
    <?php
    
    foreach ($blogs as $blog)
    {
        // Replacing unexpected characters
        $title = $blog["title"];
        $content = $blog["content"];
        $content = str_replace("&#34;", '"', $content);
        $content = str_replace("&#39;", "'", $content);
        
        // Show only preview area (recommended), if not, then show the full article (not recommended)
        $content = explode(Settings::$PREVIEW_TAG, $content);
        $content = $content[0];
        

        ?>

        <article itemscope itemtype="http://schema.org/Article">
            <header>
                <!-- Title -->
                <h2 itemprop="headline"><a href="#?section=blog_content_<?php echo($blog["blog_id"]); ?>" onclick='DisplayPage("blog_content", "contentBody", "content_id=" + <?php echo($blog["blog_id"]); ?>);'><?php echo($title); ?></a></h2>

                <!-- Publication Date -->
                <div class="contentInfo" itemprop="datePublished">
                    <b>Published at: </b><time datetime="<?php echo($blog["published"]); ?>"><?php echo($blog["published"]); ?></time><br/>
                </div>
            </header>

            
            <!-- Preview of Article -->
            <div class="content" itemprop="about">
                <?php echo($content); ?>
                <span class="continueArticle"><br/><br/><a itemprop="url" href="#?section=blog_content_<?php echo($blog["blog_id"]); ?>" onclick='DisplayPage("blog_content", "contentBody", "content_id=" + <?php echo($blog["blog_id"]); ?>);'>Tell me more...</a></span>
            </div>
        </article>

        <?php
    }
    
    ?>
    </section>    
    <?php
}
?>