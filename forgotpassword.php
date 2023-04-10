<?php 
    require_once 'vendor/autoload.php';
    include_once(__DIR__ . "/bootstrap.php");

    $user = new User();
    $user->setEmail($_POST['email']);
    $user->sendResetEmail();




?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
                <h1>Send email</h1>
                <ul>
                    <li><input type="text" name="email" required></li>
                    <li><input type="submit" value="Send email"></li>
                </ul>
            </form>
</body>
</html>