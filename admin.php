<?php
	require "connect.php";
	require "authenticate.php";

	require "header.php";

	session_start();

	if(!isset($_SESSION['admin']))
	{
		header("location: login.php");
	}
	// For the user accounts table.
	$_SESSION['adminLogedIn'] = true;
	$userAccounts;

	$query = "SELECT loginid, username, timeCreated, adminStatus FROM logins";

	$statement = $db->prepare($query);
	$statement->execute();
	$userAccounts = $statement->fetchAll();
	
	// For the character table.
	$query = "SELECT characterID, cname, race, class, background, notes, userOwner, searchBy FROM dndCharacters";

    $sort = ["name" => " ORDER BY cname",
             "race" => " ORDER BY race",
             "class" => " ORDER BY class",
			 "background" => " ORDER BY background",
			 "owner" => "ORDER BY userOwner"];


	if(isset($_GET['id']) && isset($_GET['type']))
	{
		if($_GET['id'] == '1' && $_GET['type'] == 'name')
		{
			$query .= $sort["name"];
		}

		elseif($_GET['id'] == '2' && $_GET['type'] == 'race')
		{
			$query .= $sort["race"];
		}

		elseif($_GET['id'] == '3' && $_GET['type'] == 'class')
		{
			$query .= $sort["class"];
		}

		elseif($_GET['id'] == '4' && $_GET['type'] == 'background')
		{
			$query .= $sort["background"];
		}
		elseif($_GET['id'] == '5' && $_GET['type' == 'owner'])
		{
			$query .= $sort["owner"];
		}
		else
		{
			http_response_code(404);
			die();
		}
	}

    $statement = $db->prepare($query);
    $statement->bindParam(":loginID", $_SESSION["loginid"]);
    $statement->execute();
	$dndCharacters = $statement->fetchAll();
	
	//For the categories table.
	$categoriesQuery = "SELECT * FROM categories";
	$catStatement = $db->prepare($categoriesQuery);
	$catStatement->execute();
	$catResults = $catStatement->fetchAll();
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
<!-- Start of content -->
<div class="container">
	<h1>Welcome Richard Schentag</h1>
	<p>Categories</p>
	<table class="table table-striped table-hover">
		<thead class="thead-dark">
			<tr>
				<th scope="col">Category ID</th>
				<th scope="col">Name</th>
				<th scope="col"></th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($catResults as $key => $value) :?>
				<th scope="row"><?= $value['categoryID'] ?></th>
				<th><?= $value['catname'] ?></th>
				<th><a href="editCategory.php?id=<?= $value['categoryID'] ?>&name=<?= $value['catname'] ?>">Select</a></th>
			<?php endforeach?>
		</tbody>
	</table>
	<p>Below are all of the user accounts</p>
	<a href="addAccount.php">Add Account</a>
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
					<td><a href="updateAccount.php?id=<?= $value['loginid'] ?>">Update</a></td>
					<td><a href="adminDelete.php?id=<?= $value['loginid'] ?>">Delete</a></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<p>Below are all characters from all users</p>
	<table class="table table-striped table-hover">
	<thead class="thead-dark">
		<tr>
			<th scope="col"><a href="admin.php?id=1&type=name">Name</a></th>
			<th scope="col"><a href="admin.php?id=2&type=race">Race</a></th>
			<th scope="col"><a href="admin.php?id=3&type=class">Class</a></th>
			<th scope="col"><a href="admin.php?id=4&type=background">Background</a></th>
			<th scope="col">Notes</th>
			<th scope="col"><a href="admin.php?id=5&type=owner">Owner</a></th>
			<th scope="col"></th>
			<th scope="col"></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($dndCharacters as $key => $value) :?>
			<tr>
				<th scope="row"><?= $value["cname"] ?></th>
				<td><?= $value['race'] ?></td>
				<td><?= $value['class'] ?></td>
				<td><?= $value['background'] ?></td>
				<td><?= $value['notes'] ?></td>
				<td><?= $value['userOwner'] ?></td>
				<td><a href="select.php?characterID=<?= $value['characterID'] ?>&type=<?= str_replace(' ', '-', $value['searchBy']) ?>">Select</a></td>
				<td><a href="spells.php?characterID=<?= $value['characterID'] ?>&type=<?= str_replace(' ', '-', $value['searchBy']) ?>">Spells</a></td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
</div>
</body>

</html>