<?php
    class Prompt{
        public static function getUnverifiedPrompt(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE verified = 0 AND active = 1");
            $statement->execute();
            $prompt = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompt;
        }
        public static function getVerifiedPrompt(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE verified = 1 AND active = 1");
            $statement->execute();
            $prompt = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompt;
        }
    }