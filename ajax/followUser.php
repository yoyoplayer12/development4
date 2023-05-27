<?php
require_once("../bootstrap.php"); //controleren
if (!empty($_POST)) {
    $f = new User();
    $f->setFollowerId($_SESSION['id']);
    $f->setFollowedId($_POST['followed_id']);
    if (count(User::checkFollowUser($_POST['followed_id'])) >= 1) {
        $f->deleteFollowUser($_POST['followed_id']);
        $message = "Follow";
    } else {
        $f->followUser();
        $message = "Unfollow";
    }
    $response = [
        'status' => 'success',
        'message' => $message
    ];
    header('Content-Type: application/json');
    echo json_encode($response); //status teruggeven
};
