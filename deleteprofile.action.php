<?php
include_once("bootstrap.php");
$user = new User();
$id = $_GET['uid'];
// Handle form submission
if ($id == $_SESSION['userid']) {
  $password = $_POST['password'];
  if ($user->deleteProfile($id)) {
    // Logout the user and redirect to the register page
    session_destroy();
    header('Location: register.php');
  } else {
    echo "error";
  }
}
else {
  header('Location: profile.php');
}