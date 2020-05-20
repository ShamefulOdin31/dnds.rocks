<?php 
    require "connect.php";
    session_start();
    

    if($_SESSION['adminLogedIn'] !== true || !isset($_SESSION['adminLogedIn']))
    {
        header("location: admin.php");
    }

    $username = "";
    $password = "";
    $passwordConfirm = "";

    $userError = "";
    $passwordError = "";
    $confirmError = "";

    $queryUser = "";
    $queryPass = "";

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
            $query = "SELECT LoginID FROM logins WHERE username = :username";

            if($statement = $db->prepare($query))
            {
                $statement->bindParam(":username", $queryUser, PDO::PARAM_STR);
            }
            
            $queryUser = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            
            if($statement->execute())
            {
                if($statement->rowCount() == 1)
                {
                    $userError = "This username already exists";
                }
                else
                {
                    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
                }
            }

            else
            {
                echo "Error";
            }

            unset($statement);
        }

        // Validating the first password.
        if(empty(trim($_POST['password'])))
        {
            $passwordError = "Please enter a password";
        }
        else
        {
            $password = trim($_POST['password']);
        }

        // Validating the confirmation password.
        if(empty(trim($_POST['confirm_password'])))
        {
            $confirmError = "Please enter a password";
        }
        else
        {
            $passwordConfirm = trim($_POST['confirm_password']);

            // Checks to see if the password error is empty and to see if the password match
            if(empty($passwordError) && ($password != $passwordConfirm))
            {
                $confirmError = "Password do not match";
            }
        }

        // Checks to see if the error variables are empty
        if(empty($userError) && empty($passwordError) && empty($confirmError))
        {
            $query = "INSERT INTO logins (username, password) VALUES (:username, :password)";

            $statement = $db->prepare($query);
            $statement->bindParam(":username", $queryUser);
            $statement->bindParam(":password", $queryPass);

            $queryUser = $username;

            // Hashes the password
            $queryPass = password_hash($password, PASSWORD_DEFAULT, array('cost' => 9));

            if($statement->execute())
            {
                header("location: admin.php?id=1&type=name");
            }

            else
            {
                echo "Account registration failed.";
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
    <title>Admin Add Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
        crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" 
        crossorigin="anonymous"></script>
</head>
<body>
<!-- Start of content -->
<?php require "header.php"?>
<div class="container">
    <h2>Admin Add Account</h2>
    <p>Fill out this form to create a user account.</p>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <div class="form-group">
            <div class="col-md-5">
                <label>Username</label>
                <input class="form-control" type="text" name="username">
                <span class="help-block"><?= $userError ?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-5">
                <label>Password</label>
                <input class="form-control" type="password" name="password">
                <span class="help-block"><?= $passwordError ?></span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-5">
                <label>Confirm Password</label>
                <input class="form-control" type="password" name="confirm_password">
                <span class=help/block><?= $confirmError ?></span>
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