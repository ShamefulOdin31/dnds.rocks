<?php 
    require "connect.php";
    session_start();

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $type = filter_input(INPUT_POST, "type", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $search = filter_input(INPUT_POST, "search", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $_SESSION['searchType'] = $type;
        $_SESSION['search'] = $search;

        $catResults;
        $characterResults;
        $spellResults;

        $searchType = $_SESSION['searchType'];
        $search = $_SESSION['search'];
        //$search = "%" . $search . "%";
        if($searchType == "catname")
        {
            $query = "SELECT * FROM categories where catname LIKE :catname";

            if($statement = $db->prepare($query))
            {
                
                $statement->bindParam(":catname", $search);

                if($statement->execute())
                {
                    $catResults = $statement->fetchAll();
                }
            } 
        }

        elseif($searchType == "cname")
        {
            
            $query = "SELECT * FROM dndcharacters where cname LIKE :cname";

            if($statement = $db->prepare($query))
            {
                $statement->bindParam(":cname", $search);

                if($statement->execute())
                {
                    $characterResults = $statement->fetchAll();
                }
            }
        }
        
        elseif($searchType == "spellName")
        {
            $spellsJSON = file_get_contents("spells.json");
            $spellsOutput = json_decode($spellsJSON, true);

            foreach($spellsOutput as $key => $value)
            {
                if($value['name'] == $search)
                {
                    $spellResults = array();
                    array_push($spellResults, $value);
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
    <title>Search</title>
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
    <?php if(isset($catResults)):?>
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($catResults as $key => $value) :?>
                    
                    <tr>
                        <th scope="row"><?= $value['catname'] ?></th>
                        <td><a href="categories.php?id=<?= $value['categoryID'] ?>&name=<?= $value['catname'] ?>">Select</a></td>
                    </tr>
                <?php endforeach?>
            </tbody>
        </table>
    <?php endif?>
    <?php if(isset($characterResults)):?>
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Race</th>
                    <th scope="col">Class</th>
                    <th scope="col">Background</th>
                    <th scope="col">Notes</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                    <?php foreach($characterResults as $key => $value) :?>
                        <tr>
                            <th scope="row"><?= $value['cname'] ?></th>
                            <td><?= $value['race'] ?></td>
                            <td><?= $value['class'] ?></td>
                            <td><?= $value['background'] ?></td>
                            <td><?= $value['notes'] ?></td>
                            <td><a href="select.php?characterID=<?= $value['characterID'] ?>&type=<?= str_replace(' ', '-', $value['searchBy']) ?>">Select</a></td>
                        </tr>
                    <?php endforeach?>
            </tbody>
        </table>
    <?php endif?>
    <?php if(isset($spellResults)):?>
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Race</th>
                    <th scope="col">Class</th>
                    <th scope="col">Background</th>
                    <th scope="col">Notes</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                    <?php foreach($spellsOutput as $key => $value) :?>
                        <tr>
                            <th scope="row"><?= $value['name'] ?></th>
                        </tr>
                    <?php endforeach?>
            </tbody>
        </table>
    <?php endif?>
</div>
</body>
</html>