<?php 
    define('DB_DSN', 'mysql:host=localhost;dbname=dndproject;charset=utf8');
    define('DB_USER', 'serveruser2');
    define('DB_PASS','richard');

    // login for first phpmyadmin login screen it
    // Username: serveruser2
    // Password: password
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