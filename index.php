<?php 
    require "connect.php";
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
        crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" 
        crossorigin="anonymous"></script>
</head>
<body>
<?php require "header.php"?>
<!-- Start of content -->
    <div class="container">
        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true):?>
            <h1 class="text-center">You must go to the account page in order to use this site</h1>
            <button type="button" class="btn bnt-primary btn-lg"><a href="account.php"></a>Account</button>
            <a class="btn btn-primary btn-lg" href="account.php">Account</a>
        <?php else: ?>
            <br><br><br>
            <div class="container">
                <div class="row">
                    <div class="col text-center">
                        <h1 class="center-text">You must login to a account to use this site.</h1>
                        <br>
                        <a class="btn btn-primary btn-lg center-block" href="login.php">Login</a>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
</body>
</html>