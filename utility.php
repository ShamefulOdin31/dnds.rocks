<?php 
    //Test comment
    function navbarArray($side, $db)
    {
        if($side == "r")
        {
            $queryRight = "SELECT navitemID, navItemName, navurl FROM navbar WHERE side = 'r'";
            $statement = $db->prepare($queryRight);
            $statement->execute();

            $navbarRight = $statement->fetchAll();

            return $navbarRight;
        }

        elseif($side == "l")
        {
            $queryLeft = "SELECT navitemID, navItemName, navurl FROM navbar WHERE side = 'l'";
            $statement = $db->prepare($queryLeft);
            $statement->execute();

            $navbarLeft = $statement->fetchAll();

            return $navbarLeft;
        }
    }
?>