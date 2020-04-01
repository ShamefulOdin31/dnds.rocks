<?php
    require "connect.php"; 
    session_start();

    $characterID = filter_input(INPUT_GET, 'characterID', FILTER_SANITIZE_NUMBER_INT);
    $spellID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $typeOutput = $_SESSION['type'];

    $query = "DELETE FROM spells WHERE spellID = :spellID && characterID = :characterID";

    $header = "location: editCharacter.php?characterID=" . $characterID . "&type=" . $typeOutput;
    

    if($statement = $db->prepare($query))
    {
        $statement->bindParam(":characterID", $characterID);
        $statement->bindParam(":spellID", $spellID);

        if($statement->execute())
        {
            unset($_SESSION['type']);
            header($header);
        }
    }
?>