<?php 
    require "connect.php";
    
    $spellsJSON = file_get_contents("http://dnd5eapi.co/api/spells/" . $_GET['url']);
    $spellsOutput = json_decode($spellsJSON, true);

    $description = implode($spellsOutput['desc']);
    $level = $spellsOutput['level'];

    if($level == 0)
    {
        $level = "Cantrip";
    }

    
    if(isset($spellsOutput['higher_level']))
    {
        $description .= ("<br><br>" . implode($spellsOutput['higher_level']));
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $spellsOutput['name'] ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" 
		integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" 
		crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" 
		integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" 
		crossorigin="anonymous"></script>
</head>
<body>
<?php require "header.php"?>
<!-- Start of content -->
<div class="container">
    <h2><?= $spellsOutput['name'] ?></h2>
    <table class="table table-striped table-hover">
        <tbody>
            <tr>
                <th scope="col">Spell Name</th>
                <td> <?= $spellsOutput['name'] ?></td>
            </tr>
            <tr>
                <th scope="col">Level</th>
                <td> <?= $level ?></td>
            </tr>
            <tr>
                <th scope="col">Description</th>
                <td><?= $description ?></td>
            </tr>
            <tr>
                <th scope="col">Range</th>
                <td><?= $spellsOutput['range'] ?></td>
            </tr>
            <tr>
                <th scope="col">Casting Time</th>
                <td><?= $spellsOutput['casting_time'] ?></td>
            </tr>
            <tr>
                <th scope="col">Duration</th>
                <td><?= $spellsOutput['duration'] ?></td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>