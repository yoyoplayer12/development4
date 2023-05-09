<?php 

    require_once("../bootstrap.php"); //controleren

    if(!empty($_POST)){
        $f = new Prompt();
        $f->setPostId($_POST['post_id']);
        $f->setUserId($_POST['user_id']);


        $f->saveFavorite();

        //succes teruggeven
        $response = [
            'status' => 'success',
            'message' => 'Favorite saved'
        ];

        echo json_encode($response); //status teruggeven

    };


?>