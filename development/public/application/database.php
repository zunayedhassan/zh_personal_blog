<?php
/**
 * FILE NAME:       database.php
 * 
 * DEPENDENCY:      settings.php
 * 
 * AUTHOR:          Mohammod Zunayed Hassan
 * EMAIL:           zunayed-hassan@live.com
 * 
 * CONTRIBUTOR:
 * 
 * DATE:            September 25, 2013
 * LAST EDITED:     October 14, 2013 03:57 PM
 * 
 * PURPOSE:         Provides database connection according to settings
 *                  and execute query.
 * 
 * CHANGES HISTORY:
 * 
 * NOTE:            
 * 
 **/

class Database
{
    /**  METHOD NAME:    GetMySqlConnection
     *   PARAMETER:      None
     *   RETURN:         (pdo) connection
     *   ACESS TYPE:     Public
     *   
     *   PURPOSE:        Return MySQL connection
     **/
    public function GetMySqlConnection()
    {
        $connection = null;

        // Creating connection
        $dsn = "mysql:host=" . Settings::$DB_HOST_NAME . ";dbname=" . Settings::$DB_NAME . ";charset=utf8";
        $userName = Settings::$DB_USER_NAME;
        $password = Settings::$DB_PASSWORD;

        try
        {
            $connection = new PDO($dsn, $userName, $password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $error)
        {
            echo("<p>ERROR: Connection failed: " . $error->getMessage() . "</p>");
        }

        return $connection;
    }

    /**  METHOD NAME:    GetMySqlConnection
     *   PARAMETER:      (string) sql, (boolean) singleResult
     *   RETURN:         (list) results OR, (object) results
     *   ACESS TYPE:     Public
     *   
     *   PURPOSE:        Return results after quering MySQL table.
     **/
    public function GetQueryResult($sql, $singleResult = false)
    {
        $connection = $this->GetMySqlConnection();
        $results = $connection->query($sql);

        return !$singleResult ? $results : $results->fetch();
    }
}

?>