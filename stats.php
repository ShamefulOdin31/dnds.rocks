<?php 
    require "connect.php";
    session_start();

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php");
        exit;
    }

    $strengthError = "";
    $intelligenceError = "";
    $dexterityError = "";
    $wisdomError = "";
    $constitutionError = "";
    $charismaError = "";
    $hitPointsError = "";

    $raceJSON = file_get_contents(strtolower('http://www.dnd5eapi.co/api/races/' . $_SESSION['race']));
    $raceInfo = json_decode($raceJSON, true);
    $classJSON = file_get_contents(strtolower('http://www.dnd5eapi.co/api/classes/' . $_SESSION['class']));
    $classInfo = json_decode($classJSON, true);

    $cname = $_SESSION['cname'];
    $race = $_SESSION['race'];
    $class = $_SESSION['class'];
    $background = $_SESSION['background'];
    $search = $_SESSION['searchBy'];

    $strength = filter_input(INPUT_POST, 'strength', FILTER_SANITIZE_NUMBER_INT);
    $intelligence = filter_input(INPUT_POST, 'intelligence', FILTER_SANITIZE_NUMBER_INT);
    $dexterity = filter_input(INPUT_POST, 'dexterity', FILTER_SANITIZE_NUMBER_INT);
    $wisdom = filter_input(INPUT_POST, 'wisdom', FILTER_SANITIZE_NUMBER_INT);
    $constitution = filter_input(INPUT_POST, 'constitution', FILTER_SANITIZE_NUMBER_INT);
    $charisma = filter_input(INPUT_POST, 'charisma', FILTER_SANITIZE_NUMBER_INT);
    $hitPoints = filter_input(INPUT_POST, 'hitpoints', FILTER_SANITIZE_NUMBER_INT);
    $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $visibility ="";

    if($_SERVER["REQUEST_METHOD"] === "POST")
    {
        if(empty(trim($strength)))
        {
            $strengthError = "Please enter a valid strength";
        }

        if(empty(trim($intelligence)))
        {
            $intelligenceError = "Please enter a valid intelligence";
        }

        if(empty(trim($dexterity)))
        {
            $dexterityError = "Please enter a valid dexterity";
        }
        if(empty(trim($wisdom)))
        {
            $wisdomError = "Please enter a valid wisdom";
        }

        if(empty(trim($constitution)))
        {
            $constitutionError = "Please enter a valid constitution";
        }

        if(empty(trim($charisma)))
        {
            $charismaError = "Please enter a valid charisma";
        }

        if(empty(trim($hitPoints)))
        {
            $hitPointsError = "Please enter a valid HP";
        }

        if(isset($_POST['visibility']))
        {
            $visibility = "y";
        }
        else
        {
            $visibility = "n";
        }

        if(empty($strengthError) && empty($intelligenceError) && empty($dexterityError) && empty($wisdomError) && empty($constitutionError) && empty($charismaError))
        {
            // Character and stat creation
            $query = "INSERT INTO dndcharacters (CName, Race, Class, Background, userOwner, strength, intelligence, dexterity, wisdom, constitution, charisma, hitpoints) 
                values (:cname, :race, :class, :background, :userOwner, :strength, :intelligence, :dexterity, :wisdom, :constitution, :charisma, :hitpoints)";

            $statement = $db->prepare($query);

            $statement->bindParam(":cname", $cname);
            $statement->bindParam(":race", $race);
            $statement->bindParam(":class", $class);
            $statement->bindParam(":background", $background);
            $statement->bindParam(":userOwner", $_SESSION['loginid']);
            $statement->bindParam(":strength", $strength);
            $statement->bindParam(":intelligence", $intelligence);
            $statement->bindParam(":dexterity", $dexterity);
            $statement->bindParam(":wisdom", $wisdom);
            $statement->bindParam(":constitution", $constitution);
            $statement->bindParam(":charisma", $charisma);
            $statement->bindParam(":hitpoints", $hitPoints);

            $statement->execute();

            $insertID = $db->lastInsertId();

            $_SESSION['characterID'] = $insertID;
        }

        if($_FILES['image']['size'] > 0 )
        {

            if(isset($_FILES['image']) && $_FILES['image']['error'] > 0)
            {
                echo "Error:" . $_FILES['image']['error'];
            }
            
            // Creates a string containing the desired file upload path.
            function file_upload_path($original_filename, $upload_subfolder_name = 'uploads')
            {
                //__FILE__ gets the current path of this file create.php.
                $current_folder = dirname(__FILE__);
                $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
                return join(DIRECTORY_SEPARATOR, $path_segments);
            }

            // Checks to see if the file is of proper type.
            function file_is_an_image($temporary_path, $new_path) 
            {
                //Declares the allowed types and the file extensions.
                $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
                $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
                
                // Pulls the extension and type from the upload file.
                $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
                $actual_mime_type        = getimagesize($temporary_path)['mime'];
                
                // Compares the allowed extensions and types from the array above and the upload file.
                $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
                $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
                
                return $file_extension_is_valid && $mime_type_is_valid;
            }

            $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);

            if($image_upload_detected)
            {
                $image_filename = $_FILES['image']['name'];
                $temporary_image_path = $_FILES['image']['tmp_name'];
                $new_image_path = file_upload_path($image_filename);

                if(file_is_an_image($temporary_image_path, $new_image_path))
                {
                    move_uploaded_file($temporary_image_path, $new_image_path);

                    $login = $_SESSION['loginid'];
                    $characterID = $_SESSION['characterID'];
                    $uploadLocation = strval("uploads/" . $reimage_filenameize);

                    $upload = "INSERT INTO uploads (characterID, loginID, uploadLocation) values (:characterID, :loginID, :uploadLocation)";
                    $statement = $db->prepare($upload);

                    $statement->bindParam(":characterID", $characterID);
                    $statement->bindParam(":loginID", $login);
                    $statement->bindParam(":uploadLocation", $uploadLocation);

                    $statement->execute();
                }
            }
        }
        header("location: account.php?id=1&type=name");
    }
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
<?php require "header.php"?>
<!-- Start of form -->
<div class="container">
    <h2>Enter your characters stats</h2>
    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
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
                <input type="number" class="form-control" name="hitpoints" value=10>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <input class="btn btn-primary" type="submit" value="Submit">
            <input class="btn btn-default" type="reset" value="Reset">
        </div>
    </form>
</div>
<div class="container mt-3">
    <form action="account.php" method="post" enctype="multipart/form-data">
        
    </form>
</div>
</body>
</html>