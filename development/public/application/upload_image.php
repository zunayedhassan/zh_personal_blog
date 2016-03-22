<?php
/**
 * FILE NAME:       upload_image.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            September 25, 2013
 * LAST EDITED:     October 14, 2013 04:35 PM
 * 
 * PURPOSE:         To upload a single image to the server.
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/

    /**  METHOD NAME:    UploadImage
     *   PARAMETER:      None
     *   RETURN:         None
     *   ACESS TYPE:     Default
     *   
     *   PURPOSE:        To upload image on server and check that is actually
     *                   image file or not. If so, then move to required
     *                   destinition.
     **/
    function UploadImage()
    {
        $imageFolderLocation = "images/users_images/";
        
        // If user pressed on Submit button then start uploading process
        if (isset($_POST["insertAnImageSubmitButton"]))
        {   
            // Save temporarily some unsaved data
            if (isset($_POST["savedData"])) {
                $_SESSION["tempData"] = $_POST['savedData'];
            }

            if (isset($_FILES["imageLocationLink"]) and ($_FILES["imageLocationLink"]["error"] == UPLOAD_ERR_OK))
            {
                // Check for file types
                if (!(($_FILES["imageLocationLink"]["type"] == "image/jpeg") or ($_FILES["imageLocationLink"]["type"] == "image/png") or (($_FILES["imageLocationLink"]["type"] == "image/gif") or ($_FILES["imageLocationLink"]["type"] == "image/wbmp") or ($_FILES["imageLocationLink"]["type"] == "image/xbm"))))
                {
                    $fileTypeErrorDialogMessage = new Message("Error", "Only JPEG, PNG, GIF, BMP files are allowed", "ui-icon-closethick");
                    $fileTypeErrorDialogMessage->ShowMessage();
                }
                // Can't move image file then show error message
                else if(!move_uploaded_file($_FILES["imageLocationLink"]["tmp_name"], $imageFolderLocation . basename($_FILES["imageLocationLink"]["name"])))
                {
                    $uploadErrorDialogMessage = new Message("Error", "Sorry there was a problem while uploading that image.", "ui-icon-closethick");
                    $uploadErrorDialogMessage->ShowMessage();
                }
                // Otherwise, file uploading has succesully completed, show confirmation message
                else
                {
                    $uploadDialogMessage = new Message("Upload Completed", "Image has been uploaded successfully", "ui-icon-circle-check");
                    $uploadDialogMessage->ShowMessage();

                    $title = (isset($_POST["imageTitleLocal"]) and (strlen($_POST["imageTitleLocal"]) > 0)) ? $_POST['imageTitleLocal'] : null;

                    // Save some temporary data to the session
                    $_SESSION["tempData"] .= '<div class="singleImage"><figure><a href="' . $imageFolderLocation . basename($_FILES['imageLocationLink']['name']) . '" class="lightbox"><img src="' . $imageFolderLocation . basename($_FILES['imageLocationLink']['name']) . '"' . ((strlen($title) > 0) ? 'alt="' . $title . '"' : '') . " /></a>" . ((strlen($title) > 0) ? '<figcaption>Figure: ' . $title . '</figcaption>' : '') . '</figure></div>';
                }
            }
            // Otherwise, show error message
            else
            {
                // Determine error type
                $message = null;

                switch($_FILES["imageLocationLink"]["error"])
                {
                    case UPLOAD_ERR_INI_SIZE:
                        $message = "The photo is larger than the server allows.";
                        break;

                    case UPLOAD_ERR_FORM_SIZE:
                        $message = "“The photo is larger than the script allows.";
                        break;

                    case UPLOAD_ERR_NO_FILE:
                        $message = "No file was uploaded. Make sure you choose a file to upload.";
                        break;

                    default:
                        $message = "Please contact your server administrator for help.";
                }

                $uploadErrorDialogMessage = new Message("Error", "Sorry there was a problem while uploading that image. " . $message, "ui-icon-closethick");
                $uploadErrorDialogMessage->ShowMessage();
            }
        }
    }
?>