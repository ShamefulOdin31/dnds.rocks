<?php 
    require "connect.php";
    session_start();

    $dndCharacters;

    if(!isset($_SESSION['loggedin']) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php");
        exit;
    }

    $query = "SELECT characterID, cname, race, class, background, notes FROM dndCharacters WHERE userOwner = :loginID";

    $sort = ["name" => " ORDER BY cname",
             "race" => " ORDER BY race",
             "class" => " ORDER BY class",
             "background" => " ORDER BY background"];


    if($_GET['sort'] == 'name')
    {
        $query .= $sort["name"];
    }

    elseif($_GET['sort'] == 'race')
    {
        $query .= $sort["race"];
    }

    elseif($_GET['sort'] == 'class')
    {
        $query .= $sort["class"];
    }

    elseif($_GET['sort'] == 'background')
    {
        $query .= $sort["background"];
    }
    else
    {
        header('HTTP/1.0 404 Not Found', true, 404);
    }

    $statement = $db->prepare($query);
    $statement->bindParam(":loginID", $_SESSION["loginid"]);
    $statement->execute();
    $dndCharacters = $statement->fetchAll();

    include "utility.php";
    $navbarLeft = navbarArray("l", $db);
    $navbarRight = navbarArray("r", $db);

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
        <h1>Welcome <?= htmlspecialchars($_SESSION["username"]) ?></h1>
        <p>Your characters are listed here</p>
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col"><a href="account.php?sort=name">Name</a></th>
                    <th scope="col"><a href="account.php?sort=race">Race</a></th>
                    <th scope="col"><a href="account.php?sort=class">Class</a></th>
                    <th scope="col"><a href="account.php?sort=background">Background</a></th>
                    <th scope="col">Notes</th>
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
                        <td><?= $value['notes'] ?></td>
                        <td><a href="select.php?characterID=<?= $value['characterID'] ?>">Select</a></td>
                        <td><a href="spells.php?characterID=<?= $value['characterID'] ?>">Spells</a></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</body>
</html>