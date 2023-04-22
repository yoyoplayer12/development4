<?php
    include_once(__DIR__ . "/bootstrap.php");
    //logindetection
    $prompts = [];
    $prompts = Prompt::getVerifiedPrompts();
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
    <title>Eve - Home</title>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <h1>Prompt marketplace</h1>
    <?php 
        if (empty($prompts)) {
            echo "<h1 class='noposts'>There are no prompts right now, try again later!</h1>";
        }
        else{
            foreach($prompts as $prompt): ?>
                <?php $promptUser = Prompt::getPromptUser($prompt['user_id']); ?>
                <div class="prompt">
                    <ul>
                        <li><p><b>Title: </b><?php echo $prompt["title"] ?></p></li>
                        <li><p><b>User: </b><?php echo $promptUser['username'] ?></p></li>
                        <li><img src="<?php echo $prompt["photo-url"]?>" alt="Prompt photo"></li>
                        <li><p><b>Description: </b><?php echo $prompt["description"] ?></p></li>
                        <li><p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p></li>
                        <!-- shouldnt be visible before buying -->
                        <li><p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p></li>
                        <li><p><b>Prompt description: </b><?php echo $prompt["prompt-info"] ?></p></li>
                        <!-- Hier komt de buy button ==> zorgen dat je alleen kan kopen when loggedin-->
                        <li><button>Buy</button></li>
                        <?php if(isset($_SESSION["admin"])):?>
                            <?php if($_SESSION["admin"] == true):?>
                                <li><a href="reject.action.php?id=<?php echo $prompt["id"] ?>">Reject</a></li>
                            <?php endif ?>
                        <?php endif ?>
                        <?php if(isset($_SESSION["userid"])):?>
                            <?php if($prompt["user_id"] == $_SESSION["userid"]):?>
                                <li><a href="deletepost.action.php?pid=<?php echo $prompt["id"] ?>&uid=<?php echo $prompt["user_id"] ?>">Delete</a></li>
                            <?php endif ?>
                        <?php endif ?>
                    </ul>
                    

                </div>
               
    <?php endforeach;} ?>
</body>
</html>