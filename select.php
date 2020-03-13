<?php 
    require "connect.php";
    session_start();

    include "utility.php";
    $navbarLeft = navbarArray("l", $db);
    $navbarRight = navbarArray("r", $db);

    if(!isset($_SESSION['loggedin']) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php");
        exit;
    }

    $queryResults;

    $query = "SELECT cname, race, class, background, notes, userOwner, strength, intelligence, dexterity, wisdom, constitution, charisma, hitpoints FROM dndCharacters WHERE characterID = :characterID";
    $characterID = filter_input(INPUT_GET, 'characterID', FILTER_SANITIZE_NUMBER_INT);

    $statement = $db->prepare($query);
    $statement->bindParam(":characterID", $characterID);
    $statement->execute();
    $queryResults = $statement->fetchAll();

    // For image Display
    $images = "SELECT uploadLocation FROM uploads WHERE characterID = :characterID";
    $statement3 = $db->prepare($images);
    $statement3->bindParam(":characterID", $characterID);
    $statement3->execute();
    $uploadResults = $statement3->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
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
    <h2>Character Details</h2>
    <div class="row">
        <div class="col">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Race</th>
                        <th scope="col">Class</th>
                        <th scope="col">Background</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($queryResults as $key => $value):?>
                        <tr>
                            <th scope="row"><?= $value['cname'] ?></th>
                            <th scope="row"><?= $value['race'] ?></th>
                            <th scope="row"><?= $value['class'] ?></th>
                            <th scope="row"><?= $value['background'] ?></th>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div class="col">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="row">Stats</th>
                        <th scope="row">Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        
                        <?php foreach($queryResults as $key => $value):?>
                            <th scope="col">Strength</th>
                            <th scope="col"><?= $value['strength'] ?></th>
                        <?php endforeach ?>
                    </tr>
                    <tr>
                        <?php foreach($queryResults as $key => $value):?>
                            <th scope="col">Intelligence</th>
                            <th scope="col"><?= $value['intelligence'] ?></th>
                        <?php endforeach ?>
                    </tr>
                    <tr>
                        <?php foreach($queryResults as $key => $value):?>
                            <th scope="col">Dexterity</th>
                            <th scope="col"><?= $value['dexterity'] ?></th>
                        <?php endforeach ?>
                    </tr>
                    <tr>
                        <?php foreach($queryResults as $key => $value):?>
                            <th scope="col">Wisdom</th>
                            <th scope="col"><?= $value['wisdom'] ?></th>
                        <?php endforeach ?>
                    </tr>
                    <tr>
                        <?php foreach($queryResults as $key => $value):?>
                            <th scope="col">Constitution</th>
                            <th scope="col"><?= $value['constitution'] ?></th>
                        <?php endforeach ?>
                    </tr>
                    <tr>
                        <?php foreach($queryResults as $key => $value):?>
                            <th scope="col">Charisma</th>
                            <th scope="col"><?= $value['charisma'] ?></th>
                        <?php endforeach ?>
                    </tr>
                    <tr>
                        <?php foreach($queryResults as $key => $value):?>
                            <th scope="col">Hit Points</th>
                            <th scope="col"><?= $value['hitpoints'] ?></th>
                        <?php endforeach ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <img src="C:\xampp\htdocs\webdevproject\uploads\Warframe0001.jpg" alt="">
            <?php foreach($uploadResults as $key => $value) :?>
                <img src="<?= $value?>" alt="">
                <p>test</p>
            <?php endforeach ?>
        </div>
    </div>
</div>
</body>
</html>