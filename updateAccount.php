<?php 
    require "connect.php";
    session_start();

    // Checks to see if the user accessing the page is a admin.
    if($_SESSION['adminLogedIn'] !== true || !isset($_SESSION['adminLogedIn']))
    {
        header("location: admin.php?id=1&type=name");
    }

    $username = "";
    $adminStatus = "";
    $checked = "";
    $userError = "";
    $queryUser = "";

    $loginID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    $loadValue = "SELECT username, loginID, adminStatus FROM logins WHERE loginID = :id";
    $statement = $db->prepare($loadValue);
    $statement->bindParam(":id", $loginID);
    $statement->execute();
    $userFetch = $statement->fetchAll();
    $_SESSION['loadUsername'] = $userFetch[0]['username'];

    if(($userFetch[0]['adminStatus'] === 'y'))
    {
        $checked = ' checked = "checked"';
    }
    

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Checks to see if the admin checkbox is checked.

        $username = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_EMAIL);

        if(isset($_POST['adminStatus']))
        {
            $adminStatus = "y";
        }

        else
        {
            $adminStatus = "n";
        }

        //Checks to see if the just the admin check box is changed and nothing else.
        if($username == $userFetch[0]['username'] && $loginID == $userFetch[0]['loginID'] && $userFetch[0]['admin'] != $adminStatus)
        {
            $adminUpdate = "UPDATE logins SET adminStatus = :adminStatus WHERE loginID = :loginID";

            if($statementUpdate = $db->prepare($adminUpdate))
            {
                $statementUpdate->bindParam(":adminStatus", $adminStatus);
                $statementUpdate->bindParam(":loginID", $loginID);

                if($statementUpdate->execute())
                {
                    header("location: admin.php?id=1&type=name");
                }
            }
        }

        // Checks to see if the username has changed and nothing else.
        elseif($username != $userFetch[0]['username'] && $loginID == $userFetch[0]['loginID'] && $userFetch[0]['adminStatus'] == $adminStatus)
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

            // Checks to see if the error variables are empty
            if(empty($userError))
            {
                $query = "UPDATE logins SET username = :username WHERE loginID = :loginID";

                if($statement = $db->prepare($query))
                {
                    $statement->bindParam(":username", $username);
                    $statement->bindParam(":loginID", $loginID);

                    if($statement->execute())
                    {
                        header("location: admin.php?id=1&type=name");
                    }
                    else
                    {
                        echo "Updating username failed";
                    }
                }
                
            }
        }

        //Checks to see if the username and the admin checkbox have changed.
        elseif($username != $userFetch[0]['username'] && $loginID == $userFetch[0]['loginID'] && $userFetch[0]['adminStatus'] != $adminStatus)
        {
            $adminUpdate = "UPDATE logins SET username = :username, adminStatus = :adminStatus WHERE loginID = :loginID";

            if($statementUpdate = $db->prepare($adminUpdate))
            {
                $statementUpdate->bindParam(":adminStatus", $adminStatus);
                $statementUpdate->bindParam(":loginID", $loginID);
                $statementUpdate->bindParam(":username", $username);

                if($statementUpdate->execute())
                {
                    header("location: admin.php?id=1&type=name");
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
    <title>Admin Update Account</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
        crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" 
        crossorigin="anonymous"></script>
</head>
<body>
<?php require "header.php"?>
<?php require "searchBar.php"?>
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
            <p></p>
            <div class="custom-control custom-checkbox mb-3">
                <input type="checkbox" class="custom-control-input" id="customCheck" name="adminStatus" value="adminStatus"<?php echo $checked ?>>
                <label class="custom-control-label" for="customCheck">Make Admin?</label>
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