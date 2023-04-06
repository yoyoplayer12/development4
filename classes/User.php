<?php
    class User{
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
    }