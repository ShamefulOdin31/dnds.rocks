<?php 
    session_start();

    if(!isset($_SESSION['loggedin']) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php");
        exit;
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
    <div class="container">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="account.php">Account</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="create.php">Create Character</a>
            </li>
        </ul>
        <p>Login successful</p>
        <h1>Welcome <?= htmlspecialchars($_SESSION["username"]) ?></h1>

        <p>Your characters are listed here</p>

        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Race</th>
                    <th scope="col">Class</th>
                    <th scope="col">Background</th>
                    <th scope="col">Notes</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>

        <p>
            <a class="btn btn-warning" href="resetpassword.php"> Reset your password</a>
            <a class ="btn btn-danger" href="logout.php">Logout</a>
        </p>
    </div>
</body>
</html>