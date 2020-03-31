<?php 
    require "connect.php";
    session_start();

    if(!isset($_SESSION['loggedin']) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php");
        exit;
    }

	if(!isset($_SESSION['admin']))
	{
		header("location: login.php");
    }
    
    $catNameError = "";

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $newCatName = filter_input(INPUT_POST, "newCatName", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(empty(trim($newCatName)))
        {
            $catNameError = "Name box is empty";
        }

        if(empty($nameError))
        {
            $query = "INSERT INTO categories (catname) VALUES (:catname)";

            if($statement = $db->prepare($query))
            {
                
                $statement->bindParam(":catname", $newCatName);
                

                if($statement->execute())
                {
                    header("location: admin.php");
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
    <title>Create Category</title>
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
    <div class="container">
        <h2>Edit Categories</h2>
        <form method="post">
            <div class="form-group row">
                <div class="col-md-5">
                    <label>Name</label>
                    <input type="text" class="form-control" name="newCatName">
                    <span class="help-block"><?= $catNameError ?></span>
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