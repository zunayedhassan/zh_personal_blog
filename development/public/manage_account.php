<?php
/**
 * FILE NAME:       manage_account.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            October 11, 2013
 * LAST EDITED:     October 14, 2013 11:07 AM
 * 
 * PURPOSE:         Change administrator's password
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/

// Importing required library
include("./application/database.php");
include("./application/settings.php");
include("./application/message.php");

// Creating new database
$db = new Database();
$currentPassword = null;
$newPassword = null;

$ERROR_TYPE = array(
    "CURRENT_PASSWORD_FORMAT_ERROR" => 0,
    "NEW_PASSWORD_FORMAT_ERROR" => 1
);

$currentErrors = array();

// Checking error for current password
if (isset($_POST["current_pwd"]))
{
    $tempCurrentPassword = trim($_POST["current_pwd"]);
    
    if ((strlen($tempCurrentPassword) >= Settings::$MIN_PASSWORD_LENGTH) and (strlen($tempCurrentPassword) <= Settings::$MAX_PASSWORD_LENGTH))
    {
        $currentPassword = $tempCurrentPassword;
    }
    else
    {
        array_push($currentErrors, $ERROR_TYPE["CURRENT_PASSWORD_FORMAT_ERROR"]);
    }
}

// Checking error for new password
if (isset($_POST["new_pwd"]))
{
    $tempNewPassword = trim($_POST["new_pwd"]);
    
    if ((strlen($tempNewPassword) >= Settings::$MIN_PASSWORD_LENGTH) and (strlen($tempNewPassword) <= Settings::$MAX_PASSWORD_LENGTH))
    {
        $newPassword = $tempNewPassword;
    }
    else
    {
        array_push($currentErrors, $ERROR_TYPE["NEW_PASSWORD_FORMAT_ERROR"]);
    }
}

// If everything is correct, then try to change the password
if (($currentPassword !== null) and ($newPassword !== null))
{
    // Get original password
    $originalPassword = $db->GetQueryResult("SELECT CAST(AES_DECRYPT(password, '" . Settings::$AES_KEY . "') AS CHAR) AS decrypted_password FROM login_info WHERE login_name = '" . Settings::$LOGIN_NAME . "';", true);
    $originalPassword = $originalPassword["decrypted_password"];
    
    // If current password is actually original password, then user can
    // allow to change password
    if ($originalPassword == $currentPassword)
    {
        // Change the password
        $db->GetQueryResult("UPDATE login_info SET password=AES_ENCRYPT('" . $newPassword . "', '" . Settings::$AES_KEY . "') WHERE login_name='" . Settings::$LOGIN_NAME . "';");
        
        // Now check password again for confirmation
        $originalPassword = $db->GetQueryResult("SELECT CAST(AES_DECRYPT(password, '" . Settings::$AES_KEY . "') AS CHAR) AS decrypted_password FROM login_info WHERE login_name = '" . Settings::$LOGIN_NAME . "';", true);
        $originalPassword = $originalPassword["decrypted_password"];
        
        // If password is actually changed, then show confirmation message.
        if ($originalPassword == $newPassword)
        {
            DisplayMessage("Success", "Password changed successfully", "ui-icon-check");
        }
        // Otherwise show error message
        else
        {
            DisplayMessage("Error", "Sorry, can't changed the password. Please try again.", "ui-icon-close");
        }
        
        // Remove values from password field on the parent site.
        SetClearOnManagePasswordField();
    }
    else
    {
        // Show error message
        DisplayMessage("Error", "The password you given doesn't match with the original one. So, we can't change the password.", "ui-icon-close");
        SetClearOnManagePasswordField();
    }
}
// If anything wrong on the given passwords, then show error message
else
{
    $errorReason = "Your password can't be changed for following reason(s):";
    
    if (count($currentErrors) > 0)
    {
        $errorReason .= "<ul>";
        
        foreach ($currentErrors as $error)
        {
            if ($error == $ERROR_TYPE["CURRENT_PASSWORD_FORMAT_ERROR"])
            {
                $errorReason .= "<li>Current password format problem.</li>";
            }
            else if ($error == $ERROR_TYPE["NEW_PASSWORD_FORMAT_ERROR"])
            {
                $errorReason .= "<li>New password format problem.</li>";
            }
        }
        
        $errorReason .= "</ul>";
        
        DisplayMessage("Error: Can't update password", $errorReason, "ui-icon-close");
        SetClearOnManagePasswordField();
    }
}

/** 
 * FUNCTION NAME:  SetClearOnManagePasswordField
 * PARAMETER:      None
 * RETURN:         None
 * 
 * PURPOSE:        Clear the password fields
 */
function SetClearOnManagePasswordField()
{
    ?>
    <script type="text/javascript">
        $("#currentPasswordConfigureInput").val("");
        $("#newPasswordConfigureInput").val("");
        $("#confirmPasswordConfigureInput").val("");
    </script>
    <?php
}

/** 
 * FUNCTION NAME:  DisplayMessage
 * PARAMETER:      (string) title, (string) message, (string) icon
 * RETURN:         None
 * 
 * DEPENDENCY:     jQuery, jQuery UI
 * 
 * PURPOSE:        Show message
 * 
 * NOTE:           There is another displaying message functionality
 *                 (stand alone) on message.php. But this function is better
 *                 for while using Ajax. Thats why I used this one instead of
 *                 that.
 */
function DisplayMessage($title, $message, $icon)
{
    ?>
    <script type="text/javascript">
        $("#errorMessage").css("font-size", "62.5%");
        $(".ui-dialog-title").css("font-size", "62.5%");
        $(".ui-button-text").css("font-size", "62.5%");

        $(document).ready(function() {
            $( "#errorMessage" ).dialog({
                    modal: true,
                    buttons: {
                            Ok: function() {
                                    $(this).dialog("close");
                            }
                    }
            });
        });
    </script>

    <div id="errorMessage" title="<?php echo($title); ?>">
        <p>
            <span class="<?php echo($icon); ?>" style="float:left; margin:0 7px 50px 0;"></span>
            <?php echo($message); ?>
        </p>
    </div>
    <?php
}
?>
