<?php
    class Comment {
        private $text;
        private $promptid;
        private $userid;
        

        /**
         * Get the value of text
         */ 
        public function getText()
        {
                return $this->text;
        }

        /**
         * Set the value of text
         *
         * @return  self
         */ 
        public function setText($text)
        {
                $this->text = $text;

                return $this;
        }

        /**
         * Get the value of promptid
         */ 
        public function getPromptid()
        {
                return $this->promptid;
        }

        /**
         * Set the value of promptid
         *
         * @return  self
         */ 
        public function setPromptid($promptid)
        {
                $this->promptid = $promptid;

                return $this;
        }

        /**
         * Get the value of userid
         */ 
        public function getUserid()
        {
                return $this->userid;
        }

        /**
         * Set the value of userid
         *
         * @return  self
         */ 
        public function setUserid($userid)
        {
                $this->userid = $userid;

                return $this;
        }

        public function saveComment(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("INSERT INTO comments (text, prompt_id, user_id) VALUES (:text, :promptid, :userid)");

            $text = $this->getText();
            $promptid = $this->getPromptid();
            $userid = $this->getUserid();


            $statement->bindValue(":text", $text);
            $statement->bindValue(":promptid", $promptid);
            $statement->bindValue(":userid", $userid);
            $result = $statement->execute();
            return $result;
        }

        //make a function to get all comments from a prompt
        public static function getComments($promptid){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM comments WHERE prompt_id = :promptid");
            $statement->bindValue(":promptid", $promptid);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

    }