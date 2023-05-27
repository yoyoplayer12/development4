<?php

    require_once("../bootstrap.php");

    if(!empty($_POST)){
        $comment = new Comment();
        $comment->setText($_POST['text']);
        $comment->setPromptid($_POST['promptId']);
        $comment->setUserid($_SESSION['userid']);
        $comment->saveComment();

        $response = [
            'status' => 'success',
            'body' => htmlspecialchars($comment->getText()),
            'message' => 'Comment added'
        ];

        header('Content-Type: application/json');
        echo json_encode($response);


    }

?>