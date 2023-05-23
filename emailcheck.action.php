<?php
    include_once(__DIR__ . "/bootstrap.php");
    // get input from AJAX request
    $email = $_POST['email'];
    $emailcheck = new Action();
    $emailcheck->emailcheck($email);
?>
