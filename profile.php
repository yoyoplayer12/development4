<?php
    include_once(__DIR__ . "/bootstrap.php"); 
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <title><?php echo $_SESSION['username']?> - Profile</title>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>

    <?php if(isset($_SESSION['loggedin'])):?>
        <h1><?php echo $_SESSION['username']?>'s profile</h1>
        <p>Here you can see your profile</p>
        <a href="logout.php">Log out</a>

    <?php else:?>
        <h1>Log in to see your profile</h1>
        <a href="logout.php">Log in</a>

    <?php endif;?>
</body>
</html>
