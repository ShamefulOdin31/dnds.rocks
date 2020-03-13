<?php 
    require "connect.php";
    session_start();

    include "utility.php";
    $navbarLeft = navbarArray("l", $db);
    $navbarRight = navbarArray("r", $db);

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
    {
        header("location: index.php");
        exit;
    }
    $username = "";
    $password = "";

    $userError = "";
    $passwordError = "";

    // Only executes the below code if the method from the form is POST
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Verifies that the username box is not empty
        if(empty(trim($_POST['username'])))
        {
            $userError = "Please enter a username";
        }
        elseif(!filter_input(INPUT_POST, 'username', FILTER_VALIDATE_EMAIL))
        {
            $userError = "Please enter a valid email";
        }
        else
        {
            $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        }

        // Verifies that the password box is not empty
        if(empty(trim($_POST['password'])))
        {
            $passwordError = "Please enter a password";
        }
        else
        {
            $password = trim($_POST['password']);
        }

        // Checks to see if the error variables are empty
        if(empty($userError) && empty($passwordError))
        {
            $query = "SELECT loginID, username, password FROM Logins WHERE username = :username";

            if($statement = $db->prepare($query))
            {
                $statement->bindParam(":username", $queryUser);
                $queryUser = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

                if($statement->execute())
                {
                    if($statement->rowCount() == 1)
                    {
                        if($result = $statement->fetch())
                        {
                            $loginID = $result["loginID"];
                            $username = $result["username"];
                            $passwordHash = $result["password"];
                            if(password_verify($password, $passwordHash))
                            {
                                session_start();

                                $_SESSION["loginid"] = $loginID;
                                $_SESSION["loggedin"] = true;
                                $_SESSION["username"] = $username;
                                
                                header("location: index.php");
                            }

                            else
                            {
                                $passwordError = "The password is incorrect";
                            }
                        }
                    }

                    else
                    {
                        $userError = "No account found";
                    }
                }

                else
                {
                    echo "Error";
                }

                unset($statement);
            }
        }
        unset($db);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
<!-- Start of content-->
<div class="container">  
    <h2>Login</h2>
    <p>Please enter your login details</p>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <div class="form-group row">
            <div class="col-md-5">
                <label>Username</label>
                <input class="form-control" type="text" name="username">
                <span class="help-block"><?= $userError ?></span>
            </div>
            
        </div>
        <div class="form-group row">
            <div class="col-md-5">
                <label>Password</label>
                <input class="form-control" type="password" name="password">
                <span class="help-block"><?= $passwordError ?></span>
            </div>
        </div>
        <div class="form-group">
            <input class="btn btn-primary" type="submit" value="Login">
        </div>
    </form>
</div>
</body>
</html>