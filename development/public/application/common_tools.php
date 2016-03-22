<?php
/**
 * FILE NAME:       common_tools.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            September 25, 2013
 * LAST EDITED:     October 14, 2013 03:20 PM
 * 
 * PURPOSE:         Provides various functionalities which are most common
 *                  on almost every pages.
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/

    // Importing external library
    include("message.php");
	
    /**  FUNCTION NAME:  PerformLogin
     *   PARAMETER:      (string) userFiledName, (string) passwordFieldName, (string) submitButtonName
     *   RETURN:         None
     *   
     *   PURPOSE:        To provide login, logout and session timing functionality
     **/
    function PerformLogin($userFieldName, $passwordFieldName, $submitButtonName)
    {
        // Login
        if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST[$submitButtonName]) && ($_POST[$submitButtonName] == "Sign In"))
        {
            Login($userFieldName, $passwordFieldName);
        }
        // Logout
        else if (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST[$submitButtonName]) && ($_POST[$submitButtonName] == "Sign Out"))
        {
            Logout();
        }
        // If already logged in
        else if (isset($_SESSION["logged_in"]))
        {
            CheckSessionTimeout();

            // Do things, when you are already logged in...
        }
    }


    /**  FUNCTION NAME:  Login
     *   PARAMETER:      (string) userFieldName, (string) passwordFieldName
     *   RETURN:         None
     *   
     *   PURPOSE:        To make login process including password checking from
     *                   database and session timing.
     **/
    function Login($userFieldName, $passwordFieldName)
    {
        $userName = htmlentities(trim($_POST[$userFieldName]));
        $password = htmlentities(trim($_POST[$passwordFieldName]));

        if (((strlen($userName) >= Settings::$MIN_LOGIN_NAME_LENGTH) && (strlen($userName) <= Settings::$MAX_LOGIN_NAME_LENGTH)) && ((strlen($password) >= Settings::$MIN_PASSWORD_LENGTH) && (strlen($password) <= Settings::$MAX_PASSWORD_LENGTH)) && preg_match(Settings::$LOGIN_PATTERN, $userName))
        {
            // Login validation is completed now. Now check database...
            $database = new Database();
            $result = $database->GetQueryResult("SELECT CAST(AES_DECRYPT(password, '" . Settings::$AES_KEY . "') AS CHAR) AS decrypted_password FROM login_info WHERE login_name = '". $userName . "';", true);

            if ((strlen($result["decrypted_password"]) > 0) && ($result["decrypted_password"] === $password))
            {
                CheckSessionTimeout();

                // Saving to session
                $_SESSION["username"] = $userName;
                $_SESSION["password"] = $result["decrypted_password"];
                $_SESSION["logged_in"] = true;
                session_write_close();

                // Do things after login is successful
            }
            else
            {
                $message = new Message("Error", "Please, type your username and password correctly.", "ui-icon-closethick");
                $message->ShowMessage();
            }
        }
        else
        {
            $message = new Message("Error", "Please, type your username and password correctly.", "ui-icon-closethick");
            $message->ShowMessage();
        }
    }

    /**  FUNCTION NAME:  Logout
     *   PARAMETER:      None
     *   RETURN:         None
     *   
     *   PURPOSE:        To make logout process
     **/
    function Logout()
    {
        $_SESSION = array();
        session_unset();
        session_destroy();

        // Do things after you successfully logged out...

        // Redirect to index.php
        header($_SERVER['PHP_SELF']);
    }

    /**  FUNCTION NAME:  CheckSessionTimeout
     *   PARAMETER:      None
     *   RETURN:         None
     *   
     *   PURPOSE:        To keep track of session timing
     **/
    function CheckSessionTimeout()
    {
        $currentTime = time();

        if (!isset($_SESSION["start_time"]))
        {
            $_SESSION["start_time"] = time();
        }

        // If user wanted to remember the session, then remember session
        // for 30 days.
        if (isset($_POST["rememberMe"]))
        {
            $_SESSION["timeout_interval"] = Settings::$LOGIN_SESSION_DURATION_IF_REMEMBER_IS_ON;
        }
        // Otherwise remember him for 10 minitues
        else if (!isset($_SESSION["timeout_interval"]))
        {
            $_SESSION["timeout_interval"] = Settings::$LOGIN_SESSION_DURATION_DEFAULT;
        }
        
        // If session timed out, then logout
        if (($currentTime - $_SESSION["start_time"]) > $_SESSION["timeout_interval"])
        {
            Logout();
        }
    }


    /**  FUNCTION NAME:  HelpToRememberPassword
     *   PARAMETER:      (string) emailFieldName, (string) forgotPasswordButtonName
     *   RETURN:         None
     *   
     *   PURPOSE:        If user forget his own password, then providing his
     *                   actual email address will help him to remeber his
     *                   forgotten user name and password.
     **/
    function HelpToRememberPassword($emailFieldName, $forgotPasswordButtonName)
    {
        if (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST[$forgotPasswordButtonName]))
        {
            $emailAddress = htmlentities(trim($_POST[$emailFieldName]));

            // Checking for email address pattern
            if (preg_match(Settings::$EMAIL_ADDRESS_VALIDATION_PATTERN, $emailAddress))
            {
                    // now check on database...
                    $database = new Database();
                    $result = $database->GetQueryResult("SELECT login_name, CAST(AES_DECRYPT(password, '" . Settings::$AES_KEY . "') AS CHAR) AS decrypted_password, email FROM login_info WHERE email = '" . $emailAddress . "';", true);

                    // Match the original email address from database and 
                    // given email message. If they are matched then email
                    // user name and password
                    if ((strlen($result["login_name"]) && strlen($result["decrypted_password"]) && strlen($result["email"])) && ($emailAddress === $result["email"]))
                    {
                        // Email that...
                        include("email.php");

                        $email = new Email($result["email"], "Password Recovery", ("Dear user,\nIt seems you have forgotten your login information. So as you requested, here is your \n\n\tUSER NAME: " . $result["login_name"] . "\n\tPASSWORD: ". $result["decrypted_password"] . "\n\nPlease, keep this safely.\n\nThank you\nhttp://zunayedhassan.3owl.com"));

                        $email->SendMessage();
                    }
                    // Otherwise, show error message
                    else
                    {
                        $message = new Message("Wrong email address", "It seems, you have forgotten your email address too.", "ui-icon-closethick");
                        $message->ShowMessage();
                    }
            }
            // If its not even an email address, then show error message
            else
            {
                $message = new Message("Wrong email address", "It seems, you have forgotten your email address too.", "ui-icon-closethick");
                $message->ShowMessage();
            }
        }
    }
    
    /**  FUNCTION NAME:  GetContentForPages
     *   PARAMETER:      (int) totalNumberOfPost, (list) blogIdentificationNumbers
     *   RETURN:         (array(array())) pages OR, (boolean) false
     *   
     *   PURPOSE:        It takes blog ids and arrange them for pages in
     *                   orderly manner.
     **/
    function GetContentForPages($totalNumberOfPost, $blogIdentificationNumbers)
    {
        if ($totalNumberOfPost > Settings::$MAX_BLOG_CONTENT_SHOW_PER_PAGE)
        {
            $pages = array();
            $blogIndex = 0;
            $pageIndex = 0;
            array_push($pages, array());

            foreach ($blogIdentificationNumbers as $id)
            {            
                if ($blogIndex == Settings::$MAX_BLOG_CONTENT_SHOW_PER_PAGE)
                {
                    $blogIndex = 0;
                    $pageIndex++;
                    array_push($pages, array());
                }

                array_push($pages[$pageIndex], $id["blog_id"]);
                $blogIndex++;
            }

            return $pages;
        }

        return false;
    }

    /**  FUNCTION NAME:  GetContentsFromPage
     *   PARAMETER:      (array(array())) pages, (int) pageNumber
     *   RETURN:         (list) blogIds OR, null
     *   
     *   PURPOSE:        It takes page number and return articles for that
     *                   particular page.
     **/
    function GetContentsFromPage($pages, $pageNumber)
    {
        if (($pageNumber > 0) and ($pageNumber <= count($pages)))
        {
            --$pageNumber;
            $sqlCommand = "";

            for ($i = 0; $i < count($pages[$pageNumber]) - 1; $i++)
            {
                $sqlCommand .= "blog_id=" . $pages[$pageNumber][$i] . " OR ";
            }

            $sqlCommand .= "blog_id=" . $pages[$pageNumber][count($pages[$pageNumber]) - 1] . " ";

            $db = new Database();
            $blogs = $db->GetQueryResult("SELECT * FROM blog WHERE " . $sqlCommand . " ORDER BY published DESC;");

            return $blogs;
        }

        return null;
    }
    
    /**  FUNCTION NAME:  RedirectTo
     *   PARAMETER:      (string) pageNameWithoutExtension
     *   RETURN:         None
     *   
     *   PURPOSE:        If user directly wanted access any php file, it will
     *                   redirect him through index.php page.
     * 
     *                   For example, if user wants to visit, http://<your_web
     *                   site_name>/somthing.php, then it will redirect to
     *                   http://<your_website_name>/index.php?section=something
     * 
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