<?php
    include_once(__DIR__ . "/bootstrap.php");
    //logindetection
    $user_id = $_GET['uid'];
    if($user_id == $_SESSION["userid"]) {
        $post_id = $_GET['pid'];
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE prompts SET deleted = 1, verified = 0 WHERE id = :id AND active = 1");
        $statement->bindValue(":id", $post_id);
        $statement->execute();
        var_dump($statement->execute());
        die();
        header("Location: index.php");
    }
    else {
        header("Location: index.php");
    }

    