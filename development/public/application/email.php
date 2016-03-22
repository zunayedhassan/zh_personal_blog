<?php

/**
 * FILE NAME:       email.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            September 25, 2013
 * LAST EDITED:     October 14, 2013 04:18 PM
 * 
 * PURPOSE:         To help user to email
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/

// Importing required library
require_once("Mail.php");
require_once("settings.php");

// Setting up constant values
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

class Email
{
        // Properties
	private $_to,
                $_subject,
                $_body,
                $_senderEmail,
                $_senderName,
                $_sederEmailPassword,
                $_host,
                $_port;
	
        /**  Constructor  
         * 
         *   PARAMETER:      (string) toEmailAddress, (string) subject, (string) body
         *   PURPOSE:        Set some properties for email
         **/
	public function __construct($toEmailAddress, $subject, $body)
	{	
		$this->_to = $toEmailAddress;
		$this->_subject = $subject;
		$this->_body = $body;
		
		$this->_senderEmail = Settings::$EMAIL_ADDRESS_FROM;
		$this->_senderName = Settings::$EMAIL_SENDER_NAME;
		$this->_sederEmailPassword = Settings::$EMAIL_PASSWORD;
		$this->_host = Settings::$EMAIL_HOST;
		$this->_port = Settings::$EMAIL_PORT;
	}
        
	/**  METHOD NAME:    SendMessage
         *   PARAMETER:      None
         *   RETURN:         None
         *   ACESS TYPE:     Public
         *   
         *   PURPOSE:        Send email
         **/
	public function SendMessage()
	{	
		$headers = array(
			'From'    => "\"" . $this->_senderName . "\" <" . $this->_senderEmail . ">",
			'To'      => $this->_to,
			'Subject' => $this->_subject
		);
		
		$smtp = Mail::factory(
			'smtp',
			
			array(
				'host' 	   => $this->_host,
				'port' 	   => $this->_port,
				'auth' 	   => true,
				'username' => $this->_senderEmail,
				'password' => $this->_sederEmailPassword
			)
		);
		
		$mail = $smtp->send($this->_to, $headers, $this->_body);
		
                // If something is wrong, then show error message
		if (PEAR::isError($mail))
		{
                    $message = new Message("Email Sending Failed", "Sorry, Email sending failed. <b>Reason:</b> " . $mail->getMessage(), "ui-icon-closethick");
                    $message->ShowMessage();
		}
                // Otherwise, message sending succssful
		else
		{
                    $message = new Message("Success", "Email has been sent successfully.", "ui-icon-check");
                    $message->ShowMessage();
		}
	}
}

?>