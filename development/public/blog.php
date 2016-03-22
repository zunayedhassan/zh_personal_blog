<?php
/**
 * FILE NAME:       blog.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            October 04, 2013
 * LAST EDITED:     October 13, 2013 03:46 PM
 * 
 * PURPOSE:         1. Retrieve data from blog table on database
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

// If user tries to open http://<your_page_name>/blog.php directly, then
// redirect to http://<your_page_name>/index.php and open as
// http://<your_page_name>/index.php#?section=blog, otherwise continue.
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
            $uriInfo = 'index.php#?section=blog';
        }
        else                                // It must have query string
        {            
            $uriInfo = explode("page=", $uriInfo[1]);
            $uriInfo = "index.php#?section=page_" . $uriInfo[1];
        }
        
        /* Redirect to a different page in the current directory that was requested */
        $host  = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = $uriInfo;

        echo("http://$host$uri/$extra");
        header("Location: http://$host$uri/$extra");
        exit;
    }
}

// Create new database connection
$db = new Database();
$blogs = null;

// Query for all blog_ids from blog table and sort them by published and get
// result as reversed order from database
$blogIds = $db->GetQueryResult("SELECT blog_id FROM blog ORDER BY published DESC;");

// Get total number of blog_ids (or total number of rows) and get as single
// result
$totalNumberOfRows = $db->GetQueryResult("SELECT count(*) as total_rows FROM blog;", true);
$totalNumberOfRows = $totalNumberOfRows["total_rows"];

// Get a list of pages where in every page contains particular blog ids based
// on when publised as reversed order.
$pages = GetContentForPages($totalNumberOfRows, $blogIds);

// If user doesn't request to view a particular page
if (!isset($_GET["page"]))
{
    // If total number of articles are less than or equal to 5, which means
    // we can show all result in first page.
    if ($totalNumberOfRows <= Settings::$MAX_BLOG_CONTENT_SHOW_PER_PAGE)
    {
        // Query for getting all the article from blog table according to
        // publication date as reverse order.
        $blogs = $db->GetQueryResult("SELECT * FROM blog ORDER BY published DESC;");
    }
    // Otherwise, show only first page.
    else
    {
        // Get only those articles which most new and eligable to show at the
        // first page
        $blogs = GetContentsFromPage($pages, 1);
    }
}
// If user mentioned page number
else
{
    // Get artcles from the page which user was requested.
    $blogs = GetContentsFromPage($pages, $_GET["page"]);
}

?>

<!-- External stylesheets for blog page -->
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
    
    // Changing page title
    document.title = "Zunayed Hassan's Personal Blog";
</script>


<section id="mainContent" style="min-height: 40%;">
    <header class="otherContent">
        <h2 class="hidden">Main Content</h2>
    </header>
    
<?php
// If there is at least 1 article then, show whatever articles are meant to be
// seen
if (($totalNumberOfRows > 0) and ($blogs != null))
{
    // For every blog get article title, publishing date, content and show them
    // accordingly
    foreach ($blogs as $blog)
    {
        $title = $blog["title"];
        
        // If content has any unexpected character (for browser), replace them
        $content = $blog["content"];
        $content = str_replace("&#34;", '"', $content);
        $content = str_replace("&#39;", "'", $content);
        
        // Show only preview area (recommended), if not, then show the full article (not recommended)
        $content = explode(Settings::$PREVIEW_TAG, $content);
        $content = $content[0];
        
        ?>

        <!-- Article -->
        <article itemscope itemtype="http://schema.org/Article">
            <header>
                <!-- Article Title -->
                <h2 itemprop="headline"><a href="#?section=blog_content_<?php echo($blog["blog_id"]); ?>" onclick='DisplayPage("blog_content", "contentBody", "content_id=" + <?php echo($blog["blog_id"]); ?>);'><?php echo($title); ?></a></h2>

                <!-- Article Publishing Date -->
                <div class="contentInfo" itemprop="datePublished">
                    <b>Published at: </b><time datetime="<?php echo($blog["published"]); ?>"><?php echo($blog["published"]); ?></time><br/>
                </div>
            </header>

            <!-- Preview of Article Content -->
            <div class="content" itemprop="about">
                <?php echo($content); ?><br/><br/>
                
                <!-- Show more details link -->
                <span class="continueArticle"><a itemprop="url" href="#?section=blog_content_<?php echo($blog["blog_id"]); ?>" onclick='DisplayPage("blog_content", "contentBody", "content_id=" + <?php echo($blog["blog_id"]); ?>);'>Tell me more...</a></span>
            </div>
        </article>

        <?php
    }
}
// If blog is full of article but, user requested a wrong page, then show
// error message.
else if (($totalNumberOfRows > 0) and ($blogs == null))
{
    ?>
    
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
    
    <?php
}
// If there isn't any article, then show message of no article and be sorry
// for that.
else
{
    ?>
    
    <article class='errorStyle'>
        <header>
            <h2>Sorry, there is no article... :'(</h2>
        </header>

        <div class="content">
            <p>
                It seems there isn't any article on this site. I'm really really sorry for that.<br/>Please, try another time.
            </p>
        </div>
    </article>
    
    <?php
}
?>
</section>

<!-- Page navigation -->
<?php
// If there is at least on blog on the database and total number of pages are
// more than 1 then, show page navigation
if(($blogs != null) and count($pages) > 1)
{
    ?>
    <nav id="pageNavigation">
        <header class="otherContent">
            <h2 class="hidden">Page Navigation</h2>
        </header>

        <ul>
            <?php
                $currentPage = null;
                $limitPageNav = 7;                  // User can view total 7 page button at a time
            
                if (isset($_GET["page"]))
                {
                    $currentPage = $_GET["page"];   // If user mentioned any page then current page will be that page number.
                }
                
                // If user didn't mention any page then, current page will be page 1
                $currentPage = ($currentPage == null) ? 1 : $currentPage;
                $totalPages = count($pages);        // Total number of pages
                
                // If total number of pages are at best 10, then show all the
                // page buttons, no hiding.
                if ($totalPages <= ($limitPageNav + 3))
                {
                    // If page 1 is not the current page, then show previous
                    // page button.
                    if ($currentPage != 1)
                    {
                        ?>
                            <li><a style="margin-right: 1em;" href="#?section=page_<?php echo($currentPage - 1); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo($currentPage - 1); ?>');">&laquo;</a></li>
                        <?php
                    }
                    
                    // Show all the page buttons and their numbers
                    for ($pageNumber = 1; $pageNumber <= count($pages); $pageNumber++)
                    {
                        // If this is our current page button, then show that
                        // page button slightly different way (no page links).
                        if ($pageNumber == $currentPage)
                        {
                            ?>
                                <li><a style="border: none; background: #a55400; text-decoration: none; color: #232323; cursor: default; font-weight: bold;"><?php echo($pageNumber); ?></a></li>
                            <?php
                        }
                        // Otherwise, show page button with number along with
                        // their link.
                        else
                        {
                            ?>
                                <li><a href="#?section=page_<?php echo($pageNumber); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo($pageNumber); ?>');"><?php echo($pageNumber); ?></a></li>
                            <?php
                        }
                    }
                    
                    // If current page is not the last page, then show next page
                    // button
                    if ($currentPage != count($pages))
                    {
                        ?>
                            <li><a style="margin-left: 1em;" href="#?section=page_<?php echo($currentPage + 1); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo($currentPage + 1); ?>');">&raquo;</a></li>
                        <?php
                    }
                }
                // If total number of pages are more than 10, then show some
                // initial page buttons and also show the last page buttons.
                else
                {
                    // Define page button representation style
                    class Positions
                    {
                        const FIRST = 0;        // 1, 2, 3, 4, 5 ....... Last
                        const MIDDLE = 1;       // First ... 1, 2, 3 ... Last
                        const LAST = 3;         // First .... 96, 97, 98 Last
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
                                    <li><a style="margin-right: 1em;" href="#?section=page_<?php echo($currentPage - 1); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo($currentPage - 1); ?>');">&laquo;</a></li>
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
                                        <li><a href="#?section=page_<?php echo($pageNumber); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo($pageNumber); ?>');"><?php echo($pageNumber); ?></a></li>
                                    <?php
                                }
                            }

                            ?>
                                <li> . . . . . </li>
                                <!-- Last page button -->
                                <li><a href="#?section=page_<?php echo(count($pages)); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo(count($pages)); ?>');">Last</a></li>
                                <!-- Next page button -->
                                <li><a style="margin-left: 1em;" href="#?section=page_<?php echo($currentPage + 1); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo($currentPage + 1); ?>');">&raquo;</a></li>
                            <?php

                            break;
                            
                    
                        case Positions::MIDDLE:         // First ... 1, 2, 3 ... Last
                            ?>
                                <!-- Previous page -->
                                <li><a style="margin-right: 1em;" href="#?section=page_<?php echo($currentPage - 1); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo($currentPage - 1); ?>');">&laquo;</a></li>
                                <!-- First page -->
                                <li><a href="#?section=page_1" onclick="DisplayPage('blog', 'contentBody', 'page=1');">First</a></li>
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
                                        <li><a href="#?section=page_<?php echo($pageNumber); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo($pageNumber); ?>');"><?php echo($pageNumber); ?></a></li>
                                    <?php
                                }
                            }
                            
                            ?>
                                <li> . . . . . </li>
                                <!-- Last Page -->
                                <li><a href="#?section=page_<?php echo(count($pages)); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo(count($pages)); ?>');">Last</a></li>
                                <!-- Next Page -->
                                <li><a style="margin-left: 1em;" href="#?section=page_<?php echo($currentPage + 1); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo($currentPage + 1); ?>');">&raquo;</a></li>
                            <?php
                            
                            break;
                            
                        case Positions::LAST:       // If situation is like this: First .... 96, 97, 98 Last
                            
                            ?>
                                <!-- Previous Page -->
                                <li><a style="margin-right: 1em;" href="#?section=page_<?php echo($currentPage - 1); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo($currentPage - 1); ?>');">&laquo;</a></li>
                                <!-- First Page -->
                                <li><a href="#?section=page_1" onclick="DisplayPage('blog', 'contentBody', 'page=1');">First</a></li>
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
                                        <li><a href="#?section=page_<?php echo($pageNumber); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo($pageNumber); ?>');"><?php echo($pageNumber); ?></a></li>
                                    <?php
                                }
                            }
                            
                            // If current page is not the last one, then show
                            // the last page button.
                            if ($currentPage != $lastEnd)
                            {
                                ?>
                                    <li><a style="margin-left: 1em;" href="#?section=page_<?php echo($currentPage + 1); ?>" onclick="DisplayPage('blog', 'contentBody', 'page=<?php echo($currentPage + 1); ?>');">&raquo;</a></li>
                                <?php
                            }
                            
                            break;
                    }
                }
            ?>
        </ul>
    </nav>
    <?php
}

?>