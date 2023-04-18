<?php
    class Prompt{
        public static function getPrompt(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE verified = 0 AND active = 1");
            $statement->execute();
            $prompt = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompt;
        }
    }