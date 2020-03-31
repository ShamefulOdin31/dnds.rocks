<?php 
    require "connect.php";
    session_start();

    $categoryID = filter_input(INPUT_GET, 'categoryID', FILTER_SANITIZE_NUMBER_INT);
    $catname = filter_input(INPUT_GET, "name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    $nameError = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $newCatName = filter_input(INPUT_POST, "newCatName", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if(empty(trim($newCatName)))
        {
            $nameError = "Name box is empty";
        }

        if(empty($nameError))
        {
            $query = "UPDATE categories SET catname = :newCatName WHERE categoryID = :categoryID";

            if($statement = $db->prepare($query))
            {
                
                $statement->bindParam(":newCatName", $newCatName);
                $statement->bindParam(":categoryID", $categoryID);
                

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
    <title>Edit Categories</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
        crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" 
        crossorigin="anonymous"></script>
</head>
<body>
    <?php require "header.php"?>
    <div class="container">
        <h2>Edit Categories</h2>
        <form method="post">
            <div class="form-group row">
                <div class="col-md-5">
                    <label>Name</label>
                    <input type="text" class="form-control" name="newCatName" value="<?= $catname ?>">
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