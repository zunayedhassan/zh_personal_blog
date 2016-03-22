<?php

/**
 * FILE NAME:       configure_blog_table_view.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            October 03, 2013
 * LAST EDITED:     October 14, 2013 10:01 AM
 * 
 * PURPOSE:         1. To get data from database and show them in a table.
 *                  2. Also can save, delete, update and refresh articles if
 *                     needed.
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/

// Importing required library
include("./application/settings.php");
include("./application/database.php");

// Starting session
session_start();

// Creating new database connection
$db = new Database();

// If user is logged in, then only allow to edit data (like update, delete,
// save)
if (isset($_SESSION["logged_in"]) and $_SESSION["logged_in"])
{
    // If user wants to delete
    // Delete
    if (isset($_GET["command"]) and ($_GET["command"] == "delete") and isset($_GET["blog_post"]))
    {
        $blogPosts = preg_split("/\,/", $_GET["blog_post"]);
    
        if (count($blogPosts) > 0)
        {
            $blogDeletionQuery = "";

            for ($i= 0; $i < count($blogPosts) - 1; $i++)
            {
                $blogDeletionQuery .= "DELETE FROM blog WHERE blog_id = " . $blogPosts[$i] . ";";
            }

            $db->GetQueryResult($blogDeletionQuery);
        }
    }
    // If user wants to save
    // Save
    else if (isset($_POST["command"]) and ($_POST["command"] == "save") and isset($_POST["blog_title"]) and isset($_POST["blog_content"]) and isset($_POST["blog_tags"]))
    {
        $title = ChangeEscapeValues($_POST["blog_title"]);
        
        $content = $_POST["blog_content"];
        $content = str_replace("\"", "&#34;", $content);
        $content = str_replace("\'", "&#39;", $content);
        $content = str_replace("\n", "<br/>", $content);
        
        $tags = ChangeEscapeValues($_POST["blog_tags"]);
        
        $db->GetQueryResult('INSERT INTO blog(title, published, content, tag) VALUES("' . $title . '", CURRENT_TIMESTAMP, "' . $content . '", "' . $tags . '");');
    }
    // If user wants to update
    // Update
    else if (isset($_POST["command"]) and (isset($_POST["command"]) == "update") and isset($_POST["blog_id"]) and isset($_POST["blog_title"]) and isset($_POST["blog_content"]) and isset($_POST["blog_tags"]))
    {   
        $blogId = $_POST["blog_id"];
        $title = ChangeEscapeValues($_POST["blog_title"]);
        
        $content = $_POST["blog_content"];
        $content = str_replace("\"", "&#34;", $content);
        $content = str_replace("\'", "&#39;", $content);
        $content = str_replace("\n", "<br/>", $content);
        
        $tags = ChangeEscapeValues($_POST["blog_tags"]);
        
        $db->GetQueryResult('UPDATE blog SET title="' . $title . '", content="' . $content . '", tag="' . $tags . '" WHERE blog_id=' . $blogId . ';');
    }
}

// Display
$sortOption = isset($_POST["sort_by"]) ? $_POST["sort_by"] : "published";
$orderFormat = "DESC";

// If user mentioned about order format then, format on that order, if order
// format isn't recognized then, use the default one (descending).
if (isset($_POST["order_format"]))
{
    // If ascending
    if ($_POST["order_format"] == "ascending")
    {
        $orderFormat = "";
    }
    // Otherwise, descending
    else
    {
        $orderFormat = "DESC";
    }
}

// Get artcles from blog table on database and arange them in ordered format
// that user wanted
$blogs = $db->GetQueryResult("SELECT * FROM blog ORDER BY " . $sortOption . " " . $orderFormat . ";");

foreach ($blogs as $blog)
{   
    // Formating data from which has just gotten from database and replacing 
    // unexpected character.
    $title = $blog['title'];
    $title = str_replace("\"", "&#34;", $title);
    $titleForTableColumn = str_replace('\'', "&#39;", $title);
    $title = str_replace('\'', "\&#39;", $title);
    $title = str_replace('&#39;', "\\&#39;", $title);
    $title = str_replace("\n", "<br/>", $title);
    
    $content = $blog["content"];
    $content = str_replace("\"", "&#34;", $content);
    $content = str_replace('\'', "\&#39;", $content);
    $content = str_replace("\n", "<br/>", $content);
    
    // Displaying data on table
    ?>
        <tr value="<?php echo($blog['blog_id']); ?>" onclick="$(document).ready(function() { currentMode = MODE['EDIT']; $('#contentId').val('<?php echo($blog['blog_id']); ?>'); $('#contentTitle').val('<?php echo($title); ?>'); $('#contentTags').val('<?php echo($blog['tag']); ?>'); $('#contentDateTime').val('<?php echo($blog['published']); ?>'); $('#resizable').val('<?php echo($content); ?>'); ChangePreviewContent(); $('#richTextTabs').tabs({ active: 0 }); });">
            <td><input type="checkbox" class="blogMarker" id="<?php echo('blog' . $blog['blog_id']); ?>" name="<?php echo('blog' . $blog['blog_id']); ?>" value="<?php echo($blog['blog_id']); ?>" /><label for="<?php echo('blog' . $blog['blog_id']); ?>"><span></span></label></td>
            <td><?php echo($titleForTableColumn); ?></td>
            <td><time datetime='<?php echo($blog["published"]); ?>'><?php echo($blog["published"]); ?></time></td>
            <td><?php echo($blog["tag"]); ?></td>
        </tr>
    <?php
}

/** 
 * FUNCTION NAME:  ChangeEscapeValues
 * PARAMETER:      (string) text
 * RETURN:         (string) text
 * 
 * PURPOSE:        Replace unexpected characters and return the right formated
 *                 text.
 */
function ChangeEscapeValues($text)
{
    $text = str_replace("\"", "&#34;", $text);
    $text = str_replace('\'', "&#39;", $text);
    $text = str_replace("\n", "<br/>", $text);

    return $text;
}
?>

