<?php 

    require_once("../bootstrap.php"); //controleren

    if(!empty($_POST)){
        $f = new Prompt();
        $f->setPostId($_POST['post_id']);
        $f->setUserId($_SESSION['id']);

        if(count(Prompt::checkReport($_POST['post_id'])) >=1 ){
            $f->deleteReport($_POST['post_id']);
            $message = "Report";
            
        } else {
            $f->report();
            $message = "Reported";
            
        }
        //succes teruggeven
        $response = [
            'status' => 'success',
            'message' => $message
        ];

        echo json_encode($response); //status teruggeven

    };


?>