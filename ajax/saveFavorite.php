<?php
require_once("../bootstrap.php");
if (!empty($_POST)) {
    $f = new Prompt();
    $f->setPostId($_POST['post_id']);
    $f->setUserId($_SESSION['id']);
    if (count(Prompt::checkFavorite($_POST['post_id'])) >= 1) {
        $f->deleteFavorite($_POST['post_id']);
        $message = "Add to favorites";
    } else {
        $f->saveFavorite();
        $message = "Remove from favorites";
    }
    //succes teruggeven
    $response = [
        'status' => 'success',
        'message' => $message
    ];
    echo json_encode($response); //status teruggeven
};
