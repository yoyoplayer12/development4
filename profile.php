<?php
        include_once(__DIR__ . "/bootstrap.php");
        //logindetection
        if(isset($_SESSION["loggedin"])) {
            $getUser = User::getSessionUser();
        }
        else {
            header("Location: login.php");
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Eve - <?php echo $_SESSION['username']?>'s profile</title>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>

    <?php if(isset($_SESSION['loggedin'])):?>
        <h1><?php echo $_SESSION['username']?>'s profile</h1>
        <p class="profile-bio">Bio: <?php echo $getUser["bio"];?></p>
        <img src="<?php echo $getUser["avatar_url"] ?>" alt="Avatar" class="rounded-full w-40 h-40 object-cover">

        <p>my favorite prompts:</p>
        

        <a href="editprofile.php">Edit profile</a>
        <a href="changePassword.php">Change password</a>
        <a href="logout.php">Log out</a>
        <a href="deleteprofile.action.php">Delete profile</a>

    <?php else:?>
        <h1>Log in to see your profile</h1>
        <a href="logout.php">Log in</a>

    <?php endif;?>
</body>
</html>
