<?php

    include_once(__DIR__ . "/bootstrap.php");

    if(isset($_GET['token'])){
        $token = $_GET['token'];
        $conn = Db::getInstance();
        $user = new User();
        
        $result = $user->checkToken($token);

        if($result){
            $user->activateUser($token);

        } 
        else{
            echo "invalid token";
        }
    }
    else{
        echo "no token given";
    }


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>