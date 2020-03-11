<?php 
    define('DB_DSN', 'mysql:host=localhost;dbname=dndproject;charset=utf8');
    define('DB_USER', 'serveruser');
    define('DB_PASS','richard');

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