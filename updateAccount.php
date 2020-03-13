<?php 
    require "connect.php";
    session_start();
    include "utility.php";
    $navbarLeft = navbarArray("l", $db);
    $navbarRight = navbarArray("r", $db);

    if($_SESSION['adminLogedIn'] !== true || !isset($_SESSION['adminLogedIn']))
    {
        header("location: admin.php");
    }

    $username = "";
    $userError = "";
    $queryUser = "";

    $loginID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $loadValue = "SELECT username FROM logins WHERE loginID = :id";
    $statement = $db->prepare($loadValue);
    $statement->bindParam(":id", $loginID);
    $statement->execute();
    $userFetch = $statement->fetchAll();
    $_SESSION['loadUsername'] = $userFetch[0]['username'];

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Validating the username
        if(empty(trim($_POST['username'])))
        {
            $userError = "Please enter a email";
        }

        elseif(!filter_input(INPUT_POST, 'username', FILTER_VALIDATE_EMAIL))
        {
            $userError = "Please enter a valid email";
        }

        else
        {
            $query = "SELECT LoginID FROM Logins WHERE username = :username";

            $statement = $db->prepare($query);
            $statement->bindParam(":username", $queryUser, PDO::PARAM_STR);
            $queryUser = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            
            $statement->execute();
            
            if($statement->rowCount() == 1)
            {
                $userError = "This username already exists";
            }
            else
            {
                $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            }
        }

        // Checks to see if the error variables are empty
        if(empty($userError))
        {
            $query = "UPDATE logins SET username = :username WHERE loginID = :loginID";

            $statement = $db->prepare($query);
            $statement->bindParam(":username", $queryUser);
            $statement->bindParam(":loginID", $loginID);
            $queryUser = $username;

            if($statement->execute())
            {
                header("location: admin.php");
            }

            else
            {
                echo "Account registration failed.";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Update Account</title>
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
    <a class="navbar-brand" href="<?= $navbarLeft[0]['navurl'] ?>"><?= $navbarLeft[0]['navItemName'] ?></a>
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
    <h2>Admin Update Account</h2>
    <p>Fill out this form to update a user account.</p>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>?id=<?= $loginID ?>" method="post">
        <div class="form-group">
            <div class="col-md-5">
                <label>Username</label>
                <input class="form-control" type="text" name="username" value="<?= $_SESSION['loadUsername'] ?>">
                <span class="help-block"><?= $userError ?></span>
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