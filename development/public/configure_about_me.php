<?php
/**
 * FILE NAME:       configure_about_me.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            October 03, 2013
 * LAST EDITED:     October 14, 2013 08:54 AM
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

// If user somehow wants to access directly by http://<your_page_name>/configure
// _about_me.php it will redirect.
RedirectTo();

// Starting session
session_start();

// Creating new database connection
$db = new Database();

// If user is actially logged in then, update if required
if (isset($_SESSION["logged_in"]) and $_SESSION["logged_in"])
{
    // If user wanted to update, then update
    if (isset($_POST["command"]) and ($_POST["command"] == "update") and isset($_POST["title"]) and isset($_POST["content"]) and isset($_POST["previous_title"]))
    {
        // UPDATE
        $previous_title = $_POST["previous_title"];
        $currentTitle = $_POST["title"];
        
        $currnetContent = $_POST["content"];
        $currnetContent = str_replace("\"", "\'", $currnetContent);
        $currnetContent = str_replace('\'', "\"", $currnetContent);
        $currnetContent = str_replace("\n", "",   $currnetContent);
        
        // Update query to about_me table WHERE login_name is zunayed-hassan
        $db->GetQueryResult('UPDATE about_me SET title="' . $currentTitle . '", content="' . $currnetContent . '" WHERE title="' . $previous_title . '";');
    }
}

// Get data from about_me table on databaase
$aboutMyself = $db->GetQueryResult("SELECT * FROM about_me", true);

$title = $aboutMyself["title"];

// Replacing enexpected character
$content = $aboutMyself["content"];
$content = str_replace("\"", "\'", $content);
$content = str_replace('\'', "\"", $content);
$content = str_replace("\n", "", $content);
?>

<!-- External Stylesheet -->
<link rel="stylesheet" href="stylesheets/about_me_style.css">

<script type="text/javascript">
    $(document).ready(function() {
        // This page will frequently run by Ajax. So, on the parent page if 
        // user first clicked on ManageAboutMe radio and then immediatly went
        // for another ManageAccountRadio then this Save button should disappear
        // on the process, because this Save button is highly unwanted there.
        
        // If current process is manage account, then disappear the
        // saveAboutMe button
        if (window.manageAccount) {
            $("#saveAboutMeButton").hide();
        }
        // otherwise, add some functionalities on saveAboutMeButton
        else {
            $("#saveAboutMeButton")
                .button({ icons: { primary: "ui-icon-disk" } })
                .css("margin-bottom", "1em")
                .click(
                    // Check if about me fields are valid or not. Then update.
                    function(event) {
                        var aboutMeTitle = $.trim($("#contentTitle").val());
                        var aboutMeContent = $.trim($("#resizable").val());
                        var previousTitle = $.trim($("#contentTags").val());
                        
                        if (aboutMeTitle.length == 0) {
                            $("#blogTitleValidationTipsLabel")
                                    .css("color", "orange")
                                    .slideDown();
                            }
                            else {
                                $("#blogTitleValidationTipsLabel")
                                        .css("color", "#ccc")
                                        .slideUp();
                            }

                            if (aboutMeContent.length == 0) {
                                $("#blogContentValidationTipsLabel")
                                        .css("color", "orange")
                                        .slideDown();
                            }
                            else {
                                $("#blogContentValidationTipsLabel")
                                        .css("color", "#ccc")
                                        .slideUp();
                            }

                            // If everything is OK, then update by refresing
                            // the configure_about_me.php page via Ajax.
                            if ((aboutMeTitle.length > 0) && (aboutMeContent.length > 0)) {
                                DisplayPage("configure_about_me", "configureAboutMeTabOuter", { command: "update", title: aboutMeTitle, content: aboutMeContent, previous_title: previousTitle })
                            }
                            else {
                                $("#richTextTabs").tabs({ active: 1 });
                            }
                    }
                );
                    
            // Show updated data from about_me table
            $("#contentTitle").val('<?php echo($title); ?>');
            $("#resizable").val('<?php echo($content); ?>');
            $("#contentTags").val('<?php echo($title); ?>');
            ChangePreviewContent();
        }
        
        
    })
</script>

<button id="saveAboutMeButton">Save</button>

<?php

/**  FUNCTION NAME:  RedirectTo
 *   PARAMETER:      (string) pageNameWithoutExtension
 *   RETURN:         None
 *   
 *   PURPOSE:        If user tries to open http://<your_page_name>/configure_
 *                   about_me.php directly, then redirect to
 *                   http://<your_page_name>/index.php and open as
 *                   http://<your_page_name>/index.php#?section=configure,
 *                   otherwise continue.
 * 
 *  NOTE:            There is an another function with same name in
 *                   common_tools.php, but its functionality is different
 *                   from that one. This function is only optimized for this
 *                   page.
 **/
function RedirectTo()
{
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $uriInfo = explode("?_", $_SERVER['REQUEST_URI']);
    
    if (count($uriInfo) <= 1)
    {
        /* Redirect to a different page in the current directory that was requested */
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'index.php#?section=configure';
        header("Location: http://$host$uri/$extra");
        exit;
    }
}


?>
