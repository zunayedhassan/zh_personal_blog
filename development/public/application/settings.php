<?php
/**
 * FILE NAME:       settings.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            September 25, 2013
 * LAST EDITED:     October 14, 2013 04:33 PM
 * 
 * PURPOSE:         Important settings for the site. All other pages are
 *                  depended on this page.
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/


class Settings
{
    // Settings for the http://zunayedhassan.3owl.com website
//        public static $DB_NAME      = "u760101746_dbase",
//                      $DB_USER_NAME = "u760101746_root",
//                      $DB_PASSWORD  = "zhp880528",
//                      $DB_HOST_NAME = "mysql.3owl.com";

    // Settings for the localhost
    public static $DB_NAME      = "minefield_db",
                  $DB_USER_NAME	= "root",
                  $DB_PASSWORD	= "54321",
                  $DB_HOST_NAME = "localhost";
    
    // Settings for login field validation
    public static $MIN_LOGIN_NAME_LENGTH = 1,
                  $MAX_LOGIN_NAME_LENGTH = 16,
                  $MIN_PASSWORD_LENGTH	 = 8,
                  $MAX_PASSWORD_LENGTH	 = 16,
                  $LOGIN_PATTERN         = "/^([a-z0-9]+-)*[a-z0-9]+$/i",
                  $AES_KEY		 = "welcome_to_the_real_world_neo",
                  $EMAIL_ADDRESS_VALIDATION_PATTERN = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/";

    // Settings for email field validation and other email related process
    public static $EMAIL_ADDRESS_FROM = "carelesswhisperbd@gmail.com",
                  $EMAIL_SENDER_NAME  = "zunayedhassan.3owl.com",
                  $EMAIL_PASSWORD     = "zhp.gmail",
                  $EMAIL_HOST         = "ssl://smtp.gmail.com",
                  $EMAIL_PORT         = "465";

    // Settings for the session timing and login related
    public static $LOGIN_SESSION_DURATION_IF_REMEMBER_IS_ON = 2592000,          // 60 * 60 * 24 * 30 = 1 month
                  $LOGIN_SESSION_DURATION_DEFAULT           = 600,              // 60 * 10           = 10 minute
                  $LOGIN_NAME                               = "zunayed-hassan";

    // Settings for the article
    public static $MAX_BLOG_CONTENT_SHOW_PER_PAGE = 5,
                  $PREVIEW_TAG                    = "###preview###",
                  $TOUR_TAG                       = "tour";
}

?>