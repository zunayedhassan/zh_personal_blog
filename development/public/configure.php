<?php
/**
 * FILE NAME:       configure.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            September 27, 2013
 * LAST EDITED:     October 13, 2013 07:07 PM
 * 
 * PURPOSE:         To provide an UI to manage blogs and other pages including
 *                  changing administrator's password.
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
include("./application/message.php");
include("./application/upload_image.php");
include("./application/upload_multiple_image_files.php");

// If user tries to open http://<your_page_name>/configure.php directly, then
// redirect to http://<your_page_name>/index.php and open as
// http://<your_page_name>/index.php#?section=configure, otherwise continue.
RedirectTo("configure");

// Creating new database connection
$db = new Database();
?>

<!-- External stylesheet -->
<link rel="stylesheet" href="stylesheets/other_style.css">

<section id="mainContent">
    <header class="otherContent">
        <h2 class="hidden">Main Content</h2>
    </header>

    <?php
    // If user isn't logged in, then don't let him in
    if (!isset($_SESSION["logged_in"]))
    {
        ?>
        <article class="errorStyle">
            <header>
                <h2>Woohoo! ...Cowboy</h2>
            </header>

            <p>
                You do not have sufficient permission to edit this site. So, try to login first.
            </p>
        </article>
        <?php
    }
    // Otherwise, he is already logged in, let him in
    else if ($_SESSION["logged_in"])
    {
        // Change the color of #mainHeader as a sign og logged in
        ?>
        <style type="text/css">
            #mainContent #configureHeader h2 {
                color: #999800;
            }

            #configureTabs {
                width: 100%;
                font-size: 90%;
            }
        </style>

        <script type="text/javascript">
            /**  FUNCTION NAME:  SetConfigureTabsSize
             *   PARAMETER:      None
             *   RETURN:         None
             *   
             *   PURPOSE:        To set configureTab size as much as screen
             *                   width.
             **/
            function SetConfigureTabsSize() {
                var configureBlogTabWidth = null;
                var configureBlogTabHeight = null;
                var screenWidth = window.innerWidth;
                var screenHeight = window.innerHeight;

                // If its a desktop, then save some spaces for mainNavigation
                // and take rest of the spaces for the configureTab size.
                if (screenWidth >= 1024) {
                    var mainNavigationElementPaddingRight = parseInt(($("#mainNavigation ul li").css("padding-right")).split("px")[0]);
                    configureBlogTabWidth = (screenWidth - ($("#mainNavigation ul").width() * 1) - (mainNavigationElementPaddingRight * 2) - (window.innerWidth * 0.10));
                    configureBlogTabHeight = (screenHeight - $("#pageHeader").height() - $("footer").height() - 46) / 2;
                }
                // Or, if its a netbook/tablet/mobile, then take all the screen
                // width for configure tab size.
                else if (screenWidth < 1024) {
                    configureBlogTabWidth = screenWidth * 0.90;
                    configureBlogTabHeight = screenWidth * 0.60;
                }

                // Change sizes based on previous calculation
                $("#configureBlogTab").css({
                    "width": configureBlogTabWidth,
                    "height": configureBlogTabHeight
                });
                
                // Change the size of resizable too, which is almost similar as
                // configureTab size.
                $("#resizable").css({
                    "margin-top": "0.5em",
                    "height": configureBlogTabHeight,
                    "width": configureBlogTabWidth - 40
                });
            }

            $(document).ready(function() {
                // Make rich text editor writing area (#resizable) as resizable.
                $("#configureBlogTab").resizable();
                
                // and change their sizes
                SetConfigureTabsSize();
            });
        </script>

        <article>
            <header id="configureHeader">
                <h2>Change As You Like</h2>
            </header>
            
            <!-- External Stylesheets -->
            
            <!--
            FILE NAME:      rich_text_editor_style.css

            PURPOSE:        It holds the important styling information about
                            rich text editor.
            -->
            <link rel="stylesheet" href="stylesheets/rich_text_editor_style.css">
            
            <!--
            FILE NAME:      flex_style.css
            DEPENDENCY FOR: jquery.flex.js

            PURPOSE:        Show photo gallery (if exist) in rows and make white borders
                            around pictures.
            -->
            <link rel="stylesheet" href="stylesheets/flex_style.css">
            
            <style type="text/css">
                /* Configure Tab */
                
                /* Table */
                .tableHeaderStyle {
                    background: #45484d; /* Old browsers */
                    background: -moz-linear-gradient(top,  #45484d 0%, #000000 100%); /* FF3.6+ */
                    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#45484d), color-stop(100%,#000000)); /* Chrome,Safari4+ */
                    background: -webkit-linear-gradient(top,  #45484d 0%,#000000 100%); /* Chrome10+,Safari5.1+ */
                    background: -o-linear-gradient(top,  #45484d 0%,#000000 100%); /* Opera 11.10+ */
                    background: -ms-linear-gradient(top,  #45484d 0%,#000000 100%); /* IE10+ */
                    background: linear-gradient(to bottom,  #45484d 0%,#000000 100%); /* W3C */
                    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#45484d', endColorstr='#000000',GradientType=0 ); /* IE6-9 */

                }

                #configureBlogTab table {
                    padding: 1em 2em 2em 2em;
                    border-collapse: collapse;
                    z-index: 0;
                    border-radius: 7px;
                }
                
                #configureBlogTab .tableHeaderStyle th {
                    padding: 0.5em; 
                    color: #ccc;
                }
                
                .tableHeaderStyle th a {
                    color: #ccc;
                    text-decoration: none;
                    padding-left: 1.5em;
                }
                
                .tableHeaderStyle th a:hover {
                    color: orange;
                }
                
                .tableHeaderStyle th label {
                    margin-left: 0.5em;
                }

                /* Configure blog tab */
                #configureBlogTab {
                    background: url("scripts/behaviour/themes/dark-hive/images/ui-bg_loop_25_000000_21x21.png") repeat;
                    padding: 0.5em;
                    border: 1px solid #ccc;
                    border-radius: 1em;
                    overflow-y: auto;
                    overflow-x: hidden;
                }
                
                /* configureBlogTab table shows blog table from database */
                #configureBlogTab table td {
                    padding: 0.5em;
                    padding-left: 1em;
                    color: #ccc;
                }

                #configureBlogTab table tr:nth-child(even) {
                    background: #333;
                }
                
                #configureBlogTab table tbody tr:hover {
                    background: #75890c;
                    cursor: pointer;
                }
                
                #configureBlogTab table tbody tr:active {
                    background: #4D7CBF;
                }
                
                /* Toolbar */
                #configureToolbar, #editBlogButtonSet {
                    padding: 4px;
                    display: inline-block;
                    margin-bottom: 1.5em;
                    font-size: 1em;
                }
                /* support: IE7 */
                *+html #configureToolbar, *+html #editBlogButtonSet {
                    display: inline;
                }
                
                #configureToolbar form div {
                    display: inline-block;
                }
                
                /* Validation tips */
                #blogTitleValidationTipsLabel, #blogContentValidationTipsLabel {
                    font-size: 70%;
                    margin-bottom: 0.5em;
                }
                
                /* Blog Edit Buttons */
                #editBlogButtonSet {
                    margin: 1em 0 1em;
                }
                
                /* Dialog */
                #deleteBlogConfirmationDialog, #deleteBlogWarningDialog, #updateBlogConfirmationDialog, .ui-dialog-buttonset, .ui-widget-header {
                    font-size: 75%;
                }
            </style>
            
            <!--
            LIBRARY NAME:  Rangy Inputs
            VERSION:       1.1.2
            DEPENDENCY:    jQuery
            SOURCE:        https://code.google.com/p/rangyinputs/

            PURPOSE:       A small cross-browser JavaScript library for
                           obtaining and manipulating selections within
                           <textarea> and <input type="text"> HTML elements.
            -->
            <script type="text/javascript" src="scripts/rangyinputs-jquery-1.1.2.js"></script>
            
            <!--
            LIBRARY NAME:  rich_text_editor_default_behaviour.js
            DEPENDENCY:    jQuery

            PURPOSE:       Holds the styles for rich text editor, specially for
                           the toolbars.
            -->
            <script type="text/javascript" src="scripts/behaviour/rich_text_editor_default_behaviour.js"></script>
            
            <!--
            LIBRARY NAME:  stdlib.js
            SOURCE:        http://www.jslab.dk/

            PURPOSE:       For tweaking date format.
            -->
            <script type="text/javascript" src="scripts/stdlib.js"></script>
            
            <script type="text/javascript">
                // Changing page title
                document.title = "Configure: Zunayed Hassan's Personal Blog";
                
                // configure blog table can show all article entries from
                // database. Some times user may require to sort any column in
                // particular order.
                var headerOrder = "ascending";
                
                // Rich text editors' mode for blog managing.
                var MODE = {
                    NONE: 0,
                    EDIT: 1,
                    NEW:  2
                };
                
                var currentMode = MODE["NONE"];
                window.manageAccount = false;       // This varible is for managing user account

                /**
                 *  FUNCTION NAME:  OnTableHeaderClicked
                 *  PARAMETER:      (string) columnName
                 *  RETURN:         (boolean) false
                 *  
                 *  PURPOSE:        When user click on column header, it will
                 *                  help to show, the result is in assending
                 *                  or descending order.
                 */
                function OnTableHeaderClicked(columnName) {
                    $(document).ready(function() {                        
                        DisplayPage('configure_blog_table_view', 'blog_table_display_area', { sort_by:  columnName, order_format: headerOrder});

                        $(".tableHeaderStyle th a").css("background", "none");

                        if (headerOrder === "ascending") {
                            headerOrder = "descending";
                            
                            $(".tableHeaderStyle th a[value='" + columnName + "']").css("background", 'url("icons/Arrowhead-Up.png") no-repeat');
                        }
                        else {
                            headerOrder = "ascending";
                            $(".tableHeaderStyle th a[value='" + columnName + "']").css("background", 'url("icons/Arrowhead-Down.png") no-repeat');
                        }
                    });

                    return false;
                }
                
                /**
                 *  FUNCTION NAME:  SetClearOnRichTextEditor
                 *  PARAMETER:      None
                 *  RETURN:         None
                 *  
                 *  PURPOSE:        Set void on rich text editor area
                 */
                function SetClearOnRichTextEditor() {
                    $("#contentId").val("");
                    $("#contentTitle").val("");
                    $("#contentDateTime").val("");
                    $("#contentTags").val("")
                    $("#resizable").val("");
                }
                
                
                /**
                 *  FUNCTION NAME:  ShowEditSection
                 *  PARAMETER:      (string) sectionName
                 *  RETURN:         None
                 *  
                 *  PURPOSE:        Show only the part of the section that,
                 *                  user mentioned, all other section will
                 *                  become hidden.
                 */
                function ShowEditSection(sectionName) {
                    var sections = [
                        "configureBlogTabOuter",
                        "configurePortfolioTabOuter",
                        "configureAboutMeTabOuter",
                        "configureManageAccountOuter"
                    ]
                    
                    if (sectionName != sections[0]) {
                        $("#contentTags").hide();
                        SetClearOnRichTextEditor();
                        currentMode = MODE["NONE"];
                    }
                    else {
                        $("#contentTags").show();
                        $("#" + sections[1]).html("");
                        $("#" + sections[2]).html("");
                        $("#previewArea").html("");
                        
                        SetClearOnRichTextEditor();   
                    }
                    
                    if (sectionName == sections[3]) {
                        window.manageAccount = true;
                    }
                    else {
                        window.manageAccount = false;
                    }
                    
                    for (var index = 0; index < sections.length; index++) {
                        $("#" + sections[index]).hide();
                    }
                    
                    $("#richTextTabs").hide();
                    
                    $("#" + sectionName).slideDown();
                    $("#richTextTabs").slideDown();
                }
                
                
                $(document).ready(function() {
                    /* Edit Switches */
                    $("#editRadioSet").buttonset();
                    
                    $("#blogRadio").click(function() {
                        ShowEditSection("configureBlogTabOuter");
                    });
                    
                    $("#portfolioRadio").click(function() {
                        ShowEditSection("configurePortfolioTabOuter");
                    });
                    
                    // If about me radio is clicked, then fetch data from
                    // database about author and show them through Ajax within
                    // preview area.
                    $("#aboutMeRadio").click(function() {
                        DisplayPage("configure_about_me", "configureAboutMeTabOuter");
                        
                        ShowEditSection("configureAboutMeTabOuter");
                    });
                    
                    $("#manageAccountRadio").click(function() {
                        ShowEditSection("configureManageAccountOuter");
                        $("#richTextTabs").hide();
                    });
                    
                    /* Configure Blog */
                    ShowEditSection("configureBlogTabOuter");
                    
                    $("#selectAllBlogCheckBox")
                            .click(function() {                        
                                var blogPostCheckBoxes = document.getElementsByClassName("blogMarker");

                                for (var index = 0; index < blogPostCheckBoxes.length; index++) {
                                    blogPostCheckBoxes[index].checked = $(this).is(":checked");
                                }
                            });
                            
                    $("#blogTitleValidationTipsLabel").hide();
                    $("#blogContentValidationTipsLabel").hide();
                            
                    $("#newBlogButton")
                            .button({ icons: { primary: "ui-icon-plus" } })
                            .click(function(event) {
                                currentMode = MODE["NEW"];
                        
                                var index = $('#richTextTabs li a').index($('a[href^="#editTab"]').get(0));
                                $("#richTextTabs").tabs({ active: index });
                                
                                SetClearOnRichTextEditor();
                                
                                event.preventDefault();
                            });        
                            
                    $("#deleteBlogButton")
                            .button({ icons: { primary: "ui-icon-trash" } })
                            .click(function(event) {
                                var currentMode = MODE["NONE"];
                        
                                var allBlogPosts = document.getElementsByClassName("blogMarker");
                                var isAnyItemSelected = false;
                                
                                for (var index = 0; index < allBlogPosts.length; index++) {
                                    if (allBlogPosts[index].checked) {
                                        isAnyItemSelected = true;
                                        break;
                                    }
                                }
                                
                                if (isAnyItemSelected) {
                                    $("#deleteBlogConfirmationDialog").dialog("open");
                                }
                                else {
                                    $("#deleteBlogWarningDialog").dialog("open");
                                }
                                
                                $("#selectAllBlogCheckBox").attr("checked", false);
                                event.preventDefault();
                            });
                            
                    
                            
                    $("#saveBlogButton")
                            .button({ icons: { primary: "ui-icon-disk" } })
                            .click(function() {
                                var blogTitle = $.trim($("#contentTitle").val());
                                var blogContents = $.trim($("#resizable").val());
                                var blogTags = $.trim($("#contentTags").val());

                                if (blogTitle.length == 0) {
                                    $("#blogTitleValidationTipsLabel")
                                            .css("color", "orange")
                                            .slideDown();
                                }
                                else {
                                    $("#blogTitleValidationTipsLabel")
                                            .css("color", "#ccc")
                                            .slideUp();
                                }
                                
                                if (blogContents.length == 0) {
                                    $("#blogContentValidationTipsLabel")
                                            .css("color", "orange")
                                            .slideDown();
                                }
                                else {
                                    $("#blogContentValidationTipsLabel")
                                            .css("color", "#ccc")
                                            .slideUp();
                                }

                                if ((blogTitle.length > 0) && (blogContents.length > 0)) {
                                    if ((currentMode == MODE["NEW"]) || (currentMode == MODE["NONE"])) {
                                        DisplayPage('configure_blog_table_view', 'blog_table_display_area', { command: "save", blog_title: blogTitle, blog_content: blogContents, blog_tags: blogTags });
                                    }
                                    else if (currentMode == MODE["EDIT"]) {
                                        $("#updateTitleArea").html($("#contentTitle").val());
                                        $("#updateBlogConfirmationDialog").dialog("open");
                                    }
                                }
                            });
                            
                            
                    $("#deleteBlogConfirmationDialog").dialog({
                        autoOpen: false,
                        resizable: false,
			height: 180,
			modal: true,
			buttons: {
                            "Delete all items": function() {
                                DeleteSelectedBlog();
                                $(this).dialog("close");
                            },
                            Cancel: function() {
                                $(this).dialog("close");
                            }
			}
                    });
                    
                    $("#deleteBlogWarningDialog").dialog({
                        autoOpen: false,
                        resizable: false,
			height: 180,
			modal: true,
                        buttons : {
                            Close: function() {
                                $(this).dialog("close");
                            }
                        }
                    });
                    
                    $("#updateBlogConfirmationDialog").dialog({
                        autoOpen: false,
                        resizable: false,
			height: 180,
			modal: true,
                        
                        buttons: {
                            "Save": function() {
                                UpdateBlogEntry();
                                $(this).dialog("close");
                            },
                            "Cancel": function() {
                                $(this).dialog("close");
                            }
			}
                    });
                });
                
                /**
                 *  FUNCTION NAME:  DeleteSelectedBlog
                 *  PARAMETER:      None
                 *  RETURN:         None
                 *  
                 *  PURPOSE:        1. Mark selected blog and collect their blog
                 *                     ids.
                 *                     
                 *                  2. Make a query string for processing delete
                 *                     operation
                 *                     
                 *                  3. Send query string via Ajax
                 *                  
                 *                  4. Get updated result and show them in a
                 *                     table.
                 */
                function DeleteSelectedBlog() {
                    var allBlogPosts = document.getElementsByClassName("blogMarker");
                    var selectedBlogPost = new Array();

                    // Collect blog ids
                    for (var index = 0; index < allBlogPosts.length; index++) {
                        if (allBlogPosts[index].checked) {
                            selectedBlogPost.push(allBlogPosts[index].value);
                        }
                    }

                    // If at least one item is selected, then make a query
                    // string
                    if (selectedBlogPost.length > 0) {
                        var deleteCommand = "command=delete&blog_post=";

                        for (var index = 0; index < selectedBlogPost.length; index++) {
                            deleteCommand += selectedBlogPost[index] + ",";
                        }

                        // Pass query string for deletion operation
                        DisplayPage('configure_blog_table_view', 'blog_table_display_area', deleteCommand);
                        
                        // Set clear the rich text editor
                        SetClearOnRichTextEditor();
                        
                        // Change the look of preview content.
                        ChangePreviewContent();
                    }
                }
                
                /**
                 *  FUNCTION NAME:  UpdateBlogEntry
                 *  PARAMETER:      None
                 *  RETURN:         None
                 *  
                 *  PURPOSE:        Get data from rich text editor and run
                 *                  update operation.
                 */
                function UpdateBlogEntry() {
                    var updateCommand = {
                        command: "update",
                        blog_id: $("#contentId").val(),
                        blog_title: $("#contentTitle").val(),
                        blog_content: $("#resizable").val(),
                        blog_tags: $("#contentTags").val()
                    };
                    
                    DisplayPage('configure_blog_table_view', 'blog_table_display_area', updateCommand);
                }
            </script>
            
            <script type="text/javascript">
                /**
                 *  FUNCTION NAME:  AppendTextToTextArea
                 *  PARAMETER:      (string) textAreaSelector, (string) buttonNameSelector, (string) startTag, (string) endTag, (boolean) appendToLast
                 *  RETURN:         None
                 *   
                 *  PURPOSE:        1. It takes button name and adds
                 *                     functionality of adding HTML tag which
                 *                     is required for editing blog content.
                 *                     
                 *                  2. Normaly, it can add tag where cursor is
                 *                     selected, but it can only add to the last
                 *                     by setting true to the last option.
                 */
                function AppendTextToTextArea(textAreaSelector, buttonNameSelector, startTag, endTag, appendToLast) {
                    $(buttonNameSelector).click(
                        function() {
                            // If user wants to add tag the last
                            if (!appendToLast) {
                                $(textAreaSelector).replaceSelectedText(startTag + $(textAreaSelector).extractSelectedText() + endTag);
                            }
                            // If user wants to add tag at selection point
                            else {
                                var selectedTextPosition = $(textAreaSelector).getSelection();
                                var lastIndexOfWholeText = ($(textAreaSelector).val()).length;
                                var originalText = $(textAreaSelector).extractSelectedText();

                                if (selectedTextPosition.end == lastIndexOfWholeText) {
                                    $(textAreaSelector).replaceSelectedText("");
                                    $(textAreaSelector).val($(textAreaSelector).val() + (startTag + originalText + endTag));
                                }
                                else if (selectedTextPosition.end < lastIndexOfWholeText) {
                                    $(textAreaSelector).replaceSelectedText(startTag + originalText + endTag);
                                }
                            }
                        }
                    );
                }
                
                /**
                 *  FUNCTION NAME:  SetPreviewContent
                 *  PARAMETER:      None
                 *  RETURN:         None
                 *   
                 *  PURPOSE:        Grab the code from rich text editor and make
                 *                  preview of that.
                 */
                function SetPreviewContent() {
                    var dateString = "";

                    if ($("#contentDateTime").val() != "") {
                        dateString = dateString = "<b>Published at: </b><time datetime='" + $("#contentDateTime").val() + "'>" + $("#contentDateTime").val() + "</time>";
                    }

                    $("#previewArea").html("<article><header><h2>" + $("#contentTitle").val() + "</h2><span class='contentInfo'>"+ dateString + "</span></header><div class='content'>" + $("#resizable").val() + "</div></article>");

                }
                
                /**
                 *  FUNCTION NAME:  ChangePreviewContent
                 *  PARAMETER:      None
                 *  RETURN:         None
                 *   
                 *  PURPOSE:        Apply styles on preview area
                 */
                function ChangePreviewContent() {
                    SetPreviewContent();

                    /* Design and functionality */
                    $("a.lightbox").click(function(e) {
                        // Hide scrollbars!
                        $("body").css("overflow-y", "hidden");

                        $("<div id='overlay'></div>")
                            .css('top', $(document).scrollTop())
                            .css("opacity", "0")
                            .animate(
                            {
                                "opacity": "0.8"
                            },

                            "slow"
                        )
                            .appendTo("body");

                        $('<div id="lightbox"></div>')
                            .hide()
                            .appendTo('body');

                        var currentlyClickedImage = $("#lightbox");

                        $("<img />")
                            .attr("src", $(this).attr("href"))
                            .load(function() {
                                var imageWidth = $(currentlyClickedImage).width();
                                var imageHeight = $(currentlyClickedImage).height();

                                var windowWidth = $(window).width();
                                var windowHeight = $(window).height();

                                var tempImageWidth = $(currentlyClickedImage).width();
                                var tempImageHeight = $(currentlyClickedImage).height();

                                if (imageHeight >= imageWidth) {
                                    if (imageHeight >= windowHeight) {
                                        tempImageHeight = Math.floor((windowHeight * 0.80));
                                        $("#lightbox img").height(tempImageHeight);
                                    }
                                }
                                else {
                                    if (imageWidth >= windowWidth) {
                                        tempImageWidth = Math.floor((windowWidth * 0.80));
                                        $("#lightbox img").width(tempImageWidth);
                                    }
                                }

                                positionLightBoxImage();
                            })
                            .click(function() {
                                removeLightbox();
                            })
                            .appendTo("#lightbox");

                        /* Lightbox overlay */
                        $("#overlay").css(
                            {
                                "position": "fixed",
                                "top": "0",
                                "left": "0",
                                "height": "100%",
                                "width": "100%",
                                "background": "black url('images/loading.gif') no-repeat scroll center center"
                            }
                        ).click(function() {
                                removeLightbox();
                            }
                        );

                        $("#lightbox").css("position", "absolute");

                        $(document).bind("keydown", function(keyEvent) {
                            // ESCAPE
                            if (keyEvent.keyCode == 27) {
                                removeLightbox();
                            }
                        });

                        return false;
                    });
                    

                    $(".flex").flex();

                    var flexTempWidth = null;
                    var flexOriginalWidth = null;
                    var flexSpeed = 300;

                    $(".flex a").hover(
                        function() {
                            flexTempWidth = $(this).attr("width");
                            var childImage = $(this).find("img");
                            flexOriginalWidth = $(childImage).width();

                            $(childImage).animate({"width": flexTempWidth}, flexSpeed);
                        },

                        function() {
                            var childImage = $(this).find("img");
                            $(childImage).animate({"width": flexOriginalWidth}, flexSpeed);
                            flexTempWidth = null;
                            flexOriginalWidth = null;
                        }
                    );
                }

                $(document).ready(function() {
                    $("#resizable").bind("keydown", function(keyEvent) {
                        // SHIFT + ENTER
                        if ((keyEvent.shiftKey) && (keyEvent.keyCode == 13)) {
                            $("#resizable").replaceSelectedText($("#resizable").extractSelectedText() + "<br/>");
                        }
                        // SHIFT + SPACEBAR
                        else if ((keyEvent.shiftKey) && (keyEvent.keyCode == 32)) {
                            $("#resizable").replaceSelectedText($("#resizable").extractSelectedText() + '&#160;');
                        }
                    });

                    // headers
                    AppendTextToTextArea("#resizable", "#header1", "<h1>", "</h1>", false);
                    AppendTextToTextArea("#resizable", "#header2", "<h2>", "</h2>", false);
                    AppendTextToTextArea("#resizable", "#header3", "<h3>", "</h3>", false);
                    AppendTextToTextArea("#resizable", "#header4", "<h4>", "</h4>", false);
                    AppendTextToTextArea("#resizable", "#header5", "<h5>", "</h5>", false);
                    AppendTextToTextArea("#resizable", "#header6", "<h6>", "</h6>", false);

                    // Bold
                    AppendTextToTextArea("#resizable", "#bold", "<b>", "</b>", false);
                    // Italic
                    AppendTextToTextArea("#resizable", "#italic", "<i>", "</i>", false);
                    // Underline
                    AppendTextToTextArea("#resizable", "#underline", '<span class="underline">', "</span>", false);
                    // Superscript
                    AppendTextToTextArea("#resizable", "#superscript", "<sup>", "</sup>", false);
                    // Subscript
                    AppendTextToTextArea("#resizable", "#subscript", "<sub>", "</sub>", false);
                    // Align Left
                    AppendTextToTextArea("#resizable", "#align_left", '<p class="alignLeft">', "</p>", true);
                    // Align Center
                    AppendTextToTextArea("#resizable", "#align_center", '<p class="alignCenter">', "</p>", true);
                    // Align Right
                    AppendTextToTextArea("#resizable", "#align_right", '<p class="alignRight">', "</p>", true);
                    // Align Justify
                    AppendTextToTextArea("#resizable", "#align_justify", '<p class="alignJustify">', "</p>", true);
                    // List (ordered)
                    AppendTextToTextArea("#resizable", "#bullet_numbered", "<ol><li>", "</li></ol>", true);
                    // List (unordered)
                    AppendTextToTextArea("#resizable", "#bullet_not_numbered", "<ul><li>", "</li></ul>", true);
                    // Paragraph
                    AppendTextToTextArea("#resizable", "#paragraph", "<p>", "</p>", false);
                    // Blockquote
                    AppendTextToTextArea("#resizable", "#insert_blockquote", "<blockquote>", "</blockquote>", false);
                    
                    // Table
                    $("#table")
                            .button()
                            .click(function() {
                                $("#createNewTableDialogForm").dialog("open");
                            });

                    // Hyperlink
                    $("#link")
                            .button()
                            .click(function() {
                                $("#insertNewLinkDialogForm").dialog("open");
                            });

                    // Insert an image
                    $("#inserAnImageMenuItem")
                            .click(function() {
                                $("#insertAnImageDialogForm").dialog("open");
                            });

                    // Insert a photogallery
                    $("#uploadPhotoGalleryMenuItem")
                        .click(function() {
                            $("#insertPhotoGalleryDialogForm").dialog("open");
                        });

                    // Insert Map
                    $("#insert_geolocation")
                        .click(function() {
                            $("#insertGeoLocationDialogForm").dialog("open");
                        });

                    // Code
                    AppendTextToTextArea("#resizable", "#insert_code", "<pre><code>", "</code></pre>", false);

                    // Insert smiley
                    AppendTextToTextArea("#resizable", "#smileSmiley",        '<span class="smiley"><img src="icons/smiley/smile.png" alt="Smile" title="Smile">',                   "</span>", true);      // Smile
                    AppendTextToTextArea("#resizable", "#sadSmiley",          '<span class="smiley"><img src="icons/smiley/sad.png" alt="Sad" title="Sad">',                         "</span>", true);      // Sad
                    AppendTextToTextArea("#resizable", "#tongueSmiley",       '<span class="smiley"><img src="icons/smiley/tongue.png" alt="Tongue" title="Tongue">',                "</span>", true);      // Tongue
                    AppendTextToTextArea("#resizable", "#grinSmiley",         '<span class="smiley"><img src="icons/smiley/grin.png" alt="Grin" title="Grin">',                      "</span>", true);      // Grin
                    AppendTextToTextArea("#resizable", "#amazedSmiley",       '<span class="smiley"><img src="icons/smiley/amazed.png" alt="Amazed" title="Amazed">',                "</span>", true);      // Amazed
                    AppendTextToTextArea("#resizable", "#winkSmiley",         '<span class="smiley"><img src="icons/smiley/wink.png" alt="Wink" title="Wink">',                      "</span>", true);      // Wink
                    AppendTextToTextArea("#resizable", "#laughSmiley",        '<span class="smiley"><img src="icons/smiley/laugh.png" alt="Laugh" title="Laugh">',                   "</span>", true);      // Laugh
                    AppendTextToTextArea("#resizable", "#doubtfulSmiley",     '<span class="smiley"><img src="icons/smiley/doubtful.png" alt="Doubtful" title="Doubtful">',          "</span>", true);      // Doubtful
                    AppendTextToTextArea("#resizable", "#confusedSmiley",     '<span class="smiley"><img src="icons/smiley/confused.png" alt="Confused" title="Confused">',          "</span>", true);      // Confused
                    AppendTextToTextArea("#resizable", "#crySmiley",          '<span class="smiley"><img src="icons/smiley/cry.png" alt="Cry" title="Cry">',                         "</span>", true);      // Cry
                    AppendTextToTextArea("#resizable", "#wackySmiley",        '<span class="smiley"><img src="icons/smiley/wacky.png" alt="Wacky" title="Wacky">',                   "</span>", true);      // Wacky
                    AppendTextToTextArea("#resizable", "#nerdSmiley",         '<span class="smiley"><img src="icons/smiley/nerd.png" alt="Nerd" title="Nerd">',                      "</span>", true);      // Nerd
                    AppendTextToTextArea("#resizable", "#coolSmiley",         '<span class="smiley"><img src="icons/smiley/cool.png" alt="Cool" title="Cool">',                      "</span>", true);      // Cool
                    AppendTextToTextArea("#resizable", "#loveSmiley",         '<span class="smiley"><img src="icons/smiley/love.png" alt="Love" title="Love">',                      "</span>", true);      // Love
                    AppendTextToTextArea("#resizable", "#devilSmiley",        '<span class="smiley"><img src="icons/smiley/devil.png" alt="Devil" title="Devil">',                   "</span>", true);      // Devil
                    AppendTextToTextArea("#resizable", "#angelSmiley",        '<span class="smiley"><img src="icons/smiley/angel.png" alt="Angel" title="Angel">',                   "</span>", true);      // Angel
                    AppendTextToTextArea("#resizable", "#boredSmiley",        '<span class="smiley"><img src="icons/smiley/bored.png" alt="Bored" title="Bored">',                   "</span>", true);      // Board
                    AppendTextToTextArea("#resizable", "#angrySmiley",        '<span class="smiley"><img src="icons/smiley/angry.png" alt="Angry" title="Angry">',                   "</span>", true);      // Angry
                    AppendTextToTextArea("#resizable", "#partySmiley",        '<span class="smiley"><img src="icons/smiley/party.png" alt="Party" title="Party">',                   "</span>", true);      // Party
                    AppendTextToTextArea("#resizable", "#kissSmiley",         '<span class="smiley"><img src="icons/smiley/kiss.png" alt="Kiss" title="Kiss">',                      "</span>", true);      // Kiss
                    AppendTextToTextArea("#resizable", "#shySmiley",          '<span class="smiley"><img src="icons/smiley/shy.png" alt="Shy" title="Shy">',                         "</span>", true);      // Shy
                    AppendTextToTextArea("#resizable", "#frozenSmiley",       '<span class="smiley"><img src="icons/smiley/frozen.png" alt="Frozen" title="Frozen">',                "</span>", true);      // Frozen
                    AppendTextToTextArea("#resizable", "#speechlessSmiley",   '<span class="smiley"><img src="icons/smiley/speechless.png" alt="Speechless" title="Speechless">',    "</span>", true);      // Speechless
                    AppendTextToTextArea("#resizable", "#sickSmiley",         '<span class="smiley"><img src="icons/smiley/sick.png" alt="Sick" title="Sick">',                      "</span>", true);      // Sick
                    AppendTextToTextArea("#resizable", "#ninjaSmiley",        '<span class="smiley"><img src="icons/smiley/ninja.png" alt="Ninja" title="Ninja">',                   "</span>", true);      // Ninja
                    AppendTextToTextArea("#resizable", "#beatenSmiley",       '<span class="smiley"><img src="icons/smiley/beaten.png" alt="Beaten" title="Beaten">',                "</span>", true);      // Beaten
                    AppendTextToTextArea("#resizable", "#pirateSmiley",       '<span class="smiley"><img src="icons/smiley/pirate.png" alt="Pirate" title="Pirate">',                "</span>", true);      // Pirate
                    AppendTextToTextArea("#resizable", "#clownSmiley",        '<span class="smiley"><img src="icons/smiley/clown.png" alt="Clown" title="Clown">',                   "</span>", true);      // Clown
                    AppendTextToTextArea("#resizable", "#vampireSmiley",      '<span class="smiley"><img src="icons/smiley/vampire.png" alt="Vampire" title="Vampire">',             "</span>", true);      // Vampira
                    AppendTextToTextArea("#resizable", "#millionaireSmiley",  '<span class="smiley"><img src="icons/smiley/millionaire.png" alt="Millionaire" title="Millionaire">', "</span>", true);      // Millionaire


                    $("#previewTabItem").click(ChangePreviewContent);
                });
            </script>
            
            <!-- Insert Image(s) -->
            <script type="text/javascript">
                /**
                 *  FUNCTION NAME:  positionLightBoxImage
                 *  PARAMETER:      None
                 *  RETURN:         None
                 *   
                 *  PURPOSE:        Set position of the image at the center.
                 */
                function positionLightBoxImage() {
                    var top = ($(window).height() - $('#lightbox').height()) / 2;
                    var left = ($(window).width() - $('#lightbox').width()) / 2;

                    $('#lightbox')
                        .css({
                            'top': $(document).scrollTop() + top + "px",
                            'left': left + "px"
                        })
                        .fadeIn();
                }

                /**
                 *  FUNCTION NAME:  removeLightbox
                 *  PARAMETER:      None
                 *  RETURN:         None
                 *   
                 *  PURPOSE:        Task for lightbox while disappearing.
                 */
                function removeLightbox() {
                    $('#overlay, #lightbox')
                        .fadeOut('slow', function() {
                            $(this).remove();
                            $('body').css('overflow-y', 'auto'); // show scrollbars!
                        });
                }
            </script>

            <!-- Table -->
            <script type="text/javascript">
                /**
                 *  FUNCTION NAME:  IsNumber
                 *  PARAMETER:      (string) n
                 *  RETURN:         boolean
                 *   
                 *  PURPOSE:        To check the string can be number or not
                 */
                function IsNumber(n) {
                    return !isNaN(parseFloat(n)) && isFinite(n);
                }

                $(document).ready(function() {
                    var tableCaption = $("#tableCaption"),
                        row = $("#row"),
                        column = $("#column"),
                        allFields = $([]).add(tableCaption).add(row).add(column),
                        tips = $(".tableValidateTips");
                    
                    /**
                     *  FUNCTION NAME:  updateTips
                     *  PARAMETER:      (string) t
                     *  RETURN:         None
                     *   
                     *  PURPOSE:        Highlight validation tips area if
                     *                  something went wrong.
                     */
                    function updateTips(t) {
                        tips
                                .text(t)
                                .addClass("ui-state-highlight");

                        setTimeout(function() {
                            tips.removeClass("ui-state-highlight", 1500);
                        }, 500 );
                    }

                    /**
                     *  FUNCTION NAME:  checkError
                     *  PARAMETER:      (string) row, (string) value, (string) n
                     *  RETURN:         boolean
                     *   
                     *  PURPOSE:        If given field is not an actual number,
                     *                  then add ui-state-error class on that
                     *                  and make validation tips highlighten.
                     */
                    function checkError(o, value, n) {
                        if (!IsNumber(value)) {
                            o.addClass("ui-state-error");
                            updateTips(n);

                            return false;
                        }
                        else {
                            return true;
                        }
                    }

                    $("#createNewTableDialogForm").dialog({
                        autoOpen: false,
                        height: 360,
                        width: 350,
                        modal: true,
                        buttons: {
                            "Create": function() {
                                var bValid = true;
                                    allFields.removeClass("ui-state-error");

                                    bValid = bValid && checkError(row, row.val(), "Row field should be a number");
                                    bValid = bValid && checkError(column, column.val(), "Column field should be a number");

                                // Building table according to given row and column
                                if (bValid) {
                                    var tableData = "<table>";

                                    if (tableCaption.val().length > 0) {
                                        tableData += ("<caption>" + tableCaption.val() + "</caption>");
                                    }

                                    var totalRows = row.val();
                                    var totalColumns = column.val();

                                    if ((totalRows > 0) && (totalColumns > 0)) {
                                        tableData += "<thead><tr>";

                                        for (var i = 0; i < totalColumns; i++) {
                                            tableData += "<th></th>"
                                        }

                                        tableData += "</tr></thead>";

                                        --totalRows;

                                        if (totalRows >= 1) {
                                            tableData += "<tbody>";

                                            for (; totalRows > 0; --totalRows) {
                                                tableData += "<tr>";

                                                for (var i = 0; i < totalColumns; i++) {
                                                    tableData += "<td></td>"
                                                }

                                                tableData += "</tr>";
                                            }

                                            tableData += "</tbody>";
                                        }
                                    }

                                    tableData += "</table>";

                                    $("#resizable").val($("#resizable").val() + tableData);

                                    $(this).dialog("close");
                                }
                            },
                            Close: function() {
                                $(this).dialog("close");
                            }
                        },

                        "close": function() {
                            allFields.val("").removeClass("ui-state-error");
                        }
                    });
                });
            </script>

            <!-- Insert Hyperlink -->
            <script type="text/javascript">
                $(document).ready(function() {
                    var titleLink = $("#titleLink"),
                        urlLink = $("#urlLink"),
                        allFields = $([]).add(titleLink).add(urlLink),
                        tips = $(".tableValidateTips");

                    /**
                     *  FUNCTION NAME:  updateTips
                     *  PARAMETER:      (string) t
                     *  RETURN:         None
                     *   
                     *  PURPOSE:        Highlight validation tips area if
                     *                  something went wrong.
                     */
                    function updateTips(t) {
                        tips
                                .text(t)
                                .addClass("ui-state-highlight");

                        setTimeout(function() {
                            tips.removeClass("ui-state-highlight", 1500);
                        }, 500 );
                    }

                    /**
                     *  FUNCTION NAME:  checkError
                     *  PARAMETER:      (string) row, (string) value, (string) n
                     *  RETURN:         boolean
                     *   
                     *  PURPOSE:        If given field is not an actual number,
                     *                  then add ui-state-error class on that
                     *                  and make validation tips highlighten.
                     */
                    function checkError(o, value, n) {
                        if (!checkLength(value)) {
                            o.addClass("ui-state-error");
                            updateTips(n);

                            return false;
                        }
                        else {
                            return true;
                        }
                    }

                    /**
                     *  FUNCTION NAME:  checkLength
                     *  PARAMETER:      (string) value
                     *  RETURN:         boolean
                     *   
                     *  PURPOSE:        If nothing then return false, true
                     *                  otherwise.
                     */
                    function checkLength(value) {
                        return (value.length > 0) ? true : false;
                    }

                    $("#insertNewLinkDialogForm").dialog({
                        autoOpen: false,
                        height: 300,
                        width: 350,
                        modal: true,
                        buttons: {
                            "Insert": function() {
                                var bValid = true;
                                allFields.removeClass("ui-state-error");

                                bValid = bValid && checkError(urlLink, urlLink.val(), "At least URL field is required");

                                if (bValid) {
                                    var hyperlinkData = "<a href='" + urlLink.val() + "'";

                                    if (titleLink.val().length > 0) {
                                        hyperlinkData += " title='" + titleLink.val() + "'";
                                    }

                                    hyperlinkData += ">";

                                    $("#resizable").replaceSelectedText(hyperlinkData + $("#resizable").extractSelectedText() + "</a>");

                                    $(this).dialog("close");
                                }
                            },
                            Close: function() {
                                $(this).dialog("close");
                            }
                        },

                        "close": function() {
                            allFields.val("").removeClass("ui-state-error");
                        }
                    });
                });
            </script>

            <!-- Insert Map -->
            <script type="text/javascript">
                $(document).ready(function() {
                    var imageLocationLink = $("#imageLocationLink"),
                        imageUrlLink = $("#imageUrlLink"),
                        allFields = $([]).add(imageLocationLink).add(imageUrlLink),
                        tips = $(".insertAnImageValidateTips");

                    /**
                     *  FUNCTION NAME:  updateTips
                     *  PARAMETER:      (string) t
                     *  RETURN:         None
                     *   
                     *  PURPOSE:        Highlight validation tips area if
                     *                  something went wrong.
                     */
                    function updateTips(t) {
                        tips
                                .text(t)
                                .addClass("ui-state-highlight");

                        setTimeout(function() {
                            tips.removeClass("ui-state-highlight", 1500);
                        }, 500 );
                    }

                    /**
                     *  FUNCTION NAME:  checkError
                     *  PARAMETER:      (string) row, (string) value, (string) n
                     *  RETURN:         boolean
                     *   
                     *  PURPOSE:        If given field is not an actual number,
                     *                  then add ui-state-error class on that
                     *                  and make validation tips highlighten.
                     */
                    function checkError(o, value, n) {
                        if (!checkLength(value)) {
                            o.addClass("ui-state-error");
                            updateTips(n);

                            return false;
                        }
                        else {
                            return true;
                        }
                    }

                    /**
                     *  FUNCTION NAME:  checkLength
                     *  PARAMETER:      (string) value
                     *  RETURN:         boolean
                     *   
                     *  PURPOSE:        If nothing then return false, true
                     *                  otherwise.
                     */
                    function checkLength(value) {
                        return (value.length > 0) ? true : false;
                    }

                    $("#insertAnImageDialogForm").dialog({
                        autoOpen: false,
                        height: 330,
                        width: 400,
                        modal: true
                    });

                    $("#insertAnImageTabs").tabs();


                    var insertAnImageTabClickedIndex = 0;

                    $("#uploadAnImageTabLink").click(
                            function() {
                                insertAnImageTabClickedIndex = 0;
                            }
                    );

                    $("#imageUrlTabLink").click(
                            function() {
                                insertAnImageTabClickedIndex = 1;
                            }
                    );

                    $("#insertGeoLocationDialogForm").dialog({
                        autoOpen: false,
                        height: 300,
                        width: 400,
                        modal: true
                    });

                    $("#insertGeoLocationTabs").tabs();
                });
            </script>

            <!-- Insert an Image -->
            <script type="text/javascript">
                /**
                 *  FUNCTION NAME:  IsFileExtensionCorrect
                 *  PARAMETER:      (string) fileName, (string) extension
                 *  RETURN:         boolean
                 *   
                 *  PURPOSE:        Check the file extension from a given file
                 *                  name.
                 */
                function IsFileExtensionCorrect(fileName, extension) {
                    var currentFileExtension = fileName.substring(fileName.length - extension.length);

                    if (currentFileExtension.toLowerCase() == extension.toLocaleLowerCase()) {
                        return true;
                    }

                    return false;
                }

                /**
                 *  FUNCTION NAME:  IsUploadAnImageFieldValid
                 *  PARAMETER:      None
                 *  RETURN:         boolean
                 *   
                 *  PURPOSE:        Check various way to determine the
                 *                  validation of 'upload image field'.
                 */
                function IsUploadAnImageFieldValid() {
                    var uploadImageFileLink = document.getElementById("imageLocationLink").value;
                    var errorStyle = "1px solid orange";
                    var validStyle = "1px solid #ccc";

                    // Checking if field has actualy a file name or not
                    if (uploadImageFileLink.length <= 0) {
                        document.getElementById("imageLocationLink").style.border = errorStyle;

                        return false;
                    }

                    // Checking file extension
                    if (!(IsFileExtensionCorrect(uploadImageFileLink, ".jpg") || IsFileExtensionCorrect(uploadImageFileLink, ".png") || IsFileExtensionCorrect(uploadImageFileLink, ".gif") || IsFileExtensionCorrect(uploadImageFileLink, ".bmp"))) {
                        document.getElementById("imageLocationLink").style.border = errorStyle;

                        $(document).ready(function() {
                            $("#insertAnImageDialogForm .insertAnImageValidateTips").css("color", "orange");
                        });

                        return false;
                    }
                    // When everything is correct
                    else {
                        document.getElementById("imageLocationLink").style.border = validStyle;

                        var currentText = document.getElementById("resizable").value;

                        currentText = currentText.replace(/\n/g, "<br/>");

                        document.getElementById("savedData").value = currentText;

                        return true;
                    }
                }

                /**
                 *  FUNCTION NAME:  IsUrlValid
                 *  PARAMETER:      None
                 *  RETURN:         boolean
                 *   
                 *  PURPOSE:        Checking image file URL pattern.
                 */
                function IsUrlValid() {
                    var urlValue = document.getElementById("imageUrlLink").value.trim();
                    var urlPattern = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi;
                    var errorStyle = "1px solid orange";
                    var validStyle = "1px solid #ccc";

                    if (urlPattern.test(urlValue) && (IsFileExtensionCorrect(urlValue, ".jpg") || IsFileExtensionCorrect(urlValue, ".png") || IsFileExtensionCorrect(urlValue, ".gif") || IsFileExtensionCorrect(urlValue, ".bmp"))) {
                        document.getElementById("imageUrlLink").style.border = validStyle;

                        return true;
                    }

                    document.getElementById("imageUrlLink").style.border = errorStyle;

                    $(document).ready(function() {
                        $("#insertAnImageDialogForm .insertAnImageValidateTips").css("color", "orange");
                    });

                    return false;
                }

                $(document).ready(function() {
                    $("#insertAnImageUrlButton").click(
                        function() {
                            if (IsUrlValid()) {
                                var imageUrl = document.getElementById("imageUrlLink").value.trim();
                                var title = document.getElementById("imageTitleUrl").value.trim();

                                $("#resizable").val($("#resizable").val() + "<div class='singleImage'><figure><a href='" + imageUrl + "'" + ((title.length > 0) ? " title='" + title + "'" : "") + " class='lightbox'><img src='" + imageUrl + "'" + ((title.length > 0) ? " alt='" + title + "'" : "") + "/></a>" + ((title.length > 0) ? "<figcaption>Figure: " + title + "</figcaption>" : "") + "</figure></div>");
                                $("#insertAnImageDialogForm").dialog("close");
                            }
                        }
                    );
                });
            </script>

            <!-- Photo Gallery -->
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#insertPhotoGalleryDialogForm").dialog({
                        autoOpen: false,
                        height: 230,
                        width: 350,
                        modal: true
                    });
                });
            </script>

            <script type="text/javascript">
                /**
                 *  FUNCTION NAME:  IsMultipleFilesUploadFieldValid
                 *  PARAMETER:      None
                 *  RETURN:         boolean
                 *   
                 *  PURPOSE:        Check image files scope for uploading.
                 */
                function IsMultipleFilesUploadFieldValid() {
                    var multipleFilesUploadFields = document.getElementById("photoGalleryFilesInput[]").files;

                    // If not more than 1 and if more than 20, then error
                    if ((multipleFilesUploadFields.length <= 1) || (multipleFilesUploadFields.length > 20)) {
                        $(document).ready(function() {
                            $("#insertPhotoGalleryDialogForm .insertPhotoGalleryValidateTips").css("color", "orange");
                        });

                        return false;
                    }

                    $(document).ready(function() {
                        $("#insertPhotoGalleryDialogForm .insertPhotoGalleryValidateTips").css("color", "#ccc");
                    });

                    var currentText = document.getElementById("resizable").value;

                    currentText = currentText.replace(/\n/g, "<br/>");

                    document.getElementById("otherData").value = currentText;

                    return true;
                }
            </script>

            <!-- Insert Map -->
            <script type="text/javascript">
                /**
                 *  FUNCTION NAME:  SetCoordinateFromAddress
                 *  PARAMETER:      (string) adress
                 *  RETURN:         None
                 *   
                 *  PURPOSE:        Get places' name from user and then it finds
                 *                  geo-corddinate by itself and place required
                 *                  code on the rich text editor
                 */
                function SetCoordinateFromAddress(address) {
                    var geocoder = new google.maps.Geocoder();

                    geocoder.geocode( { 'address': address }, function(results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            var coord = results[0].geometry.location;

                            $(document).ready(function() {
                                $("#resizable").val($("#resizable").val() + "<script type='text/javascript'>" + "SetMap(" + coord.lat() + ", " + coord.lng() + ");" + "</" + "script><div style='width:100%; height: 300px; border-radius: 1em; overflow: hidden;' id='" + coord.lat() + "_" + coord.lng() + "' title='" + address + "'></div>");
                            });
                        }
                        else {
                            alert("Geocode was not successful for the following reason: " + status);
                            return null;
                        }
                    });
                }

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

                $(document).ready(function() {
                    $("#insertAddressButton").click(
                        function() {
                            var address = $("#addressInput").val();

                            if (address.length > 0) {
                                $("#addressInput").css("border", "1px solid #ccc");
                                SetCoordinateFromAddress(address);

                                $("#insertGeoLocationDialogForm").dialog("close");
                            }

                            $("#addressInput").css("border", "1px solid orange");
                        }
                    );

                    $("#insertCoordinateButton").click(
                        function() {
                            var latitudeFieldValue = $("#latitudeInput").val();
                            var longitudeFieldValue = $("#longitudeInput").val();

                            if ((latitudeFieldValue.length > 0) && (longitudeFieldValue.length > 0) && IsNumber(latitudeFieldValue) && IsNumber(longitudeFieldValue)) {
                                $("#latitudeInput").css("color", "#ccc");
                                $("#longitudeInput").css("color", "#ccc");

                                $("#resizable").val($("#resizable").val() + "<script type='text/javascript'>" + "SetMap(" + latitudeFieldValue + ", " + longitudeFieldValue + ");</" + "script><div style='width:100%; height: 300px; border-radius: 1em; overflow: hidden;' id='" + latitudeFieldValue + "_" + longitudeFieldValue + "'></div>");

                                $("#insertGeoLocationDialogForm").dialog("close");
                            }
                            else {
                                $("#latitudeInput").css("color", "orange");
                                $("#longitudeInput").css("color", "orange");
                            }
                        }
                    );
                    
                });
            </script>
            
            <?php
            // While upload the images, the page needed to refreshed after
            // submitting. So, all the data that has written previously would be
            // lost. But, The text from rich text editor saves user's data and
            // return by using session.
            if (isset($_SESSION["tempData"]))
            {
                ?>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $("#resizable").val('<?php echo($_SESSION["tempData"]); ?>');
                    });
                </script>
                <?php
                
                $_SESSION["tempData"] = "";
            }
            ?>
            
            <div id="configureTabs">
                <div id="configureToolbar" class="ui-widget-header ui-corner-all">
                    <form>                        
                        <div id="editRadioSet">
                            <input type="radio" id="blogRadio" name="editRadio" checked /><label for="blogRadio">Manage Blog</label>
                            <!-- <input type="radio" id="portfolioRadio" name="editRadio" /><label for="portfolioRadio">Manage Portfolio</label> -->
                            <input type="radio" id="aboutMeRadio" name="editRadio" /><label for="aboutMeRadio">Manage About Me</label>
                            <input type="radio" id="manageAccountRadio" name="editRadio" /><label for="manageAccountRadio">Manage Account</label>
                        </div>
                    </form>
                </div>
                
                <div id="configureBlogTabOuter">
                    <div id="configureBlogTab">
                        <div>
                            <script type="text/javascript">
                                DisplayPage('configure_blog_table_view', 'blog_table_display_area', { sort_by: "published", order_format: "descending" });
                            </script>

                            <table class="darkHiveDefaultBackground">
                                <thead class="tableHeaderStyle">
                                    <tr>
                                        <!-- Select -->
                                        <th><input type="checkbox" id="selectAllBlogCheckBox" name="selectAllBlogCheckBox" /><label for="selectAllBlogCheckBox"><span></span></label></th>
                                        <!-- Title -->
                                        <th><a href="#" onclick="return OnTableHeaderClicked('title');" value="title">Title</a></th>
                                        <!-- Published -->
                                        <th><a href="#" onclick="return OnTableHeaderClicked('published');" value="published">Published</a></th>
                                        <!-- Tags -->
                                        <th><a>Tags</a></th>
                                    </tr>
                                </thead>

                                <tbody id="blog_table_display_area"></tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div id="editBlogButtonSet" class="ui-widget-header ui-corner-all">
                        <!-- New Blog -->
                        <button id="newBlogButton">New Blog</button>
                        <!-- Delete -->
                        <button id="deleteBlogButton">Delete</button>
                        <!-- Save -->
                        <button id="saveBlogButton">Save</button>
                    </div>
                    
                    <!-- ------------[ Dialog ]------------ -->

                    <div id="deleteBlogConfirmationDialog" title="Are you sure?">
                        <p>
                            <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
                            These items will be permanently deleted. Are you sure?
                        </p>
                    </div>
                    
                    <div id="deleteBlogWarningDialog" title="Warning">
                        <p>
                            <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
                            At least select a blog to delete.
                        </p>
                    </div>
                    
                    <div id="updateBlogConfirmationDialog" title="Are you sure?">
                        <p>
                            <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
                            Do you want to save changes on '<span id='updateTitleArea'></span>' blog entry?
                        </p>
                    </div>         
                </div>

                <div id="configurePortfolioTabOuter"></div>
                <div id="configureAboutMeTabOuter"></div>
                                
                <!-- Manage Account -->
                <script type="text/javascript">
                    $(document).ready(function() {                        
                        $(".validationTipStyle").hide();
                        
                        $("#changePasswordButton")
                                .button({ icons: { primary: "ui-icon-key" } })
                                .click(function() {
                                    var isCurrentPasswordFieldOk = false;
                                    var isNewPasswordFieldOk = false;
                                    var isConfirmPasswordFieldOk = false;
                            
                                    var currentPassword = $.trim($("#currentPasswordConfigureInput").val());
                                    var newPassword = $.trim($("#newPasswordConfigureInput").val());
                                    var confirmPassword = $.trim($("#confirmPasswordConfigureInput").val());
                                    
                                    // If current password field is not wrong
                                    if (currentPassword.length > 0) {
                                        isCurrentPasswordFieldOk = true;
                                        $("#currentPasswordValidationTip").slideUp();
                                    }
                                    // Otherwise
                                    else {
                                        $("#currentPasswordValidationTip").slideDown();
                                    }
                                    
                                    // If new password field is not wrong
                                    if (newPassword.length > 0) {
                                        isNewPasswordFieldOk = true;
                                        $("#newPasswordValidationTip").slideUp();
                                    }
                                    // Otherwise
                                    else {
                                        $("#newPasswordValidationTip").slideDown();
                                    }
                                    
                                    // Checking on repeat password
                                    if ((confirmPassword.length > 0) && (confirmPassword == newPassword)) {
                                        isConfirmPasswordFieldOk = true;
                                        $("#confirmPasswordValidationTip").slideUp();
                                    }
                                    // Otherwise, if wrong
                                    else if (isNewPasswordFieldOk) {
                                        $("#confirmPasswordValidationTip").slideDown();
                                    }
                                    
                                    // If everything is OK, then apply for channging password.
                                    if (isCurrentPasswordFieldOk && isNewPasswordFieldOk && isConfirmPasswordFieldOk) {
                                        DisplayPage('manage_account', 'afterChanged', { "current_pwd": currentPassword, "new_pwd": newPassword });
                                    }
                                });
                    });
                </script>
                
                <div id="configureManageAccountOuter">
                    <form onsubmit="return false;">
                        <fieldset>
                            <legend>Change Password</legend>
                            
                            <!-- Current Password -->
                            <p class="validationTipStyle" id='currentPasswordValidationTip'>Aren't you missing something?</p>
                            <label for="currentPasswordConfigureInput">Current Password:</label>
                            <input type="password" id="currentPasswordConfigureInput" name="newPasswordConfigureInput" />
                            
                            <!-- New Password -->
                            <p class="validationTipStyle" id='newPasswordValidationTip'>Please, don't be so silly...</p>
                            <label for="newPasswordConfigureInput">New Password:</label>
                            <input type="password" id="newPasswordConfigureInput" name="newPasswordConfigureInput" />
                            
                            <!-- Repeat Password -->
                            <p class="validationTipStyle" id='confirmPasswordValidationTip'>Still... passwords mismatched!!!</p>
                            <label for="confirmPasswordConfigureInput">Confirm Password:</label>
                            <input type="password" id="confirmPasswordConfigureInput" name="confirmPasswordConfigureInput" />
                            
                            <!-- Submit -->
                            <button id='changePasswordButton' name='changePasswordButton'>Change</button>
                        </fieldset>
                    </form>
                    
                    <div id='afterChanged'></div>
                </div>
                
                
                <div id="richTextTabs">
                    <ul>
                        <li id="previewTabItem"><a href="#previewTab">Preview</a></li>
                        <li id="editTabItem"><a href="#editTab">Edit</a></li>
                    </ul>

                    <div id="editTab">
                        <div>
                            <input type="hidden" id="contentId" name="contentId" />
                            <label id="blogTitleValidationTipsLabel" name="blogTitleValidationTipsLabel">How could you forgot about title?</label><br/>
                            <input type="text" class='contentStyle' id='contentTitle' name='contentTitle' placeholder="type your title here" maxlength="255" required />
                            <input type="hidden" id='contentDateTime' name='contentDateTime' />
                        </div>

                        <div>
                            <label id="blogContentValidationTipsLabel" name="blogContentValidationTipsLabel">Whats the point of publishing an article if that hasn't any content?</label>
                        </div>

                        <!-- Rich Text Editors' Toolbar -->
                        <div id="toolbar" class="ui-widget-header ui-corner-all">
                            <!-- Headers -->
                            <div id="headerDropDownMenuButton">
                                <label id="headers">Headers</label>
                                <button id="selectHeaders">Insert header</button>
                            </div>
                            <ul id="headerDropDownMenu">
                                <li id="header1" style="font-size: 16pt; font-weight: bold;"><a href="#">header 1</a></li>
                                <li id="header2" style="font-size: 14pt; font-weight: bold;"><a href="#">header 2</a></li>
                                <li id="header3" style="font-size: 12pt; font-weight: bold;"><a href="#">header 3</a></li>
                                <li id="header4" style="font-size: 11pt; font-weight: bold;"><a href="#">header 4</a></li>
                                <li id="header5" style="font-size: 10pt; font-weight: bold;"><a href="#">header 5</a></li>
                                <li id="header6" style="font-size:  9pt; font-weight: bold;"><a href="#">header 6</a></li>
                            </ul>

                            <span id="otherToolbarButtons">
                                <span class="toolbarSeparator">
                                    <!-- Bold -->
                                    <button id="bold">make text bold</button>
                                    <!-- Italic -->
                                    <button id="italic">make thing italic</button>
                                    <!-- Underline -->
                                    <button id="underline">make thing underline</button>
                                </span>

                                <span class="toolbarSeparator">
                                    <!-- Superscript -->
                                    <button id="superscript">make thing superscript</button>
                                    <!-- Subscript -->
                                    <button id="subscript">make thing subscript</button>
                                </span>

                                <span class="toolbarSeparator">
                                    <!-- Align Left -->
                                    <button id="align_left">make thing align left</button>
                                    <!-- Align Center -->
                                    <button id="align_center">make thing align center</button>
                                    <!-- Align Right -->
                                    <button id="align_right">make thing align right</button>
                                    <!-- Align Justify -->
                                    <button id="align_justify">make thing align justify</button>
                                </span>

                                <span class="toolbarSeparator">
                                    <!-- List (ordered) -->
                                    <button id="bullet_numbered">numbered bullet</button>
                                    <!-- List (unordered) -->
                                    <button id="bullet_not_numbered">not numbered bullet</button>
                                </span>

                                <span class="toolbarSeparator">
                                    <!-- Paragraph -->
                                    <button id="paragraph">new paragraph</button>
                                    <!-- Blockquote -->
                                    <button id="insert_blockquote">insert block quote</button>
                                </span>

                                <span class="toolbarSeparator">
                                    <!-- Table -->
                                    <button id="table">make a table</button>
                                    <!-- Hyperlink -->
                                    <button id="link">make thing linked</button>

                                    <div id="insertImageDropDownMenu">
                                        <!-- Insert an image -->
                                        <button id="insert_image"><img src="icons/Images.png" alt="Insert image"></button>
                                        <!-- Insert Photo Gallery -->
                                        <button id="selectImage">Insert image</button>
                                    </div>
                                    <ul id="insertImageMenu">
                                        <li id="inserAnImageMenuItem"><a href="#">Insert an image</a></li>
                                        <li id="uploadPhotoGalleryMenuItem"><a href="#">Insert photo gallery</a></li>
                                    </ul>

                                    <!-- Insert Map -->
                                    <button id="insert_geolocation">insert geolocation</button>
                                    <!-- Insert Code -->
                                    <button id="insert_code">insert code</button>
                                </span>

                                <!-- Insert Smiley -->
                                <div id="smileyDropDownMenuButton">
                                    <button id="smiley"><img src="icons/Smile.png" alt="Smiley"></button>
                                    <button id="selectSmiley">Insert smiley</button>
                                </div>
                                <table id="smileyDropDownMenu">
                                    <tr>
                                        <td id="smileSmiley"><a href="#"><img src="icons/smiley/smile.png" alt="Smile" title="Smile"></a></td>                      <!-- Smile  -->
                                        <td id="sadSmiley"><a href="#"><img src="icons/smiley/sad.png" alt="Sad" title="Sad"></a></td>                              <!-- Sad    -->
                                        <td id="tongueSmiley"><a href="#"><img src="icons/smiley/tongue.png" alt="Tongue" title="Tongue"></a></td>                  <!-- Tongue -->
                                        <td id="grinSmiley"><a href="#"><img src="icons/smiley/grin.png" alt="Grin" title="Grin"></a></td>                          <!-- Grin   -->
                                        <td id="amazedSmiley"><a href="#"><img src="icons/smiley/amazed.png" alt="Amazed" title="Amazed"></a></td>                  <!-- Amazed -->
                                        <td id="winkSmiley"><a href="#"><img src="icons/smiley/wink.png" alt="Wink" title="Wink"></a></td>                          <!-- Wink   -->
                                    </tr>

                                    <tr>
                                        <td id="laughSmiley"><a href="#"><img src="icons/smiley/laugh.png" alt="Laugh" title="Laugh"></a></td>                      <!-- Laugh  -->
                                        <td id="doubtfulSmiley"><a href="#"><img src="icons/smiley/doubtful.png" alt="Doubtful" title="Doubtful"></a></td>          <!-- Doubtful -->
                                        <td id="confusedSmiley"><a href="#"><img src="icons/smiley/confused.png" alt="Confused" title="Confused"></a></td>          <!-- Confused -->
                                        <td id="crySmiley"><a href="#"><img src="icons/smiley/cry.png" alt="Cry" title="Cry"></a></td>                              <!-- Cry    -->
                                        <td id="wackySmiley"><a href="#"><img src="icons/smiley/wacky.png" alt="Wacky" title="Wacky"></a></td>                      <!-- Wacky  -->
                                        <td id="nerdSmiley"><a href="#"><img src="icons/smiley/nerd.png" alt="Nerd" title="Nerd"></a></td>                          <!-- Nerd   -->
                                    </tr>

                                    <tr>
                                        <td id="coolSmiley"><a href="#"><img src="icons/smiley/cool.png" alt="Cool" title="Cool"></a></td>                          <!-- Cool   -->
                                        <td id="loveSmiley"><a href="#"><img src="icons/smiley/love.png" alt="Love" title="Love"></a></td>                          <!-- Love    -->
                                        <td id="devilSmiley"><a href="#"><img src="icons/smiley/devil.png" alt="Devil" title="Devil"></a></td>                      <!-- Devil  -->
                                        <td id="angelSmiley"><a href="#"><img src="icons/smiley/angel.png" alt="Angel" title="Angel"></a></td>                      <!-- Angel  -->
                                        <td id="boredSmiley"><a href="#"><img src="icons/smiley/bored.png" alt="Bored" title="Bored"></a></td>                      <!-- Bored  -->
                                        <td id="angrySmiley"><a href="#"><img src="icons/smiley/angry.png" alt="Angry" title="Angry"></a></td>                      <!-- Angry  -->
                                    </tr>

                                    <tr>
                                        <td id="partySmiley"><a href="#"><img src="icons/smiley/party.png" alt="Party" title="Party"></a></td>                      <!-- Party -->
                                        <td id="kissSmiley"><a href="#"><img src="icons/smiley/kiss.png" alt="Kiss" title="Kiss"></a></td>                          <!-- Kiss  -->
                                        <td id="shySmiley"><a href="#"><img src="icons/smiley/shy.png" alt="Shy" title="Shy"></a></td>                              <!-- Shy   -->
                                        <td id="frozenSmiley"><a href="#"><img src="icons/smiley/frozen.png" alt="Frozen" title="Frozen"></a></td>                  <!-- Frozen -->
                                        <td id="speechlessSmiley"><a href="#"><img src="icons/smiley/speechless.png" alt="Speechless" title="Speechless"></a></td>  <!-- Speechless -->
                                        <td id="sickSmiley"><a href="#"><img src="icons/smiley/sick.png" alt="Sick" title="Sick"></a></td>                          <!-- Sick   -->
                                    </tr>

                                    <tr>
                                        <td id="ninjaSmiley"><a href="#"><img src="icons/smiley/ninja.png" alt="Ninja" title="Ninja"></a></td>                      <!-- Ninja -->
                                        <td id="beatenSmiley"><a href="#"><img src="icons/smiley/beaten.png" alt="Beaten" title="Beaten"></a></td>                  <!-- Beaten -->
                                        <td id="pirateSmiley"><a href="#"><img src="icons/smiley/pirate.png" alt="Pirate" title="Pirate"></a></td>                  <!-- Pirate -->
                                        <td id="clownSmiley"><a href="#"><img src="icons/smiley/clown.png" alt="Clown" title="Clown"></a></td>                      <!-- Clown -->
                                        <td id="vampireSmiley"><a href="#"><img src="icons/smiley/vampire.png" alt="Vampire" title="Vampire"></a></td>              <!-- Vampire -->
                                        <td id="millionaireSmiley"><a href="#"><img src="icons/smiley/millionaire.png" alt="Millionaire" title="Millionaire"></a></td>  <!-- Millionaire -->
                                    </tr>
                                </table>
                            </span>
                        </div>

                        <div>
                            <textarea id="resizable" name="resizable" rows="10" cols="20" wrap></textarea>
                        </div>

                        <div>
                            <input type="text" class='contentStyle' id='contentTags' name='contentTags' placeholder="tags (example: technology, mobile, os)" />
                        </div>

                        <!-- Dialogs -->
                        
                        <!-- Dialog: Create New Table -->
                        <div id="createNewTableDialogForm" title="Create new table">
                            <p class="tableValidateTips">At least row and column fields are required.</p>

                            <form>
                                <fieldset>
                                    <label for="tableCaption">Table Caption</label>
                                    <input type="text" name="tableCaption" id="tableCaption" class="text ui-widget-content ui-corner-all" />
                                    <label for="row">Row</label>
                                    <input type="number" name="row" id="row" class="text ui-widget-content ui-corner-all" min="1" value="1" required="" />
                                    <label for="column">Column</label>
                                    <input type="number" name="column" id="column" class="text ui-widget-content ui-corner-all" min="1" value="1" required />
                                </fieldset>
                            </form>
                        </div>

                        <!-- Dialog: Insert New Link -->
                        <div id="insertNewLinkDialogForm" title="Hyperlink">
                            <p class="insertNewLinkValidateTips">At least URL field is required.</p>

                            <form>
                                <fieldset>
                                    <label for="titleLink">Title</label>
                                    <input type="text" name="titleLink" id="titleLink" class="text ui-widget-content ui-corner-all" />
                                    <label for="urlLink">Hyperlink</label>
                                    <input type="url" name="urlLink" id="urlLink" class="text ui-widget-content ui-corner-all" required />
                                </fieldset>
                            </form>
                        </div>

                        <!-- Dialog: Insert an Image -->
                        <div id="insertAnImageDialogForm" title="Insert an image">
                            <p class="insertAnImageValidateTips">Only JPEG, PNG, GIF, BMP files are allowed</p>

                            <div id="insertAnImageTabs">
                                <ul>
                                    <li id="uploadAnImageTabLink"><a href="#uploadAnImage">Upload an image</a></li>
                                    <li id="imageUrlTabLink"><a href="#imageUrl">Image URL</a></li>
                                </ul>

                                <div id="uploadAnImage">
                                    <form action="index.php#?section=configure" method="post" enctype="multipart/form-data" onsubmit="return IsUploadAnImageFieldValid();">
                                        <fieldset>
                                            <label for="imageTitleLocal">Title</label>
                                            <input type="text" name="imageTitleLocal" id="imageTitleLocal" class="text ui-widget-content ui-corner-all" />
                                            <label for="imageLocationLink">Image location</label>
                                            <input type="file" name="imageLocationLink" id="imageLocationLink" class="text ui-widget-content ui-corner-all" required />
                                        </fieldset>

                                        <div>
                                            <input type="submit" id="insertAnImageSubmitButton" name="insertAnImageSubmitButton" class="insertAnImageButtonStyle" value="Upload" />
                                        </div>

                                        <input type="hidden" name="savedData" id="savedData" />
                                    </form>
                                </div>

                                <div id="imageUrl">
                                    <form>
                                        <fieldset>
                                            <label for="imageTitleUrl">Title</label>
                                            <input type="text" name="imageTitleUrl" id="imageTitleUrl" class="text ui-widget-content ui-corner-all" />
                                            <label for="imageUrlLink">Image URL</label>
                                            <input type="url" name="imageUrlLink" id="imageUrlLink" class="text ui-widget-content ui-corner-all" required />
                                        </fieldset>

                                        <div>
                                            <input type="button" id="insertAnImageUrlButton" name="insertAnImageUrlButton" class="insertAnImageButtonStyle" value="Insert" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Dialog: Insert Photo Gallery -->
                        <div id="insertPhotoGalleryDialogForm" title="Insert Multiple Images">
                            <p class="insertPhotoGalleryValidateTips">You can select minimum 2 and maximum 20 files for uploading</p>

                            <form action="index.php#?section=configure" method="post" enctype="multipart/form-data" onsubmit="return IsMultipleFilesUploadFieldValid();">
                                <fieldset>
                                    <label for="photoGalleryFilesInput">Choose Files</label>
                                    <input type="file" name="photoGalleryFilesInput[]" id="photoGalleryFilesInput[]" class="text ui-widget-content ui-corner-all" multiple required />
                                </fieldset>

                                <div>
                                    <input type="submit" id="photoGalleryUploadButton" name="photoGalleryUploadButton" class="insertAnImageButtonStyle" value="Upload" />
                                </div>

                                <input type="hidden" name="otherData" id="otherData" />
                            </form>
                        </div>

                        <!-- Dialog: Insert Map -->
                        <div id="insertGeoLocationDialogForm" title="Insert Map">
                            <div id="insertGeoLocationTabs">
                                <ul>
                                    <li id="insertAddress"><a href="#addressLocationTab">Insert Address</a></li>
                                    <li id="Insert Coordinates"><a href="#coordinatesLocationTab">Insert Coordinates</a></li>
                                </ul>

                                <div id="addressLocationTab">
                                    <form onsubmit="return false;">
                                        <fieldset>
                                            <label for="addressInput">Address (eg: Dhaka)</label>
                                            <input type="text" id="addressInput" name="addressInput" class="text ui-widget-content ui-corner-all" required />
                                        </fieldset>

                                        <div>
                                            <button id="insertAddressButton" name="insertAddressButton" class="insertAnImageButtonStyle">Insert Map</button>
                                        </div>
                                    </form>
                                </div>

                                <div id="coordinatesLocationTab">
                                    <form>
                                        <label for="latitudeInput">Latitude</label>
                                        <input type="number" id="latitudeInput" name="latitudeInput" class="text ui-widget-content ui-corner-all" value="0" min="0" required />
                                        <label for="longitudeInput">Longitude</label>
                                        <input type="number" id="longitudeInput" name="longitudeInput" class="text ui-widget-content ui-corner-all" value="0" min="0" required />
                                    </form>

                                    <div>
                                        <button id="insertCoordinateButton" name="insertCoordinateButton" class="insertAnImageButtonStyle">Insert Map</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ------------------------------------ -->
                    
                    <!-- It shows the preview of rich text editor -->
                    <div id="previewTab">
                        <section id="previewArea"></section>
                    </div>
                </div>
            </div>
        </article>
        <?php
    }
    ?>
</section>

<?php

/**  FUNCTION NAME:  RedirectTo
 *   PARAMETER:      (string) pageNameWithoutExtension
 *   RETURN:         None
 *   
 *   PURPOSE:        If user tries to open http://<your_page_name>/configure.php
 *                   directly, then redirect to
 *                   http://<your_page_name>/index.php and open as
 *                   http://<your_page_name>/index.php#?section=configure,
 *                   otherwise continue.
 * 
 *  NOTE:            There is an another function with same name in
 *                   common_tools.php, but its functionality is different
 *                   from that one. This function is only optimized for this
 *                   page.
 **/
function RedirectTo($pageNameWithoutExtension)
{
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

    if ($_SERVER['REQUEST_URI'] === ($uri . "/" . $pageNameWithoutExtension . ".php"))
    {
        /* Redirect to a different page in the current directory that was requested */
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'index.php#?section=' . $pageNameWithoutExtension;
        header("Location: http://$host$uri/$extra");
        exit;
    }
}

?>