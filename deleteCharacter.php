<?php 
    require "connect.php";
    session_start();

    $characterID = filter_input(INPUT_GET, 'characterID', FILTER_SANITIZE_NUMBER_INT);
    $type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //Verifies that the characterID and the type are consistant
    $confirmation = "SELECT * FROM dndcharacters WHERE characterID = :characterID && searchBy = :type";
    $statement = $db->prepare($confirmation);
    $statement->bindParam(":characterID", $characterID);
    $statement->bindParam(":type", $type);
    $statement->execute();
    $queryResults = $statement->fetchAll();

    if($statement->rowCount() == 0)
    {
        http_response_code(404);
        die();
    }
    else
    {
        $query = "DELETE FROM dndcharacters WHERE characterID = :characterID";

        if($deleteStatement = $db->prepare($query))
        {
            $deleteStatement->bindParam(":characterID", $characterID);

            if($deleteStatement->execute())
            {
                header("location: account.php");
            }
        }
    }
?>