<?php 
    session_start();

    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
    {
        header("location: account.php");
        exit;
    }

    require "connect.php";

    $username = "";
    $password = "";

    $userError = "";
    $passwordError = "";

    // Only executes the below code if the method from the form is POST
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Verifies that the username box is not empty
        if(empty(trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS))))
        {
            $userError = "Please enter a username";
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
                                
                                header("location: account.php");
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please enter your login details</p>
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
                <input class="btn btn-primary" type="submit" value="Login">
            </div>
        </form>
    </div>
</body>
</html>