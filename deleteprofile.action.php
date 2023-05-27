<?php
include_once("bootstrap.php");
$user = new User();
// Handle form submission
if (!empty($_POST)) {
  $id = $_SESSION['userid'];
  $password = $_POST['password'];
  if ($user->deleteProfile($username, $id)) {
    // Logout the user and redirect to the register page
    session_destroy();
    header('Location: register.php');
    exit();
  } else {
    echo "Invalid password.";
  }
}
else {
  echo "Please fill in your password.";
}