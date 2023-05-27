<?php 
    require_once("../bootstrap.php");
    if(!empty($_POST)){
        $f = new User();
        $f->setReporterId($_SESSION['id']);
        $f->setReportedId($_POST['reported_id']);
        if(count(User::checkReportUser($_POST['reported_id'])) >=1 ){
            $f->deleteReportUser($_POST['reported_id']);
            $message = "Report";
        } else {
            $f->reportUser();
            $message = "Reported";
        }
        //succes teruggeven
        $response = [
            'status' => 'success',
            'message' => $message
        ];
        header('Content-Type: application/json');
        echo json_encode($response); //status teruggeven
    };