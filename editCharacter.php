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

    //For dropdown races and classes
    // Gets and decodes the race data from the api
    $raceJSON = file_get_contents('http://www.dnd5eapi.co/api/races');
    $races = json_decode($raceJSON, true);

    // Gets and decodes the class data from the api
    $classJSON = file_get_contents('http://www.dnd5eapi.co/api/classes');
    $classes = json_decode($classJSON, true);

    $cname = filter_input(INPUT_POST, 'cname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $race = filter_input(INPUT_POST, 'race', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $class = filter_input(INPUT_POST, 'class', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $background = filter_input(INPUT_POST, 'background', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $nameError = "";
    $raceError = "";
    $classError = "";
    $backgroundError = "";
    $searchError = "";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {

        $strength = filter_input(INPUT_POST, 'strength', FILTER_SANITIZE_NUMBER_INT);
        $intelligence = filter_input(INPUT_POST, 'intelligence', FILTER_SANITIZE_NUMBER_INT);
        $dexterity = filter_input(INPUT_POST, 'dexterity', FILTER_SANITIZE_NUMBER_INT);
        $wisdom = filter_input(INPUT_POST, 'wisdom', FILTER_SANITIZE_NUMBER_INT);
        $constitution = filter_input(INPUT_POST, 'constitution', FILTER_SANITIZE_NUMBER_INT);
        $charisma = filter_input(INPUT_POST, 'charisma', FILTER_SANITIZE_NUMBER_INT);
        $hitPoints = filter_input(INPUT_POST, 'hitpoints', FILTER_SANITIZE_NUMBER_INT);

        if(empty(trim($background)))
        {
            $backgroundError = "Please enter a valid background";
        }


        if(empty(trim($search)))
        {
            $searchError = "Please enter in a value. Use your characters name if you can't decide";
        }

        if(empty($backgroundError) && empty($notesError))
        {
            $updateQuery = "UPDATE dndcharacters SET cname = :cname, 
                                                        race=:race, 
                                                        class = :class,
                                                        background = :background, 
                                                        strength = :strength, 
                                                        intelligence = :intelligence, 
                                                        dexterity = :dexterity, 
                                                        wisdom = :wisdom, 
                                                        constitution = :constitution,
                                                        charisma = :charisma, 
                                                        hitpoints = :hitpoints
                                                        WHERE characterID = :characterID";

            if($updateStatement = $db->prepare($updateQuery))
            {
                $updateStatement->bindParam(":cname", $cname);
                $updateStatement->bindParam(":race", $race);
                $updateStatement->bindParam(":class", $class);
                $updateStatement->bindParam(":background", $background);
                $updateStatement->bindParam(":strength", $strength);
                $updateStatement->bindParam(":intelligence", $intelligence);
                $updateStatement->bindParam(":dexterity", $dexterity);
                $updateStatement->bindParam(":wisdom", $wisdom);
                $updateStatement->bindParam(":constitution", $constitution);
                $updateStatement->bindParam(":charisma", $charisma);
                $updateStatement->bindParam(":hitpoints", $hitPoints);
                $updateStatement->bindParam(":characterID", $characterID);

                if($updateStatement->execute())
                {
                    
                    header("location: account.php");
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Character</title>
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
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"])?> ?characterID=<?= $characterID ?>&type=<?= $search ?>" method="post">
        <div class="row form-group">
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
                                <th scope="row"><input class="form-control" type="text" name="cname" id="cname" value="<?= $value['cname'] ?>"></th>
                                <span class="help-block"><?= $nameError ?></span>
                                <th scope="row">
                                    <select name="race" id="race" class="form-control">
                                        <option data-placeholder="true" value="<?= $value['race'] ?>"><?= $value['race'] ?></option>
                                            <?php foreach($races['results'] as $key => $value2) :?>
                                                <option value="<?= $value2['name'] ?>"><?= $value2['name'] ?></option>
                                            <?php endforeach ?>
                                    </select>
                                    <span class="help-block"><?= $raceError ?></span>
                                </th>
                                <th scope="row">
                                    <select name="class" id="class" class="form-control">
                                        <option data-placeholder="true" value="<?= $value['class'] ?>"><?= $value['class'] ?></option>
                                        <?php foreach($classes['results'] as $key => $value3) :?>
                                            <option value="<?= $value3['name'] ?>"><?= $value3['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                    <span class="help-block"><?= $classError ?></span>
                                </th>
                                <th scope="row"><input class="form-control" type="text" name="background" id="background" value="<?= $value['background'] ?>"></th>
                                <span class="help-block"><?= $backgroundError ?></span>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="col form-group">
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
                                <th scope="col-xs-1"><input class="form-control" type="number" name="strength" id="strength" value="<?= $value['strength'] ?>"></th>
                            <?php endforeach ?>
                        </tr>
                        <tr>
                            <?php foreach($queryResults as $key => $value):?>
                                <th scope="col">Intelligence</th>
                                <th scope="col-xs-1"><input class="form-control" type="number" name="intelligence" id="intelligence" value="<?= $value['intelligence'] ?>"></th>
                            <?php endforeach ?>
                        </tr>
                        <tr>
                            <?php foreach($queryResults as $key => $value):?>
                                <th scope="col">Dexterity</th>
                                <th scope="col-xs-1"><input class="form-control" type="number" name="dexterity" id="dexterity" value="<?= $value['dexterity'] ?>"></th>
                            <?php endforeach ?>
                        </tr>
                        <tr>
                            <?php foreach($queryResults as $key => $value):?>
                                <th scope="col">Wisdom</th>
                                <th scope="col-xs-1"><input class="form-control" type="number" name="wisdom" id="wisdom" value="<?= $value['wisdom'] ?>"></th>
                            <?php endforeach ?>
                        </tr>
                        <tr>
                            <?php foreach($queryResults as $key => $value):?>
                                <th scope="col">Constitution</th>
                                <th scope="col-xs-1"><input class="form-control" type="number" name="constitution" id="constitution" value="<?= $value['constitution'] ?>"></th>
                            <?php endforeach ?>
                        </tr>
                        <tr>
                            <?php foreach($queryResults as $key => $value):?>
                                <th scope="col">Charisma</th>
                                <th scope="col-xs-1"><input class="form-control" type="number" name="charisma" id="charisma" value="<?= $value['charisma'] ?>"></th>
                            <?php endforeach ?>
                        </tr>
                        <tr>
                            <?php foreach($queryResults as $key => $value):?>
                                <th scope="col">Hit Points</th>
                                <th scope="col-xs-1"><input class="form-control" type="number" name="hitpoints" id="hitpoints" value="<?= $value['hitpoints'] ?>"></th>
                            <?php endforeach ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row form-group">
            <div class="col">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Spell Name</th>
                            <th scope="col">Level</th>
                            <th scope="col">Select</th>
                            <th scope="col">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($spellResults as $key => $value):?>
                            <tr>
                                <th scope="row"><?= $value['spellName'] ?></th>
                                <th scope="row"><?= $value['spellLevel'] ?></th>
                                <td><a href="selectspell.php?url=<?= $value['spellIndex'] ?>">Select</a></td>
                                <td><a href="removeSpell.php?id=<?= $value['spellID'] ?>&characterID=<?= $characterID ?>">Remove</a></td>
                            </tr>
                        <?php endforeach?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row form-group">
            <div class="col">
                <?php foreach($uploadResults as $key => $value) :?>
                    <img src="<?= $value?>" alt="">
                    <p>test</p>
                <?php endforeach ?>
            </div>
        </div>
        <div class="form-group">
            <input class="btn btn-primary" type="submit" value="Submit">
            <input class="btn btn-default" type="reset" value="Reset">
        </div>
    </form>
</div>
</body>
</html>