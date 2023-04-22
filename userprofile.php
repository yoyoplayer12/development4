<?php
        include_once(__DIR__ . "/bootstrap.php");
        //logindetection
        if(isset($_SESSION["loggedin"])) {
            
        }
        else {
            header("Location: login.php");
        }
        $User = User::getUser($_GET['user']);
        $prompts = [];
        $prompts = Prompt::getPromptsByUser($User["id"]);
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
    <title>Eve - <?php echo $User["username"]?>'s profile</title>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>

    <?php if(isset($_SESSION['loggedin'])):?>
        <h1><?php echo $User["username"]?>'s profile</h1>
        <p class="profile-bio">Bio: <?php echo $User["bio"];?></p>
        <img src="<?php echo $User["avatar_url"] ?>" alt="Avatar" class="rounded-full w-40 h-40 object-cover">
        <h2>Prompts:</h2>

        <?php foreach($prompts as $prompt): ?>
            <div class="prompt">
                <h3>Title: <?php echo $prompt["title"]; ?></h3>
                <p>Description: <?php echo $prompt["description"]; ?></p>
            </div>
        <?php endforeach; ?>
    <?php else:?>
        <h1>Log in to see your profile</h1>
        <a href="logout.php">Log in</a>

    <?php endif;?>
</body>
</html>
