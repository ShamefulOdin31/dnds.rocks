<?php 
    define('DB_DSN', 'mysql:host=localhost;dbname=dndproject;charset=utf8');
    define('DB_USER', 'serveruser2');
    define('DB_PASS','richard');

    // password is password for phpmyadmin additional user
    try
    {
        $db = new PDO(DB_DSN, DB_USER, DB_PASS);
    }

    catch(PDOException $e)
    {
        echo "DB Error" . $e->getMessage();
        die();
    }
?>