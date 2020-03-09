<?php 
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php");
        exit;
    }

    require "connect.php";

    $newPassword = "";
    $newPasswordConfirm = "";
    $passError = "";
    $confirmError = "";

    //Only executes when the method is post
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Checks to see if the password is empty
        if(empty(trim($_POST["newPassword"])))
        {
            $passError = "Please enter a new password";
        }
        else
        {
            $newPassword = trim($_POST["newPassword"]);
        }

        //Checks to see if the password confirmation is empty
        if(empty(trim($_POST["passwordConfirm"])))
        {
            $confirmError - "Please enter a password";
        }
        else
        {
            $newPasswordConfirm = trim($_POST["passwordConfirm"]);

            if(empty($passError) && ($newPassword != $newPasswordConfirm))
            {
                $confirmError = "Passwords do not match";
            }
        }

        // Checks to see if the error varibles are empty
        if(empty($passError) && empty($confirmError))
        {
            $query = "UPDATE Logins SET password = :password WHERE loginID = :loginID";

            if($statement = $db->prepare($query))
            {
                $statement->bindParam(":password", $queryPassword, PDO::PARAM_STR);
                $statement->bindParam(":loginID", $queryId, PDO::PARAM_INT);

                $queryPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $queryId = $_SESSION["loginid"];

                if($statement->execute())
                {
                    session_destroy();
                    header("location:login.php");
                    exit;
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
    <title>Password Reset</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <div class="form-group">
                <label>New Password</label>
                <input class="form-control" type="password" name="newPassword">
                <span class="help-block"><?= $passError ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input class="form-control" type="password" name="passwordConfirm">
                <span class="help-block"><?= $confirmError ?></span>
            </div>
            <div class="form-group">
                <input class="btn btn-primary" type="submit" name="submit" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>