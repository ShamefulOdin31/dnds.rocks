<?php 
    require "connect.php";

    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php");
        exit;
    }

    $raceJSON = file_get_contents(strtolower('http://www.dnd5eapi.co/api/races/' . $_SESSION['race']));
    $raceInfo = json_decode($raceJSON, true);
    $classJSON = file_get_contents(strtolower('http://www.dnd5eapi.co/api/classes/' . $_SESSION['class']));
    $classInfo = json_decode($classJSON, true);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
		integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
		crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
		integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" 
		crossorigin="anonymous"></script>
    <title>Stats</title>
</head>
<body>
    <!-- Start of Nav -->
	<nav class="navbar navbar-expand-sm bg-primary navbar-dark">
		<a class="navbar-brand" href="index.php">Home</a>
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="nav-link" href="account.php">Account</a>
			</li>
			<li class="nav-item">
				<?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true):?>
					<a class="nav-link" href="create.php">Create Character</a>
				<?php else :?>
					<a class="nav-link disabled" href="create.php">Create Character</a>
				<?php endif ?>
			</li>
		</ul>
		<ul class="navbar-nav ml-auto">
			<?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true):?>
				<li class="nav-item">
					<a class="nav-link" href="logout.php">Logout</a>
				</li>
			<?php else :?>
				<li class="nav-item">
					<a class="nav-link" href="registration.php">Register</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="login.php">Login</a>
				</li>
			<?php endif ?>
		</ul>
    </nav>

    <!-- Start of form -->
    <div class="container">
        <h2>Enter your characters stats</h2>
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
            <div class="form-group row">
                <div class="col-md-3">
                    <label>Strength</label>
                    <input type="number" class="form-control" name="strength" value=8>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <label>Intelligence</label>
                    <input type="number" class="form-control" name="intelligence" value=8>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <label>Dexterity</label>
                    <input type="number" class="form-control" name="dexterity" value=8>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <label>Wisdom</label>
                    <input type="number" class="form-control" name="wisdom" value=8>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <label>Consitution</label>
                    <input type="number" class="form-control" name="constitution" value=8>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <label>Charisma</label>
                    <input type="number" class="form-control" name="charisma" value=8>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <label>Hit Points</label>
                    <input type="number" class="form-control" name="hitpoints" value=8>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-3">
                    <label>Notes</label>
                    <input type="text" class="form-control" name="notes">
                    <span class="help-block"></span>
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