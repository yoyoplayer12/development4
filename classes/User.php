<?php
    class User{

        private string $email;
        private string $password;
        private string $username;
        

        public function canLogin($p_username, $p_password){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM users WHERE username = :username AND active = 1");
            $statement->bindValue(":username", $p_username);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            if($user){
                $hash = $user["password"];
                if(password_verify($p_password, $hash)){
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
        public function canLoginAdmin($p_username, $p_password){
            //eventuele code to add admin support

        //     $conn = Db::getInstance();
        //     $statement = $conn->prepare("SELECT * FROM users WHERE username = :username AND active = 1 AND admin = 1");
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
        }

        /**
         * Get the value of email
         */ 
        public function getEmail()
        {
                return $this->email;
        }


        
                /**
         * Get the value of username
         */ 
        public function getUsername()
        {
                return $this->username;
        }

        /**
         * Get the value of password
         */ 
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
            $email = new \SendGrid\Mail\Mail(); 
            $email->setFrom("test@example.com", "Example User");
            $email->setSubject("Sending with SendGrid is Fun");
            $email->addTo("test@example.com", "Example User");
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