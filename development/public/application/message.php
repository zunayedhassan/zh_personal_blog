<?php
/**
 * FILE NAME:       message.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            September 25, 2013
 * LAST EDITED:     October 14, 2013 04:26 PM
 * 
 * PURPOSE:         To display messgae
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/

class Message
{
    // Properties
    public static $MESSAGE_ID = 0;

    private $_title = null,
            $_message = null,
            $_icon = null;

    /**  Constructor  
     * 
     *   PARAMETER:      (string) title, (string) message, (string) icon
     *   PURPOSE:        Set some properties for message
     **/
    function __construct($title, $message, $icon)
    {
        $this->_title = $title;
        $this->_message = $message;
        $this->_icon = $icon;
        Message::$MESSAGE_ID++;
    }

    /**  METHOD NAME:    ShowMessage
     *   PARAMETER:      None
     *   RETURN:         None
     *   ACESS TYPE:     Public
     *   
     *   PURPOSE:        To display message
     **/
    public function ShowMessage()
    {
        echo('<link rel="stylesheet" href="scripts/behaviour/themes/base/jquery.ui.all.css">
            <link rel="stylesheet" href="scripts/behaviour/css/dark-hive/jquery-ui-1.10.3.custom.css">
            <script type="application/javascript" src="scripts/jquery-2.0.3.js"></script>
            <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.core.js"></script>
            <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.widget.js"></script>
            <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.mouse.js"></script>
            <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.button.js"></script>
            <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.draggable.js"></script>
            <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.position.js"></script>
            <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.effect.js"></script>
            <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.dialog.js"></script>
            <script type="text/javascript" src="scripts/behaviour/ui/jquery.ui.resizable.js"></script>

            <script type="text/javascript">
            $(document).ready(function() {
                $("#dialogMessage' . Message::$MESSAGE_ID . '").dialog({
                        autoOpen: false,
                        modal: true,
                        buttons: {
                            "Ok": function() {
                                $(this).dialog("close");
                            }
                        }
                    }
                );

                $("#dialogMessage' . Message::$MESSAGE_ID . '").css("font-size", "62.5%");
                $(".ui-dialog-title").css("font-size", "62.5%");
                $(".ui-button-text").css("font-size", "62.5%");
            });
        </script>
            <div id="dialogMessage' . Message::$MESSAGE_ID . '" title="' . $this->_title . '">
            <p>
                <span class="ui-icon ' . $this->_icon . '" style="float:left; margin:0 7px 50px 0;"></span>
                ' . $this->_message . '
            </p>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#dialogMessage' . Message::$MESSAGE_ID . '").dialog("open");
            });
        </script>
        ');
    }
}
?>