<?php

    class Prompt
    {
        private $title;
        private $priceid;
        private $description;
        private $photoUrl;
        private $prompt;
        private $promptInfo;
        private $categoryid;
        private $postId;
        private $userId;
        private $price;
        private $payoutid;
        private $verified;
      
        public static function getUnverifiedPrompts()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE verified = 0 AND active = 1 AND rejected = 0 AND deleted = 0 and reported = 0");
            $statement->execute();
            $prompt = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompt;
        }
        public static function getRejectedPrompts()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE rejected = 1 AND active = 1");
            $statement->execute();
            $prompt = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompt;
        }
        public static function getReportedPrompts(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT prompt_id, COUNT(*) AS count FROM `reported-prompts` GROUP BY prompt_id ORDER BY prompt_id");
            $statement->execute();
            $prompt = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompt;
        }
        public static function getPromptsByUser($id)
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE user_id = $id AND active = 1 AND deleted = 0 AND rejected = 0 AND verified = 1 and reported = 0 ORDER BY postdate DESC");
            $statement->execute();
            $prompt = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompt;
        }
        public static function getPromptUser($id)
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT id, username, avatar_url FROM users WHERE active=1 AND banned = 0 AND id = :id");
            $statement->bindValue(":id", $id);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        public function setTitle($title)
        {
            $this->title = $title;
            return $this;
        }
        public function setPriceId($priceid){
            $this->priceid = $priceid;
            return $this->priceid;
        }
        public function setDescription($description)
        {
            $this->description = $description;
            return $this;
        }
        public function setPhotoUrl($photoUrl)
        {
            $this->photoUrl = $photoUrl;
            return $this;
        }
        public function setPrompt($prompt)
        {
            $this->prompt = $prompt;
            return $this;
        }
        public function setPromptInfo($promptInfo)
        {
            $this->promptInfo = $promptInfo;
            return $this;
        }
        public function setUserId($userId)
        {
            $this->userId = $userId;
            return $this;
        }
        public function setPrice($price)
        {
            $this->price = $price;
            return $this;
        }
        public function setpayoutId($payoutid)
        {
            $this->payoutid = $payoutid;
            return $this;
        }
        public function save()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("INSERT INTO prompts (`cat_id`, `title`, `price_id`, `description`, `photo_url`, `prompt`, `prompt_info`, `user_id`, `verified`) VALUES (:cat, :title, :priceid, :description, :photoUrl, :prompt, :promptInfo, :userId, :verified)");
            $statement->bindValue(":title", $this->title);
            $statement->bindValue(":priceid", $this->priceid);
            $statement->bindValue(":description", $this->description);
            $statement->bindValue(":photoUrl", $this->photoUrl);
            $statement->bindValue(":prompt", $this->prompt);
            $statement->bindValue(":promptInfo", $this->promptInfo);
            $statement->bindValue(":userId", $this->userId);
            $statement->bindvalue(":cat", $this->categoryid);
            $statement->bindValue(":verified", $this->verified);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        public function getRandomStringRamdomInt($length = 16)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
        public static function getVerifiedPrompts($limit, $offset, $search_query)
        {
            $conn = Db::getInstance();
            $sql = "SELECT * FROM prompts WHERE verified = 1 AND active = 1 AND deleted = 0 AND rejected = 0 and reported = 0";
            if ($search_query != '') {
                $sql .= " AND title LIKE :search_query";
            }
            //add limit & offset
            $sql .= " ORDER BY postdate DESC LIMIT :limit OFFSET :offset";

            $statement = $conn->prepare($sql);
            if ($search_query != '') {
                $statement->bindValue(":search_query", "%".$search_query."%");
            }
            $statement->bindValue(":limit", intval($limit), PDO::PARAM_INT);
            $statement->bindValue(":offset", intval($offset), PDO::PARAM_INT);
            $statement->execute();
            $prompt = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompt;
        }
        public static function getVerifiedPromptsById($id, $limit, $offset, $search_query)
        {
            $conn = Db::getInstance();
            $sql = "SELECT * FROM prompts WHERE verified = 1 AND active = 1 AND deleted = 0 AND rejected = 0 and reported = 0 AND id = $id";
            if ($search_query != '') {
                $sql .= " AND title LIKE :search_query";
            }
            //add limit & offset
            $sql .= " ORDER BY postdate DESC LIMIT :limit OFFSET :offset";

            $statement = $conn->prepare($sql);
            if ($search_query != '') {
                $statement->bindValue(":search_query", "%".$search_query."%");
            }
            $statement->bindValue(":limit", intval($limit), PDO::PARAM_INT);
            $statement->bindValue(":offset", intval($offset), PDO::PARAM_INT);
            $statement->execute();
            $prompt = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompt;
        }
        public static function countAllVerifiedPrompts($search_query)
        {
            $conn = Db::getInstance();
            $sql = "SELECT * FROM prompts WHERE verified = 1 AND active = 1 AND deleted = 0 AND rejected = 0 and reported = 0";
            if ($search_query != '') {
                $sql .= " AND title LIKE :search_query";
            }

            $statement = $conn->prepare($sql);
            if ($search_query != '') {
                $statement->bindValue(":search_query", "%".$search_query."%");
            }
            $statement->execute();
            $prompt = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompt;
        }

        public static function getPromptCat($catid)
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM categories WHERE active=1 AND id = $catid");
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        public static function getPromptprice($priceid){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prices WHERE active=1 AND id = $priceid");
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        public static function getCategories()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM categories WHERE active=1");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        public static function getPrices()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prices WHERE active=1");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        public function setCategoryId($catid)
        {
            $this->categoryid = $catid;
            return $this;
        }

        public static function getPromptsByCategory($selectedCategory)
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE verified = 1 AND cat_id = $selectedCategory AND deleted = 0 AND active = 1 AND reported = 0 ORDER BY postdate DESC");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        /**
         * Get the value of postId
         */
        public function getPostId()
        {
            return $this->postId;
        }

        /**
         * Set the value of postId
         *
         * @return  self
         */
        public function setPostId($postId)
        {
            $this->postId = $postId;

            return $this;
        }


        /**
         * Get the value of userId
         */
        public function getUserId()
        {
            return $this->userId;
        }

        //save favorites to database

        public function saveFavorite()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("INSERT INTO favorites (userId, postId) VALUES (:userId, :postId)");

            $statement->bindValue(":userId", $this->userId);
            $statement->bindValue(":postId", $this->postId);
            $result = $statement->execute();

            return $result;
        }

        public static function getFavorites()
        {
            $conn = Db::getInstance();
            //get all promptDetails from table prompts, only the prompts the user has favourited in the table favourites
            $statement = $conn->prepare("SELECT * FROM prompts INNER JOIN favorites ON prompts.id = favorites.postId WHERE favorites.userId = :user_id ORDER BY favorites.id DESC");
            $statement->bindValue(":user_id", $_SESSION['id'], \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        }

        public static function checkFavorite($promptId)
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM favorites WHERE userId = :userId AND postId = :postId");
            $statement->bindValue(":userId", $_SESSION['id']);
            $statement->bindValue(":postId", $promptId);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        }
        public static function deleteFavorite($promptId)
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("DELETE FROM favorites WHERE userId = :userId AND postId = :postId");
            $statement->bindValue(":userId", $_SESSION['id']);
            $statement->bindValue(":postId", $promptId);
            $result = $statement->execute();
            
            return $result;
        }
        public static function checkReport($promptId){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM `reported-prompts` WHERE user_id = :userId AND prompt_id = :postId");
            $statement->bindValue(":userId", $_SESSION['id']);
            $statement->bindValue(":postId", $promptId);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        public static function deleteReport($promptId){
            $conn = Db::getInstance();
            $statement = $conn->prepare("DELETE FROM `reported-prompts` WHERE user_id = :userId AND prompt_id = :postId");
            $statement->bindValue(":userId", $_SESSION['userid']);
            $statement->bindValue(":postId", $promptId);
            $result = $statement->execute();
            return $result;
        }
        public function report(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("INSERT INTO `reported-prompts` (user_id, prompt_id) VALUES (:userId, :postId)");
            $statement->bindValue(":userId", $this->userId);
            $statement->bindValue(":postId", $this->postId);
            $result = $statement->execute();
            return $result;
        }
        public function buy(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("INSERT INTO `bought-prompts` (user_id, prompt_id) VALUES (:userId, :postId)");
            $statement->bindValue(":userId", $this->userId);
            $statement->bindValue(":postId", $this->postId);
            $result = $statement->execute();
            return $result;
        }
        public function payout(){
            $conn = Db::getInstance();
            $statement1 = $conn->prepare("UPDATE `bought-prompts` SET paidout = 1 WHERE user_id = :userId AND prompt_id = :postId");
            $statement2 = $conn->prepare("UPDATE `users` SET credits = credits + :price WHERE id = :payoutid");
            $statement2->bindValue(":price", $this->price);
            $statement2->bindValue(":payoutid", $this->payoutid);
            $statement1->bindValue(":userId", $this->userId);
            $statement1->bindValue(":postId", $this->postId);
            $result1 = $statement1->execute();
            $result2 = $statement2->execute();
            return $result1 && $result2;
        }
        public static function getPromptById($promptId){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE id = :postId and verified = 1 and active = 1 and deleted = 0 and reported = 0");
            $statement->bindValue(":postId", $promptId);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        public static function checkBought($promptid){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM `bought-prompts` WHERE user_id = :userId AND prompt_id = :postId");
            $statement->bindValue(":userId", $_SESSION['userid']);
            $statement->bindValue(":postId", $promptid);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        public static function priceCheckSelector($postId){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE id = :postid");
            $statement->bindValue(":postid", $postId);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        public static function priceById($priceid){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prices WHERE id = :priceid AND active = 1");
            $statement->bindValue(":priceid", $priceid);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        public function updateCredits($credits){
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET credits = :credits WHERE id = :userid");
            $statement->bindValue(":credits", $credits);
            $statement->bindValue(":userid", $_SESSION['userid']);
            $_SESSION['credits'] = $credits;
            $result = $statement->execute();
            return $result;
        }
        public static function getBoughtPromptIds(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM `bought-prompts` WHERE user_id = :userId");
            $statement->bindValue(":userId", $_SESSION['userid']);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        public static function getBoughtPrompts($promptid){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE id = :postid");
            $statement->bindValue(":postid", $promptid);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }

        /**
         * Get the value of verified
         */ 
        public function getVerified()
        {
            return $this->verified;
        }

        /**
         * Set the value of verified
         *
         * @return  self
         */ 
        public function setVerified($verified)
        {
            $this->verified = $verified;

            return $this;
        }
        public static function getMinPrice(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT MIN(price) FROM prices WHERE active=1;");
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        public static function getMaxPrice(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT MAX(price) FROM prices WHERE active=1;");
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        public static function searchByPriceRange($minPrice, $maxPrice){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT id FROM prompts WHERE price_id >= :minPrice AND price_id <= :maxPrice AND verified = 1 AND active = 1 AND deleted = 0 AND rejected = 0 AND reported = 0");
            $statement->bindValue(":minPrice", $minPrice);
            $statement->bindValue(":maxPrice", $maxPrice);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }
        public static function getPriceIdByPrice($price){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT id FROM prices WHERE price = :price");
            $statement->bindValue(":price", $price);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
    }