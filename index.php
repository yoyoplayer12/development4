<?php
    include_once(__DIR__ . "/bootstrap.php");
    //logindetection
    $prompts = [];
    $prompts = Prompt::getVerifiedPrompt();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Eve - Home</title>
</head>
<body>
    <?php include_once(__DIR__ . "/nav.php"); ?>
    <h1>Prompt marketplace</h1>
    <?php 
        if (empty($prompts)) {
            echo "<h1 class='noposts'>There are no prompts rightnow, try again later!</h1>";
        }
        else{
            foreach($prompts as $prompt): ?>
                <div class="prompt">
                    <ul>
                        <li><p><b>Title: </b><?php echo $prompt["title"] ?></p></li>
                        <li><p><b>Description: </b><?php echo $prompt["description"] ?></p></li>
                        <li><img src="<?php echo $prompt["photo-url"]?>" alt="Prompt photo"></li>
                        <li><p><b>Postdate: </b><?php echo $prompt["postdate"] ?></p></li>
                        <li><p><b>Prompt: </b><?php echo $prompt["prompt"] ?></p></li>
                        <li><p><b>Prompt description: </b><?php echo $prompt["prompt-description"] ?></p></li>
                        <!-- Hier komt de buy button ==> zorgen dat je alleen kan kopen when loggedin-->
                        <button>Buy</button>
                    </ul>
                    

                </div>
               
    <?php endforeach;} ?>
</body>
</html>