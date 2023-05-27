<?php
require_once("../bootstrap.php");
if (!empty($_POST)) {
    $like = new Like();
    $postid = $_POST['post_id'];
    if ($like->checkLike($postid)) {
        $like->deleteLike($postid);
        $status = 'Like';
    } else {
        $like->updateLikes($postid);
        $status = 'Unlike';
    }
    $likes = Like::getLikes($postid);
    $response = [
        'status' => $status,
        'likes' => $likes
    ];
    echo json_encode($response); //status teruggeven
}
