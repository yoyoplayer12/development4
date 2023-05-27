<?php
    class User{

        private string $email;
        private string $password;
        private string $username;
        private string $bio;
        private string $avatar;
        private string $token;
        private string $psswdtoken;
        private string $reportedId;
        private string $reporterId;
        
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

                    if($user["active"] == 1){
                        //getting basic user info
                        $_SESSION['username'] = $user["username"];
                        $_SESSION["userid"] = $user["id"];
                        $_SESSION["confirmed_email"] = $user["confirmed_email"];
                        $_SESSION["email"] = $user["email"];
                        $_SESSION["credits"] = $user["credits"];
                        return true;
                    } else {
                        return false;
                    }
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
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM users WHERE username = :username AND banned = 0 AND admin = 1");
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
        public static function getSessionUser(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM users WHERE username = :username AND banned = 0");
            $statement->bindValue(":username", $_SESSION['username']);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            return $user;
        }
        public static function getUser($userid){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM users WHERE id = :id AND banned = 0");
            $statement->bindValue(":id", $userid);
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
        public function userExcistanceCheck($email){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM users WHERE email = :email AND banned = 0");
            $statement->bindValue(":email", $email);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);
            if($user){
                $_SESSION['id'] = $user["id"];
                return true;
            }
            else{
                return false;
            }
        }

        public function setEmail($email){
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                return false;
            }
            else {
                $this->email = $email;
                if($this->userExcistanceCheck($email) == true){
                    return true;
                }
                else{
                    return false;
                }
                
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
            $statement = $conn->prepare("INSERT INTO users (email, username, password, activation_code) VALUES (:email, :username, :password, :activation_code)");
            $statement->bindValue(":email", $this->email); //security sql injection
            $statement->bindValue(":username", $this->username);
            $statement->bindValue(":password", $this->password);
            $statement->bindValue(":activation_code", $this->token);
            $statement->execute();
        }

        public function setPsswdToken($token){
            $this->psswdtoken = $token;
            return $this;
        }
        public function getPsswdToken(){
                return $this->psswdtoken;
        }
        public function setRandomString($n){
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            for ($i = 0; $i < $n; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }
            return $randomString;
        }
        public function updatePassword(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET `password` = :password WHERE email = :email AND banned = 0");
            $statement->bindValue(":password", $this->password);
            $statement->bindValue(":email", $_SESSION['email']);
            $statement->execute();
            return $statement;
        }
        public function sendResetEmail(){
            $config = self::getConfig();
            $sender = $config['SENDER_EMAIL'];
            
            //using sengrid api to send email
            $email = new \SendGrid\Mail\Mail(); 
            $email->setFrom($sender, "Prompt website");
            $email->setSubject("Password reset");
            $email->addTo($this->email);
            $email->addContent("text/plain", "Here's your password reset code: <br> <h1>$this->psswdtoken</h1>");
            $email->addContent("text/html", "Here's your password reset code: <br> <h1>$this->psswdtoken</h1>");
            
            $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
            try {
                $response = $sendgrid->send($email);
                //print $response->statusCode() . "\n";
                //print_r($response->headers());
                //print $response->body() . "\n";
                
            } catch (Exception $e) {
                echo 'Caught exception: '. $e->getMessage() ."\n";
            }
        }

        public function sendConfirmEmail(){
            $config = self::getConfig();
            $sender = $config['SENDER_EMAIL'];
            $email = new \SendGrid\Mail\Mail(); 
            $email->setFrom($sender, "Prompt website");
            $email->setSubject("Confirm your email");
            $email->addTo($this->email);
            $email->addContent("text/plain", "Hey, Click the link below to confirm your account: localhost/php/promptdev/development4/verifyAcc.php?token=$this->token");
            $email->addContent("text/html", "Hey, Click the link below to confirm your account: localhost/php/promptdev/development4/verifyAcc.php?token=$this->token");
            
            $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
            try {
                $response = $sendgrid->send($email);
                //print $response->statusCode() . "\n";
                //print_r($response->headers());
                //print $response->body() . "\n";
                
            } catch (Exception $e) {
                echo 'Caught exception: '. $e->getMessage() ."\n";
            }
        }



        /**
         * Get the value of token
         */ 
        public function getToken()
        {
                return $this->token;
        }

        /**
         * Set the value of token
         *
         * @return  self
         */ 
        public function setToken($token)
        {
                $this->token = $token;

                return $this;
        }

        public function checkToken($token){
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM users WHERE activation_code = :token");
            $statement->bindValue(":token", $token);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            return $result;
        } 

        public function activateUser($token){
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET active = 1, activation_code = NULL WHERE activation_code = :token");
            $statement->bindValue(":token", $token);
            $statement->execute();
            header("Location: index.php");
        }

  public function deleteProfile($username, $password) {
    $conn = Db::getInstance();
    $statement = $conn->prepare('SELECT id, password FROM users WHERE username = :username');
    $statement->bindValue(':username', $username);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        return false;
      }
    
      // Verify the password
      if (!password_verify($password, $result['password'])) {
        return false;
      }
    
      // Delete the user account
      $statement = $conn->prepare('DELETE FROM users WHERE id = :id');
      $statement->bindValue(':id', $result['id']);
      $statement->execute();
    
      return true;
  }

    public function comparePassword(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT password FROM users WHERE email = :email AND banned = 0");
        $statement->bindValue(":email", $_SESSION['email']);
        $statement->execute();
        $result = $statement->fetch();



        $password = $result['password'];
        
        if(password_verify($_POST['currentPassword'], $password )){
            return true;
        } else {
            return false;
        }
    }
    public function getId($username)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $statement->bindValue(":username", $username);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result["id"];
    }
    public static function getBalance(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT credits FROM users WHERE id = :id");
        $statement->bindValue(":id", $_SESSION['userid']);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result["credits"];
    }
    public function ban($id){
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET banned = 1 WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }
    public function bancheck($id){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT banned FROM users WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result["banned"];
    }

    public static function checkReportUser($id){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM `reported-users` WHERE reported_id = :id AND reporter_id = :reporter_id");
        $statement->bindValue(":id", $id);
        $statement->bindValue(":reporter_id", $_SESSION['id']);
        
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($result);
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

        public function deleteReportUser($id){
            $conn = Db::getInstance();
            $statement = $conn->prepare("DELETE FROM `reported-users` WHERE reported_id = :reported_id AND reporter_id = :reporter_id");
            $statement->bindValue(":reported_id", $id);
            $statement->bindValue(":reporter_id", $_SESSION['id']);
            $result = $statement->execute();
            return $result;
            var_dump($result);
        }

        public function reportUser(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("INSERT INTO `reported-users` (reported_id, reporter_id) VALUES (:reported_id, :reporter_id)");
            $statement->bindValue(":reported_id", $this->reportedId);
            $statement->bindValue(":reporter_id", $this->reporterId);
            $result = $statement->execute();
            return $result;
            var_dump($result);
        }

        public static function getReportedUsers(){
            $conn = Db::getInstance();
            // $statement = $conn->prepare("SELECT * FROM `reported-users`");
            // statement with info about the reported user
            $statement = $conn->prepare("SELECT `reported-users`.id, `reported-users`.reported_id, `reported-users`.reporter_id, users.username, users.email, users.avatar_url, users.banned, users.id FROM `reported-users` INNER JOIN users ON `reported-users`.reported_id = users.id");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
}