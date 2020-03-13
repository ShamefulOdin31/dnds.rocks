<?php 
    require "connect.php";
    
    include "utility.php";
    $navbarLeft = navbarArray("l", $db);
    $navbarRight = navbarArray("r", $db);

    $characterID = filter_input(INPUT_GET, 'characterID', FILTER_SANITIZE_NUMBER_INT);

    $spellsJSON = file_get_contents("http://dnd5eapi.co/api/spells");
    $spellsOutput = json_decode($spellsJSON, true);

    $spellApi = "http://dnd5eapi.co/api/spells";
    $spellDefinitions = array();

    foreach($spellsOutput['results'] as $key => $value)
    {
        array_push($spellDefinitions, ($spellApi . $value['url']));
    }


    $spells_JSON = array();

    foreach($spellDefinitions as $key => $value)
    {
        //array_push($spells_JSON, file_get_contents($value));
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
<!-- Start of Nav -->
<nav class="navbar navbar-expand-sm bg-primary navbar-dark">
    <a class="navbar-brand" href="i<?= $navbarLeft[0]['navurl'] ?>"><?= $navbarLeft[0]['navItemName'] ?></a>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link" href="<?= $navbarLeft[1]['navurl'] ?>"><?= $navbarLeft[1]['navItemName'] ?></a>
        </li>
        <li class="nav-item">
            <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true):?>
                <a class="nav-link" href="<?= $navbarLeft[2]['navurl'] ?>"><?= $navbarLeft[2]['navItemName'] ?></a>
            <?php else :?>
                <a class="nav-link disabled" href="<?= $navbarLeft[2]['navurl'] ?>"><?= $navbarLeft[2]['navItemName'] ?></a>
            <?php endif ?>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true):?>
            <li class="nav-item">
                <a class="nav-link" href="<?= $navbarRight[0]['navurl'] ?>"><?= $navbarRight[0]['navItemName'] ?></a>
            </li>
        <?php else :?>
            <li class="nav-item">
                <a class="nav-link" href="<?= $navbarRight[1]['navurl'] ?>"><?= $navbarRight[1]['navItemName'] ?></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= $navbarRight[2]['navurl'] ?>"><?= $navbarRight[2]['navItemName'] ?></a>
            </li>
        <?php endif ?>
    </ul>
</nav>
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