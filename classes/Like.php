<?php
class Like
{
    public static function getLikes($id)
    {
        $conn = Db::getInstance();
        //get all the rows where the user has been liked for
        $statement = $conn->prepare("SELECT * FROM `Prompts-likes` WHERE prompt_id = :prompt_id");
        $statement->bindValue(":prompt_id", $id);
        $statement->execute();
        //count the amount of rows and return it
        $count = $statement->rowCount();
        return $count;
    }
    public function checkLike($id)
    {
        //check if the user has already liked for the prompt
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM `Prompts-likes` WHERE prompt_id = :prompt_id AND user_id = :user_id");
        $statement->bindValue(":prompt_id", $id);
        $statement->bindValue(":user_id", $_SESSION['id']);
        
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return !empty($result);
    }

    public function updateLikes($id)
    {
        //update the likes for the prompt
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO `Prompts-likes` (prompt_id, user_id) VALUES (:prompt_id, :user_id)");
        $statement->bindValue(":prompt_id", $id);
        $statement->bindValue(":user_id", $_SESSION['id']);
        $statement->execute();


        $statement = $conn->prepare("SELECT credits FROM users WHERE username = :username");
        $statement->bindValue(":username", $_SESSION['username']);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $credits = $result['credits'];
        $credits = $credits + 1;

        $statement = $conn->prepare("UPDATE users SET credits = :credits WHERE username = :username");
        $statement->bindValue(":credits", $credits);
        $statement->bindValue(":username", $_SESSION['username']);
        $result = $statement->execute();


    }

    public function deleteLike($id)
    {
        //delete the like for the prompt
        $conn = Db::getInstance();
        $statement = $conn->prepare("DELETE FROM `Prompts-likes` WHERE prompt_id = :prompt_id AND user_id = :user_id");
        $statement->bindValue(":prompt_id", $id);
        $statement->bindValue(":user_id", $_SESSION['id']);
        $statement->execute();


        $statement = $conn->prepare("SELECT credits FROM users WHERE username = :username");
        $statement->bindValue(":username", $_SESSION['username']);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $credits = $result['credits'];
        $credits = $credits - 1;
        $_SESSION["credits"] = $credits;

        $statement = $conn->prepare("UPDATE users SET credits = :credits WHERE username = :username");
        $statement->bindValue(":credits", $credits);
        $statement->bindValue(":username", $_SESSION['username']);
        $result = $statement->execute();

    }
}
