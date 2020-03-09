<?php 
    require "connect.php";

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
        if(empty(trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS))))
        {
            $userError = "Please enter a username";
        }

        else
        {
            $query = "SELECT LoginID FROM Logins WHERE username = :username";

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
            $query = "INSERT INTO Logins (username, password) VALUES (:username, :password)";

            $statement = $db->prepare($query);
            $statement->bindParam(":username", $queryUser);
            $statement->bindParam(":password", $queryPass);

            $queryUser = $username;

            // Hashes the password
            $queryPass = password_hash($password, PASSWORD_DEFAULT);

            if($statement->execute())
            {
                header("login.php");
            }

            else
            {
                echo "Account registration failed.";
            }

            unset($statment);
        }

        unset($db);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <h2>Register</h2>
        <p>Fill out this form to create a account.</p>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input class="form-control" type="text" name="username">
                <span class="help-block"><?= $userError ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input class="form-control" type="password" name="password">
                <span class="help-block"><?= $passwordError ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input class="form-control" type="password" name="confirm_password">
                <span class=help/block><?= $confirmError ?></span>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="Submit">
                <input class="btn btn-default" type="reset" value="Reset">
            </div>
        </form>
    </div>
</body>
</html>