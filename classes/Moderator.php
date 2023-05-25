<?php
class Moderator {
    public static function getUsernames($username)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT username FROM users WHERE username LIKE :username AND banned = 0 AND admin = 0");
        $statement->bindValue(":username", "%".$username."%");
        $statement->execute();
        $usernames = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $usernames;
    }
    public static function getUsers($username)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE username LIKE :username AND banned = 0 AND admin = 0");
        $statement->bindValue(":username", "%".$username."%");
        $statement->execute();
        $usernames = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $usernames;
    }
    

    public static function addModerator($id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET admin = 1 WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    public static function deleteModerator($id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET admin = 0 WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }
    

    public static function getModerators() {
        $conn = Db::getInstance();
        $statement = $conn->query("SELECT * FROM users WHERE admin = 1");
        $moderators = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $moderators;
    }
    public static function getUserById($id){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    public static function ban($id){
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET banned = 1 WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }
    public static function unban($id){
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET banned = 0 WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }
    public static function getBannedUsers(){
        $conn = Db::getInstance();
        $statement = $conn->query("SELECT * FROM users WHERE banned = 1");
        $users = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }
}

?>
