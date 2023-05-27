<?php
class Action
{
    public function verify()
    {
        if ($_SESSION["admin"] == true) {
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
            $_SESSION["credits"] = $credits;
            $statement = $conn->prepare("UPDATE users SET credits = :credits WHERE username = :username");
            $statement->bindValue(":credits", $credits);
            $statement->bindValue(":username", $_GET['username']);
            $result = $statement->execute();
            //check if user has 3 or more approved prompts
            $statement = $conn->prepare("SELECT COUNT(*) FROM prompts WHERE user_id = :user_id AND verified = 1");
            $statement->bindValue(":user_id", $_GET['user_id']);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            $count = $result['COUNT(*)'];
            //if the user has 3 or more approved prompts, set verified to 1
            if ($count >= 3) {
                $statement = $conn->prepare("UPDATE users SET verified = 1 WHERE username = :username");
                $statement->bindValue(":username", $_GET['username']);
                $result = $statement->execute();
            }

            header("Location: moderator.php");
        } else {
            header("Location: login.php");
        }
    }
    public function reject()
    {
        if ($_SESSION["admin"] == true) {
            $id = $_GET['id'];
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE prompts SET rejected = 1, verified = 0 WHERE id = :id AND active = 1");
            $statement->bindValue(":id", $id);
            $statement->execute();
            header("Location: moderator.php");
        } else {
            header("Location: login.php");
        }
    }
    public function deletepost($userid)
    {
        if ($userid == $_SESSION["userid"]) {
            $post_id = $_GET['pid'];
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE prompts SET deleted = 1, verified = 0 WHERE id = :id AND active = 1");
            $statement->bindValue(":id", $post_id);
            $statement->execute();
            header("Location: index.php");
        } else {
            header("Location: index.php");
        }
    }
    public function emailcheck($email)
    {
        // check if username already exists in database
        $conn = Db::getInstance();
        $stmt = $conn->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
        $stmt->execute(array($email));
        $count = $stmt->fetchColumn();
        // return response as JSON
        if ($count > 0) {
            $response = array('available' => false);
        } else {
            $response = array('available' => true);
        }
        echo json_encode($response);
    }
}
