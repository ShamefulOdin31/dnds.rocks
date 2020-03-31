<?php 
    require "connect.php";
    session_start();

    if($_SESSION['adminLogedIn'] !== true || !isset($_SESSION['adminLogedIn']))
    {
        header("location: admin.php?id=1&type=name");
    }

    $loginID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $characterQuery = "DELETE FROM dndcharacters WHERE userOwner = :loginID";
    $statement = $db->prepare($query);
    $statement->bindParam(":loginID", $loginID);
    $statement->execute();

    $query = "DELETE FROM logins WHERE loginID = :loginID";
    $statement = $db->prepare($query);
    $statement->bindParam(":loginID", $loginID);
    $statement->execute();

    header("location: admin.php?id=1&type=name");
?>