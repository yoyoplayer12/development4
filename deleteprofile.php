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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVE - delete account</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<form method="post" action="deleteprofile.php">
  <label for="password">Password:</label>
  <input type="password" name="password" required>
  <br>
  <input type="submit" value="Delete Account">
</form>
</body>
</html>
