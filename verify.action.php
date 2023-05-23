<?php
    include_once(__DIR__ . "/bootstrap.php");
    //logindetection
    if($_SESSION["admin"] == true) {
        $id = $_GET['id'];
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE prompts SET verified = 1, rejected = 0 WHERE id = :id AND active = 1");
        $statement->bindValue(":id", $id);
        $statement->execute();


        $statement = $conn->prepare("SELECT credits FROM users WHERE username = :username");
        $statement->bindValue(":username", $_GET['username']);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $credits = $result['credits'];
        $credits = $credits + 1;
        // var_dump($credits);


        $statement = $conn->prepare("UPDATE users SET credits = :credits WHERE username = :username");
        $statement->bindValue(":credits", $credits);
        $statement->bindValue(":username", $_GET['username']);
        $result = $statement->execute();
        // var_dump($result);



        header("Location: adminpanel.php");
    }
    else {
        header("Location: login.php");
    }

    