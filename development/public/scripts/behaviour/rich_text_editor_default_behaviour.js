$(document).ready(function() {
    // Creating tabs for Edit Panel and Preview Panel
    $("#richTextTabs").tabs();

    /* Toolbar buttons style */
    // Bold Button
    $("#bold").button({
        text: false,
        icons: {
            primary: "ui-icon-bold"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Italic Button
    $("#italic").button({
        text: false,
        icons: {
            primary: "ui-icon-italic"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Underline Button
    $("#underline").button({
        text: false,
        icons: {
            primary: "ui-icon-underline"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Superscript Button
    $("#superscript").button({
        text: false,
        icons: {
            primary: "ui-icon-superscript"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Subscript Button
    $("#subscript").button({
        text: false,
        icons: {
            primary: "ui-icon-subscript"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Align Left Button
    $("#align_left").button({
        text: false,
        icons: {
            primary: "ui-icon-align-left"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Align Center Button
    $("#align_center").button({
        text: false,
        icons: {
            primary: "ui-icon-align-center"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Allign Right Button
    $("#align_right").button({
        text: false,
        icons: {
            primary: "ui-icon-align-right"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Allign Justify Button
    $("#align_justify").button({
        text: false,
        icons: {
            primary: "ui-icon-align-justify"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Bullet Numbered Button
    $("#bullet_numbered").button({
        text: false,
        icons: {
            primary: "ui-icon-bullet-numbered"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Bullet Point Button
    $("#bullet_not_numbered").button({
        text: false,
        icons: {
            primary: "ui-icon-bullet-not-numbered"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Paragraph Button
    $("#paragraph").button({
        text: false,
        icons: {
            primary: "ui-icon-paragraph"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Insert Blockquote Button
    $("#insert_blockquote").button({
        text: false,
        icons: {
            primary: "ui-icon-insert-blockquote"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Table Button
    $("#table").button({
        text: false,
        icons: {
            primary: "ui-icon-table"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Link Button
    $("#link").button({
        text: false,
        icons: {
            primary: "ui-icon-link"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Insert Geolocation Button
    $("#insert_geolocation").button({
        text: false,
        icons: {
            primary: "ui-icon-insert-geolocation"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Insert Code Button
    $("#insert_code").button({
        text: false,
        icons: {
            primary: "ui-icon-insert-code"
        }
    }).css({ "width": "32px", "height": "32px" });

    // Headers Toolbar Menu Style
    $("#headers")
        .button()
        .next()
        .button({
            text: false,
            icons: {
                primary: "ui-icon-triangle-1-s"
            }
        })
        .click(function() {
            var menu = $(this).parent().next().show().position({
                my: "left top",
                at: "left bottom",
                of: this
            });
            $(document).one("click", function() {
                menu.hide();
            });
            return false;
        })
        .parent()
        .buttonset()
        .next()
        .hide()
        .menu();

    // Insert Image Menu Style
    $("#insert_image")
        .button()
        .next()
        .button({
            text: false,
            icons: {
                primary: "ui-icon-triangle-1-s"
            }
        })
        .click(function() {
            var menu = $(this).parent().next().show().position({
                my: "left top",
                at: "left bottom",
                of: this
            });
            $(document).one("click", function() {
                menu.hide();
            });
            return false;
        })
        .parent()
        .buttonset()
        .next()
        .hide()
        .menu();

    // Smiley Menu Style
    $("#smiley")
        .button()
        .next()
        .button({
            text: false,
            icons: {
                primary: "ui-icon-triangle-1-s"
            }
        })
        .click(function() {
            var menu = $(this).parent().next().show().position({
                my: "left top",
                at: "left bottom",
                of: this
            });
            $(document).one("click", function() {
                menu.hide();
            });
            return false;
        })
        .parent()
        .buttonset()
        .next()
        .hide()
        .menu();

    $("#resizable").resizable({
        handles: "se"
    });
});