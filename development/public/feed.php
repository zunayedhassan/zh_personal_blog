<?php
/**
 * FILE NAME:       feed.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            October 13, 2013
 * LAST EDITED:     October 14, 2013 10:57 AM
 * 
 * PURPOSE:         Show RSS feeds
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/

    header("Content-Type: application/rss+xml; charset=utf-8");
    
    // Importing required library
    include("./application/settings.php");
    include("./application/database.php");
    
    // Gettings website address, this will require for adding link on each
    // RSS item.
    $host  = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $siteAddress = $host . $uri;
    
    $rssfeed = '<?xml version="1.0" encoding="ISO-8859-1"?>';
    $rssfeed .= '<rss version="2.0">';
    $rssfeed .= '<channel>';
    $rssfeed .= '<title>Zunayed Hassan\'s Personal Blog</title>';
    $rssfeed .= '<link>http://' . $siteAddress . '</link>';
    $rssfeed .= '<description>Personal website of Mohammod Zunayed Hassan</description>';
    $rssfeed .= '<language>en-us</language>';
    $rssfeed .= '<copyright>Copyright (C) 2013 ' . $siteAddress . '</copyright>';
    
    // Creating database connection
    $db = new Database();
    // Get all the article from blog table and arrange them in reversly sorted
    // order based on publication date.
    $blogs = $db->GetQueryResult("SELECT * FROM blog ORDER BY published DESC;");
    
    // Adding article item on RSS
    foreach ($blogs as $blog)
    {
        // Removing unexpected characters
        $title = $blog["title"];
        
        $description = $blog["content"];
        $description = str_replace("&#34;", '"', $description);
        $description = str_replace("&#39;", "'", $description);
        
        // Show only preview area (recommended), if not, then show the full article (not recommended)
        $description = explode(Settings::$PREVIEW_TAG, $description);
        $description = $description[0];
        
        $link = 'http://' . $siteAddress . '/blog_content.php?content_id=' . $blog["blog_id"];
        
        $pubDate = $blog["published"];
        
        $rssfeed .= '<item>';
        $rssfeed .= '<title>' . $title . '</title>';
        $rssfeed .= '<description>' . $description . '</description>';
        $rssfeed .= '<link>' . $link . '</link>';
        $rssfeed .= '<pubDate>' . $pubDate. '</pubDate>';
        $rssfeed .= '</item>';
    }
    
    $rssfeed .= '</channel>';
    $rssfeed .= '</rss>';
    
    // Show RSS feed.
    echo($rssfeed);
?>
