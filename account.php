<?php 
    require "connect.php";
    session_start();
    $dndCharacters;

    $query = "SELECT * FROM dndcharacters WHERE userOwner = :loginID";

    if(!isset($_SESSION['loggedin']) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php");
        exit;
    }

    if(!isset($_GET['id']) && !isset($_GET['type']))
    {
        header("location: account.php?id=1&type=name");
    }

    else
    {
        $sort = ["name" => " ORDER BY cname",
             "race" => " ORDER BY race",
             "class" => " ORDER BY class",
             "background" => " ORDER BY background"];


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
        else
        {
            http_response_code(404);
            die();
        }
    }
    

    

    $statement = $db->prepare($query);
    $statement->bindParam(":loginID", $_SESSION["loginid"]);
    $statement->execute();
    $dndCharacters = $statement->fetchAll();

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $search = filter_input(INPUT_POST, "search", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $_SESSION['searchType'] = $type;
        $_SESSION['search'] = $search;

        header("location: search.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
        crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" 
        crossorigin="anonymous"></script>
</head>
<body>
<?php require "header.php" ?>
<!-- Start of content -->
<div class="container">
    <h1>Welcome <?= htmlspecialchars($_SESSION["username"]) ?></h1>
    <p>Your characters are listed here</p>
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col"><a href="account.php?id=1&type=name">Name</a></th>
                <th scope="col"><a href="account.php?id=2&type=race">Race</a></th>
                <th scope="col"><a href="account.php?id=3&type=class">Class</a></th>
                <th scope="col"><a href="account.php?id=4&type=background">Background</a></th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($dndCharacters as $key => $value) :?>
                <tr>
                    <th scope="row"><?= $value["cname"] ?></th>
                    <td><?= $value['race'] ?></td>
                    <td><?= $value['class'] ?></td>
                    <td><?= $value['background'] ?></td>
                    <td><a href="select.php?characterID=<?= $value['characterID'] ?>&type=<?= str_replace(' ', '-', $value['searchBy']) ?>">Select</a></td>
                    <td><a href="spells.php?characterID=<?= $value['characterID'] ?>&type=<?= str_replace(' ', '-', $value['searchBy']) ?>">Spells</a></td>
                    <td><a href="editCharacter.php?characterID=<?= $value['characterID'] ?>&type=<?= str_replace(' ', '-', $value['searchBy']) ?>">Edit</a></td>
                    <td><a href="deleteCharacter.php?characterID=<?= $value['characterID'] ?>&type=<?= str_replace(' ', '-', $value['searchBy']) ?>">Delete</a></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <div class="form-group">
        <a class="btn btn-warning" href="resetpassword.php"> Reset your password</a>
    </div>
    <?php if(isset($_SESSION['admin'])):?>
        <div class="form-group">
            <a class="btn btn-warning" href="admin.php?id=1&type=name"> Admin Page</a>
        </div>
    <?php endif?>
</div>
</body>
</html>