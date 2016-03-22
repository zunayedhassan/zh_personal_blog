<?php
/**
 * FILE NAME:       upload_multiple_image_files.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            September 25, 2013
 * LAST EDITED:     October 14, 2013 04:46 PM
 * 
 * PURPOSE:         To upload a multiple image files (at best 20 at a time)
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/

/**  METHOD NAME:    UploadMultipleImageFiles
 *   PARAMETER:      None
 *   RETURN:         None
 *   ACESS TYPE:     Default
 *   
 *   PURPOSE:        To upload multiple images
 **/
function UploadMultipleImageFiles()
{
    // If user pressed submit button
    if (isset($_POST["photoGalleryUploadButton"]) and isset($_FILES["photoGalleryFilesInput"]))
    {
        // Save some temporary data to the session from parent page
        if (isset($_POST["otherData"])) {
            $_SESSION["tempData"] = $_POST['otherData'];
        }

        // Default settings for file uploading
        $uploadLocation = "images/users_images/";
        $totalUploads = null;
        $showInfo = false;
        $allowed =  array('jpg', 'png', 'gif');

        // Initial variable for flex photo gallery
        $photoGalleryData = '<div class="flex">';
        $maxWidth = 500;
        $initialHeight = 150;
        $initialWidth = 0;
        $currentLeft = 0;
        $currentTop = 0;
        $totalWidth = 0;
        $separator = 7;
        $previewRatio = 1.5;
 

        echo($showInfo ? "<div><table><thead><tr><th>Preview</th><th>File Name</th><th>Size (KB)</th><th>Width (px)</th><th>Height (px)</th><th>Type</th></tr></thead><tbody>" : "");

        for ($i = 0; $i < count($_FILES["photoGalleryFilesInput"]["tmp_name"]); $i++)
        {
            $fileName = $_FILES["photoGalleryFilesInput"]["name"][$i];
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Check for file extension, if illegal extentsion, then don't upload that.
            if (in_array($extension, $allowed))
            {
                // Trying to move to the image file location on server
                if (move_uploaded_file($_FILES["photoGalleryFilesInput"]["tmp_name"][$i], $uploadLocation . basename($_FILES["photoGalleryFilesInput"]["name"][$i])))
                {
                    // The file now uploaded, so, now make flex photo gallery settings
                    
                    ++$totalUploads;

                    $tmpImageNameWithPath = $uploadLocation . $_FILES["photoGalleryFilesInput"]["name"][$i];
                    $tmpImageSize = getimagesize($tmpImageNameWithPath);

                    $size = ($_FILES["photoGalleryFilesInput"]["size"][$i] / 1024) . " KB";
                    $width = $tmpImageSize[0];
                    $height = $tmpImageSize[1];
                    $type = image_type_to_mime_type($tmpImageSize[2]);

                    echo($showInfo ? "<tr><td><img src='" . $uploadLocation . $fileName . "' width='100px' /></td>><td>" . $fileName . "</td><td>" . $size . "</td><td>" . $width . "</td><td>" . $height . "</td><td>" . $type . "</td></tr>" : "");

                    // Now you have all the required information about images that you have uploaded....
                    $initialWidth = floor(($width / $height) * $initialHeight);
                    $previewWidth = $initialWidth * $previewRatio;
                    $previewHeight = $initialHeight * $previewRatio;

                    $totalWidth += $initialWidth + $separator;

                    if ($totalWidth >= $maxWidth)
                    {
                        $totalWidth = 0;
                        $currentLeft = 0;
                        $currentTop += $initialHeight - 35;
                    }

                    $photoGalleryData .= '<figure class="galleryImage"><a class="lightbox" style="left: ' . $currentLeft . 'px; top: ' . $currentTop . 'px; width: ' . $initialWidth . 'px; height: ' . $initialHeight . ';" width="' . $previewWidth . '" height="' . $previewHeight . '" href="' . $uploadLocation . $fileName . '"><img src="' . $uploadLocation . $fileName . '" alt="' . $fileName . '" width="' . $initialWidth . 'px" /></a></figure>';

                    $currentLeft += ($initialWidth + $separator * 2);
                }
            }
            // Otherwise, show error message
            else
            {
                $fileTypeErrorMessageBox = new Message("Error", "Sorry, only JPEG, PNG, GIF and PNG files are allowed", "ui-icon-closethick");
                $fileTypeErrorMessageBox->ShowMessage();
            }
        }

        // Show confirmation message for file uploading
        if ((count($_FILES["photoGalleryFilesInput"]["tmp_name"]) - $totalUploads) > 0)
        {
            $uploadErrorMessageBox = new Message("Error", "Sorry, " . (count($_FILES["photoGalleryFilesInput"]["tmp_name"]) - $totalUploads) . " files can't be uploaded", "ui-icon-closethick");
            $uploadErrorMessageBox->ShowMessage();
        }
        // Also show error message (if any)
        else
        {
            $successMessageBox = new Message("Files uploaded successfully", $totalUploads . " files are uploaded successfully", "ui-icon-check");
            $successMessageBox->ShowMessage();
        }

        $photoGalleryData .= '</div>';

        echo($showInfo ? "</tbody></table>" : "");

        $failure = count($_FILES["photoGalleryFilesInput"]["tmp_name"]) - $totalUploads;
        echo($showInfo ? "<p><b>Success:</b> " . $totalUploads . "<br/><b>Failure:</b> " . $failure . "</p></div>" : "");

        // Save to the session along with temporary data
        $_SESSION["tempData"] .= $photoGalleryData;
    }
}
?>