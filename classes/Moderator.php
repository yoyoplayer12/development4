<?php
class Moderator {
    public static function getUsernames($username)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT username FROM users WHERE username LIKE :username");
        $statement->bindValue(":username", "%".$username."%");
        $statement->execute();
        $usernames = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $usernames;
    }

    public static function addModerator($username)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET admin = 1 WHERE username = :username");
        $statement->bindValue(":username", $username);
        $statement->execute();
    }

    public static function deleteModerator($username)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET admin = 0 WHERE username = :username");
        $statement->bindValue(":username", $username);
        $statement->execute();
    }
    

    public static function getModerators() {
        $conn = Db::getInstance();
        $statement = $conn->query("SELECT username FROM users WHERE admin = 1");
        $moderators = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $moderators;
    }
}

?>
