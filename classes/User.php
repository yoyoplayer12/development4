<?php
    class User{

        private string $email;
        private string $password;
        private string $username;
        private string $bio;
        private string $avatar;
        
        private static function getConfig(){
            // get the config file
            return parse_ini_file("config/config.ini");
        }
        public function canLogin($p_username, $p_password){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM users WHERE username = :username AND banned = 0");
            $statement->bindValue(":username", $p_username);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            if($user){
                $hash = $user["password"];
                if(password_verify($p_password, $hash)){

                    //getting basic user info
                    $_SESSION['username'] = $user["username"];
                    $_SESSION['loggedin'] = true;
                    $_SESSION["confirmed_email"] = $user["confirmed_email"];
                    return true;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
        // public function canLoginAdmin($p_username, $p_password){
            //eventuele code to add admin support

        //     $conn = Db::getInstance();
        //     $statement = $conn->prepare("SELECT * FROM users WHERE username = :username AND banned = 0 AND admin = 1");
        //     $statement->bindValue(":username", $p_username);
        //     $statement->execute();
        //     $user = $statement->fetch(PDO::FETCH_ASSOC);
        //     if($user){
        //         $hash = $user["password"];
        //         if(password_verify($p_password, $hash)){
        //             return true;
        //         }
        //         else{
        //             return false;
        //         }
        //     }
        //     else{
        //         return false;
        //     }
        // }
        public static function getUser(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM users WHERE username = :username AND banned = 0");
            $statement->bindValue(":username", $_SESSION['username']);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            return $user;
        }
        public function resetBio($p_bio){
           $this->bio = $p_bio;
        }
        public function resetAvatar($p_avatar){
             $this->avatar = $p_avatar;
        }
        public function updateUser(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET bio = :bio, avatar_url = :avatar_url WHERE username = :username AND banned = 0");
            $statement->bindValue(":bio", $this->bio);
            $statement->bindValue(":avatar_url", $this->avatar);
            $statement->bindValue(":username", $_SESSION['username']);
            $statement->execute();
            return $statement;

        }
        public function updateBio(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET bio = :bio WHERE username = :username AND banned = 0");
            $statement->bindValue(":bio", $this->bio);
            $statement->bindValue(":username", $_SESSION['username']);
            $statement->execute();
            return $statement;
        }
        public function updateAvatar(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET avatar_url = :avatar_url WHERE username = :username AND banned = 0");
            $statement->bindValue(":avatar_url", $this->avatar);
            $statement->bindValue(":username", $_SESSION['username']);
            $statement->execute();
            return $statement;
        }

        public function getEmail()
        {
                return $this->email;
        }
        public function getUsername()
        {
                return $this->username;
        }
        public function getPassword()
        {
                return $this->password;
        }


        public function setEmail($email){
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                throw new Exception("Invalid email format");
            }
            else {
                $this->email = $email;
            }
        }
        
        public function setUsername($username){
            if(empty($username)){
                throw new Exception("Username is required");
            }
            else {
                $this->username = $username;
            }
            
        }
        
        public function setPassword($password){
            if(empty($password)){
                throw new Exception("Password is required");
                
            }
            else {
                 //hash password with bcrypt and cost 12
            $options = [
                'cost'=>12,
            ];
            $password = password_hash($password, PASSWORD_DEFAULT, $options);
            $this->password = $password;
            }
        }
        

        public function save(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("INSERT INTO users (email, username, password) VALUES (:email, :username, :password)");
            $statement->bindValue(":email", $this->email); //security sql injection
            $statement->bindValue(":username", $this->username);
            $statement->bindValue(":password", $this->password);
            $statement->execute();
        }

        public function sendResetEmail(){
            $config = self::getConfig();
            $sender = $config['SENDER_EMAIL'];
            $email = new \SendGrid\Mail\Mail(); 
            $email->setFrom($sender, "Prompt website");
            $email->setSubject("Wachtwoord resetten");
            $email->addTo($this->email);
            $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
            $email->addContent(
                "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
            );
            
            $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
            try {
                $response = $sendgrid->send($email);
                print $response->statusCode() . "\n";
                print_r($response->headers());
                print $response->body() . "\n";
            } catch (Exception $e) {
                echo 'Caught exception: '. $e->getMessage() ."\n";
            }
        }
    }