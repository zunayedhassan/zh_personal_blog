<?php
/**
 * FILE NAME:       blog_content.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            October 04, 2013
 * LAST EDITED:     October 13, 2013 05:39 PM
 * 
 * PURPOSE:         1. Retrieve data from blog table on database
 *                  2. Show that particular article that user requested.
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/
?>

<section id="mainContent">
    <header class="otherContent">
        <h2 class="hidden">Main Content</h2>
    </header>
    
    <!-- External stylesheet for blog_content page -->
    <link rel="stylesheet" href='stylesheets/other_style.css'>
    
    <!--
    FILE NAME:      flex_style.css
    DEPENDENCY FOR: jquery.flex.js

    PURPOSE:        Show photo gallery (if exist) in rows and make white borders
                    around pictures.
    -->
    <link rel="stylesheet" href="stylesheets/flex_style.css">
    
    <style type="text/css">
        /* Show Facebook, Twitter and Google Plus button in a single line */
        .contentInfo div {
            display: inline-block;
        }
    </style>
    
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
    
<?php
// Importing required library
include("./application/settings.php");
include("./application/database.php");
include("./application/common_tools.php");

// If user tries to open http://<your_page_name>/blog_content.php?content_id=123
// directly, then redirect to http://<your_page_name>/index.php and open as
// http://<your_page_name>/index.php#?section=blog_content_123, otherwise
// continue. Searching for '&_'
$uriInfo = $_SERVER['REQUEST_URI'];
$uriInfo = explode("&_", $uriInfo);

if (count($uriInfo) <= 1)
{
    // Searcing for '?'
    $uriInfo = explode("?", $uriInfo[0]);
    
    if (count($uriInfo) > 1)
    {
        $uriInfo = $uriInfo[1];
        
        // Searching '='
        $uriInfo = explode("=", $uriInfo);
        
        if (count($uriInfo) > 1)
        {
            $uriInfo = $uriInfo[1];
            
            /* Redirect to a different page in the current directory that was requested */
            $host  = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php#?section=blog_content_' . $uriInfo;

            echo("http://$host$uri/$extra");
            header("Location: http://$host$uri/$extra");
            exit;
        }
    }
}

// Create new database connection
$db = new Database();

// If user mentioned any blog_id then, show that article
if (isset($_GET["content_id"]))
{
    $blogId = $_GET["content_id"];
    
    // Query for getting the particular article which user was requested.
    $blogContent = $db->GetQueryResult("SELECT * FROM blog WHERE blog_id=" . $blogId . ";", true);
    
    // This page also able to navigate to next blog and previous blog. To do
    // that, we also need the information about previous blog id and next blog
    // id.
    
    // Query for getting all the blog_ids from blog table by reveresly
    // publishing order.
    $blogIdIndex = $db->GetQueryResult("SELECT blog_id FROM blog ORDER BY published DESC;");
    
    $prevId = null;
    $nextId = null;
    $found = FALSE;
    
    // Searching for next blog id and previous blog id.
    foreach ($blogIdIndex as $id)
    {
        if ($id["blog_id"] == $blogId)
        {
            $found = TRUE;
        }
        
        if (!$found)
        {
            $prevId = $id["blog_id"];
        }
        else if ($found and ($nextId == null) and ($id["blog_id"] != $blogId))
        {
            $nextId = $id["blog_id"];
        }
        
        if ($found and $nextId != null)
        {
            break;
        }
    }
    
    if ($found)
    {
        // If previous blog id found then make visible of previous blog
        // navigator and add link of previous article
        if ($prevId != null)
        {
            ?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#blogNavigationLeft")
                                .unbind("click")
                                .fadeIn()
                                .click(function(event) {
                                    DisplayPage("blog_content", "contentBody", "content_id=" + <?php echo($prevId); ?>);
                                });
                    });
                </script>
            <?php
        }
        
        // If next blog id found then make visible of next blog
        // navigator and add link of next article
        if ($nextId != null)
        {
            ?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#blogNavigationRight")
                                .unbind("click")
                                .fadeIn()
                                .click(function(event) {
                                    DisplayPage("blog_content", "contentBody", "content_id=" + <?php echo($nextId); ?>);
                                });
                    });
                </script>
            <?php
        }
    }
    
    // If user is visiting through pages of article one by one, and if user
    // reached at the first article, then remove the previous article.
    if ($prevId == null)
    {
        ?>
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#blogNavigationLeft").fadeOut();
                });
            </script>
        <?php
    }
    
    // Or, if user is reached to the last article and there is no more next
    // article, then remove next article
    if ($nextId == null)
    {
        ?>
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#blogNavigationRight").fadeOut();
                });
            </script>
        <?php
    }
    
    // If user requested for an article which doesn't exist in the database,
    // then show the error message and pretend to be sorry.
    if ($blogContent["blog_id"] != $blogId)
    {
        ?>    
        <article class="errorStyle">
            <header>
                <h2>We are really sorry, :-(</h2>
            </header>

            <div class='content'>
                <p>
                    The content you are looking for is no longer exist.
                </p>
            </div>
        </article>
        <?php
    }
    // Otherwise, everything is perfect and show the article
    else
    {
        $title = $blogContent["title"];
        
        // Replace unwanted characters
        $content = $blogContent["content"];
        $content = str_replace("&#34;", '"', $content);
        $content = str_replace("&#39;", "'", $content);
        $content = str_replace(Settings::$PREVIEW_TAG, " ", $content);         // Removing preview tag from content
        $tags = $blogContent["tag"];
        
        // If there are some tags, then pick them
        if ($tags !== "")
        {
            $tags = explode(",", $tags);
        }
        
        ?>
            <script type="text/javascript">
            // Facebook Library
            window.fbAsyncInit = function() {
                // init the FB JS SDK
                FB.init({
                    appId      : '524000594354392',                    // App ID from the app dashboard
                    channelUrl : '//zunayedhassan.3owl.com',           // Channel file for x-domain comms
                    status     : true,                                 // Check Facebook Login status
                    xfbml      : true                                  // Look for social plugins on the page
                });

                // Additional initialization code such as adding Event Listeners goes here
            };

            // Load the SDK asynchronously
            (function(d, s, id){
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {return;}
                js = d.createElement(s); js.id = id;
                js.src = "//connect.facebook.net/en_US/all.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>

        <?php
            $currentBlogTitleForPageHeader = $blogContent["title"];
            $currentBlogTitleForPageHeader = str_replace("&#34;", '"', $currentBlogTitleForPageHeader);
            $currentBlogTitleForPageHeader = str_replace("&#39;", "'", $currentBlogTitleForPageHeader);
        ?>
        
        <script type="text/javascript">
            // Change the page title according to the article name
            document.title = "<?php echo($currentBlogTitleForPageHeader); ?>: Zunayed Hassan's Personal Blog";
        </script>
        
        <!-- Article -->
        <article itemscope itemtype="http://schema.org/Article">
            <header>
                <!-- Title -->
                <h2 itemprop="headline"><?php echo($title); ?></h2>
                
                <div class="contentInfo" itemprop="datePublished">
                    <!-- Publication Date -->
                    <b>Published at: </b><time datetime="<?php echo($blogContent["published"]); ?>"><?php echo($blogContent["published"]); ?></time><br/>
                    
                    <!-- Facebook -->
                    <div class="fb-like" data-href="<?php echo("https://zunayedhassan.3owl.com/index.php#?section=blog_content_" . $blogId); ?>" data-width="The pixel width of the plugin" data-height="The pixel height of the plugin" data-colorscheme="dark" data-layout="button_count" data-action="like" data-show-faces="false" data-send="false"></div>
                    
                    <!-- Twitter -->
                    <div>
                        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://zunayedhassan.3owl.com/index.php#?section=blog_content_<?php echo($blogContent["blog_id"]); ?>" data-via="zunayedhassan">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
        
                        <script src="http://platform.twitter.com/widgets.js" type="text/javascript">
                            if(typeof(twttr) !== 'undefined') {
                                $('#twlikenode0').attr('href','http://twitter.com/share');
                                $('#twlikenode0').attr('class','twitter-share-button');
                                $('#twlikenode0').attr('data-count','vertical');
                                $('#twlikenode0').attr('data-url',document.location.href);
                                twttr.widgets.load();
                             }
                        </script>
                    </div>
                    
                    <!-- Google Plus -->
                    <!-- Place this tag where you want the +1 button to render. -->
                    <div class="g-plusone" data-size="medium" data-href="http://zunayedhassan.3owl.com/index.php#?section=blog_content_<?php echo($blogContent["blog_id"]); ?>"></div>

                    <!-- Place this tag after the last +1 button tag. -->
                    <script type="text/javascript">
                      (function() {
                        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                        po.src = 'https://apis.google.com/js/plusone.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                      })();
                    </script>
                </div>
            </header>
            
            <!-- Article Content -->
            <div class='content' itemprop="text">
                <?php echo($content); ?>
            </div>
            
            <!-- Tags -->
            <div id="tagsArea" itemprop="keywords">                
                <?php
                    // If tags exist
                    if ($tags != "")
                    {
                        // Show tag icon
                        ?>
                            <div style="display: inline-block; margin-top: -1em; width: 22px; height: 22px; background: url('icons/tag.png') no-repeat;"></div>
                        <?php
                        
                        // Show every tag and their link
                        foreach ($tags as $tag)
                        {
                            $tag = trim($tag);
                            
                            ?>
                            <div><a href="#?section=search&keyword=<?php echo(htmlspecialchars($tag)); ?>" onclick="DisplayPage('search', 'contentBody', 'keyword=<?php echo(htmlspecialchars($tag)); ?>')"><?php echo($tag); ?></a></div>
                            <?php
                        }
                    }
                ?>
            </div>
            
            <br/><br/>
        
            <!-- Facebook comment area -->
            <section id="fbComment" itemprop="comment">
                <div id="fb-root"></div>
                <script>
                    (function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=524000594354392";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));

                    if (typeof(FB) != 'undefined' && FB != null ) {
                        FB.XFBML.parse();
                    }
                </script>

                <div class="fb-comments" data-href="<?php echo("https://zunayedhassan.3owl.com/index.php#?section=blog_content_" . $blogId); ?>" data-colorscheme="dark" data-width="470"></div>
            </section>
        </article>
        <?php
    }
}
?>
    
</section>

