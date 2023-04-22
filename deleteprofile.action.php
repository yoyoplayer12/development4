<?php
include_once("bootstrap.php");
include_once(__DIR__ . "/nav.php");
$user = new User();

// Handle form submission
if (!empty($_POST)) {
  $username = $_SESSION['username'];
  $password = $_POST['password'];
  if ($user->deleteProfile($username, $password)) {
    // Logout the user and redirect to the register page
    session_destroy();
    header('Location: register.php');
    exit();
  } else {
    echo "Invalid password.";
  }
}