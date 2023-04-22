<?php
    include_once(__DIR__ . "/bootstrap.php");
    //logindetection
    if($_SESSION["admin"] == true) {
        $id = $_GET['id'];
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE prompts SET rejected = 1, verified = 0 WHERE id = :id AND active = 1");
        $statement->bindValue(":id", $id);
        $statement->execute();
        header("Location: adminpanel.php");
    }
    else {
        header("Location: login.php");
    }

    