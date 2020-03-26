<?php 
    require "connect.php";
	require "authenticate.php";

	require "header.php";

    session_start();

    $results;
    
    $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $search = "%" . $search . "%";


    $query = "SELECT * FROM dndCharacters WHERE searchBy LIKE :search";

    if($statement = $db->prepare($query))
    {
        $statement->bindParam(":search", $search);

        if($statement->execute())
        {
            $results = $statement->fetchAll();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
        crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" 
        crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th scope="col"><a href="index.php?id=1&type=name">Name</a></th>
                <th scope="col"><a href="index.php?id=2&type=race">Race</a></th>
                <th scope="col"><a href="index.php?id=3&type=class">Class</a></th>
                <th scope="col"><a href="index.php?id=4&type=background">Background</a></th>
                <th scope="col">Notes</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($results as $key => $value) :?>
                <tr>
                    <th scope="row"><?= $value["cname"] ?></th>
                    <td><?= $value['race'] ?></td>
                    <td><?= $value['class'] ?></td>
                    <td><?= $value['background'] ?></td>
                    <td><?= $value['notes'] ?></td>
                    <td><a href="select.php?characterID=<?= $value['characterID'] ?>&type=<?= str_replace(' ', '-', $value['searchBy']) ?>">Select</a></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
</body>
</html>