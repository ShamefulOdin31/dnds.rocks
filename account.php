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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page-header">
        <h1>Welcome <?= htmlspecialchars($_SESSION["username"]) ?></h1>
    </div>
    <p>
        <a class="btn btn-warning" href="resetpassword.php"> Reset your password</a>
        <a class ="btn btn-danger" href="logout.php">Logout</a>
    </p>
</body>
</html>