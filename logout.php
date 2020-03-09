<?php 
    session_start();

    // Resets all session variable to null
    $_SESSION = array();

    session_destroy();

    header("location: login.php");
    exit;
?>