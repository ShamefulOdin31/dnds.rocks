<?php 
    require "connect.php";
    session_start();

    require "header.php";

    $queryResults;

    $query = "SELECT cname, race, class, background, notes, userOwner, strength, intelligence, dexterity, wisdom, constitution, charisma, hitpoints, searchBy, username, characterID FROM dndCharacters 
                JOIN logins  ON dndCharacters.userOwner = logins.loginID WHERE visibility = 'y'";

    $sort = ["name" => " ORDER BY cname",
    "race" => " ORDER BY race",
    "class" => " ORDER BY class",
    "background" => " ORDER BY background",
    "owner" => "ORDER BY userOwner"];

    if(isset($_GET['id']) && isset($_GET['type']))
    {
        if($_GET['id'] == '1' && $_GET['type'] == 'name')
        {
            $query .= $sort["name"];
        }

        elseif($_GET['id'] == '2' && $_GET['type'] == 'race')
        {
            $query .= $sort["race"];
        }

        elseif($_GET['id'] == '3' && $_GET['type'] == 'class')
        {
            $query .= $sort["class"];
        }

        elseif($_GET['id'] == '4' && $_GET['type'] == 'background')
        {
            $query .= $sort["background"];
        }
        elseif($_GET['id'] == '5' && $_GET['type'] == 'owner')
        {
            $query .= $sort["owner"];
        }

        else
        {
            http_response_code(404);
            die();
        }
    }

    $statement = $db->prepare($query);
    $statement->execute();
    $queryResults = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
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
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col"><a href="index.php?id=1&type=name">Name</a></th>
                <th scope="col"><a href="index.php?id=2&type=race">Race</a></th>
                <th scope="col"><a href="index.php?id=3&type=class">Class</a></th>
                <th scope="col"><a href="index.php?id=4&type=background">Background</a></th>
                <th scope="col">Notes</th>
                <th scope="col"><a href="index.php?id=5&type=owner">Owner</a></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($queryResults as $key => $value) :?>
                <tr>
                    <th scope="row"><?= $value["cname"] ?></th>
                    <td><?= $value['race'] ?></td>
                    <td><?= $value['class'] ?></td>
                    <td><?= $value['background'] ?></td>
                    <td><?= $value['notes'] ?></td>
                    <td><?= $value['username'] ?></td>
                    <td><a href="select.php?characterID=<?= $value['characterID'] ?>&type=<?= str_replace(' ', '-', $value['searchBy']) ?>">Select</a></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

</body>
</html>