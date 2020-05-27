<?php 
    require "connect.php";
    session_start();
    
    
    // Verifies that the user is logged in
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
    {
        header("location: login.php");
        exit;
    }

    // Variable for error messages displayed on the page
    $nameError = "";
    $raceError = "";
    $classError = "";
    $backgroundError = "";
    $searchError = "";

    // Gets and decodes the race data from the api
    $raceJSON = file_get_contents('http://www.dnd5eapi.co/api/races');
    $races = json_decode($raceJSON, true);

    // Gets and decodes the class data from the api
    $classJSON = file_get_contents('http://www.dnd5eapi.co/api/classes');
	$classes = json_decode($classJSON, true);
	
	// Gets class details
	//$classDetails = $_POST['selectedClass'];
	//echo $classDetails;

    $categoriesQuery = "SELECT * FROM categories";
    $categoriesStatement = $db->prepare($categoriesQuery);
    $categoriesStatement->execute();
    $categoriesResults = $categoriesStatement->fetchAll();

    // Sanitizes user form input
    $cname = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $race = filter_input(INPUT_POST, 'races', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $class = filter_input(INPUT_POST, 'classes', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $background = filter_input(INPUT_POST, 'background', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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

        if(empty($nameError) && empty($raceError) && empty($classError) && empty($backgroundError) && empty($notesError))
        {
            $_SESSION['cname'] = $cname;
            $_SESSION['race'] = $race;
            $_SESSION['class'] = $class;
            $_SESSION['background'] = $background;
            $_SESSION['searchBy'] = str_replace(' ', '-',$search);
            
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
	<title>Create Character</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script type="text/javascript">
		$(function(){
			$("#classes").change(function(){
				var selectedClass = $("#classes option:selected").val();
				$.post('classes.php', {"classSelected": selectedClass})
			})
		})
	</script>
</head>
<body>
<!-- Start of content -->
    <div>
        <div class="no-gutters">
			<div class="row">
				<div class="col-1"></div>
				<div class="col">
					<h2>Create a character</h2>
        			<p>Fill out the form to create a character</p>
				</div>
			</div>
		</div>
        <div class="no-gutters">
			<div class="row">
				<div class="col-1"></div>
				<div class="col">
					<h4>Character Details</h4>
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
						<div class="form-group">
							<input class="btn btn-primary" type="submit" value="Submit">
							<input class="btn btn-default" type="reset" value="Reset">
						</div>
					</form>
				</div>

				<!-- Class details form -->
				<div class="col">
					<h4>Class details</h4>
					<div class="form-group">
						<div class="col-md-5">
							<label for="">Name</label>
							<input type="text" readonly="readonly" class="form-control" value="" id="className">
						</div>
					</div>
				</div>
				<div class="col">
					<h4>Race details</h4>
				</div>
				<div class="col-1"></div>
			</div>
		</div>
    </div>
</body>
<div class="classResults"></div>
</html>