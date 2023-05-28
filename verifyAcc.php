<?php

include_once(__DIR__ . "/bootstrap.php");
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $conn = Db::getInstance();
    $user = new User();
    $result = $user->checkToken($token);
    if ($result) {
        $user->activateUser($token);
    } else {
        echo "invalid token";
    }
} else {
    echo "no token given";
}
