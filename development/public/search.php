<?php
/**
 * FILE NAME:       search.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            October 09, 2013
 * LAST EDITED:     October 14, 2013 02:26 PM
 * 
 * PURPOSE:         To search and display the search results
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/

// Importing library
include("./application/settings.php");
include("./application/database.php");
include("./application/common_tools.php");

// If user tries to open http://<your_page_name>/search.php?keyword=<something>
// directly, then redirect to http://<your_page_name>/index.php and open as
// http://<your_page_name>/index.php#?section=search&keyword=<something>,
// otherwise continue.
$uriInfo = explode("&_", $_SERVER['REQUEST_URI']);

if (count($uriInfo) <= 1)
{
    $uriInfo = explode("search.php?", $uriInfo[0]);
    
    if (count($uriInfo) > 1)
    {
        $uriInfo = '#?section=search&' . $uriInfo[1];
    }
    else
    {
        $uriInfo = "";
    }
    
    /* Redirect to a different page in the current directory that was requested */
    $host  = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'index.php' . $uriInfo;
    header("Location: http://$host$uri/$extra");
    exit;
}

/**  FUNCTION NAME:  DisplaySearchResults
 *   PARAMETER:      (list) searchResults
 *   RETURN:         None
 *   
 *   PURPOSE:        To display all the search results that was given by
 *                   the parameter
 **/
function DisplaySearchResults($searchResults)
{
    foreach ($searchResults as $blog)
    {
        // Removing unexpected characters
        $title = $blog["title"];
        $content = $blog["content"];
        $content = str_replace("&#34;", '"', $content);
        $content = str_replace("&#39;", "'", $content);

        // Show only preview area (recommended), if not, then show the full article (not recommended)
        $content = explode(Settings::$PREVIEW_TAG, $content);
        $content = $content[0];

        ?>

        <article>
            <header>
                <!-- Title -->
                <h2 itemprop="headline"><a href="#?section=blog_content_<?php echo($blog["blog_id"]); ?>" onclick='DisplayPage("blog_content", "contentBody", "content_id=" + <?php echo($blog["blog_id"]); ?>);'><?php echo($title); ?></a></h2>

                <!-- Publication Date -->
                <div class="contentInfo" itemprop="datePublished">
                    <b>Published at: </b><time datetime="<?php echo($blog["published"]); ?>"><?php echo($blog["published"]); ?></time><br/>
                </div>
            </header>

            <!-- Article Preview -->
            <div class="content" itemprop="about">
                <?php echo($content); ?>
                <span class="continueArticle"><br/><br/><a itemprop="url" href="#?section=blog_content_<?php echo($blog["blog_id"]); ?>" onclick='DisplayPage("blog_content", "contentBody", "content_id=" + <?php echo($blog["blog_id"]); ?>);'>Tell me more...</a></span>
            </div>
        </article>

        <?php
    }
}

// Creating new database connection
$db = new Database();
$totalSearchResults = null;
$pages = null;

?>

<!-- External Stylesheets -->
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
        // By default blog navigation will be hidden
        $("#blogNavigationLeft").fadeOut();
        $("#blogNavigationRight").fadeOut();
        
        // Changing some styles for Internet Explorer
        if (navigator.userAgent.toLowerCase().indexOf('msie') > -1) {
            $("article").css({
                "margin-top": "-2em",
                "margin-bottom": "4em"
            });
        }
    });
</script>

<section id="mainContent" style="min-height: 40%;" itemscope itemtype="http://schema.org/SearchResultsPage">
    <header class="otherContent">
        <h2 class="hidden">Main Content</h2>
    </header>

<?php

// If user mentioned any keyword, then search for that
if (isset($_GET["keyword"]))
{
    // Removing unexpected characters
    $keyword = htmlspecialchars($_GET["keyword"]);
    $keyword = str_replace('"', '&#34;', $keyword);
    $keyword = str_replace("'", "&#39;", $keyword);
    
    // Get the number of total searches
    $totalSearchResults = $db->GetQueryResult("SELECT count(*) as total_results FROM blog WHERE title LIKE '%" . $keyword . "%' OR content LIKE '%" . $keyword . "%' OR tag LIKE '%" . $keyword . "%' ORDER BY published DESC;", true);
    $totalSearchResults = $totalSearchResults["total_results"];
    
    ?>
    <script type="text/javascript">
        // Change the page title
        document.title = "Search Result for <?php echo($_GET["keyword"]); ?>: Zunayed Hassan's Personal Blog";
    </script>
    <?php
    
    // If at least one search result found, display them
    if ($totalSearchResults > 0)
    {
        // If total search results can be display on a single page (less than
        // or equal to 5), then display them all
        if ($totalSearchResults <= Settings::$MAX_BLOG_CONTENT_SHOW_PER_PAGE)
        {
            $searchResults = $db->GetQueryResult("SELECT * FROM blog WHERE title LIKE '%" . $keyword . "%' OR content LIKE '%" . $keyword . "%' OR tag LIKE '%" . $keyword . "%' ORDER BY published DESC;");
            
            DisplaySearchResults($searchResults);
        }
        // Otherwise, total number of search results are more than 1 page,
        // so show a particular page, and link the other pages with page buttons
        else
        {
            // Get ids of which results are needed to show as search result
            $blogIds = $db->GetQueryResult("SELECT blog_id FROM blog WHERE title LIKE '%" . $keyword . "%' OR content LIKE '%" . $keyword . "%' OR tag LIKE '%" . $keyword . "%' ORDER BY published DESC;");
            
            // Now organize the pages, which means get a list of which page
            // would show which article (defined by blog id(s))
            $pages = GetContentForPages($totalSearchResults, $blogIds);
            
            // If user didn't mention anything about page, then show page 1
            if (!isset($_GET["page"]))
            {
                $searchResults = GetContentsFromPage($pages, 1);
                DisplaySearchResults($searchResults);
            }
            // Otherwise, show the page that, user wanted to see.
            else
            {
                $pageNumber = $_GET["page"];
                
                // If the page user requested is within the total number of
                // total pages then, the requested page number is correct.
                // Display that page.
                if ($pageNumber <= count($pages))
                {
                    $searchResults = GetContentsFromPage($pages, $pageNumber);
                    DisplaySearchResults($searchResults);
                }
                // Otherwise, the requested page number is invalid, show error
                // message for that.
                else
                {
                    ?>
                    <article class='errorStyle'>
                        <header>
                            <h2>Sorry, page not found... :'(</h2>
                        </header>

                        <div class="content">
                            <p>The page you are looking for isn't exist</p>
                        </div>
                    </article>
                    <?php
                }
            }
        }
    }
    // Otherwise, show that, nothing was found
    else
    {
        ?>
        <article class='errorStyle'>
            <header>
                <h2>... nothing :'(</h2>
            </header>
            
            <div class="content">
                <p>We looked everywhere, perhaps you can try with different keyword.</p>
            </div>
        </article>
        <?php
    }
}


?>

</section>

<?php
// If there is more than 1 page to display
if(($totalSearchResults > 0) and (count($pages) > 1))
{
    ?>
    <nav id="pageNavigation">
        <header class="otherContent">
            <h2 class="hidden">Page Navigation</h2>
        </header>

        <ul>
            <?php
                $currentPage = null;
                $limitPageNav = 7;
            
                // Get the number of current page
                if (isset($_GET["page"]))
                {
                    $currentPage = $_GET["page"];
                }
                
                // If page number isn't mentioned then, current page will be
                // page number 1
                $currentPage = ($currentPage == null) ? 1 : $currentPage;
                
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
                            <li><a style="margin-right: 1em;" href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage - 1); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage - 1); ?>');">&laquo;</a></li>
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
                                <li><a href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo($pageNumber); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo($pageNumber); ?>');"><?php echo($pageNumber); ?></a></li>
                            <?php
                        }
                    }
                    
                    // If current page is not the last page, then show next
                    // page button navigation.
                    if ($currentPage != count($pages))
                    {
                        ?>
                            <li><a style="margin-left: 1em;" href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage + 1); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage + 1); ?>');">&raquo;</a></li>
                        <?php
                    }
                }
                // If total number of pages are more than 10
                else
                {
                    // Define page button representation style
                    class Positions
                    {
                        const FIRST = 0;           // 1, 2, 3, 4, 5 ....... Last
                        const MIDDLE = 1;          // First ... 1, 2, 3 ... Last
                        const LAST = 3;            // First .... 96, 97, 98 Last
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
                                    <li><a style="margin-right: 1em;" href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage - 1); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage - 1); ?>');">&laquo;</a></li>
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
                                        <li><a href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo($pageNumber); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo($pageNumber); ?>');"><?php echo($pageNumber); ?></a></li>
                                    <?php
                                }
                            }

                            ?>
                                <li> . . . . . </li>
                                <!-- Last page button -->
                                <li><a href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo(count($pages)); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo(count($pages)); ?>');">Last</a></li>
                                <!-- Next page button -->
                                <li><a style="margin-left: 1em;" href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage + 1); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage + 1); ?>');">&raquo;</a></li>
                            <?php

                            break;
                            
                    
                        case Positions::MIDDLE:             // First ... 1, 2, 3 ... Last
                            ?>
                                <!-- Previous page -->
                                <li><a style="margin-right: 1em;" href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage - 1); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage - 1); ?>');">&laquo;</a></li>
                                <!-- First page -->
                                <li><a href="#?section=search&keyword=<?php echo($keyword); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>');">First</a></li>
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
                                        <li><a href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo($pageNumber); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo($pageNumber); ?>');"><?php echo($pageNumber); ?></a></li>
                                    <?php
                                }
                            }
                            
                            ?>
                                <li> . . . . . </li>
                                <!-- Last Page -->
                                <li><a href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo(count($pages)); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo(count($pages)); ?>');">Last</a></li>
                                <!-- Next Page -->
                                <li><a style="margin-left: 1em;" href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage + 1); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage + 1); ?>');">&raquo;</a></li>
                            <?php
                            
                            break;
                            
                        case Positions::LAST:       // If situation is like this: First .... 96, 97, 98 Last
                            
                            ?>
                                <!-- Previous Page -->
                                <li><a style="margin-right: 1em;" href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage - 1); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage - 1); ?>');">&laquo;</a></li>
                                <!-- First Page -->
                                <li><a href="#?section=search&keyword=<?php echo($keyword); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=1');">First</a></li>
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
                                        <li><a href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo($pageNumber); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo($pageNumber); ?>');"><?php echo($pageNumber); ?></a></li>
                                    <?php
                                }
                            }
                            
                            // If current page is not the last one, then show
                            // the last page button.
                            if ($currentPage != $lastEnd)
                            {
                                ?>
                                    <li><a style="margin-left: 1em;" href="#?section=search&keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage + 1); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo($keyword); ?>&page=<?php echo($currentPage + 1); ?>');">&raquo;</a></li>
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