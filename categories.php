<?php 
    require "connect.php";
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $search = filter_input(INPUT_POST, "search", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $_SESSION['searchType'] = $type;
        $_SESSION['search'] = $search;

        header("location: search.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
        crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" 
        crossorigin="anonymous"></script>
</head>
<body>
    <?php require "header.php" ?>
    <nav class="navbar navbar-expand-sm navbar-dark">
    <form class="form-inline my-2 my-lg-0 ml-auto" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <select name="type" id="type" class="form-control">
            <option value="catname">Categories</option>
            <option value="cname">Characters</option>
            <option value="spellName">Spells</option>
        </select>
        <input class="form-control" type="search" placeholder="Search" aria-label="Search" name="search">
        <button class="btn btn-primary" type="submit">Search</button>
    </form>
</nav>
</body>
</html>