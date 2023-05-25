<?php 

    require_once("../bootstrap.php"); //controleren

    if(!empty($_POST)){
        $f = new Prompt();
        $f->setPostId($_POST['post_id']);
        $f->setUserId($_SESSION['userid']);
        $f->setCredits($_SESSION['credits']);

        if(count(Prompt::checkBought($_POST['post_id'])) >=1 ){
            $message = "Already bought";
        } else {
            $prompt = Prompt::priceCheckSelector($_POST['post_id']);
            $price = Prompt::priceById($prompt['price_id']);
            //priceid linken aan price tabel

            if($price['price'] > $_SESSION['credits']){
                $message = "Not enough credits";
            } else {
                $f->buy();
                $credits = $_SESSION['credits'] - $price['price'];
                $f->updateCredits($credits);
                $message = "Bought";
            }            
        }
        //succes teruggeven
        $response = [
            'status' => 'success',
            'message' => $message
        ];

        echo json_encode($response); //status teruggeven

    };


?>