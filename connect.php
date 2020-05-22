<?php 
    define('DB_DSN', 'mysql:host=localhost;dbname=dndproject;charset=utf8');
    define('DB_USER', 'richard');
    define('DB_PASS','password');

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