<?php
    include_once(__DIR__ . "/bootstrap.php");
    // get input from AJAX request
    $email = $_POST['email'];

    // check if username already exists in database
    $conn = Db::getInstance();
    $stmt = $conn->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
    $stmt->execute(array($email));
    $count = $stmt->fetchColumn();
    // return response as JSON
    if ($count > 0) {
    $response = array('available' => false);
    } else {
    $response = array('available' => true);
    }
    echo json_encode($response);
?>
