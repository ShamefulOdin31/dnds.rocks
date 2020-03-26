<?php 
    require "connect.php";
    session_start();
    require "header.php";

    $name = filter_input(INPUT_GET, "name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    $nameError = "";

    //For the categories table.
	$categoriesQuery = "SELECT * FROM categories WHERE categoryID = :categoryID && catname = :catname";
    $catStatement = $db->prepare($categoriesQuery);
    $catStatement->bindParam(":catname", $name);
    $catStatement->bindParam(":categoryID", $id);
	$catStatement->execute();
    $catResults = $catStatement->fetchAll();
    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $newName = filter_input(INPUT_POST, "newName", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(empty(trim($newName)))
        {
            $nameError = "Name box is empty";
        }

        if(empty($nameError))
        {
            $query = "UPDATE categories SET catname = :catname WHERE categoryID = :id";

            if($statement = $db->prepare($query))
            {
                
                $statement->bindParam(":catname", $newName);
                $statement->bindParam(":id", $id);

                if($statement->execute())
                {
                    //header("location: admin.php");
                    echo $newName;
                    echo $_GET['id'];
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
    <title>Edit Categories</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
        crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" 
        crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h2>Edit Categories</h2>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <div class="form-group row">
                <div class="col-md-5">
                    <label>Name</label>
                    <input type="text" class="form-control" name="newName" value="<?= $name ?>">
                    <span class="help-block"><?= $nameError ?></span>
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