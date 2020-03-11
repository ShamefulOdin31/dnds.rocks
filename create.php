<?php 
    require "connect.php";

    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php");
        exit;
    }

    $nameError = "";
    $raceError = "";
    $classError = "";
    $backgroundError = "";
    $notesError = "";
    
    $raceJSON = file_get_contents('http://www.dnd5eapi.co/api/races');
    $races = json_decode($raceJSON, true);
    $classJSON = file_get_contents('http://www.dnd5eapi.co/api/classes');
    $classes = json_decode($classJSON, true);

    $cname = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $race = filter_input(INPUT_POST, 'races', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $class = filter_input(INPUT_POST, 'classes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $background = filter_input(INPUT_POST, 'background', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if(empty(trim($cname)))
        {
            $nameError = "Please enter a character name.";
        }

        if(empty(trim($race)))
        {
            $raceError = "Please enter a valid race";
        }

        if(empty(trim($class)))
        {
            $classError = "Please enter a valid class";
        }

        if(empty(trim($background)))
        {
            $backgroundError = "Please enter a valid background";
        }

        if(empty(trim($notes)))
        {
            $notesError = "Please enter a valid background";
        }

        if(empty($nameError) && empty($raceError) && empty($classError) && empty($backgroundError) && empty($notesError))
        {
            $query = "INSERT INTO dndcharacters (CName, Race, Class, Background, Notes, userOwner) values (:cname, :race, :class, :background, :notes, :userOwner)";

            $statement = $db->prepare($query);

            $statement->bindParam(":cname", $cname);
            $statement->bindParam(":race", $race);
            $statement->bindParam(":class", $class);
            $statement->bindParam(":background", $background);
            $statement->bindParam(":notes", $notes);
            $statement->bindParam(":userOwner", $_SESSION['loginid']);

            $statement->execute();

            $insertID = $db->lastInsertId();

            $_SESSION['characterID'] = $insertID;
            $_SESSION['race'] = $race;
            $_SESSION['class'] = $class;

            header("location: stats.php");
        }
        unset($db);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
        crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" 
        crossorigin="anonymous"></script>
</head>
<body>
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
    <div class="container">
        <h2>Create a character</h2>
        <p>Fill out the form to create a character</p>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <div class="form-group row">
                <div class="col-md-5">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name">
                    <span class="help-block"><?= $nameError ?></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-5">
                    <label for="races">Race</label>
                    <select name="races" id="races" class="form-control">
                        <option data-placeholder="true" value="">--Please select a race--</option>
                        <?php foreach($races['results'] as $key => $value) :?>
                            <option value="<?= $value['name'] ?>"><?= $value['name'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <span class="help-block"><?= $raceError ?></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-5">
                    <label>Class</label>
                    <select name="classes" id="classes" class="form-control">
                        <option data-placeholder="true" value="">--Please select a class--</option>
                        <?php foreach($classes['results'] as $key => $value) :?>
                            <option value="<?= $value['name'] ?>"><?= $value['name'] ?></option>
                        <?php endforeach ?>
                    </select>
                    <span class="help-block"><?= $classError ?></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-5">
                    <label>Background</label>
                    <input type="text" name="background" class="form-control">
                    <span class="help-block"><?= $backgroundError ?></span>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-5">
                    <label>Notes</label>
                    <input type="textarea" name="notes" class="form-control">
                    <span class="help-block"><?= $notesError ?></span>
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