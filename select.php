<?php 
    require "connect.php";
    session_start();

    $queryResults;

    $query = "SELECT cname, race, class, background, notes, userOwner, strength, intelligence, dexterity, wisdom, constitution, charisma, hitpoints, searchBy FROM dndcharacters WHERE characterID = :characterID && searchBy = :search";
    $characterID = filter_input(INPUT_GET, 'characterID', FILTER_SANITIZE_NUMBER_INT);
    $search = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $_SESSION['type'] = $search;

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

    //For spell Display
    $spellQuery = "SELECT * FROM spells WHERE characterID = :characterID";
    if($spellStatement = $db->prepare($spellQuery))
    {
        $spellStatement->bindParam(":characterID", $characterID);

        if($spellStatement->execute())
        {
            $spellResults = $spellStatement->fetchAll();
        }
    }


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
    <title>Character Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
        crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" 
        crossorigin="anonymous"></script>
</head>
<body>
<?php require "header.php"?>
<!-- Start of content -->
<div class="container">
    <h2>Character Details</h2>
    <div class="row">
        <div class="col col-md-9">
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
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Spell Name</th>
                        <th scope="col">Level</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($spellResults as $key => $value):?>
                        <tr>
                            <th scope="row"><?= $value['spellName'] ?></th>
                            <th scope="row"><?= $value['spellLevel'] ?></th>
                            <td><a href="selectspell.php?url=<?= $value['spellIndex'] ?>">Select</a></td>
                        </tr>
                    <?php endforeach?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?php foreach($uploadResults as $key => $value) :?>
                <img src="<?= $value?>" alt="">
                <p>test</p>
            <?php endforeach ?>
        </div>
    </div>
</div>
</body>
</html>