<?php
require "connect.php";
require "authenticate.php";

session_start();

$userAccounts;

$query = "SELECT loginid, username, timeCreated FROM logins";

$statement = $db->prepare($query);
$statement->execute();
$userAccounts = $statement->fetchAll();
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
	<title>Admin</title>
</head>

<body>
<!-- Start of Nav -->
<nav class="navbar navbar-expand-sm bg-primary navbar-dark">
    <a class="navbar-brand" href="index.php">Home</a>
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link" href="account.php?sort=name">Account</a>
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

	<!-- Start of content -->
	<div class="container">
		<h1>Welcome Richard Schentag</h1>
		<p>Below are all of the user accounts</p>
		<table class="table table-striped table-hover">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Login ID</th>
					<th scope="col">Username</th>
					<th scope="col">Date Created</th>
					<th scope="col"></th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($userAccounts as $key => $value) :?>
					<tr>
						<th scope="row"><?= $value['loginid'] ?></th>
						<td><?= $value['username'] ?></td>
						<td><?= $value['timeCreated'] ?></td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		<table class="table table-striped table-hove">
			<thead class="thead-dark">
				
			</thead>
		</table>
	</div>
</body>

</html>