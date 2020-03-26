<?php 
    require "connect.php";
    require "header.php";

    $characterID = filter_input(INPUT_GET, 'characterID', FILTER_SANITIZE_NUMBER_INT);
    $characterName = str_replace('-', ' ', filter_input(INPUT_GET, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    //Verifies that the get url is consistant with the db
    $query = "SELECT cname, race, class, background, notes, userOwner, strength, intelligence, dexterity, wisdom, constitution, charisma, hitpoints, searchBy FROM dndCharacters WHERE characterID = :characterID && searchBy = :search";
    $characterID = filter_input(INPUT_GET, 'characterID', FILTER_SANITIZE_NUMBER_INT);
    $search = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //Verifies that the get url is consistant with the db
    $statement = $db->prepare($query);
    $statement->bindParam(":characterID", $characterID);
    $statement->bindParam(":search", $search);
    $statement->execute();
    $queryResults = $statement->fetchAll();

    if($statement->rowCount() == 0)
    {
        http_response_code(404);
        die();
    }

    $spellsJSON = file_get_contents("http://dnd5eapi.co/api/spells");
    $spellsOutput = json_decode($spellsJSON, true);

    $spellApi = "http://dnd5eapi.co/api/spells";
    $spellDefinitions = array();

    foreach($spellsOutput['results'] as $key => $value)
    {
        array_push($spellDefinitions, ($spellApi . $value['url']));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spells</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
		integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
		crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
		integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" 
		crossorigin="anonymous"></script>
</head>
<body>
<!-- Start of content -->
<div class="container">
    <h2>List of Spells</h2>
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Spell Name</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($spellsOutput['results'] as $key => $value) :?>
                <tr>
                    <td> <?= $value['name'] ?></td>
                    <td><a href="selectspell.php?url=<?= $value['index'] ?>">Select</a></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>