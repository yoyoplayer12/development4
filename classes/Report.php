<?php

class Report
{
    private string $reportedId;
    private string $reporterId;



    public static function checkReportUser($id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM `reported-users` WHERE reported_id = :id AND reporter_id = :reporter_id");
        $statement->bindValue(":id", $id);
        $statement->bindValue(":reporter_id", $_SESSION['id']);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    /**
     * Get the value of reportedId
     */
    public function getReportedId()
    {
        return $this->reportedId;
    }
    /**
     * Set the value of reportedId
     *
     * @return  self
     */
    public function setReportedId($reportedId)
    {
        $this->reportedId = $reportedId;
        return $this;
    }
    /**
     * Get the value of reporterId
     */
    public function getReporterId()
    {
        return $this->reporterId;
    }
    /**
     * Set the value of reporterId
     *
     * @return  self
     */
    public function setReporterId($reporterId)
    {
        $this->reporterId = $reporterId;
        return $this;
    }
    public function deleteReportUser($id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("DELETE FROM `reported-users` WHERE reported_id = :reported_id AND reporter_id = :reporter_id");
        $statement->bindValue(":reported_id", $id);
        $statement->bindValue(":reporter_id", $_SESSION['id']);
        $result = $statement->execute();
        return $result;
    }
    public function reportUser()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO `reported-users` (reported_id, reporter_id) VALUES (:reported_id, :reporter_id)");
        $statement->bindValue(":reported_id", $this->reportedId);
        $statement->bindValue(":reporter_id", $this->reporterId);
        $result = $statement->execute();
        return $result;
    }
    public static function getReportedUsers()
    {
        $conn = Db::getInstance();
        // $statement = $conn->prepare("SELECT * FROM `reported-users`");
        // statement with info about the reported user
        $statement = $conn->prepare("SELECT `reported-users`.id, `reported-users`.reported_id, `reported-users`.reporter_id, users.username, users.email, users.avatar_url, users.banned, users.id FROM `reported-users` INNER JOIN users ON `reported-users`.reported_id = users.id");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
