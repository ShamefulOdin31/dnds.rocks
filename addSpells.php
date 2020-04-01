<?php
    require "connect.php"; 
    session_start();

    $spellDetails = file_get_contents("http://dnd5eapi.co/api/spells/" . $_GET['type']);
    $spellDetailsOutput = json_decode($spellDetails, true);
    
    $typeOutput = $_SESSION['type'];

    $characterID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $spellIndex = $spellDetailsOutput['index'];
    $spellLevel = $spellDetailsOutput['level'];
    $spellName = $spellDetailsOutput['name'];

    $query = "INSERT INTO spells (characterID, spellIndex, spellLevel, spellName) VALUES (:characterID, :spellIndex, :spellLevel, :spellName)";

    $header = "location: spells.php?characterID=" . $characterID . "&type=" . $typeOutput;

    if($statement = $db->prepare($query))
    {
        echo $characterID;
        echo $typeOutput;
        echo $spellIndex;
        echo $spellLevel;
        echo $spellName;

        $statement->bindParam("characterID", $characterID);
        $statement->bindParam(":spellIndex", $spellIndex);
        $statement->bindParam(":spellLevel", $spellLevel);
        $statement->bindParam(":spellName", $spellName);
        echo "prepare";

        if($statement->execute())
        {
            unset($_SESSION['type']);
            header($header);
        }
    }
?>